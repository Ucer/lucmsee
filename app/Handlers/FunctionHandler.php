<?php

namespace App\Handlers;

class FunctionHandler
{
    private $menu_style = array("│", "├ ", "└ ");  //格式化的字符

    /**
     * 找家谱树
     * @param $arr
     * @param int $id
     * @return array
     */
    public function getFatherTree($arr, $id = 0)
    {
        static $Tree = array();
        foreach ($arr as $k => $v) {
            if ($v['id'] == $id) {
                $Tree[] = $v['id'];
                $this->getFatherTree($arr, $v['pid']);

            }
        }
        return $Tree;
    }

    /**
     * 找子孙树
     * @param $data
     * @param int $pid
     * @param int $lev
     * @return array
     */
    public function getChildrenTree($data, $pid = 0, $lev = 0)
    {
        static $res = [];
        foreach ($data as $k => $v) {
            if ($v['pid'] == $pid) {
                $res[] = $v['id'];
                $this->getChildrenTree($data, $v['id'], $lev + 1);
            }
        }
        return $res;
    }


    /**
     * 格式化成树形结构
     * @param $data
     * @param int $pid
     * @param int $lev
     * @return array
     */
    public function formatTree($data, $pid = 0, $lev = 0)
    {
        static $res = [];
        foreach ($data as $k => $v) {
            $v['lev'] = $lev;
            if ($v['pid'] == $pid) {
                switch ($lev) {
                    case 0:
                        break;
                    case 1:
                        $v['name'] = $this->menu_style[1] . $v['name'];
                        break;
                    default:
                        $v['name'] = $this->menu_style[2] . str_repeat('— ', $lev) . $v['name'];
                        break;
                }
                $res[] = $v;
                $this->formatTree($data, $v['id'], $lev + 1);
            }
        }
        return $res;
    }

    public function formatArticleCategoryTree($data, $pid = 0, $lev = 0)
    {
        static $res = [];
        foreach ($data as $k => $v) {
            $v['lev'] = $lev;
            if ($v['pid'] == $pid) {
                switch ($lev) {
                    case 0:
                        break;
                    case 1:
                        $v['name'] = $this->menu_style[1] . $v['name'];
                        break;
                    default:
                        $v['name'] = $this->menu_style[2] . str_repeat('— ', $lev) . $v['name'];
                        break;
                }
                $res[] = $v;
                $this->formatArticleCategoryTree($data, $v['id'], $lev + 1);
            }
        }
        return $res;
    }
}
