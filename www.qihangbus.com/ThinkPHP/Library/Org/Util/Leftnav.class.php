<?php
namespace Org\Util;
class leftnav{
	static public function cznav($cate , $lefthtml = '— ' , $pid=0 , $lvl=0, $leftpin=0 ){
		$arr=array();
		foreach ($cate as $v){
			if($v['adminnav_leftid']==$pid){
				$v['lvl']=$lvl + 1;
				$v['leftpin']=$leftpin + 0;//左边距
				$v['lefthtml']=str_repeat($lefthtml,$lvl);
				$arr[]=$v;
				$arr= array_merge($arr,self::cznav($cate,$lefthtml,$v['adminnav_id'],$lvl+1 , $leftpin+20));
			}
		}
		return $arr;
	}


	static public function rule($cate , $lefthtml = '— ' , $pid=0 , $lvl=0, $leftpin=0 ){
		$arr=array();
		foreach ($cate as $v){
			if($v['pid']==$pid){
				$v['lvl']=$lvl + 1;
				$v['leftpin']=$leftpin + 0;//左边距
				$v['lefthtml']=str_repeat($lefthtml,$lvl);
				$arr[]=$v;
				$arr= array_merge($arr,self::rule($cate,$lefthtml,$v['id'],$lvl+1 , $leftpin+20));
			}
		}
		return $arr;
	}




	
	static public function column($cate , $lefthtml = '— ' , $pid=0 , $lvl=0, $leftpin=0 ){
		$arr=array();
		foreach ($cate as $v){
			if($v['column_leftid']==$pid){
				$v['lvl']=$lvl + 1;
				$v['leftpin']=$leftpin + 0;//左边距
				$v['lefthtml']=str_repeat($lefthtml,$lvl);
				$arr[]=$v;
				$arr= array_merge($arr,self::column($cate,$lefthtml,$v['id'],$lvl+1 , $leftpin+20));
			}
		}
		return $arr;
	}
	
	
}


?>