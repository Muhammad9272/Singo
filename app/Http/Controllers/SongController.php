<?php

namespace App\Http\Controllers;

use ZipArchive;
use App\Models\Song;
use App\Models\Album;

use App\Models\Copyright;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

class SongController extends Controller
{
    public function __construct()
    {
        return $this->middleware('auth');
    }

    public function downloadSong(Song $song)
    {
        \Gate::authorize('download', $song);


        return Storage::download('albums/' . $song->album_id . '/songs/' . $song->songFile);
    }

    public function streamSong(Song $song)
    {
        \Gate::authorize('download', $song);


        $path = storage_path('app/albums/' . $song->album_id . '/songs/' . $song->songFile);
        $response = new BinaryFileResponse($path);
        BinaryFileResponse::trustXSendfileTypeHeader();

        return $response;
    }

    public function downloadAlbum(Album $album)
    {
        \Gate::authorize('download', $album);

        $zip = new ZipArchive;

        $fileName = $album->title . '.zip';

        if ($zip->open(storage_path($fileName), ZipArchive::CREATE) === true) {
            $files = File::files(storage_path('app/albums/' . $album->id . '/songs/'));

            foreach ($files as $key => $value) {
                $relativeNameInZipFile = basename($value);
                $zip->addFile($value, $relativeNameInZipFile);
            }

            $zip->close();
        }

        return response()->download(storage_path($fileName));
    }

    public function checkCopyright(Song $song)
    {
        $fileLocation = 'albums/' . $song->album_id . '/songs/' . $song->songFile;
        $destination = asset('test');


        $check = Storage::copy('../' . $fileLocation, $destination . '/' . $song->songFile);
        return $fileLocation . 'qwqwqwqwqwqwqwqwqwqwqwqwqwqwqwqw  dst' . $destination . 'check:' . $check;
    }

    public function copyrightSave(Request $request)
    {
        $copyright = new Copyright;
        $copyright->song_id = $request->song_id;
        $copyright->copyright_status = $request->copyright_status;
        $copyright->copyright_artist = $request->copyright_artist;
        $copyright->copyright_title = $request->copyright_title;
        $copyright->copyright_album = $request->copyright_album;
        $copyright->copyright_release_date = $request->copyright_release_date;
        $copyright->copyright_label = $request->copyright_label;
        $copyright->copyright_time_code = $request->copyright_time_code;
        $copyright->copyright_song_link = $request->copyright_song_link;
        $copyright->error_details = $request->error_details;
        $copyright->created_by = auth()->user()->id;
        $copyright->save();

        Session::flash('success', " Copyright saved successfully. ID: $copyright->id ");
        return redirect()->back();
    }
}
