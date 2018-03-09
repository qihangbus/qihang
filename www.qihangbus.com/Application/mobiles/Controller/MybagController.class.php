<?php
namespace mobiles\Controller;
use Think\Controller;
class MybagController extends CommonController {
    //推荐绘本
    public function index(){
    	$model = M('schooltobook');
    	$userinfo = session('userinfo');

        $user_id = I('param.user_id',0);
        $this->assign('user_id',$user_id);
        $user_flag = I('param.user_flag',0);
        $this->assign('user_flag',$user_flag);

		$grade_name = M('grade')->where(array('grade_id'=>$userinfo['grade_id']))->getField('grade_name');
		$this->assign('grade_name',$grade_name);
        $this->assign('userinfo',$userinfo);
        $condition = array();
        $class_id = M('students')->where(array('student_id'=>$user_id))->getField('class_id');
        $condition['class_id'] = $class_id;
        $type = I('param.type','class');
        $this->assign('type',$type);
        $age = I('param.age',0);
        $this->assign('age',$age);
        
		$list = $model->alias('stb')->join(C('DB_PREFIX').'books b ON stb.book_id = b.book_id','left')->where($condition)->order('stb.book_id desc')->select();
        $school_id = M('students')->where(array('student_id'=>$user_id))->getField('school_id');
        $carr = array();
        //查询损毁的图书
        $compen = M('compensate')->where(array('school_id'=>$school_id))->select();
        foreach ($compen as $key => $value) {
            $carr[] = $value['book_id'];
        }

        $arr = array();

        //查询已经借出去和已被预约的图书
        if($type == 'subscribe'){
            $circul = M('circulation')->where(array('class_id'=>$class_id))->select();
            foreach ($circul as $key => $value) {
                $arr[] = $value['book_id'];
            }
        }


        foreach ($list as $key => $value) {
            if(in_array($value['book_id'], $carr)){
                unset($list[$key]);
            }
            if(in_array($value['book_id'], $arr)){
                unset($list[$key]);
            }
        }

        $this->assign('list',$list);
        $cate = M('book_cate')->where(array('cate_id'=>array('lt',99)))->field("cate_id,cate_name")->select();
        $this->assign('cate',$cate);
		$this->display('bag/index');
    }

    public function allow()
    {
        $user_id = I('param.user_id',0);
        $this->assign('user_id',$user_id);
        $user_flag = I('param.user_flag',0);
        $this->assign('user_flag',$user_flag);
        $circul = M('circulation')->where(array('student_id'=>$user_id))->field('book_id')->select();
        $arr = array();
        foreach ($circul as $key => $value) {
            array_push($arr, $value['book_id']);
        }

        $school_id = M('students')->where(array('student_id'=>$user_id))->getField('school_id');
        $carr = array();
        //查询损毁的图书
        $compen = M('compensate')->where(array('school_id'=>$school_id))->select();
        foreach ($compen as $key => $value) {
            $carr[] = $value['book_id'];
        }

        $class_id = M('students')->where(array('student_id'=>$user_id))->getField('class_id');
        $list = M('schooltobook')->join("fh_books on fh_books.book_id=fh_schooltobook.book_id")->where(array('class_id'=>$class_id))->select();

        foreach ($list as $key => $value) {
            if(in_array($value['book_id'], $carr)){
                unset($list[$key]);
            }
            if(in_array($value['book_id'], $arr)){
                unset($list[$key]);
            }
        }

        $this->assign('list',$list);
        $this->assign('type','allow');
        $this->display('bag/index');
    }

    //按年龄分类
    public function age_book(){
    	$model = M('schooltobook');
    	$userinfo = session('userinfo');
		$grade_name = M('grade')->where(array('grade_id'=>$userinfo['grade_id']))->getField('grade_name');
		$this->assign('grade_name',$grade_name);
		$list = $model->alias('stb')->join(C('DB_PREFIX').'books b ON stb.book_id = b.book_id','left')->where(array('age_type'=>1))->order('stb.book_id desc')->select();
		$this->assign('list',$list);
		$this->display('bag/index');
    }

    //按情景分类
    public function scene_book(){
    	$model = M('schooltobook');
    	$userinfo = session('userinfo');
		$grade_name = M('grade')->where(array('grade_id'=>$userinfo['grade_id']))->getField('grade_name');
		$this->assign('grade_name',$grade_name);
		$list = $model->alias('stb')->join(C('DB_PREFIX').'books b ON stb.book_id = b.book_id','left')->where(array('scene_type'=>1))->order('stb.book_id desc')->select();
		$this->assign('list',$list);
		$this->display('bag/index');
    }

    //建议阅读
    public function advice_read()
    {
		$model = M('schooltobook');
		$userinfo = session('userinfo');
		$list = $model->alias('stb')->join(C('DB_PREFIX').'books b ON stb.book_id = b.book_id','left')->where(array('is_recommend'=>1))->order('stb.book_id desc')->select();
		$this->assign('list',$list);
    	$this->display('bag/advice_read');
    }

    //商品详情
    public function read_info()
    {
    	$book_id = I('param.book_id',0);
    	$this->assign('book_id',$book_id);

        $user_id = I('param.user_id',0);
        $this->assign('user_id',$user_id);
        $user_flag = I('param.user_flag',0);
        $this->assign('user_flag',$user_flag);

    	$info = M('books')->where(array('book_id'=>$book_id))->find();
		$this->assign('info',$info);
		$book_gallery = M('book_gallery')->where(array('book_id'=>$book_id))->select();

    	$this->assign('book_gallery',$book_gallery);
    	$this->display('bag/read_info');
    }

    //添加商品到购物车
    public function add_to_cart()
    {
    	$book_id = I('param.book_id',0);
    	$data['user_id'] = I('param.student_id',0);
		$data['user_flag'] = 3;
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