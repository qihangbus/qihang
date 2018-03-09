<?php
namespace Freehand\Controller;
use Think\Controller;
class BaseinfoController extends CommonController {
    public function index(){
    	$setting = $this->get_settings('',array('5'));
    	//var_dump($setting);
    	$this->assign('setting',$setting);
        $this->display("config/index");
    }


    /**
     * 获得设置信息
     *
     * @param   array   $groups     需要获得的设置组
     * @param   array   $excludes   不需要获得的设置组
     *
     * @return  array
     */
    function get_settings($groups=null, $excludes=null)
    {
        $config_groups = '';
        $excludes_groups = '';

        if (!empty($excludes))
        {
            foreach ($excludes AS $key=>$val)
            {
                $excludes_groups .= " AND (parent_id<>'$val')";
            }
        }

        /* 取出全部数据：分组和变量 */
        $item_list = M('config')->where("type<>'hidden' $config_groups $excludes_groups")->order('parent_id, sort_order, id')->select();

        /* 整理数据 */
        $group_list = array();
        foreach ($item_list AS $key => $item)
        {
            $pid = $item['parent_id'];
            $cfg_name = L('_CFG_NAME_');
            $cfg_desc = L('_CFG_DESC_');
            $cfg_range = L('_CFG_RANGE_');
            $item['name'] = isset($cfg_name[$item['code']]) ? $cfg_name[$item['code']] : $item['code'];
            $item['desc'] = isset($cfg_desc[$item['code']]) ? $cfg_desc[$item['code']] : '';

            if ($pid == 0)
            {
                /* 分组 */
                if ($item['type'] == 'group')
                {
                    $group_list[$item['id']] = $item;
                }
            }
            else
            {
            	
                /* 变量 */
                if (isset($group_list[$pid]))
                {
                    if ($item['store_range'])
                    {
                        $item['store_options'] = explode(',', $item['store_range']);

                        foreach ($item['store_options'] AS $k => $v)
                        {
                            $item['display_options'][$k+1] = isset($cfg_range[$item['code']][$v]) ? $cfg_range[$item['code']][$v] : $v;
                        }
                    }
                    $group_list[$pid]['vars'][] = $item;
                }
            }

        }

        return $group_list;
    }

    //处理提交的数据
    public function edit_handle()
    {
        $type = I('post.type','');  

        //允许上传的文件类型
        $allow_file_type = '|GIF|JPG|PNG|BMP|SWF|DOC|XLS|PPT|MID|WAV|ZIP|RAR|PDF|CHM|RM|TXT|CERT|';
    
        //保存变量值
        $value = $_POST['value'];

        $count = count($value);

        $arr = array();

        $res = M('config')->select();
        
        foreach ($res as $key => $val) {
            $arr[$val['id']] = $val['value'];
        }

        foreach ($value as $k=>$val) {
            
            if($arr[$k] != $val){
                M('config')->where(array('id'=>$k))->save(array('value'=>trim($val)));
            }
        }

        //处理上传的文件
        $this->success('修改数据成功',U('Baseinfo/index'));
    }
}