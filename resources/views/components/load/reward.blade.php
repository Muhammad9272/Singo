@if($id2==0)
<p class="">{!! $rewarddata->subtitle !!}</p>
<p class="">{!! $rewarddata->detail !!}</p>
@elseif($id2==1)
<p class="">{!! $rewarddata->subtitle !!}</p>
<p class="">{!! $rewarddata->detail !!}</p>
@elseif($id2==2)
<p class="">{!! $rewarddata->subtitle !!}</p>
<p class="">{!! $rewarddata->detail !!}</p>

<hr class="text-white" style="border-top:1px solid white;">
<form class="rw-address-form" action="{{ route('user.rewards.create', $rewarddata->id) }}" method="POST"> 	
	@csrf
	@if($rewarddata->is_physical==1)
		<h4>Add Details to get Physical Rewards</h4>
		<div class="form-row">
		    <div class="form-group col-md-6">
		      <label for="input4">First name</label>
		      <input type="text" class="form-control" name="fname" id="input4" placeholder="First name" required>
		    </div>
		    <div class="form-group col-md-6">
		      <label for="input4">Last name</label>
		      <input type="text" class="form-control" name="lname" id="input4" placeholder="Last name" required>
		    </div>
		</div>

		<div class="form-row">
		    <div class="form-group col-md-6">
		      <label for="input4">Street No</label>
		      <input type="text" class="form-control" name="street_no" id="input4" placeholder="Street No" required>
		    </div>
		    <div class="form-group col-md-6">
		      <label for="input4">Contact No</label>
		      <input type="text" class="form-control" name="contact_no" id="input4" placeholder="Contact No" required>
		    </div> 
		</div>

		<div class="form-row">
		    <div class="form-group col-md-6">
		      <label for="input4">Zip Code</label>
		      <input type="text" class="form-control" name="zip_code" id="input4" placeholder="Zip Code" required>
		    </div>
		    <div class="form-group col-md-6">
		      <label for="input4">City</label>
		      <input type="text" class="form-control" name="city" id="input4" placeholder="City" required>
		    </div>
		</div>

		<div class="form-row">
		    <div class="form-group col-md-6">
		      <label for="input4">Country</label>
		      <input type="text" class="form-control" name="country" id="input4" placeholder="Country" required>
		    </div>
		    <div class="form-group col-md-6">
		      <label for="input4">Additional Info (optional)</label>
		      <input type="text" class="form-control" name="info" id="input4" placeholder="Optional">
		    </div>
		</div>
	@endif

	<div class="text-center pt-4">
        <button type="submit" class="singo-btn secondarybgcolor changerwbtntext">
            Claim Reward
        </button>
    </div>

</form>



@endif
