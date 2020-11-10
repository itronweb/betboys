var mobile_menu = false;
$(document).ready(function() {
	jQuery('.tr-click').click(function($event){
		if($event.target.parentNode.nodeName != 'UL' && $event.target.parentNode.nodeName.toLowerCase() != 'ul' && $event.target.parentNode.nodeName != 'LI' && $event.target.parentNode.nodeName.toLowerCase() != 'li'){
			var $link = $(this).data('link-to-tr');
			window.location = $link;
		}
	});
    $('.mainnav li a').removeClass("active");
    $('.mainnav li a.home').addClass("active");
    setInterval(function() {
        updateTimers();
    }, 1000);
	$(".mobile-menu-action").click(function() {
		if(mobile_menu) {
			mobile_menu = false;
			$(".mobile-menu").hide();
			$(".mobile-right-menu").hide();
			$(".mobile-left-menu").hide();
		} else {
			mobile_menu = true;
			$(".mobile-menu").show();
			var marginLeft = direction=="ltr"?"-100%":"100%";
			$(".mobile-menu").css({marginLeft:marginLeft});
			$(".mobile-menu").animate({marginLeft: '0%'}, 250);
		}
	});

	$(".mobile-menu-bet-action").click(function() {
		if(mobile_menu) {
			mobile_menu = false;
			$(".mobile-menu").hide();
			$(".mobile-right-menu").hide();
			$(".mobile-left-menu").hide();
		} else {
			mobile_menu = true;
			$(".mobile-right-menu").show();
			var marginLeft = direction=="ltr"?"100%":"-100%";
			$(".mobile-right-menu").css({marginLeft:marginLeft});
			$(".mobile-right-menu").animate({marginLeft: '0%'}, 250);
		}
	});

	$(".mobile-menu-filter-action").click(function() {
		if(mobile_menu) {
			mobile_menu = false;
			$(".mobile-menu").hide();
			$(".mobile-right-menu").hide();
			$(".mobile-left-menu").hide();
		} else {
			mobile_menu = true;
			$(".mobile-left-menu").show();
			var marginLeft = direction=="ltr"?"100%":"-100%";
			$(".mobile-left-menu").css({marginLeft:marginLeft});
			$(".mobile-left-menu").animate({marginLeft: '0%'}, 250);
		}
	});
});