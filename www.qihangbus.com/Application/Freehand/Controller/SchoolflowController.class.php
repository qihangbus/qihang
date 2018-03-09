<?php
namespace Freehand\Controller;
use Think\Controller;
class SchoolflowController extends CommonController {
    public function index()
    {
    	$page_size = I('param.page_size',15);
        $this->assign('page_size',$page_size);

        $circulation = M('circulation');
        $count = $circulation->group('school_id')->count();
        $page = new \Think\Page($count,$page_size);
        $show = $page->diyshow();

        $list = $circulation->group('school_id')->order('circulation_id desc')->limit($page->firstRow.','.$page->listRows)->select();
        
        foreach ($list as $key => $value) {
            $info = M('schools')->where(array('school_id'=>$value['school_id']))->find();
            $list[$key]['school_name'] = $info['school_name'];
            $list[$key]['school_teacher'] = $info['school_teacher'];
            $list[$key]['teacher_mobile'] = $info['teacher_mobile'];
            $list[$key]['school_num'] = $info['school_num'];
            $list[$key]['reg_time'] = $info['reg_time'];
        }

        
        $this->assign('list',$list);
        $this->assign('page',$show);

	    $this->display("circulate/index");
    }

    public function view()
    {
    	$circul_id = I('param.id','');
    	$circulation = M('circulation');
    	$list = $circulation->where(array('circulation_id'=>$circul_id))->select();

    	foreach ($list as $key => $value) {
            $info = M('students')->where(array('student_id'=>$value['student_id']))->find();
            //$list[$key]['school_name'] = $info['school_name'];
            $list[$key]['grade_name'] = $info['grade_name'];
            $list[$key]['class_name'] = $info['class_name'];
            $list[$key]['class_id'] = $info['class_id'];
            $list[$key]['teacher_name'] = $info['teacher_name'];
            $list[$key]['count'] = M('students')->where(array('class_id'=>$value['class_id']))->count();
        }

        $this->assign('list',$list);
    	$this->display("circulate/class_index");
    }

    public function detail()
    {
    	$class_id = I('param.id','');

    	$this->display("circulate/book_detail");
    }
}