window.addEventListener('load', function () {
	FastClick.attach(document.body);
}, false);

$(function() {

	
})
var Loading = function(l) {
	if ($(".cui-mask").html() != null) {
		$(".cui-mask").remove()
	}
	var n = '<div class="cui-mask" id="loading2" style="display:"><div class="load-mod"><div class="load-circle"></div><div class="load-logo"></div></div></div>';
	$("body").append(n);
	
},
closeLoad = function() {
	$(".cui-mask").fadeOut(1000,	function() {
		$(".cui-mask").remove();
	})
};
var Loading2 = function(l) {
	if ($(".cui-mask2").html() != null) {
		$(".cui-mask2").remove()
	}
	var n = '<div class="cui-mask cui-mask2" style="background-color: rgba(255,255,255,0.1)"></div>';
	$("body").append(n);
	
},
closeLoad2 = function() {
	$(".cui-mask2").fadeOut("fast",	function() {
		$(".cui-mask2").remove();
	})
};

function toast(txt,times,fun){
	$('.tips-text').remove();
	$('.tips-mask').remove();
	if(!times){times=3000;}
	var div = $('<div class="tips-text" style="background-color:rgba(0,0,0,.8);max-width:240px;min-height: 20px;padding:12px 0;position: absolute;left: -1000px;top: -1000px;text-align: center;border-radius:6px;z-index:19891016;"><span style="color: #fff;line-height: 20px;padding:0 15px; min-height: 20px; dispaly:inline-block; font-size: 14px;">'+txt+'</span></div>');
	$('body').append(div);
	div.css('width',div.find("span").width()+30);
	div.css('zIndex',99999999);
	div.css('left',parseInt(($(window).width()-div.width())/2));
	//var top = parseInt($(window).scrollTop()+($(window).height()-div.height())/2);
	var top = parseInt($(window).scrollTop()+($(window).height()-div.height())/2)+100;
	div.css('top',top);
	$('body').append('<div class="tips-mask" style="position:fixed;top:0;left:0;width:100%;height:100%;z-index:19891015;background-color:rgba(0,0,0,.2);"></div>');
	
	
	setTimeout(function(){
		$('.tips-text').fadeOut(300);
		$('.tips-mask').fadeOut(300);
    	if(fun){
    		fun();
    	}
	},times);
	$(window).resize(function(){
		$('.tips-text').css('left',parseInt(($(window).width()-$('.tips-text').width())/2));
		var top = parseInt($(window).scrollTop()+($(window).height()-$('.tips-text').height())/2);
		$('.tips-text').css('top',top);	
	})
/*	 $(".tips-mask,.tips-text").click(function() {
		$('.tips-text').fadeOut(300);
		$('.tips-mask').fadeOut(300);
	 });*/
}

