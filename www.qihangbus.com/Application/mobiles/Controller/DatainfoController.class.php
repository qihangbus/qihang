<?php
namespace mobiles\Controller;
use Think\Controller;
class DatainfoController extends Controller {
	//图书情况
	public function index() 
	{ 
		$id = I('param.id',0);
		$admin_id = I('param.admin_id',0);
		$this->assign('admin_id',$admin_id);
		$this->assign('id',$id);	
		$t = I('param.t',1);
		$this->assign('t',$t);

		if(!empty($admin_id)){
			$url = U('mobile.php/MIndex/index',array('id'=>$admin_id));
		}else{
			$url = U('mobile.php/SIndex/Index',array('id'=>$id));
		}
		$this->assign('url',$url);
		
		//查看图书总数
		$total = M('schooltobook')->where(array('school_id'=>$id))->count();
		$this->assign('total',$total);

		//查看已借阅数量
		$borrow_total = M('circulation')->where(array('school_id'=>$id,'circul_status'=>1))->count();
		$this->assign('borrow_total',$borrow_total);
		$this->assign('borrow_total_per',round($borrow_total/$total*100,2));
		//计算损坏未赔偿数量
		$compen_total = M('compensate')->where(array('school_id'=>$id,'compen_status'=>1))->count();
		$this->assign('compen_total',$compen_total);
		$this->assign('compen_total_per',round($compen_total/$total*100,2));
		//计算损坏已赔偿数量
		$shypc = M('compensate')->where(['school_id'=>$id,'compen_status'=>2,'bf_status'=>0])->count();
		$this->assign('shypc',$shypc);
		//计算未借阅数量
		$noborrow_total = $total-$borrow_total-$compen_total;
		$this->assign('noborrow_total',$noborrow_total);
		$this->assign('noborrow_total_per',round($noborrow_total/$total*100,2));
		$this->display('Datainfo/index');
	}

	// 订购情况
	public function order_list()
	{
		$day = I('param.d','');
		$tag = I('param.tag','');
		// tag表示月份的加减
		if(empty($day))
		{
			$day1 = date("Y-m",time());
			$day = date("Y-m-d",time());
			$time = strtotime($day);
		}
		if($tag == 1)
		{
			$day1 = date("Y-m",strtotime("-1 month",strtotime($day)));
			$day = date("Y-m-t",strtotime("-1 month",strtotime($day)));
		}
		if($tag == 2)
		{
			$day1 = date("Y-m",strtotime("+1 month",strtotime($day)));
			$day = date("Y-m-t",strtotime("+1 month",strtotime($day)));
		}
		$time = strtotime($day);
		$this->assign('day1',$day1);
		$id = I('param.id','');
		$admin_id = I('param.admin_id','');
		$this->assign('id', $id);
		$this->assign('admin_id',$admin_id);
		$t = I('param.t',2);
		$this->assign('t',$t);
		// 该学校的人数
		$total_num = M('students')->where(array('school_id'=>$id))->count();
		//付款时间小于选择的时间
		$condition1['school_id'] = $id;
		$semester_one = M('schools')->where(array('school_id'=>$id))->getField('semester_one');
		// 如果当前时间大于第一学期结束时间，则paid_time就要大于小于
		if($id < 19 && $id <> 2) {//旧收费模式
			if ($time <= $semester_one) {
				$condition1['paid_num'] = 1;
			} else {
				$condition1['paid_time'] = array('between', "$semester_one,$time");
			}
		}else{//新收费模式
			$condition1['paid_expires'] = ['gt',time()];
		}
		//该学校订购的人数
		$num = M('students')->where($condition1)->count();
		$percent = round($num/$total_num,2)*100;
		$this->assign('percent',$percent);
		$arr = array();
			for($i=0;$i<2;$i++)
			{
				if($i==0)
				{
					$tmp['y'] = "总数".$total_num."人";
					$tmp['a'] = $total_num;
				}
				if($i==1)
				{
					$tmp['y'] = "已订".$num."人";
					$tmp['a'] = $num;
				}
				$arr[] = $tmp;
			}
			$condition['school_id'] = $id;
		$list = M('students')->where(array('school_id'=>$id))->field("count(class_id) as total_num,class_id,grade_name,class_name,teacher_name")->group('class_id')->select();
		foreach ($list as $key => $value) {
			$arr1['class_id'] = $value['class_id'];
			if($id < 19 && $id <> 2) {//旧收费模式
				if ($time <= $semester_one) {
					$arr1['paid_num'] = 1;
				} else {
					$arr1['paid_time'] = array('between', "$semester_one,$time");
				}
			}else{//新收费模式
				$arr1['paid_expires'] = ['gt',time()];
			}

			//该班级订购的人数
			$num1 = $list[$key]['num1'] = M('students')->where($arr1)->count();
			$list[$key]['percent'] = round($num1/$value['total_num'],2)*100;
		}
		// var_dump($list);die;
		$this->assign('list',$list);
		$this->assign('list_json',json_encode($arr));
		$this->assign('day',$day);
		$this->display('Datainfo/order_list_1');

}
    // 注册情况
	public function register(){
		$t = I('param.t',3);
		$id = I('param.id','');
		$admin_id = I('param.admin_id','');
		$this->assign('id', $id);
		$this->assign('admin_id',$admin_id);
		$total_num = M('students')->where(array('school_id'=>$id))->count();
		// 该学校的注册人数
		$num = M('students_parent')->where(array('school_id'=>$id))->count('distinct(student_id)');
		$percent = round($num/$total_num,2)*100;
		$this->assign('percent',$percent);
		$arr = array();
		for($i=0;$i<2;$i++)
			{
				if($i==0)
				{
					$tmp['y'] = "总数".$total_num."人";
					$tmp['a'] = $total_num;
				}
				if($i==1)
				{
					$tmp['y'] = "注册".$num."人";
					$tmp['a'] = $num;
				}
				$arr[] = $tmp;
			}
		$list = M('students')->where(array('school_id'=>$id))->field("count(class_id) as total_num,class_id,grade_name,class_name,teacher_name")->group('class_id')->select();
		foreach ($list as $key => $value) {
			$arr1['class_id'] = $value['class_id'];
			$num1 = $list[$key]['num1'] = M('students_parent')->where($arr1)->count('distinct(student_id)');
			$list[$key]['percent'] = round($num1/$value['total_num'],2)*100;
		}
		$this->assign('t',$t);
		$this->assign('list',$list);
		$this->assign('list_json',json_encode($arr));
		$this->display('Datainfo/register_list');
	}
	//订阅情况
	public function book_list()
	{
		$id = I('param.id','');
		$this->assign('id',$id);
		$t = I('param.t',3);
		$this->assign('t',$t);

		$param = I('get.param',1);
		$date = I('get.d',0);

		if($date > 0 && $param == 1){
			$nowtime = strtotime($date." 23:59:59");
			$starttime = strtotime($date." 00:00:00");
		}elseif($date < 1 && $param == 1){
			$nowtime = time();
			$starttime = mktime(0,0,0,date('m'),date('d'),date('Y'));
		}elseif($date > 0 && $param == 2){
			$t = strtotime($date);
        	$month = date("m",$t);
        	$year = date("Y",$t);
        	$month_total_day = date('j',mktime(0,0,1,($month==12?1:$month+1),1,($month==12?$year+1:$year))-24*3600);
			$nowtime = strtotime($year.'-'.$month.'-'.$month_total_day." 23:59:59");
			$starttime = mktime(0,0,0,$month,1,$year);
		}elseif($date < 1 && $param == 2){
			$month = date("m");
        	$year = date("Y");
        	$month_total_day = date('j',mktime(0,0,1,($month==12?1:$month+1),1,($month==12?$year+1:$year))-24*3600);
			$nowtime = strtotime($year.'-'.$month.'-'.$month_total_day." 23:59:59");
			$starttime = mktime(0,0,0,date('m'),1,date('Y'));
		}


		$grade_list = M('grade')->where(array('school_id'=>$id))->select();
		$this->assign('grade_list',$grade_list);

		$grade_id = I('param.grade_id',0);

		if($grade_id > 0){
			$condition['grade_id'] = $grade_id;
			$this->assign('grade_id',$grade_id);
		}
		$condition['school_id'] = $id;
		$condition['log_time'] = array('between',"$starttime,$nowtime");
		$list = M('circul_log')->where($condition)->field("count(class_id) as total_num,class_id")->group('class_id')->select();;
		
		
		foreach ($list as $key => $value) {
			$info = M('students')->where(array('class_id'=>$value['class_id']))->field("grade_name,class_name,teacher_name")->find();
			$list[$key]['grade_name'] = $info['grade_name'];
			$list[$key]['class_name'] = $info['class_name'];
			$list[$key]['teacher_name'] = $info['teacher_name'];
		}

		$this->assign('list',$list);
		
		$arr = array();
		
		foreach ($list as $key => $value) {
			$tmp['y'] = $value['grade_name'].$value['class_name'];
			$tmp['a'] = $value['total_num'];
			$arr[] = $tmp;
		}

		$this->assign('list_json',json_encode($arr));



		if($param == 2){
			$this->assign('day',date('Y-m',$nowtime));
			$this->assign('year',date('Y',$nowtime));
			$this->assign('month',date('m',$nowtime));	
			$this->display('Datainfo/book_list_2');
		}else{
			$this->assign('day',date('Y-m-d',$nowtime));	
			$this->display('Datainfo/book_list');
		}
	}

	//损坏未赔偿情况
	public function compensate()
	{
		$id = I('param.id',0);
		$list = M('compensate')->where(['school_id'=>$id,['compen_status'=>1]])->field("count(class_id) as total_num,class_id")->group('class_id')->select();
		foreach ($list as $key => $value) {
			$list[$key]['class_name'] = M('class')->where(array('class_id'=>$value['class_id']))->getField('class_name');
			$list[$key]['teacher_name'] = M('teacher')->where(array('class_id'=>$value['class_id']))->getField('teacher_name');
		}
		$user = session('user');
		if($user['type'] == 3){
			$index_url = U('SIndex/index',['id'=>$id]);
		}else{
			$index_url = U('MIndex/index',['id'=>$user['id']]);
		}

		$this->assign('list',$list);
		$this->assign('id',$id);
		$this->assign('index_url',$index_url);
		$this->display('Datainfo/compensate');
	}

	//已借阅数据
	public function borrow()
	{
		$id = I('param.id','');
		$list = M('circulation')->where(array("school_id"=>$id,'circul_status'=>1))->field("count(class_id) as total_num,class_id")->group('class_id')->select();

		foreach ($list as $key => $value) {
			$list[$key]['class_name'] = M('class')->where(array('class_id'=>$value['class_id']))->getField('class_name');
			$list[$key]['teacher_name'] = M('teacher')->where(array('class_id'=>$value['class_id']))->getField('teacher_name');
		}
		$this->assign('list',$list);
		$this->assign('id',$id);
		$this->display('Datainfo/borrow');
	}

	//未借阅数据
	public function noborrow()
	{
		$id = I('param.id','');
		$arr = array();

		$compen_arr = array();
		$compen = M('compensate')->where(array('school_id'=>$id,'compen_status'=>1))->select();
		foreach ($compen as $key => $value) {
			$compen_arr[$value['class_id']] = 1;
		}


		$ret = M('circulation')->where(array("school_id"=>$id,'circul_status'=>1))->field("count(class_id) as total_num,class_id")->group('class_id')->select();
		foreach ($ret as $key => $value) {
			$arr[$value['class_id']] = $value['total_num'] + $compen_arr[$value['class_id']];
		}

		//计算损坏数量

		//查询每个班级的图书数
		$list = M('schooltobook')->where(array("school_id"=>$id,'class_id'=>array('gt','0')))->field("count(class_id) as totalnum,class_id")->group('class_id')->select();
		
		$lt = array();
		
		foreach ($list as $key => $value) {
			if($value['class_id'] > 0){
				$lt[$key]['total_num'] = $value['totalnum'] - $arr[$value['class_id']];
				$lt[$key]['class_name'] = M('class')->where(array('class_id'=>$value['class_id']))->getField('class_name');
				$lt[$key]['teacher_name'] = M('teacher')->where(array('class_id'=>$value['class_id']))->getField('teacher_name');
			}
		}

		$this->assign('list',$lt);	
		
		$this->display('Datainfo/noborrow');
	}
		
	public function charts(){	
		$id = I('param.id','');
		$this->assign('id',$id);
		$info = M("students");
		$query_sql = "select COUNT(*) as count,class_name as name,school_id,teacher_name,grade_name,class_name FROM fh_students where school_id =".$id." GROUP BY class_id";
		$data = $info->query($query_sql);

		foreach ($data as $key => $value) {
			$data[$key]['teacher_avatar'] = M('teacher')->where(array('teacher_id'=>$value['teacher_id']))->getField('teacher_avatar');
			//$data[$key]['num'] = M('students')->where(array('class_id'=>$value['class_id']))->count();
		}
		$this->assign('list',$data);
		$this->display('/Datainfo/index');
	}

	public function ajax_charts(){
		$id = I('get.id','');

		$info = M("students");
		$query_sql = "select COUNT(*) as count,grade_name,class_name as name FROM fh_students where school_id =".$id." GROUP BY class_id";
		$data = $info->query($query_sql);
		
		$arr = array();
		$arr2 = array();
		foreach ($data as $key => $value) {
			//$arr[] = $value['name'];
			array_push($arr, $value['grade_name'].$value['name']);
			array_push($arr2, $value['count']);
			//$arr2[] = $value['count'];
			//$arr .= $value['name'].",";
			//$arr2 .= $value['count'].",";
		}
		echo json_encode(array('name'=>$arr,'value'=>$arr2));
	}	
}