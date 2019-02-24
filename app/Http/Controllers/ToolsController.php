<?php

namespace App\Http\Controllers;


class ToolsController extends WebController
{

    public function __construct()
    {
        parent::__construct();
    }

    public function photoEditor()
    {
        return view('tools.photo-editor');
    }

}
