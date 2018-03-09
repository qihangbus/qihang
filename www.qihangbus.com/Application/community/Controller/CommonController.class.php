<?php
namespace community\Controller;
use Think\Controller;
class CommonController extends Controller {
    //自动执行
    public function _initialize()
    {
        if(ACTION_NAME != 'bbs_info'){
            if(!session('user')){
                $this->error('请先加入启航巴士幼儿亲子读书计划!');
                exit;
            }
        }
    }
}