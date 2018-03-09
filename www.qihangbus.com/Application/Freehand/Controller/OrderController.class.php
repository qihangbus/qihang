<?php
namespace mobile\Controller;
use Think\Controller;
class OrderController extends Controller {
    public function index(){
        $user_id = I('param.user_id',0);
        $status = I('param.status',1);

        $orderlist = array();
        $points_list = array();
        $condition = array();
        $condition['user_flag'] = I('param.user_flag','');
        $this->assign('user_flag',$condition['user_flag']);
        if($status == 1){
        	//待付款
        	$condition['order_status'] = array('lt',1);
        	$condition['pay_status'] = array('lt',1);
        }elseif($status == 2){
        	//待收货
        	$condition['shipping_status'] = 1;
        }
        $condition['user_id'] = $user_id;

        $order_list = M('order_info')->where($condition)->order("order_id desc,add_time desc")->select();
       // var_dump(M('order_info')->getlastsql());
        foreach ($order_list as $key => $value) {
        	if($value['pay_id'] == 1){
        		$book_id = M('order_goods')->where(array('order_id'=>$value['order_id']))->getField('book_id');
        		$value['goods_list'] = M('books')->where(array('book_id'=>$book_id))->select();
        		$points_list[] = $value;
        	}elseif($value['pay_id'] == 2){
        		$book_id = M('order_goods')->where(array('order_id'=>$value['order_id']))->getField('book_id');
        		$value['goods_list'] = M('books')->where(array('book_id'=>$book_id))->select();
        		$orderlist[] = $value;
        	}
        }

        $this->assign('user_id', $user_id);
	    $this->assign('status',$status);
	    if($status == 1){
	    	//var_dump($points_list);
	    	//exit;
	    	$this->assign('points_list',$points_list);
            //var_dump($points_list);
            //exit;
       		$this->assign('orderlist',$orderlist);
	        $this->display('order/index_1');
	    }elseif($status == 2){
	    	$list = array_merge($points_list,$orderlist);
	    	$this->assign('list',$list);
	        $this->display('order/index_2');
	    }elseif($status == 99){
	    	$list = array_merge($points_list,$orderlist);
	    	$this->assign('list',$list);
	        $this->display('order/index_99');
	    }
    }

    public function done2()
    {
        $order_id = I('param.order_id',0);
        $user_id = I('param.user_id',0);
        $user_flag = I('param.user_flag',0);
        $order_amount = M('order_info')->where(array('order_id'=>$order_id))->getField('order_amount');            
        
        if($user_flag == 1){
            $points = M('schools')->where(array('school_id'=>$user_id))->getField('rank_points');  
        }elseif($user_flag == 2){
            $points = M('teacher')->where(array('teacher_id'=>$user_id))->getField('rank_points'); 
        }elseif($user_flag == 3){
            $points = M('students')->where(array('student_id'=>$user_id))->getField('student_points'); 
        }

        if($points > 0 && $data['order_amount'] >= $points){
            $new_points = $points - $data['order_amount'];

            //更新萌星
            if($user_flag == 1){
                M('schools')->where(array('school_id'=>$user_id))->save(array('rank_points'=>$new_points));  
            }elseif($user_flag == 2){
                M('teacher')->where(array('teacher_id'=>$user_id))->save(array('rank_points'=>$new_points)); 
            }elseif($user_flag == 3){
                M('students')->where(array('student_id'=>$user_id))->save(array('student_points'=>$new_points)); 
            }

            //更新订单付款状态
            M('order_info')->where(array('order_id'=>$order_id))->save(array('pay_status'=>2));
        }else{
            $this->error('萌星不够支付',U('mobile.php/Order/index',array('user_id'=>$user_id,'user_flag'=>$user_flag)));
            exit;
        }

        $this->success('支付成功',U('mobile.php/Order/index',array('user_id'=>$user_id,'user_flag'=>$user_flag)));
    }

    //订单确认
    public function checkout()
    {
		$user_id = I('param.user_id',0);
		$user_flag = I('param.user_flag',0);
 		$cart_list = M('cart')->where(array('user_id'=>$user_id,'user_flag'=>$user_flag))->select();
 		$cart_amount = 0;
		$cart_number = 0;
		foreach ($cart_list as $key => $value) {
			$cart_number += $value['book_number'];

            $cart_list[$key]['thumb'] = M('books')->where(array('book_id'=>$value['book_id']))->getField('book_thumb');

			if($value['ret_type'] == 1){
				$cart_amount += round($value['points_price'] * $value['book_number']);
			}
			if($value['ret_type'] == 2){
				$cart_amount += round($value['shop_price'] * $value['book_number']);
			}
		}  

		$data = array();
		$time = time();
		//查询收货人信息
		if($user_flag == 1){
			$address_id = M('schools')->where(array('school_id'=>$user_id))->getField('address_id');
		}elseif($user_flag == 2){
			$address_id = M('teacher')->where(array('teacher_id'=>$user_id))->getField('address_id');
		}elseif($user_flag == 3){
			$address_id = M('students')->where(array('student_id'=>$user_id))->getField('address_id');
		}
		
		$address_info = M('address')->where(array('address_id'=>$address_id))->find();
		$order_sn = get_order_sn();


        $this->assign('goods_list',$cart_list);
		$this->assign('order_sn',$order_sn);
		$this->assign('cart_amount',$cart_amount);
		$this->assign('cart_number',$cart_number);
		$this->assign('address_info',$address_info);
		$this->assign('user_id',$user_id);
		$this->assign('user_flag',$user_flag);
    	$this->display('order/checkout');
    }

    //订单详情
    public function order_info()
    {
    	$order_id = I('param.order_id',0);
    	$order_info = M('order_info')->where(array('order_id'=>$order_id))->find();
    	$this->assign('info',$order_info);
    	$order_goods = M('order_goods')->where(array('order_id'=>$order_info['order_id']))->select();
    	foreach ($order_goods as $key => $value) {
    		$order_goods[$key]['book_thumb'] = M('books')->where(array('book_id'=>$value['book_id']))->getField('book_thumb');
    	}
    	$this->assign('user_id',$order_info['user_id']);
    	$this->assign('user_flag',$order_info['user_flag']);
    	$this->assign('goods_list',$order_goods);
    	$this->display('order/order_info');
    }

    //管理收货地址
    public function address()
    {
        $user_id = I('param.user_id',0);
        $this->assign('user_id',$user_id);
        $user_flag = I('param.user_flag',0);
        $this->assign('user_flag',$user_flag);
        $address_list = M('address')->where(array('user_id'=>$user_id,'user_flag'=>$user_flag))->select();
        
        foreach ($address_list as $key => $value) {
             if($value['province']){   
                $address_list[$key]['province_name'] = M('region')->where(array('region_id'=>$value['province']))->getField('region_name');
                $address_list[$key]['city_name'] = M('region')->where(array('region_id'=>$value['city']))->getField('region_name');
                $address_list[$key]['district_name'] = M('region')->where(array('region_id'=>$value['district']))->getField('region_name');
             }
        }
        $userinfo = M('teacher')->where(array('teacher_id'=>$user_id))->find();
        $this->assign('userinfo',$userinfo);
        $this->assign('address_list',$address_list);
        $this->display('SIndex/address');
    }

    //添加收货地址
    public function add_address()
    {
        $user_id = I('param.user_id',0);
        $this->assign('user_id',$user_id);
        $user_flag = I('param.user_flag',0);
        $this->assign('address_id',0);
        $this->assign('user_flag',$user_flag);

        $province_list = M('region')->where(array('region_level'=>1))->select();
        $this->assign('province_list',$province_list);
        $city_list = M('region')->where(array('region_level'=>2,'parent_id'=>1))->select();
        $this->assign('city_list',$city_list);
        $district_list = M('region')->where(array('region_level'=>3,'parent_id'=>2))->select();
        $this->assign('district_list',$district_list);

        $this->display('SIndex/address_info');
    }   

    //编辑收货地址
    public function address_edit()
    {
        $address_id = I('param.address_id',0);
        $address_info = M('address')->where(array('address_id'=>$address_id))->find();
        $this->assign('address_info',$address_info);
        $this->assign('address_id',$address_id);
        $user_id = I('param.user_id','');
        $this->assign('user_id',$user_id);
        $user_flag = I('param.user_flag','');
        $this->assign('user_flag',$user_flag);
        $province_list = M('region')->where(array('region_level'=>1))->select();
        $this->assign('province_list',$province_list);
        $city_list = M('region')->where(array('region_level'=>2,'parent_id'=>$address_info['province']))->select();
        $this->assign('city_list',$city_list);
        $district_list = M('region')->where(array('region_level'=>3,'parent_id'=>$address_info['city']))->select();
        $this->assign('district_list',$district_list);

        $this->display('SIndex/address_info');
    }

    //订单提交
    public function done()
    {
 		$user_id = I('param.user_id',0);
 		$user_flag = I('param.user_flag',0);
 		$cart_list = M('cart')->where(array('user_id'=>$user_id,'user_flag'=>$user_flag))->select();
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

		$data = array();
		$time = time();
		//查询收货人信息
		if($user_flag == 1){
			$address_id = M('schools')->where(array('school_id'=>$user_id))->getField('address_id');
		    $points = M('schools')->where(array('school_id'=>$user_id))->getField('rank_points');  
        }elseif($user_flag == 2){
			$address_id = M('teacher')->where(array('teacher_id'=>$user_id))->getField('address_id');
            $points = M('teacher')->where(array('teacher_id'=>$user_id))->getField('rank_points'); 
		}elseif($user_flag == 3){
			$address_id = M('students')->where(array('student_id'=>$user_id))->getField('address_id');
            $points = M('students')->where(array('student_id'=>$user_id))->getField('student_points'); 
		}
		$address_info = M('address')->where(array('address_id'=>$address_id))->find();

		if($address_info){
			$data['consignee'] = $address_info['consignee'];
			$data['province'] = $address_info['province'];
			$data['city'] = $address_info['city'];
			$data['district'] = $address_info['district'];
			$data['address'] = $address_info['address'];
			$data['mobile'] = $address_info['mobile'];	
		}
		
		$data['order_sn'] = get_order_sn();
		$data['user_id'] = $user_id;
		$data['user_flag'] = $user_flag;
		$data['order_status'] = 0;
		$data['shipping_status'] = 0;
		$data['pay_status'] = 0;
		$data['shipping_id'] = 1;
		$data['shipping_name'] = "快递配送";
		$data['pay_id'] = 1;
		$data['pay_name'] = "萌星支付";

		$data['book_amount'] = $cart_amount;
		$data['shipping_fee'] = "0.00";
		$data['money_paid'] = "0.00";
		$data['integral'] = "0";
		$data['order_amount'] = $data['book_amount'] + $data['shipping_fee'];
		$data['add_time'] = $time;



		$order_id = M('order_info')->add($data);

		//添加数据到订单商品表中
		foreach ($cart_list as $key => $value) {
			$goods_data = array();
			$goods_data['order_id'] = $order_id;
			$goods_data['book_id'] = $value['book_id'];
			$goods_data['book_name'] = $value['book_name'];
			$goods_data['book_sn'] = $value['book_sn'];
			$goods_data['book_number'] = $value['book_number'];
			$goods_data['market_price'] = $value['market_price'];
			$goods_data['shop_price'] = $value['shop_price'];
			$goods_data['points_price'] = $value['points_price'];
			$goods_data['ret_type'] = $value['ret_type'];
			M('order_goods')->add($goods_data);
		}

		//清空购物车数据
		M('cart')->where(array('user_id'=>$user_id,'user_flag'=>$user_flag))->delete();

        if($points > 0 && $data['order_amount'] >= $points){
            $new_points = $points - $data['order_amount'];

            //更新萌星
            if($user_flag == 1){
                M('schools')->where(array('school_id'=>$user_id))->save(array('rank_points'=>$new_points));  
            }elseif($user_flag == 2){
                M('teacher')->where(array('teacher_id'=>$user_id))->save(array('rank_points'=>$new_points)); 
            }elseif($user_flag == 3){
                M('students')->where(array('student_id'=>$user_id))->save(array('student_points'=>$new_points)); 
            }

            //更新订单付款状态
            M('order_info')->where(array('order_id'=>$order_id))->save(array('pay_status'=>2));
        }else{
            $this->error('萌星不够支付',U('mobile.php/Order/index',array('user_id'=>$user_id,'user_flag'=>$user_flag)));
            exit;
        }

		$this->success('提交订单成功',U('mobile.php/Order/index',array('user_id'=>$user_id,'user_flag'=>$user_flag)));
    }
}