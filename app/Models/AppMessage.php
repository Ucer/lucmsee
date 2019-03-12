<?php

namespace App\Models;

use Illuminate\Database\Eloquent\SoftDeletes;

class AppMessage extends Model
{
    use SoftDeletes;

    protected $casts = [
        'content' => 'array',
    ];

    protected $fillable = [
        'admin_id', 'user_id', 'title', 'message_type', 'content','url', 'is_read','is_alert_at_home'
    ];

    public function user()
    {
        return $this->hasOne('App\Models\User', 'id', 'user_id')->select('id', 'mobile', 'real_name');
    }

    public function destroyAdminMessage()
    {
        $this->delete();
        return $this->baseSucceed([], '删除成功');
    }
}
