<?php
namespace Freehand\Controller;
use Think\Controller;
class SuppliersController extends CommonController {
    public function index(){
        $suppliers = M('suppliers');
        $count = $suppliers->count();
        $page = new \Think\Page($count,15);
        $show = $page->diyshow();
        $list = $suppliers->order('sup_id desc')->limit($page->firstRow.','.$page->listRows)->select();
        
        foreach ($list as $key => $value) {    
            $list[$key]['cat_name'] = M('category')->where(array('cat_id'=>$value['cat_id']))->getField('cat_name');
        }

        $this->assign('list',$list);
        $this->assign('page',$show);
        $this->display("member/suppliers_index");
    }

    //添加
    public function add()
    {
        
        $this->assign('info', array('is_show'=>1));
        $ret = M('category')->where("parent_id < 1")->select();
        $this->assign('category',$ret);
        $this->display("member/suppliers_info");
    }

    //编辑
    public function edit()
    {
        $id = I('get.id');
        $suppliers = M('suppliers')->where(array('sup_id'=>$id))->find();
        $this->assign('info',$suppliers);
        $ret = M('category')->where("parent_id < 1")->select();
        $this->assign('category',$ret);
        $this->display("member/suppliers_info");
    }

    //处理数据
    public function edit_handle()
    {
        $condition = array();
        $sid = I('post.sup_id','');
        $condition['sup_name'] = I('post.sup_name','');
        $condition['sup_mobile'] = I('post.sup_mobile','');
        $condition['sup_address'] = I('post.sup_address','');
        $condition['cat_id'] = I('post.cat_id','');
        $condition['sort_order'] = I('post.sort_order','');
        $condition['is_show'] = I('post.is_show','');

        if($sid > 0){
            $ret = M('suppliers')->where(array('sup_id'=>$sid))->save($condition);
            $this->success('修改成功',U('/Suppliers/index'),1);
        }else{
            $ret = M('suppliers')->add($condition);
            $this->success('添加成功',U('/Suppliers/index'),1);
        }
    }

    //删除
    public function del(){
        $id = I('get.id');
        $ret = M('suppliers')->where(array('sup_id'=>$id))->delete();
        if($ret){
            $this->success('删除成功',U('/Suppliers/index'),1);
        }else{
            $this->error('删除失败！');
        }
    }
}