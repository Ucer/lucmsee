<?php

namespace App\Http\Controllers\Admin;

use App\Handlers\DatabaseHandler;
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
            'all_tables_length' => format_bytes($total)
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
    public function optimize()
    {
        $num = 1;
        if(request()->isPost()){
            $table = input('param.tables/a');
            $num = count($table);
            $table = implode(',',$table);
        }
        if(!Db::query("OPTIMIZE TABLE {$table} ")){
            $this->error('操作失败请重试');
        }
        $this->success("共计{$num}张表,优化成功");
    }
    /*数据库修复*/
    public function repair()
    {
        $num = 1;
        if(request()->isPost()){
            $table = input('param.tables/a');
            $num = count($table);
            $table = implode(',',$table);
        }
        if(!Db::query("REPAIR TABLE {$table} ")){
            $this->error('操作失败请重试');
        }
        $this->success("共计{$num}张表,修复成功");
    }
    /*数据库备份列表*/
    public function bakList()
    {
        $all_file = glob('./uploads/sql_data/*.sql');
        $final = [];
        $size = 0;
        if(count($all_file) > 0){
            foreach($all_file as $v){
                if(is_file($v)){
                    $size += filesize($v);
                }
                $final[] = [
                    'name'=>basename($v),
                    'size'=>filesize($v),
                    'time'=>filemtime($v),
                    'pre'=>substr(explode('.',basename($v))[0],0,-2),
                    'number'=>str_replace('_','',substr(basename($v),-6,2))
                ];
            }
        }
        krsort($final);
        return view('data/bak_list',['lists'=>$final,'total'=>formatBytes($size),'num'=>count($final)]);
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
