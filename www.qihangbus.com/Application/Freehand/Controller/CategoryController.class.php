<?php
namespace Freehand\Controller;
use Think\Controller;
class CategoryController extends CommonController {
    public function index(){
        $category = M('category');
        $count = $category->count();
        $page = new \Think\Page($count,15);
        $show = $page->diyshow();
        $list = $category->order('cat_id desc')->limit($page->firstRow.','.$page->listRows)->select();
        
        foreach ($list as $key => $value) {
            if($value['parent_id'] < 1){
                $list[$key]['parent_name'] = '无';
            }else{
                $list[$key]['parent_name'] = M('category')->where(array('cat_id'=>$value['parent_id']))->getField('cat_name');
            }
        }

        $this->assign('list',$list);
        $this->assign('page',$show);
        $this->display("member/category_index");
    }

    //添加
    public function add()
    {
        
        $this->assign('info', array('is_show'=>1));
        $ret = M('category')->where("parent_id < 1")->select();
        $this->assign('category',$ret);
        $this->display("member/category_info");
    }

    //编辑
    public function edit()
    {
        $id = I('get.id');
        $category = M('category')->where(array('cat_id'=>$id))->find();
        $this->assign('info',$category);
        $ret = M('category')->where("parent_id < 1")->select();
        $this->assign('category',$ret);
        $this->display("member/category_info");
    }

    //处理数据
    public function edit_handle()
    {
        $condition = array();
        $cid = I('post.cat_id','');
        $condition['cat_name'] = I('post.cat_name','');
        $condition['cat_desc'] = I('post.cat_desc','');
        $condition['parent_id'] = I('post.parent_id','');
        $condition['sort_order'] = I('post.sort_order','');
        $condition['is_show'] = I('post.is_show','');

        if($cid > 0){
            $ret = M('category')->where(array('cat_id'=>$cid))->save($condition);
            $this->success('修改成功',U('/Category/index'),1);
        }else{
            $ret = M('category')->add($condition);
            $this->success('添加成功',U('/Category/index'),1);
        }
    }

    //删除
    public function del(){
        $id = I('get.id');
        $ret = M('category')->where(array('cat_id'=>$id))->delete();
        if($ret){
            $this->success('删除成功',U('/Category/index'),1);
        }else{
            $this->error('删除失败！');
        }
    }
}