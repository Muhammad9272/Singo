<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Album;
use App\Models\AlbumSubmission;
use Illuminate\Http\Request;

class AlbumSubmissionController extends Controller
{
    public function show(Album $album, AlbumSubmission $submission)
    {
        return view('admin.album-submission.show', compact('album', 'submission'));
    }
}
