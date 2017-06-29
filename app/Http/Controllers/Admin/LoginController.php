<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use DB;
use Hash;

class LoginController extends Controller
{
    //显示登录页面
    public function getLogin(){
        return view('admin.login.login');
    }
    //验证登录
    public function postDologin(Request $request){
        //验证码
        $code = session('code');
        $code2 = $request -> input('code');
        //判断
        if($code2 != $code){
            return back() -> with('error','验证码错误');
            exit;
        }
        //1.处理登录
        $data = $request -> except('_token'); //获取除了token的信息
        //2.查询该登陆者的信息
        $res = DB::table('users')->where('username',$data['username'])->first();
        //判断
        if(!$res){
            return back() -> with('error','用户名不存在');
        }else{
            //用户名存在,检测密码
            if(Hash::check($data['password'],$res['password'])){
                session(['user_admin'=>$res]);
               //2.跳转到后台主页面
            return redirect('admin/index/index');
                 
            }else{
               return back() -> with('error','用户名或密码错误');
            }
        }       
    }
}
