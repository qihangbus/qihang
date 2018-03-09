<?php
namespace Freehand\Controller;
use Think\Controller;
class WeixinController extends Controller {
    public function index(){
        $url = U('mobile.php/Ucenter/index');
        
    	$res = M('config')->where(array('parent_id'=>13))->field('code,value')->select();
    	$options = array();


    	$weixin_options = array('appid','appsecret','token');
    	foreach ($res as $key => $value) {
    		if(in_array($value['code'], $weixin_options)){
    			$options[$value['code']] = $value['value'];
    		}
    	}

		$weObj = new \Vendor\Weixin\Wechat($options);
        $arr = 'http://'.$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];
        $weObj->valid();
		$type = $weObj->getRev()->getRevType();
		$wxid = $weObj->getRev()->getRevFrom();	
        $welcome_msg = M('config')->where(array('id'=>20))->getField('value');
        $welcome_msg = '欢迎关注启航巴士，“启航巴士”亲子绘本共读计划，助力中国家庭亲子教育！
1.想了解绘本，请点击<a href="https://mp.weixin.qq.com/s/csGRbQLTTUaJiChZgv5awA">儿童阅读绘本及绘本的意义</a>或<a href="https://mp.weixin.qq.com/s/Gt00-fAuQ0xE650Sj0gM6g">经典绘本分享</a>；
2.想了解亲子绘本共读计划，请点击<a href="https://mp.weixin.qq.com/s/9sYkydZFhWfthGKz5SiMgA">亲子阅读</a>；
3.想加入亲子绘本共读计划，请点击右下角“借阅平台”；
4.请点击<a href="https://mp.weixin.qq.com/s/tUJaa3pDPjA7QsJ9PpeUWw">一封信</a>查看“启航巴士”给家长的一封信；
参与到“启航巴士”亲子绘本共读计划中，将会给您的孩子一个温暖的童年，为美好的人生奠定基石！';

        $reMsg = empty($welcome_msg) ? '欢迎关注启航巴士' : $welcome_msg;
        
        switch($type) {
            case 'text':
                // $content = $weObj->getRev()->getRevContent();
                $now = date("H");
                if($now<9 || $now>18){
                    $reMsg = '启航巴士的客服时间为:
周一到周五 9:00--18:00';
                    echo $weObj->text($reMsg)->reply();
                    }else{
                        echo $weObj->textKf($reMsg)->reply();
                    }     
                exit;
            case 'event':
                $event = $weObj->getRev()->getRevEvent();
                $content =  json_encode($event);
                break;
            default:
                $reMsg = '欢迎关注启航巴士';
        }
		
		
        $followInfo = $this->getFollowUserInfo($wxid);
        if(!$followInfo or $followInfo['expire_in']-86400<time()){
            $info = $weObj->getUserInfo($wxid);
            if($info) $this->followUser($wxid,$info);
        }

        if ($event['event'] == "subscribe") {
            echo $weObj->text($reMsg)->reply();
            exit;
        }
        if ($event['event'] == "unsubscribe"){
            $this->unFollowUser($wxid);
            exit;
        }
        if ($content == "scancode_waitmsg"){
            $content = "扫码带提示：类型 ".$weObj->ScanCodeInfo->ScanType." 结果：".$weObj->ScanCodeInfo->ScanResult;
            echo $weObj->text($content)->reply();
            exit;
        }

        //查询用户输入是否为指定命令
        /*if($type == "text"){
            $userKey = $this->keywordsToKey($content,&$diy_type);
            if($userKey) $event = array('event'=>'CLICK','key'=>$userKey);
        }*/
        
		
		
        //判断用户是否点击的菜单
        if ($event['event'] == "CLICK"){
            $content = $event['key'];
			
			//$this->wx_log("wx_log.txt",var_export($content,true));
			
            /*if(count($event) == 5)
            {
                $userKey = $this->keywordsToKey($content,&$diy_type);
            }*/
			
			//$this->wx_log("wx_log.txt",var_export($content,true));
			
            switch($content){
				case "about":
                    $newsData = array(array('Title'=>'关于启航巴士','Description'=>'“启航巴士”是通过微信公众号，随时供园长、管理员、教师、家长使用的家园共育幼儿亲子图书借阅的平台','PicUrl'=>'http://www.qihangbus.com/Public/images/mobiles/login_logo.png','Url'=>$_SERVER['HTTP_HOST'].'/admin.php/Pages/index'));
					echo $weObj->news($newsData)->reply();
                break;
				case "list":
                    $newsData = array(array('Title'=>'启航巴士|产品介绍&家长端操作指南','Description'=>'启航巴士|产品介绍&家长端操作指南','PicUrl'=>'http://www.qihangbus.com/Public/images/mobiles/login_logo.png','Url'=>$_SERVER['HTTP_HOST'].'/admin.php/Pages/parents'));
					echo $weObj->news($newsData)->reply();
                break;
				case "kefu":
					$kefu_msg = M('config')->where(array('id'=>27))->getField('value');
                    //$this->wx_log("wx_log.txt",var_export(M('config')->getlastsql(),true));
                    echo $weObj->text($kefu_msg)->reply();
                break;	
				case "bbs":
					$url = U('sns.php/Index/index',array('fake_id'=>$wxid));
					header("Location:$url");exit;
                break;	
				default:
					echo $weObj->text("未定义菜单事件{$content}")->reply();
				break;
            }
            echo $weObj->text("未定义菜单事件{$content}")->reply();exit;
        }
    }

    function getstr($str){
        return htmlspecialchars($str,ENT_QUOTES);
    }

    //匹配用户输入是否为系统设置命令
    function keywordsToKey($keys,&$diy_type){
        $keys = $this->getstr($keys);

        $rs = M('weixin_keywords')->where("`keys` like '%{$keys}%' or `key`='{$keys}'")->find();
        if($rs['key']){
            $clicks = $rs['clicks'] + 1;
            M('weixin_keywords')->where(array('id'=>$rs['id']))->save(array('clicks'=>$clicks));
            $diy_type = $rs['diy_type'];
            if($diy_type > 0) $rs['key'] = $rs['diy_value'];
            return $rs['key'];
        }
        return false;
    }

    //查询用户信息
    function getFollowUserInfo($wxid){
        return M('weixin_user')->where(array('fake_id'=>$wxid))->find();
    }

    //关注
    function followUser($wxid,$info=array()){
        $nickname = $info['nickname'];
        $sex = intval($info['sex']);
        $country = $info['country'];
        $province = $info['province'];
        $city = $info['city'];
        $access_token = $info['access_token'];
        $headimgurl = $info['headimgurl'];
        $expire_in = time()+48*3600;
        $id = M('weixin_user')->where(array('fake_id'=>$wxid))->getField('uid');
        $from_id = intval($_GET['id']) > 0 ? intval($_GET['id']) : 1 ;
        if($id>0){
            $set = array();
            $set['from_id'] = $from_id;
            $set['isfollow'] = 1;
            if($info){
                $set['nickname'] = $nickname;
                $set['sex'] = $sex;
                $set['country'] = $country;
                $set['province'] = $province;
                $set['city'] = $city;
                $set['access_token'] = $access_token;
                $set['expire_in'] = $expire_in;
                $set['headimgurl'] = $headimgurl;
            }
            M('weixin_user')->where(array('uid'=>$id))->save($set);
        }else{
            $data = array();

            $createtime = time();
            $createymd = date('Y-m-d');
            $data['fake_id'] = $wxid;
            $data['createtime'] = $createtime;
            $data['createymd'] = $createymd;
            $data['isfollow'] = 1;
            $data['nickname'] = $nickname;
            $data['sex'] = $sex;
            $data['country'] = $country;
            $data['province'] = $province;
            $data['city'] = $city;
            $data['access_token'] = $access_token;
            $data['expire_in'] = $expire_in;
            $data['headimgurl'] = $headimgurl;
            $data['from_id'] = $from_id;
            M('weixin_user')->add($data);
        }
        return true;
    }

    //取消关注
    function unFollowUser($wxid){
        M('weixin_user')->where(array('fake_id'=>$wxid))->save(array('isfollow'=>0,'expire_in'=>0));
        return true;
    }

    //打印日志
    function wx_log($file,$txt)
    {
       $fp =  fopen($file,'ab+');
       fwrite($fp,'-----------'.date('Y-m-d H:i:s').'-----------------');
       fwrite($fp,$txt);
       fwrite($fp,"\r\n\r\n\r\n");
       fclose($fp);
    }
}