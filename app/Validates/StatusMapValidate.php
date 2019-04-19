<?php

namespace App\Validates;

use App\Models\Table;
use Illuminate\Support\Facades\Validator;
use Auth;

class  StatusMapValidate extends Validate
{
    protected $message = '操作成功';
    protected $data = [];


    public function storeValidate($request_data)
    {
        $rules = [
            'table_name' => 'required',
            'column' => 'required|between:1,255|alpha_dash',
            'status_code' => 'required|between:1,255|alpha_dash',
            'status_description' => 'required|between:1,255'
        ];
        $rest_validate = $this->validate($request_data, $rules);
        if ($rest_validate === true) {
            $res_isHasTableName = $this->isHasTableName($request_data['table_name']);
            if(!$res_isHasTableName) return $this->baseFailed('表不存在，请刷新后重试');
            $request_data['remark'] = isset_and_not_empty($request_data,'remark');
            return $this->baseSucceed($request_data, $this->message);
        } else {
            $this->message = $rest_validate;
            return $this->baseFailed($this->message);
        }

    }

    public function updateValidate($request_data,$model='')
    {
        $rules = [
            'table_name' => 'required',
            'column' => 'required|between:1,255|alpha_dash',
            'status_code' => 'required|between:1,255|alpha_dash',
            'status_description' => 'required|between:1,255'
        ];
        $rest_validate = $this->validate($request_data, $rules);
        if ($rest_validate === true) {
            $res_isHasTableName = $this->isHasTableName($request_data['table_name']);
            if(!$res_isHasTableName) return $this->baseFailed('表不存在，请刷新后重试');
            unset($request_data['table_name']);
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
        if(!$authUser->hasRole('Founder')) return $this->baseFailed('抱歉，您没有操作权限');
        return $this->baseSucceed();
    }

    protected function validate($request_data, $rules)
    {
        $message = [
            'table_name.required' => '表名不能为空',
            'column.required' => '字段名不能为空',
            'column.between' => '字段名只能在:min-:max个字符范围',
            'column.alpha_dash' => '字段名只能是字母、数字，以及破折号 ( - ) 和下划线 ( _ )',
            'status_code.required' => '状态码不能为空',
            'status_code.between' => '状态码只能在:min-:max个字符范围',
            'status_code.alpha_dash' => '状态码只能是字母、数字，以及破折号 ( - ) 和下划线 ( _ )',
            'status_description.required' => '状态码说明不能为空',
            'status_description.between' => '状态码说明只能在:min-:max个字符范围',
        ];
        $validator = Validator::make($request_data, $rules, $message);
        if ($validator->fails()) {
            return $validator->errors()->first();
        }
        return true;
    }

    protected function isHasTableName($table_name) {
       return Table::where('table_name',$table_name)->first();
    }
}
