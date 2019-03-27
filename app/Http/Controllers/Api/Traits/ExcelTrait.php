<?php

namespace App\Http\Controllers\Api\Traits;


use Illuminate\Support\Facades\Storage;

trait ExcelTrait
{
    protected function getFileName($basename = 'excel', $extend = 'xls')
    {
        $baseFileName = $basename . '-' . date('Y-m-d_H-i-s');
        $filename = $baseFileName . '.' . $extend;
        $url = Storage::disk('excel')->url($filename);
        return compact('filename', 'url');
    }

}
