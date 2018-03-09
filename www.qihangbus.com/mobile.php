<?php
// +----------------------------------------------------------------------
// | ThinkPHP [ WE CAN DO IT JUST THINK ]
// +----------------------------------------------------------------------
// | Copyright (c) 2006-2014 http://thinkphp.cn All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: liu21st <liu21st@gmail.com>
// +----------------------------------------------------------------------

// 应用入口文件

// 检测PHP环境
if(version_compare(PHP_VERSION,'5.3.0','<'))  die('require PHP > 5.3.0 !');



//判断是否在微信浏览器中打开
if(strpos($_SERVER['HTTP_USER_AGENT'],'MicroMessenger') === false) 
{
    header("Location:https://open.weixin.qq.com/connect/oauth2/authorize?appid=wx022eda190d44b4db&redirect_uri=http%3A%2F%2Fwww.qihangbus.com%2Fadmin.php%2FOauth%2Findex%2F&response_type=code&scope=snsapi_base&state=1&connect_redirect=1#wechat_redirect");
    exit;
}

// 开启调试模式 建议开发阶段开启 部署阶段注释或者设为false
define('APP_DEBUG',true);

// 定义应用目

define('APP_PATH','./Application/');

define('BIND_MODULE','mobiles');

// 引入ThinkPHP入口文件
require './ThinkPHP/ThinkPHP.php';

// 亲^_^ 后面不需要任何代码了 就是如此简单