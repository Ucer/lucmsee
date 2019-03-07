<?php

namespace App\Http\Controllers\Admin;

use App\Http\Resources\CommonCollection;
use App\Models\Article;
use App\Traits\TableStatusTrait;
use App\Validates\ArticleValidate;
use Illuminate\Http\Request;
use Auth;

class ArticlesController extends AdminController
{
    public function __construct()
    {
        parent::__construct();
        $this->middleware('auth:api');
    }

    public function list(Request $request, Article $model)
    {
        $per_page = $request->get('per_page', 10);
        $search_data = json_decode($request->get('search_data'), true);
        $tag_id = 0;
        $user_id = 0;
        $filter = isset_and_not_empty($search_data, 'filter');
        $title = isset_and_not_empty($search_data, 'title');
        $article_category_id = isset_and_not_empty($search_data, 'article_category_id');
        $recommend = isset_and_not_empty($search_data, 'recommend');
        $top = isset_and_not_empty($search_data, 'top');
        $enable = isset_and_not_empty($search_data, 'enable');
        $order = 'created_at';
        $order_type = 'desc';

        $order_by = isset_and_not_empty($search_data, 'order_by');
        if ($order_by) {
            $order_by = explode(',', $order_by);
            $order = $order_by[0];
            $order_type = $order_by[1];
        }

        $list = $model->getArticlesWithFilter($filter, $user_id, $title, $tag_id, $article_category_id, $recommend, $top, $enable, $order, $order_type, $per_page);
        return new CommonCollection($list);
    }

    public function show(Article $model)
    {
        return $this->success($model);
    }

    public function store(Request $request, Article $model, ArticleValidate $validate)
    {
        $request_data = $request->all();
        $request_data['description'] = strval($request_data['description']);
        $rest_validate = $validate->storeValidate($request_data);
        if ($rest_validate['status'] === false) return $this->failed($rest_validate['message']);
        $new_request_data = $rest_validate['data'];

        $res = $model->storeAction($new_request_data);
        if ($res['status'] === true) return $this->message($res['message']);
        return $this->failed($res['message']);
    }


    public function update(Request $request, Article $model, ArticleValidate $validate)
    {
        $request_data = $request->all();
        $request_data['description'] = strval($request_data['description']);
        $rest_validate = $validate->updateValidate($request_data, $model);
        if ($rest_validate['status'] === false) return $this->failed($rest_validate['message']);
        $new_request_data = $rest_validate['data'];

        $res = $model->storeAction($new_request_data);
        if ($res['status'] === true) return $this->message($res['message']);
        return $this->failed($res['message']);
    }

    public function destroy(Article $model, ArticleValidate $validate)
    {

        $rest_validate = $validate->destroyValidate($model);

        if ($rest_validate['status'] === false) return $this->failed($rest_validate['message']);
        $rest_destroy = $model->destroyAction();
        if ($rest_destroy['status'] === true) {
            admin_log_record(Auth::id(), 'destroy', 'articles', '删除文章分类', $model->toArray());
            return $this->message($rest_destroy['message']);
        }
        return $this->message('数据删除成功');
    }
}
