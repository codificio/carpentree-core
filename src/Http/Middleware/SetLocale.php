<?php

namespace Carpentree\Core\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\App;

class SetLocale
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
        $locale = $request->input('locale', null);
        if (!is_null($locale)) {
            App::setLocale($locale);
        }

        return $next($request);
    }
}
