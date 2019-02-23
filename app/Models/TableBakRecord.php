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

    public function user()
    {
        return $this->belongsTo('App\Models\User');
    }

    public function destroyAction($ids)
    {
        try {
            foreach ($ids as $id) {
                $model = $this->findOrFail($id);
                $model->delete();
                foreach ($model->files as $file) {
                    unlink($file);
                }
            }
            return $this->baseSucceed([], '备份数据删除成功');
        } catch (\Exception $e) {
            return $this->baseFailed('内部错误');
        }
    }
}
