<?php
namespace Freehand\Controller;
use Think\Controller;
class BooksController extends CommonController {
    public function index(){
        $page_size = I('param.page_size',15);
        $this->assign('page_size',$page_size);
        $keywords = I('param.keywords','');
        $this->assign('keywords',$keywords);
        if($keywords){
            //$condition['book_name'] = array('like',"%$keywords%");
            //$condition['sub_name'] = array('like',"%$keywords%");
            $condition = "book_name like '%$keywords%' or sub_name like '%$keywords%'";
        }

        $books = M('books');
        $count = $books->where($condition)->count();
        $page = new \Think\Page($count,$page_size);
        $show = $page->diyshow();

        $list = $books->where($condition)->order('book_thumb asc')->limit($page->firstRow.','.$page->listRows)->order('book_id desc')->select();
        
        foreach ($list as $key => $value) {
            $list[$key]['cate_name'] = M('book_cate')->where(array('cate_id'=>$value['cate_id']))->getField('cate_name');    
            $list[$key]['sup_name'] = M('suppliers')->where(array('sup_id'=>$value['sup_id']))->getField('sup_name');
        }

        $this->assign('list',$list);
        $this->assign('page',$show);
        $this->display("book/index");
    }

	//临时用导出文件
	public function exportcsv()
	{
		$list = M("books")->field("sup_id,book_name,cate_id,book_author,book_desc,class_2,class_3,class_4")->select();
		$str = "供应商,分类,图书名,作者,内容简介,适合小班,适合中班,适合大班\n";
		$str = iconv('utf-8','gb2312',$str);
        $arr = array(1=>'科普益智类',2=>'成长故事类',3=>'其他',4=>'情商培养类',5=>'好习惯养成类');
		for($i=0;$i<count($list);$i++){
            $sup_name = M("suppliers")->where(array('sup_id'=>$list[$i]['sup_id']))->getField("sup_name");
            $sup_name = iconv('utf-8','gb2312',$sup_name);
            $catename = $arr[$list[$i]['cate_id']];
			$catename = iconv('utf-8','gb2312',$catename);
            $name = iconv('utf-8','gb2312',$list[$i]['book_name']);
			$book_author = iconv('utf-8','gb2312',$list[$i]['book_author']);
            $book_desc = iconv('utf-8','gb2312',$list[$i]['book_desc']);
            $class_2 = $list[$i]['class_2'];
            $class_3 = $list[$i]['class_3'];
            $class_4 = $list[$i]['class_4'];
			$str .= $sup_name.",".$catename.",".$name.",".$book_author.",".$book_desc.",".$class_2.",".$class_3.",".$class_4."\n";
		}
		$filename = date('Ymd').'.csv';
		export_csv($filename,$str);
	}
	
	public function exportcsv2()
	{
		$list = M("books")->field("sup_id,book_name,cate_id,book_isbn,market_price,shop_price,book_number")->select();
		$str = "编码,供应商,分类,图书名,市场价,本店价,库存\n";
		$str = iconv('utf-8','gb2312',$str);
        $arr = array(1=>'科普益智类',2=>'成长故事类',3=>'其他',4=>'情商培养类',5=>'好习惯养成类');
		for($i=0;$i<count($list);$i++){
            $book_isbn = $list[$i]['book_isbn'];
			$sup_name = M("suppliers")->where(array('sup_id'=>$list[$i]['sup_id']))->getField("sup_name");
            $sup_name = iconv('utf-8','gb2312',$sup_name);
            $catename = $arr[$list[$i]['cate_id']];
			$catename = iconv('utf-8','gb2312',$catename);
            $name = iconv('utf-8','gb2312',$list[$i]['book_name']);
            $market_price = $list[$i]['market_price'];
            $shop_price = $list[$i]['shop_price'];
            $book_number = $list[$i]['book_number'];
			$str .= $book_isbn.",".$sup_name.",".$catename.",".$name.",".$market_price.",".$shop_price.",".$book_number."\n";
		}
		$filename = date('Ymd').'.csv';
		export_csv($filename,$str);
	}
	
     public function uploads()
    {
        $ueditor_config = json_decode(preg_replace("/\/\*[\s\S]+?\*\//", "", file_get_contents("./Public/ueditor/php/config.json")), true);
        $action = $_GET['action'];
        switch ($action) {
            case 'config':
                $result = json_encode($ueditor_config);
                break;
            /* 上传图片 */
            case 'uploadimage':
                /* 上传涂鸦 */
            case 'uploadscrawl':
                /* 上传视频 */
            case 'uploadvideo':
                /* 上传文件 */
            case 'uploadfile':
                $upload = new \Think\Upload();
                $upload->maxSize = 3145728;
                $upload->exts = array('jpg', 'gif', 'png', 'jpeg');
                $info = $upload->upload();
                if (!$info) {
                    $result = json_encode(array(
                        'state' => $upload->getError(),
                    ));
                } else {
               $url = __ROOT__ . "/Uploads/" . $info["upfile"]["savepath"] . $info["upfile"]['savename'];
                    $result = json_encode(array(
                        'url' => $url,
                        'title' => htmlspecialchars($_POST['pictitle'], ENT_QUOTES),
                        'original' => $info["upfile"]['name'],
                        'state' => 'SUCCESS'
                    ));
                }
                break;
            default:
                $result = json_encode(array(
                    'state' => '请求地址出错'
                ));
                break;
        }
        /* 输出结果 */
        if (isset($_GET["callback"])) {
            if (preg_match("/^[\w_]+$/", $_GET["callback"])) {
                echo htmlspecialchars($_GET["callback"]) . '(' . $result . ')';
            } else {
                echo json_encode(array(
                    'state' => 'callback参数不合法'
                ));
            }
        } else {
            echo $result;
        }
    }

    //添加
    public function add()
    {
        $this->assign('info', array('book_status'=>1));
        $ret = M('suppliers')->select();
        $this->assign('suppliers',$ret);

        $ret1 = M('book_cate')->select();
        $this->assign('category',$ret1);
        $this->display("book/info");
    }

    //编辑
    public function edit()
    {
        $id = I('get.id');
        $books = M('books')->where(array('book_id'=>$id))->find();
        $this->assign('info',$books);
        $ret = M('suppliers')->select();
        $this->assign('suppliers',$ret);

         $ret1 = M('book_cate')->select();
        $this->assign('category',$ret1);
        $this->display("book/info");
    }

    //处理数据
    public function edit_handle()
    {
        vendor("phpqrcode.phpqrcode");
        vendor("phpThumb.phpThumb");
        $condition = array();
        $bid = I('post.book_id','');
        $condition['sup_id'] = I('post.sup_id','');
        $condition['cate_id'] = I('post.cate_id','');
        $condition['book_name'] = I('post.book_name','');
        $condition['sub_name'] = I('post.sub_name','');
        $condition['book_isbn'] = I('post.book_isbn','');
        $book_isbn = I('post.book_isbn','');
        $condition['book_sn'] = I('post.book_sn','');
        $condition['book_author'] = I('post.book_author','');
        $condition['author_desc'] = I('post.author_desc','');
        $condition['book_number'] = I('post.book_number','');
        $condition['book_desc'] = I('post.book_desc','');
        $condition['market_price'] = I('post.market_price','');
        $condition['shop_price'] = I('post.shop_price','');
        $condition['promotion_price'] = I('post.promotion_price','');
        $condition['points_price'] = I('post.points_price','');
        $condition['contents'] = I('post.contents','');
        $condition['video'] = I('post.video','');
        $condition['book_status'] = I('post.book_status','');
        $condition['school_flag'] = I('post.school_flag',0);
        $condition['teacher_flag'] = I('post.teacher_flag',0);
        $condition['parent_flag'] = I('post.parent_flag',0);
        $condition['is_recommend'] = I('post.is_recommend','');
        $condition['recommend_desc'] = I('post.recommend_desc','');
		
		$condition['class_1'] = I('post.class_1',0);
        $condition['class_2'] = I('post.class_2',0);
        $condition['class_3'] = I('post.class_3',0);
		$condition['class_4'] = I('post.class_4',0);
		
        $upload = new \Think\Upload();
        $upload->maxSize = 3145728;
        $upload->exts = array('jpg', 'gif', 'png', 'jpeg');
        $upload->saveName = 'time';
        $info = $upload->upload();
        
        if (!$info) {
            $condition['book_img'] = I('post.book_img_old','');
            $condition['book_thumb'] = I('post.book_thumb_old','');
        }else{
            $filename = $info['img']['savepath'].$info['img']['savename'];
            $image = new \Think\Image();
            $image->open('./Uploads/'.$filename);
            $book_img = 'image_'.$info['img']['savename'];
            $book_thumb = 'thumb_'.$info['img']['savename'];

            //缩放图书图片
            $base = $this->configs;
            $thumb_width = isset($base['thumb_width']) ? $base['thumb_width'] : 100;
            $thumb_height = isset($base['thumb_height']) ? $base['thumb_height'] : 100;
            $image->thumb($thumb_width,$thumb_height)->save("./Uploads/".$info['img']['savepath']."$book_thumb");

            $img_width = isset($base['image_width']) ? $base['image_width'] : 200;
            $img_height = isset($base['image_height']) ? $base['image_height'] : 200;
            $images = new \Think\Image();
            $images->open('./Uploads/'.$filename);
            $images->thumb($img_width,$img_height)->save("./Uploads/".$info['img']['savepath']."$book_img");
            unlink("./Uploads/".$filename);
            $condition['book_img'] = "/Uploads/".$info['img']['savepath'].$book_img;
            $condition['book_thumb'] = "/Uploads/".$info['img']['savepath'].$book_thumb;
        }

        $level = "L";
        $size = 4;
        $path = __ROOT__.'Public/qrcode/';
        $filename = $path.time().'.png';
        $QRcode = new \QRcode();
        if($bid > 0){

            //删除对应的相册文件
            M('book_gallery')->where(array('book_id'=>$bid))->delete();
            M('book_gallery')->add(array('book_id'=>$bid,'img_url'=>$condition['book_img'],'thumb_url'=>$condition['book_thumb']));
            $condition['add_date'] = time();
            $condition['book_qrcode'] = $filename;
            $ret = M('books')->where(array('book_id'=>$bid))->save($condition);
            $url = 'http://'.$_SERVER['SERVER_NAME'].U('mobile.php/Book/book_info',array('book_id'=>$ret));
            $QRcode::png($$book_isbn,$filename,$level,$size);
            //unlink();//删除以前的二维码
            $this->success('修改成功',U('/Books/index'),1);
        }else{
            $url = 'http://'.$_SERVER['SERVER_NAME'].U('mobile.php/Book/book_info',array('book_id'=>$ret));
            $QRcode::png($$book_isbn,$filename,$level,$size);

            $condition['add_date'] = time();
            $condition['add_uid'] = session('uid');
            $condition['book_qrcode'] = $filename;
            $ret = M('books')->add($condition);
           
            M('book_gallery')->where(array('book_id'=>$ret))->delete();
            M('book_gallery')->add(array('book_id'=>$ret,'img_url'=>$condition['book_img'],'thumb_url'=>$condition['book_thumb']));

            
            $this->success('添加成功',U('/Books/index'),1);
        }
    }

    public function qrcode()
    {
        vendor("phpqrcode.phpqrcode");
        $book_id = I('param.book_id',0);
        $level = "L";
        $size = 4;
        $path = __ROOT__.'Public/qrcode/';
        $filename = $path.time().'.png';
        $QRcode = new \QRcode();
        $book_isbn = M('books')->where(array('book_id'=>$book_id))->getField('book_isbn');
        $url = 'http://'.$_SERVER['SERVER_NAME'].U('mobile.php/Book/book_info',array('book_id'=>$book_id));
        $QRcode::png($book_isbn,$filename,$level,$size);
        $ret = M('books')->where(array('book_id'=>$bid))->save(array('book_qrcode'=>$filename));
        if($ret){
            echo $filename;
        }
    }

    //删除
    public function del(){
        $id = I('get.id');
        $ret = M('books')->where(array('book_id'=>$id))->delete();
        if($ret){
            $this->success('删除成功',U('/Books/index'),1);
        }else{
            $this->error('删除失败！');
        }
    }

    //导入
    public function import()
    {
        $this->display("book/import_book");
    }

    //处理导入数据
    public function edit_import()
    {
        $filename = $_FILES['file']['tmp_name'];
        if(empty($filename))
        {
            $this->error('请选择要导入的csv文件!');
            exit;
        }

        $handle = fopen($filename,'r');
        $result = import_csv($handle);
        $len_result = count($result);
        if($len_result == 0)
        {
            $this->error('没有任何数据!');
            exit;
        }
        set_time_limit(0);
        $dir = 'Uploads/'.date('Y-m-d');
        is_dir($dir) || mkdir($dir,0777);

        for($i=1;$i<$len_result;$i++)
        {
            if(empty($result[$i][0])) continue;
            $edit_arr = array();

            //处理图片
            $book_isbn = iconv('gbk','utf-8',$result[$i][5]);
            $book = M('books')->where("book_isbn = $book_isbn")->find();
            if($book){//有相同的图书
                $book_number = $result[$i][7];
                M('books')->where("book_id = {$book['book_id']}")->setInc('book_number',$book_number);
                continue;
            }

            $filename = 'Public/bookImgs/'.$book_isbn.'.jpg';

            if(file_exists($filename)){//处理缩略图和详情页图

                $book_img = 'image_'.$book_isbn.'.jpg';
                $book_thumb = 'thumb_'.$book_isbn.'.jpg';
                $base = $this->configs;

                $image = new \Think\Image();
                $image->open($filename);
                $thumb_width = isset($base['thumb_width']) ? $base['thumb_width'] : 200;
                $thumb_height = isset($base['thumb_height']) ? $base['thumb_height'] : 200;
                $image->thumb($thumb_width,$thumb_height)->save("$dir/$book_thumb");

                $images = new \Think\Image();
                $images->open($filename);
                $img_width = isset($base['image_width']) ? $base['image_width'] : 100;
                $img_height = isset($base['image_height']) ? $base['image_height'] : 100;
                $images->thumb($img_width,$img_height)->save("$dir/$book_img");
                unlink($filename);
                $edit_arr['book_img'] = "/$dir/$book_img";
                $edit_arr['book_thumb'] = "/$dir/$book_thumb";
            }else{//不存在图片
                $edit_arr['book_img'] = $result[$i][4];
            }

            $supplier_name = iconv('gbk','utf-8',$result[$i][0]);

            $edit_arr['sup_id'] = M('suppliers')->where(array('sup_name'=>$supplier_name))->getField('sup_id');
            if(empty($edit_arr['sup_id'])){
                $edit_arr['sup_id'] = M('suppliers')->add(array('sup_name'=>$supplier_name,'sup_mobile'=>'13000000000','cat_id'=>5,'add_time'=>time()));
            }
            $cate_name = iconv('gbk','utf-8',$result[$i][1]);
            $edit_arr['cate_id'] = M('book_cate')->where(array('cate_name'=>$cate_name))->getField('cate_id');

            if(empty($edit_arr['cate_id'])){
                $edit_arr['cate_id'] = M('book_cate')->add(array('cate_name'=>$cate_name,'cate_desc'=>$cate_name));
            }
            $edit_arr['book_name'] = iconv('gbk','utf-8',$result[$i][2]);
            $edit_arr['sub_name'] = iconv('gbk','utf-8',$result[$i][3]);
            $edit_arr['book_isbn'] = iconv('gbk','utf-8',$result[$i][5]);
            $edit_arr['book_sn'] = iconv('gbk','utf-8',$result[$i][6]);
            $edit_arr['book_number'] = $result[$i][7];
            $edit_arr['book_author'] = iconv('gbk','utf-8',$result[$i][8]);
            $edit_arr['author_desc'] = iconv('gbk','utf-8',$result[$i][9]);
            $edit_arr['book_desc'] = iconv('gbk','utf-8',$result[$i][10]);
            $edit_arr['market_price'] = $result[$i][11];
            $edit_arr['shop_price'] = $result[$i][12];
            $edit_arr['promotion_price'] = $result[$i][13];
            $edit_arr['points_price'] = $result[$i][14];
            $edit_arr['hardcover_flag'] = $result[$i][15];
            $edit_arr['school_flag'] = $result[$i][16];
            $edit_arr['teacher_flag'] = $result[$i][17];
            $edit_arr['parent_flag'] = $result[$i][18];
            $edit_arr['class_2'] = $result[$i][19];
            $edit_arr['class_3'] = $result[$i][20];
            $edit_arr['class_4'] = $result[$i][21];
            $title = iconv('gbk','utf-8',$result[$i][22]);
            $option = iconv('gbk','utf-8',$result[$i][23]);
            $ops = iconv('gbk','utf-8',$result[$i][24]);

            $edit_arr['add_date'] = time();
            $book_id = M('books')->add($edit_arr);
            $task_id = M("task")->add(array('task_title'=>$title,'task_desc'=>$title,'task_award'=>50,'task_type'=>2,'task_book_id'=>$book_id));
            $option_arr = explode(':',$ops);

            if(count($option_arr)>1){
                for($x=0;$x<count($option_arr);$x++){
                    $correct_value = 0;
                    if($option == $option_arr[$x]) $correct_value = 1;
                    M("task_option")->add(array('task_id'=>$task_id,'option_name'=>$option_arr[$x],'option_type'=>1,'correct_value'=>$correct_value));
                }
            }else{
                M("task_option")->add(array('task_id'=>$task_id,'option_name'=>$option,'option_type'=>1,'correct_value'=>1));
            }

        }

        $this->success('导入成功',U('/Books/index'),1);
    }
}