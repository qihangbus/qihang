<?php if (!defined('THINK_PATH')) exit();?>	<!DOCTYPE html>
<html lang="en">
	<head>
		<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
		<meta charset="utf-8" />
		<title>网站后台系统管理</title>

		<meta name="description" content="" />
		<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0" />

		<!-- bootstrap & fontawesome -->
		<link rel="stylesheet" href="/Public/2017/assets/css/bootstrap.css" />
		<link rel="stylesheet" href="/Public/2017/assets/css/font-awesome.css" />

		<!-- page specific plugin styles -->

		<!-- text fonts -->
		<link rel="stylesheet" href="/Public/2017/assets/css/ace-fonts.css" />

		<!-- ace styles -->
		<link rel="stylesheet" href="/Public/2017/assets/css/ace.css" class="ace-main-stylesheet" id="main-ace-style" />

		<!--[if lte IE 9]>
			<link rel="stylesheet" href="/Public/2017/assets/css/ace-part2.css" class="ace-main-stylesheet" />
		<![endif]-->

		<!--[if lte IE 9]>
		  <link rel="stylesheet" href="/Public/2017/assets/css/ace-ie.css" />
		<![endif]-->

		<!-- inline styles related to this page -->
        <link rel="stylesheet" href="/Public/2017/assets/css/slackck.css" />
		<!-- ace settings handler -->
		<script src="/Public/2017/assets/js/ace-extra.js"></script>
		
		<!-- HTML5shiv and Respond.js for IE8 to support HTML5 elements and media queries -->

		<!--[if lte IE 8]>
		<script src="/Public/2017/assets/js/html5shiv.js"></script>
		<script src="/Public/2017/assets/js/respond.js"></script>
		<![endif]-->
		<script src="/Public/2017/assets/js/jquery.min.js"></script>
		<script src="/Public/2017/assets/js/jquery.form.js"></script>
		<script src="/Public/2017/layer/layer.js"></script>
	</head>

	<body class="no-skin">
		<!-- #section:basics/navbar.layout -->
		<div id="navbar" class="navbar navbar-default    navbar-collapse">
			<script type="text/javascript">
				try{ace.settings.check('navbar' , 'fixed')}catch(e){}
			</script>

			<div class="navbar-container" id="navbar-container">
				<!-- #section:basics/sidebar.mobile.toggle -->
				<button type="button" class="navbar-toggle menu-toggler pull-left" id="menu-toggler" data-target="#sidebar">
					<span class="sr-only">Toggle sidebar</span>

					<span class="icon-bar"></span>

					<span class="icon-bar"></span>

					<span class="icon-bar"></span>
				</button>

				<!-- /section:basics/sidebar.mobile.toggle -->
				<div class="navbar-header pull-left">
					<!-- #section:basics/navbar.layout.brand -->
					<a href="<?php echo U('Index/index');?>" class="navbar-brand">
						<small>
							<i class="fa fa-leaf"></i>
							TingLan System
						</small>
					</a>
				</div>

				<!-- #section:basics/navbar.dropdown -->
				<div class="navbar-buttons navbar-header pull-right  collapse navbar-collapse" role="navigation">
					<ul class="nav ace-nav">
						<!-- #section:basics/navbar.user_menu -->
						<li class="light-blue">
							<a data-toggle="dropdown" href="#" class="dropdown-toggle">
								<img class="nav-user-photo" src="/Public/2017/assets/avatars/user.jpg" alt="Jason's Photo" />
								<span class="user-info">
									欢迎,<?php echo ($_SESSION['username']); ?>
								</span>

								<i class="ace-icon fa fa-caret-down"></i>
							</a>

							<ul class="user-menu dropdown-menu-right dropdown-menu dropdown-yellow dropdown-caret dropdown-close">

								<li>
									<a href="javascript:;"  id="change">
										<i class="ace-icon fa fa-key"></i>
										修改密码
									</a>
								</li>

								<li class="divider"></li>

								<li>
									<a href="javascript:;"  id="logout">
										<i class="ace-icon fa fa-power-off"></i>
										注销
									</a>
								</li>
							</ul>
						</li>

						<!-- /section:basics/navbar.user_menu -->
					</ul>
				</div>

				<!-- /section:basics/navbar.dropdown -->
			</div><!-- /.navbar-container -->
		</div>
<script type="text/javascript">
$(document).ready(function(){
	$("#logout").click(function(){
		layer.confirm('你确定要退出吗？', {icon: 3}, function(index){
			layer.close(index);
			window.location.href="<?php echo U('Login/logout');?>";
		});
	});
	$("#change").click(function(){
		window.location.href = "<?php echo U('Index/editPwd');?>";
	});
});
</script>

		<!-- /section:basics/navbar.layout -->
		<div class="main-container" id="main-container">

			<!-- #section:basics/sidebar -->

				<div id="sidebar" class="sidebar responsive">

				<div class="sidebar-shortcuts" id="sidebar-shortcuts">
					<div class="sidebar-shortcuts-large" id="sidebar-shortcuts-large">
						<button class="btn btn-success">
							<a href="/default.php" target="_blank" style="color:#fff" title="前端首页">
								<i class="ace-icon fa fa-home"></i>
							</a>
						</button>

						<button class="btn btn-info">
							<i class="ace-icon fa fa-pencil"></i>
						</button>

						<!-- #section:basics/sidebar.layout.shortcuts -->
						<button class="btn btn-warning" id="cache-clean" title="清除缓存">
							<i class="ace-icon fa fa-trash-o"></i>
						</button>

						<button class="btn btn-danger">
							<i class="ace-icon fa fa-cogs"></i>
						</button>

						<!-- /section:basics/sidebar.layout.shortcuts -->
					</div>

					<div class="sidebar-shortcuts-mini" id="sidebar-shortcuts-mini">
						<span class="btn btn-success"></span>

						<span class="btn btn-info"></span>

						<span class="btn btn-warning"></span>

						<span class="btn btn-danger"></span>
					</div>
				</div><!-- /.sidebar-shortcuts -->

				<ul class="nav nav-list">
<?php use Think\Auth; $m = M('auth_rule'); $field = 'id,name,title,css'; $left = $m->field($field)->where('pid=0 AND status=1')->select(); $auth = new Auth(); foreach ($left as $k=>$v){ if(!$auth->check($v['name'], session('aid')) && session('aid') != 1){ unset($left[$k]); } } ?>

<?php if(is_array($left)): foreach($left as $key=>$v): ?><li class="<?php if(CONTROLLER_NAME == $v['name']): ?>active open<?php endif; ?>"><!--open代表打开状态-->
						<a href="#" class="dropdown-toggle">
							<i class="menu-icon fa <?php echo ($v["css"]); ?>"></i>
							<span class="menu-text">
								<?php echo ($v["title"]); ?>
							</span>

							<b class="arrow fa fa-angle-down"></b>
						</a>

						<b class="arrow"></b>

						<ul class="submenu">
<?php $m = M('auth_rule'); $left_child = $m->where(array('pid'=>$v['id'],'status'=>1))->select(); foreach ($left_child as $k=>$v){ if(!$auth->check($v['name'], session('aid')) && session('aid') != 1){ unset($left_child[$k]); } } ?>
    <?php if(is_array($left_child)): foreach($left_child as $key=>$v): ?><li class="<?php if(($_COOKIE['s'] == $v['id'])): ?>active<?php endif; ?>">
								<a href="<?php echo U($v['name'],array('s'=>$v['id']));?>">
									<i class="menu-icon fa fa-caret-right"></i>
									<?php echo ($v["title"]); ?>
								</a>
								<b class="arrow"></b>
							</li><?php endforeach; endif; ?>
						</ul>
					</li><?php endforeach; endif; ?>
                    
				</ul><!-- /.nav-list -->
				<script type="text/javascript">
					$('#cache-clean').click(function(){
						$.get("<?php echo U('Index/cacheClean');?>",function(data){
							layer.msg(data.info,{icon:6,time:800});
						});
					});
				</script>
				<!-- #section:basics/sidebar.layout.minimize -->
				<div class="sidebar-toggle sidebar-collapse" id="sidebar-collapse">
					<i class="ace-icon fa fa-angle-double-left" data-icon1="ace-icon fa fa-angle-double-left" data-icon2="ace-icon fa fa-angle-double-right"></i>
				</div>
			</div>


			<!-- /section:basics/sidebar -->
			<div class="main-content">
				<div class="main-content-inner">
					<div class="page-content">

                        
                        
                        <!--主题-->
						<div class="page-header">
							<h1>
								您当前操作
								<small>
									<i class="ace-icon fa fa-angle-double-right"></i>
									添加用户组
								</small>
							</h1>
						</div>

<script>
$(function(){
	$('#admin_group_edit').ajaxForm({
		beforeSubmit: checkForm, // 此方法主要是提交前执行的方法，根据需要设置
		success: complete, // 这是提交后的方法
		dataType: 'json'
	});
	
	function checkForm(){
		if( '' == $.trim($('#title').val())){
			layer.alert('用户组名不能为空', {icon: 6}, function(index){
 			layer.close(index);
			$('#title').focus(); 
			});
			return false;
		}

 }
	function complete(data){
		if(data.status==1){
			layer.alert(data.info, {icon: 6}, function(index){
 			layer.close(index);
			window.location.href=data.url;
			});
		}else{
			alert(data.info);
			return false;	
		}
	}
 
});
</script>


						<div class="row">
							<div class="col-xs-12">
								<form class="form-horizontal" name="admin_group_edit" id="admin_group_edit" method="post" action="/default.php/Admin/Sys/admin_group_runedit">
								<input type="hidden" name="id" id="id" value="<?php echo ($group["id"]); ?>" />

									<div class="form-group">
										<label class="col-sm-2 control-label no-padding-right" for="form-field-1"> 用户组名：  </label>
										<div class="col-sm-10">
											<input type="text" name="title" id="title" value="<?php echo ($group["title"]); ?>" class="col-xs-10 col-sm-4" />
										</div>
									</div>
                                    <div class="space-4"></div>

									<div class="form-group">
										<label class="col-sm-2 control-label no-padding-right" for="form-field-1"> 是否启用： </label>
										<div class="col-sm-10" style="padding-top:5px;">
 <input name="status" id="status" value="1"   class="ace ace-switch ace-switch-4 btn-flat" type="checkbox"  <?php if($group[status] == 1): ?>checked<?php endif; ?> />
											<span class="lbl">&nbsp;&nbsp;默认关闭</span>
										</div>
									</div>
                                    <div class="space-4"></div>

									<div class="form-group">
										<label class="col-sm-2 control-label no-padding-right" for="form-field-1"> 规则设置： </label>
										<div class="col-sm-10" style="padding-top:3px;">
											<span class="lbl">设置规则请在用户组列表页进行规则配置</span>
										</div>
									</div>
                                    <div class="space-4"></div>
									
									<div class="clearfix form-actions">
										<div class="col-md-offset-3 col-md-9">
											<button class="btn btn-info" type="submit">
												<i class="ace-icon fa fa-check bigger-110"></i>
												保存
											</button>

											&nbsp; &nbsp; &nbsp;
											<button class="btn" type="reset">
												<i class="ace-icon fa fa-undo bigger-110"></i>
												重置
											</button>
										</div>
									</div>
								</form>
                        	</div>
                        </div>
									<div class="hr hr-24"></div>

						<div class="row">
							<div class="col-xs-12">
								<!-- PAGE CONTENT BEGINS -->
								<div class="hidden">

									<div id="sidebar2" class="sidebar h-sidebar navbar-collapse collapse">
										<ul class="nav nav-list">
                                        
    <?php $m = M('auth_rule'); $dataaa = $m->where(array('pid'=>$_COOKIE['s'],'status'=>1))->select(); foreach ($dataaa as $kkk=>$vvv){ if(!$auth->check($vvv['name'], session('aid')) && session('aid') != 1){ unset($dataaa[$kkk]); } } ?>
    <?php if(is_array($dataaa)): foreach($dataaa as $key=>$k): ?><li>
												<a href="<?php echo U(''.$k['name'].'');?>">
													<o class="font12 <?php if((CONTROLLER_NAME.'/'.ACTION_NAME == $k['name'])): ?>rigbg<?php endif; ?>"><?php echo ($k["title"]); ?></o>
												</a>

												<b class="arrow"></b>
											</li><?php endforeach; endif; ?>


										</ul><!-- /.nav-list -->
									</div><!-- .sidebar -->
								</div>

							</div><!-- /.col -->
						</div><!-- /.row -->

					</div><!-- /.page-content -->
				</div>
			</div><!-- /.main-content -->

				<div class="footer">
				<div class="footer-inner">
					<!-- #section:basics/footer -->
					<div class="footer-content">
						<span class="bigger-120">
							<span class="blue bolder">TingLan</span>
							后台管理系统 &copy; 2016-2017
						</span>
					</div>

					<!-- /section:basics/footer -->
				</div>
			</div>
            

		<!-- basic scripts -->


		<!--[if IE]>
<script type="text/javascript">
 window.jQuery || document.write("<script src='../assets/js/jquery1x.js'>"+"<"+"/script>");
</script>
<![endif]-->
		<script type="text/javascript">
			if('ontouchstart' in document.documentElement) document.write("<script src='/Public/2017/assets/js/jquery.mobile.custom.js'>"+"<"+"/script>");
		</script>
		<script src="/Public/2017/assets/js/bootstrap.js"></script>

		<!-- page specific plugin scripts -->

		<!-- ace scripts -->
		<script src="/Public/2017/assets/js/maxlength.js"></script>
		<script src="/Public/2017/assets/js/ace/ace.js"></script>
		<script src="/Public/2017/assets/js/ace/ace.sidebar.js"></script>
		<script src="/Public/2017/assets/js/ace/ace.submenu-hover.js"></script>


		<!-- inline scripts related to this page -->
		<script type="text/javascript">
			jQuery(function($) {
			   $('#sidebar2').insertBefore('.page-content');
			   
			   $('.navbar-toggle[data-target="#sidebar2"]').insertAfter('#menu-toggler');
			   
			   
			   $(document).on('settings.ace.two_menu', function(e, event_name, event_val) {
				 if(event_name == 'sidebar_fixed') {
					 if( $('#sidebar').hasClass('sidebar-fixed') ) {
						$('#sidebar2').addClass('sidebar-fixed');
						$('#navbar').addClass('h-navbar');
					 }
					 else {
						$('#sidebar2').removeClass('sidebar-fixed')
						$('#navbar').removeClass('h-navbar');
					 }
				 }
			   }).triggerHandler('settings.ace.two_menu', ['sidebar_fixed' ,$('#sidebar').hasClass('sidebar-fixed')]);
			})
		</script>
		<script src="/Public/2017/assets/js/jquery.form.js"></script>


		</div><!-- /.main-container -->

    		<!--下拉样式以及JS-->
		<link rel="stylesheet" href="/Public/2017/assets/css/chosen.css" />
		<script src="/Public/2017/assets/js/chosen.jquery.js"></script>
		<script type="text/javascript">
			jQuery(function($) {
				if(!ace.vars['touch']) {
					$('.chosen-select').chosen({allow_single_deselect:true}); 
					$(window)
					.off('resize.chosen')
					.on('resize.chosen', function() {
						$('.chosen-select').each(function() {
							 var $this = $(this);
							 $this.next().css({'width': $this.parent().width()});
						})
					}).trigger('resize.chosen');
			
				}
			});
		</script>
	</body>
</html>