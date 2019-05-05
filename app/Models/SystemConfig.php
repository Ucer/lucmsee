<?php

namespace App\Models;
use DB;


class SystemConfig extends Model
{

    protected $fillable = [
        'flag', 'title', 'config_group', 'value', 'weight', 'enable', 'description'
    ];

    public function storeAction($input)
    {
        try {
            $input['value'] = str_replace([',', '，', '，'], [',', ',', ','], $input['value']);

            $this->fill($input);
            $this->save();
            return $this->baseSucceed([], '操作成功');
        } catch (\Exception $e) {
            return $this->baseFailed('内部错误');
        }
    }

    public function updateAction($input)
    {
        DB::beginTransaction();
        try {
            $input['value'] = str_replace([',', '，', '，'], [',', ',', ','], $input['value']);
            $this->fill($input)->save();
            DB::commit();
            return $this->baseSucceed([], '操作成功');
        } catch (\Exception $e) {
            DB::rollBack();
            return $this->baseFailed('内部错误');
        }
    }

    public function destroyAction()
    {
        try {
            $this->delete();
            return $this->baseSucceed([], '系统配置删除成功');
        } catch (\Exception $e) {
            return $this->baseFailed('内部错误');
        }
    }

}
