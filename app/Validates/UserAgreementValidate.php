<?php

namespace App\Validates;

use Illuminate\Support\Facades\Validator;
use Auth;
use Illuminate\Validation\Rule;

class  UserAgreementValidate extends Validate
{
    protected $message = '操作成功';
    protected $data = [];


    public function storeValidate($request_data)
    {
        $rules = [
            'name' => 'required|between:2,255',
            'content' => 'required',
            'agree_type' => 'required',
        ];
        $rest_validate = $this->validate($request_data, $rules);
        if ($rest_validate === true) {
            $request_data['description'] = isset_and_not_empty($request_data, 'description');
            return $this->baseSucceed($request_data, $this->message);
        } else {
            $this->message = $rest_validate;
            return $this->baseFailed($this->message);
        }

    }

    public function updateValidate($request_data, $model = '')
    {
        $rules = [
            'name' => 'required|between:2,255',
            'content' => 'required',
            'agree_type' => 'required'
        ];
        $rest_validate = $this->validate($request_data, $rules);
        if ($rest_validate === true) {
            $request_data['description'] = isset_and_not_empty($request_data, 'description');
            return $this->baseSucceed($request_data, $this->message);
        } else {
            $this->message = $rest_validate;
            return $this->baseFailed($this->message);
        }

    }

    public function enableOrDisableValidate($model)
    {
        $authUser = Auth::user();
        if (!$authUser->hasRole('Founder')) return $this->baseFailed('抱歉，您没有操作权限');
    }

    public function destroyValidate($model)
    {
        $authUser = Auth::user();
        if (!$authUser->hasRole('Founder')) return $this->baseFailed('抱歉，您没有操作权限');
        if ($model->enable === 'T') {
            return $this->baseFailed("启用中的协议不允许删除");
        }
        return $this->baseSucceed();
    }

    protected function validate($request_data, $rules)
    {
        $message = [
            'name.required' => '协议名称不能为空',
            'name.between' => '协议名称只能在:min-:max个字符范围',
            'content.required' => '协议内容不能为空',
            'agree_type.required' => '请选择协议类型',
        ];

        $validator = Validator::make($request_data, $rules, $message);
        if ($validator->fails()) {
            return $validator->errors()->first();
        }
        return true;
    }
}
