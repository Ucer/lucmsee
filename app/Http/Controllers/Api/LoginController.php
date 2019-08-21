<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Validates\LoginValidate;
use App\Models\User;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Hash;
use App\Http\Controllers\Api\Traits\ProxyTrait;
use Symfony\Component\HttpFoundation\Response;

class LoginController extends ApiController
{
    use AuthenticatesUsers, ProxyTrait;

    public function __construct()
    {
        parent::__construct();

        $this->middleware('guest')->except('logout,adminUserLogout,refreshToken');
    }

    public function login(Request $request, LoginValidate $validate)
    {
        $request_data = $request->all();
        $rest_validate = $validate->loginValidate($request_data);
        if ($rest_validate['status'] === false) return $this->failed($rest_validate['message']);

        if ($this->hasTooManyLoginAttempts($request)) {
            $this->fireLockoutEvent($request);
            return $this->failed('操作过于频繁，请稍后再试', Response::HTTP_TOO_MANY_REQUESTS);
        }

        $user = User::enableSearch('T')
            ->where('email', $request->email)
            ->isAdminSearch('T')
            ->firstOrFail();

        if (!Hash::check($request->password, $user->password)) {
            return $this->failed('密码不正确');
        }
        $user->last_login_at = date('Y-m-d H:i:s', time());
        $user->save();
        $return = $user->toArray();

        foreach ($user->roles as $role) {
            $return['roles'][] = $role['name'];
        }

        $tokens = $this->authenticate();
        admin_log_record($user->id, 'login', 'users', '登录后台');
        return $this->success(['token' => $tokens, 'user' => $return]);
    }

    public function logout()
    {
        if (\Auth::guard('api')->check()) {
            \Auth::guard('api')->user()->token()->delete();
        }

        return $this->message('退出登录成功');
    }
}
