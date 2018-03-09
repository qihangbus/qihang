<?php
namespace mobiles\Controller;
use Think\Controller;
class MLibraryController extends Controller {
    public function index($school_id){ //id = school_id
    	
        $school_id = I('param.school_id','');
        $id = I('param.id','');
        $t = I('param.t',1);
        $arr = array();
        $time = array();
        $tmp = M('circulation')->where(array('school_id'=>$school_id))->select();
        foreach ($tmp as $key => $value) {
            array_push($arr,$value['book_id']);
            $time[$value['book_id']] = $value['add_time'];
        }

    	$Info = M("schooltobook");
    	$list = $Info->join("fh_schools ON fh_schools.school_id = fh_schooltobook.school_id")
    				  ->join("fh_class ON fh_class.class_id = fh_schooltobook.class_id")
    				  ->join("fh_books ON fh_books.book_id = fh_schooltobook.book_id")
    				  ->field("fh_schools.school_name,fh_class.class_name,
    				  			fh_books.book_name,
    				  			fh_books.book_id,
                                fh_books.book_thumb,
    				  			fh_books.book_status,
    				  			FROM_UNIXTIME(fh_books.add_date,'%Y-%m-%d') add_time")
    				  ->where('fh_schooltobook.school_id='. $school_id)
    				  ->select();

        $list_1 = array();           
        foreach ($list as $key => $value) {
            if(in_array($value['book_id'], $arr) && $t == 1){
                $value['add_time'] = $time[$value['book_id']];
                $list_1[] = $value;
            }elseif(in_array($value['book_id'], $arr) && $t == 2){
                unset($list[$key]);
            }

        }              
        $this->assign("list_1",$list_1);
    	$this->assign("list",$list);
        $this->assign('id',$id);
        $this->assign('t',$t);
        if($t == 1){
            $this->display('MIndex/index_0');
        }elseif($t == 2){
            $this->display('MIndex/index_1');
        }
    	
    }
    
    public function sHis($id){
    	$Info = M("circul_log");
    	
    	$info = $Info -> join("fh_students ON fh_students.student_id = fh_circul_log.student_id")
					  -> join("fh_books ON fh_books.book_id = fh_circul_log.book_id")
					  -> join("fh_class ON fh_class.class_id = fh_circul_log.class_id")
					  -> join("fh_circulation ON fh_circulation.book_id = fh_circul_log.book_id")
					  -> field("fh_books.book_name,
					  			fh_class.class_name,
					  			FROM_UNIXTIME(fh_circulation.add_time,'%Y-%m-%d') add_time,
					  			fh_students.student_name,
					  			fh_circul_log.return_time,
					  			fh_circul_log.borrow_time,
					  			fh_circulation.circul_status")
					  -> where("fh_circul_log.book_id=".$id)
					  -> select();
					  
		$this->assign("data",$info);
//		var_dump($info);
					  
		$this->display();
    }
}