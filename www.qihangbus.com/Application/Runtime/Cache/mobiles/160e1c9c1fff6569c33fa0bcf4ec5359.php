<?php if (!defined('THINK_PATH')) exit();?><!doctype html>
<html class="m">
<head>
<meta charset="utf-8">
<meta http-equiv="x-dns-prefetch-control" content="on">
<meta name="viewport" content="initial-scale=1,minimum-scale=1,maximum-scale=1,user-scalable=no,minimal-ui">
<meta http-equiv="Cache-Control" content="no-siteapp"/>
<meta name="renderer" content="webkit">
<title>阅历</title>
<link rel="stylesheet" href="/Public/css/mobiles/base.css">
<link rel="stylesheet" href="/Public/css/mobiles/app.css">
<link rel="stylesheet" href="/Public/css/mobiles/iconfont.css">
</head>
<body>

<div class="wrap2"> 
<div class="jiaos_t jiaos_t_j">
<dl>
	<dt>
		<a href="<?php echo U('mobile.php/Ucenter/avatar',array('user_id'=>$userinfo['student_id'],'user_flag'=>3));?>">
		<img src="<?php echo ((isset($userinfo["student_avatar"]) && ($userinfo["student_avatar"] !== ""))?($userinfo["student_avatar"]):'/Public/images/mobiles/default.png'); ?>" alt="" />
		</a>
	</dt>
	<dd class="dd_renw"><div class="d_name"><?php echo ($userinfo["student_name"]); ?> <img src="/Public/images/mobiles/sex_w.png" alt="" /></div> <div class="d_renw">
<div class="dd_jind"><span class="s_jind"><i style=" width:<?php echo ($total); ?>%;"></i></span></div>
累计已阅读<?php echo ($total); ?>本绘本</div></dd></dl>
</div>
<div class="line_hr"></div>
<div class="datepicker"></div>

<div class="line_hr"></div>
	<?php if($l > '0'): ?><ul class="shub_list ">
		<div class="i_tit"><span class="p_date"><?php echo ($last_day); ?></span> 阅读绘本</div>
		<?php if(is_array($info)): $i = 0; $__LIST__ = $info;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><li class="li_shub">
			
			<dl class="shub_item">
				<dt><img src="<?php echo ($vo["book_thumb"]); ?>" alt="" class="p_thumb"/></dt>
				<dd class="dd_pname">
					<p class="p_title"><?php echo ($vo["book_name"]); ?></p>
					<p><span class="p_author"><?php echo ($vo["book_author"]); ?></span> 著</p>
				</dd>
			</dl>
			
		</li><?php endforeach; endif; else: echo "" ;endif; ?>
	</ul>
	<?php else: ?>
	<ul class="shub_list ">
		<div class="i_tit">无记录</div>
	</ul><?php endif; ?>
	<a href="<?php echo U('mobile.php/Ucenter/index');?>">
<span class="f_index"><span class="iconfont icon-shouyeshouye"></span></span>
</a>
</div>
<input type="hidden" id="nowyear" value=""/>
<input type="hidden" id="premonth" value=""/>
<input type="hidden" id="nowmonth" value=""/>
<script class="js_prejs" type="text/javascript" src="/Public/js/mobiles/lib/jquery.min.js"></script>
<script type="text/javascript" src="/Public/js/mobiles/jquery.datePicker-min.js"></script>
<link type="text/css" href="/Public/css/mobiles/rili.css" rel="stylesheet" />
<script type="text/javascript">
function getM(c,n){
console.log(c+'--c--'+n);
}  
	$(function(){
		var curdata = "<?php echo ($last_day); ?>";
		var now_month = "<?php echo ($now_month); ?>";
		var now_year = "<?php echo ($now_year); ?>";
		var datadata;
		var flag = true;
		$.ajax({
			type: "post",
			url: "<?php echo U('mobile.php/Borrowtoread/return_json/',array('student_id'=>$student_id));?>",
			async: false,
			dataType: "json",
			success: function(data) {
				datadata=data.dList;
			},
			error: function(XMLHttpRequest, textStatus, errorThrown)
			 {
				alert(textStatus);
			}
		});

		var ttt=new Date();
		var num = 0;
		$(".datepicker").datePicker({
			startDate: "2015-1-1",
			inline:true,
			selectMultiple:false,
			renderCallback:function(y, m, B, p){
				var str =m.getFullYear()+"-"+(m.getMonth()+1)+"-"+m.getDate();
				var month = B+1;
				num++;
				if(num == 35){
					if(p != now_year || month != now_month){
							$.ajax({
								type: "post",
								url: "/mobile.php?m=mobile.php&c=Borrowtoread&a=return_json&month="+month+"&year="+p+"&student_id="+<?php echo ($student_id); ?>,
								async: false,
								dataType: "json",
								success: function(data) {
									datadata=data.dList;
									flag = false;
								},
								error: function(XMLHttpRequest, textStatus, errorThrown)
								 {
									alert(textStatus);
								}
							});
					
					}
				}

				$.each(datadata,function(commentIndex, datadata2) {	
					if(str == curdata){
						y.addClass("selected");
					}
					if (str==datadata2.d) {
						y.addClass("hasdata");
						y.bind("click",function(event){

							if(y.hasClass("hasdata")){
								$("#calendar-NaN").children().find('td').removeClass('selected');
								y.addClass("selected");
								var da=new Date(y.find(".td_td").attr("da"));
								var str =da.getFullYear()+"-"+(da.getMonth()+1)+"-"+da.getDate(); 
								
								$.ajax({
									type: "post",
									url: "/mobile.php?m=mobile.php&c=Borrowtoread&a=ajax_history&str_date="+encodeURI(str)+"&student_id="+<?php echo ($student_id); ?>,
									async: false,
									dataType: "json",
									success: function(data) {
										
										if(data.length > 0){
											var str = '';
											$(".li_shub").remove();
											for(i=0;i<data.length;i++){
												$(".p_date").text(data.last_day);	
												str += '<li class="li_shub">';
												str += '<dl class="shub_item">';	
												str += '<dt><img src="'+data[i].book_thumb+'" alt="" class="p_thumb"/></dt>';
												str += '<dd class="dd_pname">';
												str += '<p class="p_title">'+data[i].book_name+'</p>';
												str += '<p><span class="p_author">'+data[i].book_author+'</span> 著</p>';
												str += '</dd></dl></li>';
											}
											$(".i_tit").append(str);
										}else{
											$(".i_tit").html("无记录");
										}	
									},
									error: function(XMLHttpRequest, textStatus, errorThrown)
									 {
										alert(textStatus);
									}
								});
							}
						});
					}
				});
			}
		});
	
	});
</script>
</body>
</html>