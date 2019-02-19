<?php

namespace App\Models;

use DB;
use Illuminate\Database\Eloquent\SoftDeletes;


class Table extends Model
{
    use SoftDeletes;
    protected $fillable = [
        'table_name','table_name_cn', 'remark'
    ];


    public function storeAction($input)
    {
        try {
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
            $this->fill($input)->save();
            if($this->table_name != $input['table_name']) {
                StatusMap::where('table_name',$this->table_name)->update(['table_name' => $input['table_name']]);
            }
            DB::commit();
            return $this->baseSucceed([], '操作成功');
        } catch (\Exception $e) {
            DB::rollBack();
            return $this->baseFailed('内部错误');
        }
    }
    public function destroyAction()
    {
        DB::beginTransaction();
        try {
            $this->delete();
            StatusMap::where('table_name',$this->table_name)->delete();
            DB::commit();
            return $this->baseSucceed([], '角色删除成功');
        } catch (\Exception $e) {
            DB::rollBack();
            return $this->baseFailed('内部错误');
        }
    }
}
