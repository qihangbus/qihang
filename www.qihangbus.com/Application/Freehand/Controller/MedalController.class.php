<?php
namespace Freehand\Controller;
use Think\Controller;
class MedalController extends CommonController {
    public function index(){
    	$page_size = I('param.page_size',15);
        $this->assign('page_size',$page_size);
        /*$keywords = I('param.keywords','');
        $this->assign('keywords',$keywords);
        if($keywords){
            $condition['book_name'] = array('like',"%$keywords%");
        }*/

        $medal = M('Medal');
        $count = $medal->count();
        $page = new \Think\Page($count,$page_size);
        $show = $page->diyshow();

        $list = $medal->order('medal_id desc')->limit($page->firstRow.','.$page->listRows)->select();

        $this->assign('list',$list);
        $this->assign('page',$show);
        $this->display("promotion/medal_list");
    }

    public function add()
    {
        $this->display("promotion/medal_info");
    }

    public function edit()
    {
    	$id = I('get.id',0);
    	$info = M('Medal')->where(array('medal_id'=>$id))->find();
    	$this->assign('info',$info);
    	$this->display("promotion/medal_info");
    }

    public function edit_handle()
    {
    	$data = array();
    	$medal_id = I('post.medal_id',0);
    	$data['medal_name'] = I('post.medal_name','');
    	$data['remark'] = I('post.remark','');
        $data['medal_points'] = I('post.medal_points','');

        $upload = new \Think\Upload();
        $upload->maxSize = 3145728;
        $upload->exts = array('jpg', 'gif', 'png', 'jpeg');
        $upload->saveName = 'time';
        $upload->rootPath = './Public/';
        $upload->savePath = 'medal/';
        $info = $upload->upload();
        var_dump($info);
        exit;
        $img_width = 60;
        $img_height = 87;
        if (!$info['medal_pic']) {
            $data['medal_pic'] = I('post.old_medal_pic','');
        }else{
            $filename = $info['medal_pic']['savepath'].$info['medal_pic']['savename'];
            $medal_pic = 'medal_'.$info['medal_pic']['savename'];
            //缩放图书图片
            
            $image = new \Think\Image();
            $image->open('./Public/'.$filename);
            $image->thumb($img_width,$img_height)->save("./Public/".$info['medal_pic']['savepath']."$medal_pic");
            unlink("./Public/".$filename);
            $data['medal_pic'] = "/Public/".$info['medal_pic']['savepath'].$medal_pic;
        }

        if (!$info['medal_high_pic']) {
            $data['medal_high_pic'] = I('post.old_medal_high_pic','');
        }else{
            $file_name = $info['medal_high_pic']['savepath'].$info['medal_high_pic']['savename'];
            $medal_high_pic = 'medal_high_'.$info['medal_high_pic']['savename'];
            //缩放图书图片
            $images = new \Think\Image();
            $images->open('./Public/'.$file_name);
            $images->thumb($img_width,$img_height)->save("./Public/".$info['medal_high_pic']['savepath']."$medal_high_pic");
            unlink("./Public/".$file_name);
            $data['medal_high_pic'] = "/Public/".$info['medal_high_pic']['savepath'].$medal_high_pic;
        }

        var_dump($data);
        exit;
    	if($medal_id > 0){
    		$msg = "修改成功";
    		$ret = M('Medal')->where(array('medal_id'=>$medal_id))->save($data);
    	}else{
    		$msg = "添加成功";
    		$ret = M('Medal')->add($data);
    	}

    	if($ret){
    		$this->success($msg,U('Medal/index'));
    	}else{
    		$this->error($msg);
    	}
    }

    public function del()
    {
    	$id = I('get.id',0);
    	$ret = M('Medal')->where(array('Medal_id'=>$id))->delete();
    	if($ret){
    		$this->success('删除成功',U('Medal/index'));
    	}else{
    		$this->error("删除失败",U('Medal/index'));
    	}
    }
}