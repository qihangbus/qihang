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

                <div class="row maintop hidden-xs">
                    <div class="col-xs-10">
                        <form name="admin_list_sea" class="form-search" method="get">
                            <div class="input-group col-xs-12">
                                <div class="col-sm-7 col-xs-12" style="padding-left:1px;position: relative;display: table;border-collapse: separate;">
                                    <span class="input-group-addon col-xs-1" style="border-width: 1px 1px;padding: 7px 19px 7px 6px;">
                                                <i class="ace-icon fa fa-check"></i>
                                    </span>
                                    <input name="value" value="<?php echo ($value); ?>" type="text" class="admin_sea col-sm-3 col-xs-10" style="margin-right:5px" placeholder="输入学校名称">
                                </div>
                                <div class="col-xs-5">
                                    <span class="input-group-btn inline">
                                        <button type="submit" class="btn btn-xs  btn-purple">
                                            <span class="ace-icon fa fa-search icon-on-right bigger-110"></span>
                                            搜索
                                        </button>
                                    </span>
                                    &nbsp;
                                    <span class="input-group-btn inline"  style="margin: 0px 48px;">
                                        <button id="clear" type="reset" class="btn btn-xs btn-warning">
                                            <span class="ace-icon fa fa-trash icon-on-right bigger-110"></span>
                                            清空
                                        </button>
                                    </span>
                                    &nbsp;
                                    <span class="input-group-btn inline">
                                        <a href="/default.php/Admin/School/index">
                                            <button type="button" class="btn btn-xs  btn-purple">
                                                <span class="ace-icon fa fa-globe icon-on-right bigger-110"></span>
                                                显示全部
                                            </button>
                                        </a>
                                    </span>

                                </div>
                            </div>
                        </form>
                    </div>
                    <div class="col-xs-1 hidden-xs">
                        <div class="input-group-btn">
                            <a href="javascript:void(0);" id="tpl-sms">
                                <button type="button" class="btn btn-xs  btn-info">
                                    发送模板消息
                                </button>
                            </a>
                        </div>
                    </div>
                    <div class="col-xs-1 hidden-xs">
                        <div class="input-group-btn">
                            <a href="javascript:void(0);" id="send-few">
                                <button type="button" class="btn btn-xs  btn-success">
                                    <span class="ace-icon fa  fa-envelope-o icon-on-right bigger-110"></span>
                                    批量发消息
                                </button>
                            </a>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-xs-12">
                        <div>
                            <table width="100%" class="table table-striped table-bordered table-hover" id="dynamic-table">
                                <form>
                                    <thead>
                                    <tr>
                                        <th width="5%" class="center hidden-xs">
                                            <label class="pos-rel">
                                                <input type="checkbox" class="ace"  id='chkAll' onclick='CheckAll(this.form)' value="全选"/>
                                                <span class="lbl"> </span>
                                            </label>
                                        </th>
                                        <th>名称</th>
                                        <th>省份</th>
                                        <th>城市</th>
                                        <th>规模</th>
                                        <th>注册人数</th>
                                        <th>交费人数</th>
                                        <th>今日交费</th>
                                        <th class="hidden-xs">订购率</th>
                                        <th>收费金额</th>
                                        <th class="hidden-xs">图书损耗</th>
                                        <th class="hidden-xs">补发目录</th>
                                        <th class="hidden-xs">导入年级班级</th>
                                        <th class="hidden-xs">绘本目录</th>
                                        <th class="hidden-xs">绘本替换</th>
                                        <th style="border-right:#CCC solid 1px;">操作</th>
                                    </tr>
                                    </thead>
                                    <form>
                                        <?php if(is_array($data)): foreach($data as $key=>$v): ?><tr>
                                                <td class="center hidden-xs">
                                                    <label class="pos-rel">
                                                        <input name='left_id[]' class="ace"  type='checkbox' value='<?php echo ($v["school_id"]); ?>'>
                                                        <span class="lbl"> </span>
                                                    </label>
                                                </td>
                                                <td><a href="<?php echo U('grade',['school_id'=>$v['school_id']]);?>"><?php echo ($v["school_name"]); ?></a></td>
                                                <td><?php echo ($v["province_name"]); ?></td>
                                                <td><?php echo ($v["city_name"]); ?></td>
                                                <td><?php echo ($v["school_num"]); ?></td>
                                                <td>
                                                    <?php if(($v["reg_num"]) == "0"): echo ($v["reg_num"]); ?>
                                                        <?php else: ?>
                                                        <a href="<?php echo U('student',['school_id'=>$v['school_id'],'type'=>1]);?>">
                                                            <?php echo ($v["reg_num"]); ?>
                                                        </a><?php endif; ?>
                                                </td>
                                                <td>
                                                    <?php if($v['pay_num'] == 0 or $v['pay_num'] == '等待开学'): echo ($v["pay_num"]); ?>
                                                        <?php else: ?>
                                                        <a href="<?php echo U('student',['school_id'=>$v['school_id'],'type'=>2]);?>">
                                                            <?php echo ($v["pay_num"]); ?>
                                                        </a><?php endif; ?>
                                                </td>
                                                <td><?php echo ($v["pay_today"]); ?></td>
                                                <td class="hidden-xs"><?php echo ($v["pay_percent"]); ?>%</td>
                                                <td>￥<?php echo ($v["fee"]); ?></td>
                                                <td class="hidden-xs"><?php echo ($v["book_lose_num"]); ?></td>
                                                <td class="hidden-xs">
                                                    <a href="<?php echo U('reissue',['id'=>$v['school_id']]);?>">
                                                        <button type="button" class="btn btn-minier btn-info">导出</button>
                                                    </a>
                                                </td>

                                                <td class="hidden-xs">
                                                    <?php if(($v["import_flag"]) == "1"): ?><button type="button" class="btn btn-minier">已导入</button>
                                                        <?php else: ?>
                                                        <a href="<?php echo U('classLeadin',['id'=>$v['school_id']]);?>">
                                                            <button type="button" class="btn btn-minier btn-yellow" data-id = <?php echo ($v['school_id']); ?>>导入</button>
                                                        </a><?php endif; ?>
                                                </td>

                                                <td class="hidden-xs">
                                                    <?php if(($v["import_flag"]) == "1"): ?><a href="<?php echo U('bookList',['id'=>$v['school_id']]);?>">
                                                            <button type="button" class="btn btn-minier btn-success">查看</button>
                                                        </a>
                                                        <?php else: ?>
                                                        <button type="button" class="btn btn-minier btn-yellow create" data-id = <?php echo ($v['school_id']); ?>>生成</button><?php endif; ?>
                                                </td>

                                                <td class="hidden-xs">
                                                    <!--
                                                    <?php if(($v["replace_status"]) == "1"): ?><button type="button" class="btn btn-minier">已替换</button>
                                                        <?php else: ?>
                                                        <a href="<?php echo U('dirReplace',['id'=>$v['school_id']]);?>">
                                                            <button type="button" class="btn btn-minier btn-yellow">替换</button>
                                                        </a><?php endif; ?>
                                                    -->
                                                    <a href="<?php echo U('dirReplace',['id'=>$v['school_id']]);?>">
                                                        <button type="button" class="btn btn-minier btn-yellow">替换</button>
                                                    </a>
                                                </td>

                                                <td>
                                                    <div class="action-buttons">
                                                        <?php if(($v["import_flag"]) == "1"): ?><a class="blue hidden-xs" href="<?php echo U('outExcel',array('id'=>$v['school_id']));?>" title="导出图书">
                                                                <i class="ace-icon fa fa-file-excel-o bigger-130"></i>
                                                            </a><?php endif; ?>
                                                        <a class="light-orange hidden-xs" href="javascript:void(0);" data-id="<?php echo ($v['school_id']); ?>" title="全园发送消息">
                                                            <i class="ace-icon fa fa-envelope-o bigger-130"></i>
                                                        </a>
                                                        <a class="green" href="<?php echo U('edit',array('id'=>$v['school_id']));?>" title="编辑">
                                                            <i class="ace-icon fa fa-pencil bigger-130"></i>
                                                        </a>
                                                        <a class="red hidden-xs" href="javascript:void(0);" data-id="<?php echo ($v["school_id"]); ?>" title="删除">
                                                            <i class="ace-icon fa fa-trash-o bigger-130"></i>
                                                        </a>
                                                    </div>
                                                </td>
                                            </tr><?php endforeach; endif; ?>
                                    </form>
                                    <tr>
                                        <td colspan="1" align="left" class="center hidden-xs"><button  id="btnsubmit" class="btn btn-white btn-yellow btn-sm">删</button></td>
                                        <td height="50" colspan="16" align="left"><?php echo ($page); ?></td>
                                    </tr>
                                    </tbody>
                                </form>
                            </table>

                        </div>
                    </div>
                </div>

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
<script src="/Public/2017/layer/plugin/layer.js"></script>
<style>
    .input-daterange input{ text-align:left; }
</style>
</body>
<script type="text/javascript">
    jQuery(function($) {
        $('.input-daterange').datepicker({autoclose: true});
    });

    $("#btnsubmit").click(function(){
        layer.confirm('你确定要删除吗？', {icon: 3}, function(index){
            layer.close(index);
            var ids='';
            $("input[name='left_id[]']:checkbox:checked").each(function() {
                ids += $(this).val() + ',';
            });
            ids = ids.substring(0,ids.length-1);
            if(ids == ''){
                layer.msg('至少选择一项!',{time:1000});
                return;
            }
            $.post(
                    "<?php echo U('delFew');?>",
                    {ids:ids},
                    function(data){
                        if(data.status == 1){
                            layer.msg(data.info,{icon:6,time:800},function(){
                                window.location.reload();
                            });
                        }else{
                            layer.msg(data.info,{icon:5});
                        }
                    }
            );
        });
    });

    $('.red').click(function(){
        var id = $(this).attr('data-id');
        layer.confirm('你确定要删除吗？', {icon: 3}, function (index) {
            $.post(
                    "<?php echo U('del');?>",
                    {id: id},
                    function (data) {
                        if (data.status == 1) {
                            layer.msg(data.info, {icon: 6,time:1000}, function () {
                                window.location.reload();
                            });
                        } else {
                            layer.msg(data.info, {icon: 5,time:1000});
                        }
                    }
            );
        });
    });

    $('.light-orange').click(function(){
        var id = $(this).attr('data-id');
        layer.prompt({
            formType: 2,
            title: '全园发送消息'
        }, function(value, index, elem){
            $.post(
                    "<?php echo U('sendMes');?>",
                    {id:id,message:value},
                    function(data){
                        layer.close(index);
                        if (data.status == 1) {
                            layer.msg(data.info, {icon: 6,time:1000});
                        } else {
                            layer.msg(data.info, {icon: 5,time:1000});
                        }
                    }
            );
        });
    });

    $('#send-few').click(function(){
        layer.prompt({
            formType: 2,
            title: '批量全园发送消息'
        }, function(value, index, elem){
            $.post(
                    "<?php echo U('sendFewMes');?>",
                    {message:value},
                    function(data){
                        layer.close(index);
                        if (data.status == 1) {
                            layer.msg(data.info, {icon: 6,time:1000});
                        } else {
                            layer.msg(data.info, {icon: 5,time:1000});
                        }
                    }
            );
        });
    });

    //生成目录
    $('.create').click(function(){
        var id = $(this).attr('data-id');
        var load = layer.load(2);
        $.post(
                "<?php echo U('createDir');?>",
                {id:id},
                function(data){
                    layer.close(load);
                    if(data.status == 1){
                        layer.msg(data.info,{icon:6,time:1000});
                        location.href = location.href;
                    }else{
                        layer.msg(data.info,{icon:5,time:1000});
                    }
                }
        );
    });

    function unselectall(){
        if(document.myform.chkAll.checked){
            document.myform.chkAll.checked = document.myform.chkAll.checked&0;
        }
    }
    function CheckAll(form){
        for (var i=0;i<form.elements.length;i++){
            var e = form.elements[i];
            if (e.Name != 'chkAll'&&e.disabled==false)
                e.checked = form.chkAll.checked;
        }
    }

    $('#tpl-sms').click(function(){
        var ids='';
        $("input[name='left_id[]']:checkbox:checked").each(function() {
            ids += $(this).val() + ',';
        });
        ids = ids.substring(0,ids.length-1);
        if(ids == ''){
            layer.msg('至少选择一项',{icon:5,time:3000});
            return false;
        }
        location.href="/default.php/Admin/School/sendTplSms/ids/"+ids;
    });
</script>
</html>