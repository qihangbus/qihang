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
									修改系统管理员
								</small>
							</h1>
						</div>

						<div class="row">
							<div class="col-xs-12">
								<form class="form-horizontal" id="validation-form" method="post" action="<?php echo U('adminEdit');?>" novalidate="novalidate">
									<input name="id" type="hidden" value="<?php echo ($admin["id"]); ?>" />
									<div class="form-group">
										<label class="control-label col-xs-12 col-sm-3 no-padding-right" for="group_id">所属用户组：</label>
										<div class="col-xs-12 col-sm-9">
											<div class="clearfix">
												<select name="group_id"  class="col-sm-3" >
													<option value="">请选择所属用户组</option>
													<?php if(is_array($auth_group)): foreach($auth_group as $key=>$v): ?><option <?php if($v[id] == $auth_group_access): ?>selected<?php endif; ?> value="<?php echo ($v["id"]); ?>"><?php echo ($v["title"]); ?></option><?php endforeach; endif; ?>
												</select>
											</div>
										</div>
									</div>

									<div class="space-2"></div>

									<div class="form-group">
										<label class="control-label col-xs-12 col-sm-3 no-padding-right" for="username">用户名：</label>
										<div class="col-xs-12 col-sm-9">
											<div class="clearfix">
												<input type="text" name="username" id="username" value="<?php echo ($admin["username"]); ?>"  placeholder="用户名必须是以字母开头，数字、符号组合" class="col-xs-10 col-sm-6" readonly/>
											</div>
										</div>
									</div>
									<div class="space-2"></div>

									<div class="form-group">
										<label class="control-label col-xs-12 col-sm-3 no-padding-right" for="pwd">密码：</label>
										<div class="col-xs-12 col-sm-9">
											<div class="clearfix">
												<input type="password" name="pwd" id="pwd" placeholder="密码必须大于6位，小于15位" class="col-xs-10 col-sm-6" />
											</div>
										</div>
									</div>
									<div class="space-2"></div>

									<div class="form-group">
										<label class="control-label col-xs-12 col-sm-3 no-padding-right" for="pwd2">确认密码：</label>
										<div class="col-xs-12 col-sm-9">
											<div class="clearfix">
												<input type="text" name="pwd2" id="pwd2" placeholder="两次密码需一致" class="col-xs-10 col-sm-6" />
											</div>
										</div>
									</div>
									<div class="space-2"></div>

									<div class="form-group">
										<label class="control-label col-xs-12 col-sm-3 no-padding-right" for="email">用户邮箱：</label>
										<div class="col-xs-12 col-sm-9">
											<div class="clearfix">
												<input type="text" name="email" id="email" value="<?php echo ($admin["email"]); ?>" class="col-xs-10 col-sm-6" />
											</div>
										</div>
									</div>
									<div class="space-2"></div>

									<div class="form-group">
										<label class="control-label col-xs-12 col-sm-3 no-padding-right" for="tel">手机号码：</label>
										<div class="col-xs-12 col-sm-9">
											<div class="clearfix">
												<input type="text" name="tel" id="tel" value="<?php echo ($admin["tel"]); ?>" class="col-xs-10 col-sm-6" />
											</div>
										</div>
									</div>
									<div class="space-2"></div>

									<div class="form-group">
										<label class="control-label col-xs-12 col-sm-3 no-padding-right" for="realname">真实姓名：</label>
										<div class="col-xs-12 col-sm-9">
											<div class="clearfix">
												<input type="text" name="realname" id="realname" value="<?php echo ($admin["realname"]); ?>" class="col-xs-10 col-sm-6" />
											</div>
										</div>
									</div>
									<div class="space-2"></div>

									<div class="form-group">
										<label class="control-label col-xs-12 col-sm-3 no-padding-right" for="status">是否禁用：</label>
										<div class="col-xs-12 col-sm-9">
											<div class="clearfix" style="padding-top:5px;">
												<input name="status" id="status" <?php if($admin[status] == 2): ?>checked<?php endif; ?> value="2" class="ace ace-switch ace-switch-4 btn-flat" type="checkbox" />
												<span class="lbl"></span>
											</div>
										</div>
									</div>
									<div class="space-2"></div>

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
	</body>
	<script src="/Public/2017/assets/js/jquery.validate.js"></script>
	<script type="text/javascript">
		jQuery(function($) {
			$('#validation-form').validate({
				//debug: 'true',
				errorElement: 'div',
				errorClass: 'help-block',
				focusInvalid: true,
				rules: {
					group_id: {
						required: true,
					},
					pwd: {
						minlength: 6
					},
					pwd2: {
						minlength: 6,
						equalTo: "#pwd"
					},
					username: {
						required: true,
					},
					email: {
						required: true,
						email:true
					},
					tel: {
						required: true,
						digits:true,
						minlength:11,
						maxlength:11
					},
				},

				messages: {
					group_id: {
						required: "请选择所属用户组",
					},
					pwd: {
						minlength: "密码不能小于6位"
					},
					pwd2: {
						equalTo: "两次密码不一致"
					},
					username: {
						required: "请输入用户名",
					},
					email: {
						required: "请输入邮箱",
						email: "请输入正确的邮箱"
					},
					tel: "请输入正确的手机号",
				},


				highlight: function (e) {
					$(e).closest('.form-group').removeClass('has-info').addClass('has-error');
				},

				success: function (e) {
					$(e).closest('.form-group').removeClass('has-error');//.addClass('has-info');
					$(e).remove();
				},

				errorPlacement: function (error, element) {
					if(element.is('input[type=checkbox]') || element.is('input[type=radio]')) {
						var controls = element.closest('div[class*="col-"]');
						if(controls.find(':checkbox,:radio').length > 1) controls.append(error);
						else error.insertAfter(element.nextAll('.lbl:eq(0)').eq(0));
					}
					else if(element.is('.select2')) {
						error.insertAfter(element.siblings('[class*="select2-container"]:eq(0)'));
					}
					else if(element.is('.chosen-select')) {
						error.insertAfter(element.siblings('[class*="chosen-container"]:eq(0)'));
					}
					else error.insertAfter(element.parent());
				},

				submitHandler: function (form) {
					$(form).ajaxSubmit({
						//beforeSubmit: showRequest,
						success: function(data){
							if(data.status==1){
								layer.alert(data.info, {icon: 6}, function(index){
									layer.close(index);
									window.location.href=data.url;
								});
							}else{
								layer.alert(data.info, {icon: 5}, function(index){
									layer.close(index);
								});
								return false;
							}
						},
						dataType: 'json'
					});
				},
				invalidHandler: function (form) {
				}
			});
		});
	</script>
</html>