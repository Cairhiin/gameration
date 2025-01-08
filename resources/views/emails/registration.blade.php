@component('mail::message')
    {{ __(':username, welcome to Gameration!', ['username' => $username]) }}

    {{ __('You may login by clicking the button below:') }}

    @component('mail::button', ['url' => route('login')])
        {{ __('Login') }}
    @endcomponent
@endcomponent
