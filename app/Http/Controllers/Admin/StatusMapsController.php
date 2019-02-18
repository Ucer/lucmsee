<?php

namespace App\Http\Controllers\Admin;

use App\Http\Resources\CommonCollection;
use App\Http\Resources\UserResource;
use App\Models\AdminMessage;
use App\Models\StatusMap;
use App\Validates\StatusMapValidate;
use App\Validates\UserValidate;
use App\Models\User;
use Illuminate\Http\Request;
use Auth;

class StatusMapsController extends AdminController
{
    public function __construct()
    {
        parent::__construct();
        $this->middleware('auth:api');
    }

    public function list(Request $request, StatusMap $model)
    {
        $per_page = $request->get('per_page', 10);
        $search_data = json_decode($request->get('search_data'), true);

        $table_name = isset_and_not_empty($search_data, 'table_name');
        if ($table_name) {
            $model = $model->columnLikeSearch('table_name', '%'.$table_name);
        }

        $column = isset_and_not_empty($search_data, 'column');
        if ($column) {
            $model = $model->columnEqualSearch('column',$column);
        }

        $order_by = isset_and_not_empty($search_data, 'order_by');
        if ($order_by) {
            $order_by = explode(',', $order_by);
            $model = $model->orderBy($order_by[0], $order_by[1]);
        }

        return $this->success($model->get());
    }


    public function show(StatusMap $model)
    {
        return $this->success($model);
    }

    public function store(Request $request, StatusMap $model, StatusMapValidate $validate)
    {
        $request_data = $request->all();
        $rest_validate = $validate->storeValidate($request_data);
        if ($rest_validate['status'] === false) return $this->failed($rest_validate['message']);

        $res = $model->storeAction($request_data);
        if ($res['status'] === true) {
            admin_log_record(Auth::id(), 'insert', 'status_map', '添加数据字典', $request_data);
            return $this->message($res['message']);
        }
        return $this->failed($res['message']);

    }

    public function update(StatusMap $model, Request $request, StatusMapValidate $validate)
    {
        $request_data = $request->all();

        $rest_validate = $validate->updateValidate($request_data);

        if ($rest_validate['status'] === false) return $this->failed($rest_validate['message']);
        $res = $model->updateAction($request_data);

        if ($res['status'] === true) {
            admin_log_record(Auth::id(), 'update', 'status_maps', '修改数据字典', $request_data);
            return $this->message($res['message']);
        }
        return $this->failed($res['message']);
    }

    public function destroy(User $model, StatusMapValidate $validate)
    {
//        if (!$model) return $this->failed('找不到用户', 404);
//
//        $rest_validate = $validate->destroyValidate($model);
//
//        if ($rest_validate['status'] === false) return $this->failed($rest_validate['message']);
//        $rest_destroy = $model->destroyAction();
//        if ($rest_destroy['status'] === true) {
//            admin_log_record(Auth::id(), 'destroy', 'users', '删除用户', $model->toArray());
//            return $this->message($rest_destroy['message']);
//        }
//        return $this->failed($rest_destroy['message'], 500);
    }


    public function getCascadeSourceDataForStatusMap(Request $request,$type)
    {
        $search_data = json_decode($request->get('search_data'), true);
        $book_id = isset_and_not_empty($search_data, 'book_id');
        $handle_function = new FunctionHandler();
        if ($type === 'yes') { // 获取所有
            $book_ids  = [];
        } else { // 仅获取管理员可见的
            $book_ids = (new User())->getUserBooks(Auth::id());
            if(!in_array($book_id,$book_ids->toArray())) return $this->success([]);
        }
        if($book_id) {
            $book_ids = [$book_id];
        }
        $list = $handle_function->getBookNodeBySelect($book_ids);
        return $this->success($list);

    }
}
