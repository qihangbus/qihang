<?php 
namespace mobiles\Controller;
use Think\Controller;
class TClassController extends Controller {
    public function index(){ 
       $teacher_id=I('param.teacher_id',0); 
       $readbook=I('param.readbook',1);
       $student = M('students');
       $class_id=M('teacher')->where(array('teacher_id'=>$teacher_id))->getField('class_id');

        if($readbook == 1){
            $where = "class_id = $class_id and borrow_count > 0";
        }else{
            $where = "class_id = $class_id and borrow_count = 0";
        }

        //分组管理
        $groupid = M('teacher')->where("teacher_id = $teacher_id")->getField('groupid');
        if($groupid) {
            $where .= " and groupid = $groupid";
        }

        $data = $student->where($where)->select();
       $this->assign('list',$data);
       $this->assign("readbook",$readbook);
       $this->assign("teacher_id",$teacher_id);
       $this->display("TClass/index_0");

    }

    public function readhistory(){
        $student_id=I('param.student_id',0);
        $teacher_id=I('param.teacher_id',0); 
        $this->assign("teacher_id",$teacher_id);
        $readbook = I('param.readbook','');
        $this->assign('readbook',$readbook);
	    $circulation = M('circul_log');
        $circulations= $circulation
            ->field("fh_circul_log.*,fh_books.book_id,book_name,book_thumb")
            ->join("fh_books on fh_books.book_id=fh_circul_log.book_id")
            ->where("student_id=".$student_id)
            ->order('log_id desc')
            ->select();
        //echo($circulation->getLastSql());
        $this->assign('circulations',$circulations); 
        $this->display("readhistory");
    }

    public function orderhistory(){ 
        $student_id=I('param.student_id',0);
        $teacher_id=I('param.teacher_id',0); 
        $this->assign("teacher_id",$teacher_id);
        $readbook = I('param.readbook','');
        $this->assign('readbook',$readbook);
        $paylog = M('pay_log');
        $list= $paylog->where(array('order_type'=>2,'user_id'=>$student_id,'user_flag'=>3))->order("log_time desc")->select();  

        $m = M('students')->where(array('student_id'=>$student_id))->getField('paid_num');
        
        if(!empty($list)){
          $month = date("n");
          if($month < 8) {
            $list[0]['cutoff'] = strtotime(date("Y")."-7-31 12:12:12");
          }else{
            $time = time();
            $date = date('Y',$time) + 1 . '-' . date('1-31 12:12:12');
            $list[0]['cutoff'] = strtotime($date);
          }

          //$list[0]['cutoff'] = strtotime(date("Y-m-d H:i:s", strtotime("+$m months", strtotime(date("Y-m-d H:i:s",$list[0]['log_time'])))));
        }

        $this->assign('list',$list); 
        $this->display("orderhistory");
    }

    //注册 学生列表
    public function regStu()
    {
        $teacher_id = I('get.teacher_id',0);
        $type = I('get.type',1);
        $teacher = M('teacher')->where(['teacher_id'=>$teacher_id])->find();
        $school = M('schools')->where(['school_id'=>$teacher['school_id']])->find();
        $student = M('students');
        if(time() <= $school['semester_one']){//第一学期
            $count1 = $student->where(['class_id'=>$teacher['class_id'],'is_paid'=>1])->count();
            $count2 = $student->where(['class_id'=>$teacher['class_id'],'is_paid'=>0])->count();

        }else{//第二学期
            $count1 = $student->where(['class_id'=>$teacher['class_id'],'paid_time'=>['gt',$school['semester_one']]])->count();
            $count2 = $student->where(['class_id'=>$teacher['class_id'],'paid_time'=>['elt',$school['semester_one']]])->count();
        }
        if($type == 1){
            $sql = "select * from fh_students where class_id = {$teacher['class_id']} and student_id in (select student_id from fh_students_parent)";
            $sql1 = "select count(*) as count from fh_students where class_id = {$teacher['class_id']} and student_id not in (select student_id from fh_students_parent)";
            $temp =  M()->query($sql1);
            $count4 = $temp[0]['count'];
            $data = M()->query($sql);
            $count3 = count($data);
        }else{
            $sql = "select * from fh_students where class_id = {$teacher['class_id']} and student_id not in (select student_id from fh_students_parent)";
            $sql1 = "select count(*) as count from fh_students where class_id = {$teacher['class_id']} and student_id in (select student_id from fh_students_parent)";
            $temp = M()->query($sql1);
            $count3 = $temp[0]['count'];
            $data = M()->query($sql);
            $count4 = count($data);
        }
        $this->assign('list',$data);
        $this->assign("teacher_id",$teacher_id);
        $this->assign('count1',$count1);
        $this->assign('count2',$count2);
        $this->assign('count3',$count3);
        $this->assign('count4',$count4);
        $this->assign('type',$type);
        $this->display();
    }

    //订购 学生列表
    public function payStu()
    {
        $teacher_id = I('get.teacher_id',0);
        $type = I('get.type',1);
        $teacher = M('teacher')->where(['teacher_id'=>$teacher_id])->find();
        $school = M('schools')->where(['school_id'=>$teacher['school_id']])->find();
        $student = M('students');
        if($teacher['school_id'] < 19 && $teacher['school_id'] <> 2) {//旧收费模式
            if (time() <= $school['semester_one']) {//第一学期
                $semester = '第一学期';
                $count1 = $student->where(['class_id' => $teacher['class_id'], 'is_paid' => 1])->count();
                $count2 = $student->where(['class_id' => $teacher['class_id'], 'is_paid' => 0])->count();
                if ($type == 1) {//已订购
                    $where = ['class_id' => $teacher['class_id'], 'is_paid' => 1];
                } else {//未订购
                    $where = ['class_id' => $teacher['class_id'], 'is_paid' => 0];
                }

            } else {//第二学期
                $semester = '第二学期';
                $count1 = $student->where(['class_id' => $teacher['class_id'], 'paid_time' => ['gt', $school['semester_one']]])->count();
                $count2 = $student->where(['class_id' => $teacher['class_id'], 'paid_time' => ['elt', $school['semester_one']]])->count();
                if ($type == 1) {//已订购
                    $where = ['class_id' => $teacher['class_id'], 'paid_time' => ['gt', $school['semester_one']]];
                } else {//未订购
                    $where = ['class_id' => $teacher['class_id'], 'paid_time' => ['elt', $school['semester_one']]];
                }
            }
        }else{
            $now = time();
            $count1 = $student->where("class_id = {$teacher['class_id']} and paid_expires >= $now")->count();
            $count2 = $student->where("class_id = {$teacher['class_id']} and paid_expires < $now")->count();
            if ($type == 1) {//已订购
                $where = "class_id = {$teacher['class_id']} and paid_expires >= $now";
            } else {//未订购
                $where = "class_id = {$teacher['class_id']} and paid_expires < $now";
            }
        }
        $sql3 = "select count(*) as count from fh_students where class_id = {$teacher['class_id']} and student_id in (select student_id from fh_students_parent)";
        $sql4 = "select count(*) as count from fh_students where class_id = {$teacher['class_id']} and student_id not in (select student_id from fh_students_parent)";
        $temp =  M()->query($sql3);
        $count3 = $temp[0]['count'];
        $temp =  M()->query($sql4);
        $count4 = $temp[0]['count'];
        $data = $student->where($where)->select();

        $this->assign('list',$data);
        $this->assign("teacher_id",$teacher_id);
        $this->assign("type",$type);
        $this->assign('semester',$semester);
        $this->assign('count1',$count1);
        $this->assign('count2',$count2);
        $this->assign('count3',$count3);
        $this->assign('count4',$count4);

        if($type == 1){
            $this->display("TClass/index_3");
        }else{
            $this->display("TClass/index_4");
        }
    }
}