<?php
namespace Freehand\Controller;
use Think\Controller;
class RegionController extends CommonController {
    public function index(){

    	$region_id = I('param.region_id',0);
    	if($region_id == 0){
    		$region_level = 1;
    	}else{
    		$region_level = $this->get_name($region_id,'region_level')+1;
    	}
    	$this->assign('region_level',$region_level);
    	$list = $this->area_list($region_id);
    	$this->assign('list',$list);

    	if($region_id > 0){
    		$area_name = $this->get_name($region_id,'region_name');
    		$area = "[".$area_name."]";
    		if($region_level == 1){
    			$area .= '一级地区';
    		}elseif($region_level == 2){
    			$area .= '二级地区';
    		}elseif($region_level == 3){
    			$area .= '三级地区';
    		}elseif($region_level == 4){
    			$area .= '四级地区';
    		}

    		$parent_id = $this->get_name($region_id,'parent_id');
    		$parent_url = U('/Region/index',array('region_id'=>$parent_id));
    	}else{
    		$area = "[全国]";
    		$parent_url = '';
    	}
    	$this->assign('parent_id',$region_id);
    	$this->assign('area_here',$area);
    	$this->assign('parent_url',$parent_url);
        $this->display("region/index");
    }

    public function add()
    {
    	$data['parent_id'] = I('param.parent_id',0);
    	$data['region_name'] = I('param.region_name','');
    	$data['region_level'] = I('param.region_level',0);
    	$ret = M('region')->add($data);
    	if($ret){
    		$this->error('添加成功,页面跳转中...',U('/Region/index',array('region_id'=>$data['parent_id'])),2);
    	}
    }

    public function del()
    {
        $id = I('get.id');
        $cid = M('region')->where(array('parent_id'=>$id))->getField('region_id');
        if($cid){
            $this->error('此区域存在下级不能删除！');
        }
        $ret = D('region')->where(array('region_id'=>$id))->delete();
        if($ret){
            $this->success('删除成功',U('/Region/index'),1);
        }else{
            $this->error('删除失败！');
        }
    }

    //
    function area_list($region_id)
	{
	    $area_arr = array();
	    if($region_id < 1){
	    	$condition['region_level'] = 1;
	    }else{
	    	$condition['parent_id'] = $region_id;
	    }
	    $list = M('region')->where($condition)->order('region_id')->select();
	    return $list;
	}

	function get_name($id, $name = '')
    {
        return M('region')->where(array('region_id'=>$id))->getField($name);
    }
}