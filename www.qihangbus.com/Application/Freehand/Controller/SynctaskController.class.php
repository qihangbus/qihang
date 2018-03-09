<?php
// 每周一和每周三更新常规任务数据
namespace Freehand\Controller;
use Think\Controller;
class SynctaskController extends Controller {
    public function index()
    { 
        //判断是否周一或者周四
		$week = date("w");

		//每周一和周四才可以执行程序
		if($week == 2 || $week == 5){
		
			//查询当前任务
			$ret = M('config')->where(array('id'=>24))->getField("value");
			//$str = explode(',',$ret);
			
			//查询标准任务后面的任务
			$arr_id = M('task')->where(array('task_id'=>array('gt',$ret),'task_type'=>1))->limit(1)->getField("task_id");
			
			//如果任务已经到最后一个，则从第一个重新开始
			if(empty($arr_id)){
				$arr_id = M('task')->where(array('task_type'=>1))->order("task_id")->limit(1)->getField("task_id");
			}

			//查询定制任务后面的任务
			/*$arrid = M('task')->where(array('task_id'=>array('gt',$str[1]),'task_type'=>2))->limit(1)->getField("task_id");
			
			if(empty($arrid)){
				$arrid = M('task')->where(array('task_type'=2))->order("task_id")->limit(1)->getField("task_id");
			}*/

			//更新任务数据
			$nid = '';
			
			//$nid = $arr_id.','.$arrid;

			$this->exportlog("task_log.txt",var_export($arr_id,true));
			
			$nid = $arr_id;
			if(!empty($nid) && $nid > 0){
				M("config")->where(array('id'=>24))->save(array('value'=>$nid));
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