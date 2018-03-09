<?php
/**
 * WechatController.class.php
 * 微信公众平台
 *
 * @author world <1060077601@qq.com>
 * @version 1.0
 * @datetime 2016-06-07
 */
namespace Org\Util;
Class Wechat{

	Private $appId;
	private $signKey;
	private $mchid;

	public function __construct()
	{
		$weixin = C('weixin');
		$this->appId = $weixin['AppID'];
		$this->signKey = $weixin['signKey'];
		$this->mchid = $weixin['mchid'];
		//判断导航选中状态,S为导航ID
		cookie('s',I('get.s'),86400);
	}

	/**
	 * 登录验证
	 */
	private function power(){
		if (!session('aid')){
			$this->redirect(MODULE_NAME . '/Login/index');
			exit;
		}
	}
	/**
	 * getToken
	 * 获取access_token
	 * @return string
	 */
	private function getToken(){
		$access_token = M("config",'fh_')->where(['id'=>25])->getField('value');
		$expire_in = M("config",'fh_')->where(['id'=>26])->getField('value');
		if($expire_in-7200 < time()){
			$return = $this->getAccessToken();
			$access_token = $return['access_token'];
			$up_time = time();
			M("config",'fh_')->where(array("id"=>25))->save(['value'=>$access_token]);
			M("config",'fh_')->where(array("id"=>26))->save(['value'=>$up_time]);
		}
		return $access_token;
	}

	private function getAccessToken(){
		$weixin = C('weixin');
		$wx_url = 'https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid='.$weixin['AppID'].'&secret='.$weixin['AppSecret'];
		$return = getURL($wx_url);
		$return = json_decode($return);
		$return = (array)$return;
		return $return;
	}

	/**
	 * verfity
	 * 公众号服务器URL,入口
	 */
	public function verify()
	{
		if(!isset($_GET["echostr"])){
			$this->responseMsg();
		}else{
			if($this->checkSignature()){
				echo $_GET["echostr"];
				exit;
			}
		}
	}

	private function checkSignature()
	{
		$signature = $_GET["signature"];
		$timestamp = $_GET["timestamp"];
		$nonce = $_GET["nonce"];

		$weixin = C('weixin');
		$token = $weixin['Token'];
		$tmpArr = array($token, $timestamp, $nonce);
		sort($tmpArr, SORT_STRING);
		$tmpStr = implode( $tmpArr );
		$tmpStr = sha1( $tmpStr );

		if( $tmpStr == $signature ){
			return true;
		}else{
			return false;
		}
	}

	/**
	 * 事件回复
	 */
	private function responseMsg()
	{
		$postStr = $GLOBALS['HTTP_RAW_POST_DATA'];
		if(!empty($postStr)){

			$postObj = (array)simplexml_load_string($postStr, 'SimpleXMLElement', LIBXML_NOCDATA);

			switch($postObj['MsgType']){
				case "event":
					$this->receiveEvent($postObj);
					break;
				case "text":
					//图文回复
					$this->replyText($postObj);
					//多客服回复
					//echo $this->dkfText($postObj);
					break;
			}
		}else{
			echo "";
			exit;
		}
	}

	//处理Event事件
	private function receiveEvent($object){
		$content = "";
		switch ($object['Event']){

			//关注
			case "subscribe":
				//关注后向微信用户转账（零钱）
//				$openid = $object['FromUserName'];
//				$amount = 100;
//				$desc = '欢迎关注汀兰科技微信公众平台！';
//				$result = $this->payToUser($openid,$amount,$desc);

				//自动回复欢迎语
				$reply = M('wx_reply')->find(1);
				$content = $reply['content'];
//				if($result){
//					$content = $content.'<br/>已经向您微信零钱支付1元钱，欢迎关注汀兰科技微信公众平台!';
//				}
				echo $this->transmitText($object,$content);
				break;

			//取消关注
			case "unsubscribe":
				$content = "";
				break;

			//已关注用户扫描带参二维码
			case "SCAN":

				break;

			//上报地理位置
			case "LOCATION":
				$openid = $object['FromUserName'];   //openid
				$Latitude = $object['Latitude'];   //纬度
				$Longitude = $object['Longitude'];   //经度
				if($Latitude){
					$location = $Longitude.','.$Latitude;
					//$this->editUserLocation($useropenid, $location);
					$this->editLocation($openid,$location);
				}

				break;

		}
	}

	/**
	 * 会话时获取当前微信用户的地理经纬度，并保存入location表
	 */
	private function editLocation($openid,$location){
		$db = M('wx_location');
		$result = $db->field('id')->where("openid = '".$openid."'")->find();
		if($result){
			$db->where("openid = $openid")->save(array('location'=>$location));
		}else{
			$db->data(array('openid'=>$openid,'location'=>$location))->add();
		}
	}


	/**
	 * 会话时获取当前微信用户的地理经纬度，并保存入member表
	 */
	private function editUserLocation($useropenid, $location){
		$user = M('member');
		$resfind = $user->where("openid='".$useropenid."'")->find();
		if($resfind){
			$data['location'] = $location;
			$user->where("openid='".$useropenid."'")->save($data);
		}
	}

	//文字消息 xml回复格式
	private function transmitText($object,$content){
		$textTpl = "<xml>
			<ToUserName><![CDATA[%s]]></ToUserName>
			<FromUserName><![CDATA[%s]]></FromUserName>
			<CreateTime>%s</CreateTime>
			<MsgType><![CDATA[text]]></MsgType>
			<Content><![CDATA[%s]]></Content>
			</xml>";
		$result = sprintf($textTpl, $object['FromUserName'], $object['ToUserName'], time(), $content);
		return $result;
	}

	//关键词自动回复
	private function replyText($object){
		$ucont = $object['Content'];
		$db = M('wx_rule');
		$rule = $db->order('view desc')->select();
		if($rule){
			foreach( $rule as $k=>$v ){
				$id = $v['id'];
				$keywords = array_filter(explode(',', $v['keywords']));
				if(!empty($keywords)){
					foreach( $keywords as $n=>$m ){
						$str = strstr($ucont, $m);
						if(!empty($str)){
							//更新访问量
							$db->where("id = $id")->setInc('view',1);
							$content = str_replace("&#039;","'",$v['content']);
							$content = str_replace('&quot;','"',$content);
							echo $this->transmitText($object, $content);
						}else{
							continue;
						}
					}
				}else{
					continue;
				}
			}
			$reply = M('wx_reply')->find(2);
			$content = $reply['content'];
			echo $this->transmitText($object, $content);
		}else{

			echo '';
		}
	}

	//公众号向用户发信息 客服消息 24小时与公众号无交互失效失效
	public function sendSMS($openid,$content){
		$access_token = $this->getToken();
		$smsJson = "{
    					\"touser\": \"$openid\",
    					\"msgtype\": \"text\",
						\"text\": {
							\"content\": \"$content\"
						}
					}";
		$url = "https://api.weixin.qq.com/cgi-bin/message/custom/send?access_token=$access_token";
		$resultJson = postURL($url,$smsJson);
		echo $resultJson;
	}

	//公众号向用户发信息 模板消息 需要开通
	public function sendTplSMS($data){
		$openid = $data['openid'];
		$keyword1 = $data['keyword1'];
		$keyword2 = $data['keyword2'];
		$url = $data['url'];
		$first = $data['first'];
		$template_id = $data['template_id'];
		$smsTpl = " {
           \"touser\":\"$openid\",
           \"template_id\":\"$template_id\",
           \"url\":\"$url\",
           \"data\":{
                   \"first\": {
                       \"value\":\"$first\",
                       \"color\":\"#173177\"
                   },
                   \"keyword1\":{
                       \"value\":\"$keyword1\",
                       \"color\":\"#173177\"
                   },
                   \"keyword2\": {
                       \"value\":\"$keyword2\",
                       \"color\":\"#173177\"
                   }
           }
       	}";
		$access_token = $this->getToken();
		$url = "https://api.weixin.qq.com/cgi-bin/message/template/send?access_token=$access_token";
		$resultJson = postURL($url,$smsTpl);
		return $resultJson;
	}

	//待办事项提醒
	public function sendTplSMS1($data){
		$openid = $data['openid'];
		$keyword1 = $data['keyword1'];
		$keyword2 = $data['keyword2'];
		$keyword3 = $data['keyword3'];
		$url = $data['url'];
		$first = $data['first'];
		$template_id = $data['template_id'];
		$smsTpl = " {
           \"touser\":\"$openid\",
           \"template_id\":\"$template_id\",
           \"url\":\"$url\",
           \"data\":{
                   \"first\": {
                       \"value\":\"$first\",
                       \"color\":\"#173177\"
                   },
                   \"keyword1\":{
                       \"value\":\"$keyword1\",
                       \"color\":\"#173177\"
                   },
                   \"keyword2\": {
                       \"value\":\"$keyword2\",
                       \"color\":\"#173177\"
                   },
                   \"keyword3\": {
                       \"value\":\"$keyword3\",
                       \"color\":\"#173177\"
                   },
                   \"remark\": {
                       \"value\":\"请您尽快处理。\",
                       \"color\":\"#173177\"
                   }
           }
       	}";
		$access_token = $this->getToken();
		$url = "https://api.weixin.qq.com/cgi-bin/message/template/send?access_token=$access_token";
		$resultJson = postURL($url,$smsTpl);
		return $resultJson;
	}

	/**
	 * 微信用户向公众号支付
	 */
	//生成微信支付js参数
	public function createJsBizPackage($totalFee, $outTradeNo, $orderName,$attach)
	{
		$nonce_str='tinglan'.rand(100000, 999999);

		$openid = session('openid');
		$spbill_create_ip=$_SERVER["REMOTE_ADDR"];
		//$timestamp = time();
		$time = time();
		$timestamp = "$time";
		$notifyUrl = 'http://'.$_SERVER['HTTP_HOST'].'/Pay/notify';
		$data = array(
			'appid' => $this->appId,
			'attach' => $attach,
			'body' => $orderName,
			'mch_id' => $this->mchid,
			'nonce_str' =>$nonce_str,
			'notify_url' => $notifyUrl,
			'openid' => $openid,
			'out_trade_no' => $outTradeNo,
			'spbill_create_ip' => $spbill_create_ip,
			'total_fee' => intval($totalFee * 100),       //单位 转为分
			'trade_type' => 'JSAPI',
		);
		$data['sign'] = $this->getSign($data);

		$unified = "<xml>
						   <appid><![CDATA[".$data['appid']."]]></appid>
						   <attach><![CDATA[".$data['attach']."]]></attach>
						   <body><![CDATA[".$data['body']."]]></body>
						   <mch_id><![CDATA[".$data['mch_id']."]]></mch_id>
						   <nonce_str><![CDATA[".$data['nonce_str']."]]></nonce_str>
						   <notify_url><![CDATA[".$data['notify_url']."]]></notify_url>
						   <openid><![CDATA[".$data['openid']."]]></openid>
						   <out_trade_no><![CDATA[".$data['out_trade_no']."]]></out_trade_no>
						   <spbill_create_ip><![CDATA[".$data['spbill_create_ip']."]]></spbill_create_ip>
						   <total_fee><![CDATA[".$data['total_fee']."]]></total_fee>
						   <trade_type><![CDATA[".$data['trade_type']."]]></trade_type>
						   <sign><![CDATA[".$data['sign']."]]></sign>
						</xml>";
		$responseXml = postURL('https://api.mch.weixin.qq.com/pay/unifiedorder',$unified);
		$unifiedOrder = simplexml_load_string($responseXml, 'SimpleXMLElement', LIBXML_NOCDATA);
		if ($unifiedOrder === false) {
			die('parse xml error');
		}
		if ($unifiedOrder->return_code != 'SUCCESS') {
			die($unifiedOrder->return_msg);
		}
		if ($unifiedOrder->result_code != 'SUCCESS') {
			dump($unifiedOrder);
			die($unifiedOrder->err_code);
		}


		$jsApi = array(
			"appId" => $this->appId,
			"timeStamp" => $timestamp,
			"nonceStr" => $nonce_str,
			"package" => "prepay_id=".$unifiedOrder->prepay_id,
			"signType" => 'MD5',
		);
		$jsApi['paySign'] = $this->getSign($jsApi);
		return json_encode($jsApi);
	}

	/**
	 * refundToUser
	 * 公众号向微信用户退款
	 *
	 * @param string $out_trade_no 商户侧传给微信的订单号
	 * @param integer $total_fee 总金额(单位分)
	 * @return bool
	 */
	public function refundToUser($out_trade_no,$total_fee){
		//封装数据
		$data['appid'] = $this->appId;	//公众账号ID
		$data['mch_id'] = $this->mchid;	//商户号
		$data['nonce_str'] = 'tinglan'.rand(100000,999999);	//随机字符串
		$data['out_trade_no'] = $out_trade_no;	//商户订单号
		$data['out_refund_no'] = 'TK'.$out_trade_no;	//商户退款单号
		$data['total_fee'] = $total_fee;	//总金额
		$data['refund_fee'] = $total_fee;	//退款金额
		$data['op_user_id'] = $this->mchid;	//操作员帐号, 默认为商户号
		$data['sign'] = $this->getSign($data);
		//拼接xml
		$refundTpl = "<xml>
					   <appid><![CDATA[%s]]></appid>
					   <mch_id><![CDATA[%s]]></mch_id>
					   <nonce_str><![CDATA[%s]]></nonce_str>
					   <op_user_id><![CDATA[%s]]></op_user_id>
					   <out_refund_no><![CDATA[%s]]></out_refund_no>
					   <out_trade_no><![CDATA[%s]]></out_trade_no>
					   <refund_fee><![CDATA[%s]]></refund_fee>
					   <total_fee><![CDATA[%s]]></total_fee>
					   <transaction_id></transaction_id>
					   <sign><![CDATA[%s]]></sign>
					</xml>";
		$refundXml = sprintf($refundTpl,$data['appid'],$data['mch_id'],$data['nonce_str'],$data['mch_id'],$data['out_refund_no'],$data['out_trade_no'],$data['refund_fee'],$data['total_fee'],$data['sign']);
		$info = $this->refundPost($refundXml);
		$infoArr = (array)simplexml_load_string($info, 'SimpleXMLElement', LIBXML_NOCDATA);
		$return_code = $infoArr['return_code'];
		$result_code = $infoArr['result_code'];
		if($return_code == 'SUCCESS' && $result_code == 'SUCCESS'){
			//支付成功
			return true;
		}else{
			return false;
		}
	}

	//curl post 退款请求
	private function refundPost($data){
		$payUrl="https://api.mch.weixin.qq.com/secapi/pay/refund";
		$ch = curl_init ();
		curl_setopt ( $ch, CURLOPT_URL, $payUrl );
		curl_setopt ( $ch, CURLOPT_CUSTOMREQUEST, "POST" );
		curl_setopt ( $ch, CURLOPT_SSL_VERIFYPEER, FALSE );
		curl_setopt ( $ch, CURLOPT_SSL_VERIFYHOST, FALSE );
		curl_setopt ( $ch,CURLOPT_SSLCERT,$this->cert );
		curl_setopt ( $ch,CURLOPT_SSLKEY,$this->key );
		curl_setopt ( $ch, CURLOPT_FOLLOWLOCATION, 1 );
		curl_setopt ( $ch, CURLOPT_AUTOREFERER, 1 );
		curl_setopt ( $ch, CURLOPT_POSTFIELDS, $data );
		curl_setopt ( $ch, CURLOPT_RETURNTRANSFER, true );
		$info = curl_exec ( $ch );
		//本地会出现找不到证书错误,服务器正常
		if (curl_errno ( $ch )) {
			echo 'Errno' . curl_error ( $ch );
		}
		curl_close ( $ch );
		return $info;
	}

	/**
	 * payToArt
	 * 公众号向微信用户支付
	 *
	 * @param $openid 用户openid
	 * @param $amount 企业付款金额(单位为分)
	 * @param $desc 企业付款描述信息
	 * @param $partner_trade_no 订单编号
	 * @return bool
	 */
	public function payToArt($partner_trade_no,$openid,$amount,$desc){

		$nonce_str='tinglan'.rand(100000, 999999);
		$spbill_create_ip=$_SERVER["REMOTE_ADDR"];

		//封装数据
		//公众账号
		$data['mch_appid'] =$this->appId;
		//商户号
		$data['mchid']=$this->mchid;
		//随机字符串
		$data['nonce_str']=$nonce_str;
		//商户订单号
		$data['partner_trade_no']=$partner_trade_no;
		$data['openid']=$openid;
		//check_name[NO_CHECK：不校验真实姓名,FORCE_CHECK：强校验真实姓名（未实名认证的用户会校验失败，无法转账）,OPTION_CHECK：针对已实名认证的用户才校验真实姓名（未实名认证用户不校验，可以转账成功）]
		$data['check_name'] = 'NO_CHECK';
		//收款用户姓名
		$data['re_user_name']='微信粉丝';
		$data['amount']=$amount;
		$data['desc']=$desc;
		//调用接口的机器Ip地址
		$data['spbill_create_ip']=$spbill_create_ip;
		$data['sign'] = $this->getSign($data);

		$data = $this->transmitPay($data);
		$info = $this->payPost($data);
		$infoArr = (array)simplexml_load_string($info, 'SimpleXMLElement', LIBXML_NOCDATA);
		$return_code = $infoArr['return_code'];
		$result_code = $infoArr['result_code'];
		if($return_code == 'SUCCESS' && $result_code == 'SUCCESS'){
			//支付成功
			return true;
		}else{
			return false;
		}
	}
	//关注支付
	private function payToUser($openid,$amount,$desc){

		$nonce_str='tinglan'.rand(100000, 999999);
		$partner_trade_no='TL'.time().rand(1000, 9999);
		$spbill_create_ip=$_SERVER["REMOTE_ADDR"];

		//封装数据
		//公众账号
		$data['mch_appid'] =$this->appId;
		//商户号
		$data['mchid']=$this->mchid;
		//随机字符串
		$data['nonce_str']=$nonce_str;
		//商户订单号
		$data['partner_trade_no']=$partner_trade_no;
		$data['openid']=$openid;
		//check_name[NO_CHECK：不校验真实姓名,FORCE_CHECK：强校验真实姓名（未实名认证的用户会校验失败，无法转账）,OPTION_CHECK：针对已实名认证的用户才校验真实姓名（未实名认证用户不校验，可以转账成功）]
		$data['check_name'] = 'NO_CHECK';
		//收款用户姓名
		$data['re_user_name']='微信粉丝';
		$data['amount']=$amount;
		$data['desc']=$desc;
		//调用接口的机器Ip地址
		$data['spbill_create_ip']=$spbill_create_ip;
		$data['sign'] = $this->getSign($data);

		$data = $this->transmitPay($data);
		$info = $this->payPost($data);
		$infoArr = (array)simplexml_load_string($info, 'SimpleXMLElement', LIBXML_NOCDATA);
		$return_code = $infoArr['return_code'];
		$result_code = $infoArr['result_code'];
		if($return_code && $result_code){
			//支付成功
			return true;
		}else{
			return false;
		}
	}

	//生成支付签名
	private function getSign($Obj)
	{
		foreach ($Obj as $k => $v)
		{
			$Parameters[$k] = $v;
		}
		//签名步骤一：按字典序排序参数
		ksort($Parameters);
		$String = $this->formatBizQueryParaMap($Parameters, false);
		//签名步骤二：在string后加入KEY
		$String = $String.'&key='.$this->signKey;
		//签名步骤三：MD5加密
		$String = md5($String);
		//签名步骤四：所有字符转为大写
		$result = strtoupper($String);
		return $result;
	}


	//作用：格式化参数，签名过程需要使用

	private function formatBizQueryParaMap($paraMap, $urlencode)
	{
		$buff = "";
		ksort($paraMap);
		foreach ($paraMap as $k => $v)
		{
			if($urlencode)
			{
				$v = urlencode($v);
			}
			$buff .= $k . "=" . $v . "&";
		}
		if (strlen($buff) > 0)
		{
			$reqPar = substr($buff, 0, strlen($buff)-1);
		}
		return $reqPar;
	}

	//支付请求格式 xml
	private function transmitPay($data){
		$payTpl = "<xml>
				<mch_appid><![CDATA[%s]]></mch_appid>
				<mchid><![CDATA[%s]]></mchid>
				<nonce_str><![CDATA[%s]]></nonce_str>
				<partner_trade_no><![CDATA[%s]]></partner_trade_no>
				<openid><![CDATA[%s]]></openid>
				<check_name><![CDATA[%s]]></check_name>
				<re_user_name><![CDATA[%s]]></re_user_name>
				<amount>%s</amount>
				<desc><![CDATA[%s]]></desc>
				<spbill_create_ip><![CDATA[%s]]></spbill_create_ip>
				<sign><![CDATA[%s]]></sign>
				</xml>";
		$result = sprintf(
			$payTpl, $data['mch_appid'], $data['mchid'], $data['nonce_str'],
			$data['partner_trade_no'],$data['openid'],$data['check_name'],
			$data['re_user_name'],$data['amount'],$data['desc'],
			$data['spbill_create_ip'],$data['sign']
		);
		return $result;
	}

	//curl post 支付请求
	private function payPost($data){
		$payUrl="https://api.mch.weixin.qq.com/mmpaymkttransfers/promotion/transfers";
		$ch = curl_init ();
		curl_setopt ( $ch, CURLOPT_URL, $payUrl );
		curl_setopt ( $ch, CURLOPT_CUSTOMREQUEST, "POST" );
		curl_setopt ( $ch, CURLOPT_SSL_VERIFYPEER, FALSE );
		curl_setopt ( $ch, CURLOPT_SSL_VERIFYHOST, FALSE );
		curl_setopt ( $ch,CURLOPT_SSLCERT,$this->cert );
		curl_setopt ( $ch,CURLOPT_SSLKEY,$this->key );
		curl_setopt ( $ch, CURLOPT_FOLLOWLOCATION, 1 );
		curl_setopt ( $ch, CURLOPT_AUTOREFERER, 1 );
		curl_setopt ( $ch, CURLOPT_POSTFIELDS, $data );
		curl_setopt ( $ch, CURLOPT_RETURNTRANSFER, true );
		$info = curl_exec ( $ch );
		//本地会出现找不到证书错误,服务器正常
		if (curl_errno ( $ch )) {
			echo 'Errno' . curl_error ( $ch );
		}
		curl_close ( $ch );
		return $info;
	}

	/**
	 * 微信菜单
	 * 自定义菜单最多包括3个一级菜单，每个一级菜单最多包含5个二级菜单。
	 * 一级菜单最多4个汉字，二级菜单最多7个汉字，多出来的部分将会以“...”代替。
	 */
	Public function menu(){
		$this->power();
		$list = M('wx_menu')->select();
		foreach($list as $v){
			if($v['pid'] == 0){
				$list_f[] = $v;
				$sort[] = $v['sort'];
			}
		}
		array_multisort($sort,SORT_ASC,$list_f);
		foreach($list_f as $k_f => $v_f){
			foreach($list as $k => $v){
				if($v['pid'] == $v_f['id']){
					$list_s[$k_f][] = $v;
					$sort_s[$k_f][] = $v['sort'];
				}
			}
			foreach($list_s[$k_f] as $k_s=>$v_s){
				$list_s[$k_f][$k_s]['name'] = '&nbsp;&nbsp;&nbsp;&nbsp;— '.$v_s['name'];
			}
			if($sort_s[$k_f]){
				array_multisort($sort_s[$k_f],SORT_ASC,$list_s[$k_f]);
				$v_f_arr[$k_f][] = $v_f;
				$menu = array_merge($v_f_arr[$k_f],$list_s[$k_f]);
				foreach($menu as $v_m){
					$menu_list[] = $v_m;
				}
			}else{
				$menu_list[] = $v_f;
			}
		}
		$this->data = $menu_list;
		$this->display();
	}

	public function menuOrder(){
		$data = I('post.');
		$wx_menu = M('wx_menu');
		foreach($data as $id=>$sort){
			$result = $wx_menu->where("id = $id")->setField(['sort'=>$sort]);
			if($result){
				$access = 1;
			}
		}
		if($access == 1){
			$this->success('排序成功');
		}else{
			$this->error('排序失败');
		}
	}

	Public function menuAdd(){
		$this->power();
		if($_POST) {
			$menu['pid'] = I('post.pid', 0, int);
			$menu['name'] = I('post.name', '');
			$menu['url'] = I('post.url','');
			$menu['sort'] = I('post.sort', 0, int);
			$id = M('wx_menu')->add($menu);
			if ($id) {
				$this->success('添加成功！');
			} else {
				$this->error('添加失败！');
			}
		}else{
			$this->first_level = M('wx_menu')->field('id,name')->where('pid = 0')->order('sort')->select();
			$this->display();
		}

	}

	Public function menuEdit(){
		$this->power();
		if($_POST){
			$id = I('post.id',0,int);
			$menu['pid'] = I('post.pid', 0, int);
			$menu['name'] = I('post.name', '');
			$menu['url'] = I('post.url', '');
			$menu['sort'] = I('post.sort', 0, int);
			$result = M('wx_menu')->where("id = $id")->save($menu);
			if($result){
				$this->success('修改成功，请更新菜单！',U('Wechat/menu'));
			}else{
				$this->error('修改失败！');
			}
		}else{
			$id = I('get.id',0,int);
			$this->data = M('wx_menu')->find($id);
			$this->first_level = M('wx_menu')->field('id,name')->where('pid = 0')->order('sort')->select();
			$this->display();
		}
	}

	public function menuDel(){
		$this->power();
		$id = I('get.id',0,int);
		$db = M('wx_menu');
		$result = $db->field('id')->where("pid = $id")->find();
		if($result){
			$this->error('请先删除二级菜单！');
		}
		$result = $db->where("id = $id")->delete();
		if($result){
			$this->success('删除成功，请更新菜单！',U('Weixin/menu'));
		}else{
			$this->error('删除失败！');
		}
	}

	//同步到微信
	public function menuUpdate(){
		$this->power();
		$list = M('wx_menu')->select();
		foreach($list as $v){
			if($v['pid'] == 0){
				$list_f[] = $v;
				$sort[] = $v['sort'];
			}
		}
		array_multisort($sort,SORT_ASC,$list_f);
		foreach($list_f as $k_f => $v_f){
			foreach($list as $k => $v){
				if($v['pid'] == $v_f['id']){
					$list_s[$k_f][] = $v;
					$sort_s[$k_f][] = $v['sort'];
				}
			}
			foreach($list_s[$k_f] as $k_s=>$v_s){
				$list_s[$k_f][$k_s]['sort'] = $v_s['sort'];
				$list_s[$k_f][$k_s]['name'] = $v_s['name'];
			}
			if($sort_s[$k_f]){
				array_multisort($sort_s[$k_f],SORT_ASC,$list_s[$k_f]);
				foreach($list_s[$k_f] as $s_k=>$s_v){
					$list_s[$k_f][$s_k] = array('type'=>'view','name'=>$s_v['name'],'url'=>$s_v['url']);
				}
				$v_f_arr[$k_f]['name'] = $v_f['name'];
				$v_f_arr[$k_f]['sub_button'] = $list_s[$k_f];
				if($v_f['url']) $v_f_arr[$k_f]['url'] = $v_f['url'];
				$menu_list[] = $v_f_arr[$k_f];
			}else{
				$menu_list[] = array('type'=>'view','name'=>$v_f['name'],'url'=>$v_f['url']);
			}
		}
		$menu['button'] = $menu_list;
		$menu = stripslashes(json_encode($menu,JSON_UNESCAPED_UNICODE));
		$token = $this->getToken();
		$url = 'https://api.weixin.qq.com/cgi-bin/menu/create?access_token='.$token;
		$return = json_decode(postURL($url,$menu));
		if($return->errcode === 0){
			$this->success('更新微信菜单成功!');
		}else{
			$this->error('更新菜单失败!');
		}
	}

	/**
	 * 自动回复
	 */
	// @type 1 关注回复 2 默认回复
	public function reply($type){
		$this->power();
		$type = intval($type);
		$this->type = $type;

		if($type && $_POST){
			//上传图片
			if(!$_FILES['picurl_up']['error']) {
				$config = array(
					'mimes' => array('image/jpeg', 'image/gif', 'image/png'),
					'maxSize' => 1024 * 1024 * 10,
					'exts' => array('jpg', 'png', 'gif', 'jpeg'),
					'subName' => array('date', 'Ymd'),
					'rootPath' => './upload/',
					'savePath' => 'images/',
					'saveName' => array('uniqid', ''),
				);

				$upload = new \Think\Upload($config);

				if (!$info = $upload->uploadOne($_FILES['picurl_up'])) {
					$this->error($upload->getError());
				}

				if (!file_exists($upload->savePath)) {
					mkdir($upload->savePath, 0777);
				}
				$image = 'http://'.$_SERVER['HTTP_HOST'].trim($upload->rootPath, '.') . $info['savepath'] . $info['savename'];
			}else{
				$image = I('post.image');
			}
			$content = I('post.content','',filter_encode);
			$result = M('wx_reply')->data(array('image'=>$image,'content'=>$content))->where("type = $type")->save();
			if($result){
				$this->success('更新成功！');
			}else{
				$this->error('更新失败！');
			}
		}else{
			$reply = M('wx_reply')->find($type);
			$this->reply = $reply;
			$this->display();
		}
	}

	/**
	 * 关键词自动回复
	 */
	Public function rule(){
		$this->power();
		$db = M('wx_rule');
		$count = $db->count();
		$Page = new \Think\Page($count,10);
		$Page->rollPage = 10;
		$this->show_page = $Page->show();
		$rule = M('wx_rule')->limit($Page->firstRow,$Page->listRows)->select();
		$this->rule = $rule;
		$this->display();
	}

	Public function addRule(){
		$this->power();
		if($_POST){
			$name = I('post.name','',filter);
			$keywords = I('post.keywords','',filter);
			$content = I('post.content','',filter_encode);
			if(!$name || !$keywords || !$content){
				$this->error('请填写完整！');
			}
			$result = M('wx_rule')->add(array('name'=>$name,'keywords'=>$keywords,'content'=>$content));
			if($result){
				$this->success('添加成功！');
			}else{
				$this->error('添加失败！');
			}
		}else{
			$this->display();
		}
	}

	Public function updateRule($id){
		$db = M('wx_rule');
		if($_POST){
			$id = I('post.id',0,int);
			$name = I('post.name','',filter);
			$keywords = I('post.keywords','',filter);
			$content = I('post.content','',filter_encode);
			if(!$name || !$keywords || !$content){
				$this->error('请填写完整！');
			}
			$result = $db->where("id = $id")->save(array('name'=>$name,'keywords'=>$keywords,'content'=>$content));
			if($result){
				$this->success('修改成功！');
			}else{
				$this->error('修改失败！');
			}
		}else{
			$id = intval($id);
			$this->rule = $db->find($id);
			$this->display();
		}
	}

	Public function delRule($id){
		$id = intval($id);
		$result = M('wx_rule')->delete($id);
		if($result){
			$this->success('删除成功！');
		}else{
			$this->error('删除失败！');
		}
	}

	/**
	 * JSSDK
	 */

	public function getSignPackage() {
		$jsapiTicket = $this->getJsApiTicket();

		// 注意 URL 一定要动态获取，不能 hardcode.
		$protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off' || $_SERVER['SERVER_PORT'] == 443) ? "https://" : "http://";
		$url = "$protocol$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";

		$timestamp = time();
		$nonceStr = $this->createNonceStr();

		// 这里参数的顺序要按照 key 值 ASCII 码升序排序
		$string = "jsapi_ticket=$jsapiTicket&noncestr=$nonceStr&timestamp=$timestamp&url=$url";

		$signature = sha1($string);

		$signPackage = array(
			"appId"     => $this->appId,
			"nonceStr"  => $nonceStr,
			"timestamp" => $timestamp,
			"url"       => $url,
			"signature" => $signature,
			"rawString" => $string
		);
		return $signPackage;
	}

	//分享js参数
	public function getShareSignPackage(){
		$accessToken = $this->getAccessToken();
		$accessToken = $accessToken['access_token'];
		// 如果是企业号用以下 URL 获取 ticket
		// $url = "https://qyapi.weixin.qq.com/cgi-bin/get_jsapi_ticket?access_token=$accessToken";
		$url = "https://api.weixin.qq.com/cgi-bin/ticket/getticket?type=jsapi&access_token=$accessToken";
		$res = json_decode($this->getURL($url));
		$jsapiTicket = $res->ticket;

		// 注意 URL 一定要动态获取，不能 hardcode.
		$protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off' || $_SERVER['SERVER_PORT'] == 443) ? "https://" : "http://";
		$url = "$protocol$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";

		$timestamp = time();
		$nonceStr = $this->createNonceStr();

		// 这里参数的顺序要按照 key 值 ASCII 码升序排序
		$string = "jsapi_ticket=$jsapiTicket&noncestr=$nonceStr&timestamp=$timestamp&url=$url";

		$signature = sha1($string);

		$signPackage = array(
			"appId"     => $this->appId,
			"nonceStr"  => $nonceStr,
			"timestamp" => $timestamp,
			"signature" => $signature,
			"jsApiList" => '["onMenuShareTimeline","onMenuShareAppMessage"]'
		);
		return $signPackage;
	}

	/**
	 * curl get提交
	 */
	private function getURL($url){

		//初始化
		$ch = curl_init();
		//设置选项，包括URL
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_HEADER, 0);
		curl_setopt($ch, CURLOPT_TIMEOUT,5);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
		//执行并获取HTML文档内容
		$output = curl_exec($ch);
		//释放curl句柄
		curl_close($ch);
		//返回获得的数据
		return $output;
	}

	/**
	 * curl post提交
	 */
	private function postURL($url, $post_data){

		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		// post数据
		curl_setopt($ch, CURLOPT_POST, 1);
		// post的变量
		curl_setopt($ch, CURLOPT_POSTFIELDS, $post_data);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
		$output = curl_exec($ch);
		curl_close($ch);

		//返回获得的数据
		return $output;
	}

	private function getJsApiTicket() {
		// jsapi_ticket 应该全局存储与更新，以下代码以写入到文件中做示例
		$data = M('wx_ticket')->order('id desc')->find();
		if ($data['expire_time'] > time()) {
			$ticket = $data['jsapi_ticket'];
		} else {
			$accessToken = $this->getAccessToken();
			$accessToken = $accessToken['access_token'];
			// 如果是企业号用以下 URL 获取 ticket
			// $url = "https://qyapi.weixin.qq.com/cgi-bin/get_jsapi_ticket?access_token=$accessToken";
			$url = "https://api.weixin.qq.com/cgi-bin/ticket/getticket?type=jsapi&access_token=$accessToken";
			$res = json_decode(getUrl($url));
			$ticket = $res->ticket;
			if ($ticket) {
				$data_new['expire_time'] = time() + 7000;
				$data_new['jsapi_ticket'] = $ticket;
				M('wx_ticket')->add($data_new);
			}
		}

		return $ticket;
	}

	private function createNonceStr($length = 16) {
		$chars = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789";
		$str = "";
		for ($i = 0; $i < $length; $i++) {
			$str .= substr($chars, mt_rand(0, strlen($chars) - 1), 1);
		}
		return $str;
	}

	//获取用户基本信息
	public function getInfo($openid)
	{
		$token = $this->getToken();
		$wx_url = "https://api.weixin.qq.com/cgi-bin/user/info?access_token=$token&openid=$openid&lang=zh_CN";
		$return = getURL($wx_url);
		return json_decode($return,true);
	}

	//接收支付结果
	public function notify()
	{

		$postStr = $GLOBALS["HTTP_RAW_POST_DATA"];
		$postObj = simplexml_load_string($postStr, 'SimpleXMLElement', LIBXML_NOCDATA);
		if ($postObj === false) {
			die('parse xml error');
		}
		if ($postObj->return_code != 'SUCCESS') {
			die($postObj->return_msg);
		}
		if ($postObj->result_code != 'SUCCESS') {
			die($postObj->err_code);
		}
		$arr = (array)$postObj;
		unset($arr['sign']);
		$sign = $this->getSign($arr);
		if ($sign == $postObj->sign) {

			$out_trade_no = $postObj->out_trade_no;//商户订单号

			$userid = substr($postObj->attach,0,-2);//用户名
			$type = substr($postObj->attach,-1);//支付种类 1师傅押金 2师傅结算 3充值

			$total_fee = $postObj->total_fee;//订单金额
			//处理业务逻辑
			if($type == 1) {//师傅押金
				$data = [
					'status' => 1,
					'paytime' => time(),
				];
				$result = M('master_deposit')->where("order_no = '$out_trade_no'")->save($data);
				$result = M('user_master')->where("id = $userid")->save(['status'=>3,'deposit'=>1000]);

			}elseif($type == 2){//师傅结算
				$order_id = M('order_master')->where("trans_order_no = $out_trade_no")->getField('order_id');
				$result = M('order')->where("id = $order_id")->save(['status'=>3]);
			}elseif($type == 3){//充值
				$rr = M('user_manager')->where(array('id'=>$userid))->find();
				if($rr){
					$res = M('user_manager')->field('money')->where(array('id'=>$userid))->find();
					$total_fee = $total_fee/100;

					$data['money'] = $res['money']+ $total_fee;

					$data['id'] = $userid;

					$result = M('user_manager')->where(array('id'=>$userid))->save($data);
					$dd['order_no'] = $out_trade_no;
					$dd['paytime'] = time();
					$dd['status'] = 2;
					$ro = M('user_deposit')->where(array('order_no'=>$out_trade_no))->save($dd);
				}else{

					$res = M('users')->field('money')->where(array('id'=>$userid))->find();
					$total_fee = $total_fee/100;

					$data['money'] = $res['money']+ $total_fee;

					$data['id'] = $userid;

					$result = M('users')->where(array('id'=>$userid))->save($data);
					$dd['order_no'] = $out_trade_no;
					$dd['paytime'] = time();
					$dd['status'] = 2;
					$ro = M('user_deposit')->where(array('order_no'=>$out_trade_no))->save($dd);

				}

				if($result){
					//更新交易表
					$m['paytime'] = time();
					$m['status'] = 1;
					$m['order_no'] = $out_trade_no;
					$re = M('order_recharge')->save($m);
				}

			}elseif($type == 4){//订单支付

				$money = $total_fee*0.01;
				$rr = M('user_manager')->where(array('id'=>$userid))->find();
				$id = M('order')->field('serviceid')->where(array('order_no'=>$out_trade_no))->find();
				//file_put_contents('log.txt',$id['serviceid']);
				$id = $id['serviceid'];
				$classify = M('service_class')->field('name')->where(array('id'=>$id))->find();
				$classify = $classify['name'];
				//file_put_contents('log.txt',$classify);
				if($rr){

					switch($classify){
						case 家居保洁:
							$s_ti = C('p_jjbj')/100;
							$j_ti = C('p_jjbj_m')/100;
							$s_shou = $money*$s_ti;
							$yi = $s_shou*$j_ti;

							break;
						case 家具保养:
							$s_ti = C('p_jjby')/100;
							$j_ti = C('p_jjby_m')/100;
							$s_shou = $money*$s_ti;
							$yi = $s_shou*$j_ti;
							break;
						case 空调维修:
							$s_ti = C('p_ktwx')/100;
							$j_ti = C('p_ktwx_m')/100;
							$s_shou = $money*$s_ti;
							$yi = $s_shou*$j_ti;
							break;
						case 家电厨电:
							$s_ti = C('p_jdcd')/100;
							$j_ti = C('p_jdcd_m')/100;
							$s_shou = $money*$s_ti;
							$yi = $s_shou*$j_ti;
							break;
						case 家装水电:
							$s_ti = C('p_jzsd')/100;
							$j_ti = C('p_jzsd_m')/100;
							$s_shou = $money*$s_ti;
							$yi = $s_shou*$j_ti;
							break;
						case 开锁换锁:
							$s_ti = C('p_kshs')/100;
							$j_ti = C('p_kshs_m')/100;
							$s_shou = $money*$s_ti;
							$yi = $s_shou*$j_ti;
							break;
						case 应急维修:
							$s_ti = C('p_yjwx')/100;
							$j_ti = C('p_yjwx_m')/100;
							$s_shou = $money*$s_ti;
							$yi = $s_shou*$j_ti;
							break;
						case 保姆护理:
							$s_ti = C('p_bmhl')/100;
							$j_ti = C('p_bmhl_m')/100;
							$s_shou = $money*$s_ti;
							$yi = $s_shou*$j_ti;
							break;
					}

					$su['revenue'] = $yi;
					$su['paytime'] = time();
					$su['money'] = $money;
					$su['userid'] = $userid;
					$su['order_no'] = $out_trade_no;
					$res = M('manager_revenue')->data($su)->add();

					$ss = M('manager_revenue')->where(array('id'=>$res))->save(['order_no'=>"$out_trade_no"]);

					$res = M('user_manager')->field('money')->where(array('id'=>$userid))->find();
					$ress['money'] = $res['money'] + $su['revenue'];

					$rs = M('user_manager')->where(array('id'=>$userid))->save($ress);
				}
				$total_fee = $total_fee/100;
				$order_no = $out_trade_no;
				$data['status'] = 2;
				$data['pay_type'] = 2;
				$data['pay_time'] = time();
				$data['amount'] = "$total_fee";

				$result = M('order')->where(array('order_no'=>$order_no))->save($data);

			}
			if ($result) {
				//成功输出
				echo '<xml><return_code><![CDATA[SUCCESS]]></return_code><return_msg><![CDATA[OK]]></return_msg></xml>';
			}
			//return $postObj;
		}else{
			echo '<xml><return_code><![CDATA[FAIL]]></return_code><return_msg><![CDATA[签名失败]]></return_msg></xml>';
		}
	}

}

?>