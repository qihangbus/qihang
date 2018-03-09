<?php
namespace mobiles\Controller;
use Think\Controller;
class AgentController extends Controller {
	public function index()
    {
		$id = session('userid');
        $data = M('schools')->where("agent_id = $id")->select();
        foreach($data as $k=>$v){
            if(time() > $v['semester_one']){//第二学期
                $count = M('students')->where("school_id = {$v['school_id']} and paid_time > {$v['semester_one']}")->count();
            }else{//第一学期
                $count = M('students')->where("school_id = {$v['school_id']} and is_paid = 1")->count();
            }
            //计算学校人数
            $data[$k]['school_num'] = M('students')->where("school_id = {$v['school_id']}")->count();
            $data[$k]['order_percent']  = round($count/$data[$k]['school_num'],2)*100;
        }
        foreach($data as $k=>$v){
            if($v['order_percent'] >= 50){
                unset($data[$k]);
            }
        }
        $this->assign('not_count',count($data));
        $agent = M('agent');
        $data = $agent->where("id = $id")->find();
        $school_count = M('schools')->where("agent_id = $id")->count();
        $this->assign('school_count',$school_count);
		$this->assign("data",$data);
		$this->display();
	}

    public function setting()
    {
        $this->display();
    }

    //退出登录，删除openid
    public function signOut()
    {
        $userid = session('userid');
        $result = M('agent')->where("id = $userid")->setField('openid','');
        $this->success('退出成功','/mobile.php/Oauth');
    }

    public function school()
    {
        $id = session('userid');
        $data = M('schools')->where("agent_id = $id")->order('school_id desc')->select();
        $start_time = strtotime(date('Y-m-d 00:00'));
        foreach($data as $k=>$v){
            if(time() > $v['semester_one']){//第二学期
                $count = M('students')->where("school_id = {$v['school_id']} and paid_time > {$v['semester_one']}")->count();
            }else{//第一学期
                $count = M('students')->where("school_id = {$v['school_id']} and is_paid = 1")->count();
            }
            $data[$k]['school_num'] = M('students')->where("school_id = {$v['school_id']}")->count();
            $data[$k]['order_percent']  = round($count/$data[$k]['school_num'],2)*100;
            //注册人数
            $data[$k]['reg_num'] = M('students_parent')->where(['school_id'=>$v['school_id']])->count();
            //订购人数
            $data[$k]['pay_num'] = $count;
            //今天订购人数
            $data[$k]['pay_today'] = M('students')->where("school_id = {$v['school_id']} and paid_time > $start_time")->count();
        }
        if(I('get.type') == 1){
            foreach($data as $k=>$v){
                if($v['order_percent'] >= 50){
                    unset($data[$k]);
                }
            }
        }
        $this->assign('data',$data);
        $this->assign('type',session('type'));
        $this->display();
    }

    public function rebateDetails()
    {
        $userid = session('userid');
        $school_id = I('get.id');
        $access = M('schools')->where("school_id = $school_id and agent_id = $userid")->find();
        if(!$access){
            $this->error('请不要越权操作！');
        }
        $school = M('schools')->where("school_id = $school_id")->find();
        //计算每天收益
        if($school['meal_type'] == 1){
            $amount_day = ($school['meal_market_price']-15)/30;
        }else{
            $amount_day = ($school['meal_market_price']-22)/30;
        }
        $school['school_num'] = M('students')->where("school_id = $school_id")->count();
        if($school['semester_one'] && $school['semester_one_start']){
            if($school['semester_one'] < time()){//第二学期
                $start = date('Y-m',$school['semester_two_start']);
                for($start;$start<=date('Y-m',$school['semester_two']);$start = date('Y-m',strtotime("$start +1 month"))){
                    $data["$start"]['date'] = $start;
                    $students = M('students')->where("school_id = {$school['school_id']} and paid_time <> 0 and from_unixtime(paid_time,'%Y-%m') <= '$start' and paid_time > {$school['semester_one']}")->select();
                    $amount = 0;
                    foreach($students as $v){
                        if($start == date('Y-m',$school['semester_two'])){
                            $d1 = $school['semester_two'];
                        }else{
                            $d1 = strtotime($start.'-31');
                        }

                        if(date('Y-m',$v['paid_time']) == $start){
                            $d2 = $v['paid_time'];
                        }else{
                            if($v['paid_time'] < $school['semester_two_start'] && $start == date('Y-m', $school['semester_two_start'])){
                                $d2 = $school['semester_two_start'];
                            }else{
                                $d2 = strtotime($start.'-01');
                            }
                        }
                        $days = ceil(($d1 - $d2)/3600/24);
                        $amount += round($days*$amount_day,2);
                    }
                    $count = count($students);
                    $data["$start"]['order_percent'] = round($count/$school['school_num'],2)*100;
                    $data["$start"]['income'] = $amount;
                    $data["$start"]['semester'] = '第二学期';

                }
            }else{//第一学期
                $start = date('Y-m',$school['semester_one_start']);
                for($start;$start<=date('Y-m',$school['semester_one']);$start = date('Y-m',strtotime("$start +1 month"))){
                    $data["$start"]['date'] = $start;
                    $students = M('students')->where("school_id = {$school['school_id']} and paid_time <> 0 and from_unixtime(paid_time,'%Y-%m') <= '$start'")->select();
                    $amount = 0;
                    foreach($students as $v){
                        $d1 = strtotime($start.'-31');

                        if(date('Y-m',$v['paid_time']) == $start){
                            if($v['paid_time'] < $v['try_charge_time']){
                                $d2 = $v['try_charge_time'];
                            }else{
                                $d2 = $v['paid_time'];
                            }
                        }else{
                            if($v['paid_time'] < $school['semester_one_start'] && $start == date('Y-m', $school['semester_one_start'])){
                                if($school['semester_one_start'] < $v['try_charge_time']){
                                    $d2 = $v['try_charge_time'];
                                }else{
                                    $d2 = $school['semester_one_start'];
                                }
                            }else{
                                $d2 = strtotime($start.'-01');
                            }
                        }
                        $days = ceil(($d1 - $d2)/3600/24);
                        $amount += round($days*$amount_day,2);
                    }
                    $count = count($students);
                    $data["$start"]['order_percent'] = round($count/$school['school_num'],2)*100;
                    $data["$start"]['income'] = $amount;
                    $data["$start"]['semester'] = '第一学期';
                }
            }
        }
        $this->assign('data',$data);
        $this->assign('school',$school);
        $this->display();
    }

    //获取消息列表
    public function message()
    {
        $user_id = session('userid');
        $page = I('param.page',1);
        $message = M('message');

        //更新用户中心消息小红点
        M('schools')->where(array('school_id'=>$user_id))->save(array('message_num'=>0));
        $count = $message->where(array('receiver_id'=>$user_id,'user_flag'=>4))->count();
        $page = new \Think\Page($count,15);
        $show = $page->show();
        $list = $message->where(array('receiver_id'=>$user_id,'user_flag'=>4))->order('message_id desc')->limit($page->firstRow.','.$page->listRows)->select();
        $lt = array();
        foreach ($list as $key => $value) {
            $value['sub_message'] = msubstr($value['message'],40,strlen($value['message'])-1);
            $value['message'] = msubstr($value['message'],0,40);
            $lt[date('Y-m-d',$value['sent_time'])]['lt'][] = $value;
        }

        $this->assign('list',$lt);
        $this->assign('page',$show);
        $this->display();
    }


    public function avatar()
    {
        $user_id = session('userid');
        $avatar = M('agent')->where(array('id'=>$user_id))->getField('avatar');
        $this->assign('avatar',$avatar);
        $this->display();
    }

    public function updateAvatar()
    {
        $user_id = session('userid');

        $upload = new \Think\Upload();
        $upload->maxSize = 31457280;
        $upload->exts = array('jpg', 'gif', 'png', 'jpeg');
        $upload->saveName = 'time';
        $info = $upload->upload();

        if (!$info) {
            $this->error('请先选择图片');
//            $avatar = I('post.avatar_old','');
        }else{
            $filename = $info['avatar']['savepath'].$info['avatar']['savename'];
            $image = new \Think\Image();
            $image->open('./Uploads/'.$filename);
            $avatar = 'image_'.$info['avatar']['savename'];

            $thumb_width = 300;
            $thumb_height = 300;
            $image->thumb($thumb_width,$thumb_height)->save("./Uploads/".$info['avatar']['savepath']."$avatar");

            unlink("./Uploads/".$filename);
            $avatar = "/Uploads/".$info['avatar']['savepath'].$avatar;
        }

        $ret = M('agent')->where(array('id'=>$user_id))->save(array('avatar'=>$avatar));
        if($ret){
            $this->success('修改头像成功',U('avatar'));
        }
    }

    //修改密码
    public function editPwd()
    {
        $id = session('userid');
        if(IS_POST){
            $old_pwd = md5(I('post.old_pwd',0));
            $agent = M('agent');
            $type = session('type');
            if($type == 1){
                $pwd = $agent->where(['id'=> $id])->getField('pwd');
                if($pwd != $old_pwd){
                    $this->error('旧密码错误');
                }
                $new_pwd = md5(I('post.new_pwd',0));
                $result = $agent->where(['id'=>$id])->setField('pwd',$new_pwd);
            }else{
                $pwd2 = $agent->where(['id'=> $id])->getField('pwd2');
                if($pwd2 != $old_pwd){
                    $this->error('旧密码错误');
                }
                $new_pwd2 = md5(I('post.new_pwd',0));
                $result = $agent->where(['id'=>$id])->setField('pwd2',$new_pwd2);
            }

            if($result){
                $this->success('修改成功',U('Agent/index'));
            }else{
                $this->error('修改失败');
            }

        }else{
            $this->assign('id',$id);
            $this->display('Agent/editPwd');
        }
    }
}