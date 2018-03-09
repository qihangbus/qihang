<?php
namespace Admin\Controller;

class UserController extends CommonController
{
    public function student()
    {
        $value = I('get.value');
        $class_id = I('get.class_id',0);
        if($value){
            $this->assign('value',$value);
            $where = "s.student_name like '%$value%'";
        }elseif($class_id){
            $where = "s.class_id = $class_id";
        }
        $student = M('students s','fh_');
        $count = $student->where($where)->count();
        $page = new \Think\Page($count,15);
        $show = $page->show();
        $data = $student
            ->where($where)
            ->order('class_id desc,convert(student_name USING gbk) COLLATE gbk_chinese_ci')
            ->limit("$page->firstRow,$page->listRows")
            ->select();
        $this->assign('data',$data);
        $this->assign('page',$show);
        $this->display();
    }

    //发送消息,学生
    public function sSendMes()
    {
        $student_id = I('post.id',0);
        $data['receiver_id'] = $student_id;
        $data['sender_name'] = '系统消息';
        $data['message'] = I('post.message','');
        $data['sent_time'] = time();
        //家长
        $data['user_flag'] = 3;
        M('message','fh_')->add($data);
        M('students','fh_')->where(['student_id'=>$student_id])->setInc('message_num');
        $this->success('发送成功');
    }

    public function sAdd()
    {
        if(IS_POST){
            //老师查询
            $data['class_id'] = I('post.class_id',0,'intval');
            $teacher = M('teacher','fh_')->where(['class_id'=>$data['class_id'],'first_flag'=>1])->find();
            if(!$teacher){
                $this->error('请先添加班级教师');
            }

            $data['class_name'] = M('class','fh_')->where(['class_id'=>$data['class_id']])->getField('class_name');
            $data['teacher_id'] = $teacher['teacher_id'];
            $data['teacher_name'] = $teacher['teacher_name'];
            $data['school_id'] = I('post.school_id',0,'intval');
            $data['school_name'] = M('schools','fh_')->where(['school_id'=>$data['school_id']])->getField('school_name');
            $data['grade_id'] = I('post.grade_id',0,'intval');
            $data['grade_name'] = M('grade','fh_')->where(['grade_id'=>$data['grade_id']])->getField('grade_name');
            $data['student_name'] = I('post.student_name','');
            $data['parent_name'] = I('post.parent_name');
            $data['parent_mobile'] = I('post.parent_mobile',0);

            //家长查询
            if($data['parent_mobile']){
                if(!$data['parent_name']){
                    $this->error('请添加家长姓名');
                }
                $access = M('students_parent','fh_')->where(array('parent_mobile'=>$data['parent_mobile']))->find()
                || $access = M('teacher','fh_')->where(array('teacher_mobile'=>$data['parent_mobile']))->find()
                || $access = M('schools','fh_')->where(array('leader_mobile'=>$data['parent_mobile']))->find()
                || $access = M('schools','fh_')->where(array('teacher_mobile'=>$data['parent_mobile']))->find()
                || $access = M('admins','fh_')->where(array('admin_mobile'=>$data['parent_mobile']))->find()
                || $access = M('agent','fh_')->where(array('mobile'=>$data['parent_mobile']))->find();
                if($access){
                    $this->error('手机号已注册');
                }

                $data_p = [
                    'parent_name' => $data['parent_name'],
                    'parent_mobile' => $data['parent_mobile'],
                    'flag' => 1,
                    'pwd' => md5('123456'),
                    'school_id' => $data['school_id'],
                    'grade_id' => $data['grade_id'],
                    'class_id' => $data['class_id']
                ];
            }
            $result = M('students','fh_')->add($data);
            if($data['parent_mobile']){
                $data_p['student_id'] = $result;
                M('students_parent','fh_')->add($data_p);
            }
            if($result){
                $this->success('添加成功');
            }else{
                $this->error('添加失败');
            }
        }else{
            $province = M('region','fh_')->where('parent_id = 0')->select();
            $this->assign('province',$province);
            $this->display();
        }
    }

    public function sEdit()
    {
        if(IS_POST){
            $data['grade_id'] = I('post.grade_id',0);
            $data['grade_name'] = M('grade','fh_')->where(['grade_id'=>$data['grade_id']])->getField('grade_name');
            $data['class_id'] = I('post.class_id',0);
            $data['class_name'] = M('class','fh_')->where(['class_id'=>$data['class_id']])->getField('class_name');
            $data['student_name'] = I('post.student_name','');
            $data_p = [
                'parent_name' => I('post.parent_name'),
                'parent_mobile' => I('post.parent_mobile'),
                'grade_id' => $data['grade_id'],
                'class_id' => $data['class_id']
            ];
            $parent_id = I('post.parent_id',0);
            $mobile = M('students_parent','fh_')->where(['parent_id'=>$parent_id])->getField('parent_mobile');
            if($data_p['parent_mobile'] != $mobile){
                $access = M('students_parent','fh_')->where(array('parent_mobile'=>$data_p['parent_mobile']))->find()
                    || $access = M('teacher','fh_')->where(array('teacher_mobile'=>$data_p['parent_mobile']))->find()
                        || $access = M('schools','fh_')->where(array('leader_mobile'=>$data_p['parent_mobile']))->find()
                            || $access = M('schools','fh_')->where(array('teacher_mobile'=>$data_p['parent_mobile']))->find()
                                || $access = M('admins','fh_')->where(array('admin_mobile'=>$data_p['parent_mobile']))->find()
                                    || $access = M('agent','fh_')->where(array('mobile'=>$data_p['parent_mobile']))->find();
                if($access){
                    $this->error('手机号已注册');
                }
            }
            $student_id = I('post.student_id',0);
            $result = M('students','fh_')->where(['student_id'=>$student_id])->save($data);
            if($parent_id){
                $result1 = M('students_parent','fh_')->where(['parent_id'=>$parent_id])->save($data_p);
            }else{
                if($data_p['parent_mobile']){
                    $data_p['student_id'] = $student_id;
                    $data_p['flag'] = 1;
                    $data_p['pwd'] = md5('123456');
                    $data_p['school_id'] = I('post.school_id');
                    $result1 = M('students_parent','fh_')->add($data_p);
                }
            }

            if($result || $result1){
                $this->success('操作成功');
            }else{
                $this->error('操作失败');
            }

        }else{
            $id = I('get.id',0,'intval');
            $data = M('students s','fh_')
                ->where(['s.student_id'=>$id])
                ->find();
            $parent = M('students_parent','fh_')->where(['student_id'=>$id,'flag'=>1])->find();
            $grade = M('grade','fh_')->where(['school_id'=>$data['school_id']])->select();
            $class = M('class','fh_')->where(['grade_id'=>$data['grade_id']])->select();
            $this->assign('data',$data);
            $this->assign('grade',$grade);
            $this->assign('class',$class);
            $this->assign('parent',$parent);
            $this->display();
        }
    }

    public function sDel()
    {
        $id = I('post.id',0);
        $result = M('students','fh_')->where("student_id = $id")->delete();
        M('students_parent','fh_')->where(['student_id'=>$id])->delete();
        if($result){
            $this->success('操作成功！');
        }else{
            $this->error('操作失败!');
        }
    }

    public function sDelFew()
    {
        $ids = I('post.ids');
        $ids = explode(',',$ids);
        $student = M('students','fh_');
        $parent = M('students_parent','fh_');
        foreach($ids as $v){
            $student->delete($v);
            $parent->where(['student_id'=>$v])->delete();
        }
        $this->success('操作成功!');
    }

    public function sLeadin()
    {
        if(IS_POST){
            $upload = new \Think\Upload();// 实例化上传类
            $upload->maxSize = 6145728;// 设置附件上传大小
            $upload->exts = array('xlsx');// 设置附件上传类型
            $upload->rootPath = './database/excel/'; // 设置附件上传根目录
            // 上传单个文件
            $info = $upload->uploadOne($_FILES['dir_replace']);
            if (!$info) {// 上传错误提示错误信息
                $this->error($upload->getError());
            } else {// 上传成功 获取上传文件信息
                $filename = './database/excel/' . $info['savepath'] . $info['savename'];
            }
            $leadin = new LeadInController();
            $arr = $leadin->index($filename);
            unset($arr[1]);
            //防止执行超时
            set_time_limit(0);
            //清空并关闭输出缓存
            ob_end_clean();

            //计算数据的长度
            $total = count($arr);
            //显示的进度条长度
            $width = 100;
            //每条记录的操作所占的进度条单位长度
            $pix = round($width / $total);
            //默认开始的进度条百分比
            //$progress = 0;
            header('Content-Type: text/html');
            header('Cache-Control: no-cache');
            header('X-Accel-Buffering: no');
            $this->display('progressbar');
            ob_flush();
            flush();
            $progress = $pix;
            $school = M('schools','fh_');
            $grade = M('grade','fh_');
            $class = M('class','fh_');
            $student = M('students','fh_');
            $teacher = M('teacher','fh_');
            $students_parent = M('students_parent','fh_');
            foreach ($arr as $v) {
                $school_name = $v['A'];
                if(!$school_name)   continue;
                $grade_name = $v['B'];
                $class_name = $v['C'];
                $student_name = str_replace(' ','',$v['D']);
                $student_sex = $v['E'];
                $parent_name = $v['F'];
                $parent_sex = $v['G'];
                $parent_mobile = $v['H'];
                $school_id = $school->where(['school_name'=>$school_name])->getField('school_id');
                if(!$school_id){
                    echo "<script type='text/javascript'>updateProgress('<font color=\'red\'>请先添加学校 $school_name </font>','$progress%');</script>";
                    exit();
                }
                $grade_id = $grade->where(['grade_name'=>$grade_name,'school_id'=>$school_id])->getField('grade_id');
                if(!$grade_id){
                    echo "<script type='text/javascript'>updateProgress('<font color=\'red\'>请先添加年级 $grade_name </font>','$progress%');</script>";
                    exit();
                }
                $class_id = $class->where(['grade_id'=>$grade_id,'class_name'=>$class_name])->getField('class_id');
                if(!$class_id){
                    echo "<script type='text/javascript'>updateProgress('<font color=\'red\'>请先添加班级 $class_name </font>','$progress%');</script>";
                    exit();
                }
                //检查老师
                $t_info = $teacher->where(['class_id'=>$class_id,'first_flag'=>1])->find();
                if(!$t_info){
                    echo "<script type='text/javascript'>updateProgress('<font color=\'red\'>请先添加主老师 {$t_info['teacher_name']} </font>','$progress%');</script>";
                    exit();
                }

                if(!$student_name){
                    continue;
                }
                $access = $student->where(['class_id'=>$class_id,'student_name'=>$student_name])->find();
                if($access){
                    continue;
                }

                if($student_sex == '男'){
                    $student_sex = 1;
                }else{
                    $student_sex = 2;
                }
                $data = [
                    'student_name' => $student_name,
                    'student_sex'  => $student_sex,
                    'school_id'    => $school_id,
                    'school_name'  => $school_name,
                    'grade_id'     => $grade_id,
                    'grade_name'   => $grade_name,
                    'class_id'     => $class_id,
                    'class_name'   => $class_name,
                    'teacher_id'   => $t_info['teacher_id'],
                    'teacher_name' => $t_info['teacher_name']
                ];
                $student_id = $student->add($data);

                if($parent_mobile){//添加家长
                    //验证手机号
                    $access = M('students_parent','fh_')->where(array('parent_mobile'=>$parent_mobile))->find()
                        || $access = M('teacher','fh_')->where(array('teacher_mobile'=>$parent_mobile))->find()
                            || $access = M('schools','fh_')->where(array('leader_mobile'=>$parent_mobile))->find()
                                || $access = M('schools','fh_')->where(array('teacher_mobile'=>$parent_mobile))->find()
                                    || $access = M('admins','fh_')->where(array('admin_mobile'=>$parent_mobile))->find()
                                        || $access = M('agent','fh_')->where(array('mobile'=>$parent_mobile))->find();
                    if($access){
                        $student->where(['student_id'=>$school_id])->del();
                        echo "<script type='text/javascript'>updateProgress('<font color=\'red\'>手机号 $parent_mobile 已注册 </font>','$progress%');</script>";
                        exit();
                    }

                    if($parent_sex == '男'){
                        $parent_sex = 1;
                    }elseif($parent_sex == '女'){
                        $parent_sex = 2;
                    }else{
                        $parent_sex = 0;
                    }
                    $data = [
                        'student_id'    => $student_id,
                        'parent_name'   => $parent_name,
                        'parent_mobile' => $parent_mobile,
                        'parent_sex'    => $parent_sex,
                        'flag'          => 1,
                        'pwd'           => md5('123456'),
                        'school_id'     => $school_id,
                        'grade_id'      => $grade_id,
                        'class_id'      => $class_id
                    ];
                    $students_parent->add($data);
                }

                echo "<script type='text/javascript'>updateProgress('已导入$progress%','$progress%');</script>";
                ob_flush();
                flush(); //将输出发送给客户端浏览器，使其可以立即执行服务器端输出的 JavaScript 程序。
                $progress += $pix;
            } //end foreach
            echo "<script type='text/javascript'>success();updateProgress('导入成功 !','100%');</script>";
            unlink($filename);
        }else{
            $this->display();
        }
    }

    public function teacher()
    {
        $value = I('get.value');
        if($value){
            $this->assign('value',$value);
            $where = "t.teacher_name like '%$value%'";
        }
        $teacher = M('teacher t','fh_');
        $count = $teacher->where($where)->count();
        $page = new \Think\Page($count,15);
        $show = $page->show();
        $data = $teacher
            ->join('left join fh_schools s on s.school_id = t.school_id')
            ->join('left join fh_grade g on g.grade_id = t.grade_id')
            ->join('left join fh_class c on c.class_id = t.class_id')
            ->where($where)
            ->order('teacher_id desc')
            ->limit("$page->firstRow,$page->listRows")
            ->field('t.*,s.school_name,g.grade_name,c.class_name')
            ->select();
        $this->assign('data',$data);
        $this->assign('page',$show);
        $this->display();
    }

    //发送消息,教师
    public function tSendMes()
    {
        $teacher_id = I('post.id',0);
        $data['receiver_id'] = $teacher_id;
        $data['sender_name'] = '系统消息';
        $data['message'] = I('post.message','');
        $data['sent_time'] = time();
        //老师
        $data['user_flag'] = 2;
        M('message','fh_')->add($data);
        M('teacher','fh_')->where(['teacher_id'=>$teacher_id])->setInc('message_num');
        $this->success('发送成功');
    }

    public function tAdd()
    {
        if(IS_POST){
            $mobile = I('post.teacher_mobile',0);
            $access = M('students_parent','fh_')->where(array('parent_mobile'=>$mobile))->find()
                || $access = M('teacher','fh_')->where(array('teacher_mobile'=>$mobile))->find()
                    || $access = M('schools','fh_')->where(array('leader_mobile'=>$mobile))->find()
                        || $access = M('schools','fh_')->where(array('teacher_mobile'=>$mobile))->find()
                            || $access = M('admins','fh_')->where(array('admin_mobile'=>$mobile))->find()
                                || $access = M('agent','fh_')->where(array('mobile'=>$mobile))->find();
            if($access){
                $this->error('手机号已注册');
            }

            $data['school_id'] = I('post.school_id',0);
            $data['grade_id'] = I('post.grade_id',0);
            $data['class_id'] = I('post.class_id',0);
            //验证是否已添加主老师
            $access = M('teacher','fh_')->where(['class_id'=>$data['class_id'],'first_flag'=>1])->find();
            if(!$access){
                $data['first_flag'] = 1;
            }
            $data['teacher_name'] = I('post.teacher_name','');
            $data['teacher_mobile'] = $mobile;
            $data['pwd'] = md5('123456');
            $result = M('teacher','fh_')->add($data);
            if($result){
                $this->success('操作成功');
            }else{
                $this->error('操作失败');
            }
        }else{
            $province = M('region','fh_')->where('parent_id = 0')->select();
            $this->assign('province',$province);
            $this->display();
        }
    }

    public function tEdit()
    {
        if(IS_POST){
            $data['grade_id'] = I('post.grade_id',0);
            $data['class_id'] = I('post.class_id',0);
            $data['teacher_name'] = I('post.teacher_name','');
            $teacher_id = I('post.teacher_id',0,'intval');
            $data['teacher_mobile'] = I('post.teacher_mobile',0);
            $mobile = M('teacher','fh_')->where(['teacher_id'=>$teacher_id])->getField('teacher_mobile');
            if($data['teacher_mobile'] != $mobile){
                $access = M('students_parent','fh_')->where(array('parent_mobile'=>$data['teacher_mobile']))->find()
                    || $access = M('teacher','fh_')->where(array('teacher_mobile'=>$data['teacher_mobile']))->find()
                        || $access = M('schools','fh_')->where(array('leader_mobile'=>$data['teacher_mobile']))->find()
                            || $access = M('schools','fh_')->where(array('teacher_mobile'=>$data['teacher_mobile']))->find()
                                || $access = M('admins','fh_')->where(array('admin_mobile'=>$data['teacher_mobile']))->find()
                                    || $access = M('agent','fh_')->where(array('mobile'=>$data['teacher_mobile']))->find();
                if($access){
                    $this->error('手机号已注册');
                }
            }
            $result = M('teacher','fh_')->where(['teacher_id'=>$teacher_id])->save($data);
            if($result){
                $this->success('操作成功');
            }else{
                $this->error('操作失败');
            }

        }else{
            $id = I('get.id',0,'intval');
            $data = M('teacher t','fh_')
                ->join('left join fh_schools s on s.school_id = t.school_id')
                ->where(['t.teacher_id'=>$id])
                ->field('t.*,s.school_name')
                ->find();
            $grade = M('grade','fh_')->where(['school_id'=>$data['school_id']])->select();
            $class = M('class','fh_')->where(['grade_id'=>$data['grade_id']])->select();
            $this->assign('data',$data);
            $this->assign('grade',$grade);
            $this->assign('class',$class);
            $this->display();
        }
    }

    public function tDel()
    {
        $id = I('post.id',0);
        $result = M('teacher','fh_')->where("teacher_id = $id")->delete();
        if($result){
            $this->success('操作成功');
        }else{
            $this->error('操作失败');
        }
    }

    public function tDelFew()
    {
        $ids = I('post.ids');
        $ids = explode(',',$ids);
        $teacher = M('teacher','fh_');
        foreach($ids as $v){
            $teacher->delete($v);
        }
        $this->success('操作成功');
    }

    public function tLeadin()
    {
        if(IS_POST){
            $upload = new \Think\Upload();// 实例化上传类
            $upload->maxSize = 6145728;// 设置附件上传大小
            $upload->exts = array('xlsx');// 设置附件上传类型
            $upload->rootPath = './database/excel/'; // 设置附件上传根目录
            // 上传单个文件
            $info = $upload->uploadOne($_FILES['dir_replace']);
            if (!$info) {// 上传错误提示错误信息
                $this->error($upload->getError());
            } else {// 上传成功 获取上传文件信息
                $filename = './database/excel/' . $info['savepath'] . $info['savename'];
            }
            $leadin = new LeadInController();
            $arr = $leadin->index($filename);
            unset($arr[1]);
            //防止执行超时
            set_time_limit(0);
            //清空并关闭输出缓存
            ob_end_clean();

            //计算数据的长度
            $total = count($arr);
            //显示的进度条长度
            $width = 100;
            //每条记录的操作所占的进度条单位长度
            $pix = round($width / $total);
            //默认开始的进度条百分比
            //$progress = 0;
            header('Content-Type: text/html');
            header('Cache-Control: no-cache');
            header('X-Accel-Buffering: no');
            $this->display('progressbar');
            ob_flush();
            flush();
            $progress = $pix;
            $school = M('schools','fh_');
            $grade = M('grade','fh_');
            $class = M('class','fh_');
            $teacher = M('teacher','fh_');
            foreach ($arr as $v) {
                $school_name = $v['A'];
                $grade_name = $v['B'];
                $class_name = $v['C'];
                $teacher_name = $v['D'];
                $teacher_mobile = $v['E'];
                $school_id = $school->where(['school_name'=>$school_name])->getField('school_id');
                if(!$school_id){
                    echo "<script type='text/javascript'>updateProgress('<font color=\'red\'>请先添加学校 $school_name </font>','$progress%');</script>";
                    exit();
                }
                $grade_id = $grade->where(['grade_name'=>$grade_name,'school_id'=>$school_id])->getField('grade_id');
                if(!$grade_id){
                    echo "<script type='text/javascript'>updateProgress('<font color=\'red\'>请先添加年级 $grade_name </font>','$progress%');</script>";
                    exit();
                }
                $class_id = $class->where(['grade_id'=>$grade_id,'class_name'=>$class_name])->getField('class_id');
                if(!$class_id){
                    echo "<script type='text/javascript'>updateProgress('<font color=\'red\'>请先添加班级 $class_name </font>','$progress%');</script>";
                    exit();
                }

                if(!$teacher_mobile){
                    continue;
                }
                $access = $teacher->where(['class_id'=>$class_id,'teacher_mobile'=>$teacher_mobile])->find();
                if($access){
                    continue;
                }
                $access = M('students_parent','fh_')->where(array('parent_mobile'=>$teacher_mobile))->find()
                    || $access = M('teacher','fh_')->where(array('teacher_mobile'=>$teacher_mobile))->find()
                        || $access = M('schools','fh_')->where(array('leader_mobile'=>$teacher_mobile))->find()
                            || $access = M('schools','fh_')->where(array('teacher_mobile'=>$teacher_mobile))->find()
                                || $access = M('admins','fh_')->where(array('admin_mobile'=>$teacher_mobile))->find()
                                    || $access = M('agent','fh_')->where(array('mobile'=>$teacher_mobile))->find();
                if($access){
                    echo "<script type='text/javascript'>updateProgress('<font color=\'red\'>手机号 $teacher_mobile 已注册 </font>','$progress%');</script>";
                    exit();
                }
                $data = [
                    'teacher_name' => $teacher_name,
                    'teacher_mobile'  => $teacher_mobile,
                    'school_id'    => $school_id,
                    'grade_id'     => $grade_id,
                    'class_id'     => $class_id,
                    'pwd'   => md5('123456'),
                ];
                $access = $teacher->where(['class_id'=>$class_id])->find();
                if($access){
                    $data['first_flag'] = 0;
                }else{
                    $data['first_flag'] = 1;
                }

                $teacher->add($data);
                echo "<script type='text/javascript'>updateProgress('已导入$progress%','$progress%');</script>";
                ob_flush();
                flush(); //将输出发送给客户端浏览器，使其可以立即执行服务器端输出的 JavaScript 程序。
                $progress += $pix;
            } //end foreach
            echo "<script type='text/javascript'>success();updateProgress('导入成功 !','100%');</script>";
            unlink($filename);
        }else{
            $this->display();
        }
    }

    public function librarian()
    {
        $value = I('get.value');
        if($value){
            $this->assign('value',$value);
            $where = "a.admin_name like '%$value%'";
        }
        $admins = M('admins a','fh_');
        $count = $admins->where($where)->count();
        $page = new \Think\Page($count,15);
        $show = $page->show();
        $data = $admins
            ->join('left join fh_schools s on s.school_id = a.school_id')
            ->where($where)
            ->order('admin_id desc')
            ->limit("$page->firstRow,$page->listRows")
            ->field('a.*,s.school_name')
            ->select();
        $this->assign('data',$data);
        $this->assign('page',$show);
        $this->display();
    }

    //发送消息
    public function lSendMes()
    {
        $admin_id = I('post.id',0);
        $data['receiver_id'] = $admin_id;
        $data['sender_name'] = '系统消息';
        $data['message'] = I('post.message','');
        $data['sent_time'] = time();
        //图书管理员
        $data['user_flag'] = 4;
        M('message','fh_')->add($data);
        M('admins','fh_')->where(['admin_id'=>$admin_id])->setInc('message_num');
        $this->success('发送成功');
    }

    public function lAdd()
    {
        if(IS_POST){
            $mobile = I('post.admin_mobile',0);
            $access = M('students_parent','fh_')->where(array('parent_mobile'=>$mobile))->find()
                || $access = M('teacher','fh_')->where(array('teacher_mobile'=>$mobile))->find()
                    || $access = M('schools','fh_')->where(array('leader_mobile'=>$mobile))->find()
                        || $access = M('schools','fh_')->where(array('teacher_mobile'=>$mobile))->find()
                            || $access = M('admins','fh_')->where(array('admin_mobile'=>$mobile))->find()
                                || $access = M('agent','fh_')->where(array('mobile'=>$mobile))->find();
            if($access){
                $this->error('手机号已注册');
            }

            $data['school_id'] = I('post.school_id',0);
            $data['admin_name'] = I('post.admin_name','');
            $data['admin_mobile'] = $mobile;
            $data['pwd'] = md5('123456');
            $result = M('admins','fh_')->add($data);
            if($result){
                $this->success('操作成功');
            }else{
                $this->error('操作失败');
            }
        }else{
            $province = M('region','fh_')->where('parent_id = 0')->select();
            $this->assign('province',$province);
            $this->display();
        }
    }

    public function lEdit()
    {
        if(IS_POST){
            $data['admin_name'] = I('post.admin_name','');
            $admin_id = I('post.admin_id',0,'intval');
            $data['admin_mobile'] = I('post.admin_mobile',0);
            $mobile = M('admins','fh_')->where(['admin_id'=>$admin_id])->getField('admin_mobile');
            if($data['admin_mobile'] != $mobile){
                $access = M('students_parent','fh_')->where(array('parent_mobile'=>$data['admin_mobile']))->find()
                    || $access = M('teacher','fh_')->where(array('teacher_mobile'=>$data['admin_mobile']))->find()
                        || $access = M('schools','fh_')->where(array('leader_mobile'=>$data['admin_mobile']))->find()
                            || $access = M('schools','fh_')->where(array('teacher_mobile'=>$data['admin_mobile']))->find()
                                || $access = M('admins','fh_')->where(array('admin_mobile'=>$data['admin_mobile']))->find()
                                    || $access = M('agent','fh_')->where(array('mobile'=>$data['admin_mobile']))->find();
                if($access){
                    $this->error('手机号已注册');
                }
            }
            $result = M('admins','fh_')->where(['admin_id'=>$admin_id])->save($data);
            if($result){
                $this->success('操作成功');
            }else{
                $this->error('操作失败');
            }

        }else{
            $id = I('get.id',0,'intval');
            $data = M('admins a','fh_')
                ->join('left join fh_schools s on s.school_id = a.school_id')
                ->where(['a.admin_id'=>$id])
                ->field('a.*,s.school_name')
                ->find();
            $this->assign('data',$data);
            $this->display();
        }
    }

    public function lDel()
    {
        $id = I('post.id',0);
        $result = M('admins','fh_')->where("admin_id = $id")->delete();
        if($result){
            $this->success('操作成功');
        }else{
            $this->error('操作失败');
        }
    }

    public function lDelFew()
    {
        $ids = I('post.ids');
        $ids = explode(',',$ids);
        $admins = M('admins','fh_');
        foreach($ids as $v){
            $admins->delete($v);
        }
        $this->success('操作成功');
    }

    public function agent()
    {
        $value = I('get.value');
        if($value){
            $this->assign('value',$value);
            $where = "a.name like '%$value%'";
        }
        $agent = M('agent a','fh_');
        $count = $agent->where($where)->count();
        $page = new \Think\Page($count,15);
        $show = $page->show();
        $data = $agent
            ->where($where)
            ->order('id desc')
            ->limit("$page->firstRow,$page->listRows")
            ->select();
        $this->assign('data',$data);
        $this->assign('page',$show);
        $this->display();
    }

    //发送消息
    public function aSendMes()
    {
        $id = I('post.id',0);
        $data['receiver_id'] = $id;
        $data['sender_name'] = '系统消息';
        $data['message'] = I('post.message','');
        $data['sent_time'] = time();
        //合作伙伴
        $data['user_flag'] = 5;
        M('message','fh_')->add($data);
        $this->success('发送成功');
    }

    public function aAdd()
    {
        if(IS_POST){
            $mobile = I('post.mobile',0);
            $access = M('students_parent','fh_')->where(array('parent_mobile'=>$mobile))->find()
                || $access = M('teacher','fh_')->where(array('teacher_mobile'=>$mobile))->find()
                    || $access = M('schools','fh_')->where(array('leader_mobile'=>$mobile))->find()
                        || $access = M('schools','fh_')->where(array('teacher_mobile'=>$mobile))->find()
                            || $access = M('admins','fh_')->where(array('admin_mobile'=>$mobile))->find()
                                || $access = M('agent','fh_')->where(array('mobile'=>$mobile))->find();
            if($access){
                $this->error('手机号已注册');
            }

            $province_id = I('post.province_id',0);
            $data['province'] = M('region','fh_')->where(['region_id'=>$province_id])->getField('region_name');
            $data['city'] = I('post.city','');
            $data['name'] = I('post.name','');
            $data['bank_name'] = I('post.bank_name','');
            $data['bank_card'] = I('post.bank_card','');
            $data['mobile'] = $mobile;
            $data['pwd'] = md5('123456');
            $data['addtime'] = time();
            $result = M('agent','fh_')->add($data);
            if($result){
                $this->success('操作成功');
            }else{
                $this->error('操作失败');
            }
        }else{
            $province = M('region','fh_')->where('parent_id = 0')->select();
            $this->assign('province',$province);
            $this->display();
        }
    }

    public function aEdit()
    {
        if(IS_POST){
            $id = I('post.id',0,'intval');
            $data['name'] = I('post.name','');
            $data['mobile'] = I('post.mobile',0);
            $data['mobile2'] = I('post.mobile2',0);
            $mobile = M('agent','fh_')->where(['id'=>$id])->getField('mobile');
            if($data['mobile'] != $mobile){
                $access = M('students_parent','fh_')->where(array('parent_mobile'=>$data['mobile']))->find()
                    || $access = M('teacher','fh_')->where(array('teacher_mobile'=>$data['mobile']))->find()
                        || $access = M('schools','fh_')->where(array('leader_mobile'=>$data['mobile']))->find()
                            || $access = M('schools','fh_')->where(array('teacher_mobile'=>$data['mobile']))->find()
                                || $access = M('admins','fh_')->where(array('admin_mobile'=>$data['mobile']))->find()
                                    || $access = M('agent','fh_')->where(array('mobile'=>$data['mobile']))->find();
                if($access){
                    $this->error('手机号已注册');
                }
            }
            $mobile2 = M('agent','fh_')->where(['id'=>$id])->getField('mobile2');
            if($data['mobile2'] != $mobile2){
                $access2 = M('students_parent','fh_')->where(array('parent_mobile'=>$data['mobile2']))->find()
                    || $access2 = M('teacher','fh_')->where(array('teacher_mobile'=>$data['mobile2']))->find()
                        || $access2 = M('schools','fh_')->where(array('leader_mobile'=>$data['mobile2']))->find()
                            || $access2 = M('schools','fh_')->where(array('teacher_mobile'=>$data['mobile2']))->find()
                                || $access2 = M('admins','fh_')->where(array('admin_mobile'=>$data['mobile2']))->find()
                                    || $access2 = M('agent','fh_')->where(array('mobile'=>$data['mobile2']))->find();
                $data['pwd2'] = md5('123456');
                if($access2){
                    $this->error('员工手机号已注册');
                }
            }
            $province_id = I('post.province_id',0);
            $data['province'] = M('region','fh_')->where(['region_id'=>$province_id])->getField('region_name');
            $data['city'] = I('post.city','');
            $result = M('agent','fh_')->where(['id'=>$id])->save($data);
            if($result){
                $this->success('操作成功');
            }else{
                $this->error('操作失败');
            }

        }else{
            $id = I('get.id',0,'intval');
            $data = M('agent a','fh_')
                ->where(['a.id'=>$id])
                ->find();
            $province = M('region','fh_')->where('parent_id = 0')->select();
            $this->assign('province',$province);
            $this->assign('data',$data);
            $this->display();
        }
    }

    public function aDel()
    {
        $id = I('post.id',0);
        $result = M('agent','fh_')->where("id = $id")->delete();
        if($result){
            $this->success('操作成功');
        }else{
            $this->error('操作失败');
        }
    }

    public function aDelFew()
    {
        $ids = I('post.ids');
        $ids = explode(',',$ids);
        $agent = M('agent','fh_');
        foreach($ids as $v){
            $agent->delete($v);
        }
        $this->success('操作成功');
    }

    public function selectClass()
    {
        $id = I('get.id');
        $data = M('class','fh_')->where("grade_id = $id")->order('class_name')->select();
        $this->success($data);
    }
}