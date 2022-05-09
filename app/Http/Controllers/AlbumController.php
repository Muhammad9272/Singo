<?php

namespace App\Http\Controllers;

use App\Http\Requests\AlbumRequest;
use App\Jobs\PublishAlbumToFuga;
use App\Models\Album;
use App\Models\AudioLocale;
use App\Models\Genre;
use App\Models\User;
use App\Models\Song;
use App\Models\FeaturedArtist;
use App\Models\UserRequest;
use App\Models\Store;
use App\Models\User_Store;
use App\Models\UserSetting;
use App\Models\Plan;
use App\Models\Tempfile;
use App\Mail\AlbumSubmitMail;
use App\Mail\AlbumStatus;
use App\Notifications\AlbumStatusChanged;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Session;
use Intervention\Image\Facades\Image;

class AlbumController extends Controller
{
    public function batch_mark(Request $request)
    {
        $request->validate([
            'albums' => 'required|array',
            'status' => 'required'
        ]);

        try {
            // For triggering events
            foreach ($request->get('albums', []) as $albumId) {
                $album = Album::find($albumId);
                $album->status = $request->status;
                $album->save();
            }

            return response(['message' => 'Status updated successfully.']);
        } catch (\Exception $exception) {
            return response(['message' => $exception->getMessage()], 500);
        }
    }

    public function cover(Album $album)
    {
        $path = public_path('storage/albums/' . $album->id . '/' . $album->cover);

        if (!file_exists($path)) {
            return Image::canvas(150, 150, '#ddd')
                ->text('No cover.', 75, 75, function ($font) {
                    $font->align('center');
                    $font->valign('top');
                    $font->angle(45);
                })
                ->response('jpg');
        }

        $img = Image::make($path)->resize(150, 150);

        return $img->response('jpg');
    }

    public function catalog()
    {
        $albums = auth()->user()->albums()->with('genre')->with('request')->get();

        return view('album.catalog', ['albums' => $albums]);
    }

    /**
     * Show the form for creating a new resource.
     *
     */
    public function create()
    {
        $totalAlbums = auth()->user()->albums()->count();

        if ($totalAlbums >= auth()->user()->subscriptionPlan->release_amount) {
            return redirect()
                ->route('home')
                ->with('warning', "You reached your release limit, please upgrade your plan in order to release another album.");
        }

        $genres = Genre::whereNotNull('slug')->get();

        $stores = Store::whereNotNull('fuga_store_id')
            ->orderBy('title')
            ->get();

        return view('album.create', compact('genres', 'stores'));
    }

    public function store(AlbumRequest $request)
    {
        $id = auth()->user()->id;
        $albums = Album::where('user_id', $id)->count();
        $plan_id = auth()->user()->plan;
        $plans = Plan::findOrFail($plan_id);
        $release_amount = $plans->release_amount;

        if ($albums >= $release_amount) {
            return redirect()
                ->route('home')
                ->with('warning', "You reached your release limit, please upgrade your plan in order to release another album.");
        }

        ini_set('max_execution_time', 300000);
        ini_set('upload_max_filesize', '256M');
        ini_set('post_max_size', '256M');
        ini_set('client_max_body_size', '256M');

        $temp_cover = Tempfile::findOrFail($request->cover);

        $album = new Album;
        $album->title = $request->name;
        $album->genre_id = $request->genre;
        $album->release = $request->date;
        $album->language_id = $request->album_language_id;
        $album->user_id = auth()->user()->id;

        $album->cover = $temp_cover->file;

        $album->upc = $request->upc;
        $album->spotify_url = $request->spo_url;
        $album->apple_music_url = $request->apl_url;
        $album->save();

        $from_path = 'file/tmp/' . $temp_cover->folder . '/' . $temp_cover->file;
        $to_path = 'public/albums/' . $album->id . '/' . $temp_cover->file;
        Storage::move($from_path, $to_path);
        rmdir(storage_path('app/file/tmp/' . $temp_cover->folder));

        if ($request->has('store')) {
            foreach ($request->store as $st) {
                $us = new User_Store;
                $us->store_id = $st;
                $us->album_id = $album->id;
                $us->save();
            }
        }

        foreach ($request->songs as $songInput) {
            $temp_song = Tempfile::findOrFail($songInput['song']);
            if ($temp_song) {
                $from_path = 'file/tmp/' . $temp_song->folder . '/' . $temp_song->file;
                if (Storage::exists($from_path)) {
                    $to_path = 'albums/' . $album->id . '/songs/' . $temp_song->file;
                    Storage::move($from_path, $to_path);
                    rmdir(storage_path('app/file/tmp/' . $temp_song->folder));
                } else {
                    return redirect()->route('song')->withErrors(['song' => 'no song detected']);
                }
            }

            $song = $album->songs()->create([
                'title' => $songInput['title'],
                'composer' => $songInput['composer'],
                'isrc' => $songInput['isrc'],
                'audio_locale_id' => $songInput['audio_locale_id'],
                'language' => AudioLocale::findOrFail($songInput['audio_locale_id'])->name,
                'isExplicit' => (isset($songInput['radio']) && ($songInput['radio'] == "explicit")) ? true : false,
                'isInstrumental' => (isset($songInput['radio']) && $songInput['radio'] == "instrumental") ? true : false,
                'songFile' => $temp_song->file
            ]);

            if (isset($songInput['fartist'])) {
                foreach ($songInput['fartist'] as $fa) {
                    $save = new FeaturedArtist;
                    $save->artist_name = $fa;
                    $save->song_id = $song->id;
                    $save->user_id = auth()->user()->id;
                    $save->save();
                }
            }
        }

        //send notification
        $route = "album";
        $type = "album";
        $route_id = $album->id;

        //notification for users
        $message = "Album name: " . $album->title . " successfully submited";
        $user = User::findOrFail($album->user_id);
        $user->notify(new AlbumStatusChanged($album, $route, $type, $message, $route_id));

        //notification for superadmin
        $message = "Album name: " . $album->title . " Submitted by: " . auth()->user()->name;
        foreach (User::where('type', 3)->get() as $admin) {
            $admin->notify(new AlbumStatusChanged($album, $route, $type, $message, $route_id));
        }


        //Album Submit Mail to Client
        $user = Album::with('user')->findOrFail($album->id);
        Mail::to($user->user->email)->send(new AlbumSubmitMail($user));

        if ($request->acceptsJson()) {
            return $album;
        }

        return redirect()->route('album', $album->id)->with('created', 'Your album was successfully created');
    }

    public function downloadCover(Album $album)
    {
        return Storage::download('public/albums/' . $album->id . '/' . $album->cover);
    }

    /**
     * Display the specified resource.
     *
     * @param \App\Models\Album $album
     * @return \Illuminate\Http\Response
     */
    public function show(Album $album)
    {
        if ((auth()->user()->type == 0) && ($album->user_id != auth()->user()->id)) {
            return redirect()->route('home');
        }

        $user_Store = User_Store::with('album_name')
            ->with('store_name')
            ->where('album_id', $album->id)
            ->get();
        //echo "<pre>"; print_r($user_Store); die();
        return response()->view('album.show', ['album' => $album, 'user_Store' => $user_Store]);
    }

    public function getAlbumQuery(Request $request)
    {
        return Album::select(['albums.id', 'albums.title', 'albums.created_at', 'albums.release', 'albums.genre_id', 'albums.status', 'users.artistName', 'users.name', 'users.plan', 'users.isPremium', 'albums.user_id', 'albums.cover', 'albums.song_deleted', 'albums.upc'])
            ->join('users', 'albums.user_id', '=', 'users.id')
            ->when($request->searchQuery, function ($query) {
                $query->where(function ($userQuery) {
                    $userQuery->where('title', 'LIKE', '%' . request('searchQuery') . '%')
                        ->orWhere('artistName', 'LIKE', '%' . request('searchQuery') . '%')
                        ->orWhere('upc', 'LIKE', '%' . request('searchQuery') . '%')
                        ->orWhereHas('user', function ($userQuery) {
                            $userQuery->where('name', 'LIKE', '%' . request('searchQuery') . '%');
                        });
                });
            })
            ->when($request->genre, function ($query) {
                $query->where('genre_id', request('genre'));
            })
            ->when($request->plan, function ($query) {
                $query->where('users.plan', request('plan'));
            })
            ->when($request->date, function ($query) {
                $query->whereDate('release', request('date'));
            });
    }

    public function pending(Request $request)
    {
        $albums = $this->getAlbumQuery($request)
            ->where('status', 0)
            ->orderBy('albums.created_at', 'asc')
            ->orderBy('users.isPremium', 'asc')
            ->paginate(10)
            ->withQueryString();
        //dd($albums);
        return response()->view('admin.albums', ['title' => 'Pending albums', 'albums' => $albums]);
    }

    public function distributed(Request $request)
    {
        // $this->authorize('viewAny', Album::class);
        $albums = $this->getAlbumQuery($request)
            ->where('status', 2)
            ->paginate(10)
            ->withQueryString();

        return response()->view('admin.albums', ['title' => 'Distributed albums', 'albums' => $albums]);
    }

    public function declined(Request $request)
    {
        $pageCount = isset($request['albums']) ? $request['albums'] : 10;
        $albums = $this->getAlbumQuery($request)
            ->where('status', -1)
            ->paginate($pageCount)
            ->withQueryString();

        return response()->view('admin.albums', ['title' => 'Declined albums', 'albums' => $albums, 'pageCount' => $pageCount, 'showDeleteFile' => true]);
    }

    public function deleteChecked(Request $request)
    {

        if (is_array($request->albums) && count($request->albums) > 0) {

            foreach ($request->albums as $value) {
                Album::where('id', $value)->update([
                    'song_deleted' => 'Yes',
                ]);
                // delete files 
                $this->deleteFilesFromAlbum($value);
            }

            return response()->json([
                'status' => 'success'
            ]);
        } else {
            return response()->json([
                'status' => false
            ]);
        }
    }

    public function deleteFilesFromAlbum($folder = null)
    {
        if ($folder == null) {
            return false;
        }

        $folderPath = 'albums/' . $folder . "/songs/";
        $files = Storage::files($folderPath);

        foreach ($files as $file) {
            Storage::delete($file);
        }

        return true;
    }

    public function approved(Request $request)
    {
        // $this->authorize('viewAny', Album::class);
        $pageCount = isset($request['albums']) ? $request['albums'] : 10;
        $albums = $this->getAlbumQuery($request)
            ->where('status', 1)
            ->paginate($pageCount)
            ->withQueryString();
        //echo "<pre>"; print_r($albums); die();
        return response()->view('admin.albums', ['title' => 'Approved albums', 'albums' => $albums, 'pageCount' => $pageCount]);
    }

    public function need_edit(Request $request)
    {
        // $this->authorize('viewAny', Album::class);
        $albums = $this->getAlbumQuery($request)
            ->where('status', 3)
            ->paginate(10)
            ->withQueryString();

        return response()->view('admin.albums', ['title' => 'Need Edit Albums', 'albums' => $albums]);
    }


    /**
     * Show the form for editing the specified resource.
     *
     * @param \App\Models\Album $album
     * @return \Illuminate\Http\Response
     */
    public function edit(Album $album)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param \App\Models\Album $album
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function update(Request $request, Album $album)
    {
        // $this->authorize('update', $album);

        $validator = Validator::make($request->all(), [
            'status' => 'integer|min:-1|max:3|required',
            'releaseDate' => 'date|required',
            'upc' => 'nullable',
            'note' => 'nullable'
        ]);

        if ($validator->fails()) {
            return redirect()->route('album', $album->id)->withErrors($validator);
        }

        $albumPreviousStatus = $album->status;


        $album->status = $request->status;
        $album->release = $request->releaseDate;

        if ($request->album_language_id) {
            $album->language_id = $request->album_language_id;
        }

        $album->upc = $request->upc;
        $album->note = $request->note;

        $album->save();


        //send notification
        $route = "album";
        $type = "album";
        $route_id = $album->id;

        //notification for users
        $message = "Album name: " . $album->title . " status changed";
        $user = User::findOrFail($album->user_id);
        $user->notify(new AlbumStatusChanged($album, $route, $type, $message, $route_id));

        //notification for superadmin
        $message = "Album name: " . $album->title . " status changed by: " . auth()->user()->name;
        foreach (User::where('type', 3)->get() as $admin) {
            $admin->notify(new AlbumStatusChanged($album, $route, $type, $message, $route_id));
        }

        //notifacitan for admin/moderator
        $message = "Album name: " . $album->title . " status successfully changed";
        $changer = auth()->user();
        $changer->notify(new AlbumStatusChanged($album, $route, $type, $message, $route_id));


        //Albam Status Mail to Client
        $user_info = Album::with('user')->findOrFail($request->id);
        Mail::to($user_info->user->email)->send(new AlbumStatus($user_info));


        return redirect()->route('album', $album->id)->with('updated', 'The album was successfully updated');
    }


    /**
     * Remove the specified resource from storage.
     *
     * @param \App\Models\Album $album
     * @return \Illuminate\Http\Response
     */
    public function destroy(Album $album)
    {
        //
    }


    public function album_request($id)
    {
        //authentication
        $album = Album::findOrFail($id);
        if ($album->user_id != auth()->user()->id) {
            abort(404);
        }
        $request = UserRequest::Where('album_id', $id)->orderby('created_at', 'DESC')->take(1)->get();
        foreach ($request as $rt) {
            if ($rt->status == 0) {
                $this->message('error', 'You already have a pending request for this album');
                return redirect()->route('albums');
            } else if ($album->status == 0 || $album->status == -1) {
                if ($album->status == 0) {
                    $this->message('error', 'Your Album Status is: Pending You dont need request to edit');
                } else {
                    $this->message('error', 'Your Album Status is: Declined  You cannot edit');
                }
                return redirect()->route('albums');
            } else if ($rt->status == 1 && $rt->isUpdated == 0) {
                $this->message('error', 'Your Album Status is: Accepted already  You dont need request to edit');
                return redirect()->route('albums');
            } else {
                return view('album.edit_request', ['album' => $album]);
            }
        }
        if ($album->status == 0 || $album->status == -1 || $album->status == 3) {
            if ($album->status == 0) {
                $this->message('error', 'Your Album Status is: Pending You dont need request to edit');
            } elseif ($album->status == -1) {
                $this->message('error', 'Your Album Status is: Declined  You cannot edit');
            } else {
                $this->message('error', 'Your Album Status is: Need Edit You dont need request to edit');
            }
            return redirect()->route('albums');
        } else {
            return view('album.edit_request', ['album' => $album]);
        }
    }

    public function album_request_store(Request $request)
    {
        //authentication
        $album = Album::findOrFail($request->album_id);
        if ($album->user_id != auth()->user()->id) {
            abort(404);
        }


        $cheak = UserRequest::where('album_id', $request->album_id)->where('status', '0')->get();
        if (count($cheak) != 0) {
            $this->message('error', 'You already have a pending request for this album');
            return redirect()->route('albums');
        } else {
            $save = new UserRequest;
            $save->user_id = auth()->user()->id;
            $save->album_id = $request->album_id;
            $save->reason = $request->reason;
            $save->save();

            $this->message('success', 'Your Album Edit Request Submitted Successfully');
            return redirect()->route('albums');
        }

        //send notification
        $route = "albums";
        $type = "edit_request";
        $route_id = "";

        //notification for users
        $message = "We have received your request for " . $album->title . "";
        $user = User::findOrFail($album->user_id);
        $user->notify(new AlbumStatusChanged($album, $route, $type, $message, $route_id));

        //notification for superadmin
        $route = "users.requests";
        $message = auth()->user()->name . " submitted a edit request for: " . $album->title . "";
        foreach (User::where('type', 3)->get() as $admin) {
            $admin->notify(new AlbumStatusChanged($album, $route, $type, $message, $route_id));
        }
    }

    public function album_edit(Album $album)
    {
        $user_store = User_Store::where('album_id', $album->id)->get();
        $album = Album::findOrFail($album->id);

        $request = UserRequest::where('album_id', $album->id)
            ->orderby('created_at', 'DESC')
            ->take(1)
            ->get();

        $store = Store::orderBy('title')
            ->get();
        foreach ($request as $rt) {
            if ($album->status == 0 || $album->status == 3 || auth()->user()->isAdmin()) {
                return view(
                    'album.edit',
                    ['album' => $album, 'genres' => Genre::all(), 'store' => $store, 'user_store' => $user_store]
                );
            }

            if (($album->status == 1 || $album->status == 2 || $album->status == -1) && (!isset($rt->status))) {
                if ($album->status == 1) {
                    $this->message('error', 'Your Album Status is: Approved  You need request to edit');
                } elseif ($album->status == 2) {
                    $this->message('error', 'Your Album Status is: Delivered  You need request to edit');
                } else {
                    $this->message('error', 'Your Album Status is: Declined  You cannot edit');
                }
                return redirect()->route('albums');
            } else if ($rt->status == 0) {
                $this->message('error', 'Your Edit request is: Pending  Please wait untill accept');
                return redirect()->route('albums');
            } else if ($rt->status == 2) {
                $this->message('error', 'Your Edit request is: Declined  Please create another request');
                return redirect()->route('albums');
            } else if ($rt->status == 1 && $rt->isUpdated == 1) {
                $this->message('error', 'You have successfully submitted last update if you want to update again please create another request for that');
                return redirect()->route('albums');
            } else {
                return view('album.edit', ['album' => $album, 'genres' => Genre::all(), 'store' => $store, 'user_store' => $user_store]);
            }
        }

        if (($album->status == 1 || $album->status == 2 || $album->status == -1) && (!isset($rt->status)) && (!auth()->user()->isAdmin())) {

            if ($album->status == 1) {
                $this->message('error', 'Your Album Status is: Approved  You need request to edit');
            } elseif ($album->status == 2) {
                $this->message('error', 'Your Album Status is: Delivered  You need request to edit');
            } else {
                $this->message('error', 'Your Album Status is: Declined  You cannot edit');
            }
            return redirect()->route('albums');
        }


        return view(
            'album.edit',
            ['album' => $album, 'genres' => Genre::all(), 'store' => $store, 'user_store' => $user_store]
        );
    }

    public function album_edit_store(Request $request)
    {
        // return $request;

        $request->validate([
            'name' => 'required|string|max:255',
            'genre' => 'exists:genres,id',
            'cover' => 'nullable',
            'date' => 'date',
            'upc' => 'nullable',
        ]);
        if (!auth()->user()->isAdmin()) {
            $request->validate([
                'date' => 'after:today'
            ]);
        }
        $album = Album::find($request->album_id);
        if (isset($request->name)) {
            $album->title = $request->name;
        }
        if (isset($request->genre)) {
            $album->genre_id = $request->genre;
        }

        if (isset($request->album_language_id)) {
            $album->language_id = $request->album_language_id;
        }
        if ($request->has('store')) {
            User_Store::where('album_id', $request->album_id)->delete();
            foreach ($request->store as $st) {

                $us = new User_Store;
                $us->store_id = $st;
                $us->album_id = $album->id;
                $us->save();
            }
        }
        if (isset($request->date)) {
            $album->release = $request->date;
        }
        // $album->user_id = auth()->user()->id;
        if ($request->cover) {
            $temp_cover = Tempfile::findOrFail($request->cover);
            $path = 'file/tmp/' . $temp_cover->folder . '/' . $temp_cover->file;
            if (Storage::exists($path)) {
                unlink(storage_path('app/public/albums/' . $album->id . '/' . $album->cover));

                $from_path = 'file/tmp/' . $temp_cover->folder . '/' . $temp_cover->file;
                $to_path = 'public/albums/' . $album->id . '/' . $temp_cover->file;
                Storage::move($from_path, $to_path);
                rmdir(storage_path('app/file/tmp/' . $temp_cover->folder));
                $album->cover = $temp_cover->file;
            } else {
                return redirect()->back()->withErrors(['cover' => 'no cover detected']);
            }
        }
        if (isset($request->upc)) {
            $album->upc = $request->upc;
        }
        if (isset($request->spo_url)) {
            $album->spotify_url = $request->spo_url;
        }
        if (isset($request->apl_url)) {
            $album->apple_music_url = $request->apl_url;
        }
        if (!auth()->user()->isAdmin()) {
            $album->status = 0;
        }
        $album->save();

        for ($i = 0; $i <= $request->songcount; $i++) {
            $new_song_id = "song_id" . $i;
            $new_title = "title" . $i;
            $new_composer = "composer" . $i;
            $new_language = "language" . $i;
            $new_radio = "radio" . $i;
            $new_songs = "songs" . $i;
            $new_isrc = "isrc" . $i;
            $new_count_artist = "total_artist" . $i;

            if (isset($request->$new_song_id) && $request->$new_title != null) {
                $song = Song::findOrFail($request->$new_song_id);
                $song->title = $request->$new_title;
                $song->composer = $request->$new_composer;
                $song->language = AudioLocale::findOrFail($request->$new_language)->name;
                if (isset($request->$new_radio)) {
                    if ($request->$new_radio == "explicit") {
                        $song->isExplicit = 1;
                    } else {
                        $song->isExplicit = 0;
                    }
                }
                if (isset($request->$new_radio)) {
                    if ($request->$new_radio == "instrumental") {
                        $song->isInstrumental = 1;
                    } else {
                        $song->isInstrumental = 0;
                    }
                }
                if (!isset($request->$new_radio)) {
                    $song->isExplicit = 0;
                    $song->isInstrumental = 0;
                }
                $song->isrc = $request->$new_isrc;

                if ($request->$new_songs) {

                    unlink(storage_path('app/albums/' . $song->album_id . '/songs/' . $song->songFile));

                    $temp_song = Tempfile::findOrFail($request->$new_songs);
                    if ($temp_song) {
                        $from_path = 'file/tmp/' . $temp_song->folder . '/' . $temp_song->file;
                        if (Storage::exists($from_path)) {
                            $to_path = 'albums/' . $album->id . '/songs/' . $temp_song->file;
                            Storage::move($from_path, $to_path);
                            rmdir(storage_path('app/file/tmp/' . $temp_song->folder));
                        } else {
                            return redirect()->back()->withErrors(['song' => 'no song detected']);
                        }

                        $song->songFile = $temp_song->file;
                    }
                }
                if (isset($request->$new_count_artist) && $request->$new_count_artist != null) {
                    FeaturedArtist::where('song_id', $song->id)->delete();
                    for ($j = 1; $j <= $request->$new_count_artist; $j++) {
                        $new_input_fartist = "fartist_name_" . $i . "_" . $j;
                        if ($request->$new_input_fartist != null) {

                            $save = new FeaturedArtist;
                            $save->artist_name = $request->$new_input_fartist;
                            $save->song_id = $song->id;
                            $save->user_id = $album->user_id;
                            $save->save();
                        }
                    }
                }

                $song->save();
            } elseif ($request->$new_title != null) {

                $song = new Song;
                $song->album_id = $request->album_id;
                $song->title = $request->$new_title;
                $song->composer = $request->$new_composer;
                $song->language = AudioLocale::findOrFail($request->$new_language)->name;
                if (isset($request->$new_radio)) {
                    if ($request->$new_radio == "explicit") {
                        $song->isExplicit = 1;
                    } else {
                        $song->isExplicit = 0;
                    }
                }
                if (isset($request->$new_radio)) {
                    if ($request->$new_radio == "instrumental") {
                        $song->isInstrumental = 1;
                    } else {
                        $song->isInstrumental = 0;
                    }
                }
                if (!isset($request->$new_radio)) {
                    $song->isExplicit = 0;
                    $song->isInstrumental = 0;
                }

                $song->isrc = $request->$new_isrc;

                if ($request->$new_songs) {
                    $temp_song = Tempfile::findOrFail($request->$new_songs);
                    if ($temp_song) {
                        $from_path = 'file/tmp/' . $temp_song->folder . '/' . $temp_song->file;
                        if (Storage::exists($from_path)) {
                            $to_path = 'albums/' . $album->id . '/songs/' . $temp_song->file;
                            Storage::move($from_path, $to_path);
                            rmdir(storage_path('app/file/tmp/' . $temp_song->folder));
                        } else {
                            return redirect()->back()->withErrors(['song' => 'no song detected']);
                        }

                        $song->songFile = $temp_song->file;
                    }
                }

                $song->save();

                if (isset($request->$new_count_artist) && $request->$new_count_artist != null) {
                    FeaturedArtist::where('song_id', $song->id)->delete();
                    for ($j = 1; $j <= $request->$new_count_artist; $j++) {
                        $new_input_fartist = "fartist_name_" . $i . "_" . $j;
                        if ($request->$new_input_fartist != null) {

                            $save = new FeaturedArtist;
                            $save->artist_name = $request->$new_input_fartist;
                            $save->song_id = $song->id;
                            $save->user_id = $album->user_id;
                            $save->save();
                        }
                    }
                }
            } else {
                if (isset($request->$new_song_id)) {
                    $song = Song::findOrFail($request->$new_song_id);
                    unlink(storage_path('app/albums/' . $song->album_id . '/songs/' . $song->songFile));
                    Song::where('id', $request->$new_song_id)->delete();
                    FeaturedArtist::where('song_id', $request->$new_song_id)->delete();
                }
            }
        }

        //status update //ignore status update, if updated by admin
        if (!auth()->user()->isAdmin()) {
            $request = UserRequest::Where('album_id', $album->id)->where('isUpdated', 0)->orderby('created_at', 'DESC')->take(1)->get();
            foreach ($request as $rt) {
                $rt->isUpdated = 1;
                $rt->save();
            }
        }


        $this->message('success', 'Album Updated Successfully');
        return redirect()->back();
        //return redirect()->back();


    }

    public function dark_mode(Request $request)
    {
        $i = "0";
        $user_id = auth()->user()->id;
        $cheak = UserSetting::where('user_id', $user_id)->get();
        foreach ($cheak as $ck) {
            $id = $ck->id;
            $i = $i + 1;
        }
        if ($i == "0") {
            $save = new UserSetting;
            $save->user_id = $user_id;
            $save->dark_mode = $request->dark_mode;
            $save->save();
        } else {
            $save = UserSetting::findOrFail($id);
            if ($request->dark_mode == null) {
                $save->dark_mode = "0";
            } else {
                $save->dark_mode = "1";
            }
            $save->save();
        }
        //return $request;
        return redirect()->back();
    }

    public function song_update(Request $request, Song $song)
    {
        $song->isrc = $request->isrc;
        $song->save();

        Session::flash('success', "Song ISRC Updated Successfully");
        return redirect()->back();
    }

    public function release_file($id)
    {
        $albums = Album::select(['albums.id', 'albums.title', 'albums.created_at', 'albums.release', 'albums.genre_id', 'albums.status', 'users.artistName', 'users.name', 'users.plan', 'users.isPremium', 'albums.user_id', 'albums.cover'])
            ->join('users', 'albums.user_id', '=', 'users.id')->where('albums.id', $id)
            ->get();
        // return $albums;
        return view('album.generate', ['albums' => $albums]);
    }
}
