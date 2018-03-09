<?php
namespace Home\Controller;
use Think\Controller;

class ListController extends CommonController
{
    public function index()
    {
        $id = I('get.id',0);
        //栏目名称
        $info = M('column')->where("id = $id")->find();
        $this->assign('name',$info['name']);
        if($info['type'] == 1){//列表
            $count = M('article')->where("columnid = $id")->count();
            $page = new \Think\Page($count,10);
            $show = $page->showHome();
            $data = M('article')->where("columnid = $id")->order('id desc')->limit("$page->firstRow,$page->listRows")->select();
            $this->assign('data',$data);
            $this->assign('page',$show);
            $this->display();
        }else{
            $info['content'] = htmlspecialchars_decode($info['content']);
            $this->assign('data',$info);
            $this->display('listPage');
        }
    }

    public function view()
    {
        $id = I('get.id',0);
        $data = M('article')->where("id = $id")->find();
        $data['content'] = htmlspecialchars_decode($data['content']);
        $this->assign('data',$data);
        $this->display();
    }
}