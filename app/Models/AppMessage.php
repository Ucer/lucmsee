<?php

namespace App\Models;

use Illuminate\Database\Eloquent\SoftDeletes;
use DB;

class AppMessage extends Model
{
    use SoftDeletes;


    protected $fillable = [
        'admin_id', 'user_id', 'title', 'message_type', 'content', 'url', 'is_read', 'is_alert_at_home'
    ];


    public function adminUser()
    {
        return $this->hasOne('App\Models\User', 'id', 'admin_id')->select('id', 'real_name', 'mobile');
    }

    public function user()
    {
        return $this->hasOne('App\Models\User', 'id', 'user_id')->select('id', 'mobile', 'real_name');
    }

    public function oneMessage($user_id, $title, $content, $admin_id = 0, $url = '', $is_alert_at_home = 'F', $message_type = 'system')
    {
        $insert_data = [
            'user_id' => $user_id,
            'admin_id' => $admin_id,
            'title' => $title,
            'message_type' => $message_type,
            'content' => $content,
            'url' => $url,
            'is_read' => 'F',
            'is_alert_at_home' => $is_alert_at_home,
        ];
        $this->saveData($insert_data);
        return $this->baseSucceed();
    }

    public function manyMessage($user_ids = [], $title, $content, $admin_id = 0, $url = '', $is_alert_at_home = 'F', $message_type = 'system')
    {
        if (count($user_ids) < 1) {
            $user_ids = User::pluck('id');
        }
        $now = date('Y-m-d H:i:s');
        $sql = "insert into app_messages (user_id,admin_id,title,content,url,is_alert_at_home,message_type,is_read,created_at)  values ";
        foreach ($user_ids as $user_id) {
            $sql .= "('" . $user_id . "','" . $admin_id . "','" . $title . "','" . $content . "','" . $url . "','" . $is_alert_at_home . "','" . $message_type . "','F','" . $now . "'),";
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
        return $this->baseSucceed([], '删除成功');
    }
}
