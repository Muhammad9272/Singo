@extends('layouts.app')

@section('content')
    <div class="container-fluid">
        <div class="row content-header mb-2">
            <div class="col-md-12">
                <h1>Imports</h1>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title mb-0">Create Import</h4>
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
                        <form action="{{ route('admin.imports.store') }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            <div class="form-group">
                                <label for="">Import Name</label>
                                <input type="text" class="form-control" name="name" required>
                            </div>
                            <div class="form-group">
                                <label for="">Import Type</label>
                                <select name="type" id="type" class="form-control" required>
                                    <option value="">-- Select Type --</option>
                                    <option value="1">User Payment Report - Orchard</option>
                                    <option value="2">User Payment Report - Fuga</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="">Sheet Rate</label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text">1$ = </span>
                                    </div>
                                    <input name="rate" type="number" class="form-control" id="rate" step="0.001" required>
                                </div>
                                <span class="d-block form-control-feedback warning-feedback">If sheet column is in EUR, search 1$ in eur and write the result in the field. (Not visible to users)</span>
                            </div>
                            <div class="form-group">
                                <label for="">Upload Import</label>
                                <div class="input-group">
                                    <div class="custom-file">
                                        <input name="import_file" accept=".csv" type="file" class="custom-file-input" id="exampleInputFile" required>
                                        <label class="custom-file-label" for="exampleInputFile">Choose file</label>
                                    </div>
                                </div>
                                <span class="d-block form-control-feedback warning-feedback">Be sure currency field values are in USD format.</span>
                            </div>

                            <div>
                                <button class="btn btn-success" type="submit">Submit</button>
                                <button class="btn btn-warning" type="reset">Reset</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('page_scripts')
    <script>
        $('#type').change(() => {
            var selectedType = $('#type option:selected').val()
            var toCurrency = '';
            if(selectedType == '1') {
                toCurrency = 'DKK';
            } else if(selectedType == '2') {
                toCurrency = 'EUR';
            }
            $.ajax({
                type: 'GET',
                crossDomain: true,
                dataType: 'jsonp',
                url: `https://free.currconv.com/api/v7/convert?q=USD_${toCurrency}&compact=ultra&apiKey=3d21d7131f324bacae9c`,
                success: function(jsondata){
                    $("#rate").val(Object.values(jsondata)[0].toFixed(2))
                }
            })
        });
    </script>
@endpush
