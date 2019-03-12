<?php

namespace App\Http\Controllers\Admin;


use App\Http\Resources\CommonCollection;
use App\Models\AdminMessage;
use App\Validates\AdminMessageValidate;
use Illuminate\Http\Request;
use Auth;

class AdminMessagesController extends AdminController
{
    public function __construct()
    {
        parent::__construct();
        $this->middleware('auth:api');
    }

    public function list(Request $request, AdminMessage $model)
    {
        $per_page = $request->get('per_page', 10);

        $search_data = json_decode($request->get('search_data'), true);

        $message_type = isset_and_not_empty($search_data, 'message_type');
        if ($message_type) {
            $model = $model->columnEqualSearch('message_type', $message_type);
        }
        $is_read = isset_and_not_empty($search_data, 'is_read');
        if ($is_read) {
            $model = $model->columnEqualSearch('is_read', $is_read);
        }
        $order_by = isset_and_not_empty($search_data, 'order_by');
        if ($order_by) {
            $order_by = explode(',', $order_by);
            $model = $model->orderBy($order_by[0], $order_by[1]);
        }

        $model = $model->with('user')->paginate($per_page);
        return new CommonCollection($model);
    }


    public function readMessages(Request $request, AdminMessage $model)
    {
        $is_read_all = $request->is_read_all;
        $message_ids = $request->message_ids;
        if ($is_read_all) {
            $model->where('is_read', 'F')->whereIn('admin_id', [Auth::id(), 0])->update(['is_read' => 'T']);
        } else {
            $model->whereIn('id', explode(',', $message_ids))->update(['is_rea' => 'T']);
        }
        return $this->message('操作成功');

    }

    public function destroy(AdminMessage $model, AdminMessageValidate $validate)
    {
        $rest_validate = $validate->destroyValidate($model);

        if ($rest_validate['status'] === true) {
            $rest_destroy = $model->destroyAction();
            if ($rest_destroy['status'] === true) return $this->message($rest_destroy['message']);
            return $this->failed($rest_destroy['message']);
        } else {
            return $this->failed($rest_validate['message']);
        }
    }

    public function destroyMany(AdminMessage $model, AdminMessageValidate $validate, $ids)
    {
        $rest_validate = $validate->destroyManyValidate($model);
        if ($rest_validate['status'] === true) {
            $model->whereIn('id', explode(',', $ids))->delete();
            return $this->message('操作成功');
        } else {
            return $this->failed($rest_validate['message']);
        }
    }

}
