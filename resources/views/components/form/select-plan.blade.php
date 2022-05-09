<select {{ $attributes->merge(['class' => 'form-control'])  }}>
    <option value="">-- {{ __('Select Plan') }} --</option>
    @foreach($plans as $plan)
        <option {{ ($value == $plan->id) ? 'selected' : '' }} value="{{ $plan->id }}">{{ $plan->title }}</option>
    @endforeach
</select>
