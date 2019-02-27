<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\ResourceCollection;

class TableBakRecordCollection extends ResourceCollection
{
    public function toArray($request)
    {

        $collection = $this->collection;
        $collection->each(function ($info) {
            $bak_tables_name = (explode(',',$info->bak_tables_name));
            $info->first_table_name = $bak_tables_name[0];
            $info->bak_tables_name = $bak_tables_name;
            $info->file_size = format_bytes($info->file_size);
        });
        return [
            'data' => $collection,
            'status' => 'success'
        ];

    }
}
