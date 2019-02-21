<?php

namespace App\Models;

use DB;
use Illuminate\Database\Eloquent\SoftDeletes;


class TableBakRecord extends Model
{
    protected $casts = [
        'files' => 'array'
    ];

    protected $fillable = [
        'bak_tables_name', 'user_id', 'file_num', 'files', 'file_size'
    ];


    public function destroyAction()
    {
        try {
            $this->delete();
            return $this->baseSucceed([], '表记录删除成功');
        } catch (\Exception $e) {
            return $this->baseFailed('内部错误');
        }
    }
}
