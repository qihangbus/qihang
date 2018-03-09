<?php
/*
 * ZHOU 09.05
 * */
namespace Freehand\Controller;
use  Think\Controller;
class OrdersController extends CommonController
{
    public function index(){
        $where = '1=1';
        $sn = I('sn');
        $user = I('user');
        $pay_status = I('pay');
        $ship = I('ship');
        if (!empty($sn)){
            $where .= "  and order_sn like '%$sn%'";
        }
        if (!empty($user)){
            $where .= "  and consignee like '%$user%'";
        }
        if ($pay_status){
            $where .= "  and pay_status='$pay_status'-1";
        }
        if ($ship) {
            $where.= "  and shipping_status='$ship'-1";
        }
        $count = M('order_info')->where($where)->count();
        $page = new \Think\Page($count,15);
        $show = $page->diyshow();
        $data = M('order_info')
            ->field('order_id,order_sn,order_status,shipping_status,pay_status,consignee,
        add_time,book_amount')->order('add_time DESC')->limit($page->firstRow.','.$page->listRows)
            ->where($where)->select();
        $this->assign('data',$data);
        $this->assign('page',$show);
        $this->assign('sn',$sn);
        $this->assign('user',$user);
        $this->assign('pay',$pay_status);
        $this->assign('ship',$ship);
        $this->display('orders/index');
    }

    //订单查看修改
    public function info(){
        if(IS_POST){
            $data = array(
                'shipping_status' => 1,
                'invoice_code' => I('invoice_code'),
                'invoice_no' => I('invoice_no'),
                'shipping_time' => time()
            );
            $send = M('order_info')->where(['order_sn'=>I('order_sn')])->save($data);
            if ($send) {
                $this->success('提交成功!');
            }else{
                $this->error('提交失败!');
            }
        }else{
            $id = I('id');
            $book = M('order_goods')->where("order_id=$id")->select();
            foreach ($book as $k => $val){
                $goods[$k] = M('books')->where(['book_id' => $val['book_id']])->find();
                $goods[$k]['book_number'] = $val['book_number'];
            }
            $info = M('order_info')->where(['order_id'=>$id])->find();
            $province = M('region')->where(['region_id'=>$info['province']])->getField('region_name');
            $city = M('region')->where(['region_id'=>$info['city']])->getField('region_name');
            $district = M('region')->where(['region_id'=>$info['district']])->getField('region_name');
            $info['address'] = $province.$city.$district.$info['address'];
            $this->assign('goods',$goods);
            $this->assign('info',$info);
            $this->display('orders/info');
        }

    }

    //订单删除
    public function del(){
        $id = I('id');
        $infodel = M('order_info')->where(['order_id' => $id])->delete();
        if($infodel){
            M('order_goods')->where(['order_id'=>$id])->delete();
            $this->success('操作成功!',U('orders/index'),3);
        }
    }
}