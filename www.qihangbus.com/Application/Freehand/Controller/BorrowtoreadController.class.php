<?php
namespace mobile\Controller;
use Think\Controller;
class BorrowtoreadController extends CommonController {
    public function index(){
    	$student_id = I('param.student_id',0);
    	$this->assign('student_id',$student_id);
        $flag = I('param.flag',1);
        $this->assign('flag',$flag);
        $user_flag = I('param.user_flag',0);
        $this->assign('user_flag',$user_flag);
    	$model = M('circulation');
    	
        //当前阅读
        if($flag == 1){
            $now_info = $model->alias('c')->join(C('DB_PREFIX').'books b ON c.book_id = b.book_id','left')->where(array('student_id'=>$student_id,'circul_status'=>1))->select();
    		foreach ($now_info as $key => $value) {
                $now_info[$key]['return_time'] = date("Y-m-d",strtotime("+1 day"));
            }
    		$this->assign('now_info',$now_info);
        }

        //即将阅读
        if($flag == 2){
    		$next_info = $model->alias('c')->join(C('DB_PREFIX').'books b ON c.book_id = b.book_id','left')->where(array('student_id'=>$student_id,'circul_status'=>2))->select();
    		//$next_info['return_time'] = date("Y-m-d",strtotime("+1 day"));	
    	    foreach ($next_info as $key => $value) {
                $next_info[$key]['return_time'] = date("Y-m-d",strtotime("+1 day"));
            }
            $this->assign('next_info',$next_info);
        }

        //赔偿管理
        if($flag == 99){
            $list = M("compensate")->join("fh_books on fh_books.book_id=fh_compensate.book_id")->where("fh_compensate.student_id=$student_id and fh_compensate.compen_status=1")->select();
            $this->assign('list',$list);
        }

        $condition['book_rand_time'] = array('gt',strtotime(date("Y-m-d")." 00:00:00"));
        $condition['student_id'] = $student_id;
        $book_rand_num = M('students')->where($condition)->getField('book_rand_num');
        if($book_rand_num < 1){
            $book_rand_num = 3;
        }
        $this->assign('book_rand_num',$book_rand_num);
		  
        if($flag == 1){
            $this->display('borrow/index_1');
        }elseif($flag == 2){
            $this->display('borrow/index_2');
        }elseif($flag == 99){
            $this->display('borrow/index_99');
        }else{
            $this->display('borrow/index_1');
        }  

        
    }

    public function return_json()
    {
        //$array = array('dList'=>array(array('d'=>'2016-11-15'),array('d'=>'2016-11-16'),array('d'=>'2016-11-17')));
        //echo json_encode($array);
        $student_id = I('get.student_id',0);
        $now_month = I('get.month',0);
        $now_year = I('get.year',0);

        $start_date = '';
        $end_date = '';

        if($now_year > 0){
            $start_date .= $now_year; 
            $end_date .= $now_year;    
        }else{
            $start_date .= date('Y');
            $end_date .= date('Y');
        }

        if($now_month > 0){
            $start_date .= '-'.$now_month.'-01  00:00:00'; 
              
            if($now_month < date('m')) {
                $end_date .= '-'.date('m').'-30 23:59:59';  
            }else{
                $end_date .= '-'.$now_month.'-'.date('d H:i:s'); 
            } 
        }else{
            $start_date .= '-'.date('m').'-01  00:00:00';
            $end_date .= '-'.date('m').'-'.date('d H:i:s');
                   
        }


        $start = $start_date;
        $end =  $end_date;
        $condition['student_id'] = $student_id;
        $condition['log_time'] = array('between',array($start,$end));
        $ret = M('circul_log')->where($condition)->select();
        //var_dump(M('circul_log')->getlastsql());
        $arr = array();
        
        foreach ($ret as $key => $value) {
            $data['d'] = date("Y-n-j",strtotime($value['log_time']));
            $arr[] = $data;
        }
        $array = array('dList'=>$arr);
        echo json_encode($array);
    }

    //阅历
    public function history()
    {
    	$student_id = I('param.student_id',0);
        $this->assign('student_id',$student_id);

        //查询当月数据
        $start = date("Y-m-01 00:00:00");
        $end = date("Y-m-d H:i:s");
        $condition['student_id'] = $student_id;
        $condition['log_time'] = array('between',array($start,$end));
        $ret = M('circul_log')->where($condition)->order('log_time asc')->select();
        
        $arr = array();

        foreach ($ret as $key => $value) {
            array_push($arr,$value['book_id']);
        }



        if($ret){
            $last = end($ret);
            $this->assign('last_day',date("Y-m-j",strtotime($last['log_time'])));
            $this->assign('now_month',date("m",strtotime($last['log_time'])));
            $this->assign('now_year',date("Y",strtotime($last['log_time'])));
            $info = M('books')->where(array('book_id'=>array('in',$arr)))->select();
            $this->assign('l',count($info));
            $this->assign('info',$info);
        }

        $total = M('circul_log')->where(array('student_id'=>$student_id))->count();
        $this->assign('total',$total);
        $this->display('borrow/history');
    }

    //异步加载数据
    public function ajax_history()
    {
        $student_id = I('get.student_id',0);
        $str_date = I('get.str_date',0);
        
        //查询当月数据
        $start = $str_date.' '.date("00:00:00");
        $end = $str_date.' 23:59:59';
        $condition['student_id'] = $student_id;
        $condition['log_time'] = array('between',array($start,$end));
        $ret = M('circul_log')->where($condition)->order('log_time asc')->select();
        
        $arr = array();
        foreach ($ret as $key => $value) {
            array_push($arr,$value['book_id']);
        }

        if($ret){
            $last = end($ret);
            $info = M('books')->where(array('book_id'=>array('in',$arr)))->select();

            $list = array();
            foreach ($info as $key => $value) {
                $list[$key]['last_day'] = date("Y-m-j",strtotime($last['log_time']));
                $list[$key]['book_thumb'] = $value['book_thumb'];
                $list[$key]['book_name'] = $value['book_name'];
                $list[$key]['book_author'] = $value['book_author'];
            }

            echo json_encode($list);
        }
        
    }

    //图书详情
    public function info()
    {
    	$book_id = I('param.book_id',0);
    	$student_id = I('param.student_id',0);

        $user_flag = I('param.user_flag',0);
        $this->assign('user_flag',$user_flag);
    	$book_info = M('books')->where(array('book_id'=>$book_id))->find();
    	$this->assign('student_id',$student_id);
    	$book_gallery = M('book_gallery')->where(array('book_id'=>$book_id))->select();
    	$this->assign('book_gallery',$book_gallery);
    	$this->assign('book_info',$book_info);
        $this->assign('book_id',$book_id);
    	$this->display('borrow/info');
    }

    /*public function add_to_borrow()
    {
        $data = array();
        $data['book_id'] = I('param.book_id',0);
        $student_id = I('param.student_id',0);
        $str = M('book_borrow')->where(array('book_id'=>$data['book_id'],'student_id'=>$student_id))->find();
        if($str){
            echo 2;
            exit;
        }
        $info = M('students')->where(array('student_id'=>$student_id))->field('school_id,class_id')->find();
        $data['student_id'] = $student_id;
        $data['school_id'] = $info['school_id'];
        $data['class_id'] = $info['class_id'];
        $data['add_time'] = time();
        $ret = M('book_borrow')->add($data);
        if($ret){
            echo 1;
        }
    }*/

    public function add_to_borrow()
    {
        $data = array();
        $data['book_id'] = I('param.book_id',0);
        $student_id = I('param.student_id',0);

		$class_id = M("students")->where(array('student_id'=>$student_id))->getField("class_id");
		
        //判断是否节约正在阅读的图书
        $num = M('circulation')->where(array('student_id'=>$student_id,'class_id'=>$class_id,'book_id'=>$data['book_id'],'circul_status'=>1))->count();
        if($num > 0){
            echo 96;
            exit;
        } 

        //判断是否有未归还的图书
        $num = M('circulation')->where(array('student_id'=>$student_id,'class_id'=>$class_id,'circul_status'=>1))->count();
        if($num > 0){
            echo 95;
            exit;
        }

		//判断是否有损坏未赔偿的记录
        $compen = M('compensate')->where(array('student_id'=>$student_id,'class_id'=>$class_id,'compen_status'=>1))->count();
        if($compen > 0){
            echo 94;
            exit;
        }	

        //判断是否超出预约限制
		//15==>2本，20=>4本，30=>4本
		$age_cate_num = C('AGE_CATE_NUM');
		$meal_type = M('schools')->join("fh_students on fh_students.school_id=fh_schools.school_id")->where(array('student_id'=>$student_id))->getField('meal_type');//获取订阅价
		$cate_num = $age_cate_num[$meal_type];
		$starttime = strtotime(date('Ymd'));
        // 获得今天24点的时间戳
        $endtime = strtotime(date('Ymd')) + 86400;
        $num = M('circulation')->where(array('student_id'=>$student_id,'class_id'=>$class_id,'circul_status'=>2))->count();
		
        $start_time = date("Y-m-d H:i:s",$starttime);
        $end_time = date("Y-m-d H:i:s",$endtime);
		$num2 = M('circul_log')->where(array('student_id'=>$student_id,'borrow_time'=>array('between',"$start_time,$end_time")))->count();
        if($num >= $cate_num){
            echo 97;
            exit;
        } 

       
        //判断是否已经是预约过的图书
        $ret = M('circulation')->where(array('book_id'=>$data['book_id'],'class_id'=>$class_id,'student_id'=>$student_id,'circul_status'=>2))->find();
        if($ret){
            echo 99;
            exit;
        }
        //判断是否被别人预约过
        $str = M('circulation')->where(array('book_id'=>$data['book_id'],'class_id'=>$class_id,'circul_status'=>2))->find();
        if($str){
            echo 98;
            exit;
        }
        

        //判断是否存在预约的信息
        //$cid = M('circulation')->where(array('student_id'=>$student_id,'circul_status'=>2))->getField('circulation_id');
        $cid = 0;
        $info = M('students')->where(array('student_id'=>$student_id))->field('school_id,class_id')->find();
        $data['student_id'] = $student_id;
        $data['school_id'] = $info['school_id'];
        $data['class_id'] = $info['class_id'];
        $data['add_time'] = time();
        $data['circul_status'] = 2;
        if($cid > 0){
            $ret = M('circulation')->where(array('circulation_id'=>$cid))->save($data);
        }else{
            $ret = M('circulation')->add($data);
        }
        if($ret){
            echo 1;
        }else{
            echo 0;
        }
    }

    //更换图书
    public function refresh()
    {
    	$book_id = I('param.book_id',0);
    	$student_id = I('param.student_id',0);
        $book_rand_num = I('param.num','0');

        if($book_rand_num == 1){
            echo 0;
            exit;
        }
    	$info = M('circulation')->where(array('book_id'=>$book_id,'student_id'=>$student_id,'circul_status'=>2))->delete();
    	$userinfo = session('userinfo');
        $book_rand_num--;

        M('students')->where(array('student_id'=>$student_id))->save(array('book_rand_num'=>$book_rand_num,'book_rand_time'=>time()));

    	$class_id = $userinfo['class_id'];
    	//查询当前班级所有的图书
    	$book_arr = M('schooltobook')->where(array('class_id'=>$class_id))->field('book_id')->select();
    	$bookarr = array();
    	foreach ($book_arr as $key => $value) {
    		array_push($bookarr,$value['book_id']);
    	}

    	//查询当前班级已经借出和待借出的
    	$class_arr = M('circulation')->where(array('class_id'=>$class_id))->field('book_id')->select();
    	unset($bookarr[array_search($book_id,$bookarr)]);
    	//剔除已经借出的图书
    	foreach ($class_arr as $key => $value) {
    		if(in_array($value['book_id'], $bookarr)){
    			unset($bookarr[array_search($value['book_id'],$bookarr)]);
    		}
    	}

    	$rand_val = array_rand($bookarr,1);
    	$ret = M('circulation')->add(array('book_id'=>$rand_val,'school_id'=>$userinfo['school_id'],'class_id'=>$userinfo['class_id'],'student_id'=>$student_id,'circul_status'=>2,'add_time'=>time()));
    	echo $book_rand_num;
    }

    public function add_to_cart()
    {
        $book_id = I('param.book_id',0);
        $data['user_id'] = I('param.student_id',0);
        $data['user_flag'] = 3;
        $data['book_number'] = I('param.book_num',0);
        $book_info = M('books')->where(array('book_id'=>$book_id))->find();
        $data['book_id'] = $book_id;
        $data['book_sn'] = $book_info['book_sn'];
        $data['book_name'] = $book_info['book_name'];
        $data['market_price'] = $book_info['market_price'];
        $data['shop_price'] = $book_info['shop_price'];
        $data['points_price'] = $book_info['points_price'];
        $data['ret_type'] = 1;

        $ret = M("cart")->add($data);
        echo $data['user_id'];
    }

    public function ok()
    {
        Vendor('WxPayPubHelper.WxPayPubHelper');   

        $book_id = I('param.book_id','');
        $student_id = I('param.student_id','');  
        $hf = I('param.hf',0);

        $jsApi = new \JsApi_pub();
        $code = I('get.code','');
        $order_id = I('get.order_id','');
        $info = M('config')->where(array('parent_id'=>13))->field('code,value')->select();
        $payment = array();
        foreach ($info as $key => $value) {
            $payment[$value['code']] = $value['value'];
        }


        define("WXAPPID", $payment['appid']);
        define("WXMCHID", $payment['mchid']);
        define("WXKEY", $payment['appkey']);
        define("WXAPPSECRET", $payment['appsecret']);
        define("WXCURL_TIMEOUT", 30);
        define('WXNOTIFY_URL',"http://".$_SERVER['HTTP_HOST'].'wx_native_callback.php');

        if(empty($code)){
            $redirect = urlencode("http://".$_SERVER['HTTP_HOST']."/mobile.php?m=mobile.php&c=Order&a=ok&student_id=$student_id&book_id=$book_id&hf=$hf");
            $url = $jsApi->createOauthUrlForCode($redirect,$payment['appid']);
            header("Location:$url");
        }else{
            $jsApi->setCode($code);
            $openid = $jsApi->getOpenid();
            
        }
 


        $info = M('students')->where(array('student_id'=>$student_id))->find();
        $binfo = M('books')->where(array('book_id'=>$book_id))->find();
        
        //判断是否全额赔偿，5元赔偿，3元赔偿
        $order_type = 3;
        $order_id = 0;
        if($hf == 2){
            $binfo['shop_price'] = 5;
            $order_type = 4;
            $order_id = $book_id;
        }elseif($hf == 1){
            $binfo['shop_price'] = 3;
            $order_type = 4;
            $order_id = $book_id;
        }else{
			$order_type = 3;
            $order_id = $book_id;
		}

        if($openid)
        {
            $unifiedOrder = new \UnifiedOrder_pub();
            
            //$order_amount = '0.01';
            
            $log_id = M('pay_log')->add(array('order_amount'=>$binfo['shop_price'],'order_id'=>$order_id,'order_type'=>$order_type,'user_id'=>$student_id,'user_flag'=>3,'log_time'=>time()));

            $unifiedOrder->setParameter("openid","$openid");//商品描述
            $unifiedOrder->setParameter("body",'损坏赔偿费用');//商品描述
            $unifiedOrder->setParameter("out_trade_no",date("YmdHis").$log_id);//商户订单号
            $unifiedOrder->setParameter("attach",strval($log_id));//商户支付日志
            $unifiedOrder->setParameter("total_fee",strval(intval($binfo['shop_price']*100)));//总金额
            //$unifiedOrder->setParameter("total_fee",1);//总金额
            $unifiedOrder->setParameter("notify_url","http://".$_SERVER['HTTP_HOST']."/wxpay/index.php");//通知地址
            $unifiedOrder->setParameter("trade_type","JSAPI");//交易类型
            $prepay_id = $unifiedOrder->getPrepayId();
            $jsApi->setPrepayId($prepay_id);
            $jsApiParameters = $jsApi->getParameters();
            $user_agent = $_SERVER['HTTP_USER_AGENT'];
            $allow_use_wxPay = true;

            if(strpos($user_agent, 'MicroMessenger') === false)
            {
                $allow_use_wxPay = false;
            }
            else
            {
                preg_match('/.*?(MicroMessenger\/([0-9.]+))\s*/', $user_agent, $matches);
                if($matches[2] < 5.0)
                {
                    $allow_use_wxPay = false;
                }
            }
            $html .= '<script language="javascript">';
            if($allow_use_wxPay)
            {
                $html .= "function jsApiCall(){";
                $html .= "WeixinJSBridge.invoke(";
                $html .= "'getBrandWCPayRequest',";
                $html .= $jsApiParameters.",";
                $html .= "function(res){";
                $html .= "WeixinJSBridge.log(res.err_msg);";
                $html .= "if(res.err_msg == 'get_brand_wcpay_request:ok'){";
                $html .= "location.href='".U('mobile.php/Order/succ')."'";
                $html .= "}";
                $html .= "}";
                $html .= ");";
                $html .= "}";
                $html .= "function callpay(){";
                $html .= 'if (typeof WeixinJSBridge == "undefined"){';
                $html .= "if( document.addEventListener ){";
                $html .= "document.addEventListener('WeixinJSBridgeReady', jsApiCall, false);";
                $html .= "}else if (document.attachEvent){";
                $html .= "document.attachEvent('WeixinJSBridgeReady', jsApiCall); ";
                $html .= "document.attachEvent('onWeixinJSBridgeReady', jsApiCall);";
                $html .= "}";
                $html .= "}else{";
                $html .= "jsApiCall();";
                $html .= "}}";
            }
            else
            {
                $html .= 'function callpay(){';
                $html .= 'alert("您的微信不支持支付功能,请更新您的微信版本")';
                $html .= "}";
            }
            $html .= '</script>';
            $html .= '<a href="javascript:void(0);" class="btn btn2" onclick="callpay()">微信支付</a>';
            //return $html;
            $this->assign('jsapi',$html);
        }
        else
        {
            $html .= '<script language="javascript">';
            $html .= 'function callpay(){';
            $html .= 'alert("请在微信中使用微信支付")';
            $html .= "}";
            $html .= '</script>';
            $html .= '<a href="javascript:void(0);" class="btn btn2" onclick="callpay()">微信支付</a>';
            //return $html;
            $this->assign('jsapi',$html);
        }

        $info['order_sn'] = get_order_sn();

        $this->assign('info',$info);
        $this->assign('binfo',$binfo);
        $this->display('TBorrow/done');
    }

    public function pay()
    {
        $book_id = I('param.book_id','');
        $student_id = I('param.student_id','');    
        $hf = I('param.hf',0);
        Vendor('WxPayPubHelper.WxPayPubHelper');   

        $jsApi = new \JsApi_pub();
        $code = I('get.code','');
        
        $info = M('config')->where(array('parent_id'=>13))->field('code,value')->select();
        $payment = array();
        foreach ($info as $key => $value) {
            $payment[$value['code']] = $value['value'];
        }
        define("WXAPPID", $payment['appid']);
        define("WXMCHID", $payment['mchid']);
        define("WXKEY", $payment['appkey']);
        define("WXAPPSECRET", $payment['appsecret']);
        define("WXCURL_TIMEOUT", 30);
        define('WXNOTIFY_URL',"http://".$_SERVER['HTTP_HOST'].'wx_native_callback.php');

        if(empty($code)){
            $redirect = urlencode("http://".$_SERVER['HTTP_HOST']."/mobile.php?m=mobile.php&c=TBorrow&a=ok&student_id=$student_id&book_id=$book_id&hf=$hf");
            $url = $jsApi->createOauthUrlForCode($redirect,$payment['appid']);
            header("Location:$url");
        }
    }
}