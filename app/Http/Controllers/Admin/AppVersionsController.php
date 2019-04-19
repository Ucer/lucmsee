<?php

namespace App\Http\Controllers\Admin;


use App\Http\Resources\CommonCollection;
use App\Models\AppMessage;
use App\Models\AppVersion;
use App\Models\User;
use App\Validates\AppMessageValidate;
use App\Validates\AppVersionValidate;
use Illuminate\Http\Request;
use Auth;

class AppVersionsController extends AdminController
{
    public function __construct()
    {
        parent::__construct();
        $this->middleware('auth:api');
    }

    public function list(Request $request, AppVersion $model)
    {
//        $per_page = $request->get('per_page', 10);

        $search_data = json_decode($request->get('search_data'), true);

        $name = isset_and_not_empty($search_data, 'name');
        if ($name) {
            $model = $model->columnEqualSearch('name', $name);
        }
        $mobile_os = isset_and_not_empty($search_data, 'mobile_os');
        if ($mobile_os) {
            $model = $model->columnEqualSearch('mobile_os', $mobile_os);
        }
        $version_sn = isset_and_not_empty($search_data, 'version_sn');
        if ($version_sn) {
            $model = $model->columnLikeSearch('version_sn', '%' . $version_sn);
        }
        $order_by = isset_and_not_empty($search_data, 'order_by');
        if ($order_by) {
            $order_by = explode(',', $order_by);
            $model = $model->orderBy($order_by[0], $order_by[1]);
        }

        return $this->success($model->get());
    }

    public function show(AppVersion $model)
    {
        return $this->success($model);
    }

    public function store(Request $request, AppVersion $model, AppVersionValidate $validate)
    {
        $request_data = $request->all();
        $rest_validate = $validate->storeValidate($request_data);
        if ($rest_validate['status'] === false) return $this->failed($rest_validate['message']);
        $new_request_data = $rest_validate['data'];

        $res = $model->storeAction($new_request_data);
        if ($res['status'] === true) return $this->message($res['message']);
        return $this->failed($res['message']);
    }


    public function update(Request $request, AppVersion $model, AppVersionValidate $validate)
    {
        $request_data = $request->all();
        $rest_validate = $validate->updateValidate($request_data, $model);
        if ($rest_validate['status'] === false) return $this->failed($rest_validate['message']);
        $new_request_data = $rest_validate['data'];

        $res = $model->updateAction($new_request_data);
        if ($res['status'] === true) return $this->message($res['message']);
        return $this->failed($res['message']);
    }
}
