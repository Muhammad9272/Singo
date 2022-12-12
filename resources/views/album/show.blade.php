@extends('layouts.app')

@push('page_css')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/fancybox/3.5.7/jquery.fancybox.min.css"/>
    <link rel="stylesheet" href="https://cdn.plyr.io/3.6.12/plyr.css" />
@endpush

@section('content')

    <div class="container-fluid">
        <div class="row content-header mb-2 w-100">
            <div class="col-md-6">
                <h1>{{ $album->title }}</h1>
            </div>
            @if(auth()->user()->type != 0)
                <div class="col-md-6">
                    <a href="{{ route('admin.user', $album->user->id) }}" class="btn btn-sm btn-success float-right">
                        <i class="fas fa-eye"></i>
                        View User
                    </a>
                </div>
            @endif
        </div>

        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">{{ $album->title }} by {{ $album->user->artistName }}
                        <span class="btn btn-sm float-right text-white" style="background-color: {{ auth()->user()->subscriptionPlan->show_button }}">
                            {{ auth()->user()->subscriptionPlan->title }}
                        </span>
                        @if(auth()->user()->type != 0)
                        <span class="float-right mr-3"><a class="btn btn-sm btn-secondary" href="{{ route('album.release', $album->id) }}"> Generate report</a></span>
                        <span class="float-right mr-3"><a class="btn btn-sm btn-info" href="{{ route('album.edit', $album->id) }}"> Edit Album</a></span>
                        @endif
                    </div>
                    <div class="card-body">
                        @if(session('created'))
                            <div class="alert alert-success text-center">{{ session('created') }}</div>
                        @endif

                        @if(session('updated'))
                            <div class="alert alert-success text-center">{{ session('updated') }}</div>
                        @endif
                        @if(session('success'))
                            <p class="alert alert-success text-center">
                                {{ session('success') }}
                            </p>
                        @elseif(session('error'))
                            <p class="alert alert-danger text-center">
                                {{ session('error') }}
                            </p>
                        @endif

                        <div class="row">
                            <div class="col-md-3">
                                @if(auth()->user()->type == 0)
                                    <img class="w-100" src="{{ \Illuminate\Support\Facades\Storage::url('albums/'.$album->id.'/'.$album->cover) }}">
                                @else
                                    <a class="fancybox" href="{{ \Illuminate\Support\Facades\Storage::url('albums/'.$album->id.'/'.$album->cover) }}">
                                        <img class="w-100" src="{{ $album->cover_route }}">
                                    </a>
                                @endif

                                <a href="{{ route('album.download', $album->id) }}" target="_blank" class="btn btn-success1 btn-sm mt-2 divcentered">
                                    <i class="fas fa-download"></i>
                                    Download Cover
                                </a>
                            </div>
                            <div class="col-md-9">
                                <h5 class="text-white">Songs</h5>
                                <div class="table-responsive">
                                    <table class="table table-striped table-dark album-table">
                                        <thead>
                                        <tr>
                                            <th>Nr.</th>
                                            <th>Name</th>
                                            <th>Information</th>
                                            <th>Featured Artist</th>
                                            <th>Action</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        @foreach($album->songs()->get() as $song)
                                            @if($song->title != null )
                                                <tr>
                                                    <td>{{ $loop->iteration }}</td>
                                                    <td>{{ $song->title }} @if($song->isExplicit)
                                                            <span class="badge badge-secondary">E</span> @endif @if($song->isInstrumental)
                                                            <span class="badge badge-secondary">I</span> @endif
                                                    </td>
                                                    <td>
                                                        <small>
                                                            Composer: {{ $song->composer }},
                                                            Language: {{ $song->language }}
                                                        </small>
                                                    </td>
                                                    <td>
                                                        @if($song->fartist->count() > 0)
                                                            {{ $song->fartist->pluck('artist_name')->join(',') }}
                                                        @else
                                                            No featured artist found
                                                        @endif
                                                    </td>
                                                    <td>
                                                        <div class="btn-group">
                                                            <a href="{{ route('song.download', $song->id) }}" target="_blank" class="btn btn-success btn-sm">
                                                                <i class="fas fa-download"></i>
                                                            </a>
                                                            @if (auth()->user()->type != 0)
                                                                @php
                                                                    $copyRight = App\Models\Copyright::where('song_id', $song->id)->first();
                                                                @endphp
                                                                @if( $copyRight != null )
                                                                    <a class="btn btn-sm btn-secondary @if( $copyRight->copyright_title != null ) bg-primary @else bg-danger @endif checkBtn" href="" id="show_modal" data-toggle="modal" data-target="#showCopyright" data-show_song_id="{{ $copyRight->song_id }}" data-show_copyright_status="{{ $copyRight->copyright_status }}" data-show_copyright_artist="{{ $copyRight->copyright_artist }}" data-show_copyright_title="{{ $copyRight->copyright_title }}" data-show_copyright_album="{{ $copyRight->copyright_album }}" data-show_copyright_release_date="{{ $copyRight->copyright_release_date }}" data-show_copyright_label="{{ $copyRight->copyright_label }}" data-show_copyright_time_code="{{ $copyRight->copyright_time_code }}" data-show_copyright_song_link="{{ $copyRight->copyright_song_link }}" data-show_error_details="{{ $copyRight->error_details }}">
                                                                        <i class="far fa-copyright"></i>
                                                                    </a>
                                                                @else
                                                                    <a class="btn btn-sm btn-secondary checkBtn" href="" id="check_copyright" data-toggle="modal" data-target="#checkCopyright" data-song_id="{{ $song->id }}">
                                                                        <i class="far fa-copyright"></i>
                                                                    </a>
                                                                @endif
                                                            @endif
                                                        </div>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td colspan="5">
                                                        <audio id="player_{{ $song->id }}" controls data-title="{{ $song->title }}">
                                                            <source src="{{ route('song.stream', $song->id) }}" type="audio/x-wav">
                                                        </audio>
                                                    </td>
                                                </tr>
                                                @if (auth()->user()->type != 0)
                                                    <tr>
                                                        <form method="post" action="{{ route('song.update', $song->id) }}">
                                                            @csrf
                                                            <td class="align-middle">ISRC:</td>
                                                            <td class="align-middle" colspan="3">
                                                                <div class="from-group">
                                                                    <input type="text" id="isrc" value="{{ $song->isrc }}" name="isrc" class="form-control" placeholder="ISRC of The Song">
                                                                </div>
                                                            </td>
                                                            <td class="align-middle">
                                                                <div class="from-group">
                                                                    <button class="btn btn-success" type="submit">
                                                                        <i class="fas fa-save"></i> Save ISRC
                                                                    </button>
                                                                </div>
                                                            </td>
                                                        </form>
                                                    </tr>
                                                @else
                                                    <tr>
                                                        <td class="align-middle">ISRC:</td>
                                                        <td class="align-middle" colspan="4">
                                                            {{ $song->isrc ?? 'N/A' }}
                                                        </td>
                                                    </tr>
                                                @endif
                                            @endif
                                        @endforeach
                                        </tbody>
                                    </table>
                                </div>
                                <h5 class="text-white">Album Info</h5>
                                <div class="table table-responsive ">
                                    <table class="table-striped table-dark album-table">
                                        <thead>
                                        <tr>
                                            <th>Name</th>
                                            <th>Genre</th>
                                            <th>UPC</th>
                                            <th>Release</th>
                                            <th>Spotify URL</th>
                                            <th>Apple Music URL</th>
                                            <th>Status</th>
                                            <th class="text-center">Download</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        <tr>
                                            <td>{{ $album->title }}</td>
                                            <td>{{ $album->genre->name }}</td>
                                            <td>@if($album->upc !== null) {{ $album->upc }} @else Not set
                                                yet @endif</td>
                                            <td>@if($album->release !== null) {{ $album->release->format('d.m.Y') }} @else
                                                    Not set yet @endif</td>
                                            <td>
                                                @if(isset($album->spotify_url))
                                                    <p id="p1" class="d-none">{{$album->spotify_url}}</p>
                                                    <button onclick="copyToClipboard('#p1')" class="badge badge-info">
                                                        Copy URL
                                                    </button>
                                                @else
                                                    <button class="badge badge-yellow">No URL</button>
                                                @endif
                                            </td>
                                            <td>
                                                @if(isset($album->apple_music_url))
                                                    <p id="p2" class="d-none">{{$album->apple_music_url}}</p>
                                                    <button onclick="copyToClipboard('#p2')" class="badge badge-info">
                                                        Copy URL
                                                    </button>
                                                @else
                                                    <button class="badge badge-yellow">No URL</button>
                                                @endif
                                            </td>
                                            <td>
                                                <span class="badge badge-{{ $album->getStatusColor() }}">{{ $album->getStatusText() }}</span>
                                            </td>
                                            <td class="text-center">
                                                <a href="{{ route('album.all.download', $album->id) }}" target="_blank" class="btn btn-success btn-sm">
                                                    <i class="fas fa-download"></i>
                                                </a>
                                            </td>
                                        </tr>

                                        <tr>
                                            <td colspan="8">
                                                Selected Stores:
                                                @foreach($album->deliverableStores->pluck('title') as $store)
                                                    <span class="badge badge-dark p-2 mb-1">{{ $store }}</span>
                                                @endforeach
                                            </td>
                                        </tr>
                                        @if(auth()->user()->type != 0 )
                                            <tr>
                                                <td colspan="8">Note: @if($album->note != null) {{ $album->note }} @else
                                                        You are good for now @endif</td>
                                            </tr>
                                        @endif
                                        </tbody>
                                    </table>

                                    
                                </div>
                                @if(auth()->user()->type != 0)
                                        <form method="post" class="album-tableu" id="updateStatus" name="updateStatus" action="{{ route('album.update', $album->id) }}">
                                            @csrf
                                            <input type="hidden" name="id" value="{{$album->id}}">
                                            <div class="form-group">
                                                <label for="status">Status</label>
                                                <select class="custom-select" name="status" id="status">
                                                    <option value="-1" @if($album->status === -1) selected @endif>
                                                        Declined
                                                    </option>
                                                    <option value="0" @if($album->status === 0) selected @endif>
                                                        Pending
                                                    </option>
                                                    <option value="1" @if($album->status === 1) selected @endif>
                                                        Approved
                                                    </option>
                                                    <option value="2" @if($album->status === 2) selected @endif>
                                                        Delivered
                                                    </option>
                                                    <option value="3" @if($album->status === 3) selected @endif>Need
                                                        Edit
                                                    </option>
                                                </select>
                                            </div>
                                            <div class="from-group mb-2">
                                                <label for="release">Release Date</label>
                                                <input type="date" value="{{date('Y-m-d', strtotime($album->release)) }}" id="release" name="releaseDate" class="form-control" placeholder="Release date" required>
                                            </div>
                                            <div class="from-group mb-2">
                                                <label for="note">Add Note</label>
                                                <input type="text" id="note" value="{{ $album->note ?? ''  }}" name="note" class="form-control" placeholder="Add note. If you don't have any leave it.">
                                                <span class="text-sm">If you don't have any note leave it blank</span>
                                            </div>
                                            <div class="from-group mb-2">
                                                <label for="upc">UPC</label>
                                                <input type="text" id="upc" value="{{ $album->upc }}" name="upc" class="form-control" placeholder="UPC">
                                            </div>
                                            <button class="btn btn-success" type="submit"><i class="fas fa-save"></i>
                                                Save details
                                            </button>
                                        </form>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>

                @if($album->submissions()->count() && auth()->user()->type != 0)
                    <div class="card">
                        <div class="card-header">
                            <h4 class="card-title mb-0">Album Submission Logs</h4>
                        </div>

                        <div class="card-body">
                            <x-datatable.footable>
                                <thead>
                                <tr>
                                    <th>Attempt At</th>
                                    <th>Publisher</th>
                                    <th>Status</th>
                                    <th>Actions</th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($album->submissions()->latest()->get() as $submission)
                                    <tr>
                                        <td>{{ $submission->created_at->diffForHumans() }}</td>
                                        <td>{{ \App\Models\Album::PUBLISHERS[$submission->publisher] }}</td>
                                        <td>{{ \App\Models\AlbumSubmission::PUBLISH_STATUSES[$submission->status] }}</td>
                                        <td>
                                            <a href="{{ route('album-submissions.show', [$album->id, $submission->id]) }}">View Logs</a>
                                        </td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </x-datatable.footable>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Check Modal -->
    <div class="modal fade" id="checkCopyright" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Check Copyright</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <input type="hidden" name="id" value="">
                    <div class="input-group mb-3">
                        <div class="custom-file">
                            <input type="file" class="custom-file-input" name="file" id="file" onchange="sendFile();" required>
                            <label class="custom-file-label" for="inputGroupFile02" aria-describedby="inputGroupFileAddon02">Choose file</label>
                        </div>
                        <div class="input-group-append">
                            <span class="input-group-text" id="inputGroupFileAddon02">Upload</span>
                        </div>
                    </div>
                    <form method="post" id="" name="" action="{{ route('song.copyright.save') }}">
                        @csrf
                        <input type="hidden" name="song_id" id="song_id">
                        <span class="text-cener" id="indicator">
                            <p class="text-center" id="test"></p>
                        </span>
                        <div id="response">
                            <span class="text-cener" id="">
                                <p class="text-center" id="">Response</p>
                            </span>
                            <div class="form-group copyright_status">
                                <label for="copyright_status">Copyright Status</label>
                                <textarea name="copyright_status" value="" id="copyright_status" class="form-control form-control-sm" rows="5" readonly></textarea>
                                <small id="copyright_status_note" class="form-text text-muted"></small>
                            </div>
                            <div class="form-group copyright_artist">
                                <label for="copyright_artist">Artist</label>
                                <textarea name="copyright_artist" value="" id="copyright_artist" class="form-control form-control-sm" rows="5" readonly></textarea>
                                <small id="copyright_artist_note" class="form-text text-muted"></small>
                            </div>
                            <div class="form-group copyright_title">
                                <label for="copyright_title">Title</label>
                                <textarea name="copyright_title" value="" id="copyright_title" class="form-control form-control-sm" rows="5" readonly></textarea>
                                <small id="copyright_title_note" class="form-text text-muted"></small>
                            </div>
                            <div class="form-group copyright_album">
                                <label for="copyright_album">Album</label>
                                <textarea name="copyright_album" value="" id="copyright_album" class="form-control form-control-sm" rows="5" readonly></textarea>
                                <small id="copyright_album_note" class="form-text text-muted"></small>
                            </div>
                            <div class="form-group copyright_release_date">
                                <label for="copyright_release_date">Release Date</label>
                                <textarea name="copyright_release_date" value="" id="copyright_release_date" class="form-control form-control-sm" rows="5" readonly></textarea>
                                <small id="release_date_note" class="form-text text-muted"></small>
                            </div>
                            <div class="form-group copyright_label">
                                <label for="copyright_label">Label</label>
                                <textarea name="copyright_label" value="" id="copyright_label" class="form-control form-control-sm" rows="5" readonly></textarea>
                                <small id="label_note" class="form-text text-muted"></small>
                            </div>
                            <div class="form-group copyright_time_code">
                                <label for="copyright_time_code">Time Code</label>
                                <textarea name="copyright_time_code" value="" id="copyright_time_code" class="form-control form-control-sm" rows="5" readonly></textarea>
                                <small id="time_code_note" class="form-text text-muted"></small>
                            </div>
                            <div class="form-group copyright_song_link">
                                <label for="copyright_song_link">Song Link</label>
                                <textarea name="copyright_song_link" value="" id="copyright_song_link" class="form-control form-control-sm" rows="5" readonly></textarea>
                                <small id="song_link_note" class="form-text text-muted"></small>
                            </div>

                            <div class="form-group error_details">
                                <label for="error_details">Error Details</label>
                                <textarea name="error_details" value="" id="error_details" class="form-control form-control-sm" rows="5" readonly></textarea>
                                <small id="error_details_note" class="form-text text-muted"></small>
                            </div>
                        </div>
                </div>
                <div class="modal-footer">
                    <button class="btn btn-danger" style="width: 20%;" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary w-75">Save Status</button>
                </div>

                </form>
            </div>
        </div>
    </div>

    <!-- Show Modal -->
    <div class="modal fade" id="showCopyright" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Check Copyright</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div id="show_response">
                        <span class="text-cener" id="">
                            <p class="text-center" id="">Response</p>
                        </span>
                        <div class="form-group show_copyright_status">
                            <label for="show_copyright_status">Copyright Status</label>
                            <textarea name="show_copyright_status" value="" id="show_copyright_status" class="form-control form-control-sm" rows="3" readonly></textarea>
                            <small id="show_copyright_status_note" class="form-text text-muted"></small>
                        </div>
                        <div class="form-group show_copyright_artist">
                            <label for="show_copyright_artist">Artist</label>
                            <textarea name="show_copyright_artist" value="" id="show_copyright_artist" class="form-control form-control-sm" rows="3" readonly></textarea>
                            <small id="show_copyright_artist_note" class="form-text text-muted"></small>
                        </div>
                        <div class="form-group show_copyright_title">
                            <label for="show_copyright_title">Title</label>
                            <textarea name="show_copyright_title" value="" id="show_copyright_title" class="form-control form-control-sm" rows="3" readonly></textarea>
                            <small id="show_copyright_title_note" class="form-text text-muted"></small>
                        </div>
                        <div class="form-group show_copyright_album">
                            <label for="show_copyright_album">Album</label>
                            <textarea name="show_copyright_album" value="" id="show_copyright_album" class="form-control form-control-sm" rows="3" readonly></textarea>
                            <small id="show_copyright_album_note" class="form-text text-muted"></small>
                        </div>
                        <div class="form-group show_copyright_release_date">
                            <label for="show_copyright_release_date">Release Date</label>
                            <textarea name="show_copyright_release_date" value="" id="show_copyright_release_date" class="form-control form-control-sm" rows="3" readonly></textarea>
                            <small id="show_release_date_note" class="form-text text-muted"></small>
                        </div>
                        <div class="form-group show_copyright_label">
                            <label for="show_copyright_label">Label</label>
                            <textarea name="show_copyright_label" value="" id="show_copyright_label" class="form-control form-control-sm" rows="3" readonly></textarea>
                            <small id="show_label_note" class="form-text text-muted"></small>
                        </div>
                        <div class="form-group show_copyright_time_code">
                            <label for="show_copyright_time_code">Time Code</label>
                            <textarea name="show_copyright_time_code" value="" id="show_copyright_time_code" class="form-control form-control-sm" rows="3" readonly></textarea>
                            <small id="show_time_code_note" class="form-text text-muted"></small>
                        </div>
                        <div class="form-group show_copyright_song_link">
                            <label for="show_copyright_song_link">Song Link</label>
                            <textarea name="show_copyright_song_link" value="" id="show_copyright_song_link" class="form-control form-control-sm" rows="3" readonly></textarea>
                            <small id="show_song_link_note" class="form-text text-muted"></small>
                        </div>

                        <div class="form-group show_error_details">
                            <label for="show_error_details">Error Details</label>
                            <textarea name="show_error_details" value="" id="show_error_details" class="form-control form-control-sm" rows="5" readonly></textarea>
                            <small id="show_error_details_note" class="form-text text-muted"></small>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button class="btn btn-danger" style="width: 20%;" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary w-75">Save Status</button>
                </div>

                </form>
            </div>
        </div>
    </div>
@endsection


@push('page_scripts')
    <script>
        function copyToClipboard(element) {
            var $temp = $("<input>");
            $("body").append($temp);
            $temp.val($(element).text()).select();
            document.execCommand("copy");
            $temp.remove();
            alert("URL copied successfully");
        }
    </script>
    <script src="https://code.jquery.com/jquery-3.6.0.js" integrity="sha256-H+K7U5CnXl1h5ywQfKtSj8PCmoN9aaq30gDh27Xc0jk=" crossorigin="anonymous"></script>
    <script src="https://cdn.plyr.io/3.6.12/plyr.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/fancybox/3.5.7/jquery.fancybox.min.js"></script>
    <script>
        const player = [];
        @foreach($album->songs as $song)
             player.push(new Plyr('#player_{{ $song->id }}'));
        @endforeach

        $("a.fancybox").fancybox();

        function sendFile() {
            var fd = new FormData();
            var files = $('#file')[0].files[0];
            fd.append('file', files);
            $.ajax({
                url: 'https://enterprise.audd.io/?api_token=cbf8f02634a3173749897f39089e36b3',
                type: 'post',
                data: fd,
                contentType: false,
                processData: false,
                beforeSend: function () {
                    $('#test').text('Checking.....');
                    $('#response').hide();
                    $('.error_details').hide();
                },
                success: function (response) {
                    if (response != 0) {
                        $('#test').text('');
                        let obj = response;
                        console.log(response);
                        let error_msg = 'Not Found'
                        let status = obj.status;
                        let score = 0;
                        let total_score = 0;
                        let average = 0;
                        let count = 1;
                        let check = 0;
                        if (obj.status == 'success') {
                            var arr1 = [];
                            var arr2 = [];
                            var arr3 = [];
                            var arr4 = [];
                            var arr5 = [];
                            var arr6 = [];
                            var arr7 = [];

                            $.each(obj.result, function (key, value) {
                                $.each(obj.result[key].songs, function (song_count, songs) {
                                    $.each(obj.result[key].songs[song_count], function (score_count, score) {
                                        score = obj.result[key].songs[song_count].score;
                                        total_score += score;
                                        count += 1;
                                        arr1.push(obj.result[key].songs[song_count].artist);
                                        arr2.push(obj.result[key].songs[song_count].title);
                                        arr3.push(obj.result[key].songs[song_count].album);
                                        arr4.push(obj.result[key].songs[song_count].release_date);
                                        arr5.push(obj.result[key].songs[song_count].label);
                                        arr6.push(obj.result[key].songs[song_count].timecode);
                                        arr7.push(obj.result[key].songs[song_count].song_link);
                                    });
                                })
                            });
                            $.each($.unique(arr1), function (key, value1) {
                                $('#copyright_artist').val($('#copyright_artist').val() + value1 + ';\r\n');
                            });
                            $.each($.unique(arr2), function (key, value2) {
                                $('#copyright_title').val($('#copyright_title').val() + value2 + ';\r\n');
                            });
                            $.each($.unique(arr3), function (key, value3) {
                                $('#copyright_album').val($('#copyright_album').val() + value3 + ';\r\n');
                            });
                            $.each($.unique(arr4), function (key, value4) {
                                $('#copyright_release_date').val($('#copyright_release_date').val() + value4 + ';\r\n');
                            });
                            $.each($.unique(arr5), function (key, value5) {
                                $('#copyright_label').val($('#copyright_label').val() + value5 + ';\r\n');
                            });
                            $.each($.unique(arr6), function (key, value6) {
                                $('#copyright_time_code').val($('#copyright_time_code').val() + value6 + ';\r\n');
                            });
                            $.each($.unique(arr7), function (key, value7) {
                                $('#copyright_song_link').val($('#copyright_song_link').val() + value7 + ';\r\n');
                            });
                            average = total_score / count;
                            $('#copyright_status').val(status + '(' + average + ')');
                            $('.copyright_artist').show();
                            $('.copyright_title').show();
                            $('.copyright_album').show();
                            $('.copyright_song_link').show();
                            $('.copyright_label').show();
                            $('.copyright_time_code').show();
                            $('.copyright_release_date').show();
                        } else {
                            $('.copyright_artist').hide();
                            $('.copyright_title').hide();
                            $('.copyright_album').hide();
                            $('.copyright_release_date').hide();
                            $('.copyright_label').hide();
                            $('.copyright_time_code').hide();
                            $('.copyright_song_link').hide();
                            $('#error_details').val(obj.error.error_code + ' - ' + obj.error.error_message);
                            $('.error_details').show();
                        }

                        $('#response').show();
                    }
                },
                error: function (err) {
                    let error = err;
                    console.log(error);
                },
            });
        }
    </script>
    <script>
        $('.checkBtn').click(function () {
            $('#response').hide();
            $('.error_details').hide();
        });
    </script>
    <script>
        $(document).ready(function () {
            let modal = $('#show_modal');
            modal.click(function () {
                $('#show_copyright_status').val($(this).data('show_copyright_status'));
                $('#show_copyright_artist').val($(this).data('show_copyright_artist'));
                $('#show_copyright_title').val($(this).data('show_copyright_title'));
                $('#show_copyright_album').val($(this).data('show_copyright_album'));
                $('#show_copyright_release_date').val($(this).data('show_copyright_release_date'));
                $('#show_copyright_label').val($(this).data('show_copyright_label'));
                $('#show_copyright_time_code').val($(this).data('show_copyright_time_code'));
                $('#show_copyright_song_link').val($(this).data('show_copyright_song_link'));
                $('#show_error_details').val($(this).data('show_error_details'));
            });
        });
    </script>
    <script>
        $(document).ready(function () {
            let modal = $('#check_copyright');
            modal.click(function () {
                $('#song_id').val($(this).data('song_id'));
            });
        });
    </script>

@endpush
