<?php

namespace App\Http\Controllers\Admin;

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
    public function bakUpTable(Request $request)
    {
        set_time_limit(0);//防止超时
        $tables = $request->selectes;
        if(empty($tables)) {
            $this->failed('请选择要备份的数据表');
        }
        pr($tables);
        $stime = time();//开始时间
        if(!file_exists('./uploads/sql_data')){
            mkdir('./uploads/sql_data');
        }
        $path = './uploads/sql_data/pc_tables_'.date('YmdHis').getRandStr(3);
        $pre = "/* -----------------------------------------------------------*/\n";

        //取得表结构信息
        //1，表示表名和字段名会用``包着的,0 则不用``
        Db::query("SET SQL_QUOTE_SHOW_CREATE = 0");
        $outstr = '';
        foreach($tables as $k=>$v){
            $outstr .="/* 表的结构 {$v}*/ \n";
            $outstr .="DROP TABLE IF EXISTS {$v};\n";
            $tmp = Db::query("SHOW CREATE TABLE {$v}");
            $outstr .= $tmp[0]['Create Table'] . ";\n\n";
        }
        $sqlTable = $outstr;//表结构--建表语句
        $file_n =1 ;
        $outstr = "";
        $backed_table = [];//备份的表
        //表中的数据
        foreach($tables as $k=>$v){//循环出表名
            $backed_table[] = $v;
            $outstr .="\n\n/* 转存表中的数据:{$v}*/ \n";//表中的数据
//            $table_info = Db::query("SHOW TABLE STATUS LIKE '{$v}'");
            $one_table = Db::query("SELECT * FROM {$v}");//查出每一个表的所有数据
            foreach($one_table as $kk=>$vv){
                $tn = 0 ;//表中的第几条数据
                $tem_sql = '';//将每一张表的每条数据拼接起来
                foreach($vv as $vvv){
                    $tem_sql .= $tn==0?"":",";
                    $tem_sql .= $v==''?"''":"'{$vvv}'";
                    $tn++;
                }
                $tem_sql = "INSERT INTO `{$v}` VALUES ({$tem_sql});\n";
                $sql_no = "\n/* Time: " . date("Y-m-d H:i:s")."*/\n" .
                    "/* -----------------------------------------------------------*/\n" .
                    "/* SQLFile Label：#{$file_n}*/\n/* -----------------------------------------------------------*/\n\n\n";
                if ($file_n == 1) {
                    $sql_no = "/* Description:备份的数据表[结构]：" . implode(",", $tables)."*/\n".
                        "/* Description:备份的数据表[数据]：" . implode(",", $backed_table).'*/' . $sql_no;
                } else {//如果不是第一个文件
                    $sql_no = "/* Description:备份的数据表[数据]：" . implode(",", $backed_table).'*/' . $sql_no;
                }
                if (strlen($pre) + strlen($sql_no) + strlen($sqlTable) + strlen($outstr) + strlen($tem_sql) > (1024*1024*config("CFG_SQL_FILESIZE"))) {//如果超出了每个sql文件的限制
                    $file = $path . "_" . $file_n . ".sql";
                    if($file_n ==1){
                        $outstr =$pre . $sql_no . $sqlTable . $outstr;
                    }else{
                        $outstr =$pre . $sql_no  . $outstr;
                    }
                    if (!file_put_contents($file, $outstr, FILE_APPEND)) {
                        $this->error("备份文件写入失败！", url('DataBase/index'));
                    }
                    $sqlTable = $outstr = "";
                    $backed_table = array();
                    $backed_table[] = $v;
                    $file_n++;
                }
                $outstr.=$tem_sql;
            }
        }
        if (strlen($sqlTable . $outstr) > 0 ) {
            $sql_no = "\n/* Time: " . date("Y-m-d H:i:s")."*/\n" .
                "/* -----------------------------------------------------------*/\n" .
                "/* SQLFile Label：#{$file_n}*/\n/* -----------------------------------------------------------*/\n\n\n";
            if ($file_n == 1) {
                $sql_no = "/* Description:备份的数据表[结构]：" . implode(",", $tables)."*/\n".
                    "/* Description:备份的数据表[数据]：" . implode(",", $backed_table).'*/' . $sql_no;
            } else {//如果不是第一个文件
                $sql_no = "/* Description:备份的数据表[数据]：" . implode(",", $backed_table).'*/' . $sql_no;
            }
            $file = $path . "_" .$file_n. ".sql";
            if($file_n==1){
                $outstr =$pre . $sql_no . $sqlTable . $outstr;
            }else{
                $outstr =$pre . $sql_no  . $outstr;
            }
            if (!file_put_contents($file, $outstr, FILE_APPEND)) {
                $this->error("备份文件写入失败！", url('DataBase/index'));
            }
            $file_n++;
        }
        $etime = time() - $stime;
        $this->success("备份操作成功，本次备份共生成了" . ($file_n-1) . "个SQL文件。耗时：{$etime} 秒");
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
