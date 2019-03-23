<?php

namespace App\Models;

use Illuminate\Database\Eloquent\SoftDeletes;

class Log extends Model
{
    use SoftDeletes;

    protected $casts = [
        'content' => 'array',
    ];

    protected $fillable = [
        'type', 'table_name', 'ip', 'description', 'content'
    ];

    public function user()
    {
        return $this->belongsTo('App\Models\User');
    }

    public function scopeTypeSearch($query, $type)
    {
        return $query->where('type', '=', $type);
    }

    public function scopeTableNameSearch($query, $table_name)
    {
        return $query->where('table_name', '=', $table_name);
    }

    public function storeLog($input)
    {
        try {
            $this->fill($input);
            $this->user_id = $input['user_id'];
            $this->save();
            return $this->baseSucceed([], '操作成功');
        } catch (\Exception $e) {
            return $this->baseFailed('内部错误');
        }
    }

    public function destroyAction()
    {
        $this->delete();
        return $this->baseSucceed([], '日志删除成功');

    }

}
