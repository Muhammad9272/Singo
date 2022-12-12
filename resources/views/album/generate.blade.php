@extends('layouts.app')
@push('page_css')
<link rel="stylesheet" href="{{ asset('assets/vendor/data-table/css/jquery.dataTables.min.css') }}">
<link rel="stylesheet" href="{{ asset('assets/vendor/data-table/css/buttons.dataTables.min.css') }}">
<style>
    .form-control{
        width: auto !important;
    }
</style>
@endpush
@section('content')
    <div class="container-fluid">
        <div class="row content-header mb-2">
            <h1> Album file </h1>
        </div>
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">Generate Release</div>
                    <div class="card-body table-responsive">
                    @if(session('success'))
                        <p class="alert alert-success text-center">
                            {{ session('success') }}
                        </p>
                    @elseif(session('error'))
                        <p class="alert alert-danger text-center">
                            {{ session('error') }}
                        </p>
                    @endif

                        <div class="table-responsive">
                            <table class="table table-dark table-striped table-bordered display "  id = "table">
                                <thead>
                                    <tr>
                                        <th class = "text-centeralign-middle pl-2">Release Name</th>
                                        <th class = "text-centeralign-middle pl-2">Release Meta Language</th>
                                        <th class = "text-centeralign-middle pl-2">Orchard Artist</th>
                                        <th class = "text-centeralign-middle pl-2">Artist Country</th>
                                        <th class = "text-centeralign-middle pl-2">Subaccount Name</th>
                                        <th class = "text-centeralign-middle pl-2">Artist URL</th>
                                        <th class = "text-centeralign-middle pl-2">Release Artist(s)-Primary Artist(s)</th>
                                        <th class = "text-centeralign-middle pl-2">Release Artist(s)-Featuring(s)</th>
                                        <th class = "text-centeralign-middle pl-2">Release Artist(s)-Remixer(s)</th>
                                        <th class = "text-centeralign-middle pl-2">Release Artist(s)-Producer(s)</th>
                                        <th class = "text-centeralign-middle pl-2">Release Artist(s)-Composer(s)</th>
                                        <th class = "text-centeralign-middle pl-2">Release Artist(s)-Orchestra(s)</th>
                                        <th class = "text-centeralign-middle pl-2">Release Artist(s)-Ensemble(s)</th>
                                        <th class = "text-centeralign-middle pl-2">Release Artist(s)-Conductor(s)</th>
                                        <th class = "text-centeralign-middle pl-2">Release Date</th>
                                        <th class = "text-centeralign-middle pl-2">Sale Start Date</th>
                                        <th class = "text-centeralign-middle pl-2">iTunes Pre-Order</th>
                                        <th class = "text-centeralign-middle pl-2">iTunes Pre-Order Date</th>
                                        <th class = "text-centeralign-middle pl-2">Preorder Preview</th>
                                        <th class = "text-centeralign-middle pl-2">Album Pricing</th>
                                        <th class = "text-centeralign-middle pl-2">Format: Full Length / EP / Single</th>
                                        <th class = "text-centeralign-middle pl-2">Imprint</th>
                                        <th class = "text-centeralign-middle pl-2">Genre</th>
                                        <th class = "text-centeralign-middle pl-2">Sub-genre</th>
                                        <th class = "text-centeralign-middle pl-2">[C] Information</th>
                                        <th class = "text-centeralign-middle pl-2">P-Line</th>
                                        <th class = "text-centeralign-middle pl-2">Digital UPC</th>
                                        <th class = "text-centeralign-middle pl-2">Manufacturer's UPC</th>
                                        <th class = "text-centeralign-middle pl-2">Folder Name / Project Code</th>
                                        <th class = "text-centeralign-middle pl-2">Product Code</th>
                                        <th class = "text-centeralign-middle pl-2">Release Version</th>
                                        <th class = "text-centeralign-middle pl-2">File Name</th>
                                        <th class = "text-centeralign-middle pl-2">Volume</th>
                                        <th class = "text-centeralign-middle pl-2">Track No.</th>
                                        <th class = "text-centeralign-middle pl-2">Track Name</th>
                                        <th class = "text-centeralign-middle pl-2">Track Audio Language</th>
                                        <th class = "text-centeralign-middle pl-2">Version</th>
                                        <th class = "text-centeralign-middle pl-2">Track Artist</th>
                                        <th class = "text-centeralign-middle pl-2">Track Artist(s) - Featuring(s)</th>
                                        <th class = "text-centeralign-middle pl-2">Track Artist(s) - Remixer(s)</th>
                                        <th class = "text-centeralign-middle pl-2">Track Artist(s) - Producer(s)</th>
                                        <th class = "text-centeralign-middle pl-2">Track Artist(s) - Composer(s)</th>
                                        <th class = "text-centeralign-middle pl-2">Track Artist(s) - Orchestra(s)</th>
                                        <th class = "text-centeralign-middle pl-2">Track Artist(s) - Ensemble(s)</th>
                                        <th class = "text-centeralign-middle pl-2">Track Artist(s) - Conductor(s)</th>
                                        <th class = "text-centeralign-middle pl-2">Track Pricing</th>
                                        <th class = "text-centeralign-middle pl-2">Explicit (No/Yes/Clean)</th>
                                        <th class = "text-centeralign-middle pl-2">ISRC</th>
                                        <th class = "text-centeralign-middle pl-2">3rd Party Publisher? (Yes/No)</th>
                                        <th class = "text-centeralign-middle pl-2">[P] Information</th>
                                        <th class = "text-centeralign-middle pl-2">Ownership For This Sound Recording</th>
                                        <th class = "text-centeralign-middle pl-2">Country of Recording</th>
                                        <th class = "text-centeralign-middle pl-2">Nationality of Original Copyright Owner</th>
                                        <th class = "text-centeralign-middle pl-2">Track Lyrics</th>
                                        <th class = "text-centeralign-middle pl-2">Songwriter(s)</th>
                                        <th class = "text-centeralign-middle pl-2">Publisher(s)</th>
                                        <th class = "text-centeralign-middle pl-2">Performer 1 Type</th>
                                        <th class = "text-centeralign-middle pl-2">Performer 1 Legal Name</th>
                                        <th class = "text-centeralign-middle pl-2">Performer 1 Main Role</th>
                                        <th class = "text-centeralign-middle pl-2">Performer 2 Type</th>
                                        <th class = "text-centeralign-middle pl-2">Performer 2 Legal Name</th>
                                        <th class = "text-centeralign-middle pl-2">Performer 2 Main Role</th>
                                        <th class = "text-centeralign-middle pl-2">Performer 3 Type</th>
                                        <th class = "text-centeralign-middle pl-2">Performer 3 Legal Name</th>
                                        <th class = "text-centeralign-middle pl-2">Performer 3 Main Role</th>
                                        <th class = "text-centeralign-middle pl-2">Performer 4 Type</th>
                                        <th class = "text-centeralign-middle pl-2">Performer 4 Legal Name</th>
                                        <th class = "text-centeralign-middle pl-2">Performer 4 Main Role</th>
                                        <th class = "text-centeralign-middle pl-2">Performer 5 Type</th>
                                        <th class = "text-centeralign-middle pl-2">Performer 5 Legal Name</th>
                                        <th class = "text-centeralign-middle pl-2">Performer 5 Main Role</th>
                                        <th class = "text-centeralign-middle pl-2">Only Include/Only Exclude?</th>
                                        <th class = "text-centeralign-middle pl-2">Territories (ISO Codes)</th>
                                    </tr>
                                </thead>
                                <tbody>

                                @foreach($albums as $key2 => $album)
                                @php
                                    $key2++;
                                @endphp
                                    @foreach($album->songs()->get() as $key1 => $song)
                                    @php
                                        $key1++;
                                    @endphp
                                    <tr>
                                        <td  class = "align-middle pl-2"> {{ $album->title }}</td>
                                        <td  class = "align-middle pl-2"> English </td>
                                        <td  class = "align-middle pl-2"> {{ $album->artistName }}</td>
                                        <td  class = "align-middle pl-2"> Germany</td>
                                        <td  class = "align-middle pl-2"> </td>
                                        <td  class = "align-middle pl-2"> </td>
                                        <td  class = "align-middle pl-2"> {{ $album->artistName }} </td>
                                        <td  class = "align-middle pl-2"> </td>
                                        <td  class = "align-middle pl-2"> </td>
                                        <td  class = "align-middle pl-2"> </td>
                                        <td  class = "align-middle pl-2"> </td>
                                        <td  class = "align-middle pl-2"> </td>
                                        <td  class = "align-middle pl-2"> </td>
                                        <td  class = "align-middle pl-2"> </td>
                                        <td  class = "align-middle pl-2"> {{  date('d.m.y', strtotime($album->release)) }}</td>
                                        <td  class = "align-middle pl-2"> {{  date('d.m.y', strtotime($album->release)) }}</td>
                                        <td  class = "align-middle pl-2"> </td>
                                        <td  class = "align-middle pl-2"> </td>
                                        <td  class = "align-middle pl-2"> </td>
                                        <td  class = "align-middle pl-2"> </td>
                                        <td  class = "align-middle pl-2"> @if ($album->songs()->count() > 1 && $album->songs()->count() < 9) EP @elseif($album->songs()->count() > 9) Full Length @else Single @endif</td>
                                        <td  class = "align-middle pl-2"> Singo.io</td>
                                        <td  class = "align-middle pl-2"> {{ $album->genre->name }}</td>
                                        <td  class = "align-middle pl-2"> {{ $album->genre->name }}</td>
                                        <td  class = "align-middle pl-2"> 2021 Singo.io</td>
                                        <td  class = "align-middle pl-2"> 2021 Singo.io</td>
                                        <td  class = "align-middle pl-2">  </td>
                                        <td  class = "align-middle pl-2">  </td>
                                        <td  class = "align-middle pl-2">  {{ $album->artistName }}</td>
                                        <td  class = "align-middle pl-2">  </td>
                                        <td  class = "align-middle pl-2">  </td>
                                        <td  class = "align-middle pl-2">  @php $file = explode('.', $song->songFile); @endphp {{ $file[0] }}.wav</td>
                                        <td  class = "align-middle pl-2">  {{ $key2 }} </td>
                                        <td  class = "align-middle pl-2">  {{ $key1 }} </td>
                                        <td  class = "align-middle pl-2">  {{ $song->title }} </td>
                                        <td  class = "align-middle pl-2">  @if ( $song->isInstrumental == 1 ) Instrumental @else {{ $song->language }} @endif</td>
                                        <td  class = "align-middle pl-2">  </td>
                                        <td  class = "align-middle pl-2">  </td>
                                        <td  class = "align-middle pl-2">  </td>
                                        <td  class = "align-middle pl-2">  </td>
                                        <td  class = "align-middle pl-2">  </td>
                                        <td  class = "align-middle pl-2">  </td>
                                        <td  class = "align-middle pl-2">  </td>
                                        <td  class = "align-middle pl-2">  </td>
                                        <td  class = "align-middle pl-2">  </td>
                                        <td  class = "align-middle pl-2">  </td>
                                        <td  class = "align-middle pl-2">  @if ( $song->isExplicit == 1 ) Yes @else No @endif </td>
                                        <td  class = "align-middle pl-2">  {{ $song->isrc ?? '' }}</td>
                                        <td  class = "align-middle pl-2">  No</td>
                                        <td  class = "align-middle pl-2">  2021 Singo.io</td>
                                        <td  class = "align-middle pl-2">  I am the original master copyright owner</td>
                                        <td  class = "align-middle pl-2">  Germany</td>
                                        <td  class = "align-middle pl-2">  Germany</td>
                                        <td  class = "align-middle pl-2">  </td>
                                        <td  class = "align-middle pl-2">  @php $name = explode(' ', $album->user->name); @endphp @if(isset($name[1])) {{ $name[0] }} {{ $name[1] }} @else {{ $name[0] }} singo @endif</td>
                                        <td  class = "align-middle pl-2">  </td>
                                        <td  class = "align-middle pl-2"> Primary Performer  </td>
                                        <td  class = "align-middle pl-2"> @php $name = explode(' ', $album->user->name); @endphp @if(isset($name[1])) {{ $name[0] }} {{ $name[1] }} @else {{ $name[0] }} singo @endif</td>
                                        <td  class = "align-middle pl-2"> Electronics - Sampler </td>
                                        <td  class = "align-middle pl-2">  </td>
                                        <td  class = "align-middle pl-2">  </td>
                                        <td  class = "align-middle pl-2">  </td>
                                        <td  class = "align-middle pl-2">  </td>
                                        <td  class = "align-middle pl-2">  </td>
                                        <td  class = "align-middle pl-2">  </td>
                                        <td  class = "align-middle pl-2">  </td>
                                        <td  class = "align-middle pl-2">  </td>
                                        <td  class = "align-middle pl-2">  </td>
                                        <td  class = "align-middle pl-2">  </td>
                                        <td  class = "align-middle pl-2">  </td>
                                        <td  class = "align-middle pl-2">  </td>
                                        <td  class = "align-middle pl-2">  </td>
                                        <td  class = "align-middle pl-2">  </td>
                                    </tr>

                                    @endforeach
                                @endforeach
                                </tbody>
                            </table>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection


@push('page_scripts')
<script src="{{ asset('assets/vendor/data-table/js/jquery-3.3.1.js') }}"></script>
<script src="{{ asset('assets/vendor/data-table/js/jquery.dataTables.min.js') }}"></script>
<script src="{{ asset('assets/vendor/data-table/js/dataTables.buttons.min.js') }}"></script>
<script src="{{ asset('assets/vendor/data-table/js/buttons.flash.min.js') }}"></script>
<script src="{{ asset('assets/vendor/data-table/js/jszip.min.js') }}"></script>
<script src="{{ asset('assets/vendor/data-table/js/pdfmake.min.js') }}"></script>
<script src="{{ asset('assets/vendor/data-table/js/vfs_fonts.js') }}"></script>
<script src="{{ asset('assets/vendor/data-table/js/buttons.html5.min.js') }}"></script>
<script src="{{ asset('assets/vendor/data-table/js/buttons.print.min.js') }}"></script>
<script>
    $(document).ready(function() {
        $('#table').DataTable( {
            dom: 'Bfrtip',
            buttons: [
                 'pageLength',
                 {
                    extend: 'excelHtml5',

                    title: ''
                }
            ],

        });
    } );
</script>

@endpush
