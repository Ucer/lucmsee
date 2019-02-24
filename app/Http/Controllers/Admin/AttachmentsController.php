<?php

namespace App\Http\Controllers\Admin;

use App\Http\Resources\CommonCollection;
use App\Models\Attachment;
use App\Validates\AttachmentValidate;
use Illuminate\Http\Request;

class AttachmentsController extends AdminController
{
    public function __construct()
    {
        parent::__construct();
        $this->middleware('auth:api');
    }

    public function list(Request $request, Attachment $attachment)
    {
        $per_page = $request->get('per_page', 10);

        $search_data = json_decode($request->get('search_data'), true);

        $file_type = isset_and_not_empty($search_data, 'file_type');
        if ($file_type) {
            $attachment = $attachment->columnEqualSearch('file_type', $file_type);
        }
//        $mime_type = isset_and_not_empty($search_data, 'mime_type');
//        if ($mime_type) {
//            $attachment = $attachment->columnLike('mime_type', $mime_type);
//        }
        $original_name = isset_and_not_empty($search_data, 'original_name');
        if ($original_name) {
            $attachment = $attachment->columnLikeSearch('original_name', '%' . $original_name);
        }
        $order_by = isset_and_not_empty($search_data, 'order_by');
        if ($order_by) {
            $order_by = explode(',', $order_by);
            $attachment = $attachment->orderBy($order_by[0], $order_by[1]);
        }

        $attachment = $attachment->with('user')->paginate($per_page);
        return new CommonCollection($attachment);
    }


    public function show(Attachment $model)
    {
        return $this->success($model);
    }

    public function destroy(Attachment $model, AttachmentValidate $validate)
    {
        $rest_validate = $validate->destroyValidate($model);
        if ($rest_validate['status'] === false) return $this->failed($rest_validate['message']);

        if ($rest_validate['status'] === true) {
            $rest_destroy = $model->destroyAction();
            if ($rest_destroy['status'] === true) return $this->message($rest_destroy['message']);
            return $this->failed($rest_destroy['message'], 500);
        } else {
            return $this->failed($rest_validate['message']);
        }
    }
}
