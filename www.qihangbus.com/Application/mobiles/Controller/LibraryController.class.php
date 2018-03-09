<?php
namespace mobiles\Controller;
use Think\Controller;
class LibraryController extends Controller {
    public function index(){ //id = school_id
        $this->display();
    }

    public function detail($id)
    {
        $type = I('param.type',0);
        $grade_list = M('grade')->where(array('school_id'=>$id))->select();
        $this->assign('grade_list',$grade_list);

        if(count($grade_list) > 3){
            $this->assign('f',1);
        }else{
            $this->assign('f',0);
        }

        if($type < 1){
            $type = $grade_list[0]['grade_id'];
        }

        $arr = array();
        $ret = M('class')->where(array('grade_id'=>$type))->field('class_id')->select();

        foreach ($ret as $key => $value) {
            array_push($arr, $value['class_id']);
        }

        $list = M('schooltobook')->join("fh_books on fh_books.book_id=fh_schooltobook.book_id")->where(array('class_id'=>array('in',$arr)))->select();

        foreach ($list as $key => $value) {
            $info = M('students')->where(array('class_id'=>$value['class_id']))->field("grade_name,class_name")->find();
            $list[$key]['class_name'] = $info['class_name'];

        }
        $this->assign('type',$type);
        $this->assign('list',$list);
        $this->assign('id',$id);
        $this->display('Library/detail');
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