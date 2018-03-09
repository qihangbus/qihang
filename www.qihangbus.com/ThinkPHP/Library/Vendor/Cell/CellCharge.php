<?php
//单号码话费充值类
header("Content-type: text/html; charset=utf-8");                //设置字符格式
date_default_timezone_set('PRC'); 			    //设置中国时区

class CellCharge {
	protected $userName = 'cf_testapi';									//测试用户名
	protected $apiKey 	= '6j3ao593wMNQRz4Zo4ao';						//测试apikey
	protected $mobile;												//充值手机号
	protected $basicUrl = 'http://f.ihuyi.com/phone?action=recharge&%s';//单号码充值接口地址
	protected $package;													//充值套餐数额
	protected $orderId;
	
	public function __construct()
	{
		
	}
	
	public function getMethod($package, $mobile, $orderId)              //get充值入口
    {
		$this->mobile  = $mobile;										//手机号
		$this->package = $package;
		$this->orderId = $orderId;						    //充值包
		$returnMsg = $this->inputCheck();								//检测手机号与充值包
		if($returnMsg['code'] != 1) return json_encode($returnMsg);
		/*获得签名*/
		$urlGet  = $this->sign();
		/*获得**/
		$getData = $this->getCurl($urlGet);
		return $getData;
	}

	public function postMethod($package, $mobile, $orderId)			    //post充值入口
    {
		$this->mobile  = $mobile;										//手机号
		$this->package = $package;                                      //充值包  固定值 1,2,3,4,5,6,7,8,9,10,20,30,50,100,200,300,500;
		$this->orderId = $orderId;
		$returnMsg = $this->inputCheck();								//检测手机号与充值包
		if($returnMsg['code'] != 1) return json_encode($returnMsg);
		$getData = $this->getPostData();
		$getData = $this->postCurl($getData);
		return $getData;
	}
	
	protected function getCurl($urlGet)                                 //以curl方式发送参数到接口，注意curl扩展要开启
	{
		$ch     = curl_init();
		curl_setopt($ch, CURLOPT_URL, $urlGet);                  //定义表单提交地址
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_TIMEOUT, 30);
		curl_setopt($ch, CURLOPT_HEADER, 1);
		curl_setopt($ch, CURLOPT_REFERER, isset($_SERVER['HTTP_HOST']) ? 'http://' . $_SERVER['HTTP_HOST'] : '');
		curl_setopt($ch, CURLOPT_POST, 0);
		$data = curl_exec($ch);
		curl_close($ch);
		$res = explode("\r\n\r\n", $data);
		$getData = $res[1];
		return $getData;
	}

	public function postCurl($getData)
	{
		$ch = \curl_init();
        curl_setopt($ch, CURLOPT_URL, $this->basicUrl);          //定义表单提交地址
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_TIMEOUT, 100); //60秒
        curl_setopt($ch, CURLOPT_REFERER, isset($_SERVER['HTTP_HOST']) ? 'http://' . $_SERVER['HTTP_HOST'] : '');
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($getData));
        $data = curl_exec($ch);
        return $data;
	}

	public function getPostData()
	{
		$time = date("YmdHis");
		$inputOrderId = $this->orderId == '' ? 'TEST_'.date('YmdHis').mt_rand(100, 1000) : $this->orderId;
		$sign = "apikey=".$this->apiKey."&mobile=".$this->mobile."&orderid=".$inputOrderId."&package=".$this->package."&timestamp=".$time."&username=".$this->userName;
		$data['mobile']    = $this->mobile;
		$data['orderid']   = $this->orderId;
		$data['timestamp'] = $time;
		$data['username']  = $this->userName;
		$data['package']   = $this->package;
		$data['sign']      = strtolower(md5($sign));
		return $data;
	}
	
	protected function sign()//生成签名
	{
		$dataGet = array();
		$dataGet['apikey'] 	  = $this->apiKey;
		$dataGet['package']   = $this->package;
		$dataGet['username']  = $this->userName;
		$dataGet['timestamp'] = date("YmdHis");
		$dataGet['mobile'] 	  = $this->mobile;
		$dataGet['orderid']	  = $this->orderId == '' ? 'TEST_'.$dataGet['timestamp'].mt_rand(100, 1000) : $this->orderId;

		ksort($dataGet);
		$buff = "";
		foreach ($dataGet as $k => $v){
		    if($k != "sign") {
		        $buff .= $k . "=" . $v . "&";
            }
		}
		$buff = trim($buff, "&");
		$dataGet['sign']  	  = strtolower(md5($buff));
		$dataReturn           = array();
		foreach ($dataGet as $key => $row) {
			$dataReturn[]     = sprintf("%s=%s", $key, $row);
		}
		$urlGet = sprintf($this->basicUrl, implode("&", $dataReturn));
		return $urlGet;
	}
	
	protected function inputCheck()			                               //检测手机号，充值包
	{
		$returnMsg['code']	  = 1;
		$returnMsg['message'] = array();
		$packageData = array(1,2,5,10,20,30,50,100,200,300,500);
		if(! in_array($this->package, $packageData)) {
			$returnMsg['code']	  = 0;
			$returnMsg['message'][] = '充值包设置错误，充值包只有1,2,3,4,5,6,7,8,9,10,20,30,50,100,200,300,500';
		}
		$reg = "/^1[34578]\d{9}$/";
		if(! preg_match($reg, $this->mobile)){
			$returnMsg['code']	  = 0;
			$returnMsg['message'][] = '手机号码格式错误';
		}
		return $returnMsg;
	}
}
