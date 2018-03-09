<?php
namespace Admin\Controller;

class StockController extends CommonController
{
    public function index()
    {
        $value = I('get.value');
        $start = I('get.start');
        $end = I('get.end');
        $where = '1=1';
        if($value){
            $this->assign('value',$value);
            $where .= " and s.name like '%$value%'";
        }
        if($start){
            $this->assign('start',$start);
            $start = strtotime($start);
            $where .=" and s.addtime >= $start";
        }
        if($end){
            $this->assign('end',$end);
            $end = strtotime($end)+86400;
            $where .= " and s.addtime <= $end";
        }
        $stock = M('stock s');
        $count = $stock->where($where)->count();
        $page = new \Think\Page($count,15);
        $show = $page->show();
        $data = $stock
            ->where($where)
            ->order('s.id desc')
            ->select();
        $this->assign('data',$data);
        $this->assign('page',$show);
        $this->display();
    }

    public function edit()
    {
        $id = I('post.id');
        $status = I('post.status');
        $result = M('order')->where("id = $id")->setField('status',$status);
        if($result){
            $this->success('保存成功!');
        }else{
            $this->error('保存失败!');
        }
    }

    public function detail()
    {
        $id = I('get.id');
        $data = M('stock')->find($id);
        $this->assign('data',$data);
        $this->display();
    }

    public function del()
    {
        $id = I('post.id');
        $result = M('stock')->delete($id);
        if($result){
            $this->success('删除成功!');
        }else{
            $this->error('删除失败!');
        }
    }

    public function delFew()
    {
        $ids = I('post.ids');
        $ids = explode(',',$ids);
        $order = M('stock');
        foreach($ids as $v){
            $order->delete($v);
        }
        $this->success('删除成功!');
    }

    public function outExcel()
    {
        $value = I('get.value');
        $start = I('get.start');
        $end = I('get.end');
        $where = '1=1';
        if($value){
            $this->assign('value',$value);
            $where .= " and s.name like '%$value%'";
        }
        if($start){
            $this->assign('start',$start);
            $start = strtotime($start);
            $where .=" and s.addtime >= $start";
        }
        if($end){
            $this->assign('end',$end);
            $end = strtotime($end)+86400;
            $where .= " and s.addtime <= $end";
        }
        $out = M('stock s');
        $data = $out
            ->where($where)
            ->order('s.id desc')
            ->field('name,num,addtime')
            ->select();
        foreach($data as $k=>$v){
            $data[$k]['addtime'] = date('Y-m-d H:i:s', $data[$k]['addtime']);
        }
        $headArr = ['产品名称','数量','添加时间'];
        $leadIn = new LeadInController();
        $leadIn->out('库存',$headArr,$data);
    }

    /**
     * 出库管理
     */
    public function out()
    {
        $value = I('get.value');
        $start = I('get.start');
        $end = I('get.end');
        $where = '1=1';
        if($value){
            $this->assign('value',$value);
            $where .= " and so.name like '%$value%'";
        }
        if($start){
            $this->assign('start',$start);
            $start = strtotime($start);
            $where .=" and so.addtime >= $start";
        }
        if($end){
            $this->assign('end',$end);
            $end = strtotime($end)+86400;
            $where .= " and so.addtime <= $end";
        }
        $in = M('stock_out so');
        $count = $in->where($where)->count();
        $page = new \Think\Page($count,15);
        $show = $page->show();
        $data = $in
            ->where($where)
            ->order('so.id desc')
            ->select();
        $this->assign('data',$data);
        $this->assign('page',$show);
        $this->display();
    }

    public function outLeadin()
    {
        if(!IS_POST){
            $this->display();
        }else {
            $upload = new \Think\Upload();// 实例化上传类
            $upload->maxSize = 6145728;// 设置附件上传大小
            $upload->exts = array('xlsx');// 设置附件上传类型
            $upload->rootPath = './database/excel/'; // 设置附件上传根目录
            // 上传单个文件
            $info = $upload->uploadOne($_FILES['user_excel']);
            if (!$info) {// 上传错误提示错误信息
                $this->error($upload->getError());
            } else {// 上传成功 获取上传文件信息
                $filename = './database/excel/' . $info['savepath'] . $info['savename'];
            }
            $leadin = new LeadInController();
            $arr = $leadin->index($filename);

            foreach($arr as $k=>$v){
                if(empty($v['A'])){
                    unset($arr[$k]);
                }
            }

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
            $this->display('progressbar');
            flush();
            $progress = $pix;
            $stock = M('stock');
            unset($arr[1]);
//            dump($arr);
            foreach ($arr as $v) {
                $data['name'] = $v['A'];
                $data['num'] = $v['B'];
                $data['addtime'] = time();
                $result = $stock->where("name = '{$data['name']}'")->find();
                if($result){
                    if($data['num'] > $result['num']){
                        echo "<script type='text/javascript'>error();updateProgress('《 {$data['name']} 》库存不足,请删除此行及此行以上数据重新上传','$progress%');</script>";
                        exit();
                    }
                    $stock->where("id = {$result['id']}")->setDec('num',$data['num']);
                }else{
                    echo "<script type='text/javascript'>error();updateProgress('库存中没有《 {$data['name']} 》,请删除此行及此行以上数据重新上传','$progress%');</script>";
                    exit();
                }
                $stock_out = M('stock_out');
                $v['C'] && $data['des'] = $v['C'];
                $stock_out->add($data);
                echo "<script type='text/javascript'>updateProgress('已导入$progress%','$progress%');</script>";
                flush(); //将输出发送给客户端浏览器，使其可以立即执行服务器端输出的 JavaScript 程序。
                $progress += $pix;
            } //end foreach
            echo "<script type='text/javascript'>success();updateProgress('出库数据已全部导入成功 !','100%');</script>";
            flush();
        }
    }

    public function outOutExcel()
    {
        $value = I('get.value');
        $start = I('get.start');
        $end = I('get.end');
        $where = '1=1';
        if($value){
            $this->assign('value',$value);
            $where .= " and so.name like '%$value%'";
        }
        if($start){
            $this->assign('start',$start);
            $start = strtotime($start);
            $where .=" and so.addtime >= $start";
        }
        if($end){
            $this->assign('end',$end);
            $end = strtotime($end)+86400;
            $where .= " and so.addtime <= $end";
        }
        $out = M('stock_out so');
        $data = $out
            ->where($where)
            ->order('so.id desc')
            ->field('name,num,des,addtime')
            ->select();
        foreach($data as $k=>$v){
            $data[$k]['addtime'] = date('Y-m-d H:i:s', $data[$k]['addtime']);
        }
        $headArr = ['产品名称','数量','备注','添加时间'];
        $leadIn = new LeadInController();
        $leadIn->out('出库记录',$headArr,$data);
    }

    public function outDelFew()
    {
        $ids = I('post.ids');
        $ids = explode(',',$ids);
        $stock_out = M('stock_out');
        foreach($ids as $v){
            $stock_out->delete($v);
        }
        $this->success('删除成功!');
    }

    public function outDel()
    {
        $id = I('post.id');
        $result = M('stock_out')->delete($id);
        if($result){
            $this->success('删除成功!');
        }else{
            $this->error('删除失败!');
        }
    }

    public function outDetail()
    {
        $id = I('get.id');
        $data = M('stock_out')->find($id);
        $this->assign('data',$data);
        $this->display();
    }

    /**
     * 入库管理
     */
    public function in()
    {
        $value = I('get.value');
        $start = I('get.start');
        $end = I('get.end');
        $where = '1=1';
        if($value){
            $this->assign('value',$value);
            $where .= " and si.name like '%$value%'";
        }
        if($start){
            $this->assign('start',$start);
            $start = strtotime($start);
            $where .=" and si.addtime >= $start";
        }
        if($end){
            $this->assign('end',$end);
            $end = strtotime($end)+86400;
            $where .= " and si.addtime <= $end";
        }
        $in = M('stock_in si');
        $count = $in->where($where)->count();
        $page = new \Think\Page($count,15);
        $show = $page->show();
        $data = $in
            ->where($where)
            ->order('si.id desc')
            ->select();
        $this->assign('data',$data);
        $this->assign('page',$show);
        $this->display();
    }

    public function inLeadin()
    {
        if(!IS_POST){
            $this->display();
        }else {
            $upload = new \Think\Upload();// 实例化上传类
            $upload->maxSize = 6145728;// 设置附件上传大小
            $upload->exts = array('xlsx');// 设置附件上传类型
            $upload->rootPath = './database/excel/'; // 设置附件上传根目录
            // 上传单个文件
            $info = $upload->uploadOne($_FILES['user_excel']);
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
            $this->display('progressbar');
            flush();
            $progress = $pix;
            $stock = M('stock');
            unset($arr[1]);
            foreach ($arr as $v) {
                $data['name'] = $v['A'];
                $data['num'] = $v['B'];
                $data['addtime'] = time();
                $result = $stock->where("name = '{$data['name']}'")->find();
                if($result){
                    $stock->where("id = {$result['id']}")->setInc('num',$data['num']);
                }else{
                    $stock->add($data);
                }
                $stock_in = M('stock_in');
                $v['C'] && $data['des'] = $v['C'];
                $stock_in->add($data);
                echo "<script type='text/javascript'>updateProgress('已导入$progress%','$progress%');</script>";
                flush(); //将输出发送给客户端浏览器，使其可以立即执行服务器端输出的 JavaScript 程序。
                $progress += $pix;
            } //end foreach
            echo "<script type='text/javascript'>success();updateProgress('入库数据已全部导入成功 !','100%');</script>";
            flush();
        }
    }

    public function inOutExcel()
    {
        $value = I('get.value');
        $start = I('get.start');
        $end = I('get.end');
        $where = '1=1';
        if($value){
            $this->assign('value',$value);
            $where .= " and si.name like '%$value%'";
        }
        if($start){
            $this->assign('start',$start);
            $start = strtotime($start);
            $where .=" and si.addtime >= $start";
        }
        if($end){
            $this->assign('end',$end);
            $end = strtotime($end)+86400;
            $where .= " and si.addtime <= $end";
        }
        $in = M('stock_in si');
        $data = $in
            ->where($where)
            ->order('si.id desc')
            ->field('name,num,des,addtime')
            ->select();
        foreach($data as $k=>$v){
            $data[$k]['addtime'] = date('Y-m-d H:i:s', $data[$k]['addtime']);
        }
        $headArr = ['产品名称','数量','备注','添加时间'];
        $leadIn = new LeadInController();
        $leadIn->out('入库记录',$headArr,$data);
    }

    public function inDelFew()
    {
        $ids = I('post.ids');
        $ids = explode(',',$ids);
        $stock_in = M('stock_in');
        foreach($ids as $v){
            $stock_in->delete($v);
        }
        $this->success('删除成功!');
    }

    public function inDel()
    {
        $id = I('post.id');
        $result = M('stock_in')->delete($id);
        if($result){
            $this->success('删除成功!');
        }else{
            $this->error('删除失败!');
        }
    }

    public function inDetail()
    {
        $id = I('get.id');
        $data = M('stock_in')->find($id);
        $this->assign('data',$data);
        $this->display();
    }
}