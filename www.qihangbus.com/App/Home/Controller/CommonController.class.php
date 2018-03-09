<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/8/7/007
 * Time: 11:46
 */
namespace Home\Controller;
use Think\Controller;

class CommonController extends Controller
{
    public function _initialize()
    {
        //导航
        $column = M('column')->where('column_leftid = 0')->order('sort')->select();
        foreach($column as $k=>$v){
            $column[$k]['submenu'] = M('column')->where("column_leftid = {$v['id']}")->select();
        }
        $this->assign('column',$column);
    }
}