<?php

namespace App\Models;

use DB;
use Illuminate\Database\Eloquent\SoftDeletes;
use Mews\Purifier\Facades\Purifier;


class UserAgreement extends Model
{
    use SoftDeletes;
    protected $fillable = [
        'agree_type', 'name', 'description', 'content'
    ];


    protected function setContentAttribute($value)
    {
        $value = Purifier::clean($value, 'purifier_article_content');
        $data = [
            'raw' => '',
            'html' => $value,
        ];
        $this->attributes['content'] = json_encode($data);
    }

    protected function getContentAttribute($value)
    {
        return json_decode($value, true);
    }


    public function user()
    {
        return $this->belongsTo('App\Models\User')->select("id", "nickname", 'real_name');
    }

    public function storeAction($input, $authUser)
    {
        try {
            $this->fill($input);
            $this->user_id = $authUser->id;
            $this->save();
            return $this->baseSucceed([], '操作成功');
        } catch (\Exception $e) {
            return $this->baseFailed('内部错误');
        }
    }

    public function updateAction($input, $authUser)
    {
        DB::beginTransaction();
        try {
            $this->fill($input);
            $this->user_id = $authUser->id;
            $this->save();
            DB::commit();
            return $this->baseSucceed([], '操作成功');
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
            return $this->baseFailed('内部错误');
        }
    }

    public function destroyAction()
    {
        DB::beginTransaction();
        try {
            $this->delete();
            DB::commit();
            return $this->baseSucceed([], '协议删除成功');
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
            return $this->baseFailed('内部错误');
        }
    }

    /**
     * 协议启用禁用：同一类型的协议，只允许出现一条启用协议
     * @return array
     */
    public function enableOrDisableAction()
    {
        DB::beginTransaction();
        try {
            if ($this->enable === 'T') {
                $this->enable = 'F';
                $msg = "禁用成功";
            } else {
                $this->where('enable', 'T')->where('agree_type', $this->agree_type)->update(['enable' => 'F']);
                $this->enable = 'T';
                $msg = "启用成功";
            }
            $this->save();
            DB::commit();
            return $this->baseSucceed([], $msg);
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
            return $this->baseFailed('内部错误');
        }
    }
}
