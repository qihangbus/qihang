<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>幼儿园列表</title>
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
        .cashlist dl dd ul li {
            line-height: 23px;
        }
    </style>
</head>

<body>

<div class="cashlist">
    <div class="list">
        <?php if(is_array($data)): foreach($data as $key=>$v): ?><dl>
                <dt>
                    <span><?php echo ($v["school_name"]); ?></span>
                    <em>
                        <?php echo ($v["school_num"]); ?>人
                    </em>
                    <em style="color:#888">
                        <?php switch($v["meal_type"]): case "1": ?>一周两本<?php break;?>
                            <?php case "2": ?>一周四本<?php break; endswitch;?>
                    </em>
                </dt>
                <dd>
                    <ul>
                        <!--<li>-->
                        <!--<i class="address"><?php echo ($v["province_name"]); echo ($v["city_name"]); echo ($v["district_name"]); ?></i>-->
                        <!--</li>-->
                        <li>今日订购人数：<strong style="color:#fb8c8d"><?php echo ($v["pay_today"]); ?></strong></li>
                        <li>注册人数：<strong style="color:#fb8c8d"><?php echo ($v["reg_num"]); ?></strong></li>
                        <li>订购人数：<strong style="color:#fb8c8d"><?php echo ($v["pay_num"]); ?></strong></li>
                        <li>订购比例：<strong style="color:#fb8c8d"><?php echo ($v["order_percent"]); ?>%</strong></li>
                        <!--<li><i class="fa fa-clock-o icon"></i>添加时间：<?php echo (date('Y-m-d',$v["reg_time"])); ?></li>-->

                    </ul>
                    <?php if(($type) == "1"): ?><div class="button"><a href="<?php echo U('rebateDetails',array('id'=>$v['school_id']));?>">返利明细</a></div><?php endif; ?>
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