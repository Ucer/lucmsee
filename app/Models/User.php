<?php

namespace App\Models;

use App\Http\Controllers\Api\Traits\BaseResponseTrait;
use App\Models\Traits\ExcuteTrait;
use App\Models\Traits\ScopeTrait;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Support\Facades\Config;
use Laravel\Passport\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;
use DB;

class User extends Authenticatable
{
    use Notifiable, HasApiTokens, ScopeTrait, ExcuteTrait, HasRoles, BaseResponseTrait;

    protected $fillable = [
        'nickname', 'real_name', 'password', 'avatar', 'description'
    ];

    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * passport 认证时会通过该方法查找数据，修改该方法可实现通过其它表或非 email 字段登录
     * @param $username
     * @return mixed
     */
    public function findForPassport($username)
    {
        return User::where('email', $username)->first();
    }

    protected function getAvatarAttribute($value)
    {
        return $value ?: Config::get('set_file_path.default_avatar');
    }

    public function scopeIsAdminSearch($query, $value)
    {
        return $query->where('is_admin', $value);
    }

    public function storeAction($input)
    {
        try {
            $this->fill($input);
            $this->email = $input['email'];
            $this->password = bcrypt($input['password']);
            $this->save();

            return $this->baseSucceed([], '操作成功');
        } catch (\Exception $e) {
            return $this->baseFailed('内部错误');
        }
    }

    public function updateAction($input)
    {
        if ($input['id'] === 1) {
            unset($input['email']);
        }
        try {
            $this->fill($input)->save();
            return $this->baseSucceed([], '操作成功');
        } catch (\Exception $e) {
            return $this->baseFailed('内部错误');
        }
    }

    public function destroyAction()
    {
        DB::beginTransaction();
        try {
            $this->syncRoles([]);
            $this->delete();
            DB::commit();
            return $this->baseSucceed([], '用户删除成功');
        } catch (\Exception $e) {
            DB::rollBack();
            return $this->baseFailed('内部错误');
        }

    }


}
