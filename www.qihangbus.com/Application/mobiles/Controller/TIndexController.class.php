<?php
namespace mobiles\Controller;
use Think\Controller;
class TIndexController extends Controller {
    public function index(){ 

		$teacher_id = I('get.teacher_id','');

		if(strpos($teacher_id,'h') > 0)
		{
			$arr = explode(".",$teacher_id);
			var_dump($arr);
			$teacher_id = $arr[0];
		}
	
	    $teacher = M('teacher');
        $teachers=$teacher->
         field('teacher_id,fh_teacher.message_num,fh_teacher.teacher_avatar,fh_teacher.rank_points,fh_teacher.teacher_rank,fh_teacher.school_id,fh_teacher.grade_id,fh_class.class_id,school_name,grade_name,class_name,teacher_name')->
        join('fh_class on fh_class.class_id=fh_teacher.class_id')->
        join('fh_grade on fh_grade.grade_id=fh_class.grade_id')->
        join('fh_schools on fh_schools.school_id=fh_grade.school_id')-> 
        where('teacher_id='.$teacher_id)-> 
        find();   
        
        session('teacher_id',$teacher_id);
        
        $this->assign('teacher' ,$teachers);

        $teachers['student_id'] = $teachers['teacher_id'];
        $teachers['student_points'] = $teachers['rank_points'];
        //var_dump($teachers);
        //exit;
        $this->assign('userinfo' ,$teachers); 
        $this->assign('teacher_id',$teacher_id);
        //var_dump($teachers);
        //exit;

        $condition['user_id'] = $teacher_id;
        $condition['user_flag'] = 2;
        $condition['add_time'] = array('EGT',strtotime(date('Y-m-d')." 00:00:00"));
        
        $id = M('signin')->where($condition)->getField('id');
        $this->assign('signin',$id);
        $signinnum = M('signin')->where(array('user_id'=>$teacher_id,'user_flag'=>2))->order('id desc')->getField('signin_num');
        if(empty($signinnum)) $signinnum = 1;
        $this->assign('signnum',$signinnum);
        $this->display('TIndex/index');
        
    }

    //自动分配图书
    public function distributeBook()
    {
        $teacherid = I('get.teacher_id',0);
        $classid = M('teacher')->where("teacher_id = $teacherid")->getField('class_id');

        //查询班级所有的图书,并把图书ID存放到book_arr数组中
        $books = M("schooltobook")->where(array('class_id'=>$classid))->field("book_id")->select();

        //排除正在流转的图书
        $books_cir = M('circulation')->where("class_id = $classid")->field('book_id')->select();
        foreach($books as $k=>$v){
            foreach($books_cir as $kc=>$vc){
                if($v['book_id'] == $vc['book_id']){
                    unset($books[$k]);
                }
            }
        }
        foreach($books as $k=>$v){
            $books_temp[] = $v['book_id'];
        }
        $books = $books_temp;

        set_time_limit(0);
        //查询班级所有的学生
        $students = M("students")->where(array('class_id'=>$classid))->field("school_id,class_id,student_id")->select();
        //查询班级套餐类型  1：一本 2：两本 3：两本
        $schoolid =  M('teacher')->where("teacher_id = $teacherid")->getField('school_id');
        $meal_type = M('schools')->where("school_id = $schoolid")->getField('meal_type');
        if($schoolid < 19 && $schoolid <> 2) {//旧收费模式
            for ($j = 0; $j < 2; $j++) {
                foreach ($students as $k => $v) {
                    //判断有没有支付本学期的费用
                    $data = M('schools')->where("school_id = {$v['school_id']}")->field('semester_one_start,semester_one,semester_two_start,semester_two,try_charge_time')->find();
                    if (time() > $data['try_charge_time']) {

                        $paid_num = M("students")->where(array('student_id' => $v['student_id']))->getField("paid_num");
                        if (time() > ($data['semester_two_start'] - 86400 * 7) && time() < ($data['semester_two'] + 86400)) {//已到第二学期
                            if ($paid_num <> 2) {//未支付
                                continue;

                            }
                        } elseif (time() < ($data['semester_two_start'] - 86400 * 7)) {//第一学期
                            if ($paid_num <> 1 && $paid_num <> 2) {//未支付
                                continue;
                            }
                        } else {
                            continue;
                        }
                    } else {//试用期，过滤未注册的学生
                        $reg = M('students_parent')->where(['student_id' => $v['student_id']])->find();
                        if (!$reg) {
                            continue;
                        }
                    }

                    //判断学生是否有图书在流转
                    $exist = M("circulation")->where("student_id = {$v['student_id']}")->count();
                    if ($meal_type == 1) {
                        if ($exist > 0) continue;
                    } else {
                        if ($exist > 1) continue;
                    }


                    //查询出学生已经读过的图书，从班级所有的图书中剔除出去
                    $booked = M("circul_log")->where("student_id = {$v['student_id']}")->field("book_id")->select();
                    $books_now = $books;
                    foreach ($booked as $kb => $vb) {
                        if (in_array($vb['book_id'], $books_now)) {
                            $key = array_search($vb['book_id'], $books_now);
                            array_splice($books_now, $key, 1);
                        }
                    }
                    $bookid = $books_now[0];
                    if (!$bookid) {
                        $this->error('请检查是否需要轮换图书');
                    }

                    //插入轮转表
                    //添加到预约表中
                    $data = array(
                        'book_id' => $bookid,
                        'school_id' => $v['school_id'],
                        'class_id' => $v['class_id'],
                        'student_id' => $v['student_id'],
                        'circul_status' => 2,
                        'add_time' => time()
                    );
                    $result = M("circulation")->add($data);

                    $key = array_search($bookid, $books);
                    array_splice($books, $key, 1);
                }
            }
        }else{//新收费模式
            foreach ($students as $k => $v) {
                //判断有没有支付本学期的费用
                $data = M('schools')->where("school_id = {$v['school_id']}")->field('semester_one_start,semester_one,semester_two_start,semester_two,try_charge_time')->find();
                if (time() > $data['try_charge_time']) {
                    $paid_expires = M("students")->where(['student_id' => $v['student_id']])->getField("paid_expires");
                    if(time() > $paid_expires) {//未付费
                        continue;
                    }
                } else {//试用期，过滤未注册的学生
                    $reg = M('students_parent')->where(['student_id' => $v['student_id']])->find();
                    if (!$reg) {
                        continue;
                    }
                }

                //判断学生是否有图书在流转
                $exist = M("circulation")->where("student_id = {$v['student_id']}")->count();
                if ($meal_type == 1) {
                    if ($exist > 0) continue;
                } else {
                    if ($exist > 0) continue;
                }


                //查询出学生已经读过的图书，从班级所有的图书中剔除出去
                $booked = M("circul_log")->where("student_id = {$v['student_id']}")->field("book_id")->select();
                $books_now = $books;
                foreach ($booked as $kb => $vb) {
                    if (in_array($vb['book_id'], $books_now)) {
                        $key = array_search($vb['book_id'], $books_now);
                        array_splice($books_now, $key, 1);
                    }
                }
                $bookid = $books_now[0];
                if (!$bookid) {
                    $this->error('请检查是否需要轮换图书');
                }

                //插入轮转表
                //添加到预约表中
                $data = array(
                    'book_id' => $bookid,
                    'school_id' => $v['school_id'],
                    'class_id' => $v['class_id'],
                    'student_id' => $v['student_id'],
                    'circul_status' => 2,
                    'add_time' => time()
                );
                $result = M("circulation")->add($data);

                $key = array_search($bookid, $books);
                array_splice($books, $key, 1);
            }
        }
        $this->success('图书分配完成');
    }

	public function circul()
    {
        $teacher = M("teacher");
        $id = I('param.user_id','');
		$user_flag = I('param.user_flag','');
		$this->assign('user_id' ,$id);
		$this->assign('user_flag' ,$user_flag);		
        $t = I('param.t',1);
        $condition['teacher_id'] = $id;
        $info=$teacher->where($condition)->find();
        $info['school_name'] = M('schools')->where(array('school_id'=>$info['school_id']))->getField('school_name');
        
        $list = M('grade')->where(array('grade_id'=>$info['grade_id']))->select();



        foreach ($list as $key => $value) {
            $ret = M('class')->where(array('grade_id'=>$value['grade_id']))->order("class_id desc")->select();
      
            for ($i=0; $i < count($ret); $i++) { 
                if(empty($ret[$i+1]['class_name'])){
                    $ret[$i]['next_name'] = $ret[0]['class_name'];
                }else{
                    $ret[$i]['next_name'] = $ret[$i+1]['class_name'];
                }
            }

            $list[$key]['lt'] = $ret;
        }

        $this->assign('list',$list);
        $this->assign('info' ,$info);
        $this->assign('id',$id);
        $this->assign('t',$t);  
        $this-> display('TIndex/circul');
    }
	
    //获取用户等级
    public function get_levle_info()
    {
        $user_id = I('param.user_id',0);
        $this->assign('user_id',$user_id);
        $user_flag = I('param.user_flag',0);
        $user_points = I('param.user_points',0);
        $teacher_rank = I('param.teacher_rank',0);
        $this->assign('user_points',$user_points);

        if($teacher_rank < 1){
            $teacher_rank = 1;
        }

        $this->assign('teacher_rank',$teacher_rank);
        $nextrank = $teacher_rank + 1;
        $this->assign('nextrank',$nextrank);
        //查询勋章列表
        $medal_list = M('medal')->select();

        //查询当前用户所获的勋章
        $student_medal = M('student_medal')->where(array('user_id'=>$user_id,'user_flag'=>$user_flag))->select();
        $studentmedal = array();
        if(!empty($student_medal)){
            foreach ($student_medal as $key => $value) {
                $studentmedal[$value['medal_id']] = $value['user_id'];
            }

            foreach($medal_list as $k=>$v){
                if($studentmedal[$v['medal_id']] > 0){
                    $medal_list[$k]['flag'] = 1;
                }else{
                    $medal_list[$k]['flag'] = 0;
                }
            }
        }
        $this->assign('medal_list',$medal_list);
        //查询学生下一等级和积分
        $next_points = M('student_rank')->where("rank_id > $teacher_rank")->getField('min_points');
        $diff_points = $next_points - $student_points;
        $this->assign('next_points',$next_points);
        $this->display('TIndex/level_info');
    }

    public function ajax_message()
    {
        $message_id = I('param.message_id','');
        $ret = M('message')->where(array('message_id'=>$message_id))->save(array('readed'=>1,'read_time'=>time()));
        echo $ret;
    }

    // //获取用户排名
    // public function get_class_rank()
    // {
    //     $user_id = I('param.user_id',0);
    //     $this->assign('user_id',$user_id);
    //     $student_points = I('param.student_points',0);
    //     $school_id = I('param.school_id',0);

    //     Vendor('Weixin.jssdk');
    //     $wx_info = M('config')->where(array('parent_id'=>13))->field('code,value')->select();
    //     $wx_arr = array();
    //     foreach ($wx_info as $key => $value) {
    //         $wx_arr[$value['code']] = $value['value'];
    //     }
    //     $jssdk = new \JSSDK($wx_arr['appid'], $wx_arr['appsecret']);
    //     $signPackage = $jssdk->GetSignPackage();

    //     $this->assign('signPackage',$signPackage);

    //     //查询当前学生所在班级总人数
    //     $total_num = M('teacher')->where(array('school_id'=>$school_id))->count();
    //     //查询当前班级小于当前学生积分的人数
    //     $student_num = M('teacher')->where("rank_points <= $student_points AND school_id = $school_id")->count();
    //     $student_per = round($student_num/$total_num,2)*100 ."%";
    //     $this->assign('student_per',$student_per);
    //     $userinfo = M('teacher')->where(array('teacher_id'=>$user_id))->find();
    //     $this->assign('userinfo',$userinfo);
    //     $this->display('TIndex/class_rank');
    // }


    // 订购率排名
    public function get_class_rank()    //接书时间received_time开始计算第一个月
    {
        $time = time();
        $school_id = I('school_id',0);
        $user_id = I('user_id',0);
        $cla_ids = M('class')->where(array('school_id'=>$school_id))->select();
        foreach ($cla_ids as $key => $value) {
            $total_num = M('students')->where(array('class_id'=>$value['class_id']))->count();
            $cla_ids[$key]['total_num'] = $total_num;
            // 该班订购的人数
            $data['class_id'] = $value['class_id'];
            $data['paid_expires'] = array('gt',time());
            $num = M('students')->where($data)->count();
            $cla_ids[$key]['num'] = $num;
            $percent = round($num/$total_num,2)*100;
            $cla_ids[$key]['percent'] = $percent;
            $sort[$key] = $cla_ids[$key]['percent'];
        }
        // var_dump($sort);die;
        array_multisort($sort,SORT_DESC,$cla_ids);
        // var_dump($cla_ids);die;
        $this->assign('cla_ids',$cla_ids);
        $this->assign('user_id',$user_id);
        $this->display('TIndex/order_rank');
    }




    //兑换充值码
    public function exchange()
    {
        $user_id = I('param.user_id',0);
        $this->assign('user_id',$user_id);
        $user_flag = I('param.user_flag',0);
        $userinfo = M("teacher")->
         field('teacher_id,fh_teacher.message_num,fh_teacher.teacher_avatar,fh_teacher.diamond,fh_teacher.rank_points,fh_teacher.teacher_rank,fh_teacher.school_id,fh_teacher.grade_id,fh_class.class_id,school_name,grade_name,class_name,teacher_name')->
        join('fh_class on fh_class.class_id=fh_teacher.class_id')->
        join('fh_grade on fh_grade.grade_id=fh_class.grade_id')->
        join('fh_schools on fh_schools.school_id=fh_grade.school_id')-> 
        where('teacher_id='.$user_id)-> 
        find(); ;
        $this->assign('userinfo',$userinfo);
		$this->assign('user_id' ,$id);
		$this->assign('user_flag' ,$user_flag);		
        $this->display('TIndex/exchange');
    }
	
	//获取消息列表
    public function get_message_list()
    {
        $user_id = I('param.user_id',0);
        $this->assign('user_id',$user_id);
        $message = M('message');
        //更新用户中心消息小红点
        M('teacher')->where(array('teacher_id'=>$user_id))->save(array('message_num'=>0));
        $userinfo = M('teacher')->where(array('teacher_id'=>$user_id))->find();
        session('userinfo',$userinfo);
        $list = $message->where("(sender_id = $user_id and sender_name = '{$userinfo['teacher_name']}') or (receiver_id = $user_id and user_flag = 2)")->order('message_id desc')->limit(20)->select();
        $lt = array();
        foreach ($list as $key => $value) {
            $value['sub_message'] = msubstr($value['message'],40,strlen($value['message'])-1);
            $value['message'] = msubstr($value['message'],0,40);
            $lt[date('Y-m-d',$value['sent_time'])]['lt'][$key] = $value;
            if($value['sender_id'] == $user_id){
                $lt[date('Y-m-d',$value['sent_time'])]['lt'][$key]['type'] = 1;
            }else{
                $lt[date('Y-m-d',$value['sent_time'])]['lt'][$key]['type'] = 2;
            }

            if($value['user_flag'] == 2){//teacher
                $receiver_name = M('teacher')->where(['teacher_id'=>$value['receiver_id']])->getField('teacher_name');
            }else{//student
                $receiver_name = M('students')->where(['student_id'=>$value['receiver_id']])->getField('student_name');
            }
            $lt[date('Y-m-d',$value['sent_time'])]['lt'][$key]['receiver_name'] = $receiver_name;
        }
        $this->assign('list',$lt);
        $this->assign('userinfo',$userinfo);
        $this->display('TIndex/message_list');
    }

    public function send_message()
    {
        $user_id = I('param.user_id','');
        $type = I('param.type','');
        $receive_id = I('param.receive_id','');
        $this->assign('user_id',$user_id);
        $this->assign('receive_id',$receive_id);
        $this->assign('type',$type);
        $this->display('TIndex/send_message');
    }

    public function select_user()
    {
        $user_id = I('param.user_id','');
        $class_id = I('param.class_id','');
        $type = I('param.type','');

        //分组管理
        $groupid = M('teacher')->where("teacher_id = $user_id")->getField('groupid');
        if($groupid) {
            $stu_conditon = "and groupid = $groupid";
        }else{
            $stu_conditon = '';
        }

        if($type == 1){
            $list = M('teacher')->where("class_id = $class_id $stu_conditon")->select();
            foreach ($list as $key => $value) {
                if($value['teacher_id'] == $user_id){
                    unset($list[$key]);
                    continue;
                }
                $list[$key]['user_id'] = $value['teacher_id'];
                $list[$key]['thumb'] = $value['teacher_avatar'];
                $list[$key]['user_name'] = $value['teacher_name'];
            }
        }elseif($type == 2){
            $list = M('students')->where("class_id = $class_id $stu_conditon")->select();
            foreach ($list as $key => $value) {
                $list[$key]['user_id'] = $value['student_id'];
                $list[$key]['thumb'] = $value['student_avatar'];
                $list[$key]['user_name'] = $value['student_name'];
            }
        }
        $this->assign('list',$list);
        $this->assign('type',$type);
        $this->assign('user_id',$user_id);
        $this->assign('class_id',$class_id);
        $this->display('TIndex/select_user');
    }

    public function message_handle()
    {
        $data = array();
        $data['sender_id'] = I('param.user_id','');
        $sender_name = M('teacher')->where(array('teacher_id'=>$data['sender_id']))->getField('teacher_name');
        $data['sender_name'] = $sender_name;
        $data['sent_time'] = time();
        $data['title'] = I('post.title','');
        $data['message'] = I('post.message','');
        $type = I('param.t','');
        $rec = array();
        $receiver = I('param.receive_id','');

        if(strpos($receiver,",") === false){
            $rec[] = $receiver;
        }else{
            $rec = explode(',', $receiver);
        }

        if($type == 1){
            $data['user_flag'] = 2;
            
        }elseif($type == 2){
            $data['user_flag'] = 3;
        }
        
        for ($i=0; $i < count($rec); $i++) {
            $data['receiver_id'] = $rec[$i]; 
            if($type == 1){
                M('teacher')->where(array('teacher_id'=>$rec[$i]))->save(array('message_num'=>1));
            }elseif($type == 2){
                $data['user_flag'] = 3;
                M('students')->where(array('student_id'=>$rec[$i]))->save(array('message_num'=>1));
            }
            M('message')->add($data);
        }
            
        $this->success('发送成功',U('mobile.php/TIndex/index',array('teacher_id'=>$data['sender_id'])));
        
    }

	//学生管理
	public function students()
	{
		$user_id = I('get.user_id',0);
        $user_flag = I('get.user_flag',0);
        $list = M('students s')
            ->join("left join fh_students_parent p on p.student_id=s.student_id")
            ->where(array('teacher_id'=>$user_id))
            ->field('s.*,p.parent_name,p.parent_mobile')
            ->select();
        $this->assign('user_id',$user_id);
        $this->assign('user_flag',$user_flag);
        $this->assign('list',$list);
        $this->display('TIndex/students'); 
	}
	
	//添加学生
	public function add_students()
	{
		$user_id = I('get.user_id',0);
        $user_flag = I('get.user_flag',0);
        $this->assign('user_id',$user_id);
        $this->assign('user_flag',$user_flag);
        $this->display('TIndex/students_info');
	}
	
	//编辑学生
	public function student_edit()
	{
		$user_id = I('get.user_id',0);
        $user_flag = I('get.user_flag',0);
        $this->assign('user_id',$user_id);
        $this->assign('user_flag',$user_flag);
		
		$student_id = I("get.student_id",0);
		$this->assign('student_id',$student_id);
		$info = M("students")->where("student_id=$student_id")->find();
		$ret = M("students_parent")->where(array('student_id'=>$student_id,'flag'=>1))->find();
		if($ret){
            $all = array_merge($info,$ret);
        }else{
            $all = $info;
        }

        $this->assign('info',$all);
        $this->display('TIndex/students_info'); 
	}
	
	//处理学生信息
    public function student_handle()
    {
        $parent_id = I('param.parent_id',0);
        $data['parent_sex'] = I('param.parent_sex',0);
        $data['parent_mobile'] = I('param.parent_mobile','');
        $data['parent_name'] = I('param.parent_name','');
        $student_id = I('param.student_id','');
        $student_name = I('param.student_name','');
        $teacher_id = I('param.teacher_id','');
        if($data['parent_mobile'] && $data['parent_name']){
            if($parent_id){
                $where = "parent_id <> $parent_id and parent_mobile = {$data['parent_mobile']}";
            }else{
                $where = "parent_mobile = {$data['parent_mobile']}";
            }
            //判断手机号是否重复
            $access = M('students_parent')->where($where)->find()
                || $access = M('teacher')->where(array('teacher_mobile'=>$data['parent_mobile']))->find()
                    || $access = M('schools')->where(array('leader_mobile'=>$data['parent_mobile']))->find()
                        || $access = M('schools')->where(array('teacher_mobile'=>$data['parent_mobile']))->find()
                            || $access = M('admins')->where(array('admin_mobile'=>$data['parent_mobile']))->find();

            if($access){
                $this->error('手机号已注册');
            }
        }elseif($parent_id){
            $result_p = M('students_parent')->where(['parent_id'=>$parent_id])->delete();
            $msg = '家长删除成功';
            $parent_id = 0;
        }

        $info = M("teacher")->where(array('teacher_id'=>$teacher_id))->find();

        if($student_id > 0){
            $result_s = M("students")->where(array('student_id'=>$student_id))->save(array('student_name'=>$student_name));
            if($result_s) $msg = '学生姓名修改成功';
        }else{
            $school_name = M("schools")->where(array('school_id'=>$info['school_id']))->getField("school_name");
            $grade_name = M('grade')->where(array('grade_id'=>$info['grade_id']))->getField("grade_name");
            $class_name = M('class')->where(array('class_id'=>$info['class_id']))->getField("class_name");
            $student_id = M("students")->add(array('student_name'=>$student_name,'school_id'=>$info['school_id'],'school_name'=>$school_name,'grade_id'=>$info['grade_id'],'grade_name'=>$grade_name,'class_id'=>$info['class_id'],'class_name'=>$class_name,'teacher_id'=>$info['teacher_id'],'teacher_name'=>$info['teacher_name']));
            $result_s = $student_id;
            $msg = '操作成功';
        }

        if($parent_id > 0){
            $msg = '修改成功';
            $data['student_id'] = $student_id;
            $ret = M('students_parent')->where("parent_id=$parent_id")->save($data);
        }elseif($data['parent_mobile'] && $data['parent_name']){
            $msg = '添加成功';
            $data['parent_id'] = $parent_id;
            $data['student_id'] = $student_id;
            $data['flag'] = 1;
            $data['pwd'] = md5('123456');
            $data['school_id'] = $info['school_id'];
            $data['grade_id'] = $info['grade_id'];
            $data['class_id'] = $info['class_id'];
            $ret = M('students_parent')->add($data);
        }

        if($ret || $result_s || $result_p){
            $this->success("$msg",U('mobile.php/TIndex/students',array('user_id'=>$teacher_id,'user_flag'=>2)));
        }else{
            $this->error('操作失败');
        }
    }
	
    public function avatar()
    {
        $user_id = I('get.user_id',0);
        $user_flag = I('get.user_flag',0);
        $avatar = M('teacher')->where(array('teacher_id'=>$user_id))->getField('teacher_avatar');
        $this->assign('user_id',$user_id);
        $this->assign('user_flag',$user_flag);
        $this->assign('avatar',$avatar);
        $this->display('TIndex/avatar');   
    }

    public function update_avatar()
    {
        $user_id = I('post.user_id',0);
        $user_flag = I('post.user_flag',0);
        
        $upload = new \Think\Upload();
        $upload->maxSize = 31457280;
        $upload->exts = array('jpg', 'gif', 'png', 'jpeg');
        $upload->saveName = 'time';
        $info = $upload->upload();
        
        if (!$info) {
            $this->error('请先选择图片');
//            $avatar = I('post.avatar_old','');
        }else{
            $filename = $info['avatar']['savepath'].$info['avatar']['savename'];
            $image = new \Think\Image();
            $image->open('./Uploads/'.$filename);
            $avatar = 'image_'.$info['avatar']['savename'];

            $thumb_width = 300;
            $thumb_height = 300;
            $image->thumb($thumb_width,$thumb_height)->save("./Uploads/".$info['avatar']['savepath']."$avatar");

            unlink("./Uploads/".$filename);
            $avatar = "/Uploads/".$info['avatar']['savepath'].$avatar;
        }

        $ret = M('teacher')->where(array('teacher_id'=>$user_id))->save(array('teacher_avatar'=>$avatar));
        if($ret){
            $this->success('修改头像成功',U('mobile.php/TIndex/avatar',array('user_id'=>$user_id,'user_flag'=>$user_flag)));
        }
    }

    //用户中心设置
    public function setting()
    {
        $user_id = I('param.user_id',0);
        $first_flag = M('teacher')->where(array('teacher_id'=>$user_id))->getField('first_flag');
        $this->assign('first_flag',$first_flag);
        $this->assign('user_id',$user_id);
        $user_flag = I('param.user_flag',0);
        $this->assign('user_flag',$user_flag);
        $this->display('TIndex/setting');
    }

    //管理收货地址
    public function address()
    {
        $user_id = I('param.user_id',0);
        $this->assign('user_id',$user_id);
        $user_flag = I('param.user_flag',0);
        $this->assign('user_flag',$user_flag);
        $address_list = M('address')->where(array('user_id'=>$user_id,'user_flag'=>$user_flag))->select();
        
        foreach ($address_list as $key => $value) {
             if($value['province']){   
                $address_list[$key]['province_name'] = M('region')->where(array('region_id'=>$value['province']))->getField('region_name');
                $address_list[$key]['city_name'] = M('region')->where(array('region_id'=>$value['city']))->getField('region_name');
                $address_list[$key]['district_name'] = M('region')->where(array('region_id'=>$value['district']))->getField('region_name');
             }
        }
        $userinfo = M('teacher')->where(array('teacher_id'=>$user_id))->find();
        $this->assign('userinfo',$userinfo);
        $this->assign('address_list',$address_list);
        $this->display('TIndex/address');
    }

    //添加收货地址
    public function add_address()
    {
        $user_id = I('param.user_id',0);
        $this->assign('user_id',$user_id);
        $user_flag = I('param.user_flag',0);
        $this->assign('address_id',0);
        $this->assign('user_flag',$user_flag);

        $province_list = M('region')->where(array('region_level'=>1))->select();
        $this->assign('province_list',$province_list);
        $city_list = M('region')->where(array('region_level'=>2,'parent_id'=>1))->select();
        $this->assign('city_list',$city_list);
        $district_list = M('region')->where(array('region_level'=>3,'parent_id'=>2))->select();
        $this->assign('district_list',$district_list);

        $this->display('TIndex/address_info');
    }   

    //编辑收货地址
    public function address_edit()
    {
        $address_id = I('param.address_id',0);
        $address_info = M('address')->where(array('address_id'=>$address_id))->find();
        $this->assign('address_info',$address_info);
        $this->assign('address_id',$address_id);
        $user_id = I('param.user_id','');
        $this->assign('user_id',$user_id);
        $user_flag = I('param.user_flag','');
        $this->assign('user_flag',$user_flag);
        $province_list = M('region')->where(array('region_level'=>1))->select();
        $this->assign('province_list',$province_list);
        $city_list = M('region')->where(array('region_level'=>2,'parent_id'=>$address_info['province']))->select();
        $this->assign('city_list',$city_list);
        $district_list = M('region')->where(array('region_level'=>3,'parent_id'=>$address_info['city']))->select();
        $this->assign('district_list',$district_list);

        $aid = M('teacher')->where(array('teacher_id'=>$user_id))->getField('address_id');
        $this->assign('aid',$aid);
        $this->display('TIndex/address_info');
    }

    //异步请求地址
    public function ajax_address()
    {
        $region_level = I('param.region_level',1);
        $region_id = I('param.region_id',0);
        $region_list = M('region')->where(array('region_level'=>$region_level,'parent_id'=>$region_id))->select();
        echo json_encode($region_list);
    }

    //处理地址信息
    public function edit_address()
    {
        $address_id = I('param.address_id',0);
        $data['consignee'] = I('param.consignee','');
        $data['mobile'] = I('param.mobile','');
        $data['province'] = I('param.province','');
        $data['city'] = I('param.city','');
        $data['district'] = I('param.district','');
        $data['address'] = I('param.address','');
        $data['user_id'] = I('param.user_id','');
        $data['user_flag'] = I('param.user_flag','');
        $default = I('param.default',0);

        if($address_id > 0){
            $msg = '修改成功';    
            $ret = M('address')->where("address_id=$address_id")->save($data);
        }else{
            $msg = '添加成功';
            $ret = M('address')->add($data);
            $address_id = $ret;
        }

        if($default > 0){
            M('teacher')->where(array('teacher_id'=>$data['user_id']))->save(array('address_id'=>$address_id));
        }

        $this->success("$msg",U('mobile.php/TIndex/address',array('user_id'=>$data['user_id'],'user_flag'=>$data['user_flag'])));
    }

    public function del_address()
    {
        $aid = I('param.address_id',0);
        $ret = M('address')->where(array('address_id'=>$aid))->delete();
        if($ret){
            echo 1;
            exit;
        }
    }

    public function signin()
    {
        $data = array();
        $data['user_id'] = I('param.user_id',0);
        $data['user_flag'] = I('param.user_flag',0);
        $data['signin_num'] = I('param.signnum',0);

        //判断是否连续签到
        $day = date("d");
        $month = date("m");
        $year = date("Y");
        $month_total_day = date('j',mktime(0,0,1,($month==12?1:$month+1),1,($month==12?$year+1:$year))-24*3600);
        $signtime = M('signin')->where(array('user_id'=>$data['user_id'],'user_flag'=>$data['user_flag']))->order('id desc')->getField('add_time');
        if(empty($signtime)) $signtime = time();
		$preday = date('d',$signtime);
        $premonth = date('m',$signtime);
        $preyear = date('Y',$signtime);
        $preday = $preday + 1;
        $month_total_day = $month_total_day +  1;
        $integral = C('INTEGRAL');
        if($data['signin_num'] == 5){
            //签到5次后，重新循环
            $data['signin_num'] = 1;
            $data['user_points'] = $integral[1];
        }elseif($preyear == $year && $premonth == $month && $preday == $day){
            //当前月持续签到
            $data['signin_num'] = $data['signin_num'] + 1;
            $data['user_points'] = $integral[$data['signin_num']];
        }elseif($preyear == $year && $premonth == $month && $preday != $day){
            //当前月中断签到
            $data['signin_num'] = 1;
            $data['user_points'] = $integral[1];
        }elseif($preyear == $year && $premonth != $month && $preday == $month_total_day){
            //下个月持续签到
            $data['signin_num'] = $data['signin_num'] + 1;
            $data['user_points'] = $integral[$data['signin_num']];
        }elseif($preyear == $year && $premonth != $month && $preday != $month_total_day){
            //下个月中断签到
            $data['signin_num'] = 1;
            $data['user_points'] = $integral[1];
        }elseif($preyear != $year && $month == 1 && $preday == $month_total_day){
            //下一年持续签到
            $data['signin_num'] = $data['signin_num'] + 1;
            $data['user_points'] = $integral[$data['signin_num']];
        }elseif($preyear != $year && $month == 1 && $preday != $month_total_day){
            //下一年中断签到
            $data['signin_num'] = 1;
            $data['user_points'] = $integral[1];
        }
        $data['add_time'] = time();
        $ret = M('signin')->add($data);
        M('teacher')->where(array('teacher_id'=>$data['user_id']))->setInc('rank_points',$data['user_points']);
        M('points_record')->add(array('user_id'=>$data['user_id'],'student_points'=>$data['user_points'],'change_time'=>time(),'change_desc'=>'签到金豆','change_type'=>1,'user_flag'=>2));
        if($ret){
            echo json_encode(array('code'=>1,'user_points'=>$data['user_points'],'msg'=>'签到成功'));
            exit;
        }
    }

    //退出登录，删除openid
    public function signOut($user_id)
    {
        $result = M('teacher')->where("teacher_id = $user_id")->setField('wx_id','');
        $this->success('退出成功','/mobile.php/Oauth');
    }

    //修改密码
    public function editPwd()
    {
        $user = session('user');
        $id = $user['id'];
        if(IS_POST){
            $old_pwd = md5(I('post.old_pwd',0));
            $teacher = M('teacher');
            $pwd = $teacher->where(['teacher_id'=> $id])->getField('pwd');
            if($pwd != $old_pwd){
                $this->error('旧密码错误');
            }
            $new_pwd = md5(I('post.new_pwd',0));
            $result = $teacher->where(['teacher_id'=>$id])->setField('pwd',$new_pwd);
            if($result){
                $this->success('修改成功',U('TIndex/index',['teacher_id'=>$id]));
            }else{
                $this->error('修改失败');
            }

        }else{
            $this->assign('id',$id);
            $this->display('TIndex/editPwd');
        }
    }

    // 收益
//     public function earn($user_id)
//     {
//         $first_flag = M('teacher')->where(array('teacher_id'=>$user_id))->getField('first_flag');
//         if($first_flag == 3)
//         {
//         // 判断points_record里边是否有记录
//         // 该学校的学期起止时间
//         $school_id = M('teacher')->where("teacher_id = $user_id")->getField('school_id');
//         $res = M('schools')->where("school_id = $school_id")->find();
//         $semester_one_start = $res['semester_one_start'];
//         $semester_one = $res['semester_one'];
//         $semester_two_start = $res['semester_two_start'];
//         $semester_two = $res['semester_two'];
//         $students = M('students')->where(array('teacher_id'=>$user_id))->select();

//         if($time <= $semester_one){
//             $start_time = $semester_one_start;
//             $end_time = $semester_one;
//         }else{
//             $start_time = $semester_two_start;
//             $end_time = $semester_two;
//         }
//         // $a = strtotime(date("Y-m",$end_time));
//         // 下个月的第一天
//         $next_month = strtotime(date("Y-m-1", strtotime("+1 months", $start_time)));
//         $time = time();
//         $b = strtotime(date("Y-m",$time));

//         $points_logs = M('points_record')->where(array('user_id'=>$user_id,'change_desc'=>'月返金豆','change_time'=>$next_month))->order('change_time desc')->select();
//         if($time>$next_time)
//         {
//             if(empty($points_logs))
//             {
//                 $data['user_id'] = $user_id;
//                 // $student_points
//                 foreach ($students as $key => $value) 
//                 {
//                     if($value['is_paid']==1 && $value['paid_time']<$next_month)
//                     {
//                         $nums += 1;
//                     }
//                 }
//                 $days = 30 - date("j",$start_time);
//                 $student_points = $days*$nums*80;
//                 $data['student_points'] = $student_points;
//                 $data['change_desc'] = '月返金豆';
//                 $data['change_type'] = 1;
//                 $data['change_time'] = $next_month;
//                 $data['user_flag'] = 2;
//                 M('points_record')->add($data);
//                 M('teacher')->where(array('teacher_id'=>$user_id))->setInc('rank_points',$student_points);
//             }else
//             {
//                 // echo "string"."<br />";
//                 $next_month = M('points_record')->where(array('user_id'=>$user_id,'change_desc'=>'月返金豆'))->order('change_time desc')->getField('change_time');
//             }
//         }
//         if($time>$next_month)
//         {
//             for($i=1;$i<=12;$i++)
//             {
//                 $next_month = strtotime(date("Y-m-1", strtotime("+1 months", $next_month)));
//                 if($time<$next_month)
//                 {
//                     break;
//                 }
//                 // 最后一个月如何算
//                 // if($a == $b)
//                 // {
//                 //     M('points_record')->add();//记录时间为end_time,下一次就进不来了
//                 //     M('students')->add();
//                 // }
//                 foreach ($students as $key => $value) 
//                 {
//                     if($value['is_paid']==1 && $value['paid_time']<$next_month)
//                     {
//                         $nums += 1;
//                     }
//                 }
//                 $student_points = 30*$nums*80;
//                 $data['student_points'] = $student_points;
//                 $data['change_desc'] = '月返金豆';
//                 $data['change_type'] = 1;
//                 $data['change_time'] = $next_month;
//                 $data['user_id'] = $user_id;
//                 $data['user_flag'] = 2;
//                 M('points_record')->add($data);
//                 M('teacher')->where(array('teacher_id'=>$user_id))->setInc('rank_points',$student_points);
//             }
//             // 如果最后当前时间和最后一个月的时间，则把最后一个月加上；
//         }
//     }   //if语句的结尾 
//         $gold = M('teacher')->where(array('teacher_id'=>$user_id))->getField('rank_points');
//          // 每个学生一天80个金豆
//          // $gold = $total_days*$num*80;
//          $this->assign('gold',$gold);
//          $this->assign('user_id',$user_id);
//          $this->display();
// }



/**********************更新之后**************************/
    public function earn($user_id)
    {
        $first_flag = M('teacher')->where(array('teacher_id'=>$user_id))->getField('first_flag');
        // $first_flag == 1表示为主老师
        if($first_flag == 1)
        {
        $time = time();
        // 判断points_record里边是否有记录
        // 该学校的学期起止时间
        $school_id = M('teacher')->where("teacher_id = $user_id")->getField('school_id');// 获取机构根据机构表中的数据
        $res = M('schools')->where("school_id = $school_id")->find();  // 获取此老师的所在机构
        $semester_one_start = $res['semester_one_start'];
        $semester_one = $res['semester_one'];
        $semester_two_start = $res['semester_two_start'];
        $semester_two = $res['semester_two'];
        $students = M('students')->where(array('teacher_id'=>$user_id))->select();  //获取此老师的学生

        if($time <= $semester_one){
            $start_time = $semester_one_start;
            $end_time = $semester_one;
        }else{
            $start_time = $semester_two_start;
            $end_time = $semester_two;
        }
        // $time1为最后一个月的第一天
        $time1 = date('Y-m',$end_time);
        $time1 = strtotime($time1);
        // $a = strtotime(date("Y-m",$end_time));
        // 下个月的第一天
        $next_month = strtotime(date("Y-m-1", strtotime("+1 months", $start_time)));
        $points_logs = M('points_record')->where(array('user_id'=>$user_id,'change_desc'=>'月返金豆','change_time'=>$next_month))->order('change_time desc')->select();
        if($time>$next_month)
        {
            if(empty($points_logs))
            {
                $nums = 0;
                $data['user_id'] = $user_id;
                // $student_points
                foreach ($students as $key => $value) 
                {
                    if($value['is_paid']==1 && $value['paid_time']<$next_month)
                    {
                        $nums += 1;
                    }
                }
                $days = 30 - date("j",$start_time);
                $student_points = $days*$nums*80;
                $data['student_points'] = $student_points;
                $data['change_desc'] = '月返金豆';
                $data['change_type'] = 1;
                $data['change_time'] = $next_month;
                $data['user_flag'] = 2;
                M('points_record')->add($data);
                M('teacher')->where(array('teacher_id'=>$user_id))->setInc('rank_points',$student_points);
            }else
            {
                // echo "string"."<br />";
                $next_month = M('points_record')->where(array('user_id'=>$user_id,'change_desc'=>'月返金豆'))->order('change_time desc')->getField('change_time');
            }
        }
        if($time>$next_month && $time<=$end_time)
        {
            for($i=1;$i<=12;$i++)
            {
                $nusm2 = 0;
                $next_month = strtotime(date("Y-m-1", strtotime("+1 months", $next_month)));
                if($time<$next_month)
                {
                    break;
                }
                // 最后一个月
                if($next_month == $time1)
                {
                    $nums1 = 0;
                    foreach ($students as $key => $value) 
                    {
                        if($value['is_paid']==1 && $value['paid_time']<$next_month)
                        {
                        // 学生数
                            $nums1 += 1;
                        }
                    }
                    $days = date("j",$end_time);
                    $student_points = $days*$nums1*80;
                    $data['student_points'] = $student_points;
                    $data['change_desc'] = '月返金豆';
                    $data['change_type'] = 1;
                    $data['change_time'] = $end_time;
                    $data['user_flag'] = 2;
                    M('points_record')->add($data);
                    M('teacher')->where(array('teacher_id'=>$user_id))->setInc('rank_points',$student_points);
                    break;
                }

                foreach ($students as $key => $value) 
                {
                    if($value['is_paid']==1 && $value['paid_time']<$next_month)
                    {
                        $nums2 += 1;
                    }
                }
                $student_points = 30*$nums2*80;
                $data['student_points'] = $student_points;
                $data['change_desc'] = '月返金豆';
                $data['change_type'] = 1;
                $data['change_time'] = $next_month;
                $data['user_id'] = $user_id;
                $data['user_flag'] = 2;
                M('points_record')->add($data);
                M('teacher')->where(array('teacher_id'=>$user_id))->setInc('rank_points',$student_points);
            }
        }
    }   //if语句的结尾 
         $gold = M('teacher')->where(array('teacher_id'=>$user_id))->getField('rank_points');
         // 每个学生一天80个金豆
         // $gold = $total_days*$num*80;
         $this->assign('gold',$gold);
         $this->assign('user_id',$user_id);
         $this->display();
    }







    // 收益记录
    public function earn_log($user_id)
    {
        $user_id = I('user_id');
        $this->assign('user_id',$user_id);
        $list = M('points_record')->where("user_id = $user_id")->order('change_time desc')->limit(50)->select();
        // var_dump($list);die;
        $this->assign('list',$list);
        $this->display();
    }

}