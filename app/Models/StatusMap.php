<?php

namespace App\Models;

use DB;

class StatusMap extends Model
{
    protected $fillable = [
        'table_name', 'column', 'status_code', 'status_description'
    ];
}
