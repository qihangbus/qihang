<?php
namespace Freehand\Controller;
use Think\Controller;
class GradeController extends CommonController {
    public function index(){
        $grade = M('grade');
        $count = $grade->count();
        $page = new \Think\Page($count,15);
        $show = $page->diyshow();
        $list = $grade->order('grade_id desc')->limit($page->firstRow.','.$page->listRows)->select();

        foreach ($list as $k=>$v){
            $list[$k]['school_name'] = M('schools')->where(array('school_id'=>$v['school_id']))->getField('school_name');
        }

        $this->assign('list',$list);
        $this->assign('page',$show);
        $this->display("department/grade_index");
    }

    //发送消息
    public function send()
    {
        $id = I('get.id');
        $this->assign('id',$id);
        $this->assign('user_flag',2);
        $this->display("department/grade_message");
    }

    //
    public function message_handle()
    {
        $data = array();
        $grade_id = I('post.grade_id','');
        

        $grade_list = M('teacher')->where(array('grade_id'=>$grade_id))->field('teacher_id')->select();

        $data['title'] = I('post.title','');
        $data['message'] = I('post.message','');
        $data['sent_time'] = time();
        $data['user_flag'] = I('post.user_flag','');
        
        //循环给教师发送消息
        for($i=0;$i<count($grade_list);$i++)
        {
            $data['receiver_id'] = $grade_list[$i]['teacher_id'];
            $ret = M('message')->add($data);
        }

        
        if($ret > 0){
            $this->success('发送成功',U('/Grade/index'),1);
        }else{
            $this->error('发送失败',U('/Grade/index'),1);
        }
    }

    //添加
    public function add()
    {
        $this->assign('info','');
        $school = M('schools')->order('school_id desc')->select();
        $this->assign('school',$school);
        $this->display("department/grade_info");
    }

    //编辑
    public function edit()
    {
        $id = I('get.id');
        $ret = M('grade')->where(array('grade_id'=>$id))->find();
        $this->assign('info',$ret);
        $school = M('schools')->order('school_id desc')->select();
        $this->assign('school',$school);
        $this->display("department/grade_info");
    }

    //处理数据
    public function edit_handle()
    {
        $condition = array();
        $gid = I('post.grade_id','');
        $condition['grade_name'] = I('post.grade_name','');
        $condition['school_id'] = I('post.school_id','');
        $condition['grade_desc'] = I('post.grade_desc','');
        $condition['grade_sn'] = I('post.grade_sn','');
        $condition['grade_flag'] = I('post.grade_flag','1');

        if($gid > 0){
            $ret = M('grade')->where(array('grade_id'=>$gid))->save($condition);
            $this->success('修改成功',U('/Grade/index'),1);
        }else{
            $ret = M('grade')->add($condition);
            $this->success('添加成功',U('/Grade/index'),1);
        }
    }

    //删除
    public function del(){
        $id = I('get.id');
        $ret = M('grade')->where(array('grade_id'=>$id))->delete();
        if($ret){
            $this->success('删除成功',U('/Grade/index'),1);
        }else{
            $this->error('删除失败！');
        }
    }
}