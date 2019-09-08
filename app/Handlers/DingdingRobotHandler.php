<?php

namespace App\Handlers;

use App\Http\Controllers\Api\Traits\BaseResponseTrait;

class DingdingRobotHandler
{
    use BaseResponseTrait;

    protected $webhook = 'https://oapi.dingtalk.com/robot/send?access_token=36e71602898d6c5e3f0898c10269c3f153767cc456ca2c080d41dd0d021fed3e';

    /**
     * 测试推送
     */
    public function testMessageSend()
    {
        $data = [
            "msgtype" => "markdown",
            "markdown" => [
                "title" => "「实名认证」",
                "text" => "#### 「**实名认证**」\n\n" .
                    "> ##### 用户已提交审核申请，请管理员尽快审核 @18313852226 \n" .
                    "> ##### 手机号：18313852226 \n" .
                    "> ###### " . date('Y-m-d H:i:s', time()) . " \n"
            ],
            "at" => [
                "atMobiles" => [
                    "18313852226",
                    "18487308470"
                ],
                "isAtAll" => false
            ]
        ];
        $rest = http_post_request($this->webhook, $data);
        return $this->baseSucceed("消息发送成功");

    }


    /**
     * 有用户等待审核
     */
    public function userWaitAuditMessageSend($mobile, $real_name)
    {
        $data = [
            "msgtype" => "markdown",
            "markdown" => [
                "title" => "「实名认证」",
                "text" => "#### 「**实名认证**」\n\n" .
                    "> ##### 用户已提交审核申请，请管理员尽快审核 @18313852226 \n" .
                    "> ##### 真实姓名：" . $real_name . "\n" .
                    "> ##### 手机号：" . $mobile . "\n" .
                    "> ###### " . date('Y-m-d H:i:s', time()) . " \n"
            ],
            "at" => [
                "atMobiles" => [
                    "18313852226",
                    "18487308470"
                ],
                "isAtAll" => false
            ]
        ];
        $rest = http_post_request($this->webhook, $data);
        return $this->baseSucceed("消息发送成功");

    }

    public function sendMsgWhenOrderFailed($datas)
    {
        $data = [
            "msgtype" => "markdown",
            "markdown" => [
                "title" => "日租订单记录异常",
                "text" => "#### 「**日租**」订单里程计算失败\n\n" .
                    "> ##### @18313852226 \n" .
                    "> ##### 订单号： " . $datas['order_sn'] . " \n" .
                    "> ##### 车牌号： " . $datas['car_sn'] . " \n" .
                    "> ##### 异常原因：" . $datas['remark'] . " \n" .
                    "> ###### " . date('Y-m-d H:i:s', time()) . " \n"],
            "at" => [
                "atMobiles" => [
                    "18313852226"
                ],
                "isAtAll" => false
            ]
        ];
        http_post_request($this->webhook, $data);
    }
}
