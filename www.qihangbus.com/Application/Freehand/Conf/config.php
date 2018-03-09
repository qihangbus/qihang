<?php
return array(
	//'配置项'=>'配置值'
    'URL_MODEL'             =>  1,//REWRITE模式
    'URL_CASE_INSENSITIVE'  =>  true, 
    'LANG_SWITCH_ON'       =>   true,//开启语言包功能
    'LANG_AUTO_DETECT' => true, // 自动侦测语言 开启多语言功能后有效
    'LANG_LIST'         => 'zh-cn', // 允许切换的语言列表 用逗号分隔
    'VAR_LANGUAGE'     => 'l', // 默认语言切换变量

    'MEDIA_TYPE'        =>  array(1=>'图片'),

    //'TMPL_ACTION_SUCCESS'=>'Public:dispatch_jump',
    //'TMPL_ACTION_ERROR'=>'Public:dispatch_jump',

    //快递编码（参考快递100）
    'KUAIDI'    =>  array(/*'anxindakuaixi'=>'安信达',*/'huitongkuaidi'=>'百世汇通'/*,'baifudongfang'=>'百福东方',
                        'bangsongwuliu'=>'邦送物流','chuanxiwuliu'=>'传喜物流','datianwuliu'=>'大田物流',
                        'debangwuliu'=>'德邦物流','bangsongwuliu'=>'邦送物流','dsukuaidi'=>'D速快递',
                        'disifang'=>'递四方','feikangda'=>'飞康达物流','feikuaida'=>'飞快达','rufengda'=>'凡客如风达',
                        'fenxingtianxia'=>'风行天下','feibaokuaidi'=>'飞豹快递','ganzhongnengda'=>'港中能达',
                        'guotongkuaidi'=>'国通快递','guangdongyouzhengwuliu'=>'广东邮政','gongsuda'=>'共速达',
                        'huitongkuaidi'=>'汇通快运','huiqiangkuaidi'=>'汇强快递','tiandihuayu'=>'华宇物流',
                        '恒路物流'=>'恒路物流','huaxialongwuliu'=>'华夏龙','tiantian'=>'海航天天','haiwaihuanqiu'=>'海外环球',
                        'haimengsudi'=>'海盟速递','huaqikuaiyun'=>'华企快运','haihongwangsong'=>'山东海红',
                        'jiajiwuliu'=>'佳吉物流','bangyiwuliu'=>'佳怡物流','jiayunmeiwuliu'=>'加运美',
                        'jingguangsudikuaijian'=>'京广速递','jixianda'=>'急先达','jinyuekuaidi'=>'晋越快递',
                        'jietekuaidi'=>'捷特快递','jindawuliu'=>'金大物流','jialidatong'=>'嘉里大通',
                        'kuaijiesudi'=>'快捷速递','kangliwuliu'=>'康力物流','kuayue'=>'跨越物流',
                        'lianhaowuliu'=>'联昊通','longbangwuliu'=>'龙邦物流','lanbiaokuaidi'=>'蓝镖快递',
                        'lianbangkuaidi'=>'联邦快递','longlangkuaidi'=>'隆浪快递','menduimen'=>'门对门',
                        'meiguokuaidi'=>'美国快递','mingliangwuliu'=>'明亮物流','quanchenkuaidi'=>'全晨快递',
                        'quanjitong'=>'全际通','quanritongkuaidi'=>'全日通','quanyikuaidi'=>'全一快递','quanfengkuaidi'=>'全峰快递',
                        'sevendays'=>'七天连锁','rufengda'=>'如风达快递','santaisudi'=>'三态速递','shenghuiwuliu'=>'盛辉物流',
                        'suer'=>'速尔物流','shengfengwuliu'=>'盛丰物流','shangda'=>'上大物流','saiaodi'=>'赛澳递',
                        'shenganwuliu'=>'圣安物流','suijiawuliu'=>'穗佳物流','tiandihuayu'=>'天地华宇','tiantian'=>'天天快递',
                        'youshuwuliu'=>'优速快递','wanjiawuliu'=>'万家物流','wanxiangwuliu'=>'万象物流',
                        'xinbangwuliu'=>'新邦物流','xinfengwuliu'=>'信丰物流','xinbangwuliu'=>'新邦物流',
                        'newerrozzo'=>'新蛋奥硕物流','hkpost'=>'香港邮政','yuntongkuaidi'=>'运通快递',
                        'yuanchengwuliu'=>'远成物流','yafengsudi'=>'亚风速递','yibangwuliu'=>'一邦速递',
                        'youshuwuliu'=>'优速物流','yuanweifeng'=>'源伟丰快递','yuanzhijiecheng'=>'元智捷诚',
                        'yuefengwuliu'=>'越丰物流','yuananda'=>'源安达','yuanfeihangwuliu'=>'原飞航',
                        'zhongxinda'=>'忠信达快递','zhimakaimen'=>'芝麻开门','yinjiesudi'=>'银捷速递',
                        'zhaijisong'=>'宅急送','zhongyouwuliu'=>'中邮物流','tiantian'=>'天天快递','zhongtianiwanyun'=>'中天万运'*/),

    //全局变量
    'ADMIN_USER_TYPE'     =>        array(1=>'后台管理',2=>'业务人员',3=>'运营人员'),
    'ADMIN_MENU_ICON'     =>        array(0=>'fa-home',1=>'fa-book',2=>'fa-laptop',3=>'fa-cogs',4=>'fa-laptop',5=>'fa-truck',6=>'fa-pinterest',7=>'fa-envelope',8=>'fa-columns'),
);