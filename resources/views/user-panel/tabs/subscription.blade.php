@if ($errors->any())
    <div class="alert alert-danger">
        <ul>
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif
<div class="col-md-10 m-auto">
    <div class="from-group">
        <form action="{{ route('user.payment.settings') }}" method="post">
            @csrf

            <div class="form-group row">
                <label for="previous_password" class="col-sm-5 col-form-label">Yearly
                    Subscription Price</label>
                <div class="col-sm-7">
                    <input type="number" min="1" class="form-control"
                           value="{{@$data->subscrption_price}}"
                           id="subscrption_price" name="subscrption_price"
                           placeholder="Yearly Subscription Price">
                </div>
            </div>

            <div class="form-group row">
                <label for="previous_password" class="col-sm-5 col-form-label">Stripe
                    Secret Key</label>
                <div class="col-sm-7">
                    <input type="text" class="form-control"
                           id="stripe_scret_key"
                           value="{{@$data->stripe_secret_key}}"
                           name="stripe_secret_key"
                           placeholder="Stripe Secret Key">
                </div>
            </div>

            <div class="form-group row">
                <label for="previous_password" class="col-sm-5 col-form-label">Stripe
                    Publish Key</label>
                <div class="col-sm-7">
                    <input type="text" class="form-control"
                           id="stripe_publish_key"
                           value="{{@$data->stripe_publish_key}}"
                           name="stripe_publish_key"
                           placeholder="Stripe Publish Key">
                </div>
            </div>

            <div class="form-group row">
                <label for="previous_password" class="col-sm-5 col-form-label">Paypal
                    Client Key</label>
                <div class="col-sm-7">
                    <input type="text" class="form-control"
                           id="stripe_scret_key"
                           value="{{@$data->paypal_client_key}}"
                           name="paypal_client_key"
                           placeholder="Paypal Client Key">
                </div>
            </div>

            <div class="form-group row">
                <label for="previous_password" class="col-sm-5 col-form-label">Paypal
                    Client Secret</label>
                <div class="col-sm-7">
                    <input type="text" class="form-control"
                           id="stripe_publish_key"
                           value="{{@$data->paypal_secret_key}}"
                           name="paypal_secret_key"
                           placeholder="Paypal Client Secret">
                </div>
            </div>

            <div class="form-group row">
                <label for="" class="col-sm-4 col-form-label"></label>
                <div class="col-sm-6">
                    <button type="submit" class="btn btn-primary w-100">Update
                        Details
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>
