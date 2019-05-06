<?php

namespace App\Handlers;

use App\Models\Attachment;
use Intervention\Image\Facades\Image;
use Illuminate\Support\Facades\Storage;

class FileUploadHandler
{
    protected $status = true;
    protected $message = '附件上传成功';
    protected $data = [];
    protected $base_image_up_dir = 'uploads';
    protected $base_file_up_dir = 'files';
    protected $m_attachment;

    public function __construct()
    {
        $this->m_attachment = new Attachment();
    }


    public function uploadImage($file, $min_type, $file_type, $user_id, $max_width = 0, $category = 'tmp', $extend = '')
    {
        $up_dir = $this->base_image_up_dir . '/' . $category;

        if ($file) {
            $rest_upload = $this->data = $file->store($up_dir);
            if ($extend) {
                $old_file = storage_path() . '/app/public/' . $rest_upload;
                $new_file = explode('.', $old_file)[0] . '.' . $extend;
                rename($old_file, $new_file);
                $storage_name = basename($new_file);
            } else {
                $storage_name = basename($rest_upload);
            }
        } else {
            $this->status = false;
            $this->message = '请选择要上传的图片';
            return ['status' => $this->status, 'data' => $this->data, 'message' => $this->message];
        }
        $inser_data = [
            'user_id' => $user_id,
            'ip' => '',
            'file_type' => $file_type,
            'original_name' => $file->getClientOriginalName(),
            'mime_type' => $min_type,
            'size' => round($file->getClientSize() / 1000, 2),
            'category' => $category,
            'domain' => config('app.url'),
            'link_path' => 'storage/' . $up_dir,
            'storage_name' => $storage_name,
        ];

        $inser_data['storage_path'] = storage_path() . '/app/public/' . $up_dir;
        $inser_data['url'] = $inser_data['domain'] . '/' . $inser_data['link_path'] . '/' . $inser_data['storage_name'];

        try {
            $rest_insert_attachment_table = $this->m_attachment->saveData($inser_data);
            // 如果限制了图片宽度，就进行裁剪
            if ($max_width && ($min_type != 'gif')) {

                // 此类中封装的函数，用于裁剪图片
                $this->reduceSize($inser_data['storage_path'] . '/' . $inser_data['storage_name'], $max_width);
            }
            $this->data = array_merge($inser_data, ['attachment_id' => $rest_insert_attachment_table->id]);
        } catch (\Exception $e) {
            $this->status = false;
            $this->message = $e;
        }
        return ['status' => $this->status, 'data' => $this->data, 'message' => $this->message];
    }

    public function base64ImageUpload($base_str, $category = 'avatars', $user_id = 0, $max_width = 0, $original_name = 'base64String')
    {
        $file_type = 'pic';
        $up_dir = $this->base_image_up_dir . '/' . $category;

        if (preg_match('/^(data:\s*image\/(\w+);base64,)/', $base_str, $result)) {
            $extend = $result[2];
            if (in_array($extend, array('pjpeg', 'jpeg', 'jpg', 'gif', 'bmp', 'png'))) {
                if (in_array($extend, ['jpeg', 'pjpeg'])) $extend = 'jpg';
                $new_file = $up_dir . '/' . md5(microtime(true)) . '.' . $extend;
                Storage::put($new_file, base64_decode(str_replace($result[1], '', $base_str)));
                $storage_name = basename($new_file);
                $full_path = storage_path('app') . '/public';
                $inser_data = [
                    'user_id' => $user_id,
                    'ip' => '',
                    'file_type' => $file_type,
                    'original_name' => $original_name,
                    'mime_type' => 'image/' . $extend,
                    'size' => round(filesize($full_path . '/' . $new_file) / 1000, 2),
                    'category' => $category,
                    'domain' => config('app.url'),
                    'link_path' => 'storage/' . $up_dir,
                    'storage_name' => $storage_name,
                ];
                $inser_data['storage_path'] = storage_path() . '/app/public/' . $up_dir;
                $inser_data['url'] = $inser_data['domain'] . '/' . $inser_data['link_path'] . '/' . $inser_data['storage_name'];

                try {
                    $rest_insert_attachment_table = $this->m_attachment->saveData($inser_data);
                    // 如果限制了图片宽度，就进行裁剪
                    if ($max_width) {

                        // 此类中封装的函数，用于裁剪图片
                        $this->reduceSize($inser_data['storage_path'] . '/' . $inser_data['storage_name'], $max_width);
                    }
                    $this->data = array_merge($inser_data, ['attachment_id' => $rest_insert_attachment_table->id]);
                } catch (\Exception $e) {
                    $this->status = false;
                    $this->message = $e;
                }

            } else {
                $this->message = '图片格式错误';
                $this->status = false;
            }

        } else {
            $this->status = false;
            $this->message = 'base64 编码格式不正确';
        }
        return ['status' => $this->status, 'data' => $this->data, 'message' => $this->message];

    }


    public function uploadFile($file, $min_type, $file_type, $user_id, $category = 'tmp', $extend = '')
    {
        $up_dir = $this->base_image_up_dir . '/' . $category;

        if ($file) {
            $rest_upload = $this->data = $file->store($up_dir);
            if ($extend) {
                $old_file = storage_path() . '/app/public/' . $rest_upload;
                $new_file = explode('.', $old_file)[0] . '.' . $extend;
                rename($old_file, $new_file);
                $storage_name = basename($new_file);
            } else {
                $storage_name = basename($rest_upload);
            }
        } else {
            $this->status = false;
            $this->message = '请选择要上传的文件';
            return ['status' => $this->status, 'data' => $this->data, 'message' => $this->message];
        }
        $inser_data = [
            'user_id' => $user_id,
            'ip' => '',
            'file_type' => $file_type,
            'original_name' => $file->getClientOriginalName(),
            'mime_type' => $min_type,
            'size' => round($file->getClientSize() / 1000, 2),
            'category' => $category,
            'domain' => config('app.url'),
            'link_path' => 'storage/' . $up_dir,
            'storage_name' => $storage_name,
        ];

        $inser_data['storage_path'] = storage_path() . '/app/public/' . $up_dir;
        $inser_data['url'] = $inser_data['domain'] . '/' . $inser_data['link_path'] . '/' . $inser_data['storage_name'];

        try {
            $rest_insert_attachment_table = $this->m_attachment->saveData($inser_data);
            $this->data = array_merge($inser_data, ['attachment_id' => $rest_insert_attachment_table->id]);
        } catch (\Exception $e) {
            $this->message = $e;
            $this->status = false;
        }
        return ['status' => $this->status, 'data' => $this->data, 'message' => $this->message];
    }


    public function fileDelete($file_name = [])
    {
        return Storage::delete($file_name);
    }

    /**
     * 图片裁剪
     * @param $file_path
     * @param $max_width
     */
    protected function reduceSize($file_path, $max_width)
    {
        // 先实例化，传参是文件的磁盘物理路径
        $image = Image::make($file_path);

        // 进行大小调整的操作
        $image->resize($max_width, null, function ($constraint) {

            // 设定宽度是 $max_width，高度等比例双方缩放
            $constraint->aspectRatio();

            // 防止裁图时图片尺寸变大
            $constraint->upsize();
        });

        // 对图片修改后进行保存
        $image->save();
    }
}
