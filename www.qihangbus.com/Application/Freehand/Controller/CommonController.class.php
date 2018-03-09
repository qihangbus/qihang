<?php
namespace Freehand\Controller;
use Think\Controller;
class CommonController extends Controller {
    //自动执行
    public function _initialize(){
        $uid = session('uid');
        if(!$uid){
            $this->error('对不起,您还没有登陆,请先登陆!',U('/Login/index'),2);
        }


        //查询网站配置信息
        $baseinfo = M('config')->select();
        $configs = array();
        foreach ($baseinfo as $key => $value) {
            $configs[$value['code']] = $value['value'];
        }

        $this->assign('configs',$configs);

        //调用左侧菜单
        $left_menu = $this->admin_menu(0);
        $icon = C('ADMIN_MENU_ICON');

        for($i = 0; $i < count($left_menu); $i++){
            $left_menu[$i]['icon'] = $icon[$i];
        }
        $this->assign('menu',$left_menu);
    }

    /**
     * 按父ID查找菜单子项
     * @param integer $parentid   父菜单ID
     * @param integer $with_self  是否包括他自己
     */
    final public static function admin_menu($parentid, $with_self = 0) {
        $parentid = intval($parentid);
        $where = array('parentid'=>$parentid,'display'=>1);

        $result = D('menu')->where($where)->limit(1000)->order('listorder ASC')->select();
        if($with_self) {
            $result2[] = D('menu')->where(array('id'=>$parentid))->find();
            $result = array_merge($result2,$result);
        }
        //权限检查
        if($_SESSION['roleid'] == 1){
            for($i = 0; $i < count($result); $i++){
                $ret = D('menu')->where(array('parentid'=>$result[$i]['id']))->order('listorder')->select();
                $result[$i]['child'] = $ret;
                $result[$i]['num'] = count($ret);
            }
            return $result;
        }
        $array = array();
        foreach($result as $v) {
            $action = $v['a'];
            $r = D('admin_role_priv')->where(array('m'=>$v['m'],'c'=>$v['c'],'a'=>$v['a'],'role_id'=>$_SESSION['roleid']))->count();
            if($r) {
                $ret = D('menu')->where(array('parentid'=>$v['id']))->order('listorder')->select();
                $v['child'] = $ret;
                $v['num'] = count($ret);
                $array[] = $v;
            }
        }
        return $array;
    }

   
}