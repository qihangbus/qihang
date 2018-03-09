<?php
namespace community\Controller;
use Think\Controller;
class IndexController extends Controller {
    //社区首页
	public function index()
	{
		$fake_id = I('get.fake_id','');
		
		$this->assign('fake_id',$fake_id);
		
		if(!empty($fake_id)){
			$school_id = 2;
		}else{
			//验证是否是学生家长
			$parentinfo = M('students_parent')->where(array('wx_id'=>$fake_id))->find();
			if(!empty($parentinfo)){
				$info = M('students')->where(array('student_id'=>$parentinfo['student_id']))->find();
				$school_id = $info['school_id'];
				$user_id = $info['student_id'];
				$user_flag = 3;
			}
			
			//验证是否是教师
			$teacherinfo = M('teacher')->where(array('wx_id'=>$fake_id))->find();
			if(!empty($teacherinfo)){
				$school_id = $teacherinfo['school_id'];
				$user_id = $teacherinfo['teacher_id'];
				$user_flag = 2;
			}
			
			//验证是否是园长
			$schoolinfo = M('schools')->where(array('wx_id'=>$fake_id))->find();
			if(!empty($schoolinfo)){
				$school_id = $schoolinfo['school_id'];
				$user_id = $schoolinfo['school_id'];
				$user_flag = 1;
			}
			
			//验证手机号是否存在于图书管理员表中
			$adminsinfo = M('admins')->where(array('wx_id'=>$fake_id))->find();
			if(!empty($adminsinfo)){
				$school_id = $adminsinfo['school_id'];
				$user_id = $adminsinfo['admin_id'];
				$user_flag = 4;
			}
		}
		
		$school_name = M('schools')->where(array('school_id'=>$school_id))->getField("school_name");
		
		$this->assign('school_id',$school_id);
		$this->assign('school_name',$school_name);
		$this->assign('user_flag',$user_flag);
		$cate = I("get.cate_id",1);//默认取启航要闻
		$list = M("news")->where(array('category'=>$cate))->order("is_top desc,add_time desc")->limit(5)->select();
		$this->assign('list',$list);
		$this->assign('cate',$cate);
		$this->display("sns/index");
	}
	
	//加载更多
	public function ajax_more()
	{
		$page_size = I('get.page_size',5);
		$cate = I("get.cate_id",1);//默认取启航要闻
		$pages = I('get.p',2);
		$count = M("news")->where(array('category'=>$cate))->count();
        $page = new \Think\Page($count,$page_size);
        $show = $page->diyshow();
		
		$news = M("news")->where(array('category'=>$cate))->order("add_time desc")->page($pages.",$page_size")->select();
//echo M("news")->getlastsql();
		$list = array();
		foreach($news as $key=>$value)
		{
			$temp['link'] = U('sns.php/Index/info',array('id'=>$value['id']));
			$temp['pic'] = empty($value['thumb']) ? '/Public/images/mobiles/default.png' : $value['thumb'];
			$temp['title'] = $value['title'];
			$temp['description'] = $value['description'];
			$temp['time_formated'] = date('Y-m-d H:i:s',$value['add_time']);
			$list[] = $temp;
		}
		echo json_encode(array('lists'=>$list));
	}
	
	//详情
	public function info()
	{
		$cate = I("get.cate_id",1);//默认取启航要闻
		$id = I('get.id','');
		$cate_arr = array(1=>'启航要闻',2=>'启航社区',3=>'专家讲坛',4=>'园所风采');
		$info = M("news")->where(array('id'=>$id))->find();
		$info['cate_name'] = $cate_arr[$info['category']];
		$this->assign('info',$info);
		$this->assign('cate',$cate);
		$this->display("sns/info");
	}
	
	//学校论坛首页
	public function bbs()
	{
		$school_id = I('get.school_id','');
		$user_id = I('get.user_id','');
		$user_flag = I('get.user_flag','');
		$fake_id = I('get.fake_id','');
		$user_id = '';
		$user_flag = '';
		if(empty($user_id) && empty($user_flag))
		{
			$this->error("请先加入启航巴士幼儿亲子读书计划",U('sns.php/Index/index',array('fake_id'=>$fake_id)));
			exit;
		}
		
		if($user_flag == 1){
			$thumb = M("schools")->where(array('school_id'=>$value['user_id']))->getField("teacher_avatar");
		}elseif($user_flag == 2){
			$thumb = M("teacher")->where(array('teacher_id'=>$value['user_id']))->getField("teacher_avatar");
		}elseif($user_flag == 3){
			$thumb = M("students_parent")->where(array('student_id'=>$value['user_id']))->getField("parent_avatar");
		}elseif($user_flag == 4){
			$thumb = M("admins")->where(array('admin_id'=>$value['user_id']))->getField("admin_avatar");
		}
		
		$this->assign('thumb',$thumb);
		
		$list = M("forum")->where(array('school_id'=>$school_id,'audit_status'=>1))->order("add_time desc")->limit(5)->select();
		$this->assign('num',count($list));
		foreach($list as $key=>$value)
		{
			if($value['user_flag'] == 1){
				$list[$key]['thumb'] = M("schools")->where(array('school_id'=>$value['user_id']))->getField("teacher_avatar");
			}elseif($value['user_flag'] == 2){
				$list[$key]['thumb'] = M("teacher")->where(array('teacher_id'=>$value['user_id']))->getField("teacher_avatar");
			}elseif($value['user_flag'] == 3){
				$list[$key]['thumb'] = M("students_parent")->where(array('student_id'=>$value['user_id']))->getField("parent_avatar");
			}elseif($value['user_flag'] == 4){
				$list[$key]['thumb'] = M("admins")->where(array('admin_id'=>$value['user_id']))->getField("admin_avatar");
			}else{
				$list[$key]['thumb'] = '';
			}
		}
		$this->assign('list',$list);
		
		$school_name = M("schools")->where(array('school_id'=>$school_id))->getField('school_name');
		$this->assign('school_name',$school_name);
		$member_num = M("students")->where(array('school_id'=>$school_id,'is_paid'=>array('gt',0)))->count();
		$this->assign('member_num',$member_num);
		
		$hits = M("forum")->where(array('school_id'=>$school_id))->field("hit")->select();
		$hit_num = 0;
		foreach($hits as $k=>$v)
		{
			$hit_num += $v['hit'];
		}
		$this->assign('hit_num',$hit_num);
		$this->display("sns/bbs");
	}
	
	//详情
	public function bbs_info()
	{
		$id = I("get.id","");
	}
}