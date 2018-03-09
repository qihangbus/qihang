<?php
namespace Freehand\Controller;
use Think\Controller;
class AdPositionController extends CommonController {
    public function index(){
    	$page_size = I('param.page_size',15);
        $this->assign('page_size',$page_size);
        /*$keywords = I('param.keywords','');
        $this->assign('keywords',$keywords);
        if($keywords){
            $condition['book_name'] = array('like',"%$keywords%");
        }*/

        $ads = M('AdPosition');
        $count = $ads->count();
        $page = new \Think\Page($count,$page_size);
        $show = $page->diyshow();

        $list = $ads->order('position_id desc')->limit($page->firstRow.','.$page->listRows)->select();

        $this->assign('list',$list);
        $this->assign('page',$show);
        $this->display("ad/ad_position");
    }

    public function add()
    {
    	$this->display("ad/position_info");
    }

    public function edit()
    {
    	$id = I('get.id',0);
    	$info = M('AdPosition')->where(array('position_id'=>$id))->find();
    	$this->assign('info',$info);
    	$this->display("ad/position_info");
    }

    public function edit_handle()
    {
    	$data = array();
    	$position_id = I('post.position_id',0);

    	$data['position_name'] = I('post.position_name','');
    	$data['ad_width'] = I('post.ad_width',0);
    	$data['ad_height'] = I('post.ad_height',0);
    	$data['position_desc'] = I('post.position_desc','');

    	if($position_id > 0){
    		$msg = "修改成功";
    		$ret = M('AdPosition')->where(array('position_id'=>$position_id))->save($data);
    	}else{
    		$msg = "添加成功";
    		$ret = M('AdPosition')->add($data);
    	}

    	if($ret){
    		$this->success($msg,U('AdPosition/index'));
    	}else{
    		$this->error($msg);
    	}
    }

    public function del()
    {
    	$id = I('get.id',0);
    	$ret = M('AdPosition')->where(array('position_id'=>$id))->delete();
    	if($ret){
    		$this->success('删除成功',U('AdPosition/index'));
    	}else{
    		$this->error("删除失败",U('AdPosition/index'));
    	}
    }
}