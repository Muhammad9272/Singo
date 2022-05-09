<?php

namespace App\Services\Publishers\Fuga;

use App\Models\User;
use Illuminate\Support\Facades\Log;

trait ArtistOperations
{
    public function ensureArtist($user_id, $artist_name)
    {
        Log::info('Search artist: ' . $artist_name);

        $result = $this->client->get('artists', [
            'query' => [
                'search' => $artist_name
            ],
        ])->getBody()->getContents();

        $artists = json_decode($result, true);

        Log::info(json_encode($artists));

        if (!empty($artists)) {
            $artist_id = $artists[0]['id'];
        } else {
            Log::info('Try creating artist: ' . $artist_name);

            $result = $this->client->post('artists', [
                'json' => [
                    'name' => $artist_name
                ],
            ])->getBody()->getContents();

            $res = json_decode($result, true);
            $artist_id = $res['id'];
        }

        $this->ensureArtistIdentifier($artist_id);


        return $artist_id;
    }

    public function ensureArtistIdentifier($artist_id)
    {
        $response = $this->client->get('artists/' . $artist_id . '/identifier');

        if ($response->getStatusCode() === 200 && $response->getBody()->getContents() != '[]') {
            return true;
        }

        return $this->createArtistIdentifier($artist_id);
    }

    public function createArtistIdentifier($artist_id)
    {
        $response = $this->client->post('artists/' . $artist_id . '/identifier', [
            "json" => [
                "identifier" => "",
                "newForIssuingOrg" => true,
                "issuingOrganization" => 746109
            ]
        ]);

        return $response->getStatusCode() === 200;
    }

    public function ensurePeople($name)
    {
        $response = $this->client->get('/api/v2/people', [
            'query' => [
                'name' => $name
            ]
        ]);


        $people = null;
        if ($response->getStatusCode() === 200) {
            $peoples = json_decode($response->getBody()->getContents(), JSON_OBJECT_AS_ARRAY);

            if (isset($peoples['person'])) {
                $people = collect($peoples['person'])->where('name', $name)->first();
            }
        }

        if (!$people) {
            $people = $this->createPeople($name);
        }

        if ($people) {
            return $people['id'];
        }

        return null;
    }

    public function createPeople($name)
    {
        $response = $this->client->post('people', [
            'json' => [
                'name' => $name
            ]
        ]);

        if ($response->getStatusCode() === 200) {
            return json_decode($response->getBody()->getContents(), JSON_OBJECT_AS_ARRAY);
        }

        return null;
    }

    public function ensurePublisher($name)
    {
        $response = $this->client->get('/api/v2/publishing_houses', [
            'query' => [
                'search' => $name
            ]
        ]);

        $publisher = null;
        if ($response->getStatusCode() === 200) {
            $publishers = collect(json_decode($response->getBody()->getContents(), JSON_OBJECT_AS_ARRAY));

            if (isset($publishers['publishing_house'])) {
                $publisher = collect($publishers['publishing_house'])->where('name', $name)->first();
            }
        }

        if (!$publisher) {
            $publisher = $this->createPublisher($name);
        }

        if ($publisher) {
            return $publisher['id'];
        }

        return null;
    }

    public function createPublisher($name)
    {
        $response = $this->client->post('publishing_houses', [
            'json' => [
                'name' => $name
            ]
        ]);

        if ($response->getStatusCode() === 200) {
            return json_decode($response->getBody()->getContents(), JSON_OBJECT_AS_ARRAY);
        }

        return null;
    }
}
