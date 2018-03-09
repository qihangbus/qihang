<?php
namespace mobiles\Controller;
use Think\Controller;
class OrderController extends Controller {
    public function index(){
        $user_id = I('param.user_id',0);
        $status = I('param.status',1);

        $orderlist = array();
        $points_list = array();
        $condition = array();
        $user_flag = $condition['user_flag'] = I('param.user_flag','');
        $this->assign('user_flag',$condition['user_flag']);
        if($status == 1){
        	//待付款
        	$condition['order_status'] = array('lt',1);
        	$condition['pay_status'] = array('lt',2);
            $condition['book_amount'] = array('gt',0);
        }elseif($status == 2){
        	//待收货
            $condition['order_status'] = 1;
        	$condition['shipping_status'] = array('lt',1);
        }elseif($status == 99)
        {
            $condition['book_amount'] = array('gt',0);
        }
        $condition['user_id'] = $user_id;
        $condition['is_cell'] = 0;

        $flag = 0;

        $order_status = array(0=>'未确认',1=>'已确认',2=>'已取消',3=>'无效');
        $shipping_status = array(0=>'未发货',1=>'已发货',2=>'已收货');
        $pay_status = array(0=>'未付款',1=>'付款中',2=>'已付款');
        $order_list = M('order_info')->where($condition)->order("order_id desc,add_time desc")->select();
        foreach ($order_list as $key => $value) {
        	if($value['pay_id'] == 1){
                // 商品的proudct_id就是book_id
        		$product_arr = M('order_goods')->where(array('order_id'=>$value['order_id']))->field('book_id')->select();
        		$goods = array();
				for($i=0;$i<count($product_arr);$i++){
					$temp = array();
                    // 从商品表中查出详细信息
					$temp = M('product')->where(array('product_id'=>$product_arr[$i]['book_id']))->field("img_url,product_name,product_price")->find();
					$temp['num'] = M('order_goods')->where(array('order_id'=>$value['order_id'],'book_id'=>$product_arr[$i]['book_id']))->getField('book_number');
					$goods[] = $temp;
				}
                $value['goods_list'] = $goods;
				if(count($value['goods_list']) > 0) $flag = 1;
                $value['order_status_name'] = $order_status[$value['order_status']]; 
                $value['shipping_status_name'] = $shipping_status[$value['shipping_status']];
                $value['pay_status_name'] = $pay_status[$value['pay_status']];
                $points_list[] = $value;
        	}elseif($value['pay_id'] == 2){
        		$book_id = M('order_goods')->where(array('order_id'=>$value['order_id']))->getField('book_id');
        		$value['goods_list'] = M('books')->where(array('book_id'=>$book_id))->select();
        		$value['order_status_name'] = $order_status[$value['order_status']]; 
                $value['shipping_status_name'] = $shipping_status[$value['shipping_status']];
                $value['pay_status_name'] = $pay_status[$value['pay_status']];
                $orderlist[] = $value;
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
        $this->assign('flag', $flag);
        $this->assign('user_id', $user_id);
	    $this->assign('status',$status);
	    if($status == 1){
            // var_dump($points_list);die;
	    	$this->assign('points_list',$points_list);
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
    // 确认收货
    public function receive()
    {
        $order_id = I('order_id');
        M('order_info')->where(array('order_id'=>$order_id))->setField('shipping_status',2); 
        echo 1;
        exit;
    }
    //订单详情
    public function order_info()
    {
    	$order_id = I('param.order_id',0);
    	$order_info = M('order_info')->where(array('order_id'=>$order_id))->find();
    	$this->assign('info',$order_info);
    	$order_goods = M('order_goods')->where(array('order_id'=>$order_info['order_id']))->select();
    	foreach ($order_goods as $key => $value) {
    		$order_goods[$key]['product_thumb'] = M('product')->where(array('product_id'=>$value['book_id']))->getField('img_url');
    	}

        if($order_info['order_status'] < 1){
            $order_status = '未确认';
        }elseif($order_info['order_status'] == 1){
            $order_status = '已确认';
        }elseif($order_info['order_status'] == 2){
            $order_status = '已取消';
        }elseif($order_info['order_status'] == 3){
            $order_status = '无效订单';
        }
        $this->assign('order_status',$order_status);
        
        if($order_info['shipping_status'] < 1){
            $shipping_status = '未发货';
        }elseif($order_info['shipping_status'] == 1){
            $shipping_status = '已发货';
        }elseif($order_info['shipping_status'] == 2){
            $shipping_status = '已收货';
        }
        $this->assign('shipping_status',$shipping_status);

        if($order_info['pay_status'] < 1){
            $pay_status = '未付款';
        }elseif($order_info['pay_status'] == 1){
            $pay_status = '付款中';
        }elseif($order_info['pay_status'] == 2){
            $pay_status = '已付款';
        }
        $this->assign('pay_status',$pay_status);

        $AppKey='a8da6e85036b33c9';
        $url ='http://api.kuaidi100.com/api?id='.$AppKey.'&com='.$order_info['invoice_code'].'&nu='.$order_info['invoice_no'].'&show=0&muti=1&order=desc';
        $invoice_list = curlinit($url);
        $invoice = json_decode($invoice_list);
        $invoice = objectToArray($invoice);

        if($invoice['message'] == 'ok'){
            $this->assign('invoice',$invoice['data']);
        }else{
            $this->assign('invoice','');
        }
    	$this->assign('user_id',$order_info['user_id']);
    	$this->assign('user_flag',$order_info['user_flag']);
    	$this->assign('goods_list',$order_goods);

        if($order_info['user_flag'] == 1){
            $this->assign('url',U('mobile.php/SIndex/Index/',array('id'=>$order_info['user_id'])));
        }elseif($order_info['user_flag'] == 2){
            $this->assign('url',U('mobile.php/TIndex/index/',array('teacher_id'=>$order_info['user_id'])));
        }elseif($order_info['user_flag'] == 3){
            $this->assign('url',U('mobile.php/Ucenter/index'));
        }elseif($user_flag == 4){
            $this->assign('url',U('mobile.php/MIndex/index',array('id'=>$order_info['user_id'])));
        }else{
            $this->assign('url',U('mobile.php/Ucenter/index'));
        }
    	$this->display('order/order_info');
    }

    //管理收货地址
    public function address()
    {
        $user_id = I('param.user_id',0);
        $this->assign('user_id',$user_id);
        $user_flag = I('param.user_flag',0);
        $this->assign('user_flag',$user_flag);
        $recid = I('param.recid',0);
		
        $this->assign('recid',$recid);
        $address_list = M('address')->where(array('user_id'=>$user_id,'user_flag'=>$user_flag))->select();
       
        foreach ($address_list as $key => $value) {
             if($value['province']){   
                $address_list[$key]['province_name'] = M('region')->where(array('region_id'=>$value['province']))->getField('region_name');
                $address_list[$key]['city_name'] = M('region')->where(array('region_id'=>$value['city']))->getField('region_name');
                $address_list[$key]['district_name'] = M('region')->where(array('region_id'=>$value['district']))->getField('region_name');
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

       
        $userinfo = M('teacher')->where(array('teacher_id'=>$user_id))->find();
        $this->assign('userinfo',$userinfo);
        $this->assign('address_list',$address_list);
        $this->display('order/address');
    }

    //添加收货地址
    public function add_address()
    {
        $user_id = I('param.user_id',0);
        $this->assign('user_id',$user_id);
        $user_flag = I('param.user_flag',0);
        $this->assign('address_id',0);
        $this->assign('user_flag',$user_flag);

        $recid = I('param.recid',0);
        $this->assign('recid',$recid);

        $province_list = M('region')->where(array('region_level'=>1))->select();
        $this->assign('province_list',$province_list);
        $city_list = M('region')->where(array('region_level'=>2,'parent_id'=>1))->select();
        $this->assign('city_list',$city_list);
        $district_list = M('region')->where(array('region_level'=>3,'parent_id'=>2))->select();
        $this->assign('district_list',$district_list);

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

        $this->display('order/address_info');
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

        $recid = I('param.recid',0);
        $this->assign('recid',$recid);

        $province_list = M('region')->where(array('region_level'=>1))->select();
        $this->assign('province_list',$province_list);
        $city_list = M('region')->where(array('region_level'=>2,'parent_id'=>$address_info['province']))->select();
        $this->assign('city_list',$city_list);
        $district_list = M('region')->where(array('region_level'=>3,'parent_id'=>$address_info['city']))->select();
        $this->assign('district_list',$district_list);

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

       $this->display('order/address_info');
    }

    //处理地址信息
    public function edit_address()
    {
        $address_id = I('param.address_id',0);
        $data['consignee'] = I('param.consignee','');
        $data['mobile'] = I('param.mobile','');
        $data['province'] = I('param.province','');
        $data['city'] = I('param.city','');
        $data['district'] = I('param.district','');
        $data['address'] = I('param.address','');
        $data['user_id'] = I('param.user_id','');
        $data['user_flag'] = I('param.user_flag','');
        $default = I('param.default',0);
        $recid = I('param.recid',0);
        if($address_id > 0){
            $msg = '修改成功';    
            $ret = M('address')->where("address_id=$address_id")->save($data);
        }else{
            $msg = '添加成功';
            $ret = M('address')->add($data);
            $address_id = $ret;
        }

        if($data['user_flag'] == 1){
            $this->assign('url',U('mobile.php/SIndex/Index/',array('id'=>$data['user_id'])));
            M('schools')->where(array('school_id'=>$data['user_id']))->save(array('address_id'=>$address_id));
        }elseif($data['user_flag'] == 2){
            $this->assign('url',U('mobile.php/TIndex/index/',array('teacher_id'=>$data['user_id'])));
            M('teacher')->where(array('teacher_id'=>$data['user_id']))->save(array('address_id'=>$address_id));
        }elseif($data['user_flag'] == 3){
            $this->assign('url',U('mobile.php/Ucenter/index'));
            M('students')->where(array('student_id'=>$data['user_id']))->save(array('address_id'=>$address_id));
        }elseif($user_flag == 4){
            $this->assign('url',U('mobile.php/MIndex/index',array('id'=>$data['user_id'])));
            M('admins')->where(array('admin_id'=>$data['user_id']))->save(array('address_id'=>$address_id));
        }else{
            $this->assign('url',U('mobile.php/Ucenter/index'));
            M('students')->where(array('student_id'=>$data['user_id']))->save(array('address_id'=>$address_id));
        }

        
        redirect(U('mobile.php/Order/checkout/',array('user_id'=>$data['user_id'],'user_flag'=>$data['user_flag'],'recid'=>$recid,'address_id'=>$address_id)));
        //$this->success("$msg",U('mobile.php/Ucenter/address',array('student_id'=>$data['user_id'],'user_flag'=>$data['user_flag'])));
        
    }

    public function cancel()
    {
        $order_id = I('param.order_id',0);

        // $ret = M('order_info')->where(array('order_id'=>$order_id))->save(array('order_status'=>2));
        
        // by yx
        $ret = M('order_info')->where(array('order_id'=>$order_id))->delete();

        $info = M('order_info')->where(array('order_id'=>$order_id))->find();
        $integral = $info['integral'];
        $user_id = $info['user_id'];
        $user_flag = $info['user_flag'];

        
        if($ret){
            
            if($integral > 0)
            {
                if($user_flag == 1){
                    M('schools')->where(array('school_id'=>$user_id))->setInc('rank_points',$integral);  
                }elseif($user_flag == 2){
                    M('teacher')->where(array('teacher_id'=>$user_id))->setInc('rank_points',$integrals); 
                }elseif($user_flag == 3){
                    M('students')->where(array('student_id'=>$user_id))->setInc('rank_points',$integral); 
                }elseif($user_flag == 4){
                    M('admins')->where(array('admin_id'=>$user_id))->setInc('rank_points',$integral); 
                }
            }
            echo 1;
            exit;
        }
    }

        //订单确认
    public function checkout()
    {
        $user_id = I('param.user_id',0);
        $user_flag = I('param.user_flag',0);
        $recid = I('param.recid','');
        $address_id = I('param.address_id',0);
		if($user_flag == 1 && $address_id < 1){
			$address_id = M('schools')->where(array('school_id'=>$user_id))->getField("address_id");
		}elseif($user_flag == 2 && $address_id < 1){
			$address_id = M('teacher')->where(array('teacher_id'=>$user_id))->getField("address_id");
		}elseif($user_flag == 3 && $address_id < 1){
			$address_id = M('students')->where(array('student_id'=>$user_id))->getField("address_id");
		}elseif($user_flag == 4 && $address_id < 1){
            // 此处添加
            $address_id = M('admins')->where(array('admin_id'=>$user_id))->getField("address_id");
        }
        if($address_id > 0){
            if($data['user_flag'] == 1){
                $this->assign('url',U('mobile.php/SIndex/Index/',array('id'=>$user_id)));
                M('schools')->where(array('school_id'=>$user_id))->save(array('address_id'=>$address_id));
            }elseif($data['user_flag'] == 2){
                $this->assign('url',U('mobile.php/TIndex/index/',array('teacher_id'=>$user_id)));
                M('teacher')->where(array('teacher_id'=>$user_id))->save(array('address_id'=>$address_id));
            }elseif($data['user_flag'] == 3){
                $this->assign('url',U('mobile.php/Ucenter/index'));
                M('students')->where(array('student_id'=>$user_id))->save(array('address_id'=>$address_id));
            }elseif($data['user_flag'] == 4){
                $this->assign('url',U('mobile.php/MIndex/index',array('id'=>$user_id)));
                M('students')->where(array('admin_id'=>$user_id))->save(array('address_id'=>$address_id));
            }else{
                $this->assign('url',U('mobile.php/Ucenter/index'));
                M('students')->where(array('student_id'=>$user_id))->save(array('address_id'=>$address_id));
            }
            // 此处添加管理员
        }else{
			$this->error("请选择收货地址",U('mobile.php/Order/address',array('user_id'=>$user_id,'user_flag'=>$user_flag,'recid'=>$recid)));
			exit;
		}
        if(strpos($recid,',') === false){
            $rec_id = $recid;
        }else{
            $recs = explode(',', $recid);
            for ($i=0; $i < count($recs); $i++) { 
                $rec_id .= $recs[$i].','; 
            } 
            $rec_id = rtrim($rec_id,',');
        }
        //$cart_list = M('cart')->where(array('user_id'=>$user_id,'user_flag'=>$user_flag))->select();

        $cart_list = M('cart')->where(array('rec_id'=>array('in',$rec_id)))->select();

        if(empty($cart_list)){
            if($user_flag == 1){ 
                $u = U('mobile.php/SIndex/Index/',array('id'=>$user_id));
            }elseif($user_flag == 2){
                $u = U('mobile.php/TIndex/index/',array('teacher_id'=>$user_id));
            }elseif($user_flag == 3){
                $u = U('mobile.php/Ucenter/index');
            }elseif($user_flag == 4){
                $u = U('mobile.php/MIndex/index',array('id'=>$user_id));
            }
            $this->error('购物车中暂无商品',$u);
        }

        $cart_amount = 0;
        $cart_number = 0;
        foreach ($cart_list as $key => $value) {
            $cart_number += $value['book_number'];

            // $cart_list[$key]['thumb'] = M('books')->where(array('book_id'=>$value['book_id']))->getField('book_thumb');
            $cart_list[$key]['thumb'] = M('product')->where(array('product_id'=>$value['book_id']))->getField('thumb_url');

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
            if($address_id < 1) $address_id = M('schools')->where(array('school_id'=>$user_id))->getField('address_id');
            $this->assign('url',U('mobile.php/SIndex/Index/',array('id'=>$user_id)));  
            $integral = M('schools')->where(array('school_id'=>$user_id))->getField('rank_points');
        }elseif($user_flag == 2){
            if($address_id < 1) $address_id = M('teacher')->where(array('teacher_id'=>$user_id))->getField('address_id');
            $this->assign('url',U('mobile.php/TIndex/index/',array('teacher_id'=>$user_id)));  
            $integral = M('teacher')->where(array('teacher_id'=>$user_id))->getField('rank_points');
        }elseif($user_flag == 3){
            if($address_id < 1) $address_id = M('students')->where(array('student_id'=>$user_id))->getField('address_id');
            $this->assign('url',U('mobile.php/Ucenter/index')); 
            $integral = M('students')->where(array('student_id'=>$user_id))->getField('student_points'); 
        }elseif($user_flag == 4){
            if($address_id < 1) $address_id = M('admins')->where(array('admin_id'=>$user_id))->getField('address_id');
            $this->assign('url',U('mobile.php/MIndex/index',array('id'=>$user_id))); 
            $integral = M('admins')->where(array('admin_id'=>$user_id))->getField('rank_points'); 
        }
        $address_info = M('address')->where(array('address_id'=>$address_id))->find();
        $order_sn = get_order_sn();

        $integral_amount = 0;
        $order_amount = 0;


        //计算是否可以使用萌星支付
   //      if($integral >= 1200){
   //          $integral_amount = floor($integral/1200);
   //          $cart_amount = $cart_amount - $integral_amount*1200;
   //          $order_amount = ceil($cart_amount/1200)+5;
			// $cart_amount = ceil($cart_amount/1200);
   //      }
        // 用金豆支付(包括部分和全部)      
        if($integral >= 1200){
            // 金豆折价
            $integral_amount = floor($integral/1200);
            // 订单总金额=商品原价+运费($ship_amount)
            $order_amount = ceil($cart_amount/1200)+10;
            // $cart_amount 商品原价
            $cart_amount = ceil($cart_amount/1200);
            if($integral_amount>=$order_amount)
            {
                // 金豆支付(全额)
                $integral_amount = $order_amount;
            }
        }
        else{
            $integral_amount = 0;
            $order_amount = ceil($cart_amount/1200)+10;
			$cart_amount = ceil($cart_amount/1200);
        }  
        $this->assign('integral_amount',$integral_amount);


        $this->assign('rec_id',$rec_id);
        $this->assign('goods_list',$cart_list);
        $this->assign('order_sn',$order_sn);
        $this->assign('order_amount',$order_amount);
		$this->assign('cart_amount',$cart_amount);
		$this->assign('shipping_amount',10);
        $this->assign('cart_number',$cart_number);
        $this->assign('address_info',$address_info);
        $this->assign('user_id',$user_id);
        $this->assign('user_flag',$user_flag);
        $this->display('order/checkout');
    }

     // 更改状态
    public function ok1()
    {
        $a = file_get_contents('1.txt');
        $a = $a."0";
        file_put_contents('1.txt',$a);
        $order_id = I('get.order_id','');
        $user_id = I('param.user_id',0);
        $user_flag = I('param.user_flag',0);
        $info = M('order_info')->where(array('order_id'=>$order_id))->find();
        // var_dump($info);die;
        $integral = $info['integral'];
        $integral_amount = 0;
        if($integral > 0){
            $integral_amount = floor($integral/1200);  
        }
        $order_sn = $info['order_sn'].rand(1,10000);
        $order_amount = $info['order_amount'];

        if($info['order_amount'] < 1){
            if($user_flag == 1){ 
                $u = U('mobile.php/SIndex/Index/',array('id'=>$user_id));
            }elseif($user_flag == 2){
                $u = U('mobile.php/TIndex/index/',array('teacher_id'=>$user_id));
            }elseif($user_flag == 3){
                $u = U('mobile.php/Ucenter/index');
            }elseif($user_flag == 4){
                $u = U('mobile.php/MIndex/index',array('id'=>$user_id));
            }
            $this->error('购物车中暂无商品',$u);
        }

        // $orderamount = $info['order_amount']-$integral_amount+5;
        $this->assign('order_amount',$order_amount);
        // $this->assign('orderamount',$orderamount);
        $user_id = $info['user_id'];
        //$user_flag = $info['user_flag'];

        if($user_flag == 1){
            $this->assign('url',U('mobile.php/SIndex/Index/',array('id'=>$user_id)));  
        }elseif($user_flag == 2){
            $this->assign('url',U('mobile.php/TIndex/index/',array('teacher_id'=>$user_id)));  
        }elseif($user_flag == 3){
            $this->assign('url',U('mobile.php/Ucenter/index')); 
        }elseif($user_flag == 4){
            $this->assign('url',U('mobile.php/MIndex/index',array('id'=>$user_id))); 
        }
        // 扣除金豆
        if($integral_amount > 0){
            $integrals = $integral_amount * 1200;
            if($user_flag == 1){
                M('schools')->where(array('school_id'=>$user_id))->setDec('rank_points',$integrals);  
            }elseif($user_flag == 2){
                M('teacher')->where(array('teacher_id'=>$user_id))->setDec('rank_points',$integrals); 
            }elseif($user_flag == 3){
                M('students')->where(array('student_id'=>$user_id))->setDec('student_points',$integrals); 
            }elseif($user_flag == 4){
                M('admins')->where(array('admin_id'=>$user_id))->setDec('rank_points',$integrals); 
            }
        }
        // 支付日志
        $log_id = M('pay_log')->add(array('order_amount'=>$order_amount,'order_id'=>$order_id,'order_type'=>1,'user_id'=>$user_id,'is_paid'=>1,'user_flag'=>$user_flag,'log_time'=>time()));
        // 更改支付状态;
        $data = array('order_status'=>1,'pay_status'=>2);
        M('order_info')->where(array('order_id'=>$order_id))->setField($data);
        $res = M('order_goods')->where(array('order_id'=>$order_id))->select();
            foreach($res as $key=>$value)
            {
                $log = file_get_contents('1.txt','0');
                $product_id = $value['book_id'];
                $product_number = $value['book_number'];
                M('product')->where(array('product_id'=>$product_id))->setDec('product_num',$product_number);
            }
        // $this->succ();
        $orderinfo = M('order_goods')->where(array('order_id'=>$order_id))->find();
        $mobile = $orderinfo['mobile'];
        $shop_price = $orderinfo['shop_price'];

        if($mobile!=0 && $res['flow']!=0)
        {
            // 充流量
            echo 1;
        }
        if($mobile!=0)
        {
            // 充话费
            $package = intval($shop_price);
            Vendor('Cell.CellCharge');
            $ihuyi = new \CellCharge();   
            $data = $ihuyi->getMethod($package, $mobile, $orderId = md5(date('YmdHis')));
            $res = json_decode($data,true);
            if($res['code'] != 1)
            {
                echo "充值失败";
                die;
            }

        }
        // die;
        $this->success('支付成功',U('mobile.php/Ucenter/index'));
        die;
    }
    // public function ok1()
    // {
    //     $a = file_get_contents('1.txt');
    //     $a = $a."0";
    //     file_put_contents('1.txt',$a);
    //     // $this->success('支付成功',U('mobile.php/Ucenter/index'));
    // }
    public function ok()
    {
        Vendor('WxPayPubHelper.WxPayPubHelper');   

        $jsApi = new \JsApi_pub();
        $code = I('get.code','');
        $order_id = I('get.order_id','');
        $user_id = I('param.user_id',0);
        $user_flag = I('param.user_flag',0);
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
            //$redirect = urlencode("http://".$_SERVER['HTTP_HOST']."/mobile.php?m=mobile.php&c=Order&a=ok&order_id=$order_id&user_id=$user_id&user_flag=$user_flag");
            //$url = $jsApi->createOauthUrlForCode($redirect);
            //header("Location:$url");
        }else{
            $jsApi->setCode($code);
            $openid = $jsApi->getOpenid();
        }

        $info = M('order_info')->where(array('order_id'=>$order_id))->find();
        $this->assign('info',$info);
        // 要扣除的金豆数
        $integral = $info['integral'];
        $integral_amount = 0;

        if($integral > 0){
            $integral_amount = floor($integral/1200);  
        }

        $this->assign('integral_amount',$integral_amount);
        $this->assign('shipping_amount',10);
        $order_sn = $info['order_sn'].rand(1,10000);
        // 人民币价格
        $order_amount = $info['order_amount'];

        if($info['order_amount'] < 1){
            if($user_flag == 1){ 
                $u = U('mobile.php/SIndex/Index/',array('id'=>$user_id));
            }elseif($user_flag == 2){
                $u = U('mobile.php/TIndex/index/',array('teacher_id'=>$user_id));
            }elseif($user_flag == 3){
                $u = U('mobile.php/Ucenter/index');
            }elseif($user_flag == 4){
                $u = U('mobile.php/MIndex/index',array('id'=>$user_id));
            }
            $this->error('购物车中暂无商品',$u);
        }
        // 还需支付
        $orderamount = $info['order_amount']-$integral_amount;
        // $orderamount = 0.01;

        // echo $orderamount;die;



        $this->assign('order_amount',$order_amount);
        $this->assign('orderamount',$orderamount);
        $user_id = $info['user_id'];
        //$user_flag = $info['user_flag'];

        if($user_flag == 1){
            $this->assign('url',U('mobile.php/SIndex/Index/',array('id'=>$user_id)));  
        }elseif($user_flag == 2){
            $this->assign('url',U('mobile.php/TIndex/index/',array('teacher_id'=>$user_id)));  
        }elseif($user_flag == 3){
            $this->assign('url',U('mobile.php/Ucenter/index')); 
        }elseif($user_flag ==4){
            $this->assign('url',U('mobile.php/MIndex/index',array('id'=>$user_id)));
        }

        if($openid)
        {
            $unifiedOrder = new \UnifiedOrder_pub();
            
            //$order_amount = '0.01';
            $log_id = M('pay_log')->add(array('order_amount'=>$order_amount,'order_id'=>$order_id,'order_type'=>1,'user_id'=>$user_id,'user_flag'=>$user_flag,'log_time'=>time()));

            $unifiedOrder->setParameter("openid","$openid");//商品描述
            $unifiedOrder->setParameter("body",'购买图书费用');//商品描述
            $unifiedOrder->setParameter("out_trade_no","$order_sn");//商户订单号
            $unifiedOrder->setParameter("attach",strval($log_id));//商户支付日志
            $unifiedOrder->setParameter("total_fee",strval(intval($orderamount*100)));//总金额
            //$unifiedOrder->setParameter("total_fee",1);//总金额
            $unifiedOrder->setParameter("notify_url","http://".$_SERVER['HTTP_HOST']."/wxpay/index.php");//通知地址
            $unifiedOrder->setParameter("trade_type","JSAPI");//交易类型
            $prepay_id = $unifiedOrder->getPrepayId();

            if(empty($prepay_id)){
                $redirect = urlencode("http://".$_SERVER['HTTP_HOST']."/mobile.php?m=mobile.php&c=Order&a=ok&order_id=$order_id&user_id=$user_id&user_flag=$user_flag");
                $url = $jsApi->createOauthUrlForCode($redirect,$payment['appid']);
                header("Location:$url");
                exit;
            }

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
                $html .= "location.href='".U('mobile.php/Order/succ',array('order_id'=>$order_id))."'";
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

        $this->assign('info',$info);
        $this->display('order/done');
    }

    public function succ()
    {
        $order_id = I('order_id',0);
        $orderinfo = M('order_goods')->where(array('order_id'=>$order_id))->find();
        $mobile = $orderinfo['mobile'];
        $shop_price = $orderinfo['shop_price'];

        if($mobile!=0 && $res['flow']!=0)
        {
            // 充流量
            echo 1;
        }
        if($mobile!=0)
        {
            // 充话费
            $package = intval($shop_price);
            Vendor('Cell.CellCharge');
            $ihuyi = new \CellCharge();   
            $data = $ihuyi->getMethod($package, $mobile, $orderId = md5(date('YmdHis')));
            $res = json_decode($data,true);
            if($res['code'] == 1)
            {
                echo "充值成功";

            }
            die;
        }
        
        $this->success('支付成功',U('mobile.php/Ucenter/index'));
    }
    // 金豆支付
    public function done1()
    {
        $user_id = I('param.user_id',0);
        $user_flag = I('param.user_flag',0);
        $recid = I('param.recid','');
        $is_cell = I('param.is_cell',0);
        $this->assign('is_cell',$is_cell);
        $this->assign('user_id',$user_id);
        $this->assign('user_flag',$user_flag);
        $this->assign('recid',$recid);
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
        }elseif($user_flag == 4){
            $address_id = M('admins')->where(array('admin_id'=>$user_id))->getField('address_id');
            $points = M('admins')->where(array('admin_id'=>$user_id))->getField('rank_points'); 
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
        $integral_amount = 0;

        //计算是否可以使用萌星支付
        if($points >= 1200){
            // $cart_amount = $cart_amount - $integral_amount;
            $integral_amount = floor($points/1200);
            // 手机流量无运费
            if($is_cell == 1)
            {
                $order_amount = ceil($cart_amount/1200);
            }else
            {
                $order_amount = ceil($cart_amount/1200)+10;
            }
            $cart_amount = ceil($cart_amount/1200);
            if($integral_amount<=$order_amount)
            {
                // 还需支付
                $orderamount = $order_amount - $integral_amount; 
            }else{
                $orderamount = 0;
                $integral_amount = $order_amount;  
            }
        }
        $this->assign('shipping_amount',10);
        $this->assign('integral_amount',$integral_amount);
        $this->assign('order_amount',$cart_amount);

        $data['order_sn'] = get_order_sn();
        $data['user_id'] = $user_id;
        $data['user_flag'] = $user_flag;
        $data['invoice_name'] = I('get.invoice','');
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
        $data['integral'] = $integral_amount * 1200;
        $data['order_amount'] = $data['book_amount'] + $data['shipping_fee'];
        $data['add_time'] = $time;
        if($is_cell == 1){
            $data['is_cell'] = 1;
        }
        $order_id = M('order_info')->add($data);
        $this->assign('order_id',$order_id);
        
        //添加数据到订单商品表中
        foreach ($cart_list as $key => $value) {
            $goods_data = array();
            $goods_data['order_id'] = $order_id;
            $goods_data['book_id'] = $value['book_id'];
            $goods_data['product_id'] = $value['product_id'];
            $goods_data['book_name'] = $value['book_name'];
            $goods_data['book_sn'] = $value['book_sn'];
            $goods_data['book_number'] = $value['book_number'];
            $goods_data['market_price'] = $value['market_price'];
            $goods_data['shop_price'] = $value['shop_price'];
            $goods_data['points_price'] = $value['points_price'];
            $goods_data['ret_type'] = $value['ret_type'];
            $goods_data['mobile'] = $value['mobile'];
            M('order_goods')->add($goods_data);
        }
        //清空购物车数据
        M('cart')->where(array('rec_id'=>array('in',$recid)))->delete();
        $this->assign('info',$data);
        $this->display('order/done');
    }
    // 订单提交
    public function done()
    {
 		$user_id = I('param.user_id',0);
 		$user_flag = I('param.user_flag',0);
        $recid = I('param.recid','');
        $is_cell = I('param.is_cell',0);
 		//$cart_list = M('cart')->where(array('user_id'=>$user_id,'user_flag'=>$user_flag))->select();
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
		}elseif($user_flag == 4){
            $address_id = M('admins')->where(array('admin_id'=>$user_id))->getField('address_id');
            $points = M('admins')->where(array('admin_id'=>$user_id))->getField('rank_points'); 
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
		

        $integral_amount = 0;
        //计算是否可以使用萌星支付
        if($points >= 1200){
            // $cart_amount = $cart_amount - $integral_amount;
            // 金豆折算
            $integral_amount = floor($points/1200);//50
            // 订单总金额
            if($is_cell == 1)
            {
                $order_amount = ceil($cart_amount/1200);
            }else
            {
                $order_amount = ceil($cart_amount/1200)+10;
            }
            // 商品原价
            $cart_amount = ceil($cart_amount/1200);//30
            if($integral_amount<=$order_amount)
            {
                // 还需支付
                $orderamount = $order_amount - $integral_amount; 
            }else{
                $orderamount = 0;
                $integral_amount = $order_amount;  
            }
        } 
        // 使用金豆支付(全部或者部分)
        else
        {
            // echo $cart_amount."<br />";
            // 订单总金额+运费
            if($is_cell == 1)
            {
                $order_amount = ceil($cart_amount/1200);
            }else
            {
                $order_amount = ceil($cart_amount/1200)+10;
            }
            $cart_amount = ceil($cart_amount/1200);
            // echo $order_amount;die;
            // 金豆支付
            $integral_amount = 0;
            // 还需支付
            $orderamount = $order_amount;
        } 
        $this->assign('integral_amount',$integral_amount);
        $this->assign('order_amount',$order_amount);
        // $this->assign('orderamount',$orderamount);

		$data['order_sn'] = get_order_sn();
		$data['user_id'] = $user_id;
		$data['user_flag'] = $user_flag;
        $data['invoice_name'] = I('get.invoice','');
		$data['order_status'] = 0;
		$data['shipping_status'] = 0;
		$data['pay_status'] = 0;
		$data['shipping_id'] = 1;
		$data['shipping_name'] = "快递配送";
		$data['pay_id'] = 1;
		$data['pay_name'] = "萌星支付";
		$data['book_amount'] = $cart_amount;
		$data['shipping_fee'] = "10.00";
        if($is_cell == 1){
            $data['shipping_fee'] = '0.00';
        }
		$data['money_paid'] = "0.00";
		$data['integral'] = $integral_amount * 1200;
		$data['order_amount'] = $data['book_amount'] + $data['shipping_fee'];
		$data['add_time'] = $time;
        // 如果是手机添加手机标识符
        if($is_cell == 1){
            $data['is_cell'] = 1;
        }

		$order_id = M('order_info')->add($data);

        //扣除相应的积分
        if($integral_amount > 0){
            $integrals = $integral_amount * 1200;
            if($user_flag == 1){
                M('schools')->where(array('school_id'=>$user_id))->setDec('rank_points',$integrals);  
            }elseif($user_flag == 2){
                M('teacher')->where(array('teacher_id'=>$user_id))->setDec('rank_points',$integrals); 
            }elseif($user_flag == 3){
                M('students')->where(array('student_id'=>$user_id))->setDec('student_points',$integrals); 
            }elseif($user_flag == 4){
                M('admins')->where(array('admin_id'=>$user_id))->setDec('rank_points',$integrals); 
            }
        }
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
            $goods_data['mobile'] = $value['mobile'];
			M('order_goods')->add($goods_data);
		}

		//清空购物车数据
		//M('cart')->where(array('user_id'=>$user_id,'user_flag'=>$user_flag))->delete();
        M('cart')->where(array('rec_id'=>array('in',$recid)))->delete();


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
            $redirect = urlencode("http://".$_SERVER['HTTP_HOST']."/mobile.php?m=mobile.php&c=Order&a=ok&order_id=$order_id&user_id=$user_id&user_flag=$user_flag");
            $url = $jsApi->createOauthUrlForCode($redirect,$payment['appid']);
            header("Location:$url");
        }
        
        $this->assign('info',$data);
        $this->display('order/done');
        /*exit;

        if($points > 1200 && $data['order_amount'] >= $points){
            //$integral_amount
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

		$this->success('提交订单成功',U('mobile.php/Order/index',array('user_id'=>$user_id,'user_flag'=>$user_flag)));*/
    }

    public function donetwo()
    {
        $order_id = I('param.order_id',0);
        $user_id = I('param.user_id',0);
        $user_flag = I('param.user_flag',0);
        $order_amount = M('order_info')->where(array('order_id'=>$order_id))->getField('order_amount');            
        
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

        $redirect = urlencode("http://".$_SERVER['HTTP_HOST']."/mobile.php?m=mobile.php&c=Order&a=ok&order_id=$order_id&user_id=$user_id&user_flag=$user_flag");
        $url = $jsApi->createOauthUrlForCode($redirect,$payment['appid']);
        header("Location:$url");
        


        /*if($user_flag == 1){
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
                $points = M('schools')->where(array('school_id'=>$user_id))->getField('rank_points');
            }elseif($user_flag == 2){
                M('teacher')->where(array('teacher_id'=>$user_id))->save(array('rank_points'=>$new_points)); 
                $points = M('teacher')->where(array('teacher_id'=>$user_id))->getField('rank_points'); 
            }elseif($user_flag == 3){
                M('students')->where(array('student_id'=>$user_id))->save(array('student_points'=>$new_points)); 
                $points = M('students')->where(array('student_id'=>$user_id))->getField('student_points');
            }

            //更新订单付款状态
            M('order_info')->where(array('order_id'=>$order_id))->save(array('pay_status'=>2));
        }else{
            $this->error('萌星不够支付',U('mobile.php/Order/index',array('user_id'=>$user_id,'user_flag'=>$user_flag)));
            exit;
        }

        $this->success('支付成功',U('mobile.php/Order/index',array('user_id'=>$user_id,'user_flag'=>$user_flag)));*/
    }
}