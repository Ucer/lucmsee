<?php

namespace App\Http\Controllers\Api;

use App\Handlers\FileUploadHandler;
use App\Validates\AcceptCommonAccessValidate;
use DB;
use Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Config;

class AcceptLucmseeApiAccessController extends ApiController
{
    protected $accessToken = '';

    public function __construct()
    {
        parent::__construct();
        $this->middleware('accept_access');
    }
}
