<?php
namespace Admin\Controller;
use Think\Controller;

class TimedTaskController extends Controller
{
    public function bookReminder()
    {
        set_time_limit(0);
        $week = date('w');
        if($week == 0){
            $week = 7;
        }

        $schools = M('schools','fh_')->field('school_id,book_reminder_first,book_reminder_second')->select();
        //模板消息
        $msg = [
//            'openid' => 'oKJMMwvwsgQD6o_e7K8KxSY8xnrE',
            '$template_id' => 'X6JXF6sFVQgUG3tyzqiOYrVuBs6zV1COPGEssDWMl8s',
            'url' => '',
            'first' => '亲，为了不影响宝宝的正常借阅，明天一早请记得把上次借阅的图书，让宝宝带回幼儿园呦！',
            'keyword2' => '明天',
            'topcolor' => '#FF0000',
        ];
        $wechat = new WechatController();
        foreach($schools as $k=>$v){

            if($v['book_reminder_first'] == $week || $v['book_reminder_second'] == $week){
                $sql = "select student_id from fh_circulation where school_id = {$v['school_id']} and circul_status = 1 and student_id not in
                        (select student_id from fh_compensate where school_id = {$v['school_id']} and compen_status = 1) group by student_id";
                $data = M()->query($sql);
                foreach($data as $k=>$v1){
                    $temp = M('circulation c','fh_')
                    ->join('left join fh_books b on c.book_id = b.book_id')
                    ->where("c.student_id = {$v1['student_id']} and c.circul_status = 1")
                    ->field('book_name')
                    ->select();
                    $book_name = '';
                    foreach($temp as $v2){
                        $book_name .= '【'.$v2['book_name'].'】';
                    }
                    $msg['keyword1'] = $book_name;
                    $parents = M('students_parent','fh_')->where(['student_id'=>$v1['student_id']])->field('wx_id')->select();
                    foreach($parents as $v3){
                        if($v3['wx_id']){
                            $msg['openid'] = $v3['wx_id'];
                            $wechat->sendTplSMS($msg);
                        }
                    }
                }
            }
        }
    }
}