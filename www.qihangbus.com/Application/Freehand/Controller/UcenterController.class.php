<?php
namespace mobiles\Controller;
use Think\Controller;
class UcenterController extends CommonController {
    public function index()
    {
        $condition['user_id'] = $this->userinfo['student_id'];
        $condition['user_flag'] = 3;
        $condition['add_time'] = array('EGT',strtotime(date('Y-m-d')." 00:00:00"));
       
        $id = M('signin')->where($condition)->getField('id');
        $this->assign('signin',$id);
        $signinnum = M('signin')->where(array('user_id'=>$condition['user_id'],'user_flag'=>3))->order('id desc')->getField('signin_num');
        if(empty($signinnum)) $signinnum = 1;
		$this->assign('signnum',$signinnum);
        $this->display('ucenter/index');
    }

    //获取用户等级
    public function get_levle_info()
    {
    	$student_id = I('param.student_id',0);
		$student_points = I('param.student_points',0);
		$student_rank = I('param.student_rank',0);

        $student_points = M('students')->where(array('student_id'=>$student_id))->getField("student_points");

		$this->assign('student_points',$student_points);

        if($student_rank < 1){
            $student_rank = 1;
        }

		$this->assign('student_rank',$student_rank);
		$nextrank = $student_rank + 1;
		$this->assign('nextrank',$nextrank);
		//查询勋章列表
		$medal_list = M('medal')->select();

		//查询当前用户所获的勋章
		$student_medal = M('student_medal')->where(array('student_id'=>$student_id))->select();
		$studentmedal = array();
		if(!empty($student_medal)){
			foreach ($student_medal as $key => $value) {
				$studentmedal[$value['medal_id']] = $value['student_id'];
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
		$next_points = M('student_rank')->where("rank_id > $student_rank")->getField('min_points');
		$diff_points = $next_points - $student_points;
		$this->assign('next_points',$next_points);
		$this->display('ucenter/level_info');
    }

    //获取用户排名
    public function get_class_rank()
    {
    	$student_id = I('param.student_id',0);
		$student_points = I('param.student_points',0);
		$class_id = I('param.class_id',0);

        $student_points = M('students')->where(array('student_id'=>$student_id))->getField("student_points");
        $this->assign('student_points',$student_points);
        Vendor('Weixin.jssdk');
        $wx_info = M('config')->where(array('parent_id'=>13))->field('code,value')->select();
        $wx_arr = array();
        foreach ($wx_info as $key => $value) {
            $wx_arr[$value['code']] = $value['value'];
        }
        $jssdk = new \JSSDK($wx_arr['appid'], $wx_arr['appsecret']);
        $signPackage = $jssdk->GetSignPackage();

        $this->assign('signPackage',$signPackage);

		//查询当前学生所在班级总人数
		$total_num = M('students')->where(array('class_id'=>$class_id))->count();
		//查询当前班级小于当前学生积分的人数
		$student_num = M('students')->where("student_points <= $student_points AND class_id = $class_id")->count();
		
		$student_per = round($student_num/$total_num,2)*100 ."%";
		$this->assign('student_per',$student_per);
		$this->display('ucenter/class_rank');
    }

    public function avatar()
    {
        $user_id = I('get.user_id',0);
        $user_flag = I('get.user_flag',0);
        $avatar = M('students')->where(array('student_id'=>$user_id))->getField('student_avatar');
        $this->assign('user_id',$user_id);
        $this->assign('user_flag',$user_flag);
        $this->assign('avatar',$avatar);
        $this->display('ucenter/avatar');   
    }

    public function update_avatar()
    {
        $user_id = I('post.user_id',0);
        $user_flag = I('post.user_flag',0);
        
        $upload = new \Think\Upload();
        $upload->maxSize = 3145728;
        $upload->exts = array('jpg', 'gif', 'png', 'jpeg');
        $upload->saveName = 'time';
        $info = $upload->upload();
        
        if (!$info) {
            $avatar = I('post.avatar_old','');
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

        $userinfo = session('userinfo');
        $userinfo['student_avatar'] = $avatar;
        session('userinfo',$userinfo);
        $ret = M('students')->where(array('student_id'=>$user_id))->save(array('student_avatar'=>$avatar));
        if($ret){
            $this->success('修改头像成功',U('mobile.php/Ucenter/avatar',array('user_id'=>$user_id,'user_flag'=>$user_flag)));
        }
    }

    //获取消息列表
    public function get_message_list()
    {
    	$student_id = I('param.student_id',0);
    	$page = I('param.page',1);
    	$message = M('message');

    	//更新用户中心消息小红点
    	M('students')->where(array('student_id'=>$student_id))->save(array('message_num'=>0));
    	$userinfo = session('userinfo');
    	$userinfo['message_num'] = 0;
    	session('userinfo',$userinfo);
    	$count = $message->where(array('receiver_id'=>$student_id))->count();
        $page = new \Think\Page($count,15);
        $show = $page->show();
        $list = $message->where(array('receiver_id'=>$student_id))->order('message_id desc')->limit($page->firstRow.','.$page->listRows)->select();
        
        $lt = array();
        foreach ($list as $key => $value) {
            $value['sub_message'] = msubstr($value['message'],40,strlen($value['message'])-1);
            $value['message'] = msubstr($value['message'],0,40);
            $lt[date('Y-m-d',$value['sent_time'])]['lt'][] = $value;
        }

        $this->assign('list',$lt);
        $this->assign('page',$show);
        $this->assign('user_id',$student_id);
        $class_id = M('students')->where(array('student_id'=>$student_id))->getField('class_id');
        $this->assign('class_id',$class_id);
    	$this->display('ucenter/message_list');
    }

    public function ajax_message()
    {
        $message_id = I('param.message_id','');
        $ret = M('message')->where(array('message_id'=>$message_id))->save(array('readed'=>1,'read_time'=>time()));
        echo $ret;
    }

    public function select_teacher()
    {
        $user_id = I('param.user_id','');
        $class_id = I('param.class_id','');
        $list = M('teacher')->where(array('class_id'=>$class_id))->select();
        $this->assign('list',$list);
        $this->assign('user_id',$user_id);
        $this->assign('class_id',$class_id);
        $this->display('ucenter/select_teacher');
    }

    public function send_message()
    {
        $user_id = I('param.user_id','');
        $this->assign('user_id',$user_id);
        $teacher_id = I('param.receive_id','');
        $this->assign('teacher_id',$teacher_id);
        $this->display('ucenter/send_message');
    }

    public function message_handle()
    {
        $data = array();
        $data['sender_id'] = I('post.user_id');
        $sender_name = M('students')->where(array('student_id'=>$data['sender_id']))->getField('student_name');
        $data['sender_name'] = $sender_name;
        $receiver = I('post.teacher_id','');

        if(strpos($receiver,",") === false){
            $rec[] = $receiver;
        }else{
            $rec = explode(',', $receiver);
        }
        $data['user_flag'] = 2;
        $data['sent_time'] = time();
        $data['title'] = I('post.title','');
        $data['message'] = I('post.message','');

        for ($i=0; $i < count($rec); $i++) {
            $data['receiver_id'] = $rec[$i]; 
            M('teacher')->where(array('teacher_id'=>$rec[$i]))->save(array('message_num'=>1));
            M('message')->add($data);
        }

        $this->success('发送成功',U('mobile.php/Ucenter/index'));

    }


    //用户中心设置
    public function setting()
    {
    	$student_id = I('param.student_id',0);
    	$this->assign('student_id',$student_id);
    	$this->display('ucenter/setting');
    }

    //管理家长信息
    public function parent()
    {
    	$student_id = I('param.student_id',0);
		$this->assign('student_id',$student_id);
    	$parent_list = M('students_parent')->where(array('student_id'=>$student_id))->select();
    	$this->assign('parent_list',$parent_list);
    	$this->display('ucenter/parent');
    }

    //编辑家长信息
    public function parent_edit()
    {
    	$parent_id = I('param.parent_id',0);
    	$parent_info = M('students_parent')->where(array('parent_id'=>$parent_id))->find();
    	$this->assign('parent_info',$parent_info);
        $this->assign('student_id',$parent_info['student_id']);
    	$this->display('ucenter/parent_info');
    }

    //添加家长
    public function add_parent()
    {
        $student_id = I('param.student_id','');
        $this->assign('student_id',$student_id);
        $parent_info['parent_sex'] = 1;
        $this->assign('parent_info',$parent_info);
        $this->display('ucenter/parent_info');
    }

    //处理家长信息
    public function edit_handle()
    {
    	$parent_id = I('param.parent_id',0);
        $data['parent_sex'] = I('param.parent_sex',0);
        $data['parent_mobile'] = I('param.parent_mobile','');
        $data['parent_name'] = I('param.parent_name','');
        $data['student_id'] = I('param.student_id','');


        if($parent_id > 0){
            $msg = '修改成功';    
            $ret = M('students_parent')->where("parent_id=$parent_id")->save($data);
        }else{
            $msg = '添加成功';
            $ret = M('students_parent')->add($data);
        }

        if($ret){
            $this->success("$msg",U('mobile.php/Ucenter/parent',array('student_id'=>$data['student_id'])));
        }
    }

    public function del_parent()
    {
        $pid = I('param.parent_id',0);
		
        $flag = M('students_parent')->where(array('parent_id'=>$pid))->getField("flag");
		if($flag > 0){
			echo 99;
			exit;
		}
        $ret = M('students_parent')->where(array('parent_id'=>$pid))->delete();
        if($ret){
            echo 1;
            exit;
        }
    }

    //管理收货地址
    public function address()
    {
        $student_id = I('param.student_id',0);
        $this->assign('student_id',$student_id);

        $user_flag = I('param.user_flag',0);
        $this->assign('user_flag',$user_flag);

        $address_list = M('address')->where(array('user_id'=>$student_id,'user_flag'=>3))->select();
        
        foreach ($address_list as $key => $value) {
             if($value['province']){   
                $address_list[$key]['province_name'] = M('region')->where(array('region_id'=>$value['province']))->getField('region_name');
                $address_list[$key]['city_name'] = M('region')->where(array('region_id'=>$value['city']))->getField('region_name');
                $address_list[$key]['district_name'] = M('region')->where(array('region_id'=>$value['district']))->getField('region_name');
             }
        }
        $userinfo['address_id'] = M('students')->where(array('student_id'=>$student_id))->getField('address_id');
        
        $this->assign('address_list',$address_list);
        $this->assign('userinfo',$userinfo);
        $this->display('ucenter/address');
    }

    //添加收货地址
    public function add_address()
    {
        $student_id = I('param.student_id',0);

        $this->assign('address_id',0);
        $this->assign('student_id',$student_id);

        $user_flag = I('param.user_flag',0);
        $this->assign('user_flag',$user_flag);

        $province_list = M('region')->where(array('region_level'=>1))->select();
        $this->assign('province_list',$province_list);
        $city_list = M('region')->where(array('region_level'=>2,'parent_id'=>1))->select();
        $this->assign('city_list',$city_list);
        $district_list = M('region')->where(array('region_level'=>3,'parent_id'=>2))->select();
        $this->assign('district_list',$district_list);

        $this->display('ucenter/address_info');
    }   

    //编辑收货地址
    public function address_edit()
    {
        $address_id = I('param.address_id',0);
        $address_info = M('address')->where(array('address_id'=>$address_id))->find();
        $this->assign('address_info',$address_info);
        $this->assign('address_id',$address_id);
        $student_id = I('param.student_id',0);
        $this->assign('student_id',$student_id);
        $user_flag = I('param.user_flag',0);
        $this->assign('user_flag',$user_flag);
        $province_list = M('region')->where(array('region_level'=>1))->select();
        $this->assign('province_list',$province_list);
        $city_list = M('region')->where(array('region_level'=>2,'parent_id'=>$address_info['province']))->select();
        $this->assign('city_list',$city_list);
        $district_list = M('region')->where(array('region_level'=>3,'parent_id'=>$address_info['city']))->select();
        $this->assign('district_list',$district_list);

        $this->display('ucenter/address_info');
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
        $data['user_id'] = I('param.student_id','');
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
            M('students')->where(array('student_id'=>$data['user_id']))->save(array('address_id'=>$address_id));
        }

        
        $this->success("$msg",U('mobile.php/Ucenter/address',array('student_id'=>$data['user_id'],'user_flag'=>$data['user_flag'])));
        
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

    //任务列表
    public function task()
    {
        $student_id = I('param.student_id',0);
        $this->assign('student_id',$student_id);

        //查询任务对应的图书信息
        /*$arr = M('circulation')->where(array('student_id'=>$student_id,'circul_status'=>1))->field('book_id')->select();
        $lt = array();
        foreach ($arr as $key => $value) {
            array_push($lt,$value['book_id']);
        }*/

        $book_id = M('circulation')->where(array('student_id'=>$student_id,'circul_status'=>1))->getField('book_id');

        $this->assign('book_arr',$book_id);

        //查询今天任务
        $taskid = M('config')->where(array('id'=>24))->getField('value');
        if(!empty($book_id)){
            $task_book_id = M("task")->where(array('task_book_id'=>$book_id))->getField("task_id");
            $taskid .= ','.$task_book_id;
        }
        $task_list = M('task')->where(array('task_id'=>array('in',$taskid)))->select();

        $this->assign('task_list',$task_list);
        $time = strtotime(date("Y-m-d")." 00:00:00");
        $studenttask = array();
        $student_task = M('student_task')->where("student_id=$student_id and task_time >= $time")->field('task_id')->select();
         
        for ($i=0; $i < count($student_task); $i++) { 
            array_push($studenttask,$student_task[$i]['task_id']);           
        }    

        //计算当前时间
        $hour = date("H");
        $minute = date("i");
        $second = date("s");
        $times = ($hour * 3600) + ($minute * 60) + $second;
        $this->assign('times',$times);
        $task = array();
        $num = 0;

        foreach ($task_list as $key => $value) {
            if(in_array($value['task_id'],$studenttask)){    
                $num++;
            }
            $task_list[$key]['option'] = M('task_option')->where(array('task_id'=>$value['task_id']))->order('option_id asc')->select();
        }
        $this->assign('task_list',$task_list);
		
		//判断是否可以做任务
		if(empty($book_id)){
			$num = -1;
		}
		
        $this->assign('num',$num);

        $student_points = M('students')->where(array('student_id'=>$student_id))->getField("student_points");
        $this->assign('student_points',$student_points);

        $task_number = M('students')->where(array('student_id'=>$student_id))->getField("task_number");
        $this->assign('task_number',$task_number);

        $this->display('ucenter/task');
    }

    //记录任务
    public function task_comment()
    {
        $student_id = I('param.student_id',0);
        $this->assign('student_id',$student_id);
        $task_id = I('param.task_id',0);
        $this->assign('task_id',$task_id);
        $info = M('task')->where(array('task_id'=>$task_id))->find();
        $this->assign('info',$info);
        $option = M('task_option')->where(array('task_id'=>$task_id))->order('option_id asc')->select();
        $this->assign('option',$option);
        $this->display('ucenter/task_comment');
    }

    public function comment_handle()
    {
        $data = array();
        $data['student_id'] = I('param.student_id',0);
		
		$data['book_arr'] = I('param.book_arr',0);
		$ret = M('circulation')->where(array('circul_status'=>1,'student_id'=>$data['student_id']))->count();
        if($ret < 1){
			$this->error("您暂无借阅绘本，无法提交任务！",U('mobile.php/Ucenter/task',array('student_id'=>$data['student_id'])));
			exit;
		}
        $option_id = I('param.option_id','');
        $data['task_time'] = time();
        $data['comment_time'] = time();

		//判断任务是否正确
		
		//如果不正确更新任务次数，并跳转到任务界面(每次任务都有两次机会)
		

        foreach ($option_id as $key => $value) {
            
            $data['task_id'] = $key;

            if(count($value) > 1){
                $optionid = '';
                $optionname = '';
                for ($i=0; $i < count($value); $i++) { 
                    $optionid .= $value[$i].",";
                    $tmp = M('task_option')->where(array('option_id'=>$value[$i]))->getField('option_name');
                    $optionname .= $tmp.",";
                }

                $data['option_id'] = rtrim($optionid, ",");
                $data['option_name'] = rtrim($optionname, ",");
            }else{
                $data['option_id'] = $value[0];
                $data['option_name'] = M('task_option')->where(array('option_id'=>$value[0]))->getField('option_name');
            }

            M('student_task')->add($data);

            $points = M('task')->where(array('task_id'=>$key))->getField('task_award');
            M('students')->where(array('student_id'=>$data['student_id']))->setInc('student_points',$points);
            M('students')->where(array('student_id'=>$data['student_id']))->setInc('task_number',1);
			M('points_record')->add(array('user_id'=>$data['student_id'],'student_points'=>$points,'change_time'=>time(),'change_desc'=>'任务金豆','change_type'=>1,'user_flag'=>3));
        }
		
        $info = M('students')->where(array('student_id'=>$data['student_id']))->find();
        session('userinfo',$info);
        
        $this->success("评论成功",U('mobile.php/Ucenter/task',array('student_id'=>$data['student_id'])));
        
    }

    public function return_json()
    {
        //$array = array('dList'=>array(array('d'=>'2016-11-15'),array('d'=>'2016-11-16'),array('d'=>'2016-11-17')));
        //echo json_encode($array);
        $student_id = I('get.student_id',0);
        $start = strtotime(date("Y-m-01 00:00:00"));
        $end = strtotime(date("Y-m-d H:i:s"));
        $condition['student_id'] = $student_id;
        $condition['task_time'] = array('between',array($start,$end));
        $ret = M('student_task')->where($condition)->select();
        $arr = array();

        foreach ($ret as $key => $value) {
            $data['d'] = date("Y-m-j",$value['task_time']);
            $arr[] = $data;
        }

        $array = array('dList'=>$arr);
        echo json_encode($array);
    }

    //任务记录
    public function task_record()
    {
        $student_id = I('param.student_id',0);
        $this->assign('student_id',$student_id);
        //查询当月数据
        $start = strtotime(date("Y-m-01 00:00:00"));
        $end = strtotime(date("Y-m-d H:i:s"));
        $condition['student_id'] = $student_id;
        $condition['task_time'] = array('between',array($start,$end));
        $ret = M('student_task')->where($condition)->order('task_time asc')->select();

        if($ret){
            $last = end($ret);

            $this->assign('last_day',date("Y-m-j",$last['task_time']));
            $info = M('task')->where(array('task_id'=>$last['task_id']))->find();
            $info['st_id'] = $last['st_id'];
            $info['comment_time'] = $last['comment_time'];
            $this->assign('info',$info);
        }
        $this->display('ucenter/task_record');
    }

    //异步加载数据
    public function ajax_task()
    {
        $student_id = I('get.student_id',0);
        $str_date = I('get.str_date',0);
        
        //查询当月数据
        $start = strtotime($str_date.' 00:00:00');
        $end = strtotime($str_date.' 23:59:59');
        $condition['student_id'] = $student_id;
        $condition['task_time'] = array('between',array($start,$end));
        $ret = M('student_task')->where($condition)->order('task_time asc')->find();
        
        if($ret){
            $info = M('task')->where(array('task_id'=>$ret['task_id']))->find();
            echo json_encode(
                array('last_day'=>date("Y-m-j",$last['task_time']),
                'task_title'=>$info['task_title'],
                'task_desc'=>$info['task_desc'],
                'task_award'=>$info['task_award']));
        }
        
    }

    public function signin()
    {
        $data = array();
        $data['user_id'] = I('post.user_id',0);
        $data['user_flag'] = I('post.user_flag',0);
        $data['signin_num'] = I('post.signnum',0);

		
		
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
        M('students')->where(array('student_id'=>$data['user_id']))->setInc('student_points',$data['user_points']);
        M('points_record')->add(array('user_id'=>$data['user_id'],'student_points'=>$data['user_points'],'change_time'=>time(),'change_desc'=>'签到金豆','change_type'=>1,'user_flag'=>3));
        if($ret){
            echo json_encode(array('code'=>1,'user_points'=>$data['user_points'],'msg'=>'签到成功'));
            exit;
        }
    }
}