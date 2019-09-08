<?php

namespace App\Http\Middleware;

use App\Http\Controllers\Api\Traits\ApiResponseTraits;
use Closure;

class OnlyDevModel
{
    use ApiResponseTraits;


    /**
     * Handle an incoming request.
     *
     * @param \Illuminate\Http\Request $request
     * @param \Closure $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $model = env("APP_ENV", 'production');
        if ($model != 'local') {
            return $this->failed('非开发模式下禁止访问');
        }
        return $next($request);
    }
}
