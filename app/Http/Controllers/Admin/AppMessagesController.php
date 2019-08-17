<?php

namespace App\Http\Controllers\Admin;


use App\Http\Resources\CommonCollection;
use App\Models\AppMessage;
use App\Models\User;
use App\Validates\AppMessageValidate;
use Illuminate\Http\Request;
use Auth;

class AppMessagesController extends AdminController
{
    public function __construct()
    {
        parent::__construct();
        $this->middleware('auth:api');
    }

    public function list(Request $request, AppMessage $model)
    {
        $per_page = $request->get('per_page', 10);

        $search_data = json_decode($request->get('search_data'), true);

        $send_user_mobile = isset_and_not_empty($search_data, 'send_user_mobile');
        if ($send_user_mobile) {
            $user_ids = User::columnLikeSearch('mobile', '%' . $send_user_mobile)->pluck('id')->toArray();
            $model = $model->columnInSearch('admin_id', $user_ids);
        }

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

        $model = $model->with('user', 'adminUser')->paginate($per_page);
        return new CommonCollection($model);
    }


    public function sendMessageToAppUser(Request $request, AppMessage $model, AppMessageValidate $validate)
    {
        $request_data = $request->all();
        $is_send_to_all = true;
        if ($request_data['users']) {
            $is_send_to_all = false;
        } else {
            unset($request_data['users']);
        }
        $rest_validate = $validate->sendMessageToAppUserValidate($request_data);
        if ($rest_validate['status'] === true) {
            $new_request_data = $rest_validate['data'];
            $admin_id = Auth::id();
            if ($is_send_to_all) {
                $rest = $model->manyMessage([], $new_request_data['title'], $new_request_data['content'], $admin_id, $new_request_data['url'], $new_request_data['is_alert_at_home'], $new_request_data['message_type']);
            } else {
                $rest = $model->manyMessage($new_request_data['users'], $new_request_data['title'], $new_request_data['content'], $admin_id, $new_request_data['url'], $new_request_data['is_alert_at_home'], $new_request_data['message_type']);
            }
            if ($rest['status'] === true) return $this->message($rest['message']);
            return $this->failed($rest['message']);
        } else {
            return $this->failed($rest_validate['message']);
        }
    }

    public function destroy(AppMessage $model, AppMessageValidate $validate)
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

    public function destroyBatch(AppMessage $model, AppMessageValidate $validate, $ids)
    {
        $rest_validate = $validate->destroyManyValidate($model);
        if ($rest_validate['status'] === true) {
            $model->columnInSearch('id', explode(',', $ids))->delete();
            return $this->message('操作成功');
        } else {
            return $this->failed($rest_validate['message']);
        }
    }

}
