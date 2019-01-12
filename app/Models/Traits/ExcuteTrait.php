<?php

namespace App\Models\Traits;

trait ExcuteTrait
{
    public function getById($id)
    {
        return $this->findOrFail($id);
    }

    public function saveData($input)
    {
        $this->fill($input)->save();
        return $this;
    }

    public function getFirstRecordByWhere($where)
    {
        return $this->where($where)->first();
    }
}
