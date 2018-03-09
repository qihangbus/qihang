<?php
namespace Freehand\Controller;
use Think\Controller;
class ExlogController extends CommonController {
    public function index()
    {
    	// product_id>0
    	$res = M('order_info')->where(array('pay_status'=>2))->select();
    	// 遍历每个订单的product_id
    	// product_id->find();
    	// 把一维数组构造成二维数组
    	// foreach ($res as $key => $value) {
    		
    	// }
    	// $this->assign('arr',$arr);
    	// var_dump($res);die;
     	$this->display();
    }
}
// 再次上线之后，user_id就是