<?php
namespace mobiles\Controller;
use Think\Controller;
class CartController extends Controller {
    public function index(){
		$user_id = I('param.user_id',0);
		$this->assign('user_id',$user_id);

		$user_flag = I('param.user_flag',0);
		$this->assign('user_flag',$user_flag);

		$ret_type = I('param.ret_type',1);
		$this->assign('ret_type',$ret_type);

		$cart_list = M('cart')->where(array('user_id'=>$user_id,'ret_type'=>$ret_type,'user_flag'=>$user_flag,'mobile'=>0))->select();
		$cart_amount = 0;
		$cart_number = 0;
		foreach ($cart_list as $key => $value) {
			$cart_list[$key]['thumb'] = M('product')->where(array('product_id'=>$value['book_id']))->getField('img_url');
			$cart_number += $value['book_number'];
			if($value['ret_type'] == 1){
				$cart_amount += round($value['points_price'] * $value['book_number']);
			}
			if($value['ret_type'] == 2){
				$cart_amount += round($value['shop_price'] * $value['book_number']);
			}
		}

		if($user_flag == 1){
            $this->assign('url',U('mobile.php/SIndex/Index/',array('id'=>$user_id)));
        }elseif($user_flag == 2){
            $this->assign('url',U('mobile.php/TIndex/index/',array('teacher_id'=>$user_id)));
        }elseif($user_flag == 3){
            $this->assign('url',U('mobile.php/Ucenter/index'));
        }elseif($user_flag == 4){
            $this->assign('url',U('mobile.php/MIndex/index',array('id'=>$user_id)));
        }else{
            $this->assign('url',U('mobile.php/Ucenter/index'));
        }

		$this->assign('cart_amount',$cart_amount);
		$this->assign('cart_number',$cart_number);
		$this->assign('cart_list',$cart_list);
		$this->assign('user_id',$user_id);
		$this->display('order/cart');
    }

    //删除购物车中的商品
    public function del_cart()
    {
    	$rec_id = I('param.rec_id',0);
    	$ret = M('cart')->where(array('rec_id'=>$rec_id))->delete();
    	echo $ret;
    }

    //更新商品数量
    public function ajax_cart()
    {
    	$rec_id = I('param.rec_id',0);
    	$cart_num = I('param.cart_num',0);
    	$user_flag = I('param.user_flag',0);
    	$ret_type = I('param.ret_type',0);
    	$user_id = I('param.user_id',0);

    	M('cart')->where(array('rec_id'=>$rec_id))->save(array('book_number'=>$cart_num));
    	$cart_list = M('cart')->where(array('user_id'=>$user_id,'ret_type'=>$ret_type,'user_flag'=>$user_flag))->select();

		$cart_amount = 0;
		$cart_number = 0;
		foreach ($cart_list as $key => $value) {
			$cart_number += $value['book_number'];
			if($value['ret_type'] == 1){
				$cart_amount += round($value['points_price'] * $value['book_number']);
			}
			if($value['ret_type'] == 2){
				$cart_amount += round($value['shop_price'] * $value['book_number']);
			}
		}
    	echo json_encode(array('cart_amount'=>$cart_amount,'cart_number'=>$cart_number));
    }

    //更新商品数量
    public function ajax_cart_two()
    {
    	$rec_id = I('param.rec_id',0);
    	$cart_num = I('param.cart_num',0);
    	$user_flag = I('param.user_flag',0);
    	$ret_type = I('param.ret_type',0);
    	$user_id = I('param.user_id',0);

    	$recid = array();
    	if(strpos($rec_id, ',') === false){
    		array_push($recid, $rec_id);
    	}else{
    		$recid = explode(',', $rec_id);
    	}

    	$cart_list = M('cart')->where(array('rec_id'=>array('in',$recid)))->select();

		$cart_amount = 0;
		$cart_number = 0;
		foreach ($cart_list as $key => $value) {
			$cart_number += $value['book_number'];
			if($value['ret_type'] == 1){
				$cart_amount += round($value['points_price'] * $value['book_number']);
			}
			if($value['ret_type'] == 2){
				$cart_amount += round($value['shop_price'] * $value['book_number']);
			}
		}
    	echo json_encode(array('cart_amount'=>$cart_amount,'cart_number'=>$cart_number));
    }
}