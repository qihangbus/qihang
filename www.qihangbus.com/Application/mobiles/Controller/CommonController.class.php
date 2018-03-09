<?php
namespace mobiles\Controller;
use Think\Controller;
class CommonController extends Controller {
    //自动执行
    public function _initialize()
    {
        $userinfo = session('userinfo');
        if(!$userinfo){
            $this->redirect(U('mobile.php/Parentlogin/index'));
            exit;
        }
//        $data = M('schools')->where("school_id = {$userinfo['school_id']}")->field('semester_one_start,semester_one,semester_two_start,semester_two,try_charge_time')->find();
//        if(time() > $data['try_charge_time']){
//            //判断有没有支付本学期的费用
//            if(time() > ($data['semester_two_start'] - 86400*7) && time() < ($data['semester_two'] + 86400)){//已到第二学期
//                if($userinfo['paid_time'] < ($data['semester_two_start'] - 86400*7)){//未支付
//                    $this->success('欢迎登陆',U('mobile.php/Parentlogin/getChildrenInfo',['student_id'=>$userinfo['student_id'],'appre'=>2]));
//                    exit;
//                }
//            }elseif(time() < ($data['semester_two_start'] - 86400*7)){//第一学期
//                if($userinfo['paid_time'] < ($data['semester_one_start'] - 86400*7)){//未支付
//                    $this->success('欢迎登陆',U('mobile.php/Parentlogin/getChildrenInfo',['student_id'=>$userinfo['student_id'],'appre'=>1]));
//                    exit;
//                }
//            }else{
//                session('userinfo','');
//                $this->error('请您等待新学期开学！',U('mobile.php/Parentlogin/index'));
//                exit;
//            }
//        }
        $this->assign('userinfo',$userinfo);
    }
}