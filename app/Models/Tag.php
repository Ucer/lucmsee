<?php

namespace App\Models;

use DB;

class Tag extends Model
{
    protected $fillable = [
        'name'
    ];
    public function articles()
    {
        return $this->morphedByMany('App\Models\Article', 'model', 'model_has_tags', 'tag_id');
    }

    public function storeAction($input)
    {
        try {
            $this->fill($input)->save();
            return $this->baseSucceed([], '操作成功');
        } catch (\Exception $e) {
            return $this->baseFailed('内部错误');
        }
    }

    public function updateAction($input)
    {
        try {
            $this->fill($input)->save();
            $this->save();
            return $this->baseSucceed([], '操作成功');
        } catch (\Exception $e) {
            return $this->baseFailed('内部错误');
        }
    }

    public function destroyAction()
    {
        DB::beginTransaction();
        try {
            $this->delete();
            DB::commit();
            return $this->baseSucceed([], '删除成功');
        } catch (\Exception $e) {
            DB::rollBack();
            return $this->baseFailed('内部错误');
        }
    }

}
