<?php

namespace App\Services\Publishers;

use App\Models\Album;
use App\Models\AlbumSubmission;
use App\Models\User;
use App\Services\Publishers\Fuga\ArtistOperations;
use App\Services\Publishers\Fuga\DeliveryInstruction;
use App\Services\Publishers\Fuga\ProductPublisher;
use App\Services\Publishers\Fuga\TrackPublisher;
use Illuminate\Support\Facades\File;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class FugaPublisher extends BasePublisher
{
    use TrackPublisher, ArtistOperations, ProductPublisher, DeliveryInstruction;

    private $user_name;
    private $password;
    private $client;
    private $fuga_product;

    public $fugaLabel = null;
    public $fugaProduct = null;
    public $fugaPublisher = null;

    public function __construct(Album $album)
    {
        parent::__construct($album);

        $this->client = new Client([
            'cookies' => true,
            'base_uri' => 'https://next.fugamusic.com/api/v1/',
            'headers' => [
                'Accept' => 'application/json',
                'Content-Type' => 'application/json',
            ],
        ]);

        $this->user_name = 'devapi';
        $this->password = '(&/nouiii23jk(&%/&)pmnohgz';

        Log::info('Initializing Fuga Publisher: Album ID: ' . $album->id . ' Album Title: ' . $album->title);
        $this->log(
            "INIT",
            true,
            'Initializing Fuga Publisher: Album ID: ' . $album->id . ' Album Title: ' . $album->title
        );
        $this->login();
    }

    /**
     * @inheritDoc
     */
    public function publish()
    {
        /*
         * Album submission steps
         * Create Label
         * Create/Get Artist
         * Create Album/Product
         * Upload Cover
         * Upload Songs & Assign to the product
         */
        $songs = $this->album->songs;

        $album_id = $this->album->id;
        $album_name = $this->album->title;
        $cover_image = $this->album->cover;

        $album_data = Album::with('user')
            ->with('genre')
            ->with('songs')
            ->findOrFail($album_id)
            ->toArray();

        $path = storage_path('app/public/albums/' . $album_id . '/' . $cover_image);

        $user_id = $album_data['user']['id'];
        $artist_name = $album_data['user']['artistName'];
        $label_name = $album_data['user']['artistName'];

        try {
            $fuga_artist_id = $this->ensureArtist($user_id, $artist_name);

            User::where('id', $user_id)->update([
                'fuga_artist_id' => $fuga_artist_id
            ]);

            $fuga_people_id = $this->ensurePeople($this->album->user->name);
            $fuga_publisher_id = $this->ensurePublisher($this->album->user->name);
            $fuga_label_id = $this->createLabel($user_id, $label_name);

            $product_data = $this->createProduct(
                $album_id,
                $album_name,
                $fuga_label_id,
                $fuga_artist_id,
            );
            $this->assignUpcCode();

            // Create product cover photo
            if ($product_data && File::exists($path)) {
                $ext = pathinfo($path, PATHINFO_EXTENSION);
                $size = getimagesize($path);

                if (in_array(strtolower($ext), ['jpg', 'png', 'gif']) && $size[0] > 1400 && $size[1] > 1400) {
                    $uuid = $this->createCoverPhotoRelease($product_data['fuga_cover_image_id']);

                    if ($uuid) {
                        $this->uploadCoverPhoto($uuid, $path);

                        $this->finishUpload($uuid, basename($path));

                        $this->log(
                            "COVER_UPLOAD",
                            true,
                            "Cover Uploaded: {$uuid}"
                        );
                    }
                } else {
                    Log::info("Not a valid image.");

                    $this->log(
                        "COVER_UPLOAD",
                        false,
                        "Not a valid image. {$path}"
                    );
                }
            }

            $genre = '';
            if ($album_data['genre']) {
                $genre = strtoupper($album_data['genre']['name']);
            }


            Log::info("Total Songs: " . count($songs));

            $this->log(
                "CHECK_TRACKS",
                true,
                "Total Songs: " . count($songs)
            );

            foreach ($songs as $song) {
                $songFile = $song['songFile'];
                $title = $song['title'];

                $path = 'albums/' . $album_id . '/songs/' . $songFile;

                if (Storage::exists($path)) {
                    $fuga_track_id = $this->createTrack(
                        $title,
                        $genre,
                        $song,
                        $fuga_people_id,
                        $fuga_publisher_id
                    );

                    Log::info("Track created: {$fuga_track_id}");

                    $this->log(
                        "TRACK_CREATE",
                        true,
                        "Track created: {$fuga_track_id}"
                    );

                    if ($fuga_track_id) {
                        $uuid = $this->initiateChunkUpload($fuga_track_id);
                        $this->log(
                            "PREPARE_TRACK_UPLOAD",
                            true,
                            "Track UID: {$uuid}"
                        );

                        if ($uuid) {
                            $this->uploadAudioChunks($uuid, $path);

                            sleep(2);

                            $this->finishUpload($uuid, basename($path));

                            $this->log(
                                "FINISH_TRACK_UPLOAD",
                                true,
                                "Track UID: {$uuid}"
                            );

                            $song->update([
                                'fuga_track_id' => $fuga_track_id
                            ]);

                            $id = $this->linkTrackToProduct($fuga_track_id, $this->album->fuga_product_id);

                            $this->log(
                                "LINK_TRACK_TO_PRODUCT",
                                true,
                                "Linked release track id {$id}"
                            );

                            $song->update([
                                'fuga_link_release_track_id' => $id
                            ]);
                        }
                    }
                } else {
                    Log::info("Song file not found: {$path}");

                    $this->log(
                        "TRACK_UPLOAD",
                        false,
                        "Song file not found: {$path}"
                    );
                }
            }

            $this->addDsp($this->album->fuga_product_id);

            $this->saveAlbumSubmission(AlbumSubmission::PUBLISH_STATUS_DELIVERED);
        } catch (\Exception $exception) {
            Log::error($exception->getMessage());

            $this->log('EXIT_JOB', false, $exception->getMessage());

            $this->saveAlbumSubmission(AlbumSubmission::PUBLISH_STATUS_FAILED);
        }
    }

    public function createLabel($user_id, $label_name)
    {
        Log::info('createLabel');
        $path = base_path() . '/public/';

        $data = array('name' => $label_name);
        $cookie = @file_get_contents($path . 'cookie.txt');
        $connect_sid = @trim(explode('connect.sid', $cookie)[1]);
        //echo $connect_sid; die;

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, 'https://next.fugamusic.com/api/catalog/labels');
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));

        $headers = array();
        $headers[] = "Content-Type: application/json";
        $headers[] = "Cookie: connect.sid=$connect_sid";
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

        $result = curl_exec($ch);

        if (curl_errno($ch)) {
            echo 'Error:' . curl_error($ch);
        }

        curl_close($ch);
        $res = json_decode($result, true);

        $label_id = '';
        if (@$res['code'] == 'DUPLICATE_LABEL_NAME') {
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, 'https://next.fugamusic.com/api/catalog/labels');
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

            $headers = array();
            $headers[] = "Content-Type: application/json";
            $headers[] = "Cookie: connect.sid=$connect_sid";
            curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

            $result = curl_exec($ch);
            if (curl_errno($ch)) {
                echo 'Error:' . curl_error($ch);
            }

            curl_close($ch);
            $res = json_decode($result, true);

            foreach ($res as $lbl) {
                if ($label_name == $lbl['name']) {
                    $label_id = $lbl['id'];
                }
            }
        } else {
            $label_id = @$res['id'];
        }

        User::where('id', $user_id)->update([
            'fuga_label_id' => $label_id
        ]);

        return $label_id;
        //print_r($res); die;
    }

    public function login()
    {
        Log::info('Login to Fuga API');

        $this->client->post('login', [
            'json' => [
                'name' => $this->user_name,
                'password' => $this->password
            ]
        ]);
    }

    public function setPublisherType(): void
    {
        $this->publisherType = Album::PUBLISHER_FUGA;
    }
}
