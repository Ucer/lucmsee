<?php

namespace App\Models;

use App\Handlers\FileUploadHandler;
use DB;
use Illuminate\Database\Eloquent\SoftDeletes;


class Attachment extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'user_id', 'ip', 'original_name', 'mime_type', 'file_type', 'size', 'category',
        'domain', 'storage_path', 'link_path', 'storage_name', 'remark'
    ];

    protected function setIpAttribute()
    {
        $this->attributes['ip'] = get_client_ip();
    }

    protected function getSizeAttribute($value)
    {
        return format_bytes($value*1024);
    }

    public function user()
    {
        return $this->belongsTo('App\Models\User');
    }

    public function destroyAction()
    {
        $base_dir = substr($this->link_path, strlen('storage/'), strlen($this->link_path)) . '/' . $this->storage_name;
        $rest_fileDelete = (new FileUploadHandler)->fileDelete($base_dir);

        DB::beginTransaction();
        try {
            $this->delete();
            if ($rest_fileDelete) {
                $tip = '';
            } else {
                $tip = '：附件缺失';
            }
            DB::commit();
            return $this->baseSucceed([], '附件删除成功' . $tip);
        } catch (\Exception $e) {
            DB::rollBack();
            return $this->baseFailed('内部错误');
        }
    }

}
