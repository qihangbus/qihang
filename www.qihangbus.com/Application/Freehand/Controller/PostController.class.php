<?php
/**
 * Created by PhpStorm.
 * User: ZHOU
 * Date: 2017/9/11/011
 * Time: 9:58
 */
namespace Freehand\Controller;
use Think\Controller;

class PostController extends CommonController{

    //获取学校列表
    public function index(){
        $count = M('schools')->count();
        $Page = new \Think\Page($count,5);// 实例化分页类 传入总记录数和每页显示的记录数
        $show = $Page->diyshow();// 分页显示输出
        $schools = M('schools')->field('province_name,city_name,district_name,school_name,
        school_id,school_address')->limit($Page->firstRow.','.$Page->listRows)->select();
        foreach($schools as $key=>$val){
            $schools[$key]['forum'] = M('forum')->where(array('school_id'=>$val['school_id']))->count();
        }
        $this->assign('schools',$schools);
        $this->assign('page',$show);
        $this->display();

    }
/*    //加载帖子数据列表
    public function show(){
        $id = I('id');
        $data = M('forum')->order('add_time desc')->where("school_id=$id")->select();
        $this->assign('data',$data);
        $this->display();
    }*/
    //加载帖子数据列表
    public function show(){
        $id = I('id');   //GET 学校ID
        //分页    查询发帖用户 内容信息 评论数量  点赞数
        $count = M('forum')->where(['school_id'=>$id])->count();
        $Page = new \Think\Page($count,5);// 实例化分页类 传入总记录数和每页显示的记录数
        $show = $Page->diyshow();// 分页显示输出
        $user = M('forum')->limit($Page->firstRow.','.$Page->listRows)->where("school_id=$id")->select();
        foreach($user as $k=>$v){
            switch($v['user_flag']){
                // 1家长    2老师   3园长   4图书管理员   0 后台管理
                case 1:
                    $join = 'left join fh_students_parent p on p.parent_id = f.user_id';
                    $field = 'p.parent_avatar as avatar,p.parent_name as name';
                    break;
                case 2:
                    $join = 'left join fh_teacher t on t.teacher_id = f.user_id';
                    $field = 't.teacher_avatar as avatar,t.teacher_name as name';
                    break;
                case 3:
                    $join = 'left join fh_schools s on s.school_id = f.user_id';
                    $field = 's.teacher_avatar as avatar,s.school_leader as name';
                    break;
                case 4:
                    $join = 'left join fh_admins a on a.admin_id = f.user_id';
                    $field = 'a.admin_avatar as avatar,a.admin_name as name';
                    break;
                default:
                    $join = 'left join fh_users u on u.uid = f.user_id';
                    $field = 'u.nickname as name';
                    break;
            }
            $data[$k] = M('forum f')
                ->field("f.forum_id,f.user_flag,f.title,f.add_time,f.description,f.zan,f.is_top,f.audit_status,$field")
                ->join($join)
                ->where(['forum_id'=> $v['forum_id']])
                ->find();
            $data[$k]['count'] = M('forum_comment')->where(['forum_id'=>$v['forum_id']])->count();
        }
        $this->assign('page',$show);
        $this->assign('data',$data);
        $this->display();
    }

    //查看评论信息
    public function comment(){
        $id = I('id');
        //获取该帖子及发帖用户信息
        $user_flag = M('forum')->where("forum_id=$id")->getField('user_flag');
        if($user_flag == 1) {
            $join = 'left join fh_students_parent p on p.parent_id = f.user_id';
            $field = 'p.parent_avatar as avatar,p.parent_name as name';
        }elseif ($user_flag == 2) {
            $join = 'left join fh_teacher t on t.teacher_id = f.user_id';
            $field = 't.teacher_avatar as avatar,t.teacher_name as name';
        }elseif ($user_flag == 3) {
            $join = 'left join fh_schools s on s.school_id = f.user_id';
            $field = 's.teacher_avatar as avatar,s.school_leader as name';
        }elseif ($user_flag == 4) {
            $join = 'left join fh_admins a on a.admin_id = f.user_id';
            $field = 'a.admin_avatar as avatar,a.admin_name as name';
        }else{
            $join = 'left join fh_users u on u.uid = f.user_id';
            $field = 'u.nickname as name';
        }
        $forum = M('forum f')
            ->field("f.forum_id,f.user_id,f.user_flag,f.title,f.add_time,f.description,f.content,$field")
            ->join($join)
            ->where(['forum_id'=>$id])
            ->find();
        $forum['content'] = htmlspecialchars_decode($forum['content']);
        $this->assign('info',$forum);
        //加载评论列表用户信息
        $forum_comment = M('forum_comment')->where(array('forum_id'=>$id))->select();
        foreach($forum_comment as $k=>$v ){
            switch($v['user_flag']){
                // 1家长    2老师   3园长   4图书管理员   0 后台管理
                case 1:
                    $join = 'left join fh_students_parent p on p.parent_id = f.user_id';
                    $field = 'p.parent_avatar as avatar,p.parent_name as name';
                    break;
                case 2:
                    $join = 'left join fh_teacher t on t.teacher_id = f.user_id';
                    $field = 't.teacher_avatar as avatar,t.teacher_name as name';
                    break;
                case 3:
                    $join = 'left join fh_schools s on s.school_id = f.user_id';
                    $field = 's.teacher_avatar as avatar,s.school_leader as name';
                    break;
                case 4:
                    $join = 'left join fh_admins a on a.admin_id = f.user_id';
                    $field = 'a.admin_avatar as avatar,a.admin_name as name';
                    break;
                default:
                    $join = 'left join fh_users u on u.uid = f.user_id';
                    $field = 'u.nickname as name';
                    break;
            }
            $data[$k] = M('forum_comment f')
                ->field("f.forum_id,f.user_flag,f.add_time,f.content,$field")
                ->join($join)
                ->where(['fc_id'=> $v['fc_id']])
                ->find();
        }
        $this->assign("data",$data);
        $this->display();
    }





    //编辑
    public function edit(){
        if(IS_POST){
            $school_id = I('school_id');
            $forum_id = I('forum_id');
            $data = [
                'school_id' => $school_id,
                'forum_id' => $forum_id,
                'user_id' => session('uid'),
                'user_flag' => 0,
                'title'=>I('title'),
                'description' => I('description'),
                'add_time' => time(),
                'content' => I('content'),
                'is_top' => I('is_top'),
                'is_recommend' => I('is_recommend')
            ];
            M('forum')->where(array('forum_id'=>$forum_id,'scholl_id'=>$school_id))->save($data);
            if($_FILES['img']['tmp_name'] != ''){
                $upload = new \Think\Upload();// 实例化上传类
                $upload->maxSize   =     3145728 ;// 设置附件上传大小
                $upload->exts      =     array('jpg', 'gif', 'png', 'jpeg');// 设置附件上传类型
                $info   =   $upload->upload();
                if($info) {
                    // 上传成功
                    $pic = [
                        'forum_id' => $forum_id,
                        'fp_pic' => 'Uploads/'.$info['img']['savepath'].$info['img']['savename']
                    ];
                    //存在更新
                    //不存在添加
                    $result = M('forum_pic')->where(['forum_id'=>$forum_id])->find();
                    if($result){
                        M('forum_pic')->where(['forum_id'=>$forum_id])->save($pic);
                    }else{
                        M('forum_pic')->data($pic)->add();
                    }

                }else{
                    $this->error($upload->getError());
                    exit();
                }
            }
            $this->success('提交成功',U('Post/show',array('id'=>$school_id)),2);
        }else {
            $id = I('id');
            $data = M('forum f')
                ->field('f.*,p.fp_pic')->join('left join fh_forum_pic p on p.forum_id=f.forum_id')
                ->where("f.forum_id=$id")->find();
            $this->assign('data', $data);
            $this->display('Post/edit');
        }
    }

    //发帖
    public function send(){
        if(IS_POST){
            $school_id = I('school_id');
            //根据GET school_id 判断是否是一键发帖
            if ($school_id){
                //单个学校发帖
                $data = [
                    'user_id' => session('uid'),
                    'user_flag' => 0,
                    'school_id' => $school_id,
                    'title'=>I('title'),
                    'description' => I('description'),
                    'add_time' => time(),
                    'content' => I('content'),
                    'is_top' => I('is_top'),
                    'is_recommend' => I('is_recommend'),
                    'audit_status' => 1
                ];
                $res = M('forum')->data($data)->add();
                if($_FILES['img']['tmp_name'] != ''){
                    $upload = new \Think\Upload();// 实例化上传类
                    $upload->maxSize   =     3145728 ;// 设置附件上传大小
                    $upload->exts      =     array('jpg', 'gif', 'png', 'jpeg');// 设置附件上传类型
                    $info   =   $upload->upload();
                    if($info) {
                        // 上传成功
                        $pic = [
                            'forum_id' => $res,
                            'fp_pic' => 'Uploads/'.$info['img']['savepath'].$info['img']['savename']
                        ];
                        M('forum_pic')->data($pic)->add();
                    }else{
                        $this->error($upload->getError());
                        exit();
                    }
                }
                $this->success('提交成功!',U('Post/show',array('id' => $school_id)),2);

            }else{
                $content = I('content');
                //一键发帖
                $school_id = M('schools')->field('school_id')->select();
                if($_FILES['img']['tmp_name'] != ''){
                    $upload = new \Think\Upload();// 实例化上传类
                    $upload->maxSize   =     3145728 ;// 设置附件上传大小
                    $upload->exts      =     array('jpg', 'gif', 'png', 'jpeg');// 设置附件上传类型
                    $info   =   $upload->upload();
                }
                //循环添加学校帖子数据  替换学校名 描述
                foreach($school_id as $val){
                    $school_data = M('schools')->where(['school_id'=>$val['school_id']])->field('school_name,
                    school_desc')->find();
                    $con = str_replace(['{学校名称}','{学校描述}'],[$school_data['school_name'],$school_data['school_desc']],$content);
                    $data = [
                        'user_id' => session('uid'),
                        'user_flag' => 0,
                        'school_id' => $val['school_id'],
                        'title'=> I('title'),
                        'description' => I('description'),
                        'add_time' => time(),
                        'content' => $con,
                        'is_top' => I('is_top'),
                        'is_recommend' => I('is_recommend'),
                        'audit_status' => 1
                    ];
                    $res = M('forum')->data($data)->add();
                    if($info) {
                        // 上传成功
                        $pic = [
                            'forum_id' => $res,
                            'fp_pic' => 'Uploads/'.$info['img']['savepath'].$info['img']['savename']
                        ];
                        M('forum_pic')->data($pic)->add();
                    }

                }
                $this->success('提交成功!',U('Post/index'),2);
            }

        }else{
            $status = I('status');
            if($status !== 'all'){
                $school_id = I('school_id');
                $this->assign('school_id',$school_id);
            }
            $this->display('Post/info');
        }

    }
    //删除
    public function del(){
        $id = I('id');
        //删除帖子 查看图片表是否有数据删除
        $school_id = M('forum')->where("forum_id=$id")->getField('school_id');
        $del = M('forum')->where(array('forum_id'=>$id))->delete();
        if($del){
            $fpid = M('forum_pic')->where(['forum_id'=>$id])->getField('fp_id');
            if($fpid){
                M('forum_pic')->where(array('fp_id'=>$fpid))->delete();
                $this->success('操作成功!',U('Post/show',array('id'=>$school_id)),2);
            }else{
                $this->success('操作成功!',U('Post/show',array('id'=>$school_id)),2);
            }
        }
    }


}