var mobile_menu = false;


$(document).ready(function() {

	$.mask.definitions['9'] = '';
	$.mask.definitions['+'] = '[0-9]';	

	$(".text-format").each(function() {
		var f = $(this).attr("data-format");
		if(f!=undefined && f!="")
		{
			$(this).css({"direction":"ltr"});
			$(this).mask(f);
		}
	});

	$(".language-selector-link").click(function() {
		$(".language-selector").toggle();
	});

	$(".language-box a").click(function() {
		var iso = $(this).attr("data");
		createCookie("language",iso);
		$(".language-selector").hide();
		location.reload();
	});

	if($(".security_captcha").length>0) crateCaptcha();

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

	$(".box-title-action").click(function() {
		var box = $(this).attr("data-box");
		$("."+box).toggle();
	});

	$(".static-menu .selector").change(function() {
		location = $(this).val();
	});

	$(".text-money-format").css({"direction":"ltr"});

	$(".text-money-format").keyup(function() {

		var a = $(this).val().replace(new RegExp(m_format[2], 'g'), '').replace(m_format[1],'.');
		if(!isNumeric(a)) {
			$(this).val("");
			return false;
		}

		var lea = $(this).attr("data-lea");
		if(lea==undefined) lea = 0;

		var a = parseFloat(a);
		if(a!=lea)
		{
			$(this).attr({"data-lea":a});
			var a = moneyFormatLtr(a) + "";
			if(m_format[0]==2)
			{
				if(a.substr(-2)=="00") a = a.substr(0,a.length-3);
				else if(a.substr(-1)=="0") a = a.substr(0,a.length-1);
			}
			$(this).val(a);
		}

	});

	if($(".form-container .alert").length>0) {
		$(".form-container .alert").css({"max-width":$(".form-container .alert").parent().width()+"px"});	
	}

	setInterval(function() {
		$.get("/ping");
	}, 30000);

});

closeMobileBetPanel = function() {
	if(mobile_menu) {
		mobile_menu = false;
		$(".mobile-menu").hide();
		$(".mobile-right-menu").hide();
		$(".mobile-left-menu").hide();
	}
}

showMessage = function(text, type) {
	var button = (language.ok!=null)?language.ok:"OK";
	if(type!=null)
	{
		swal({type:type,title:"",html:"<font style='font-size:20px;'>"+text+"</font>",confirmButtonText:button});
	}
	else
	{
		swal({title:"",html:"<font style='font-size:20px;'>"+text+"</font>",confirmButtonText:button});
	}
}

openSplash = function(content,title) {

	$(".splash-view").hide();
	$(".splash-view .splash-content").html(content);
	if(title!=null) $(".splash-view .splash-title").html(title);
	else $(".splash-view .splash-title").html("");

	var max_width = parseInt($(window).width()) * 0.9;
	var max_height = parseInt($(window).height()) * 0.9;

	$(".splash-view .splash-content").attr({"style":"max-width: "+max_width+"px; max-height: "+max_height+"px;"});
	$(".splash-view").fadeIn('normal');

	$(".splash-close-button, .splash-view, .splash-container").unbind("click");
	$(".splash-close-button, .splash-view").click(function() {
		$(".splash-view").fadeOut();
	});
	$(".splash-container").click(function(event) {
		event.stopPropagation(); 
	});

}

crateCaptcha = function() {
	$(".security_captcha").attr({"src":"data:image/png;base64,"});
	$("#captcha").val("");
	$.post('/api/user/auth/captcha', function(response) {
		var json = typeof response == "object" ? response : JSON.parse(response);
		if(json.result!="ok") {
			location.reload();
			return false;
		}
		var code = json.data;
		$(".security_captcha").attr({"src":"data:image/png;base64,"+code.data});
		$(".security_captcha_hash").val(code.hash);
	});
}

function createCookie(name,value) {
	var date = new Date();
	date.setTime(date.getTime()+(30*24*60*60*1000));
	var expires = "; expires="+date.toUTCString();
    document.cookie = name+"="+value+expires+"; path=/";
}

function readCookie(name) {
    var nameEQ = name + "=";
    var ca = document.cookie.split(';');
    for(var i=0;i < ca.length;i++) {
        var c = ca[i];
        while (c.charAt(0)==' ') c = c.substring(1,c.length);
        if (c.indexOf(nameEQ) == 0) return c.substring(nameEQ.length,c.length);
    }
    return null;
}

function eraseCookie(name) {
	var expires = "; expires=Thu, 01 Jan 1970 00:00:01 GMT";
	document.cookie = name+"="+expires+"; path=/";
}

Number.prototype.formatMoney = function(c,d,t) {
if(c==null) c = m_format[0];
if(d==null) d = m_format[1];
if(t==null) t = m_format[2];
var n = this,
    s = n < 0 ? "-" : "", 
    i = String(parseInt(n = Math.abs(Number(n) || 0).toFixed(c))), 
    j = (j = i.length) > 3 ? j % 3 : 0;
   return s + (j ? i.substr(0, j) + t : "") + i.substr(j).replace(/(\d{3})(?=\d)/g, "$1" + t) + (c ? d + Math.abs(n - i).toFixed(c).slice(2) : "");
 };







