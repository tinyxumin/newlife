<?php

namespace app\admin\controller;

use think\Controller;
use think\Request;
use think\facade\Cookie;
use think\facade\Session;

class Login extends Controller
{

    /**
     * 登陆页
     */
    public function index()
    {
        return view('login/login');
    }

    /**
     * 注册页
     */
    public function register()
    {
        return view('login/registration');
    }

    /**
     * 锁屏页面
     */
    public function lock()
    {
        $userId = cookie('userId');
        if(empty($userId)){
            echo "<script>alert('登陆已过期,请重新登陆!');</script>";
            return view('login/login');
        }
        $res = model('User')->userLogin(['userId' => $userId]);
        return view('login/lock',['userName' => $res['userName'],'image' => $res['image'],'phone'=>$res['phone']]);
    }


    /**
     * 保存新建的资源
     *
     * @param  \think\Request  $request
     * @return \think\Response
     */
    public function save(Request $request)
    {
        $info = $request->post();
        if(empty($info['userName']) || empty($info['phone'])){
            echo "<script>alert('请输入个人信息');</script>";
            return view('login/registration');
        }
        if(empty($info['agree'])){
            echo "<script>alert('请同意徐家政策!');</script>";
            return view('login/registration');
        }
        if ($info['pas'] != $info['repas']) {
            echo "<script>alert('密码不一致!');</script>";
            return view('login/registration');
        }
        $date = date('Y-m-d H:i:s');
        $this->user->data([
            'userName' => $info['userName'],
            'phone'    => $info['phone'],
            'password' => md5($info['repas']),
            'addTime'  => $date
            
        ]);
        $res = $this->user->save();
        if($res){
            return $this->success('添加成功','admin/login/index');
        }
    }

    /**
     * 登陆成功
     */
    public function loginSuccess(Request $request)
    {
        // cookie('name', null);     助手函数  删除cookie
        // session('name', null);     助手函数  删除session
        $info = $request->post();
        if (empty($info)){
            return view('login/login');die;
        }
        $where = [
            'phone'=>$info['phone'],
            'password' => md5($info['password'])
        ];
        $res = model('user')->userLogin($where);
        if($res){
            $token = md5(time()).$res['userId'];
            Cookie::set('token',$token,24*3600);
            Cookie::set('userId',$res['userId']);
            Cookie::set('userName',$res['userName']);
            Session::set('token',$token);
            model('user')->save(['token'=>$token],['userId'=>$res['userId']]);
            return view('account/account',['userName'=>$res['userName'],'data'=>$res]);
        }else{
            return $this->error('用户名和密码错误');
        }
    }

}
