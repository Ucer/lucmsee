<?php

namespace App\Http\Controllers\Admin;

use App\Models\AdminMessage;
use App\Models\Article;
use App\Models\User;
use Auth;

class StatisticsController extends AdminController
{

    public function __construct()
    {
        parent::__construct();
        $this->middleware('auth:api');

    }

    public function base()
    {
        $model_user = new User();
        $model_adminMessage = new AdminMessage();
        $model_article = new Article();
        $authId = Auth::id();
        $return = [
            'unread_message' => $model_adminMessage->columnInSearch('admin_id',[0,$authId])->columnEqualSearch('is_read','F')->count(),
            'user_count' => $model_user->count(),
            'article_count' => $model_article->count(),
            'user_echart_data' => $this->getUserChartData($model_user)
        ];
        return $this->success($return);
    }

    protected function getUserChartData($model_user)
    {
        $return = [];
        $time = time();
        $keys = [
            date("Y-m-d", strtotime("-6 day", $time)),
            date("Y-m-d", strtotime("-5 day", $time)),
            date("Y-m-d", strtotime("-4 day", $time)),
            date("Y-m-d", strtotime("-3 day", $time)),
            date("Y-m-d", strtotime("-2 day", $time)),
            date("Y-m-d", strtotime("-1 day", $time)),
            date("Y-m-d", $time),
        ];

        foreach ($keys as $v) {
            $return[] = $model_user->columnLikeSearch('created_at', $v)->count();
        }

        return ['keys' => $keys, 'values' => $return];
    }


}
