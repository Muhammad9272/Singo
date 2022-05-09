<select class="form-control" {{ $attributes->merge() }}>
    <option value="0">-- {{ __("All DSPs") }} --</option>
    @foreach($allDsp as $dsp)
        <option value="{{ $dsp['id'] }}">{{ $dsp['name'] }}</option>
    @endforeach
</select>
