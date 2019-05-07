<?php

namespace App\Models\Traits;

use App\Models\Tag;
use Illuminate\Support\Facades\DB;

trait ArticleFilterTrait
{

    /**
     * @param $searchData
     * @param $order
     * @param $order_type
     * @param $limit
     * @return mixed
     */
    public function getArticlesWithFilter($searchData, $order, $order_type, $limit)
    {
        $filter = $searchData['filter'];
        $user_id = $searchData['user_id'];
        $title = $searchData['title'];
        $tag_id = $searchData['tag_id'];
        $category_id = $searchData['category_id'];
        $recommend = $searchData['recommend'];
        $top = $searchData['top'];
        $enable = $searchData['enable'];

        $filter = $this->getArticleFilter($filter);

        return $this->applyFilter($filter, $user_id, $title, $tag_id, $category_id, $recommend, $top, $enable, $order, $order_type)
            ->with('user', 'articleCategory', 'tags')
            ->paginate($limit);
    }

    public function getArticlesWithWhoFilter($filter, $limit = 5, $user_id = 0)
    {

        $filter = $this->getArticleFilter($filter);

        return $this->applyFilter($filter, 0)
            ->where('user_id', '=', $user_id)
            ->paginate($limit);
    }


    protected function applyFilter($filter, $user_id, $title, $tag_id, $category_id, $recommend, $top, $enable, $order, $order_type)
    {
        $query = $this;
        if ($user_id) {
            $query = $query->useridSearch($user_id);
        }

        if ($category_id) {
            $query = $query->ArticleCategorySearch($category_id);
        }

        if ($recommend) {
            $query = $query->recommendSearch($recommend);
        }

        if ($top) {
            $query = $query->topSearch($top);
        }

        if ($enable) {
            $query = $query->enableSearch($enable);
        }

        if ($tag_id) {
            $article_ids = DB::table('model_has_tags')
                ->where('tag_id', $tag_id)
                ->where('model_type', 'App\Models\Article')
                ->distinct()
                ->pluck('model_id');
            $query = $query->articleIdSearch($article_ids);
        }

        if ($title) {
            $query = $query->titleSearch($title);
        }

        if ($filter) {
            $query = $query->withAccessType($filter);
        }
        return $query->orderBy($order, $order_type);
    }

    public function scopeUseridSearch($query, $user_id)
    {
        return $query->where('user_id', '=', $user_id);
    }

    public function scopeTitleSearch($query, $title)
    {
        return $query->where('title', 'like', '%' . $title . '%');
    }

    public function scopeArticleCategorySearch($query, $category_id)
    {
        return $query->where('article_category_id', '=', $category_id);
    }

    public function scopeRecommendSearch($query, $recommend)
    {
        return $query->where('recommend', '=', $recommend);
    }

    public function scopeTopSearch($query, $top)
    {
        return $query->where('top', '=', $top);
    }

    public function scopeWithAccessType($query, $access_type)
    {
        return $query->where('access_type', '=', $access_type);
    }

    public function scopeArticleIdSearch($query, $articleid)
    {
        return $query->whereIn('id', $articleid);
    }

    protected function getArticleFilter($request_filter)
    {
        $filters = ['pub', 'pri', 'pwd'];
        if (in_array($request_filter, $filters)) {
            return $request_filter;
        }
        return '';
    }

}
