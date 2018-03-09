<?php
namespace Admin\Controller;

class BookController extends CommonController
{
    public function index()
    {
        $value = I('get.value');
//        $where = 'sort<>0';
        $where = '1=1';
        if($value){
            $this->assign('value',$value);
            $where .= " and b.book_name like '%$value%'";
        }
        $stock = M('books b','fh_');
        $count = $stock->where($where)->count();
        $page = new \Think\Page($count,15);
        $show = $page->show();
        $data = $stock
            ->where($where)
            ->order('sort desc')
            ->limit("$page->firstRow,$page->listRows")
            ->select();
        $this->assign('data',$data);
        $this->assign('page',$show);
        $this->display();
    }

    public function edit()
    {
        if(IS_POST){
            $data=I('post.');
            $id=I('id');
            unset($data['id']);
            $data['content'] = I('post.content','','htmlspecialchars');
            if($_FILES['image_upload']){
                $upload = new \Think\Upload();// 实例化上传类
                $upload->maxSize   =     3145728 ;// 设置附件上传大小
                $upload->exts      =     array('jpg', 'gif', 'png', 'jpeg');// 设置附件上传类型
                $upload->rootPath  =      './Uploads/'; // 设置附件上传根目录
                // 上传单个文件
                $info   =   $upload->uploadOne($_FILES['image_upload']);
                if(!$info) {// 上传错误提示错误信息
                    $this->error($upload->getError());
                }else{// 上传成功 获取上传文件信息
                    $data['book_thumb'] = '/Uploads/'.$info['savepath'].$info['savename'];
                }
            }
            unset($data['image_upload']);
            $data["{$data['class']}"] = 1;
            unset($data['class']);
            unset($data['content']);
            $data['contents'] = I('post.contents','','htmlspecialchars');
            $data['book_img'] = $data['book_thumb'];
            $books=M('books','fh_');
            $res=$books->where(array('book_id'=>$id))->save($data);
            if($res){
                $this->success('修改成功',U('index'));
            }else{
                $this->error('修改失败');
            }
        }else{
            $id = I('get.id',0);
            $data = M('books','fh_')->where(['book_id'=>$id])->find();
            $data['contents'] = htmlspecialchars_decode($data['contents']);
            $this->assign('data',$data);
            $this->display();
        }
    }

    public function uploadImage(){
        $config = array(
            'maxSize'    =>    3145728,
            'rootPath'   =>    './Uploads/',
            'savePath'   =>    '',
            'saveName'   =>    array('uniqid',''),
            'exts'       =>    array('jpg', 'gif','png', 'jpeg'),
            'autoSub'    =>    true,
            'subName'    =>    array('date','Ymd'),
        );
        $upload = new \Think\Upload($config);// 实例化上传类
        $info   =   $upload->uploadOne($_FILES['upfile']);
        if(!$info) {// 上传错误提示错误信息
            $info['state']=$upload->getError();
            echo json_encode($info);
//            $this->error($upload->getError());
        }else{// 上传成功 获取上传文件信息
            $info['url']=$info['savepath'].$info['savename'];
            $info['state']='SUCCESS';
            echo json_encode($info);
        }
    }

    public function del(){
        $id=I('post.id');
        $res=M('books','fh_')->where(array('book_id'=>$id))->delete();
        if (!$res){
            $this->error('参数不正确',0,0);
        }else {
            $this->success('删除成功',U('index'));
        }
    }

    public function delFew(){
        $ids = I('post.ids');
        $map['book_id'] = array('in',$ids);
        M('books','fh_')->where($map)->delete();
        $this->success('删除成功!',U('index'));
    }

    public function outExcel()
    {
        $data = M('books b','fh_')
            ->order('b.class_2,b.class_3,b.class_4,sort')
            ->select();
        foreach($data as $k=>$v){
            $temp['name'] = $v['book_name'];
            $temp['press'] = $v['press'];
            $temp['isbn'] = $v['book_isbn'];
            if($v['class_2'] == 1){
                $temp['grade'] = '小班';
            }elseif($v['class_3']  == 1){
                $temp['grade'] = '中班';
            }elseif($v['class_4'] == 1){
                $temp['grade'] = '大班';
            }
            $books[] = $temp;
        }

        $headArr = ['绘本名称','出版社','ISBN','所属年级'];
        $leadIn = new LeadInController();
        $leadIn->out('绘本目录',$headArr,$books);
    }

    public function add()
    {
        if(IS_POST){
            $isbn = I('post.book_isbn',0);
            $access = M('books','fh_')->where(['book_isbn'=>$isbn])->find();
            if($access){
                $this->error('ISBN编码重复');
            }
            $data=I('post.');
            $data['content'] = I('post.content','','htmlspecialchars');
            if($_FILES['image_upload']){
                $upload = new \Think\Upload();// 实例化上传类
                $upload->maxSize   =     3145728 ;// 设置附件上传大小
                $upload->exts      =     array('jpg', 'gif', 'png', 'jpeg');// 设置附件上传类型
                $upload->rootPath  =      './Uploads/'; // 设置附件上传根目录
                // 上传单个文件
                $info   =   $upload->uploadOne($_FILES['image_upload']);
                if(!$info) {// 上传错误提示错误信息
                    $this->error($upload->getError());
                }else{// 上传成功 获取上传文件信息
                    $data['book_thumb'] = '/Uploads/'.$info['savepath'].$info['savename'];
                    $data['book_img'] = $data['book_thumb'];
                }
            }
            unset($data['image_upload']);
            $data["{$data['class']}"] = 1;
            unset($data['class']);
            unset($data['content']);
            $data['contents'] = I('post.contents','','htmlspecialchars');
            $books=M('books','fh_');
            $res=$books->add($data);
            if($res){
                $this->success('添加成功',U('index'));
            }else{
                $this->error('修改失败');
            }
        }else{
            $this->display();
        }
    }

    //导入任务
    public function taskLeadin()
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
            unset($arr[1]);
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
            $book = M('books','fh_');
            $task = M('task','fh_');
            $task_option = M('task_option','fh_');
            foreach ($arr as $v) {
                $book_id = $v['A'];
                $task_title = $v['B'];
                $result = trim($v['C']);
                $option = $v['D'];
                $option = explode(':',$option);
                $access = $book->where(['book_id'=>$book_id])->find();
                if(!$access){
                    continue;
                }
                //查询有没有任务
                $access = $task->where(['task_book_id'=>$book_id])->find();
                if($access){
                    continue;
                }

                $task_data = [
                    'task_title' => $task_title,
                    'task_desc'  => $task_title,
                    'task_award' => 50,
                    'task_type'  => 2,
                    'task_book_id' => $book_id,
                    'edit_time' => time()
                ];
                $task_id = $task->add($task_data);
                if($task_id){
                    $option_data = [
                        'task_id'     => $task_id,
                        'option_type' => 1
                    ];
                    $correct = 0;
                    $option_id = [];
                    foreach($option as $v){
                        $option_data['option_name'] = trim($v);
                        if($option_data['option_name'] == $result){
                            $option_data['correct_value'] = 1;
                            $correct = 1;
                        }else{
                            $option_data['correct_value'] = 0;
                        }
                        $option_id[] = $task_option->add($option_data);
                    }
                    if($correct <> 1){
                        foreach($option_id as $v){
                            $task_option->delete($v);
                        }
                        $task->delete($task_id);
                        echo "<script type='text/javascript'>updateProgress('<font color=\'red\'>问题 $task_title 没有正确答案</font>','$progress%');</script>";
                        exit();
                    }
                }
                echo "<script type='text/javascript'>updateProgress('已导入$progress%','$progress%');</script>";
                flush(); //将输出发送给客户端浏览器，使其可以立即执行服务器端输出的 JavaScript 程序。
                $progress += $pix;
            }
            echo "<script type='text/javascript'>success();updateProgress('导入成功 !','100%');</script>";
            flush();
            unlink($filename);
        }else{
            $this->display();
        }
    }
}