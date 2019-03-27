<?php

namespace App\Imports;

use App\Models\Tag;
use Maatwebsite\Excel\Concerns\ToModel;

class TagsImport implements ToModel
{
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
    {
        return new Tag([
            'id' => $row[0],
            'name' => $row[1],
            'created_at' => $row[2],
        ]);
    }
}
