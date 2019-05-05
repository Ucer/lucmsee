<?php

namespace App\Http\Controllers\Api;

use App\Traits\SystemConfigTrait;
use App\Traits\TableStatusTrait;
use Illuminate\Http\Request;
use DB;
use Auth;
use Illuminate\Support\Facades\Config;

class CommonController extends ApiController
{
    use TableStatusTrait, SystemConfigTrait;

    public function __construct()
    {
        parent::__construct();
        $this->middleware('auth:api');
    }

    /**
     * 通用启用禁用操作
     * @param Request $request
     * @return mixed
     */
    public function switchEnable(Request $request)
    {
        $table = $request->table;
        $column = 'enable';
        $authUser = Auth::user();
        switch ($request->table) {
            case 'users':
                if ($authUser->id == $request->id) return $this->failed('操作对象不能是你自己');
                break;
            case 'attachments':
                break;
            case 'advertisements':
                break;
            case 'system_configs':
                if (!$authUser->hasRole('Founder')) return $this->failed('抱歉，您没有操作权限');
                break;
            case 'articles_column_enable':
                $table = 'articles';
                break;
            case 'articles_column_top':
                $table = 'articles';
                $column = 'top';
                break;
            case 'articles_column_recommend':
                $table = 'articles';
                $column = 'recommend';
                break;
            case 'article_categories_column_enable':
                $table = 'article_categories';
                $column = 'enable';
                break;
            default:
                return $this->failed('不允许的操作');
                break;

        }
        $rest = DB::table($table)
            ->where('id', $request->id)
            ->update([$column => $request->value]);
        if ($rest) return $this->message('操作成功');
        return $this->failed('出错了');
    }

    /**
     * 更新某一张表的某个字段值
     * @param Request $request
     * @return mixed
     */
    public function editTalbleColumn(Request $request)
    {
        switch ($request->table) {
            case 'users':
                if (Auth::id() == $request->id) return $this->failed('操作对象不能是你自己');
                if (!in_array($request->column, ['status'])) {
                    return $this->failed('不允许修改此字段操作');
                }
                break;
            case 'article_categories':
                if (!in_array($request->column, ['weight'])) {
                    return $this->failed('不允许修改此字段操作');
                }
                break;
            case 'carousels':
                if (!in_array($request->column, ['weight'])) {
                    return $this->failed('不允许修改此字段操作');
                }
                break;
            default:
                return $this->failed('不允许的操作');
                break;

        }
        $rest = DB::table($request->table)
            ->where('id', $request->id)
            ->update([$request->column => $request->value]);
        if ($rest) return $this->message('字段值修改成功');
        return $this->failed('出错了');
    }

    /**
     * 读取数据字典
     * @param $table_name
     * @param string $column_name
     * @return mixed
     */
    public function getTableStatus($table_name, $column_name = '')
    {
        return $this->success($this->getBaseStatus($table_name, $column_name));
    }


    /**
     * 获取 system_config 表中的配置值
     * @param string $search_data
     * @return mixed
     */
    public function getSystemConfig(string $search_data)
    {
        return $this->success($this->getSystemConfigFunction(json_decode($search_data, true)));
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
}
