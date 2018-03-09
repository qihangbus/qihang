<?php
//单号码流量充值类
header("Content-type: text/html; charset=utf-8");   //设置字符格式
date_default_timezone_set('PRC'); 					//设置中国时区

class FlowCharge
{
	protected $userName = 'cf_testapi';									//测试用户名
	protected $apiKey 	= '6j3ao593wMNQRz4Zo4ao';						//测试apikey
	protected $basicUrl = "http://f.ihuyi.com/v2?action=recharge&%s";   //单号码充值接口地址
	protected $mobile;													//充值手机号
	protected $package;													//充值套餐数额
	protected $orderId;													//客户订单号

	public function __construct()
	{
		
	}
	
	public function getMethod($package, $mobile, $orderId)              //get充值入口
    {
        $this->orderId = $orderId;                                      //客户订单号
		$this->mobile  = $mobile;										//手机号
		$this->package = (int)$package;	                   			    //充值包 
		                                                                //移动   $arr = array(10, 30, 70, 150, 500, 1024, 2048, 3072, 4096, 6144, 11264);
                                                                    	//联通   $arr = array(20, 50, 100, 200, 500, 1024);
                                                                    	//电信  $arr = array(5, 10, 30, 50, 100, 200, 500, 1024);
		$amount    = $this->package();
		if(!$amount) return json_encode(array('code'=>'0','msg'=>'流量包错误')); 
		$returnMsg = $this->inputCheck();								//检测手机号
		if($returnMsg['code'] != 1) return json_encode($returnMsg);
		$urlGet    = $this->sign();
		$getData   = $this->curl($urlGet);
		return $getData;
	}

	public function postMethod($package,$mobile, $orderId){				//post充值入口
		$this->mobile  = $mobile;										//手机号
		$this->package = $package;
		$this->orderId = $orderId;										//充值包
		$returnMsg = $this->inputCheck();								//检测手机号与充值包
		if($returnMsg['code'] != 1) return json_encode($returnMsg);
		$getData = $this->getPostData();
		$getData = $this->postCurl($getData);
		return $getData;
	}
	
	protected function curl($urlGet)                                    //以curl方式发送参数到接口，注意curl扩展要开启
	{
		$ch     = curl_init();
		curl_setopt($ch, CURLOPT_URL, $urlGet);                  //定义表单提交地址
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_TIMEOUT, 30); //60秒
		curl_setopt($ch, CURLOPT_HEADER, 1);
		curl_setopt($ch, CURLOPT_REFERER, isset($_SERVER['HTTP_HOST']) ? 'http://' . $_SERVER['HTTP_HOST'] : '');
		curl_setopt($ch, CURLOPT_POST, 1);
		$data = curl_exec($ch);
		curl_close($ch);
		$res = explode("\r\n\r\n", $data);
		$getData = $res[1];
		return $getData;
	}

	public function postCurl($urlGet)
	{
		$ch = \curl_init();
        curl_setopt($ch, CURLOPT_URL, $this->basicUrl);         //定义表单提交地址
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_TIMEOUT, 100); //60秒
        curl_setopt($ch, CURLOPT_REFERER, isset($_SERVER['HTTP_HOST']) ? 'http://' . $_SERVER['HTTP_HOST'] : '');
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $urlGet);
        $data = curl_exec($ch);
        return $data;
	}

	public function getPostData()
	{
		$time = date("YmdHis");
		$inputOrderId = $this->orderId == '' ? md5(date('YmdHis')) : $this->orderId;
		$sign = "apikey=".$this->apiKey."&mobile=".$this->mobile."&orderid=".$inputOrderId."&package=".$this->package."&timestamp=".$time."&username=".$this->userName;
		$data['mobile']    = $this->mobile;
		$data['orderid']   = $inputOrderId;
		$data['timestamp'] = $time;
		$data['username']  = $this->userName;
		$data['package']   = $this->package;
		$data['sign']      = strtolower(md5($sign));
		return $data;
	}
	
	protected function sign()               //生成签名和请求url
	{
		$dataGet = array();
		$dataGet['apikey']    = $this->apiKey;
		$dataGet['package']   = $this->package;
		$dataGet['username']  = $this->userName;
		$dataGet['timestamp'] = date('YmdHis');
		$dataGet['mobile'] 	  = $this->mobile;
		$dataGet['orderid']	  = $this->orderId == '' ? md5(date('YmdHis')) : $this->orderId;
		ksort($dataGet);
		$buff = "";
		foreach ($dataGet as $k => $v){
		    if($k != "sign"){
		        $buff .= $k . "=" . $v . "&";
		    }
		}
		$buff = trim($buff, "&");
		$dataGet['sign'] = md5($buff);
		$dataReturn = array();
		foreach ($dataGet as $key => $row) {
			$dataReturn[] = sprintf("%s=%s", $key, $row);
		}
		$urlGet = sprintf($this->basicUrl, implode("&", $dataReturn));
		return $urlGet;
	}
	
	protected function inputCheck()			                            //检测手机号
	{
		$returnMsg['code']	  = 1;
		$returnMsg['message']   = array();
		$reg = "/^1[34578]\d{9}$/";
		if(! preg_match($reg, $this->mobile)){
			$returnMsg['code']	  = 0;
			$returnMsg['message'][] = '手机号码格式错误';
		}
		return $returnMsg;
	}
	
	/**
	 * 流量充值数额
	 */
    protected function package()
    {
        $pre = substr($this->mobile, 0, 3);
        $package = $this->package;
        $moSale  = array(
            'cncm'=> array('134', '135', '136', '137', '138', '139', '147', '150', '151','152', '157', '158', '159', '178', '182', '183', '184', '187', '188'),//移动
            'cncu'=> array('130', '131', '132', '145', '154', '155', '156', '176', '185', '186'), //联通
            'cnct'=> array('133', '153', '177', '180', '181', '189') //电信
        );
        foreach($moSale as $key=>$val){
            if(in_array($pre,$val)){
                $saleNum = $key;
            }
        }

        if($saleNum == "cncm"){ //移动
            $arr = array(10, 30, 70, 150, 500, 1024, 2048, 3072, 4096, 6144, 11264);
            if(in_array($package, $arr)){
                return $package;
            }
        }
        if($saleNum == "cncu"){ //联通
            $arr = array(20, 50, 100, 200, 500, 1024);
            if(in_array($package, $arr)){
                return $package;
            }
        }
        if($saleNum == "cnct"){ //电信
            $arr = array(5, 10, 30, 50, 100, 200, 500, 1024);
            if(in_array($package, $arr)){
                return $package;
            }
        }
        return false;
    }
}
