<select name="{{ $name }}" id="{{ $name }}" class="form-control" {{ $attributes->merge() }}>
    <option value=""> -- {{ __("Select language") }} --</option>
    @foreach($languages as $language)
        <option value="{{ $language->id }}" {{ ($selected == $language->id) ? 'selected="selected"' : '' }}>{{ $language->name }}</option>
    @endforeach
</select>
