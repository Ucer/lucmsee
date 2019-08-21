<?php

namespace App\Http\Middleware;

use App\Http\Controllers\Api\Traits\ApiResponseTraits;
use Closure;
use Illuminate\Support\Facades\Config;
use Symfony\Component\HttpFoundation\Response;

class AcceptAccess
{
    use ApiResponseTraits;

    protected $accessToken = '';

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \Closure $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $requestController = explode('@', ($request->route()->getAction())['controller'])[0];
        switch ($requestController) {
            case 'App\Http\Controllers\Api\AcceptCommonAccessController':
                $baseConfig = Config::get('lu.accept_other_host.common');
                break;
            case 'App\Http\Controllers\Api\AcceptLucmseeApiAccessController':
                $baseConfig = Config::get('lu.accept_other_host.lucmsee_api');
                break;
            default:
                return $this->failed("配置不存在", Response::HTTP_OK);

        }
        $this->accessToken = $baseConfig['access_token'];
        $requestHost = $this->dealReferer($request->header("Referer"));
        $requestAccessToken = $request->input('access_token');
        if (!in_array($requestHost, $baseConfig['hosts'])) {
            return $this->failed('不允许该域名访问', Response::HTTP_OK);
        }
        if ($this->accessToken != $requestAccessToken) return $this->failed('token不正确', Response::HTTP_OK);
        return $next($request);
    }


    protected function dealReferer($referer)
    {
        if (!$referer) return '';
        $array = explode('/', $referer);
        return $array[0] . '//' . $array[2];
    }
}
