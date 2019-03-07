<?php

namespace App\Models;


use App\Models\Traits\ArticleFilterTrait;

class Article extends Model
{
    use ArticleFilterTrait;
    protected $fillable = [
        'title', 'slug', 'keywords', 'description', 'cover_image', 'content', 'user_id', 'article_category_id', 'view_count', 'vote_count', 'comment_count',
        'collection_count', 'enable', 'recommend', 'top', 'weight', 'access_type', 'access_value'
    ];

    public function user()
    {
        return $this->belongsTo('App\Models\User');
    }

    public function articleCategory()
    {
        return $this->belongsTo('App\Models\ArticleCategory');
    }

    public function tags()
    {
        return $this->morphToMany('App\Models\Tag', 'model', 'model_has_tags', 'model_id');
    }

    public function syncTag($tags = '')
    {
        return $this->tags()->sync($tags);
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
        try {
            $this->delete();
            return $this->baseSucceed([], '文章删除成功');
        } catch (\Exception $e) {
            return $this->baseFailed('内部错误');
        }
    }

}
