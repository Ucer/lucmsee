<?php

namespace App\Validates;

use App\Models\AdminMessage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use DB;
use Auth;

class  AdminMessageValidate extends Validate
{
    protected $message = '操作成功';
    protected $data = [];

    public function sendMessageToAdminValidate($request_data)
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

    public function readOneValidate($model, $authId)
    {
        if ($model->admin_id != $authId) {
            return $this->baseFailed('抱歉，您没有权限删除其它管理员的消息');
        }
        return $this->baseSucceed();
    }

    public function destroyValidate($model)
    {
        $authUser = Auth::user();

        if ($model->admin_id != $authUser->id) {
            if (!$authUser->hasRole('Founder')) return $this->baseFailed('抱歉，您没有权限删除其它管理员的消息');
        }
        return $this->baseSucceed($this->data, $this->message);
    }

    public function destroyBatchValidate($message_ids)
    {
        $authUser = Auth::user();
        $m_adminMessage = new AdminMessage();
        if (!$authUser->hasRole('Founder')) {
            foreach ($message_ids as $key => $message_id) {
                $value = $m_adminMessage->find($message_id);
                if ($value->admin_id != $authUser->id) {
                    unset($message_ids[$key]);
                }
            }
        }
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
