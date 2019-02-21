<?php

namespace App\Http\Controllers\Admin;

use App\Handlers\DatabaseHandler;
use App\Http\Resources\CommonCollection;
use App\Http\Resources\TableBakRecordCollection;
use App\Models\TableBakRecord;
use App\Validates\DatabaseValidate;
use Illuminate\Http\Request;
use Auth;
use DB;

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
            $data_table_list = collect(DB::select("SHOW TABLE STATUS LIKE '".$table_name."%'"));
        } else {
            $data_table_list = collect(DB::select('SHOW TABLE STATUS'));
        }

        $total = 0;
        $data_table_list->each(function($item,$key) use(&$total) {
            $data_length  = (int) $item->Data_length;
            $index_length  = (int) $item->Index_length;
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
    public function bakTable(Request $request,DatabaseValidate $validate)
    {
        set_time_limit(0);//防止超时
        $tables = $request->selectes;
        if(empty($tables)) {
            $this->failed('请选择要备份的数据表');
        }
        $selects = array_filter(explode(',',$tables));
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
        if(empty($tables)) {
            $this->failed('请选择要备份的数据表');
        }
        $selects = array_filter(explode(',',$tables));
        $num  = count($selects);
        $selects = implode(',',$selects);
        if(!DB::query("OPTIMIZE TABLE {$selects} ")){
            $this->failed('操作失败请重试');
        }
        return $this->message("共计{$num}张表,优化成功");
    }
    /*数据库修复*/
    public function repairTable(Request $request)
    {
        $tables = $request->selectes;
        if(empty($tables)) {
            $this->failed('请选择要备份的数据表');
        }
        $selects = array_filter(explode(',',$tables));
        $num  = count($selects);
        $selects = implode(',',$selects);
        if(!DB::query("REPAIR TABLE {$selects} ")){
            $this->failed('操作失败请重试');
        }
        return $this->message("共计{$num}张表,修复成功");
    }
    /*数据库备份列表*/
    public function tableBakRecords(Request $request,TableBakRecord $model)
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
    public function downFile()
    {
        $file = './uploads/sql_data/'.input('param.file');
        if(!file_exists($file)){
            $this->error("该文件不存在，可能是已经被删除");
        }
        $filename = basename($file);
        header("Content-type: application/octet-stream");
        header('Content-Disposition: attachment; filename="' . $filename . '"');
        header("Content-Length: " . filesize($file));
        readfile($file);
    }
    /*删除备份*/
    public function delSqlFile()
    {
        $sql_file = glob('./uploads/sql_data/'.input('param.ids').'_*.sql');
//        dd($sql_file);
        foreach($sql_file as $k=>$v){
            unlink($v);
        }
        $this->success("删除成功,共删除".count($sql_file)."个文件");
    }

}
