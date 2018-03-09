<?php
namespace Freehand\Controller;
use Think\Controller;
class ListsController extends CommonController {
    public function index(){
        $page_size = I('param.page_size',15);
        $this->assign('page_size',$page_size);
        $keywords = I('param.keywords','');
        $this->assign('keywords',$keywords);
        if($keywords){
            $condition = "title like '%$keywords%'";
        }

		$arr = array(1=>'启航要闻',2=>'启航社区',3=>'专家讲坛',4=>'园所风采');
		
        $news = M('news');
        $count = $news->where($condition)->count();
        $page = new \Think\Page($count,$page_size);
        $show = $page->diyshow();

        $list = $news->where($condition)->order('add_time desc')->limit($page->firstRow.','.$page->listRows)->select();
        
        foreach ($list as $key => $value) {
            $list[$key]['cate_name'] = $arr[$value['category']];    
        }

        $this->assign('list',$list);
        $this->assign('page',$show);
        $this->display("forum/index");
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

	//微信扫码预览
	public function view()
	{
		$id = I('get.id','');
		$qrcode = M("news")->where(array('id'=>$id))->getField("qrcode");
		
		if(empty($qrcode)){
			vendor("phpqrcode.phpqrcode");
			$level = "L";
			$size = 4;
			$path = __ROOT__.'Public/qrcode/';
			$qrcode = $path.time().'.png';
			$QRcode = new \QRcode();
			
			
			$url = "http://".$_SERVER['SERVER_NAME']."/sns.php?m=sns.php&c=Index&a=info&id=$id";
			$QRcode::png($url,$qrcode,$level,$size);
			M("news")->where(array('id'=>$id))->save(array('qrcode'=>$qrcode));
		}
		
		$header_url = "http://".$_SERVER['SERVER_NAME'].'/'.$qrcode;

		header("Location:$header_url");
	}
	
    //添加资讯
    public function add()
    {
        $this->display("forum/info");
    }

    //编辑资讯
    public function edit()
    {
        $id = I('get.id');
        $news = M('news')->where(array('id'=>$id))->find();
        $this->assign('info',$news);
        
        $this->display("forum/info");
    }

    //处理数据
    public function edit_handle()
    {
        vendor("phpThumb.phpThumb");
        $condition = array();
        $id = I('post.id','');
        $condition['sup_id'] = I('post.sup_id','');
        $condition['category'] = I('post.category','');
        $condition['title'] = I('post.title','');
        $condition['description'] = I('post.description','');
        $condition['content'] = I('post.content','');
        $condition['is_top'] = I('post.is_top',0);

		
        $upload = new \Think\Upload();
        $upload->maxSize = 3145728;
        $upload->exts = array('jpg', 'gif', 'png', 'jpeg');
        $upload->saveName = 'time';
        $info = $upload->upload();
        
        if (!$info) {
            $condition['thumb'] = I('post.thumb_old','');
        }else{
            $filename = $info['img']['savepath'].$info['img']['savename'];
            $image = new \Think\Image();
            $image->open('./Uploads/'.$filename);
            $book_img = 'image_'.$info['img']['savename'];
            $book_thumb = 'thumb_'.$info['img']['savename'];

            //缩放图书图片
            $base = $this->configs;
            $thumb_width = isset($base['thumb_width']) ? $base['thumb_width'] : 300;
            $thumb_height = isset($base['thumb_height']) ? $base['thumb_height'] : 300;
            $image->thumb($thumb_width,$thumb_height)->save("./Uploads/".$info['img']['savepath']."$book_thumb");

            $img_width = isset($base['image_width']) ? $base['image_width'] : 300;
            $img_height = isset($base['image_height']) ? $base['image_height'] : 300;
            $images = new \Think\Image();
            $images->open('./Uploads/'.$filename);
            $images->thumb($img_width,$img_height)->save("./Uploads/".$info['img']['savepath']."$book_img");
            unlink("./Uploads/".$filename);
            $condition['thumb'] = "/Uploads/".$info['img']['savepath'].$book_thumb;
        }

        if($id > 0){

            //删除对应的相册文件
            $condition['add_time'] = time();
            $ret = M('news')->where(array('id'=>$id))->save($condition);
            $this->success('修改成功',U('/Lists/index'),1);
        }else{
           
            $condition['add_time'] = time();
            $condition['user_id'] = session('uid');
            $ret = M('news')->add($condition);
           
            $this->success('添加成功',U('/Lists/index'),1);
        }
    }

    //删除
    public function del(){
        $id = I('get.id');
        $ret = M('news')->where(array('id'=>$id))->delete();
        if($ret){
            $this->success('删除成功',U('/Lists/index'),1);
        }else{
            $this->error('删除失败！');
        }
    }
}