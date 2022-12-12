@extends('layouts.app')
@push('page_css')
    <link href="https://unpkg.com/filepond@^4/dist/filepond.css" rel="stylesheet"/>
    <link href="https://unpkg.com/filepond-plugin-image-preview/dist/filepond-plugin-image-preview.css" rel="stylesheet"/>

    <style>
        .ck-editor__editable_inline, #message {
            min-height: 400px;
        }

        .w-45 {
            width: 45% !important;
        }
        .ck.ck-editor__main>.ck-editor__editable:not(.ck-focused) {
            border-color: #E7ECEE !important;
            border-bottom-left-radius: 10px !important;
            border-bottom-right-radius: 10px !important;
        }

        .ck.ck-toolbar {
            border-color: #E7ECEE !important;
            border-top-left-radius: 10px !important;
            border-top-right-radius: 10px !important;
        }

        .ck-rounded-corners .ck.ck-editor__main>.ck-editor__editable, .ck.ck-editor__main>.ck-editor__editable.ck-rounded-corners {
            border-bottom-left-radius: 10px !important;
            border-bottom-right-radius: 10px !important;
        }

        .filepond--panel-root {
            background: white !important;
        }
    </style>
@endpush

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header border-0">
                        <span class="float-left">
                            Ticket System by Singo.io
                        </span>
                        <span class="float-right">
                            <a href="javascript:void(0);" class="btn btn-success">
                                <i class="fas fa-plus mr-2"></i>
                                Create a ticket
                            </a>
                        </span>
                    </div>

                    <div class="row pl-5 py-5">
                        <div class="col-md-8">
                            <form method="post" action="{{ route('ticket.create.store') }}">
                                @csrf
                                <div class="form-group row">
                                    <div class="col-md-6">
                                        <label for="name">Name</label>
                                        <input type="text" name="name" value="{{ $user->name }}" id="name" class="form-control" disabled>
                                        <small id="name_note" class="form-text text-muted"></small>
                                    </div>
                                    <div class="col-md-6">
                                        <label for="email">Email</label>
                                        <input type="email" name="email" value="{{ $user->email }}" id="email" class="form-control" disabled>
                                        <small id="email_note" class="form-text text-muted"></small>
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <div class="col-md-6">
                                        <label for="ticketType">Ticket Type</label>
                                        <select name="ticketType" id="ticketType" class="form-control" required>
                                            <option selected hidden value="">Choose...</option>
                                            <option value="1">Technical</option>
                                            <option value="2">General</option>
                                        </select>
                                        <small id="ticketType_note" class="form-text text-muted"></small>
                                    </div>
                                    <div class="col-md-6">
                                        <label for="priority">Priority</label>
                                        <select name="priority" id="priority" class="form-control" required>
                                            <option selected hidden value="">Choose...</option>
                                            <option value="1">High</option>
                                            <option value="2">Medium</option>
                                            <option value="3">Low</option>
                                        </select>
                                        <small id="priority_note" class="form-text text-muted"></small>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label for="subject">Subject</label>
                                    <input type="text" name="subject" value="" id="subject" class="form-control" required>
                                    <small id="subject_note" class="form-text text-muted"></small>
                                </div>

                                <div class="form-group">
                                    <label for="message">Message</label>
                                    <textarea name="message" id="message" class="form-control"></textarea>
                                    <small id="mssage_note" class="form-text text-muted"></small>
                                </div>

                                <div class="">
                                    <label for="file">Attach file</label>
                                    <div class="filepond_custom_ui_container mb-5">
                                        <input type="file" class="" name="file" data-name="file" id="file">

                                        <div class="filepond_custom_ui_container_inner">
                                            <i class="fas fa-inbox text-xl py-3"></i>
                                            <h5>Click or drag file to this area to upload</h5>
                                            <p class="font-weight-light">Support for a single or bulk upload. Strictly prohibit from uploading company data or other band files</p>
                                        </div>
                                    </div>
                                    <small id="file_note" class="form-text text-muted"></small>
                                </div>

                                <div class="form-group mt-4 mb-2">
                                    <button type="submit" class="btn btn-dark-purple px-6">Submit</button>
                                    <button type="reset" class="btn text-danger px-5">Reset</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@push('page_scripts')
    <script src="{{ asset('assets/vendor/data-table/js/jquery-3.3.1.js') }}"></script>
    <script src="{{ asset('assets/vendor/ckeditor5_more/build/ckeditor.js') }}"></script>

    <script>
        $(document).ready(function () {
            ClassicEditor.create(document.querySelector('#message'), {
                toolbar: {
                    items: [
                        'heading',
                        '|',
                        'bold',
                        'italic',
                        'fontBackgroundColor',
                        'fontColor',
                        'fontFamily',
                        'fontSize',
                        'highlight',
                        'link',
                        '|',
                        'bulletedList',
                        'numberedList',
                        '|',
                        'alignment',
                        '|',
                        'undo',
                        'redo'
                    ]
                },
                language: 'en',
                licenseKey: '',
            });
        });
    </script>

    <script src="https://unpkg.com/filepond/dist/filepond.min.js"></script>
    <script src="https://unpkg.com/filepond-plugin-image-preview/dist/filepond-plugin-image-preview.js"></script>
    <script src="https://unpkg.com/filepond-plugin-image-validate-size/dist/filepond-plugin-image-validate-size.js"></script>
    <script src="https://unpkg.com/filepond-plugin-file-validate-type/dist/filepond-plugin-file-validate-type.js"></script>
    <script src="https://unpkg.com/filepond-plugin-image-transform/dist/filepond-plugin-image-transform.js"></script>
    <script src="https://unpkg.com/filepond-plugin-file-metadata/dist/filepond-plugin-file-metadata.js"></script>
    <script>
        FilePond.registerPlugin(FilePondPluginImagePreview);
        FilePond.registerPlugin(FilePondPluginImageValidateSize);
        FilePond.registerPlugin(FilePondPluginFileValidateType);
        FilePond.registerPlugin(FilePondPluginImageTransform);

        // Get a reference to the file input element
        const inputElement_cover = document.getElementById('file');
        // Create a FilePond instance
        const pondInstance = FilePond.create(inputElement_cover, {
            acceptedFileTypes: ['image/*'],
            imageTransformOutputMimeType: 'image/jpeg',
        });
        var name = inputElement_cover.dataset.name;
        var _url = ("{{ route('ajax.upload', ['name']) }}");
        var __url = _url.replace('name', name);
        pondInstance.setOptions({
            server: {
                url: __url,
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                }
            },
        });

        $('.filepond_custom_ui_container_inner').click(() => {
            pondInstance.browse();
        });

        const pondDOM = document.querySelector('.filepond--root');

        // listen for events
        pondDOM.addEventListener('FilePond:addfile', (e) => {
            $('.filepond_custom_ui_container_inner').hide();
        });
        pondDOM.addEventListener('FilePond:removefile', (e) => {
            if (pondInstance.getFiles().length === 0) {
                $('.filepond_custom_ui_container_inner').show();
            }
        });

        for (let el of document.querySelectorAll('.filepond--credits')) el.style.visibility = 'hidden';
    </script>
@endpush
