{{-- <select class="form-control custom-select " {{ $attributes->merge() }}> --}}
<select class="form-control custom-select select2custom select2-hidden-accessible" style="width: 100%;" tabindex="-1" aria-hidden="true" {{ $attributes->merge() }}>
    <option value="0" data-image="{{ asset('image/icons/c_icon1.png') }}" > {{ __("All DSPs") }} --</option>
    @foreach($allDsp as $dsp)
        <option value="{{ $dsp['id'] }}" data-image="
        {{ asset("image/icons/album/".strtoupper( str_replace(' ', '', $dsp['name']) ).".svg") }}">{{$dsp['name']}}</option>
    @endforeach
</select>
{{-- <div class="dropdown dsp-dropdown">
  <button class="btn btn-default dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" >Image Droprdown
    <span class="caret"></span>
  </button>
  <ul class="dropdown-menu">
    @foreach($allDsp as $dsp)
    <li>
      <a href="#">
        <img src="{{ asset('image/icons/c_icon1.png') }}" width="17px" />{{ $dsp['name'] }}</a>
    </li>
    @endforeach

  </ul>
</div> --}}