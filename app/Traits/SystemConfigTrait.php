<?php

namespace App\Traits;

use App\Models\SystemConfig;

trait SystemConfigTrait
{
    public function getSystemConfigFunction(array $flagList)
    {
        $m_systemconfig = SystemConfig::whereIn('flag', $flagList)->enableSearch('T')->select('id', 'flag', 'title', 'value', 'enable')->get();
        foreach ($flagList as $flag) {
            $return[$flag] = '';
        }
        if ($m_systemconfig) {
            foreach ($m_systemconfig as $k => $item) {
                $return[$item->flag] = $this->parseRule($item->flag, $item->value);
            }
        }
        return $return;
    }

    protected function parseRule($flag, $value)
    {
        switch ($flag) {
            case 'user_ids':
                $new_value = explode(',', $value);
                break;
            default:
                $new_value = $value;
                break;
        }
        return $new_value;
    }
}
