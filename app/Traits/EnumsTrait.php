<?php

namespace App\Traits;


trait EnumsTrait
{
    // 公用字段
    protected $common = [
        'enable' => [ // 启用禁用
            'AA_01' => ['T', '启用'],
            'AA_02' => ['F', '禁用']
        ],
        'true_or_false' => [ // 是否判断
            'AA_03' => ['T', '是'],
            'AA_04' => ['F', '否']
        ]
    ];
    protected $zc_demo = [
        'demo1' => [ // 车辆状态
            'AA_20' => ['c1', '下线'],
        ]
    ];


    protected function getTableStatusEnum($tableName, $columnName, $code)
    {
        return $this->$tableName[$columnName][$code][0];
    }
}
