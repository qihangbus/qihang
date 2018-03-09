<?php
namespace mobiles\Controller;

use Think\Controller;

class OauthController extends Controller
{
    public function index()
    {
        $res = M('config')->where(array('parent_id' => 13))->field('code,value')->select();
        $options = array();

        $weixin_options = array('appid', 'appsecret', 'token');
        foreach ($res as $key => $value) {
            if (in_array($value['code'], $weixin_options)) {
                $options[$value['code']] = $value['value'];
            }
        }

        $weObj = new \Vendor\Weixin\Wechat($options);

        if ($_GET['code']) {
            $json = $weObj->getOauthAccessToken();

            if ($json['openid']) {
                $url = M('config')->where(array('id' => 16))->getField('value');
                $url = "$url?fack_id=" . $json['openid'];
                session('openid',$json['openid']);
                header("Location:$url");
                exit;
            }

        }


        $url_base = 'http://' . $_SERVER['HTTP_HOST'] . "/mobile.php/Oauth/index/";
        $url = $weObj->getOauthRedirect($url_base, 1, 'snsapi_base');
        header("Location:$url");
        exit;
    }


//打印日志
    function wx_log($file, $txt)
    {
        $fp = fopen($file, 'ab+');
        fwrite($fp, '-----------' . date('Y-m-d H:i:s') . '-----------------');
        fwrite($fp, $txt);
        fwrite($fp, "\r\n\r\n\r\n");
        fclose($fp);
    }
}