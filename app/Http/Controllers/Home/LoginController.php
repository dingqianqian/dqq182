<?php

namespace App\Http\Controllers\Home;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use DB;
use Hash;

class LoginController extends Controller
{
   //显示登录页面
    public function getAdd(){
        return view('home.login.login');
    }
    //接收登录的数据
    public function postDologin(Request $request){

        //4.验证码
        $code = session('code');
        $code2 = $request -> input('code');
        //判断
        if($code2 != $code){
            return back() -> with('error','验证码错误');
            exit;
        }
        
        //1.处理登录 
        //获取数据
        $data = $request -> except('_token');
        
        //dd($data['password']);
        //2.查询
        $res = DB::table('users')->where('username',$data['username'])
            ->orWhere('email',$data['username'])
            ->orWhere('phone',$data['username'])
            ->first();
        // var_dump($res);die;
        //判断
        if(!$res){
            return back() -> with('error','用户名不存在');
        }else{
            //用户名存在 检测密码
            if(Hash::check($data['password'],$res['password'])){
                session(['user_admin'=>$res]);
               echo '恭喜您登录成功';
            }else{
                return back() -> with('error','用户名或密码错误');
            }
        }
    }
}
