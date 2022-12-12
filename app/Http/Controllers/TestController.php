<?php

namespace App\Http\Controllers;

use App\Jobs\PublishAlbumToFuga;
use App\Models\Album;
use App\Services\Publishers\FugaPublisher;

class TestController extends Controller
{
    public function __invoke()
    {
        $album = Album::findOrFail(request()->get('id'))->load('songs.fartist', 'language');

//        dd($album->toArray());

        PublishAlbumToFuga::dispatch($album);

        return "PublishAlbumToFuga dispatched. Album: {$album->id}";
    }
}
