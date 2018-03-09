<?php
//echo '<xml><return_code><![CDATA[SUCCESS]]></return_code><return_msg><![CDATA[OK]]></return_msg></xml>';
include_once('../ThinkPHP/Library/Vendor/WxPayPubHelper/WxPayPubHelper.php'); 
$notify = new Notify_pub();
$xml = $GLOBALS['HTTP_RAW_POST_DATA'];
//file_put_contents('xujie.txt',000);
//$conn = mysql_connect('localhost','root',',.qaz123');
$conn = new mysqli('localhost', 'root', ',.qaz123','qihangbus');

//mysql_select_db('qihangbus',$conn);
$notify->saveData($xml);
$payment['logs'] = false;


//if($notify->checkSign() == TRUE)
//{
	if ($notify->data["return_code"] == "FAIL") {
		if($payment['logs']){
			wx_log('wx_new_log.txt',"return_code失败\r\n");
		}
	}
	elseif($notify->data["result_code"] == "FAIL"){
		if($payment['logs']){
			wx_log('wx_new_log.txt',"result_code失败\r\n");
		}
	}

	else
	{
		if($payment['logs']){
			wx_log('wx_new_log.txt',"支付成功\r\n");
		}
		$total_fee = $notify->data["total_fee"];
		$log_id = $notify->data["attach"];
	   
		$sql = "select order_amount,user_id,order_type,user_flag,order_id,remark from `fh_pay_log` where `log_id`=$log_id";
		// $result = mysqli_query($sql);
		$result = $conn->query($sql);
		$ret = $result->fetch_row();

		$amount = $ret[0];

		
		if($payment['logs'])
		{
			wx_log('wx_new_log.txt',var_export($result,TRUE)."\r\n");
		}

//		if(intval($amount*100) != $total_fee)
//		{
//
//			if($payment['logs'])
//			{
//				wx_log(ROOT_PATH.'/wx_new_log.txt','订单金额不符'."\r\n");
//			}
//			echo 'fail';
//		}
		$user_id = $ret[1];
		$order_type = $ret[2];
		$user_flag = $ret[3];
		$order_id = $ret[4];
		$appre = $ret[5];
		$sql = "update `fh_pay_log` set is_paid=1 where log_id=$log_id";
		$conn->query($sql);

		
		if($order_type == 2){
			//支付软件使用费用
			$t = time();
			$sql1 = "update `fh_students` set is_paid=1,paid_num=$appre,paid_time=$t where student_id=$user_id";
			$conn->query($sql1);
		}elseif($order_type == 1){

			//订单支付
			$sql1 = "update `fh_order_info` set order_status=1,pay_status=2 where order_id=$order_id";
			$conn->query($sql1);
			
			// $sql = "select book_id,book_number from `fh_order_goods` where `order_id`=$order_id";
			
			$sql = "select book_id,book_number from `fh_order_goods` where `order_id`=$order_id";
			$log = file_get_contents('1.txt');
			$log = $log."\r\n\r\n".date('Y-m-d H:i:s').'--'.$sql;
			file_put_contents('1.txt',$log);
			$result = $conn->query($sql);
			$log = file_get_contents('2.txt');
			$log = $log.date('Y-m-d H:i:s').var_export($result,TRUE)."\r\n\r\n";
			file_put_contents('2.txt',$log);
			// while($res = $conn->fetch_assoc($result)) {
			// 	$book_id = $res['book_id'];
			// 	$book_number = $res['book_number'];
			// 	$sql1 = "update `fh_books` set book_number=book_number-$book_number where book_id_id=$book_id";
			// 	$conn->query($sql1);	
			// }	
			while($res = $result->fetch_assoc()) {
				$product_id = $res['book_id'];
				$product_number = $res['book_number'];
				$log = file_get_contents('3.txt');
				file_put_contents('3.txt',$log.date('Y-m-d H:i:s').'--'.$res['book_id'].'--'.$res['book_number']."\r\n");
				$sql1 = "update `fh_product` set product_num=product_num-$product_number where product_id=$product_id";
				$log = file_get_contents('4.txt');
				file_put_contents('4.txt',$log.date('Y-m-d H:i:s').$sql1."\r\n");
				$conn->query($sql1);	
			}	
		}elseif($order_type == 3 || $order_type == 4){
			//支付赔偿
			$compen_status = 2;
			if($order_type == 4) $compen_status = 3;
			$sql1 = "update `fh_compensate` set compen_status=$compen_status where student_id=$user_id";
			$conn->query($sql1);

			//支付成功后，清除借阅记录,并插入到借阅记录表里
			$sql = "select book_id,add_time from fh_circulation where student_id=$user_id and circul_status=1";
			$result = $conn->query($sql);
			$ret = $result->fetch_row();
			$sql = "select * from fh_students where student_id=$user_id";
			$query = $conn->query($sql);
			$userinfo = $query->fetch_assoc();
			$book_id = $ret[0];
			$borrow_time = $ret[1];
			$return_time = time();
			$teacher_id = $userinfo['teacher_id'];
			$school_id = $userinfo['school_id'];
			$school_name = $userinfo['school_name'];
			$grade_id = $userinfo['grade_id'];
			$class_id = $userinfo['class_id'];
			$class_name = $userinfo['class_name'];
			$student_name = $userinfo['student_name'];
			$insert_sql = "insert into fh_circul_log('book_id','teacher_id','school_id','grade_id','class_id','student_id','borrow_time','return_time','circul_status','log_time') 
							values($book_id,$teacher_id,$school_id,$grade_id,$class_id,$user_id,$borrow_time,$return_time,1,$return_time)";
			$conn->query($insert_sql);

			$sql = "delete from `fh_circulation` where student_id=$user_id and book_id=$book_id and circul_status=1";
			$conn->query($sql);
			
			if($order_type == 3){

				//查看绘本是否已轮换
				$sql = "select * from fh_schooltobook where school_id=$school_id and grade_id=$grade_id and class_id=$class_id and book_id=$book_id";
				$result = $conn->query($sql);
				$res = $result->fetch_assoc();
				if($next_classid = $res['next_classid']){//已轮换,更新classid
					$sql = "update `fh_compensate` set class_id=$next_classid where student_id=$user_id and book_id=$book_id and bf_status = 0";
					$conn->query($sql);
				}

				$sql = "delete from `fh_schooltobook` where school_id=$school_id and grade_id=$grade_id and class_id=$class_id and book_id=$book_id";
				$conn->query($sql);

				//更新学校图书缺失量
				$sql = "update fh_schools set book_lose_num = book_lose_num + 1 where school_id = $school_id";
				$conn->query($sql);

				//如果在轮换接收表中，删除
				$sql = "delete from fh_rotate_receive where book_id = $book_id and pre_class_id = $class_id";
				$conn->query($sql);

				/*********支付成功向老师发送一条信息*************/
				// $name = M('students')->where(array('student_id'=>$user_id))->getField('student_name');
				// $data['sender_id'] = 0;
				// $data['receiver_id'] = $teacher_id;
				// $data['sender_name'] = '系统消息';
				// $data['send_time'] = time();
				// $data['message'] = $name."的家长已赔偿".$amount."元";
				// $data['user_flag'] = 3;
				// M('message')->add($data);
				/***********************************************/

				//给系统管理员推送消息(全额赔偿)
				$sql = "select code,value from fh_config where parent_id in(13,99)";
				$result = $conn->query($sql);
				
				$options = array();
				$weixin_options = array('appid','appsecret','token','access_token','expire_in');
				while($res = $result->fetch_assoc()) {
					
					if(in_array($res['code'], $weixin_options)){
						$options[$res['code']] = $res['value'];
					}
				}
				
				if($options['expire_in']-7200<time()){
					$access_token = update_access_token($options);
					$sql1 = "update `fh_config` set value=$access_token where id=25";
					$conn->query($sql1);
					$t = time();
					$sql1 = "update `fh_config` set value=$t where id=26";
					$conn->query($sql1);
					$options['access_token'] = $access_token;
					$options['expire_in'] = $t;
				}


				//推送微信消息，格式：
				//{"touser":"OPENID","template_id":"6_yZwUakw2DiD9-3YyWNFkiqLAWLXuONeZl7-LnBX_4","url":"http://#","topcolor":"#FF0000","data":{"User": {"value":"黄先生","color":"#173177}}			
				//$data['touser'] = M("students_parent")->where(array('student_id'=>$student_list[$j]['student_id']))->getField("wx_id");
				//$data['touser'] = 'oKJMMwrkIU3Ekjtk5_1K4w7PeCuQ';
				$data['touser'] = 'oKJMMwnaHDy16vfeYROjai3hhRMU';
				$data['template_id'] = '6_yZwUakw2DiD9-3YyWNFkiqLAWLXuONeZl7-LnBX_4';
				$data['url'] = '';
				$data['topcolor'] = '#FF0000';
				
				$first['value'] = '订单支付成功';
				$first['color'] = '#173177';
				$tmp['first'] = $first;
				
				$sql = "select * from fh_schools where school_id=$school_id";
				$query = $conn->query($sql);
				$schoolinfo = $query->fetch_assoc();
				
				$keyword1['value'] = $schoolinfo['province_name'].$schoolinfo['city_name'].$schoolinfo['district_name'];
				$keyword1['color'] = '#173177';
				$tmp['keyword1'] = $keyword1;
				
				$keyword2['value'] = $school_name;
				$keyword2['color'] = '#173177';
				$tmp['keyword2'] = $keyword2;
				
				$keyword3['value'] = $class_name;
				$keyword3['color'] = '#173177';
				$tmp['keyword3'] = $keyword3;
				
				
				$keyword4['value'] = $student_name;
				$keyword4['color'] = '#173177';
				$tmp['keyword4'] = $keyword4;
				
				$remark['value'] = '图书损坏赔偿已支付成功，请尽快安排图书配送。';
				$remark['color'] = '#173177';
				$tmp['remark'] = $remark;
				
				$data['data'] = $tmp;
				wx_log('wx_new_log.txt',"1111\r\n");
				$arr = sendTemplateMessage($options['access_token'],json_encode($data));
				echo 'SUCCESS';
			}
		}
		// echo 'SUCCESS';
		echo '<xml><return_code><![CDATA[SUCCESS]]></return_code><return_msg><![CDATA[OK]]></return_msg></xml>';
	}

//}
/*else
{
	wx_log(ROOT_PATH.'/data/wx_new_log.txt',"签名失败\r\n");
	echo 'fail';
}*/

function update_access_token($options)
{
	$appid = $options['appid'];
	$appsecret = $options['appsecret'];
	
	//TODO: get the cache access_token
	$result = http_get('https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid='.$appid.'&secret='.$appsecret);
	if ($result)
	{
		$json = json_decode($result,true);
		if (!$json || isset($json['errcode'])) {
			return false;
		}
		$access_token = $json['access_token'];
		$expire = $json['expires_in'] ? intval($json['expires_in'])-100 : 3600;
		//TODO: cache access_token
		return $access_token;
	}
}

function sendTemplateMessage($access_token,$data){
	$result = http_post('https://api.weixin.qq.com/cgi-bin/message/template/send?access_token='.$access_token,$data);
	
	if ($result)
	{
		$json = json_decode($result,true);
		if (!$json || !empty($json['errcode'])) {
			return false;
		}
		return $json;
	}
	return false;
}

function http_get($url){
	$oCurl = curl_init();
	if(stripos($url,"https://")!==FALSE){
		
		curl_setopt($oCurl, CURLOPT_SSL_VERIFYPEER, FALSE);
		curl_setopt($oCurl, CURLOPT_SSL_VERIFYHOST, FALSE);
	}
	curl_setopt($oCurl, CURLOPT_URL, $url);
	curl_setopt($oCurl, CURLOPT_RETURNTRANSFER, 1 );
	$sContent = curl_exec($oCurl);
	$aStatus = curl_getinfo($oCurl);

	curl_close($oCurl);
	if(intval($aStatus["http_code"])==200){
		return $sContent;
	}else{
		return false;
	}
}

function http_post($url,$param){
	$oCurl = curl_init();
	if(stripos($url,"https://")!==FALSE){
		curl_setopt($oCurl, CURLOPT_SSL_VERIFYPEER, FALSE);
		curl_setopt($oCurl, CURLOPT_SSL_VERIFYHOST, false);
	}
	if (is_string($param)) {
		$strPOST = $param;
	} else {
		$aPOST = array();
		foreach($param as $key=>$val){
			$aPOST[] = $key."=".urlencode($val);
		}
		$strPOST =  join("&", $aPOST);
	}
	curl_setopt($oCurl, CURLOPT_URL, $url);
	curl_setopt($oCurl, CURLOPT_RETURNTRANSFER, 1 );
	curl_setopt($oCurl, CURLOPT_POST,true);
	curl_setopt($oCurl, CURLOPT_POSTFIELDS,$strPOST);
	$sContent = curl_exec($oCurl);
	$aStatus = curl_getinfo($oCurl);
	curl_close($oCurl);
	if(intval($aStatus["http_code"])==200){
		return $sContent;
	}else{
		return false;
	}
}

function wx_log($file,$txt)
{
   $fp =  fopen($file,'ab+');
   fwrite($fp,'-----------'.date('Y-m-d H:i:s').'-----------------');
   fwrite($fp,$txt);
   fwrite($fp,"\r\n\r\n\r\n");
   fclose($fp);
}