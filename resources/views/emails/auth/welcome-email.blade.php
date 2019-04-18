<h1>{{ __("Welcome!") }}</h1>
<p>{{ __("Dear :fullname welcome to :sitename", ['fullname' => $user->fullname, 'sitename' => env('APP_NAME', 'Carpentree')]) }}</p>

@if ($verificationUrl)
    <div>
        <a href="{{ $verificationUrl }}">{{ __("Verify email") }}</a>
    </div>
@endif
