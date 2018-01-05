$(function(){
	function resentSearch(){
        var kwObj = $("#home-searchInput");
        var kwWidth = kwObj.width();
        var url = "http://www.58pic.com/index.php?m=searchtips&a=recentSearch&callback=?";
        $.getJSON(url, function(data) {
            if (data != "") {
                $("#keyup_d").html(data).css({
                    top: "42px",
                    width: kwWidth + 20 + "px"
                }).show();
                $("#keyup_d .sokeyup_2").width(kwWidth - 130);
            } else {
                $("#keyup_d").hide();
            }
        });
    }
    $("#home-searchInput").val("");
    $("#home-searchInput").focus(function(event) {
    	if($("#home-searchInput").val().length == 0){
    		resentSearch();
    	}
    });
	$("#home-searchInput").keyup(function(event){
	      if(window.event){
			  var key =window.event.keyCode;
		  }else{
		      var key =event.which;
		  }								  
		  var kwObj = $("#home-searchInput");//������
		  /*var kwHeight = kwObj.height();//������ĸ߿�*/
	      var kwWidth = kwObj.width();
		  if(key!=38&&key!=40&&key!=13){   //���������ֵ��Ϊup/down/enterʱ����ajax����
				var kw = kwObj.val();
				if(kw==""){
					resentSearch();
				}
				//�����ȡ������ʾ
				var url ="http://www.58pic.com/index.php?m=searchtips&a=search&kw="+kw+"&callback=?";
				$.getJSON(url,function(data){
					if (data!='') { //�������ֵ��Ϊ�գ�����ʾ������ʾ��Ϣ
						$("#keyup_d").html(data).css({"top": "42px", "width": kwWidth+20+"px"  }).show();
						$("#keyup_d .sokeyup_2").width(kwWidth-130);
					} else {
						$("#keyup_d").hide();
					}
				});
		  }
	});
	
	$("#home-searchInput").blur(function(){
	    setTimeout('$("#keyup_d").hide()', 500);
	});
	
	
	$("#keyup_d").on('mouseover','.sokeyup_1', function(){
	    var index=$("#keyup_d .sokeyup_1").index(this);
		$("#keyup_d .sokeyup_1").eq(index).css("background-color","#EBEBEB");
		$("#keyup_d .sokeyup_2").eq(index).css("background-color","#EBEBEB");
		$("#keyup_d .sokeyup_3").eq(index).css("background-color","#EBEBEB");
	});
	
	$("#keyup_d").on('mouseout','.sokeyup_1', function(){
	    var index=$(".sokeyup_1").index(this);
		$("#keyup_d .sokeyup_1").eq(index).css("background-color","#FFF");
		$("#keyup_d .sokeyup_2").eq(index).css("background-color","#FFF");
		$("#keyup_d .sokeyup_3").eq(index).css("background-color","#FFF");
	});
	$("#keyup_d").on('click','.sokeyup_1', function(){
	    var index=$("#keyup_d .sokeyup_1").index(this);
		var kw=$(".sokeyup_2").eq(index).html();
		$("#home-searchInput").val(kw);
		$("#home-searchIcon").trigger("click");
	});
	
});

$(function(){
    var ul_id=0;
	$("#home-searchInput").keyup(function(event){
		  
	      if(window.event){
			  var key =window.event.keyCode;
		  }else{
		      var key =event.which;
		  }								  
		  if(key!=38&&key!=40&&key!=13){
			  ul_id=0;
		  }else{
		  if($("#keyup_d").css("display")=="block"){
			  var ulcount=$("#keyup_d ul").length;
			  //����
			 if(key==38){
			    ul_id--;
				if(ul_id<1){
					ul_id = ulcount;
					keyup_over(ul_id);
					keyup_out(1);
				}else{
					keyup_out(ul_id+1);
					keyup_over(ul_id);
				}
				$("#home-searchInput").val($("#l_"+ul_id).html());
			    return false;
			 }
			 //����
			 if(key==40){
				ul_id++;
				if(ul_id>ulcount){
					ul_id=1;
					keyup_over(1);
					keyup_out(ulcount);
				}else{
					keyup_out(ul_id-1);
					keyup_over(ul_id);
				}
				$("#home-searchInput").val($("#l_"+ul_id).html());
				return false;
			 }
	     }else{
			ul_id=0;
		 }
		 }
	});
	function keyup_over(id){
		$("#u_"+id).css("background-color","#EBEBEB");$("#l_"+id).css("background-color","#EBEBEB");$("#r_"+id).css("background-color","#EBEBEB");
	}
	function keyup_out(id){
		$("#u_"+id).css("background-color","#fff");$("#l_"+id).css("background-color","#fff");$("#r_"+id).css("background-color","#fff");
	}
});
