<?php
namespace Home\Controller;
use Think\Controller;

class VoiceController extends Controller
{
    public function index()
    {
        $wechat = new \Admin\Controller\WechatController();
        $signPackage = $wechat->getShareSignPackage();
        $this->assign('signPackage',$signPackage);
        $this->display();
    }
}