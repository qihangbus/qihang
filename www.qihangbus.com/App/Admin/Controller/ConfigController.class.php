<?php
namespace Admin\Controller;

class ConfigController extends CommonController
{
    public function index()
    {
        $this->display();
    }

    public function siteSave()
    {
        $config = I('post.');
        $result = $this->configSave($config,'site');
        echo $result;
    }

    public function liveSave()
    {
        $config = I('post.');
        $result = $this->configSave($config,'live');
        echo $result;
    }

    public function banner()
    {
        $banner = M('banner');
        $this->data = $banner->order('sort')->select();
        $this->display();
    }

    public function bannerAdd()
    {
        if(IS_POST){
            $data = [];
            $data['name'] = I('post.name');
            $data['sort'] = I('post.sort');
            $data['url'] = I('post.url');
            $data['content'] = I('post.content','','htmlspecialchars');
            $upload = new \Think\Upload();// 实例化上传类
            $upload->maxSize   =     3145728 ;// 设置附件上传大小
            $upload->exts      =     array('jpg', 'gif', 'png', 'jpeg');// 设置附件上传类型
            $upload->rootPath  =      './Uploads/'; // 设置附件上传根目录
            // 上传单个文件
            $info   =   $upload->uploadOne($_FILES['image']);
            if(!$info) {// 上传错误提示错误信息
                $this->error($upload->getError());
            }else{// 上传成功 获取上传文件信息
                $data['image'] = 'http://'.$_SERVER['HTTP_HOST'].'/Uploads/'.$info['savepath'].$info['savename'];
            }
            $data['addtime'] = time();
            $banner = M('banner');
            $result = $banner->add($data);
            if($result){
                $this->success('添加成功!');
            }else{
                $this->error('添加失败!');
            }
        }else{
            $this->display();
        }
    }

    public function bannerEdit()
    {
        $banner = M('banner');
        if(IS_POST){
            $id = I('post.id');
            $data = [];
            $data['name'] = I('post.name');
            $data['sort'] = I('post.sort');
            $data['url'] = I('post.url');
            $data['content'] = I('post.content','','htmlspecialchars');
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
            }
            $result = $banner->where("id = $id")->save($data);
            if($result){
                $this->success('修改成功!');
            }else{
                $this->error('修改失败!');
            }
        }else{
            $id = I('get.id');
            $data = $banner->find($id);
            $data['content'] = htmlspecialchars_decode($data['content']);
            $this->assign('data',$data);
            $this->display();
        }
    }

    public function bannerDel()
    {
        $id = I('post.id');
        $banner = M('banner');
        $result = $banner->delete($id);
        if($result){
            $this->success('删除成功!');
        }else{
            $this->error('删除失败!');
        }
    }

    public function bannerDelFew()
    {
        $ids = I('post.ids');
        $ids = explode(',',$ids);
        $banner = M('banner');
        foreach($ids as $v){
            $banner->delete($v);
        }
        $this->success('删除成功!');
    }

    public function bannerOrder()
    {
        $data = I('post.data');
        $data = explode(',',$data);
        $banner = M('banner');
        foreach($data as $v){
            $id = substr($v,0,1);
            $sort = substr($v,-1,1);
            $banner->where("id = $id")->setField(['sort'=>$sort]);
        }
        $this->success('排序成功!');
    }

    private function configSave($config,$filename)
    {
        $data = '<?php return array(';
        foreach($config as $k=>$v){
            $data .= "'$k' => '$v',";
        }
        $data .= ');';
        $file = @fopen(CONFIG_PATH.$filename.'.php','w');
        $result = fwrite($file,$data);
        if($file)   @fclose($file);
        if($result){
            return json_encode(['info'=>'保存成功!','status'=>1]);
        }else{
            return json_encode(['info'=>'保存失败!','status'=>0]);
        }
    }

}