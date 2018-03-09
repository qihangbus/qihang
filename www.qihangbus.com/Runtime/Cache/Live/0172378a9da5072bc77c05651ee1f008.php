<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title><?php echo ($title); ?>_启航巴士</title>
    <!-- nePlayer CSS -->
    <link href="/Public/2017/css/nep.min.css" rel="stylesheet"/>
    <link href="/Public/2017/css/live.css" rel="stylesheet"/>
    <style>html{overflow-x: hidden; overflow-y: hidden;}</style>
    <meta name="viewport" content="width=device-width,initial-scale=1,minimum-scale=1,maximum-scale=1,user-scalable=no" />
</head>
<body>
<iframe src="http://e.vhall.com/webinar/inituser/<?php echo ($id); ?>?email=qihangbus@163.com&name=<?php echo ($name); ?>" frameBorder="0" scrolling="no"  width="100%" height="100%"></iframe>
<?php echo ($wqhg); ?>
</body>
</html>