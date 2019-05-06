<?php

namespace App\Validates;

use App\Traits\SystemConfigTrait;
use Illuminate\Support\Facades\Validator;
use Auth;
use Illuminate\Validation\Rule;

class  UserValidate extends Validate
{
    use SystemConfigTrait;

    protected $message = '操作成功';
    protected $data = [];


    public function storeValidate($request_data)
    {
        $rules = [
            'real_name' => 'required|between:2,50',
            'password' => 'bail|required|between:6,12|alpha_num|confirmed',
        ];
        $rest_validate = $this->validate($request_data, $rules);
        if ($rest_validate === true) {
            $request_data['email'] = isset_and_not_empty($request_data, 'email');
            $request_data['password'] = isset_and_not_empty($request_data, 'password');
            $request_data['avatar'] = isset_and_not_empty($request_data, 'avatar');
            if ($request_data['email']) {
                $rules_email = [
                    'email' => 'email|unique:users'
                ];
                $rest_validate_email = $this->validate($request_data, $rules_email);
                if ($rest_validate_email !== true) {
                    return $this->baseFailed($rest_validate_email);
                }
            }

            if ($request_data['avatar']) {
                $request_data['avatar'] = $request_data['avatar']['url'];
            }
            $request_data = unset_if_no_value($request_data, ['nickname','avatar']);
            return $this->baseSucceed($request_data, $this->message);
        } else {
            $this->message = $rest_validate;
            return $this->baseFailed($this->message);
        }

    }

    public function updateValidate($request_data, $model = '')
    {
        $rules = [
            'real_name' => 'required|between:2,50',
        ];
        $rest_validate = $this->validate($request_data, $rules);
        if ($rest_validate === true) {

            $request_data['email'] = isset_and_not_empty($request_data, 'email');
            $request_data['password'] = isset_and_not_empty($request_data, 'password');
            $request_data['avatar'] = isset_and_not_empty($request_data, 'avatar');

            if ($model->id === 1) { // 超级管理员密码只能在个人中心修改
                if ($request_data['password'] || ($request_data['email'] != $model->email)) {
                    return $this->baseFailed('超级管理员的密码与账号不可以修改');
                }
            }

            if ($request_data['password']) {
                $rules_password = [
                    'password' => 'between:6,12|alpha_num|confirmed',
                ];
                $rest_validate_password = $this->validate($request_data, $rules_password);
                if ($rest_validate_password !== true) {
                    return $this->baseFailed($rest_validate_password);
                }
            }

            if ($request_data['email']) {
                $rules_email = [
                    'email' => [
                        'email',
                        Rule::unique('users')->ignore($model->id),
                    ]
                ];
                $rest_validate_email = $this->validate($request_data, $rules_email);
                if ($rest_validate_email !== true) {
                    return $this->baseFailed($rest_validate_email);
                }
            }

            if ($request_data['avatar']) {
                $request_data['avatar'] = $request_data['avatar']['url'];
            }

            $request_data = unset_if_no_value($request_data, ['nickname', 'avatar']);

            return $this->baseSucceed($request_data, $this->message);
        } else {
            $this->message = $rest_validate;
            return $this->baseFailed($this->message);
        }

    }

    public function updatePasswordValidate($request_data)
    {
        $rules = [
            'password' => 'required|between:6,12|alpha_num|confirmed',
        ];
        $rest_validate = $this->validate($request_data, $rules);
        if ($rest_validate === true) {
            return $this->baseSucceed($request_data, $this->message);
        } else {
            $this->message = $rest_validate;
            return $this->baseFailed($this->message);
        }

    }

    public function destroyValidate($model)
    {
        if ($model->id === 1) return $this->baseFailed('不允许删除最高管理员');
        $authUser = Auth::user();
        if (!$authUser->hasRole('Founder')) return $this->baseFailed('抱歉，您没有操作权限');
        return $this->baseSucceed();
    }


    public function resetPasswordValidate($request_data)
    {
        $rules = [
            'password' => 'required|between:6,12|alpha_num|confirmed',
        ];
        $rest_validate = $this->validate($request_data, $rules);
        if ($rest_validate === true) {
            return $this->baseSucceed($request_data, $this->message);
        } else {
            $this->message = $rest_validate;
            return $this->baseFailed($this->message);
        }

    }

    protected function validate($request_data, $rules)
    {
        $message = [
            'real_name.required' => '真实姓名不能为空',
            'real_name.between' => '真实姓名只能在:min-:max个字符范围',
            'password.required' => '密码不能为空',
            'password.between' => '密码只能在:min-:max个字符范围',
            'password.alpha_num' => '密码只能是数字跟字母',
            'password.confirmed' => '两次输入密码不一致',
            'email.email' => '请输入正确的邮箱格式',
            'email.unique' => '邮箱已经被占用',
        ];
        $validator = Validator::make($request_data, $rules, $message);
        if ($validator->fails()) {
            return $validator->errors()->first();
        }
        return true;
    }
}
