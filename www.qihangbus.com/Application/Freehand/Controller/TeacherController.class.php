<?php
namespace Freehand\Controller;
use Think\Controller;
class TeacherController extends CommonController {
    public function index(){
        $teacher = M('teacher');
        $count = $teacher->count();
        $page = new \Think\Page($count,15);
        $show = $page->diyshow();
        $list = $teacher->order('teacher_id desc')->limit($page->firstRow.','.$page->listRows)->select();
        foreach($list as $k=>$v){
            $list[$k]['school_name'] = M('schools')->where(array('school_id'=>$v['school_id']))->getField('school_name');
            $list[$k]['grade_name'] = M('grade')->where(array('grade_id'=>$v['grade_id']))->getField('grade_name');
            $list[$k]['class_name'] = M('class')->where(array('class_id'=>$v['class_id']))->getField('class_name');
        }
        $this->assign('list',$list);
        $this->assign('page',$show);
        $this->display("member/teacher_index");
    }

    //发送消息
    public function send()
    {
        $id = I('get.id');
        $this->assign('id',$id);
        $this->assign('user_flag',2);
        $this->display("member/teacher_message");
    }

    //
    public function message_handle()
    {
        $data = array();
        $data['receiver_id'] = I('post.teacher_id','');
        $data['title'] = I('post.title','');
        $data['message'] = I('post.message','');
        $data['sent_time'] = time();
        $data['user_flag'] = I('post.user_flag','');
        $ret = M('message')->add($data);
        if($ret > 0){
            $this->success('发送成功',U('/Teacher/index'),1);
        }else{
            $this->error('发送失败',U('/Teacher/index'),1);
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
        $class = M('class')->where(array('grade_id'=>$grade[0]['grade_id']))->select();
        $this->assign('class',$class);
        $this->display("member/teacher_info");
    }

    //异步请求年级数据
    public function ajax_grade()
    {
        $sid = I('post.school_id','');
        $ret = M('grade')->where(array('school_id'=>$sid))->order('grade_id desc')->select();
        echo json_encode($ret);
    }

    //异步请求班级数据
    public function ajax_class()
    {
        $gid = I('post.grade_id','');
        $ret = M('class')->where(array('grade_id'=>$gid))->order('class_id desc')->select();
        echo json_encode($ret);
    }

    //异步请求班级数据
    public function ajax_group()
    {
        $cid = I('post.class_id','');
        $ret = M('class_groups')->where(array('classid'=>$cid))->select();
        echo json_encode($ret);
    }

    //编辑
    public function edit()
    {
        $id = I('get.id');
        $ret = M('teacher')->where(array('teacher_id'=>$id))->find();
        $this->assign('info',$ret);
        $school = M('schools')->order('school_id desc')->select();
        $this->assign('school',$school);
        $grade = M('grade')->where(array('school_id'=>$ret['school_id']))->select();
        $this->assign('grade',$grade);
        $class = M('class')->where(array('grade_id'=>$ret['grade_id']))->select();
        $this->assign('class',$class);
        $class_groups = M('class_groups')->where("classid = {$ret['class_id']}")->select();
        $this->assign('class_groups',$class_groups);
        $this->display("member/teacher_info");
    }

    //处理会员数据
    public function edit_handle()
    {
        $condition = array();
        $tid = I('post.teacher_id','');

        $condition['school_id'] = I('post.school_id','');
        $condition['grade_id'] = I('post.grade_id','');
        $condition['class_id'] = I('post.class_id','');
        $condition['groupid'] = I('post.group_id',0);
        $condition['teacher_name'] = I('post.teacher_name','');
        $condition['teacher_mobile'] = I('post.teacher_mobile','');

        if($tid > 0){
            $ret = D('teacher')->where(array('teacher_id'=>$tid))->save($condition);
            $this->success('修改成功',U('/Teacher/index'),1);
        }else{
            $access = M('teacher')->where("class_id = {$condition['class_id']}")->find();
            if(!$access){
                $condition['first_flag'] = 1;
            }
            $condition['pwd'] = md5('123456');
            $ret = D('teacher')->add($condition);
            $this->success('添加成功',U('/Teacher/index'),1);
        }
    }

    //删除会员
    public function del(){
        $id = I('get.id');
        $ret = M('teacher')->where(array('teacher_id'=>$id))->delete();
        if($ret){
            $this->success('删除成功',U('/Teacher/index'),1);
        }else{
            $this->error('删除失败！');
        }
    }

    //导入
    public function import()
    {
        $this->display("member/import_teacher");
    }

    //处理导入数据
    public function edit_import()
    {
        $filename = $_FILES['file']['tmp_name'];
        if(empty($filename))
        {
            $this->error('请选择要导入的csv文件!');
            exit;
        }

        $handle = fopen($filename,'r');
        $result = import_csv($handle);
        $len_result = count($result);
        if($len_result == 0)
        {
            $this->error('没有任何数据!');
            exit;
        }

        for($i=1;$i<$len_result;$i++)
        {
            $edit_arr = array();

            $school_name = iconv('gb2312','utf-8',$result[$i][0]);
            $grade_name = iconv('gb2312','utf-8',$result[$i][1]);
            $class_name = iconv('gb2312','utf-8',$result[$i][2]);

            $edit_arr['school_id'] = M('schools')->where(array('school_name'=>$school_name))->getField('school_id');
            $edit_arr['grade_id'] = M('grade')->where(array('grade_name'=>$grade_name,'school_id'=>$edit_arr['school_id']))->getField('grade_id');
            $edit_arr['class_id'] = M('class')->where(array('class_name'=>$class_name,'school_id'=>$edit_arr['school_id'],'grade_id'=>$edit_arr['grade_id']))->getField('class_id');

            //分组信息
            $group = iconv('gb2312','utf-8',$result[$i][5]);
            if($group){
                $edit_arr['groupid'] = M('class_groups')->where("classid = {$edit_arr['class_id']} and name = '$group'")->getField('id');
            }

            $edit_arr['teacher_name'] = iconv('gb2312','utf-8',$result[$i][3]);
            $edit_arr['teacher_mobile'] = $result[$i][4];
            $edit_arr['pwd'] = md5('123456');
            $edit_arr['first_flag'] = 1;
            M('teacher')->add($edit_arr);
        }

        $this->success('导入成功',U('/Teacher/index'),1);
    }
}