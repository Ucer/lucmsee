<?php

namespace App\Http\Middleware;

use Closure;

class CrosOption
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if($request->method() == 'options') {
            return true;
        }
        return $next($request);
    }
}
