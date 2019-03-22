<?php

namespace App\Validates;

use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use DB;
use Auth;

class  CarouselValidate extends Validate
{
    protected $message = '操作成功';
    protected $data = [];

    public function storeValidate($request_data)
    {
        $rules = [
            'cover_image' => 'required',
            'weight' => 'required|numeric'
        ];
        $rest_validate = $this->validate($request_data, $rules);
        if ($rest_validate === true) {
            $request_data['url'] = isset_and_not_empty($request_data, 'url');
            $request_data = unset_if_no_value($request_data, ['title', 'description']);
            $request_data['cover_image'] = $request_data['cover_image']['url'];
            return $this->baseSucceed($request_data, $this->message);
        } else {
            $this->message = $rest_validate;
            return $this->baseFailed($this->message);
        }
    }


    public function updateValidate($request_data, $model = '')
    {
        $rules = [
            'cover_image' => 'required',
            'weight' => 'required|numeric'
        ];
        $rest_validate = $this->validate($request_data, $rules);
        if ($rest_validate === true) {
            $request_data['url'] = isset_and_not_empty($request_data, 'url');
            $request_data = unset_if_no_value($request_data, ['title', 'description']);
            $request_data['cover_image'] = $request_data['cover_image']['url'];
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
        return $this->baseSucceed($this->data, $this->message);
    }

    protected function validate($request_data, $rules)
    {
        $message = [
            'cover_image.required' => '必须上传图片',
            'weight.required' => '请填写排序',
            'weight.numeric' => '排序字段格式不正确',
        ];
        $validator = Validator::make($request_data, $rules, $message);
        if ($validator->fails()) {
            return $validator->errors()->first();
        }
        return true;
    }
}
