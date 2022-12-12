@props([
    'name',
])

<div class="">
    <input type="file" name="{{ $name }}" data-name="{{ $name }}" id="{{ $name }}" {{ $attributes->merge() }}>
{{--    <div class="filepond_custom_ui_container_inner">--}}
{{--        <i class="fas fa-inbox text-xl py-3"></i>--}}
{{--        <h5>Click or drag file to this area to upload</h5>--}}
{{--        <p class="font-weight-light">Support for a single or bulk upload. Strictly prohibit from uploading company data or other band files</p>--}}
{{--    </div>--}}
</div>

@push('page_scripts')
    <script src="https://unpkg.com/filepond/dist/filepond.min.js"></script>
    <script src="https://unpkg.com/filepond-plugin-image-preview/dist/filepond-plugin-image-preview.js"></script>
    <script src="https://unpkg.com/filepond-plugin-image-validate-size/dist/filepond-plugin-image-validate-size.js"></script>
    <script src="https://unpkg.com/filepond-plugin-file-validate-type/dist/filepond-plugin-file-validate-type.js"></script>

    <script>
        const pondInstance = FilePond.create('#{{ $name }}', {
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
    </script>
@endpush
