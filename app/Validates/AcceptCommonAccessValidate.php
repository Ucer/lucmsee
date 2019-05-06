<?php

namespace App\Validates;

use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use DB;
use Auth;

class  AcceptCommonAccessValidate extends Validate
{
    protected $message = '操作成功';
    protected $data = [];

    public function uploadImageUseBase64Validate($request_data)
    {
        $rules = [
            'base_string' => 'required|min:12',
        ];
        $rest_validate = $this->validate($request_data, $rules);
        if ($rest_validate === true) {
            $request_data['user_id'] = isset_and_not_empty($request_data, 'user_id');
            $request_data['original_name'] = isset_and_not_empty($request_data, 'original_name');
            $request_data['max_width'] = isset_and_not_empty($request_data, 'max_width');
            return $this->baseSucceed($request_data);
        } else {
            $this->message = $rest_validate;
            return $this->baseFailed($this->message);
        }

    }

    protected function validate($request_data, $rules)
    {
        $message = [
            'base_string.required' => '必须填写base64字符串',
            'base_string.min' => 'base64字符串格式不正确',
        ];
        $validator = Validator::make($request_data, $rules, $message);
        if ($validator->fails()) {
            return $validator->errors()->first();
        }
        return true;
    }
}
