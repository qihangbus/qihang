<?php
namespace Freehand\Controller;
use Think\Controller;
class StudentController extends CommonController {
    public function index(){
		
		$page_size = I('param.page_size',15);
        $this->assign('page_size',$page_size);
        $keywords = I('param.keywords','');
        $this->assign('keywords',$keywords);
		
		if($keywords){
            $condition = "student_name like '%$keywords%'";
        }
		
        $student = M('students');
        $count = $student->where($condition)->count();
        $page = new \Think\Page($count,$page_size);
        $show = $page->diyshow();
        $list = $student->where($condition)->order('student_id desc')->limit($page->firstRow.','.$page->listRows)->select();
        foreach($list as $k=>$v){
            $list[$k]['school_name'] = M('schools')->where(array('school_id'=>$v['school_id']))->getField('school_name');
            $list[$k]['grade_name'] = M('grade')->where(array('grade_id'=>$v['grade_id']))->getField('grade_name');
            $list[$k]['class_name'] = M('class')->where(array('class_id'=>$v['class_id']))->getField('class_name');
        }
        $this->assign('list',$list);
        $this->assign('page',$show);
        $this->display("member/student_index");
    }

    //家长信息
    public function parent()
    {
        $id = I('get.id');
        $this->assign('id',$id);
        $sex = array(1=>'男',2=>'女');
        $list = M('students_parent')->where(array('student_id'=>$id,'flag'=>1))->select();
        foreach ($list as $key => $value) {
            $list[$key]['sex_name'] = $value['parent_sex'] > 0 ? $sex[$value['parent_sex']] : '无';
            $list[$key]['wxid'] = empty($value['wx_id']) ? '否' : "是";
        }
        $this->assign('list',$list);
        $this->display("member/parent_list");
    }

    //发送消息
    public function send()
    {
        $id = I('get.id');
        $this->assign('id',$id);
        $this->assign('user_flag',3);
        $this->display("member/student_message");
    }


    //
    public function message_handle()
    {
        $data = array();
        $data['receiver_id'] = I('post.student_id','');
        $data['title'] = I('post.title','');
        $data['message'] = I('post.message','');
        $data['sent_time'] = time();
        $data['user_flag'] = I('post.user_flag','');
        $ret = M('message')->add($data);
        if($ret > 0){
            $this->success('发送成功',U('/Student/index'),1);
        }else{
            $this->error('发送失败',U('/Student/index'),1);
        }
    }

    //添加
    public function add()
    {
        $this->assign('info','');
        $school = M('schools')->order('school_id desc')->select();
        $this->assign('school',$school);
        $grade = M('grade')->where(array('school_id'=>$school[0]['school_id']))->select();
        $this->assign('grade',$grade);
        $class = M('class')->where(array('grade_id'=>$grade[0]['grade_id']))->select();
        $this->assign('class',$class);
        $teacher = M('teacher')->where(array('class_id'=>$class[0]['class_id']))->select();
        $this->assign('teacher',$teacher);
        $this->display("member/student_info");
    }

    //异步请求年级数据
    public function ajax_grade()
    {
        $sid = I('post.school_id','');
        $ret = M('grade')->where(array('school_id'=>$sid))->order('grade_id desc')->select();
        echo json_encode($ret);
    }

    //异步请求班级数据
    public function ajax_class()
    {
        $gid = I('post.grade_id','');
        $ret = M('class')->where(array('grade_id'=>$gid))->order('class_id desc')->select();
        echo json_encode($ret);
    }

    //异步请求班级数据
    public function ajax_teacher()
    {
        $cid = I('post.class_id','');
        $ret = M('teacher')->where(array('class_id'=>$cid))->order('teacher_id desc')->select();
        echo json_encode($ret);
    }

    public function ajaxTeacherNew()
    {
        $cid = I('post.class_id','');
        $data['teacher'] = M('teacher')->where("class_id = $cid")->select();
        $data['group'] = M('class_groups')->where("classid = $cid")->select();
        $this->ajaxReturn($data);
    }

    //编辑
    public function edit()
    {
        $id = I('get.id');
        $ret = M('students')->where(array('student_id'=>$id))->find();
        $this->assign('info',$ret);
        $parent = M('students_parent')->where(array('student_id'=>$id))->find();
        $this->assign('parent',$parent);
        $school = M('schools')->order('school_id desc')->select();
        $this->assign('school',$school);
        $grade = M('grade')->where(array('school_id'=>$ret['school_id']))->select();
        $this->assign('grade',$grade);
        $class = M('class')->where(array('grade_id'=>$ret['grade_id']))->select();
        $this->assign('class',$class);
        $teacher = M('teacher')->where(array('class_id'=>$ret['class_id']))->select();
        $this->assign('teacher',$teacher);
        $group = M('class_groups')->where("classid = {$ret['class_id']}")->select();
        $this->assign('group',$group);
        $this->display("member/student_info");
    }

    //处理会员数据
    public function edit_handle()
    {
        $condition = array();
        $data = array();
        $sid = I('post.student_id','');
        $pid = I('post.parent_id','');

        $condition['school_id'] = I('post.school_id','');
        $condition['school_name'] = M("schools")->where(array('school_id'=>$condition['school_id']))->getField("school_name");
        $condition['grade_id'] = I('post.grade_id','');
        $condition['grade_name'] = M("grade")->where(array('grade_id'=>$condition['grade_id']))->getField("grade_name");
        $condition['class_id'] = I('post.class_id','');
        $condition['class_name'] = M("class")->where(array('class_id'=>$condition['class_id']))->getField("class_name");
        $condition['teacher_id'] = I('post.teacher_id','');
        $condition['teacher_name'] = M("teacher")->where(array('teacher_id'=>$condition['teacher_id']))->getField("teacher_name");
        $condition['student_name'] = I('post.student_name','');
        $condition['school_sn'] = I('post.school_sn','');
        $condition['student_sn'] = I('post.student_sn','');
        $condition['groupid'] = I('post.groupid',0);
        $data['parent_name'] = I('post.parent_name','');
        $data['parent_sex'] = I('post.parent_sex',0);
        $data['parent_mobile'] = I('post.parent_mobile','');
        $data['flag'] = 1;
        $data['pwd'] = md5('123456');

        if($sid > 0){
            $data['student_id'] = $sid;
            $ret = D('students')->where(array('student_id'=>$sid))->save($condition);
            if(empty($pid)){
                M('students_parent')->add($data);
            }else{
                M('students_parent')->where(array('parent_id'=>$parent_id))->save($data);
            }
            
            $this->success('修改成功',U('/Student/index'),1);
        }else{
            $ret = D('students')->add($condition);
            $data['student_id'] = $ret;
            M('students_parent')->add($data);
            $this->success('添加成功',U('/Student/index'),1);
        }
    }

    public function exportcsv()
    {
        $list = M("students")->where(array('school_id'=>1))->select();
        $str = "学校名称,年级名称,班级名称,教师名称,学生名称,家长姓名,家长手机号\n";
        $str = iconv('utf-8','gb2312',$str);
        
       
        for($i=0;$i<count($list);$i++){
            $school_name = iconv('utf-8','gb2312',$list[$i]['school_name']);
            $grade_name = iconv('utf-8','gb2312',$list[$i]['grade_name']);
			$class_name = iconv('utf-8','gb2312',$list[$i]['class_name']);
            $teacher_name = iconv('utf-8','gb2312',$list[$i]['teacher_name']);
			$student_name = iconv('utf-8','gb2312',$list[$i]['student_name']);
            $parent_name = M("students_parent")->where(array('student_id'=>$list[$i]['student_id']))->getField("parent_name");
            $parent_name = iconv('utf-8','gb2312',$parent_name);
            $parent_mobile = M("students_parent")->where(array('student_id'=>$list[$i]['student_id']))->getField("parent_mobile");

            $str .= $school_name.",".$grade_name.",".$class_name.",".$teacher_name.",".$student_name.",".$parent_name.",".$parent_mobile."\n";
        }

        $filename = date('Ymd').'.csv';
        export_csv($filename,$str);
    }

    //删除会员
    public function del(){
        $id = I('get.id');
        $ret = M('students')->where(array('student_id'=>$id))->delete();
        if($ret){
            $this->success('删除成功',U('/Student/index'),1);
        }else{
            $this->error('删除失败！');
        }
    }

    //导入
    public function import()
    {
        $this->display("member/import_student");
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


            if(!iconv('gb2312','utf-8',$result[$i][0])){
                $school_name = iconv('gbk','utf-8',$result[$i][0]);
            }else{
                $school_name = iconv('gb2312','utf-8',$result[$i][0]);
            }

            if(!iconv('gb2312','utf-8',$result[$i][1])){
                $grade_name = iconv('gbk','utf-8',$result[$i][1]);
            }else{
                $grade_name = iconv('gb2312','utf-8',$result[$i][1]);
            }

            if(!iconv('gb2312','utf-8',$result[$i][2])){
                $class_name = iconv('gbk','utf-8',$result[$i][2]);
            }else{
                $class_name = iconv('gb2312','utf-8',$result[$i][2]);
            }

            if(!iconv('gb2312','utf-8',$result[$i][3])){
                $teacher_name = iconv('gbk','utf-8',$result[$i][3]);
            }else{
                $teacher_name = iconv('gb2312','utf-8',$result[$i][3]);
            }

            if(!iconv('gb2312','utf-8',$result[$i][4])){
                $parent_name = iconv('gbk','utf-8',$result[$i][4]);
            }else{
                $parent_name = iconv('gb2312','utf-8',$result[$i][4]);
            }


            $edit_arr['school_id'] = M('schools')->where(array('school_name'=>$school_name))->getField('school_id');
            $edit_arr['school_name'] = $school_name;
            $edit_arr['grade_id'] = M('grade')->where(array('grade_name'=>$grade_name,'school_id'=>$edit_arr['school_id']))->getField('grade_id');
            $edit_arr['grade_name'] = $grade_name;
            $edit_arr['class_id'] = M('class')->where(array('class_name'=>$class_name,'school_id'=>$edit_arr['school_id'],'grade_id'=>$edit_arr['grade_id']))->getField('class_id');
            header('Content-Type:text/html;charset=utf-8');
            $edit_arr['class_name'] = $class_name;
            $edit_arr['teacher_id'] = M('teacher')->where(array('teacher_name'=>$teacher_name,'school_id'=>$edit_arr['school_id'],'grade_id'=>$edit_arr['grade_id'],'class_id'=>$edit_arr['class_id']))->getField('teacher_id');

            $edit_arr['teacher_name'] = $teacher_name;
            $edit_arr1['parent_name'] = $parent_name;
            $parent_sex = iconv('gb2312','utf-8',$result[$i][5]);
            $edit_arr1['parent_sex'] = 0;
            /*if($parent_sex == '男'){
                $edit_arr1['parent_sex'] = 1;
            }elseif($parent_sex == '女'){
                $edit_arr1['parent_sex'] = 2;
            }*/
            $edit_arr1['parent_mobile'] = $result[$i][6];

            if(!iconv('gb2312','utf-8',$result[$i][7])){
                $edit_arr['student_name'] = iconv('gbk','utf-8',$result[$i][7]);
            }else{
                $edit_arr['student_name'] = iconv('gb2312','utf-8',$result[$i][7]);
            }

            $student_sex = iconv('gb2312','utf-8',$result[$i][8]);
            if($student_sex == '男'){
                $edit_arr['student_sex'] = 1;
            }elseif($student_sex == '女'){
                $edit_arr['student_sex'] = 2;
            }

            $edit_arr['school_sn'] = iconv('gb2312','utf-8',$result[$i][9]);
            $edit_arr['student_sn'] = iconv('gb2312','utf-8',$result[$i][10]);

            if($group_name = iconv('gb2312','utf-8',$result[$i][11])){
                ($edit_arr['groupid'] = M('class_groups')->where("classid = {$edit_arr['class_id']} and name='$group_name'")->getField('id')) || $edit_arr['groupid'] = 0;
            }

            $edit_arr1['student_id'] = M('students')->add($edit_arr);

            if($edit_arr1['parent_name']){
                $edit_arr1['pwd'] = md5('123456');
                $edit_arr1['flag'] = 1;
                M('students_parent')->add($edit_arr1);
            }
        }
        $this->success('导入成功',U('/Student/index'),1);
    }
}