<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html>
<html lang="en">
<head>
  <meta name="description" content="html教程，完善的html内容，使初学者迅速掌握html的精髓，猴子编写的梦之，都html教程" />
	<meta charset="UTF-8">           
	<title>我是小天才--21天亲子共读成长计划</title>
	<link href="/Public/css/mobiles/canvas.css" rel="stylesheet" type="text/css">
  <!-- <link rel="stylesheet" href="/Public/2017/css/share1.css"> -->
  <!-- <link rel="stylesheet" href="/Public/2017/css/share2.css" type="text/css" />  -->
 
</head>
<body>
<!-- <div style="display:none;">
    <img src="../../<?php echo ($info['image']); ?>" alt="" style="width:301px;height:301px;">
  </div> -->
	<canvas id="myCanvas">
	Your browser does not support the HTML5 canvas tag.
	</canvas>
<?php if($tan == 1): ?><div id="shareit" style="top:0px;position:fixed;width:100%;height:100%;background:rgba(0,0,0,0.85);">
    <img class="arrow" style="max-width:95%;position:absolute;right:10%;top:5%;width:40%;" src="/Public/2017/image/share-it.png">
    <a href="#" id="follow">
        <img style="width:90%;left:20%;margin-top:110%;" id="share-text" src="/Public/2017/image/share-text.png">
    </a>
</div><?php endif; ?>
<script src="/Public/js/mobiles/lib/jquery.min.js"></script>
<script src="/Public/js/mobiles/layer/mobile/layer.js"></script>
<script>
  var canvas=document.getElementById("myCanvas");
  var ctx = canvas.getContext("2d");
  ctx.canvas.width  = window.innerWidth;
  ctx.canvas.height = window.innerHeight;
</script>

<script>
    //准备img对象 
    var img = new Image(); 
    var img1 = new Image();
    // img1.src = './Public/images/1511405335.jpeg';
    // img1.src = './Public/images/542704807756015015.jpg';
    img1.src = "../../<?php echo ($info['image']); ?>";
    img.src = './Public/images/553787771094079183.jpg';
    // 加载完成开始绘制
    img.onload=function(){
       //准备canvas环境 
       var canvas=document.getElementById("myCanvas");
       var ctx=canvas.getContext("2d");
       // 绘制图片
       // ctx.drawImage(img,0,0,981,1641);
       ctx.drawImage(img,0,0,$(window).get(0).innerWidth,$(window).get(0).innerHeight);
       // 图片坐标x,y,w,h
       var w1 = Math.ceil((116/981)*$(window).get(0).innerWidth);
       var h1 = (543/1641)*$(document).height();
       // ctx.drawImage(img1,116,543,350,460);
       ctx.drawImage(img1,w1,h1,350,460);
       // 绘制水印
       ctx.font="43px microsoft yahei";
       // ctx.fillStyle = "rgba(255,255,255,0.5)";
       var w = (641/981)*$(window).get(0).innerWidth;
       var h = (703/1641)*$(window).get(0).innerHeight;
       ctx.fillText("<?php echo ($student_name); ?>",w,h);
       var w2 = (560/981)*$(window).get(0).innerWidth;
       var h2 = (866/1641)*$(window).get(0).innerHeight;
       ctx.fillText("<?php echo ($info['content']); ?>",w2,h2);
       var w3 = (380/981)*$(window).get(0).innerWidth;
       var h3 = (1256/1641)*$(window).get(0).innerHeight;
       ctx.fillText("<?php echo ($day); ?>",w3,h3);
       // ctx.fillText("19",380,1256);
    }

</script>
<script>
  $(window).resize(resizeCanvas);
	function resizeCanvas() {  
        $("#myCanvas").attr("width", $(window).get(0).innerWidth);  
        $("#myCanvas").attr("height", $(window).get(0).innerHeight);
        // context.fillRect(0, 0, $("#myCanvas").width(), $("#myCanvas").height());  
 };  
 resizeCanvas(); 
</script>
<script>
    $(function(){

        $("#shareit").on("click", function(){
            $("#shareit").hide();
        });
    });
</script>
</body>
</html>