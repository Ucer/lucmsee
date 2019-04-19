<?php

namespace App\Validates;

use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use DB;
use Auth;

class  SystemConfigValidate extends Validate
{
    protected $message = '操作成功';
    protected $data = [];

    public function storeValidate($request_data)
    {
        $rules = [
            'flag' => [
                'bail',
                'regex:/^[a-z][a-zA-Z0-9_]{2,100}$/',
                'unique:system_configs'
            ],
            'title' => 'bail|between:2,100|unique:system_configs',
            'config_group' => 'required'
        ];
        $rest_validate = $this->validate($request_data, $rules);
        if ($rest_validate === true) {
            $request_data['description'] = isset_and_not_empty($request_data,'description');

            $authUser = Auth::user();
            if(!$authUser->hasRole('Founder')) return $this->baseFailed('抱歉，您没有操作权限');
            return $this->baseSucceed($request_data, $this->message);
        } else {
            $this->message = $rest_validate;
            return $this->baseFailed($this->message);
        }
    }


    public function updateValidate($request_data, $model='')
    {
        $rules = [
            'flag' => [
                'bail',
                'regex:/^[a-z][a-zA-Z0-9_]{2,100}$/',
                Rule::unique('system_configs')->ignore($model->id)
            ],
            'title' => [
                'bail',
                'between:2,100',
                Rule::unique('system_configs')->ignore($model->id)
            ],
            'config_group' => 'required'
        ];
        $rest_validate = $this->validate($request_data, $rules);
        if ($rest_validate === true) {
            $request_data['description'] = isset_and_not_empty($request_data,'description');

            $authUser = Auth::user();
            if(!$authUser->hasRole('Founder')) return $this->baseFailed('抱歉，您没有操作权限');
            return $this->baseSucceed($request_data, $this->message);
        } else {
            $this->message = $rest_validate;
            return $this->baseFailed($this->message);
        }
    }

    public function destroyValidate($model)
    {
        $authUser = Auth::user();
        if(!$authUser->hasRole('Founder')) return $this->baseFailed('抱歉，您没有操作权限');
        return $this->baseSucceed($this->data, $this->message);
    }

    protected function validate($request_data, $rules)
    {
        $message = [
            'flag.regex' => '标识只能是2-100位的字母、数字、下划线组成',
            'flag.unique' => '标识已经存在',
            'title.between' => '配置标题只能在:min-:max个字符范围',
            'title.unique' => '配置标题已经被占用',
            'config_group.required' => '请选择配置分组'
        ];
        $validator = Validator::make($request_data, $rules, $message);
        if ($validator->fails()) {
            return $validator->errors()->first();
        }
        return true;
    }
}
