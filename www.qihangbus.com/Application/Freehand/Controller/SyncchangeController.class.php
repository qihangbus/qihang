<?php
// 更换图书
namespace Freehand\Controller;
use Think\Controller;
class SyncchangeController extends Controller {
    public function index()
    {
		exit();
        //执行定时程序前清除缓存
		header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
		header("Last-Modified: " . gmdate("D, d M Y H:i:s") . "GMT");
		header("Cache-Control: no-cache, must-revalidate");
		header("Pragma: no-cache");
		
		//设置脚本最大执行时间
		set_time_limit(0);
		
		//查询所有符合更换图书年级信息
		$starttime = strtotime(date("Y-m-d"));
		$endtime = $starttime+86400;
		//$list = M('grade')->where(array('change_time'=>array("between",array("$starttime","$endtime"))))->field("grade_id,school_id,change_time")->select();
		
		$list = M('grade')->where(array('grade_id'=>array("in","1,2")))->field("grade_id,school_id,change_time")->select();
		
		//迁移图书到备份表中
		foreach($list as $key=>$value)
		{
			//临时屏蔽中班数据
			if($value['grade_id'] == 2) continue;
			
			$book = M("schooltobook")->where(array('grade_id'=>$value['grade_id']))->select();
			for($j=0;$j<count($book);$j++){
				M("schooltobook_temp")->add(array("school_id"=>$book[$j]['school_id'],'class_id'=>$book[$j]['class_id'],'grade_id'=>$book[$j]['grade_id'],'book_id'=>$book[$j]['book_id']));
			}
			
			//删除当前年级的图书
			M("schooltobook")->where(array('grade_id'=>$value['grade_id']))->delete();
		}
		
		//循环更换图书
		foreach($list as $k=>$v)
		{
			//查询班级信息
			$classlist = M("class")->where(array('grade_id'=>$v['grade_id']))->order("class_id asc")->select();
			
			for($i=0;$i<count($classlist);$i++)
			{
				//临时屏蔽中班数据
				if($classlist[$i]['grade_id'] == 2) continue;
				
				$next_class_id = 0;
				$books = '';
				//查询出当前班级所有的图书
				$books = M("schooltobook_temp")->where(array('class_id'=>$classlist[$i]['class_id']))->select();
				//插入到下一个班级
				if($i < count($classlist)){
					$next_class_id = $classlist[$i+1]['class_id'];					
				}
				
				if(empty($next_class_id)){
					$next_class_id = $classlist[0]['class_id'];
				}

				
				for($j=0;$j<count($books);$j++){
					M("schooltobook")->add(array("school_id"=>$books[$j]['school_id'],'class_id'=>$next_class_id,'grade_id'=>$books[$j]['grade_id'],'book_id'=>$books[$j]['book_id']));
					echo M("schooltobook")->getlastsql()."<br>";
				}
				//删除当前班级的图书
				//M("schooltobook_temp")->where(array('class_id'=>$classlist[$i]['class_id']))->delete();
			}
			//延迟1秒
			sleep(1);
		}
    }
}