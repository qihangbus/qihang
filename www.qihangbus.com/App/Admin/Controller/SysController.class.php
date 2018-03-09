<?php
namespace Admin\Controller;
use Think\Controller;

class SysController extends CommonController
{
	public function adminList()
	{
		$username = I('post.username');
		if($username){
			$this->username = $username;
			$map['a.username'] = ['like',"%$username%"];
		}
		$admin = M('admin a');
		$count = $admin->where($map)->count();
		$Page = new \Think\Page($count,15);
		foreach($map as $k=>$v){
			$Page->parameter[$k] = urlencode($v);
		}
		$this->page = $Page->show();
		$data = $admin
			->join('left join xs_auth_group_access ga on ga.uid = a.id')
			->join('left join xs_auth_group g on g.id = ga.group_id')
			->where($map)
			->order('a.id desc')
			->limit("$Page->firstRow,$Page->listRows")
			->field('a.*,g.title')
			->select();
		$this->assign('data',$data);
		$this->display();
	}
    
    public function adminAdd(){
		if(!IS_POST){
			$auth_group = M('auth_group')->select();
			$this->assign('auth_group',$auth_group);
			$this->display();
		}else{
			$admin = M('admin');
			$admin_access = M('auth_group_access');
			$check_user = $admin->where(array('username'=>I('username')))->find();
			if ($check_user){
				$this->error('用户名已存在',0,0);
			}
			$data = array(
				'username'=>I('username'),
				'pwd'=>I('pwd','','md5'),
				'email'=>I('email'),
				'tel'=>I('tel'),
				'status'=>I('status',1),
				'realname'=>I('realname'),
				'ip'=>get_client_ip(),
			);
			$result = $admin->add($data);
			if($result){
				$data=array(
					'uid'=>$result,
					'group_id'=>I('group_id'),
				);
				$result = $admin_access->add($data);
			}
			if($result){
				$this->success('添加成功',U('adminList'),1);
			}else{
				$this->error('添加失败');
			}			
		}
	}

	public function adminEdit(){
		if(!IS_POST){
			$auth_group = M('auth_group')->select();
			$admin = M('admin')->where(array('id'=>I('id')))->find();
			$auth_group_access = M('auth_group_access')->where(array('uid'=>$admin['id']))->getField('group_id');
			$this->assign('admin',$admin);
			$this->assign('auth_group',$auth_group);
			$this->assign('auth_group_access',$auth_group_access);
			$this->display();
		}else{
			$pwd = I('pwd');
			$group_id = I('group_id');
			$id = I('id');
			if ($pwd){
				$data['pwd'] = md5($pwd);
			}
			$data['email']=I('email');
			$data['tel']=I('tel');
			$data['realname']=I('realname');
			$data['status'] = I('status',1);
			$admin = M('admin');
			$result1 = $admin->where("id = $id")->save($data);
			//修改用户组
			$result2 = M('auth_group_access')->where(array('uid'=>I('id')))->setField('group_id',$group_id);
			if($result1 || $result2){
				$this->success('修改成功',U('adminList'),1);
			}else{
				$this->error('修改失败');
			}
		}
	}
	
    public function adminDel(){
    	$id=I('id');
    	M('admin')->where(array('id'=>I('id')))->delete();
    	M('auth_group_access')->where(array('uid'=>I('id')))->delete();
    	$this->redirect('adminList');
    }
    
    public function adminState(){
    	$id=I('x');
    	$status=M('admin')->where(array('id'=>$id))->getField('status');//判断当前状态情况
    	if($status==1){
    		$statedata = array('status'=>2);
    		$auth_group=M('admin')->where(array('id'=>$id))->setField($statedata);
    		$this->success('状态禁止',1,1);
    	}else{
    		$statedata = array('status'=>1);
    		$auth_group=M('admin')->where(array('id'=>$id))->setField($statedata);
    		$this->success('状态开启',1,1);
    	}
    
    }
    
    //用户组管理
    public function adminGroup(){
    	$auth_group = M('auth_group')->select();
    	$this->assign('auth_group',$auth_group);
    	$this->display();
    }

	public function groupAdd(){
		if(!IS_POST){
			$this->display();
		}else{
			$data=array(
				'title'=>I('title'),
				'status'=>I('status',0),
				'addtime'=>time(),
			);
			$title = $data['title'];
			$result = M('auth_group')->where("title = '$title'")->find();
			if($result){
				$this->error('用户组名重复');
			}
			$result = M('auth_group')->add($data);
			if($result){
				$this->success('添加成功',U('adminGroup'),1);
			}else{
				$this->error('添加失败');
			}
		}
	}
    
    public function groupDel(){
    	M('auth_group')->where(array('id'=>I('id')))->delete();
    	$this->redirect('adminGroup');
    }
    
    public function admin_group_edit(){
    	$group=M('auth_group')->where(array('id'=>I('id')))->find();
    	$this->assign('group',$group);
    	$this->display();
    }

    public function admin_group_runedit(){
    	if (!IS_AJAX){
    		$this->error('提交方式不正确',0,0);
    	}else{
    		$sldata=array(
    				'id'=>I('id'),
    				'title'=>I('title'),
    				'status'=>I('status'),
    		);
    		M('auth_group')->save($sldata);
    		$this->success('用户组修改成功',U('adminGroup'),1);
    	}
    }
    
    public function admin_group_state(){
    	$id=I('x');
    	$status=M('auth_group')->where(array('id'=>$id))->getField('status');//判断当前状态情况
    	if($status==0){
    		$statedata = array('status'=>1);
    		$auth_group=M('auth_group')->where(array('id'=>$id))->setField($statedata);
			$this->success('状态开启',1,1);
    	}else{
    		$statedata = array('status'=>0);
    		$auth_group=M('auth_group')->where(array('id'=>$id))->setField($statedata);
    		$this->success('状态禁用',1,1);
    	}

    }
    
    public function admin_rule(){
    	$nav = new \Org\Util\Leftnav;
    	$admin_rule=M('auth_rule')->order('sort')->select();
    	$arr = $nav::rule($admin_rule);
	   	$this->assign('admin_rule',$arr);//权限列表
    	$this->display();
    }
    
    public function admin_rule_add(){
    	if(!IS_AJAX){
    		$this->error('提交方式不正确',0,0);
    	}else{
    		$admin_rule=M('auth_rule');
    		$sldata=array(
    				'name'=>I('name'),
    				'title'=>I('title'),
					'type'=>1,
    				'status'=>I('status'),
    				'sort'=>I('sort'),
    				'addtime'=>time(),
    				'pid'=>I('pid'),
    		);
    		$admin_rule->add($sldata);
    		$this->success('权限添加成功',U('admin_rule'),1);
    	}
    }
    
    public function admin_rule_state(){
    	$id=I('x');
    	$statusone=M('auth_rule')->where(array('id'=>$id))->getField('status');//判断当前状态情况
    	if($statusone==1){
    		$statedata = array('status'=>0);
    		$auth_group=M('auth_rule')->where(array('id'=>$id))->setField($statedata);
    		$this->success('状态禁止',1,1);
    	}else{
    		$statedata = array('status'=>1);
    		$auth_group=M('auth_rule')->where(array('id'=>$id))->setField($statedata);
    		$this->success('状态开启',1,1);
    	}
    
    }
    
    public function ruleorder(){
    	if (!IS_AJAX){
    		$this->error('提交方式不正确',0,0);
    	}else{
    		$auth_rule=M('auth_rule');
    		foreach ($_POST as $id => $sort){
    			$auth_rule->where(array('id' => $id ))->setField('sort' , $sort);
    		}
    		$this->success('排序更新成功',U('admin_rule'),1);
    	}
    }
    
    public function admin_rule_edit(){
    	$admin_rule=M('auth_rule')->where(array('id'=>I('id')))->find();
    	$this->assign('rule',$admin_rule);
    	$this->display();
    }

    public function admin_rule_runedit(){
        	if(!IS_AJAX){
    		$this->error('提交方式不正确',0,0);
    	}else{
    		$admin_rule=M('auth_rule');
    		$sldata=array(
    				'id'=>I('id'),
    				'name'=>I('name'),
    				'title'=>I('title'),
    				'status'=>I('status'),
    				'css'=>I('css'),
    				'sort'=>I('sort'),
    		);
    		$admin_rule->save($sldata);
    		$this->success('权限修改成功',U('admin_rule'),1);
    	}
    }
    
    public function admin_rule_del(){
    	M('auth_rule')->where(array('id'=>I('id')))->delete();
		M('auth_rule')->where(array('pid'=>I('id')))->delete();
    	$this->redirect('admin_rule');
    }
    
    //三重权限配置
    public function admin_group_access(){
    	$admin_group=M('auth_group')->where(array('id'=>I('id')))->find();
		$admin_rules = array();
		$admin_rules = explode(',',$admin_group['rules']);
    	$m = M('auth_rule');
    	$data = $m->field('id,name,title')->where('pid=0')->select();
    	foreach ($data as $k=>$v){
    		$data[$k]['sub'] = $m->field('id,name,title')->where('pid='.$v['id'])->select();
    		foreach ($data[$k]['sub'] as $kk=>$vv){
    			$data[$k]['sub'][$kk]['sub'] = $m->field('id,name,title')->where('pid='.$vv['id'])->select();
    		}
    	}
    	$this->assign('admin_group',$admin_group);	// 顶级
		$this->assign('admin_rules',$admin_rules);
    	$this->assign('datab',$data);	// 顶级
    	$this->display();
    }
    
    public function admin_group_runaccess(){
    	$m = M('auth_group');
    	$new_rules = I('new_rules');
    	$imp_rules = implode(',', $new_rules).',';
    	$sldata=array(
    		'id'=>I('id'),
    		'rules'=>$imp_rules,	
    	);
    	if($m->save($sldata)){
    		$this->success('权限配置成功',U('adminGroup'),1);
    	}else{
    		$this->error('权限配置失败');
    	}
    }
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
}