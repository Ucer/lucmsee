<?php

namespace App\Models;


class AppVersion extends Model
{
    protected $fillable = [
        'name', 'mobile_os', 'version_sn', 'description', 'package_url', 'is_force_update'
    ];

    public function storeAction($input)
    {
        try {
            $this->saveData($input);
            return $this->baseSucceed([], '操作成功');
        } catch (\Exception $e) {
            return $this->baseFailed('内部错误');
        }

    }

    public function updateAction($input)
    {
        try {
            $this->saveData($input);
            return $this->baseSucceed([], '操作成功');
        } catch (\Exception $e) {
            return $this->baseFailed('内部错误');
        }
    }

    public function destroyAppVersion()
    {
        try {
            $this->delete();
            return $this->baseSucceed([], '删除成功');
        } catch (\Exception $e) {
            return $this->baseFailed('内部错误');
        }
    }
}
