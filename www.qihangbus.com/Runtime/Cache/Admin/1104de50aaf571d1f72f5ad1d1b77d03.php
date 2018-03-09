<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html>
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
							修改文章
						</small>
					</h1>
				</div>


				<div class="row">
					<div class="col-xs-12">
						<form class="form-horizontal" name="article-edit" id="article-edit" method="post">
							<input type="hidden" name="id" id="id" value="<?php echo ($res["id"]); ?>" />

							<div class="form-group">
								<label class="col-sm-2 control-label no-padding-right"> 栏目： </label>
								<div class="col-sm-10">
									<select name="columnid"  class="col-sm-3 " value="<?php echo ($res["columnid"]); ?>" id='column'>
										<option value="">请选择所属栏目</option>
										<?php if(is_array($column)): foreach($column as $key=>$vo): ?><option value="<?php echo ($vo["id"]); ?>" <?php if(($vo["id"]) == $res["columnid"]): ?>selected<?php endif; ?> ><?php echo ($vo["lefthtml"]); echo ($vo["name"]); ?></option><?php endforeach; endif; ?>
									</select>
								</div>
							</div>
							<div class="space-4"></div>

							<div class="form-group">
								<label class="col-sm-2 control-label no-padding-right"> 标题：  </label>
								<div class="col-sm-10">
									<input type="text" name="title" id="column_name"  value="<?php echo ($res["title"]); ?>"  class="col-xs-10 col-sm-6"/>
								</div>
							</div>
							<div class="space-4"></div>

							<div class="form-group">
								<label class="col-sm-2  control-label no-padding-right" > 缩略图： </label>
								<div class="col-sm-10" style="width:400px; margin:12px;">
									<div class="clearfix">
										<?php if(!empty($res["image"])): ?><div>
												<style>.ace-file-input{display:none}</style>
												<span class="profile-picture">
												<img data-pk="13" src="<?php echo ($res["image"]); ?>" class="img-responsive editable editable-click editable-empty"/>
											</span>
											</div><?php endif; ?>
										<input id="slt"  type="file" name="image_upload" multiple/>
										<input type="hidden" name="image" value="<?php echo ($res["image"]); ?>"/>
									</div>
									<div class="help-block"></div>
								</div>
							</div>
							<div class="space-4"></div>

							<div class="form-group">
								<label class="col-sm-2  control-label no-padding-right"> 简介： </label>
								<div class="col-sm-10">
									<textarea class="col-sm-6" rows="3" name="description" id="column_order" placeholder="50字以内" class="col-xs-10 col-sm-10" maxlength="50"/><?php echo ($res["description"]); ?></textarea>
								</div>
							</div>
							<div class="space-4"></div>

							<div class="form-group">

								<label class="col-sm-2 control-label no-padding-right" for="form-field-1"> 内容： </label>

								<div class="col-sm-10">
									<link href="/Public/2017/umeditor/themes/default/css/umeditor.min.css" rel="stylesheet">
									<script src="/Public/2017/umeditor/umeditor.config.js"></script>
									<script src="/Public/2017/umeditor/umeditor.min.js"></script>
									<script>
										$(function(){
											//载入在线编辑器
											UM.getEditor("myEditor",{
												"imageUrl":"<?php echo U('Article/uploadImage');?>", //图片上传提交地址
												"imagePath":"/Uploads/" //图片显示地址
											});
										});
									</script>

									<script type="text/plain" id="myEditor" style="width:700px;height:500px;" name="content"><?php echo ($res["content"]); ?></script>


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
<script src="/Public/2017/assets/js/bootstrap.js"></script>
<script src="/Public/2017/assets/js/ace.js"></script>
<script src="/Public/2017/assets/js/ace-elements.js"></script>
<script type="text/javascript">
	jQuery(function($) {
		var file_input = $('#slt');
		var upload_in_progress = false;

		file_input.ace_file_input({
			style : 'well',
			btn_choose : '点击或拖拽到这',
			btn_change: null,
			droppable: true,
			thumbnail: 'large',

			maxSize: 1024000,//bytes
			allowExt: ["jpeg", "jpg", "png", "gif"],
			allowMime: ["image/jpg", "image/jpeg", "image/png", "image/gif"],

			before_remove: function() {
				if(upload_in_progress)
					return false;//if we are in the middle of uploading a file, don't allow resetting file input
				return true;
			},

			preview_error: function(filename , code) {
				//code = 1 means file load error
				//code = 2 image load error (possibly file is not an image)
				//code = 3 preview failed
			}
		})
		file_input.on('file.error.ace', function(ev, info) {
			if(info.error_count['ext'] || info.error_count['mime']) layer.alert('文件类型错误! 请选择一个图片!',{icon:5});
			if(info.error_count['size']) layer.alert('文件大小错误! 最大 1M',{icon:5});
		});
	});
	//编辑缩略图
	$('.profile-picture').click(function(){
		$(this).parent().remove();
	});
</script>
<script src="/Public/2017/assets/js/jquery.validate.js" type="text/javascript"></script>
<script type="text/javascript">
	$(function(){
		$("form[name='article-edit']").validate({
			//debug: 'true',
			errorElement: 'span',
			errorClass: 'help-inline middle col-sm-5',
			focusInvalid: true,
			rules: {
				columnid: {required:true},
				title: {required:true},
			},

			messages: {
				columnid: "请选择栏目",
				title: "请输入标题",
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
					if(controls.find(':checkbox,:radio').length > 1)
						controls.append(error);
					else
						error.insertAfter(element.nextAll('.lbl:eq(0)').eq(0));
				}else
					error.insertAfter(element);
			},

			submitHandler: function (form) {
				$(form).ajaxSubmit({
					beforeSubmit: function(){
						wait = layer.load(2);
					},
					success: function(data){
						layer.close(wait);
						if(data.status==1){
							layer.msg(data.info, {icon:6,time:1000}, function(index){
								layer.close(index);
								window.location.href=data.url;
							});
						}else{
							layer.msg(data.info, {time:800}, function(index){
								layer.close(index);
							});
							return false;
						}
					},
					error: function showError(responseText, statusText){
						layer.close(wait);
						if(statusText=='timeout'){
							layer.msg("服务器繁忙，请稍后再试！", {time:1000});
							return;
						}else{
							layer.msg(statusText,{time:1000});
						}
					},
					timeout: 5000,
					dataType: 'json'
				});
			},
		});
	});
</script>
</body>
</html>