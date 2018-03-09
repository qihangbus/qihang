
$(function () {

    var left = $('#choice_city');
    var bg = $('.bgDiv');
    var leftNav = $('#prvince');
    var citys = $('#citys');

    showNav(left, leftNav, "left");

    function showNav(btn, navDiv, direction) {
        btn.on('click', function () {
            bg.css({
                display: "block",
                transition: "opacity .5s"
            });
            if (direction == "left") {
                navDiv.css({
                    left: "0px",
                    transition: "left 1s"
                });
            }
        });
    }

    bg.on('click', function () {
        hideNav();
    });

    function hideNav() {
        leftNav.css({
            left: "-30%",
            transition: "left .5s"
        });
        bg.css({
            display: "none",
            transition: "display 1s"
        });
        citys.css({
            display: "none",
            transition: "display 1s"
        });
    }

	$('#prvince li').click(function(){
		$('#prvince li').removeClass("on");
		$(this).addClass("on");
		var provinceid = $(this).data("id");
		$.post("/index.php/Index/getCitys",{provinceid:provinceid},function(data){
			if(data.status){
				$('#citys').css("display", "block");
				$('#citys').html(data.result);			
			}		
		},"json");
	
	});
});




