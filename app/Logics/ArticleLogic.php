<?php

namespace App\Logics;

use App\Models\Article;

class ArticleLogic extends Logic
{
    /**
     * 获取最近的五篇文章
     * @return mixed
     */
    public function getLatestFiveArticle()
    {
        $field = ['id', 'title', 'description'];
        $latest_articles = Article::where('access_type', 'pub')
            ->where('enable', 'T')
            ->orderBy('id', 'desc')
            ->select($field)
            ->take(5)
            ->get();
        return $latest_articles;
    }
}