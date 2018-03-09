<?php
namespace Home\Controller;
use Think\Controller;
use Think\Page;

class BookController extends CommonController
{
    public function index()
    {
        $type = I('get.type',1);
        switch($type){
            case 1:
                $where = 'sort <> 0';
                break;
            case 2:
                $where = 'class_2 = 1 and sort <> 0';
                break;
            case 3:
                $where = 'class_3 = 1 and sort <> 0';
                break;
            default:
                $where = 'class_4 = 1 and sort <> 0';
        }
        $count = M('books','fh_')->where($where)->count();
        $page = new Page($count,12);
        $show = $page->showHome();
        $data = M('books','fh_')->where($where)->limit("$page->firstRow,$page->listRows")->order('sort')->select();
        $this->assign('data',$data);
        $this->assign('page',$show);
        $this->display();
    }

    public function view()
    {
        $id = I('get.id',0);
        $data = M('books','fh_')->where(array('book_id'=>$id))->find();
        $book_gallery = M('book_gallery','fh_')->where(array('book_id'=>$id))->select();
        $this->assign('book_gallery',$book_gallery);
        $this->assign('data',$data);
        $this->display();
    }
}