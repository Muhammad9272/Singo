@extends('layouts.app')

@section('content')
@php
$exp = "";
$inst = "";

@endphp
    <div class="container-fluid">
        <div class="row content-header mb-2">
            <h1>Update album( {{$album->title}} )</h1>
        </div>
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">Update album info</div>
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
                            <input type="hidden" value="{{ $album->id }}" name = "album_id">
                            <h5>General Information</h5>
                            <div class="form-group">
                                <label for="name">Name</label>
                                <input type="text" id="name" value = "{{ $album->title }}" name="name" class="form-control @error('name') is-invalid @enderror" required placeholder="Album name">
                                @error('name')
                                <span class="error invalid-feedback">{{ $message }}</span>
                                @enderror
                            </div>
                            <div class="form-group">
                                <label for="genre">Genre</label>
                                <select name="genre" class="custom-select @error('genre') is-invalid @enderror" id="genre">
                                    <option value = "{{ $album->genre->id }}" hidden selected>{{ $album->genre->name }}</option>
                                    @foreach($genres as $genre)
                                        <option value="{{ $genre->id }}">{{ $genre->name }}</option>
                                    @endforeach
                                        
                                </select>
                                @error('error')
                                <span class="error invalid-feedback">{{ $message }}</span>
                                @enderror
                            </div>
                            <div class="form-group">
                                <label for="date">Release Date</label>
                                <input type="date" id="date" value = "{{date('Y-m-d', strtotime($album->release))}}" name="date" class="form-control @error('date') is-invalid @enderror" required>
                                @error('date')
                                <span class="error invalid-feedback">{{ $message }}</span>
                                @enderror
                            </div>
                            <div class="form-group">
                                <label for="upc">UPC </label>
                                <input type="text" id="upc" value = "{{$album->upc}}" name="upc" class="form-control" placeholder="If you don't have one leave this blank">
                            </div>

                            <div class="form-group">
                                <label for="cover">Upload Cover</label>
                                <div class="custom-file mb-2">
                                    <input type="file" value = "{{ Illuminate\Support\Facades\Storage::url('albums/'.$album->id.'/'.$album->cover) }}" class="custom-file-input @error('cover') is-invalid @enderror" id="cover" name="cover">
                                    <label class="custom-file-label" for="customFile">{{$album->cover}}</label>
                                    <span class="text-danger">If you want to update cover click Browse</span><br>
                                    @error('cover')
                                    <span class="error invalid-feedback">{{ $message }}</span>
                                    @enderror
                                    <span class="text-sm">The cover should be at least 3000x3000px you can use <a href="https://canva.com" target="_blank"> Canva</a> to create easy a cover in correct format.</span>
                                </div>
                            </div>
                            <div class="form-group">
                            <label for="spo_url">Spotify Artist URL </label>
                                <div class = "form-row">                                 
                                    <div class = "col-md-10">                                       
                                        <input type="text" value = "{{$album->spotify_url}}" id="spo_url" name="spo_url" class="form-control @error('spo_url') is-invalid @enderror"  placeholder="If you don't have one leave this blank">
                                    </div>
                                    <div class = "col-md-2 ">
                                        <a href = "#" id = "spo_url_set" class = "btn btn-primary w-100" target = "blank">Check URL</a>
                                    </div>
                                </div>
                                    @error('spo_url')
                                    <span class="error invalid-feedback">{{ $message }}</span>
                                    @enderror
                                    <span class = "text-danger">Please check URL before submit<span><br>
                                    <span class = "text-danger">Please enter the full link. (eg:https://www.google.com/)<span>
                                
                            </div>
                            <div class="form-group">
                            <label for="apl_url">Apple Artist URL </label>
                                <div class = "form-row">
                                    <div class = "col-md-10"> 
                                        <input type="text" value = "{{ $album->apple_music_url }}" id="apl_url" name="apl_url" class="form-control @error('apl_url') is-invalid @enderror"  placeholder="If you don't have one leave this blank">
                                    </div>
                                    <div class = "col-md-2">
                                        <a href = "#" id = "apl_url_set"class = "btn btn-primary w-100" target = "blank">Check URL</a>
                                    </div>
                                </div>
                                    @error('apl_url')
                                    <span class="error invalid-feedback">{{ $message }}</span>
                                    @enderror
                                    <span class = "text-danger">Please check URL before submit<span><br>
                                    <span class = "text-danger">Please enter the full link. (eg:https://www.google.com/)<span>
                                
                            </div>                           
                            <h5>Songs</h5>
                            
                            <button id="add" type="button" class="btn btn-sm btn-success mb-2"><i class="fas fa-plus"></i> Add song</button>
                            <div id="songs">

                            @php
                                $count = 0;
                            @endphp
                            @foreach($album->songs()->get() as $song)
                            
                            <input type = "hidden" name = "song_id{{$count}}" value ="{{ $song->id }}"  >                        
                            <div class="card" id="song{{$count}}">
                                    <div class="card-header">Song - {{$count + 1}}</div>
                                    <div class="card-body">
                                        <div class="form-group">
                                            <label for="title{{$count}}">Name</label>
                                            <input type="text" id="title{{$count}}" value = "{{ $song->title }}" name="title{{$count}}" class="form-control" required placeholder="Song name">
                                        </div>
                                        <div class="form-group">
                                            <label for="composer{{$count}}">Composer</label>
                                            <input type="text" id="composer{{$count}}" value = "{{ $song->composer }}" name="composer{{$count}}" class="form-control" required placeholder="Song composer">
                                        </div>
                                        <div class="form-group">
                                            <label for="language{{$count}}">Language</label>
                                            <input type="text" value = "{{ $song->language }}" id="language{{$count}}" name="language{{$count}}" class="form-control" required placeholder="Language of the song">
                                        </div>
                                        @php if($song->isExplicit){
                                            $exp = 'checked="checked"';
                                        }
                                        else{
                                            $exp = "";
                                        }
                                        @endphp
                                        <div class="form-group">
                                            <div class="form-check">
                                                <input type="radio" id="explicit{{$count}}" name="radio{{$count}}" class="form-check-input" placeholder="" value = "explicit" {{$exp}} >
                                                <label for="explicit{{$count}}" class="form-check-label">Explict Content</label>
                                            </div>
                                        </div>                                      
                                        @php if($song->isInstrumental){
                                            $inst = 'checked="checked';
                                        }
                                        else{
                                            $inst = "";
                                        }
                                        @endphp
                                        <div class="form-group">
                                            <div class="form-check">
                                                <input type="radio" id="instrumental{{$count}}" name="radio{{$count}}" class="form-check-input" placeholder="" value = "instrumental" {{$inst}}>
                                                <label for="instrumental{{$count}}" class="form-check-label">Instrumental</label>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label>Upload song</label>
                                            <div class="custom-file">
                                                <input type="file" value = "{{'albums/'.$song->album_id.'/songs/'.$song->songFile}}" accept=".mp3,.wav,.flac" class="custom-file-input" name="songs{{$count}}" id="customFile{{$count}}">
                                                <label class="custom-file-label" for="customFile{{$count}}">{{$song->songFile}}</label>
                                                <span class="text-danger">If you want to update song click Browse</span><br>
                                                <span class="text-sm">Allowed formats: .flac, .mp3, .wav</span>
                                            </div>
                                        </div>
                                        <button type="button" class="btn-danger btn btn-sm removeBtn" id="remove{{$count}}"><i class="fas fa-trash"></i> Remove</button>
                                    </div>
                                </div>
                            @php
                                $count++;
                                @endphp
                            @endforeach
                            <input type="hidden" autocomplete="off" value="{{$count}}" id="songcount" name = "songcount" >

                            </div>
                            <button type="submit" class="btn btn-success"><i class="fas fa-upload"></i> Release album</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>














@endsection


@push('page_scripts')
    <script>
    $(document).ready(function() {
        @if(!auth()->user()->isPremium)
        var currentTime = new Date();
        currentTime.setDate(currentTime.getDate()+14);
        window.$("#date").attr('min', currentTime.toISOString().substr(0, 10));
        @endif

        window.$("#songs").on("click",".removeBtn",(event) => {
            $("#song"+window.$(event.target).attr("id").replace("remove", "")).remove();
            var count = parseInt($("#songcount").val());
            $("#songcount").val(count-1);
        });

        window.$("#add").on("click", event => {
            var count = parseInt(window.$("#songcount").val());
            var song_count = count + 1;
            window.$("#songs").append(
                `
                <div class="card" id="song${count}">
                                    <div class="card-header">Song - ${song_count}</div>
                                    <div class="card-body">
                                        <div class="form-group">
                                            <label for="title${count}">Name</label>
                                            <input type="text" id="title${count}" name="title${count}" class="form-control" required placeholder="Song name" required>
                                        </div>
                                        <div class="form-group">
                                            <label for="composer${count}">Composer</label>
                                            <input type="text" id="composer${count}" name="composer${count}" class="form-control" required placeholder="Song composer" required>
                                        </div>
                                        <div class="form-group">
                                            <label for="language${count}">Language</label>
                                            <input type="text" id="language${count}" name="language${count}" class="form-control" required placeholder="Language of the song" required>
                                        </div>
                                        <div class="form-group">
                                            <div class="form-check">
                                                <input type="radio" id="explicit${count}" name="radio${count}" class="form-check-input" placeholder="" value = "explicit" >
                                                <label for="explicit${count}" class="form-check-label">Explict Content</label>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <div class="form-check">
                                                <input type="radio" id="instrumental${count}" name="radio${count}" class="form-check-input" placeholder="" value = "instrumental">
                                                <label for="instrumental${count}" class="form-check-label">Instrumental</label>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label>Upload song</label>
                                            <div class="custom-file">
                                                <input type="file" accept=".mp3,.wav,.flac" class="custom-file-input" name="songs${count}" id="customFile${count}" required>
                                                <label class="custom-file-label" for="customFile${count}">Choose song</label>
                                                <span class="text-sm">Allowed formats: .flac, .mp3, .wav</span>
                                            </div>
                                        </div>
                                        <button type="button" class="btn-danger btn btn-sm removeBtn" id="remove${count}"><i class="fas fa-trash"></i> Remove</button>
                                    </div>
                                </div>
                `
            );
            window.$("#songcount").val(count+1);
            bsCustomFileInput.init();
        });
    });
    </script>
    <script>
        var inputs = $('#apl_url');
        var url = $('#apl_url_set');
            inputs.keyup(function() {
            var new_url = inputs.val();
            url.attr("href", new_url);
            
            });
    </script>
    <script>
        var inputs_2 = $('#spo_url');
        var url_2 = $('#spo_url_set');
            inputs_2.keyup(function() {
            var new_url_2 = inputs_2.val();
            url_2.attr("href", new_url_2);
            
            });
    </script>
@endpush





