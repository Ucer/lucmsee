<?php

namespace App\Http\Controllers\Admin;

use App\Models\UserAgreement;
use App\Validates\UserAgreementValidate;
use Illuminate\Http\Request;
use Auth;

class UserAgreementsController extends AdminController
{
    public function __construct()
    {
        parent::__construct();
        $this->middleware('auth:api');
    }

    public function list(Request $request, UserAgreement $model)
    {
        $per_page = $request->get('per_page', 10);
        $search_data = json_decode($request->get('search_data'), true);

        $name = isset_and_not_empty($search_data, 'name');
        if ($name) {
            $model = $model->columnLikeSearch('name', '%' . $name);
        }

        $agree_type = isset_and_not_empty($search_data, 'agree_type');
        if ($agree_type) {
            $model = $model->columnEqualSearch('agree_type', $agree_type);
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
        $list = $model->with('user')->get();
        return $this->success($list);
    }


    public function show(UserAgreement $model)
    {
        return $this->success($model);
    }

    public function store(Request $request, UserAgreement $model, UserAgreementValidate $validate)
    {
        $request_data = $request->all();

        $rest_validate = $validate->storeValidate($request_data);
        if ($rest_validate['status'] === false) return $this->failed($rest_validate['message']);
        $new_request_data = $rest_validate['data'];

        $res = $model->storeAction($new_request_data, Auth::user());
        if ($res['status'] === true) {
            return $this->message($res['message']);
        }
        return $this->failed($res['message']);

    }

    public function update(UserAgreement $model, Request $request, UserAgreementValidate $validate)
    {
        $request_data = $request->all();

        $rest_validate = $validate->updateValidate($request_data, $model);
        if ($rest_validate['status'] === false) return $this->failed($rest_validate['message']);
        $new_request_data = $rest_validate['data'];

        $res = $model->updateAction($new_request_data, Auth::user());
        if ($res['status'] === true) {
            return $this->message($res['message']);
        }
        return $this->failed($res['message']);
    }

    /**
     * 启用禁用
     * @param UserAgreement $model
     * @param UserAgreementValidate $validate
     * @return mixed
     */
    public function enableOrDisable(UserAgreement $model, UserAgreementValidate $validate)
    {
        $rest_validate = $validate->enableOrDisableValidate($model);
        if ($rest_validate['status'] === false) return $this->failed($rest_validate['message']);

        $res = $model->enableOrDisableAction();
        if ($res['status'] === true) {
            return $this->message($res['message']);
        }
        return $this->failed($res['message']);
    }

    public function destroy(UserAgreement $model, UserAgreementValidate $validate)
    {

        $rest_validate = $validate->destroyValidate($model);

        if ($rest_validate['status'] === false) return $this->failed($rest_validate['message']);
        $rest_destroy = $model->destroyAction();
        if ($rest_destroy['status'] === true) {
            return $this->message($rest_destroy['message']);
        }
        return $this->failed($rest_destroy['message']);
    }
}
