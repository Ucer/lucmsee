<?php

namespace App\Validates;

use App\Models\User;
use App\Traits\SystemConfigTrait;
use Illuminate\Support\Facades\Validator;
use Auth;

class  UserValidate extends Validate
{
    use SystemConfigTrait;

    protected $message = '操作成功';
    protected $data = [];


    public function storeValidate($request_data)
    {
        $rules = [
            'real_name' => 'required|between:3,50',
            'password' => 'required|between:6,12|alpha_num|confirmed',
        ];
        $rest_validate = $this->validate($request_data, $rules);
        if ($rest_validate === true) {
            $request_data['email'] = isset_and_not_empty($request_data, 'email');
            $request_data['password'] = isset_and_not_empty($request_data, 'password');
            if ($request_data['email']) {

                $systemConfig = $this->getSystemConfigFunction(['regex_email']);
                if (preg_match($systemConfig['regex_email'], $request_data['email'])) {
                    return $this->baseFailed('您输入的电子邮件地址合法');
                }
                $isset = User::columnEqualSearch('email', $request_data['email'])->count();
                if ($isset) {
                    return $this->baseFailed('管理员账号已经存在了');
                }
            }

            $request_data = unset_if_no_value($request_data, ['avatar', 'nickname', 'email']);
            return $this->baseSucceed($request_data, $this->message);
        } else {
            $this->message = $rest_validate;
            return $this->baseFailed($this->message);
        }

    }

    public function updateValidate($request_data, $model = '')
    {
        $rules = [
            'real_name' => 'required|between:3,50',
            'password' => 'between:6,12|alpha_num|confirmed',
        ];
        $rest_validate = $this->validate($request_data, $rules);
        if ($rest_validate === true) {

            $request_data['email'] = isset_and_not_empty($request_data, 'email');
            $request_data['password'] = isset_and_not_empty($request_data, 'password');

            if ($model->id === 1) {
                $request_data['password'] = '';
                $request_data['email'] = $model->email;
            }

            if ($request_data['email']) {
                $systemConfig = $this->getSystemConfigFunction(['regex_email']);
                if (preg_match($systemConfig['regex_email'], $request_data['email'])) {
                    return $this->baseFailed('您输入的电子邮件地址合法');
                }
                $isset = User::columnEqualSearch('email', $request_data['email'])->where('id', '<>', $model->id)->count();
                if ($isset) {
                    return $this->baseFailed('管理员账号已经存在了');
                }
            }

            $request_data = unset_if_no_value($request_data, ['avatar', 'nickname', 'email']);

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
