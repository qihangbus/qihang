<?php
namespace Home\Controller;
use Think\Controller;

class IndexController extends CommonController
{
    public function index()
    {
        //banner
        $banner = M('banner')->order('sort')->select();
        $this->assign('banner',$banner);
        //公司动态
        $company_news = M('article')->where("columnid = 48 and image <> ''")->limit(6)->order('id desc')->select();
        $this->assign('company_news',$company_news);
        //行业资讯
        $industry_news = M('article')->where('columnid = 49')->order('id desc')->limit(6)->select();
        $this->assign('industry_news',$industry_news);
        //关于我们-简介
        $about = M('column')->where('id = 54')->getField('content');
        $about = htmlspecialchars_decode($about);
        $this->assign('about',$about);
        $this->display();
    }
}