<?php
namespace mobiles\Controller;
use Think\Controller;
class StudentController extends Controller {
    public function index($id){ //id = school_id
    	$type = I('param.type',0);
        $user_flag = I('get.user_flag','');
        // 管理员的传递admin_id  院长传递id
        $admin_id = I('get.admin_id','');
        $id = I('get.id','');
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
		if($user_flag == 4 && !empty($admin_id)){
			$url = U('mobile.php/MIndex/index',array('id'=>$admin_id));;
		}else{
			$url = U('mobile.php/SIndex/Index',array('id'=>$id));
		}
		$this->assign('url',$url);
        $list = M('students')->where(array('grade_id'=>$type))->select();

        $this->assign('list',$list);
        $this->assign('type',$type);
        $this->assign('id',$id);
        $this->assign('admin_id',$admin_id);
		$this->display('Student/index');
    }

    public function readhistory(){
        $student_id=I('param.student_id',0);
        $id=I('param.id',0); 
        $admin_id=I('param.admin_id',0); 
        $circulation = M('circul_log');
        $circulations= $circulation-> 
        field("fh_circul_log.*,fh_books.book_id,book_name,book_thumb")->
        join("fh_books on fh_books.book_id=fh_circul_log.book_id")->
        where("student_id=".$student_id)->
        select();  

        $grade_list = M('grade')->where(array('school_id'=>$id))->select();
        $this->assign('grade_list',$grade_list);
        
        if(count($grade_list) > 3){
            $this->assign('f',1);
        }else{
            $this->assign('f',0);
        }
        $this->assign('id',$id);
        $this->assign('admin_id',$admin_id);
        $this->assign('circulations',$circulations); 
        $this->display("Student/readhistory");
    }

    public function getClass()
    {
    	$id = I('param.id','');
    	$gid = I('param.grade_id','');
    	$list = M('class')->where(array('grade_id'=>$gid))->select();
		$this->assign('list',$list);
		$this->assign('id',$id);				
		$this->display('Student/class');
    }

    public function getStudent()
    {
    	$id = I('param.id','');
    	$cid = I('param.class_id','');
    	$list = M('students')->where(array('class_id'=>$cid))->select();
		$this->assign('list',$list);
		$this->assign('id',$id);				
		$this->display('Student/student');
    }

    public function history()
    {
        $book_id = I('param.book_id',0);
        $student_id = I('param.student_id',0);
        $tid = I('param.tid',0);
        $this->assign('id',$tid);
        $log = M('circul_log');

        if($book_id > 0)
        {
            $list = $log->join("fh_books on fh_books.book_id=fh_circul_log.book_id")->where("fh_circul_log.book_id=$book_id and circul_status=1")->field("fh_circul_log.*,fh_books.book_name,fh_books.book_thumb")->select();
        }

        if($student_id > 0)
        {
            $list = $log->join("fh_books on fh_books.book_id=fh_circul_log.book_id")->where("fh_circul_log.student_id=$student_id and circul_status=1")->field("fh_circul_log.*,fh_books.book_name,fh_books.book_thumb")->select();
        }

        $this->assign('list',$list);
        $this->display('Student/bookhistory');
    }

    public function del()
    {
        $id = I('post.id',0);
        $result = M('circulation')->where(['student_id'=>$id])->find();
        if($result){
            echo 99;
            exit();
        }
        $result = M('compensate')->where(['student_id'=>$id,'compen_status'=>1])->find();
        if($result){
            echo 98;
            exit();
        }
        M('students_parent')->where(['student_id'=>$id])->delete();
        M('students')->where(['student_id'=>$id])->delete();
        echo 1;
    }
}