<select name="{{ $name }}" id="{{ $name }}" class="form-control custom-select " {{ $attributes->merge() }}>
    <option value=""> -- {{ __("Select audio language") }} --</option>
    @foreach($audioLocales as $audioLocale)
        <option value="{{ $audioLocale->id }}"  {{ ($selected == $audioLocale->name) ? 'selected="selected"' : '' }}>{{ $audioLocale->name }}</option>
    @endforeach
</select>
