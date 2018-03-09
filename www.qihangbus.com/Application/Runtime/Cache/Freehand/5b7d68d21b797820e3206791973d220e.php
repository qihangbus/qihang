<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0">
  <meta name="keywords" content="admin, dashboard, bootstrap, template, flat, modern, theme, responsive, fluid, retina, backend, html5, css, css3">
  <meta name="description" content="">
  <meta name="author" content="ThemeBucket">
  <link rel="shortcut icon" href="#" type="image/png">
  <title>广告信息_<?php echo L('_WEBSITE_NAME_');?></title>
  <link href="/Public/js/advanced-datatable/css/demo_page.css" rel="stylesheet" />
  <link href="/Public/js/advanced-datatable/css/demo_table.css" rel="stylesheet" />
  <link rel="stylesheet" href="/Public/js/data-tables/DT_bootstrap.css" />

  <link href="/Public/css/style.css" rel="stylesheet">
  <link href="/Public/css/style-responsive.css" rel="stylesheet">

  <!-- HTML5 shim and Respond.js IE8 support of HTML5 elements and media queries -->
  <!--[if lt IE 9]>
  <script src="/Public/js/html5shiv.js"></script>
  <script src="/Public/js/respond.min.js"></script>
  <![endif]-->
</head>

<body class="sticky-header">

<section>
    <!-- left side start-->
    <div class="left-side sticky-left-side">

        <!--logo and iconic logo start-->
        <div class="logo">
            <a href="./"><img src="/Public/images/logo.png" alt=""></a>
        </div>

        <div class="logo-icon text-center">
            <a href="./"><img src="/Public/images/logo_icon.png" alt=""></a>
        </div>
        <!--logo and iconic logo end-->

        <div class="left-side-inner">

            <!-- visible to small devices only -->
            <div class="visible-xs hidden-sm hidden-md hidden-lg">
                <div class="media logged-user">
                    <img alt="" src="/Public/images/photos/user-avatar.png" class="media-object">
                    <div class="media-body">
                        <h4><a href="#">John Doe</a></h4>
                        <span>"Hello There..."</span>
                    </div>
                </div>

                <h5 class="left-nav-title">Account Information</h5>
                <ul class="nav nav-pills nav-stacked custom-nav">
                  <li><a href="#"><i class="fa fa-user"></i> <span>Profile</span></a></li>
                  <li><a href="#"><i class="fa fa-cog"></i> <span>Settings</span></a></li>
                  <li><a href="#"><i class="fa fa-sign-out"></i> <span>Sign Out</span></a></li>
                </ul>
            </div>

            <meta charset="UTF-8">
<!--sidebar nav start-->
<ul class="nav nav-pills nav-stacked custom-nav">
    <?php if(is_array($menu)): $k = 0; $__LIST__ = $menu;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($k % 2 );++$k;?><li class="<?php if($vo[num] > 0): ?>menu-list<?php endif; foreach($vo[child] as $v){if(strtolower($v[c]) == strtolower(CONTROLLER_NAME) && $v[a] == ACTION_NAME)echo ' nav-active';} ?>">
            <a href='<?php if($vo[num] < 1): echo U("/$vo[c]/$vo[a]/$vo[data]"); endif; ?>'>
                <i class="fa <?php echo ($vo['icon']); ?>"></i> <span><?php echo ($vo['language']); ?></span>
            </a>
            <?php if($vo['num'] > 0): ?><ul class="sub-menu-list">
                    <?php if(is_array($vo["child"])): $i = 0; $__LIST__ = $vo["child"];if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$sub): $mod = ($i % 2 );++$i;?><li <?php if(strtolower($sub[c]) == strtolower(CONTROLLER_NAME) && $sub[a] == ACTION_NAME) echo 'class="active"'; ?> >
                        <a href='<?php echo U("$sub[c]/$sub[a]");?>'><?php echo ($sub['language']); ?></a>
                        </li><?php endforeach; endif; else: echo "" ;endif; ?>
                </ul><?php endif; ?>
        </li><?php endforeach; endif; else: echo "" ;endif; ?>
</ul>
<!--sidebar nav end-->


        </div>
    </div>
    <!-- left side end-->
    
    <!-- main content start-->
    <div class="main-content" >

        <meta charset="UTF-8">
<!-- header section start-->
<div class="header-section">

    <!--toggle button start-->
    <a class="toggle-btn"><i class="fa fa-bars"></i></a>
    <!--toggle button end-->

    <!--search start-->
    <form class="searchform" style="display: none;" action="index.html" method="post">
        <input type="text" class="form-control" name="keyword" placeholder="Search here..." />
    </form>
    <!--search end-->

    <!--notification menu start -->
    <div class="menu-right">
        <ul class="notification-menu">
            <li>
                <a href="#" class="btn btn-default dropdown-toggle info-number" data-toggle="dropdown">
                    <i class="fa fa-tasks"></i>
                    <span class="badge">0</span>
                </a>
                <div class="dropdown-menu dropdown-menu-head pull-right">
                    <h5 class="title">You have 8 pending task</h5>
                    <ul class="dropdown-list user-list">
                        <li class="new">
                            <a href="#">
                                <div class="task-info">
                                    <div>Database update</div>
                                </div>
                                <div class="progress progress-striped">
                                    <div style="width: 40%" aria-valuemax="100" aria-valuemin="0" aria-valuenow="40" role="progressbar" class="progress-bar progress-bar-warning">
                                        <span class="">40%</span>
                                    </div>
                                </div>
                            </a>
                        </li>
                        <li class="new">
                            <a href="#">
                                <div class="task-info">
                                    <div>Dashboard done</div>
                                </div>
                                <div class="progress progress-striped">
                                    <div style="width: 90%" aria-valuemax="100" aria-valuemin="0" aria-valuenow="90" role="progressbar" class="progress-bar progress-bar-success">
                                        <span class="">90%</span>
                                    </div>
                                </div>
                            </a>
                        </li>
                        <li>
                            <a href="#">
                                <div class="task-info">
                                    <div>Web Development</div>
                                </div>
                                <div class="progress progress-striped">
                                    <div style="width: 66%" aria-valuemax="100" aria-valuemin="0" aria-valuenow="66" role="progressbar" class="progress-bar progress-bar-info">
                                        <span class="">66% </span>
                                    </div>
                                </div>
                            </a>
                        </li>
                        <li>
                            <a href="#">
                                <div class="task-info">
                                    <div>Mobile App</div>
                                </div>
                                <div class="progress progress-striped">
                                    <div style="width: 33%" aria-valuemax="100" aria-valuemin="0" aria-valuenow="33" role="progressbar" class="progress-bar progress-bar-danger">
                                        <span class="">33% </span>
                                    </div>
                                </div>
                            </a>
                        </li>
                        <li>
                            <a href="#">
                                <div class="task-info">
                                    <div>Issues fixed</div>
                                </div>
                                <div class="progress progress-striped">
                                    <div style="width: 80%" aria-valuemax="100" aria-valuemin="0" aria-valuenow="80" role="progressbar" class="progress-bar">
                                        <span class="">80% </span>
                                    </div>
                                </div>
                            </a>
                        </li>
                        <li class="new"><a href="">See All Pending Task</a></li>
                    </ul>
                </div>
            </li>
            <li>
                <a href="#" class="btn btn-default dropdown-toggle info-number" data-toggle="dropdown">
                    <i class="fa fa-envelope-o"></i>
                    <span class="badge">0</span>
                </a>
                <div class="dropdown-menu dropdown-menu-head pull-right">
                    <h5 class="title">You have 5 Mails </h5>
                    <ul class="dropdown-list normal-list">
                        <li class="new">
                            <a href="">
                                <span class="thumb"><img src="/Public/images/photos/user1.png" alt="" /></span>
                                        <span class="desc">
                                          <span class="name">John Doe <span class="badge badge-success">new</span></span>
                                          <span class="msg">Lorem ipsum dolor sit amet...</span>
                                        </span>
                            </a>
                        </li>
                        <li>
                            <a href="">
                                <span class="thumb"><img src="/Public/images/photos/user2.png" alt="" /></span>
                                        <span class="desc">
                                          <span class="name">Jonathan Smith</span>
                                          <span class="msg">Lorem ipsum dolor sit amet...</span>
                                        </span>
                            </a>
                        </li>
                        <li>
                            <a href="">
                                <span class="thumb"><img src="/Public/images/photos/user3.png" alt="" /></span>
                                        <span class="desc">
                                          <span class="name">Jane Doe</span>
                                          <span class="msg">Lorem ipsum dolor sit amet...</span>
                                        </span>
                            </a>
                        </li>
                        <li>
                            <a href="">
                                <span class="thumb"><img src="/Public/images/photos/user4.png" alt="" /></span>
                                        <span class="desc">
                                          <span class="name">Mark Henry</span>
                                          <span class="msg">Lorem ipsum dolor sit amet...</span>
                                        </span>
                            </a>
                        </li>
                        <li>
                            <a href="">
                                <span class="thumb"><img src="/Public/images/photos/user5.png" alt="" /></span>
                                        <span class="desc">
                                          <span class="name">Jim Doe</span>
                                          <span class="msg">Lorem ipsum dolor sit amet...</span>
                                        </span>
                            </a>
                        </li>
                        <li class="new"><a href="">Read All Mails</a></li>
                    </ul>
                </div>
            </li>
            <li>
                <a href="#" class="btn btn-default dropdown-toggle info-number" data-toggle="dropdown">
                    <i class="fa fa-bell-o"></i>
                    <span class="badge">0</span>
                </a>
                <div class="dropdown-menu dropdown-menu-head pull-right">
                    <h5 class="title">Notifications</h5>
                    <ul class="dropdown-list normal-list">
                        <li class="new">
                            <a href="">
                                <span class="label label-danger"><i class="fa fa-bolt"></i></span>
                                <span class="name">Server #1 overloaded.  </span>
                                <em class="small">34 mins</em>
                            </a>
                        </li>
                        <li class="new">
                            <a href="">
                                <span class="label label-danger"><i class="fa fa-bolt"></i></span>
                                <span class="name">Server #3 overloaded.  </span>
                                <em class="small">1 hrs</em>
                            </a>
                        </li>
                        <li class="new">
                            <a href="">
                                <span class="label label-danger"><i class="fa fa-bolt"></i></span>
                                <span class="name">Server #5 overloaded.  </span>
                                <em class="small">4 hrs</em>
                            </a>
                        </li>
                        <li class="new">
                            <a href="">
                                <span class="label label-danger"><i class="fa fa-bolt"></i></span>
                                <span class="name">Server #31 overloaded.  </span>
                                <em class="small">4 hrs</em>
                            </a>
                        </li>
                        <li class="new"><a href="">See All Notifications</a></li>
                    </ul>
                </div>
            </li>
            <li>
                <a href="#" class="btn btn-default dropdown-toggle" data-toggle="dropdown">
                    <img src="<?php echo (session('avatar')); ?>" alt="" style="width:20px;height:20px;"/>
                    <?php echo (session('nickname')); ?>
                    <span class="caret"></span>
                </a>
                <ul class="dropdown-menu dropdown-menu-usermenu pull-right">
                    <li><a href="<?php echo U('admin/Administrator/info/',array('id'=>session('uid')));?>"><i class="fa fa-cog"></i>  修改信息</a></li>
                    <li><a href="<?php echo U('/Login/logout');?>"><i class="fa fa-sign-out"></i> 退出</a></li>
                </ul>
            </li>

        </ul>
    </div>
    <!--notification menu end -->

</div>
<!-- header section end-->

        <!-- page heading start-->
        <div class="page-heading">
            <h3>
                <?php if($info["ad_id"] > 0): ?>修改<?php else: ?>添加<?php endif; ?>广告信息
            </h3>
            <ul class="breadcrumb">
                <li>
                    <a href="#">后台首页</a>
                </li>
                <li>
                    <a href="#">广告管理</a>
                </li>
            </ul>
        </div>
        <!-- page heading end-->

        <!--body wrapper start-->
        <style type="text/css">
            .col-lg-4{color:#65cea7}
            .col-lg-4 label{color:#cccccc}
        </style>
        <div class="wrapper">
            <div class="row">
                        <div class="col-lg-12">
                            <section class="panel">
                                <header class="panel-heading">
                                    <?php if($info["ad_id"] > 0): ?>修改<?php else: ?>添加<?php endif; ?>广告
                                    <span class="pull-right">
                                        <a href="<?php echo U('Ads/index');?>" class="btn btn-primary">广告列表</a>
                                        <a href="javascript:void(0);" class="btn disabled">添加广告</a>
                                     </span>
                                </header>

                                <div class="panel-body">
                                    <form class="form-horizontal" enctype='multipart/form-data' method="post" name="myform" action="<?php echo U('Ads/edit_handle');?>" role="form">
                            
                                        <div class="form-group">
                                            <label class="col-lg-2 col-sm-2 control-label">广告名称</label>
                                            <div class="col-lg-3">
                                                <input type="text" class="form-control" name="ad_name" value="<?php echo ($info["ad_name"]); ?>">
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="col-lg-2 col-sm-2 control-label">媒介类型</label>
                                            <div class="col-lg-4">
                                                <select name="media_type" id="media_type" class="form-control m-bot15">
                                                    <option>请选择</option>
                                                    <option value="1" <?php if(($info["media_type"]) == "1"): ?>selected<?php endif; ?>>图片</option>
                                                    
                                                </select>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="col-lg-2 col-sm-2 control-label">广告位置</label>
                                            <div class="col-lg-4">
                                                <select name="position_id" id="position_id" class="form-control m-bot15">
                                                    <option>请选择</option>
                                                    <?php if(is_array($positions)): $i = 0; $__LIST__ = $positions;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><option value="<?php echo ($vo['position_id']); ?>" <?php if(($info["position_id"]) == $vo["position_id"]): ?>selected<?php endif; ?>><?php echo ($vo['position_name']); ?></option><?php endforeach; endif; else: echo "" ;endif; ?>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="col-lg-2 col-sm-2 control-label">开始日期</label>
                                            <div class="col-lg-3">
                                                <input type="text" class="form-control" id="start_time" name="start_time" value="<?php echo ($info["start_time"]); ?>">
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="col-lg-2 col-sm-2 control-label">结束日期</label>
                                            <div class="col-lg-3">
                                                <input type="text" class="form-control" id="end_time" name="end_time" value="<?php echo ($info["end_time"]); ?>">
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="col-lg-2 col-sm-2 control-label">广告链接</label>
                                            <div class="col-lg-3">
                                                <input type="text" class="form-control" name="ad_link" value="<?php echo ($info["ad_link"]); ?>">
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="col-lg-2 col-sm-2 control-label">上传广告图片</label>
                                            <div class="col-lg-3">
                                                <input type="file" name="ad_code">
                                                <input type="hidden" name="old_ad_code" value="<?php echo ($info["ad_code"]); ?>">
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="col-lg-2 col-sm-2 control-label">广告联系人</label>
                                            <div class="col-lg-3">
                                                <input type="text" class="form-control" name="link_man" value="<?php echo ($info["link_man"]); ?>">
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="col-lg-2 col-sm-2 control-label">联系人Email</label>
                                            <div class="col-lg-3">
                                                <input type="text" class="form-control" name="link_email" value="<?php echo ($info["link_email"]); ?>">
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="col-lg-2 col-sm-2 control-label">联系人手机</label>
                                            <div class="col-lg-3">
                                                <input type="text" class="form-control" name="link_phone" value="<?php echo ($info["link_phone"]); ?>">
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <div class="col-lg-offset-1 col-lg-10">
                                                <input type="hidden" name="ad_id" value="<?php echo ($info["ad_id"]); ?>"/>
                                                <input type="submit" class="btn btn-primary" value="提交"/>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </section>

                        </div>


            </div>
        </div>
        <!--body wrapper end-->
        <meta charset="UTF-8">
<!--footer section start-->
<footer>
    版权所有 © 2016-2026 <?php echo L('_WEBSITE_NAME_');?>，并保留所有权利。
</footer>
<!--footer section end-->
    </div>
    <!-- main content end-->
</section>
<!-- Placed js at the end of the document so the pages load faster -->
<script src="/Public/js/jquery-1.10.2.min.js"></script>
<script src="/Public/js/jquery-ui-1.9.2.custom.min.js"></script>
<script src="/Public/js/jquery-migrate-1.2.1.min.js"></script>
<script src="/Public/js/bootstrap.min.js"></script>
<script src="/Public/js/modernizr.min.js"></script>
<script src="/Public/js/jquery.nicescroll.js"></script>
<!--easy pie chart-->
<script src="/Public/js/easypiechart/jquery.easypiechart.js"></script>
<script src="/Public/js/easypiechart/easypiechart-init.js"></script>
<!--Sparkline Chart-->
<script src="/Public/js/sparkline/jquery.sparkline.js"></script>
<script src="/Public/js/sparkline/sparkline-init.js"></script>
<!--icheck -->
<script src="/Public/js/iCheck/jquery.icheck.js"></script>
<script src="/Public/js/icheck-init.js"></script>
<!-- jQuery Flot Chart-->
<script src="/Public/js/flot-chart/jquery.flot.js"></script>
<script src="/Public/js/flot-chart/jquery.flot.tooltip.js"></script>
<script src="/Public/js/flot-chart/jquery.flot.resize.js"></script>
<!--Morris Chart-->
<script src="/Public/js/morris-chart/morris.js"></script>
<script src="/Public/js/morris-chart/raphael-min.js"></script>
<!--Calendar-->
<script src="/Public/js/calendar/clndr.js"></script>
<script src="/Public/js/calendar/evnt.calendar.init.js"></script>
<script src="/Public/js/calendar/moment-2.2.1.js"></script>
<script src="http://cdnjs.cloudflare.com/ajax/libs/underscore.js/1.5.2/underscore-min.js"></script>
<!--common scripts for all pages-->
<script src="/Public/js/scripts.js"></script>
<!--Dashboard Charts-->
<script src="/Public/js/dashboard-chart-init.js"></script>
<script src="/Public/js/laydate/laydate.js"></script>
<script>
    laydate({
        elem: '#start_time'
    });
    laydate({
        elem: '#end_time'
    });
    function change_grade(){
        var sid = $('#school_id').val();
        $.ajax({
            type:'post',
            url:"<?php echo U('/Student/ajax_grade');?>",
            data:{school_id:sid},
            dataType:'json',
            success:function(data){
                var arr = '';
                arr += "<option>请选择</option>";
                for(var i=0;i<data.length;i++){
                    arr += "<option value='"+data[i]['grade_id']+"'>"+data[i]['grade_name']+"</option>";
                }
                $('#grade_id').html(arr);
                $('#class_id').html("<option>请选择</option>");
                $('#teacher_id').html("<option>请选择</option>");
            }
        });
    }

    function change_class(){
        var gid = $('#grade_id').val();
        $.ajax({
            type:'post',
            url:"<?php echo U('/Student/ajax_class');?>",
            data:{grade_id:gid},
            dataType:'json',
            success:function(data){
                var arr = '';
                arr += "<option>请选择</option>";
                for(var i=0;i<data.length;i++){
                    arr += "<option value='"+data[i]['class_id']+"'>"+data[i]['class_name']+"</option>";
                }
                $('#class_id').html(arr);
                $('#teacher_id').html("<option>请选择</option>");
            }
        });
    }

    function change_teacher(){
        var cid = $('#class_id').val();
        $.ajax({
            type:'post',
            url:"<?php echo U('/Student/ajax_teacher');?>",
            data:{class_id:cid},
            dataType:'json',
            success:function(data){
                var arr = '';
                arr += "<option>请选择</option>";
                for(var i=0;i<data.length;i++){
                    arr += "<option value='"+data[i]['teacher_id']+"'>"+data[i]['teacher_name']+"</option>";
                }
                $('#teacher_id').html(arr);
            }
        });
    }
</script>
</body>
</html>