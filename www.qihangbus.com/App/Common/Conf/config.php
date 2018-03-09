<?php
return array(
    'DB_TYPE'   => 'mysql', // 数据库类型
    'DB_HOST'   => '127.0.0.1', // 数据库连接地址
    'DB_NAME'   => 'test', // 数据库名
    'DB_USER'   => 'root', // 数据库用户名
    'DB_PWD'    => 'root', // 数据库密码
    'DB_PORT'   => 3306, // 数据库端口
    'DB_PREFIX' => 'xs_', // 数据库前缀
    'DB_CHARSET'=> 'utf8', // 数据库编码
    'AUTH_CONFIG' => [
        'AUTH_ON' => true, //是否开启权限
        'AUTH_TYPE' => 1, //
        'AUTH_GROUP' => 'xs_auth_group', //用户组
        'AUTH_GROUP_ACCESS' => 'xs_auth_group_access', //用户组规则
        'AUTH_RULE' => 'xs_auth_rule', //规则中间表
        'AUTH_USER' => 'xs_admin'// 管理员表
    ],
    'DEFAULT_FILTER' => 'addslashes', // 默认参数过滤方法 用于I函数...
    /* URL设置 */
    'URL_CASE_INSENSITIVE'  =>  true,   // 默认false 表示URL区分大小写 true则表示不区分大小写
    'URL_MODEL'             =>  1,       // URL访问模式,可选参数0、1、2、3,代表以下四种模式：
    // 0 (普通模式); 1 (PATHINFO 模式); 2 (REWRITE  模式); 3 (兼容模式)  默认为PATHINFO 模式
    'URL_PATHINFO_DEPR'     =>  '/',	// PATHINFO模式下，各参数之间的分割符号
    'URL_PATHINFO_FETCH'    =>  'ORIG_PATH_INFO,REDIRECT_PATH_INFO,REDIRECT_URL', // 用于兼容判断PATH_INFO 参数的SERVER替代变量列表
    'URL_REQUEST_URI'       =>  'REQUEST_URI', // 获取当前页面地址的系统变量 默认为REQUEST_URI
    'URL_HTML_SUFFIX'       =>  'html',  // URL伪静态后缀设置
    'TMPL_PARSE_STRING' => array (
        '__PUBLIC__' => __ROOT__ . '/Public/2017' // 更改默认的/Public 替换规则
    ),
    'LOAD_EXT_CONFIG' => 'site,live',
    'weixin' => array(
        'AppID' => 'wx022eda190d44b4db',
        'AppSecret' => 'e1893686d7f1d12a2ce0206b1a34e162',
        'Token' => 'freehand',
        'signKey' => 'e1893686d7f1d12a2ce0206b1a34e162',
        'mchid' => '1432112902',
    ),
);