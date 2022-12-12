<?php

namespace App\Http\Controllers;

use App\Models\Album;

use Illuminate\Support\Facades\Session;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    public function message($type, $message)
    {
        if ($type == 'success') {
            Session::flash('success', $message);
        } elseif ($type == 'error') {
            Session::flash('error', $message);
        }
    }

    public function cheak_authentication($album_id)
    {
        $album = Album::findOrFail($album_id);
        if ($album->user_id == auth()->user()->id) {
            return "1";
        } else {
            abort(404);
        }
    }
}

