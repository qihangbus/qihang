<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html>
<html style="font-size: 250px;" data-dpr="1">
<head>
<meta http-equiv="content-type" content="text/html; charset=UTF-8">
<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
<meta name="viewport" content="width=device-width,initial-scale=1,minimum-scale=1,maximum-scale=1,user-scalable=no">
<meta name="format-detection" content="telephone=no">
<meta name="apple-mobile-web-app-capable" content="yes">
<meta name="apple-mobile-web-app-status-bar-style" content="black">
<title>发帖_启航巴士</title>
<link href="/Public/css/mobiles/main.min.css" rel="stylesheet">
<style>	
	.z_photo {
		width: 100%;

		padding: 0.1rem 0rem 0.1rem 0.3rem;
		overflow: auto;
		clear: both;
		margin: 0;
		border: none;
	}
	
	.z_photo img {
		width: 1rem;
		height: 1rem;
	}
	
	.z_addImg {
		float: left;
		margin-right: 0.1rem;
	}
	
	.z_file {
		width: 1rem;
		height: 1rem;
		background: url(/Public/images/mobiles/z_add.png) no-repeat;
		background-size: 100% 100%;
		float: left;
		margin-right: 0.1rem;
	}
	
	.z_file input::-webkit-file-upload-button {
		width: 1rem;
		height: 1rem;
		border: none;
		position: absolute;
		outline: 0;
		opacity: 0;
	}
	
	.z_file input#file {
		display: block;
		width: auto;
		border: 0;
		vertical-align: middle;
	}
	/*遮罩层*/
	
	.z_mask {
		width: 100%;
		height: 100%;
		background: rgba(0, 0, 0, .5);
		position: fixed;
		top: 0;
		left: 0;
		z-index: 999;
		display: none;
	}
	
	.z_alert {
		width: 3rem;
		height: 2rem;
		border-radius: .2rem;
		background: #fff;
		font-size: .24rem;
		text-align: center;
		position: absolute;
		left: 50%;
		top: 50%;
		margin-left: -1.5rem;
		margin-top: -2rem;
	}
	
	.z_alert p:nth-child(1) {
		line-height: 1.5rem;
	}
	
	.z_alert p:nth-child(2) span {
		display: inline-block;
		width: 49%;
		height: .5rem;
		line-height: .5rem;
		float: left;
		border-top: 1px solid #ddd;
	}
	
	.z_cancel {
		border-right: 1px solid #ddd;
	}

    </style>
</head>
<body>
<div id="default-wrapper" class="default-wrapper js_uploadBox" style="visibility: visible;">
	<form name="form1" id="form1" action="<?php echo U('sns.php/Forum/edit_handle');?>" enctype="multipart/form-data" method="post" onsubmit="return check();">
	<input type="hidden" name="user_id" value="<?php echo ($user_id); ?>">
	<input type="hidden" name="school_id" value="<?php echo ($school_id); ?>">
	<input type="hidden" name="user_flag" value="<?php echo ($user_flag); ?>">
	<input type="hidden" name="fake_id" value="<?php echo ($fake_id); ?>">
	<input type="hidden" id="fp_id" name="fp_id" value="<?php echo ($fp_id); ?>">
	<div class="default-timeline-wrapper">
		<input type="text" name="title" style="width:100%;margin-bottom:5px;height:35px;line-height:35px;padding:5px 15px;border:none;" placeholder="帖子标题">

		<textarea name="content" style="width:100%;margin-bottom:5px;height:120px;padding:5px 15px;border:none;font-size:13px;" placeholder="写几句准备发帖子啦......"></textarea>	
		
		<div style="border-bottom:1px solid #e9ecf1;height:auto;background-color:#FFFFFF;width:100%;">
			<!--    照片添加    -->
			<div class="z_photo">
				<div class="z_file">
					<input type="file" name="file[]" class="file js_upFile" id="files" value="" accept="image/*" multiple onchange="imgChange('z_photo','z_file');" />
					
				</div>

			</div>
			<div class="js_showBox" style="display:none;"></div>
			<!--遮罩层-->
			<div class="z_mask">
				<!--弹出框-->
				<div class="z_alert">
					<p>确定要删除这张图片吗？</p>
					<p>
						<span class="z_cancel">取消</span>
						<span class="z_sure">确定</span>
					</p>
				</div>
			</div>
			
		</div>	
		<input type="submit" style="clear:both;background-color:#ff964b;display:block;border:none;border-radius:5px;height:0.6rem;line-height:0.6rem;margin:5px auto;width:90%;color:#ffffff;background-color:#ff964b;" value="发送">
	</div>
	
	</form>
</div>
<script class="js_prejs" type="text/javascript" src="/Public/js/mobiles/lib/jquery.min.js"></script>
<script src="/Public/js/mobiles/layer/layer.js"></script>
<script src="/Public/js/mobiles/ajaxfileupload.js"></script>
<script type="text/javascript">
        //文件上传
		//px转换为rem
        (function(doc, win) {
            var docEl = doc.documentElement,
                resizeEvt = 'orientationchange' in window ? 'orientationchange' : 'resize',
                recalc = function() {
                    var clientWidth = docEl.clientWidth;
                    if (!clientWidth) return;
                    if (clientWidth >= 640) {
                        docEl.style.fontSize = '100px';
                    } else {
                        docEl.style.fontSize = 100 * (clientWidth / 640) + 'px';
                    }
                };

            if (!doc.addEventListener) return;
            win.addEventListener(resizeEvt, recalc, false);
            doc.addEventListener('DOMContentLoaded', recalc, false);
        })(document, window);

        function imgChange(obj1, obj2) {
            //获取点击的文本框
            var x = document.getElementById("files");
			if (!x || !x.value) return;
			var patn = /\.jpg$|\.jpeg$|\.png$|\.gif$/i;
			if (!patn.test(x.value)) {
				alert("您选择的似乎不是图像文件。");
				x.value = "";
				return;
			}
			
			//var file = document.getElementById("file");
            var file = document.getElementsByClassName("file")[0];
			var arr = file.value;
			
			//存放图片的父级元素
            var imgContainer = document.getElementsByClassName(obj1)[0];
            //获取的图片文件
            var fileList = file.files;
            //文本框的父级元素
            var input = document.getElementsByClassName(obj2)[0];
            var imgArr = [];
            //遍历获取到得图片文件
            for (var i = 0; i < fileList.length; i++) {
                var imgUrl = window.URL.createObjectURL(file.files[i]);
				
                imgArr.push(imgUrl);
                var img = document.createElement("img");
                img.setAttribute("src", imgArr[i]);
				
                var imgAdd = document.createElement("div");
                imgAdd.setAttribute("class", "z_addImg");
                imgAdd.appendChild(img);
				
                imgContainer.appendChild(imgAdd);
            };
			
			var elementIds = ["files"]; //flag为id、name属性名
			$.ajaxFileUpload({
				url: "<?php echo U('sns.php/Forum/upload_img');?>",//上传的url，根据自己设置
				type: 'post',
				secureuri: false, //一般设置为false
				fileElementId: 'files', // 上传文件的id、name属性名
				dataType: 'text', //返回值类型，一般设置为json、application/json
				elementIds: elementIds, //传递参数到服务器
				success: function (data, status) {
					if (data == "Error1") {
						alert("文件太大，请上传不大于5M的文件！");
						return;
					} else if (data == "Error2") {
						alert("上传失败，请重试！");
						return;
					} else {
						var fp_id = document.getElementById("fp_id").value;
						if(fp_id == null || fp_id == ''){
							fp_id = data;
						}else{
							fp_id = fp_id + ',' + data;
						}
						document.getElementById("fp_id").value=fp_id;
						img.setAttribute("fid", data);
						img.setAttribute("class", "imgs");
					}
				},
				error: function (data, status, e) {
					alert(e);
				}
			});
			
            imgRemove();
        };

        function imgRemove() {
            var imgList = document.getElementsByClassName("z_addImg");
            var mask = document.getElementsByClassName("z_mask")[0];
            var cancel = document.getElementsByClassName("z_cancel")[0];
            var sure = document.getElementsByClassName("z_sure")[0];
            for (var j = 0; j < imgList.length; j++) {
                imgList[j].index = j;
                imgList[j].onclick = function() {
                    var t = this;
					var fid = t.getElementsByClassName("imgs")[0].getAttribute('fid');
                    mask.style.display = "block";
                    cancel.onclick = function() {
                        mask.style.display = "none";
                    };
                    sure.onclick = function() {
                        mask.style.display = "none";
                        t.style.display = "none";
						var ret = 0;
						var fp_id = document.getElementById("fp_id").value;
						if(fp_id.indexOf(",") > 0){
							var arr = fp_id.split(",");
							alert(ret);
							for(var i=0;i<arr.length;i++){
								if(fid != arr[i] && ret == 0){
									ret = arr[i];
								}else if(fid != arr[i]){
									ret += ','+arr[i];
								}
							}
						}
						document.getElementById("fp_id").value = ret;
						//str.replace(/Microsoft/, "W3School");
                    };

                }
            };
        }

		function check()
		{
			var title = $("input[name='title']").val();
			var content = $("textarea[name='content']").val();

			if(title == ''){
				layer.msg('请输入标题',{time:800});
				return false;
			}

			if(content == ''){
				layer.msg('请输入内容',{time:800});
				return false;
			}
			return true;
		}

    </script>
</body>
</html>