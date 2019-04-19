<?php

namespace App\Validates;

use App\Models\AppVersion;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use DB;
use Auth;

class  AppVersionValidate extends Validate
{
    protected $message = '操作成功';
    protected $data = [];

    public function storeValidate($request_data)
    {
        $rules = [
            'name' => 'required',
            'mobile_os' => 'required',
            'version_sn' => 'required|between:3,20',
            'description' => 'required|between:5,1000',
            'is_force_update' => [
                'bail',
                'required',
                Rule::in(['T', 'F'])
            ],
        ];
        $rest_validate = $this->validate($request_data, $rules);
        if ($rest_validate === true) {
            $authUser = Auth::user();
            if (!$authUser->hasRole('Founder')) return $this->baseFailed('抱歉，您没有操作权限');

            $versionSn_is_exsist = AppVersion::columnEqualSearch('name', $request_data['name'])
                ->columnEqualSearch('mobile_os', $request_data['mobile_os'])
                ->columnEqualSearch('version_sn', $request_data['version_sn'])
                ->count();
            if ($versionSn_is_exsist) return $this->baseFailed('版本号已存在');

            $request_data['package_url'] = isset_and_not_empty($request_data, 'package_url');
            if ($request_data['package_url']) {
                $request_data['package_url'] = $request_data['package_url']['url'];
            }

            return $this->baseSucceed($request_data, $this->message);
        } else {
            return $this->baseFailed($this->message);
        }
    }

    public function updateValidate($request_data, $model)
    {
        $rules = [
            'name' => 'required',
            'mobile_os' => 'required',
            'version_sn' => 'required|between:3,20',
            'description' => 'required|between:5,1000',
            'is_force_update' => [
                'required',
                Rule::in(['T', 'F'])
            ],
        ];
        $rest_validate = $this->validate($request_data, $rules);
        if ($rest_validate === true) {
            $authUser = Auth::user();
            if (!$authUser->hasRole('Founder')) return $this->baseFailed('抱歉，您没有操作权限');

            $versionSn_is_exsist = AppVersion::columnEqualSearch('name', $request_data['name'])
                ->columnEqualSearch('mobile_os', $request_data['mobile_os'])
                ->columnEqualSearch('version_sn', $request_data['version_sn'])
                ->where('id', '<>', $model->id)
                ->count();
            if ($versionSn_is_exsist) return $this->baseFailed('版本号已存在');

            $request_data['package_url'] = isset_and_not_empty($request_data, 'package_url');
            if ($request_data['package_url']) {
                $request_data['package_url'] = $request_data['package_url']['url'];
            }

            return $this->baseSucceed($request_data, $this->message);
        } else {
            return $this->baseFailed($this->message);
        }
    }

    protected function validate($request_data, $rules)
    {
        $message = [
            'name.required' => '请选择app名称',
            'mobile_os.required' => '请选择手机操作系统',
            'version_sn.required' => '请选择版本号',
            'version_sn.between' => '版本号只能在:min-:max个字符范围',
            'description.required' => '请填写描述',
            'description.between' => '描述只能在:min-:max个字符范围',
        ];
        $validator = Validator::make($request_data, $rules, $message);
        if ($validator->fails()) {
            $this->message = $validator->errors()->first();;
            return false;
        }
        return true;
    }
}
