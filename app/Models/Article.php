<?php

namespace App\Models;


use App\Handlers\MarkdownerHandler;
use App\Models\Traits\ArticleFilterTrait;
use Auth;
use Purifier;
use DB;

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

    protected function setContentAttribute($value)
    {
        /***
         * wangeditor
        $value = Purifier::clean($value, 'purifier_article_content');
        $data = [
            'raw' => '',
            'html' => $value,
        ];
         */

        /***
         * markdowneditor
         */
        $data = [
            'raw' => $value,
            'html' => (new MarkdownerHandler())->convertMarkdownToHtml($value)
        ];

        $this->attributes['content'] = json_encode($data);
    }

    protected function getContentAttribute($value)
    {
        return json_decode($value, true);
    }

    public function storeAction($input)
    {
        DB::beginTransaction();
        try {

            $this->fill($input);
            $this->user_id = Auth::id();
            $this->save();

            if (is_array($input['tags']) && count($input['tags']) > 0) {
                $this->syncTag($input['tags']);
            }
            DB::commit();
            return $this->baseSucceed([], '操作成功');
        } catch (\Exception $e) {
            throw $e;
            DB::rollBack();
            return $this->baseFailed('内部错误');
        }
    }

    public function updateAction($input)
    {
        DB::beginTransaction();
        try {
            $this->fill($input)->save();
            if ($input['tags']) {
                $this->syncTag($input['tags']);
            } else {
                $this->syncTag([]);
            }

            DB::commit();
            return $this->baseSucceed([], '操作成功');
        } catch (\Exception $e) {
            throw $e;
            DB::rollBack();
            return $this->baseFailed('内部错误');
        }
    }

    public function destroyAction()
    {
        DB::beginTransaction();
        try {
            $this->syncTag([]);
            $this->delete();
            DB::commit();
            return $this->baseSucceed([], '文章删除成功');
        } catch (\Exception $e) {
            DB::rollBack();
            return $this->baseFailed('内部错误');
        }
    }

}
