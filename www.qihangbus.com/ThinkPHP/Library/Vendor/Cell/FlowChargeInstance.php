<?php
//手机  流量  充值示例
include_once './FlowCharge.php';  //引入充值类文件
$ihuyi = new FlowCharge();        //实例化充值类

$data = $ihuyi->getMethod(10, '18717927005', $orderId = md5(date('YmdHis'))); //
	//移动   $arr = array(10, 30, 70, 150, 500, 1024, 2048, 3072, 4096, 6144, 11264);
	//联通   $arr = array(20, 50, 100, 200, 500, 1024);
	//电信   $arr = array(5, 10, 30, 50, 100, 200, 500, 1024);
d(json_decode($data,true));

$data = $ihuyi->postMethod(10, '18717927005', $orderId = md5(date('YmdHi-s'))); //
	//移动   $arr = array(10, 30, 70, 150, 500, 1024, 2048, 3072, 4096, 6144, 11264);
	//联通   $arr = array(20, 50, 100, 200, 500, 1024);
	//电信   $arr = array(5, 10, 30, 50, 100, 200, 500, 1024);
d(json_decode($data,true));

function d($data){
	echo '<pre>';
	print_r($data);
	echo '</pre>';
}
