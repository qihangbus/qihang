<?php
namespace Admin\Controller;
use Think\Controller;

class SchoolController extends CommonController
{
    public function index()
    {
        $value = I('get.value');
        $where = "s.school_id not in (4,8,9,12)";
        if($value){
            $this->assign('value',$value);
            $where = "s.school_name like '%$value%'";
        }
        $school = M('schools s','fh_');
        $count = $school->where($where)->count();
        $page = new \Think\Page($count,15);
        $show = $page->show();
        $data = $school
            ->where($where)
            ->order('school_id desc')
            ->limit("$page->firstRow,$page->listRows")
            ->select();
        $start_time = strtotime(date('Y-m-d 00:00'));
        $now = time();
        foreach($data as $k => $v){
            //注册人数
            $sql = "select count(*) as count from (select student_id from fh_students_parent where school_id = {$v['school_id']} group by student_id) as t1";
            $temp = M()->query($sql);
            $data[$k]['reg_num'] = $temp[0]['count'];
            //交费人数
//            if($v['school_id'] < 19 && $v['school_id'] <> 2) {//旧收费模式
                if (time() > ($v['semester_one'] + 86400) && time() < ($v['semester_two'] + 86400)) {//已到第二学期
                    $data[$k]['pay_num'] = M('students', 'fh_')->where("school_id = {$v['school_id']} and paid_num = 2")->count();
                    $end_date = $v['semester_two'];
                    $start_date = $v['try_charge_time'] ? $v['try_charge_time'] : $v['semester_two_start'];
                } elseif (time() <= ($v['semester_one'] + 86400)) {//第一学期
                    $data[$k]['pay_num'] = M('students', 'fh_')->where("school_id = {$v['school_id']} and is_paid <> 0")->count();
                    $end_date = $v['semester_one'];
                    $start_date = $v['try_charge_time'] ? $v['try_charge_time'] : $v['semester_one_start'];
                } else {
                    $data[$k]['pay_num'] = '等待开学';
                    $data[$k]['fee'] = 0;
                    $end_date = '';
                }

                //今天订购人数
                $data[$k]['pay_today'] = M('students', 'fh_')->where("school_id = {$v['school_id']} and paid_time > $start_time")->count();

                //订购比例
                if ($data[$k]['pay_num'] <> '等待开学') {
                    $data[$k]['pay_percent'] = round($data[$k]['pay_num'] / $v['school_num'] * 100);
                } else {
                    $data[$k]['pay_percent'] = 0;
                }

                if ($end_date) {
                    $all_day = round(($end_date - $start_date) / 3600 / 24);
                    $meal = round($v['meal_market_price'] / 30, 2);
                    $data[$k]['fee'] = ceil($all_day * $meal);
                }
//            }else{//新收费模式
//                $data[$k]['pay_num'] = M('students', 'fh_')->where("school_id = {$v['school_id']} and paid_expires > $now")->count();
//                //今天订购人数
//                $data[$k]['pay_today'] = M('students', 'fh_')->where("school_id = {$v['school_id']} and paid_time > $start_time")->count();
//                //订购比例
//                $data[$k]['pay_percent'] = round($data[$k]['pay_num'] / $v['school_num'] * 100);
//                //收费金额
//                $data[$k]['fee'] = '年费';
//            }
        }
        $this->assign('data',$data);
        $this->assign('page',$show);
        $this->display();
    }

    public function student()
    {
        $school_id = I('get.school_id',0);
        $type = I('get.type',1);
        $student = M('students','fh_');
        if($type == 1){//注册学生
            $count = $student->where("school_id = {$school_id} and student_id in (select student_id from fh_students_parent)")->count();
            $page = new \Think\Page($count,15);
            $show = $page->show();
            $sql = "select * from fh_students where school_id = {$school_id} and student_id in (select student_id from fh_students_parent) limit $page->firstRow,$page->listRows";
            $data = M()->query($sql);
        }else{
            $school = M('schools','fh_')->where(['school_id'=>$school_id])->find();
//            if($school_id < 19 && $school_id <> 2) {//旧收费模式
                if (time() > ($school['semester_two_start'] - 86400 * 7) && time() < ($school['semester_two'] + 86400)) {//已到第二学期
                    $count = $student->where("school_id = {$school_id} and paid_time > ({$school['semester_two_start']} - 86400 * 7) and paid_time < ({$school['semester_two']} + 86400)")->count();
                    $page = new \Think\Page($count, 15);
                    $show = $page->show();
                    $data = M('students', 'fh_')->where("school_id = {$school_id} and paid_time > ({$school['semester_two_start']} - 86400 * 7) and paid_time < ({$school['semester_two']} + 86400)")->order('paid_time')->limit($page->firstRow, $page->listRows)->select();
                } elseif (time() < ($school['semester_two_start'] - 86400 * 7)) {//第一学期
                    $count = $student->where("school_id = {$school_id} and paid_time < ({$school['semester_two_start']} - 86400 * 7) and paid_time > 0")->count();
                    $page = new \Think\Page($count, 15);
                    $show = $page->show();
                    $data = M('students', 'fh_')->where("school_id = {$school_id} and paid_time < ({$school['semester_two_start']} - 86400 * 7) and paid_time > 0")->order('paid_time')->limit($page->firstRow, $page->listRows)->select();
                } else {
                    $this->error('等待下学期开学');
                }
//            }else{//新收费模式
//                $now = time();
//                $count = $student->where("school_id = {$school_id} and paid_expires > $now")->count();
//                $page = new \Think\Page($count, 15);
//                $show = $page->show();
//                $data = M('students', 'fh_')->where("school_id = {$school_id} and paid_expires > $now")->order('paid_time')->limit($page->firstRow, $page->listRows)->select();
//            }
        }
        $this->assign('page',$show);
        $this->assign('data',$data);
        $this->assign('type',$type);
        $this->assign('school_id',$school_id);
        $this->display();
    }

    public function sOutExcel()
    {
        $school_id = I('get.school_id',0);
        $type = I('get.type',1);
        $school_name = M('schools','fh_')->where(['school_id'=>$school_id])->getField('school_name');
        if($type == 1){//注册学生
            $sql = "select student_name,grade_name,class_name from fh_students where school_id = {$school_id} and student_id in (select student_id from fh_students_parent)";
            $data = M()->query($sql);
            $name = $school_name.'注册学生';
        }else{
            $school = M('schools','fh_')->where(['school_id'=>$school_id])->find();
            if (time() > ($school['semester_two_start'] - 86400 * 7) && time() < ($school['semester_two'] + 86400)) {//已到第二学期
                $data = M('students','fh_')
                    ->where("school_id = {$school_id} and paid_time > ({$school['semester_two_start']} - 86400 * 7) and paid_time < ({$school['semester_two']} + 86400)")
                    ->field('student_name,grade_name,class_name,paid_time')
                    ->select();
            } elseif (time() < ($school['semester_two_start'] - 86400 * 7)) {//第一学期
                $data = M('students','fh_')
                    ->where("school_id = {$school_id} and paid_time < ({$school['semester_two_start']} - 86400 * 7) and paid_time > 0")
                    ->field('student_name,grade_name,class_name,paid_time')
                    ->select();
            } else {
                $this->error('等待下学期开学');
            }
            $name=$school_name.'交费学生';
            foreach($data as $k=>$v){
                $data[$k]['paid_time'] = date('Y-m-d H:i:s',$v['paid_time']);
            }
        }

        $headArr = ['学生姓名','年级','班级','时间'];
        $leadIn = new LeadInController();
        $leadIn->out($name,$headArr,$data);
    }

    public function edit()
    {
        if(IS_POST){
            $agent_mobile = I('post.agent_mobile');
            if($agent_mobile){
                $agent_id = M('agent','fh_')->where("mobile = '$agent_mobile'")->getField('id');
                if(!$agent_id){
                    $this->error('合作伙伴手机号错误!');
                }
            }
            $data=I('post.');
            $sid=I('post.id');
            unset($data['id']);
            $data['agent_id'] = $agent_id ? $agent_id : 0;
            $data['agent_mobile'] = $agent_mobile ? $agent_mobile : '';
            $data['province_name'] = M("region",'fh_')->where(['region_id'=>$data['province_id']])->getField("region_name");
            $data['city_name'] = M("region",'fh_')->where(['region_id'=>$data['city_id']])->getField("region_name");
            $data['district_name'] = M("region",'fh_')->where(['region_id'=>$data['district_id']])->getField("region_name");
            $data['semester_one_start'] = strtotime(I('post.semester_one_start',''));
            $data['semester_one'] = strtotime(I('post.semester_one',''));
            $data['semester_two_start'] = strtotime(I('post.semester_two_start',''));
            $data['semester_two'] = strtotime(I('post.semester_two',''));
            $data['charge_type'] = I('post.charge_type',1);
            $data['try_charge_time'] = strtotime(I('post.try_charge_time',''));
            $result = M('schools','fh_')->where("school_id = $sid")->save($data);
            if($result){
                $this->success('修改成功',U('index'));
            }else{
                $this->error('修改失败');
            }
        }else{
            $id = I('get.id',0);
            $data = M('schools','fh_')->where(['school_id'=>$id])->find();
            $province = M('region','fh_')->where('parent_id = 0')->select();
            $city = M('region','fh_')->where("parent_id = {$data['province_id']}")->select();
            $zone = M('region','fh_')->where("parent_id = {$data['city_id']}")->select();
            $this->assign('province',$province);
            $this->assign('city',$city);
            $this->assign('zone',$zone);
            $this->assign('data',$data);
            $this->display();
        }
    }

    public function add()
    {
        if(IS_POST){
            $agent_mobile = I('post.agent_mobile');
            if($agent_mobile){
                $agent_id = M('agent','fh_')->where("mobile = '$agent_mobile'")->getField('id');
                if(!$agent_id){
                    $this->error('合作伙伴手机号错误!');
                }
            }
            $data=I('post.');
            $data['agent_id'] = $agent_id ? $agent_id : 0;
            $data['agent_mobile'] = $agent_mobile ? $agent_mobile : '';
            $data['province_name'] = M("region",'fh_')->where(['region_id'=>$data['province_id']])->getField("region_name");
            $data['city_name'] = M("region",'fh_')->where(['region_id'=>$data['city_id']])->getField("region_name");
            $data['district_name'] = M("region",'fh_')->where(['region_id'=>$data['district_id']])->getField("region_name");
            $data['semester_one_start'] = strtotime(I('post.semester_one_start',''));
            $data['semester_one'] = strtotime(I('post.semester_one',''));
            $data['semester_two_start'] = strtotime(I('post.semester_two_start',''));
            $data['semester_two'] = strtotime(I('post.semester_two',''));
            $data['pwd'] = md5('123456');
            $result = M('schools','fh_')->add($data);
            if($result){
                $this->success('操作成功',U('index'));
            }else{
                $this->error('操作失败');
            }
        }else{
            $province = M('region','fh_')->where('parent_id = 0')->select();
            $this->assign('province',$province);
            $this->display();
        }

    }

    public function del()
    {
        $id = I('post.id',0);
        $result = M('schools','fh_')->where("school_id = $id")->delete();
        if($result){
            $this->success('操作成功！');
        }else{
            $this->error('操作失败!');
        }
    }

    public function delFew()
    {
        $ids = I('post.ids');
        $ids = explode(',',$ids);
        $school = M('schools','fh_');
        foreach($ids as $v){
            $school->delete($v);
        }
        $this->success('操作成功!');
    }

    public function select()
    {
        $id = I('get.id');
        $data = M('region','fh_')->where("parent_id = $id")->select();
        $this->success($data);
    }

    //补发图书
    public function reissue()
    {
        $sid = I('get.id');
        $compensate = M('compensate c','fh_')
            ->join('left join fh_schools s on s.school_id = c.school_id')
            ->join('left join fh_grade g on g.grade_id = c.grade_id')
            ->join('left join fh_class cl on cl.class_id = c.class_id')
            ->join('left join fh_books b on b.book_id = c.book_id')
            ->where(['c.bf_status'=>0,'c.school_id'=>$sid])
            ->field('s.school_name,g.grade_name,cl.class_name,b.book_name,c.book_no,b.book_isbn')
            ->select();
        $lose = M('rotate_lose rl','fh_')
            ->join('left join fh_schools s on s.school_id = rl.school_id')
            ->join('left join fh_grade g on g.grade_id = rl.grade_id')
            ->join('left join fh_class cl on cl.class_id = rl.class_id')
            ->join('left join fh_books b on b.book_id = rl.book_id')
            ->where(['rl.bf_status'=>0,'rl.school_id'=>$sid])
            ->field('s.school_name,g.grade_name,cl.class_name,b.book_name,rl.book_no,b.book_isbn')
            ->select();
        $data = array_merge($compensate,$lose);
        if(!$data)  $this->error('没有数据！','',1);
        foreach($data as $k=>$v){
            $school_name = $v['school_name'];
            $class_name[] = $v['class_name'];
            $book_no[] = $v['book_no'];
        }
        array_multisort($class_name,SORT_ASC,$book_no,SORT_ASC,$data);
        $headArr = ['学校','年级','班级','绘本名称','编号','isbn'];
        $leadIn = new LeadInController();
        $leadIn->out('绘本补发_'.$school_name,$headArr,$data);
    }

    public function outExcel()
    {
        $id = I('get.id',0);
        $school_name = M('schools','fh_')->where("school_id = $id")->getField('school_name');
        $data = M('schooltobook stb','fh_')
            ->join("fh_books b on b.book_id = stb.book_id")
            ->join("fh_class c on c.class_id = stb.class_id")
            ->join("fh_grade g on g.grade_id = stb.grade_id")
            ->field("g.grade_name,c.class_name,b.book_name,b.press,b.book_isbn,stb.book_no,b.sort,stb.class_id")
            ->where("stb.school_id=$id")
            ->order('stb.class_id desc,stb.book_no asc')
            ->select();

        $headArr = ['年级','班级','绘本名称','出版社','ISBN','班级编号','目录编号','班级ID'];
        $leadIn = new LeadInController();
        $leadIn->out($school_name,$headArr,$data);
    }

    public function bookList()
    {
        $id = I('get.id',0);
        $value = I('get.value');
        $where = "stb.school_id = $id";
        if($value){
            $this->assign('value',$value);
            $where .= " and b.book_name like '%$value%'";
        }
        $stb = M('schooltobook stb','fh_');
        $count = $stb
            ->join('left join fh_books b on b.book_id = stb.book_id')
            ->where($where)
            ->count();
        $page = new \Think\Page($count,15);
        $show = $page->show();
        $data = $stb
            ->join('left join fh_books b on b.book_id = stb.book_id')
            ->join('left join fh_grade g on g.grade_id = stb.grade_id')
            ->join('left join fh_class c on c.class_id = stb.class_id')
            ->where($where)
            ->order('b.class_2,b.class_3,b.class_4,sort')
            ->limit("$page->firstRow,$page->listRows")
            ->field('b.*,stb.book_no,g.grade_name,c.class_name')
            ->select();
        $this->assign('data',$data);
        $this->assign('page',$show);
        $this->assign('id',$id);
        $this->display();
    }

    //发送消息
    public function sendMes()
    {
        $school_id = I('post.id',0);
        $data['sender_name'] = '系统消息';
        $data['message'] = I('post.message','');
        $data['sent_time'] = time();
        //园长
        $data['user_flag'] = 1;
        $data['receiver_id'] = I('post.id',0);
        M('message','fh_')->add($data);
        M('schools','fh_')->where(['school_id'=>$school_id])->setInc('message_num');

        //管理员
        $data['user_flag'] = 4;
        $admins = M('admins','fh_')->where(['school_id'=>$school_id])->select();
        foreach($admins as $k=>$v){
            $data['receiver_id'] = $v['admin_id'];
            M('message','fh_')->add($data);
        }
        M('admins','fh_')->where(['school_id'=>$school_id])->setInc('message_num');

        //老师
        $data['user_flag'] = 2;
        $teachers = M('teacher','fh_')->where(['school_id'=>$school_id])->select();
        foreach($teachers as $k=>$v){
            $data['receiver_id'] = $v['teacher_id'];
            M('message','fh_')->add($data);
        }
        M('teacher','fh_')->where(['school_id'=>$school_id])->setInc('message_num');

        //家长
        $data['user_flag'] = 3;
        $students = M('students','fh_')->where(['school_id'=>$school_id])->select();
        foreach($students as $k=>$v){
            $data['receiver_id'] = $v['student_id'];
            M('message','fh_')->add($data);
        }
        M('students','fh_')->where(['school_id'=>$school_id])->setInc('message_num');

        $this->success('发送成功');
    }
    //批量发送消息
    public function sendFewMes()
    {
        $data['message'] = I('post.message','');
        $data['sent_time'] = time();
        $data['sender_name'] = '系统消息';
        $schools = M('schools','fh_')->field('school_id')->select();
        foreach($schools as $k => $v){
            $school_id = $v['school_id'];
            //园长
            $data['user_flag'] = 1;
            $data['receiver_id'] = I('post.id',0);
            M('message','fh_')->add($data);
            M('schools','fh_')->where(['school_id'=>$school_id])->setInc('message_num');

            //管理员
            $data['user_flag'] = 4;
            $admins = M('admins','fh_')->where(['school_id'=>$school_id])->select();
            foreach($admins as $k=>$v){
                $data['receiver_id'] = $v['admin_id'];
                M('message','fh_')->add($data);
            }
            M('admins','fh_')->where(['school_id'=>$school_id])->setInc('message_num');

            //老师
            $data['user_flag'] = 2;
            $teachers = M('teacher','fh_')->where(['school_id'=>$school_id])->select();
            foreach($teachers as $k=>$v){
                $data['receiver_id'] = $v['teacher_id'];
                M('message','fh_')->add($data);
            }
            M('teacher','fh_')->where(['school_id'=>$school_id])->setInc('message_num');

            //家长
            $data['user_flag'] = 3;
            $students = M('students','fh_')->where(['school_id'=>$school_id])->select();
            foreach($students as $k=>$v){
                $data['receiver_id'] = $v['student_id'];
                M('message','fh_')->add($data);
            }
            M('students','fh_')->where(['school_id'=>$school_id])->setInc('message_num');
        }

        $this->success('发送成功');
    }

    //生成目录
    public function createDir()
    {
        $id = I('post.id',0);
        $access = M('class','fh_')->where(['school_id'=>$id])->find();
        if(!$access){
            $this->error('请先导入班级数据');
        }
        $school = M('schools','fh_')->where(['school_id'=>$id])->find();
        if($school['import_flag'] == 1){
            $this->error('不要重复生成目录');
        }
        $schoolname = $school['school_name'];
        $meal_type = $school['meal_type'];
        $class_max_num = $school['class_max_num'];
        //套餐1一次一本，2一次两本
        if($meal_type == 1){//套餐1
            $meal_num = 1;
        }else{//套餐2
            $meal_num = 2;
        }
        //多配图书
        $class_book_num = ($class_max_num * $meal_num) + (5 * $meal_num);
        //计算一个学生一年不重复读书所需的图书种类(按照十个月一学期，40周，一周借阅两次计算)
        $theory_max_num = 40 * 2 * $meal_num;
        //根据班级所需最大图书量和一年不重复阅读绘本量计算几个班级循环
        $real_circul_num = ceil($theory_max_num/$class_book_num);    //有余数进一
        //查询年级列表数据
        $grade_list = M("grade",'fh_')->where(['school_id'=>$id])->select();
        //轮换信息
        for($a=0;$a<count($grade_list);$a++){
            $class_ret = [];
            $class_list = M("class",'fh_')->where(array('grade_id'=>$grade_list[$a]['grade_id']))->order("class_id asc")->select();
            //如果一个年级只有一个班级,按照理论最大值配书
            if(count($class_list) == 1){
                array_push($class_ret,$class_list[0]['class_id']);
                $class_ret = $this->arraySlice($class_ret,1);
            }else{
                if(count($class_list) < $real_circul_num)
                {
                    $realcirculnum = count($class_list);
                }
                else
                {
                    $realcirculnum = $real_circul_num;
                }
                //转换为一维数组
                $class_arr = [];
                foreach($class_list as $key=>$value)
                {
                    array_push($class_arr,$value['class_id']);
                }
                //按照程序算出的数据把班级进行分组
                $class_ret = $this->arraySlice($class_arr,$realcirculnum);
            }
            for($i=0;$i<count($class_ret);$i++)
            {
                $real_book_num = intval($theory_max_num/count($class_ret[$i]));
                if($real_book_num < $class_book_num)     $real_book_num = $class_book_num;
                //计算绘本下次轮换时间
                $month_num = intval(($real_book_num/($meal_num*2))/4);
                $date_time = time();
                $grade_time = strtotime("+".$month_num."months",$date_time);

                M("grade",'fh_')->where(['grade_id'=>$grade_list[$a]['grade_id']])->save(['change_time'=>$grade_time,'change_interval'=>$month_num]);
                //查询已经轮换次数
                $temp = M("class_circul",'fh_')->where(['school_id'=>$grade_list[$a]['school_id']])->order("circul_num desc")->getField("circul_num");
                $num = $temp ? ++$temp : 1;
                foreach($class_ret[$i] as $k=>$v){
                    if(!$class_ret[$i][$k+1]){//小组内的最后一个班级
                        $pre_class_id = $v;
                        $next_class_id = $class_ret[$i][0];
                    }else{
                        $pre_class_id = $v;
                        $next_class_id = $class_ret[$i][$k+1];
                    }
                    $data = ['school_id'=>$grade_list[$a]['school_id'],
                        'grade_id'=>$grade_list[$a]['grade_id'],
                        'pre_class_id'=>$pre_class_id,
                        'next_class_id'=>$next_class_id,
                        'add_time'=>time(),
                        'book_number'=>$real_book_num,
                        'circul_num'=>$num
                    ];
                    M("class_circul",'fh_')->add($data);  //添加轮换信息
                }
            }
        }
        //分配目录
        $ret = M("class_circul",'fh_')->where(['school_id'=>$id])->order("id desc")->select();
        //查询所有的年级
        for($i=0;$i<count($ret);$i++){
            $school_id = $ret[$i]['school_id'];
            $grade_id = $ret[$i]['grade_id'];
            $class_id = $ret[$i]['pre_class_id'];
            $book_number = $ret[$i]['book_number'];
            $circul_num = $ret[$i]['circul_num'];   //轮换分组

            //查询班级名称
            $class_name  = M('class','fh_')->where("class_id = $class_id")->getField('class_name');

            //查询同一个分组内的班级分的图书数据(不同分组间可以有相同的图书)
            $arr = M("class_circul",'fh_')->where(['grade_id'=>$grade_id,'circul_num'=>$circul_num])->field("pre_class_id")->select();

            $arr_id = [];
            foreach($arr as $k=>$v)
            {
                array_push($arr_id,$v['pre_class_id']);
            }
            $book_arr = M("schooltobook",'fh_')->where(['school_id'=>$school_id,'grade_id'=>$grade_id,'class_id'=>["in",$arr_id]])->field("book_id")->select();
            $book_ids = 0;
            foreach ($book_arr as $key => $value) {
                $book_ids .= ','.$value['book_id'];
            }

            $grade_flag = M("grade",'fh_')->where(['grade_id'=>$grade_id])->getField("grade_flag");      //grade_flag 哪个年级
            if($grade_flag == 1){
                $f = " class_2=1 AND book_id NOT IN($book_ids) and sort>0";
            }elseif($grade_flag == 2){
                $f = " class_3=1 AND book_id NOT IN($book_ids) and sort>0";
            }elseif($grade_flag == 3){
                $f = " class_4=1 AND book_id NOT IN($book_ids) and sort>0";
            }

            $sql = "SELECT book_id,book_isbn,book_name,sub_name FROM `fh_books` WHERE $f ORDER BY sort LIMIT 0,$book_number";
            $list = M()->query($sql);

            //查找图书编号
            $book_no = M('schooltobook','fh_')->where("class_id = $class_id")->order('book_no desc')->getfield('book_no');
            $book_no++;

            //插入到对应的数据库中
            for ($k=0;$k<count($list);$k++) {
                M('schooltobook','fh_')->add(
                    ['school_id'=>$school_id,
                        'class_id'=>$class_id,
                        'grade_id'=>$grade_id,
                        'book_id'=>$list[$k]['book_id'],
                        'book_no'=>$book_no+$k
                    ]
                );
            }
        }
        //设置分配标识
        M('schools','fh_')->where(['school_id'=>$id])->save(['import_flag'=>1]);
        $this->success('生成目录成功');
    }

    //切割数组 xj 2017-6-20 14:26:46
    private function arraySlice($class_arr,$realcirculnum,$class_ret = array())
    {
        $num = count($class_arr);
        if($num < $realcirculnum){
            $num = count($class_ret);
            foreach($class_arr as $v){
                $class_ret[$num-1][] = $v;
            }
        }else{
            $class_ret[] = array_slice($class_arr,0,$realcirculnum);
        }
        $class_arr = array_slice($class_arr,$realcirculnum);
        if(empty($class_arr)){
            return $class_ret;
        }
        return $this->arraySlice($class_arr,$realcirculnum,$class_ret);
    }

    //绘本缺失替换
    public function dirReplace()
    {
        if(!IS_POST){
            $this->display();
        }else {
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
            $schooltobook = M('schooltobook','fh_');
            $books = M('books','fh_');
            unset($arr[1]);
            foreach ($arr as $v) {
                $class_id = $v['A'];
                $book_no = $v['B'];
                $book_isbn = $v['C'];
                $book_id = $books->where("book_isbn = '$book_isbn'")->getField('book_id');
                if(!$book_id){
                    echo "<script type='text/javascript'>updateProgress('请先添加ISBN为 $book_isbn 的绘本','$progress%');</script>";
                    exit();
                }
                $rs = $schooltobook->where("class_id = $class_id and book_no = $book_no")->setField('book_id',$book_id);
                echo "<script type='text/javascript'>updateProgress('已导入$progress%','$progress%');</script>";
                ob_flush();
                flush(); //将输出发送给客户端浏览器，使其可以立即执行服务器端输出的 JavaScript 程序。
                $progress += $pix;
            } //end foreach
            $id = I('get.id',0);
            M('schools','fh_')->where("school_id = $id")->setField('replace_status',1);
            echo "<script type='text/javascript'>success();updateProgress('目录替换成功 !','100%');</script>";
            unlink($filename);
        }
    }

    public function grade()
    {
        $value = I('get.value');
        $school_id = I('get.school_id',0);
        if($value){
            $this->assign('value',$value);
            $where = "s.school_name like '%$value%'";
        }elseif($school_id){
            $where = "s.school_id = $school_id";
        }
        $grade = M('grade g','fh_');
        $count = $grade
            ->join('fh_schools s on s.school_id = g.school_id')
            ->where($where)
            ->count();
        $page = new \Think\Page($count,15);
        $show = $page->show();
        $data = $grade
            ->join('fh_schools s on s.school_id = g.school_id')
            ->where($where)
            ->order('g.school_id desc,g.grade_id desc')
            ->limit("$page->firstRow,$page->listRows")
            ->field('g.*,s.school_name')
            ->select();
        $this->assign('data',$data);
        $this->assign('page',$show);
        $this->display();
    }

    public function selectSchool()
    {
        $id = I('get.id');
        $data = M('schools','fh_')->where("district_id = $id")->select();
        $this->success($data);
    }

    public function gradeAdd()
    {
        if(IS_POST){
            $data['school_id'] = I('post.school_id',0);
            $data['grade_name'] = I('post.grade_name','');
            $data['grade_flag'] = I('post.grade_flag',1);
            $result = M('grade','fh_')->add($data);
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

    public function gradeDel()
    {
        $id = I('post.id',0);
        $result = M('grade','fh_')->where("grade_id = $id")->delete();
        if($result){
            $this->success('操作成功！');
        }else{
            $this->error('操作失败!');
        }
    }

    public function gradeDelFew()
    {
        $ids = I('post.ids');
        $ids = explode(',',$ids);
        $grade = M('grade','fh_');
        foreach($ids as $v){
            $grade->delete($v);
        }
        $this->success('操作成功!');
    }

    public function gradeEdit()
    {
        $id = I('get.id',0);
        if(IS_POST){
            $id = I('post.id',0);
            $grade_name = I('post.grade_name','');
            $result = M('grade','fh_')->where("grade_id = $id")->setField('grade_name',$grade_name);
            if($result){
                $this->success('修改成功');
            }else{
                $this->error('修改失败');
            }
        }else{
            $data = M('grade g','fh_')
                ->join('left join fh_schools s on s.school_id = g.school_id')
                ->where("g.grade_id = $id")
                ->field('g.*,s.school_name')
                ->find();
            $this->assign('data',$data);
            $this->display();
        }
    }

    //发送模板消息
    public function sendTplSms()
    {
        if(IS_POST){
            //防止执行超时
            set_time_limit(0);
            if (ob_get_level() == 0) ob_start();
            ob_clean();

            $data = I('post.');
            $ids = $data['ids'];
            unset($data['ids']);
            $data['template_id'] = 'I6gYWQyxUgSsb9mw71dFy_HAODMIreZMre8VArjsl8M';
            $parent = M('students_parent','fh_')->where("school_id in ($ids) and wx_id <> ''")->field('wx_id')->select();

            //计算数据的长度
            $total = count($parent);
            //显示的进度条长度
            $width = 100;
            //每条记录的操作所占的进度条单位长度
            $pix = round($width / $total);
            //默认开始的进度条百分比
            $progress = $pix;
            header('Content-Type: text/html');
            header('Cache-Control: no-cache');
            header('X-Accel-Buffering: no');
            $this->display('progressbar');
            $wechat = new WechatController();
            foreach($parent as $k=>$v){
                $data['openid'] = $v['wx_id'];
                $wechat->sendTplSMS1($data);
                $now = $k + 1;
                echo "<script type='text/javascript'>updateProgress('已发送$now 人','$progress%');</script>";
                ob_flush();
                flush(); //将输出发送给客户端浏览器，使其可以立即执行服务器端输出的 JavaScript 程序。
                $progress += $pix;
            }
            echo "<script type='text/javascript'>success();updateProgress('模板消息发送成功，共$total 人','100%');</script>";
            ob_end_flush();
        }else{
            $ids = I('get.ids','');
            $this->assign('ids',$ids);
            $this->display();
        }
    }

    public function sclass()
    {
        $value = I('get.value');
        $grade_id = I('get.grade_id',0);
        if($value){
            $this->assign('value',$value);
            $where = "s.school_name like '%$value%'";
        }elseif($grade_id){
            $where = "c.grade_id = $grade_id";
        }
        if($value){
            $this->assign('value',$value);
            $where = "s.school_name like '%$value%'";
        }
        $class = M('class c','fh_');
        $count = $class
            ->join('fh_schools s on s.school_id = c.school_id')
            ->where($where)
            ->count();
        $page = new \Think\Page($count,15);
        $show = $page->show();
        $data = $class
            ->join('left join fh_schools s on s.school_id = c.school_id')
            ->join('left join fh_grade g on g.grade_id = c.grade_id')
            ->where($where)
            ->order('c.school_id desc,c.class_id desc')
            ->limit("$page->firstRow,$page->listRows")
            ->field('c.*,s.school_name,g.grade_name,s.semester_two_start,s.semester_two,s.semester_one_start,s.semester_one')
            ->select();
        foreach($data as $k=>$v){
            $data[$k]['stu_num'] = M('students','fh_')->where(['class_id'=>$v['class_id']])->count();
            $data[$k]['reg_stu'] = M()->query("select count(*) as count from (select parent_id from fh_students_parent where class_id = {$v['class_id']} group by student_id) as sp")[0]['count'];
            if (time() > ($v['semester_one'] + 86400) && time() < ($v['semester_two'] + 86400)) {//已到第二学期
                $data[$k]['pay_stu'] = M('students','fh_')->where("class_id = {$v['class_id']} and paid_num = 2")->count();
            } elseif (time() <= ($v['semester_one'] + 86400)) {//第一学期
                $data[$k]['pay_stu'] = M('students','fh_')->where("class_id = {$v['class_id']} and paid_num <> 0")->count();
            } else {
                $data[$k]['pay_stu'] = '等待开学';
            }

            //订购比例
            if($data[$k]['pay_stu'] <> '等待开学'){
                $data[$k]['pay_percent'] = round($data[$k]['pay_stu']/$data[$k]['stu_num']*100);
            }else{
                $data[$k]['pay_percent'] = 0;
            }
        }
        $this->assign('data',$data);
        $this->assign('page',$show);
        $this->display();
    }

    public function classAdd()
    {
        if(IS_POST){
            $data['school_id'] = I('post.school_id',0);
            $data['grade_id'] = I('post.grade_id',0);
            $data['class_name'] = I('post.class_name','');
            $result = M('class','fh_')->add($data);
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

    public function classDel()
    {
        $id = I('post.id',0);
        $result = M('class','fh_')->where("class_id = $id")->delete();
        if($result){
            $this->success('操作成功！');
        }else{
            $this->error('操作失败!');
        }
    }

    public function classDelFew()
    {
        $ids = I('post.ids');
        $ids = explode(',',$ids);
        $grade = M('class','fh_');
        foreach($ids as $v){
            $grade->delete($v);
        }
        $this->success('操作成功!');
    }

    public function classEdit()
    {
        $id = I('get.id',0);
        if(IS_POST){
            $id = I('post.id',0);
            $data['grade_id'] = I('post.grade_id',0);
            $data['class_name'] = I('post.class_name','');
            $result = M('class','fh_')->where("class_id = $id")->save($data);
            if($result){
                $this->success('修改成功');
            }else{
                $this->error('修改失败');
            }
        }else{
            $data = M('class c','fh_')
                ->join('left join fh_schools s on s.school_id = c.school_id')
                ->where("c.class_id = $id")
                ->field('c.*,s.school_name')
                ->find();
            $grade = M('grade','fh_')
                ->where(['school_id'=>$data['school_id']])
                ->select();
            $this->assign('grade',$grade);
            $this->assign('data',$data);
            $this->display();
        }
    }

    public function selectGrade()
    {
        $id = I('get.id');
        $data = M('grade','fh_')->where("school_id = $id")->select();
        $this->success($data);
    }

    public function classLeadin()
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
            unset($arr[1]);
            $school = M('schools','fh_');
            $grade = M('grade','fh_');
            $class = M('class','fh_');
            $id = I('get.id',0);
            foreach ($arr as $v) {
                $grade_name = $v['A'];
                $class_name = $v['B'];
                if(!$class_name){
                    continue;
                }
                if($v['C'] == '小班'){
                    $grade_flag = 1;
                }elseif($v['C'] == '中班'){
                    $grade_flag = 2;
                }else{
                    $grade_flag = 3;
                }
                $access =$school->where(['school_id'=>$id])->find();
                if(!$access){
                    echo "<script type='text/javascript'>updateProgress('没有查询到学校','$progress%');</script>";
                    exit();
                }
                //添加年级
                $grade_id = $grade->where(['school_id'=>$id,'grade_name'=>$grade_name])->getField('grade_id');
                if(!$grade_id){
                    $data = [
                        'school_id'=>$id,
                        'grade_name'=>$grade_name,
                        'grade_flag'=>$grade_flag
                    ];
                    $grade_id = $grade->add($data);
                }
                //添加班级
                $access = $class->where(['grade_id'=>$grade_id,'class_name'=>$class_name])->find();
                if(!$access){
                    $data = [
                        'school_id'=>$id,
                        'grade_id'=>$grade_id,
                        'class_name'=>$class_name
                    ];
                    $class->add($data);
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
}