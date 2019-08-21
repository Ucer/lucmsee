<?php

namespace App\Http\Controllers\Admin;

use App\Http\Resources\CommonCollection;
use App\Http\Resources\PermissionResource;
use App\Models\Permission;
use App\Validates\PermissionValidate;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class PermissionsController extends AdminController
{
    public function __construct()
    {
        parent::__construct();
        $this->middleware('auth:api');
    }

    public function list(Request $request, Permission $model)
    {
        $search_data = json_decode($request->get('search_data'), true);
        $name = isset_and_not_empty($search_data, 'name');
        if ($name) {
            $model = $model->columnLikeSearch('name', '%' . $name);
        }

        $order_by = isset_and_not_empty($search_data, 'order_by');
        if ($order_by) {
            $order_by = explode(',', $order_by);
            $model = $model->orderBy($order_by[0], $order_by[1]);
        }

        $permissions = $model->get();

        return new CommonCollection($permissions);
    }

    public function show(Permission $model)
    {
        return $this->success($model);
    }

    public function store(Request $request, Permission $model, PermissionValidate $validate)
    {
        $request_data = $request->only('name', 'description');

        $rest_validate = $validate->storeValidate($request_data);
        if ($rest_validate['status'] === false) return $this->failed($rest_validate['message']);
        $new_request_data = $rest_validate['data'];

        $res = $model->storeAction($new_request_data);
        if ($res['status'] === true) return $this->message($res['message']);
        return $this->failed($res['message']);
    }

    public function update(Request $request, Permission $model, PermissionValidate $validate)
    {
        $request_data = $request->only('name', 'description');

        $rest_validate = $validate->updateValidate($request_data, $model);
        if ($rest_validate['status'] === false) return $this->failed($rest_validate['message']);
        $new_request_data = $rest_validate['data'];

        $res = $model->updateAction($new_request_data);
        if ($res['status'] === true) return $this->message($res['message']);
        return $this->failed($res['message']);
    }

    public function destroy(Permission $model, PermissionValidate $validate)
    {
        if (!$model) return $this->failed('找不到权限', Response::HTTP_NOT_FOUND);
        $rest_destroy_validate = $validate->destroyValidate($model);
        if ($rest_destroy_validate['status'] === true) {
            $rest_destroy = $model->destroyAction();
            if ($rest_destroy['status'] === true) return $this->message($rest_destroy['message']);
            return $this->failed($rest_destroy['message']);
        } else {
            return $this->failed($rest_destroy_validate['message']);
        }
    }

    public function allPermissions(Permission $model)
    {
        $permissions = $model->get();
        $return = [];
        $permissions->each(function ($per) use (&$return) {
            $return[] = [
                'key' => strval($per->id),
                'label' => $per->name,
                'description' => $per->description
            ];
        });
        return $this->success($return);
    }

}
