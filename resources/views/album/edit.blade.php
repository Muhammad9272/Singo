@extends('layouts.app')

@push('page_css')
    <link rel="stylesheet" href="{{ asset('assets/vendor/icheck-bootstrap-3.0.1/icheck-bootstrap.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/vendor/icheck-bootstrap-3.0.1/icheck-bootstrap.min.css') }}">
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet"/>
    <link href="https://unpkg.com/filepond@^4/dist/filepond.css" rel="stylesheet"/>
    <link href="https://unpkg.com/filepond-plugin-image-preview/dist/filepond-plugin-image-preview.css" rel="stylesheet"/>
    
    <!-- Intro JS CDN -->
    <link href="https://cdn.bootcdn.net/ajax/libs/intro.js/5.0.0/introjs.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/intro.js@5.0.0/themes/introjs-flattener.css" rel="stylesheet">
    <style>
        .introjs-tooltip {
            min-width: 300px;
        }
        a:not([href]):not([tabindex]).introjs-button:hover {
            color: #fff
        }
    </style>
    <!-- End Intro JS CDN -->

    <x-styles.select2/>

    <style>
        /*tooltip Box*/
        .con-tooltip {
            position: relative;
            display: inline-block;
            transition: all 0.3s ease-in-out;
            cursor: default;
        }

        .tooltip {
            visibility: hidden;
            z-index: 999;
            opacity: .40;
            width: 300px;
            padding: 5px 30px;
            background: #333;
            color: #E086D3;
            position: absolute;
            top: -140%;
            left: 150%;
            border-radius: 9px;
            font: 16px;
            transform: translateY(9px);
            transition: all 0.3s ease-in-out;
            box-shadow: 0 0 3px rgba(56, 54, 54, 0.86);
        }


        /* tooltip  after*/
        .tooltip::after {
            content: " ";
            width: 0;
            height: 0;
            border-style: solid;
            border-width: 12px 12.5px 0 12.5px;
            border-color: #333 transparent transparent transparent;
            position: absolute;
            left: 40%;
        }

        .con-tooltip:hover .tooltip {
            visibility: visible;
            transform: translateY(-10px);
            opacity: 1;
            transition: .3s linear;
            animation: odsoky 1s ease-in-out infinite alternate;
        }

        @keyframes odsoky {
            0% {
                transform: translateY(6px);
            }

            100% {
                transform: translateY(1px);
            }

        }

        .left:hover {
            transform: translateX(-6px);
        }

        .top:hover {
            transform: translateY(-6px);
        }

        .bottom:hover {
            transform: translateY(6px);
        }

        .right:hover {
            transform: translateX(6px);
        }

        .left .tooltip {
            top: -20%;
            left: -170%;
        }

        .left .tooltip::after {
            top: 40%;
            left: 90%;
            transform: rotate(-90deg);
        }

        .bottom .tooltip {
            top: 115%;
            left: -20%;
        }

        .bottom .tooltip::after {
            top: -17%;
            left: 40%;
            transform: rotate(180deg);
        }

        .right .tooltip {
            top: -20%;
            left: 115%;
        }

        .right .tooltip::after {
            top: 40%;
            left: -12%;
            transform: rotate(90deg);
        }

        .collapsing {
            position: relative;
            height: 0;
            overflow: hidden;
            -webkit-transition-property: height, visibility;
            transition-property: height, visibility;
            -webkit-transition-duration: 0.55s;
            transition-duration: 0.55s;
            -webkit-transition-timing-function: ease;
            transition-timing-function: ease;
        }

        .fallout {
            transition: 0.4s !important;
        }


        .filepond--drop-label {
            border: 1px solid #ced4da;
            border-radius: 7px;
        }

        @media (max-width: 575.98px) {
            .url {
                margin-top: 10px;
            }

            .rs_header {
                display: flex;
                flex-wrap: wrap;
                justify-content: center;
            }

            .left_content {
                margin: 0px 0px 5px 0px !important;
            }

            .left_content span {
                margin: 0px 0px 0px 0px !important;
            }

            .right_button {
                width: 100% !important;
                margin: 0px 0px 5px 0px !important;
            }
        }

        @media (max-width: 767.98px) {
            .url {
                margin-top: 10px;
            }
        }

    </style>
    <script src="https://code.jquery.com/jquery-3.5.0.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.3.1/jquery.js"></script>
    <script src="https://unpkg.com/filepond/dist/filepond.min.js"></script>
    <script src="https://unpkg.com/filepond-plugin-image-preview/dist/filepond-plugin-image-preview.js"></script>
    <script src="https://unpkg.com/filepond-plugin-image-validate-size/dist/filepond-plugin-image-validate-size.js"></script>
    <script src="https://unpkg.com/filepond-plugin-file-validate-type/dist/filepond-plugin-file-validate-type.js"></script>
    <script src="https://unpkg.com/filepond-plugin-image-transform/dist/filepond-plugin-image-transform.js"></script>
    <script src="https://unpkg.com/filepond-plugin-file-metadata/dist/filepond-plugin-file-metadata.js"></script>


@endpush

@section('content')
    @php
        $exp = "";
        $inst = "";
        $selected = "";
    @endphp
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12"> 
                <div class="card">
                    <div class="card-header">
                        Update album info
                        <div class="float-right">
                            <button class="btn btn-sm btn-info" type="button" id="intro"><i class="fa fa-question-circle"></i>&nbsp;&nbsp;How to?</button>
                            <button class="btn btn-sm btn-secondary" type="button" id="hints"><i class="fa fa-question-circle"></i>&nbsp;&nbsp;Hints?</button>
                        </div>
                    </div>
                    <div class="card-body">
                        @if ($errors->any())
                            <div class="alert alert-danger">
                                <ul>
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif
                        @error('numsong')
                        <div class="alert alert-danger">{{ $message }}</div>
                        @enderror
                        @error('songs.*')
                        <div class="alert alert-danger">You did not upload a valid song. Please try again</div>
                        @enderror
                        <form method="post" enctype="multipart/form-data" action="{{ route('album.edit.store') }}">
                            @csrf
                            <input type="hidden" value="{{ $album->id }}" name="album_id">
                            <h5>General Information</h5>
                            <div class="form-row">
                                <div class="form-group col-md-6">
                                    <label for="name">
                                        Name
                                        <sup class="text-danger">*</sup>
                                    </label>
                                    <input type="text" id="name" value="{{ $album->title }}" name="name" class="form-control @error('name') is-invalid @enderror" required placeholder="Album name">
                                    @error('name')
                                        <span class="error invalid-feedback">{{ $message }}</span>
                                    @enderror
                                </div>
                                <div class="form-group col-md-6">
                                    <label for="genre">
                                        Genre
                                        <sup class="text-danger">*</sup>
                                    </label>
                                    <select name="genre" class="custom-select @error('genre') is-invalid @enderror" id="genre">
                                        <option value="{{ $album->genre->id }}" hidden
                                                selected>{{ $album->genre->name }}</option>
                                        @foreach($genres as $genre)
                                            <option value="{{ $genre->id }}">{{ $genre->name }}</option>
                                        @endforeach
                                    </select>
                                    @error('error')
                                    <span class="error invalid-feedback">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="store">Store</label>
                                <select class="js-example-basic-single form-control" name="store[]" multiple="multiple" id="store">
                                    @foreach($store as $st)
                                        @foreach($user_store as $u_st)
                                            @if($u_st->store_id == $st->id )
                                                @php $selected = "selected"; @endphp
                                            @endif
                                        @endforeach

                                        @if($selected == "selected" )
                                            <option value="{{ $st->id }}" selected="selected"> {{ $st->title }}</option>
                                        @else
                                            <option value="{{ $st->id }}"> {{ $st->title }}</option>
                                        @endif
                                        @php $selected = null; @endphp

                                    @endforeach
                                </select>
                                @error('error')
                                <span class="error invalid-feedback">{{ $message }}</span>
                                @enderror
                            </div>

                            <div class="form-row">
                                <div class="form-group col-md-4">
                                    <label for="date">
                                        Release Date
                                        <sup class="text-danger">*</sup>
                                        <small class="text-danger">(The date must be a date after today.)</small>
                                    </label>
                                    <input type="date" id="date" value="{{date('Y-m-d', strtotime($album->release))}}" name="date" class="form-control @error('date') is-invalid @enderror" required>
                                    @error('date')
                                    <span class="error invalid-feedback">{{ $message }}</span>
                                    @enderror
                                </div>

                                <div class="form-group col-md-4">
                                    <label for="upc">UPC </label>
                                    <input type="text" id="upc" value="{{$album->upc}}" maxlength="13" name="upc" class="form-control" placeholder="If you don't have one leave this blank">
                                </div>

                                <div class="form-group col-md-4">
                                    <label for="album_language">Language
                                        <sup class="text-danger">*</sup>
                                    </label>
                                    <x-form.album-language :name="'album_language_id'" :selected="$album->language_id" required/>
                                </div>
                            </div>
                            <div class="">
                                <label for="cover">Upload Cover
                                    <sup class="text-danger">*</sup>

                                    <span class="text-danger" style="position:relative;z-index:2;">
                                        <!-- Top tooltip-->
                                        <div class="con-tooltip top">
                                            <i class="fas fa-question"></i>
                                            <div class="tooltip ">
                                                <span class="text-info">
                                                    The cover should be at least 3000x3000px you can use
                                                    <a href="https://canva.com" target="_blank"> Canva</a>
                                                    to create easy a cover in correct format.
                                                </span>
                                            </div>
                                        </div>
                                    </span>
                                </label>
                                <div class="">
                                    <p class="text-danger"><a href="{{ route('album.download', $album->id) }}">{{$album->cover}}</a> <br> If you want to update cover click Browse</p>
                                    <input type="file" class="" id="cover" data-name="cover" name="cover">
                                    @error('cover')
                                    <span class="error invalid-feedback">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                            <script>
                                FilePond.registerPlugin(FilePondPluginImagePreview);
                                FilePond.registerPlugin(FilePondPluginImageValidateSize);
                                FilePond.registerPlugin(FilePondPluginFileValidateType);
                                FilePond.registerPlugin(FilePondPluginImageTransform);

                                // Get a reference to the file input element
                                const inputElement_cover = document.getElementById('cover');

                                // Create a FilePond instance
                                const pond_cover = FilePond.create(inputElement_cover, {
                                    imageValidateSizeMinWidth: 3000,
                                    imageValidateSizeMinWidth: 3000,
                                    imageValidateSizeMaxWidth: 3000,
                                    imageValidateSizeMinHeight: 3000,
                                    acceptedFileTypes: ['image/*'],
                                    imageTransformOutputMimeType: 'image/jpeg',

                                });

                                var name = inputElement_cover.dataset.name;
                                var _url = ("{{ route('ajax.upload', ['name']) }}");
                                var __url = _url.replace('name', name);

                                pond_cover.setOptions({
                                    server: {
                                        url: __url,
                                        headers: {
                                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                                        }
                                    },
                                });

                                for (let el of document.querySelectorAll('.filepond--credits')) el.style.visibility = 'hidden';

                            </script>


                            <div class="form-group">
                                <label for="spo_url">Spotify Artist URL
                                    <span class="text-danger" style="position:relative;z-index:2;">
                                        <div class="con-tooltip top">
                                            <i class="fas fa-question"></i>
                                            <div class="tooltip ">
                                                <span class="text-info">Please check URL before submit</span>
                                                <br>
                                                <span class="text-info">Please enter the full link. (eg:https://www.google.com/)
                                                </span>
                                            </div>
                                        </div>
                                    </span>
                                </label>
                                <div class="form-row">
                                    <div class="col-md-10">
                                        <input type="text" value="{{$album->spotify_url}}" id="spo_url" name="spo_url"
                                               class="form-control @error('spo_url') is-invalid @enderror"
                                               placeholder="If you don't have one leave this blank">
                                    </div>
                                    <div class="col-md-2 ">
                                        <a href="{{$album->spotify_url}}" id="spo_url_set" class="btn btn-primary w-100"
                                           target="_blank">Check URL</a>
                                    </div>
                                </div>
                                @error('spo_url')
                                <span class="error invalid-feedback">{{ $message }}</span>
                                @enderror

                            </div>
                            <div class="form-group">
                                <label for="apl_url">Apple Artist URL 
                                    <span class="text-danger" style="position:relative;z-index:2;">
                                        <!-- Top tooltip-->
                                        <div class="con-tooltip top">
                                            <i class="fas fa-question"></i>
                                            <div class="tooltip ">
                                                <span class="text-info">Please check URL before submit</span>
                                                <br>
                                                <span class="text-info">Please enter the full link. (eg:https://www.google.com/)</span>
                                            </div>
                                        </div>
    
                                    </span>
                                </label>
                                <div class="form-row">
                                    <div class="col-md-10">
                                        <input type="text" value="{{ $album->apple_music_url }}" id="apl_url"
                                               name="apl_url"
                                               class="form-control @error('apl_url') is-invalid @enderror"
                                               placeholder="If you don't have one leave this blank">
                                    </div>
                                    <div class="col-md-2">
                                        <a href="{{ $album->apple_music_url }}" id="apl_url_set"
                                           class="btn btn-primary w-100" target="_blank">Check URL</a>
                                    </div>
                                </div>
                                @error('apl_url')
                                <span class="error invalid-feedback">{{ $message }}</span>
                                @enderror
                            </div>
                            <h5>Songs</h5>
                            <input type="hidden" autocomplete="off" value="{{count($album->songs()->get())}}"
                                   id="songcount" name="songcount">
                            <input type="hidden" autocomplete="off" value="{{count($album->songs()->get())}}"
                                   id="idcontroll" name="idcount">
                            <button id="add" type="button" class="btn btn-sm btn-success mb-2"><i
                                    class="fas fa-plus"></i> Add song
                            </button>
                            <div id="accordion">
                                @php
                                    $id_ctnt = 0;
                                @endphp
                                @foreach($album->songs()->get() as $song)
                                    <input type="hidden" name="song_id{{$id_ctnt}}" value="{{ $song->id }}">
                                    <div class="card" id="song{{$id_ctnt}}">
                                        <div class="card-header rs_header" id="heading{{$id_ctnt}}">
                                            <span class="float-left left_content">
                                                <span class="ml-4"
                                                      id="song_name{{$id_ctnt}}">Song - {{$id_ctnt + 1}}</span>
                                            </span>
                                            <span class="float-right">
                                                <button type="button"
                                                        class="btn-danger btn btn-sm removeBtn mr-2 right_button"
                                                        id="remove{{$id_ctnt}}">
                                                    <i class="fas fa-trash"></i> Remove
                                                </button>
                                                <button type="button" class="btn-info btn btn-sm toggle right_button"
                                                        data-toggle="collapse" data-target="#collapse{{$id_ctnt}}"
                                                        aria-expanded="true" aria-controls="collapse{{$id_ctnt}}">
                                                    <i class="fas fa-minus mr-2"></i>Minimize/Maximize
                                                </button>

                                            </span>

                                        </div>

                                        <div class="card-body collapse show p-0" id="collapse{{$id_ctnt}}"
                                             aria-labelledby="heading{{$id_ctnt}}" data-parent="#accordion">
                                            <div class="clearfix p-3">
                                                <div class="form-group">
                                                    <label for="name_{{$id_ctnt}}">
                                                        Name
                                                        <sup class="text-danger">*</sup>
                                                    </label>
                                                    <input type="text" id="name_{{$id_ctnt}}" name="title{{$id_ctnt}}"
                                                           value="{{ $song->title }}" class="form-control" required
                                                           placeholder="Song name" onkeyup="song_name_new(this.id)">
                                                </div>
                                                <div class="form-group">
                                                    <label for="composer{{$id_ctnt}}">
                                                        Composer
                                                        <sup class="text-danger">*</sup>
                                                    </label>
                                                    <input type="text" id="composer{{$id_ctnt}}"
                                                           value="{{ $song->composer }}" name="composer{{$id_ctnt}}"
                                                           class="form-control" required placeholder="Song composer">
                                                </div>
                                                <div class="form-group mb-0" id="addfartist{{$id_ctnt}}">
                                                    @forelse($song->fartist()->get() as $key_1 => $fa)
                                                        <div class="mt-2 fallout" id="fid_{{$id_ctnt}}_{{$key_1+1}}">
                                                            <label>Featured Artist - {{$key_1+1}}</label>

                                                            <input type="text" value="{{$fa->artist_name}}"
                                                                   class=" form-control"
                                                                   placeholder="Featured Artist - {{$key_1+1}}"
                                                                   id="fartist_name_{{$id_ctnt}}_{{$key_1+1}}"
                                                                   name="fartist_name_{{$id_ctnt}}_{{$key_1+1}}">
                                                            <a href="javascript:void(0)" class="float-right text-danger"
                                                               id="dlt_fartist_{{$id_ctnt}}_{{$key_1+1}}"
                                                               onclick="dlt_fartist(this.id)">
                                                                - Remove Artist
                                                            </a>
                                                        </div>
                                                    @empty
                                                    @endforelse
                                                </div>
                                                <div class="clearfix ml-2 mb-2">
                                                    <input type="hidden" value="{{count($song->fartist()->get()) - 1}}"
                                                           id="count_artist_{{$id_ctnt}}">
                                                    <input type="hidden" value="{{count($song->fartist()->get())}}"
                                                           id="total_artist_{{$id_ctnt}}"
                                                           name="total_artist{{$id_ctnt}}">
                                                    <input type="hidden" value="{{count($song->fartist()->get()) - 1}}"
                                                           id="fartist_{{$id_ctnt}}">
                                                    <a href="javascript:void(0)" id="new_fartist_{{$id_ctnt}}"
                                                       class="float-left fallout" onclick="new_fartist(this.id)">
                                                        + Featured Artist
                                                    </a><br>
                                                    <small>You can add one or more featured artist if you want</small>
                                                </div>
                                                <div class="form-row">
                                                    <div class="form-group col-md-6">
                                                        <label for="lang{{$id_ctnt}}">
                                                            Language
                                                            <sup class="text-danger">*</sup>
                                                        </label>
                                                        <x-form.song-audio-locale name="language{{$id_ctnt}}" :selected="$song->language" id="audio_locale{{ $id_ctnt }}" required />
                                                    </div>
                                                    <div class="form-group col-md-6">
                                                        <label for="isrc{{$id_ctnt}}">ISRC</label>
                                                        <input type="text" id="isrc{{$id_ctnt}}" name="isrc{{$id_ctnt}}"
                                                            value="{{ $song->isrc }}" class="form-control"
                                                            placeholder=" ISRC of the song, leave blank if you dont have one">
                                                    </div>
                                                </div>
                                                <div class="form-group">
                                                    <div class="icheck-primary">
                                                        <input type="radio" id="not_explicit{{$id_ctnt}}" name="radio{{$id_ctnt}}" class="form-check-input" placeholder="" value="not_explicit">
                                                        <label for="not_explicit{{$id_ctnt}}" class="form-check-label">Not Explicit Content</label>
                                                    </div>
                                                </div>
                                                <div class="form-group">
                                                    <div class="icheck-primary">
                                                        <input type="radio" id="explicit{{$id_ctnt}}" name="radio{{$id_ctnt}}" class="form-check-input" placeholder="" value="explicit" @if ($song->isExplicit)  checked="checked" @endif>
                                                        <label for="explicit{{$id_ctnt}}" class="form-check-label">Explict Content</label>
                                                    </div>
                                                </div>
                                                <div class="form-group">
                                                    <div class="icheck-primary">
                                                        <input type="radio" id="instrumental{{$id_ctnt}}" name="radio{{$id_ctnt}}" class="form-check-input" placeholder="" value="instrumental" @if ($song->isInstrumental)  checked="checked" @endif>
                                                        <label for="instrumental{{$id_ctnt}}" class="form-check-label">Instrumental</label>
                                                    </div>
                                                </div>
                                                <div class="clearfix mb-2">
                                                    <a style="margin-left: 30px;" href="javascript:void(0)"
                                                       id="clear_radio_{{$id_ctnt}}" class="float-left fallout"
                                                       onclick="clear_radio(this.id)">
                                                        Resest Section
                                                    </a>
                                                </div>
                                                <div class="">
                                                    <label>Upload song</label>
                                                    <p class="text-danger"><a href="{{ route('song.download', $song->id) }}">{{$song->songFile}}</a> <br> If you want to update song click Browse</p>
                                                    <div class="">
                                                        <input type="file" value="" name="songs{{$id_ctnt}}"
                                                               id="customFile{{$id_ctnt}}"
                                                               data-name="songs{{$id_ctnt}}">
                                                        <span class="text-sm">Allowed formats: .flac, .wav</span>
                                                        <p class="text-sm">If your files in other format, go to this website to convert it to wav: <a href="https://www.freeconvert.com/wav-converter" target="_blank">freeconvert.com</a></p>
                                                    </div>
                                                </div>

                                                <script>
                                                    FilePond.registerPlugin(FilePondPluginFileValidateType);
                                                    FilePond.registerPlugin(FilePondPluginFileMetadata);

                                                    var inputElement_{{ $id_ctnt }} = document.getElementById('customFile{{ $id_ctnt }}');

                                                    // Create a FilePond instance
                                                    const pond_{{ $id_ctnt }} = FilePond.create(inputElement_{{ $id_ctnt }}, {
                                                        acceptedFileTypes: ['audio/*']
                                                    });
                                                    var name = inputElement_{{ $id_ctnt }}.dataset.name;
                                                    var _url = ("{{ route('ajax.upload', ['name']) }}");
                                                    var __url = _url.replace('name', name);

                                                    pond_{{ $id_ctnt }}.setOptions({
                                                        server: {
                                                            url: __url,
                                                            headers: {
                                                                'X-CSRF-TOKEN': '{{ csrf_token() }}'
                                                            },
                                                        },
                                                    });

                                                    for (let el of document.querySelectorAll('.filepond--credits')) el.style.visibility = 'hidden';
                                                </script>


                                            </div>
                                        </div>
                                    </div>
                                    @php
                                        $id_ctnt = $id_ctnt + 1;
                                    @endphp
                                @endforeach

                            </div>

                            <button type="submit" class="btn btn-sm btn-success"><i class="fas fa-upload"></i> Update Album
                            </button>
                            <button id="add_duplicate" type="button" class="btn btn-sm btn-success"><i
                                    class="fas fa-plus"></i> Add song
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('page_scripts')

    <script>
        @if(!auth()->user()->isPremium && !auth()->user()->isAdmin())
        var currentTime = new Date();
        currentTime.setDate(currentTime.getDate() + 14);
        window.$("#date").attr('min', currentTime.toISOString().substr(0, 10));
        @endif

        window.$("#accordion").on("click", ".removeBtn", (event) => {
            $("#song" + window.$(event.target).attr("id").replace("remove", "")).remove();
            var count = parseInt($("#songcount").val());
            $("#songcount").val(count - 1);
        });

        window.$("#add,#add_duplicate").on("click", event => {
            var count = parseInt(window.$("#songcount").val());
            var id_ctnt = parseInt(window.$("#idcontroll").val());
            window.$("#accordion").append(
                `
                <div class="card" id="song${id_ctnt}">
                    <div class="card-header rs_header"  id="heading${id_ctnt}">
                        <span class = "float-left left_content">
                            <span class = "ml-4" id = "song_name${id_ctnt}" >Song - ${id_ctnt + 1}</span>
                        </span>
                        <span class = "float-right">
                            <button type="button" class="btn-danger btn btn-sm removeBtn mr-2 right_button" id="remove${id_ctnt}">
                                <i class="fas fa-trash"></i> Remove
                            </button>
                            <button type="button" class="btn-info btn btn-sm toggle right_button" data-toggle="collapse" data-target="#collapse${id_ctnt}" aria-expanded="true" aria-controls="collapse${id_ctnt}">
                                <i class="fas fa-minus mr-2" ></i>Minimize/Maximize
                            </button>

                        </span>

                    </div>

                    <div class="card-body collapse show p-0" id="collapse${id_ctnt}" aria-labelledby="heading${id_ctnt}" data-parent="#accordion">
                        <div class="clearfix p-3">
                            <div class="form-group">
                                <label for="name${id_ctnt}">
                                    Name
                                    <sup class="text-danger">*</sup>
                                </label>
                                <input type="text" id="name_${id_ctnt}" name="title${id_ctnt}" class="form-control" required placeholder="Song name"  onkeyup="song_name_new(this.id)">
                            </div>
                            <div class="form-group">
                                <label for="composer${id_ctnt}">
                                    Composer
                                    <sup class="text-danger">*</sup>
                                </label>
                                <input type="text" id="composer${id_ctnt}" name="composer${id_ctnt}" class="form-control" required placeholder="Song composer">
                            </div>
                            <div class="form-group mb-0" id = "addfartist${id_ctnt}">

                            </div>
                            <div class="clearfix ml-2 mb-2">
                                <input type = "hidden" value = "0" id = "total_artist_${id_ctnt}" name = "total_artist${id_ctnt}">
                                <input type = "hidden" value = "" id = "fartist_${id_ctnt}">
                                <a href="javascript:void(0)" id="new_fartist_${id_ctnt}" class="float-left fallout" onclick="new_fartist(this.id)">
                                    + Featured Artist
                                </a><br>
                                <small>You can add one or more featured artist if you want</small>
                            </div>
                                            
                            <div class="form-row">
                                <div class="form-group col-md-6">
                                    <label for="lang${id_ctnt}">
                                        Language
                                        <sup class="text-danger">*</sup>
                                    </label>
                                        <x-form.song-audio-locale name="language${id_ctnt}" id="audio_locale${id_ctnt}" required />
                                </div>
                                <div class="form-group col-md-6">
                                    <label for="isrc${id_ctnt}">ISRC</label>
                                    <input type="text" id="isrc${id_ctnt}" name="isrc${id_ctnt}" class="form-control"  placeholder=" ISRC of the song, leave blank if you dont have one">
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="icheck-primary">
                                    <input type="radio" id="not_explicit${id_ctnt}" name="songs[${id_ctnt}][radio]" class="form-check-input" placeholder="" value = "not_explicit">
                                    <label for="not_explicit${id_ctnt}" class="form-check-label">Not Explicit Content</label>
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="icheck-primary">
                                    <input type="radio" id="explicit${id_ctnt}" name="songs[${id_ctnt}][radio]" class="form-check-input" placeholder="" value = "explicit">
                                    <label for="explicit${id_ctnt}" class="form-check-label">Explicit Content</label>
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="icheck-primary">
                                    <input type="radio" id="instrumental${id_ctnt}" name="songs[${id_ctnt}][radio]" class="form-check-input" placeholder="" value = "instrumental">
                                    <label for="instrumental${id_ctnt}" class="form-check-label">Instrumental</label>
                                </div>
                            </div>
                            <div class="clearfix mb-2">
                                <a style="margin-left: 30px;" href="javascript:void(0)" id="clear_radio_${id_ctnt}" class="float-left fallout" onclick="clear_radio(this.id)">
                                    Resest Section
                                </a>
                            </div>
                            <div class="">
                                <label>Upload song</label>
                                <div class="">
                                    <input type="file" accept=".wav,.flac" name="songs${id_ctnt}" data-name="songs${id_ctnt}" id="customFile${id_ctnt}">
                                    <span class="text-sm">Allowed formats: .flac, .wav</span>
                                    <p class="text-sm">If your files in other format, go to this website to convert it to wav: <a href="https://www.freeconvert.com/mp3-to-wav" target="_blank">freeconvert.com</a></p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                `
            );
            window.$("#songcount").val(count + 1);
            window.$("#idcontroll").val(id_ctnt + 1);
            bsCustomFileInput.init();

            FilePond.registerPlugin(FilePondPluginFileValidateType);
            FilePond.registerPlugin(FilePondPluginFileMetadata);

            var inputElement = document.getElementById('customFile' + id_ctnt);

            // Create a FilePond instance
            const pond = FilePond.create(inputElement, {
                acceptedFileTypes: ['audio/*']
            });
            var name = inputElement.dataset.name;
            var _url = ("{{ route('ajax.upload', ['name']) }}");
            var __url = _url.replace('name', name);

            pond.setOptions({
                server: {
                    url: __url,
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                },
            });

            for (let el of document.querySelectorAll('.filepond--credits')) el.style.visibility = 'hidden';

        });
    </script>
    <script>
        var inputs = $('#apl_url');
        var url = $('#apl_url_set');
        inputs.keyup(function () {
            var new_url = inputs.val();
            url.attr("href", new_url);

        });
    </script>
    <script>
        var inputs_2 = $('#spo_url');
        var url_2 = $('#spo_url_set');
        inputs_2.keyup(function () {
            var new_url_2 = inputs_2.val();
            url_2.attr("href", new_url_2);

        });
    </script>
    <script>
        function song_name_new(id) {

            let res_1 = id.split("_");
            var no = res_1[1];
            let song_name_id = "#" + "song_name" + res_1[1];
            let new_val = document.getElementById(id).value;
            $(song_name_id).text(new_val);
        }
    </script>
    <script>
        function new_fartist(id) {

            let res_1 = id.split("_");
            let fcount = 1;
            let fartist_append_id = "#" + "addfartist" + res_1[2];
            let total_id = "#" + "total_artist_" + res_1[2];
            let change = "#count_artist_" + res_1[2];
            let change_val = parseInt(window.$(change).val());
            var new_val = parseInt(window.$(total_id).val());
            // console.log(fartist_append_id);

            window.$(fartist_append_id).append(
                `
                <div class = "mt-2 fallout" id = "fid_${res_1[2]}_${new_val + 1}">
                    <label>Featured Artist - ${new_val + 1}</label>
                    <input type="text" class=" form-control" placeholder="Featured Artist - ${new_val + 1}" id = "fartist_name_${res_1[2]}_${new_val + 1}" name = "fartist_name_${res_1[2]}_${new_val + 1}">
                    <a href="javascript:void(0)" class="float-right text-danger" id="dlt_fartist_${res_1[2]}_${new_val + 1}" onclick="dlt_fartist(this.id)" >
                            - Remove Artist
                    </a>
                </div>
                `);
            window.$(total_id).val(new_val + 1);
            window.$(change).val(change_val + 1);
        }
    </script>
    <script>
        function dlt_fartist(id) {
            let res_1 = id.split("_");
            let dlt_id = "#fid_" + res_1[2] + "_" + res_1[3];
            let change = "#count_artist_" + res_1[2];
            let change_val = parseInt(window.$(change).val());
            window.$(change).val(change_val - 1);
            // console.log(dlt_id);
            $(dlt_id).remove();
        }
    </script>
    <script>
        function clear_radio(id) {
            let res_1 = id.split("_");
            let inst = "instrumental" + res_1[2];
            let exp = "explicit" + res_1[2];
            let not_explicit = "not_explicit" + res_1[2];
            // console.log(res_1);
            document.getElementById(inst).checked = false;
            document.getElementById(exp).checked = false;
            document.getElementById(not_explicit).checked = false;

        }
    </script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <script>
        $(document).ready(function () {
            $('.js-example-basic-single').select2();
        });
    </script>
    <!-- Intro JS CDN & Config -->
    <script src="https://cdn.bootcdn.net/ajax/libs/intro.js/5.0.0/intro.min.js"></script>
    <script>
        $("#hints").click(e => {
            e.preventDefault();
            if($("[id*='name_']").length == 0) {
                $("#add").click();
            }
            $('label[for="name"]').attr('data-hint', 'Here you can set the album/single name you want to appear in streaming stores.<br>Tip: If you want to release a Single please make sure to name the "Album name" field the same as your Song so it will be automatically published as a Single.')
            $('label[for="genre"]').attr('data-hint', 'Please make sure your genre matches the music you publish.')
            $('label[for="store"]').attr('data-hint', 'Here you can select or de-select streaming stores, we always recommend leaving it like it is so your music will be available everywhere for everyone.')
            $('label[for="date"]').attr('data-hint', 'The release date must be a date after today.<br>Depending on your current plan you can choose your release date like this:<br>Free plan: today + 14 days.<br>Basic plan: today + 10 days.<br>Premium plan. Today + 2 days<br>Free plan example: (today) 10. March.2022 + 14 = (release date) 24.March.2022.')
            $('label[for="upc"]').attr('data-hint', 'In 99.99% of the cases, you can leave this empty. We will assign a UPC for free automatically.')
            $('label[for="album_language"]').attr('data-hint', 'Please choose the language of the metadata of your album/single. Example: Your album is named: Fire Bird but you are singing in German, you still need to select English as a Language on this general information because this is regarding the textes you chose on album name/ single name and cover.')
            $('label[for="cover"]').attr('data-hint', 'The information on the artwork should align with the metadata. It is not allowed to have information on the artwork that is not in the metadata. However, it is allowed to have information in the metadata that is not on the artwork (for instance, an additional primary artist or a featuring artist can be credited in the metadata but does not need to also be mentioned on the artwork).')
            $('label[for="spo_url"]').attr('data-hint', 'If you are a new artist and you dont got music on streaming stores already you can ignore this and move to the next step. IF you got music already live on streaming stores you can add your artist URL for Apple Music & Spotify here so we can add your music to the correct artist profile.')
            $('button[id="add"]').attr('data-hint', 'You can press this button as often as you like to add more songs. Please notice; if you want to release just a single (one song) you simply need to press this button one time. If you want to release an album with 7 songs, simply press it 7 times.')
            $('[id*="song"] label[for^="name"]').attr('data-hint', 'Here you need to add the name of your song. (If you want to release a SINGLE please make sure the album name on the general section is the same as this song so we can publish it automatically as a single release).')
            $('[id*="song"] label[for^="composer"]').attr('data-hint', 'Here you can add the composer of the song.')
            $('[id*="song"] a[id^="new_fartist"]').attr('data-hint', 'Here you can add feature artists who worked with you on this song.')
            $('[id*="song"] label[for^="lang"]').attr('data-hint', 'Please select the language of the actual lyrics of your music (if any). For example, You name your song; "Blue Star" - but your vocals on this song are German. In this case, you need to select German as the language here. If your song is Instrumental you need to select the language of the song metadata. Example: Your song is without lyrics and its called "House of Cards" - in this case, you need to select English here because "House of Cards" is English.')
            $('[id*="song"] label[for^="not_explicit"]').attr('data-hint', 'If you are not using any bad wording inside your music you need to select: Not Explicit Content.')
            $('[id*="song"] label[for^="explicit"]').attr('data-hint', 'If you use "bad wording" inside your music you need to select: Explicit Content.')
            $('[id*="song"] label[for^="instrumental"]').attr('data-hint', 'If your music is without any vocals at all you need to select instrumental.')
            $('[id*="song"] div[id^="customFile"]').attr('data-hint', 'Simply Drag & Drop your audio files here, please make sure it is in .wav or .flac format. If you dont got the correct format please us any converter like this one: <a href="https://www.freeconvert.com/wav-converter" target="_blank">freeconvert.com</a>.')
            $('form > button:nth-child(16)').attr('data-hint', 'Once added all the songs, click this button to submit the album.')
            
            introJs().addHints();
        })
        $("#intro").click(e => {
            e.preventDefault();
            if($("[id*='name_']").length == 0) {
                $("#add").click();
            }
            introJs().setOptions({
                steps: [
                    {
                        title: 'General Information',
                        intro: 'Here you need to submit general Information about your release. You can release a single song or an album.'
                    },
                    {
                        title: 'Album Name',
                        element: document.querySelector('#name'),
                        intro: 'Here you can set the album/single name you want to appear in streaming stores.<br>Tip: If you want to release a Single please make sure to name the "Album name" field the same as your Song so it will be automatically published as a Single.'
                    },
                    {
                        title: 'Album Genre',
                        element: document.querySelector('#genre'),
                        intro: 'Please make sure your genre matches the music you publish.',
                        position: 'left'
                    },
                    {
                        title: 'Store',
                        element: document.querySelector('span.select2'),
                        intro: 'Here you can select or de-select streaming stores, we always recommend leaving it like it is so your music will be available everywhere for everyone.'
                    },
                    {
                        title: 'Release date',
                        element: document.querySelector('#date'),
                        intro: 'The release date must be a date after today.<br>Depending on your current plan you can choose your release date like this:<br>Free plan: today + 14 days.<br>Basic plan: today + 10 days.<br>Premium plan. Today + 2 days<br>Free plan example: (today) 10. March.2022 + 14 = (release date) 24.March.2022',
                        position: 'bottom'
                    },
                    {
                        title: 'UPC',
                        element: document.querySelector('#upc'),
                        intro: 'In 99.99% of the cases, you can leave this empty. We will assign a UPC for free automatically'
                    },
                    {
                        title: 'Language',
                        element: document.querySelector('#album_language_id'),
                        intro: 'Please choose the language of the metadata of your album/single. Example: Your album is named: Fire Bird but you are singing in German, you still need to select English as a Language on this general information because this is regarding the textes you chose on album name/ single name and cover.',
                        position: 'left'
                    },
                    {
                        title: 'Cover Picture',
                        element: document.querySelector('#cover'),
                        intro: 'The information on the artwork should align with the metadata. It is not allowed to have information on the artwork that is not in the metadata. However, it is allowed to have information in the metadata that is not on the artwork (for instance, an additional primary artist or a featuring artist can be credited in the metadata but does not need to also be mentioned on the artwork)'
                    },
                    {
                        title: 'Artist URLs',
                        element: document.querySelector('#spo_url'),
                        intro: 'If you are a new artist and you dont got music on streaming stores already you can ignore this and move to the next step. IF you got music already live on streaming stores you can add your artist URL for Apple Music & Spotify here so we can add your music to the correct artist profile.'
                    },
                    {
                        title: 'Add Song',
                        element: document.querySelector('#add'),
                        intro: 'You can press this button as often as you like to add more songs. Please notice; if you want to release just a single (one song) you simply need to press this button one time. If you want to release an album with 7 songs, simply press it 7 times'
                    },
                    {
                        title: 'Song Name',
                        element: document.querySelector('[id*="name_"]'),
                        intro: 'Here you need to add the name of your song. (If you want to release a SINGLE please make sure the album name on the general section is the same as this song so we can publish it automatically as a single release)'
                    },
                    {
                        title: 'Composer Name',
                        element: document.querySelector('[id*="composer"]'),
                        intro: 'Here you can add the composer of the song.'
                    },
                    {
                        title: 'Add More Artist',
                        element: document.querySelector('[id*="new_fartist_"]'),
                        intro: 'Here you can add feature artists who worked with you on this song.'
                    },
                    {
                        title: 'Song Language',
                        element: document.querySelector('[id^="language"]'),
                        intro: 'Please select the language of the actual lyrics of your music (if any). For example, You name your song; "Blue Star" - but your vocals on this song are German. In this case, you need to select German as the language here. If your song is Instrumental you need to select the language of the song metadata. Example: Your song is without lyrics and its called "House of Cards" - in this case, you need to select English here because "House of Cards" is English.'
                    },
                    {
                        title: 'Not Explicit Content',
                        element: document.querySelector('[id^=collapse] > div > div:nth-child(6)'),
                        intro: 'If you are not using any bad wording inside your music you need to select: Not Explicit Content.'
                    },
                    {
                        title: 'Explicit Content',
                        element: document.querySelector('[id^=collapse] > div > div:nth-child(7)'),
                        intro: 'If you use "bad wording" inside your music you need to select: Explicit Content.'
                    },
                    {
                        title: 'Instrumental',
                        element: document.querySelector('[id^=collapse] > div > div:nth-child(8)'),
                        intro: 'If your music is without any vocals at all you need to select instrumental.'
                    },
                    {
                        title: 'Upload Song',
                        element: document.querySelector('[id*="customFile"]'),
                        intro: 'Simply Drag & Drop your audio files here, please make sure it is in .wav or .flac format. If you dont got the correct format please us any converter like this one: <a href="https://www.freeconvert.com/wav-converter" target="_blank">freeconvert.com</a>.'
                    },
                    {
                        title: 'Release Album',
                        element: document.querySelector('form > button:nth-child(16)'),
                        intro: 'Once added all the songs, click this button to submit the album',
                    },
                    {
                        title: 'Good Luck!',
                        element: document.querySelector('.card__image'),
                        intro: 'You\'re onborad to release your album with singo.io! 👋'
                    }
                ]
            }).start();
        })
    </script>
    <!-- End Intro JS CDN -->

@endpush
