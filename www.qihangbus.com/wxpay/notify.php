<?php
include_once('../ThinkPHP/Library/Vendor/WxPayPubHelper/WxPayPubHelper.php');
$notify = new Notify_pub();
$xml = $GLOBALS['HTTP_RAW_POST_DATA'];
$conn = new mysqli('localhost', 'root', ',.qaz123','qihangbus');

$notify->saveData($xml);
if ($notify->data["return_code"] == "FAIL") {
	if($payment['logs']){
		wx_log('wx_new_log.txt',"return_code失败\r\n");
	}
}elseif($notify->data["result_code"] == "FAIL"){
	if($payment['logs']){
		wx_log('wx_new_log.txt',"result_code失败\r\n");
	}
}else {
	$total_fee = $notify->data["total_fee"];
	$log_id = $notify->data["attach"];
	$sql = "select order_amount,user_id,order_type,user_flag,order_id,expires,pay_year,remark from `fh_pay_log` where `log_id`=$log_id";
	$result = $conn->query($sql);
	$ret = $result->fetch_row();
	$amount = $ret[0];
	$user_id = $ret[1];
	$order_type = $ret[2];
	$user_flag = $ret[3];
	$order_id = $ret[4];
	$expires = $ret[5];
	$pay_year = $ret[6];
	$appre = $ret[7];
	$sql = "update `fh_pay_log` set is_paid=1 where log_id=$log_id";
	$conn->query($sql);
	//支付软件使用费用
	$time = time();
	$sql = "update `fh_students` set paid_time=$time,paid_expires=$expires,paid_type=$pay_year where student_id=$user_id";
	$conn->query($sql);
	echo '<xml><return_code><![CDATA[SUCCESS]]></return_code><return_msg><![CDATA[OK]]></return_msg></xml>';
}


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