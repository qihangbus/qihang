<?php
// 定时给未预定图书的学生分配图书
// 每天凌晨3点 判断用户是否预约
namespace Freehand\Controller;
use Think\Controller;
class SyncallocationController extends Controller {
    public function index()
    { 
        //执行定时程序前清除缓存
		//header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
		//header("Last-Modified: " . gmdate("D, d M Y H:i:s") . "GMT");
		//header("Cache-Control: no-cache, must-revalidate");
		//header("Pragma: no-cache");

		//设置脚本最大执行时间
		set_time_limit(0);
		
		//判断是否周一或者周四
		$week = date("w");

		//每周一和周四才可以执行程序
		if($week == 1 || $week == 4){
			
			//查询所有的班级
			$class_list = M('class')->where(array('school_id'=>1))->field('class_id')->select();
			

			//循环班级
			for($i=0;$i<count($class_list);$i++)
			{
				//查询班级所有的学生
				$student_list = M("students")->where(array('class_id'=>$class_list[$i]['class_id']))->field("school_id,class_id,student_id")->select();
			
				//查询班级所有的图书,并把图书ID存放到book_arr数组中
				$book_list = M("schooltobook")->where(array('class_id'=>$class_list[$i]['class_id']))->field("book_id")->select();
				$book_arr = array();
				
				
				foreach ($book_list as $key => $value) {
					//if($value['book_id'] == 70) continue;//临时用损坏管理
					array_push($book_arr,$value['book_id']);
				}

				
				
				//循环班级学生
				for($j=0;$j<count($student_list);$j++)
				{
					//判断学生是否预约过图书和未归还的图书,已经预约过的系统就不重新预约了
					$exist = M("circulation")->where(array('school_id'=>$student_list[$j]['school_id'],'class_id'=>$student_list[$j]['class_id'],'student_id'=>$student_list[$j]['student_id']))->count();
					if($exist > 0) continue;

					//判断当前学生是否有损坏未赔偿记录，如果有就不重新预约了	
					$compen = M("compensate")->where(array('student_id'=>$student_list[$j]['student_id'],'compen_status'=>1))->count();
					if($compen > 0) continue;
					
					//判断当前学生是否订购软件
					$oflag = M("students")->where(array('student_id'=>$student_list[$j]['student_id']))->getField("is_paid");
					if($oflag < 1) continue; 
					
					//查询出当前班级已经预约过的图书，从班级所有的图书中剔除出去
					$booksub = M("circulation")->where(array('school_id'=>$student_list[$j]['school_id'],'class_id'=>$student_list[$j]['class_id'],'circul_status'=>2))->field("book_id")->select();
					foreach ($booksub as $key => $value) {
						if(in_array($value['book_id'], $book_arr))
						{
							$key=array_search($value['book_id'], $book_arr);
							if($key!==false) array_splice($book_arr, $key, 1);
						}
					}

					//查询出学生已经读过的图书，从班级所有的图书中剔除出去
					$booked = M("circul_log")->where(array('school_id'=>$student_list[$j]['school_id'],'class_id'=>$student_list[$j]['class_id'],'student_id'=>$student_list[$j]['student_id']))->field("book_id")->select();
					foreach ($booked as $key => $value) {
						if(in_array($value['book_id'], $book_arr))
						{
							$key=array_search($value['book_id'], $book_arr);
							if($key!==false) array_splice($book_arr, $key, 1);
						}
					}

					//查询出当前班级损坏未入库的图书，从班级所有的图书中剔除出去
					$compensates = M("compensate")->where(array('class_id'=>$student_list[$j]['class_id'],'compen_status'=>array('lt',3)))->field("book_id")->select();
					foreach ($compensates as $key => $value) {
						if(in_array($value['book_id'], $book_arr))
						{
							$key=array_search($value['book_id'], $book_arr);
							if($key!==false) array_splice($book_arr, $key, 1);
						}
					}
					
					
					//在班级所有的图书中随机取一本图书
					//防止程序取不到图书ID,循环10次	
					for($x=0;$x<10;$x++){
						$rand_keys = array_rand($book_arr, 1);
						$new_book_id = $book_arr[$rand_keys];
						if(empty($new_book_id)){
							continue;
						}else{
							break;
						}
						echo $new_book_id;
					}
					
					$this->exportlog("allocation_log.txt",var_export($student_list[$j]['student_id'],true));
					
					//添加到预约表中
					M("circulation")->add(array('book_id'=>$new_book_id,'school_id'=>$student_list[$j]['school_id'],'class_id'=>$student_list[$j]['class_id'],'student_id'=>$student_list[$j]['student_id'],'circul_status'=>2,'add_time'=>time()));
					
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