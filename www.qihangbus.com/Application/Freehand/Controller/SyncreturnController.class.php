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
		
		//读取微信配置信息
		$res = M('config')->where(array('parent_id'=>array("in","13,99")))->field('code,value')->select();
    	$options = array();
    	$weixin_options = array('appid','appsecret','token','access_token','expire_in');
    	foreach ($res as $key => $value) {
    		if(in_array($value['code'], $weixin_options)){
    			$options[$value['code']] = $value['value'];
    		}
    	}
		
		//判断access_token是否过期，如果过期重新获取,并更新到数据库中
		if($options['expire_in']-7200<time()){
			$access_token = update_access_token($options);
			$t = time();
			M("config")->where(array("id"=>25))->save(array('value'=>$access_token));
			M("config")->where(array("id"=>26))->save(array('value'=>$t));
			$options['access_token'] = $access_token;
			$options['expire_in'] = $t;
		}
		
		//每周日和周三才可以执行程序
		if($week < 1 || $week == 3){
			
			//查询所有的班级
			$class_list = M('class')->where(array('school_id'=>array("in","1")))->field('class_id')->select();
			
			//循环班级
			for($i=0;$i<count($class_list);$i++)
			{
				//查询班级所有的学生
				$student_list = M("students")->where(array('class_id'=>$class_list[$i]['class_id']))->field("school_id,class_id,student_id")->select();
			
				//循环班级学生
				for($j=0;$j<count($student_list);$j++)
				{
					//判断当前学生是否有损坏未赔偿记录
					$exits = M("compensate")->where(array('student_id'=>$student_list[$j]['student_id'],'compen_status'=>array('lt',3)))->count();
					if($exits > 0) continue;
					
					//判断当前学生是否订购软件
					$oflag = M("students")->where(array('student_id'=>$student_list[$j]['student_id']))->getField("is_paid");
					if($oflag < 1) continue; 
					
					//判断当前学生是否借阅图书
					$borrows = M("circulation")->where(array('student_id'=>$student_list[$j]['student_id'],'circul_status'=>1))->count();
					if($borrows < 1) continue;
					
					//修改学生的消息数据
					M("message")->add(array('sender_id'=>0,'sender_name'=>'系统消息','receiver_id'=>$student_list[$j]['student_id'],'sent_time'=>time(),'message'=>$message,'user_flag'=>3));
					M('students')->where(array('student_id'=>$student_list[$j]['student_id']))->setInc('message_num',1);
					
					$this->exportlog("return_log.txt",var_export($student_list[$j]['student_id'],true));
					
					//推送微信消息，格式：
					//{"touser":"OPENID","template_id":"X6JXF6sFVQgUG3tyzqiOYrVuBs6zV1COPGEssDWMl8s","url":"http://#","topcolor":"#FF0000","data":{"User": {"value":"黄先生","color":"#173177}}			
					$data['touser'] = M("students_parent")->where(array('student_id'=>$student_list[$j]['student_id']))->getField("wx_id");
					//$data['touser'] = 'oKJMMwnaHDy16vfeYROjai3hhRMU';
					$data['template_id'] = 'X6JXF6sFVQgUG3tyzqiOYrVuBs6zV1COPGEssDWMl8s';
					$data['url'] = '';
					$data['topcolor'] = '#FF0000';
					$first['value'] = '亲，为了不影响宝宝的正常借阅，明天一早请记得把上次借阅的图书，让宝宝带回幼儿园呦！';
					$first['color'] = '#173177';
					$tmp['first'] = $first;
					$book_id = M("circulation")->where(array('school_id'=>$student_list[$j]['school_id'],'class_id'=>$student_list[$j]['class_id'],'student_id'=>$student_list[$j]['student_id'],'circul_status'=>1))->getField("book_id");
					$keyword1['value'] = M("books")->where(array('book_id'=>$book_id))->getField("book_name");
					$keyword1['color'] = '#173177';
					$tmp['keyword1'] = $keyword1;
					$keyword2['value'] = '明天';
					$keyword2['color'] = '#173177';
					$tmp['keyword2'] = $keyword2;
					$data['data'] = $tmp;
					$arr = sendTemplateMessage($options['access_token'],json_encode($data));
					//延迟1秒
					sleep(1);
				}
			}
		
		}
    }
	
	function exportlog($file,$txt)
	{
	   $fp =  fopen($file,'ab+');
	   fwrite($fp,'-----------'.date('Y-m-d H:i:s').'-----------------');
	   fwrite($fp,$txt);
	   fwrite($fp,"\r\n\r\n\r\n");
	   fclose($fp);
	}
	
}