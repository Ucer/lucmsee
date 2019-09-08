<?php

namespace App\Http\Controllers\Api;


use App\Handlers\DingdingRobotHandler;
use App\Http\Controllers\Api\ApiController;
use App\Jobs\TestJob;
use Illuminate\Http\Request;

class TestController extends ApiController
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

    public function testMessageSend(DingdingRobotHandler $handler)
    {
        $res = $handler->testMessageSend();
        dd($res);
    }

}
