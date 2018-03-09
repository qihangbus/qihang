<?php
namespace Freehand\Controller;
use Think\Controller;
class AdminController extends CommonController {
    public function index(){
     $admins = M('admins');
        $count = $admins->count();
        $page = new \Think\Page($count,15);
        $show = $page->diyshow();
        $list = $admins->order('admin_id desc')->limit($page->firstRow.','.$page->listRows)->select();
        foreach($list as $k=>$v){
             $list[$k]['school_name'] = M('schools')->where(array('school_id'=>$v['school_id']))->getField('school_name');
        }
        $this->assign('list',$list);
        $this->assign('page',$show);
        $this->display("member/admin_index");
    }

    public function add()
    {
        $this->assign('info','');
        $school = M('schools')->order('school_id desc')->select();
        $this->assign('school',$school);

        $this->display("member/admin_info");
    }


    //编辑
    public function edit()
    {
        $id = I('id');
        $ret = M('admins')->where(array('admin_id'=>$id))->find();
        $this->assign('info',$ret);
        $school = M('schools')->order('school_id desc')->select();
        $this->assign('school',$school);
        $this->display("member/admin_info");
    }




    public function edit_handle()
    {

        // $condition = array();
        $aid = I('admin_id','');

        $condition['school_id'] = I('school_id','');
        $condition['admin_name'] = I('admin_name','');
        $condition['admin_mobile'] = I('admin_mobile','');

        if($aid > 0){
            $ret = D('admins')->where(array('admin_id'=>$aid))->save($condition);
            $this->success('修改成功',U('/Admin/index'),1);
        }else{
            //MD5加密
            $condition['pwd'] = md5(123456);
            $ret = D('admins')->add($condition);
            $this->success('添加成功',U('/Admin/index'),1);
         }
    }

    public function del()
    {
        $id = I('get.id');
        $ret = M('admins')->where(array('admin_id'=>$id))->delete();
        if($ret){
            $this->success('删除成功',U('/Admin/index'),1);
        }else{
            $this->error('删除失败！');
        }
    }


}