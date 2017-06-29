<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use DB;
use Hash;
class UserController extends Controller
{
    //用户添加
    public function getAdd(){
        return view('admin.user.add');
    }
    //接收数据
    public function postInsert(Request $request){
        //手动验证 用户是否必填
        // if(!$request -> has($data['username'])){
        //     return back() -> withInput();
        // }
        // $data = $request -> except('_token');
        // dd($data);
        //自动验证
        $this->validate($request,[
            'username' => 'required',
            'password' => 'required',
            'repassword' => 'required|same:password',
            'age' => 'required',
            'phone' => 'required',
            'email' => 'required|email',
            ],[
                'username.required' => '用户名必填',
                'password.required' => '密码必填',
                'repassword.required' => '确认密码必填',
                'age.required' => '年龄必填',
                'phone.required' => '手机号必填',
                'email.required' => '邮箱必填',
                'email.email' => '邮箱格式不正确',
            ]);
        //接收用户提交的值
        $data = $request -> except('_token','repassword');
        $data['password'] = Hash::make($data['password']);
        //注册时间
        $data['ctime'] = time();
        //token 随机一个长度为50位的自付
        $data['token'] = str_random(50);
        //将接过存在数据库
        $res = DB::table('users') -> insert($data);
        if($res){
            return redirect('/admin/user/index')->with('success','添加成功');
        }else{
            return back()->with('error','添加失败');
        }
    }
    //用户主页面
    public function getIndex(Request $request){
        
       //把所有的数据获取到并且分配到主页面
        $count = $request -> input('count',10);
        $search = $request -> input('search','');
        $all = $request -> all();
        // $data = DB::select('select * from users');
       // dump($all);
        $data = DB::table('users') ->where('username','like','%'.$search.'%')-> paginate($count); 

       return view('admin.user.index',['data'=>$data,'request'=>$all]);
      
    }

    //用户删除
    public function getDelete($id){
        $res = DB::table('users')->where('id',$id)->delete();
        if($res){
            return redirect('/admin/user/index')->with('success','删除成功');
        }else{
            return back() -> with('error','删除失败');
        }
    }

    //修改页面
    public function getEdit($id){
        //获取要修改的那 一条 数据
        $arr = DB::table('users')->where('id',$id)->first();
        //显示修改页面
        return view('admin.user.edit',['arr'=>$arr]);
    }
    //处理修改操作
    public function postUpdate(Request $request){
        //修改数据
        $arr = $request -> only(['age','phone','email']);
        $id = $request -> input('id');
        //修改 
        $res = DB::table('users')->where('id',$id)->update($arr);
        if($res){
            return redirect('/admin/user/index')->with('success','修改成功');
        }else{
            return back() -> with('error','修改失败');
        }
    }
}
 