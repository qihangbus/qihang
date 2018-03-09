<?php
// echo date("Y-m-d H:i:s",time())."<br />";
//单号码  话费  充值示例   在文件"./CellCharge.php"设置正式用户名，apikey
include_once './CellCharge.php';  //引入充值类文件
// date_default_timezone_set('PRC'); 
$ihuyi = new CellCharge();        //实例化充值类
$data = $ihuyi->getMethod(1, "13569264934", $orderId = md5(date('YmdHis')));
// $data = $ihuyi->getMethod($package, $mobile, $orderId);
$res = json_decode($data,true);
// echo $res['code'];die;
var_dump($res);
d(json_decode($data,true));


$data = $ihuyi->postMethod(1, "18717927005", $orderId = md5(date('YmdHis')));  //public function charge($package,$mobile);话费充值入口
d(json_decode($data,true));
                                                                                                      //$package充值包，1元,2元,5元,10元,20元,50元,100元,200元,300元,500元
                                                                                                      //$mobile手机号

function d($data){
	echo '<pre>';
	print_r($data);
	echo '</pre>';
}
