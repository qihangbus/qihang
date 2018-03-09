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
							修改学校
						</small>
					</h1>
				</div>


				<div class="row">
					<div class="col-xs-12">
						<form class="form-horizontal" name="school-edit" id="school-edit" method="post">
							<input type="hidden" name="id" id="id" value="<?php echo ($data["school_id"]); ?>" />

							<div class="form-group">
								<label class="col-sm-2 control-label no-padding-right"> 学校名称：  </label>
								<div class="col-sm-10">
									<input type="text" name="school_name"  value="<?php echo ($data["school_name"]); ?>"  class="col-xs-10 col-sm-6"/>
								</div>
							</div>
							<div class="space-4"></div>

							<div class="form-group">
								<label class="col-sm-2 control-label no-padding-right"> 负责人：  </label>
								<div class="col-sm-10">
									<input type="text" name="school_leader" value="<?php echo ($data["school_leader"]); ?>"  class="col-xs-10 col-sm-6"/>
								</div>
							</div>
							<div class="space-4"></div>

							<div class="form-group">
								<label class="col-sm-2 control-label no-padding-right"> 手机号：  </label>
								<div class="col-sm-10">
									<input type="text" name="leader_mobile" value="<?php echo ($data["leader_mobile"]); ?>"  class="col-xs-10 col-sm-6"/>
								</div>
							</div>
							<div class="space-4"></div>

							<div class="form-group">
								<label class="col-sm-2 control-label no-padding-right"> 园长：  </label>
								<div class="col-sm-10">
									<input type="text" name="school_teacher" value="<?php echo ($data["school_teacher"]); ?>"  class="col-xs-10 col-sm-6"/>
								</div>
							</div>
							<div class="space-4"></div>

							<div class="form-group">
								<label class="col-sm-2 control-label no-padding-right"> 手机号：  </label>
								<div class="col-sm-10">
									<input type="text" name="teacher_mobile" value="<?php echo ($data["teacher_mobile"]); ?>"  class="col-xs-10 col-sm-6"/>
								</div>
							</div>
							<div class="space-4"></div>


							<div class="form-group">
								<label class="col-sm-2 control-label no-padding-right"> 合作伙伴手机号：  </label>
								<div class="col-sm-10">
									<input type="text" name="agent_mobile" value="<?php echo ($data["agent_mobile"]); ?>"  class="col-xs-10 col-sm-6"/>
								</div>
							</div>
							<div class="space-4"></div>

							<div class="form-group">
								<label class="col-sm-2 control-label no-padding-right"> 省/市/县： </label>
								<div class="col-sm-10">
									<select name="province_id"  class="col-sm-2" >
										<option value="">-请选择省份-</option>
										<?php if(is_array($province)): foreach($province as $key=>$v): ?><option <?php if(($data["province_id"]) == $v["region_id"]): ?>selected<?php endif; ?> value="<?php echo ($v["region_id"]); ?>"><?php echo ($v["region_name"]); ?></option><?php endforeach; endif; ?>
									</select>
									<select name="city_id"  class="col-sm-2" >
										<option value="">-请选择城市-</option>
										<?php if(is_array($city)): foreach($city as $key=>$v): ?><option <?php if(($data["city_id"]) == $v["region_id"]): ?>selected<?php endif; ?> value="<?php echo ($v["region_id"]); ?>"><?php echo ($v["region_name"]); ?></option><?php endforeach; endif; ?>

									</select>
									<select name="district_id"  class="col-sm-2" >
										<option value="">-请选择区县-</option>
										<?php if(is_array($zone)): foreach($zone as $key=>$v): ?><option <?php if(($data["district_id"]) == $v["region_id"]): ?>selected<?php endif; ?> value="<?php echo ($v["region_id"]); ?>"><?php echo ($v["region_name"]); ?></option><?php endforeach; endif; ?>
									</select>
								</div>
							</div>

							<div class="form-group">
								<label class="col-sm-2 control-label no-padding-right"> 详细地址：  </label>
								<div class="col-sm-10">
									<input type="text" name="school_address" value="<?php echo ($data["school_address"]); ?>"  class="col-xs-10 col-sm-6"/>
								</div>
							</div>
							<div class="space-4"></div>

							<div class="form-group">
								<label class="col-sm-2 control-label no-padding-right"> 规模：  </label>
								<div class="col-sm-10">
									<input type="number" name="school_num"  value="<?php echo ($data["school_num"]); ?>"  class="col-xs-10 col-sm-6"/>
								</div>
							</div>
							<div class="space-4"></div>

							<div class="form-group">
								<label class="col-sm-2 control-label no-padding-right"> 套餐类型：  </label>
								<div class="col-sm-10">
									<select name="meal_type"  class="col-sm-3" >
										<option <?php if(($data["meal_type"]) == "1"): ?>selected<?php endif; ?> value="1">一周两本</option>
										<option <?php if(($data["meal_type"]) == "2"): ?>selected<?php endif; ?> value="2">一周四本</option>
									</select>
								</div>
							</div>
							<div class="space-4"></div>

							<div class="form-group">
								<label class="col-sm-2 control-label no-padding-right"> 市场价：  </label>
								<div class="col-sm-10">
									<input type="number" name="meal_market_price"  value="<?php echo ($data["meal_market_price"]); ?>"  class="col-xs-10 col-sm-3"/>
									<span style="line-height: 31px;margin-left: 5px;">元/月</span>
								</div>
							</div>
							<div class="space-4"></div>

							<div class="form-group">
								<label class="col-sm-2 control-label no-padding-right"> 最大班级人数：  </label>
								<div class="col-sm-10">
									<input type="number" name="class_max_num"  value="<?php echo ($data["class_max_num"]); ?>"  class="col-xs-10 col-sm-3"/>
								</div>
							</div>
							<div class="space-4"></div>

							<div class="form-group">
								<label class="col-sm-2 control-label no-padding-right"> 第一学期开学时间：  </label>
								<div class="col-sm-10">
									<input type="datetime" name="semester_one_start"  value="<?php echo (date('Y-m-d',$data["semester_one_start"])); ?>"  class="col-xs-10 col-sm-3 daterange"/>
								</div>
							</div>
							<div class="space-4"></div>

							<div class="form-group">
								<label class="col-sm-2 control-label no-padding-right"> 第一学期放假时间：  </label>
								<div class="col-sm-10">
									<input type="datetime" name="semester_one"  value="<?php echo (date('Y-m-d',$data["semester_one"])); ?>"  class="col-xs-10 col-sm-3 daterange"/>
								</div>
							</div>
							<div class="space-4"></div>

							<div class="form-group">
								<label class="col-sm-2 control-label no-padding-right"> 第二学期开学时间：  </label>
								<div class="col-sm-10">
									<input type="datetime" name="semester_two_start"  value="<?php echo (date('Y-m-d',$data["semester_two_start"])); ?>"  class="col-xs-10 col-sm-3 daterange"/>
								</div>
							</div>
							<div class="space-4"></div>

							<div class="form-group">
								<label class="col-sm-2 control-label no-padding-right"> 第二学期结束时间：  </label>
								<div class="col-sm-10">
									<input type="datetime" name="semester_two"  value="<?php echo (date('Y-m-d',$data["semester_two"])); ?>"  class="col-xs-10 col-sm-3 daterange"/>
								</div>
							</div>
							<div class="space-4"></div>

							<div class="form-group">
								<label class="col-sm-2 control-label no-padding-right"> 还书提醒(一)：  </label>
								<div class="col-sm-10">
									<select name="book_reminder_first"  class="col-sm-3" >
										<option value="0" <?php if(($data["book_reminder_first"]) == "0"): ?>selected<?php endif; ?> >-请选择-</option>
										<option value="1" <?php if(($data["book_reminder_first"]) == "1"): ?>selected<?php endif; ?> >星期一</option>
										<option value="2" <?php if(($data["book_reminder_first"]) == "2"): ?>selected<?php endif; ?> >星期二</option>
										<option value="3" <?php if(($data["book_reminder_first"]) == "3"): ?>selected<?php endif; ?> >星期三</option>
										<option value="4" <?php if(($data["book_reminder_first"]) == "4"): ?>selected<?php endif; ?> >星期四</option>
										<option value="5" <?php if(($data["book_reminder_first"]) == "5"): ?>selected<?php endif; ?> >星期五</option>
										<option value="6" <?php if(($data["book_reminder_first"]) == "6"): ?>selected<?php endif; ?> >星期六</option>
										<option value="7" <?php if(($data["book_reminder_first"]) == "7"): ?>selected<?php endif; ?> >星期日</option>
									</select>
								</div>
							</div>
							<div class="space-4"></div>

							<div class="form-group">
								<label class="col-sm-2 control-label no-padding-right"> 还书提醒(二)：  </label>
								<div class="col-sm-10">
									<select name="book_reminder_second"  class="col-sm-3" >
										<option value="0" <?php if(($data["book_reminder_second"]) == "0"): ?>selected<?php endif; ?> >-请选择-</option>
										<option value="1" <?php if(($data["book_reminder_second"]) == "1"): ?>selected<?php endif; ?> >星期一</option>
										<option value="2" <?php if(($data["book_reminder_second"]) == "2"): ?>selected<?php endif; ?> >星期二</option>
										<option value="3" <?php if(($data["book_reminder_second"]) == "3"): ?>selected<?php endif; ?> >星期三</option>
										<option value="4" <?php if(($data["book_reminder_second"]) == "4"): ?>selected<?php endif; ?> >星期四</option>
										<option value="5" <?php if(($data["book_reminder_second"]) == "5"): ?>selected<?php endif; ?> >星期五</option>
										<option value="6" <?php if(($data["book_reminder_second"]) == "6"): ?>selected<?php endif; ?> >星期六</option>
										<option value="7" <?php if(($data["book_reminder_second"]) == "7"): ?>selected<?php endif; ?> >星期日</option>
									</select>
								</div>
							</div>
							<div class="space-4"></div>

							<div class="form-group">
								<label class="col-sm-2 control-label no-padding-right"> 借书日期(一)：  </label>
								<div class="col-sm-10">
									<select name="borrow_first"  class="col-sm-3" >
										<option value="0" <?php if(($data["borrow_first"]) == "0"): ?>selected<?php endif; ?> >-请选择-</option>
										<option value="1" <?php if(($data["borrow_first"]) == "1"): ?>selected<?php endif; ?> >星期一</option>
										<option value="2" <?php if(($data["borrow_first"]) == "2"): ?>selected<?php endif; ?> >星期二</option>
										<option value="3" <?php if(($data["borrow_first"]) == "3"): ?>selected<?php endif; ?> >星期三</option>
										<option value="4" <?php if(($data["borrow_first"]) == "4"): ?>selected<?php endif; ?> >星期四</option>
										<option value="5" <?php if(($data["borrow_first"]) == "5"): ?>selected<?php endif; ?> >星期五</option>
										<option value="6" <?php if(($data["borrow_first"]) == "6"): ?>selected<?php endif; ?> >星期六</option>
										<option value="7" <?php if(($data["borrow_first"]) == "7"): ?>selected<?php endif; ?> >星期日</option>
									</select>
								</div>
							</div>
							<div class="space-4"></div>

							<div class="form-group">
								<label class="col-sm-2 control-label no-padding-right"> 借书日期(二)：  </label>
								<div class="col-sm-10">
									<select name="borrow_second"  class="col-sm-3" >
										<option value="0" <?php if(($data["borrow_second"]) == "0"): ?>selected<?php endif; ?> >-请选择-</option>
										<option value="1" <?php if(($data["borrow_second"]) == "1"): ?>selected<?php endif; ?> >星期一</option>
										<option value="2" <?php if(($data["borrow_second"]) == "2"): ?>selected<?php endif; ?> >星期二</option>
										<option value="3" <?php if(($data["borrow_second"]) == "3"): ?>selected<?php endif; ?> >星期三</option>
										<option value="4" <?php if(($data["borrow_second"]) == "4"): ?>selected<?php endif; ?> >星期四</option>
										<option value="5" <?php if(($data["borrow_second"]) == "5"): ?>selected<?php endif; ?> >星期五</option>
										<option value="6" <?php if(($data["borrow_second"]) == "6"): ?>selected<?php endif; ?> >星期六</option>
										<option value="7" <?php if(($data["borrow_second"]) == "7"): ?>selected<?php endif; ?> >星期日</option>
									</select>
								</div>
							</div>
							<div class="space-4"></div>

							<div class="form-group">
								<label class="col-sm-2  control-label no-padding-right"> 备注： </label>
								<div class="col-sm-10">
									<textarea class="col-sm-6" rows="3" name="school_desc" placeholder="50字以内" class="col-xs-10 col-sm-10" maxlength="50"/><?php echo ($data["school_desc"]); ?></textarea>
								</div>
							</div>
							<div class="space-4"></div>

							<div class="form-group">
								<label class="col-sm-2  control-label no-padding-right"> 收费模式： </label>
								<div class="col-sm-10">
									<div class="radio">
										<label>
											<input name="charge_type" type="radio" class="ace" value="1" <?php if(($data["charge_type"]) == "1"): ?>checked="checked"<?php endif; ?> >
											<span class="lbl"> 线上</span>
										</label>
										<label>
											<input name="charge_type" type="radio" class="ace" value="2" <?php if(($data["charge_type"]) == "2"): ?>checked="checked"<?php endif; ?> >
											<span class="lbl"> 线下</span>
										</label>
									</div>
								</div>
							</div>
							<div class="space-4"></div>

							<div class="form-group">
								<label class="col-sm-2 control-label no-padding-right"> 优惠计费日期：  </label>
								<div class="col-sm-10">
									<input type="datetime" name="try_charge_time"  value="<?php if(!empty($data["try_charge_time"])): echo (date('Y-m-d',$data["try_charge_time"])); endif; ?>"  class="col-xs-10 col-sm-3 daterange"/>
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
<link rel="stylesheet" href="/Public/2017/assets/css/datepicker.css">
<script src="/Public/2017/assets/js/date-time/bootstrap-datepicker.js"></script>
<script src="/Public/2017/assets/js/jquery.validate.js" type="text/javascript"></script>
<script type="text/javascript">
	$(function(){
		$("form[name='school-edit']").validate({
			//debug: 'true',
			errorElement: 'span',
			errorClass: 'help-inline middle col-sm-5',
			focusInvalid: true,
			rules: {
				school_name: {required:true},
				school_leader: {required:true},
				leader_mobile: {required:true},
				school_teacher: {required:true},
				teacher_mobile: {required:true},
				province_id: {required:true},
				city_id: {required:true},
				school_num: {required:true},
				meal_type: {required:true},
				meal_market_price: {required:true},
				class_max_num: {required:true},
				semester_one_start: {required:true},
				semester_one: {required:true},
				semester_two_start: {required:true},
				semester_two: {required:true},
			},

			messages: {
				school_name: "请填写学校名称",
				school_leader: "请输入负责人",
				leader_mobile: "请输入负责人手机号",
				school_teacher: "请输入园长",
				teacher_mobile: "请输入园长手机号",
				province_id: "请选择省份",
				city_id: "请选择城市",
				school_num: "请填写规模",
				meal_type: "请选择套餐类型",
				meal_market_price: "请输入市场价",
				class_max_num: "请输入最大班级人数",
				semester_one_start: "请选择时间",
				semester_one: "请选择时间",
				semester_two_start: "请选择时间",
				semester_two: "请选择时间",
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
							layer.msg(data.info, {icon:5,time:800}, function(index){
								layer.close(index);
							});
							return false;
						}
					},
					error: function showError(responseText, statusText){
						layer.close(wait);
						if(statusText=='timeout'){
							layer.msg("服务器繁忙，请稍后再试！", {icon:5,time:1000});
							return;
						}else{
							layer.msg(statusText,{icon:5,time:1000});
						}
					},
					timeout: 5000,
					dataType: 'json'
				});
			},
		});
		$('select[name="province_id"]').change(function(){
			var id = $(this).val();
			$.get("/default.php/Admin/School/select/id/"+id,function(data,status){
				if(data.status == 1){
					var option = '<option value="">-请选择城市-</option>';
					$.each(data.info,function(){
						option += '<option value="'+this.region_id+'">'+this.region_name+'</option>'
					});
					$('select[name="city_id"]').html(option);
				}
			});
		});
		$('select[name="city_id"]').change(function(){
			var id = $(this).val();
			$.get("/default.php/Admin/School/select/id/"+id,function(data,status){
				if(data.status == 1){
					var option = '<option value="">-请选择区县-</option>';
					$.each(data.info,function(){
						option += '<option value="'+this.region_id+'">'+this.region_name+'</option>'
					});
					$('select[name="district_id"]').html(option);
				}
			});
		});
		$('.daterange').datepicker({autoclose: true});
	});
</script>
</body>
</html>