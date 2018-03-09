<?php
namespace Admin\Controller;
use Think\Controller;
use Think\Auth;

class CommonController extends Controller
{
	Protected function _initialize()
	{
		if (!session('aid')){
			$this->redirect(MODULE_NAME . '/Login/index');
		}else{
			//判断导航选中状态,S为导航ID
			cookie('s',I('get.s'),86400);

			//不需要验证的权限
			$not_check = [
				'Index/index',
//				'Index/cacheClean',
				'Index/editPwd',
				'Book/edit',
				'Book/uploadImage',
				'Book/del',
				'Book/outExcel',
				'School/edit',
				'School/del',
				'School/select',
				'School/reissue',
				'School/outExcel',
				'School/bookList',
				'School/sendMes',
				'School/sendFewMes',
				'School/createDir',
				'School/dirReplace',
				'School/selectSchool',
				'School/gradeAdd',
				'School/gradeDel',
				'School/gradeDelFew',
				'School/gradeEdit',
				'School/classAdd',
				'School/classDel',
				'School/classDelFew',
				'School/classEdit',
				'School/selectGrade',
				'School/classLeadin',
				'School/student',
				'School/sOutExcel',
				'School/sendTplSms',
				'Config/liveSave',
				'User/sSendMes',
				'User/sEdit',
				'User/sDel',
				'User/sDelFew',
				'User/sLeadin',
				'User/tSendMes',
				'User/tEdit',
				'User/tDel',
				'User/tDelFew',
				'User/tLeadin',
				'User/lSendMes',
				'User/lEdit',
				'User/lDel',
				'User/lDelFew',
				'User/selectClass',
			];//用户组设置：状态、配置、删除、修改

			//当前操作的请求,模块名/方法名
			if(in_array(CONTROLLER_NAME.'/'.ACTION_NAME, $not_check)){
				return true;
			}

			//下面代码动态判断权限
			$auth = new Auth();
			if(!$auth->check(CONTROLLER_NAME.'/'.ACTION_NAME,session('aid')) && session('aid')!= 1){
				$this->error('没有权限',0,0);
			}
		}
	}

}