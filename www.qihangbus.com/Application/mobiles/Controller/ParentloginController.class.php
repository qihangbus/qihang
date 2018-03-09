<?php
namespace mobiles\Controller;

use Think\Controller;

class ParentloginController extends Controller
{
    public function index()
    {
        $wxid = I('get.fack_id', '');
        $this->assign('fack_id', $wxid);

        if (strlen($wxid) > 28) {
            $wxid = substr($wxid, 0, 28);
        }
        $this->display('parent_login/parent_login');
    }

    //手机号和密码验证
    public function validate_code()
    {
        $mobile = I('param.mobile', '');
        $pwd = I('password', '');
        $pwd = md5($pwd);
        $wxid = I('param.fack_id', '');
        //验证手机号是否存在于学生家长表中
        $parentinfo = M('students_parent')->where(array('parent_mobile' => $mobile, 'pwd' => $pwd))->find();

        if (!empty($parentinfo)) {
            $info = M('weixin_user')->where(array('fake_id' => $wxid))->find();
            M('students_parent')->where(array('parent_mobile' => $mobile))->save(array('parent_avatar' => $info['headimgurl'], 'wx_id' => $wxid));
            $userinfo = M('students')->where(array('student_id' => $parentinfo['student_id']))->find();
            $userinfo['student_avatar'] = M('weixin_user')->where(array('fake_id' => $wxid))->getField('headimgurl');
            session('user', ['id' => $parentinfo['parent_id'], 'type' => 1]);
            session('userinfo', $userinfo);
            session('parent_id', $parentinfo['parent_id']);
            if ($parentinfo['parent_sex'] < 1) {
                $this->success('欢迎登陆', U('mobile.php/Parentlogin/changesex', array('parent_id' => $parentinfo['parent_id'], 'student_id' => $parentinfo['student_id'])));
                exit;
            }

            $turn = U('mobile.php/Ucenter/index');
            //判断有没有支付本学期的费用
            $data = M('schools')->where("school_id = {$userinfo['school_id']}")->field('semester_one_start,semester_one,semester_two_start,semester_two,try_charge_time')->find();
            if($userinfo['school_id'] < 19 && $userinfo['school_id'] <> 2) {//旧收费模式
                if (time() > ($data['semester_one'] + 86400) && time() < ($data['semester_two'] + 86400)) {//已到第二学期
                    if ($userinfo['paid_num'] <> 2) {//未支付
                        $turn = U('mobile.php/Ucenter/index', ['pay_tips' => 1]);
                    }
                } elseif (time() <= ($data['semester_one'] + 86400)) {//第一学期
                    if ($userinfo['paid_num'] <> 1 && $userinfo['paid_num'] <> 2) {//未支付
                        $turn = U('mobile.php/Ucenter/index', ['pay_tips' => 1]);
                    }
                } else {
                    $turn = U('mobile.php/Ucenter/index');
                }
            }else{//新收费模式
                if(time() > $userinfo['paid_expires']){//未付费
                    $turn = U('mobile.php/Ucenter/index', ['pay_tips' => 1]);
                }else{//已付费
                    $turn = U('mobile.php/Ucenter/index');
                }
            }
            $this->success('欢迎登陆', $turn);
            exit;
        }

        //验证手机号是否存在于教师表中
        $teacherinfo = M('teacher')->where(array('teacher_mobile' => $mobile, 'pwd' => $pwd))->find();
        if (!empty($teacherinfo)) {
            //发送短信
            $adinfo = M("admins")->where(array('school_id' => $teacherinfo['school_id']))->find();
            $list = M('grade')->where(array('school_id' => $teacherinfo['school_id']))->select();


            session('user', ['id' => $teacherinfo['teacher_id'], 'type' => 2]);
            session('teacher', $teacherinfo);
            $info = M('weixin_user')->where(array('fake_id' => $wxid))->find();
            M('teacher')->where(array('teacher_mobile' => $mobile))->save(array('teacher_avatar' => $info['headimgurl'], 'wx_id' => $wxid));
            $this->success('欢迎登陆', U('mobile.php/TIndex/Index', array('teacher_id' => $teacherinfo['teacher_id'])));
            exit;
        }

        //验证手机号是否存在于园长表中
        $schoolinfo = M('schools')->where(array('teacher_mobile' => $mobile, 'pwd' => $pwd))->find();
        if (!empty($schoolinfo)) {
            $adinfo = M("admins")->where(array('school_id' => $teacherinfo['school_id']))->find();
            $list = M('grade')->where(array('school_id' => $teacherinfo['school_id']))->select();
            // var_dump($list);die;
            foreach ($list as $key => $value) {
                // 找出轮换日期
                // $time1 = 1502812800;//8.16
                $time1 = $value['change_time'];
                $time2 = time();
                $days = round(($time1 - $time2) / 3600 / 24);
                // 时间和状态符合才可以
                if ($value['messaged'] == 0 && $days <= 6 && $days >= 0) {
                    //获取管理员的手机号
                    $admin_mobile = M("admins")->where(array('school_id' => $teacherinfo['school_id']))->getField('admin_mobile');
                    // $admin_mobile = 13569264934;
                    // echo $admin_mobile;die;
                    $change_time = date("Y-m-d", $value['change_time']);
                    $smsbao = C('smsbao');
                    // var_dump($smsbao);die;
                    $url = $smsbao['url'];  //短信网关
                    $username = $smsbao['username'];    //短信平台帐号
                    $password = md5($smsbao['password']);   //短信平台密码
                    $content = "【启航巴士】您幼儿园{$value['grade_name']}的图书轮换时间为$change_time,不要忘记哦";  //要发送的短信内容
                    $sendurl = $url . "sms?u=" . $username . "&p=" . $password . "&m=" . $admin_mobile . "&c=" . urlencode($content);
                    $result = file_get_contents($sendurl);
                    if (intval($result) == 0) {
                        // 更新管理员的数据表 0--->1
                        M("grade")->where(array('grade_id' => $value['grade_id']))->setField('messaged', 1);
                    }
                }
            }


            session('user', ['id' => $schoolinfo['school_id'], 'type' => 3]);
            $info = M('weixin_user')->where(array('fake_id' => $wxid))->find();
            M('schools')->where(array('teacher_mobile' => $mobile))->save(array('teacher_avatar' => $info['headimgurl'], 'wx_id' => $wxid));
            $this->success('欢迎登陆', U('mobile.php/SIndex/Index', array('id' => $schoolinfo['school_id'])));
            exit;
        }

        //验证手机号是否存在于图书管理员表中
        $adminsinfo = M('admins')->where(array('admin_mobile' => $mobile, 'pwd' => $pwd))->find();
        if (!empty($adminsinfo)) {
            session('user', ['id' => $adminsinfo['admin_id'], 'type' => 4]);
            $info = M('weixin_user')->where(array('fake_id' => $wxid))->find();
            M('admins')->where(array('admin_mobile' => $mobile))->save(array('admin_avatar' => $info['headimgurl'], 'wx_id' => $wxid));
            $this->success('欢迎登陆', U('mobile.php/MIndex/index', array('id' => $adminsinfo['admin_id'])));
            exit;
        }

        $agent = M('agent');
        $agent_id = $agent->where("mobile = '$mobile' and pwd = '$pwd'")->getField('id');
        if ($agent_id) {
            session('user', ['id' => $agent_id, 'type' => 5]);
            session('userid', $agent_id);
            session('type',1);
            $info = M('weixin_user')->where("fake_id = '$wxid'")->find();
            $agent->where("mobile = '$mobile'")->save(array('avatar' => $info['headimgurl'], 'openid' => $wxid));
            $this->success('登录成功', U('mobile.php/Agent/index'));
            exit();
        }
        if($mobile){
            $agent_id2 = $agent->where("mobile2 = '$mobile' and pwd2 = '$pwd'")->getField('id');
        }
        if ($agent_id2) {
            session('user', ['id' => $agent_id2, 'type' => 5]);
            session('userid', $agent_id2);
            session('type',0);
            //$info = M('weixin_user')->where("fake_id = '$wxid'")->find();
            //$agent->where("mobile = '$mobile'")->save(array('avatar' => $info['headimgurl'], 'openid' => $wxid));
            $this->success('登录成功', U('mobile.php/Agent/index'));
            exit();
        }

        $this->error('手机号或密码错误', U('mobile.php/Parentlogin/index'));

    }

    public function select_manage()
    {
        $wxid = I('get.fack_id', '');
        if (strlen($wxid) > 28) {
            $wxid = substr($wxid, 0, 28);
        }

        //验证手机号是否存在于学生家长表中
        $parentinfo = M('students_parent')->where(array('wx_id' => $wxid))->find();
        if (!empty($parentinfo)) {
            $userinfo = M('students')->where(array('student_id' => $parentinfo['student_id']))->find();
            session('user', ['id' => $parentinfo['parent_id'], 'type' => 1]);
            session('userinfo', $userinfo);
            session('parent_id', $parentinfo['parent_id']);
            $this->redirect(U('mobile.php/Ucenter/index'));
        }

        //验证手机号是否存在于教师表中
        $teacherinfo = M('teacher')->where(array('wx_id' => $wxid))->find();
        if (!empty($teacherinfo)) {
            session('user', ['id' => $teacherinfo['teacher_id'], 'type' => 2]);
            session('teacher', $teacherinfo);
            $this->redirect(U('mobile.php/TIndex/index', array('teacher_id' => $teacherinfo['teacher_id'])));
        }

        //验证手机号是否存在于园长表中
        $schoolinfo = M('schools')->where(array('wx_id' => $wxid))->find();
        if (!empty($schoolinfo)) {
            session('user', ['id' => $schoolinfo['school_id'], 'type' => 3]);
            $this->redirect(U('mobile.php/SIndex/Index', array('id' => $schoolinfo['school_id'])));
        }

        //验证手机号是否存在于图书管理员表中
        $adminsinfo = M('admins')->where(array('wx_id' => $wxid))->find();
        if (!empty($adminsinfo)) {
            session('user', ['id' => $adminsinfo['admin_id'], 'type' => 4]);
            $this->redirect(U('mobile.php/MIndex/Index', array('id' => $adminsinfo['admin_id'])));
        }

        //验证渠道商表
        $agent = M('agent');
        $agent_id = $agent->where("openid = '$wxid'")->getField('id');
        if ($agent_id) {
            session('user', ['id' => $agent_id, 'type' => 5]);
            session('userid', $agent_id);
            $this->redirect(U('moible.php/Agent/index'));
        }

        $this->redirect(U('mobile.php/Parentlogin/index', array('fack_id' => $wxid)));
    }

    //身份选择
    public function changesex()
    {
        $parent_id = I('param.parent_id', '');
        $student_id = I('param.student_id', '');
        $this->assign('parent_id', $parent_id);
        $this->assign('student_id', $student_id);
        $this->display('parent_login/parent_change');
    }

    //修改家长身份
    public function edit_sex()
    {
        $parent_id = I('param.parent_id', '');
        $student_id = I('param.student_id', '');
        $sex = I('param.sex', '');
        $ret = M('students_parent')->where(array('parent_id' => $parent_id))->save(array('parent_sex' => $sex));
        echo $student_id;
    }

    //获取微信支付code
    public function ok()
    {

    }

    //获取学生信息
    public function getChildrenInfo()
    {
        Vendor('WxPayPubHelper.WxPayPubHelper');
        $jsApi = new \JsApi_pub();
        $student_id = I('get.student_id', '');
        $this->assign('student_id', $student_id);
        $code = I('get.code', '');
        $appre = I('get.appre', 1);
        $this->assign('appre', $appre);
        $year = I('get.year',1);

        $info = M('config')->where(array('parent_id' => 13))->field('code,value')->select();
        $payment = array();
        foreach ($info as $key => $value) {
            $payment[$value['code']] = $value['value'];
        }

        define("WXAPPID", $payment['appid']);
        define("WXMCHID", $payment['mchid']);
        define("WXKEY", $payment['appkey']);
        define("WXAPPSECRET", $payment['appsecret']);
        define("WXCURL_TIMEOUT", 30);
        define('WXNOTIFY_URL', "http://" . $_SERVER['HTTP_HOST'] . 'wx_native_callback.php');

        if (empty($code)) {
            $redirect = urlencode("http://" . $_SERVER['HTTP_HOST'] . "/mobile.php?m=mobile.php&c=Parentlogin&a=getChildrenInfo&student_id=$student_id&appre=$appre&year=$year");
            $url = $jsApi->createOauthUrlForCode($redirect, $payment['appid']);
            header("Location:$url");
        } else {
            $jsApi->setCode($code);
            $openid = $jsApi->getOpenid();

        }

        $school_id = M('students')->where(['student_id'=>$student_id])->getField('school_id');
        if($school_id < 19 && $school_id <> 2){//旧收费模式
            $age_cate_num = C('AGE_CATE_NUM');
            $cate = C('AGE_CATE');
            $set_meal = C("SET_MEAL");

            //计算当前天数到期末的天数
            $userinfo = session('userinfo');
            $data = M('schools')->where("school_id = {$userinfo['school_id']}")->field('semester_one_start,semester_one,semester_two_start,semester_two,meal_market_price,try_charge_time,borrow_first,borrow_second')->find();
            if (time() > ($data['semester_one'] + 86400) && time() < ($data['semester_two'] + 86400)) {//已到第二学期
                $end_date = $data['semester_two'];
            } elseif (time() <= ($data['semester_one'] + 86400)) {//第一学期
                $end_date = $data['semester_one'];
            } else {
                session('userinfo', '');
                $this->error('请您等待新学期开学！', U('mobile.php/Parentlogin/index'));
                exit;
            }
            if (time() > $data['try_charge_time']) {
                $week = date('w');
                if($week == 0){
                    $week = 7;
                }

                if($data['borrow_first']){
                    if($data['borrow_second']){//一周两次
                        if($week <= $data['borrow_first']){
                            $start_date = strtotime(date('Y-m-d')) - ($week + (7 - $data['borrow_second']) - 1) * 86400;
                        }elseif($week > $data['borrow_second']){
                            $start_date = strtotime(date('Y-m-d')) - ($week - $data['borrow_second'] - 1) * 86400;
                        }else{
                            $start_date = strtotime(date('Y-m-d')) - ($week - $data['borrow_first'] - 1) * 86400;
                        }
                    }else{//一周一次
                        if($week <= $data['borrow_first']){
                            $start_date = strtotime(date('Y-m-d')) - ($week + (7 - $data['borrow_first'])-1) * 86400;
                        }else{
                            $start_date = strtotime(date('Y-m-d')) - ($week - $data['borrow_first'] - 1) * 86400;
                        }
                    }
                }else{
                    $start_date = strtotime(date('Y-m-d'));
                }

                $all_day = round(($end_date - $start_date) / 3600 / 24);
            } else {
                $all_day = round(($end_date - $data['try_charge_time']) / 3600 / 24);
            }

            $info = M('config')->where(array('parent_id' => 13))->field('code,value')->select();
            $payment = array();
            foreach ($info as $key => $value) {
                $payment[$value['code']] = $value['value'];
            }

            if (!empty($openid) && $openid) {
                $unifiedOrder = new \UnifiedOrder_pub();
                $meal_type = M('schools')->join("fh_students on fh_students.school_id=fh_schools.school_id")->where(array('student_id' => $student_id))->getField('meal_type');//获取订阅价
                $this->assign('cate_num', $age_cate_num[$meal_type]);
                $this->assign('cate_price', $data['meal_market_price']);
                //计算每天需要多少钱
                $meal = round($data['meal_market_price'] / 30, 2);
                $order_amount = $all_day * $meal;
                $order_amount = ceil($order_amount);
                $order_sn = get_order_sn();
                $log_id = M('pay_log')->add(array('order_amount' => $order_amount, 'order_type' => 2, 'user_id' => $student_id, 'user_flag' => 3, 'log_time' => time(), 'remark' => $appre));

                $unifiedOrder->setParameter("openid", "$openid");//商品描述
                $unifiedOrder->setParameter("body", '订购启航巴士费用');//商品描述
                $unifiedOrder->setParameter("out_trade_no", "$order_sn");//商户订单号
                $unifiedOrder->setParameter("attach", strval($log_id));//商户支付日志
                $unifiedOrder->setParameter("total_fee", strval(intval($order_amount * 100)));//总金额
                //            $unifiedOrder->setParameter("total_fee",1);//总金额
                $unifiedOrder->setParameter("notify_url", "http://" . $_SERVER['HTTP_HOST'] . "/wxpay/index.php");//通知地址
                $unifiedOrder->setParameter("trade_type", "JSAPI");//交易类型
                $prepay_id = $unifiedOrder->getPrepayId();
                $jsApi->setPrepayId($prepay_id);
                $jsApiParameters = $jsApi->getParameters();
                $user_agent = $_SERVER['HTTP_USER_AGENT'];
                $allow_use_wxPay = true;

                if (strpos($user_agent, 'MicroMessenger') === false) {
                    $allow_use_wxPay = false;
                } else {
                    preg_match('/.*?(MicroMessenger\/([0-9.]+))\s*/', $user_agent, $matches);
                    if ($matches[2] < 5.0) {
                        $allow_use_wxPay = false;
                    }
                }
                $html = '<script language="javascript">';
                if ($allow_use_wxPay) {
                    $html .= "function jsApiCall(){";
                    $html .= "WeixinJSBridge.invoke(";
                    $html .= "'getBrandWCPayRequest',";
                    $html .= $jsApiParameters . ",";
                    $html .= "function(res){";
                    $html .= "WeixinJSBridge.log(res.err_msg);";
                    $html .= "if(res.err_msg == 'get_brand_wcpay_request:ok'){";
                    $html .= "window.location.href='http://" . $_SERVER['HTTP_HOST'] . "/mobile.php/Parentlogin/succ'";
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
                } else {
                    $html .= 'function callpay(){';
                    $html .= 'alert("您的微信不支持支付功能,请更新您的微信版本")';
                    $html .= "}";
                }
                $html .= '</script>';
                $this->assign('jsapi', $html);
            } else {
                $html = '<script language="javascript">';
                $html .= 'function callpay(){';
                $html .= 'alert("请在微信中使用微信支付")';
                $html .= "}";
                $html .= '</script>';
                $this->assign('jsapi', $html);
            }

            $student_info = M('students')->where(array('student_id' => $student_id))->find();
            session('userinfo', $student_info);
            $charge_type = M('schools')->where(['school_id' => $student_info['school_id']])->getField('charge_type');
            $this->assign('charge_type', $charge_type);
            $this->assign('student_info', $student_info);
            $this->assign('school_id', $student_info['school_id']);
            $this->assign('order_amount', $order_amount);
            $this->display('parent_login/get_children_info');
        }else{//新收费模式
            $year = I('get.year',1);    //收费时间，半年、1年、2年、3年
            $data = M('schools')->where("school_id = $school_id")->field('try_charge_time')->find();
            if (time() > $data['try_charge_time']) {//已过优惠期
                switch($year){
                    case 0.5 :
                        $order_amount = 0.5*360;
                        break;
                    case 1:
                        $order_amount = 360;
                        break;
                    case 2:
                        $order_amount = 2*300;
                        break;
                    default:
                        $order_amount = 3*300;
                }
                $start_time = time();
            }else{//优惠期内
                switch($year){
                    case 0.5 :
                        $order_amount = 160;
                        break;
                    case 1:
                        $order_amount = 300;
                        break;
                    case 2:
                        $order_amount = 2*280;
                        break;
                    default:
                        $order_amount = 3*260;
                }
                $start_time = $data['try_charge_time'];
            }

            $info = M('config')->where(array('parent_id' => 13))->field('code,value')->select();
            foreach ($info as $key => $value) {
                $payment[$value['code']] = $value['value'];
            }

            if (!empty($openid) && $openid) {
                $unifiedOrder = new \UnifiedOrder_pub();
                $order_amount = ceil($order_amount);
                $order_sn = get_order_sn();
                $expires = $start_time + $year*3600*24*365;
                $log_id = M('pay_log')
                    ->add([
                        'order_amount' => $order_amount,
                        'order_type' => 2,
                        'user_id' => $student_id,
                        'user_flag' => 3,
                        'log_time' => time(),
                        'expires' => $expires,
                        'pay_year' => $year,
                        'remark' => $appre
                    ]);

                $unifiedOrder->setParameter("openid", "$openid");//商品描述
                $unifiedOrder->setParameter("body", '启航巴士会员费用');//商品描述
                $unifiedOrder->setParameter("out_trade_no", "$order_sn");//商户订单号
                $unifiedOrder->setParameter("attach", strval($log_id));//商户支付日志
                $unifiedOrder->setParameter("total_fee", strval(intval($order_amount * 100)));//总金额
//                $unifiedOrder->setParameter("total_fee", 1);//总金额
                $unifiedOrder->setParameter("notify_url", "http://" . $_SERVER['HTTP_HOST'] . "/wxpay/notify.php");//通知地址
                $unifiedOrder->setParameter("trade_type", "JSAPI");//交易类型
                $prepay_id = $unifiedOrder->getPrepayId();
                $jsApi->setPrepayId($prepay_id);
                $jsApiParameters = $jsApi->getParameters();
                $user_agent = $_SERVER['HTTP_USER_AGENT'];
                $allow_use_wxPay = true;

                if (strpos($user_agent, 'MicroMessenger') === false) {
                    $allow_use_wxPay = false;
                } else {
                    preg_match('/.*?(MicroMessenger\/([0-9.]+))\s*/', $user_agent, $matches);
                    if ($matches[2] < 5.0) {
                        $allow_use_wxPay = false;
                    }
                }
                $html = '<script language="javascript">';
                if ($allow_use_wxPay) {
                    $html .= "function jsApiCall(){";
                    $html .= "WeixinJSBridge.invoke(";
                    $html .= "'getBrandWCPayRequest',";
                    $html .= $jsApiParameters . ",";
                    $html .= "function(res){";
                    $html .= "WeixinJSBridge.log(res.err_msg);";
                    $html .= "if(res.err_msg == 'get_brand_wcpay_request:ok'){";
                    $html .= "window.location.href='http://" . $_SERVER['HTTP_HOST'] . "/mobile.php/Parentlogin/succ'";
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
                } else {
                    $html .= 'function callpay(){';
                    $html .= 'alert("您的微信不支持支付功能,请更新您的微信版本")';
                    $html .= "}";
                }
                $html .= '</script>';
                $this->assign('jsapi', $html);
            } else {
                $html = '<script language="javascript">';
                $html .= 'function callpay(){';
                $html .= 'alert("请在微信中使用微信支付")';
                $html .= "}";
                $html .= '</script>';
                $this->assign('jsapi', $html);
            }
            $student_info = M('students')->where(['student_id' => $student_id])->find();
            session('userinfo', $student_info);
            $charge_type = M('schools')->where(['school_id' => $student_info['school_id']])->getField('charge_type');
            $this->assign('charge_type', $charge_type);
            $this->assign('student_info', $student_info);
            $this->assign('school_id', $student_info['school_id']);
            $this->assign('order_amount', $order_amount);
            $this->assign('student_id',$student_id);
            $this->assign('year',$year);
            $this->display('parent_login/get_children_info_new');
        }
    }

    public function respond()
    {
        Vendor('WxPayPubHelper.WxPayPubHelper');
        $notify = new \Notify_pub();
        $xml = $GLOBALS['HTTP_RAW_POST_DATA'];
        $notify->saveData($xml);
        $this->log('wx_new_log.txt', var_export($xml, true));
        $payment['logs'] = false;
        if ($notify->checkSign() == TRUE) {
            if ($notify->data["return_code"] == "FAIL") {
                //此处应该更新一下订单状态，商户自行增删操作
                if ($payment['logs']) {
                    $this->log(ROOT_PATH . '/wx_new_log.txt', "return_code失败\r\n");
                }
            } elseif ($notify->data["result_code"] == "FAIL") {
                //此处应该更新一下订单状态，商户自行增删操作
                if ($payment['logs']) {
                    $this->log(ROOT_PATH . '/wx_new_log.txt', "result_code失败\r\n");
                }
            } else {
                //此处应该更新一下订单状态，商户自行增删操作
                if ($payment['logs']) {
                    $this->log(ROOT_PATH . '/wx_new_log.txt', "支付成功\r\n");
                }
                $total_fee = $notify->data["total_fee"];
                $log_id = $notify->data["attach"];

                $amount = M('pay_log')->where(array('log_id' => $log_id))->getField('order_amount');
                if ($payment['logs']) {
                    $this->log(ROOT_PATH . '/wx_new_log.txt', '订单金额' . $amount . "\r\n");
                }

                if (intval($amount * 100) != $total_fee) {

                    if ($payment['logs']) {
                        $this->log(ROOT_PATH . '/wx_new_log.txt', '订单金额不符' . "\r\n");
                    }

                    echo 'fail';
                    return;
                }
                $user_id = M('pay_log')->where(array('log_id' => $log_id))->getField('user_id');
                M('pay_log')->where(array('log_id' => $log_id))->save(array('is_paid' => 1));
                M('students')->where(array('student_id' => $user_id))->save(array('order_flag' => 1));
                echo 'SUCCESS';
                return true;
            }

        } else {
            $this->log(ROOT_PATH . '/data/wx_new_log.txt', "签名失败\r\n");
        }
        return false;
    }

    public function succ()
    {
        $userinfo = session('userinfo');
        $info = M("students")->where(array('student_id' => $userinfo['student_id']))->find();
        session('userinfo', $info);
        $this->success('支付成功', U('mobile.php/Ucenter/index'));
    }

    public function about()
    {
        $this->display('parent_login/about');
    }

    public function error_msg()
    {
        $this->display('Common/weixin_error');
    }

    function log($file, $txt)
    {
        $fp = fopen($file, 'ab+');
        fwrite($fp, '-----------' . date('Y-m-d H:i:s') . '-----------------');
        fwrite($fp, $txt);
        fwrite($fp, "\r\n\r\n\r\n");
        fclose($fp);
    }

    public function loginClean()
    {
        if(IS_POST){
            $mobile = I('post.mobile',0);
            $access = M('students_parent')->where(['parent_mobile'=>$mobile])->find();
            if(!$access){
                $this->error('手机号错误');
            }
            $result = M('students_parent')->where(['parent_mobile'=>$mobile])->setField('wx_id','');
            if($result){
                $this->success('清除成功');
            }else{
                $this->error('清除失败');
            }
        }else{
            $this->display('parent_login/loginClean');
        }
    }

    //忘记密码
    public function forget()
    {
        if(IS_POST){
            $mobile = I('post.mobile');
            $code = I('post.code');
            $pwd = I('post.pwd');
            $cache = S($mobile);
            if($code != $cache){
                $this->error('验证码错误');
            }

            $result = M('students_parent')->where(array('parent_mobile'=>$mobile))->setField('pwd',md5($pwd));
            if($result){
                $this->success('密码设置成功');
                exit();
            }
            $result = M('teacher')->where(array('teacher_mobile'=>$mobile))->setField('pwd',md5($pwd));
            if($result){
                $this->success('密码设置成功');
                exit();
            }
            $result = M('schools')->where(array('leader_mobile'=>$mobile))->setField('pwd',md5($pwd));
            if($result){
                $this->success('密码设置成功');
                exit();
            }
            $result = M('schools')->where(array('teacher_mobile'=>$mobile))->setField('pwd',md5($pwd));
            if($result){
                $this->success('密码设置成功');
                exit();
            }
            $result = M('admins')->where(array('admin_mobile'=>$mobile))->setField('pwd',md5($pwd));
            if($result){
                $this->success('密码设置成功');
                exit();
            }
            $this->error('操作失败');

        }else{
            $this->display('parent_login/forget');
        }
    }

    //发送验证码
    public function sendSMS($mobile)
    {
        $sendNums = S('a'.$mobile) ? S('a'.$mobile) : 0;
        if($sendNums > 3){
            $data = array(
                'status' => 0,
                'msg' => '发送已超4次,请使用最后一个验证码',
            );
            echo json_encode($data,true);
            exit();
        }
        $smsbao = C('smsbao');
        $url = $smsbao['url'];  //短信网关
        $username = $smsbao['username'];	//短信平台帐号
        $password = md5($smsbao['password']);	//短信平台密码
        //获取手机号是否存在表中?
        $parent_mobile = M('students_parent')->where(array('parent_mobile'=>$mobile))->find()
            || $teacher_mobile = M('teacher')->where(array('teacher_mobile'=>$mobile))->find()
                || $leader_mobile = M('schools')->where(array('leader_mobile'=>$mobile))->find()
                    || $school_mobile = M('schools')->where(array('teacher_mobile'=>$mobile))->find()
                        || $admins_mobile = M('admins')->where(array('admin_mobile'=>$mobile))->find();
        if (!$parent_mobile && !$teacher_mobile && !$leader_mobile && !$school_mobile && !$admins_mobile) {
            $data = array(
                'status' => 0,
                'msg' => '未查询到手机号',
            );
            echo json_encode($data,true);
        }else{
            $smscode = rand(1000,9999);	//随机生成四位验证码
            $smscode = str_replace('1989', '1 9 8 9', $smscode);			//过滤黑字典
            $smscode = str_replace('1259', '1 2 5 9', $smscode);
            S($mobile, $smscode, 1800);
            S('a'.$mobile, $sendNums+1, 86400);
            $content = "【启航巴士】您的验证码为{$smscode}，在20分钟内有效。";	//要发送的短信内容
            $sendurl = $url."sms?u=".$username."&p=".$password."&m=".$mobile."&c=".urlencode($content);
            $result =file_get_contents($sendurl) ;
            if(intval($result) == 0){
                $data = array(
                    'status' => 1,
                    'msg' => '发送成功',
                );
            }else{
                $data = array(
                    'status' => 0,
                    'msg' => '发送失败',
                );
            }
            echo json_encode($data,true);
        }
    }
}