<?php

namespace App\Http\Controllers\Api;

use App\Handlers\FileUploadHandler;
use App\Handlers\FileUploadFromPlatHandler;
use App\Validates\AcceptCommonAccessValidate;
use DB;
use Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Config;
use Symfony\Component\HttpFoundation\Response;

class AcceptCommonAccessController extends ApiController
{

    public function __construct()
    {
        parent::__construct();
        $this->middleware('accept_access');
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
     *供 luapi base 64图片上传
     * @param Request $request
     * @param AcceptCommonAccessValidate $validate
     * @param FileUploadHandler $fileUploadHandler
     * @return mixed
     * 返回
     */
    public function uploadImageUseBase64(Request $request, AcceptCommonAccessValidate $validate, FileUploadHandler $fileUploadHandler)
    {
        $request_data = $request->all();
        $rest_validate = $validate->uploadImageUseBase64Validate($request_data);
        if ($rest_validate['status'] === false) return $this->failed($rest_validate['message'], Response::HTTP_OK);
        $new_request_data = $rest_validate['data'];

        $rest_fileUploadHandler = $fileUploadHandler->base64ImageUpload($new_request_data['base_string'], 'api_img', $new_request_data['user_id'], $new_request_data['max_width'], $new_request_data['original_name']);

        if ($rest_fileUploadHandler['status'] === true) {
            return $this->success($rest_fileUploadHandler['data']);
        } else {
            return $this->failed($rest_fileUploadHandler['message'], Response::HTTP_OK);
        }
    }

}
