<?php

namespace App\Validates;

use Illuminate\Support\Facades\Validator;
use DB;
use Captcha;

class  LoginValidate extends Validate
{
    protected $message = '操作成功';
    protected $data = [];

    /**
     * 后台登录验证
     * @param $request_data
     * @return array
     */
    public function loginValidate($request_data)
    {
        $rules = [
            'email' => 'required|email',
            'password' => 'required|string|min:6',
            'captcha' => 'required|between:4,4',
            'captcha_key' => 'required'
        ];
        $rest_validate = $this->validate($request_data, $rules);
        if ($rest_validate === true) {
            $captcha_validate = Captcha::check_api($request_data['captcha'],$request_data['captcha_key']);
            if(!$captcha_validate) return $this->baseFailed('验证码输入错误');
            return $this->baseSucceed($this->data, $this->message);
        } else {
            $this->message = $rest_validate;
            return $this->baseFailed($this->message);
        }

    }

    protected function validate($request_data, $rules)
    {
        $message = [
            'email.required' => '请输入邮箱',
            'email.email' => '邮箱格式不正确',
            'password.required' => '请输入密码',
            'password.min' => '密码长度至少是6位',
            'captcha.required' => '请输入验证码',
            'captcha.between' => '请输入4位数的验证码',
            'captcha.captcha' => '验证码输入错误',
            'captcha_key' => '验证码 key 错误'
        ];
        $validator = Validator::make($request_data, $rules, $message);
        if ($validator->fails()) {
            return $validator->errors()->first();
        }
        return true;
    }
}
