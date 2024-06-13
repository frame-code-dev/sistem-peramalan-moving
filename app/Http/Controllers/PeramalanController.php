<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class PeramalanController extends Controller
{
    public function index(){
        $param['title'] = "Peramalan";
        return view('backoffice.peramalan.index', $param);
    }
}
