<?php
namespace community\Controller;
use Think\Controller;
class ForumController extends CommonController {
	//加载更多
	public function ajax_more()
	{
		$page_size = I('get.page_size',5);
		$cate = I("get.cate_id",1);//默认取启航要闻
		$pages = I('get.p',2);
		$count = M("forum")->where(array('school_id'=>$cate))->count();
        $page = new \Think\Page($count,$page_size);
        $show = $page->diyshow();
		
		$news = M("forum")->where(array('school_id'=>$cate))->order("add_time desc")->page($pages.",$page_size")->select();
		$list = array();
		foreach($news as $key=>$value)
		{
			$temp['link'] = U('sns.php/Forum/bbs_info',array('id'=>$value['id']));
			$temp['pic'] = empty($value['thumb']) ? '/Public/images/mobiles/default.png' : $value['thumb'];
			$temp['title'] = $value['title'];
			$temp['description'] = $value['description'];
			$temp['time_formated'] = date('Y-m-d H:i:s',$value['add_time']);
			$list[] = $temp;
		}
		echo json_encode(array('lists'=>$list));
	}
	
	//学校论坛首页
	public function bbs()
	{
		$user = session('user');
		switch($user['type']){
			case 1:	//家长
				$data = M('students_parent')->where("parent_id = {$user['id']}")->find();
				$thumb = $data['parent_avatar'];
				$school_id = M('students')->where("student_id = {$data['student_id']}")->getField('school_id');
				$index_url = "/mobile.php/Ucenter/index";
				break;
			case 2:	//教师
				$data = M('teacher')->where(['teacher_id'=>$user['id']])->find();
				$thumb = $data['teacher_avatar'];
				$school_id = $data['school_id'];
				$index_url = "/mobile.php/TIndex/index/teacher_id/{$user['id']}";
				break;
			case 3:	//园长
				$data = M('schools')->where(['school_id'=>$user['id']])->find();
				$thumb = $data['teacher_avatar'];
				$school_id = $data['school_id'];
				$index_url = "/mobile.php/SIndex/index/id/{$user['id']}";
				break;
			default:
				$data = M('admins')->where(array('admin_id'=>$user['id']))->find();
				$thumb = $data['admin_avatar'];
				$school_id = $data['school_id'];
				$index_url = "/mobile.php/MIndex/index/id/{$user['id']}";
		}

		$this->assign('school_id',$school_id);
		$this->assign('user_id',$user['id']);
		$this->assign('user_flag',$user['type']);
		$this->assign('index_url',$index_url);

		$user['school_id'] = $school_id;
		session('user',$user);
		$this->assign('thumb',$thumb);

		$list = M("forum")->where(array('school_id'=>$school_id,'audit_status'=>1))->order("add_time desc")->limit(15)->select();
		$this->assign('num',count($list));
		foreach($list as $key=>$value)
		{
			if($value['user_flag'] == 1){
				$list[$key]['thumb'] = M("students_parent")->where(array('parent_id'=>$value['user_id']))->getField("parent_avatar");
				$list[$key]['name'] = M("students_parent")->where(array('parent_id'=>$value['user_id']))->getField("parent_name");
			}elseif($value['user_flag'] == 2){
				$list[$key]['thumb'] = M("teacher")->where(array('teacher_id'=>$value['user_id']))->getField("teacher_avatar");
				$list[$key]['name'] = M("teacher")->where(array('teacher_id'=>$value['user_id']))->getField("teacher_name");
			}elseif($value['user_flag'] == 3){
				$list[$key]['thumb'] = M("schools")->where(array('school_id'=>$value['user_id']))->getField("teacher_avatar");
				$list[$key]['name'] = M("schools")->where(array('school_id'=>$value['user_id']))->getField("school_leader");
			}elseif($value['user_flag'] == 4){
				$list[$key]['thumb'] = M("admins")->where(array('admin_id'=>$value['user_id']))->getField("admin_avatar");
				$list[$key]['name'] = M("admins")->where(array('admin_id'=>$value['user_id']))->getField("admin_name");
			}else{
				$list[$key]['thumb'] = '';
				$list[$key]['name'] = '启航巴士';
			}
			$list[$key]['pj_num'] = M('forum_comment')->where(['forum_id'=>$value['forum_id']])->count();
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
		$this->display("forum/bbs");
	}
	
	//详情
	public function bbs_info()
	{
		$id = I("get.id","");
		$school_id = I('get.school_id','');
		$this->assign('school_id',$school_id);
		$user_id = I('get.user_id','');
		$this->assign('user_id',$user_id);
		$user_flag = I('get.user_flag','');
		$this->assign('user_flag',$user_flag);
		$fake_id = I('get.fake_id','');
		$this->assign('fake_id',$fake_id);
		
		$sort = I('get.sort','desc');
		//更新点击次数
		M('forum')->where(array('forum_id'=>$id))->setInc('hit',1);
		$comments = M("forum_comment")
			->where(['forum_id'=>$id])
			->order("add_time $sort")
			->select();
		foreach($comments as $v){
			if($v['user_flag'] == 1){
				$join = 'left join fh_schools s on s.school_id = fc.user_id';
				$field = 's.school_leader as name,s.teacher_avatar as avatar';
			}elseif($v['user_flag'] == 2){
				$join = 'left join fh_teacher t on t.teacher_id = fc.user_id';
				$field = 't.teacher_name as name,t.teacher_avatar as avatar';
			}elseif($v['user_flag'] == 3){
				$join = 'left join fh_students_parent p on p.student_id = fc.user_id';
				$field = 'p.parent_name as name,p.parent_avatar as avatar';
			}elseif($v['user_flag'] == 4){
				$join = 'left join fh_admins a on a.admin_id = fc.user_id';
				$field = 'a.admin_name as name,a.admin_avatar as avatar';
			}else{
				$join = '';
				$field = 0;
			}
			$data[] = M('forum_comment fc')
				->join($join)
				->where(['fc_id'=>$v['fc_id']])
				->field("fc.*,$field")
				->find();
		}
//		dump($comments);
//		die;
		$this->assign('comments',$data);
		$num = count($comments);
		$this->assign('num',$num);
		$info = M("forum")->where(array('forum_id'=>$id))->find();
		if($info['user_flag'] == 1){
			$join = 'left join fh_students_parent p on p.parent_id = f.user_id';
			$field = 'p.parent_avatar as avatar,p.parent_name as name';
		}elseif($info['user_flag'] == 2){
			$join = 'left join fh_teacher t on t.teacher_id = f.user_id';
			$field = 't.teacher_avatar as avatar,t.teacher_name as name';
		}elseif($info['user_flag'] == 3){
			$join = 'left join fh_schools s on s.school_id = f.user_id';
			$field = 's.teacher_avatar as avatar,s.school_leader as name';
		}elseif($info['user_flag'] == 4){
			$join = 'left join fh_admins a on a.admin_id = f.user_id';
			$field = 'a.admin_avatar as avatar,a.admin_name as name';
		}else{
			$join = '';
			$field = 0;
			}
		$info = M('forum f')
			->join($join)
			->where(['forum_id'=>$id])
			->field("f.*,$field")
			->find();
		$info['content']= htmlspecialchars_decode($info['content']);
		$this->assign('info',$info);
		$list = M("forum_pic")->where(array('forum_id'=>$id))->select();
		$this->assign('list',$list);
		
		$this->display("forum/bbs_info");
	}
	
	//异步更新赞
	public function ajax_zan()
	{
		$id = I("get.forum_id","");
		//更新点击次数
		M('forum')->where(array('forum_id'=>$id))->setInc('zan',1);
		echo 1;
	}
	
	//发帖子
	public function send_forum()
	{
		$school_id = I('get.school_id','');
		$this->assign('school_id',$school_id);
		$user_id = I('get.user_id','');
		$this->assign('user_id',$user_id);
		$user_flag = I('get.user_flag','');
		$this->assign('user_flag',$user_flag);
		$fake_id = I('get.fake_id','');
		$this->assign('fake_id',$fake_id);
		
		$this->display("forum/send_forum");
	}
	
	public function edit_handle()
	{
		$edit_arr = array();
		$edit_arr['school_id'] = I("post.school_id","");
		$edit_arr['user_id'] = I("post.user_id","");
		$edit_arr['user_flag'] = I("post.user_flag","");
		$edit_arr['title'] = I("post.title","");
		$edit_arr['add_time'] = time();
		$content = I("post.content","");
		$edit_arr['description'] = substr($content, 0, 200);
		$edit_arr['content'] = $content;
		$edit_arr['audit_status'] = 1;
		
		$bid = M("forum")->add($edit_arr);
		$fid = I("post.fp_id","");
		$arr = explode(",",$fid);
		for($i=0;$i<count($arr);$i++)
		{
			M("forum_pic")->where(array('fp_id'=>$arr[$i]))->save(array('forum_id'=>$bid));
		}
		
		$this->success("发送成功",U('sns.php/Forum/bbs',array('school_id'=>$edit_arr['school_id'],'user_id'=>$edit_arr['user_id'],'user_flag'=>$edit_arr['user_flag'],'fake_id'=>$edit_arr['fake_id'])));
	}

	//显示评论界面
	public function show_comments()
	{
		$id = I('get.id','');
		$this->assign('forum_id',$id);
		$school_id = I('get.school_id','');
		$this->assign('school_id',$school_id);
		$user_id = I('get.user_id','');
		$this->assign('user_id',$user_id);
		$user_flag = I('get.user_flag','');
		$this->assign('user_flag',$user_flag);
		$fake_id = I('get.fake_id','');
		$this->assign('fake_id',$fake_id);
		
		$userflag = M("forum")->where(array('forum_id'=>$id))->getField("user_flag");
		$userid = M("forum")->where(array('forum_id'=>$id))->getField("user_id");
		
		if($userflag == 1){
			$title = M("schools")->where(array('school_id'=>$userid))->getField('school_teacher');
		}elseif($userflag == 2){
			$title = M("teacher")->where(array('teacher_id'=>$userid))->getField('teacher_name');
		}elseif($userflag == 3){
			$title = M("students_parent")->where(array('student_id'=>$userid))->getField('parent_name');
		}elseif($userflag == 4){
			$title = M("admins")->where(array('admin_id'=>$userid))->getField('admin_name');
		}

		$school_name = M('schools')->where(['school_id'=>$school_id])->getField('school_name');
		$this->assign('school_name',$school_name);
		$this->assign('title',$title);
		$this->display("forum/show_comments");
	}
	
	public function upload_img()
	{
		$upload = new \Think\Upload();
        $upload->maxSize = 31457280;
        $upload->exts = array('jpg', 'gif', 'png', 'jpeg');
        $upload->saveName = 'time';
        $info = $upload->upload();       
		
		$fp_id = '';
		for($i=0;$i<count($info);$i++){
			$filename = "Uploads/".$info[$i]['savepath'].$info[$i]['savename'];
			$fpid = M('forum_pic')->add(array('fp_pic'=>$filename));
			if(!empty($fpid)){
				$fp_id .= $fpid.',';
			}
		}
		
		echo rtrim($fp_id,",");
	}
	
	public function comments_handle()
	{
		$edit_arr = array();
		$school_id = I("post.school_id");
		$edit_arr['forum_id'] = I("post.forum_id","");
		$edit_arr['user_id'] = I("post.user_id","");
		$edit_arr['user_flag'] = I("post.user_flag","");
		$edit_arr['add_time'] = time();
		$edit_arr['content'] = I("post.content","");
		M("forum_comment")->add($edit_arr);
		$this->success("发送成功",U('sns.php/Forum/bbs_info',array('id'=>$edit_arr['forum_id'],'school_id'=>$school_id,'user_id'=>$edit_arr['user_id'],'user_flag'=>$edit_arr['user_flag'],'fake_id'=>$edit_arr['fake_id'])));
	}
	
	public function forum_del()
	{
		$forum_id = I("get.forum_id","");
		
		M('forum')->where(array('forum_id'=>$forum_id))->delete();
		M('forum_comment')->where(array('forum_id'=>$forum_id))->delete();
		M('forum_pic')->where(array('forum_id'=>$forum_id))->delete();
		echo 1;
	}

}