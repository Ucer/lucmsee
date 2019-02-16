<?php

namespace App\Models;

use DB;
use Illuminate\Database\Eloquent\SoftDeletes;


class StatusMap extends Model
{
    use SoftDeletes;
    protected $fillable = [
        'table_name', 'column', 'status_code', 'status_description'
    ];
}
