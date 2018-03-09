<?php
namespace mobiles\Controller;
use Think\Controller;
class RotateController extends Controller
{
    public function index()
    {
        $teacher = session('teacher');
        $data = M('rotate_receive r')
            ->join('fh_books b on b.book_id = r.book_id')
            ->where(['r.class_id'=>$teacher['class_id']])
            ->order('r.book_no')
            ->field('r.*,b.book_name,b.book_thumb')
            ->select();
        $this->assign('data',$data);
        $this->assign('teacher_id',$teacher['teacher_id']);
        $this->assign('cid',$teacher['class_id']);
        $this->display();
    }

    public function receive()
    {
        $teacher = session('teacher');
        $cid = $teacher['class_id'];
        $bids = I('post.bids',0);
        $result = M('rotate_receive')->where(['book_id'=>['in',$bids],'class_id'=>$cid])->delete();
        if($result){
            $this->success('操作成功!');
        }else{
            $this->error('操作失败!');
        }
    }

    public function lose()
    {
        $teacher = session('teacher');
        $cid = $teacher['class_id'];
        $bid = I('post.bid',0);
        $data = M('rotate_receive')->where(['class_id'=>$cid,'book_id'=>$bid])->find();
        unset($data['id']);
        $result = M('rotate_lose')->add($data);
        if($result){
            //如果在班级书架，删除
            M('schooltobook')->where(['class_id'=>$cid,'book_id'=>$bid])->delete();
            $result = M('rotate_receive')->where(['class_id'=>$cid,'book_id'=>$bid])->delete();
            if($result){
                //更新学校图书缺失量
                M('schools')->where(['school_id'=>$teacher['school_id']])->setInc('book_lose_num');
                $this->success('操作成功!');
            }else{
                M('rotate_lose')->where(['class_id'=>$cid,'book_id'=>$bid])->delete();
                $this->error('操作失败!');
            }
        }else{
            $this->error('操作失败!');
        }
    }
}