<?php
namespace Freehand\Controller;
use Think\Controller;
class LoginController extends Controller {
    public function index(){
        $this->display('./login');
    }

    //验证用户账号和密码
    public function validate()
    {
        import('ORG.Net.IpLocation');
        $users = D('users');
        $username = I('post.username','');
        $password = I('post.password','');
        $ret = $users->where(array('username'=>$username,'user_type'=>1))->find();
        if(!$ret){
            $this->error('用户名不存在,请重新输入',U('/Login/index'),2);
        }

        $info = $users->where(array('username'=>$username,'password'=>md5($password),'user_type'=>1))->find();

        if(!$info){
            $this->error('用户密码错误,请重新输入',U('/Login/index'),2);
        }else{
            session('uid',$info['uid']);
            session('roleid',$info['role_id']);
            session('username',$info['username']);
            if(empty($info['nickname'])){
                session('nickname',$info['username']);
            }else{
                session('nickname',$info['nickname']);
            }

            if(empty($info['user_avatar'])){
                session('avatar', __ROOT__.'/Public/images/photos/user-avatar.png');
            }else {
                session('avatar', $info['user_avatar']);
            }

            $users->where(array('uid'=>$info['uid']))->save(array('login_ip'=>get_client_ip(),'login_time'=>time()));
            $this->error('登陆成功,页面跳转中...',U('/index'),2);
        }

    }

    public function logout()
    {
        session_unset();
        session_destroy();
        $this->error('退出成功,页面跳转中...',U('/Login/index'),2);
    }
}