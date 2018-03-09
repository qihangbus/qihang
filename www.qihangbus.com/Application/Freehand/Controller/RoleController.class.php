<?php
namespace Freehand\Controller;
use Think\Controller;
class RoleController extends CommonController {
    public function index()
    {

        $ret = D('admin_role')->order('sort_order asc,role_id asc')->select();
        $this->assign('role',$ret);
        $this->display("role/index");
    }

    //修改角色状态
    public function ajax()
    {
        $id = I('get.id','');
        $flag = I('get.f','');
        if($flag == 1){
            $ret = D('admin_role')->where(array('role_id'=>$id))->save(array('disabled'=>2));
        }elseif($flag == 2){
            $ret = D('admin_role')->where(array('role_id'=>$id))->save(array('disabled'=>1));
        }
        if($ret) {
            $this->success('操作成功', U('/Role/index'), 1);
        }
    }

    //排序
    public function listorder()
    {
        $listorders = I('post.listorders','');
        $role = D('admin_role');
        foreach($listorders as $roleid=>$listorder){
            $role->where(array('role_id'=>$roleid))->save(array('sort_order'=>$listorder));
        }
        $this->success('操作成功',U('/Role/index'),1);
    }

    //添加
    public function add()
    {
        $this->edit_info();
    }

    //编辑
    public function edit()
    {
        $id = I('get.id');
        $this->edit_info($id);
    }

    private function edit_info($id = 0)
    {
        $info = D('admin_role')->where(array('role_id'=>$id))->find();
        if($id < 1){
            $info['disabled'] = 0;
        }
        $this->assign('info',$info);
        $this->display('role/info');
    }

    //处理数据
    public function edit_handle()
    {
        $condition = array();
        $role_id = I('post.role_id','0');
        $condition['role_name'] = I('post.role_name','');
        $condition['role_desc'] = I('post.role_desc','');
        $condition['disabled'] = I('post.disabled','');
        $condition['sort_order'] = I('post.sort_order','0');

        if($role_id > 0){
            $ret = D('admin_role')->where(array('role_id'=>$role_id))->save($condition);
        }else{
            $ret = D('admin_role')->add($condition);
        }

        if($ret){
            $this->success('操作成功',U('/Role/index'),1);
        }else{
            $this->success('操作失败！');
        }
    }

    //删除
    public function del(){
        $id = I('get.id');
        if($id == 1) $this->error('删除失败,此角色不能删除！');
        $ret = D('admin_role')->where(array('role_id'=>$id))->delete();
        if($ret){
            $this->success('删除成功',U('/Role/index'),1);
        }else{
            $this->error('删除失败！');
        }
    }

    //删除会员
    public function member_del(){
        $id = I('get.id');
        if($id == 1) $this->error('删除失败,此用户不能删除！');
        $ret = D('users')->where(array('uid'=>$id))->delete();
        if($ret){
            $this->success('删除成功',U('/Role/index'),1);
        }else{
            $this->error('删除失败！');
        }
    }

    //会员管理
    public function member_manage()
    {
        $id = I('get.id');
        $ret = D('users')->where(array('role_id'=>$id))->select();
        foreach($ret as $k=>$v){
            $ret[$k]['role_name'] = D('admin_role')->where(array('role_id'=>$v['role_id']))->getField('role_name');
        }
        $this->assign('ret',$ret);
        $this->display('role/member_manage');
    }

    //修改会员信息
    public function member_edit()
    {
        $id = I('get.id');
        $ret = D('users')->where(array('uid'=>$id))->find();
        $this->assign('info',$ret);
        $roles = D('admin_role')->where(array('disabled'=>1))->select();
        $this->assign('roles',$roles);
        $this->display('role/member_info');
    }

    //处理会员数据
    public function member_handle()
    {
        $condition = array();
        $uid = I('post.uid','');
        $password = I('post.password','');
        $confirm_password = I('post.cpassword','');
        $condition['nickname'] = I('post.nickname','');
        $condition['user_mobile'] = I('post.user_mobile','');

        if(!empty($password) && !empty($confirm_password)){
            if($password != $confirm_password){
                $this->error('两次输入的密码不一致');
            }
            $condition['password'] = MD5($password);
        }

        if($uid > 0){
            $ret = D('users')->where(array('uid'=>$uid))->save($condition);
            $this->success('修改成功',U('/Role/index'),1);
        }else{
            $this->error('参数错误,请重试');
        }
    }

    /**
     * 获取菜单表信息
     * @param int $menuid 菜单ID
     * @param int $menu_info 菜单数据
     */
    public function get_menuinfo($menuid,$menu_info) {
        $menuid = intval($menuid);
        unset($menu_info[$menuid][id]);
        return $menu_info[$menuid];
    }

    /**
     *  检查指定菜单是否有权限
     * @param array $data menu表中数组
     * @param int $roleid 需要检查的角色ID
     */
    public function is_checked($data,$roleid,$priv_data) {
        $priv_arr = array('m','c','a','data');
        if($data['m'] == '') return false;
        foreach($data as $key=>$value){
            if(!in_array($key,$priv_arr)) unset($data[$key]);
        }
        $data['role_id'] = $roleid;
        $info = in_array($data, $priv_data);
        if($info){
            return true;
        } else {
            return false;
        }
    }

    /**
     * 获取菜单深度
     * @param $id
     * @param $array
     * @param $i
     */
    public function get_level($id,$array=array(),$i=0) {
        foreach($array as $n=>$value){
            if($value['id'] == $id)
            {
                if($value['parentid']== '0') return $i;
                $i++;
                return $this->get_level($value['parentid'],$array,$i);
            }
        }
    }

    //角色权限设置
    public function role_priv()
    {
        $roleid = I('get.id');
        $menu = new \Org\Util\tree;
        $menu->icon = array('│ ','├─ ','└─ ');
        $menu->nbsp = '&nbsp;&nbsp;&nbsp;';
        $result = D('menu')->select();
        $priv_data = D('admin_role_priv')->select(); //获取权限表数据
        foreach ($result as $n=>$t) {
            $result[$n]['checked'] = ($this->is_checked($t,$roleid, $priv_data)) ?  ' checked' : '';
            $result[$n]['level'] = $this->get_level($t['id'],$result);
            $result[$n]['parentid_node'] = ($t['parentid'])? ' class="child-of-node-'.$t['parentid'].'"' : '';
        }
        $str  = "<tr id='node-\$id' \$parentid_node>
                        <td style='padding-left:30px;'>\$spacer<input type='checkbox' name='menuid[]' value='\$id' level='\$level' \$checked onclick='javascript:checknode(this);'> \$language</td>
                    </tr>";

        $menu->init($result);

        $categorys = $menu->get_tree(0, $str);

        $this->assign('roleid',$roleid);
        $this->assign('categorys',$categorys);
        $this->display('role/role_priv');
    }

    //处理权限数据
    public function act_role_priv()
    {
        if (is_array($_POST['menuid']) && count($_POST['menuid']) > 0) {

            D('admin_role_priv')->where(array('role_id'=>$_POST['roleid']))->delete();
            $menuinfo = D('menu')->field('id,m,c,a,data')->select();
            $menu_info = array();
            foreach ($menuinfo as $_v) $menu_info[$_v[id]] = $_v;
            foreach($_POST['menuid'] as $menuid){
                $info = array();
                $info = $this->get_menuinfo(intval($menuid),$menu_info);
                $info['role_id'] = $_POST['roleid'];
                D('admin_role_priv')->add($info);
            }
        } else {
            D('admin_role_priv')->where(array('role_id'=>$_POST['role_id']))->delete();
        }
        $this->success('操作成功',U('/Role/index'),1);
    }

    //栏目权限
    public function setting_cat_priv()
    {
        $roleid = I('get.id','');
        $this->assign('roleid',$roleid);
        $category = D('menu')->select();
        $priv = D('category_priv')->select();
        $privs = array();
        foreach ($priv as $k=>$v) {
            $privs[$v['catid']][$v['action']] = true;
        }

        //加载tree
        $tree = new \Org\Util\tree;
        $categorys = array();
        $arr = array('2','3');
        foreach ($category as $k=>$v) {
            if((session('roleid') != 1) && (in_array($v['id'],$arr) || in_array($v['parentid'],$arr))) continue;
            $v['disabled'] = '';
            $v['index_check'] = isset($privs[$v['id']]['index']) ? 'checked' : '';
            $v['add_check'] = isset($privs[$v['id']]['add']) ? 'checked' : '';
            $v['delete_check'] = isset($privs[$v['id']]['del']) ? 'checked' : '';
            $v['edit_check'] = isset($privs[$v['id']]['edit']) ? 'checked' : '';
            $category[$k] = $v;
        }
        $show_header = true;
        $str = "<tr>
				  <td align='left'><input type='checkbox'  value='1' onclick='select_all(\$id, this)' ></td>
				  <td>\$spacer\$language</td>
				  <td align='left'><input type='checkbox' name='priv[\$id][]' \$index_check  value='index' ></td>
				  <td align='left'><input type='checkbox' name='priv[\$id][]' \$disabled \$add_check value='add' ></td>
				  <td align='left'><input type='checkbox' name='priv[\$id][]' \$disabled \$edit_check value='edit' ></td>
				  <td align='left'><input type='checkbox' name='priv[\$id][]' \$disabled \$delete_check  value='del' ></td>
			  </tr>";

        $tree->init($category);
        $categorys = $tree->get_tree(0, $str);
        $this->assign('categorys',$categorys);
        $this->display('role/role_cat_priv_list');
    }

    //处理栏目权限数据
    public function act_setting_cat_priv()
    {
        $roleid = I('post.roleid','');
        $priv = I('post.priv','');
        //删除该角色当前的权限
        D('category_priv')->where(array('roleid'=>$roleid))->delete();

        foreach ($priv as $k=>$v) {
            if (is_array($v) && !empty($v[0])) {
                foreach ($v as $key=>$val) {
                    D('category_priv')->add(array('catid'=>$k, 'roleid'=>$roleid, 'action'=>$val));
                }
            }
        }
        $this->success('操作成功',U('/Role/index'),1);
    }
}