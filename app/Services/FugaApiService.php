<?php

namespace App\Services;

use GuzzleHttp\Client;

class FugaApiService
{
    private $client;

    public function __construct()
    {
        $this->client = new Client([
            'base_uri' => 'https://next.fugamusic.com/api/v1/',
            'cookies' => true,
            'headers' => [
                'Accept' => 'application/json',
                'Content-Type' => 'application/json',
            ],
        ]);
        $this->login();
    }

    private function login()
    {
        $this->client->post('login', [
            'json' => [
                'name' => config('services.fuga.username'),
                'password' => config('services.fuga.password')
            ]
        ]);
    }

    public function getClient()
    {
        return $this->client;
    }

    public function searchArtist($artist_name)
    {
        $result = $this->client->get('artists', [
            'query' => [
                'search' => $artist_name
            ],
        ])->getBody()->getContents();

        return json_decode($result, true);
    }
}
