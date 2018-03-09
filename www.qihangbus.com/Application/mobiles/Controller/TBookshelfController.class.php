<?php
// 本类由系统自动生成，仅供测试用途
namespace mobiles\Controller;
use Think\Controller;
class TBookshelfController extends Controller {
    public function index(){
	     $bookshelf=M("books");
       $teacher_id=I('param.teacher_id',0);
       $this->assign('user_id',$teacher_id);

       $type = I('param.type',1);
       $this->assign('type',$type);
       $book_in = array();
       $book_out = array();
       $list = array();
       $class_id = M('teacher')->where(array('teacher_id'=>$teacher_id))->getField('class_id');

       $schooltobook = M('schooltobook');
       $classbooks=$schooltobook
           ->field("fh_schooltobook.class_id,fh_schooltobook.book_id,fh_books.book_name,fh_books.book_thumb,book_no")
           ->join("fh_books on fh_books.book_id=fh_schooltobook.book_id")
           ->where("class_id=$class_id")
           ->order('book_no')
           ->select();

       $ret = M('circulation')->where(array('class_id'=>$class_id,'circul_status'=>1))->select();
       $arr = array();
       $circula = array();
       $uarr = array();



       foreach ($ret as $key => $value) {
         array_push($arr, $value['book_id']);
         $uarr[$value['book_id']] = $value['student_id'];
         $circula[$value['book_id']] = $value['add_time'];
       }


       $school_id = M('teacher')->where(array('teacher_id'=>$teacher_id))->getField('school_id');
       $carr = array();
        //查询损毁的图书
        $compen = M('compensate')->where(array('school_id'=>$school_id,'class_id'=>$class_id))->select();
        foreach ($compen as $key => $value) {
            $carr[] = $value['book_id'];
        }

        $carr = array_flip(array_flip($carr));

        if($type == 1){
          foreach ($classbooks as $key => $value) {
            if(in_array($value['book_id'], $arr)) continue;
            if(in_array($value['book_id'], $carr)) continue;
            $value['user_name'] = M('students')->where(array('student_id'=>$uarr[$value['book_id']]))->getField('student_name');
            $value['add_time'] = $circula[$value['book_id']];
            $list[] = $value;    
          }
        }
        elseif($type == 2)
        {
          foreach ($classbooks as $key => $value) {
            //if(in_array($value['book_id'], $carr)) continue;
            if(in_array($value['book_id'], $arr) || in_array($value['book_id'], $carr)){
              $value['user_name'] = M('students')->where(array('student_id'=>$uarr[$value['book_id']]))->getField('student_name');
              $value['add_time'] = $circula[$value['book_id']];
              if(in_array($value['book_id'], $carr)){
				  $value['bad'] = 1;
			  }else{
				  $value['bad'] = 0;
				 }
			  $list[] = $value;  
            }  
          }
        }
      
        
        $total = count($classbooks);
        $out_num = count($list);
        $in_num = $total - $out_num;
        if($type == 1){
          $this->assign('in_num',$out_num);
          $this->assign('out_num',$in_num);
        }elseif($type == 2){
          $this->assign('in_num',$in_num);
          $this->assign('out_num',$out_num);
        }
        $this->assign("list",$list);
        $this->assign("book_in",$book_in);
        $this->display("index");
    }
    
    public function bookhistory(){
       $book_id=I("param.book_id",0);
       $teacher_id=I('param.teacher_id',0);
       $this->assign('user_id',$teacher_id); 


       $circulation = M('circulation');
       $list=$circulation->
       field("fh_books.book_id,fh_books.book_name,fh_books.book_thumb,fh_circulation.add_time,fh_circulation.student_id")->
       join('fh_books on fh_books.book_id=fh_circulation.book_id')-> 
       where('fh_circulation.book_id='.$book_id)-> 
       select();
       foreach ($list as $key => $value) {
         $list[$key]['user_name'] = M('students')->where(array('student_id'=>$value['student_id']))->getField('student_name');
       }

       $this->assign('list',$list);  
       $log=M("circul_log");
       $history=$log->
       field("fh_books.book_id,fh_books.book_name,book_thumb,borrow_time,return_time")->
       join('fh_books on fh_books.book_id=fh_circul_log.book_id')-> 
       where('fh_circul_log.book_id='.$book_id)-> 
       select();    

       $this->assign('history',$history);  
       $this->display("TBookshelf:bookhistory");
    }
	
	
	public function info()
    {
    	$book_id = I('param.id',0);
        $this->assign('book_id',$book_id);
    	$user_id = I('param.user_id',0);
        $user_flag = I('param.user_flag',0);
    	$book_info = M('books')->where(array('book_id'=>$book_id))->find();
    	$this->assign('user_id',$user_id);
        $this->assign('user_flag',$user_flag);
    	$book_gallery = M('book_gallery')->where(array('book_id'=>$book_id))->select();
    	$this->assign('book_gallery',$book_gallery);
    	$this->assign('info',$book_info);

        if($user_flag == 1){
            $this->assign('url',U('mobile.php/SIndex/Index/',array('id'=>$user_id)));
        }elseif($user_flag == 2){
            $this->assign('url',U('mobile.php/TIndex/index/',array('teacher_id'=>$user_id)));
        }elseif($user_flag == 3){
            $this->assign('url',U('mobile.php/Ucenter/index'));
        }else{
            $this->assign('url',U('mobile.php/Ucenter/index'));
        }

    	$this->display('TBookshelf/book_info');
    }

    //添加商品到购物车
    public function add_to_cart()
    {
      $book_id = I('param.book_id',0);
      $data['user_id'] = I('param.student_id',0);
      $data['user_flag'] = 2;
      $data['book_number'] = I('param.book_num',0);
      $book_info = M('books')->where(array('book_id'=>$book_id))->find();
      $data['book_id'] = $book_id;
      $data['book_sn'] = $book_info['book_sn'];
      $data['book_name'] = $book_info['book_name'];
      $data['market_price'] = $book_info['market_price'];
      $data['shop_price'] = $book_info['shop_price'];
      $data['points_price'] = $book_info['points_price'];
      $data['ret_type'] = 1;

        $recid = M("cart")->where(array('book_id'=>$book_id,'user_id'=>$data['user_id'],'user_flag'=>$data['user_flag']))->getField('rec_id');
        if($recid > 0){
            $ret = M('cart')->where(array('rec_id'=>$recid))->setInc('book_number',$data['book_number']);
        }else{
          $ret = M("cart")->add($data);
        }
      echo $data['user_id'];
    }
}