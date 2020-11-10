function loadBetslipCookie() {
    var u = $.cookie("betslip"),
            t, r, i, n, f;
    if (typeof u != "undefined" && (t = JSON.parse(u), t.length > 0)) {
        for ($(".nobet").hide(), $(".bettotal").show(), r = "", i = 0; i < (t.length < 21 ? t.length : 20); i++)
            n = t[i], f = '<b class="team1 ellipsis ellipsis2">' + n.team1 + '<\/b><br /><b class="team2 ellipsis ellipsis2">' + n.team2 + "<\/b>", r += createBet(n.eventid, f, n.odd, n.pick, n.market, n.marketid, n.runnerid, n.points);
        $(".selectedodds div.betlist").append(r);
        renewDeleteEvent();
        prepareMultiple()
    }
	var count = 0;
	$(".selectedodds div.betlist ul").not(".suspended").each(function(r){
		count++;
	});
	$('.notification').text(count);
}

function createBet(n, t, i, r, u, f, e, o) {
//	console.log('create bet');
//	console.log(parseFloat(i).toFixed(2));
//	console.log(n);
    var s = '<ul data-eventid="' + n + '" data-pick="' + r + '" data-points="' + o + '" data-marketid="' + f + '" data-runnerid="' + e + '" class="bet">';
    return s += '<li style="overflow:hidden"><span class="fa fa-times-circle delete has-tip" title="' + ($("#lang").val() == "fa" ? "حذف" : "Delete") + '"><\/span>' + t + "<\/li>", s += "<li>" + u + "<\/li>", s += '<li><span class="pick"><div class="ellipsis floatleft">' + (lang == "fa" ? "انتخاب" : "Pick") + ": " + r + ' <\/div><div class="ltrinput points floatleft margin-left-5px">' + o + '<\/div><\/span><span class="odd"><span>' + parseFloat(i).toFixed(2) + '<\/span><i class="marginright"><\/i><\/span><\/li>', s += '<li><input class="input stake" type="text" placeholder="0" /><span>' + (lang == "fa" ? "مبلغ برد" : "To Win") + '&nbsp;<span class="ToWin">' + en_cur + "0<\/span><\/span><\/li>", s += "<\/ul>", $('li[data-runnerid="' + e + '"]').addClass("selected"), s
}

function findInArray(n, t, i) {
    var r = -1;
    return $.each(n, function(n, u) {
        if (u[t] == i)
            return r = n, !0
    }), r
}

function findAndRemove(n, t, i) {
    $.each(n, function(r, u) {
        if (u[t] == i)
            return n.splice(r, 1), !1
    });
    return
}

function selectBetslipBets() {
    $("ul.inplay .betlist ul.bet").each(function() {
        $('li[data-runnerid="' + $(this).data("runnerid") + '"]').addClass("selected")
    })
}

function saveBet2Cookie() {
    var n = [],
            i = $.cookie("betslip");
    typeof i != "undefined" && (n = JSON.parse(i));
    $("ul.inplay .betlist ul.bet").each(function() {
        if (findInArray(n, "runnerid", $(this).data("runnerid")) < 0) {
            var t = {};
            t.eventid = $(this).data("eventid");
            t.marketid = $(this).data("marketid");
            t.runnerid = $(this).data("runnerid");
            t.pick = $(this).data("pick");
            t.points = $(this).data("points");
            t.market = $(this).find("li:nth-child(2)").text();
            t.team1 = $(this).find("b.team1").text();
            t.team2 = $(this).find("b.team2").text();
            t.odd = $(this).find("span.odd span").text();
//			console.log($(this));
//			console.log(t);
            t.stake = $(this).find("input.stake").val();
            n.push(t)
        }
    });
    var r = JSON.stringify(n),
            t = new Date;
    return t.setTime(t.getTime() + 18e5), $.cookie("betslip", r, {
        expires: t,
        path: "/"
    }), r
}

function createBetCode() {
    var t = new Object(),
            n;
    return $("ul.inplay .betlist ul.bet").each(function() {
        if ($(this).find(".overlay").length == 0) {
            var n = $(this).data("eventid"),
                    i = $(this).data("marketid"),
                    r = $(this).data("runnerid"),
//                    u = $(this).attr("data-points"),
                    f = $(this).find("span.odd span").text(),
                    e = $(this).find("input.stake").val();
            t.match_id = n;
            t.odd = f;
            t.stake = e;
        }
    }), n = "", $(".multiple tr").each(function() {
        var t = $(this).find("td:eq(0)").html().split(" ("),
                i = $(this).find(".stake").val() == "" ? "0" : $(this).find(".stake").val();
        n += t[0] + "&" + t[1].replace("<span>", "").replace("<\/span>)", "") + "&" + i
    }), t + "&" + n
}

function createMultiple() {
    var n = "",
            t, i;
//	console.log(getMultipleCount(r, t));
	
    return $(".selectedodds div.betlist ul").not(".suspended").length > 1 && $(".selectedodds div.betlist ul").not(".suspended").length < 9 && (t = [], $(".selectedodds div.betlist ul").not(".suspended").find(".odd").each(function() {
        t.push($.trim($(this).text()))
    }), i = !1, $(".selectedodds div.betlist ul").not(".suspended").each(function() {
        var n = $(this).data("eventid");
        if ($('.betlist ul[data-eventid="' + n + '"]').not(".suspended").length > 1)
            return i = !0, !1
    }), $(".selectedodds div.betlist ul").not(".suspended").each(function(r) {
        if (i && r > 0)
            return !0;
        n += "<tr>";
        n += "<td>" + getMultipleName(r + 1) + " (<span>x" + getMultipleCount(r, t) + "<\/span>)<\/td>";
        n += r == 0 ? '<td><input class="mulOdd" type="hidden" value="' + getMultipleOdds(r + 1, t) + '" /><span class="ToWin ignore"><\/span><\/td>' : '<td><input class="mulOdd" type="hidden" value="' + getMultipleOdds(r + 1, t) + '" /><span class="ToWin"><\/span><\/td>';
        n += '<td><input type="text" class="input stake stake' + r + '" placeholder="0" /><\/td>';
        n += "<\/tr>"
    })), n
}

function prepareMultiple() {
	var count = 0;
	$(".selectedodds div.betlist ul").not(".suspended").each(function(r){
		count++;
	});
	$('.notification').text(count);
//	console.log($('.notification').text());
    $(".multiple").html(createMultiple());
	
    $(".betlist .stake").unbind("keyup").keyup(function() {
        var n, t;
        $(".alertbox").addClass("hidden");
        $(this).numeric();
        n = $(this).val();
        n.indexOf(".") >= 0 && n.length - n.indexOf(".") > 3 && $(this).val(parseFloat(Math.round(n * 100)) / 100);
        $(this).val() === "NaN" ? $(this).val("") : (t = parseFloat($(this).val()) * parseFloat($(this).parent().prev().find("span.odd span").text()), $(this).next().find(".ToWin").html(en_cur + t.toFixed(0).replace(/\B(?=(?:\d{3})+(?!\d))/g, ",")), $(".multiple .stake0").val(""), $(".multiple .stake0").parent().prev().find(".ToWin").html(""));
        calcTotal();
        updateWin()
    });
    $(".multiple .stake").unbind("keyup").keyup(function() {
        var n, t;
        $(".alertbox").addClass("hidden");
        $(this).numeric();
        n = $(this).val();
        n.indexOf(".") >= 0 && n.length - n.indexOf(".") > 3 && $(this).val(parseFloat(Math.round(n * 100)) / 100);
        $(this).val() === "NaN" && $(this).val("");
        $(this).hasClass("stake0") && ($(".betlist .stake").val($(this).val()), $(".betlist .stake").each(function() {
            var n = parseFloat($(this).val()) * parseFloat($(this).parent().prev().find("span.odd span").text());
            $(this).next().find(".ToWin").html(en_cur + n.toFixed(0).replace(/\B(?=(?:\d{3})+(?!\d))/g, ","))
        }));
        t = parseFloat($(this).val()) * parseFloat($(this).parent().prev().find(".mulOdd").val());
        $(this).parent().prev().find(".ToWin").html(en_cur + t.toFixed(0).replace(/\B(?=(?:\d{3})+(?!\d))/g, ","));
        calcTotal()
    })
}

function updateMultiple() {
    if ($(".selectedodds div.betlist ul").not(".suspended").length > 1 && $(".selectedodds div.betlist ul").not(".suspended").length < 9) {
        var n = [];
        $(".selectedodds div.betlist ul").not(".suspended").find(".odd").each(function() {
            n.push($.trim($(this).text()))
        });
        $("div.bettotal table.multiple tr").each(function(t) {
            var i = getMultipleOdds(t + 1, n),
                    u = $(this).find("input.stake").val(),
                    r;
            $(this).find(".mulOdd").val(i);
            r = i * u;
            $(this).find(".ToWin").text(en_cur + r.toFixed(0).replace(/\B(?=(?:\d{3})+(?!\d))/g, ","))
        })
    }
}

function getMultipleOdds(i, odds) {
    switch (i) {
        case 1:
            return calcSingles(odds);
        case 2:
            return calcDoubles(odds);
        case 3:
            return calcTrebles(odds)
    }
    return eval("calc" + i + "Folds(odds)")
}

function getMultipleCount(n, t) {
    var i = t.length;
    return n += 1, f(i) / (f(n) * f(i - n))
}

function f(n) {
    return n <= 1 ? 1 : n * f(n - 1)
}

function calcSingles(n) {
    for (var i = 0, t = 0; t < n.length; t++)
        i += parseFloat(n[t]);
    return i.toFixed(2)
}

function calcDoubles(n) {
    for (var i, r = 0, t = 0; t < n.length - 1; t++)
        for (i = t + 1; i < n.length; i++)
            r += parseFloat(n[t]) * parseFloat(n[i]);
    return r.toFixed(2)
}

function calcTrebles(n) {
    for (var t, r, u = 0, i = 0; i < n.length - 1; i++)
        for (t = i + 1; t < n.length; t++)
            for (r = t + 1; r < n.length; r++)
                u += parseFloat(n[i]) * parseFloat(n[t]) * parseFloat(n[r]);
    return u.toFixed(2)
}

function calc4Folds(n) {
    for (var t, i, u, f = 0, r = 0; r < n.length - 1; r++)
        for (t = r + 1; t < n.length; t++)
            for (i = t + 1; i < n.length; i++)
                for (u = i + 1; u < n.length; u++)
                    f += parseFloat(n[r]) * parseFloat(n[t]) * parseFloat(n[i]) * parseFloat(n[u]);
    return f.toFixed(2)
}

function calc5Folds(n) {
    for (var t, i, r, f, e = 0, u = 0; u < n.length - 1; u++)
        for (t = u + 1; t < n.length; t++)
            for (i = t + 1; i < n.length; i++)
                for (r = i + 1; r < n.length; r++)
                    for (f = r + 1; f < n.length; f++)
                        e += parseFloat(n[u]) * parseFloat(n[t]) * parseFloat(n[i]) * parseFloat(n[r]) * parseFloat(n[f]);
    return e.toFixed(2)
}

function calc6Folds(n) {
    for (var t, i, r, u, e, o = 0, f = 0; f < n.length - 1; f++)
        for (t = f + 1; t < n.length; t++)
            for (i = t + 1; i < n.length; i++)
                for (r = i + 1; r < n.length; r++)
                    for (u = r + 1; u < n.length; u++)
                        for (e = u + 1; e < n.length; e++)
                            o += parseFloat(n[f]) * parseFloat(n[t]) * parseFloat(n[i]) * parseFloat(n[r]) * parseFloat(n[u]) * parseFloat(n[e]);
    return o.toFixed(2)
}

function calc7Folds(n) {
    for (var t, i, r, u, f, o, s = 0, e = 0; e < n.length - 1; e++)
        for (t = e + 1; t < n.length; t++)
            for (i = t + 1; i < n.length; i++)
                for (r = i + 1; r < n.length; r++)
                    for (u = r + 1; u < n.length; u++)
                        for (f = u + 1; f < n.length; f++)
                            for (o = f + 1; o < n.length; o++)
                                s += parseFloat(n[e]) * parseFloat(n[t]) * parseFloat(n[i]) * parseFloat(n[r]) * parseFloat(n[u]) * parseFloat(n[f]) * parseFloat(n[o]);
    return s.toFixed(2)
}

function calc8Folds(n) {
    for (var t, i, r, u, f, e, s, h = 0, o = 0; o < n.length - 1; o++)
        for (t = o + 1; t < n.length; t++)
            for (i = t + 1; i < n.length; i++)
                for (r = i + 1; r < n.length; r++)
                    for (u = r + 1; u < n.length; u++)
                        for (f = u + 1; f < n.length; f++)
                            for (e = f + 1; e < n.length; e++)
                                for (s = e + 1; s < n.length; s++)
                                    h += parseFloat(n[o]) * parseFloat(n[t]) * parseFloat(n[i]) * parseFloat(n[r]) * parseFloat(n[u]) * parseFloat(n[f]) * parseFloat(n[e]) * parseFloat(n[s]);
    return h.toFixed(2)
}

function getMultipleName(n) {
    switch (n) {
        case 1:
            return lang == "fa" ? "تکی ها" : "Singles";
        case 2:
            return lang == "fa" ? "2 تایی ها" : "Doubles";
        case 3:
            return lang == "fa" ? "3 تایی ها" : "Trebles"
    }
    return n + (lang == "fa" ? " تایی ها" : " Folds")
}

function updateWin() {
    $(".betlist ul.bet").each(function() {
        var n = parseFloat($(this).find("span.odd span").text()),
                t = parseFloat($(this).find("input.stake").val()),
                i = n * t;
        $(this).find("span.ToWin").html(en_cur + i.toFixed(0).replace(/\B(?=(?:\d{3})+(?!\d))/g, ","))
    });
    updateMultiple();
    calcTotal()
}

function calcTotal() {
    var n, t;
    $(".ToWin").each(function() {
        $(this).html() == en_cur + "NaN" && $(this).html(en_cur + "0")
    });
    n = 0;
    $(".betslip .bet").not(".suspended").find(".stake").each(function() {
        $.isNumeric(parseFloat($(this).val())) && (n += parseFloat($(this).val()))
    });
    $(".multiple .stake").each(function() {
        $.isNumeric($(this).val()) && !$(this).hasClass("stake0") && (n += parseFloat($(this).val()) * parseInt($(this).parent().prev().prev().find("span").text().replace("x", "")))
    });
    n > 0 ? $(".placebet").not(".in-progress").removeClass("disabled") : $(".placebet").addClass("disabled");
    $(".totalstake").html(en_cur + n.toFixed(0).replace(/\B(?=(?:\d{3})+(?!\d))/g, ","));
    t = 0;
    $(".betslip .bet").not(".suspended").find(".ToWin").each(function() {
        $.isNumeric($(this).text().replace(en_cur, "").replace(/,/g, "")) && !$(this).hasClass("ignore") && (/*console.log(parseInt($(this).text().replace(en_cur, "").replace(/,/g, ""))) , */ t += parseInt($(this).text().replace(en_cur, "").replace(/,/g, "")))
    });
    $(".bettotal").find(".ToWin").each(function() {
        $.isNumeric($(this).text().replace(en_cur, "").replace(/,/g, "")) && !$(this).hasClass("ignore") && (/*console.log(parseInt($(this).text().replace(en_cur, "").replace(/,/g, ""))), */t += parseInt($(this).text().replace(en_cur, "").replace(/,/g, "")))
    });
    /* console.log(t);*/
    $(".totalwin").html(en_cur + t.toFixed(0).replace(/\B(?=(?:\d{3})+(?!\d))/g, ","))
}

function prepareButtonEvents() {
	
    $(".has-tip").frosty();
    $(".inplaybtn").click(function() {
//		console.log('click function');
//		console.log($(this));
        var s, f, e, o;
        if ($(".betlist ul.bet").length < 21 && ($(".alertbox").addClass("hidden"), !$(this).hasClass("selected") && !$(this).hasClass("disabled"))) {
            $(".nobet").hide();
            $(".bettotal").show();
            s = new Date;
            $(this).addClass("selected");
//			console.log($( this));
//			if($(this).closest("tr.odddetails").length == 0)
//				console.log($(this).closest("div.odddetails").length);
			var oddDetails = ($(this).closest("div.odddetails").length == 0) ? $(this).closest("tr.odddetails") : $(this).closest("div.odddetails");
            var n = $(this).closest(".odddetails"),
                    t = n.data("eventid"),
                    i = n.data("marketid"),
                    h = $(this).data("runnerid"),
                    r = n.find(".host").text(),
                    u = n.find(".guest").text(),
                    f = lang == "fa" ? "نتیجه مسابقه" : "Match Result",
                    c = $(this).data("pick"),
                    l = $(this).data("points");
//			console.log(n);
//			console.log(t);
            n.hasClass("eventodd") && (r = $(this).closest("ul.odds").find(".host").val(), u = $(this).closest("ul.odds").find(".guest").val(), t = $(this).closest(".eventodds").data("eventid"), i = $(this).closest(".eventodds").data("marketid"), f = $(this).closest(".odddetails").parent().find(".inplayheader b").text());
            e = '<b class="team1 ellipsis ellipsis2">' + r + '<\/b><br /><b class="team2 ellipsis ellipsis2">' + u + "<\/b>";
            o = $(this).find("span").text();
            $(".selectedodds div.betlist").append(createBet(t, e, o, c, f, i, h, l));
            saveBet2Cookie();
            renewDeleteEvent();
            prepareMultiple()
        }else if($(this).hasClass("selected")){
//			alert('11111');
			var n, i, r, t, u;
			var element = $(this).data("runnerid");
//			console.log($(this).data("runnerid"));
	//		test = document.getElementById('2020386-2-1x2-X');
	//		alert(test.getAttribute('data-runnerid'));
//			console.log($('ul[data-runnerid="' + element + '"]'));

			$(".alertbox").addClass("hidden");
	//        n = $(this).parent().parent();
			n = $('ul[data-runnerid="' + element + '"]');
//			console.log(n);
			i = $.cookie("betslip");
			typeof i != "undefined" && (r = JSON.parse(i), findAndRemove(r, "runnerid", n.data("runnerid")), t = new Date, u = 30, t.setTime(t.getTime() + u * 6e4), $.cookie("betslip", JSON.stringify(r), {
				expires: t,
				path: "/"
			}));
			$('ul[data-runnerid="' + element + '"]').slideUp("slow", function() {
				var i = n.data("eventid"),
						r = n.data("marketid"),
						t = n.data("runnerid");
				$('li[data-runnerid="' + t + '"]').removeClass("selected");
				$(this).remove();
				prepareMultiple();
				calcTotal();
				$(".selectedodds div ul").length == 0 && ($(".nobet").show(), $(".bettotal").hide())
			})
		}
    });
	
//	$(".selected").click(function(){
//		var n, i, r, t, u;
//		var element = $(this).data("runnerid");
//		console.log($(this).data("runnerid"));
////		test = document.getElementById('2020386-2-1x2-X');
////		alert(test.getAttribute('data-runnerid'));
//		
//        $(".alertbox").addClass("hidden");
////        n = $(this).parent().parent();
//        n = $('#'+element);
//		console.log(n);
//        i = $.cookie("betslip");
//        typeof i != "undefined" && (r = JSON.parse(i), findAndRemove(r, "runnerid", n.data("runnerid")), t = new Date, u = 30, t.setTime(t.getTime() + u * 6e4), $.cookie("betslip", JSON.stringify(r), {
//            expires: t,
//            path: "/"
//        }));
//        $(this).parent().parent().slideUp("slow", function() {
//            var i = n.data("eventid"),
//                    r = n.data("marketid"),
//                    t = n.data("runnerid");
//            $('li[data-runnerid="' + t + '"]').removeClass("selected");
//            $(this).remove();
//            prepareMultiple();
//            calcTotal();
//            $(".selectedodds div ul").length == 0 && ($(".nobet").show(), $(".bettotal").hide())
//        })
////		alert('111111');
//	});
}

function renewDeleteEvent() {
    $("ul.bet .delete").unbind("click").click(function() {
        var n, i, r, t, u;
        $(".alertbox").addClass("hidden");
        n = $(this).parent().parent();
//		console.log(n);
        i = $.cookie("betslip");
        typeof i != "undefined" && (r = JSON.parse(i), findAndRemove(r, "runnerid", n.data("runnerid")), t = new Date, u = 30, t.setTime(t.getTime() + u * 6e4), $.cookie("betslip", JSON.stringify(r), {
            expires: t,
            path: "/"
        }));
        $(this).parent().parent().slideUp("slow", function() {
            var i = n.data("eventid"),
                    r = n.data("marketid"),
                    t = n.data("runnerid");
            $('li[data-runnerid="' + t + '"]').removeClass("selected");
            $(this).remove();
            prepareMultiple();
            calcTotal();
            $(".selectedodds div ul").length == 0 && ($(".nobet").show(), $(".bettotal").hide())
        })
    })
}

function toFarsi(n) {
    var t, r, i, u;
    if (lang == "fa") {
        for (n = n + "", t = "", r = 0; r < n.length; r++)
            i = n.charCodeAt(r), i >= 48 && i <= 57 ? (u = i + 1728, t = t + String.fromCharCode(u)) : t = t + String.fromCharCode(i);
        return t
    }
    return n
}

function updateEventScore(n) {
    var i = Math.round(n.GameTime + (new Date(n.CurrentTime) - new Date(n.LastTimeUpdate)) / 1e3),
            t = secondsTimeSpanToMS(i, n.Status);
    $(".eninplaytime").val(t);
    $(".inplaytime").text(toFarsi(t));
    $(".hScore").text(toFarsi(n.Score1));
    $(".aScore").text(toFarsi(n.Score2));
    (n.HTScore1 != null || n.HTScore2 != null) && ($(".htScore").removeClass("hidden"), $(".htScore1").text(toFarsi(n.HTScore1)).removeClass("hidden"), $(".htScore2").text(toFarsi(n.HTScore2)).removeClass("hidden"))
}

function updateBetslipOdds(UpdatedMatches) {

    var preMatches = $('table.betslip ul.bet');

    // if the matche was removed, remove the tr 
    if (UpdatedMatches !== undefined) {
        $.each(preMatches, function(i, item) {
//			console.log(item);
            if (!($(item).attr('data-eventid') in UpdatedMatches)) {
                $(item).find('span.delete').trigger('click');
            }
        });
    }

    if (UpdatedMatches !== undefined) {
        $.each(UpdatedMatches, function(match_id, item) {
            var preMatches = $('table.inplaytable > tbody > tr.odddetails');
			
            var row_event = $("table.betslip ul[data-eventid=" + match_id + "]"),
                    suspend_status = 0,
                    odd = row_event.find("span.odd span"),
                    pick = row_event.attr('data-pick'),
					win = row_event.find("span.ToWin"),
					stake = row_event.find("input.stake").val();
            if (row_event.length > 0) {
//				console.log(row_event);
				$.each(row_event, function(row_event_index, row_event_item){
					
					odd = $(row_event_item).find("span.odd span"),
                    pick = $(row_event_item).attr('data-pick'),
					runner_id = $(row_event_item).attr('data-runnerid'),
					win = $(row_event_item).find("span.ToWin"),
					stake = $(row_event_item).find("input.stake").val();
//					console.log(runner_id);
					$.each(item.odds.data[0].types.data, function(index3, item3) {
//						console.log(item3);
						$.each(item3.odds.data, function(index4, item4){
//							console.log(item4);
							suspend_status = item4.suspend;
							
							if (match_id+'-2-'+item3.type+'-'+item4.label == runner_id){
//								console.log(item3	.type);
								if (suspend_status > 0 || item4.value == 0 ||(item.minute>80 && item3.type !='1x2') ) {
									if (!$(row_event_item).hasClass('suspended')) {
										$(row_event_item).addClass('suspended');
										$(row_event_item).append("<li class='suspend-li overlay' style='width: 236px; height: 54px; margin-top: -54px;'><div>" + ("غیر فعال") + "<br />" + ("شرط حذف شد") + "<\/div><\/li>");
//										console.log($(row_event_item).hasClass('suspended'));
									}
								} else if (suspend_status == 0 && item.value != 0) {
									$(row_event_item).removeClass('suspended').find('.suspend-li').remove();
								}
								
								
								odd.text(item4.value);
								win.text(item4.value * stake);
								updateWin();
							}

						});
//						suspend_status = item3.suspend;
//						if (suspend_status > 0) {
//							if (!row_event.hasClass('suspended')) {
//								row_event.addClass('suspended');
//								row_event.append("<li class='suspend-li overlay' style='width: 236px; height: 54px; margin-top: -54px;'><div>" + ("غیر فعال") + "<br />" + ("شرط حذف شد") + "<\/div><\/li>");
//							}
//						} else {
//							row_event.removeClass('suspended').find('.suspend-li').remove();
//						}
//						if (item3.name == pick)
//							odd.text(item3.odd);
	//                if (e < i.Odds) {
	//                    odd.next().removeClass("fa fa-caret-down downarrow").addClass("fa fa-caret-up uparrow");
	//                    odd.removeClass("arrowdown").addClass("arrowup");
	//                }
					})
				});
                
            }
        });
    }

}

function updateEventOdds(n) {
    $.each(n, function(n, t) {
        var e = $('div[data-marketid="' + t.MarketId + '"]'),
                r = $('li[data-runnerid="' + t.RunnerId + '"]'),
                i = r.find("span"),
                o = r.prev(),
                u = $('li[data-runnerid="' + t.RunnerId + '"] i'),
                f;
        t.Danger ? e.find(".eventsuspended").removeClass("hidden") : e.find(".eventsuspended").addClass("hidden");
        f = parseFloat(i.text());
        t.Odds > 1 ? (f > t.Odds ? u.removeClass("fa fa-caret-up up-arrow").addClass("fa fa-caret-down down-arrow") : f < t.Odds && u.removeClass("fa fa-caret-down down-arrow").addClass("fa fa-caret-up up-arrow"), i.text(t.Odds), i.parent().removeClass("disabled")) : (u.removeClass("fa fa-caret-up up-arrow fa-caret-down down-arrow"), i.html("&nbsp;"), i.parent().addClass("disabled"));
        o.hasClass("betpoints") && (r.attr("data-points", t.Points), o.find("b").text(showPoints(t.Points)))
    });
    selectBetslipBets()
}

function updateInplayOdds(UpdatedMatches) {
	$('.inplayheaders').addClass('leagueChange');
	
	if(UpdatedMatches == '' ){
		window.location.replace("../inplayBet");
	}
    var preMatches = $('div.odddetails');
    var Headers = $('div.inplayheader');
	
	
//	$(Headers).addClass('leagueChange');
//	console.log(preMatches);
//	console.log(UpdatedMatches);
    $.each(Headers, function(i, item) {
        if ($('div.odddetails[data-league="' + $(item).attr('data-league') + '"]').length == 0)
            $('div.inplayheader[data-league="' + $(item).attr('data-league') + '"]').remove();
    });
	
    // if the matche was removed, remove the tr 
    if (UpdatedMatches !== undefined) {
        $('span.totalevents').text(toFarsi(Object.keys(UpdatedMatches).length));
        $.each(preMatches, function(i, item) {
//			console.log($(item).attr('data-eventid'));
//			console.log(UpdatedMatches);
			if( $(item).attr('data-eventid') != undefined){
				if (!($(item).attr('data-eventid') in UpdatedMatches)) {
					$(item).fadeOut().remove();
					$('span.totalevents').text(toFarsi(Object.keys(UpdatedMatches).length));
					}	
			}

            
        });
    }
//	console.log(UpdatedMatches);

    if (UpdatedMatches !== undefined) {
		
        $.each(UpdatedMatches, function(match_id, item) {
			
            var preMatches = $('div.odddetails');

            var row_event = $('div[data-eventid="' + match_id + '"]'),
                    suspend_status = 0;
			var preevnets = $('table div.eventodds');
			row_event.addClass('noChanging');
//			var Headers = $('tr.inplayheader');
			
//			console.log(preevnets.length);
            if (row_event.length == 0 || preevnets.length > 0) {
				
//				var isInplayOdds = $('div[data-eventname="Fulltime Result"]'),suspend_status = 0;
				var isInplayOdds = $('.eventodds[data-eventname]'),suspend_status = 0;
				isInplayOdds = preevnets;
				if(isInplayOdds.length > 0){
					// Update inplay odds
//					console.log(isInplayOdds);
//					console.log(item);
					$('div.reasult').find('span').eq(0).text(item.home_score);
					$('div.reasult').find('span').eq(2).text(item.away_score);
					var half;
					if(item.status == 'LIVE' || item.status == 'HT'){
//						console.log('test' + isInplayOdds);
					}
					if(item.status == 'LIVE'){
						if(item.minute < 45)
							half = 'نیمه اول';
						else
							half = 'نیمه دوم';
					}
					else if(item.status == 'HT'){
						half = '';
						
					}
					else{
						if(item.minute == null)
							half = 'شروع نشده';
						else
							half = 'بازی تمام شده';
					}
					
					$('div.play > div.rand').find('span').eq(0).text(half);
					if(item.status == 'HT'){
						$('input.eninplaytime').val('HT');
//						$('div.play > div.rand').find('span').eq(1).text('');
//						$('div.play > div.rand').find('span').eq(1).text('45:00');	
					}else{
//						$('div.play > div.rand').find('span').eq(1).text(item.minute+':00');
//						$('div.play > div.rand').find('span').eq(1).text(item.minute+':'+item.second);	
//						$('input.eninplaytime').val((item.minute) + ':' + item.second);
					}
					
					if(item.minute > 45){
						$('input.eninplaytime').val((item.minute) + ':' + item.second);
					}
					
					
					// score
					$('div.mb-20').find('tr').eq(1).find('td').eq(1).text(item.home_score);
					$('div.mb-20').find('tr').eq(2).find('td').eq(1).text(item.away_score);
					// Yellow cards
					$('div.mb-20').find('tr').eq(1).find('td').eq(2).text(item.homeTeam.yellow_cards);
					$('div.mb-20').find('tr').eq(2).find('td').eq(2).text(item.awayTeam.yellow_cards);
					// Red cards
					$('div.mb-20').find('tr').eq(1).find('td').eq(3).text(item.homeTeam.red_cards);
					$('div.mb-20').find('tr').eq(2).find('td').eq(3).text(item.awayTeam.red_cards);
					// Corners
					$('div.mb-20').find('tr').eq(1).find('td').eq(4).text(item.homeTeam.corners);
					$('div.mb-20').find('tr').eq(2).find('td').eq(4).text(item.awayTeam.corners);
					// Penalties
					$('div.mb-20').find('tr').eq(1).find('td').eq(5).text(item.home_score_penalties);
					$('div.mb-20').find('tr').eq(2).find('td').eq(5).text(item.away_score_penalties);
					
//					if(item.minute < 80 ){
//						
						$('table.inplaytable').addClass('noChanging');
						$.each(item.odds.data[0].types.data, function(odd_index,odds_type){
							if(((item.minute < 80 && odds_type.type != '1x2') || (odds_type.type == '1x2')) && (item.status != 'FT' || item.status != 'CNCL') ){
								var inplayOddsObject = $('div[data-eventname="'+odds_type.type+'"]'),suspend_status = 0;
								inplayOddsObject.parent().parent().parent().parent().removeClass('noChanging');
//								console.log(inplayOddsObject);

									$.each(odds_type.odds.data, function(odd_index1,odds_type1){
		//							console.log(odds_type1);
		//							console.log(inplayOddsObject.find('li[data-runnerid="'+item.id+'-2-'+odds_type.type+'-'+odds_type1.label+'"]').find('span'));
									if(odds_type1.suspend == 0 && odds_type1.value !=0 ){
										inplayOddsObject.find('li[data-runnerid="'+item.id+'-2-'+odds_type.type+'-'+odds_type1.label+'"]').find('span').text(odds_type1.value);
										var test = $('div[data-runnerid="'+item.id+'-2-'+odds_type.type+'-'+odds_type1.label+'"]');	
										inplayOddsObject.find('li[data-runnerid="'+item.id+'-2-'+odds_type.type+'-'+odds_type1.label+'"]').removeClass('disabled  lock');
										inplayOddsObject.find('li[data-runnerid="'+item.id+'-2-'+odds_type.type+'-'+odds_type1.label+'"]').find('span').removeClass('fa fa-lock lock');

										var prev_odd = inplayOddsObject.find('li[data-runnerid="'+item.id+'-2-'+odds_type.type+'-'+odds_type1.label+'"]').attr('data-prev_odd');
										if (prev_odd > odds_type1.value)
											inplayOddsObject.find('li[data-runnerid="'+item.id+'-2-'+odds_type.type+'-'+odds_type1.label+'"]').find('span').prepend('<i class="fa-caret-down down-arrow fa"></i>');
										else if (prev_odd < odds_type1.value)
											inplayOddsObject.find('li[data-runnerid="'+item.id+'-2-'+odds_type.type+'-'+odds_type1.label+'"]').find('span').prepend('<i class="fa-caret-up up-arrow fa"></i>');
		//									row_event.find('.eventodds .mlodds>li').eq(index4).find('span').prepend('<i class="fa-caret-up up-arrow fa"></i>');

										inplayOddsObject.find('li[data-runnerid="'+item.id+'-2-'+odds_type.type+'-'+odds_type1.label+'"]').attr('data-prev_odd', odds_type1.value);	

									}
									else{
										inplayOddsObject.find('li[data-runnerid="'+item.id+'-2-'+odds_type.type+'-'+odds_type1.label+'"]').find('span').text(' ');
										inplayOddsObject.find('li[data-runnerid="'+item.id+'-2-'+odds_type.type+'-'+odds_type1.label+'"]').addClass('disabled  lock');
										inplayOddsObject.find('li[data-runnerid="'+item.id+'-2-'+odds_type.type+'-'+odds_type1.label+'"]').find('span').addClass('fa fa-lock lock');

									}

		//							console.log(test);
		//							test.text(odds_type1.value);
								});	
							}
							

	//						isInplayOdds.attr('data-eventname',odds_type.type);
						});
						$('table.noChanging').remove();
//					}
//					else{
////						console.log($('table.inplaytable').remove());
//						$('table.inplaytable').addClass('hidden');
//						$('table.inplaytable ul.row').remove();
//					}
					
				}
				else{
				   // If odd is not in page create new row
//					alert('new row added');
//					var td = '<td class="col-xs-7">'+
//									'<span class="fa fa-clock-o timer"></span>'+
//									'<b class="host ellipsis">'+item.homeTeam.name+
//									'<img class="team-logo" width="25" src="'+item.homeTeam.logo+'" /></b>'+
//									'<span class="inplayscore">'+ item.home_score +'-'+ item.away_score +'</span>'+
//										'<b class="guest ellipsis"><img class="team-logo" width="25" src="'+item.awayTeam.logo+'" />'+item.awayTeam.name+'</b>' +
//															'<span class="inplaytime">'+item.minute+':00</span>'+
//															'<input class="eninplaytime" type="hidden"'+ 'value="'+item.minute+'">';
//
//							'</td>';
//					var li = '' ;
//					$.each(item.odds.data[0].types.data, function(odd_index, $odds_value){
//	//					console.log($odds_value.type);
//						if($odds_value.type == "1x2"){
//							li += '<td class="col-xs-4">'+
//									'<div class="eventodds">'+
//										'<span class="eventsuspended hidden">غیر فعال</span>'+
//										'<ul class="mlodds">';
//							$.each($odds_value.odds.data, function(odd_index2, odds_value2){
//								var dataPick;
//								if(odd_index2 == 0)
//									dataPick = '1';
//								else if (odd_index2 == 1)
//									dataPick = 'x';
//								else if (odd_index2 == 2)
//									dataPick = '2';
//								var runner_id = item.id+'2-1x2-'+odds_value2.label;
//								var label = odds_value2.label;
//								li += '<li data-runnerid="'+runner_id+'" data-pick="'+label+'" data-points="" class="col-xs-4 inplaybtn eventodd"><i></i>'+
//											'<span>'+odds_value2.value+'</span> </li>';	
//							});
//							li += '</ul> </div> </td> <td> <a class="has-tip fa fa-plus-circle more" title="شرط های بیشتر" href="InplayOdds/'+item.id+'"></a></td>';
//
//						}
//					});
//
//
//
//
//					$('table.inplaytable > tbody').append('<tr data-eventid="' + match_id + '" data-marketid="" class="odddetails">'+td+li+'</tr>');
//					var row_event = $('tr[data-eventid="' + match_id + '"]'),
//						suspend_status = 0;
				}
				
				
				
				
            } 
			else {
				// InplayMe or inplayBet
				// Set time
				
				if(item.status == 'LIVE' || item.status == 'HT' ){
						row_event.removeClass('hidden');
						var inplayHeaders = $('.inplayheaders[data-leagueid="'+item.competition.id+'"]');
						
						inplayHeaders.removeClass('hidden');
					}
				if (item.status == 'HT'  ) {
                    row_event.find('input.eninplaytime').val('HT');
//					row_event.find('span.inplaytime').text('پایان نیمه اول' );
                }
                else{
//					row_event.find('span.inplaytime').text((item.minute) + ':00' );
//					row_event.find('span.inplaytime').text((item.minute) + ':' + item.second );
//                    row_event.find('input.eninplaytime').val(parseInt(item.minute) + ':' + item.id % 100);
                    row_event.find('input.eninplaytime').val((item.minute) + ':' + item.second);
				}
//				if(item.minute > 80){
//					row_event.addClass('hidden');
//					console.log(row_event);
//				}
					
				
//                console.log(item.home_score);
				if (item.home_score === 'null')
                    item.home_score = 0;
                if (item.away_score === 'null')
                    item.home_score = 0;
				
//                row_event.find('.hScore').text(toFarsi(item.home_score));
//                row_event.find('.aScore').text(toFarsi(item.away_score));
				
                row_event.find('.inplayscore').text(toFarsi(item.home_score)+ " - " +toFarsi(item.away_score));
				if(item.status == 'HT' || item.status == 'LIVE'){
					var inplayHeader = $('.inplayheaders[data-leagueid="'+item.competition.id+'"]');
					inplayHeader.removeClass('leagueChange');
					row_event.removeClass('noChanging');
				}
					
				
				// inplayMe odd change
                $.each(item.odds.data[0].types.data, function(index3, item3) {
                    if(item3.type == 'Fulltime Result' || item3.type == '1x2'  ){
//						row_event.removeClass('noChanging');
//						row_event.siblings('.inplayheader').prev().removeClass('leagueChange');
//						row_event.prevAll('tr.inplayheader').closest('tr').removeClass('leagueChange');
						
						
						
//						console.log(row_event.prevAll('tr.inplayheader:first').removeClass('leagueChange'));
						if (suspend_status > 0 && 0 )
							row_event.find('.eventodds .eventsuspended').removeClass('hidden');
						else
							row_event.find('.eventodds .eventsuspended').addClass('hidden');
						
						if (item3.odds.data.length <= 1) {
							row_event.find('.eventodds .mlodds>li').eq(index3).addClass('disabled');
							row_event.find('.eventodds .mlodds>li').eq(index3).find('span').text(' ')
						} else
							$.each(item3.odds.data, function(index4,item4){
								suspend_status = item4.suspend;		
								// check odds susoended
								if(suspend_status == 0 && item4.value != 0 ){
//									row_event.find('.eventodds .eventsuspended').addClass('hidden');
									row_event.find('.eventodds .mlodds>li').eq(index4).removeClass('disabled  lock');
									row_event.find('.eventodds .mlodds>li').eq(index4).find('span').removeClass('fa fa-lock lock');
									
									row_event.find('.eventodds .mlodds>li').eq(index4).find('span').text(item4.value);
	//								console.log(item4.value);
	//								console.log(index4);
									var prev_odd = row_event.find('.eventodds .mlodds>li').eq(index4).attr('data-prev_odd');
	//								console.log(prev_odd);
									if (prev_odd > item4.value)
										row_event.find('.eventodds .mlodds>li').eq(index4).find('span').prepend('<i class="fa-caret-down down-arrow fa"></i>');
									else if (prev_odd < item4.value)
										row_event.find('.eventodds .mlodds>li').eq(index4).find('span').prepend('<i class="fa-caret-up up-arrow fa"></i>');
	//									row_event.find('.eventodds .mlodds>li').eq(index4).find('span').prepend('<i class="fa-caret-up up-arrow fa"></i>');

									row_event.find('.eventodds .mlodds>li').eq(index4).attr('data-prev_odd', item4.value);	
								}
								else{
//									row_event.find('.eventodds .eventsuspended').removeClass('hidden');
									row_event.find('.eventodds .mlodds>li').eq(index4).find('span').text(' ');
									row_event.find('.eventodds .mlodds>li').eq(index4).addClass('disabled  lock');
									row_event.find('.eventodds .mlodds>li').eq(index4).find('span').addClass('fa fa-lock lock');
	//								console.log(item4.value);
	//								console.log(index4);
									var prev_odd = row_event.find('.eventodds .mlodds>li').eq(index4).attr('data-prev_odd');
	//								console.log(prev_odd);
								}
//								if (suspend_status > 0 && 0)
//									row_event.find('.eventodds .eventsuspended').removeClass('hidden');
//								else{}
//									row_event.find('.eventodds .eventsuspended').addClass('hidden');
								
							});

//						var prev_odd = row_event.find('.eventodds .mlodds>li').eq(index3).attr('data-prev_odd');
//						if (prev_odd > item3.odd)
//							row_event.find('.eventodds .mlodds>li').eq(index3).find('span').prepend('<i class="fa-caret-down down-arrow fa"></i>');
//						else if (prev_odd < item3.odd)
//							row_event.find('.eventodds .mlodds>li').eq(index3).find('span').prepend('<i class="fa fa-caret-up up-arrow"></i>');
//						row_event.find('.eventodds .mlodds>li').eq(index3).attr('data-prev_odd', item3.odd);
					}
                });
//				$('.leagueChange').addClass('hidden');
				$('.noChanging').addClass('hidden');
            }
//			console.log(row_event);
			
        });
		$('.leagueChange').addClass('hidden');
    }
	
}

function updateInplayOdds1(UpdatedMatches) {
	$('.inplayheader').addClass('leagueChange');
	if(UpdatedMatches == '' ){
		window.location.replace("../inplayBet");
	}
    var preMatches = $('table.inplaytable > tbody > tr.odddetails');
    var Headers = $('table.inplaytable > tbody > tr.inplayheader');
	
//	console.log(preMatches);
//	console.log(UpdatedMatches);
    $.each(Headers, function(i, item) {
        if ($('tr.odddetails[data-league="' + $(item).attr('data-league') + '"]').length == 0)
            $('tr.inplayheader[data-league="' + $(item).attr('data-league') + '"]').remove();
    });
	
    // if the matche was removed, remove the tr 
    if (UpdatedMatches !== undefined) {
        $('span.totalevents').text(toFarsi(Object.keys(UpdatedMatches).length));
        $.each(preMatches, function(i, item) {
//			console.log($(item).attr('data-eventid'));
//			console.log(UpdatedMatches);
			if( $(item).attr('data-eventid') != undefined){
				if (!($(item).attr('data-eventid') in UpdatedMatches)) {
					$(item).fadeOut().remove();
					$('span.totalevents').text(toFarsi(Object.keys(UpdatedMatches).length));
					}	
			}
//			else if (1){
//				$.each($('.eventodds'), function(index1, item1){
//					var row_events = $('tr[data-eventname="' + $(item1).data().eventname + '"]'),
//                    suspend_status = 0;
//					console.log(row_events);
//				});
//				console.log($('.eventodds'));
				
//			}
            
        });
    }
//	console.log(UpdatedMatches);

    if (UpdatedMatches !== undefined) {
		
        $.each(UpdatedMatches, function(match_id, item) {
			
            var preMatches = $('table.inplaytable > tbody > tr.odddetails');

            var row_event = $('tr[data-eventid="' + match_id + '"]'),
                    suspend_status = 0;
			
			
//			console.log(row_event);
            if (row_event.length == 0) {
				
//				var isInplayOdds = $('div[data-eventname="Fulltime Result"]'),suspend_status = 0;
				var isInplayOdds = $('.eventodds[data-eventname]'),suspend_status = 0;
				if(isInplayOdds.length > 0){
					// Update inplay odds
//					console.log(isInplayOdds);
//					console.log(item);
					$('div.reasult').find('span').eq(0).text(item.home_score);
					$('div.reasult').find('span').eq(2).text(item.away_score);
					var half;
					if(item.status == 'LIVE'){
						if(item.minute < 45)
							half = 'نیمه اول';
						else
							half = 'نیمه دوم';
					}
					else if(item.status == 'HT'){
						half = '';
						
					}
					else{
						if(item.minute == null)
							half = 'شروع نشده';
						else
							half = 'بازی تمام شده';
					}
					
					$('div.play > div.rand').find('span').eq(0).text(half);
					if(item.status == 'HT'){
						$('input.eninplaytime').val('HT');
//						$('div.play > div.rand').find('span').eq(1).text('');
//						$('div.play > div.rand').find('span').eq(1).text('45:00');	
					}else{
//						$('div.play > div.rand').find('span').eq(1).text(item.minute+':00');
//						$('div.play > div.rand').find('span').eq(1).text(item.minute+':'+item.second);	
//						$('input.eninplaytime').val((item.minute) + ':' + item.second);
					}
					
					if(item.minute > 45){
						$('input.eninplaytime').val((item.minute) + ':' + item.second);
					}
					
					
					// score
					$('div.mb-20').find('tr').eq(1).find('td').eq(1).text(item.home_score);
					$('div.mb-20').find('tr').eq(2).find('td').eq(1).text(item.away_score);
					// Yellow cards
					$('div.mb-20').find('tr').eq(1).find('td').eq(2).text(item.homeTeam.yellow_cards);
					$('div.mb-20').find('tr').eq(2).find('td').eq(2).text(item.awayTeam.yellow_cards);
					// Red cards
					$('div.mb-20').find('tr').eq(1).find('td').eq(3).text(item.homeTeam.red_cards);
					$('div.mb-20').find('tr').eq(2).find('td').eq(3).text(item.awayTeam.red_cards);
					// Corners
					$('div.mb-20').find('tr').eq(1).find('td').eq(4).text(item.homeTeam.corners);
					$('div.mb-20').find('tr').eq(2).find('td').eq(4).text(item.awayTeam.corners);
					// Penalties
					$('div.mb-20').find('tr').eq(1).find('td').eq(5).text(item.home_score_penalties);
					$('div.mb-20').find('tr').eq(2).find('td').eq(5).text(item.away_score_penalties);
					
//					if(item.minute < 80 ){
//						
						$('table.inplaytable').addClass('noChanging');
						$.each(item.odds.data[0].types.data, function(odd_index,odds_type){
							if(((item.minute < 80 && odds_type.type != '1x2') || (odds_type.type == '1x2')) && (item.status != 'FT' || item.status != 'CNCL') ){
								var inplayOddsObject = $('div[data-eventname="'+odds_type.type+'"]'),suspend_status = 0;
								inplayOddsObject.parent().parent().parent().parent().removeClass('noChanging');
//								console.log(inplayOddsObject);

									$.each(odds_type.odds.data, function(odd_index1,odds_type1){
		//							console.log(odds_type1);
		//							console.log(inplayOddsObject.find('li[data-runnerid="'+item.id+'-2-'+odds_type.type+'-'+odds_type1.label+'"]').find('span'));
									if(odds_type1.suspend == 0 && odds_type1.value !=0 ){
										inplayOddsObject.find('li[data-runnerid="'+item.id+'-2-'+odds_type.type+'-'+odds_type1.label+'"]').find('span').text(odds_type1.value);
										var test = $('div[data-runnerid="'+item.id+'-2-'+odds_type.type+'-'+odds_type1.label+'"]');	
										inplayOddsObject.find('li[data-runnerid="'+item.id+'-2-'+odds_type.type+'-'+odds_type1.label+'"]').removeClass('disabled  lock');
										inplayOddsObject.find('li[data-runnerid="'+item.id+'-2-'+odds_type.type+'-'+odds_type1.label+'"]').find('span').removeClass('fa fa-lock lock');

										var prev_odd = inplayOddsObject.find('li[data-runnerid="'+item.id+'-2-'+odds_type.type+'-'+odds_type1.label+'"]').attr('data-prev_odd');
										if (prev_odd > odds_type1.value)
											inplayOddsObject.find('li[data-runnerid="'+item.id+'-2-'+odds_type.type+'-'+odds_type1.label+'"]').find('span').prepend('<i class="fa-caret-down down-arrow fa"></i>');
										else if (prev_odd < odds_type1.value)
											inplayOddsObject.find('li[data-runnerid="'+item.id+'-2-'+odds_type.type+'-'+odds_type1.label+'"]').find('span').prepend('<i class="fa-caret-up up-arrow fa"></i>');
		//									row_event.find('.eventodds .mlodds>li').eq(index4).find('span').prepend('<i class="fa-caret-up up-arrow fa"></i>');

										inplayOddsObject.find('li[data-runnerid="'+item.id+'-2-'+odds_type.type+'-'+odds_type1.label+'"]').attr('data-prev_odd', odds_type1.value);	

									}
									else{
										inplayOddsObject.find('li[data-runnerid="'+item.id+'-2-'+odds_type.type+'-'+odds_type1.label+'"]').find('span').text(' ');
										inplayOddsObject.find('li[data-runnerid="'+item.id+'-2-'+odds_type.type+'-'+odds_type1.label+'"]').addClass('disabled  lock');
										inplayOddsObject.find('li[data-runnerid="'+item.id+'-2-'+odds_type.type+'-'+odds_type1.label+'"]').find('span').addClass('fa fa-lock lock');

									}

		//							console.log(test);
		//							test.text(odds_type1.value);
								});	
							}
							

	//						isInplayOdds.attr('data-eventname',odds_type.type);
						});
						$('table.noChanging').remove();
//					}
//					else{
////						console.log($('table.inplaytable').remove());
//						$('table.inplaytable').addClass('hidden');
//						$('table.inplaytable ul.row').remove();
//					}
					
				}
				else{
				   // If odd is not in page create new row
					alert('new row added');
//					var td = '<td class="col-xs-7">'+
//									'<span class="fa fa-clock-o timer"></span>'+
//									'<b class="host ellipsis">'+item.homeTeam.name+
//									'<img class="team-logo" width="25" src="'+item.homeTeam.logo+'" /></b>'+
//									'<span class="inplayscore">'+ item.home_score +'-'+ item.away_score +'</span>'+
//										'<b class="guest ellipsis"><img class="team-logo" width="25" src="'+item.awayTeam.logo+'" />'+item.awayTeam.name+'</b>' +
//															'<span class="inplaytime">'+item.minute+':00</span>'+
//															'<input class="eninplaytime" type="hidden"'+ 'value="'+item.minute+'">';
//
//							'</td>';
//					var li = '' ;
//					$.each(item.odds.data[0].types.data, function(odd_index, $odds_value){
//	//					console.log($odds_value.type);
//						if($odds_value.type == "1x2"){
//							li += '<td class="col-xs-4">'+
//									'<div class="eventodds">'+
//										'<span class="eventsuspended hidden">غیر فعال</span>'+
//										'<ul class="mlodds">';
//							$.each($odds_value.odds.data, function(odd_index2, odds_value2){
//								var dataPick;
//								if(odd_index2 == 0)
//									dataPick = '1';
//								else if (odd_index2 == 1)
//									dataPick = 'x';
//								else if (odd_index2 == 2)
//									dataPick = '2';
//								var runner_id = item.id+'2-1x2-'+odds_value2.label;
//								var label = odds_value2.label;
//								li += '<li data-runnerid="'+runner_id+'" data-pick="'+label+'" data-points="" class="col-xs-4 inplaybtn eventodd"><i></i>'+
//											'<span>'+odds_value2.value+'</span> </li>';	
//							});
//							li += '</ul> </div> </td> <td> <a class="has-tip fa fa-plus-circle more" title="شرط های بیشتر" href="InplayOdds/'+item.id+'"></a></td>';
//
//						}
//					});
//
//
//
//
//					$('table.inplaytable > tbody').append('<tr data-eventid="' + match_id + '" data-marketid="" class="odddetails">'+td+li+'</tr>');
//					var row_event = $('tr[data-eventid="' + match_id + '"]'),
//						suspend_status = 0;
				}
				
				
				
				
            } 
			else {
				// InplayMe or inplayBet
				// Set time
				if (item.status == 'HT'  ) {
                    row_event.find('input.eninplaytime').val('HT');
//					row_event.find('span.inplaytime').text('پایان نیمه اول' );
                }
                else{
//					row_event.find('span.inplaytime').text((item.minute) + ':00' );
//					row_event.find('span.inplaytime').text((item.minute) + ':' + item.second );
//                    row_event.find('input.eninplaytime').val(parseInt(item.minute) + ':' + item.id % 100);
                    row_event.find('input.eninplaytime').val((item.minute) + ':' + item.second);
				}
//				if(item.minute > 80){
//					row_event.addClass('hidden');
//					console.log(row_event);
//				}
					
				
//                console.log(item.home_score);
				if (item.home_score === 'null')
                    item.home_score = 0;
                if (item.away_score === 'null')
                    item.home_score = 0;
				
//                row_event.find('.hScore').text(toFarsi(item.home_score));
//                row_event.find('.aScore').text(toFarsi(item.away_score));
				
                row_event.find('.inplayscore').text(toFarsi(item.home_score)+ " - " +toFarsi(item.away_score));
				row_event.addClass('noChanging');
				
				// inplayMe odd change
                $.each(item.odds.data[0].types.data, function(index3, item3) {
                    if(item3.type == 'Fulltime Result' || item3.type == '1x2' ){
						row_event.removeClass('noChanging');
//						row_event.siblings('.inplayheader').prev().removeClass('leagueChange');
//						row_event.prevAll('tr.inplayheader').closest('tr').removeClass('leagueChange');
						var inplayHeader = $('.inplayheader[data-leagueid="'+item.competition.id+'"]');
						inplayHeader.removeClass('leagueChange');
//						console.log(row_event.prevAll('tr.inplayheader:first').removeClass('leagueChange'));
						if (suspend_status > 0 && 0 )
							row_event.find('.eventodds .eventsuspended').removeClass('hidden');
						else
							row_event.find('.eventodds .eventsuspended').addClass('hidden');
						
						if (item3.odds.data.length <= 1) {
							row_event.find('.eventodds .mlodds>li').eq(index3).addClass('disabled');
							row_event.find('.eventodds .mlodds>li').eq(index3).find('span').text(' ')
						} else
							$.each(item3.odds.data, function(index4,item4){
								suspend_status = item4.suspend;		
								// check odds susoended
								if(suspend_status == 0 && item4.value != 0 ){
//									row_event.find('.eventodds .eventsuspended').addClass('hidden');
									row_event.find('.eventodds .mlodds>li').eq(index4).removeClass('disabled  lock');
									row_event.find('.eventodds .mlodds>li').eq(index4).find('span').removeClass('fa fa-lock lock');
									
									row_event.find('.eventodds .mlodds>li').eq(index4).find('span').text(item4.value);
	//								console.log(item4.value);
	//								console.log(index4);
									var prev_odd = row_event.find('.eventodds .mlodds>li').eq(index4).attr('data-prev_odd');
	//								console.log(prev_odd);
									if (prev_odd > item4.value)
										row_event.find('.eventodds .mlodds>li').eq(index4).find('span').prepend('<i class="fa-caret-down down-arrow fa"></i>');
									else if (prev_odd < item4.value)
										row_event.find('.eventodds .mlodds>li').eq(index4).find('span').prepend('<i class="fa-caret-up up-arrow fa"></i>');
	//									row_event.find('.eventodds .mlodds>li').eq(index4).find('span').prepend('<i class="fa-caret-up up-arrow fa"></i>');

									row_event.find('.eventodds .mlodds>li').eq(index4).attr('data-prev_odd', item4.value);	
								}
								else{
//									row_event.find('.eventodds .eventsuspended').removeClass('hidden');
									row_event.find('.eventodds .mlodds>li').eq(index4).find('span').text(' ');
									row_event.find('.eventodds .mlodds>li').eq(index4).addClass('disabled  lock');
									row_event.find('.eventodds .mlodds>li').eq(index4).find('span').addClass('fa fa-lock lock');
	//								console.log(item4.value);
	//								console.log(index4);
									var prev_odd = row_event.find('.eventodds .mlodds>li').eq(index4).attr('data-prev_odd');
	//								console.log(prev_odd);
								}
//								if (suspend_status > 0 && 0)
//									row_event.find('.eventodds .eventsuspended').removeClass('hidden');
//								else{}
//									row_event.find('.eventodds .eventsuspended').addClass('hidden');
								
							});

//						var prev_odd = row_event.find('.eventodds .mlodds>li').eq(index3).attr('data-prev_odd');
//						if (prev_odd > item3.odd)
//							row_event.find('.eventodds .mlodds>li').eq(index3).find('span').prepend('<i class="fa-caret-down down-arrow fa"></i>');
//						else if (prev_odd < item3.odd)
//							row_event.find('.eventodds .mlodds>li').eq(index3).find('span').prepend('<i class="fa fa-caret-up up-arrow"></i>');
//						row_event.find('.eventodds .mlodds>li').eq(index3).attr('data-prev_odd', item3.odd);
					}
                });
				$('.leagueChange').addClass('hidden');
				$('.noChanging').addClass('hidden');
            }
//			console.log(row_event);
        });
    }
	
}

function updateUpcoming(n) {
    $.each(n, function(n, t) {
        var i = $('tr[data-eventid="' + t.EventId + '"]'),
                o, e, u, r, f;
        t.Command == "Update" ? (i.find(".inplayscore").text(toFarsi(t.Score1 + " - " + t.Score2)), o = Math.round(t.GameTime + (new Date(t.CurrentTime) - new Date(t.LastTimeUpdate)) / 1e3), e = secondsTimeSpanToMS(o, t.Eventstatus), i.find(".eninplaytime").val(e), i.find(".inplaytime").text(toFarsi(e))) : t.Command == "Add" && i.length == 0 ? (u = $('tr[data-leagueid="' + t.LeagueId + '"]'), u.length == 0 && (r = '<tr class="inplayheader" data-leagueid="' + t.LeagueId + '" data-rankid="' + t.LeagueRankID + '">', r += '<th><span class="match"><b>' + t.LeagueName + "<\/b><\/span><\/th>", r += '<th style="width:44px"><b>1<\/b><\/th>', r += '<th style="width:44px"><b>X<\/b><\/th>', r += '<th style="width:44px"><b>2<\/b><\/th>', r += '<th style="width:25px"><\/th>', r += "<\/tr>", $(".inplayheader").each(function() {
            if (parseInt($(this).data("rankid")) > t.LeagueRankID)
                return $(this).before(r), u = $('tr[data-leagueid="' + t.LeagueId + '"]'), !1
        }), u.length == 0 && ($("table.inplaytable").append(r), u = $('tr[data-leagueid="' + t.LeagueId + '"]'))), f = '<tr data-eventid="' + t.EventId + '" data-marketid="' + t.MarketId + '" class="odddetails">', f += '<td><span class="fa fa-clock-o timer"><\/span><b class="host ellipsis">' + t.Team1 + '<\/b><span class="scoredash">-<\/span><b class="guest ellipsis">' + t.Team2 + '<\/b>\n<span class="inplaytime2">' + getTime(t.StartAt) + "<\/span><\/td>", f += '<td style="padding:0" colspan="3"><\/td>', f += '<td><a class="has-tip fa fa-plus-circle more" href="/Sport/PreEvent/' + t.EventId + '" title="' + ($("#lang").val() == "fa" ? "شرط های بیشتر" : "More Markets") + '"><\/a><\/td>', f += "<\/tr>", u.after(f)) : t.Command == "Remove" && (i.prev().hasClass("inplayheader") && (i.next().hasClass("inplayheader") || i.next(".inplayheader").length == 0) && i.prev().remove(), i.remove(), $(".totalevents").text(toFarsi($("tr.odddetails").length)))
    });
    $(".totalevents").text(toFarsi($("tr.odddetails").length));
    $(".has-tip").frosty()
}

function updateInplay(n) {
    $.each(n, function(n, t) {
        var i = $('tr[data-eventid="' + t.EventId + '"]'),
                s, e, h, u, f, r;
        if (t.Command == "Update")
            i.find(".inplayscore").text(toFarsi(t.Score1 + " - " + t.Score2)), s = Math.round(t.GameTime + (new Date(t.CurrentTime) - new Date(t.LastTimeUpdate)) / 1e3), e = secondsTimeSpanToMS(s, t.Eventstatus), i.find(".eninplaytime").val(e), i.find(".inplaytime").text(toFarsi(e));
        else if (t.Command == "Remove")
            i.prev().hasClass("inplayheader") && (i.next().hasClass("inplayheader") || i.next(".inplayheader").length == 0) && i.prev().remove(), i.remove(), $(".totalevents").text(toFarsi($("tr.odddetails").length));
        else if (t.Command == "Add" && i.length == 0) {
            h = $(".branchheader");
            h.length == 0 && (u = '<tr class="branchheader inplayheader">', u += '<th><span class="match">', u += "<strong>" + ($("#lang").val() == "fa" ? "فوتبال" : "Football") + '<\/strong>\n(<span class="totalevents"><\/span>)', u += "<\/span><\/th>", u += '<th colspan="4"><\/th>', u += "<\/tr>", $("table.inplaytable").prepend(u));
            f = $('tr[data-leagueid="' + t.LeagueId + '"]');
            f.length == 0 && (r = '<tr class="inplayheader" data-leagueid="' + t.LeagueId + '" data-rankid="' + t.LeagueRankID + '">', r += '<th><span class="match"><b>' + t.LeagueName + "<\/b><\/span><\/th>", r += '<th style="width:44px"><b>1<\/b><\/th>', r += '<th style="width:44px"><b>X<\/b><\/th>', r += '<th style="width:44px"><b>2<\/b><\/th>', r += '<th style="width:25px"><\/th>', r += "<\/tr>", $(".inplayheader").each(function() {
                if (parseInt($(this).data("rankid")) > t.LeagueRankID)
                    return $(this).before(r), f = $('tr[data-leagueid="' + t.LeagueId + '"]'), !1
            }), f.length == 0 && ($("table.inplaytable").append(r), f = $('tr[data-leagueid="' + t.LeagueId + '"]')));
            var s = Math.round(t.GameTime + (new Date(t.CurrentTime) - new Date(t.LastTimeUpdate)) / 1e3),
                    o = '<tr data-eventid="' + t.EventId + '" data-marketid="' + t.MarketId + '" class="odddetails">',
                    e = secondsTimeSpanToMS(s, t.Eventstatus);
            o += '<td><span class="fa fa-clock-o timer green"><\/span><b class="host ellipsis">' + t.Team1 + '<\/b><span class="inplayscore">' + toFarsi(t.Score1 + " - " + t.Score2) + '<\/span><b class="guest ellipsis">' + t.Team2 + '<\/b>\n<span class="inplaytime">' + toFarsi(e) + '<\/span><input class="eninplaytime" type="hidden" value="' + e + '"><\/td>';
            o += '<td style="padding:0" colspan="3"><\/td>';
            o += '<td><a class="has-tip fa fa-plus-circle more" href="/Sport/Event/' + t.EventId + '" title="' + ($("#lang").val() == "fa" ? "شرط های بیشتر" : "More Markets") + '"><\/a><\/td>';
            o += "<\/tr>";
            f.after(o)
        }
    });
    $(".totalevents").text(toFarsi($("tr.odddetails").length));
    $(".has-tip").frosty()
}

function secondsTimeSpanToMS(n, t) {
    if (t == 3)
        return $("#lang").val() == "fa" ? "پایان نیمه اول" : "HT";
    if (t == 100)
        return $("#lang").val() == "fa" ? "پایان" : "FT";
    var i = Math.floor(n / 60);
	
    return n -= i * 60, (i < 10 ? "0" + i : i) + ":" + (n < 10 ? "0" + n : n)
}

function getTime(n) {
    var t = n.split("T")[1].split(":");
    return toFarsi(checkTime(parseInt(t[0])) + ":" + checkTime(parseInt(t[1])))
}

function checkTime(n) {
    return n < 10 && (n = "0" + n), n
}

function MStoSeconds(n) {
    var t = n.split(":");
    return parseInt(t[0]) * 60 + parseInt(t[1])
}

function updateTimers() {
    $(".eninplaytime").each(function() {
//		console.log('update timers');
        if ($(this).val() == "HT" || $(this).val() == "پایان نیمه اول") {
            //    $(this).val(secondsTimeSpanToMS(n, 3));
            $(this).prev().text(toFarsi(secondsTimeSpanToMS(n, 3)))
        } else if ($(this).val() == "94") {
            $(this).prev().text(toFarsi(secondsTimeSpanToMS(n, 100)))
        }
        else {
            var secss = $(this).prev().text();
            var n = MStoSeconds(secss) + 1;
            if(isNaN(n)) {
                n = MStoSeconds($(this).val()) + 1;
            }
            
            n = n > 5640 ? 5640 : n;
//            console.log(n);
    //    if ($(this).val() != "HT" && $(this).val() != "نیمه اول" && $(this).val() != "FT" && $(this).val() != "پایان") {
    //        t = 3;
    //    }
            $(this).val(secondsTimeSpanToMS(n, 0));
            $(this).prev().text(toFarsi(secondsTimeSpanToMS(n, 0)));
        }
    });

    
}

function toFarsi(n) {
    var t, r, i, u;
    if (lang == "fa") {
        for (n = n + "", t = "", r = 0; r < n.length; r++)
            i = n.charCodeAt(r), i >= 48 && i <= 57 ? (u = i + 1728, t = t + String.fromCharCode(u)) : t = t + String.fromCharCode(i);
        return t
    }
    return n
}

function showPoints(n) {
    var i = Math.floor(n),
            t = Math.floor((n - i) * 100);
    return t == 25 || t == 75 ? n > 0 ? i + (t - 25) / 100 + "," + (i + (t + 25) / 100) : i + (t + 25) / 100 + "," + (i + (t - 25) / 100) : n
}
var en_cur = "",
        fa_cur = "",
        lang = "";
$(document).ready(function() {
    lang = "fa";
    loadBetslipCookie();
    lang == "fa" ? fa_cur = "تومان" : en_cur = "$";
    $(".datedropdown").easyDropDown({
        cutOff: 8,
        wrapperClass: "dropdown datedropdown",
        onChange: function(n) {
            window.location = n.value
        }
    });
	
    prepareButtonEvents();
    $(".deleteall").click(function() {
        $.removeCookie("betslip", {
            path: "/"
        });
        $(".selectedodds div.betlist ul").each(function() {
            var n = $(this).attr("class").split(" ")[1];
            $(".odds td").removeClass(n)
        });
        $(".betlist ul").remove();
        $(".odds li").removeClass("selected");
        calcTotal();
        $(".nobet").show();
        $(".bettotal").hide();
        $(".placebet").removeClass("disabled in-progress").prop("disabled", !1);
//		console.log('delete');
		var count = 0;
		$(".selectedodds div.betlist ul").not(".suspended").each(function(r){
			count++;
		});
		$('.notification').text(count);
    });
    $(".placebet").unbind().click(function(n) {

        var mix = "";
        var total = $('.multiple tr').length;
        $(".multiple tr").each(function(index) {
            var t = $(this).find("td:eq(0)").html().split(" ("),
                    i = $(this).find(".stake").val() == "" ? "0" : $(this).find(".stake").val();
            if (index === total - 1) {
                mix += t[0] + "-" + t[1].replace("<span>", "").replace("<\/span>)", "") + "-" + i
            } else {
                mix += t[0] + "-" + t[1].replace("<span>", "").replace("<\/span>)", "") + "-" + i + '/'
            }
        })
        var myItem = $("ul.inplay .betlist ul.bet");
        var jsonObject = {data: []};
        if (myItem.length == 1) {
            jsonObject.data.push({
                "match_id": myItem.data("eventid"),
                "runner_id": myItem.data("runnerid"),
                "pick": myItem.data("pick"),
                "stake": myItem.find("input.stake").val(),
                "odd": myItem.find("span.odd span").text(),
            });
            mix = 'تکی ها-x1-' + myItem.find("input.stake").val();
        } else {
            myItem.each(function(i) {
                jsonObject.data.push({
                    "match_id": $(this).data("eventid"),
                    "runner_id": $(this).data("runnerid"),
                    "pick": $(this).data("pick"),
                    "stake": $(this).find("input.stake").val(),
                    "odd": $(this).find("span.odd span").text(),
                });
            });
        }
        var action = base_url + "bets/insertBet";

        n.handled !== !0 && (n.handled = !0, n.stopImmediatePropagation(), $(this).prop("disabled", !0), $(this).addClass("disabled in-progress"), $(".alertbox").addClass("hidden"), parseFloat($(".totalstake").html().replace("$", "").replace(/,/g, "")) > 0 ? $.post(action, {
            'forms': jsonObject, 'mix_data': mix
        }, function(n) {
            $(".alertbox").removeClass("notice").removeClass("errorbox").removeClass("warning").removeClass("success");
            switch (n.result) {
                case "Login":
                    $(".alertbox").html((lang == "fa" ? "برای ثبت فرم باید ابتدا با کاربری خود وارد سایت شوید." : "You need to be logged in in order to place a bet.") + '<br /><br /><a class="Login" href="javascript:void(0)">' + (lang == "fa" ? "ورود" : "Login") + "<\/a>").addClass("notice").removeClass("hidden");
                    $(".Login").unbind("click").click(function() {
                        window.location = base_url + "/users/login"
                    });
                    break;
                case "MinBet":
                    $(".alertbox").html(lang == "fa" ? "حداقل مبلغ پیش بینی ۱۰۰۰ تومان است." : "The minimum bet is 1000 Tomans.").addClass("errorbox").removeClass("hidden");
                    break;
                case "MaxBet":
                    $(".alertbox").html(lang == "fa" ? "حداکثر مبلغ پیش بینی ۵میلیون تومان است." : "The Maximum bet is 5000000 Tomans.").addClass("errorbox").removeClass("hidden");
                    break;
                case "MatchFuckedUp":
                    $(".alertbox").html(lang == "fa" ? "در ثبت بازی خطایی رخ داد." : "Error on insert bet").addClass("errorbox").removeClass("hidden");
                    $(".placebet").prop("disabled", !1);
                    $(".placebet").removeClass("disabled in-progress")
                    break;
                case "OddChanged":
                    $(".alertbox").html(lang == "fa" ? "ضرایب پیش بینی تغییر کرده است. مجدد تلاش کنید." : "The selected odds have been changed. Please try again.").addClass("errorbox").removeClass("hidden");
                    break;
                case "Suspend":
                    $(".alertbox").html(lang == "fa" ? "بازی غیرفعال است. مجدد تلاش کنید." : "The selected odds have been suspended. Please try again.").addClass("errorbox").removeClass("hidden");
                    break;
                case "Expired":
                    $(".alertbox").html(lang == "fa" ? "مهلت پیش بینی برای مسابقات انتخاب شده تمام شده است." : "This bet slip has been timed out.").addClass("errorbox").removeClass("hidden");
                    break;
                case "Expired2":
                    $(".alertbox").html(lang == "fa" ? "مشکلی رخ داده است. حجم بالای عملیات." : "This bet slip has been timed out.").addClass("errorbox").removeClass("hidden");
                    break;
                case "LowBalance":
                    $(".alertbox").html((lang == "fa" ? "موجودی حساب شما برای این پیش بینی کافی نیست. ابتدا موجودی حساب خود را افزایش دهید و دوباره سعی کنید." : "You don't have sufficient funds in your account to place this bet. Deposit your account and try again.") + '<br /><br /><a class="closeAlert" href="javascript:void(0)">' + (lang == "fa" ? "بستن" : "Close") + '<\/a><a class="gototopup" href="javascript:void(0)">' + (lang == "fa" ? "افزایش موجودی" : "Deposit") + "<\/a>").addClass("warning").removeClass("hidden");
                    $(".closeAlert").unbind("click").click(function() {
                        $(this).parent().addClass("hidden")
                    });
                    $(".gototopup").unbind("click").click(function() {
                        window.location = base_url + "payment/credit"
                    });
                    break;
                case "Error":
                case "Invalid":
                    $(".alertbox").html((lang == "fa" ? "متاسفانه خطایی در هنگام ثبت فرم رخ داده است." : "Oops! Something went wrong.") + '<br /><a class="closeAlert" href="javascript:void(0)">' + (lang == "fa" ? "سعی دوباره" : "Try again") + "<\/a>").addClass("errorbox").removeClass("hidden");
                    $(".closeAlert").unbind("click").click(function() {
                        $(this).parent().addClass("hidden");
                        $(this).prop("disabled", !1).removeClass("disabled in-progress");
                    });
                    break;
                default:
                case "Success":
                    $(".deleteall").trigger("click");
                    $(".alertbox").html((lang == "fa" ? "فرم شما با موفقیت ثبت شد." : "Your bet has been placed successfully.") + '<br /><br /><a class="closeAlert" href="javascript:void(0)">' + (lang == "fa" ? "بستن" : "Close")).addClass("success").removeClass("hidden");
                    $(".closeAlert").unbind("click").click(function() {
                        $(this).parent().addClass("hidden")
                    });
                    $('body > div > header > div.top_bar > div > ul > li.balance.last > span').html(n.new_cash);
                    $(".gotomybets").unbind("click").click(function() {
                        window.location = base_url + "bets/myrecords";
                    })
            }
            $(".placebet").prop("disabled", !1);
            $(".placebet").removeClass("disabled in-progress")
        }, 'json').fail(function(xhr, status, error) {

            $(".alertbox").html((lang == "fa" ? "متاسفانه خطایی در هنگام ثبت فرم رخ داده است. دوباره تلاش کنید." : "Oops! Something went wrong.") + '<br /><a class="closeAlert" href="javascript:void(0)">' + (lang == "fa" ? "سعی دوباره" : "Try again") + "<\/a>").addClass("errorbox").removeClass("hidden");
            $(".closeAlert").unbind("click").click(function() {
                $(this).parent().addClass("hidden");
                $(this).prop("disabled", !1).removeClass("disabled in-progress");
            });
            $(this).prop("disabled", !1).removeClass("disabled in-progress")
        }) : ($(this).prop("disabled", !1), $(this).removeClass("disabled in-progress")))
    })
});
var updateCounter = 0,
        updateOddsCounter = 0,
        scoreCounter = 0,
        updateBetslipCounter = 0;
$(document).ready(function() {
    $('#upcoming-select-id').on('change', function() {
        window.location.replace($(this).val());
    });
//    setInterval(function() {
//        updateOddsCounter -= 1;
//        updateOddsCounter <= 0 && updateOdds()
//    }, 1e3);
//    setInterval(function() {
//        updateBetslipCounter -= 1;
//        updateBetslipCounter <= 0 && updateBetslip()
//    }, 1e3);
})