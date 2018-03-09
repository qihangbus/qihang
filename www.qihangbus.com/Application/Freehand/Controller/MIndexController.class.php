<?php
namespace mobile\Controller;
use Think\Controller;
class MIndexController extends Controller {
	public function index(){  
		$admin = M("admins");
        $id = I('param.id','');


        $t = I('param.t',1);
		$condition['admin_id'] = $id;
		$info=$admin->where($condition)->find();
        $info['school_name'] = M('schools')->where(array('school_id'=>$info['school_id']))->getField('school_name');
        
        $list = M('grade')->where(array('school_id'=>$info['school_id']))->select();



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

        
        $cond['user_id'] = $id;
        $cond['user_flag'] = 4;
        $cond['add_time'] = array('EGT',strtotime(date('Y-m-d')." 00:00:00"));
        $signin = M('signin')->where($cond)->getField('id');
        $this->assign('signin',$signin);
        $signinnum = M('signin')->where(array('user_id'=>$id,'user_flag'=>4))->order('id desc')->getField('signin_num');
        if(empty($signinnum)) $signinnum = 1;
        $this->assign('signnum',$signinnum);

        $this->assign('list',$list);
        $this->assign('info' ,$info);

		$this->assign('id',$id);
        $this->assign('t',$t);  
		$this-> display('MIndex/index');
	}

    public function circul()
    {
        $admin = M("admins");
        $id = I('param.id','');
        $t = I('param.t',1);
        $condition['admin_id'] = $id;
        $info=$admin->where($condition)->find();
        $info['school_name'] = M('schools')->where(array('school_id'=>$info['school_id']))->getField('school_name');
        
        $list = M('grade')->where(array('school_id'=>$info['school_id']))->select();



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
        $this-> display('MIndex/circul');
    }

    public function ajax_time()
    {
        $str_date = I('param.str_date','');
        $gid = I('param.grade_id','');
	
		$grade_name = M("grade")->where(array('grade_id'=>$gid))->getField("grade_name");
	
		//查询老师
		$t_list = M('teacher')->where(array('grade_id'=>$gid))->select();
		for($i=0;$i<count($t_list);$i++){
			$message = '您所在'.$grade_name.'的图书轮换日期已调整为:'.$str_date.',请及时关注';

			$ret = M('message')->add(array('sender_id'=>0,'sender_name'=>'系统消息','receiver_id'=>$t_list[$i]['teacher_id'],'sent_time'=>time(),'message'=>$message,'user_flag'=>2));
			M('teacher')->where(array('teacher_id'=>$t_list[$i]['teacher_id']))->setInc('message_num',1);
		}
		
		
		
		//查询园长	
		$s_id = M("grade")->where(array('grade_id'=>$gid))->getField("school_id");
		
		
		$message = $grade_name.'的图书轮换日期已被图书管理员修改为:'.$str_date.',请及时关注';

		$ret = M('message')->add(array('sender_id'=>0,'sender_name'=>'系统消息','receiver_id'=>$s_id,'sent_time'=>time(),'message'=>$message,'user_flag'=>1));
		M('schools')->where(array('teacher_id'=>$s_id))->setInc('message_num',1);
		
        $ret = M('grade')->where(array('grade_id'=>$gid))->save(array('change_time'=>strtotime($str_date)));
        echo $ret;
    }

    public function avatar()
    {
        $user_id = I('get.user_id',0);
        $avatar = M('admins')->where(array('admin_id'=>$user_id))->getField('admin_avatar');
        $this->assign('user_id',$user_id);
        $this->assign('avatar',$avatar);
        $this->display('MIndex/avatar');
    }

    public function update_avatar()
    {
        $user_id = I('post.user_id',0);
        
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



        $ret = M('admins')->where(array('admin_id'=>$user_id))->save(array('admin_avatar'=>$avatar));
        if($ret){
            $this->success('修改头像成功',U('mobile.php/MIndex/avatar',array('user_id'=>$user_id)));
        }
    }

    public function grade()
    {
        $school_id = I('param.school_id','');
        $id = I('param.id','');
        $list = M('grade')->where(array('school_id'=>$school_id))->select();
        $this->assign('id',$id);  
        $this->assign('list',$list);
        $this->display('MIndex/grade');
    }

    public function getClass()
    {
        $grade_id = I('param.grade_id','');
        $id = I('param.id','');
        $list = M('class')->where(array('grade_id'=>$grade_id))->select();
        $this->assign('id',$id);  
        $this->assign('list',$list);
        $this->display('MIndex/class');
    }

    public function getList()
    {
        $id = I('param.id','');
        $class_id = I('param.class_id','');
        //$school_id = M('admins')->where(array('admin_id'=>$id))->getField('school_id');
        $list = M('schooltobook')->join("fh_books ON fh_books.book_id=fh_schooltobook.book_id")->where("fh_schooltobook.class_id=$class_id")->select();

        $this->assign('list',$list);
        $this->assign('id',$id);
        $this->display('MIndex/books'); 
    }

    public function classs()
    {
        $id = I('param.id','');

        $school_id = M('admins')->where(array('admin_id'=>$id))->getField('school_id');
        $list = M('class')->where(array('school_id'=>$school_id))->select();
        foreach ($list as $key => $value) {
            $list[$key]['grade_name'] = M('grade')->where(array('grade_id'=>$value['grade_id']))->getField('grade_name');
            $info = M('teacher')->where(array('class_id'=>$value['class_id']))->find();
            $list[$key]['teacher_avatar'] = $info['teacher_avatar'];
            $list[$key]['teacher_name'] = $info['teacher_name'];
        }
        $this->assign('list',$list);
        $this->assign('id',$id);
        $this->display('MIndex/class'); 
    }

    public function books()
    {
        $id = I('param.id','');
        $t = I('param.t',2);
        $this->assign('t',$t);
        $school_id = M('admins')->where(array('admin_id'=>$id))->getField('school_id');
        $list = M('schooltobook')->where("school_id=$school_id")->join("fh_books ON fh_books.book_id=fh_schooltobook.book_id")->field("count(class_id) as total_num,class_id")->group("class_id")->select();
        
        $total = 0;
        foreach ($list as $key => $value) {
            $total += $value['total_num'];
            $list[$key]['class_name'] = M('class')->where(array('class_id'=>$value['class_id']))->getField('class_name');
            $list[$key]['teacher_name'] = M('teacher')->where(array('class_id'=>$value['class_id']))->getField('teacher_name');
        }
        $this->assign('total',$total);
        $this->assign('list',$list);
        $this->assign('id',$id);

        $condition['admin_id'] = $id;
        $info=M("admins")->where($condition)->find();
        $this->assign('info' ,$info);

        $this->display('MIndex/books'); 
    }

    public function book_list()
    {
        $id = I('param.id','');
        $this->assign('id',$id);
		$t = I('param.t',2);
        $this->assign('t',$t);
        $cid = I("param.cid",'');
        $list = M('schooltobook')->where(array('class_id'=>$cid))->join("fh_books ON fh_books.book_id=fh_schooltobook.book_id")->select();
        $this->assign('list',$list);
        $this->display('MIndex/book_list'); 
    }
	
	public function info()
    {
        $book_id = I('param.id',0);
    	$user_id = I('param.user_id',0);
        $user_flag = I('param.user_flag',0);
    	$book_info = M('books')->where(array('book_id'=>$book_id))->find();
    	$this->assign('user_id',$user_id);
        $this->assign('user_flag',$user_flag);
    	$book_gallery = M('book_gallery')->where(array('book_id'=>$book_id))->select();
    	$this->assign('book_gallery',$book_gallery);
    	$this->assign('info',$book_info);
        $this->assign('url',U('mobile.php/SIndex/Index/',array('id'=>$user_id)));
    	$this->display('MIndex/book_info');
    }

    public function compen()
    {
        
        $t = I('get.t',3);
        $this->assign('t',$t);
		$id = I('get.id','');
        $school_id = M('admins')->where(array('admin_id'=>$id))->getField('school_id');

        if($school_id > 0){
			$list = M('compensate')->
			join("fh_books on fh_books.book_id=fh_compensate.book_id")->
			join("fh_students on fh_students.student_id=fh_compensate.student_id")->
			where("fh_compensate.school_id=$school_id and fh_compensate.compen_status<3")->select();
			
			$compen = array('1'=>'赔偿中','2'=>'已付款','3'=>'已入库');

			foreach ($list as $key => $value) {
				
				$list[$key]['replace_book_name'] = M("books")->where(array('book_id'=>$value['replace_book_id']))->getField("book_name");
				$list[$key]['status_name'] = $compen[$value['compen_status']];
			}
		}else{
			$list = array();
		}
        $this->assign('list',$list);
        $this->assign('id',$id);

        $condition['admin_id'] = $id;
        $info=M("admins")->where($condition)->find();
        $this->assign('info' ,$info);

        $this->display('MIndex/compensate');
    }
	
	public function storage()
	{
		$book_id = I('post.bid','');
        $student_id = I('post.sid','');
		$compen_id = I('post.cid','');
		
		//查询赔偿信息
		$info = M("compensate")->where(array('id'=>compen_id))->find();
		
		//更新对应的班级图书
		M("schooltobook`")->add(array('school_id'=>$info['school_id'],'grade_id'=>$info['grade_id'],'class_id'=>$info['class_id'],'book_id'=>$info['replace_book_id']));
		
		//删除老数据
		M("schooltobook`")->where(array('school_id'=>$info['school_id'],'grade_id'=>$info['grade_id'],'class_id'=>$info['class_id'],'book_id'=>$info['book_id']))->delete();
		
		//更新赔偿状态
		M("compensate")->save(array('compen_status'=>3));
	}

    public function push_msg()
    {
        $book_id = I('post.bid','');
        $student_id = I('post.sid','');

        $info = M('students')->where(array('student_id'=>$student_id))->find();
        $binfo = M('books')->where(array('book_id'=>$book_id))->find();

        $url = U('mobile.php/TBorrow/pay',array('student_id'=>$student_id,'book_id'=>$book_id));

        $message = '尊敬的'.$info["student_name"].'家长您好，该绘本《'.$binfo["book_name"].'》经检查发现有损坏情况，造成无法继续使用，请您及时归还（<a href="'.$url.'">点击采购</a>），以免影响您的正常借阅。';

        $ret = M('message')->add(array('sender_id'=>0,'sender_name'=>'系统消息','receiver_id'=>$student_id,'sent_time'=>time(),'message'=>$message,'user_flag'=>3));
        M('students')->where(array('student_id'=>$student_id))->setInc('message_num',1);
		M('compensate')->where(array('student_id'=>$student_id,'book_id'=>$book_id))->setInc('message_num',1);
        echo 99;
    }

    //用户中心设置
    public function setting()
    {
        $user_id = I('param.user_id',0);
        $this->assign('user_id',$user_id);
        $user_flag = I('param.user_flag',0);
        $this->assign('user_flag',$user_flag);
        $this->display('MIndex/setting');
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
        $userinfo = M('admins')->where(array('admin_id'=>$user_id))->find();
        $this->assign('userinfo',$userinfo);
        $this->assign('address_list',$address_list);
        $this->display('MIndex/address');
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

        $this->display('MIndex/address_info');
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

        $aid = M('admins')->where(array('admin_id'=>$user_id))->getField('address_id');
        $this->assign('aid',$aid);
        $this->display('MIndex/address_info');
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
            M('admins')->where(array('admin_id'=>$data['user_id']))->save(array('address_id'=>$address_id));
        }

        $this->success("$msg",U('mobile.php/MIndex/address',array('user_id'=>$data['user_id'],'user_flag'=>$data['user_flag'])));
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

	//获取消息列表
    public function get_message_list()
    {
        $user_id = I('param.user_id',0);
        $this->assign('user_id',$user_id);
        $user_flag = I('param.user_flag',0);
        $page = I('param.page',1);
        $message = M('message');

        //更新用户中心消息小红点
        M('admins')->where(array('admin_id'=>$user_id))->save(array('message_num'=>0));
        $userinfo = M('admins')->where(array('admin_id'=>$user_id))->find();
        //$userinfo['message_num'] = 0;
        session('userinfo',$userinfo);
        $count = $message->where(array('receiver_id'=>$user_id,'user_flag'=>4))->count();
        $page = new \Think\Page($count,15);
        $show = $page->show();
        $list = $message->where(array('receiver_id'=>$user_id,'user_flag'=>4))->order('message_id desc')->limit($page->firstRow.','.$page->listRows)->select();
        
        $lt = array();
        foreach ($list as $key => $value) {
            $value['sub_message'] = msubstr($value['message'],40,strlen($value['message'])-1);
            $value['message'] = msubstr($value['message'],0,40);
            $lt[date('Y-m-d',$value['sent_time'])]['lt'][] = $value;
        }  

        $this->assign('list',$lt);
        $this->assign('page',$show);
        $this->assign('userinfo',$userinfo);
        $this->display('MIndex/message_list');
    }

    public function send_message()
    {
        $user_id = I('param.user_id','');
        $type = I('param.type','');
        $receive_id = I('param.receive_id','');
        $this->assign('user_id',$user_id);
        $this->assign('receive_id',$receive_id);
        $this->assign('type',$type);
        $this->display('MIndex/send_message');
    }

    public function select_user()
    {
        $user_id = I('param.user_id','');
        $school_id = I('param.school_id','');
        $type = I('param.type','');
        if($type == 1){
            $list = M('teacher')->where(array('school_id'=>$school_id))->select();
            foreach ($list as $key => $value) {
                $list[$key]['user_id'] = $value['teacher_id'];
                $list[$key]['thumb'] = $value['teacher_avatar'];
                $list[$key]['user_name'] = $value['teacher_name'];
            }
        }elseif($type == 2){
            $list = M('students')->where(array('school_id'=>$school_id))->select();
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
        $this->display('MIndex/select_user');
    }

    public function message_handle()
    {
        $data = array();
        $data['sender_id'] = I('param.user_id','');
        $sender_name = M('admins')->where(array('admin_id'=>$data['sender_id']))->getField('admin_name');
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
            
        $this->success('发送成功',U('mobile.php/MIndex/index',array('id'=>$data['sender_id'])));
        
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
        M('admins')->where(array('admin_id'=>$data['user_id']))->setInc('rank_points',$data['user_points']);
        M('points_record')->add(array('user_id'=>$data['user_id'],'student_points'=>$data['user_points'],'change_time'=>time(),'change_desc'=>'签到金豆','change_type'=>1,'user_flag'=>4));
        if($ret){
            echo json_encode(array('code'=>1,'user_points'=>$data['user_points'],'msg'=>'签到成功'));
            exit;
        }
    }

}