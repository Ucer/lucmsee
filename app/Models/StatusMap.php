<?php

namespace App\Models;

use DB;
use Illuminate\Database\Eloquent\SoftDeletes;


class StatusMap extends Model
{
    use SoftDeletes;
    protected $fillable = [
        'table_name', 'table_name_cn', 'column', 'status_code', 'status_description', 'remark'
    ];

    public function handleCascadeSourceDataForStatusMap()
    {
        $table_name = $this->pluck('table_name');
        dd($table_name);
    }
}
