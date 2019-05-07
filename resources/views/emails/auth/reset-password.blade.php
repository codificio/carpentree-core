<h1>{{ __('Reset Password Notification') }}</h1>
<p>{{ __('You are receiving this email because we received a password reset request for your account.') }}</p>
<div>
    <a href="{{ $resetPasswordUrl }}">{{ __('Reset Password') }}</a>
</div>
<p>{{ __('This password reset link will expire in :count minutes.', ['count' => $expiresIn]) }}</p>
<p>{{ __('If you did not request a password reset, no further action is required.') }}</p>
