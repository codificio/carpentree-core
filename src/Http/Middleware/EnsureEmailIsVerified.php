<?php

namespace Carpentree\Core\Http\Middleware;

use Carpentree\Core\Models\User;
use Closure;
use Illuminate\Auth\MustVerifyEmail;
use Illuminate\Support\Facades\Redirect;
use Spatie\Permission\Traits\HasRoles;

class EnsureEmailIsVerified
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle($request, Closure $next)
    {
        $user = $request->user();
        $instanceOf = method_exists($user, 'hasVerifiedEmail');
        $hasVerifiedEmail = $user->hasVerifiedEmail();

        if (!$user || ($instanceOf && !$hasVerifiedEmail)) {
            return $request->expectsJson()
                ? abort(403, 'Your email address is not verified.')
                : Redirect::route('verification.notice');
        }

        return $next($request);
    }
}
