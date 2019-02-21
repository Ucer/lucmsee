<?php

namespace App\Http\Controllers\Admin;

use App\Models\Table;
use App\Validates\TableValidate;
use Illuminate\Http\Request;
use Auth;

class TablesController extends AdminController
{
    public function __construct()
    {
        parent::__construct();
        $this->middleware('auth:api');
    }

    public function list(Request $request, Table $model)
    {
        $per_page = $request->get('per_page', 10);
        $search_data = json_decode($request->get('search_data'), true);

        $table_name = isset_and_not_empty($search_data, 'table_name');
        if ($table_name) {
            $model = $model->where('table_name','like', '%'.$table_name)->orWhere('table_name_cn','like','%'.$table_name.'%');
        }

        $order_by = isset_and_not_empty($search_data, 'order_by');
        if ($order_by) {
            $order_by = explode(',', $order_by);
            $model = $model->orderBy($order_by[0], $order_by[1]);
        }

        return $this->success($model->get());
    }


    public function show(Table $model)
    {
        return $this->success($model);
    }

    public function store(Request $request, Table $model, TableValidate $validate)
    {
        $request_data = $request->all();

        $rest_validate = $validate->storeValidate($request_data);
        if ($rest_validate['status'] === false) return $this->failed($rest_validate['message']);
        $new_request_data = $rest_validate['data'];

        $res = $model->storeAction($new_request_data);
        if ($res['status'] === true) {
            admin_log_record(Auth::id(), 'insert', 'tables', '添加数据表', $new_request_data);
            return $this->message($res['message']);
        }
        return $this->failed($res['message']);

    }

    public function update(Table $model, Request $request, TableValidate $validate)
    {
        $request_data = $request->all();

        $rest_validate = $validate->updateValidate($request_data,$model);
        if ($rest_validate['status'] === false) return $this->failed($rest_validate['message']);
        $new_request_data = $rest_validate['data'];

        $res = $model->updateAction($new_request_data);
        if ($res['status'] === true) {
            admin_log_record(Auth::id(), 'update', 'tables', '修改数据表,如果表名有改动，同时修改status_maps表中的表名', $new_request_data);
            return $this->message($res['message']);
        }
        return $this->failed($res['message']);
    }

    public function destroy(Table $model, TableValidate $validate)
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


}