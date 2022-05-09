@if($updated ?? '')
    <div class="alert alert-success">Your payment methods have been updated!
    </div>
@endif

<div>
    <form method="post" action="{{ route('wallet.update-payout') }}">
        @csrf

        <div class="form-row">
            <div class="form-group col-md-6">
                <label for="btc">Bitcoin Address</label>
                <input id="btc" type="text" value="{{ auth()->user()->btcAddress }}" class="form-control @error('btcAddress') is-invalid @enderror" name="btcAddress" placeholder="Your Bitcoin Address">
                @error('btcAddress')
                <span class="error invalid-feedback">{{ $message }}</span>
                @enderror
            </div>

            <div class="form-group col-md-6">
                <label for="paypal">Paypal Email</label>
                <input id="paypal" type="email" value="{{ auth()->user()->paypalEmail }}" class="form-control @error('paypalEmail') is-invalid @enderror" name="paypalEmail" placeholder="Your PayPal email">
                @error('paypalEmail')
                <span class="error invalid-feedback">{{ $message }}</span>
                @enderror
            </div>

            <div class="form-group col-md-6">
                <label for="ltc">Litecoin Address</label>
                <input id="ltc" type="text" value="{{ auth()->user()->ltcAddress }}" class="form-control @error('ltcAddress') is-invalid @enderror" name="ltcAddress" placeholder="Your Litecoin Address">
                @error('ltcAddress')
                <span class="error invalid-feedback">{{ $message }}</span>
                @enderror
            </div>

            <div class="form-group col-md-6">
                <label for="iban">IBAN</label>
                <input id="iban" type="text" value="{{ auth()->user()->iban }}" class="form-control @error('iban') is-invalid @enderror" name="iban" placeholder="Your IBAN">
                @error('iban')
                <span class="error invalid-feedback">{{ $message }}</span>
                @enderror
            </div>

            <div class="form-group col-md-6">
                <label for="eth">Ethereum Address</label>
                <input id="eth" type="text" value="{{ auth()->user()->ethAddress }}" class="form-control @error('ethAddress') is-invalid @enderror" name="ethAddress" placeholder="Your Ethereum Address">
                @error('ethAddress')
                <span class="error invalid-feedback">{{ $message }}</span>
                @enderror
            </div>
        </div>

        <button class="btn btn-success px-5" type="submit">
            Update
        </button>
    </form>

    <p class="text-gray py-2">You need add at least one payout method.</p>
</div>
