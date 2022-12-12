@extends('layouts.app')
@push('page_css')
    <link rel="stylesheet" href="{{ asset('assets/vendor/data-table/css/jquery.dataTables.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/vendor/data-table/css/buttons.dataTables.min.css') }}">
    <link href="https://unpkg.com/filepond@^4/dist/filepond.css" rel="stylesheet" />
    <link href="https://unpkg.com/filepond-plugin-image-preview/dist/filepond-plugin-image-preview.css" rel="stylesheet" />

    <style>
        .ck-editor__editable_inline,
        #message {
            min-height: 200px;
        }

        .w-45 {
            width: 45% !important;
        }

        .comment-section {
            display: block;
            border-radius: 11px;
            box-shadow: 0 4px 8px 0 rgba(0, 0, 0, 0.2), 0 6px 20px 0 rgba(0, 0, 0, 0.19);
            margin-top: 5%;
            margin-bottom: 5%;
            max-width: 700px;
            margin-left: auto;
            margin-right: auto
        }

        .reply-section {
            display: block;
            border-radius: 11px;
            box-shadow: 0 4px 8px 0 rgba(0, 0, 0, 0.2), 0 6px 20px 0 rgba(0, 0, 0, 0.19);
            margin-left: auto;
            margin-right: auto
        }

        .name {
            font-size: 20px
        }

        .comment-content {
            font-size: 14px
        }

        .comments {
            color: blue
        }
        .popup{
            cursor: pointer;
        }

    </style>
@endpush

@section('content')
    @foreach ($ticket as $tk)
        <div class="container-fluid orig-dtb">
            <div class="row content-header mb-2">
                <h1>

                    #{{ $tk->id }} {{ $tk->subject }}

                </h1>
            </div>
            <div class="row">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-header">
                            <div class="row">
                                <div class="col-md-3 text-center">
                                    <h6>Ticket by Singo.io</h6>
                                </div>
                                <div class="col-md-3">
                                </div>
                                <div class="col-md-3">
                                </div>
                                <div class="col-md-3 text-center">
                                    <a href="{{ route('ticket.close', $tk->id) }}" class="btn btn-success">
                                        <i class="fas fa-check mr-2"></i>
                                        Mark as Closed
                                    </a>
                                </div>
                            </div>
                        </div>


                        <section>
                            <div class="mt-2">
                                @foreach ($tk->messages as $msg)
                                    <div
                                        class="d-flex justify-content-{{ $msg->type == 2 ? 'end' : 'start' }} ml-2 mr-2 row">
                                        <div class="col-md-6">
                                            <div class="comment-section">
                                                <div class="d-flex flex-row user p-2">
                                                    <div class="d-flex flex-column ml-2"><span
                                                            class="name font-weight-bold">{{ $msg->user()->get()->first()->name }}</span><span>{{ date('h:i A, M.d', strtotime($msg->created_at)) }}</span>
                                                    </div>
                                                </div>
                                                <div class="mt-2 p-2">
                                                    <p class="comment-content text-justify">{!! html_entity_decode($msg->message) !!}</p>
                                                </div>
                                                @if ($msg->files)
                                                    <div class="d-flex justify-content-between p-3 border-top">

                                                        <img class="popup"
                                                            src="{{ \Illuminate\Support\Facades\Storage::url($msg->files) }}"
                                                            height="15%" width="25%">

                                                    </div>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                            <form method="post" action="{{ route('ticket.message.store') }}">
                                @csrf
                                <input type="hidden" name="ticket_id" value="{{ $tk->id }}">
                                <div class="mb-3 ml-3 mr-3">
                                    <div class="reply-section ml-2 mr-2">
                                        <div class="">
                                            <div class="">
                                                <div class="d-flex flex-row user p-2">
                                                    <div class="ml-2">Send Reply<span class="ml-2 text-info"><i
                                                                class="fas fa-paper-plane"></i></span></div>
                                                </div>
                                                <div class="mt-2 p-2">
                                                    <textarea name="message" id="message"></textarea>
                                                </div>

                                                <div class="p-2">
                                                    <label for="file">Attach file</label>
                                                    <input type="file" class="" name="file" data-name="file"
                                                        id="file">
                                                    <small id="file_note" class="form-text text-muted"></small>
                                                </div>

                                                <div class="d-flex justify-content-center p-3 border-top"><span>
                                                        <input type="submit" class="btn btn-primary w-100 pl-2 pr-2"
                                                            value="Send Reply">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </form>


                        </section>



                    </div>
                </div>
            </div>
        </div>



        <button class="d-none" id="imagebtn" data-toggle="modal" data-target="#imagemodal"></button>



        <!-- Creates the bootstrap modal where the image will appear -->
        <div class="modal fade" id="imagemodal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel"
            aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal"><span
                                aria-hidden="true">&times;</span></button>
                    </div>
                    <div class="modal-body">
                        <img src="" id="imagepreview" style="width:100%;height:100%;object-fit:cover;">
                    </div>
                </div>
            </div>
        </div>
    @endforeach
@endsection
@push('page_scripts')
    <script src="{{ asset('assets/vendor/data-table/js/jquery-3.3.1.js') }}"></script>
    <script src="{{ asset('assets/vendor/data-table/js/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('assets/vendor/data-table/js/dataTables.buttons.min.js') }}"></script>
    <script src="{{ asset('assets/vendor/data-table/js/buttons.html5.min.js') }}"></script>
    <script src="{{ asset('assets/vendor/ckeditor5_more/build/ckeditor.js') }}"></script>


    <script>
        $(document).ready(function() {
            $('#tableOne').DataTable({
                dom: 'Bfrtip',
                buttons: [
                    'pageLength',
                ]
            });


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

            $(".popup").on("click", function() {
                $('#imagepreview').attr('src', $(this).attr(
                'src')); // here asign the image to the modal when the user click the enlarge link
                $('#imagebtn')
            .click(); // imagemodal is the id attribute assigned to the bootstrap modal, then i use the show function
            });

        });
    </script>

    <script src="https://unpkg.com/filepond/dist/filepond.min.js"></script>
    <script src="https://unpkg.com/filepond-plugin-image-preview/dist/filepond-plugin-image-preview.js"></script>
    <script src="https://unpkg.com/filepond-plugin-image-validate-size/dist/filepond-plugin-image-validate-size.js">
    </script>
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
        const pond_cover = FilePond.create(inputElement_cover, {
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
@endpush
