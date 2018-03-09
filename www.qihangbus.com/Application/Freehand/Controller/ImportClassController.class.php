<?php
namespace Freehand\Controller;
use Think\Controller;
class ImportClassController extends CommonController {
    public function index(){
        $this->display("department/import_class");
    }

    //处理导入数据
    public function edit_import()
    {
        $filename = $_FILES['file']['tmp_name'];
        if(empty($filename))
        {
            $this->error('请选择要导入的csv文件!');
            exit;
        }

        $handle = fopen($filename,'r');
        $result = import_csv($handle);
        $len_result = count($result);
        if($len_result == 0)
        {
            $this->error('没有任何数据!');
            exit;
        }

        for($i=1;$i<$len_result;$i++)
        {
            $edit_arr = array();
            $edit_arr1 = array();
            $school_name = iconv('gb2312','utf-8',$result[$i][0]);
            $edit_arr1['school_id'] = $edit_arr['school_id'] = M('schools')->where(array('school_name'=>$school_name))->getField('school_id');
            
            //判断数据库中是否存在年级
            $grade_name = iconv('gb2312','utf-8',$result[$i][1]);
            $grade_id = M('grade')->where(array('grade_name'=>$grade_name,'school_id'=>$edit_arr['school_id']))->getField('grade_id');
           
            if(empty($grade_id))
            {
	            $edit_arr['grade_name'] = iconv('gb2312','utf-8',$result[$i][1]);
	            $edit_arr['grade_sn'] = $result[$i][2];	
				$edit_arr1['grade_id'] = M('grade')->add($edit_arr);
			}else{
				$edit_arr1['grade_id'] = $grade_id;
			}

			//判断数据库中是否存在班级
			$class_name = iconv('gb2312','utf-8',$result[$i][3]);
			$class_id = M('class')->where(array('class_name'=>$class_name,'grade_id'=>$grade_id,'school_id'=>$edit_arr1['school_id']))->getField('class_id');
            if(empty($class_id)){
	            $edit_arr1['class_name'] = $class_name;
	            $edit_arr1['class_sn'] = $result[$i][4];
	            M('class')->add($edit_arr1);
	        }
        }

        $this->success('导入成功',U('/Import_Class/index'),1);
    }
}