
	oddFormat = function(n) {
		n = parseFloat(n).formatMoney(2);
		if(direction=="rtl") return toRtlNumber(n);
		return n;
	}

	timeFormat = function(n) {
		if(direction=="rtl") return toRtlNumber(n);
		return n;
	}

	scoreFormat = function(n) {
		if(direction=="rtl") return toRtlNumber(n);
		return n;
	}

	numberFormat = function(n) {
		if(direction=="rtl") return toRtlNumber(n);
		return n;
	}

	moneyFormat = function(n) {
		n = parseFloat(n).formatMoney();
		if(direction=="rtl") return toRtlNumber(n);
		return n;
	}

	moneyFormatLtr = function(n) {
		n = parseFloat(n).formatMoney();
		return n;
	}

	toRtlNumber = function(n) {
		if(typeof(pers_num)!="undefined" && !pers_num) return n;
		n = n + "";
		n = n.replace(new RegExp("\\.", 'g'), unescape(","));
		n = n.replace(new RegExp("0", 'g'), unescape("%u06F0"));
		n = n.replace(new RegExp("1", 'g'), unescape("%u06F1"));
		n = n.replace(new RegExp("2", 'g'), unescape("%u06F2"));
		n = n.replace(new RegExp("3", 'g'), unescape("%u06F3"));
		n = n.replace(new RegExp("4", 'g'), unescape("%u06F4"));
		n = n.replace(new RegExp("5", 'g'), unescape("%u06F5"));
		n = n.replace(new RegExp("6", 'g'), unescape("%u06F6"));
		n = n.replace(new RegExp("7", 'g'), unescape("%u06F7"));
		n = n.replace(new RegExp("8", 'g'), unescape("%u06F8"));
		n = n.replace(new RegExp("9", 'g'), unescape("%u06F9"));
		return n;
	}

	odd_selected = function(item, d, p, on) {

		var d = d.split("x");

		var data = new Object();

		data.id = new Date().getTime();
		data.event_id = d[0];
		data.outcome_id = d[1];
		data.odd_id = d[2];
		data.price = p;

		data.home_team_name = $(".event-"+data.event_id+" .home-team").length>0 ? $(".event-"+data.event_id+" .home-team").html() : $(".home-team").html();
		data.away_team_name = $(".event-"+data.event_id+" .home-team").length>0 ? $(".event-"+data.event_id+" .away-team").html() : $(".away-team").html();
		
		data.outcome_name = $(".market-type-"+data.outcome_id+" .market-name").html();
		if(data.outcome_name==undefined) data.outcome_name = findKeyword("result");

		data.odd_name = on;

		add_to_list(data);

	}

	var cloud_socket = function(address) {

		this.address = address;
		this.connection;

		this.connect = function(onData,onConnect,onClose) {
			
			if(this.connection!=null) {
				this.connection.close();
				this.connection = null;
			}

			this.connection = new WebSocket(this.address);

			this.connection.onopen = function(e) {
				if(onConnect!=null) onConnect();
			}

			this.connection.onmessage = function(e) {
				if(onData!=null) onData(e.data);
			} 

			this.connection.onclose = function(e) {
				if(onClose!=null) onClose();
			}

			this.connection.onerror = function(e) {
				if(onClose!=null) onClose();
			}

		}

		this.disconnect = function() {
			if(this.connection!=null) {
				this.connection.close();
				this.connection = null;
			}
		}

		this.send = function(data) {
			if(this.connection!=null && this.connection.readyState==1) {
				this.connection.send(JSON.stringify(data));
			}
		}

	}

	var socket = null;
	var langKeys = null;

	var team_name_markets = null;
	var market_priority = null;

	findKeyword = function(k) {
		var k2 = k.toLowerCase();
		if(langKeys==null || langKeys[k2]==null) return numberFormat(k);
		return numberFormat(langKeys[k2]);
	}

	$(document).ready(function() {

		var stn = $("#subscribe-team-names").val();
		if(stn!=undefined) team_name_markets = stn.split(',');

		var smp = $("#subscribe-market-priority").val();
		if(smp!=undefined) market_priority = smp.split(',');

		$.get("/api/sport/data/language", function(response) {
			var json = typeof response == "object" ? response : JSON.parse(response);
			if(json.result==null || json.result!="ok") {
				//location.reload();
				return false;
			}
			//if(json.data!=null) langKeys = json.data;
			langKeys = [];
			if(json.data!=null) {
				for(ijy in json.data) {
					var ijy2 = ijy.toLowerCase();
					langKeys[ijy2] = json.data[ijy];
				}
			}
			event_subscription();
		});

		setInterval(function() {
			calculateTimes();
		}, 1000);
		
	});

	event_subscription = function() {

		var address = $("#cloud-server").val();
		if(address==undefined || address=="") return false;

		var sevents = $("#subscribe-events").val();
		if(sevents==undefined || sevents=="") return false;

		var stype = $("#subscribe-type").val();
		if(stype==undefined || stype=="") return false;

		var sfields = $("#subscribe-fields").val();
		if(sfields==undefined || sfields=="") return false;

		var sses = $("#subscribe-session").val();

		address = getconnectionurl(address, false);

		socket = new cloud_socket(address);
		socket.connect(function(data) {
			var json = JSON.parse(data);
			subscription_data(json);

		}, function() {

			var sj = new Object({command:"subscribe",events:sevents.split(","),fields:sfields.split(","),type:stype,session:sses});

			var soutcomes = $("#subscribe-outcomes").val();
			if(soutcomes!=undefined && soutcomes!="") {
				var po = atob(soutcomes);
				sj = new Object({command:"subscribe",events:sevents.split(","),fields:sfields.split(","),type:stype,markets:JSON.parse(po),session:sses});
			}
				
			socket.send(sj);

		}, function() {
			setTimeout(function() {
				getconnectionurl("", true);
				location.reload();
			}, 2000);
		});

	}

	subscription_data = function(data) {

		if(data.command==null) return false;
		
		var command = data.command;
		
		if(command=="event") update_event(data);
		if(command=="event-changed") event_changed(data);
		if(command=="event-removed") event_removed(data);
		if(command=="markets-changed") markets_changed(data);
		
		bind_clicks();

		calculateTimes();

	}

	bind_clicks = function() {

		$(".odd-link").unbind("click");
		$(".odd-link").click(function() {
			var d = $(this).attr("data");
			if(d==undefined || d==null) return false;
			var b = $(this).hasClass("passive") || $(this).hasClass("passive-ev") || $(this).hasClass("passive-ma");
			var c = $(this).hasClass("passive-link") || $(this).hasClass("passive-link-ev") || $(this).hasClass("passive-link-ma");
			var p = $(this).attr("price");
			if(p==undefined || p==null) return false;
			var on = $(this).attr("oname");
			if(on==undefined || on==null) return false;
			if(b==false && c==false) odd_selected(this, d, p, on);
		});

	}

	update_event = function(data) {

		var spage = $("#subscribe-page").val();
		var details_page = (spage!=undefined && spage=="details") ? true : false;
		
		var sport = $("#subscribe-sport").val();

		for(i in data.events) {
			var e = data.events[i];
			if(e.status=="not_found") {
				$(".event-"+e.id).remove();
				$(".event-type").each(function() {
					if($(this).find(".event-row").length==0) $(this).remove();
				});
				$(".score-type").each(function() {
					if($(this).find(".score-row").length==0) $(this).remove();
				});
				continue;
			}

			var event_id = e.id;
			var event_data = e.data!=null?e.data:null;
			var event_status = e.info!=null&&e.info.status!=null?e.info.status:"";
			var event_suspended = e.info!=null&&e.info.suspended!=null?e.info.suspended:true;

			if(details_page) setEventDetails(e);

			var outcomes_finded = false;
			for(j in e.markets) {
				if(inArray2(e.markets[j].id,market_priority,e.pid)) {
					var odds = e.markets[j].odds;
					var odd_1 = null;
					var odd_2 = null;
					var odd_x = null;
					for(k in odds) {
						if(odds[k].name=="1") odd_1 = odds[k];
						if(odds[k].name=="2") odd_2 = odds[k];
						if(odds[k].name=="x") odd_x = odds[k];
					}
					var result = '<div class="market-box-'+e.markets[j].id+'">';
					if(odd_1!=null) {
						odd_1.name = $(".event-"+e.id+" .home-team").html();
						//odd_1.name = findKeyword("home");
						result = result + '<a href="javascript:;"  data="' + e.id + 'x' + e.markets[j].id + 'x' + odd_1.id + '" price="' + odd_1.price + '" oname="'+odd_1.name+'" class="odd-rate odd-main-button odd-link odd-' + odd_1.id + '">' + oddFormat(odd_1.price) + '</a>';
					} else {
						result = result + '<a href="javascript:;" class="odd-rate passive">...</a>';
					}
					if(odd_x!=null) {
						result = result + '<a href="javascript:;"  data="' + e.id + 'x' + e.markets[j].id + 'x' + odd_x.id + '" price="' + odd_x.price + '" oname="'+odd_x.name+'" class="odd-rate odd-main-button odd-link odd-' + odd_x.id + '">' + oddFormat(odd_x.price) + '</a>';
					} else {
						result = result + '<a href="javascript:;" class="odd-rate passive">...</a>';
					}
					if(odd_2!=null) {
						odd_2.name = $(".event-"+e.id+" .away-team").html();
						//odd_2.name = findKeyword("away");
						result = result + '<a href="javascript:;" data="' + e.id + 'x' + e.markets[j].id + 'x' + odd_2.id + '" price="' + odd_2.price + '" oname="'+odd_2.name+'" class="odd-rate odd-main-button odd-link odd-' + odd_2.id + '">' + oddFormat(odd_2.price) + '</a>';
					} else {

						result = result + '<a href="javascript:;" class="odd-rate passive">...</a>';
					}
					result = result + '</div>';

					$(".event-"+e.id+" .event-odds").html(result);
					outcomes_finded = true;
					break;
				}
			}

			if(!outcomes_finded) {
				var result = '<a href="javascript:;" class="odd-rate passive">...</a><a href="javascript:;" class="odd-rate passive">...</a><a href="javascript:;" class="odd-rate passive">...</a>';
				$(".event-"+e.id+" .event-odds").html(result);
			}

			for(j in e.markets) {
				var suspended = false;
				var market_id = e.markets[j].id;
				var market_suspended = e.markets[j].suspended;
				var o = e.markets[j];
				for(k in o.odds) {
					var odd = o.odds[k];
					if(odd.status!='Open') suspended = true;
					$(".odd-"+odd.id).html(oddFormat(odd.price));

					if(odd.status=='Open') {
						$(".odd-"+odd.id).removeClass("passive");
						$(".odd-link-"+odd.id).removeClass("passive-link");
					} else {
						$(".odd-"+odd.id).addClass("passive");
						$(".odd-link-"+odd.id).addClass("passive-link");
					}
				}
				if(market_suspended) {
					$(".event-"+event_id+" .market-box-"+market_id+" .odd-main-button").addClass("passive-ma");
					$(".event-"+event_id+" .market-box-"+market_id+" .odd-sub-button").addClass("passive-link-ma");
				} else {
					$(".event-"+event_id+" .market-box-"+market_id+" .odd-main-button").removeClass("passive-ma");
					$(".event-"+event_id+" .market-box-"+market_id+" .odd-sub-button").removeClass("passive-link-ma");
				}
			}

			if(event_suspended) {
				$(".event-"+event_id+" .odd-main-button").addClass("passive-ev");
				$(".event-"+event_id+" .odd-sub-button").addClass("passive-link-ev");
			} else {
				$(".event-"+event_id+" .odd-main-button").removeClass("passive-ev");
				$(".event-"+event_id+" .odd-sub-button").removeClass("passive-link-ev");
			}
			
			if(event_data!=null) {
				if(setEventTime(event_id, event_data, event_status) && details_page) {
					$(".time .period").hide();
				}
			}

			if(e.data!=null) {
				var scores = get_score(e.data);
				$(".event-"+e.id+" .home-score").html(timeFormat(scores.home));
				$(".event-"+e.id+" .away-score").html(timeFormat(scores.away));

				if(details_page)
				{
					if(sport=="1")
					{
						$(".home-score").html(timeFormat(scores.home));
						$(".away-score").html(timeFormat(scores.away));

						$(".home-cards").html("");
						$(".away-cards").html("");

						var stats = get_stats(e.data);
						for(ci=0; ci<stats.home.yellow; ci++) $(".home-cards").append('<div class="card mt5"></div>'+"\n");
						for(ci=0; ci<stats.home.red; ci++) $(".home-cards").append('<div class="red card mt5"></div>'+"\n");
						for(ci=0; ci<stats.away.red; ci++) $(".away-cards").append('<div class="red card mt5"></div>'+"\n");
						for(ci=0; ci<stats.away.yellow; ci++) $(".away-cards").append('<div class="card mt5"></div>'+"\n");						
					}
					else
					{
						var scores = get_full_score(e.data);
						if(scores["0"]!=null)
						{
							$(".home-score-0").html(scores["0"].home);
							$(".away-score-0").html(scores["0"].away);
						}
						if(scores["1"]!=null)
						{
							$(".home-score-1").html(scores["1"].home);
							$(".away-score-1").html(scores["1"].away);
						}
						if(scores["2"]!=null)
						{
							$(".home-score-2").html(scores["2"].home);
							$(".away-score-2").html(scores["2"].away);
						}
						if(scores["3"]!=null)
						{
							$(".home-score-3").html(scores["3"].home);
							$(".away-score-3").html(scores["3"].away);
						}
						if(scores["4"]!=null)
						{
							$(".home-score-4").html(scores["4"].home);
							$(".away-score-4").html(scores["4"].away);
						}
					}

				}

			}

		}

		bet_selected_marker();

	}

	event_changed = function(data) {

		var spage = $("#subscribe-page").val();
		var details_page = (spage!=undefined && spage=="details") ? true : false;
		
		var sport = $("#subscribe-sport").val();

		var event_id = data.event;
		var event_data = data.data!=null?data.data:null;
		var event_status = data.info!=null&&data.info.status!=null?data.info.status:"";
		var event_suspended = data.info!=null&&data.info.suspended!=null?data.info.suspended:true;

		if(event_data!=null) {
			if(setEventTime(event_id, event_data, event_status) && details_page) {
				$(".time .period").hide();
			}
		}

		if(data.data!=null) {
			var scores = get_score(data.data);
			$(".event-"+data.event+" .home-score").html(timeFormat(scores.home));
			$(".event-"+data.event+" .away-score").html(timeFormat(scores.away));
				
			if(details_page)
			{
				if(sport=="1")
				{
					$(".home-score").html(timeFormat(scores.home));
					$(".away-score").html(timeFormat(scores.away));

					$(".home-cards").html("");
					$(".away-cards").html("");

					var stats = get_stats(data.data);
					for(ci=0; ci<stats.home.yellow; ci++) $(".home-cards").append('<div class="card mt5"></div>'+"\n");
					for(ci=0; ci<stats.home.red; ci++) $(".home-cards").append('<div class="red card mt5"></div>'+"\n");
					for(ci=0; ci<stats.away.red; ci++) $(".away-cards").append('<div class="red card mt5"></div>'+"\n");
					for(ci=0; ci<stats.away.yellow; ci++) $(".away-cards").append('<div class="card mt5"></div>'+"\n");						
				}
				else
				{
					var scores = get_full_score(data.data);
					if(scores["0"]!=null)
					{
						$(".home-score-0").html(scores["0"].home);
						$(".away-score-0").html(scores["0"].away);
					}
					if(scores["1"]!=null)
					{
						$(".home-score-1").html(scores["1"].home);
						$(".away-score-1").html(scores["1"].away);
					}
					if(scores["2"]!=null)
					{
						$(".home-score-2").html(scores["2"].home);
						$(".away-score-2").html(scores["2"].away);
					}
					if(scores["3"]!=null)
					{
						$(".home-score-3").html(scores["3"].home);
						$(".away-score-3").html(scores["3"].away);
					}
					if(scores["4"]!=null)
					{
						$(".home-score-4").html(scores["4"].home);
						$(".away-score-4").html(scores["4"].away);
					}
				}

			}

		}

		if(event_suspended) {
			$(".event-"+event_id+" .odd-main-button").addClass("passive-ev");
			$(".event-"+event_id+" .odd-sub-button").addClass("passive-link-ev");
		} else {
			$(".event-"+event_id+" .odd-main-button").removeClass("passive-ev");
			$(".event-"+event_id+" .odd-sub-button").removeClass("passive-link-ev");
		}		

	}

	event_removed = function(data) {

		$(".event-"+data.event).fadeOut("slow", function() {
			$(".event-"+data.event).remove();
			setTimeout(function() {
				$(".event-type").each(function() {
					if($(this).find(".event-row").length==0) $(this).remove();
				});
			}, 3000);
		});

	}

	markets_changed = function(data) {

		var spage = $("#subscribe-page").val();
		var details_page = (spage!=undefined && spage=="details") ? true : false;

		var event_id = data.event;
		var pid = data.pid;
		var suspended = false;

		var new_markets = [];

		for(mi in data.markets) {

			var new_odds = [];

			var market_id = data.markets[mi].id;
			var market_suspended = data.markets[mi].suspended;

			if(market_suspended) {
				$(".event-"+event_id+" .market-box-"+market_id+" .odd-main-button").addClass("passive-ma");
				$(".event-"+event_id+" .market-box-"+market_id+" .odd-sub-button").addClass("passive-link-ma");
			} else {
				$(".event-"+event_id+" .market-box-"+market_id+" .odd-main-button").removeClass("passive-ma");
				$(".event-"+event_id+" .market-box-"+market_id+" .odd-sub-button").removeClass("passive-link-ma");
			}

			for(i in data.markets[mi].odds) {

				var odds = data.markets[mi].odds[i];

				if(odds.state=="new") {
					if($(".event-"+event_id+" .market-box-"+market_id).length>0) {
						var obj = {"id":odds.id,"odd":odds.odd,"price":odds.price,"status":odds.status,"type":odds.type,"update":0};
						if(odds.balance!=null) obj.most_balanced = odds.balance;
						if(odds.line!=null) obj.line = odds.line;
						if(odds.name!=null) obj.name = odds.name;
						new_odds.push(obj);
					}

				} else if(odds.state=="update") {
					
					if(odds.ps=="same") {
						
						var ex_odd = $(".odd-"+odds.id).html();
						var caret  = "";

						if(ex_odd!=undefined && ex_odd.indexOf("caret-up")>0) caret = ' <i class="fa fa-caret-up green-arrow"></i>';
						if(ex_odd!=undefined && ex_odd.indexOf("caret-down")>0) caret = ' <i class="fa fa-caret-down red-arrow"></i>';

						$(".odd-"+odds.id).html(oddFormat(odds.price)+caret);
					
					} else if(odds.ps=="higher") {
						$(".odd-"+odds.id).html(oddFormat(odds.price) + ' <i class="fa fa-caret-up green-arrow"></i>');
						add_odd_effect(".odd-"+odds.id, "green");
					
					} else if(odds.ps=="lower") {
						$(".odd-"+odds.id).html(oddFormat(odds.price) + ' <i class="fa fa-caret-down red-arrow"></i>');
						add_odd_effect(".odd-"+odds.id, "red");

					}

					if(odds.status=='Open') {
						$(".odd-"+odds.id).removeClass("passive");
						$(".odd-link-"+odds.id).removeClass("passive-link");
					} else {
						$(".odd-"+odds.id).addClass("passive");
						$(".odd-link-"+odds.id).addClass("passive-link");
					}

					$(".odd-"+odds.id).attr({price:odds.price});
					$(".odd-link-"+odds.id).attr({price:odds.price});
					
				} else if(odds.state=="remove") {
					if(details_page) {
						$(".odd-"+odds.id).remove();
						$(".odd-link-"+odds.id).remove();
					} else {
						$(".odd-"+odds.id).addClass("passive");
					}
					
				}

			}

			if(new_odds.length>0) new_markets.push({"id":market_id,"suspended":market_suspended,"odds":new_odds});

		}

		// update markets
		if(new_markets.length>0) editMarkets(details_page, event_id, new_markets, pid);

	}

	editMarkets = function(details_page, event_id, new_markets, pid) {

		if(details_page) new_markets = sortOutcomes(event_id, new_markets, pid);

		for(j in new_markets) {

			var market_id = new_markets[j].id;
			var market_suspended = new_markets[j].suspended;

			if($(".event-"+event_id+" .market-box-"+new_markets[j].id).length==0) continue;

			if(details_page) {

				var odds = new_markets[i].odds;

				var total_odds = odds.length;
				if(total_odds==0) continue;

				var col_type = "";
				var frst_col = [];
				var scnd_col = [];
				var thrd_col = [];

				// Unique odd types
				var unique_type = 0;
				var unique_names = [];
				for(j in odds) {
					var finded = false;
					for(k in unique_names) {
						if(odds[j].name==unique_names[k]) {
							finded = true;
							break;
						}
					}
					if(finded==false) {
						unique_names.push(odds[j].name);
						unique_type += 1;
					}
				}

				col_type = "odd-triple";
				if(total_odds<3 || (total_odds%2==0 && total_odds%3!=0)) col_type = "odd-double";

				if(unique_type==2) col_type = "odd-double";
				if(unique_type==3) col_type = "odd-triple";

				if(col_type == "odd-double") {

					for(j in odds) {
						if(j<(total_odds/2)) frst_col.push(odds[j]);
						else scnd_col.push(odds[j]);
					}

				} else {

					for(j in odds) {
						if(j<(total_odds/3)) {
							frst_col.push(odds[j]);
						} else {
							if(j<((total_odds/3)*2)) {
								scnd_col.push(odds[j]);
							} else {
								thrd_col.push(odds[j]);
							}
						}
					}				

				}

				var d = '';

				var max = frst_col.length > scnd_col.length ? frst_col.length : scnd_col.length;
				max = thrd_col.length > max ? thrd_col.length : max;

				if(max>0) {
					for(j=0; j<max; j++) {
						for(x=0; x<3; x++) {

							var odd = null;

							if(x==0 && frst_col.length>=(j-1)) odd = frst_col[j];
							if(x==1 && scnd_col.length>=(j-1)) odd = scnd_col[j];
							if(x==2 && thrd_col.length>=(j-1)) odd = thrd_col[j];

							if(odd==null) continue;

							// 1-Under, 2-over fixing
							if(pid==1 && odd.type!=null && odd.type=="Live" && market_id==21) {
								if(odd.odd!=null && odd.odd==17) odd.name = 'under';
								else if(odd.odd!=null && odd.odd==18) odd.name = 'over';
							}

							var oname = findKeyword(odd.name);
							if(odd.line!=null && odd.line!="" && odd.line!="-1") oname = oname + " " + numberFormat(odd.line);

							d = d + '<a href="javascript:;" class="odd-link odd-sub-button odd-link-'+odd.id+' ' + col_type + '" data="'+event_id+'x'+market_id+'x'+odd.id+'" price="' + odd.price + '" oname="' + oname + '">';
							d = d + '<div class="odd-title"><span>'+findKeyword(odd.name)+'</span>';

							if(odd.line!=null && odd.line!="" && odd.line!="-1")
							{
								d = d + '<span class="odd-line">'+numberFormat(odd.line)+'</span>';
							}

							d = d + '</div>';
							d = d + '<div class="odd-rate odd-main-button odd-'+odd.id+'">'+oddFormat(odd.price)+'</div>';
							d = d + '</a>';
						}
					}
				}

				d = d + '<div class="clear"></div>';
				$(".market-box-"+market_id).append(d);

			} else {

				if(inArray2(new_markets[j].id,market_priority,pid)) {
					var odds = new_markets[j].odds;
					var odd_1 = null;
					var odd_2 = null;
					var odd_x = null;

					for(k in odds) {
						if(odds[k].name=="1") odd_1 = odds[k];
						if(odds[k].name=="2") odd_2 = odds[k];
						if(odds[k].name=="x") odd_x = odds[k];
					}
					var result = '<div class="market-box-'+new_markets[j].id+'">';
					if(odd_1!=null) {
						odd_1.name = $(".event-"+event_id+" .home-team").html();
						//odd_1.name = findKeyword("home");
						result = result + '<a href="javascript:;"  data="' + event_id + 'x' + new_markets[j].id + 'x' + odd_1.id + '" price="' + odd_1.price + '" oname="'+odd_1.name+'" class="odd-rate odd-main-button odd-link odd-' + odd_1.id + '">' + oddFormat(odd_1.price) + '</a>';
					} else {
						result = result + '<a href="javascript:;" class="odd-rate passive">...</a>';
					}
					if(odd_x!=null) {
						result = result + '<a href="javascript:;"  data="' + event_id + 'x' + new_markets[j].id + 'x' + odd_x.id + '" price="' + odd_x.price + '" oname="'+odd_x.name+'" class="odd-rate odd-main-button odd-link odd-' + odd_x.id + '">' + oddFormat(odd_x.price) + '</a>';
					} else {
						result = result + '<a href="javascript:;" class="odd-rate passive">...</a>';
					}
					if(odd_2!=null) {
						odd_2.name = $(".event-"+event_id+" .away-team").html();
						//odd_2.name = findKeyword("away");
						result = result + '<a href="javascript:;" data="' + event_id + 'x' + new_markets[j].id + 'x' + odd_2.id + '" price="' + odd_2.price + '" oname="'+odd_2.name+'" class="odd-rate odd-main-button odd-link odd-' + odd_2.id + '">' + oddFormat(odd_2.price) + '</a>';
					} else {

						result = result + '<a href="javascript:;" class="odd-rate passive">...</a>';
					}
					result = result + '</div>';

					$(".event-"+event_id+" .event-odds").html(result);

					if(odd_1!=null) {
						if(odd_1.status=='Open') {
							$(".odd-"+odd_1.id).removeClass("passive");
							$(".odd-link-"+odd_1.id).removeClass("passive-link");
						} else {
							$(".odd-"+odd_1.id).addClass("passive");
							$(".odd-link-"+odd_1.id).addClass("passive-link");
						}
					}

					if(odd_x!=null) {
						if(odd_x.status=='Open') {
							$(".odd-"+odd_x.id).removeClass("passive");
							$(".odd-link-"+odd_x.id).removeClass("passive-link");
						} else {
							$(".odd-"+odd_x.id).addClass("passive");
							$(".odd-link-"+odd_x.id).addClass("passive-link");
						}
					}

					if(odd_2!=null) {
						if(odd_2.status=='Open') {
							$(".odd-"+odd_2.id).removeClass("passive");
							$(".odd-link-"+odd_2.id).removeClass("passive-link");
						} else {
							$(".odd-"+odd_2.id).addClass("passive");
							$(".odd-link-"+odd_2.id).addClass("passive-link");
						}
					}

				}

			}

			if(market_suspended) {
				$(".event-"+event_id+" .market-box-"+market_id+" .odd-main-button").addClass("passive-ma");
				$(".event-"+event_id+" .market-box-"+market_id+" .odd-sub-button").addClass("passive-link-ma");
			} else {
				$(".event-"+event_id+" .market-box-"+market_id+" .odd-main-button").removeClass("passive-ma");
				$(".event-"+event_id+" .market-box-"+market_id+" .odd-sub-button").removeClass("passive-link-ma");
			}			

		}

	}

	setEventDetails = function(e) {
		
		var outcome_titles = "";

		e.markets = sortOutcomes(e.id,e.markets,e.pid);

		for(i in e.markets) {

			var market_id = e.markets[i].id;
			var market_suspended = e.markets[i].suspended;

			var odds = e.markets[i].odds;

			// Remove passive odds
			var new_odds = [];
			for(j in odds) {
				//if(inArray2(e.markets[i].id,market_priority,e.pid)) {
					if(odds[j].status!='Open' && parseInt(odds[j].update)>300000) continue;
				//}
				new_odds.push(odds[j]);
			}
			odds = new_odds;

			var total_odds = odds.length;

			if(total_odds==0) continue;
			
			var col_type = "";
			var frst_col = [];
			var scnd_col = [];
			var thrd_col = [];

			// Unique odd types
			var unique_type = 0;
			var unique_names = [];
			for(j in odds) {
				var finded = false;
				for(k in unique_names) {
					if(odds[j].name==unique_names[k]) {
						finded = true;
						break;
					}
				}
				if(finded==false) {
					unique_names.push(odds[j].name);
					unique_type += 1;
				}
			}

			col_type = "odd-triple";
			if(total_odds<3 || (total_odds%2==0 && total_odds%3!=0)) col_type = "odd-double";

			if(unique_type==2) col_type = "odd-double";
			if(unique_type==3) col_type = "odd-triple";

			if(col_type == "odd-double") {

				for(j in odds) {
					if(j<(total_odds/2)) frst_col.push(odds[j]);
					else scnd_col.push(odds[j]);
				}

			} else {

				for(j in odds) {
					if(j<(total_odds/3)) {
						frst_col.push(odds[j]);
					} else {
						if(j<((total_odds/3)*2)) {
							scnd_col.push(odds[j]);
						} else {
							thrd_col.push(odds[j]);
						}
					}
				}				

			}

			outcome_titles = outcome_titles + e.pid + '.' + market_id + ",";

			var d = '<div class="mt5 market-type market-type-'+market_id+'" data="'+market_id+'">' +
					'	<a href="javascript:;" class="title box-title-action" data-box="market-box-'+market_id+'"><span class="fa fa-caret-right"></span> <span class="market-name mn-'+e.pid+'-'+market_id+'"><!--outcome name--></span></a>' +
					'	<div class="odd-container market-box-'+market_id+'">';

			var max = frst_col.length > scnd_col.length ? frst_col.length : scnd_col.length;
			max = thrd_col.length > max ? thrd_col.length : max;

			if(max>0) {
				for(j=0; j<max; j++) {
					for(x=0; x<3; x++) {

						var odd = null;

						if(x==0 && frst_col.length>=(j-1)) odd = frst_col[j];
						if(x==1 && scnd_col.length>=(j-1)) odd = scnd_col[j];
						if(x==2 && thrd_col.length>=(j-1)) odd = thrd_col[j];

						if(odd==null) continue;

						// 1-Under, 2-over fixing
						if(e.pid!=null && e.pid==1 && odd.type!=null && odd.type=="Live" && market_id==21) {
							if(odd.odd!=null && odd.odd==17) odd.name = 'under';
							else if(odd.odd!=null && odd.odd==18) odd.name = 'over';
						}

						var oname = findKeyword(odd.name);
						if(odd.line!=null && odd.line!="" && odd.line!="-1") oname = oname + " " + numberFormat(odd.line);

						d = d + '<a href="javascript:;" class="odd-link odd-sub-button odd-link-'+odd.id+' ' + col_type + '" data="'+e.id+'x'+market_id+'x'+odd.id+'" price="' + odd.price + '" oname="' + oname + '">';
						d = d + '<div class="odd-title"><span>'+findKeyword(odd.name)+'</span>';

						if(odd.line!=null && odd.line!="" && odd.line!="-1")
						{
							d = d + '<span class="odd-line">'+numberFormat(odd.line)+'</span>';
						}

						d = d + '</div>';
						d = d + '<div class="odd-rate odd-main-button odd-'+odd.id+'">'+oddFormat(odd.price)+'</div>';
						d = d + '</a>';
					}
				}
			}

			d = d +
				'		<div class="clear"></div>' +
				'	</div>' +
				'</div>';

			$(".market-types").append(d);

			$(".box-title-action").unbind("click");
			$(".box-title-action").click(function() {
				var box = $(this).attr("data-box");
				$("."+box).toggle();
			});			

		}

		$(".market-type").each(function() {
			var c = $(this).find(".odd-link").length;
			if(c==0) $(this).remove();
		});		

		$.post("/api/sport/data/outcomes",{outcomes:outcome_titles,type:$("#subscribe-type").val()},function(response) {
			var json = typeof response == "object" ? response : JSON.parse(response);
			if(json.result==null || json.result!="ok") {
				location.reload();
				return false;
			}
			if(json.data!=null) {
				for(i in json.data)
				{
					var item = json.data[i];
					//$(".market-type-" + item.id + " .market-name").html(item.name);
					$(".mn-" + item.pid + "-" + item.id).html(item.name);
				}
			}
		});

	}

	setEventTime = function(event_id, event_data, event_status) {

		var time_found = false;

		if(event_data!=null) {
			var lst = event_data.last_score_time!=null?event_data.last_score_time:0;
			if(event_data.clock_stopped!=null && event_data.clock_stopped=="1") lst = 0;
			if(!time_found && event_data.remaining_time!=null) {
				var me = event_data.remaining_time.split(":");
				if(me.length==2 && me[1].length==2) {
					if(event_data.matchtime!=null && event_data.matchtime=="1") lst = 0;
					time_found = true;
					var time = parseInt(me[0])*60+parseInt(me[1]);
					$(".event-"+event_id+" .event-minute").html(timeFormat(event_data.remaining_time));
					$(".event-"+event_id+" .event-minute").attr({"last-time":lst,"step":-1,"time":time});
				}
				if(!time_found && event_data.remaining_time!="") {
					time_found = true;
					$(".event-"+event_id+" .event-minute").html(timeFormat(event_data.remaining_time.split(" ")[0]));
					$(".event-"+event_id+" .event-minute").attr({"last-time":0});
				}
			}
			if(!time_found && event_data.remaining_time_in_period!=null) {
				var me = event_data.remaining_time_in_period.split(":");
				if(me.length==2 && me[1].length==2) {
					if(event_data.matchtime!=null && event_data.matchtime=="1") lst = 0;
					time_found = true;
					var time = parseInt(me[0])*60+parseInt(me[1]);
					$(".event-"+event_id+" .event-minute").html(timeFormat(event_data.remaining_time_in_period));
					$(".event-"+event_id+" .event-minute").attr({"last-time":lst,"step":-1,"time":time});
				}
				if(!time_found && event_data.remaining_time_in_period!="") {
					time_found = true;
					$(".event-"+event_id+" .event-minute").html(timeFormat(event_data.remaining_time_in_period.split(" ")[0]));
					$(".event-"+event_id+" .event-minute").attr({"last-time":0});
				}
			}
			if(event_data.matchtime_extended!=null) {
				var me = event_data.matchtime_extended.split(":");
				if(me.length==2 && me[1].length==2) {
					time_found = true;
					var time = parseInt(me[0])*60+parseInt(me[1]);
					$(".event-"+event_id+" .event-minute").html(timeFormat(event_data.matchtime_extended));
					$(".event-"+event_id+" .event-minute").attr({"last-time":lst,"step":1,"time":time});
				}
				if(!time_found && event_data.matchtime_extended!="") {
					time_found = true;
					$(".event-"+event_id+" .event-minute").html(timeFormat(event_data.matchtime_extended.split(" ")[0]));
					$(".event-"+event_id+" .event-minute").attr({"last-time":0});
				}
			}
			if(!time_found && event_data.matchtime!=null && event_data.matchtime.length<3) {
				time_found = true;
				$(".event-"+event_id+" .event-minute").html(timeFormat(event_data.matchtime+":00"));
				$(".event-"+event_id+" .event-minute").attr({"last-time":0});
			}			
			if(!time_found && event_status!="") {
				//time_found = true;
				$(".event-"+event_id+" .event-minute").html(findKeyword(event_status));
				$(".event-"+event_id+" .event-minute").attr({"last-time":0});	
			}
		}

		return time_found;

	}

	get_score = function(data) {

		if(data.score!=null)
		{
			var p = data.score.split(":");
			if(p.length==2) return {"home":p[0],"away":p[1]};
		}
		
		return {"home":"&nbsp;","away":"&nbsp;"};

	}

	get_full_score = function(data) {

		var result = {};

		if(data.score!=null) {
			var st = data.score.split(":");
			if(st.length==2) result["0"] = {"home":st[0],"away":st[1]};
		}

		if(data.setscore1!=null) {
			var st = data.setscore1.split(":");
			if(st.length==2) result["1"] = {"home":st[0],"away":st[1]};
		}

		if(data.setscore2!=null) {
			var st = data.setscore2.split(":");
			if(st.length==2) result["2"] = {"home":st[0],"away":st[1]};
		}

		if(data.setscore3!=null) {
			var st = data.setscore3.split(":");
			if(st.length==2) result["3"] = {"home":st[0],"away":st[1]};
		}

		if(data.setscore4!=null) {
			var st = data.setscore4.split(":");
			if(st.length==2) result["4"] = {"home":st[0],"away":st[1]};
		}

		if(data.setscore5!=null) {
			var st = data.setscore5.split(":");
			if(st.length==2) result["5"] = {"home":st[0],"away":st[1]};
		}

		if(data.setscore6!=null) {
			var st = data.setscore6.split(":");
			if(st.length==2) result["6"] = {"home":st[0],"away":st[1]};
		}

		return result;

	}

	get_stats = function(data) {

		var r = {"home":{"red":0,"yellow":0},"away":{"red":0,"yellow":0}};

		if(data.stats!=null && data.stats.cards!=null) {
			for(i in data.stats.cards) {
				var c = data.stats.cards[i];
				if(c.type=='red' && c.team=='home') r.home.red += 1;
				if(c.type=='red' && c.team=='away') r.away.red += 1;
				if(c.type=='yellow' && c.team=='home') r.home.yellow += 1;
				if(c.type=='yellow' && c.team=='away') r.away.yellow += 1;
			}
		}

		if(data.redcardshome!=null) r.home.red = parseInt(data.redcardshome);
		if(data.redcardsaway!=null) r.away.red = parseInt(data.redcardsaway);
		if(data.yellowcardshome!=null) r.home.yellow = parseInt(data.yellowcardshome);
		if(data.yellowcardsaway!=null) r.away.yellow = parseInt(data.yellowcardsaway);

		return r;

	}

	add_odd_effect = function(item, color) {

		if($(item).hasClass("passive") || $(item).hasClass("passive-ev") || $(item).hasClass("passive-ma")) return false;
		if($(item).hasClass("passive-link") || $(item).hasClass("passive-link-ev") || $(item).hasClass("passive-link-ma")) return false;

		$(item).addClass(color+"-back");
		setTimeout(function() {
			$(item).removeClass(color+"-back");
			setTimeout(function() {
				$(item).addClass(color+"-back");
				setTimeout(function() {
					$(item).removeClass(color+"-back");
					setTimeout(function() {
						$(item).addClass(color+"-back");
						setTimeout(function() {
							$(item).removeClass(color+"-back");
							setTimeout(function() {
								$(item).addClass(color+"-back");
								setTimeout(function() {
									$(item).removeClass(color+"-back");
								}, 250);
							}, 250);
						}, 250);
					}, 250);
				}, 250);
			}, 250);
		}, 250);
	}

	var dest;

	var pmasian = [51,52,53,54,260,261,473,482,562,566];
	var liasian = [33,34,35,36,38,44,48,51,54,76,113,114,349,351,371,373,375,377,379,381,383,385,387,389,391,393,395,482,528,890,892,930,1046,1094,1142,1234,1264,1266,1268,1270,1272,1274,1290,1308,1368,1396,1406,1428,1442,1456,1514,1630,1634];

	sortOutcomes = function(event_id,oc,pid) {

		// Fixing asian handicap signs
		if(pid==1) {
			for(i in oc) {
				if(oc[i].odds.length==0) continue;
				var prmt = oc[i].odds[0].type=="Prematch";
				if(prmt && !inArray(oc[i].id,pmasian)) continue;
				if(!prmt && !inArray(oc[i].id,liasian)) continue;
				for(j in oc[i].odds)
				{
					if(oc[i].odds[j].name=="1") {
						if(oc[i].odds[j].line.length>0 && oc[i].odds[j].line[0]!='-' && oc[i].odds[j].line[0]!='+') {
							oc[i].odds[j].line = "+" + oc[i].odds[j].line;
						}
					} else if(oc[i].odds[j].name=="2") {
						if(oc[i].odds[j].line.length==0) {
							continue;
						} else {
							if(oc[i].odds[j].line[0]=='-') {
								oc[i].odds[j].line = '+' + oc[i].odds[j].line.substr(1);
							} else if(oc[i].odds[j].line[0]=='+') {
								oc[i].odds[j].line = '-' + oc[i].odds[j].line.substr(1);
							} else {
								oc[i].odds[j].line = "-" + oc[i].odds[j].line;
							}
						}
					}
				}
			}
		}

		dest = oc;

		var r = [];
		
		for(jid in market_priority) {
			for(i in oc) {
				if(pid+"@"+oc[i].id==market_priority[jid]) {
					oc[i].odds = oc[i].odds.sort(compareByTeamBet);
					r.push(oc[i]);
					break;
				}
			}
		}

		for(i in oc) {
			if(!inArray2(oc[i].id,market_priority,pid)) {
				if(inArray2(oc[i].id,team_name_markets,pid)) {
					oc[i].odds = oc[i].odds.sort(compareByTeamBet);
				} else {
					oc[i].odds = oc[i].odds.sort(compareByBet);
				}
				r.push(oc[i]);
			}
		}

		var home_team_name = findKeyword("home");
		var away_team_name = findKeyword("away");

		home_team_name = $(".event-"+event_id+" .home-team").html();
		if(home_team_name==undefined || home_team_name==null) home_team_name = findKeyword("home");

		away_team_name = $(".event-"+event_id+" .away-team").html();
		if(away_team_name==undefined || away_team_name==null) away_team_name = findKeyword("away");

		// Replace with team names
		for(i in r) {
			if(inArray2(r[i].id,team_name_markets,pid)) {
				for(j in r[i].odds) {
					if(r[i].odds[j].name=="1") {
						r[i].odds[j].name = home_team_name;
					} else if(r[i].odds[j].name=="2") {
						r[i].odds[j].name = away_team_name;
					}
				}
			}
		}

		return r;

	}

	inArray = function(xId,xArray) {
		for(ij in xArray) if(xArray[ij]==xId) return true;
		return false;
	}

	inArray2 = function(xId,xArray,xPid) {
		for(ij in xArray)
		{
			if(xArray[ij]==xPid+"@"+xId) return true;
		}
		return false;
	}

	compareByBet = function(a,b) {

		if(a.name < b.name) {
			return -1;
	 	}

		if(a.name > b.name) {
			return 1;
		}

		if(a.line < b.line) {
			return -1;
		}

		if(a.line > b.line) {
			return 1;
		}

		if(a.most_balanced!=null || b.most_balanced!=null) {
			if(mostBalancedCode(a) < mostBalancedCode(b)) return -1;
			if(mostBalancedCode(a) > mostBalancedCode(b)) return 1;
		}

		return 0;
	}

	compareByTeamBet = function(a,b) {

		if(teamNameCode(a) < teamNameCode(b)) return -1;
		if(teamNameCode(a) > teamNameCode(b)) return 1;

		if(a.line > b.line) {
			return 1;
		}

		if(a.line < b.line) {
			return -1;
		}

		if(a.most_balanced!=null || b.most_balanced!=null) {
			if(mostBalancedCode(a) < mostBalancedCode(b)) return -1;
			if(mostBalancedCode(a) > mostBalancedCode(b)) return 1;
		}

		return 0;
	}

	mostBalancedCode = function(o) {
		if(o.most_balanced!=null) {
			if(o.most_balanced=='Yes') return -1;
			if(o.most_balanced=='No') return 1;
		}
		return 0;
	}

	teamNameCode = function(o) {
		if(o.name!=null) {
			if(o.name=='1') return -1;
			if(o.name=='2') return 1;
		}
		return 0;
	}

	calculateTimes = function() {

		$(".event-minute").each(function() {
			var step = $(this).attr("step");
			var last = $(this).attr("last-time");
			var time = $(this).attr("time");
			if(step==undefined || last==undefined || time==undefined || last==0) return true;
			var dif = new Date().getTime()-parseInt(last);
			time  = parseInt(time);
			if(step>0) time += Math.floor(dif / 1000);
			if(step<0) time -= Math.floor(dif / 1000);
			if(time<0) time  = 0;
			var second = time % 60;
			var minute = Math.floor(time / 60);
			if(second<10) second = "0"+second;
			$(this).html(timeFormat(minute+":"+second));
		});

	}

	function getconnectionurl(current_url,cannotconnect) {
		if(cannotconnect==true) {
			var cck = getcookieforconnection("connfix");
			if(cck!=null) {
				var cck = parseInt(cck);
				cck = cck +1;
				if(cck>=4) {
					createcookieforconnection("connfix",1);					
					return current_url;	
				}
				createcookieforconnection("connfix",cck);
				return current_url;
			}
			createcookieforconnection("connfix",1);
			return current_url;
		}
		var cck = getcookieforconnection("connfix");
		if(cck!=null && parseInt(cck)>=2) {
			return current_url.replace("ws://","wss://");
		}
		return current_url;
	}

	function createcookieforconnection(name,value) {
		var date = new Date();
		date.setTime(date.getTime()+(6*60*60*1000));
		var expires = "; expires="+date.toUTCString();
	    document.cookie = name+"="+value+expires+"; path=/";
	}

	function getcookieforconnection(name) {
	    var nameEQ = name + "=";
	    var ca = document.cookie.split(';');
	    for(var i=0;i < ca.length;i++) {
	        var c = ca[i];
	        while (c.charAt(0)==' ') c = c.substring(1,c.length);
	        if (c.indexOf(nameEQ) == 0) return c.substring(nameEQ.length,c.length);
	    }
	    return null;
	}


