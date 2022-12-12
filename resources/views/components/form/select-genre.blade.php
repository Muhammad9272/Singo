<select {{ $attributes->merge(['class' => 'form-control custom-select '])  }}>
    <option value="">-- {{ __('Select Genre') }} --</option>
    @foreach($genres as $genre)
        <option {{ ($value == $genre->id) ? 'selected' : '' }} value="{{ $genre->id }}">{{ $genre->name }}</option>
    @endforeach
</select>
