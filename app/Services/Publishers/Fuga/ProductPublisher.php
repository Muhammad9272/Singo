<?php

namespace App\Services\Publishers\Fuga;

use App\Models\Album;
use App\Services\Publishers\FugaPublisher;
use CURLFile;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

trait ProductPublisher
{
    public function generateCatalogNumber(): string
    {
        $response = $this->client->get('products?page=0&page_size=20');

        if ($response->getStatusCode() !== 200) {
            return 'SNG' . date('ydw');
        }

        $album = collect(json_decode($response->getBody()->getContents())->product)->sortByDesc('catalog_number')->first();

        if (!$album) {
            return 'SNG' . date('ydw');
        }

        return 'SNG' . str_pad(explode('SNG', $album->catalog_number)[1] + 1, 5, 0, STR_PAD_LEFT);
    }

    public function createProduct($album_id, $album_name, $fuga_label_id, $fuga_artist_id)
    {
        Log::info('createProduct');

        $data = $this->album->getFugaApiSubmissionData();

        $data = array_merge($data, [
            'catalog_number' => $this->generateCatalogNumber(),
            'artists' => [
                [
                    "id" => $fuga_artist_id,
                    "primary" => true
                ]
            ],
            'display_artist' => $this->album->user->artistName
        ]);

        $response = $this->client->post('products', [
            'json' => $data
        ]);

        if ($response->getStatusCode() === 200) {
            $res = json_decode($response->getBody()->getContents(), true);

            $this->fuga_product = $res;

            $product_id = $res['id'];
            $fuga_cover_image_id = $res['cover_image']['id'];

            $this->log(
            "CREATE_ALBUM",
            true,
            "Album created successfully: {$product_id}"
            );

            $this->album->update([
                'fuga_product_id' => $product_id,
                'fuga_cover_image_id' => $fuga_cover_image_id
            ]);

            return [
                'product_id' => $product_id,
                'fuga_cover_image_id' => $fuga_cover_image_id
            ];
        }

        return false;
    }

    /**
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function assignUpcCode(): FugaPublisher
    {
        $response = $this->client->post('products/' . $this->album->fuga_product_id . '/barcode')
            ->getBody()
            ->getContents();

        $product = json_decode($response);

        if (isset($product->upc)) {
            $this->album->update([
                'upc' => $product->upc
            ]);
        }

        return $this;
    }


    public function uploadCoverPhoto($uuid, $image_path)
    {
        Log::info('Uploading cover photo');

        $fileBaseName = basename($image_path);

        $this->client->post('upload', [
                'multipart' => [
                    [
                        'name' => 'uuid',
                        'contents' => $uuid,
                    ],
                    [
                        'name' => 'partbyteoffset',
                        'contents' => 0,
                    ],
                    [
                        'name' => 'file',
                        'contents' => file_get_contents($image_path),
                        'filename' => $fileBaseName,
                    ]
                ],
            ]
        );

        return true;
    }

    public function createCoverPhotoRelease($cover_image_id = false)
    {
        Log::info('createCoverPhotoRelease');

        $request = $this->client->post('upload/start', [
            'json' => [
                'id' => $cover_image_id, 'type' => "image"
            ]
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

    public function createProductCoverArt($album_id, $album_name)
    {
        Log::info('createProductCoverArt');

        $path = base_path() . '/public/';

        $data = array('name' => $album_name);
        $cookie = @file_get_contents($path . 'cookie.txt');
        $connect_sid = @trim(explode('connect.sid', $cookie)[1]);
        //echo $connect_sid; die;

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, 'https://next.fugamusic.com/api/catalog/products');
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));

        $headers = array();
        $headers[] = "Content-Type: application/json";
        $headers[] = "Cookie: connect.sid=$connect_sid";
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

        $result = curl_exec($ch);
        if (curl_errno($ch)) {
            Log::error('Error:' . curl_error($ch));
        }

        curl_close($ch);
        $res = json_decode($result, true);
        $product_id = $res['id'];

        $this->album->update([
            'fuga_product_id' => $product_id
        ]);

        return $product_id;
    }
}
