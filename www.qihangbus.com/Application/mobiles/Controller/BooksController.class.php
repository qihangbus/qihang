<?php
namespace mobiles\Controller;
use Think\Controller;
class BooksController extends Controller {
    public function shis($id){ //id = student_id
    	$Student = M("students");
    	
    	$info = $Student -> join('fh_circul_log c on c.student_id = fh_students.student_id')
    					 -> join('fh_books b ON b.book_id = c.book_id')
    					 -> join('fh_circulation cc ON cc.student_id = fh_students.student_id')
    					 -> field("fh_students.student_name,
    					 		   fh_students.student_id,
    					 		   b.book_name,
    					 		   c.circul_status,
    					 		   c.return_time,
    					 		   c.borrow_time,
    					 		   FROM_UNIXTIME(cc.add_time,'%Y-%m-%d') add_time")
    					 -> where("fh_students.school_id",$id)
    					 -> select();
						
//		var_dump($info);
		
		$this->assign("data",$info);
		
		$this->display();
    }
}