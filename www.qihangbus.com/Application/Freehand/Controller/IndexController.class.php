<?php
namespace Freehand\Controller;
use Think\Controller;
class IndexController extends CommonController {
    public function index(){
		
		//订购总人数
		$buy_number = M("students")->where(array('is_paid'=>array('gt',0)))->count();
		$this->assign('buy_number',$buy_number);
		
		//上线总人数
		$online_number = M("students")->join("fh_students_parent on fh_students_parent.student_id=fh_students.student_id","left")->where("fh_students_parent.wx_id is not null AND fh_students_parent.wx_id != ''")->count();
		$this->assign('online_number',$online_number);
		
		//新订单(未发货的都属于新订单)
		$order_number = M("order_info")->where(array('order_status'=>1,'shipping_status'=>array('lt',1),'pay_status'=>2))->count();
		$this->assign('order_number',$order_number);
		
		//损坏赔偿
		$damage_number = M("compensate")->where(array('compen_status'=>array('lt',3)))->count();
		$this->assign('damage_number',$damage_number);
		
		//图书总数量
		$book_total = M("books")->where(array('cate_id'=>array('lt',6)))->count();
		//五大分类
		$book_cate_1 = M("books")->where(array('cate_id'=>1))->count();
		$this->assign('book_cate_1',$book_cate_1);
		$this->assign('book_cate_1_pre',round( $book_cate_1/$book_total * 100));
		$book_cate_2 = M("books")->where(array('cate_id'=>2))->count();	
		$this->assign('book_cate_2',$book_cate_2);
		$this->assign('book_cate_2_pre',round( $book_cate_2/$book_total * 100));
		$book_cate_3 = M("books")->where(array('cate_id'=>3))->count();
		$this->assign('book_cate_3',$book_cate_3);
		$this->assign('book_cate_3_pre',round( $book_cate_3/$book_total * 100));
		$book_cate_4 = M("books")->where(array('cate_id'=>4))->count();
		$this->assign('book_cate_4',$book_cate_4);
		$this->assign('book_cate_4_pre',round( $book_cate_4/$book_total * 100));
		$book_cate_5 = M("books")->where(array('cate_id'=>5))->count();
		$this->assign('book_cate_5',$book_cate_5);
		$this->assign('book_cate_5_pre',round( $book_cate_5/$book_total * 100));
		
        $this->display("./index");
    }
}