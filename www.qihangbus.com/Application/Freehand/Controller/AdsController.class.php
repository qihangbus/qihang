<?php
namespace Freehand\Controller;
use Think\Controller;
class AdsController extends CommonController {
    public function index(){
    	$page_size = I('param.page_size',15);
        $this->assign('page_size',$page_size);
        /*$keywords = I('param.keywords','');
        $this->assign('keywords',$keywords);
        if($keywords){
            $condition['book_name'] = array('like',"%$keywords%");
        }*/

        $ad = M('Ad');
        $count = $ad->count();
        $page = new \Think\Page($count,$page_size);
        $show = $page->diyshow();

        $list = $ad->order('ad_id desc')->limit($page->firstRow.','.$page->listRows)->select();

        $type = C('MEDIA_TYPE');
       
        foreach ($list as $key => $value) {
            $list[$key]['type_name'] = $type[$value['media_type']];
            $list[$key]['position_name'] = M('AdPosition')->where(array('position_id'=>$value['position_id']))->getField('position_name');
        }

        $this->assign('list',$list);
        $this->assign('page',$show);
        $this->display("ad/ad");
    }

    public function add()
    {
    	$positions = M('AdPosition')->select();
        $this->assign('positions',$positions);
        $this->display("ad/ad_info");
    }

    public function edit()
    {
    	$id = I('get.id',0);
    	$info = M('Ad')->where(array('ad_id'=>$id))->find();
    	$this->assign('info',$info);
        $positions = M('AdPosition')->select();
        $this->assign('positions',$positions);
    	$this->display("ad/ad_info");
    }

    public function edit_handle()
    {
    	$data = array();
    	$ad_id = I('post.ad_id',0);
        $data['position_id'] = I('post.position_id','');
        $data['media_type'] = I('post.media_type','');
    	$data['ad_name'] = I('post.ad_name','');
    	$data['ad_link'] = I('post.ad_link','');
    	//$data['ad_code'] = I('post.ad_code','');
        $data['start_time'] = strtotime(I('post.start_time',0));
        $data['end_time'] = strtotime(I('post.end_time',''));
        $data['link_man'] = I('post.link_man','');
    	$data['link_email'] = I('post.link_email','');
        $data['link_phone'] = I('post.link_phone','');

        $upload = new \Think\Upload();
        $upload->maxSize = 3145728;
        $upload->exts = array('jpg', 'gif', 'png', 'jpeg');
        $upload->saveName = 'time';
        $upload->rootPath = './Public/';
        $upload->savePath = 'ads/';
        $info = $upload->upload();
        
        if (!$info) {
            $data['ad_code'] = I('post.old_ad_code','');
        }else{
            $filename = $info['ad_code']['savepath'].$info['ad_code']['savename'];
            $ad_img = 'ad_'.$info['ad_code']['savename'];

            $base = M('AdPosition')->where(array('position_id'=>$data['position_id']))->find();
            //缩放图书图片

            $img_width = isset($base['ad_width']) ? $base['ad_width'] : 200;
            $img_height = isset($base['ad_height']) ? $base['ad_height'] : 200;
            $images = new \Think\Image();
            $images->open('./Public/'.$filename);
            $images->thumb($img_width,$img_height)->save("./Public/".$info['ad_code']['savepath']."$ad_img");
            unlink("./Public/".$filename);
            $data['ad_code'] = "/Public/".$info['ad_code']['savepath'].$ad_img;
        }

    	if($ad_id > 0){
    		$msg = "修改成功";
    		$ret = M('Ad')->where(array('ad_id'=>$ad_id))->save($data);
    	}else{
    		$msg = "添加成功";
    		$ret = M('Ad')->add($data);
    	}

    	if($ret){
    		$this->success($msg,U('Ads/index'));
    	}else{
    		$this->error($msg);
    	}
    }

    public function del()
    {
    	$id = I('get.id',0);
    	$ret = M('Ad')->where(array('ad_id'=>$id))->delete();
    	if($ret){
    		$this->success('删除成功',U('Ads/index'));
    	}else{
    		$this->error("删除失败",U('Ads/index'));
    	}
    }
}