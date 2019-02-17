<?php

namespace App\Http\Controllers\Api;


use App\Handlers\FileUploadHandler;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UploadsController extends ApiController
{

    public function __construct()
    {
        parent::__construct();
        $this->middleware('auth:api');
    }

    public function commonUpload($category, Request $request, FileUploadHandler $fileuploadHandler)
    {
        pr($request->all());
        pr($request->all());
        $file = $request->file('file');

        $rest_upload_image = $fileuploadHandler->uploadImage($file, Auth::id(), $request->post('max_width'), $category);
        if ($rest_upload_image['status'] === true) {
            return $this->success($rest_upload_image['data']);
        } else {
            return $this->failed($rest_upload_image['message']);
        }

    }
}
