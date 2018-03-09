<?php
namespace mobiles\Controller;
use Think\Controller;
class PagesController extends Controller {
    //关于我们
	public function index(){
		$this->assign('title',"关于启航巴士");
		$this->display('Weixin/about');
	}
	
	public function index1(){
		$this->assign('title',"关于启航巴士");
		$this->display('Weixin/about1');
	}
	
	//家长端操作指南
	public function parents(){
		$this->assign('title',"家长端操作指南");
		$this->display('Weixin/parents');
	}
	
	//教师端操作指南
	public function teachers(){
		$this->assign('title',"教师端操作指南");
		$this->display('Weixin/teachers');
	}
	
	//园长端操作指南
	public function schools(){
		$this->assign('title',"园长端操作指南");
		$this->display('Weixin/schools');
	}
	
	//图书管理员端操作指南
	public function manages(){
		$this->assign('title',"图书管理员端操作指南");
		$this->display('Weixin/manages');
	}
}