<?php
namespace Freehand\Controller;
use Think\Controller;
class ClassGroupController extends CommonController {
    public function index(){
        $groups = M('class_groups g');
        $count = $groups->count();
        $page = new \Think\Page($count,15);
        $show = $page->diyshow();
        $list = $groups
            ->join('left join fh_class c on c.class_id = g.classid')
            ->join('left join fh_grade gr on gr.grade_id = c.grade_id')
            ->join('left join fh_schools s on s.school_id = c.school_id')
            ->limit($page->firstRow.','.$page->listRows)
            ->field('g.id,g.name,c.class_name,gr.grade_name,s.school_name')
            ->select();
        $this->assign('list',$list);
        $this->assign('page',$show);
        $this->display();
    }


    //添加
    public function add()
    {
        if(IS_POST){
            $data = array();
            $data['classid']  = I('post.class_id');
            $data['name'] = I('post.name');
            $result = M('class_groups')->add($data);
            if($result){
                $this->success('添加成功！',U('index'));
            }else{
                $this->error('添加失败！');
            }
        }else{
            $school = M('schools')->order('school_id desc')->select();
            $grade = M('grade')->where(array('school_id'=>$school[0]['school_id']))->select();
            $this->assign('school',$school);
            $this->assign('grade',$grade);
            $this->display();
        }
    }

    //异步请求年级数据
    public function ajax_grade()
    {
        $sid = I('post.school_id','');
        $ret = M('grade')->where(array('school_id'=>$sid))->order('grade_id desc')->select();
        echo json_encode($ret);
    }

    public function ajax_class()
    {
        $gid = I('post.grade_id','');
        $data = M('class')->where("grade_id = $gid")->select();
        $this->ajaxReturn($data);
    }

    //编辑
    public function edit()
    {
        if(IS_POST){
            $id = I('post.id');
            $data['classid'] = I('post.class_id');
            $data['name'] = I('post.name');
            $result = M('class_groups')->where("id = $id")->save($data);
            if($result){
                $this->success('修改成功！',U('index'));
            }else{
                $this->error('修改失败！');
            }
        }else{
            $id = I('get.id');
            $data = M('class_groups')->find($id);
            $class = M('class')->find($data['classid']);
            $grades = M('grade')->where("school_id = {$class['school_id']}")->select();
            $classes = M('class')->where("grade_id = {$class['grade_id']}")->select();
            $schools = M('schools')->select();
            $this->assign('id',$id);
            $this->assign('data',$data);
            $this->assign('class',$class);
            $this->assign('schools',$schools);
            $this->assign('grades',$grades);
            $this->assign('classes',$classes);
            $this->display();
        }

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
        $ret = M('class_groups')->where(array('id'=>$id))->delete();
        if($ret){
            $this->success('删除成功',U('ClassGroup/index'),1);
        }else{
            $this->error('删除失败！');
        }
    }
}