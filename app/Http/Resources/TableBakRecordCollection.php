<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\ResourceCollection;

class TableBakRecordCollection extends ResourceCollection
{
    public function toArray($request)
    {

        $collection = $this->collection;
        $collection->each(function ($info) {
            $info->first_table_name = (explode(',',$info->bak_tables_name))[0];
        });
        return [
            'data' => $collection
        ];

    }
}
