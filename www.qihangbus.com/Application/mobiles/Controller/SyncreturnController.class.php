<?php
// 定时给学生推送
// 每天凌晨3点30分 发送提醒消息
namespace Freehand\Controller;
use Think\Controller;
class SyncreturnController extends Controller {
    public function index()
    { 
        //执行定时程序前清除缓存
		header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
		header("Last-Modified: " . gmdate("D, d M Y H:i:s") . "GMT");
		header("Cache-Control: no-cache, must-revalidate");
		header("Pragma: no-cache");

		//设置脚本最大执行时间
		set_time_limit(0);
		
		//判断是否周一或者周四
		$week = date("w");

		$message = '亲，为了不影响宝宝的正常借阅，明天一早请记得把上次借阅的图书让宝宝带回幼儿园呦！';
		
		//每周日和周三才可以执行程序
		if($week < 1 || $week == 3){
			
			//查询所有的班级
			$class_list = M('class')->where(array('school_id'=>2))->field('class_id')->select();
			
			//循环班级
			for($i=0;$i<count($class_list);$i++)
			{
				//查询班级所有的学生
				$student_list = M("students")->where(array('class_id'=>$class_list[$i]['class_id']))->field("school_id,class_id,student_id")->select();
			
				//循环班级学生
				for($j=0;$j<count($student_list);$j++)
				{
					//判断当前学生是否有损坏未赔偿记录
					$exits = M("compensate")->where(array('student_id'=>$student_list[$j]['student_id'],'compen_status'=>1))->count();
					if($exits > 0) continue;
					
					//添加到预约表中
					M("message")->add(array('sender_id'=>0,'sender_name'=>'系统消息','receiver_id'=>$student_list[$j]['student_id'],'sent_time'=>time(),'message'=>$message,'user_flag'=>3));
					M('students')->where(array('student_id'=>$student_list[$j]['student_id']))->setInc('message_num',1);
					//延迟1秒
					sleep(1);
					
				}
			}
		
		}
    }
}