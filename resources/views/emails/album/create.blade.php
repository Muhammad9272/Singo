@extends('layouts.app')

@push('page_css')
<link rel="stylesheet" href="{{ asset('assets/vendor/icheck-bootstrap-3.0.1/icheck-bootstrap.css') }}">
<link rel="stylesheet" href="{{ asset('assets/vendor/icheck-bootstrap-3.0.1/icheck-bootstrap.min.css') }}">
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />

<style>

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
.fallout{
    transition: 0.4s !important;
}
.select2-selection__choice__display{
    margin-left: 10px;
    color: #000000;
}
.select2-container{
    width: 100% !important;
}

@media (max-width: 575.98px) {
    .url{
        margin-top: 10px;
    }
    .rs_header{
        display: flex;
        flex-wrap: wrap;
        justify-content: center;
    }
    .left_content{
        margin:0px 0px 5px 0px !important ;
    }
    .left_content span{
        margin:0px 0px 0px 0px !important ;
    }
    .right_button{
        width: 100% !important;
        margin:0px 0px 5px 0px !important ;
    }
 }

 @media (max-width: 767.98px) { 
    .url{        
        margin-top: 10px;
    }
  }

</style>
@endpush
@section('content')
    <div class="container-fluid">
        <div class="row content-header mb-2">
            <h1>Release new album</h1>
        </div>
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">Release new album</div>
                    <div class="card-body">
                        @error('numsong')
                        <div class="alert alert-danger">{{ $message }}</div>
                        @enderror
                        @error('songs.*')
                        <div class="alert alert-danger">You did not upload a valid song. Please try again</div>
                        @enderror
                        <form method="post" enctype="multipart/form-data" action="{{ route('albums.store') }}">
                            @csrf
                            <h5>General Information</h5>
                            <div class="form-group">
                                <label for="name">Name</label>
                                <input type="text" id="name" name="name" class="form-control @error('name') is-invalid @enderror" required placeholder="Album name">
                                @error('name')
                                <span class="error invalid-feedback">{{ $message }}</span>
                                @enderror
                            </div>
                            <div class="form-group">
                                <label for="genre">Genre</label>
                                <select name="genre" class="custom-select @error('genre') is-invalid @enderror" id="genre">
                                    @foreach($genres as $genre)
                                        <option value="{{ $genre->id }}">{{ $genre->name }}</option>
                                    @endforeach
                                </select>
                                @error('error')
                                <span class="error invalid-feedback">{{ $message }}</span>
                                @enderror
                            </div>

                            <div class="form-group">
                                <label for="store">Store</label>
                                <select class="js-example-basic-single custom-select" name="store[]" multiple="multiple" id="store">
                                    
                                    @foreach($store as $st)

                                        <option value="{{ $st->id }}" selected="selected">{{ $st->title }}</option>
                                    @endforeach
                                </select>
                                @error('error')
                                <span class="error invalid-feedback">{{ $message }}</span>
                                @enderror
                            </div>

                            <div class="form-group">
                                <label for="date">Release Date</label>
                                <input type="date" id="date" name="date" class="form-control @error('date') is-invalid @enderror" required>
                                @error('date')
                                <span class="error invalid-feedback">{{ $message }}</span>
                                @enderror
                            </div>
                            <div class="form-group">
                                <label for="upc">UPC </label>
                                <input type="text" id="upc" name="upc" class="form-control" placeholder="If you don't have one leave this blank">
                            </div>

                            <div class="form-group">
                                <label for="cover">Upload Cover</label>
                                <div class="custom-file mb-2">
                                    <input type="file" class="custom-file-input @error('cover') is-invalid @enderror" id="cover" name="cover">
                                    <label class="custom-file-label" for="customFile">Choose cover</label>
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
                                        <input type="text" id="spo_url" name="spo_url" class="form-control @error('spo_url') is-invalid @enderror"  placeholder="If you don't have one leave this blank">
                                    </div>
                                    <div class = "col-md-2 ">
                                        <a href = "#" id = "spo_url_set" class = "btn btn-primary w-100 url" target = "blank">Check URL</a>
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
                                        <input type="text" id="apl_url" name="apl_url" class="form-control @error('apl_url') is-invalid @enderror"  placeholder="If you don't have one leave this blank">
                                    </div>
                                    <div class = "col-md-2">
                                        <a href = "#" id = "apl_url_set"class = "btn btn-primary w-100 url" target = "blank">Check URL</a>
                                    </div>
                                </div>
                                    @error('apl_url')
                                    <span class="error invalid-feedback">{{ $message }}</span>
                                    @enderror
                                    <span class = "text-danger">Please check URL before submit<span><br>
                                    <span class = "text-danger">Please enter the full link. (eg:https://www.google.com/)<span>
                                
                            </div>
                            

                            <h5>Songs</h5>
                            <input type="hidden" autocomplete="off" value="0" id="songcount">
                            <input type="hidden" autocomplete="off" value="0" id="idcontroll">
                            <button id="add" type="button" class="btn btn-sm btn-success mb-2"><i class="fas fa-plus"></i> Add song</button>
                            <div id="accordion">

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
<script src="https://code.jquery.com/jquery-3.5.0.js"></script>
    <script>
        @if(!auth()->user()->isPremium)
        var currentTime = new Date();
        currentTime.setDate(currentTime.getDate()+14);
        window.$("#date").attr('min', currentTime.toISOString().substr(0, 10));
        @endif

        window.$("#accordion").on("click",".removeBtn",(event) => {
            $("#song"+window.$(event.target).attr("id").replace("remove", "")).remove();
            var count = parseInt($("#songcount").val());
            $("#songcount").val(count-1);
        });

        window.$("#add").on("click", event => {
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
                                            <label for="name${id_ctnt}">Name</label>
                                            <input type="text" id="name_${id_ctnt}" name="songs[${id_ctnt}][title]" class="form-control" required placeholder="Song name"  onkeyup="song_name_new(this.id)">
                                        </div>
                                        <div class="form-group">
                                            <label for="composer${id_ctnt}">Composer</label>
                                            <input type="text" id="composer${id_ctnt}" name="songs[${id_ctnt}][composer]" class="form-control" required placeholder="Song composer">
                                        </div>
                                        <div class="form-group mb-0" id = "addfartist${id_ctnt}">
                                            
                                        </div>
                                            <div class="clearfix ml-2 mb-2">
                                               <input type = "hidden" value = "0" id = "total_artist_${id_ctnt}"> 
                                               <input type = "hidden" value = "" id = "fartist_${id_ctnt}" name = "artist[${id_ctnt}][fartist]" > 
                                                <a href="javascript:void(0)" id="new_fartist_${id_ctnt}" class="float-left fallout" onclick="new_fartist(this.id)">
                                                    + Featured Artist
                                                </a><br>
                                                <small>You can add one or more featured artist if you want</small>
                                            </div>
                                        <div class="form-group">
                                            <label for="lang${id_ctnt}">Language</label>
                                            <input type="text" id="lang${id_ctnt}" name="songs[${id_ctnt}][language]" class="form-control" required placeholder="Language of the song">
                                        </div>
                                        <div class="form-group">
                                            <label for="isrc${id_ctnt}">ISRC</label>
                                            <input type="text" id="isrc${id_ctnt}" name="songs[${id_ctnt}][isrc]" class="form-control"  placeholder=" ISRC of the song, leave blank if you dont have one">
                                        </div>
                                        <div class="form-group">
                                            <div class="icheck-primary">
                                                <input type="radio" id="explicit${id_ctnt}" name="songs[${id_ctnt}][radio]" class="form-check-input" placeholder="" value = "explicit">
                                                <label for="explicit${id_ctnt}" class="form-check-label">Explict Content</label>
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
                                        <div class="form-group">
                                            <label>Upload song</label>
                                            <div class="custom-file">
                                                <input type="file" accept=".mp3,.wav,.flac" class="custom-file-input" name="songs[${id_ctnt}][song]" id="customFile${id_ctnt}">
                                                <label class="custom-file-label" for="customFile${id_ctnt}">Choose song</label>
                                                <span class="text-sm">Allowed formats: .flac, .mp3, .wav</span>
                                            </div>
                                        </div>
                                    </div>   
                                </div>
                </div>
                `
            );
            window.$("#songcount").val(count+1);
            window.$("#idcontroll").val(id_ctnt+1);
            bsCustomFileInput.init();
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
<script>
    function song_name_new(id) {
        
        let res_1 = id.split("_");
        var no = res_1[1];
        let song_name_id = "#"+"song_name"+res_1[1];
        let new_val = document.getElementById(id).value;
        $(song_name_id).text(new_val);
    }
</script>
<script>
    function new_fartist(id) {
        
        let res_1 = id.split("_");       
        let fcount = 1;
        let fartist_append_id = "#"+"addfartist"+res_1[2];
        let total_id = "#"+"total_artist_"+res_1[2];

        var new_val = parseInt(window.$(total_id).val()); 
        // console.log(fartist_append_id);

        window.$(fartist_append_id).append(
                `
                <div class = "mt-2 fallout" id = "fid_${res_1[2]}_${new_val+1}">
                    <label>Featured Artist - ${new_val+1}</label>
                    <input type="text" class=" form-control" placeholder="Featured Artist - ${new_val+1}" onfocusout="total_name(this.id)"  id = "fartist_name_${res_1[2]}_${new_val+1}" name = "fartist_name[${new_val}][faname]">
                    <a href="javascript:void(0)" class="float-right text-danger" id="dlt_fartist_${res_1[2]}_${new_val+1}" onclick="dlt_fartist(this.id)" >
                            - Remove Artist
                    </a>                               
                </div>
                `);
                window.$(total_id).val(new_val+1);       
    }
</script>
<script>
    function dlt_fartist(id){
        let res_1 = id.split("_");
        let dlt_id = "#fid_"+res_1[2]+"_"+res_1[3];
        // console.log(dlt_id);
        $(dlt_id).remove();
    }
</script>
<script>
    function clear_radio(id){
        let res_1 = id.split("_");
        let inst = "instrumental"+res_1[2];
        let exp = "explicit"+res_1[2];
        // console.log(res_1);
        document.getElementById(inst).checked = false;
        document.getElementById(exp).checked = false;
        
    }
</script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script>
$(document).ready(function() {
    $('.js-example-basic-single').select2();

});
</script>





@endpush
