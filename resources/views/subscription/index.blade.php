@extends('layouts.app')
@push('page_css')
<style>
    .status {
    position: relative;
    display: inline-block;
    width: 60px;
    height: 34px;
    }

    .status input {
    opacity: 0;
    width: 0;
    height: 0;
    }

    .status_slider {
    position: absolute;
    cursor: pointer;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background-color: #f30049;
    -webkit-transition: .4s;
    transition: .4s;
    }

    .status_slider:before {
    position: absolute;
    content: "";
    height: 26px;
    width: 26px;
    left: 4px;
    bottom: 4px;
    background-color: white;
    -webkit-transition: .4s;
    transition: .4s;
    }

    input:checked + .status_slider {
    background-color: #23f381;
    }

    input:focus + .status_slider {
    box-shadow: 0 0 1px #23f381;
    }

    input:checked + .status_slider:before {
    -webkit-transform: translateX(26px);
    -ms-transform: translateX(26px);
    transform: translateX(26px);
    }

    /* Rounded sliders */
    .status_slider.round {
    border-radius: 34px;
    }

    .status_slider.round:before {
    border-radius: 50%;
    }
</style>
@endpush

@section('content')
    <div class="container-fluid" id="close_class">
        <div class="row content-header mb-2">
            <h1>Subscriptions</h1>
        </div>
        @if(session('success'))
            <p class="alert alert-success text-center mt-2">
                {{ session('success') }}
            </p>
        @elseif(session('error'))
            <p class="alert alert-danger text-center mt-2">
                {{ session('error') }}
            </p>
        @endif
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <span class="float-left">
                            <h4>All Plan</h4>
                        </span>
                        <span class="float-right">
                            <a href="#" class="btn btn-sm btn-primary" class="btn btn-primary" data-toggle="modal" data-target="#add_new_plan">Add new plan</a>
                        </span>
                    </div>
                    <div class="card-body">
                        <div class="row">
                        @foreach($plans as $plan)
                            <div class="col-12 col-sm-4 col-md-4 d-flex align-items-stretch flex-column">
                                <div class="card d-flex flex-fill" style="border-color: {{ $plan->button }}">
                                    <div class="card-header text-muted border-bottom-0">

                                        <span class="float-left" style="margin-top: 6px;">
                                            {{ $plan->title }}
                                        </span>
                                        <span class="float-right">
                                            <label class="status mb-0 mr-4 " style = "margin-top: 3px;">
                                                <input type="checkbox" name="status" onclick="edit_info({{ $plan->id  }});" value="1" id="status" data-id="{{ $plan->id  }}" class="status" @if($plan->status == 1) checked @endif>
                                                <span class="status_slider round" ></span>
                                            </label>
                                        </span>

                                    </div>
                                    <div class="card-body pt-0 mt-2">
                                        <div class="row">
                                            <div class="col-12">
                                                <span class="text-center">
                                                    <p>
                                                        @if( (isset($plan->discount_percent ) && $plan->discount_percent > 0)|| (isset ($plan->discount_amount ) && $plan->discount_amount > 0 ))

                                                            <s>{{ $plan->amount }} €</s>   <span class="ml-2">{{ $plan->total_amount }} € </span>

                                                        @else
                                                            {{ $plan->amount }} €
                                                        @endif
                                                    </p>
                                                </span>
                                                <span class="text-center">
                                                        {!!html_entity_decode($plan->content_1)!!}
                                                </span>
                                                <span class="text-center">
                                                        {!!html_entity_decode($plan->content_2)!!}
                                                </span>
                                                <span class="text-center">
                                                        {!!html_entity_decode($plan->content_3)!!}
                                                </span>
                                                <span class="text-center">
                                                        {!!html_entity_decode($plan->content_4)!!}
                                                </span>
                                                <span class="text-center">
                                                        {!!html_entity_decode($plan->content_5)!!}
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="card-footer">
                                        <div class="d-flex justify-content-between align-items-center ">
                                            <button class="btn btn-sm btn-primary" style="width: 48%;" onclick="status_change({{ $plan->id  }});">
                                                <i class="fas fa-edit mr-1"></i>Edit Information
                                            </button>
                                            <button id="edit_plan_btn" data-toggle="modal" data-target="#edit_plan" style="display:none;"></button>
                                            <a href="{{ route('subscription.plan.delete', $plan->id) }}" class="btn btn-sm btn-danger close_btn" style="width: 48%;">
                                                <i class="fas fa-trash-alt mr-1"></i>Delete Information
                                            </a>
                                            <button type="button" id="delete_alert" class="btn btn-primary d-none" data-toggle="modal" data-target="#delete_alert_model">

                                              </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach


                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>



    <!-- Modal -->
    <div class="modal fade" id="add_new_plan" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Add new plan</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form method="post" id="" name="" action="{{ route('subscription.add.plan') }}">
                        @csrf
                            <div class="form-group" style="display: flex;justify-content: space-around;align-items: flex-end;">
                                <label for="status">Status</label>
                                <label class="status mb-0 mr-4 " style = "margin-top: 3px;">
                                    <input type="checkbox" name = "status" value = "1" id = "status" checked>
                                    <span class="status_slider round" ></span>
                                </label>
                            </div>
                            <div class="form-group ">
                                <label for="title">Plan title<span class="text-danger">*</span></label>
                                <input type="text" name="title" value="{{ old('title') }}" id="title" class="form-control form-control-sm" required>
                                <small id="title_note" class="form-text text-muted"></small>
                            </div>
                            <div class="form-group ">
                                <label for="price">Main Price<span class="text-danger">*</span></label>
                                <input type="number" step=any name="price" value="{{ old('price') }}" id="price" class="form-control form-control-sm" required>
                                <small id="price_note" class="form-text text-muted"></small>
                            </div>
                            <div class="form-group mb-0">
                                <label for="discount_percent">Discount</label>
                            </div>
                            <div class="form-group row">
                                <div class="col-md-6">
                                    <input type="number" step=any name="discount_percent" value="{{ old('discount_percent') }}" id="discount_percent" class="form-control form-control-sm" >
                                    <small id="discount_percent_note" class="form-text text-muted">Discount in percent</small>
                                </div>
                                <div class="col-md-6">
                                    <input type="number" step=any name="discount_amount" value="{{ old('discount_amount') }}" id="discount_amount" class="form-control form-control-sm" >
                                    <small id="discount_amount_note" class="form-text text-muted">Discount in number</small>
                                </div>
                            </div>
                            <div class="form-group ">
                                <label for="total_price">Total price</label>
                                <input type="number" step=any name="total_price" value="{{ old('total_price') }}" id="total_price" class="form-control form-control-sm" readonly required>
                                <small id="total_price_note" class="form-text text-muted"></small>
                            </div>
                            <div class="form-group ">
                                <label for="content_1">Content - line 1<span class="text-danger">*</span></label>
                                <textarea name="content_1" value="{{ old('content_1') }}" id="content_1" class="form-control form-control-sm" ></textarea>
                                <small id="content_1_note" class="form-text text-muted">Hint: How many releases included</small>
                            </div>
                            <div class="form-group ">
                                <label for="content_2">Content - line 2<span class="text-danger">*</span></label>
                                <textarea name="content_2" value="{{ old('content_2') }}" id="content_2" class="form-control form-control-sm" ></textarea>
                                <small id="content_2_note" class="form-text text-muted">Hint: Distribution</small>
                            </div>
                            <div class="form-group ">
                                <label for="content_3">Content - line 3<span class="text-danger">*</span></label>
                                <textarea name="content_3" value="{{ old('content_3') }}" id="content_3" class="form-control form-control-sm" ></textarea>
                                <small id="content_3_note" class="form-text text-muted">Hint: Keep earnings</small>
                            </div>
                            <div class="form-group ">
                                <label for="content_4">Content - line 4<span class="text-danger">*</span></label>
                                <textarea name="content_4" value="{{ old('content_4') }}" id="content_4" class="form-control form-control-sm" ></textarea>
                                <small id="content_4_note" class="form-text text-muted">Hint: Live support</small>
                            </div>
                            <div class="form-group ">
                                <label for="content_5">Content - line 5<span class="text-danger">*</span></label>
                                <textarea name="content_5" value="{{ old('content_5') }}" id="content_5" class="form-control form-control-sm" ></textarea>
                                <small id="content_5_note" class="form-text text-muted">Hint: Content ID</small>
                            </div>
                            <div class="form-group ">
                                <label for="button">Purchase Button color<span class="text-danger">*</span></label>
                                <input type="color" name="button" value="{{ old('button') }}" id="button" class="form-control form-control-sm" required>
                                <small id="button_note" class="form-text text-muted">Select button color</small>
                            </div>
                            <div class="form-group ">
                                <label for="show_button">Show Button color<span class="text-danger">*</span></label>
                                <input type="color" name="show_button" value="{{ old('show_button') }}" id="show_button" class="form-control form-control-sm" required>
                                <small id="show_button_note" class="form-text text-muted">Select button color</small>
                            </div>
							<div class="form-group ">
                                <label for="price">Stripe Plan ID<span class="text-danger">*</span></label>
                                <input type="text" name="stripe_plan_id" value="{{ old('stripe_plan_id') }}" id="stripe_plan_id" class="form-control form-control-sm" required>
                                <small id="stripe_plan_id" class="form-text text-muted"></small>
                            </div>
                </div>
                <div class="modal-footer">
                    <button class="btn btn-danger" style="width: 20%;" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary w-75">Save Status</button>
                </div>
                </form>
            </div>
        </div>
    </div>






        <!-- Modal -->
        <div class="modal fade" id="edit_plan" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Edit plan</h5>
                    <button type="button" class="close close-btn" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form method="post" id="" name="" action="{{ route('subscription.edit.plan.store') }}">
                        @csrf
                        <input type="hidden" name="id" id="id" value="">
                        <input type="hidden" name="check_click" value="0" id="check_click">
                            <div class="form-group" style="display: flex;justify-content: space-around;align-items: flex-end;">
                                <label for="status">Status</label>
                                <label class="status mb-0 mr-4 " style = "margin-top: 3px;">
                                    <input type="checkbox" name = "edit_status" value = "1" id = "edit_status">
                                    <span class="status_slider round" ></span>
                                </label>
                            </div>
                            <div class="form-group ">
                                <label for="edit_title">Plan title</label>
                                <input type="text" name="edit_title" value="" id="edit_title" class="form-control form-control-sm" required>
                                <small id="edit_title_note" class="form-text text-muted"></small>
                            </div>
                            <div class="form-group ">
                                <label for="edit_">Main Price</label>
                                <input type="number" step=any name="edit_price" value="{{ old('edit_') }}" id="edit_price" class="form-control form-control-sm" required>
                                <small id="edit_price_note" class="form-text text-muted"></small>
                            </div>
                            <div class="form-group mb-0">
                                <label for="edit_discount_percent">Discount</label>
                            </div>
                            <div class="form-group row">
                                <div class="col-md-6">
                                    <input type="number" step=any name="edit_discount_percent" value="{{ old('edit_discount_percent') }}" id="edit_discount_percent" class="form-control form-control-sm" >
                                    <small id="edit_discount_percent_note" class="form-text text-muted">Discount in percent</small>
                                </div>
                                <div class="col-md-6">
                                    <input type="number" step=any name="edit_discount_amount" value="{{ old('edit_discount_amount') }}" id="edit_discount_amount" class="form-control form-control-sm" >
                                    <small id="edit_discount_amount_note" class="form-text text-muted">Discount in number</small>
                                </div>
                            </div>
                            <div class="form-group ">
                                <label for="edit_total_price">Total price</label>
                                <input type="number" step=any name="edit_total_price" value="{{ old('edit_total_price') }}" id="edit_total_price" class="form-control form-control-sm" readonly required>
                                <small id="edit_total_price_note" class="form-text text-muted"></small>
                            </div>
                            <div class="form-group ">
                                <label for="edit_content_1">Content - line 1</label>
                                <textarea name="edit_content_1" value="{{ old('edit_content_1') }}" id="edit_content_1" class="form-control form-control-sm" ></textarea>
                                <small id="edit_content_1_note" class="form-text text-muted">Hint: How many releases included</small>
                            </div>
                            <div class="form-group ">
                                <label for="edit_content_2">Content - line 2</label>
                                <textarea name="edit_content_2" value="{{ old('edit_content_2') }}" id="edit_content_2" class="form-control form-control-sm" ></textarea>
                                <small id="edit_content_2_note" class="form-text text-muted">Hint: Distribution</small>
                            </div>
                            <div class="form-group ">
                                <label for="edit_content_3">Content - line 3</label>
                                <textarea name="edit_content_3" value="{{ old('edit_content_3') }}" id="edit_content_3" class="form-control form-control-sm" ></textarea>
                                <small id="edit_content_3_note" class="form-text text-muted">Hint: Keep earnings</small>
                            </div>
                            <div class="form-group ">
                                <label for="edit_content_4">Content - line 4</label>
                                <textarea name="edit_content_4" value="{{ old('edit_content_4') }}" id="edit_content_4" class="form-control form-control-sm" ></textarea>
                                <small id="edit_content_4_note" class="form-text text-muted">Hint: Live support</small>
                            </div>
                            <div class="form-group ">
                                <label for="edit_content_5">Content - line 5</label>
                                <textarea name="edit_content_5" value="{{ old('edit_content_5') }}" id="edit_content_5" class="form-control form-control-sm" ></textarea>
                                <small id="edit_content_5_note" class="form-text text-muted">Hint: Content ID</small>
                            </div>
                            <div class="form-group ">
                                <label for="edit_button">Purchase Button color</label>
                                <input type="color" name="edit_button" value="{{ old('edit_button') }}" id="edit_button" class="form-control form-control-sm" required>
                                <small id="edit_button_note" class="form-text text-muted">Select button color</small>
                            </div>
                            <div class="form-group ">
                                <label for="edit_show_button">Show Button color</label>
                                <input type="color" name="edit_show_button" value="{{ old('edit_show_button') }}" id="edit_show_button" class="form-control form-control-sm" required>
                                <small id="edit_show_button_note" class="form-text text-muted">Select button color</small>
                            </div>
							<div class="form-group ">
                                <label for="price">Stripe Plan ID<span class="text-danger">*</span></label>
                                <input type="text" name="edit_stripe_plan_id" value="{{ old('edit_stripe_plan_id') }}" id="edit_stripe_plan_id" class="form-control form-control-sm" required>
                                <small id="edit_stripe_plan_id" class="form-text text-muted"></small>
                            </div>
                </div>
                <div class="modal-footer">
                    <button class="btn btn-danger close-btn" style="width: 20%;" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary w-75">Save Status</button>
                </div>
                </form>
            </div>
        </div>
    </div>


  <!-- Modal -->
  <div class="modal fade" id="delete_alert_model" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="exampleModalLabel">Delete alert</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
            This action will delete all the content releted to this plan.<br>
            <span class="text-danger">If any user have this plan you can't delete this plan.</span><br>
            <span class="text-primary">Insted you can turn the status off.</span>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" style="width: 20%;" data-dismiss="modal">Close</button>
          <a class="btn btn-danger" id="delete_btn" style="width: 75%;">Delete data</a>
        </div>
      </div>
    </div>
  </div>
@endsection

@push('page_scripts')
<script src="{{ asset('assets/vendor/ckeditor5/build/ckeditor.js') }}"></script>
<script>ClassicEditor.create( document.querySelector( '#content_5' ), {

    toolbar: {
        items: [
            'heading',
            'bold',
            'italic'
        ]
    },
    language: 'en',
        licenseKey: '',
    } )
    .then( editor => {
        window.editor = editor;
    } )
    .catch( error => {
        console.error( 'Oops, something went wrong!' );
        console.error( 'Please, report the following error on https://github.com/ckeditor/ckeditor5/issues with the build id and the error stack trace:' );
        console.warn( 'Build id: 3665xww77gla-mlojmmnh8fek' );
        console.error( error );
    } );
</script>
<script>ClassicEditor.create( document.querySelector( '#content_4' ), {

    toolbar: {
        items: [
            'heading',
            'bold',
            'italic'
        ]
    },
    language: 'en',
        licenseKey: '',
    } )
    .then( editor => {
        window.editor = editor;
    } )
    .catch( error => {
        console.error( 'Oops, something went wrong!' );
        console.error( 'Please, report the following error on https://github.com/ckeditor/ckeditor5/issues with the build id and the error stack trace:' );
        console.warn( 'Build id: 3665xww77gla-mlojmmnh8fek' );
        console.error( error );
    } );
</script>
<script>ClassicEditor.create( document.querySelector( '#content_3' ), {

    toolbar: {
        items: [
            'heading',
            'bold',
            'italic'
        ]
    },
    language: 'en',
        licenseKey: '',
    } )
    .then( editor => {
        window.editor = editor;
    } )
    .catch( error => {
        console.error( 'Oops, something went wrong!' );
        console.error( 'Please, report the following error on https://github.com/ckeditor/ckeditor5/issues with the build id and the error stack trace:' );
        console.warn( 'Build id: 3665xww77gla-mlojmmnh8fek' );
        console.error( error );
    } );
</script>
<script>ClassicEditor.create( document.querySelector( '#content_2' ), {

    toolbar: {
        items: [
            'heading',
            'bold',
            'italic'
        ]
    },
    language: 'en',
        licenseKey: '',
    } )
    .then( editor => {
        window.editor = editor;
    } )
    .catch( error => {
        console.error( 'Oops, something went wrong!' );
        console.error( 'Please, report the following error on https://github.com/ckeditor/ckeditor5/issues with the build id and the error stack trace:' );
        console.warn( 'Build id: 3665xww77gla-mlojmmnh8fek' );
        console.error( error );
    } );
</script>
<script>ClassicEditor.create( document.querySelector( '#content_1' ), {

    toolbar: {
        items: [
            'heading',
            'bold',
            'italic'
        ]
    },
    language: 'en',
        licenseKey: '',
    } )
    .then( editor => {
        window.editor = editor;
    } )
    .catch( error => {
        console.error( 'Oops, something went wrong!' );
        console.error( 'Please, report the following error on https://github.com/ckeditor/ckeditor5/issues with the build id and the error stack trace:' );
        console.warn( 'Build id: 3665xww77gla-mlojmmnh8fek' );
        console.error( error );
    } );
</script>
<script src="https://code.jquery.com/jquery-3.6.0.js" integrity="sha256-H+K7U5CnXl1h5ywQfKtSj8PCmoN9aaq30gDh27Xc0jk=" crossorigin="anonymous"></script>
<script>
$("a.close_btn").click(function(){
    let href = $(this).attr('href');
    $('#delete_btn').attr('href', href);
    $('#delete_alert').click();
    return false;
});
function edit_info(id) {
    if(id != null)
    {
        let _url = ("{{ route('subscription.status.change', ['id']) }}");
        let __url = _url.replace('id', id);
        $.ajax({
            url: __url,
            method: "GET",
            success: function (response) {
                alert('status updated');
            }
        });
    }
 };

function status_change(id) {
    if(id != null)
    {
        let _url = ("{{ route('subscription.get.plan', ['id']) }}");
        let __url = _url.replace('id', id);
        $.ajax({
            url: __url,
            method: "GET",
            success: function (response) {
                console.log(response.title);

                $('#edit_title').val(response.title);
                $('#edit_price').val(response.amount);
				$('#edit_stripe_plan_id').val(response.stripe_plan_id);
				
                if(response.discount_percent) $('#edit_discount_percent').val(response.discount_percent);
                if(response.discount_amount) $('#edit_discount_amount').val(response.discount_amount);
                $('#edit_total_price').val(response.total_amount);
                $('#id').val(response.id);
                if(response.status == 1)
                {
                    $('#edit_status').attr('checked', '');
                }
                else
                {

                }
                let check_val = $('#check_click').val();
                if( check_val >= 1 )
                {
                    editor1.destroy();
                    editor2.destroy();
                    editor3.destroy();
                    editor4.destroy();
                    editor5.destroy();
                }
                else{
                    $('#check_click').val( check_val+1 );
                }


                // $('#edit_content_1').html(response.content_1);

                    ClassicEditor.create( document.querySelector( '#edit_content_1' ), {

                    toolbar: {
                        items: [
                            'heading',
                            'bold',
                            'italic'
                        ]
                    },
                    language: 'en',
                        licenseKey: '',
                    } )
                    .then( editor => {
                        window.editor1 = editor;
                        editor1.setData(response.content_1);
                    } )
                    .catch( error => {
                        console.error( 'Oops, something went wrong!' );
                        console.error( 'Please, report the following error on https://github.com/ckeditor/ckeditor5/issues with the build id and the error stack trace:' );
                        console.warn( 'Build id: 3665xww77gla-mlojmmnh8fek' );
                        console.error( error );
                    } );
                // $('#edit_content_2').val(response.content_2);
                    ClassicEditor.create( document.querySelector( '#edit_content_2' ), {

                    toolbar: {
                        items: [
                            'heading',
                            'bold',
                            'italic'
                        ]
                    },
                    language: 'en',
                        licenseKey: '',
                    } )
                    .then( editor => {
                        window.editor2 = editor;
                        editor2.setData(response.content_2);
                    } )
                    .catch( error => {
                        console.error( 'Oops, something went wrong!' );
                        console.error( 'Please, report the following error on https://github.com/ckeditor/ckeditor5/issues with the build id and the error stack trace:' );
                        console.warn( 'Build id: 3665xww77gla-mlojmmnh8fek' );
                        console.error( error );
                    } );
                // $('#edit_content_3').val(response.content_3);
                    ClassicEditor.create( document.querySelector( '#edit_content_3' ), {

                    toolbar: {
                        items: [
                            'heading',
                            'bold',
                            'italic'
                        ]
                    },
                    language: 'en',
                        licenseKey: '',
                    } )
                    .then( editor => {
                        window.editor3 = editor;
                        editor3.setData(response.content_3);
                    } )
                    .catch( error => {
                        console.error( 'Oops, something went wrong!' );
                        console.error( 'Please, report the following error on https://github.com/ckeditor/ckeditor5/issues with the build id and the error stack trace:' );
                        console.warn( 'Build id: 3665xww77gla-mlojmmnh8fek' );
                        console.error( error );
                    } );
                // $('#edit_content_4').val(response.content_4);
                    ClassicEditor.create( document.querySelector( '#edit_content_4' ), {

                    toolbar: {
                        items: [
                            'heading',
                            'bold',
                            'italic'
                        ]
                    },
                    language: 'en',
                        licenseKey: '',
                    } )
                    .then( editor => {
                        window.editor4 = editor;
                        editor4.setData(response.content_4);
                    } )
                    .catch( error => {
                        console.error( 'Oops, something went wrong!' );
                        console.error( 'Please, report the following error on https://github.com/ckeditor/ckeditor5/issues with the build id and the error stack trace:' );
                        console.warn( 'Build id: 3665xww77gla-mlojmmnh8fek' );
                        console.error( error );
                    } );
                // $('#edit_content_5').val(response.content_5);
                    ClassicEditor.create( document.querySelector( '#edit_content_5' ), {

                    toolbar: {
                        items: [
                            'heading',
                            'bold',
                            'italic'
                        ]
                    },
                    language: 'en',
                        licenseKey: '',
                    } )
                    .then( editor => {
                        window.editor5 = editor;
                        editor5.setData(response.content_5);
                    } )
                    .catch( error => {
                        console.error( 'Oops, something went wrong!' );
                        console.error( 'Please, report the following error on https://github.com/ckeditor/ckeditor5/issues with the build id and the error stack trace:' );
                        console.warn( 'Build id: 3665xww77gla-mlojmmnh8fek' );
                        console.error( error );
                    } );
                $('#edit_button').val(response.button);
                $('#edit_show_button').val(response.show_button);

                $('#edit_plan_btn').click();
            }
        });
    }

};

</script>
<script>
        function payment_calculation() {

            let discount_percent = $("#discount_percent");
            let discount_percent_val = $("#discount_percent").val();
            let discount_amount = $("#discount_amount");
            let discount_amount_val = $("#discount_amount").val();
            let price = $("#price");
            let price_val = $("#price").val();
            let total_price = $("#total_price");
            let total_price_val = $("#total_price").val();

            let total = 0;

            if ((price_val != '') && (discount_percent_val != '')) {
                total = price_val - ((price_val * discount_percent_val) / 100);
            } else {
                total = price_val;
            }
            if (discount_percent_val) {
                discount_amount.val('');
                discount_amount.attr('disabled', 'disabled');
            } else {
                discount_amount.removeAttr('disabled', 'disabled');
            }
            if (discount_amount_val) {
                discount_percent.val('');
                discount_percent.attr('disabled', 'disabled');
            } else {
                discount_percent.removeAttr('disabled', 'disabled');
            }
            if (discount_percent_val && discount_amount_val) {
                discount_amount.val('');
                discount_percent.val('');
                discount_amount.removeAttr('disabled', 'disabled');
                discount_percent.removeAttr('disabled', 'disabled');
            }
            if (discount_amount_val != '' && total > 0) {
                total = total - discount_amount_val;
            }
            total_price.val(parseFloat(total).toFixed(2));
        }

        payment_calculation();

        $(document).on('keyup change focusout', "#price, #discount_percent, #discount_amount", function () {
            payment_calculation();
        });

</script>
<script>
        function payment_calculation() {

            let discount_percent = $("#edit_discount_percent");
            let discount_percent_val = $("#edit_discount_percent").val();
            let discount_amount = $("#edit_discount_amount");
            let discount_amount_val = $("#edit_discount_amount").val();
            let price = $("#edit_price");
            let price_val = $("#edit_price").val();
            let total_price = $("#edit_total_price");
            let total_price_val = $("#edit_total_price").val();

            let total = 0;

            if ((price_val != '') && (discount_percent_val != '')) {
                total = price_val - ((price_val * discount_percent_val) / 100);
            } else {
                total = price_val;
            }
            if (discount_percent_val) {
                discount_amount.val('');
                discount_amount.attr('disabled', 'disabled');
            } else {
                discount_amount.removeAttr('disabled', 'disabled');
            }
            if (discount_amount_val) {
                discount_percent.val('');
                discount_percent.attr('disabled', 'disabled');
            } else {
                discount_percent.removeAttr('disabled', 'disabled');
            }
            if (discount_percent_val && discount_amount_val) {
                discount_amount.val('');
                discount_percent.val('');
                discount_amount.removeAttr('disabled', 'disabled');
                discount_percent.removeAttr('disabled', 'disabled');
            }
            if (discount_amount_val != '' && total > 0) {
                total = total - discount_amount_val;
            }
            total_price.val(parseFloat(total).toFixed(2));
        }

        payment_calculation();

        $(document).on('keyup change focusout', "#edit_price, #edit_discount_percent, #edit_discount_amount", function () {
            payment_calculation();
        });
        console.log = function() {}
</script>
@endpush
