<?php
namespace mobiles\Controller;
use Think\Controller;
class BookController extends Controller {
    // public function index(){
    // 	$user_id = I('param.user_id',0);
    //   $user_flag = I('param.user_flag',1);
    // 	$user_points = I('param.student_points',0);
		  // $this->assign('user_points',$user_points);
		  // $cate_id = I('param.cate_id',0);
    //   $this->assign('cate_id',$cate_id);
    // 	$type = I('param.type',1);
    //     if($user_flag == 1){
    //         $condition['school_flag'] = 1;
    //         $userinfo = M('schools')->where(array('school_id'=>$user_id))->find();
    //         $userinfo['student_id'] = $userinfo['school_id'];
    //         $userinfo['student_points'] = $userinfo['rank_points'];
    //         $this->assign('url',U('mobile.php/SIndex/Index/',array('id'=>$user_id)));
    //     }elseif($user_flag == 2){
    //         $condition['teacher_flag'] = 1;
    //         $userinfo = M('teacher')->where(array('teacher_id'=>$user_id))->find();
    //         $userinfo['student_id'] = $userinfo['teacher_id'];
    //         $userinfo['student_points'] = $userinfo['rank_points'];
    //         $this->assign('url',U('mobile.php/TIndex/Index/',array('teacher_id'=>$user_id)));
    //     }elseif($user_flag == 3){
    //         $condition['parent_flag'] = 1;
    //         $userinfo = M('students')->where(array('student_id'=>$user_id))->find();
    //         $this->assign('url',U('mobile.php/Ucenter/index'));
    //     }elseif($user_flag == 4){
    //         $condition['school_flag'] = 1;
    //         $userinfo = M('admins')->where(array('admin_id'=>$user_id))->find();
    //         $userinfo['student_id'] = $userinfo['admin_id'];
    //         $userinfo['student_points'] = $userinfo['rank_points'];
    //         $this->assign('url',U('mobile.php/MIndex/index',array('id'=>$user_id)));
    //     }else{
    //         $condition['parent_flag'] = 1;
    //         $userinfo = M('students')->where(array('student_id'=>$user_id))->find();
    //         $this->assign('url',U('mobile.php/Ucenter/index'));
    //     }

		
		  // $condition['book_number'] = array("gt",'0');
    //   $count = M('cart')->where(array('user_id'=>$user_id,'user_flag'=>$user_flag))->count();
    //   $this->assign('count',$count);
    // 	$book_list = M('books')->where($condition)->select();
        
    // 	$this->assign('book_list',$book_list);

    //   $cate = M('book_cate')->select();
    //   $this->assign('cate',$cate);

    // 	$this->assign('type',$type);
    // 	$this->assign('user_id',$user_id);
    //   $this->assign('userinfo',$userinfo);
    //   $this->assign('user_flag',$user_flag);
		  // $this->display('book/index');
    // }
    // ----------------by yx 2017.9.1
    public function index(){
        $user_id = I('param.user_id',0);
        $user_flag = I('param.user_flag',1);
        $user_points = I('param.user_points',0);
        $this->assign('user_points',$user_points);
        $cateid = I('param.cateid',0);
        $this->assign('cateid',$cateid);
        $condition['cate_id'] = $cateid;

  // 该处代码为区分推荐和可兑换
        // if($cateid > 0){
        //     $condition['cate_id'] = $cateid;
        // }
        // $cate_id = I('param.cate_id',0);
        // $this->assign('cate_id',$cate_id);
        // if($cate_id == 1){
        //     $condition['cate_id'] = array('lt',6);
        // }elseif($cate_id == 2){
        //     $condition['cate_id'] = array('gt',5);
        // }
        $type = I('param.type',1);
        if($type == 1){
            $condition['_string'] = 'hardcover_flag=1 OR awards_flag=1 OR praise_flag=1 OR demand_flag=1';
        }
        // 判断用户身份
        if($user_flag == 1){
            $condition['school_flag'] = 1;
            $userinfo = M('schools')->where(array('school_id'=>$user_id))->find();
            $userinfo['student_id'] = $userinfo['school_id'];
            $userinfo['student_points'] = $userinfo['rank_points'];
            $this->assign('url',U('mobile.php/SIndex/Index/',array('id'=>$user_id)));
        }elseif($user_flag == 2){
            // 2,老师
            $condition['teacher_flag'] = 1;
            $userinfo = M('teacher')->where(array('teacher_id'=>$user_id))->find();
            $userinfo['student_id'] = $userinfo['teacher_id'];
            $userinfo['student_points'] = $userinfo['rank_points'];
            $this->assign('url',U('mobile.php/TIndex/Index/',array('teacher_id'=>$user_id)));
        }elseif($user_flag == 3){
            // 3,学生家长
            $condition['parent_flag'] = 1;
            $userinfo = M('students')->where(array('student_id'=>$user_id))->find();
            $this->assign('url',U('mobile.php/Ucenter/index'));
        }elseif($user_flag == 4){  //图书管理员
            $condition['school_flag'] = 1;
            $userinfo = M('admins')->where(array('admin_id'=>$user_id))->find();
            $userinfo['student_id'] = $userinfo['admin_id'];
            $userinfo['student_points'] = $userinfo['rank_points'];
            $this->assign('url',U('mobile.php/MIndex/index',array('id'=>$user_id)));
        }else{
            $condition['parent_flag'] = 1;
            $userinfo = M('students')->where(array('student_id'=>$user_id))->find();
            $this->assign('url',U('mobile.php/Ucenter/index'));
        }

        // 购物车
        $condition['book_number'] = array("gt",'0');
        $count = M('cart')->where(array('user_id'=>$user_id,'user_flag'=>$user_flag))->count();
        $this->assign('count',$count);
        // $book_list = M('books')->where($condition)->select();
     //    $this->assign('book_list',$book_list);
        if($cateid == 0)
        {
            $product = M('product')->where(array('is_product'=>0))->select();
        }else
        {
            $product = M('product')->where(array('is_product'=>0,'cate_id'=>$cateid))->select();
        }
        // var_dump($product);die;
        $this->assign('product',$product);
        // 产品分类
        $cate = M('product_cate')->select();
        $this->assign('cate',$cate);
        $this->assign('type',$type);
        $this->assign('user_id',$user_id);
        $this->assign('userinfo',$userinfo);
        $this->assign('user_flag',$user_flag);
        // $this->display('book/index');
        $this->display('book/index1');
    }

    //兑换记录
    public function points_record()
    {
    	$user_id = I('param.user_id',0);
        $user_flag = I('param.user_flag',1);
    	$record = M('points_record')->where(array('user_id'=>$user_id,'user_flag'=>$user_flag))->select();
    	$this->assign('record',$record);
        
        if($user_flag == 1){
            $userinfo = M('schools')->where(array('school_id'=>$user_id))->find();
            $userinfo['student_points'] = $userinfo['rank_points'];

            $this->assign('url',U('mobile.php/SIndex/Index/',array('id'=>$user_id)));
        }elseif($user_flag == 2){
            $userinfo = M('teacher')->where(array('teacher_id'=>$user_id))->find();
            $userinfo['student_points'] = $userinfo['rank_points'];
            $this->assign('url',U('mobile.php/TIndex/index/',array('teacher_id'=>$user_id)));
        }elseif($user_flag == 3){
            $userinfo = M('students')->where(array('student_id'=>$user_id))->find();
            $this->assign('url',U('mobile.php/Ucenter/index'));
        }else{
            $userinfo = M('students')->where(array('student_id'=>$user_id))->find();
            $this->assign('url',U('mobile.php/Ucenter/index'));
        }
        $this->assign('userinfo',$userinfo);
    	$this->display('book/points_record');
    }

    //图书详情
    // public function book_info()
    // {
    // 	$book_id = I('param.book_id',0);
    // 	$user_id = I('param.user_id',0);
		  // $user_points = I('param.user_points',0);
		  // $this->assign('user_points',$user_points);
    //   $user_flag = I('param.user_flag',0);
    // 	$book_info = M('books')->where(array('book_id'=>$book_id))->find();
    // 	$this->assign('user_id',$user_id);
    //   $this->assign('user_flag',$user_flag);
    // 	$book_gallery = M('book_gallery')->where(array('book_id'=>$book_id))->select();
    // 	$this->assign('book_gallery',$book_gallery);
    // 	$this->assign('book_info',$book_info);

    //   if($user_flag == 1){
    //         $this->assign('url',U('mobile.php/SIndex/Index/',array('id'=>$user_id)));
    //     }elseif($user_flag == 2){
    //         $this->assign('url',U('mobile.php/TIndex/index/',array('teacher_id'=>$user_id)));
    //     }elseif($user_flag == 3){
    //         $this->assign('url',U('mobile.php/Ucenter/index'));
    //     }else{
    //         $this->assign('url',U('mobile.php/Ucenter/index'));
    //     }
    // 	$this->display('book/book_info');
    // }
    
    //商品详情
    public function book_info()
    {
      $product_id = I('param.product_id',0); 
      $user_id = I('param.user_id',0);
      $user_points = I('param.user_points',0);
      $this->assign('user_points',$user_points);
      $user_flag = I('param.user_flag',0);
      // $book_info = M('books')->where(array('book_id'=>$book_id))->find();
      $product_info = M('product')->where(array('product_id'=>$product_id))->find(); 
        // var_dump($product_info);die;
      $this->assign('user_id',$user_id);
      $this->assign('user_flag',$user_flag);
      // $book_gallery = M('book_gallery')->where(array('book_id'=>$book_id))->select();
      $this->assign('book_gallery',$book_gallery);
      // $this->assign('book_info',$book_info);
      $this->assign('product_info',$product_info); 
      $this->assign('product_id',$product_id); 
      if($user_flag == 1){
            $this->assign('url',U('mobile.php/SIndex/Index/',array('id'=>$user_id)));
        }elseif($user_flag == 2){
            // 老师
            $this->assign('url',U('mobile.php/TIndex/index/',array('teacher_id'=>$user_id)));
        }elseif($user_flag == 3){
            $this->assign('url',U('mobile.php/Ucenter/index'));
        }elseif($user_flag ==4){
            $this->assign('url',U('mobile.php/MIndex/index',array('id'=>$user_id)));
        }else{
            $this->assign('url',U('mobile.php/Ucenter/index'));
        }
      $this->display('book/book_info1');
    }
    // 添加购物车   by------------>yx
    public function add_to_cart()
    {
        $data['book_id'] = I('param.product_id',0);
        $data['user_id'] = I('param.user_id',0);
        $data['user_flag'] = I('param.user_flag',0);
        $data['book_number'] = I('param.product_num',0);
        $data['mobile'] = I('param.mobile',0);
        $product_info = M('product')->where(array('product_id'=>$data['book_id']))->find();
        $data['book_name'] = $product_info['product_name'];
        // $data['market_price'];
        $data['shop_price'] = $product_info['shop_price']; 
        $data['points_price'] = $product_info['product_price'];
        // ret_type什么意思？金豆支付
        $data['ret_type'] = 1;
        // 标注 1 说明是兑换中心的
        $data['is_exchange'] = 1;
        // 判断数量
        $recid = M("cart")->where(array('book_id'=>$data['book_id'],'user_id'=>$data['user_id'],'user_flag'=>$data['user_flag']))->getField('rec_id');
        if($recid > 0){
            $ret = M('cart')->where(array('rec_id'=>$recid))->setInc('book_number',$data['book_number']);
        }else{
            $ret = M("cart")->add($data);
        }
        echo $data['user_id'];
    }
    // 兑换记录
    public function change_log(){
        $user_id = I('user_id',0);
        $log = M('order_info')->where(array('user_id'=>$user_id,'shipping_status'=>2))->select();
        
        // var_dump($log);die;

    }
    // 话费和流量的流程与礼物的不一样     结算-->金豆支付or微信支付
    public function checkout_cell()
    {
        // 应该是book_id
        // $data['book_id'] = I('param.product_id',0);
        $data['product_id'] = I('param.product_id',0);
        $data['user_id'] = I('param.user_id',0);
        $data['user_flag'] = I('param.user_flag',0);
        $data['book_number'] = I('param.product_num',1);
        $data['mobile'] = I('param.mobile',0);
        // 判断不同的用户的积分
        if($data['user_flag'] == 1){
            $points = M('schools')->where(array('school_id'=>$data['user_id']))->getField('rank_points');  
        }elseif($data['user_flag'] == 2){
            $points = M('teacher')->where(array('teacher_id'=>$data['user_id']))->getField('rank_points'); 
        }elseif($data['user_flag'] == 3){
            $points = M('students')->where(array('student_id'=>$data['user_id']))->getField('student_points'); 
        }elseif($data['user_flag'] == 4){
            $points = M('admins')->where(array('admin_id'=>$data['user_id']))->getField('rank_points'); 
        }
        $product_info = M('product')->where(array('product_id'=>$data['product_id']))->find();
        // book_name product_name
        $data['book_name'] = $product_info['product_name'];
        // $data['market_price'];
        $data['shop_price'] = $product_info['shop_price'];
        $data['points_price'] = $product_info['product_price'];
        // ret_type什么意思？金豆支付
        $data['ret_type'] = 1;
        // 兑换商品的标注(可有可无)
        $data['is_exchange'] = 1;
        $rec_id = M("cart")->add($data);
        $integral_amount = floor($points/1200);
        $order_amount = $data['shop_price'];
        // if($integral_amount>$order_amount)
        // {
        //     $integral_amount = $order_amount;
        // }
        $this->assign('is_cell',1);
        $this->assign('user_flag',$data['user_flag']);
        $this->assign('user_id',$data['user_id']);
        $this->assign('user_flag',$data['user_flag']);
        $this->assign('rec_id',$rec_id);
        $this->assign('integral_amount',$integral_amount);
        $this->assign('order_amount',$order_amount);
        $this->assign('cart_amount',$order_amount);
        $this->display('order/checkout');
    }
}