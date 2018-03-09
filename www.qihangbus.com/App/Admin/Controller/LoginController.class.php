<?php
namespace Admin\Controller;
use Think\Controller;

class LoginController extends Controller
{
    public function index()
	{
		if (!IS_AJAX) {
			$this->display();
		} else {
			$username = I('post.username');
			$pwd = md5(I('post.pwd'));
			$admin = M('admin')->where(array('username' => $username, 'pwd' => $pwd))->find();
			if (!$admin || $pwd !== $admin['pwd']) {
				$this->error('用户名或者密码错误，重新输入');
			}elseif($admin['status'] == 2){
				$this->error('账号已被禁用');
			}else{
				//登录后更新数据库，登录IP,登录时间
				$data = array(
					'ip' => get_client_ip(),
					'login_time' => time(),
				);
				M('admin')->where(array('id' => $admin['id']))->save($data);
				// 录入session
				session('aid', $admin['id']);
				session('username', $admin['username']);
				$this->success('恭喜您，登陆成功');
			}
		}
	}

	public function logout()
	{
		session('aid',null);
		$this->redirect('Login/index');
	}
}