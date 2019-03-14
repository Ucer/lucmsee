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
        $sendByMyself_or_beloneMe = isset_and_not_empty($search_data, 'sendByMyself_or_beloneMe');
        $authId = Auth::id();
        if ($sendByMyself_or_beloneMe == 'send_by_myself') {
            $model = $model->where('user_id', $authId);
        } elseif ($sendByMyself_or_beloneMe == 'belone_me') {
            $model = $model->whereIn('admin_id', [$authId, 0]);
        }
        $order_by = isset_and_not_empty($search_data, 'order_by');
        if ($order_by) {
            $order_by = explode(',', $order_by);
            $model = $model->orderBy($order_by[0], $order_by[1]);
        }

        $model = $model->with('user', 'adminUser')->paginate($per_page);
        return new CommonCollection($model);
    }

    public function sendMessageToAdmin(Request $request, AdminMessage $model, AdminMessageValidate $validate)
    {
        $request_data = $request->all();
        $is_send_to_all = true;
        if ($request_data['users']) {
            $is_send_to_all = false;
        } else {
            unset($request_data['users']);
        }
        $rest_validate = $validate->sendMessageToAdminValidate($request_data);
        if ($rest_validate['status'] === true) {
            $new_request_data = $rest_validate['data'];
            $admin_id = Auth::id();
            if ($is_send_to_all) {
                $rest = $model->manyMessage([], $new_request_data['title'], $new_request_data['content'], $admin_id, $new_request_data['message_type']);
            } else {
                $rest = $model->manyMessage($new_request_data['users'], $new_request_data['title'], $new_request_data['content'], $admin_id, $new_request_data['message_type']);
            }
            if ($rest['status'] === true) return $this->message($rest['message']);
            return $this->failed($rest['message']);
        } else {
            return $this->failed($rest_validate['message']);
        }
    }

    public function readOne(AdminMessage $model, AdminMessageValidate $validate)
    {
        $authId = Auth::id();
        $rest_validate = $validate->readOneValidate($model, $authId);
        if ($rest_validate['status'] === true) {
            $model->is_read = 'T';
            $model->save();
            return $this->message('操作成功');
        } else {
            return $this->failed($rest_validate['message']);
        }
    }

    public function readAll(Request $request, AdminMessage $model)
    {
        $model->where('is_read', 'F')->whereIn('admin_id', [Auth::id(), 0])->update(['is_read' => 'T']);
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

    public function destroyBatch(AdminMessage $model, AdminMessageValidate $validate, $ids)
    {
        $rest_validate = $validate->destroyBatchValidate($model);
        if ($rest_validate['status'] === true) {
            $model->whereIn('id', explode(',', $ids))->delete();
            return $this->message('操作成功');
        } else {
            return $this->failed($rest_validate['message']);
        }
    }

}
