<?php
namespace mobiles\Controller;
use Think\Controller;
class TeacherController extends Controller {
    public function index($id)
	{ //id = school_id
		$data = M('class')->where(['school_id'=>$id])->select();
		foreach($data as $k=>$v){
			$data[$k]['teacher'] = M('teacher')
				->where(['class_id'=>$v['class_id']])
				->field('teacher_name,teacher_avatar')
				->select();
		}
		$this->assign("data", $data);
		$this->assign('id',$id);		
		$this->display();
    }
    
    public function showStudent($id){ // id=teacher_id
    	$Student = M("students");

    	$info = $Student -> field("fh_students.student_name,fh_students.teacher_name,
    							   fh_students.grade_name,
    							   fh_students.class_name,fh_students.student_id")
    					 -> where('fh_students.teacher_id='. $id)
    					 -> select();
    	$this -> assign("data", $info);
    	$this->assign('id',$id);				 
    	$this->display();
    }
    
    public function showBooks()		// id =class_id
	{
    	$cid = I('get.id',0);
		$id = I('get.sid',0);
		$data = M('schooltobook stb')
			->join('left join fh_books b on b.book_id = stb.book_id')
			->where(['stb.class_id'=>$cid])
			->select();
		$class_name = M('class')->where(['class_id'=>$cid])->getField('class_name');
		$this -> assign("data", $data);
		$this->assign('id',$id);
		$this->assign('class_name',$class_name);
    	$this->display();
    }

    public function history()
    {
        $book_id = I('param.book_id',0);
        $tid = I('param.tid',0);
        $this->assign('id',$tid);
		$data = M('circul_log c')
			->join('fh_books b on b.book_id = c.book_id')
			->where("c.book_id=$book_id and c.school_id = $tid")
			->field('c.*,b.book_name,b.book_thumb')
			->select();
        $this->assign('list',$data);
        $this->display('Teacher/bookhistory');
    }
}