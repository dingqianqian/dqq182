<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the controller to call when that URI is requested.
|
*/

Route::get('/', function () {
    return view('welcome');
});
// 专门指向后台
Route::get('/admin',function(){
	return redirect('/admin/login/login');
});

// 后台主页  操作
Route::controller('/admin/index','Admin\IndexController');
// 后台用户管理 
Route::controller('/admin/user','Admin\UserController');
//后台文章管理
Route::controller('/admin/article','Admin\ArticleController');
//登录页面操作
Route::controller('/admin/login','Admin\LoginController');

//验证码的路由
Route::get('/code','CodeController@code');
//前台注册路由
Route::controller('/home/zhuce','Home\ZhuceController');
//前台登录路由
Route::controller('/home/login','Home\LoginController');
//前台主页操作
//Route::controller('/home/index','Home\IndexController');
