@component('mail::message')
# Your account has been banned

Your account has been banned because of breaking terms & service. If you think this is a mistake please contact us.

@component('mail::button', ['url' => 'mailto:support@singo.io'])
    Contact Us
@endcomponent

Thanks,<br>
{{ config('app.name') }}
@endcomponent
