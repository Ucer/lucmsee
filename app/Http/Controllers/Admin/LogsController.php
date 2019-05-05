<?php

namespace App\Http\Controllers\Admin;

use App\Http\Resources\CommonCollection;
use App\Models\Log;
use App\Models\User;
use App\Validates\LogValidate;
use Illuminate\Http\Request;
use Auth;

class LogsController extends AdminController
{
    public function __construct()
    {
        parent::__construct();
        $this->middleware('auth:api');
    }

    public function list(Request $request, Log $model)
    {
        $per_page = $request->get('per_page', 10);
        $search_data = json_decode($request->get('search_data'), true);

        $real_name = isset_and_not_empty($search_data, 'real_name');
        if ($real_name) {
            $model_user = new User();
            $user_ids = $model_user->columnEqualSearch('real_name', $real_name)->pluck('id')->toArray();
            $model = $model->columnInsearch('user_id', $user_ids);
        }

        $type = isset_and_not_empty($search_data, 'type');
        if ($type) {
            $model = $model->columnEqualSearch('type', $type);
        }
        $table_name = isset_and_not_empty($search_data, 'table_name');
        if ($table_name) {
            $model = $model->columnEqualSearch('table_name', $table_name);
        }

        $start_time = isset_and_not_empty($search_data, 'start_time');
        if ($start_time) {
            $model = $model->where('created_at', '>=', $start_time);
        }
        $end_time = isset_and_not_empty($search_data, 'end_time');
        if ($end_time) {
            $model = $model->where('created_at', '<=', $end_time);
        }

        $order_by = isset_and_not_empty($search_data, 'order_by');
        if ($order_by) {
            $order_by = explode(',', $order_by);
            $model = $model->orderBy($order_by[0], $order_by[1]);
        }

        return new CommonCollection($model->with('user')->paginate($per_page));
    }

    public function show(Log $model)
    {
        return $this->success($model);
    }

    public function destroy(Log $model, LogValidate $validate)
    {
        $rest_validate = $validate->destroyValidate($model);

        if ($rest_validate['status'] === false) return $this->failed($rest_validate['message']);
        $rest_destroy = $model->destroyAction();
        if ($rest_destroy['status'] === true) {
            return $this->message($rest_destroy['message']);
        }
        return $this->message('数据删除成功');
    }
}
