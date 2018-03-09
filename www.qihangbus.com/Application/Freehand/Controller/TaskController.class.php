<?php
namespace Freehand\Controller;
use Think\Controller;
class TaskController extends CommonController {
    public function index(){
    	$page_size = I('param.page_size',15);
        $this->assign('page_size',$page_size);
        /*$keywords = I('param.keywords','');
        $this->assign('keywords',$keywords);
        if($keywords){
            $condition['book_name'] = array('like',"%$keywords%");
        }*/

        $task = M('task');
        $count = $task->count();
        $page = new \Think\Page($count,$page_size);
        $show = $page->diyshow();

        $list = $task->order('task_id desc')->order("edit_time asc,task_type desc")->limit($page->firstRow.','.$page->listRows)->select();

        $this->assign('list',$list);
        $this->assign('page',$show);
        $this->display("promotion/task_list");
    }

    //导入任务
    public function importtask()
    {
        $this->display("promotion/importtask");
    }

    //处理导入数据
    public function task_handle()
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
            
            $book_name = iconv('gb2312','utf-8',$result[$i][0]);
            $edit_arr['task_book_id'] = M("books")->where(array("book_name"=>$book_name))->getField("book_id");
            $edit_arr['task_desc'] = $edit_arr['task_title'] = iconv('gb2312','utf-8',$result[$i][1]);
            $edit_arr['task_award'] = 50;
            $edit_arr['task_type'] = 2;
            $option = iconv('gb2312','utf-8',$result[$i][2]);
            
            $task_id = M('task')->add($edit_arr);
            M("task_option")->add(array('task_id'=>$task_id,'option_name'=>$option,'option_type'=>1));
        }

        $this->success('导入成功',U('/Task/index'),1);
    }

    public function add()
    {
        $this->display("promotion/task_info");
    }

    public function edit()
    {
    	$id = I('get.id',0);
    	$info = M('Task')->where(array('task_id'=>$id))->find();

		$options = M("task_option")->where(array('task_id'=>$info['task_id']))->select();
		$this->assign('options',$options);
    	$this->assign('info',$info);
    	$this->display("promotion/task_info");
    }

    public function edit_handle()
    {
		$data = array();
    	$task_id = I('post.task_id',0);
    	$data['task_title'] = I('post.task_title','');
    	$data['task_desc'] = I('post.task_desc','');
        $data['task_award'] = I('post.task_award','');
        $data['task_type'] = I('post.task_type','');
        $data['task_url'] = I('post.task_url','');	
		$data['edit_time'] = time();			
		$option_type = I('post.option_type','');
		$option_name = I('post.option_name','');
		$correct_value = I('post.correct_value','');
    	if($task_id > 0){
    		$msg = "修改成功";
    		$ret = M('Task')->where(array('task_id'=>$task_id))->save($data);
			if(!$ret){
				$ret = M("task_option")->where(array('task_id'=>$task_id))->delete();
			}else{
				M("task_option")->where(array('task_id'=>$task_id))->delete();
			}
    	}else{
    		$msg = "添加成功";
    		$ret = M('Task')->add($data);
			$task_id = $ret;
    	}

    	if($ret){
			
			for($i=0;$i<count($option_type);$i++){
				$options = array();
				$options['task_id'] = $task_id;
				$options['option_type'] = $option_type[$i];
				$options['option_name'] = $option_name[$i];
				$options['correct_value'] = $correct_value[$i];
				M("task_option")->add($options);
			}
			
    		$this->success($msg,U('Task/index'));
    	}else{
    		$this->error("操作失败");
    	}
    }

    public function del()
    {
    	$id = I('get.id',0);
    	$ret = M('Task')->where(array('task_id'=>$id))->delete();
    	if($ret){
    		$this->success('删除成功',U('Task/index'));
    	}else{
    		$this->error("删除失败",U('Task/index'));
    	}
    }
}