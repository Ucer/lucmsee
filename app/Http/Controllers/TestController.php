<?php

namespace App\Http\Controllers;


use App\Jobs\TestJob;
use Illuminate\Http\Request;

class TestController extends WebController
{

    public function __construct()
    {
        parent::__construct();
    }

    public function testJob(Request $request)
    {
        $request_data = $request->all();
        TestJob::dispatchNow($request_data);
        TestJob::dispatch($request_data)
            ->delay(now()->addMinutes(1));
    }

}
