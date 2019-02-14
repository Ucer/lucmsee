<?php

namespace App\Traits;

use Config;
use App\Models\StatusMap;

trait TableStatusTrait
{
//
//    public function formatTablesAllStatus($table_name, $data = [])
//    {
//
//        $source_data = Config::get('table_status');
//        if (empty($table_name) || !isset($source_data[$table_name])) return $data;
//        foreach ($source_data[$table_name] as $key => $val) {
//            if (isset($data[$key])) {
//                $data[$key . '_text'] = $val[$data[$key]];
//            }
//        }
//        return $data;
//    }

    public function getBaseStatus($table_name, $column = '')
    {
        $source_data = $this->getSourceData($table_name);
        if ($column) {
            return $source_data[$column];
        }
        return $source_data;
    }

    protected  function getSourceData($table_name)
    {
        $source_data = StatusMap::where('table_name',$table_name)->select('id','column','status_code','status_description')->get();
        $return = [];
        if($source_data) {
            foreach ($source_data as $v) {
                $return[$v->column][$v->status_code] = $v->status_description;
            }
        }
        return $return ;
    }
}
