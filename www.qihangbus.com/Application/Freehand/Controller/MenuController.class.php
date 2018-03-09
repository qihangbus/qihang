<?php
namespace Freehand\Controller;
use Think\Controller;
class MenuController extends CommonController {
    public function index()
    {
        $tree = new \Org\Util\tree;
        $tree->icon = array('&nbsp;&nbsp;&nbsp;│ ','&nbsp;&nbsp;&nbsp;├─ ','&nbsp;&nbsp;&nbsp;└─ ');
        $tree->nbsp = '&nbsp;&nbsp;&nbsp;';

        $result = D('menu')->order('listorder ASC,id DESC')->select();
        $array = array();
        foreach($result as $r) {
            $r['str_manage'] = '<a href='.U("/Menu/add/parentid/".$r[id]).'>添加子菜单</a> | <a href='.U("/Menu/edit/id/".$r[id]).'>修改</a> | <a href="javascript:confirmurl(\''.U("/Menu/del/id/".$r['id']).'\',\''.确认删除.'\')">删除</a> ';
            $array[] = $r;
        }

        $str = "<tr>
                    <td class='col-lg-1'><input class='form-control' name='listorders[\$id]' value='\$listorder' type='text'></td>
                    <td class='col-lg-1'>\$id</td>
                    <td class='col-lg-7'>\$spacer\$language</td>
                    <td class='col-lg-3'>\$str_manage</td>
                </tr>";
        $tree->init($array);
        $categorys = $tree->get_tree(0, $str);
        $this->assign('categorys',$categorys);

        $this->display("menu/index");
    }

    //添加菜单
    public function add()
    {
        $tree = new \Org\Util\tree;
        $result = D('menu')->select();
        $array = array();
        foreach($result as $r) {
            $r['selected'] = $r['id'] == $_GET['parentid'] ? 'selected' : '';
            $array[] = $r;
        }
        $str  = "<option value='\$id' \$selected>\$spacer \$language</option>";
        $tree->init($array);
        $select_categorys = $tree->get_tree(0, $str);
        $this->assign('select_categorys',$select_categorys);
        $info['display'] = 1;
        $this->assign('info',$info);
        $this->display("menu/info");
    }

    //编辑菜单
    public function edit()
    {
        $show_validator = $array = $r = '';
        $tree = new \Org\Util\tree;
        $id = intval($_GET['id']);
        $info = D('menu')->where(array('id'=>$id))->find();
        $this->assign('info',$info);
        $result = D('menu')->select();
        foreach($result as $r) {
            $r['selected'] = $r['id'] == $info['parentid'] ? 'selected' : '';
            $array[] = $r;
        }
        $str  = "<option value='\$id' \$selected>\$spacer \$language</option>";
        $tree->init($array);
        $select_categorys = $tree->get_tree(0, $str);
        $this->assign('select_categorys',$select_categorys);
        $this->display("menu/info");
    }

    //处理数据
    public function edit_handle()
    {
        $condition = array();
        $id = I('post.id','0');
        $condition['parentid'] = I('post.parentid','');
        $condition['language'] = I('post.language','');
        $condition['name'] = I('post.name','');
        $condition['m'] = I('post.mname','');
        $condition['c'] = I('post.cname','');
        $condition['a'] = I('post.aname','');
        $condition['data'] = I('post.data','');
        $condition['display'] = I('post.display','0');

        if($id > 0){
            $ret = D('menu')->where(array('id'=>$id))->save($condition);
        }else{
            $ret = D('menu')->add($condition);
        }

        if($ret){
            $this->success('操作成功',U('/Menu/index'),1);
        }else{
            $this->success('操作失败！');
        }
    }

    //排序
    public function listorder()
    {
        $listorders = I('post.listorders','');
        $menu = D('menu');
        foreach($listorders as $id=>$listorder){
            $menu->where(array('id'=>$id))->save(array('listorder'=>$listorder));
        }
        $this->success('操作成功',U('/Menu/index'),1);
    }

    //删除
    public function del(){
        $id = I('get.id');
        $ret = D('menu')->where(array('id'=>$id))->delete();
        if($ret){
            $this->success('删除成功',U('/Menu/index'),1);
        }else{
            $this->error('删除失败！');
        }
    }

}