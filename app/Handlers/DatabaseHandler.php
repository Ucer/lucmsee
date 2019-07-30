<?php

namespace App\Handlers;

use App\Http\Controllers\Api\Traits\BaseResponseTrait;
use App\Models\TableBakRecord;
use App\Traits\SystemConfigTrait;
use File;
use DB;
use Auth;
use Illuminate\Support\Facades\Config;


class DatabaseHandler
{
    use BaseResponseTrait, SystemConfigTrait;

    protected $data_table_bak_dir = '';
    protected $status = true;
    protected $message = '操作成功';
    protected $data = [];

    public function __construct()
    {
        $this->data_table_bak_dir = Config::get('set_file_path.data_table_bak_dir');
    }

    /**
     * 数据表备份
     * @param $tables 数组，传入要备份的表表名
     */
    public function dataTableBak($tables)
    {
        $systemConfig = $this->getSystemConfigFunction(['max_bak_sql_file_size']);
        if (!$systemConfig['max_bak_sql_file_size']) {
            return $this->baseFailed("管理员未设置备份文件最大大小");
        }
        if (!file_exists($this->data_table_bak_dir)) {
            return $this->baseFailed('备份目录：' . $this->data_table_bak_dir . '不存在');
//            mkdir($this->data_table_bak_dir);
        }
        $start_time = time();//开始时间

        $full_filename = $this->data_table_bak_dir . '/' . env('DB_DATABASE', '') . '=' . date('Y-m-d=His=', $start_time) . get_rand_str(3);
        $pre = "/* -----------------------------------------------------------*/\n";

        //取得表结构信息
        //1，表示表名和字段名会用``包着的,0 则不用``
        DB::select("SET SQL_QUOTE_SHOW_CREATE = 1");
        $outstr = '';
        foreach ($tables as $k => $v) {
            $outstr .= "/* 表的结构 {$v}*/ \n";
            $outstr .= "DROP TABLE IF EXISTS {$v};\n";
            $tmp = DB::select("SHOW CREATE TABLE {$v}");
            $outstr .= ($tmp[0])->{'Create Table'} . ";\n\n";
        }
        $sqlTable = $outstr;//表结构--建表语句
        $file_n = 1;
        $outstr = "";

        $backed_table = [];//备份的表
        //表中的数据
        foreach ($tables as $k => $table_name) {//循环出表名
            $backed_table[] = $table_name;
            $outstr .= "\n\n/* 转存表中的数据:{$table_name}*/ \n";//表中的数据
//            $table_info = Db::query("SHOW TABLE STATUS LIKE '{$v}'");
            $one_table_data_list = obj_to_array(DB::table($table_name)->get());//查出每一个表的所有数据
            foreach ($one_table_data_list as $kk => $one_row_for_table) {
                $tn = 0;//表中的第几条数据
                $tem_sql = '';//将每一张表的每条数据拼接起来
                if (!isset($one_row_for_table['created_at']) || !$one_row_for_table['created_at']) {
                    unset($one_row_for_table['created_at']);
                }
                if (!isset($one_row_for_table['updated_at']) || !$one_row_for_table['updated_at']) {
                    unset($one_row_for_table['updated_at']);
                }
                if (!isset($one_row_for_table['deleted_at']) || !$one_row_for_table['deleted_at']) {
                    unset($one_row_for_table['deleted_at']);
                }
                $table_columns = '';
                foreach (array_keys($one_row_for_table) as $key => $table_column) {
                    if ($key < 1) {
                        $table_columns = "`{$table_column}`";
                    } else {
                        $table_columns .= ",`{$table_column}`";;
                    }
                }
//                $table_columns = implode(',', array_keys($one_row_for_table));
                foreach (array_values($one_row_for_table) as $value) {
                    $tem_sql .= $tn == 0 ? "" : ",";
                    $tem_sql .= $table_name == '' ? "''" : "'{$value}'";
                    $tn++;
                }
                $tem_sql = "INSERT INTO `{$table_name}` (" . $table_columns . ") VALUES ({$tem_sql});\n";
                $sql_no = "\n/* Time: " . date("Y-m-d H:i:s", time()) . "*/\n" .
                    "/* -----------------------------------------------------------*/\n" .
                    "/* SQLFile Label：#{$file_n}*/\n/* -----------------------------------------------------------*/\n\n\n";
                if ($file_n == 1) {
                    $sql_no = "/* Description:备份的数据表[结构]：" . implode(",", $tables) . "*/\n" .
                        "/* Description:备份的数据表[数据]：" . implode(",", $backed_table) . '*/' . $sql_no;
                } else {//如果不是第一个文件
                    $sql_no = "/* Description:备份的数据表[数据]：" . implode(",", $backed_table) . '*/' . $sql_no;
                }
                if (strlen($pre) + strlen($sql_no) + strlen($sqlTable) + strlen($outstr) + strlen($tem_sql) > (1024 * 1024 * $systemConfig['max_bak_sql_file_size'])) {//如果超出了每个sql文件的限制
                    $file = $full_filename . "=" . $file_n . ".sql";
                    if ($file_n == 1) {
                        $outstr = $pre . $sql_no . $sqlTable . $outstr;
                    } else {
                        $outstr = $pre . $sql_no . $outstr;
                    }
                    if (!file_put_contents($file, $outstr, FILE_APPEND)) {
                        return $this->baseFailed("备份文件写入失败！");
                    }
                    $sqlTable = $outstr = "";
                    $backed_table = array();
                    $backed_table[] = $table_name;
                    $file_n++;
                }
                $outstr .= $tem_sql;
            }
        }
        if (strlen($sqlTable . $outstr) > 0) {
            $sql_no = "\n/* Time: " . date("Y-m-d H:i:s", time()) . "*/\n" .
                "/* -----------------------------------------------------------*/\n" .
                "/* SQLFile Label：#{$file_n}*/\n/* -----------------------------------------------------------*/\n\n\n";
            if ($file_n == 1) {
                $sql_no = "/* Description:备份的数据表[结构]：" . implode(",", $tables) . "*/\n" .
                    "/* Description:备份的数据表[数据]：" . implode(",", $backed_table) . '*/' . $sql_no;
            } else {//如果不是第一个文件
                $sql_no = "/* Description:备份的数据表[数据]：" . implode(",", $backed_table) . '*/' . $sql_no;
            }
            $file = $full_filename . "=" . $file_n . ".sql";
            if ($file_n == 1) {
                $outstr = $pre . $sql_no . $sqlTable . $outstr;
            } else {
                $outstr = $pre . $sql_no . $outstr;
            }
            if (!file_put_contents($file, $outstr, FILE_APPEND)) {
                return $this->baseFailed("备份文件写入失败！");
            }
            $file_n++;
        }
        $usetime = time() - $start_time;

        $insert_tableBakRecord = [
            'user_id' => Auth::id(),
            'bak_tables_name' => implode(',', $tables),
            'file_num' => $file_n - 1
        ];

        $filesize = 0;
        for ($i = 1; $i < $file_n; $i++) {
            $filename = $full_filename . '=' . $i . '.sql';
            $filesize += filesize($filename);
            $insert_tableBakRecord['files'][] = $filename;
        }
        $insert_tableBakRecord['file_size'] = $filesize;
        (new TableBakRecord())->fill($insert_tableBakRecord)->save();

        return $this->baseSucceed(['file_num' => $file_n - 1, 'use_time' => $usetime, 'base_file_name' => $full_filename], "备份操作成功，本次备份共生成了" . ($file_n - 1) . "个SQL文件。耗时：{$usetime} 秒");

    }

}
