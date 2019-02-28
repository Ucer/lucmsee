<?php

namespace App\Http\Controllers\Admin;

use App\Handlers\FunctionHandler;
use App\Models\ArticleCategory;
use App\Traits\TableStatusTrait;
use App\Validates\ArticleCategoryValidate;
use Illuminate\Http\Request;
use Auth;

class ArticleCategoriesController extends AdminController
{
    use TableStatusTrait;

    public function __construct()
    {
        parent::__construct();
        $this->middleware('auth:api');
    }

    public function list(Request $request, ArticleCategory $model)
    {
        $search_data = json_decode($request->get('search_data'), true);
        $name = isset_and_not_empty($search_data, 'name');
        if ($name) {
            $model = $model->columnLikeSearch('name', '%' . $name);
        }
        $enable = isset_and_not_empty($search_data, 'enable');
        if ($enable) {
            $model = $model->columnEqualSearch('enable', $enable);
        }
        $order_by = isset_and_not_empty($search_data, 'order_by');
        if ($order_by) {
            $order_by = explode(',', $order_by);
            $model = $model->orderBy($order_by[0], $order_by[1]);
        }
        $list = $model->get()->toArray();
        $list = (new FunctionHandler())->formatArticleCategoryTree($list);

        return $this->success($list);
    }

    /**
     * 获取系统配置分组
     * @return mixed
     */
    public function getAllCategories(ArticleCategory $model)
    {

        $rest_formatArticleCqtegoryTree = (new FunctionHandler())->formatArticleCategoryTree($model->get()->toArray());
        return $this->success($rest_formatArticleCqtegoryTree);
    }

    public function show(ArticleCategory $model)
    {
        return $this->success($model);
    }

    public function store(Request $request, ArticleCategory $model, ArticleCategoryValidate $validate)
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


    public function update(Request $request, ArticleCategory $model, ArticleCategoryValidate $validate)
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

    public function destroy(ArticleCategory $model, ArticleCategoryValidate $validate)
    {

        $rest_validate = $validate->destroyValidate($model);

        if ($rest_validate['status'] === false) return $this->failed($rest_validate['message']);
        $rest_destroy = $model->destroyAction();
        if ($rest_destroy['status'] === true) {
            admin_log_record(Auth::id(), 'destroy', 'article_category', '删除文章分类', $model->toArray());
            return $this->message($rest_destroy['message']);
        }
        return $this->message('数据删除成功');
    }
}
