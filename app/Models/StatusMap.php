<?php

namespace App\Models;

use DB;
use Illuminate\Database\Eloquent\SoftDeletes;


class StatusMap extends Model
{
    use SoftDeletes;
    protected $fillable = [
        'table_name','column', 'status_code', 'status_description', 'remark'
    ];


    public function storeAction($input)
    {
        try {
            $this->fill($input);
            $this->save();
            return $this->baseSucceed([], '操作成功');
        } catch (\Exception $e) {
            throw $e;
            return $this->baseFailed('内部错误');
        }
    }

    public function updateAction($input)
    {
        try {
            $this->fill($input)->save();
            return $this->baseSucceed([], '操作成功');
        } catch (\Exception $e) {
            return $this->baseFailed('内部错误');
        }
    }
    public function destroyAction()
    {
        try {
            $this->delete();
            return $this->baseSucceed([], '表状态删除成功');
        } catch (\Exception $e) {
            return $this->baseFailed('内部错误');
        }
    }
}
