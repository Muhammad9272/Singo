<?php

namespace App\Services\Publishers\Fuga;

use App\Models\Setting;
use App\Services\Publishers\FugaPublisher;
use GuzzleHttp\Client;
use GuzzleHttp\Handler\CurlHandler;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Psr7\Utils;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use CURLFile;

trait TrackPublisher
{
    public function generateIsrcCode()
    {
        // Format - DE1CV2100001
        $response = $this->client->get('assets?page=0&page_size=200');

        if ($response->getStatusCode() !== 200) {
            return 'DE1CV' . date('ydw');
        }

        $asset = collect(json_decode($response->getBody()->getContents())->asset)
            ->filter(function ($item) {
                return $item->isrc && str_contains($item->isrc, 'DE1CV');
            })
            ->sortByDesc('isrc')
            ->values()
            ->first();


        if (!$asset) {
            return 'DE1CV' . date('y') . '00001';
        }

        if (Str::contains($asset->isrc, 'DE1CV')) {
            if (substr($asset->isrc, 5, 2) != date('y')) {
                return 'DE1CV' . date('y') . '00001';
            }

            $last_isrc = $asset->isrc;
        } else {
            $last_isrc = Setting::where('name', 'last_used_isrc')->first()->value;
        }

        return 'DE1CV' . str_pad(explode('DE1CV', $last_isrc)[1] + 1, 5, 0, STR_PAD_LEFT);
    }

    public function saveLastIsrc($isrc)
    {
        Setting::where('name', 'last_used_isrc')->update(['value' => $isrc]);
    }

    public function linkTrackToProduct($track_id, $product_id)
    {
        Log::info("Linking track #{$track_id} to product #{$product_id}");

        $request = $this->client->post("products/" . $product_id . "/assets", [
            "json" => [
                'id' => $track_id
            ]
        ]);

        $res = json_decode($request->getBody()->getContents(), JSON_OBJECT_AS_ARRAY);

        if (is_array($res)) {
            if (array_key_exists("id", $res)) {
                return $res['id'];
            }
        }

        return false;
    }

    public function uploadAudioChunks($uuid, $filename)
    {
        Log::info("Uploading file to UUID {$uuid}");

        $splitSizeInMegaBytes = 4;
        $splitSizeInBytes = $splitSizeInMegaBytes * 1024 * 1024;

        $totalfilesize = Storage::size($filename);
        $fileBaseName = basename($filename);

        $splitDestPath = str_replace($fileBaseName, '', $filename) . 'temp_uploads/';
        $fileAbsPath = Storage::path($filename);
        $splitDestAbsPath = Storage::path($splitDestPath);

        Storage::delete($splitDestPath);
        Storage::makeDirectory($splitDestPath);

        $splitCommand = 'split -b '. $splitSizeInMegaBytes .'m "' . $fileAbsPath . '" "'. $splitDestAbsPath . $fileBaseName .'."';

        Log::critical('TrackPublisher: ' . $splitCommand);

        exec($splitCommand, $commandOut, $commandReturn);

        if ($commandReturn) {
            throw new \Exception("Failed to chunk files. Command: {$splitCommand} | Returned {$commandReturn} | Output " . json_encode($commandOut));
        }

        $splitFiles = Storage::files($splitDestPath);
        $totalparts = count($splitFiles);

        foreach ($splitFiles as $i => $splitFile) {
            Log::info("Uploading chunk #{$i}");

            $partbyteoffset = $splitSizeInBytes * $i;
            $chunkSize = Storage::size($splitFile);

            $request = $this->client->post('upload', [
                    'multipart' => [
                        [
                            'name' => 'uuid',
                            'contents' => $uuid,
                        ],
                        [
                            'name' => 'partbyteoffset',
                            'contents' => $partbyteoffset,
                        ],
                        [
                            'name' => 'filename',
                            'contents' => $fileBaseName,
                        ],
                        [
                            'name' => 'totalfilesize',
                            'contents' => $totalfilesize,
                        ],
                        [
                            'name' => 'partindex',
                            'contents' => $i,
                        ],
                        [
                            'name' => 'totalparts',
                            'contents' => $totalparts,
                        ],
                        [
                            'name' => 'chunksize',
                            'contents' => $chunkSize,
                        ],
                        [
                            'name' => 'file',
                            'contents' => Storage::get($splitFile),
                            'filename' => $fileBaseName,
                        ]
                    ],
                ]
            );

            Storage::delete($splitFile);
            Log::info($request->getBody()->getContents());
        }

        return true;
    }

    public function createUpload($id, $type)
    {
        Log::info('Initiate Chunk Audio Upload');

        $request = $this->client->post('upload/start', [
            'json' => [
                'id' => $id,
                'type' => $type
            ],
        ]);

        $response = json_decode($request->getBody()->getContents());

        return $response->id;
    }

    public function finishUpload($uuid, $filename)
    {
        Log::info("Mark upload as done: {$uuid}");

        $this->client->post('upload/finish', [
            'json' => [
                'uuid' => $uuid,
                'filename' => $filename
            ],
        ]);
    }

    public function initiateChunkUpload($fuga_track_id)
    {
        Log::info('Initiate Chunk Audio Upload');

        $request = $this->client->post('upload/start', [
            'json' => [
                'id' => $fuga_track_id,
                'type' => 'audio'
            ],
        ]);

        $res = json_decode($request->getBody()->getContents(), true);

        if (is_array($res)) {
            if (array_key_exists("id", $res)) {
                $id = $res['id'];
                return $id;
            }
        }

        return false;
    }

    public function createTrack($title, $genre, $song, $fuga_people_id, $publisherId)
    {
        Log::info('createTrack');

        $artists = [
            [
                "id" => $this->album->user->fuga_artist_id,
                "primary" => true
            ]
        ];

        foreach ($song->fartist as $fartist) {
            $feature_artist_id = $this->ensureArtist($this->album->user->id, $fartist->artist_name);

            if ($feature_artist_id != $this->album->user->fuga_artist_id) {
                $artists[] = [
                    "id" => $feature_artist_id,
                    "primary" => false
                ];
            }
        }

        $isrc = $song->isrc;

        if (empty($song->isrc)) {
            $isrc = $this->generateIsrcCode();
        }

        $data = array(
            'name' => $title,
            'genre' => strtoupper($this->album->genre->slug),
            'isrc' => $isrc,
            'audio_locale' => $song->isInstrumental ? 'ZXX' : ($song->audioLocale ? $song->audioLocale->slug : 'EN'),
            'parental_advisory_next' => $song->isExplicit ? 'YES' : 'NO',
            'artists' => $artists,
            'display_artist' => $this->album->user->artistName
        );

        $data['language'] = $song->audioLocale ? $song->audioLocale->slug : 'EN';

        $response = $this->client->post('assets', [
            'json' => $data
        ]);

        if ($response->getStatusCode() === 200) {
            if (empty($song->isrc)) {
                $this->saveLastIsrc($isrc);

                $song->update([
                    'isrc' => $isrc,
                ]);
            }

            $res = json_decode($response->getBody()->getContents(), true);
            $this->attachContributorToTrack($res['id'], $fuga_people_id, 'COMPOSER');

            if (!$song['isInstrumental']) {
                $this->attachContributorToTrack($res['id'], $fuga_people_id, 'LYRICIST');
            }

            $this->attachPublishersToTrack($res['id'], $fuga_people_id, 4843303395);
        }

        if (is_array($res)) {
            if (array_key_exists("id", $res)) {
                return $res['id'];
            }
        }

        return false;
    }

    public function attachContributorToTrack($trackId, $personId, $role)
    {
        $data = [
            'person' => $personId,
            'role' => $role,
        ];
        $data['role'] = $role;

        $response = $this->client->post('assets/' . $trackId . '/contributors', [
            'json' => $data
        ]);

        if ($response->getStatusCode() === 200) {
            return json_decode($response->getBody()->getContents(), JSON_OBJECT_AS_ARRAY);
        }

        return false;
    }

    public function attachPublishersToTrack($trackId, $personId, $publisherId)
    {
        $data = [
            'person' => $personId,
            'publishing_house' => $publisherId,
        ];

        $response = $this->client->post('assets/' . $trackId . '/publishers', [
            'json' => $data
        ]);

        if ($response->getStatusCode() === 200) {
            return json_decode($response->getBody()->getContents(), JSON_OBJECT_AS_ARRAY);
        }

        return false;
    }
}
