<?php

namespace App\Validates;

use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use DB;
use Auth;

class  AppMessageValidate extends Validate
{
    protected $message = '操作成功';
    protected $data = [];

    public function sendMessageToAppUserValidate($request_data)
    {
        $authUser = Auth::user();
        if (!$authUser->hasRole('Founder')) return $this->baseFailed('抱歉，您没有操作权限');

        $rules = [
            'title' => 'between:2,255',
            'message_type' => 'required',
        ];
        $rest_validate = $this->validate($request_data, $rules);
        if ($rest_validate === true) {
            $request_data['url'] = isset_and_not_empty($request_data, 'url');
            return $this->baseSucceed($request_data);
        } else {
            return $this->baseFailed($this->message);
        }

    }

    public function destroyValidate($model)
    {
        $authUser = Auth::user();
        if (!$authUser->hasRole('Founder')) return $this->baseFailed('抱歉，您没有操作权限');
        return $this->baseSucceed($this->data, $this->message);
    }

    public function destroyManyValidate($message_ids)
    {
        $authUser = Auth::user();
        if (!$authUser->hasRole('Founder')) return $this->baseFailed('抱歉，您没有操作权限');
        return $this->baseSucceed($this->data, $this->message);
    }

    protected function validate($request_data, $rules)
    {
        $message = [
            'title.between' => '标题只能在:min-:max个字符范围',
            'message_type.required' => '请选择消息类型',
        ];
        $validator = Validator::make($request_data, $rules, $message);
        if ($validator->fails()) {
            $this->message = $validator->errors()->first();;
            return false;
        }
        return true;
    }
}
