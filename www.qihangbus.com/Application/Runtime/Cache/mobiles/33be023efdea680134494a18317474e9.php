<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<meta http-equiv="x-dns-prefetch-control" content="on">
	<meta name="viewport" content="initial-scale=1,minimum-scale=1,maximum-scale=1,user-scalable=no,minimal-ui">
	<meta http-equiv="Cache-Control" content="no-siteapp"/>
	<meta name="renderer" content="webkit">
	<title>班级书架</title>
	<link rel="stylesheet" href="/Public/css/mobiles/iconfont.css">
	<style type="text/css">
		html {
			color: #000;
			background: #FFF;
		}

		body, div, dl, dt, dd, ul, ol, li, h1, h2, h3, h4, h5, h6, pre, form,
		fieldset, input, textarea, p, blockquote, th, td {
			margin: 0;
			padding: 0;
		}

		body {
			font-family: "微软雅黑";
			font-size: 14px;
			color: #333;
			background-color: #F2F2F2;
		}

		li, ul {
			list-style: none;
			list-style-type: none;
			margin: 0;
			padding: 0;
		}

		a {
			color: #333;
			text-decoration: none;
		}

		.wrap2 {
			width: 100%
		}

		.iban {
			width: 100%
		}

		.iban img {
			width: 100%
		}

		.head-cate {
			height: 41px;
			background-color: #fff;
		}

		.head-cate ul {
			height: 42px;
		}

		.head-cate a {
			display: block;
		}

		.head-cate .ul-control .li_cur {
			background: #fff;
			border-bottom: 2px solid #aee078;
			color: #333;
		}

		.head-cate ul li {
			height: 42px;
			line-height: 40px;
			float: left;
			width: 50%;
			text-align: center;
			font-size: 14px;
			border-right: 1px solid #eee;
			box-sizing: border-box;
		}

		.head-cate ul li.right {
			float: right;
			border-right: none;
		}

		.clearfix {
			display: block;
		}

		.prolist li {
			width: 50%;
			box-sizing: border-box;
			float: left;
			border-bottom: 1px solid #F2F2F2;
			border-right: 1px solid #F2F2F2;
		}

		.dl_pro {
			padding: 8% 5% 5%;
			text-align: center;
			margin: 0;
		}

		.dl_pro dt {
			height: 111px;
		}

		.dl_pro dt img {
			width: 70%;
			max-width: 130px;
			height: 110px;
		}

		.dl_pro .dd_p {
			font-size: 14px;
			color: #ffae00;
		}

		.dl_pro .dd_name {
			color: #6f6a5f;
		}

		.dl_pro dd {
			margin: 0;
			text-overflow: ellipsis;
			overflow: hidden;
			white-space: nowrap;
			word-wrap: normal;
		}
		.line_hr{ padding:2px 0}
		.f_index {
			display: inline-block;
			background-size: 20px auto;
			background: rgba(174,224,120,.5);
			text-align: center;
			vertical-align: middle;
			width: 40px;
			height: 40px;
			line-height: 40px;
			position: fixed;
			left: 10px;
			bottom: 15px;
			color: #FFFFFF;
			border-radius: 50%;
			z-index: 90;
		}
		.f_index .icon-shouyeshouye {
			font-size: 30px;
		}
	</style>
</head>
<body>

<div class="wrap2">
	<div class="c_t">
		<div class="iban">
			<img src="/Public/images/mobiles/ban_mx.jpg" alt=""/>
		</div>
		<div class="head-cate">
			<ul class="ul_3 ul-control">
				<a href="<?php echo U('mobile.php/Mybag/index');?>&type=class&user_id=<?php echo ($user_id); ?>&user_flag=<?php echo ($user_flag); ?>">
					<li <?php if($type == 'class'): ?>class="li_cur"<?php endif; ?> ><span>班级图书</span></li>
				</a>
				<a href="<?php echo U('mobile.php/Mybag/index');?>&type=subscribe&user_id=<?php echo ($user_id); ?>&user_flag=<?php echo ($user_flag); ?>">
					<li <?php if($type == 'subscribe'): ?>class="li_cur"<?php endif; ?> ><span>可预约图书</span></li>
				</a>
			</ul>
		</div>
	</div>
	<div class="line_hr"></div>
	<ul class="prolist clearfix">
		<?php if(is_array($list)): $i = 0; $__LIST__ = $list;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i; if($vo['book_id'] != ''): ?><li>
					<a href="<?php echo U('mobile.php/Mybag/read_info/',array('book_id'=>$vo['book_id'],'user_id'=>$user_id,'user_flag'=>$user_flag));?>">
						<dl class="dl_pro">
							<dt><img src="<?php echo ($vo["book_thumb"]); ?>"></dt>
							<dd class="dd_p">
								<?php if(!empty($vo["sub_name"])): echo ($vo["sub_name"]); ?>
									<?php else: ?>
									&nbsp;<?php endif; ?>
							</dd>
							<dd class="dd_name" style="color:#6f6a5f;">【<?php echo ($vo["book_name"]); ?>】</dd>
						</dl>
					</a>
				</li><?php endif; endforeach; endif; else: echo "" ;endif; ?>
	</ul>
	<a href="<?php echo U('mobile.php/Ucenter/index');?>">
		<span class="f_index"><span class="iconfont icon-shouyeshouye"></span></span>
	</a>
</div>
</body>
</html>