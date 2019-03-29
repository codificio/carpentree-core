<?php

namespace Carpentree\Core\Http\Middleware;

use Carpentree\Core\Exceptions\EmailNotVerified;
use Closure;

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
            throw EmailNotVerified::create();
        }

        return $next($request);
    }
}
