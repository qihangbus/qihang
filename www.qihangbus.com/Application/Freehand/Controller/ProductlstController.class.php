<?php
namespace Freehand\Controller;
use Think\Controller;
class ProductlstController extends CommonController {
    public function index()
    {
    	$res = M('product')->where(array('is_product'=>0))->select();
    	foreach ($res as $key => $value) {
    		$res[$key]['cate_name'] = M('product_cate')->where(array('cate_id'=>$value['cate_id']))->getField('cate_name');
    	}
    	$this->assign('res',$res);
     	$this->display('product/index');
    }
    // 添加商品
    public function add()
    {
    	$category = M('product_cate')->select();
    	$this->assign('category',$category);
    	$this->display('product/info');
    }
    // 删除
    public function del()
    {
    	$id = I('get.id');
        $ret = M('product')->where(array('product_id'=>$id))->delete();
        if($ret){
            $this->success('删除成功',U('/Productlst/index'),1);
        }else{
            $this->error('删除失败！');
        }
    }
    // 修改
    public function update()
    {
    	$product_id = I('id');
    	$product = M('product')->find($product_id);
    	$this->assign('product',$product);
    	$ret1 = M('product_cate')->select();
    	$this->assign('category',$ret1);
    	$this->display('product/info');
    }
    public function handle()
    {
    	$product_id = I('product_id');
        $data['product_name'] = I('product_name');
    	$data['product_price'] = I('product_price');
    	$data['cate_id'] = I('cate_id');
        $data['product_desc'] = I('product_desc');
        $data['content_desc'] = I('content_desc');
        $data['market_price'] = I('market_price');
        $data['product_num'] = I('product_num');
        $data['shop_price'] = I('shop_price');
        $data['flow'] = I('flow',0);
    if($_FILES['product_img']['tmp_name']!='')
    {
        $upload = new \Think\Upload();
        $upload->maxSize = 3145728;
        $upload->exts = array('jpg', 'gif', 'png', 'jpeg');
        $upload->saveName = 'time';
        $info = $upload->upload();

        if (!$info) {
            // 原图和缩略图
            $data['img_url'] = I('post.img_url','');
            // $data['thumb_url'] = I('post.thumb_url','');
        }else{
            // var_dump($info);
            $filename = $info['product_img']['savepath'].$info['product_img']['savename'];

            // echo $filename;die;

            $image = new \Think\Image();
            // open()方法
            $image->open('./Uploads/'.$filename);
            $book_img = $info['product_img']['savename'];
            $book_thumb = 'thumb_'.$info['product_img']['savename'];
            //缩放图书图片
            $base = $this->configs;
            $thumb_width = isset($base['thumb_width']) ? $base['thumb_width'] : 100;
            $thumb_height = isset($base['thumb_height']) ? $base['thumb_height'] : 100;
            // $image->thumb($thumb_width,$thumb_height)->save("./Uploads/".$info['product_img']['savepath']."$book_thumb");

            // $img_width = isset($base['image_width']) ? $base['image_width'] : 230;
            // $img_height = isset($base['image_height']) ? $base['image_height'] : 230;
            $images = new \Think\Image();
            $images->open('./Uploads/'.$filename);
            $images->thumb($img_width,$img_height)->save("./Uploads/".$info['product_img']['savepath']."$book_img");
            // 删除图片
            // unlink("./Uploads/".$filename);
            $data['img_url'] = "/Uploads/".$info['product_img']['savepath'].$book_img;
            // $data['thumb_url'] = "/Uploads/".$info['product_img']['savepath'].$book_thumb;
        }
    }
    	if($product_id>0)
    	{
    		M('product')->where(array('product_id'=>$product_id))->save($data);
    		$this->success('修改成功',U('/Productlst/index'),1);
    	}else
    	{
			M('product')->add($data);
    		$this->success('添加成功',U('/Productlst/index'),1);
    	}	
    }
}