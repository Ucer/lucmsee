<?php

namespace App\Models;


class ArticleCategory extends Model
{

    protected $fillable = [
        'name', 'pid', 'enable', 'weight', 'description'
    ];

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
        try {
            $this->delete();
            return $this->baseSucceed([], '文章分类删除成功');
        } catch (\Exception $e) {
            return $this->baseFailed('内部错误');
        }
    }

}
