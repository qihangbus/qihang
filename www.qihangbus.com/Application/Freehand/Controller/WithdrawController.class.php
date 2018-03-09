<?php
namespace Freehand\Controller;
use Think\Controller;
class WithdrawController extends CommonController {
    public function index(){
    	$page_size = I('param.page_size',15);
        $this->assign('page_size',$page_size);
    	
        $wd = M('withdraw');
        $count = $wd->count();
        $page = new \Think\Page($count,$page_size);
        $show = $page->diyshow();

        $list = $wd->order('id desc')->limit($page->firstRow.','.$page->listRows)->select();
        $st = array(1=>'等待放款',2=>'提现完成',3=>'取消提现');
        foreach ($list as $key => $value) {
        	$list[$key]['username'] = M('schools')->where(array('school_id'=>$value['user_id']))->getField('school_teacher');
        	if($value['account_type'] == 1){
        		$list[$key]['type_name'] = '微信账户';
        	}else{
        		$list[$key]['type_name'] = '银联账户';
        	}
            $list[$key]['status_name'] = $st[$value['withdraw_status']];
        }

        $this->assign('list',$list);
        $this->assign('page',$show);

        $this->display("user/withdraw_index");
    }
}