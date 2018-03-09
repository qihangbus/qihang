<?php
namespace Admin\Controller;
use Think\Controller;

class ArticleController extends CommonController {
	
    public function index(){
    	$count= M('Article')->count();// 查询满足要求的总记录数
    	$Page= new \Think\Pagediy($count,15);// 实例化分页类 传入总记录数和每页显示的记录数
    	$show= $Page->show();// 分页显示输出
    	$article=M('Article a')
            ->join('left join xs_column c on c.id = a.columnid')
            ->order('a.id desc')
            ->limit($Page->firstRow.','.$Page->listRows)
            ->field('a.*,c.name as column_name')
            ->select();
    	$this->assign('article',$article);
    	$this->assign('page',$show);
		$this->display();
    }
    
    
    public function add(){
        if ($_POST){

            $article=M('Article');
            $data=I('post.');
            $data['content'] = I('content','','htmlspecialchars');
            $data['addtime']=time();
            if($_FILES['image']){
                $upload = new \Think\Upload();// 实例化上传类
                $upload->maxSize   =     3145728 ;// 设置附件上传大小
                $upload->exts      =     array('jpg', 'gif', 'png', 'jpeg');// 设置附件上传类型
                $upload->rootPath  =      './Uploads/'; // 设置附件上传根目录
                // 上传单个文件
                $info   =   $upload->uploadOne($_FILES['image']);
                if(!$info) {// 上传错误提示错误信息
                    $this->error($upload->getError());
                }else{// 上传成功 获取上传文件信息
                    $data['image'] = '/Uploads/'.$info['savepath'].$info['savename'];
                }
//                $image = new \Think\Image();
//                $image->open($data['image']);
//                $image->thumb(200, 130)->save($data['image']);
            }
            $res=$article->add($data);
            $this->success('添加成功','index');
        }else {

            $column=M('column');
            $nav = new \Org\Util\Leftnav;
            $column_next=$column->where('type = 1')-> order('sort') -> select();
            $arr = $nav::column($column_next);
            $this->assign('column',$arr);
            $this->display();
        }
    }
    
    
    public function release_article(){
        $id=I('get.id');
        $article=M('Article');
        $content=$article->where(array('id'=>$id))->getField('content');
        $content=htmlspecialchars_decode($content);
        $this->assign('content',$content);
        $this->display();
    }
    
    
    public function edit(){
        if (IS_POST){
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
                    $data['image'] = '/Uploads/'.$info['savepath'].$info['savename'];
                }
//                $image = new \Think\Image();
//                $image->open('.'.$data['image']);
//                $image->thumb(200, 130)->save('.'.$data['image']);
            }
            $article=M('Article');
            $res=$article->where(array('id'=>$id))->save($data);
            if($res){
                $this->success('修改成功',U('index'));
            }else{
                $this->error('修改失败');
            }
        }else{
            $id=I('get.id','');
            $column=M('column');
            $nav = new \Org\Util\Leftnav;
            $column_next=$column->where('type <> 2 and type <> 3')-> order('sort') -> select();
            $arr = $nav::column($column_next);

            //查询文章数据
            $article=M('Article');
            $res=$article->where(array('id'=>$id))->find();
            $res['content'] = htmlspecialchars_decode($res['content']);
            $this->assign('res',$res);
            $this->assign('column',$arr);
            $this->display();
        }
    }
    
    public function del(){
        $id=I('get.id');
        $res=M('Article')->where(array('id'=>$id))->delete();
        if (!$res){
            $this->error('参数不正确',0,0);
        }else {
            $this->success('删除成功',U('index'));
        }
    }
    
    public function delFew(){
        $ids = I('post.ids');
        $map['id'] = array('in',$ids);
        M('article')->where($map)->delete();
        $this->success('删除成功!',U('index'));
    }
    
    
    
    
    public function upload_file(){
        
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
        $info   =   $upload->uploadOne($_FILES['file_url']);
        
        $type = $_REQUEST['type'];
        $callback=$_GET['callback'];
        
        if(!$info) {// 上传错误提示错误信息
            
            $this->error($upload->getError());
            
        }else{// 上传成功 获取上传文件信息
            $file_url=$info['savepath'].$info['savename'];
            
            return $file_url;
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
    
    
    //栏目管理
	public function column(){
		$column=M('column');

		$nav = new \Org\Util\Leftnav;
		$column=$column->order('sort')->select();
		$arr = $nav::column($column);
		$this->assign('arr',$arr);
		$this->display();
	}

    //修改栏目
    public function columnEdit()
    {
        if(IS_POST){
            $data = I('post.');
            $data['status'] || $data['status'] = 0;
            $id = $data['id'];
            unset($data['id']);
            $data['content'] = I('post.content','','htmlspecialchars');
            $result = M('column')->where("id = $id")->save($data);
            if($result){
                $this->success('修改成功！',U('column'));
            }else{
                $this->error('修改失败！');
            }

        }else{
            $id = I('get.id');
            $data = M('column')->find($id);
            $data['content'] = htmlspecialchars_decode($data['content']);
            $this->assign('data',$data);
            $this->display();
        }
    }
    
	//添加栏目
	public function columnAdd(){
		$column_leftid=I('id',0);
		$this->assign('column_leftid',$column_leftid);
		$this->display();
	}
	
	public function runColumnAdd(){
		if (!IS_AJAX){
			$this->error('提交方式不正确',U('article/column'),0);
		}else{
			$data=array(
					'name'=>I('name'),
					'en_name'=>I('en_name'),
					'type'=>I('type'),
					'column_leftid'=>I('column_leftid'),
					'jump_url'=>I('jump_url'),
					'status'=>I('status',0),
					'sort'=>I('sort'),
					'seo_title'=>I('seo_title'),
					'seo_key'=>I('seo_key'),
					'seo_des'=>I('seo_des'),
					'content'=>I('content'),
			);
			M('column')->add($data);
			$this->success('栏目保存成功',U('column'),1);
		}
	}
	
	//删除栏目
	public function columnDel(){
		$id = I('id');
		$access = M('column')->where(array('column_leftid'=>$id))->find();
		if($access){
			$this->error('请先删除子栏目!');
		}
		$column=M('column')->where(array('id'=>$id))->delete();
        $this->success('删除成功！',U('column'));
	}

	//批量删除
	public function batchDel(){
		$ids = I('post.ids');
		$map['id'] = array('in',$ids);
		M('column')->where($map)->delete();
		$this->success('删除成功!',U('column'));
	}

    //排序
    public function columnOrder()
    {
        $data = I('post.data');
        foreach($data as $v){
            $id = strstr($v,'-',ture);
            $sort = strstr($v,'-');
            $sort = substr($sort,1);
            M('column')->where("id = $id")->save(['sort'=>$sort]);
        }
        $this->success('排序成功！',U('column'));
    }
	
}