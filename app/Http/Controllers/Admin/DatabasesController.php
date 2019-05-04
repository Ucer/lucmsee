<?php

namespace App\Http\Controllers\Admin;

use App\Handlers\DatabaseHandler;
use App\Http\Resources\TableBakRecordCollection;
use App\Models\TableBakRecord;
use App\Validates\DatabaseValidate;
use Illuminate\Http\Request;
use Auth;
use DB;
use Illuminate\Support\Facades\Config;

class DatabasesController extends AdminController
{
    public function __construct()
    {
        parent::__construct();
        $this->middleware('auth:api');
    }

    public function list(Request $request)
    {
        $per_page = $request->get('per_page', 10);
        $search_data = json_decode($request->get('search_data'), true);

        $table_name = isset_and_not_empty($search_data, 'table_name');
        if ($table_name) {
            $data_table_list = collect(DB::select("SHOW TABLE STATUS LIKE '" . $table_name . "%'"));
        } else {
            $data_table_list = collect(DB::select('SHOW TABLE STATUS'));
        }

        $total = 0;
        $data_table_list->each(function ($item, $key) use (&$total) {
            $data_length = (int)$item->Data_length;
            $index_length = (int)$item->Index_length;
            $item->Data_length = format_bytes($data_length);
            $item->Index_length = format_bytes($index_length);
            $plus = $data_length + $index_length;
            $item->Total_length = format_bytes($plus);
            $total += $plus;
        });

        $list = [
            'data' => $data_table_list,
            'all_tables_num' => count($data_table_list),
            'all_tables_length' => format_bytes($total),
            'bak_data_rows' => TableBakRecord::count()
        ];

        return $this->success($list);
    }


    /*数据库表备份*/
    public function bakTable(Request $request, DatabaseValidate $validate)
    {
        set_time_limit(0);//防止超时
        $tables = $request->selectes;
        if (empty($tables)) {
            $this->failed('请选择要备份的数据表');
        }
        $selects = array_filter(explode(',', $tables));
        $rest_validate = $validate->bakTableValidate($selects);
        if ($rest_validate['status'] === false) return $this->failed($rest_validate['message']);
        $res = (new DatabaseHandler())->dataTableBak($selects);
        if ($res['status'] === true) return $this->message($res['message']);
        return $this->failed($res['message']);
    }

    /*数据库表优化*/
    public function optimizeTable(Request $request)
    {
        $tables = $request->selectes;
        if (empty($tables)) {
            $this->failed('请选择要备份的数据表');
        }
        $selects = array_filter(explode(',', $tables));
        $num = count($selects);
        $selects = implode(',', $selects);
        if (!DB::query("OPTIMIZE TABLE {$selects} ")) {
            $this->failed('操作失败请重试');
        }
        return $this->message("共计{$num}张表,优化成功");
    }

    /*数据库修复*/
    public function repairTable(Request $request)
    {
        $tables = $request->selectes;
        if (empty($tables)) {
            $this->failed('请选择要备份的数据表');
        }
        $selects = array_filter(explode(',', $tables));
        $num = count($selects);
        $selects = implode(',', $selects);
        if (!DB::query("REPAIR TABLE {$selects} ")) {
            $this->failed('操作失败请重试');
        }
        return $this->message("共计{$num}张表,修复成功");
    }

    /*数据库备份列表*/
    public function tableBakRecords(Request $request, TableBakRecord $model)
    {
        $per_page = $request->get('per_page', 10);
        $search_data = json_decode($request->get('search_data'), true);

        $order_by = isset_and_not_empty($search_data, 'order_by');
        if ($order_by) {
            $order_by = explode(',', $order_by);
            $model = $model->orderBy($order_by[0], $order_by[1]);
        }
        $list = $model->paginate($per_page);
        return new TableBakRecordCollection($list);
    }

    /*下载*/
    public function tableBakSqlFileDownload($table_bak_record_id, TableBakRecord $model, DatabaseValidate $validate)
    {
        $rest_validate = $validate->tableBakSqlFileDownloadValidate();
        if ($rest_validate['status'] === false) return $this->failed($rest_validate['message']);

        $model = $model->findOrFail($table_bak_record_id);

        $zip_file = Config::get("set_file_path.data_table_bak_dir") . "/tmp_file.zip";
        $filename = basename($zip_file);
        if (file_exists($zip_file)) {
            unlink($zip_file);
        }
        $zip = new \ZipArchive();
        if ($zip->open($zip_file, \ZipArchive::CREATE)) {
            foreach ($model->files as $key => $file) {
                if (file_exists($file)) {
                    $zip->addFile($file, basename($file));
                }
            }
        }
        $zip->close();
        header("Cache-Control: public");
        header("Content-Description: File Transfer");
        header('Content-disposition: attachment; filename=' . basename($filename)); //文件名
        header("Content-Type: application/zip"); //zip格式的
        header("Content-Transfer-Encoding: binary"); //告诉浏览器，这是二进制文件
        header('Content-Length: ' . filesize($zip_file)); //告诉浏览器，文件大小
        @readfile($zip_file);

        unlink($zip_file);
    }

    /*删除备份*/
    public function destroyManyTableBakRecord(Request $request, TableBakRecord $model, DatabaseValidate $validate)
    {
        $rest_validate = $validate->destroyManyTableBakRecordValidate();
        if ($rest_validate['status'] === false) return $this->failed($rest_validate['message']);

        $ids = $request->selectes;
        if (empty($ids)) {
            return $this->failed('请选择要备份的数据表');
        }
        $ids = array_filter(explode(',', $ids));
        $res = $model->destroyAction($ids);
        if ($res['status'] === true) {
            return $this->message($res['message']);
        } else {
            return $this->failed($res['message']);
        }
    }

}
