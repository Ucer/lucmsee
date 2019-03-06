<?php

namespace App\Models;


use Illuminate\Database\Eloquent\SoftDeletes;

class Article extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'title', 'slug', 'keywords', 'description', 'cover_image', 'content', 'user_id', 'article_category_id', 'view_count', 'vote_count', 'comment_count',
        'collection_count', 'enable', 'recommend', 'top', 'weight', 'access_type', 'access_value'
    ];

    /**
     * Set the title and the readable slug.
     *
     * @param $value
     */
    public function setTitleAttribute($value)
    {
        $this->attributes['title'] = $value;

        if (!config('services.youdao.appKey') || !config('services.youdao.appSecret')) {
            $this->setUniqueSlug($value, str_random(7));
        } else {
            $this->setUniqueSlug(translug($value), '');
        }
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
