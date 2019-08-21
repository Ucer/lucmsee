<?php

namespace App\Http\Controllers\Api;


use App\Handlers\FileUploadHandler;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class UploadController extends ApiController
{

    public function __construct()
    {
        parent::__construct();
        $this->middleware('auth:api');

    }

    public function commonUpload($file_type, $category, Request $request, FileUploadHandler $fileuploadHandler)
    {
        $file = $request->file('file');
        $min_type = $file->getClientMimeType();
        $file_name = explode('.', $file->getClientOriginalName());
        if (count($file_name) < 2) return $this->failed('无法识别文件类型', Response::HTTP_OK);
        $real_file_type = array_pop($file_name);

        switch ($file_type) {
            case 'pic':
                if (!in_array($real_file_type, ['png', 'jpg', 'jpeg', 'gif'])) {
                    if (strstr($category, 'editor_')) { // 富文本编辑器上传出错返回 400
                        return $this->failed('只能上传图片');
                    }
                    return $this->failed('只能上传图片', Response::HTTP_OK);
                }
                $rest_upload_image = $fileuploadHandler->uploadImage($file, $min_type, $file_type, Auth::id(), $request->post('max_width'), $category);
                break;
            case 'file':
                if (!in_array($real_file_type, ['xlsx', 'doc', 'docx', 'txt', 'sql', 'apk'])) {
                    return $this->failed('不支持的文件类型', Response::HTTP_OK);
                }
                $rest_upload_image = $fileuploadHandler->uploadFile($file, $min_type, $file_type, Auth::id(), $category, $real_file_type);
                break;
            case 'video':
                if (!in_array($real_file_type, ['mp4', 'avi'])) {
                    return $this->failed('不支持的视频类型', Response::HTTP_OK);
                }
                $rest_upload_image = $fileuploadHandler->uploadFile($file, $min_type, $file_type, Auth::id(), $category, $real_file_type);
                break;
            default:
                return $this->failed('错误的文件类型', Response::HTTP_OK);
                break;
        }

        if ($rest_upload_image['status'] === true) {
            return $this->success($rest_upload_image['data']);
        } else {
            return $this->failed($rest_upload_image['message'], Response::HTTP_OK);
        }

    }
}
