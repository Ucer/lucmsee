<?php

namespace App\Http\Controllers\Api;

use App\Exports\LogsExport;
use App\Handlers\FileUploadHandler;
use App\Http\Controllers\Api\Traits\ExcelTrait;
use App\Imports\TagsImport;
use App\Models\Log;
use App\Models\User;
use Illuminate\Http\Request;
use Auth;
use Maatwebsite\Excel\Facades\Excel;
use Symfony\Component\HttpFoundation\Response;

class ExcelsController extends ApiController
{
    use ExcelTrait;

    public function __construct()
    {
        parent::__construct();
        $this->middleware('auth:api')->only(['importExcelTag']);
    }

    public function exportExcelLog(Request $request, Log $model)
    {
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
        $rest_getFileName = $this->getFileName('系统日志');
        Excel::store(new LogsExport($model->get(), '系统日志'), $rest_getFileName['filename'], 'excel');
        return $this->success($rest_getFileName);

    }

    public function importExcelTag(Request $request)
    {
        $fileuploadHandler = new FileuploadHandler();
        $file = $request->file('file');
        $min_type = $file->getClientMimeType();
        $file_name = explode('.', $file->getClientOriginalName());
        if (count($file_name) < 2) return $this->failed('无法识别文件类型', Response::HTTP_OK);
        $real_file_type = array_pop($file_name);
        if (!in_array($real_file_type, ['xlsx', 'xls', 'csv'])) {
            return $this->failed('不支持的文件类型', Response::HTTP_OK);
        }
        $rest_upload_file = $fileuploadHandler->uploadFile($file, $min_type, 'file', Auth::id(), 'tmp', $real_file_type);

        if ($rest_upload_file['status'] === true) {
            $file_path = $rest_upload_file['data']['storage_path'] . '/' . $rest_upload_file['data']['storage_name'];
            Excel::import(new TagsImport(), $file_path);
            return $this->success('导入成功');
        } else {
            return $this->failed($rest_upload_file['message']);
        }
    }

}
