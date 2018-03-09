<?php
return array(
	//'配置项'=>'配置值'
    'URL_MODEL'             =>  0,//REWRITE模式
    'LANG_SWITCH_ON'       =>   true,//开启语言包功能
    'LANG_AUTO_DETECT' => true, // 自动侦测语言 开启多语言功能后有效
    'LANG_LIST'         => 'zh-cn', // 允许切换的语言列表 用逗号分隔
    'VAR_LANGUAGE'     => 'l', // 默认语言切换变量

    //数据库配置信息预案
    'DB_TYPE'   => 'mysql', // 数据库类型
    'DB_HOST'   => '127.0.0.1', // 服务器地址
    'DB_NAME'   => 'test', // 数据库名
    'DB_USER'   => 'root', // 用户名
    'DB_PWD'    => 'root', // 密码
    'DB_PORT'   => 3306, // 端口
    'DB_PREFIX' => 'fh_', // 数据库表前缀
    'DB_CHARSET'=> 'utf8', // 字符集
    'DB_DEBUG'  =>  TRUE, // 数据库调试模式 开启后可以记录SQL日志 3.2.3新增

    //短信接口账号密码
    'SMS_USER_ID'         =>    '49639',
    'SMS_ACCOUNT'         =>    'xd000921',
    'SMS_PASSWORD'        =>    'xd000921033',

    //定义年龄段
    'AGE_CATE'  => array(1=>'0-3',2=>'3-4',3=>'4-5',4=>'5-6'), 

    //套餐类型
	'AGE_CATE'  => array(1=>'20',2=>'30'),
	'AGE_CATE_NUM'  => array(1=>'1',2=>'2'),

	'INTEGRAL'	=> array(1=>'5',2=>'10',3=>'15',4=>'20',5=>'30'),
	
    //评价语
    'FINISH'    => array(1=>'太棒了,任务完成的很好呀！',2=>'完成的很好呀，继续努力呦！',3=>'任务完成的非常棒！'),
    'UNFINISH'  => array(1=>'任务还未完成，请抓紧时间完成任务吧！',2=>'其他小朋友都已经完成任务了，赶快抓紧时间完成吧！',3=>'你还没有完成任务呦，赶快去完成吧！',4=>'还没有完成任务，加油呦！'),

    //套餐对应的每天钱数
    'SET_MEAL'  => array(1=>"0.5",2=>"0.7",3=>"1"),
	
	//套餐对应的每天钱数
	'AGENCY_TYPE'    => array(1=>'省级代理',2=>'市级代理',3=>'县级代理'),

	//短信宝
	'smsbao' => array(
		'url' => "http://www.smsbao.com/", //短信网关
		'username' => 'qihangbus',		//短信平台帐号
		'password' => 'qaz123,.',	//短信平台密码
	),
	'weixin' => array(
		'AppID' => 'wx022eda190d44b4db',
		'AppSecret' => 'e1893686d7f1d12a2ce0206b1a34e162',
		'Token' => 'freehand',
		'signKey' => 'e1893686d7f1d12a2ce0206b1a34e162',
		'mchid' => '1432112902',
	),
);