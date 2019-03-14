<?php

namespace App\Models;

use Illuminate\Database\Eloquent\SoftDeletes;
use DB;

class AdminMessage extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'admin_id', 'user_id', 'title', 'message_type', 'content', 'is_read'
    ];


    public function adminUser()
    {
        return $this->hasOne('App\Models\User', 'id', 'admin_id')->select('id', 'real_name', 'mobile');
    }

    public function user()
    {
        return $this->hasOne('App\Models\User', 'id', 'user_id')->select('id', 'mobile', 'real_name');
    }

    public function manyMessage($admin_ids = [], $title, $content, $user_id = 0, $message_type = 'system')
    {
        if (count($admin_ids) < 1) {
            $admin_ids = User::where('is_admin', 'T')->pluck('id');
        }
        $now = date('Y-m-d H:i:s');
        $sql = "insert into admin_messages (admin_id,user_id,title,content,message_type,is_read,created_at)  values ";
        foreach ($admin_ids as $admin_id) {
            $sql .= "('" . $admin_id . "','" . $user_id . "','" . $title . "','" . $content . "','" . $message_type . "','F','" . $now . "'),";
        }
        $insert_sql = substr($sql, 0, -1);
        DB::beginTransaction();
        try {
            DB::insert($insert_sql);

            DB::commit();
            return $this->baseSucceed([], '消息发送成功');
        } catch (\Exception $e) {
            throw  $e;
            DB::rollBack();
            return $this->baseFailed('内部错误');
        }
    }

    public function destroyAction()
    {
        $this->delete();
        return $this->baseSucceed([], '消息删除成功');
    }
}
