<?php

namespace App\Http\Controllers\Api;

use Captcha;

class OtherController extends ApiController
{

    public function __construct()
    {
        parent::__construct();
    }

    public function getCaptcha()
    {
        return $this->success(Captcha::create('admin_login', true));
    }
}
