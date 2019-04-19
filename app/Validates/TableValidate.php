<?php

namespace App\Validates;

use Illuminate\Support\Facades\Validator;
use Auth;
use Illuminate\Validation\Rule;

class  TableValidate extends Validate
{
    protected $message = '操作成功';
    protected $data = [];


    public function storeValidate($request_data)
    {
        $rules = [
            'table_name' => 'bail|required|between:2,255|alpha_dash|unique:tables',
            'table_name_cn' => 'bail|required|between:2,255|unique:tables',
        ];
        $rest_validate = $this->validate($request_data, $rules);
        if ($rest_validate === true) {
            $request_data['remark'] = isset_and_not_empty($request_data,'remark');
            return $this->baseSucceed($request_data, $this->message);
        } else {
            $this->message = $rest_validate;
            return $this->baseFailed($this->message);
        }

    }

    public function updateValidate($request_data, $model='')
    {
        $rules = [
            'table_name' =>
                [
                    'bail',
                    'required',
                    'between:2,255',
                    'alpha_dash',
                    Rule::unique('tables')->ignore($model->id),
                ],
            'table_name_cn' => [
                'bail',
                'required',
                'between:2,255',
                Rule::unique('tables')->ignore($model->id),
            ],
        ];
        $rest_validate = $this->validate($request_data, $rules);
        if ($rest_validate === true) {
            $request_data['remark'] = isset_and_not_empty($request_data,'remark');
            return $this->baseSucceed($request_data, $this->message);
        } else {
            $this->message = $rest_validate;
            return $this->baseFailed($this->message);
        }

    }

    public function destroyValidate($model)
    {
        $authUser = Auth::user();
        if (!$authUser->hasRole('Founder')) return $this->baseFailed('抱歉，您没有操作权限');
        return $this->baseSucceed();
    }

    protected function validate($request_data, $rules)
    {
        $message = [
            'table_name.required' => '表名不能为空',
            'table_name.between' => '表名只能在:min-:max个字符范围',
            'table_name.alpha_dash' => '表名只能是字母、数字，以及破折号 ( - ) 和下划线 ( _ )',
            'table_name.unique' => '表名已经存在了',
            'table_name_cn.required' => '表中文名不能为空',
            'table_name_cn.between' => '表中文只能在:min-:max个字符范围',
            'table_name_cn.unique' => '表中文名已经存在了',
        ];

        $validator = Validator::make($request_data, $rules, $message);
        if ($validator->fails()) {
            return $validator->errors()->first();
        }
        return true;
    }
}
