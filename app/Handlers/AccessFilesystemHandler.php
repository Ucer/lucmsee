<?php

namespace App\Handlers;

use App\Http\Controllers\Api\Traits\BaseResponseTrait;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\DB;

class AccessFilesystemHandler
{
    use BaseResponseTrait;

    protected $host = '';
    protected $accessToken = '';
    protected $plat_name = 'lucmsee';

    public function __construct()
    {
        $baseConfig = Config::get('lu.access_other_host.filesystem');
        $this->host = $baseConfig['host'];
        $this->accessToken = $baseConfig['access_token'];
    }


    /**
     * base64图片上传，只能传图片
     * @param $base_string
     * @param int $max_width 图片最大宽度，用于裁剪
     * @param $original_name  源文件名称：如「 测试图片.png」
     * @param int $user_id 操作用户id
     * @return array
     */
    public function uploadImageUseBase64($category, $base_string, $max_width = 200, $original_name, $user_id = 0)
    {
        $data = [
            'plat_name' => $this->plat_name,
            'category' => $category,
            'access_token' => $this->accessToken,
            'base_string' => $base_string, //
            'original_name' => $original_name,
            'user_id' => $user_id,
            'max_width' => $max_width
        ];
        $rest = json_decode(http_post_request($this->host . '/api/accept/common/upload_image_use_base64', $data), true);
        if (isset($rest['status'])) {
            if ($rest['status'] == 'success') {
                $restData = $rest['data'];
                return $this->baseSucceed($restData, $rest['message']);
            } else {
                return $this->baseFailed('目标主机返回错误：' . $rest['message']);
            }

        } else {
            return $this->baseFailed('目标主机未返回status状态码');
        }
    }
}
