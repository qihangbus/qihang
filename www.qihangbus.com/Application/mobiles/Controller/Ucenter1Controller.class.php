<?php
namespace mobiles\Controller;
use Think\Controller;
class Ucenter1Controller extends Controller {

    public function index()
    {
        $this->userinfo = M('students')->where("student_id = ".$this->userinfo['student_id'])->find();
        $condition['user_id'] = $this->userinfo['student_id'];
        $condition['user_flag'] = 3;
        $condition['add_time'] = array('EGT',strtotime(date('Y-m-d')." 00:00:00"));

        $id = M('signin')->where($condition)->getField('id');
        $this->assign('signin',$id);
        $signinnum = M('signin')->where(array('user_id'=>$condition['user_id'],'user_flag'=>3))->order('id desc')->getField('signin_num');
        if(empty($signinnum)) $signinnum = 1;
        $this->assign('signnum',$signinnum);
        $pay_tips = I('get.pay_tips',0);
        $this->assign('pay_tips',$pay_tips);
        $turn = U('mobile.php/Parentlogin/getChildrenInfo', ['student_id' => $this->userinfo['student_id']]);
        $this->assign('turn',$turn);

        //判断有没有交费
        $student_id = $this->userinfo['student_id'];
        $userinfo1 = M('students')->where(array('student_id'=>$student_id))->find();
        $data = M('schools')->where("school_id = {$userinfo1['school_id']}")->field('semester_one_start,semester_one,semester_two_start,semester_two')->find();
        if (time() > ($data['semester_one'] + 86400) && time() < ($data['semester_two'] + 86400)) {//已到第二学期
            if ($userinfo1['paid_num'] <> 2) {//未支付
                $pay_url = U('mobile.php/Parentlogin/getChildrenInfo', array('student_id' => $student_id));
            }else{
                $end_time = $data['semester_two'];
            }
        } elseif (time() <= ($data['semester_one'] + 86400)) {//第一学期
            if ($userinfo1['paid_num'] <> 1 && $userinfo1['paid_num'] <> 2) {//未支付
                $pay_url = U('mobile.php/Parentlogin/getChildrenInfo', array('student_id' => $student_id));
            }else{
                $end_time = $data['semester_one'];
            }
        }

        $day = M('students')->where(array('student_id'=>$condition['user_id']))->getField('punch_num');
        $this->assign('day',$day);
        $this->assign('paid_time',$userinfo1['paid_time']);
        $this->assign('end_time',$end_time);
        $this->assign('pay_url',$pay_url);
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
        $upload->maxSize = 31457280;
        $upload->exts = array('jpg', 'gif', 'png', 'jpeg');
        $upload->saveName = 'time';
        $info = $upload->upload();
        
        if (!$info) {
            $this->error('请先选择图片');
            //$avatar = I('post.avatar_old','');
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
        $message = M('message');

        //更新用户中心消息小红点
        M('students')->where(array('student_id'=>$student_id))->save(array('message_num'=>0));
        $userinfo = session('userinfo');
        $userinfo['message_num'] = 0;
        session('userinfo',$userinfo);
        $list = $message->where("(sender_id = $student_id and sender_name = '{$userinfo['student_name']}') or (receiver_id = $student_id and user_flag = 3)")->order('message_id desc')->limit(30)->select();
        $lt = array();
        foreach ($list as $key => $value) {
            $value['sub_message'] = msubstr($value['message'],40,strlen($value['message'])-1);
            $value['message'] = msubstr($value['message'],0,40);
            $lt[date('Y-m-d',$value['sent_time'])]['lt'][$key] = $value;
            if($value['sender_id'] == $student_id){
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
        //判断有没有交费
        $userinfo = M('students')->where(array('student_id'=>$student_id))->find();
        $data = M('schools')->where("school_id = {$userinfo['school_id']}")->field('semester_one_start,semester_one,semester_two_start,semester_two')->find();
        if (time() > ($data['semester_one'] + 86400) && time() < ($data['semester_two'] + 86400)) {//已到第二学期
            if ($userinfo['paid_num'] <> 2) {//未支付
                $pay_url = U('mobile.php/Parentlogin/getChildrenInfo', array('student_id' => $student_id));
            }else{
                $end_time = $data['semester_two'];
            }
        } elseif (time() <= ($data['semester_one'] + 86400)) {//第一学期
            if ($userinfo['paid_num'] <> 1 && $userinfo['paid_num'] <> 2) {//未支付
                $pay_url = U('mobile.php/Parentlogin/getChildrenInfo', array('student_id' => $student_id));
            }else{
                $end_time = $data['semester_one'];
            }
        }else{
            $pay_url = U('mobile.php/Parentlogin/getChildrenInfo', ['student_id' => $student_id]);
        }
        $this->assign('paid_time',$userinfo['paid_time']);
        $this->assign('end_time',$end_time);
        $this->assign('pay_url',$pay_url);
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
        $parent_info['parent_sex'] = 1;
        $this->assign('student_id',$student_id);
        $this->assign('parent_info',$parent_info);
        $this->assign('type',1);
        $this->display('ucenter/parent_info');
    }

    //处理家长信息
    public function edit_handle()
    {
        $parent_id = I('param.parent_id');
        $parent_id || $parent_id = 0;
        $data['parent_sex'] = I('param.parent_sex',0);
        $data['parent_name'] = I('param.parent_name','');
        $data['student_id'] = I('param.student_id','');

        if($parent_id > 0){
            $msg = '修改成功';
            $ret = M('students_parent')->where("parent_id=$parent_id")->save($data);
        }else{
            $data['parent_mobile'] = trim(I('param.parent_mobile',''));
            if(!$data['parent_mobile']) $this->error('手机号不能为空');
            $access = M('students_parent')->where("parent_mobile = {$data['parent_mobile']}")->find()
                || $access = M('teacher')->where(array('teacher_mobile'=>$data['parent_mobile']))->find()
                    || $access = M('schools')->where(array('leader_mobile'=>$data['parent_mobile']))->find()
                        || $access = M('schools')->where(array('teacher_mobile'=>$data['parent_mobile']))->find()
                            || $access = M('admins')->where(array('admin_mobile'=>$data['parent_mobile']))->find();

            if($access){
                $this->error('手机号已注册');
            }

            $code = I('post.code',0);
            if(S($data['parent_mobile']) != $code){
                $this->error('验证码错误');
            }
            $student = M('students')->where(['student_id'=>$data['student_id']])->find();
            $data['pwd'] = md5('123456');
            $data['school_id'] = $student['school_id'];
            $data['grade_id'] = $student['grade_id'];
            $data['class_id'] = $student['class_id'];
            $msg = '添加成功';
            $ret = M('students_parent')->add($data);
        }

        if($ret){
            $this->success("$msg",U('mobile.php/Ucenter/parent',array('student_id'=>$data['student_id'])));
        }else{
            $this->error('操作失败');
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
        $user = session('user');
        $pid = $user['id'];
        $sid = M('students_parent')->where("parent_id = $pid")->getField('student_id');
        $this->assign('student_id',$sid);

        $task = M('circulation c')
            ->join('left join fh_task t on t.task_book_id = c.book_id')
            ->where(['c.student_id'=>$sid,'c.circul_status'=>1])
            ->field('t.*')
            ->select();

        //判断任务有没有完成
        $access = M('circulation c')
            ->join('left join fh_task t on t.task_book_id = c.book_id')
            ->join('fh_student_task st on st.task_id = t.task_id and st.student_id = c.student_id')
            ->where(['c.student_id'=>$sid,'c.circul_status'=>1])
            ->field('st.*')
            ->select();
        $points = 0;
        //查询选项
        $ids = '';
        foreach ($task as $k => $v) {
            $task[$k]['option'] = M('task_option')->where(array('task_id'=>$v['task_id']))->order('option_id asc')->select();
            if($access){
                $task[$k]['select_op'] = M('student_task')->where("task_id = {$v['task_id']} and student_id = $sid")->getField('option_id');
                $ids .= $v['task_id'].',';
            }
            $points += $v['task_award'];
        }

        $task_number = M('students')->where(array('student_id'=>$sid))->getField("task_number");
        $this->assign('task',$task);
        $this->assign('access',$access);
        $this->assign('points',$points);
        $this->assign('task_number',$task_number);
        $this->assign('ids',$ids);
        $this->display('ucenter/task');
    }

    /****21天打卡****/
    public function punch()
    { 
        $punch = 1;
        // 弹框展示
        $tan = I('tan',0);

        $student_id = I('student_id','0');
        $time = time();
        // $time = 1512921600;
        // 打卡的天数
        $day = M('students')->where(array('student_id'=>$student_id))->getField('punch_num');

        
        $punch_time = M('punch')->where(array('student_id'=>$student_id))->getField('punch_time');

        // 第一次打卡
        if(empty($punch_time))
        {
            $this->assign('day',0);
            $this->assign('punch',$punch);
            $this->display('ucenter/punch');
            die;
        }
        // 如果当前时间距离上次打卡判断，显示打卡详情

        /* $timestamp 上次打卡时间的零点*/
        $timestamp = mktime(0,0,0,date('m',$punch_time),date('d',$punch_time),date('Y',$punch_time));


        $nextday = $timestamp+24*60*60;
        if($time<$nextday)
        {
            // 打卡详情页面
        
            $punch = 0;
            
        }elseif($time>($nextday+24*60*60)){
        // 如果打卡时间有间隔,则清零计算(在21天之内)
            if($day >= 1 && $day<21)
            { 
               M('students')->where(array('student_id'=>$student_id))->setField('punch_num',0);
               M('punch')->where(array('student_id'=>$student_id))->delete();
               $day = 0;
            }
        }
        if($day >= 21)
        {
            $percent = 100;
        }else{
            $percent = round($day/21,2)*100;
        }
        $this->assign('percent',$percent);
        $this->assign('tan',$tan);
        $this->assign('day',$day);
        $this->assign('punch',$punch);
        $this->display('ucenter/punch');
    }

    public function punch_handle()
    {

        $data['image'] = I('imgimg1',0);
        // $data['image2'] = I('imgimg2',0);
        $data['content'] = I('post.content','');
        $data['punch_time'] = time();
        $data['student_id'] = I('student_id',0);
        $ret = M('punch')->where(array('student_id'=>$data['student_id']))->find();
        if(empty($ret))
        {
            M('punch')->where(array('student_id'=>$data['student_id']))->add($data);
         }else{
            M('punch')->where(array('student_id'=>$data['student_id']))->save($data);
         }
       
        M('students')->where(array('student_id'=>$data['student_id']))->setInc('punch_num');
        $day = M('students')->where(array('student_id'=>$data['student_id']))->getField('punch_num');
        // if($day==1 || $day==7 || $day)
        // 学生增加500金豆
        if($day == 21)
        {
            M('students')->where(array('student_id'=>$data['student_id']))->setInc('student_points',500);
        }
        // $this->success("恭喜您,打卡成功！",U('mobile.php/Ucenter/punch',array('student_id'=>$data['student_id'],'tan'=>1)));
        
        $this->success("恭喜您,打卡成功！",U('mobile.php/Ucenter/punchShare',array('student_id'=>$data['student_id'],'tan'=>1)));
    }
    public function punchShare()
    {
        $tan = I('tan',0);
        $student_id = I('student_id',0);
        $info = M('punch')->where(array('student_id'=>$student_id))->find();
        $student_name = M('students')->where(array('student_id'=>$student_id))->getField('student_name');
        $day = M('students')->where(array('student_id'=>$student_id))->getField('punch_num');
        $this->assign('tan',$tan);
        $this->assign('day',$day);
        $this->assign('student_name',$student_name);
        $this->assign('info',$info);
        if($day ==21){
            $this->display('ucenter/punchshare2');
        }else{
            $this->display('ucenter/punchshare1');
        }
       
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
        $user = session('user');
        $pid = $user['id'];
        $sid = M('students_parent')->where("parent_id = $pid")->getField('student_id');
        $ret = M('circulation')->where(array('circul_status'=>1,'student_id'=>$sid))->count();
        if($ret < 1){
            $this->error("您暂无借阅绘本，无法提交任务！",U('mobile.php/Ucenter/task',array('student_id'=>$sid)));
            exit;
        }
        $option_id = I('param.option_id','');
        if(!$option_id){
            $this->error('请选择任务答案');
        }
        //判断答题次数
        $task_num = M("students")->where(array('student_id'=>$sid))->getField("task_num");
        $points = 0;
        foreach($option_id as $k=>$v){
            $result = M('task_option')->where("task_id = $k and option_id = $v and correct_value = 1")->count();
            if(!$result){
                if($task_num < 1){
                    M("students")->where(array('student_id'=>$sid))->setInc("task_num",1);
                    $this->error("任务回答不正确,您还有一次机会",U('mobile.php/Ucenter/task',array('student_id'=>$sid)));
                }
            }else{
                $points_plus = M('task')->where("task_id = $k")->getField('task_award');
                $points += $points_plus;
            }
        }
        $content = I('post.content','');
        $image = I('post.pic','');
        foreach($option_id as $k=>$v){
            $data = [
                'task_id' => $k,
                'student_id' => $sid,
                'option_id' => $v,
                'task_time' => time(),
                'comment_time' => time(),
                'content' => $content,
                'image' => $image
            ];
            M('student_task')->add($data);
            //增加绘本录音试听
            $sql = "select * from fh_book_video where video not in (select video from fh_task_video where student_id = $sid) limit 1";
            $video = M()->query($sql);
            $video = $video[0]['video'];
            if($video){
                M('task_video')->add(['task_id'=>$k,'student_id'=>$sid,'video'=>$video]);
            }
        }
        M('students')->where(array('student_id'=>$sid))->setInc('student_points',$points);
        M('students')->where(array('student_id'=>$sid))->save(['task_number'=>['exp','task_number+1'],'task_num'=>0]);
        M('points_record')->add(array('user_id'=>$sid,'student_points'=>$points,'change_time'=>time(),'change_desc'=>'任务金豆','change_type'=>1,'user_flag'=>1));
        $this->success("任务完成,本次奖励金豆100",U('mobile.php/Ucenter/task',array('student_id'=>$sid)));
    }

    public function taskVideo()
    {
        $user = session('user');
        $pid = $user['id'];
        $sid = M('students_parent')->where("parent_id = $pid")->getField('student_id');
        $task_num = M('circulation c')->where(['c.student_id'=>$sid,'c.circul_status'=>1])->count();
        $task = M('circulation c')
            ->join('left join fh_task t on t.task_book_id = c.book_id')
            ->where(['c.student_id'=>$sid,'c.circul_status'=>1])
            ->field('t.*')
            ->select();
        if($task){
            foreach($task as $k => $v){
                $task_id[] = $v['task_id'];
            }
        }else{
            $task_id[] = 0;
        }


        $video = M('task_video')
            ->where(['student_id'=>$sid,'task_id'=>['in',$task_id]])
            ->order('id desc')
            ->limit($task_num)
            ->field('video')
            ->select();
        import("Org.Util.getid3.getid3",'','.php');
        $getID3 = new \getID3(); //实例化类
        foreach($video as $k=>$v){
            $isbn = substr($v['video'],0,-4);
            $temp = M('books')->where(['book_isbn'=>$isbn])->find();
            if(!$temp){
                $temp = M('books_bak')->where(['book_isbn'=>$isbn])->find();
            }
            $data[$k] = $temp;
            $thisFileInfo = $getID3->analyze('./Public/bookVideo/'.$v['video']);//分析文件
            $time = $thisFileInfo['playtime_seconds'];
            $time = round($time/60,2);
            $data[$k]['time'] = $time;
        }
        $this->assign('data',$data);
        $this->display('ucenter/taskVideo');
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

    //退出登录，删除openid
    public function signOut()
    {
        $parent_id = session('parent_id');
        $result = M('students_parent')->where("parent_id = $parent_id")->setField('wx_id','');
        $this->success('退出成功','/mobile.php/Oauth');
    }

    //修改密码
    public function editPwd()
    {
        if(IS_POST){
            $user = session('user');
            $id = $user['id'];
            $old_pwd = md5(I('post.old_pwd',0));
            $parent = M('students_parent');
            $pwd = $parent->where(['parent_id'=> $id])->getField('pwd');
            if($pwd != $old_pwd){
                $this->error('旧密码错误');
            }
            $new_pwd = md5(I('post.new_pwd',0));
            $result = $parent->where(['parent_id'=>$id])->setField('pwd',$new_pwd);
            if($result){
                $this->success('修改成功',U('Ucenter/index'));
            }else{
                $this->error('修改失败');
            }

        }else{
            $this->display('ucenter/editPwd');
        }
    }

    //ajax上传图片
    public function uploadPic(){
        $base64_string = $_POST['base64_string'];

        $savename = $this->uniqueXj().'.jpeg';//localResizeIMG压缩后的图片都是jpeg格式

        $savepath = 'Uploads/'.date('Y-m-d',time());
        if(!is_dir($savepath)){
            mkdir($savepath);
        }

        $image = $this->base64ToImg( $base64_string, $savepath.'/'.$savename );

        if($image){
            echo '{"status":1,"content":"上传成功","url":"'.$image.'"}';
        }else{
            echo '{"status":0,"content":"上传失败"}';
        }
    }
    private function base64ToImg($base64_string, $output_file){
        $ifp = fopen( $output_file, "wb" );
        fwrite( $ifp, base64_decode( $base64_string) );
        fclose( $ifp );
        return( $output_file );
    }
    //生成唯一名字
    private function uniqueXj(){
        $name = time().rand(1,10000);
        return md5($name);
    }

    public function readShare()
    {
        $ids = I('get.ids',0);
        $id_arr = explode(',',$ids);
        $id_arr = array_filter($id_arr);
        $books = M('task t')
            ->join('fh_books b on b.book_id = t.task_book_id')
            ->where(['t.task_id'=>['in',$id_arr]])
            ->field('b.*')
            ->select();
        $data = M('student_task')->where(['task_id'=>['in',$id_arr]])->find();
        $name = M('students')->where(['student_id'=>$data['student_id']])->getField('student_name');
        $this->assign('book1',$books[0]);
        $this->assign('book2',$books[1]);
        $this->assign('data',$data);
        $this->assign('name',$name);
        $this->display('ucenter/readShare');
    }

    // 收益
    public function earn($student_id)
    {  
         $gold = M('students')->where(array('student_id'=>$student_id))->getField('student_points');
         $this->assign('gold',$gold);
         $this->assign('user_id',$student_id);
         $this->display('ucenter/earn');
    }
    // 收益记录
    public function earn_log()
    {
        $user_id = I('user_id');
        $this->assign('user_id',$user_id);
        $list = M('points_record')->where("user_id = $user_id")->order('change_time desc')->limit(50)->select();
        // var_dump($list);die;
        $this->assign('list',$list);
        $this->display('ucenter/earn_log');
    }
}