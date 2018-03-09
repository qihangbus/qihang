<?php
namespace Home\Controller;
use Think\Controller;

class TestController extends Controller
{
    public function index()
    {

    }

    //替换目录，更改目录排序
    public function test()
    {
        set_time_limit(0);
//        $leadin = new \Admin\Controller\LeadInController();
//        $data = $leadin->index('./database/小班.xlsx');
//        foreach($data as $k=>$v){
//            M('books','fh_')->where(['book_isbn'=>$v['A']])->save(['class_1'=>0,'class_2'=>0,'class_3'=>0,'class_4'=>0]);
//            M('books','fh_')->where(['book_isbn'=>$v['A']])->save(['class_2'=>1]);
//        }
        echo '成功';
    }

}