<?php
namespace mobiles\Controller;
use Think\Controller;
class MessageController extends Controller {
	public function index($id){  //id = school_id
	
		$msg = M("message");
	
		$condition['recevier_id'] = $id;
		$condition['user_flag'] = 1;
	
		$info = $msg -> where($condition)
		-> field("FROM_UNIXTIME(sent_time,'%Y-%m-%d') sent_time,
					 		   title,
					 		   message")
						 		   -> select();
	
						 		   // 		var_dump($info);
						 		   $this->assign("data",$info);
	
						 		   $this-> display();
	}
}