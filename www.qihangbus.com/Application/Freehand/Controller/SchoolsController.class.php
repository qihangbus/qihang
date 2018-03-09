<?php
namespace Freehand\Controller;
use Think\Controller;
class SchoolsController extends CommonController {
    public function index(){
        if($agent_id = I('get.id')){
            $where = "agent_id = $agent_id";
            $data = M('agent')->where("id = $agent_id")->find();
            $des = "{$data['name']}-{$data['province']}{$data['city']}";
            $this->assign('des',$des);
        }
        $schools = M('schools');
        $count = $schools->where($where)->count();
        $page = new \Think\Page($count,15);
        $show = $page->diyshow();
        $list = $schools->where($where)->order('school_id desc')->limit($page->firstRow.','.$page->listRows)->select();
        $this->assign('list',$list);
        $this->assign('page',$show);
        $this->display("department/index");
    }

    //添加
    public function add()
    {
        $this->assign('info','');

        $province_list = M('region')->where(array('region_level'=>1))->select();
        $this->assign('province_list',$province_list);
        $city_list = M('region')->where(array('region_level'=>2,'parent_id'=>1))->select();
        $this->assign('city_list',$city_list);
        $district_list = M('region')->where(array('region_level'=>3,'parent_id'=>2))->select();
        $this->assign('district_list',$district_list);

        $this->display("department/info");
    }

    //编辑
    public function edit()
    {
        $id = I('get.id','');
        $ret = M('schools')->where(array('school_id'=>$id))->find();
        $this->assign('info',$ret);

        $province_list = M('region')->where(array('region_level'=>1))->select();
        $this->assign('province_list',$province_list);
        $city_list = M('region')->where(array('region_level'=>2,'parent_id'=>$ret['province_id']))->select();
        $this->assign('city_list',$city_list);
        $district_list = M('region')->where(array('region_level'=>3,'parent_id'=>$ret['city_id']))->select();
        $this->assign('district_list',$district_list);

        $this->display("department/info");
    }

    public function batch_send()
    {
        $this->assign('user_flag',1);
        $this->display("department/batch_message");
    }

    //发送消息
    public function send()
    {
        $id = I('get.id');
        $this->assign('id',$id);
        $this->assign('user_flag',1);
        $this->display("department/message");
    }

    //临时用批量给所有的家长发送消息
    public function send_all()
    {
        //查询所有的学生
        $list = M("students")->where(array('school_id'=>2))->select();

        for($i=0;$i<count($list);$i++){
            $data = array();
            $data['receiver_id'] = $list[$i]['student_id'];
            $data['title'] = "系统消息";
            $data['message'] = "欢迎加入“启航巴士”图书借阅平台。在使用过程中有任何疑问和建议，欢迎向启航巴士客服或老师反馈，谢谢！";
            $data['sent_time'] = time();
            $data['user_flag'] = 3;
            $ret = M('message')->add($data);
        }
    }

    //异步请求地址
    public function ajax_address()
    {
        $region_level = I('param.region_level',1);
        $region_id = I('param.region_id',0);
        $region_list = M('region')->where(array('region_level'=>$region_level,'parent_id'=>$region_id))->select();
        echo json_encode($region_list);
    }

    //
    public function message_handle()
    {
        $data = array();
        $data['receiver_id'] = I('post.school_id','');
        $data['title'] = I('post.title','');
        $data['message'] = I('post.message','');
        $data['sent_time'] = time();
        $data['user_flag'] = I('post.user_flag','');
        $ret = M('message')->add($data);
        if($ret > 0){
            $this->success('发送成功',U('/Schools/index'),1);
        }else{
            $this->error('发送失败',U('/Schools/index'),1);
        }
    }

    public function message_batch_handle()
    {
        $data = array();

        $school_list = M('schoole')->field('school_id')->select();

        $data['title'] = I('post.title','');
        $data['message'] = I('post.message','');
        $data['sent_time'] = time();
        $data['user_flag'] = I('post.user_flag','');

        //循环给教师发送消息
        for($i=0;$i<count($school_list);$i++)
        {
            $data['receiver_id'] = $school_list[$i]['school_id'];
            $ret = M('message')->add($data);
        }


        if($ret > 0){
            $this->success('发送成功',U('/Schools/index'),1);
        }else{
            $this->error('发送失败',U('/Schools/index'),1);
        }
    }

    //处理数据
    public function edit_handle()
    {
        $condition = array();
        $agent_mobile = I('post.agent_mobile');
        if($agent_mobile){
            $agent_id = M('agent')->where("mobile = '$agent_mobile'")->getField('id');
            if(!$agent_id){
                $this->error('没有此渠道商!');
            }
        }
        $condition['agent_id'] = $agent_id ? $agent_id : 0;
        $condition['agent_mobile'] = $agent_mobile ? $agent_mobile : '';

        $sid = I('post.school_id','');
        $condition['school_name'] = I('post.school_name','');
        $condition['school_leader'] = I('post.school_leader','');
        $condition['leader_mobile'] = I('post.leader_mobile','');
        $condition['school_teacher'] = I('post.school_teacher','');
        $condition['teacher_mobile'] = I('post.teacher_mobile','');
        $condition['province_id'] = I('post.province','');
        $condition['province_name'] = M("region")->where(array('region_id'=>$condition['province_id']))->getField("region_name");
        $condition['city_id'] = I('post.city','');
        $condition['city_name'] = M("region")->where(array('region_id'=>$condition['city_id']))->getField("region_name");
        $condition['district_id'] = I('post.district','');
        $condition['district_name'] = M("region")->where(array('region_id'=>$condition['district_id']))->getField("region_name");
        $condition['school_address'] = I('post.school_address','');
        $condition['school_desc'] = I('post.school_desc','');
        $condition['school_num'] = I('post.school_num','');
        $condition['meal_type'] = I('post.meal_type',1);
        $condition['meal_market_price'] = I('post.meal_market_price');
        $condition['class_max_num'] = I('post.class_max_num','');
        $condition['semester_one_start'] = strtotime(I('post.semester_one_start',''));
        $condition['semester_one'] = strtotime(I('post.semester_one',''));
        $condition['semester_two_start'] = strtotime(I('post.semester_two_start',''));
        $condition['semester_two'] = strtotime(I('post.semester_two',''));
        $condition['remark'] = I('post.remark','');

        if($sid > 0){
            $ret = M('schools')->where(array('school_id'=>$sid))->save($condition);
            $this->success('修改成功',U('/Schools/index'),1);
        }else{
            $condition['pwd'] = md5('123456');
            $condition['reg_time'] = time();
            $ret = M('schools')->add($condition);
            $this->success('添加成功',U('/Schools/index'),1);
        }
    }

    //删除
    public function del(){
        $id = I('get.id');
        $ret = M('schools')->where(array('school_id'=>$id))->delete();
        if($ret){
            $this->success('删除成功',U('/Schools/index'),1);
        }else{
            $this->error('删除失败！');
        }
    }

    //图书管理
    public function book_list()
    {
        $id = I('get.id','');
        if(empty($id)) $id = I('post.id','');
        $this->assign("id",$id);


        $condition = "1";
        if($id){
            $condition .= " and fh_schooltobook.school_id = '$id'";
        }

        $page_size = I('post.page_size',15);
        $this->assign('page_size',$page_size);
        $keywords = I('post.keywords','');
        $this->assign('keywords',$keywords);
        if($keywords){
            $condition .= " and fh_books.book_name like '%$keywords%' or fh_books.sub_name like '%$keywords%'";
        }



        $count = M('schooltobook')->join("fh_books on fh_books.book_id=fh_schooltobook.book_id","left")->where($condition)->count();

        $page = new \Think\Page($count,$page_size);
        $show = $page->diyshow();

        $list = M('schooltobook')->join("fh_books on fh_books.book_id=fh_schooltobook.book_id")->where($condition)->order('press desc')->limit($page->firstRow.','.$page->listRows)->select();

        //查询损坏图书列表
        $books = M("compensate")->where(array('school_id'=>$id,'compen_status'=>array('lt',3)))->field("book_id,replace_book_id")->select();
        $bookarr= $books_arr = array();

        foreach($books as $k=>$v)
        {
            array_push($bookarr,$v['replace_book_id']);
            array_push($books_arr,$v['book_id']);
        }

        foreach ($list as $key => $value) {

            if(in_array($value['book_id'],$books_arr)){
                $list[$key]['desc'] = "<font color='red'>图书损坏</font>";
                $list[$key]['flag'] = 1;
            }else{
                $list[$key]['desc'] = "正常";
                $list[$key]['flag'] = 0;
            }



            $replace_book_id = M("compensate")->where(array('book_id'=>$value['book_id'],'school_id'=>$id))->getField("replace_book_id");

            if($replace_book_id>0 && in_array($replace_book_id,$bookarr)){
                $book_name = M("books")->where(array('book_id'=>$replace_book_id))->getField("book_name");
                $list[$key]['recplae_desc'] = "<font color='red'>图书已替换为：".$book_name."</font>";
                $list[$key]['flag'] = 2;
            }
            $list[$key]['cate_name'] = M('book_cate')->where(array('cate_id'=>$value['cate_id']))->getField('cate_name');
            $list[$key]['sup_name'] = M('suppliers')->where(array('sup_id'=>$value['sup_id']))->getField('sup_name');
        }

        $this->assign('list',$list);
        $this->assign('page',$show);
        $this->display("department/book_list");
    }

    //查找查找可以替换的图书
    public function bookslist()
    {
        $id = I('get.id','');
        $this->assign("id",$id);
        $grade_id = I('get.gid','');
        $this->assign("grade_id",$grade_id);

        $book_id = I('get.bid','');
        $this->assign("book_id",$book_id);


        //图书必须库存大于0
        $condition = " book_number > 0";

        //判断
        $grade_flag = M("grade")->where(array('school_id'=>$id,'grade_id'=>$grade_id))->getField("grade_flag");
        if($grade_flag == 1){
            $condition .= " and class_2=1";
        }elseif($grade_flag == 2){
            $condition .= " and class_3=1";
        }elseif($grade_flag == 3){
            $condition .= " and class_4=1";
        }


        $page_size = I('param.page_size',15);
        $this->assign('page_size',$page_size);
        $keywords = I('param.keywords','');
        $this->assign('keywords',$keywords);
        if($keywords){
            $condition .= " and book_name like '%$keywords%' or sub_name like '%$keywords%'";
        }

        $list = M('books')->where($condition)->order('book_id desc')->select();

        //查询当前园的图书
        $book_arr = array();
        $books = M("schooltobook")->where(array('school_id'=>$id))->field("book_id")->select();


        foreach($books as $k=>$v)
        {
            //判断是否损毁得图书
            if($v['book_id']!=$book_id){
                array_push($book_arr,$v['book_id']);
            }
        }

        $books_list = array();
        foreach ($list as $key => $value) {
            if(in_array($value['book_id'],$book_arr)) {
                unset($list[$key]);
            }else{
                $list[$key] = $value;
                $list[$key]['cate_name'] = M('book_cate')->where(array('cate_id'=>$value['cate_id']))->getField('cate_name');
                $list[$key]['sup_name'] = M('suppliers')->where(array('sup_id'=>$value['sup_id']))->getField('sup_name');
            }
        }

        $this->assign('list',$list);
        $this->display("department/bookslist");
    }

    //更换图书
    public function change_book()
    {
        $book_id = I("get.bid","");
        $school_id = I("get.sid","");
        $grade_id = I("get.gid","");
        $resourcebid = I("get.resourcebid","");
        //查询损坏图书ID
        $ret = M("compensate")->where(array('school_id'=>$school_id,'grade_id'=>$grade_id,'book_id'=>$resourcebid))->save(array('replace_book_id'=>$book_id));

        //图书库存减1
        M("books")->where(array('book_id'=>$book_id))->setDec("book_number",1);

        $this->success('替换成功',U('/Schools/index'),1);
    }

    //导出备份
    public function import_list_temp()
    {
        $id = I('get.id','');

        //查询学校名称
        $schoolname = M('schools')->where(array('school_id'=>$id))->getField('school_name');

        //查询套餐
        $meal_type = M('schools')->where(array('school_id'=>$id))->getField('meal_type');

        //查询本校中年级人数最多的一个
        $ret = M("students")->field("school_id,grade_id,class_id,count(grade_id) as total_num")->where(array('school_id'=>$id))->group("grade_id")->order("total_num desc")->find();

        $grade_id = $ret['grade_id'];
        $student_max_num = $ret['total_num'];

        //如果年级人数小于80则按80计算
        if($student_max_num < 80){
            $student_max_num = 80;
        }

        //查询所有的年级
        $grade_list = M("grade")->where(array('school_id'=>$id))->select();

        //计算本校总共需要多少本图书
        $book_max_num = $student_max_num * count($grade_list);

        //查询所有的班级
        $class = M('students')->field("grade_id,class_id,count(class_id) as class_total")->where(array('school_id'=>$id))->group("class_id")->select();


        //计算每个班级所需图书数
        $class_student_max_num = $book_max_num / count($class);

        /*for($i=0;$i<count($class);$i++)
        {
            for($j=0;$j<intval($class_student_max_num);$j++)
            {
                $student_arr[] = $class[$i]['class_id'];
            }
        }*/

        $class_arr = array();
        $class = M('students')->field("grade_id,class_id")->where(array('school_id'=>$id))->select();
        foreach ($class as $key => $value) {
            $class_arr[$value['class_id']] = $value['grade_id'];
        }

        for($i=0;$i<count($grade_list);$i++)
        {
            //查询本年级班级数
            $class_num = M("class")->where(array('grade_id'=>$grade_list[$i]['grade_id']))->select();


            //计算每个班级所需图书数
            $class_pre_num = $student_max_num / count($class_num);

            for($k=0;$k<count($class_num);$k++)
            {
                for($j=0;$j<intval($class_pre_num);$j++)
                {
                    $student_arr[] = $class_num[$k]['class_id'];
                }
            }

            for($j=0;$j<intval($student_max_num);$j++)
            {
                $grade_arr[] = $grade_list[$i]['grade_id'];
            }
        }

        //判断是否导出
        $d = M('schools')->where(array('school_id'=>$id))->getField('import_flag');


        if($d < 1){
            //计算平均数
            $avg = floor($student_max_num/5);
            $avg1 = $avg;
            //计算余数
            $remainder = $student_max_num%5;
            $list = array();

            //保存已经获取到的图书ID
            $book_arr = 0;

            //在五大图书分类中随机取
            for($i=0;$i<count($grade_list);$i++){
                $grade_flag = $grade_list[$i]['grade_flag'];

                for ($j=1; $j <= 5; $j++) {
                    if($j == 1) {
                        $avg1 = $avg + $remainder;
                    }else{
                        $avg1 = $avg;
                    }

                    if($grade_flag == 1){
                        $f = " class_2=1 AND book_id NOT IN($book_arr) ";
                    }elseif($grade_flag == 2){
                        $f = " class_3=1 AND book_id NOT IN($book_arr) ";
                    }elseif($grade_flag == 3){
                        $f = " class_4=1 AND book_id NOT IN($book_arr) ";
                    }

                    $sql = "SELECT * FROM `fh_books` WHERE $f and book_id >= ((SELECT MAX(`book_id`) FROM `fh_books`)-(SELECT MIN(`book_id`) FROM `fh_books`)) * RAND() + (SELECT MIN(`book_id`) FROM `fh_books`) ORDER BY book_id LIMIT $avg1";
                    $ret = M()->query($sql);

                    foreach ($ret as $key => $value) {
                        $book_arr .= ','.$value['book_id'];
                    }

                    $list = array_merge_recursive($list,$ret);
                }
            }

            //插入到对应的数据库中
            for ($i=0;$i<count($list);$i++) {
                $class_id = $student_arr[$i];
                $grade_id = $grade_arr[$i];
                $list[$i]['class_name'] = M("class")->where(array('class_id'=>$class_id))->getField("class_name");
                $list[$i]['grade_name'] = M("grade")->where(array('grade_id'=>$grade_id))->getField("grade_name");
                M('schooltobook')->add(array('school_id'=>$id,'class_id'=>$class_id,'grade_id'=>$grade_id,'book_id'=>$list[$i]['book_id']));
            }

            //设置导出标识
            M('schools')->where(array('school_id'=>$id))->save(array('import_flag'=>1));

        }else{
            //如果导出则直接从schooltobook表中导出对应得图书数据
            $list = M('schooltobook')->
            join("fh_books on fh_books.book_id=fh_schooltobook.book_id")->
            join("fh_class on fh_class.class_id=fh_schooltobook.class_id")->
            join("fh_grade on fh_grade.grade_id=fh_schooltobook.grade_id")->
            field("fh_books.book_name,fh_books.shop_price,fh_class.class_name,fh_grade.grade_name")->
            where("fh_schooltobook.school_id=$id")->
            select();
        }

        $str = "学校名,班级名,图书名\n";
        $str = iconv('utf-8','gb2312',$str);

        foreach ($list as $key => $value) {
            $school_name = iconv('utf-8','gb2312',$schoolname);
            $class_name = iconv('utf-8','gb2312',$value['grade_name'].$value['class_name']);
            $book_name = iconv('utf-8','gb2312','"'.$value['book_name'].$value['sub_name'].'"');
            $shop_price = iconv('utf-8','gb2312',$value['shop_price'].'元');
            $str .= $school_name.",".$class_name.",".$book_name."\n";
        }

        $filename = $schoolname.'的图书列表'.'.csv';
        export_csv($filename,$str);
    }

    //最新导出图书数据带五大分类和小中大班限制
    public function import_list_temp2()
    {
        $id = I('get.id','');

        $info = M('schools')->where(array('school_id'=>$id))->find();
        $schoolname = $info['school_name'];//查询学校名称
        $meal_type = $info['meal_type'];//查询学校定制套餐
        $classmaxnum = $info['class_max_num'];//查询学校班级人数招收上限
        $classmaxnum = $classmaxnum + 5;//在班级最大人数的基础上在增加5本图书，以方便老师更换图书操作
        //计算本校年级所需要的最大图书数量
        $ret = M("class")->field("school_id,grade_id,class_id,count(grade_id) as grade_num")->where(array('school_id'=>$id))->group("grade_id")->order("grade_num desc")->find();
        $student_max_num = $ret['grade_num'] * $classmaxnum;

        //计算理论上一个学生一学期不重复读书所需的图书数量(按照五个月一学期，22周，一周2本计算)
        $theory_num = 22 * 2;

        //如果年级人数小于理论值则按理论值计算
        if($student_max_num < $theory_num){
            $student_max_num = $theory_num;
        }

        //查询所有的年级
        $grade_list = M("grade")->where(array('school_id'=>$id))->select();

        //计算本校总共需要多少本图书
        $book_max_num = $student_max_num * count($grade_list);

        //查询所有的班级
        $class = M('class')->field("class_id")->where(array('school_id'=>$id))->select();

        for($i=0;$i<count($grade_list);$i++)
        {
            //查询本年级班级数
            $class_num = M("class")->where(array('grade_id'=>$grade_list[$i]['grade_id']))->select();

            //计算每个班级所需图书数
            $class_pre_num = intval($student_max_num / count($class_num));
            $class_pre = $student_max_num % count($class_num);

            for($k=0;$k<count($class_num);$k++)
            {

                $classprenum = 0;
                if($k < 1) {
                    $classprenum = $class_pre_num + $class_pre;
                }else{
                    $classprenum = $class_pre_num;
                }
                //echo $k;
                //echo '=>'.$classprenum."<br>";
                for($j=0;$j<intval($classprenum);$j++)
                {
                    $student_arr[] = $class_num[$k]['class_id'];
                }
            }

            for($j=0;$j<intval($student_max_num);$j++)
            {
                $grade_arr[] = $grade_list[$i]['grade_id'];
            }
        }

        //判断是否导出
        $d = M('schools')->where(array('school_id'=>$id))->getField('import_flag');


        if($d < 1){
            //计算平均数
            $avg = floor($student_max_num/5);
            $avg1 = $avg;
            //计算余数
            $remainder = $student_max_num%5;
            $list = array();

            //保存已经获取到的图书ID
            $book_arr = 0;

            //在五大图书分类中随机取
            for($i=0;$i<count($grade_list);$i++){
                $grade_flag = $grade_list[$i]['grade_flag'];

                for ($j=1; $j <= 5; $j++) {
                    if($j == 1) {
                        $avg1 = $avg + $remainder;
                    }else{
                        $avg1 = $avg;
                    }

                    if($grade_flag == 1){
                        $f = " class_2=1 AND book_number > 0 AND book_id NOT IN($book_arr) ";
                    }elseif($grade_flag == 2){
                        $f = " class_3=1 AND book_number > 0 AND book_id NOT IN($book_arr) ";
                    }elseif($grade_flag == 3){
                        $f = " class_4=1 AND book_number > 0 AND book_id NOT IN($book_arr) ";
                    }

                    $sql = "SELECT * FROM `fh_books` WHERE $f and book_id >= ((SELECT MAX(`book_id`) FROM `fh_books`)-(SELECT MIN(`book_id`) FROM `fh_books`)) * RAND() + (SELECT MIN(`book_id`) FROM `fh_books`) ORDER BY book_id LIMIT $avg1";
                    $ret = M()->query($sql);

                    foreach ($ret as $key => $value) {
                        $book_arr .= ','.$value['book_id'];
                    }

                    $list = array_merge_recursive($list,$ret);
                }
            }

            //插入到对应的数据库中
            for ($i=0;$i<count($list);$i++) {
                $class_id = $student_arr[$i];
                $grade_id = $grade_arr[$i];
                $list[$i]['class_name'] = M("class")->where(array('class_id'=>$class_id))->getField("class_name");
                $list[$i]['grade_name'] = M("grade")->where(array('grade_id'=>$grade_id))->getField("grade_name");
                M('schooltobook')->add(array('school_id'=>$id,'class_id'=>$class_id,'grade_id'=>$grade_id,'book_id'=>$list[$i]['book_id']));
                M("books")->where(array('book_id'=>$list[$i]['book_id']))->setDec("book_number",1);//图书减库存
            }

            //设置导出标识
            M('schools')->where(array('school_id'=>$id))->save(array('import_flag'=>1));

        }else{
            //如果导出则直接从schooltobook表中导出对应得图书数据
            $list = M('schooltobook')->
            join("fh_books on fh_books.book_id=fh_schooltobook.book_id")->
            join("fh_class on fh_class.class_id=fh_schooltobook.class_id")->
            join("fh_grade on fh_grade.grade_id=fh_schooltobook.grade_id")->
            join("fh_suppliers on fh_suppliers.sup_id=fh_books.sup_id")->
            field("fh_books.book_name,fh_books.book_isbn,fh_books.shop_price,fh_class.class_name,fh_grade.grade_name,fh_suppliers.sup_name")->
            where("fh_schooltobook.school_id=$id")->
            select();
        }

        $str = "班级名,ISBN,图书名\n";
        $str = iconv('utf-8','gbk',$str);

        foreach ($list as $key => $value) {
            $class_name = iconv('utf-8','gbk',$value['grade_name'].$value['class_name']);
            $book_isbn = iconv('utf-8','gbk',$value['book_isbn']);
            $book_name = iconv('utf-8','gbk','"【'.$value['sup_name'].'】'.$value['sub_name'].' '.$value['book_name'].'"');
            $shop_price = iconv('utf-8','gbk',$value['shop_price'].'元');
            $str .= $class_name.",".$book_isbn.",".$book_name."\n";
        }

        $filename = $schoolname.'的图书列表'.'.csv';
        export_csv($filename,$str);
    }

    public function import_list()
    {
        $id = I('get.id','');
        $age_cate_num = C('AGE_CATE_NUM');      //不同套餐每次借阅书本数

        /*查询学校名称
        查询学校定制套餐:1=>15套餐,2=>20套餐(一周四本),3=>30套餐(一周四本全精装)
        套餐修改：1=>20元套餐(一周两本)，2=>30元套餐(一周四本)
        查询学校班级人数招收上限*/

        $info = M('schools')->where(array('school_id'=>$id))->find();
        $schoolname = $info['school_name'];
        $meal_type = $info['meal_type'];
        $class_max_num = $info['class_max_num'];
        $meal_num = $age_cate_num[$meal_type];      //此套餐一次借阅书本数
        //在班级最大人数的基础上在增加5(20元)/10(30元)本图书，方便老师更换图书操作
        $class_max_num = ($class_max_num * $meal_num) + (5 * $meal_num);


        //计算理论上一个学生一年不重复读书所需的图书种类(按照十个月一学期，40周，一周2本/4本计算)
        $theory_max_num = 40 * 2 * $meal_num;

        //根据每个班级所需的最大图书数量和理论上一年不重复阅读绘本数量去计算需要几个班级循环
        $real_circul_num = ceil($theory_max_num/$class_max_num);    //有余数进一

        //查询年级列表数据
        $grade_list = M("grade")->where(array('school_id'=>$id))->select();
        for($a=0;$a<count($grade_list);$a++){
            //如果已经分配过绘本，则进行其他年级的绘本分配
            $exist = M("class_circul")->where(array('grade_id'=>$grade_list[$a]['grade_id']))->count();
            if($exist > 0) continue;    //假如已经分配，跳过
            $class_ret = array();
            $class_list = M("class")->where(array('grade_id'=>$grade_list[$a]['grade_id']))->order("class_id asc")->select();
            //如果一个年级只有一个班级,则按照理论最大值去给班级配书
            if(count($class_list) == 1){
                array_push($class_ret,$class_list[0]['class_id']);
                $class_ret = $this->arraySlice($class_ret,1);
            }else{
                //根据班级数量去计算每个班级
                if(count($class_list) < $real_circul_num)
                {
                    $realcirculnum = count($class_list);
                    //$booknumber = ceil($theory_max_num / count($class_list));
                }
                else
                {
                    $realcirculnum = $real_circul_num;
                    //$booknumber = $class_max_num;
                }
                //转换为一维数组
                $class_arr = array();
                foreach($class_list as $key=>$value)
                {
                    array_push($class_arr,$value['class_id']);
                }
                //按照程序算出的数据把班级进行分组
                $class_ret = $this->arraySlice($class_arr,$realcirculnum);      //xj 2017-6-20 15:12:57
//				$class_ret = splitArray($class_arr,$group_num);       //卜光辉
            }
            for($i=0;$i<count($class_ret);$i++)
            {
                $real_book_num = intval($theory_max_num/count($class_ret[$i]));
                if($real_book_num < $class_max_num)     $real_book_num = $class_max_num;
                //计算绘本下次轮换时间
                $month_num = intval(($real_book_num/($meal_num*2))/4);
                $date_time = time();
                $grade_time = strtotime("+".$month_num."months",$date_time);

                M("grade")->where(array('grade_id'=>$grade_list[$a]['grade_id']))->save(array('change_time'=>$grade_time,'change_interval'=>$month_num));
                //查询已经轮换次数
                $temp = M("class_circul")->where(array('school_id'=>$grade_list[$a]['school_id']))->order("circul_num desc")->getField("circul_num");
                $num = $temp ? ++$temp : 1;
                foreach($class_ret[$i] as $k=>$v){
                    if(!$class_ret[$i][$k+1]){//小组内的最后一个班级
                        $pre_class_id = $v;
                        $next_class_id = $class_ret[$i][0];
                    }else{
                        $pre_class_id = $v;
                        $next_class_id = $class_ret[$i][$k+1];
                    }
                    $data = array('school_id'=>$grade_list[$a]['school_id'],
                        'grade_id'=>$grade_list[$a]['grade_id'],
                        'pre_class_id'=>$pre_class_id,
                        'next_class_id'=>$next_class_id,
                        'add_time'=>time(),
                        'book_number'=>$real_book_num,
                        'circul_num'=>$num
                    );
                    M("class_circul")->add($data);  //添加轮换信息
                }
            }
        }
        //判断是否导出
        $import_flag = M('schools')->where(array('school_id'=>$id))->getField('import_flag');
        if(!$import_flag){
            $ret = M("class_circul")->where(array('school_id'=>$id,'flag'=>0))->order("id desc")->select();

            //查询所有的年级
            for($i=0;$i<count($ret);$i++){

                $school_id = $ret[$i]['school_id'];
                $grade_id = $ret[$i]['grade_id'];

                $class_id = $ret[$i]['pre_class_id'];
                $book_number = $ret[$i]['book_number'];
                $circul_num = $ret[$i]['circul_num'];   //轮换分组

                //查询班级名称
                $class_name  = M('class')->where("class_id = $class_id")->getField('class_name');

                //查询同一个分组内的班级分的图书数据(不同分组间可以有相同的图书)
                $arr = M("class_circul")->where(array('grade_id'=>$grade_id,'circul_num'=>$circul_num))->field("pre_class_id")->select();

                $arr_id = array();
                foreach($arr as $k=>$v)
                {
                    array_push($arr_id,$v['pre_class_id']);
                }
                $book_arr = M("schooltobook")->where(array('school_id'=>$school_id,'grade_id'=>$grade_id,'class_id'=>array("in",$arr_id)))->field("book_id")->select();
                $book_ids = 0;
                foreach ($book_arr as $key => $value) {
                    $book_ids .= ','.$value['book_id'];
                }

                $grade_flag = M("grade")->where(array('grade_id'=>$grade_id))->getField("grade_flag");      //grade_flag 哪个年级
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
                $book_no = M('schooltobook')->where("class_id = $class_id")->order('book_no desc')->getfield('book_no');
                $book_no++;

                //插入到对应的数据库中
                for ($k=0;$k<count($list);$k++) {
                    M('schooltobook')->add(
                        array('school_id'=>$school_id,
                            'class_id'=>$class_id,
                            'grade_id'=>$grade_id,
                            'book_id'=>$list[$k]['book_id'],
                            'book_no'=>$book_no+$k
                        )
                    );
                }
            }
            //设置导出标识
            M('schools')->where(array('school_id'=>$id))->save(array('import_flag'=>1));
        }

        //如果导出则直接从schooltobook表中导出对应得图书数据
        $lists = M('schooltobook')
            ->join("fh_books on fh_books.book_id=fh_schooltobook.book_id")
            ->join("fh_class on fh_class.class_id=fh_schooltobook.class_id")
            ->join("fh_grade on fh_grade.grade_id=fh_schooltobook.grade_id")
            ->field("fh_books.sort,fh_books.book_name,fh_books.book_isbn,fh_books.shop_price,fh_class.class_name,fh_grade.grade_name,fh_books.press,fh_schooltobook.book_no")
            ->where("fh_schooltobook.school_id=$id")
            ->order('fh_schooltobook.class_id desc,fh_books.sort asc')
            ->select();
        $str = "班级名,ISBN,图书名,班级编号,目录编号\n";
        $str = iconv('utf-8','gbk',$str);

        foreach ($lists as $key => $value) {
            $class_name = iconv('utf-8','gbk',$value['grade_name'].$value['class_name']);
            $book_isbn = iconv('utf-8','gbk',$value['book_isbn']);
            $book_name = iconv('utf-8','gbk','"【'.$value['press'].'】'.$value['sub_name'].' '.$value['book_name'].'"');
            $book_no = iconv('utf-8','gbk',$value['book_no']);
            $sort = $value['sort'];
            //$shop_price = iconv('utf-8','gbk',$value['shop_price'].'元');
            $str .= "$class_name,$book_isbn,$book_name,$book_no,$sort\n";
        }

        $filename = $schoolname.'的图书列表'.'.csv';
        export_csv($filename,$str);
    }

    //导出图书数据
    public function importlisttemp()
    {
        $id = I('get.id','');
        $schoolname = M('schools')->where(array('school_id'=>$id))->getField('school_name');
        //查询本校中所有年级人数最多的一个
        $student_max_num = M('students')->field("class_id,count(class_id) as total_num")->where(array('school_id'=>$id))->group("class_id")->order("total_num desc")->find();
        //计算班级数量
        //$class_total = M('students')->field("class_id,count(class_id)")->where(array('school_id'=>$id))->group("class_id")->count();

        //判断是否导出
        $f = M('schools')->where(array('school_id'=>$id))->getField('import_flag');

        //查询所有的学生信息
        $students_arr = array();
        $student_list = M("students")->where(array('school_id'=>$id))->select();

        /*for ($i=0;$i<count($student_list);$i++) {
			$tmp[$student_list[$i]['student_id']] = $student_list[$i]['class_id'];
			array_push($students_arr,$tmp);
        }*/

        //查询所有的班级
        $class = M('students')->field("class_id,count(class_id)")->where(array('school_id'=>$id))->group("class_id")->select();
        for($i=0;$i<count($class);$i++)
        {
            for($j=0;$j<intval($student_max_num['total_num']);$j++)
            {
                $student_arr[] = $class[$i]['class_id'];
            }
        }

        if($f < 1){
            //计算总图书数
            $book_max_num = $student_max_num['total_num'] * count($class);

            //判断如果小于80本则重新赋值给80本
            if($book_max_num < 80) $book_max_num = 80;

            //计算平均数
            $avg = floor($book_max_num/5);
            $avg1 = $avg;
            //计算余数
            $remainder = $book_max_num%5;
            $list = array();



            for($i=1;$i<=5;$i++){
                if($i == 1) {
                    $avg1 = $avg + $remainder;
                }else{
                    $avg1 = $avg;
                }
                $sql = "SELECT cate_id,book_id,book_name,sub_name,shop_price,class_2,class_3,class_4 FROM `fh_books` WHERE `cate_id` >= ((SELECT MAX(`cate_id`) FROM `fh_books` WHERE `cate_id`= $i)-(SELECT MIN(`cate_id`) FROM `fh_books` WHERE `cate_id`= $i)) * RAND() + (SELECT MIN(`cate_id`) FROM `fh_books` WHERE `cate_id`= $i)  LIMIT $avg1";
                $ret = M()->query($sql);

                $list = array_merge_recursive($list,$ret);
            }

            //插入到对应的数据库中
            for ($i=0;$i<count($list);$i++) {
                $class_id = $student_arr[$i];
                M('schooltobook')->add(array('school_id'=>$id,'class_id'=>$class_id,'book_id'=>$list[$i]['book_id']));
            }

            //设置导出标识
            //M('schools')->where(array('school_id'=>$id))->save(array('import_flag'=>1));
        }else{
            //如果导出则直接从schooltobook表中导出对应得图书数据
            $list = M('schooltobook')->
            join("fh_books on fh_books.book_id=fh_schooltobook.book_id")->
            join("fh_class on fh_class.class_id=fh_schooltobook.class_id")->
            field("fh_books.book_name,fh_books.shop_price,fh_class.class_name")->
            where(array('school_id'=>$id))->
            select();
        }

        $str = "学校名,班级名,图书名,图书价格\n";
        $str = iconv('utf-8','gb2312',$str);

        foreach ($list as $key => $value) {
            $school_name = iconv('utf-8','gb2312',$schoolname);
            $class_name = iconv('utf-8','gb2312',$value['class_name']);
            $book_name = iconv('utf-8','gb2312','"'.$value['book_name'].$value['sub_name'].'"');
            $shop_price = iconv('utf-8','gb2312',$value['shop_price'].'元');
            $str .= $school_name.",".$class_name.",".$book_name.",".$shop_price."\n";
        }

        $filename = $schoolname.'的图书列表'.'.csv';
        //export_csv($filename,$str);   
    }

    //导入
    public function import()
    {
        $this->display("department/import_school");
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

            $edit_arr['school_name'] = iconv('gb2312','utf-8',$result[$i][0]);
            $edit_arr['school_leader'] = iconv('gb2312','utf-8',$result[$i][1]);
            $edit_arr['leader_mobile'] = $result[$i][2];
            $edit_arr['school_teacher'] = iconv('gb2312','utf-8',$result[$i][3]);
            $edit_arr['teacher_mobile'] = $result[$i][4];
            $edit_arr['school_address'] = iconv('gb2312','utf-8',$result[$i][5]);
            $edit_arr['school_num'] = $result[$i][6];
            $edit_arr['school_desc'] = iconv('gb2312','utf-8',$result[$i][7]);
            $edit_arr['reg_time'] = time();
            M('schools')->add($edit_arr);
        }

        $this->success('导入成功',U('/Schools/index'),1);
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
}