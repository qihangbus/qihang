<?php
namespace Freehand\Controller;
use Think\Controller;
class ClassController extends CommonController {
    public function index(){
        $class = M('class');
        $count = $class->count();
        $page = new \Think\Page($count,15);
        $show = $page->diyshow();
        $list = $class->order('class_id desc')->limit($page->firstRow.','.$page->listRows)->select();

        foreach ($list as $k=>$v){
            $list[$k]['school_name'] = M('schools')->where(array('school_id'=>$v['school_id']))->getField('school_name');
            $list[$k]['grade_name'] = M('grade')->where(array('grade_id'=>$v['grade_id']))->getField('grade_name');
        }

        $this->assign('list',$list);
        $this->assign('page',$show);
        $this->display("department/class_index");
    }

     //发送消息
    public function send()
    {
        $id = I('get.id');
        $this->assign('id',$id);
        $this->assign('user_flag',2);
        $this->display("department/class_message");
    }

    //
    public function message_handle()
    {
        $data = array();
        $class_id = I('post.class_id','');
        $class_list = M('teacher')->where(array('class_id'=>$class_id))->field('teacher_id')->select();

        $data['title'] = I('post.title','');
        $data['message'] = I('post.message','');
        $data['sent_time'] = time();
        $data['user_flag'] = I('post.user_flag','');
        
        //循环给教师发送消息
        for($i=0;$i<count($class_list);$i++)
        {
            $data['receiver_id'] = $class_list[$i]['teacher_id'];
            $ret = M('message')->add($data);
        }

        
        if($ret > 0){
            $this->success('发送成功',U('/Class/index'),1);
        }else{
            $this->error('发送失败',U('/Class/index'),1);
        }
    }

    //添加
    public function add()
    {
        $this->assign('info','');
        $school = M('schools')->order('school_id desc')->select();
        $this->assign('school',$school);
        $grade = M('grade')->where(array('school_id'=>$school[0]['school_id']))->select();
        $this->assign('grade',$grade);
        $this->display("department/class_info");
    }

    //异步请求年级数据
    public function ajax_grade()
    {
        $sid = I('post.school_id','');
        $ret = M('grade')->where(array('school_id'=>$sid))->order('grade_id desc')->select();
        echo json_encode($ret);
    }

    //编辑
    public function edit()
    {
        $id = I('get.id');
        $ret = D('class')->where(array('class_id'=>$id))->find();
        $this->assign('info',$ret);
        $school = M('schools')->order('school_id desc')->select();
        $this->assign('school',$school);
        $grade = M('grade')->where(array('school_id'=>$ret['school_id']))->select();
        $this->assign('grade',$grade);
        $this->display("department/class_info");
    }

    //处理数据
    public function edit_handle()
    {
        $condition = array();
        $cid = I('post.class_id','');
        $condition['school_id'] = I('post.school_id','');
        $condition['grade_id'] = I('post.grade_id','');
        $condition['class_name'] = I('post.class_name','');
        $condition['class_desc'] = I('post.class_desc','');
        $condition['school_num'] = I('post.school_num','');
        $condition['class_sn'] = I('post.class_sn','');


        if($cid > 0){
            $ret = M('class')->where(array('class_id'=>$cid))->save($condition);
            $this->success('修改成功',U('/Class/index'),1);
        }else{
            $ret = M('class')->add($condition);
            $this->success('添加成功',U('/Class/index'),1);
        }
    }

    //删除
    public function del(){
        $id = I('get.id');
        $ret = M('class')->where(array('class_id'=>$id))->delete();
        if($ret){
            $this->success('删除成功',U('/Class/index'),1);
        }else{
            $this->error('删除失败！');
        }
    }
}