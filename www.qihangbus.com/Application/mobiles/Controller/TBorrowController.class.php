<?php
namespace mobiles\Controller;
use Think\Controller;
class TBorrowController extends Controller {
    public function index(){
        $teacher_id=I('param.teacher_id',0);
        $circul_status=I('param.circul_status',2);

        //分组管理
        $groupid = M('teacher')->where("teacher_id = $teacher_id")->getField('groupid');
        if($groupid) {
            $stu_conditon = "and fh_students.groupid = $groupid";
        }else{
            $stu_conditon = '';
        }

        $borrow=M("circulation");
        //$borrowwhere["fh_students.teacher_id"]=$teacher_id;
        $borrowwhere["fh_circulation.class_id"]=M('teacher')->where(array('teacher_id'=>$teacher_id))->getField('class_id');
        $borrowwhere["fh_circulation.circul_status"]=$circul_status;
        $borrows=$borrow
            ->field("circulation_id,fh_circulation.book_id,circul_status,fh_circulation.student_id,fh_circulation.type,student_name,student_avatar,book_name,stb.book_no,c.class_name")
            ->join("fh_students on fh_students.student_id=fh_circulation.student_id $stu_conditon")
            ->join("fh_books on fh_books.book_id=fh_circulation.book_id")
            ->join("left join fh_schooltobook stb on stb.book_id = fh_circulation.book_id and stb.class_id = fh_circulation.class_id")
            ->join('left join fh_class c on c.class_id = stb.next_classid')
            ->where($borrowwhere)
            ->order('student_name')
            ->select();
        $carr = array();
        $school_id = M('teacher')->where(array('teacher_id'=>$teacher_id))->getField('school_id');
        $ret = M('compensate')->where(array('school_id'=>$school_id,'compen_status'=>array('lt',3)))->select();

        $status_name = array();
        $statuss = array('1'=>'赔偿中','2'=>'已付款','3'=>'已入库');
        foreach ($ret as $key => $value) {
            array_push($carr, $value['book_id']);
            $status_name[$value['book_id']] = $statuss[$value['compen_status']];
        }

        /*$sql = "select student_id from fh_students_parent where student_id in (select student_id from fh_students where school_id = $school_id and class_id = {$borrowwhere["fh_circulation.class_id"]})";
        $temp = M()->query($sql);
        foreach($temp as $k=>$v){
            $student_id[] = $v['student_id'];
        }*/

        foreach ($borrows as $key => $value) {
            if($circul_status == 2 && in_array($value['book_id'], $carr)){
                unset($borrows[$key]);
                continue;
            }

            /*if(!in_array($value['student_id'],$student_id)){
                unset($borrows[$key]);
                continue;
            }*/

            if(in_array($value['book_id'], $carr)){
                $borrows[$key]['flag'] = 1;
                $borrows[$key]['compen'] = $status_name[$value['book_id']];
            }else{
                $borrows[$key]['flag'] = 0;
                $borrows[$key]['compen'] = '';
            }
        }
        //查询班级信息
        $class_id = M('teacher')->where(array('teacher_id'=>$teacher_id))->getField("class_id");
        //查询是否有未到图书（轮转后）
        $rotate_msg = M('class')->where("class_id = $class_id")->getField('rotate_msg');
        if($rotate_msg) $rotate_msg = substr($rotate_msg,0,-1);
        $this->assign('class_id',$class_id);
        $this->assign('rotate_msg',$rotate_msg);
        $this->assign("borrows",$borrows);
        $this->assign("circul_status",$circul_status);
        $this->assign("teacher_id",$teacher_id);
        if($circul_status==1){
            $this->display("TBorrow/index_1");
        }else{
            $this->display("TBorrow/index_2");
        }

    }

    //批量借阅
    public function batch_borrow()
    {
        $sids = I("post.sid","");
        $bookids = I("post.bid","");
        foreach($sids as $k=>$v){
            //更新借阅状态和时间
            M("circulation")
                ->where("student_id = $v and book_id = {$bookids[$k]} and circul_status = 2")
                ->save(array('circul_status'=>1,'borrow_time'=>time()));
        }
        echo 99;
    }

    //批量归还
    public function batch_return()
    {
        $sids = I("post.sid","");
        $bookids = I("post.bid","");
        foreach($sids as $k=>$v){
            $info = M("circulation")->where(array('book_id'=>$bookids[$k],'student_id'=>$v,'circul_status'=>1))->find();
            $next_classid = M('schooltobook')->where("class_id = {$info['class_id']} and book_id = {$info['book_id']}")->getField('next_classid');
            if($next_classid){
                M('schooltobook')->where("class_id = {$info['class_id']} and book_id = {$info['book_id']}")->save(array('class_id'=>$next_classid,'next_classid'=>0));
            }
            $sinfo = session('teacher');
            $data = array();
            $data['book_id'] = $info['book_id'];
            $data['teacher_id'] = $sinfo['teacher_id'];
            $data['school_id'] = $info['school_id'];
            $data['grade_id'] = $sinfo['grade_id'];
            $data['class_id'] = $info['class_id'];
            $data['student_id'] = $info['student_id'];
            $data['borrow_time'] = date("Y-m-d H:i:s",$info['borrow_time']);
            $data['return_time'] = date("Y-m-d H:i:s");
            $data['circul_status'] = 1;
            $data['log_time'] = date('Y-m-d H:i:s');
            M('circul_log')->add($data);

            M("circulation")->where(array('circulation_id'=>$info['circulation_id']))->delete();
            //更新借阅次数（试用）
            M('students')->where(['student_id'=>$v])->setInc('borrow_count');
        }
        echo 99;
    }

    //评价
    public function evaluate()
    {
        $teacher_id=I('get.teacher_id',0);
        $circul_status=I('get.circul_status',3);
        $this->assign("circul_status",$circul_status);
        $this->assign("teacher_id",$teacher_id);

        //查询出对应的班级
        $class_id = M('teacher')->where(array('teacher_id'=>$teacher_id))->getField('class_id');

        //查询当前借阅的学生
        $student_list = M("circulation c")
            ->join("fh_students s on c.student_id=s.student_id")
            ->where("c.class_id=$class_id and circul_status=1")
            ->field("s.*,book_id")
            ->group('c.student_id')
            ->select();

        //查询当天评价的学生
        $starttime = strtotime(date('Ymd'));
        $endtime = strtotime(date('Ymd')) + 86400;
        $eval_arr = M('evaluate_detail')->where(['class_id'=>$class_id,'add_time'=>['between',"$starttime,$endtime"]])->select();
        foreach ($eval_arr as $v) {
            $eval[] = $v['student_id'];
        }

        //判断是否完成任务
        foreach ($student_list as $k => $v)
        {
            $temp = M('student_task st')
                ->join('left join fh_task t on t.task_id = st.task_id')
                ->where(['st.student_id'=>$v['student_id']])
                ->order('st_id desc')
                ->limit(2)
                ->field('task_book_id')
                ->select();
            if(in_array($v['student_id'],$eval)){
                unset($student_list[$k]);
                continue;
            }
            foreach($temp as $v1){
                if($v['book_id'] == $v1['task_book_id']){
                    $list[] = $v;
                    unset($student_list[$k]);
                }
            }
        }

        $this->assign("list",$list);
        $this->assign('circulation_list',$student_list);

        $this->display("TBorrow/evaluate");
    }

    //任务评价
    public function task_comment()
    {
        $student_id = I('get.student_id','');
        $eval_type = I('get.eval_type',1);
        $flag = I('get.flag',0);
        $this->assign('flag',$flag);
        $this->assign('student_id',$student_id);
        $this->assign('eval_type',$eval_type);

        $teacher_id=I('param.teacher_id',0);
        $circul_status=I('param.circul_status',3);
        $this->assign("circul_status",$circul_status);
        $this->assign("teacher_id",$teacher_id);

        $finish = C('FINISH');
        $unfinish = C('UNFINISH');
        if($eval_type == 1){
            $this->assign('list',$finish);
        }elseif($eval_type == 2){
            $this->assign('list',$unfinish);
        }

        $this->display("TBorrow/task_comment");
    }

    public function comment_handle()
    {
        $student_id = I('post.student_id','');

        $eval_type = I('post.eval_type',1);
        $content = I('post.content','');
        $flag = I('get.flag',0);
        $teacher_id=I('post.teacher_id',0);
        $circul_status=I('post.circul_status',3);
        $info = M('students')->where(array('student_id'=>array('in',$student_id)))->find();

        $st = array();
        if(strpos($student_id,',') > 0){
            $st = explode(",",$student_id);
        }else{
            array_push($st,$student_id);
        }

        for($i=0;$i<count($st);$i++){
            M('evaluate_detail')->add(array('school_id'=>$info['school_id'],'class_id'=>$info['class_id'],'student_id'=>$st[$i],'add_time'=>time(),'content'=>$content,'eval_type'=>$eval_type));
        }

        $teacher_name = M("teacher")->where(array('teacher_id'=>$teacher_id))->getField("teacher_name");



        //推送消息
        for($i=0;$i<count($st);$i++){
            M('message')->add(array('sender_id'=>0,'sender_name'=>$teacher_name,'receiver_id'=>$st[$i],'sent_time'=>time(),'message'=>$content,'user_flag'=>3));
        }

        for($i=0;$i<count($st);$i++){
            M('students')->where(array('student_id'=>$st[$i]))->setInc('message_num',1);
        }
        if($flag < 1){
            M('teacher')->where(array('teacher_id'=>$teacher_id))->setInc('rank_points',5);
            M('points_record')->add(array('user_id'=>$teacher_id,'student_points'=>5,'change_time'=>time(),'change_desc'=>'评论金豆','change_type'=>1,'user_flag'=>2));
        }

        if($eval_type == 1){
            $msg = "评论成功";
        }else{
            $msg = "提醒成功";
        }

        $this->success($msg,U('mobile.php/TBorrow/evaluate',array('teacher_id'=>$teacher_id,'circul_status'=>$circul_status)));
    }

    //更换图书
    public function books()
    {
        $circulation_id = I('get.circulation_id','');
        $this->assign("circulation_id",$circulation_id);

        $teacher_id = I('get.teacher_id','');
        $this->assign("teacher_id",$teacher_id);

        $student_id = I('get.student_id','');
        $this->assign("student_id",$student_id);

        $circul_status = I('get.circul_status','');
        $this->assign("circul_status",$circul_status);

        //查询当前预约信息
        $info = M("circulation")->where(array('circulation_id'=>$circulation_id,'circul_status'=>2))->find();

        $book_arr = array();
        //查询出当前班级所有的图书
        $books = M("schooltobook")->join("fh_books on fh_books.book_id=fh_schooltobook.book_id")->where(array('school_id'=>$info['school_id'],'class_id'=>$info['class_id']))->order('book_no')->select();

        //删除重复的记录
        $list = array();
        foreach($books as $key=>$val)
        {
            array_push($book_arr,$val['book_id']);
            $list[$val['book_id']] = $val;
        }

        //查询已经被预约过的和已经借出去的图书
        $booked = M("circulation")->where(array('school_id'=>$info['school_id'],'class_id'=>$info['class_id']))->field("book_id")->select();

        foreach ($booked as $key => $value) {
            if(in_array($value['book_id'], $book_arr))
            {
                unset($list[$value['book_id']]);
            }
        }

        //查询当前学生已经看过的图书
        $log = M("circul_log")->where(array('school_id'=>$info['school_id'],'class_id'=>$info['class_id'],'student_id'=>$student_id))->field("book_id")->select();
        foreach ($log as $key => $value) {
            if(in_array($value['book_id'], $book_arr))
            {
                unset($list[$value['book_id']]);
            }
        }
        $this->assign("list",$list);
        $this->display("TBorrow/books");
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
        if($hf == 1){
            $binfo['shop_price'] = 5;
            $order_type = 4;
            $order_id = $book_id;
        }elseif($hf < 1){
            $binfo['shop_price'] = 3;
            $order_type = 4;
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

    //临时用，推送赔偿
    public function pushmsg()
    {
        $book_id = '2362';
        $student_id = '1711';
        //精装赔偿2，普通赔偿1
        $hf = '2';
        $info = M('students')->where(array('student_id'=>$student_id))->find();
        $binfo = M('books')->where(array('book_id'=>$book_id))->find();

        $url = U('mobile.php/TBorrow/pay',array('student_id'=>$student_id,'book_id'=>$book_id,'hf'=>$hf));

        if($hf == 99){
            $message = '尊敬的'.$info["student_name"].'家长您好，该绘本《'.$binfo["book_name"].'》经检查发现有损坏情况，造成无法继续使用，请您及时归还（<a href="'.$url.'">点击付款</a>），以免影响您的正常借阅。';
            $compen_type = 1;
        }else{
            $message = '尊敬的'.$info["student_name"].'家长您好，该绘本《'.$binfo["book_name"].'》经检查发现有损坏情况，请您及时归还（<a style="color:red;" href="'.$url.'">点击付款</a>），以免影响您的正常借阅。';
            $compen_type = 2;
        }
        $ret = M('message')->add(array('sender_id'=>0,'sender_name'=>'系统消息','receiver_id'=>$student_id,'sent_time'=>time(),'message'=>$message,'user_flag'=>3));
        M('students')->where(array('student_id'=>$student_id))->setInc('message_num',1);

        M('compensate')->add(array('school_id'=>$info['school_id'],'grade_id'=>$info['grade_id'],'class_id'=>$info['class_id'],'student_id'=>$info['student_id'],'add_time'=>time(),'book_id'=>$binfo['book_id'],'book_price'=>$binfo['shop_price'],'compen_type'=>$compen_type,'compen_status'=>1,'message_num'=>1));

        echo 99;

    }

    public function push_msg()
    {
        $book_id = I('post.bid','');
        $student_id = I('post.sid','');
        $hf = I('post.hf',0);
        $info = M('students')->where(array('student_id'=>$student_id))->find();
        $binfo = M('books')->where(array('book_id'=>$book_id))->find();
        $book_no = M('schooltobook')->where(['class_id'=>$info['class_id'],'book_id'=>$book_id])->getField('book_no');

        $url = U('mobile.php/TBorrow/pay',array('student_id'=>$student_id,'book_id'=>$book_id,'hf'=>$hf));
        $compen_type = 1;
        if($hf == 99){
            $message = '尊敬的'.$info["student_name"].'家长您好，该绘本《'.$binfo["book_name"].'》经检查发现有损坏情况，造成无法继续使用，请您及时归还（<a href="'.$url.'">点击付款</a>），以免影响您的正常借阅。';
            $compen_type = 99;
        }else{
            $message = '尊敬的'.$info["student_name"].'家长您好，该绘本《'.$binfo["book_name"].'》经检查发现有损坏情况，请您及时归还（<a style="color:red;" href="'.$url.'">点击付款</a>），以免影响您的正常借阅。';
            if($hk == 1) $compen_type = 2;
        }
        $ret = M('message')->add(array('sender_id'=>0,'sender_name'=>'系统消息','receiver_id'=>$student_id,'sent_time'=>time(),'message'=>$message,'user_flag'=>3));
        M('students')->where(array('student_id'=>$student_id))->setInc('message_num',1);

        M('compensate')->add(array('school_id'=>$info['school_id'],'grade_id'=>$info['grade_id'],'class_id'=>$info['class_id'],'student_id'=>$info['student_id'],'add_time'=>time(),'book_id'=>$binfo['book_id'],'book_no'=>$book_no,'book_price'=>$binfo['shop_price'],'compen_type'=>$compen_type,'compen_status'=>1,'message_num'=>1));

        echo 99;
        /*$res = M('config')->where(array('parent_id'=>array('in',"13,99")))->field('code,value')->select();
        $options = array();
        $weixin_options = array('appid','appsecret','appsecret','access_token','expire_in');
        foreach ($res as $key => $value) {
            if(in_array($value['code'], $weixin_options)){
                $options[$value['code']] = $value['value'];
            }
        }*/



        /*$data="['touser'=>$options['appid'],'template_id'=>模板id,'url'=>'链接url','topcolor'=>'#FF0000',
              'data'=>array(
                  'toName'=>array('value'=>'内容1','color'=>'#173177'),
                  'gift'=>array('value'=>'内容2<span style='font-family: Arial, Helvetica, sans-serif;''>,'color'=>'#173177'),</span>'
                  'time'=>array('value'=>'date('Y-m-d h:i:s',time())','color'=>'#173177'),
                  'remark'=>array('value'=>'内容3','color'=>'#173177')
              )
          ]";  */
        //$result = curl_post_send_information($options['access_token'],json_encode($data));
        //家长你好，该绘本经检查发现有损坏情况，造成无法继续使用，请你及时归还（点击采购），以免影响你的正常借阅。
    }

    //获取用户及书本信息
    private function getInfo(){
        $circulation=M("circulation");
        $circulation_id=I("param.circulation_id",0);
        $circulations=$circulation->
        field("circulation_id,fh_books.book_sn,fh_books.book_id,fh_books.book_name,fh_books.book_thumb,fh_students.student_id,fh_students.student_name")->
        join("fh_books on fh_books.book_id=fh_circulation.book_id")->
        join("fh_students on fh_students.student_id=fh_circulation.student_id")->
        where("circulation_id=".$circulation_id)->
        find();

        $this->assign("circulations",$circulations);
    }

    //更换图书
    public function randombook()
    {
        $circulation_id=I("post.circulation_id",0);
        $info = M("circulation")->where(array('circulation_id'=>$circulation_id))->find();

        $book_id = I("post.book_id","");


        //更新当前学生的预约图书
        $ret = M("circulation")->where(array('circulation_id'=>$circulation_id))->save(array('book_id'=>$book_id));

        echo json_encode($ret);
    }

    //查询借阅信息
    public function getborrow(){
        $circulation_id=I("param.circulation_id",0);
        $this->assign('circulation_id',$circulation_id);
        $this->getInfo($circulation_id);
        $teacher_id=I('param.teacher_id',0);
        $this->assign('user_id',$teacher_id);
        $student_id=I('param.student_id',0);
        $this->assign('student_id',$student_id);
        $circul_status=I('param.circul_status',2);
        $this->assign('circul_status',$circul_status);
        $ret = M('circulation')->where(array('student_id'=>$student_id,'circul_status'=>1))->find();
        if($ret){
            $this->error('还有图书未归还',U('mobile.php/TBorrow/index',array('teacher_id'=>$teacher_id,'circul_status'=>$circul_status)));
            exit;
        }
        $this->display("borrow");
    }

    //查询归还信息
    public function getborrowReturn(){
        $circulation_id=I("param.circulation_id",0);
        $this->getInfo($circulation_id);
        $teacher_id = I('param.teacher_id',0);
        Vendor('Weixin.jssdk');
        $wx_info = M('config')->where(array('parent_id'=>13))->field('code,value')->select();
        $wx_arr = array();
        foreach ($wx_info as $key => $value) {
            $wx_arr[$value['code']] = $value['value'];
        }
        $jssdk = new \JSSDK($wx_arr['appid'], $wx_arr['appsecret']);
        $signPackage = $jssdk->GetSignPackage();

        $this->assign('signPackage',$signPackage);
        $this->assign('teacher_id',$teacher_id);
        $this->display("borrowReturn");
    }

    //保存借阅信息
    public function setborrow(){
        $circulation_id=I("param.circulation_id",0);
        $book_sn = I('param.book_sn','');
        $teacher_id=session("teacher_id");
        $circulation=M("circulation");
        $data["circul_status"] = 1;
        $data["borrow_time"]=time();
        $where["circulation_id"]=$circulation_id;

        //查询是否有未归还的图书
        $info = $circulation->where(array('book_id'=>$book_id,'student_id'=>$student_id,'circul_status'=>1))->find();
        if(!empty($info)){
            echo 99;
            $this->error('还有未归还的图书',U('mobile.php/TBorrow/index',array('teacher_id'=>$teacher_id,'circul_status'=>2)));
            exit;
        }

        //加入到借阅记录表中
        /*$info = $circulation->where(array('circulation_id'=>$circulation_id))->find();
        $teacher_id = M('students')->where(array('student_id'=>$info['student_id']))->getField('teacher_id');
        $data = array();
        $data['book_id'] = $info['book_id'];
        $data['teacher_id'] = $teacher_id;
        $data['class_id'] = $info['class_id'];
        $data['student_id'] = $info['student_id'];
        $data['borrow_time'] = $info['borrow_time'];
        $data['return_time'] = date("Y-m-d H:i:s");
        $data['circul_status'] = 1;
        $data['log_time'] = date('Y-m-d H:i:s');
        M('circul_log')->add($data);*/
        //清除借阅记录
        //$circulation->where(array('circulation_id'=>$circulation_id))->delete();

        $circulation->where($where)->data($data)->save();
        $students=M("students");
        $students->where("student_id=(select student_id from fh_circulation where circulation_id=".$circulation_id.")")->data("readbook=1")->save();

        $books=M("books");
        $books->where("book_id=(select book_id from fh_circulation where circulation_id=".$circulation_id.")")->data("book_status=3")->save();

        $this->success('提交成功',U('mobile.php/TBorrow/index',array('teacher_id'=>$teacher_id,'circul_status'=>2)));
    }

    //保存归还信息
    public function setborrowReturn(){
        $circulation_id=I("param.circulation_id",0);
        $teacher_id=session("teacher_id");
        $circulation=M("circulation");
        $data["circul_status"] = 3;
        $data["return_time"]=time();
        $where["circulation_id"]=$circulation_id;



        $circulation->where($where)->data($data)->save();

        $students=M("students");
        $students->where("student_id=(select student_id from fh_circulation where circulation_id=".$circulation_id.")")->data("readbook=0")->save();

        $books=M("books");
        $books->where("book_id=(select book_id from fh_circulation where circulation_id=".$circulation_id.")")->data("book_status=1")->save();
        $this->success('提交成功',U('mobile.php/TBorrow/index',array('teacher_id'=>$teacher_id,'circul_status'=>1)));
    }

    //保存借阅信息
    /*public function setborrow2(){
        //$circulation_id=I("param.circulation_id",0);
        $book_sn = I('param.book_sn','');
        $student_id = I('param.student_id','');
        $teacher_id=session("teacher_id");
        $circulation=M("circulation");

        $book_id = M('books')->where(array("book_isbn"=>$book_sn))->getField('book_id');

		$circulation_id = M("circulation")->where(array('book_id'=>$book_id,'circul_status'=>1))->getField("circulation_id");

		if(empty($book_id)){
			echo 99;
            exit;
		}

        //加入到借阅记录表中
        $info = M("students")->where(array('student_id'=>$student_id))->find();
        if(empty($info)){
            echo 98;
            exit;
        }
        $teacher_id = M('students')->where(array('student_id'=>$info['student_id']))->getField('teacher_id');
        $grade_id = M('students')->where(array('student_id'=>$info['student_id']))->getField('grade_id');
        $data = array();
        $data['book_id'] = $book_id;
        $data['teacher_id'] = $info['teacher_id'];
        $data['school_id'] = $info['school_id'];
        $data['grade_id'] = $info['grade_id'];
        $data['class_id'] = $info['class_id'];
        $data['student_id'] = $info['student_id'];
        $data['borrow_time'] = date("Y-m-d H:i:s");
        $data['return_time'] = date("Y-m-d H:i:s");
        $data['circul_status'] = 1;
        $data['log_time'] = date('Y-m-d H:i:s');
        M('circul_log')->add($data);

        //清除借阅记录
        $circulation->where(array('circulation_id'=>$circulation_id ))->delete();

        //$students=M("students");
        //$students->where("student_id=(select student_id from fh_circulation where circulation_id=".$info['circulation_id'].")")->data("readbook=1")->save();

        //$books=M("books");
        //$books->where("book_id=(select book_id from fh_circulation where circulation_id=".$info['circulation_id'].")")->data("book_status=3")->save();

        echo 1;
        //$this->success('提交成功',U('mobile.php/TBorrow/index',array('teacher_id'=>$teacher_id,'circul_status'=>2)));
    } */

    //备份确认归还
    public function setborrow2(){
        //$circulation_id=I("param.circulation_id",0);
        $book_sn = I('param.book_sn','');
        $student_id = I('param.student_id','');
        $teacher_id=session("teacher_id");
        $circulation=M("circulation");

        $book_id = M('books')->where("book_isbn=$book_sn OR book_sn=$book_sn")->getField('book_id');

        if(empty($book_id)){
            echo 99;
            exit;
        }

        //加入到借阅记录表中
        $info = $circulation->where(array('book_id'=>$book_id,'student_id'=>$student_id))->find();
        if(empty($info)){
            echo 98;
            exit;
        }
        $teacher_id = M('students')->where(array('student_id'=>$info['student_id']))->getField('teacher_id');
        $grade_id = M('students')->where(array('student_id'=>$info['student_id']))->getField('grade_id');
        $data = array();
        $data['book_id'] = $info['book_id'];
        $data['teacher_id'] = $teacher_id;
        $data['school_id'] = $info['school_id'];
        $data['grade_id'] = $grade_id;
        $data['class_id'] = $info['class_id'];
        $data['student_id'] = $info['student_id'];
        $data['borrow_time'] = date("Y-m-d H:i:s",$info['borrow_time']);
        $data['return_time'] = date("Y-m-d H:i:s");
        $data['circul_status'] = 1;
        $data['log_time'] = date('Y-m-d H:i:s');
        M('circul_log')->add($data);

        //清除借阅记录
        $circulation->where(array('circulation_id'=>$info['circulation_id']))->delete();

        $students=M("students");
        $students->where("student_id=(select student_id from fh_circulation where circulation_id=".$info['circulation_id'].")")->data("readbook=1")->save();

        $books=M("books");
        $books->where("book_id=(select book_id from fh_circulation where circulation_id=".$info['circulation_id'].")")->data("book_status=3")->save();

        echo 1;
        //$this->success('提交成功',U('mobile.php/TBorrow/index',array('teacher_id'=>$teacher_id,'circul_status'=>2)));
    }

    //损坏赔偿
    public function indemnify(){
        $teacher_id=I('param.teacher_id',0);

        //分组管理
        $groupid = M('teacher')->where("teacher_id = $teacher_id")->getField('groupid');
        if($groupid) {
            $stu_conditon = "and fh_students.groupid = $groupid";
        }else{
            $stu_conditon = '';
        }

        $circul_status=1;
        $borrow=M("circulation");
        $borrowwhere["fh_circulation.class_id"]=M('teacher')->where(array('teacher_id'=>$teacher_id))->getField('class_id');
        $borrowwhere["fh_circulation.circul_status"]=$circul_status;
        $borrows=$borrow
            ->field("circulation_id,fh_circulation.book_id,circul_status,fh_circulation.student_id,student_name,student_avatar,book_name,stb.book_no")
            ->join("fh_students on fh_students.student_id=fh_circulation.student_id $stu_conditon")
            ->join("fh_books on fh_books.book_id=fh_circulation.book_id")
            ->join("left join fh_schooltobook stb on stb.book_id = fh_circulation.book_id and stb.class_id = fh_circulation.class_id")
            ->where($borrowwhere)
            ->order('stb.book_no')
            ->select();

        $carr = array();
        $school_id = M('teacher')->where(array('teacher_id'=>$teacher_id))->getField('school_id');
        $ret = M('compensate')->where(array('school_id'=>$school_id,'compen_status'=>array('lt',3)))->select();

        $status_name = array();
        $statuss = array('1'=>'赔偿中','2'=>'已付款','3'=>'已入库');
        foreach ($ret as $key => $value) {
            array_push($carr, $value['book_id']);
            $status_name[$value['book_id']] = $statuss[$value['compen_status']];
        }


        foreach ($borrows as $key => $value) {
            if($circul_status == 2 && in_array($value['book_id'], $carr)){
                unset($borrows[$key]);
                continue;
            }

            if(in_array($value['book_id'], $carr)){
                $borrows[$key]['flag'] = 1;
                $borrows[$key]['compen'] = $status_name[$value['book_id']];
            }else{
                $borrows[$key]['flag'] = 0;
                $borrows[$key]['compen'] = '';
            }
        }


        //查询班级信息
        $class_id = M('teacher')->where(array('teacher_id'=>$teacher_id))->getField("class_id");
        $this->assign('class_id',$class_id);
        $this->assign("borrows",$borrows);
        $this->assign("circul_status",$circul_status);
        $this->assign("teacher_id",$teacher_id);
        $this->display();
    }

    //赔偿记录
    public function compenRecord()
    {
        $user = session('user');
        $teacher_id = $user['id'];
        $class_id = M('teacher')->where(['teacher_id'=>$teacher_id])->getField('class_id');
        $data = M('compensate')->where(['class_id'=>$class_id,'compen_status'=>2])->select();
        $this->assign('teacher_id',$teacher_id);
        $this->assign('data',$data);
        $this->display();
    }

    //借阅记录
    public function loanRecord()
    {
        $teacher_id=I('param.teacher_id',0);
        $circul_status=1;
        $borrow=M("circulation");
        //$borrowwhere["fh_students.teacher_id"]=$teacher_id;
        $borrowwhere["fh_circulation.class_id"]=M('teacher')->where(array('teacher_id'=>$teacher_id))->getField('class_id');
        $borrowwhere["fh_circulation.circul_status"]=$circul_status;
        $borrows=$borrow
            ->field("circulation_id,fh_circulation.book_id,circul_status,fh_circulation.student_id,student_name,student_avatar,book_name,stb.book_no,fh_circulation.borrow_time")
            ->join("fh_students on fh_students.student_id=fh_circulation.student_id")
            ->join("fh_books on fh_books.book_id=fh_circulation.book_id")
            ->join("left join fh_schooltobook stb on stb.book_id = fh_circulation.book_id and stb.class_id = fh_circulation.class_id")
            ->where($borrowwhere)
            ->order('stb.book_no')
            ->select();
        $carr = array();
        $school_id = M('teacher')->where(array('teacher_id'=>$teacher_id))->getField('school_id');
        $ret = M('compensate')->where(array('school_id'=>$school_id,'compen_status'=>array('lt',3)))->select();

        $status_name = array();
        $statuss = array('1'=>'赔偿中','2'=>'已付款','3'=>'已入库');
        foreach ($ret as $key => $value) {
            array_push($carr, $value['book_id']);
            $status_name[$value['book_id']] = $statuss[$value['compen_status']];
        }


        foreach ($borrows as $key => $value) {
            if($circul_status == 2 && in_array($value['book_id'], $carr)){
                unset($borrows[$key]);
                continue;
            }

            if(in_array($value['book_id'], $carr)){
                $borrows[$key]['flag'] = 1;
                $borrows[$key]['compen'] = $status_name[$value['book_id']];
            }else{
                $borrows[$key]['flag'] = 0;
                $borrows[$key]['compen'] = '';
            }
        }
        //查询班级信息
        $class_id = M('teacher')->where(array('teacher_id'=>$teacher_id))->getField("class_id");
        $this->assign('class_id',$class_id);
        $this->assign("borrows",$borrows);
        $this->assign("circul_status",$circul_status);
        $this->assign("teacher_id",$teacher_id);
        $this->display("TBorrow/loanRecord");
    }

    //借阅撤销
    public function loanRevoke()
    {
        if(IS_POST){//批量操作
            $teacher_id = I('post.teacher_id');
            $circulation_ids = I('post.cids');
            $ids = explode(',',$circulation_ids);
            $circulation = M('circulation');
            foreach($ids as $v){
                $result = $circulation->where("circulation_id = $v")->save(array('circul_status'=>2,'borrow_time'=>''));
            }
        }else{//单个操作
            $circulation_id = I('get.circulation_id');
            $teacher_id = I('teacher_id');
            $result = M('circulation')->where("circulation_id = $circulation_id")->save(array('circul_status'=>2,'borrow_time'=>''));
        }
        if($result){
            $this->success('撤销成功！',U('mobile.php/TBorrow/index',array('teacher_id'=>$teacher_id,'circul_status'=>2)));
        }else{
            $this->error('撤销失败！');
        }
    }

    //确认轮换书籍已还
    public function rotateReturn()
    {
        $classid = I('get.classid');
        $result = M('class')->where("class_id = $classid")->setField('rotate_msg','');
        if($result){
            $this->success('操作成功!');
        }else{
            $this->error('操作失败!');
        }
    }

    //归还记录
    public function returnRecord()
    {
        $teacher_id = I('param.teacher_id',0);
        $return = M('circul_log');
        // $time = $return->where(array('teacher_id'=>$teacher_id))->order('log_id desc')->limit(1)->find();
        // $time = substr($time['return_time'],0,10);
        $time = Date("Y-m-d",time());

        $returns = $return
            ->join("fh_students on fh_students.student_id=fh_circul_log.student_id")
            ->join("fh_books on fh_books.book_id=fh_circul_log.book_id")
            ->join("left join fh_schooltobook stb on stb.book_id = fh_circul_log.book_id and stb.class_id = fh_circul_log.class_id")->
            where("fh_circul_log.teacher_id = $teacher_id AND Date(return_time)='$time'")->select();
        // var_dump($returns);die;
        $this->assign('returns',$returns);
        $this->assign("teacher_id",$teacher_id);
        $this->display('TBorrow/returnRecord');
    }

    // 归还撤销
    public function returnRevoke()
    {
        if(IS_POST){
            $circul_log = M('circul_log');
            $teacher = M('teacher');
            $teacher_id = I('post.teacher_id');
            $log_ids = I('post.cid');
            $arr = explode(',',$log_ids);
            foreach($arr as $v)
            {
                $log_id = $v;
                $res['class_id'] = $teacher->where(array('teacher_id'=>$teacher_id))->getField('class_id');
                $res = M('circul_log')->find($log_id);
                $data['book_id']     = $res['book_id'];
                $data['class_id']    = $res['class_id'];
                $data['school_id']   = $res['school_id'];
                $data['student_id']  = $res['student_id'];
                $data['add_time']    = strtotime($res['borrow_time']);
                $data['borrow_time'] = strtotime($res['borrow_time']);
                $data['circul_status'] = 1;
                $add = M('circulation')->add($data);
                // 删除circul_log一条记录
                $delete = M('circul_log')->delete($log_id);
            }
        }
        else{

            $log_id = I('log_id');
            $teacher_id = I('teacher_id');
            $res['class_id'] = M('teacher')->where(array('teacher_id'=>$teacher_id))->getField('class_id');
            $res = M('circul_log')->find($log_id);
            $data['book_id']     = $res['book_id'];
            $data['class_id']    = $res['class_id'];
            $data['school_id']   = $res['school_id'];
            $data['student_id']  = $res['student_id'];
            $data['add_time']    = strtotime($res['borrow_time']);
            $data['borrow_time'] = strtotime($res['borrow_time']);
            $data['circul_status'] = 1;
            // var_dump($data);die;
            $add = M('circulation')->add($data);
            // 删除circul_log一条记录
            $delete = M('circul_log')->delete($log_id);
        }
        if($add && $delete)
        {
            $this->success('撤销成功！',U('mobile.php/TBorrow/index',array('teacher_id'=>$teacher_id,'circul_status'=>1)));
        }
        else
        {
            $this->error('撤销失败！');
        }

    }


}