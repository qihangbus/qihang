<?php
namespace mobiles\Controller;
use Think\Controller;
class CashController extends Controller {
    public function index(){ //id = student_id
    	$user_id = I('param.user_id',0);
    	$user_flag = I('param.user_flag',0);
    	if($user_flag == 1){
    		$info = M('schools')->where(array('school_id'=>$user_id))->find();
    		$money = intval($info['rank_points']/100);
    	}elseif($user_flag == 2){
    		$info = M('schools')->where(array('teacher_id'=>$user_id))->find();
    		$money = intval($info['rank_points']/100);
    	}elseif($user_flag == 3){
    		$info = M('students')->where(array('student_id'=>$user_id))->find();
    		$money = intval($info['student_points']/100);
    	}
		$this->assign('user_id',$user_id);
    	$this->assign('user_flag',$user_flag);
    	$this->assign('info',$info);
    	$this->assign('money',$money);
    	$this->display();
    }

    //发送验证码
    public function send_code()
    {
    	$mobile = I('param.mobile','');

    	$ret = M('students_parent')->where(array('parent_mobile'=>$mobile))->count();
    	if(!$ret) echo 0;	

    	$code = mt_rand(100000,999999);
    	$content = "【剩蛋农场】尊敬的用户".$mobile."，您的短信验证码为：".$code;

    	$userid = C('SMS_USER_ID');
    	$account = C('SMS_ACCOUNT');
    	$password = C('SMS_PASSWORD');

    	$time = time();

    	//发送短信
		$gateway = "http://114.113.154.5/sms.aspx?action=send&userid={$userid}&account={$account}&password={$password}&mobile={$mobile}&content={$content}&sendTime=";
		$result = file_get_contents($gateway);
		$xml = simplexml_load_string($result);

		if($xml->returnstatus == 'Success'){
			$data['mobile'] = $mobile;
			$data['code'] = $code;
			$data['send_time'] = $time;
			$data['status'] = 1;
			$data['remark'] = $content;	
			$ret = M('mobile_code_record')->add($data);
			echo $ret;
		}
    }

    public function add_withdraw()
    {
    	$data = array();
    	$data['money'] = I('param.money','');
    	$data['account_type'] = I('param.account_type','');
    	$data['user_id'] = I('param.user_id','');
    	$data['user_flag'] = I('param.user_flag','');
    	$data['withdraw_price'] = I('param.withdraw_money','');
    	if($data['account_type'] == 1){
    		$data['account_number'] = M('schools')->where(array('school_id'=>$data['user_id']))->getField('wx_id');
    	}elseif($data['account_type'] == 2){
    		$data['account_number'] = M('schools')->where(array('school_id'=>$data['user_id']))->getField('bank_card');
    	}
    	$data['withdraw_status'] = 1;
    	$data['withdraw_time'] = time();

    	$ret = M('withdraw')->add($data);
    	if($ret){
    		//冻结体现金额，并减去响应的积分
    		M('schools')->where(array('school_id'=>$data['user_id']))->save(array('freeze_money'=>$data['withdraw_price']));
    		$points = $data['withdraw_price'] * 100;
    		M('schools')->where(array('school_id'=>$data['user_id']))->setDec('rank_points',$points);
    		echo $ret;
    	}else{
    		echo 0;
    	}
    }

    public function withdraw_list()
    {
    	$user_id = I('param.user_id','');
    	$user_flag = I('param.user_flag','');
    	$withdraw_list = M('withdraw')->where(array('user_id'=>$user_id,'user_flag'=>$user_flag))->select();
    	
    	foreach ($withdraw_list as $key => $value) {
    		if($value['withdraw_status'] == 1){
	    		$withdraw_list[$key]['status_name'] = '等待放款';
	    	}elseif($value['withdraw_status'] == 2){
	    		$withdraw_list[$key]['status_name'] = '提现完成';
	    	}
	    	if($value['account_type'] == 1){
	    		$withdraw_list[$key]['bank_name'] = '微信支付提现';
	    		$withdraw_list[$key]['class_name'] = 'tx_wx';
	    		$withdraw_list[$key]['bank_card'] = '';
	    	}elseif($value['account_type'] == 2){
	    		$withdraw_list[$key]['bank_name'] = '银联提现';
	    		$withdraw_list[$key]['class_name'] = 'tx_yin';
	    		$withdraw_list[$key]['bank_card'] = $value['account_number'];
	    	}
    	}

    	$this->assign('list',$withdraw_list);
    	$this->display('Cash/history');
    }
}