<?php

namespace mobiles\Controller;

use Think\Controller;

class TestController extends Controller {
	public function index() { 
			
		$this->display();
	}
	
	public function trans(){
		$info = M("students");
		$query_sql = "select COUNT(*) as count FROM fh_students where school_id =1 GROUP BY class_id";
		$data = $info -> query($query_sql);
		
		$val = json_encode($data);
		
		echo $val;
	}
	
	public function charts(){
		
//		$info = M("students");
//		$query_sql = "select COUNT(*) as count,class_name as name FROM fh_students where school_id =1 GROUP BY class_id";
//		$data = $info -> query($query_sql);
//		
//		$arr = array();
//		$arr2 = array();
//		foreach ($data as $key => $value) {
//			//$arr[] = $value['name'];
//			array_push($arr, $value['name']);
//			array_push($arr2, $value['count']);
//			//$arr2[] = $value['count'];
//			//$arr .= $value['name'].",";
//			//$arr2 .= $value['count'].",";
//		}
//
//		var_dump($this->array_php2js($arr,'menus'));
//
//		$this->assign("name",json_encode($arr));
//		$this->assign("value",json_encode($arr2));
		$this->display();
	}

	public function ajax_charts(){
		
		$info = M("students");
		$query_sql = "select COUNT(*) as count,class_name as name FROM fh_students where school_id =1 GROUP BY class_id";
		$data = $info -> query($query_sql);
		
		$arr = array();
		$arr2 = array();
		foreach ($data as $key => $value) {
			//$arr[] = $value['name'];
			array_push($arr, $value['name']);
			array_push($arr2, $value['count']);
			//$arr2[] = $value['count'];
			//$arr .= $value['name'].",";
			//$arr2 .= $value['count'].",";
		}
		echo json_encode(array('name'=>$arr,'value'=>$arr2));
	}
	
	public function test(){
		
		$info = D("students");
		$condition['school_id'] = 1;
		$data1 = $info -> field("count(*) as count,class_name as name")
					   -> where($condition)
					   -> group("class_id")
					   -> select();
		
		var_dump($data1);
		
		$res = '';
		foreach($data1 as $v){
			$res .= $v;
		}
		
		var_dump($res);
	
// 		vendor('Jpgraph.Chart');
// 		$chart = new \Chart;
// 		$title = "��״ͼ"; //����
// 		$data = $data1[0]; //���
// 		$size = 140; //�ߴ�
// 		$width = 750; //���
// 		$height = 350; //�߶�
// 		$legend = $data1[1];//˵��
// 		$chart->createcolumnar($title,$data,$size,$height,$width,$legend);
	}
}