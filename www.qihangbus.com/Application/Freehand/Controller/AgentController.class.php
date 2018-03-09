<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/7/19/019
 * Time: 17:38
 */
namespace Freehand\Controller;
class AgentController extends CommonController
{
    public function index()
    {
        $agent = M('agent');
        $count = $agent->count();
        $page = new \Think\Page($count,15);
        $show = $page->diyshow();
        $list = $agent->order('id desc')->limit($page->firstRow.','.$page->listRows)->select();
        $this->assign('list',$list);
        $this->assign('page',$show);
        $this->display();
    }

    //添加
    public function add()
    {
        if(IS_POST){
            $data['name'] = I('post.name');
            $data['mobile'] = I('post.mobile');
            //查询是否有重复手机号
            $access = M('agent')->where("mobile = '{$data['mobile']}'")->find()
            || $access = M('admins')->where("admin_mobile = '{$data['mobile']}'")->find()
            || $access = M('schools')->where("leader_mobile = '{$data['mobile']}' or teacher_mobile = '{$data['mobile']}'")->find()
            || $access = M('students_parent')->where("parent_mobile = '{$data['mobile']}'")->find()
            || $access = M('teacher')->where("teacher_mobile = '{$data['mobile']}'")->find();
            if($access){
               $this->error('手机号重复！');
            }

            $data['pwd'] = md5(I('post.pwd'));
            $data['province'] = I('post.province');
            $pos = strpos($data['province'],',');
            $data['province'] = substr($data['province'],$pos+1);
            $data['city'] = I('post.city');
            $pos = strpos($data['city'],',');
            $data['city'] = substr($data['city'],$pos+1);
            $data['bank_card'] = I('post.bank_card');
            $data['bank_name'] = I('post.bank_name');
            $result = M('agent')->add($data);
            if($result){
                $this->success('添加成功!',U('index'));
            }else{
                $this->error('添加失败');
            }
        }else{
            $province = M('region')->where('parent_id = 0')->select();
            $this->assign('province',$province);
            $this->display();
        }
    }

    public function getCity()
    {
        $province = I('get.province');
        $province = explode(',',$province);
        $id = $province[0];
        $data = M('region')->where("parent_id = $id")->select();
        $this->ajaxReturn($data);
    }

    public function del()
    {
        $id = I('post.id',0);
        $result = M('agent')->where(array('id'=>$id))->delete();
        if($result){
            $this->success('删除成功！');
        }else{
            $this->error('删除失败！');
        }
    }

    public function edit()
    {
       if(IS_POST){
           $id = I('post.id',0);
           $data['name'] = I('post.name',0);
           I('post.pwd','') && $data['pwd'] = md5(I('post.pwd'));
           $province = I('post.province');
           $pos = strpos($province,',');
           $data['province'] = substr($province,$pos+1);
           $city = I('post.city');
           $pos = strpos($city,',');
           if(!$pos){
               $data['city'] = $city;
           }else{
               $data['city'] = substr($city,$pos+1);
           }
           $data['bank_card'] = I('post.bank_card');
           $data['bank_name'] = I('post.bank_name');
           $result = M('agent')->where("id = $id")->save($data);
           if($result){
               $this->success('修改成功！',U('index'));
           }else{
               $this->error('修改失败');
           }
       }else{
           $id = I('get.id',0);
           $data = M('agent')->where("id = $id")->find();
           $province = M('region')->where('parent_id = 0')->select();
           $this->assign('province',$province);
           $this->assign('data',$data);
           $this->display();
       }
    }

    public function sendMes()
    {
        if(IS_POST){
            $data = array(
                'sender_name' => '系统消息',
                'receiver_id' => I('post.receiver_id'),
                'sent_time' => time(),
                'title' => I('post.title'),
                'message' => I('post.message'),
                'user_flag' => 4,
            );
            $result = M('message')->add($data);
            if($result){
                $this->success('发送成功！');
            }else{
                $this->error('发送失败!');
            }
        }else{
            $receiver_id = I('get.id');
            $this->assign('receiver_id',$receiver_id);
            $this->display();
        }
    }

    //发送消息
    public function send()
    {
        $id = I('get.id');
        $this->assign('id',$id);
        $this->assign('user_flag',2);
        $this->display("member/teacher_message");
    }

    //
    public function message_handle()
    {
        $data = array();
        $data['receiver_id'] = I('post.teacher_id','');
        $data['title'] = I('post.title','');
        $data['message'] = I('post.message','');
        $data['sent_time'] = time();
        $data['user_flag'] = I('post.user_flag','');
        $ret = M('message')->add($data);
        if($ret > 0){
            $this->success('发送成功',U('/Teacher/index'),1);
        }else{
            $this->error('发送失败',U('/Teacher/index'),1);
        }
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
    public function ajax_group()
    {
        $cid = I('post.class_id','');
        $ret = M('class_groups')->where(array('classid'=>$cid))->select();
        echo json_encode($ret);
    }


    //处理会员数据
    public function edit_handle()
    {
        $condition = array();
        $tid = I('post.teacher_id','');

        $condition['school_id'] = I('post.school_id','');
        $condition['grade_id'] = I('post.grade_id','');
        $condition['class_id'] = I('post.class_id','');
        $condition['groupid'] = I('post.group_id',0);
        $condition['teacher_name'] = I('post.teacher_name','');
        $condition['teacher_mobile'] = I('post.teacher_mobile','');

        if($tid > 0){
            $ret = D('teacher')->where(array('teacher_id'=>$tid))->save($condition);
            $this->success('修改成功',U('/Teacher/index'),1);
        }else{
            $ret = D('teacher')->add($condition);
            $this->success('添加成功',U('/Teacher/index'),1);
        }
    }

}