<?php

namespace App\Http\Controllers\Api;

use App\Handlers\FileUploadHandler;
use App\Validates\AcceptLucmseeApiAccessValidate;
use DB;
use Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Config;

class AcceptLucmseeApiAccessController extends ApiController
{
    protected $accessToken = '';

    public function __construct()
    {
        $baseConfig = Config::get('lu.access_other_host.lucmsee_api');
        $this->accessToken = $baseConfig['access_token'];
        $request = request();
        $requestHost = $request->url();
        $requestAccessToken = $request->input('access_token');
        if ($requestHost != $baseConfig['host']) return $this->failed('不允许该域名访问', 200);
        if ($this->accessToken != $requestAccessToken) return $this->failed('抱歉,您没有权限访问', 200);
        parent::__construct();
    }

    /**
     * 提供读取配置信息的方法
     * @param Request $request
     * @return mixed
     */
    public function getConfigData(Request $request)
    {
        $configItem = $request->config_item;
        if (is_array($configItem)) {
            foreach ($configItem as $item) {
                $returnData[$item] = Config::get($item);
            }
        } else {
            $returnData = Config::get($configItem);
        }
        return $this->success($returnData);
    }

    /**
     * base 64图片上传
     * @param Request $request
     * @param AcceptLucmseeApiAccessValidate $validate
     * @param FileUploadHandler $fileUploadHandler
     * @return mixed
     * 返回
     */
    public function uploadImageUseBase64(Request $request, AcceptLucmseeApiAccessValidate $validate, FileUploadHandler $fileUploadHandler)
    {
        $request_data = $request->all();
        $rest_validate = $validate->uploadImageUseBase64Validate($request_data);
        if ($rest_validate['status'] === false) return $this->failed($rest_validate['message'],200);
        $new_request_data = $rest_validate['data'];

        $rest_fileUploadHandler = $fileUploadHandler->base64ImageUpload($new_request_data['base_string'], 'api_img', $new_request_data['user_id'], $new_request_data['max_width'], $new_request_data['original_name']);

        if ($rest_fileUploadHandler['status'] === true) {
            return $this->success($rest_fileUploadHandler['data']);
        } else {
            return $this->failed($rest_fileUploadHandler['message'], 200);
        }
    }

}
