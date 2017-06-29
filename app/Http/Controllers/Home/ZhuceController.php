<?php

namespace App\Http\Controllers\Home;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Hash;
use DB;
use Mail;
use App\Http\Controllers\HttpController;
class ZhuceController extends Controller
{
    //加载注册页面
   public function getAdd(){
    return view('home.zhuce.add');
   }
   //处理注册
   public function postInsert(Request $request){
    // dd($request -> all());
    //1.检测数据是否必填
     // 自动验证
        $this->validate($request, [
            'email' => 'required|email',
            'password' => 'required',
            'repassword' => 'required|same:password',
        ],[
            'email.required' => '邮箱必填',
            'email.email' => '邮箱格式不正确',
            'password.required' => '密码必填',
            'repassword.required' => '确认密码必填',
            'repassword.same' => '确认密码不一致',
        ]);
    //2.处理数据
    //ctime 创建时间
    //token 用户加密
    //password 加密
    $data = $request -> except('_token','repassword');
    $data['ctime'] = time();
    $data['password'] = Hash::make($data['password']);
    $data['token'] = str_random(50);

    //3.发送邮件
    //给谁发 注册邮箱号
    $id = DB::table('users')->insertGetId($data);
    if($id){
        self::mailto($data['email'],$id,$data['token']);
    }
    //dd($data);
    echo '信息已发送到您的邮箱,请您查看邮箱进行激活';
   }
   
   //邮箱激活
   public function getJihuo(Request $request){
    //获取注册的token
    $arr = $request -> all();
    //获取数据库里的token
    $token = DB::table('users')->where('id',$arr['id'])->select('token')->first();
    //判断注册者的token和数据库里的是否一致
    if($arr['token'] == $token['token']){
        //修改数据库里的激活状态
        $res = DB::table('users')->where('id',$arr['id'])->update(['status'=>1,'token'=>str_random(50)]);
        //如果激活成功 判断
        if($res){
            //echo '激活成功';
            return redirect('/home/login/add');
        }else{
            echo '激活失败';
        }
    }else{
        return redirect('/home/zhuce/add')->with('error','验证失败,请您重新注册');
    }
   }

   //处理添加  手机注册
   public function postInsert2(Request $request){
     //1.检测数据是否必填
     // 自动验证
        $this->validate($request, [
           'phone'=>'regex:/^1[34578][0-9]{9}$/', //手机号的验证
            'password' => 'required',
            'phone' => 'required'
           
        ],[
            'phone.required' => '手机号必填',
             
            'password.required' => '密码必填', 

        ]);
    // dump(session('phone_code'));
    //判断验证码

    //系统验证码
    $code = session('phone_code'); 
     
    //用户输入的验证码
    $code2 = $request -> input('phone_code');  
    //判断
     if($code2 != $code){
            return back() -> with('error','验证码错误');
            exit;
        }
         $data = $request -> except('_token','phone_code');
         $data['password'] = Hash::make($data['password']);
         $data['ctime'] = time();
         $data['token'] = str_random(50);
         // dd($data);
         $res = DB::table('users')->insert($data);
         if($res){
          return redirect('/home/login/add');
         }else{
          return redirect('/home/zhuce/add') -> with('error','注册失败');
         }
     //dd($request -> all());
     
    }

   public function getPhone(Request $request){
        $phone = $request -> input('phone');
        $res = self::phoneto($phone);
        echo $res;

   }
   public static function mailto($email,$id,$token){
    Mail::send('home.mail.index', ['id' => $id,'token'=>$token,'email'=>$email], function ($m) use ($email) {
            $m->to($email)->subject('这是一封注册邮件');
   });

}
    public static function phoneto($phone){
        //$phone = '15729389665';
        $phone_code = rand(1000,9999);
        session(['phone_code'=>$phone_code]);
        $str = 'http://106.ihuyi.com/webservice/sms.php?method=Submit&account=C65973580&password=40eafba7e4366a2a6ad55b0c2e2a0723&format=json&mobile='.$phone.'&content=您的验证码是：'.$phone_code.'。请不要把验证码泄露给其他人。';
        $res = HttpController::get($str);
        return $res;  
    }
}
