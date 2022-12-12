@extends('layouts.app')

@push('page_css')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/jquery-toast-plugin/1.3.2/jquery.toast.min.css" />
    <link rel="stylesheet" href="{{ asset('assets/vendor/icheck-bootstrap-3.0.1/icheck-bootstrap.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/vendor/icheck-bootstrap-3.0.1/icheck-bootstrap.min.css') }}">
    <link rel="stylesheet" href="https://unpkg.com/filepond@^4/dist/filepond.css" />
    <link rel="stylesheet" href="https://unpkg.com/filepond-plugin-image-preview/dist/filepond-plugin-image-preview.css" />

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
@endpush
@section('content')
    <div class="container-fluid release-albumm ">
        <div class="row">
            <div class="col-md-12">
                    @if ($errors->any())
                    <div class="alert alert-danger">
                        <ul>
                            @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                    @endif
                    <form method="post" enctype="multipart/form-data" action="{{ route('albums.store') }}" id="form-submit-new-album">
                            @csrf
                        <div class="card">
                            <div class="card-header row">
                                {{-- Release new album --}}
                                <div class="col-md-6 m-0">
                                    <h4 class="text-dark m-0">General Information</h4>
                                </div>
                                <div class="col-md-6 m-0">
                                    <div class="float-right">
                                        <button class="btn btn-sm btn-info" type="button" id="intro"><i class="fa fa-question-circle"></i>&nbsp;&nbsp;How to?</button>
                                        <button class="btn btn-sm btn-secondary" type="button" id="hints"><i class="fa fa-question-circle"></i>&nbsp;&nbsp;Hints?</button>
                                    </div>
                                </div>
                                
                                
                            </div>
                            <div class="card-body">
                                
                                    {{-- <h5>General Information</h5> --}}

                                    <div class="form-row">
                                        <div class="form-group col-md-6">
                                            <label for="name">
                                                Name
                                                <sup class="text-danger11">*</sup>
                                            </label>
                                            <input type="text" id="name" name="name" class="form-control @error('name') is-invalid @enderror" required placeholder="Album name">
                                            @error('name')
                                            <span class="error invalid-feedback">{{ $message }}</span>
                                            @enderror
                                        </div>

                                        <div class="form-group col-md-6">
                                            <label for="genre">
                                                Genre
                                                <sup class="text-danger11">*</sup>
                                            </label>
                                            <select name="genre" class="custom-select form-control @error('genre') is-invalid @enderror" id="genre" required>
                                                <option value=""> -- {{ __("Select genre") }} --</option>
                                                @foreach ($genres as $genre)
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
                                            @foreach ($stores as $st)
                                                <option value="{{ $st->id }}" selected="selected">{{ $st->title }}</option>
                                            @endforeach
                                        </select>

                                        @error('error')
                                        <span class="error invalid-feedback">{{ $message }}</span>
                                        @enderror
                                    </div>

                                    <div class="form-row">
                                        <div class="form-group col-md-4">
                                            <label for="date" >
                                                Release Date
                                                <sup class="">*</sup>
                                                <span class="text-danger" style="position:relative;z-index:2;">
                                                    <!-- Top tooltip-->
                                                    <div class="con-tooltip top">
                                                        <img src="{{ asset('image/icons/info1.svg') }}" class="w-20">{{--  <i class="fas fa-question"></i> --}}
                                                        <div class="tooltip ">
                                                            <span class="text-info">
                                                               The date must be a date after today.
                                                            </span>
                                                        </div>
                                                    </div>
                                                </span>
                                            </label>
                                            <input type="date" id="date" name="date"
                                                   class="form-control @error('date') is-invalid @enderror" required>
                                            @error('date')
                                            <span class="error invalid-feedback">{{ $message }}</span>
                                            @enderror
                                        </div>

                                        <div class="form-group col-md-4">
                                            <label for="upc">UPC </label>
                                            <input type="text" id="upc" maxlength="13" name="upc" class="form-control" style="font-size: 14px" placeholder="If you don't have one leave this blank">
                                        </div>

                                        <div class="form-group col-md-4">
                                            <label for="album_language">Language
                                                <sup class="text-danger11">*</sup>
                                            </label>
                                            <x-form.album-language :name="'album_language_id'" required/>
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label for="cover">Upload Cover
                                            <sup class="text-danger11">*</sup>

                                            <span class="text-danger" style="position:relative;z-index:2;">
                                                <!-- Top tooltip-->
                                                <div class="con-tooltip top">
                                                    <img src="{{ asset('image/icons/info1.svg') }}" class="w-20">
                                                    
                                                   {{--  <i class="fas fa-question"></i> --}}
                                                    <div class="tooltip ">
                                                        <span class="text-info">
                                                            The cover should be at least 3000x3000px you can use<a href="https://canva.com" target="_blank"> Canva</a> to create easy a cover in correct format.
                                                        </span>
                                                    </div>
                                                </div>
                                            </span>
                                        </label>
                                        <div class="mb-2">
                                            <input type="file" class="" id="cover" data-name="cover" name="cover" required>
                                            @error('cover')
                                            <span class="error invalid-feedback">{{ $message }}</span>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="form-row">
                                        <div class="form-group col-md-6">
                                            <label for="spo_url">Spotify Artist URL
                                                <span class="text-danger" style="position:relative;z-index:2;">
                                                <div class="con-tooltip top">
                                                    <img src="{{ asset('image/icons/info1.svg') }}" class="w-20">
                                                    <div class="tooltip ">
                                                        <span class="text-info">Please check URL before submit</span>
                                                        <br>
                                                        <span class="text-info">Please enter the full link. (eg:https://www.google.com/)</span>
                                                    </div>
                                                </div>
                                                </span>
                                            </label>
                                            <div class="form-row11">
                                                
                                                    <input type="text" id="spo_url" name="spo_url"
                                                           class="form-control @error('spo_url') is-invalid @enderror"
                                                           placeholder="If you don't have one leave this blank">
                                                
                                                    <a href="#" id="spo_url_set" class="btn  w-100 url mlk-abslt-btn" target="_blank">
                                                        Check URL
                                                    </a>
                                                
                                            </div>
                                            @error('spo_url')
                                            <span class="error invalid-feedback">{{ $message }}</span>
                                            @enderror

                                        </div>
                                        <div class="form-group col-md-6">
                                            <label for="apl_url">Apple Artist URL
                                                <span class="text-danger" style="position:relative;z-index:2;">
                                                <!-- Top tooltip-->
                                                <div class="con-tooltip top">
                                                    <img src="{{ asset('image/icons/info1.svg') }}" class="w-20">
                                                    <div class="tooltip ">
                                                        <span class="text-info">Please check URL before submit</span>
                                                        <br>
                                                        <span class="text-info">Please enter the full link. (eg:https://www.google.com/)</span>
                                                    </div>
                                                </div>
                                            </span>
                                            </label>
                                            <div class="form-row11">
                                                    <input type="text" id="apl_url" name="apl_url" class="form-control @error('apl_url') is-invalid @enderror" placeholder="If you don't have one leave this blank">
                                                
                                                    <a href="#" id="apl_url_set" class="btn w-100 url mlk-abslt-btn" target="_blank">
                                                        Check URL
                                                    </a>
                                            </div>
                                            @error('apl_url')
                                            <span class="error invalid-feedback">{{ $message }}</span>
                                            @enderror
                                        </div>
                                    </div>

                                   {{--  <h5>Songs</h5> --}}
                                    <input type="hidden" autocomplete="off" value="0" id="songcount">
                                    <input type="hidden" autocomplete="off" value="0" id="idcontroll">
                                    <button id="add" type="button" class="btn w-auto singo-btn secondarybgcolor mb-2"><i class="fas fa-plus"></i> Add song</button>
                                    {{-- <div id="accordion">

                                    </div> --}}
                                    <div class="float-right" id="pp1">
                                        <span class="text-danger" style="position:relative;z-index:2;">
                                            <div class="con-tooltip top">
                                                <button id="add_duplicate" type="button" class="btn  singo-btn secondarybgcolor addduplicate mb-2">
                                                    <i class="fas fa-plus"></i> Add song
                                                </button>

                                                <div class="tooltip " style="top:-70%;left:0%;">
                                                    <span class="text-info"> Please click "+Add Song" as often as you want.</span>
                                                </div>
                                            </div>
                                        </span>
                                        <button class="btn singo-btn secondarybgcolor w-auto mb-2" type="submit" id="btn-open-copyright-modal">
                                            <i class="fas fa-arrow-right mr-1"></i>
                                            Next
                                        </button>
                                    </div>
                                    
                                    <div class="modal fade" id="copyrightModel" tabindex="-1" role="dialog"
                                         aria-labelledby="exampleModalLabel" aria-hidden="true">
                                        <div class="modal-dialog" role="document">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title" id="exampleModalLabel">Copyright Status</h5>
                                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                                                </div>
                                                <div class="modal-body">
                                                    <div class="form-check mb-2">
                                                        <div class="icheck-primary">
                                                            <input type="checkbox" class="form-check-input" id="copyrightCheck1"
                                                                   name="copyrightCheck1" value="copyrightCheck1" required>
                                                            <label class="form-check-label" for="copyrightCheck1">
                                                                I confirm that this release is 100% mine or that I have 100% rights to publish this music with all samples and instrumentals included
                                                            </label>
                                                        </div>
                                                        <small></small>
                                                    </div>
                                                    <div class="form-check mb-2">
                                                        <div class="icheck-primary">
                                                            <input type="checkbox" class="form-check-input" id="copyrightCheck2" name="copyrightCheck2" value="copyrightCheck2" required>
                                                            <label class="form-check-label" for="copyrightCheck2">
                                                                I am aware that it is forbidden to publish music to which I do not have the rights
                                                            </label>
                                                        </div>
                                                    </div>
                                                    <div class="form-check mb-2">
                                                        <div class="icheck-primary">
                                                            <input type="checkbox" class="form-check-input" id="copyrightCheck3" name="copyrightCheck3" value="copyrightCheck3" required>
                                                            <label class="form-check-label" for="copyrightCheck3">
                                                                <span class="text-danger">Im aware that my account can be closed and banned without further notice from Singo service if i dont follow these rules.</span>
                                                            </label>
                                                        </div>
                                                    </div>
                                                    <button type="submit" id="btn-submit-album" class="btn btn-sm btn-success w-100 mt-1 mb-2">
                                                        <i class="fas fa-upload"></i> Release album
                                                    </button>
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                              
                            </div>
                        </div>
                        <div id="accordion">

                        </div>
                        
                        <div class="mlk-sm-card" id="pp2" style="display:none">
                            <div class="card-body">
                                <div class="float-right11" >
                                    <span class="text-danger" style="position:relative;z-index:2;">
                                        <div class="con-tooltip top">
                                            <button id="add_duplicate1" type="button" class="btn  singo-btn secondarybgcolor mb-2">
                                                <i class="fas fa-plus"></i> Add song
                                            </button>
                                        </div>
                                    </span>
                                    <button class="btn singo-btn secondarybgcolor w-auto mb-2" type="submit" id="btn-open-copyright-modal">
                                        <i class="fas fa-arrow-right mr-1"></i>
                                        Next
                                    </button>
                                </div>
                            </div>                    
                        </div>


                    </form>

            </div>
        </div>
    </div>
@endsection

@push('page_scripts')
    <script src="https://unpkg.com/filepond/dist/filepond.min.js"></script>
    <script src="https://unpkg.com/filepond-plugin-image-preview/dist/filepond-plugin-image-preview.js"></script>
    <script src="https://unpkg.com/filepond-plugin-image-validate-size/dist/filepond-plugin-image-validate-size.js"></script>
    <script src="https://unpkg.com/filepond-plugin-file-validate-type/dist/filepond-plugin-file-validate-type.js"></script>

    <script>
        function submitNewAlbumForm() {
            const formData = new FormData(document.getElementById('form-submit-new-album'));

            $.ajax({
                url: "{{ route('albums.store') }}",
                type: 'POST',
                data: formData,
                headers: {
                    'Accept' : 'application/json'
                },
                success: function (data) {
                    toastr.success(`Your album was created successfully.`)
                    setTimeout(function () {
                        window.location.href = `/albums/${data.id}`
                    }, 800)
                },
                error: function (error, status, errorThrown) {
                    if (error.status === 422) {
                        const validationMessages = error.responseJSON.errors;
                        Object.values(validationMessages).forEach(function (value) {
                            toastr.error(value, "Errors")
                        })
                    }
                },
                cache: false,
                contentType: false,
                processData: false
            });
        }

        $('#btn-open-copyright-modal,#btn-open-copyright-modal1').click(function (e) {
            e.preventDefault();
            let requiredFields = document.querySelectorAll('#form-submit-new-album input:required,select:required')

            let hasErrors = false;
            for (index = 0; index < requiredFields.length; index++) {
                element = requiredFields[index];
                elementName = element.getAttribute('name');


                if (elementName.includes('song')) {
                    elementName = `Song ${ parseInt(elementName.split('[')[1].substr(-2, 1)) + 1} ${elementName.split('[')[2].substr(0, elementName.split('[')[2].length - 1)}`;
                }

                if (elementName.includes('upload')) {
                    elementName = elementName.replace('upload', 'song file');
                }

                if (elementName.includes('audio_locale_id')) {
                    elementName = elementName.replace('audio_locale_id', 'language');
                }

                elementName = elementName.replaceAll('_', ' ').replace('id', '')

                if (element.value == '') {
                    toastr.error(`${ elementName } is required.`, "Errors")

                    $(element).focus()
                    hasErrors = true;
                    break;
                }
            }

            if (hasErrors) {
                return;
            }

            if (! hasErrors) {
                if(parseInt($('#songcount').val()) === 0) {
                    toastr.error('Please add at least one song.', "Errors")
                    return;
                }
                hasErrors = false;
            }


            $('#copyrightModel').modal('show');
        });

        $('#btn-submit-album').click(function (e) {
            e.preventDefault();

            submitNewAlbumForm();
        })

        @if (!auth()->user()->isPremium)
        var currentTime = new Date();
        currentTime.setDate(currentTime.getDate() + 14);
        window.$("#date").attr('min', currentTime.toISOString().substr(0, 10));
        @endif

        window.$("#accordion").on("click", ".removeBtn", (event) => {
            $("#song" + window.$(event.target).attr("id").replace("remove", "")).remove();
            var count = parseInt($("#songcount").val());
            $("#songcount").val(count - 1);
            if(count==1){
                $('#pp1').show();
                $('#pp2').hide();
            }
        });
        
        

        window.$("#add,#add_duplicate,#add_duplicate1").on("click", event => {

            $('#pp1').hide();
            $('#pp2').show();

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
                            <button type="button" class="text-danger btn btn-sm removeBtn mr-2 right_button fw-500" id="remove${id_ctnt}">
                                <i class="fas fa-times-circle"></i> Remove
                            </button>
                            <button type="button" class="text-primary btn btn-sm toggle right_button fw-500" data-toggle="collapse" data-target="#collapse${id_ctnt}" aria-expanded="true" aria-controls="collapse${id_ctnt}">
                                <i class="fas fa-copy mr-2" ></i>Minimize/Maximize
                            </button>

                        </span>
                    </div>
                    <div class="card-body collapse show p-0" id="collapse${id_ctnt}" aria-labelledby="heading${id_ctnt}" data-parent="#accordion">
                        <div class="clearfix p-3">
                            <div class="form-group">
                                <label for="name${id_ctnt}">Name
                                <sup class="text-danger">*</sup>
                                </label>
                                <input type="text" id="name_${id_ctnt}" name="songs[${id_ctnt}][title]" class="form-control" required placeholder="Song name"  onkeyup="song_name_new(this.id)">
                            </div>
                            <div class="form-group">
                                <label for="composer${id_ctnt}">Composer
                                    <sup class="text-danger">*</sup>
                                </label>
                                <input type="text" id="composer${id_ctnt}" name="songs[${id_ctnt}][composer]" class="form-control" required placeholder="Song composer">
                            </div>
                            <div class="form-group mb-0" id = "addfartist${id_ctnt}">
                            </div>
                            <div class="clearfix ml-2 mb-2 mt-2">
                                <input type = "hidden" value = "0" id = "total_artist_${id_ctnt}">
                                <input type = "hidden" value = "" id = "fartist_${id_ctnt}" name = "artist[${id_ctnt}][fartist]" >
                                <a href="javascript:void(0)" id="new_fartist_${id_ctnt}" class="btn float-left fallout btn-yellow1" onclick="new_fartist(this.id)">
                                    + Featured Artist
                                </a>
                                <small class="ml-20 text-grey1">You can add one or more featured artist if you want</small>
                            </div>
                            <div class="form-row">
                                <div class="form-group col-md-6">
                                    <label for="audio_locale${id_ctnt}">
                                        Language
                                        <sup class="text-danger">*</sup>
                                    </label>
                                    <x-form.song-audio-locale name="songs[${id_ctnt}][audio_locale_id]" id="audio_locale${id_ctnt}" required />
                                </div>
                                <div class="form-group col-md-6">
                                    <label for="isrc${id_ctnt}">ISRC</label>
                                    <input type="text" id="isrc${id_ctnt}" name="songs[${id_ctnt}][isrc]" class="form-control"  placeholder=" ISRC of the song, leave blank if you dont have one">
                                </div>
                            </div>
                            <div class="d-flex justify-content-between">
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
                                    <a style="margin-left: 30px;" href="javascript:void(0)" id="clear_radio_${id_ctnt}" class="btn float-left fallout btn-yellow1" onclick="clear_radio(this.id)">
                                        Reset Selection
                                    </a>
                                </div>
                            </div>
                            <div class="form-group">
                                <label>Upload song</label>
                                <div class="">
                                    <input type="file" accept="audio/wav,audio/flac,audio/x-wav" name="upload_${id_ctnt}" data-name="upload_${id_ctnt}" id="customFileShow${id_ctnt}" required>
                                    <input type="hidden" class="" name="songs[${id_ctnt}][song]" id="customFile${id_ctnt}" >
                                    <span class="text-sm text-white">Allowed formats: .flac, .wav</span>
                                        <p class="text-sm d-inline float-right">If your files in other format, go to this website to convert it to wav: <a href="https://www.freeconvert.com/wav-converter" target="_blank">freeconvert.com</a></p>
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
            var inputElement = document.getElementById('customFileShow' + id_ctnt);

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
                    process: {
                        onload: (response) => {
                            $('#customFile' + id_ctnt).val(response);
                        },
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
            const songNum = res_1[2];
            let fcount = 1;
            let fartist_append_id = "#" + "addfartist" + res_1[2];
            let total_id = "#" + "total_artist_" + res_1[2];

            var new_val = parseInt(window.$(total_id).val());
            // console.log(fartist_append_id);

            window.$(fartist_append_id).append(
                `
                <div class = "fallout" id = "fid_${res_1[2]}_${new_val + 1}">
                    <label>Featured Artist - ${new_val + 1}</label>
                    <input type="text" class=" form-control" placeholder="Featured Artist - ${new_val + 1}" onfocusout="total_name(this.id)"  id = "fartist_name_${res_1[2]}_${new_val + 1}" name = "songs[${songNum}][fartist][]">
                    <a href="javascript:void(0)" class="float-right text-danger btn-danger1 btn mt-2 mb-2" id="dlt_fartist_${res_1[2]}_${new_val + 1}" onclick="dlt_fartist(this.id)" >
                            - Remove Artist
                    </a>
                </div>
                `);
            window.$(total_id).val(new_val + 1);
        }
    </script>
    <script>
        function dlt_fartist(id) {
            let res_1 = id.split("_");
            let dlt_id = "#fid_" + res_1[2] + "_" + res_1[3];
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
    <script src="https://unpkg.com/filepond-plugin-image-transform/dist/filepond-plugin-image-transform.js"></script>
    <script>
        FilePond.registerPlugin(FilePondPluginImagePreview);
        FilePond.registerPlugin(FilePondPluginImageValidateSize);
        FilePond.registerPlugin(FilePondPluginFileValidateType);
        FilePond.registerPlugin(FilePondPluginImageTransform);

        // Get a reference to the file input element
        const inputElement = document.getElementById('cover');

        // Create a FilePond instance
        const pond = FilePond.create(inputElement, {
            imageValidateSizeMinWidth: 3000,
            imageValidateSizeMinWidth: 3000,
            imageValidateSizeMaxWidth: 3000,
            imageValidateSizeMinHeight: 3000,
            acceptedFileTypes: ['image/*'],
            imageTransformOutputMimeType: 'image/jpeg',

        });

        var name = inputElement.dataset.name;
        var _url = ("{{ route('ajax.upload', ['name']) }}");
        var __url = _url.replace('name', name);

        pond.setOptions({
            server: {
                url: __url,
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                }
            },
        });

        for (let el of document.querySelectorAll('.filepond--credits')) el.style.visibility = 'hidden';

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
            $('[id*="song"] label[for^="audio_locale"]').attr('data-hint', 'Please select the language of the actual lyrics of your music (if any). For example, You name your song; "Blue Star" - but your vocals on this song are German. In this case, you need to select German as the language here. If your song is Instrumental you need to select the language of the song metadata. Example: Your song is without lyrics and its called "House of Cards" - in this case, you need to select English here because "House of Cards" is English.')
            $('[id*="song"] label[for^="not_explicit"]').attr('data-hint', 'If you are not using any bad wording inside your music you need to select: Not Explicit Content.')
            $('[id*="song"] label[for^="explicit"]').attr('data-hint', 'If you use "bad wording" inside your music you need to select: Explicit Content.')
            $('[id*="song"] label[for^="instrumental"]').attr('data-hint', 'If your music is without any vocals at all you need to select instrumental.')
            $('[id*="song"] div[id^="customFileShow"]').attr('data-hint', 'Simply Drag & Drop your audio files here, please make sure it is in .wav or .flac format. If you dont got the correct format please us any converter like this one: <a href="https://www.freeconvert.com/wav-converter" target="_blank">freeconvert.com</a>.')
            $('button[id^="btn-open-copyright"]').attr('data-hint', 'Once added all the songs, click this button to submit the album.')
            
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
                        element: document.querySelector('#form-submit-new-album > div:nth-child(7)'),
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
                        element: document.querySelector('[id*="audio_locale_id"]'),
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
                        element: document.querySelector('[id*="customFileShow"]'),
                        intro: 'Simply Drag & Drop your audio files here, please make sure it is in .wav or .flac format. If you dont got the correct format please us any converter like this one: <a href="https://www.freeconvert.com/wav-converter" target="_blank">freeconvert.com</a>.'
                    },
                    {
                        title: 'Release Album',
                        element: document.querySelector('#btn-open-copyright-modal'),
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
