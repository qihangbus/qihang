<?php
return array(
    /* 语言变量 */
    '_WEBSITE_NAME_'     => '手绘本',

    '_NO_PARENT_MENU_'     => '作为一级菜单',

    //基本配置
    '_CFG_NAME_'    =>  array('web_info'=>'网站信息',
                              'basic'=>'基础设置',
                              'api_token'=>'接口加密TOKEN',
                              'service_qq'=>'客服QQ',
                              'service_tel'=>'客服电话',
                              'integral_name'=>'积分名称',
                              'base_price'=>'订阅价格',
                              'display'=>'显示设置',
                              'auto_generate_gallery'=>'上传商品是否自动生成相册图',
                              'thumb_width'=>'缩略图宽度',
                              'thumb_height'=>'缩略图高度',
                              'image_width'=>'图书图片宽度',
                              'image_height'=>'图书图片高度',
                              'weixin'=>'微信设置',
                              'web_url'=>'跳转地址',
                              'appid'=>'微信(APPID)',
                              'appsecret'=>'微信(SECRET)',
                              'appkey'=>'支付秘钥',
                              'mchid'=>'商户号',
							  'kefu_msg'=>'客服提示语',
                              'token'=>'微信(TOKEN)',
                              'welcome_msg'=>'关注自动回复'),
    '_CFG_DESC_'    =>  array('service_qq'=>'如果您有多个客服的QQ号码，请在每个号码之间使用半角逗号（,）分隔。',
                              'service_tel'=>'如果您有多个客服电话，请在每个号码之间使用半角逗号（,）分隔。',
                              'image_height'=>'如果您的服务器支持GD，在您上传商品图片的时候将自动将图片缩小到指定的尺寸'),
    '_CFG_RANGE_'   =>  array('auto_generate_gallery'=>array('1'=>'是','2'=>'否'),),
);
