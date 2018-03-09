<?php
namespace Live\Controller;
use Think\Controller;

class IndexController extends Controller
{
    private $user;
    public function _initialize()
    {
        $this->user = session('user');
    }

    //专家讲堂
    public function index()
    {
        $id = C('live_zj');
        if($this->user['type'] == 1){
            $name = M('students_parent')->where("parent_id = {$this->user['id']}")->getField('parent_name');
        }elseif($this->user['type'] ==2 ){
            $name = M('teacher')->where("teacher_id = {$this->user['id']}")->getField('teacher_name');
        }elseif($this->user['type'] == 3){
            $name = M('schools')->where("school_id = {$this->user['id']}")->getField('school_leader');
        }elseif($this->user['type'] == 4){
            $name = M('admins')->where("admin_id = {$this->user['id']}")->getField('admin_name');
        }elseif($this->user['type'] == 5){
            $name = M('agent')->where("id = {$this->user['id']}")->getField('name');
        }else{
            redirect('/mobile.php/Oauth/Index', 0);
            $this->error('请先登录借阅平台');
        }
        $wqhg = '<div style="position:absolute;right:0px;top:50%;text-align: center;">
                    <a href="http://e.vhall.com/user/home/21047718" style="font-size: 0.4rem;color: #2b2b32;"><img width="26" src="/Public/2017/image/wqhg.png"/></a>
                </div>';
        $this->assign('title','专家讲堂');
        $this->assign('id',$id);
        $this->assign('name',$name);
        $this->assign('wqhg',$wqhg);
        $this->display();
    }

    //教师培训
    public function trainTeach()
    {
        if($this->user['type'] != 2){
            $this->error('您没有权限观看');
        }
        $id = C('live_ls');
        $name = M('teacher')->where("teacher_id = {$this->user['id']}")->getField('teacher_name');
        $this->assign('title','在线培训');
        $this->assign('id',$id);
        $this->assign('name',$name);
        $this->display('index');
    }

    //代理商培训
    public function trainAgent()
    {
        if($this->user['type'] != 5){
            $this->error('您没有权限观看');
        }
        $id = C('live_dl');
        $name = M('agent')->where("id =  {$this->user['id']}")->getField('name');
        $this->assign('title','在线培训');
        $this->assign('id',$id);
        $this->assign('name',$name);
        $this->display('index');
    }

    public function test()
    {
        dump(session('user'));
    }
}