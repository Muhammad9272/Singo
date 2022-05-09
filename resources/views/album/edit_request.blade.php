@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-10 col-lg-12">
            <div class="card mt-5">
                <div class="card-header">
                    <span class="float-left">
                        <h4 class = "mb-0">Send Edit Request</h4>
                    </span>
                    <span class="float-right">
                        <a href="{{ route('albums') }}" class="btn btn-info btn-sm">Back</a>
                    </span>
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
                    <div class="row">
                        <div class="col-md-8 offset-md-2">
                            <form action="{{ route('album.request.store') }}" method="POST" class="form-horizontal">
                                @csrf
                                <span class = "text-left">
                                    <p>Your albums current status: {{$album->getStatusText()}}<br>
                                    Thats why you need to send a request to edit this album</P>
                                </span>
                                <input type="hidden" name="album_id" value="{{$album->id}}">
                                <div class="form-group">
                                    <label class="orm-control-label"> Describe Your Reason for Edit <span class="text-danger">*</span> </label>                                                                   
                                        <textarea name="reason" value="{{ old('reason') }}" class="form-control form-control-success" required rows="3"></textarea>
                                        @if ($errors->has('reason'))
                                            <span class="text-danger">{{ $errors->first('reason') }}</span>
                                        @endif                                   
                                </div>             
                                <div class="form-group">                                    
                                    <input type="submit" value="Send Request" class="btn btn-primary">                                   
                                </div>
                                <span class = "text-left">
                                    <p>We will review your reason<br>
                                    We will notify you about every updates</P>
                                </span>
                            </form>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>
</div>
@endsection

