@extends('layouts.app')

@section('third_party_stylesheets')
  <style type="text/css">

       .propress-infos .p-infos{
            position: absolute;
            z-index: 1;
            width: 9%;
            text-align: end;        
       }
       .propress-infos .top-info{
           margin-top: -60px;
           margin-left: 15px;
       }
       .propress-infos .bottom-info{
            margin-top: 20px;
       }
        .propress-infos .top-info img{
           transition: transform .2s;
           opacity: 0.8;
        }
       .propress-infos .top-info img:hover{
        opacity: 1;
        transform: scale(1.15); 
        cursor: pointer;       
       }
       /*.progress .bottom-info{
            position: absolute;
            margin-top: 20px;
            z-index: 1;
            width: 11%;
            text-align: end;
       }*/
  </style> 
@endsection

@section('content')
    <div class="container-fluid mt-50">

        <h3 class="secondarycolor  mb-50">Reward <span class="text-white">Points</span></h3>
        <div class="progress prog-reward-radius" style="">

          @foreach($rewards as $reward) 
              <div class="progress-bar {{$totalstreamss<$reward->points?'bg-white':'secondarybgcolor'}} " role="progressbar" style="width: 12.5%" aria-valuenow="15" aria-valuemin="0" aria-valuemax="100" >
                    <div class="propress-infos reward-reading" data-toggle="modal" data-target="#rewardmodal" data-name="{{$reward->title}}" data-href="{{route('user.rewards.show',['id'=>$reward->id,'id2'=>'0'] )}}">
                         <p class="p-infos top-info"><img src="{{ asset('image/icons/medal.svg') }}">
                         </p>
                        <p class="p-infos bottom-info">{{$reward->points}}</p>                        
                    </div>               
              </div>
          @endforeach
          {{-- <div class="progress-bar bg-success" role="progressbar" style="width: 12.5%"  aria-valuenow="30" aria-valuemin="0" aria-valuemax="100">
              <div class="propress-infos">
                <p class="p-infos top-info"><img src="{{ asset('image/icons/medal.svg') }}"></p>
                <p class="p-infos bottom-info">100</p>
              </div>
          </div>
          <div class="progress-bar bg-info" role="progressbar" style="width: 12.5%" aria-valuenow="20" aria-valuemin="0" aria-valuemax="100">
              <div class="propress-infos">
                <p class="p-infos top-info"><img src="{{ asset('image/icons/medal.svg') }}"></p>
                <p class="p-infos bottom-info">100</p>
              </div>
          </div>

          <div class="progress-bar" role="progressbar" style="width: 12.5%" aria-valuenow="15" aria-valuemin="0" aria-valuemax="100">
              <div class="propress-infos">
                <p class="p-infos top-info"><img src="{{ asset('image/icons/medal.svg') }}"></p>
                <p class="p-infos bottom-info">100</p>
              </div>
          </div>
          <div class="progress-bar bg-success" role="progressbar" style="width: 12.5%"  aria-valuenow="30" aria-valuemin="0" aria-valuemax="100">
              <div class="propress-infos">
                <p class="p-infos top-info"><img src="{{ asset('image/icons/medal.svg') }}"></p>
                <p class="p-infos bottom-info">100</p>
              </div>
          </div>
          <div class="progress-bar bg-info" role="progressbar" style="width: 12.5%" aria-valuenow="20" aria-valuemin="0" aria-valuemax="100">
              <div class="propress-infos">
                <p class="p-infos top-info"><img src="{{ asset('image/icons/medal.svg') }}"></p>
                <p class="p-infos bottom-info">100</p>
              </div>
          </div>


          <div class="progress-bar bg-success" role="progressbar" style="width: 12.5%"  aria-valuenow="30" aria-valuemin="0" aria-valuemax="100">
              <div class="propress-infos">
                <p class="p-infos top-info"><img src="{{ asset('image/icons/medal.svg') }}"></p>
                <p class="p-infos bottom-info">100</p>
              </div>
          </div>
          <div class="progress-bar bg-info" role="progressbar" style="width: 12.5%" aria-valuenow="20" aria-valuemin="0" aria-valuemax="100">
              <div class="propress-infos">
                <p class="p-infos top-info"><img src="{{ asset('image/icons/medal.svg') }}"></p>
                <p class="p-infos bottom-info">100</p>
              </div>
          </div> --}}


        </div>

        <div class="row mt-50 cus-r-mar">
            @foreach($rewards as $reward)
            <div class="col-md-3">
                <div class="reward-card">
                    @if($totalstreamss<$reward->points)
                        <div class="ab-locker">
                           <img src="{{ asset('image/icons/rw-lock.png') }}" class="w-100">
                        </div>
                    @endif
                    <div class="reward-card-img {{$totalstreamss<$reward->points?'d-opacity':''}} "><img src="{{ asset($reward->photo) }}" class="w-100">
                    </div>
                    <div class="reward-card-content {{$totalstreamss<$reward->points?'d-opacity':''}}">
                        <p class="mb-0">{{$reward->title}}</p>
                        <p class="mb-0 text-grey1">{{$reward->subtitle}}</p>
                        <div class="d-flex justify-content-between mt-4">

                            <p class="mb-0 text-grey1">{{$reward->points}} <img src="{{ asset('image/icons/medal.png') }}">
                             
                             @php
                              $rewardrq=App\Models\RewardRequest::where('user_id',Auth::id())->where('reward_id',$reward->id)->first();
                             @endphp
                             @if(isset($rewardrq) )
                             {{-- <a href="{{ route('user.rewards.create', $reward->id) }}" class="claim-bdg">Claimed</a> --}}
                             <a href="javascript:;"  class="claim-bdg">Claimed</a>
                             @elseif($totalstreamss<$reward->points)
                              <a href="javascript:;" class="claim-bdg">Claim</a>
                             @else
                              <a href="javascript:;" data-name="{{$reward->title}}" data-href="{{route('user.rewards.show',['id'=>$reward->id,'id2'=>'2'] )}}" class="claim-bdg reward-reading" data-toggle="modal" data-target="#rewardmodal">Claim</a>
                             @endif

                            </p>
                            <a href="javascript:;" data-name="{{$reward->title}}" data-href="{{route('user.rewards.show',['id'=>$reward->id,'id2'=>'1'] )}}" class="secondarycolor singo-anchor f-sz-15 reward-reading" data-toggle="modal" data-target="{{$totalstreamss<$reward->points?'':'#rewardmodal'}}"> Reading</a>
                        </div>
                    </div>
                </div>
            </div>      
            @endforeach
            {{-- <div class="col-md-3">
                <div class="reward-card">
                    <div class="ab-locker">
                       <img src="{{ asset('image/icons/rw-lock.png') }}" class="w-100">
                    </div>
                    <div class="reward-card-img d-opacity">
                        <img src="{{ asset('image/icons/rwig.png') }}" class="w-100">
                    </div>
                    <div class="reward-card-content d-opacity">
                        <p class="mb-0">Donec semper</p>
                        <p class="mb-0 text-grey1">Phasellus vel tristique</p>
                        <div class="d-flex justify-content-between mt-3">
                            <p class="mb-0 text-grey1">100 <img src="{{ asset('image/icons/medal.png') }}">
                             <a href="" class="claim-bdg">Claim</a>
                            </p>
                            <a href="" class="secondarycolor singo-anchor"> Reading</a>
                        </div>
                    </div>

                </div>
            </div>
            <div class="col-md-3">
                <div class="reward-card">
                    <div class="ab-locker">
                       <img src="{{ asset('image/icons/rw-lock.png') }}" class="w-100">
                    </div>
                    <div class="reward-card-img d-opacity">
                        <img src="{{ asset('image/icons/rwig.png') }}" class="w-100">
                    </div>
                    <div class="reward-card-content d-opacity">
                        <p class="mb-0">Donec semper</p>
                        <p class="mb-0 text-grey1">Phasellus vel tristique</p>
                        <div class="d-flex justify-content-between mt-3">
                            <p class="mb-0 text-grey1">100 <img src="{{ asset('image/icons/medal.png') }}">
                             <a href="" class="claim-bdg">Claim</a>
                            </p>
                            <a href="" class="secondarycolor singo-anchor"> Reading</a>
                        </div>
                    </div>

                </div>
            </div>
            <div class="col-md-3">
                <div class="reward-card">
                    <div class="ab-locker">
                       <img src="{{ asset('image/icons/rw-lock.png') }}" class="w-100">
                    </div>
                    <div class="reward-card-img d-opacity">
                        <img src="{{ asset('image/icons/rwig.png') }}" class="w-100">
                    </div>
                    <div class="reward-card-content d-opacity">
                        <p class="mb-0">Donec semper</p>
                        <p class="mb-0 text-grey1">Phasellus vel tristique</p>
                        <div class="d-flex justify-content-between mt-3">
                            <p class="mb-0 text-grey1">100 <img src="{{ asset('image/icons/medal.png') }}">
                             <a href="" class="claim-bdg">Claim</a>
                            </p>
                            <a href="" class="secondarycolor singo-anchor"> Reading</a>
                        </div>
                    </div>

                </div>
            </div>
            <div class="col-md-3">
                <div class="reward-card">
                    <div class="ab-locker">
                       <img src="{{ asset('image/icons/rw-lock.png') }}" class="w-100">
                    </div>
                    <div class="reward-card-img d-opacity">
                        <img src="{{ asset('image/icons/rwig.png') }}" class="w-100">
                    </div>
                    <div class="reward-card-content d-opacity">
                        <p class="mb-0">Donec semper</p>
                        <p class="mb-0 text-grey1">Phasellus vel tristique</p>
                        <div class="d-flex justify-content-between mt-3">
                            <p class="mb-0 text-grey1">100 <img src="{{ asset('image/icons/medal.png') }}">
                             <a href="" class="claim-bdg">Claim</a>
                            </p>
                            <a href="" class="secondarycolor singo-anchor"> Reading</a>
                        </div>
                    </div>

                </div>
            </div> --}}


        </div>



    </div>







<!-- Modal Rewards -->
<div class="modal fade" id="rewardmodal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true" style="z-index:11111111">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Reward</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body reward-md-body">
             {{-- 10 shirts free --}}
      </div>
      
    </div>
  </div>
</div>


@endsection



@push('page_scripts')
 
 <script type="text/javascript">
    $(document).on('click','.reward-reading',function(){
      // $('#product_list_modal').removeClass('show');      
      $('#rewardmodal').find('.modal-title').html($(this).attr('data-name'));
      $('#rewardmodal .modal-body').html('').load($(this).attr('data-href'),function(response, status, xhr){
        });
    });
    
    $(document).on('submit','.rw-address-form',function(){
          $('.changerwbtntext').text('Claiming ...');
    });
    
 </script>
@endpush

@section('third_party_scripts')

@endsection
