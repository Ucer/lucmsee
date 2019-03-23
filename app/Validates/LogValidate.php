<?php

namespace App\Validates;

use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use DB;
use Auth;

class  LogValidate extends Validate
{
    protected $message = '操作成功';
    protected $data = [];

    public function destroyValidate($model)
    {
        $authUser = Auth::user();
        if (!$authUser->hasRole('Founder')) return $this->baseFailed('抱歉，您没有操作权限');
        return $this->baseSucceed($this->data, $this->message);
    }

    protected function validate($request_data, $rules)
    {
        $message = [
        ];
        $validator = Validator::make($request_data, $rules, $message);
        if ($validator->fails()) {
            return $validator->errors()->first();
        }
        return true;
    }
}
