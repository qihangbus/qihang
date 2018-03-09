<?php
namespace Freehand\Controller;
use Think\Controller;
class AdministratorController extends CommonController {
    public function index(){
        $user = M('users');
        $count = $user->where(array('user_type'=>1))->count();
        $page = new \Think\Page($count,15);
        $show = $page->show();
        $list = $user->where(array('user_type'=>1))->order('uid desc')->limit($page->firstRow.','.$page->listRows)->select();
        foreach($list as $k=>$v){
            $list[$k]['role_name'] = D('admin_role')->where(array('role_id'=>$v['role_id']))->getField('role_name');
        }
        $this->assign('list',$list);
        $this->assign('page',$show);
        $this->display("user/index");
    }

    //添加
    public function add()
    {
        $this->assign('info','');
        $roles = D('admin_role')->where(array('disabled'=>1))->select();
        $this->assign('roles',$roles);
        $this->display("user/user_info");
    }

    //编辑
    public function edit()
    {
        $id = I('get.id');
        $ret = D('users')->where(array('uid'=>$id))->find();
        $this->assign('info',$ret);
        $roles = D('admin_role')->where(array('disabled'=>1))->select();
        $this->assign('roles',$roles);
        $this->display("user/user_info");
    }

    //处理会员数据
    public function edit_handle()
    {
        $condition = array();
        $uid = I('post.uid','');
        $password = I('post.password','');
        $confirm_password = I('post.cpassword','');
        $condition['username'] = I('post.username','');
        $condition['nickname'] = I('post.nickname','');
        $condition['user_mobile'] = I('post.user_mobile','');
        $condition['role_id'] = I('post.role_id','');

        if(!empty($password) && !empty($confirm_password)){
            if($password != $confirm_password){
                $this->error('两次输入的密码不一致');
            }
            $condition['password'] = MD5($password);
        }

        if($uid > 0){
            $ret = D('users')->where(array('uid'=>$uid))->save($condition);
            $this->success('修改成功',U('/Administrator/index'),1);
        }else{
            $condition['user_type'] = 1;
            $condition['reg_time'] = time();
            $ret = D('users')->add($condition);
            $this->success('添加成功',U('/Administrator/index'),1);
        }
    }

    //处理会员数据
    public function info_handle()
    {
        $condition = array();
        $uid = I('post.uid','');
        $password = I('post.password','');
        $confirm_password = I('post.cpassword','');
        $condition['nickname'] = I('post.nickname','');
        $condition['user_mobile'] = I('post.user_mobile','');

        if(!empty($password) && !empty($confirm_password)){
            if($password != $confirm_password){
                $this->error('两次输入的密码不一致');
            }
            $condition['password'] = MD5($password);
        }



        if($uid > 0){
            $ret = D('users')->where(array('uid'=>$uid))->save($condition);

            session('username',$condition['username']);
            if(empty($condition['nickname'])){
                session('nickname',$condition['username']);
            }else{
                session('nickname',$condition['nickname']);
            }

            $this->success('修改成功',U('/Administrator/index'),1);
        }else{
            $this->success('修改失败',U('/Administrator/index'),1);
        }
    }

    //修改管理员信息
    public function info()
    {
        $id = I('get.id');
        $ret = D('users')->where(array('uid'=>$id))->find();
        $this->assign('info',$ret);
        $roles = D('admin_role')->where(array('disabled'=>1))->select();
        $this->assign('roles',$roles);
        $this->display("user/info");
    }

    //删除会员
    public function del(){
        $id = I('get.id');
        if($id == 1) $this->error('删除失败,此用户不能删除！');
        $ret = D('users')->where(array('uid'=>$id))->delete();
        if($ret){
            $this->success('删除成功',U('/Administrator/index'),1);
        }else{
            $this->error('删除失败！');
        }
    }
}