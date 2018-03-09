<?php
namespace mobiles\Controller;
use Think\Controller;
class SIndexController extends Controller {
	public function index(){  //id = school_id

		$id = I('get.id','');
		
		
		if(strpos($id,'h') > 0)
		{
			$arr = explode(".",$id);
			$id = $arr[0];
		}
		
//		$test_arr = array("3","5","6");
//		//测试园数据
//		if(in_array($id,$test_arr)){
//			$id = 2;
//			echo $id;
//		}
	
		
		$School = M("schools");

		$condition['school_id'] = $id;
		$school_info = $School -> where($condition)
		-> find();

        if(count($school_info['teacher_avatar']) > 255){
            $school_info['teacher_avatar'] = base64_decode($school_info['teacher_avatar']);
        }

		$this->assign("data",$school_info);
		$school_info['student_id'] = $school_info['school_id'];
        $school_info['student_points'] = $school_info['rank_points'];

		$this->assign('userinfo' ,$school_info);
		$this->assign('id',$id);  

        $condition['user_id'] = $this->userinfo['student_id'];
        $condition['user_flag'] = 1;
        $condition['add_time'] = array('EGT',strtotime(date('Y-m-d')." 00:00:00"));
        
        $id = M('signin')->where($condition)->getField('id');
        $this->assign('signin',$id);
        $signinnum = M('signin')->where(array('user_id'=>$condition['user_id'],'user_flag'=>1))->order('id desc')->getField('signin_num');
        $this->assign('signnum',$signinnum);

		$this->display("SIndex/Index");
	}
	
	public function circul()
    {
        $schools = M("schools");
        $id = I('param.id','');
        $t = I('param.t',1);
        $list = M('grade')->where(array('school_id'=>$id))->select();
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
        $this-> display('SIndex/circul');
    }

    //获取用户等级
    public function get_levle_info()
    {
        $student_id = I('param.student_id',0);
        $this->assign('user_id',$student_id);
        $student_points = I('param.student_points',0);
        $student_rank = I('param.student_rank',0);
        if($student_rank < 1){
            $student_rank = 1;
        }
        $this->assign('student_points',$student_points);
        $this->assign('student_rank',$student_rank);
        $nextrank = $student_rank + 1;
        $this->assign('nextrank',$nextrank);
        //查询勋章列表
        $medal_list = M('medal')->select();

        //查询当前用户所获的勋章
        $student_medal = M('student_medal')->where(array('user_id'=>$student_id,'user_flag'=>1))->select();
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
        $this->display('SIndex/level_info');
    }

	//获取消息列表
    public function get_message_list()
    {
        $user_id = I('param.user_id',0);
        $this->assign('user_id',$user_id);
        $user_flag = I('param.user_flag',0);
        $this->assign('user_flag',$user_flag);
        $message = M('message');

        //更新用户中心消息小红点
        M('schools')->where(array('school_id'=>$user_id))->save(array('message_num'=>0));
        $userinfo = M('schools')->where(array('school_id'=>$user_id))->find();
        session('userinfo',$userinfo);
        $list = $message->where("(sender_id = $user_id and sender_name = '{$userinfo['school_teacher']}') or (receiver_id = $user_id and user_flag = 1)")->order('message_id desc')->limit(20)->select();
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
        $this->display('SIndex/message_list');
    }

    public function avatar()
    {
        $user_id = I('get.user_id',0);
        $user_flag = I('get.user_flag',0);
        $avatar = M('schools')->where(array('school_id'=>$user_id))->getField('teacher_avatar');
        $this->assign('user_id',$user_id);
        $this->assign('user_flag',$user_flag);
        $this->assign('avatar',$avatar);
        $this->display('SIndex/avatar');   
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

        $ret = M('schools')->where(array('school_id'=>$user_id))->save(array('teacher_avatar'=>$avatar));
        if($ret){
            $this->success('修改头像成功',U('mobile.php/SIndex/avatar',array('user_id'=>$user_id,'user_flag'=>$user_flag)));
        }
    }

	public function select_user()
    {
        $user_id = I('param.user_id','');
        $class_id = I('param.class_id','');
        $type = I('param.type','');
        if($type == 1){
            $list = M('teacher')->where(array('school_id'=>$user_id))->select();
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
            $list = M('students')->where(array('school_id'=>$user_id))->select();
            foreach ($list as $key => $value) {
                $list[$key]['user_id'] = $value['student_id'];
                $list[$key]['thumb'] = $value['student_avatar'];
                $list[$key]['user_name'] = $value['student_name'];
            }
        }
        $this->assign('list',$list);
		$this->assign('type',$type);
        $this->assign('user_flag',$type);
        $this->assign('user_id',$user_id);
        $this->assign('class_id',$class_id);
        $this->display('SIndex/select_user');
    }	
	
	public function send_message()
    {
        $user_id = I('param.user_id','');
        $type = I('param.type','');
        $receive_id = I('param.receive_id','');
        $this->assign('user_id',$user_id);
        $this->assign('receive_id',$receive_id);
        $this->assign('type',$type);
        $this->display('SIndex/send_message');
    }
	
	public function message_handle()
    {
        $data = array();
        $data['sender_id'] = I('param.user_id','');
        $sender_name = M('schools')->where(array('id'=>$data['sender_id']))->getField('school_teacher');
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
            
        $this->success('发送成功',U('mobile.php/SIndex/Index',array('id'=>$data['sender_id'])));
        
    }
	
    public function ajax_message()
    {
        $message_id = I('param.message_id','');
        $ret = M('message')->where(array('message_id'=>$message_id))->save(array('readed'=>1,'read_time'=>time()));
        echo $ret;
    }

    //用户中心设置
    public function setting()
    {
        $user_id = I('param.user_id',0);
        $this->assign('user_id',$user_id);
        $user_flag = I('param.user_flag',0);
        $this->assign('user_flag',$user_flag);
        $this->display('SIndex/setting');
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
        $userinfo = M('schools')->where(array('school_id'=>$user_id))->find();
        $this->assign('userinfo',$userinfo);
        $this->assign('address_list',$address_list);
        $this->display('SIndex/address');
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

        $this->display('SIndex/address_info');
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

        $this->display('SIndex/address_info');
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
            M('schools')->where(array('school_id'=>$data['user_id']))->save(array('address_id'=>$address_id));
        }

        
        $this->success("$msg",U('mobile.php/SIndex/address',array('user_id'=>$data['user_id'],'user_flag'=>$data['user_flag'])));
        
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
        M('schools')->where(array('school_id'=>$data['user_id']))->setInc('rank_points',$data['user_points']);
        M('points_record')->add(array('user_id'=>$data['user_id'],'student_points'=>$data['user_points'],'change_time'=>time(),'change_desc'=>'签到金豆','change_type'=>1,'user_flag'=>1));
        if($ret){
            echo json_encode(array('code'=>1,'user_points'=>$data['user_points'],'msg'=>'签到成功'));
            exit;
        }
    }

    //退出登录，删除openid
    public function signOut($user_id)
    {
        $result = M('schools')->where("school_id = $user_id")->setField('wx_id','');
        $this->success('退出成功','/mobile.php/Oauth');
    }

    //修改密码
    public function editPwd()
    {
        $user = session('user');
        $id = $user['id'];
        if(IS_POST){
            $old_pwd = md5(I('post.old_pwd',0));
            $school = M('schools');
            $pwd = $school->where(['school_id'=> $id])->getField('pwd');
            if($pwd != $old_pwd){
                $this->error('旧密码错误');
            }
            $new_pwd = md5(I('post.new_pwd',0));
            $result = $school->where(['school_id'=>$id])->setField('pwd',$new_pwd);
            if($result){
                $this->success('修改成功',U('SIndex/index',['id'=>$id]));
            }else{
                $this->error('修改失败');
            }

        }else{
            $this->assign('id',$id);
            $this->display('SIndex/editPwd');
        }
    }
}