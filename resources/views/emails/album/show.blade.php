@extends('layouts.app')

@section('content')
    <div class="container-fluid">
        <div class="row content-header mb-2 w-100">
            <div class="col-md-6">
                <h1>{{ $album->title }}</h1>
            </div>
            @if(auth()->user()->type != 0)
            <div class="col-md-6">
                <a href="{{ route('admin.user', $album->user->id) }}" class="btn btn-sm btn-success float-right"><i class="fas fa-eye"></i> View User</a>
            </div>
            @endif
        </div>
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">{{ $album->title }} by {{ $album->user->artistName }} <span class="btn btn-{{ ($album->user->isPremium) ? 'danger' : 'secondary' }} btn-sm float-right">@if($album->user->isPremium) Premium @else Free @endif</span></div>
                    <div class="card-body">
                        @if(session('created'))
                            <div class="alert alert-success text-center">{{ session('created') }}</div>
                        @endif

                        @if(session('updated'))
                            <div class="alert alert-success text-center">{{ session('updated') }}</div>
                        @endif

                        <div class="row">
                            <div class="col-md-3">
                                <img class="w-100" src="{{ \Illuminate\Support\Facades\Storage::url('albums/'.$album->id.'/'.$album->cover) }}">
                                <a href="{{ route('album.download', $album->id) }}" target="_blank" class="btn btn-success btn-sm mt-2"><i class="fas fa-download"></i> Download Cover</a>
                            </div>
                            <div class="col-md-9">
                                <h5>Songs</h5>
                                <div class="table-responsive">
                                    <table class="table table-dark table-striped">
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
                                        @php
                                        $count =1;
                                        @endphp
                                        @foreach($album->songs()->get() as $song)
                                        @if($song->title != null )
                                            <tr>
                                                <td>{{ $count }}</td>
                                                <td>{{ $song->title }} @if($song->isExplicit) <span class="badge badge-secondary">E</span> @endif @if($song->isInstrumental) <span class="badge badge-secondary">I</span> @endif</td>
                                                <td><small>Composer: {{ $song->composer }}, Language: {{ $song->language }}, ISRC: {{ $song->isrc }} </small></td>
                                                <td>@php $i = 0; @endphp
                                                    @forelse($song->fartist()->get() as $fa)
                                                        @if($i != 0),@endif {{$fa->artist_name}}
                                                        @php $i++; @endphp 
                                                    @empty 
                                                        No featured artist found 
                                                    @endforelse
                                                </td>
                                                <td><a href="{{ route('song.download', $song->id) }}" target="_blank" class="btn btn-success btn-sm"><i class="fas fa-download"></i> </a> </td>
                                            </tr>
                                            @php
                                            $count++;
                                            @endphp
                                        @endif
                                        @endforeach
                                        </tbody>
                                    </table>
                                </div>
                                <h5>Album Info</h5>
                                <div class="table table-responsive">
                                    <table class="table table-dark table-striped"  style = "background-color: #212529">
                                        <thead>
                                        <tr>
                                            <th>Name</th>
                                            <th>Genre</th>
                                            <th>UPC</th>
                                            <th>Release</th>
                                            <th>Spotify URL</th>
                                            <th>Apple Music URL</th>
                                            <th>Status</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        <tr>
                                            <td>{{ $album->title }}</td>
                                            <td>{{ $album->genre->name }}</td>
                                            <td>@if($album->upc !== null) {{ $album->upc }} @else Not set yet @endif</td>
                                            <td>@if($album->release !== null) {{ $album->release->format('d.m.Y') }} @else Not set yet @endif</td>
                                            <td>
                                                @if(isset($album->spotify_url))
                                                    <p id = "p1" class = "d-none">{{$album->spotify_url}}</p>
                                                    <button onclick="copyToClipboard('#p1')" class = "badge badge-info">Copy URL</button>
                                                @else
                                                    <button class = "badge badge-danger">No URL</button>
                                                @endif
                                            </td>
                                            <td>
                                                @if(isset($album->apple_music_url))
                                                    <p id = "p2" class = "d-none">{{$album->apple_music_url}}</p>
                                                    <button onclick="copyToClipboard('#p2')" class = "badge badge-info">Copy URL</button>
                                                @else
                                                    <button class = "badge badge-danger">No URL</button>
                                                @endif                                          
                                            </td>
                                            <td>
                                                <span class="badge badge-{{ $album->getStatusColor() }}">{{ $album->getStatusText() }}</span>
                                            </td>
                                        </tr>
                                        
                                        <tr>
                                            <td colspan = "7">Selected Stores:@php $i = 0; @endphp @forelse($user_Store as $us) @if($i != 0),@endif {{ $us->store_name->title }} @php $i++; @endphp @empty No Store Added @endforelse</td>
                                        </tr>
                                        
                                        </tbody>
                                    </table>

                                    @if(auth()->user()->type != 0)
                                        <form method="post" id="updateStatus" name="updateStatus" action="{{ route('album.update', $album->id) }}">
                                            @csrf
                                            <input type="hidden" name ="id" value="{{$album->id}}">
                                            <div class="form-group">
                                                <label for="status">Status</label>
                                                <select class="custom-select" name="status" id="status">
                                                    <option value="-1" @if($album->status === -1) selected @endif>Declined</option>
                                                    <option value="0" @if($album->status === 0) selected @endif>Pending</option>
                                                    <option value="1" @if($album->status === 1) selected @endif>Approved</option>
                                                    <option value="2" @if($album->status === 2) selected @endif>Delivered</option>
                                                    <option value="3" @if($album->status === 3) selected @endif>Need Edit</option>
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
                                            <button class="btn btn-success" type="submit"><i class="fas fa-save"></i> Save details</button>
                                        </form>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
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





@endpush