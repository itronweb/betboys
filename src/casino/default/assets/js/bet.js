	
	var bet_in_progress = false;	
	var total_money_to_pay = 0;
	var last_entered_amount = 0;

	$(document).ready(function() {
		if($(".slip-container").length>0) {
			bet_coupon();
		}
		$(".slip-container .slip-title").click(function() {
			var d = $(this).attr("data");
			$(".slip-container .slip-title").removeClass("slip-active");
			$(this).addClass("slip-active");
			$(".slip-container .amount-input").hide();
			$(".slip-container .amount-"+d).show();
			$(".slip-container").attr({"data":d});
			calculate_all();
		});
	});

	play_my_bet = function() {

		if(bet_in_progress) return false;
		bet_in_progress = true;

		if(total_money_to_pay>0) {
			$(".slip-container .slip-buttons").hide();
			$(".slip-container .form-loading").show();
		} else {
			bet_in_progress = false;
			return false;
		}

		swal({type:"info",title:language.bet_slip_wait,allowOutsideClick:false});
		swal.showLoading();

		var data = JSON.stringify(get_bet_list_data());
		var force = $(".slip-bet-force").is(":checked") ? "true" : "false";
		var currency = $(".slip-container .slip-currency-type").length>0 ? $(".slip-container .slip-currency-type").val() : "cash";

		$.post("/api/sport/bet/ticket", {data:data,force:force,currency:currency}, function(response) {

			var json = typeof response == "object" ? response : JSON.parse(response);
			var type = json.data.type != null ? json.data.type : "";

			if(json.result==null || json.data==null)
			{
				location.reload();
				return false;
			}

			if(type=="login")
			{
				location = "/user/login?return=" + encodeURIComponent(location.pathname);
				return false;
			}

			if(type=="topup")
			{
				location = "/user/topup";
				return false;
			}
			
			if(type=="wait")
			{
				var timeout = parseInt(json.data.timeout);
				var ticket  = json.data.ticket;
						
				setTimeout(function() {
					
					var force = $(".slip-bet-force").is(":checked") ? "true" : "false";
					var currency = $(".slip-container .slip-currency-type").length>0 ? $(".slip-container .slip-currency-type").val() : "cash";

					$.post("/api/sport/bet/play", {data:data,ticket:ticket,force:force,currency:currency}, function(resp) {
						var jsn = typeof resp == "object" ? resp : JSON.parse(resp);
						play_my_bet_result(jsn);
						
					}).fail(function(e) {
						location.reload();
					});
				}, timeout);

				return false;
			}

			if(json.data.message!=null)
			{
				$(".slip-container .form-loading").hide();
				$(".slip-container .slip-buttons").show();
				bet_in_progress = false;

				var type = json.result == "ok" ? "success" : "error";
				showMessage(json.data.message, type);

				return false;
			}

		}).fail(function(e) {
			location.reload();
		});

	}

	play_my_bet_result = function(response) {

		var result = response.result != null && response.result=="ok" ? "success" : "error";
		var message = response.data != null && response.data.message != null ? response.data.message : "";
		
		showMessage(message, result);

		if(result=="success")
		{
			if(response.data.balance!=null)
			{
				$(".user-balance-place").html(moneyFormat(parseInt(response.data.balance)/100));

				$("#slip-currency-cash").html(language.bet_slip_cash.replace('{amount}',moneyFormat(parseInt(response.data.balance)/100)));
				$("#slip-currency-bonus").html(language.bet_slip_bonus.replace('{amount}',moneyFormat(parseInt(response.data.bonus)/100)));
			}
			closeMobileBetPanel();
			deleteBetCookies();
			bet_coupon();
		
		} else {

			if(response.data.odds!=null) {

				var od = response.data.odds;
				var list = get_bet_list();

				for(i in od) {
					var odd = od[i];
					if(odd.ps=="lower" || odd.ps=="higher") {
						for(j in list) {
							if(list[j].odd_id==odd.id) list[j].price = odd.price;
						}
					}
				}

				save_bet_list(list);
				bet_coupon();

				for(i in od) {
					var odd = od[i];

					if(odd.status=="Open") {
						$(".bet-odd-overlay-"+odd.id).hide();
					} else {
						$(".bet-odd-overlay-"+odd.id).show();
					}
					if(odd.ps=="lower") {
						$(".bet-odd-price-"+odd.id).html(oddFormat(odd.price) + '&nbsp;<i class="fa fa-caret-down"></i>');
						$(".bet-odd-price-"+odd.id).attr({style:"color: red !important;"});
					} else if(odd.ps=="higher") {
						$(".bet-odd-price-"+odd.id).html(oddFormat(odd.price) + '&nbsp;<i class="fa fa-caret-up"></i>');
						$(".bet-odd-price-"+odd.id).attr({style:"color: green !important;"});
					}
				}

			}

		}

		$(".slip-container .form-loading").hide();
		$(".slip-container .slip-buttons").show();
		bet_in_progress = false;

	}

	get_bet_list = function() {
		return readBetCookies();
	}

	save_bet_list = function(lst) {
		saveBetCookies(lst);
	}

	add_to_list = function(d) {
		
		var list = get_bet_list();
		if(b_setting.maxbet>0 && list.length>=b_setting.maxbet) return false;

		for(i in list) {
			var c = list[i];
			//if(d.event_id==c.event_id) return false;
			if(d.event_id==c.event_id) list.splice(i,1);
		}

		list.push(d);

		save_bet_list(list);

		bet_coupon();

	}

	remove_from_coupon = function(id) {
		
		var list = get_bet_list();
		var news = [];

		for(i in list) {
			if(list[i].id==id) continue;
			news.push(list[i]);
		}

		save_bet_list(news);
		bet_coupon();

	}

	bet_coupon = function() {

		var list = get_bet_list();

		$(".slip-count-badge").html(numberFormat(list.length));
		if(list.length==0) $(".slip-count-badge").hide();
		if(list.length>0) $(".slip-count-badge").show();

		if(list.length==0) {
			$(".slip-container .bets-list").html("");
			$(".slip-container .bets-container").hide();
			$(".slip-container .no-bet").show();
			bet_selected_marker();
			return false;
		}

		var bcnt = "";
		for(i in list) bcnt = bcnt + bet_event(list[i]);

		var all_prices = [];
		for(i in list) all_prices.push(list[i].price);

		// Amounts
		$(".slip-container .amount-normal").html(bet_combination((list.length-1),list.length,all_prices,false));

		var system_amounts = "";
		for(i in list) system_amounts += bet_combination(i,list.length,all_prices,true);

		$(".slip-container .amount-system").html(system_amounts);

		$(".slip-container .no-bet").hide();
		$(".slip-container .bets-list").html(bcnt);
		$(".slip-container .bets-container").show();

		$(".delete-odd").unbind("click");
		$(".delete-odd").click(function() {
			remove_from_coupon($(this).attr("data"));
		});

		bet_text_listeners();
		bet_selected_marker();
		calculate_all();

	}

	bet_selected_marker = function() {
		var list = get_bet_list();
		
		// selected
		$(".selected-odd").removeClass("selected-odd");
		for(i in list) {
			$(".odd-link-"+list[i].odd_id).addClass("selected-odd");
			$(".odd-"+list[i].odd_id).addClass("selected-odd");
		}

		$(".slip-container .delete-slip").click(function() {
			deleteBetCookies();
			bet_coupon();				
		});

		$(".slip-container .play-my-bet").click(function() {
			play_my_bet();
		});
	}

	bet_event = function(e) {

		return 	'<div class="slip-event">' +
				'	<div class="slip-remove"><a href="javascript:;" class="fa fa-trash-o delete-odd" data="' + e.id + '"></a></div>' +
				'	<div class="slip-row slip-first-row">' + e.home_team_name + '</div>' +
				'	<div class="slip-row">' + e.away_team_name + '</div>' +
				'	<div class="slip-row slip-market">' + e.outcome_name + '</div>' +
				'	<div class="slip-row slip-bet">' + e.odd_name + '</div>' +
				'	<div class="slip-bet-price bet-odd-price-' + e.odd_id + '">' + oddFormat(e.price) + '</div>' +
				'	<div class="event-amount hidden"><input type="text" class="input-all-odds input-text-' + e.odd_id + '" rate="' + e.price + '" data="' + e.odd_id + '" value=""></div>' +
				'	<div class="slip-event-won hidden input-odd-profit-' + e.odd_id + '">0</div>' +
				'	<div class="end"></div>' +
				'</div>' +
				'<div class="event-changed bet-odd-overlay bet-odd-overlay-' + e.odd_id + '">' +
				'	<div class="background">' +
				'		<div class="text">' + language.odd_changed + '</div>' +
				'	</div>' +
				'</div>';

	}

	bet_combination = function(j,all,all_prices,system) {

		var t = parseInt(j) + 1;
		var k = fact(all) / (fact(t)*fact(all-t));

		var combs = get_comb(all_prices,t);

		var total_rate = 0;

		for(cbi in combs) {
			var subrate = 1;
			for(cbj in combs[cbi]) {
				var rt = parseFloat(combs[cbi][cbj]);
				subrate = subrate * rt;
			}
			total_rate += subrate;
		}

		if(b_setting.maxrate>0 && total_rate>b_setting.maxrate) total_rate=b_setting.maxrate;

		var total_rate_text = language.bet_split_total_rate.replace('{amount}',oddFormat(total_rate));

		if(!system)
		{

			var title = language.bet_split_amount.replace('{count}',numberFormat(t)).replace('{times}',numberFormat(k));

			return 	'<div>' +
					'	<div class="total-rate">' + total_rate_text + '</div>' +
					'	<div class="text">' + title + '</div>' +
					'	<div class="amount"><input type="text" class="slip-input slip-input-comb slip-comb-' + t + '" data="' + t + '" rate="' + total_rate + '" payment="' + k + '" value=""></div>' +
					'	<div class="end"></div>' +
					'</div>';
		}

		var title = language.bet_split_amounts.replace('{count}',numberFormat(t)).replace('{times}',numberFormat(k));

		return 	'<div>' +
				'	<div class="total-rate">' + total_rate_text + '</div>' +
				'	<div class="text">' + title + '</div>' +
				'	<div class="amount"><input type="text" class="slip-input slip-input-comb slip-comb-' + t + '" data="' + t + '" rate="' + total_rate + '" payment="' + k + '" value=""></div>' +
				'	<div class="end"></div>' +
				'</div>';

	}

	bet_text_listeners = function() {

		$(".slip-container .slip-input").unbind("keyup");
		$(".slip-container .slip-input").keyup(function() {
			var a = $(this).val().replace(new RegExp(m_format[2], 'g'), '').replace(m_format[1],'.');
			if(!isNumeric(a)) {
				$(this).val("");
				return false;
			}

			var a = parseFloat(a);
			if(a!=last_entered_amount)
			{
				last_entered_amount = a;
				var a = moneyFormatLtr(a) + "";
				if(m_format[0]==2)
				{
					if(a.substr(-2)=="00") a = a.substr(0,a.length-3);
					else if(a.substr(-1)=="0") a = a.substr(0,a.length-1);
				}
				$(this).val(a);
			}

			calculate_all();
		});

	}

	calculate_all = function() {

		total_money_to_pay = 0;
		var total_money_to_win = 0;

		var t = $(".slip-container").attr("data");

		$(".slip-container .amount-" + t + " .slip-input").each(function() {
			var t = $(this).attr("data");
			var rate = $(this).attr("rate");
			var me = $(this).val().replace(new RegExp(m_format[2], 'g'), '').replace(m_format[1],'.');
			if(isNumeric(me)) {
				me = parseFloat(me);
			} else {
				me = 0;
			}
			rate = parseFloat(rate);
			var result = me * rate;

			if(b_setting.maxwin>0 && result>b_setting.maxwin) result = b_setting.maxwin;

			var ptm = $(this).attr("payment");
			total_money_to_pay += (me * parseInt(ptm));
			total_money_to_win += result;

		});

		$(".slip-total-amount").html(moneyFormat(total_money_to_pay));
		$(".slip-won-amount").html(moneyFormat(total_money_to_win));

	}

	get_bet_list_data = function() {

		var data = new Object();
		data.list = get_bet_list();
		data.odds = [];
		data.comb = [];

		var t = $(".slip-container").attr("data");

		$(".slip-container .amount-" + t + " .slip-input").each(function() {
			var t = $(this).attr("data");
			var me = $(this).val().replace(new RegExp(m_format[2], 'g'), '').replace(m_format[1],'.');
			if(isNumeric(me)) {
				me = parseInt(parseFloat(me)*100);
				data.comb.push(new Object({id:t,amount:me}));
			}
		});

		return data;

	}

	get_comb = function(list, n) {
	    var set = [],
	        listSize = list.length,
	        combinationsCount = (1 << listSize),
	        combination;
	    for (var i = 1; i < combinationsCount ; i++ ){
	        var combination = [];
	        for (var j=0;j<listSize;j++){
	            if ((i & (1 << j))) {
	            	combination.push(list[j]);
	            }
	        }
	        if(combination.length==n) set.push(combination);
	    }
	    return set;
	}	

	fact = function(n) {
    	var r = 1;
		for(i=1; i<=n; i++) r = r * i;
    	return r;
	}

	isNumeric = function(n) {
	  return !isNaN(parseFloat(n)) && isFinite(n);
	}

	var storage_support = typeof(Storage) !== "undefined" ? true : false;

	if(storage_support)
	{
		try {
			localStorage.setItem("test",1);
			localStorage.removeItem("test");
		} catch(error) {
			storage_support = false;
		}
	}

	var last_bet_readed = false;
	var last_bet_data = [];

	readBetCookies = function() {
		
		if(storage_support)
		{
			if(localStorage.bets==undefined || localStorage.bets=="") return [];
			return JSON.parse(unescape(localStorage.bets));
		}

		if(last_bet_readed) return last_bet_data;

		var data = "";
		var cookies = getAllBetCookies();
		for(i in cookies)
		{
			var d = readCookie(cookies[i]);
			if(d!=null && d!="") data += d;
		}

		if(data=="") return [];
		last_bet_data = JSON.parse(unescape(data));
		last_bet_readed = true;
		return last_bet_data;
	}

	saveBetCookies = function(lst) {

		if(storage_support)
		{
			localStorage.setItem("bets", escape(JSON.stringify(lst)));
			return true;
		}

		deleteBetCookies();
		last_bet_data = lst;

		var d = escape(JSON.stringify(lst));
		var p = Math.ceil(d.length / 3500);
		for(var i=0; i<p; i++)
		{
			var h = d.substr(i*3500,3500);
			createCookie("bt_"+(i+100),h);
		}
	}

	deleteBetCookies = function() {

		if(storage_support)
		{
			localStorage.removeItem("bets");
			return true;
		}

		last_bet_data = [];
		var cookies = getAllBetCookies();
		for(i in cookies)
		{
			var expires = "; expires=Thu, 01 Jan 1970 00:00:01 GMT";
			document.cookie = cookies[i]+"="+expires+"; path=/";
		}
	}

	getAllBetCookies = function() {
		
		var all = [];
	    var ca = document.cookie.split(';');
	    for(var i=0;i < ca.length;i++) {
	        var c = ca[i];
	        while (c.charAt(0)==' ') c = c.substring(1,c.length);
	        if(c.indexOf("bt_") == 0) all.push(c.substring(0,c.indexOf("=")));
	    }
	    all.sort();
	    return all;
	}









