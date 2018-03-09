<?php
namespace Freehand\Controller;
use Think\Controller;
class ForumController extends CommonController {
    public function index(){
        $page_size = I('param.page_size',15);
        $this->assign('page_size',$page_size);
        $keywords = I('param.keywords','');
        $this->assign('keywords',$keywords);
        if($keywords){
            $condition = "title like '%$keywords%'";
        }

        $forum = M('forum');
        $count = $forum->where($condition)->count();
        $page = new \Think\Page($count,$page_size);
        $show = $page->diyshow();

        $list = $forum->where($condition)->order('audit_status desc,add_time desc')->limit($page->firstRow.','.$page->listRows)->select();

        $this->assign('list',$list);
        $this->assign('page',$show);
        $this->display("forum/forum");
    }
	
	//审核帖子
	public function audit()
	{
		$id = I('get.id','');
		
		$ret = M("forum")->where(array('forum_id'=>$id))->save(array('audit_status'=>1));
		$this->success('审核通过成功',U('/forum/index'),1);
	}
	
    public function uploads()
    {
        $ueditor_config = json_decode(preg_replace("/\/\*[\s\S]+?\*\//", "", file_get_contents("./Public/ueditor/php/config.json")), true);
        $action = $_GET['action'];
        switch ($action) {
            case 'config':
                $result = json_encode($ueditor_config);
                break;
            /* 上传图片 */
            case 'uploadimage':
                /* 上传涂鸦 */
            case 'uploadscrawl':
                /* 上传视频 */
            case 'uploadvideo':
                /* 上传文件 */
            case 'uploadfile':
                $upload = new \Think\Upload();
                $upload->maxSize = 3145728;
                $upload->exts = array('jpg', 'gif', 'png', 'jpeg');
                $info = $upload->upload();
                if (!$info) {
                    $result = json_encode(array(
                        'state' => $upload->getError(),
                    ));
                } else {
               $url = __ROOT__ . "/Uploads/" . $info["upfile"]["savepath"] . $info["upfile"]['savename'];
                    $result = json_encode(array(
                        'url' => $url,
                        'title' => htmlspecialchars($_POST['pictitle'], ENT_QUOTES),
                        'original' => $info["upfile"]['name'],
                        'state' => 'SUCCESS'
                    ));
                }
                break;
            default:
                $result = json_encode(array(
                    'state' => '请求地址出错'
                ));
                break;
        }
        /* 输出结果 */
        if (isset($_GET["callback"])) {
            if (preg_match("/^[\w_]+$/", $_GET["callback"])) {
                echo htmlspecialchars($_GET["callback"]) . '(' . $result . ')';
            } else {
                echo json_encode(array(
                    'state' => 'callback参数不合法'
                ));
            }
        } else {
            echo $result;
        }
    }

    //添加资讯
    public function add()
    {
        $this->display("forum/forum_info");
    }

    //编辑资讯
    public function edit()
    {
        $id = I('get.id');
        $forum = M('forum')->where(array('forum_id'=>$id))->find();
        $this->assign('info',$forum);
        
        $this->display("forum/forum_info");
    }

    //删除
    public function del(){
        $id = I('get.id');
        $ret = M('forum')->where(array('forum_id'=>$id))->delete();
        if($ret){
            $this->success('删除成功',U('/forum/index'),1);
        }else{
            $this->error('删除失败！');
        }
    }
}