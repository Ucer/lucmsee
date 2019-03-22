<?php

namespace App\Models;


class Carousel extends Model
{

    protected $fillable = [
        'title', 'cover_image', 'description', 'url', 'weight'
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
            $this->fill($input);
            $this->save();
            return $this->baseSucceed([], '操作成功');
        } catch (\Exception $e) {
            throw $e;
            return $this->baseFailed('内部错误');
        }
    }

    public function destroyAction()
    {
        $this->delete();
        return $this->baseSucceed();
    }
}
