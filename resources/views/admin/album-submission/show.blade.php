@extends('layouts.app')

@section('content')
    <div class="container-fluid custt-back">
        <div class="row content-header mb-2">
            <div class="col-md-12">
                <h1>Album Submission Log #{{ $submission->id }}</h1>
            </div>
        </div>

        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title mb-0">
                            Album Submission Log #{{ $submission->id }}
                        </h4>
                    </div>
                    <div class="card-body">
                        <table class="table">
                            <tr>
                                <th>Status</th>
                                <td>{{ \App\Models\AlbumSubmission::PUBLISH_STATUSES[$submission->status] }}</td>
                            </tr>

                            <tr>
                                <th>Publisher</th>
                                <td>{{ \App\Models\Album::PUBLISHERS[$submission->publisher] }}</td>
                            </tr>

                            <tr>
                                <th>Publisher Album ID</th>
                                <td>{{ $submission->publisher_album_id }}</td>
                            </tr>

                            <tr>
                                <th colspan="2" class="text-center">Logs</th>
                            </tr>
                            <tr>
                                <td colspan="2" class="px-0">
                                    <ul class="list-group">
                                        @foreach($submission->logs as $log)
                                            <li class="list-group-item">
                                                <div class="d-flex w-100 justify-content-between">
                                                    <h5 class="mb-1">{{ $log['STEP_NAME'] }}</h5>
                                                    <small>
                                                        @if($log['STEP_STATUS'])
                                                            <i class="fas fa-check-circle text-lg text-success"></i>
                                                        @else
                                                            <i class="fas fa-times text-lg text-danger"></i>
                                                        @endif
                                                    </small>
                                                </div>
                                                <p class="mb-1">
                                                    {{ $log['STEP_MESSAGE'] }}
                                                </p>
                                            </li>
                                        @endforeach
                                    </ul>
                                </td>
                            </tr>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

