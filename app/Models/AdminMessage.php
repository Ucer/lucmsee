<?php

namespace App\Models;

use Illuminate\Database\Eloquent\SoftDeletes;

class AdminMessage extends Model
{
    use SoftDeletes;

    protected $casts = [
        'content' => 'array',
    ];

    protected $fillable = [
        'admin_id', 'user_id', 'title', 'message_type', 'content', 'is_read'
    ];

    public function user()
    {
        return $this->hasOne('App\Models\User', 'id', 'user_id')->select('id', 'mobile', 'real_name');
    }

    public function destroyAction()
    {
        $this->delete();
        return $this->baseSucceed([], '消息删除成功');
    }
}
