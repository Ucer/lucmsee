<?php

namespace App\Validates;

use Illuminate\Support\Facades\Validator;
use Auth;

class  DatabaseValidate extends Validate
{
    protected $message = '操作成功';
    protected $data = [];


    public function bakTableValidate()
    {
        $authUser = Auth::user();
        if(!$authUser->hasRole('Founder')) return $this->baseFailed('抱歉，您没有操作权限');
        return $this->baseSucceed();
    }

    public function tableBakSqlFileDownloadValidate()
    {
        $authUser = Auth::user();
        if(!$authUser->hasRole('Founder')) return $this->baseFailed('抱歉，您没有操作权限');
        return $this->baseSucceed();
    }

    public function destroyManyTableBakRecordValidate()
    {
        $authUser = Auth::user();
        if(!$authUser->hasRole('Founder')) return $this->baseFailed('抱歉，您没有操作权限');
        return $this->baseSucceed();
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
