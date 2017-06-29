<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

class IndexController extends Controller
{
   //后台主页面
    public function getIndex(){
        //显示后台模板
        return view('admin.index.index');
    }
    

    
}
