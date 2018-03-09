<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>返利明细-<?php echo ($school['school_name']); ?></title>
    <meta name="viewport" content="width=device-width,initial-scale=1, maximum-scale=1, minimum-scale=1, user-scalable=no">
    <link href="/Public/school2017/style.css" rel="stylesheet" type="text/css" />
    <script type="text/javascript" src="/Public/school2017/jquery.min.js"></script>
    <link rel="stylesheet" href="/Public/css/mobiles/iconfont.css">
    <link rel="stylesheet" href="/Public/css/mobiles/font-awesome/css/font-awesome.min.css">
    <style>
        .f_index{ display:inline-block; background-size:20px auto;background:rgba(174,224,120,.5); text-align:center; vertical-align:middle;width: 40px;height: 40px; line-height:40px;position:fixed; left:10px; bottom:15px;color:#FFFFFF; border-radius:50%;z-index: 90}
        .f_index .icon-shouyeshouye{
            font-size: 30px;
        }
    </style>
</head>

<body>

<div class="cashlist">
    <div class="list">
        <?php if(is_array($data)): foreach($data as $k=>$v): ?><dl>
            <dt>
                <span><?php echo ($v["semester"]); ?></span>
                <em>
                   <?php echo ($k); ?>
                </em>
            </dt>
            <dd>
                <ul>
                    <li><i class="fa fa-credit-card icon"></i>收益金额：￥<?php echo ($v["income"]); ?></li>
                    <li><i class="fa fa-pie-chart icon"></i>订购比例：<?php echo ($v["order_percent"]); ?>%</li>
                </ul>
            </dd>
        </dl><?php endforeach; endif; ?>
    </div>
    <a href="<?php echo U('Agent/index');?>">
			<span class="f_index">
				<span class="iconfont icon-shouyeshouye"></span>
			</span>
    </a>
</div>


</body>
</html>