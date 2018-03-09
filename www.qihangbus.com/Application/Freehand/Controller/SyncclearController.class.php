<?php
//每天00:00:00软件订购时间清除
//1,2,3,4,5,6,7,8,9,10,11,12
namespace Freehand\Controller;
use Think\Controller;
class SyncclearController extends Controller {
    public function index()
    { 
        //查询所有订购软件的学生
        $list = M("students")->where(array('paid_num'=>array('gt',0)))->select();
		
		$day = date("j");    	
        $month = date("m");
        $year = date("Y");
        $last_day = date('j',mktime(0,0,1,($month==12?1:$month+1),1,($month==12?$year+1:$year))-24*3600);

    	foreach ($list as $key => $value) {
    		$nday = date("d",$value['paid_time']);
    		$nmonth = date("m",$value['paid_time']);
    		$nyear = date("Y",$value['paid_time']);
    		//如果购买月份相加不超过12个月，则年份相等，天数等于月末，月份等于订购时间的月份加上订购月份
    		//如果购买月份相加超过12个月，则年份加1年，天数等于月末，月份等于订购时间的月份加上订购月份减去12

    		if((($nmonth+$value['paid_num'] <= 12) && ($nyear == $year && ($nmonth+$value['paid_num']) == $month && $day == $last_day)) || 
    		   (($nmonth+$value['paid_num'] > 12) && ($nyear+1 == $year && ($nmonth+$value['paid_num']-12) == $month && $day == $last_day))){
    			M("students")->where(array('student_id'=>$value['student_id']))->save(array('is_paid'=>0,'paid_time'=>0,'paid_num'=>0));
    		}

    		
    	}
    }
}