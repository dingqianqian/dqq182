<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

class ArticleController extends Controller
{
    public function getAdd(){
        return view('admin.article.add');
    }

    public function postInsert(Request $request){
        dd($request -> all());
    }
}
