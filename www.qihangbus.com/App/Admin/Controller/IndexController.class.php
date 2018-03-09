<?php
namespace Admin\Controller;
use Think\Controller;

class IndexController extends CommonController
{
    public function index()
	{
    	$mysql_ver = M()->query("select version() as ver");
    	$info = array(
    			'PCTYPE'=>PHP_OS,
    			'RUNTYPE'=>$_SERVER["SERVER_SOFTWARE"],
    			'ONLOAD'=>ini_get('upload_max_filesize'),
    			'ThinkPHPTYE'=>THINK_VERSION,
				'MYSQL_VER'=>$mysql_ver[0]['ver'],
    	);
		$this->assign('info',$info);
		$this->display();
    }

	public function cacheClean()
	{
		$result = $this->deleteFile(RUNTIME_PATH);
		$result = $this->deleteFile('./Application/Runtime/');
		if($result == 1){
			$this->success('缓存清除成功');
		}else{
			$this->success('缓存清除失败');
		}
	}

	private function deleteFile($dirName)
	{
		if ($handle = opendir( $dirName )) {
			while ( false !== ($item = readdir ($handle))) {
				if ($item != "." && $item != "..") {
					// 判断是否为目录
					if (is_dir($dirName . "/" . $item )) {
						// 递归删除
						$this->deleteFile($dirName . "/" . $item);
					} else {
						unlink($dirName . "/" . $item);
					}
				}
			}
			closedir( $handle );
			$status = 1;
		}else{
			$status = 2;
		}
		return $status;
	}

	public function editPwd()
	{
		if(IS_POST){
			$id = session('aid');
			$pwd = md5(I('post.pwd',0));
			$access = M('admin')->where("id = $id and pwd = '$pwd'")->find();
			if(!$access){
				$this->error('旧密码不正确');
			}
			$new_pwd = md5(I('post.new_pwd',0));
			M('admin')->where("id = $id")->setField('pwd',$new_pwd);
			$this->success('修改成功');
		}else{
			$this->display();
		}
	}
}