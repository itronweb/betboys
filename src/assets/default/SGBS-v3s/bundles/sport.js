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
}

function createBet(n, t, i, r, u, f, e, o) {
    var s = '<ul data-eventid="' + n + '" data-pick="' + r + '" data-points="' + o + '" data-marketid="' + f + '" data-runnerid="' + e + '" class="bet">';
    return s += '<li style="overflow:hidden"><span class="fa fa-times-circle delete has-tip" title="' + ($("#lang").val() == "fa" ? "&#1581;&#1584;&#1601;" : "Delete") + '"><\/span>' + t + "<\/li>", s += "<li>" + u + "<\/li>", s += '<li><span class="pick"><div class="ellipsis floatleft">' + (lang == "fa" ? "&#1575;&#1606;&#1578;&#1582;&#1575;&#1576;" : "Pick") + ": " + r + ' <\/div><div class="ltrinput points floatleft margin-left-5px">' + o + '<\/div><\/span><span class="odd"><span>' + parseFloat(i).toFixed(2) + '<\/span><i class="marginright"><\/i><\/span><\/li>', s += '<li><input class="input stake" type="text" placeholder="0" /><span>' + (lang == "fa" ? "&#1605;&#1576;&#1604;&#1594; &#1576;&#1585;&#1583;" : "To Win") + '&nbsp;<span class="ToWin">' + en_cur + "0<\/span><\/span><\/li>", s += "<\/ul>", $('[data-runnerid="' + e + '"]').addClass("selected"), s
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
        $('[data-runnerid="' + $(this).data("runnerid") + '"]').addClass("selected")
    })
}

function saveBet2Cookie() {
    var n = [],
            i = $.cookie("betslip");
    typeof i != "undefined" && (n = JSON.parse(i));
    $(".betlist ul.bet").each(function() {
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
    return $(".selectedodds div.betlist ul").not(".suspended").length > 1 && $(".selectedodds div.betlist ul").not(".suspended").length < 9 && (t = [], $(".selectedodds div.betlist ul").not(".suspended").find(".odd").each(function() {
        t.push($.trim($(this).text()))
    }), i = !1, $(".selectedodds div.betlist ul").not(".suspended").each(function() {
        var n = $(this).data("eventid");
        if ($('.betlist ul[data-eventid="' + n + '"]').not(".suspended").length > 1)
            return i = !0, !1
    }), $(".selectedodds div.betlist ul").not(".suspended").each(function(r) {
        n += "<tr>";
        n += "<td>" + getMultipleName(r + 1) + " (<span>x" + getMultipleCount(r, t) + "<\/span>)<\/td>";
        n += r == 0 ? '<td><input class="mulOdd" type="hidden" value="' + getMultipleOdds(r + 1, t) + '" /><span class="ToWin ignore"><\/span><\/td>' : '<td><input class="mulOdd" type="hidden" value="' + getMultipleOdds(r + 1, t) + '" /><span class="ToWin"><\/span><\/td>';
        n += '<td><input type="text" class="input stake stake' + r + '" placeholder="0" /><\/td>';
        n += "<\/tr>"
    })), n
}
function prepareMultiple() {
    $(".multiple").html(createMultiple());
    $(".betlist .stake").unbind("keyup").keyup(function() {
        var n, t;
        $(".alertbox").addClass("hidden");
        n = $(this).val();
		n = n.replace(/,/g, '');
		if(n >= 1000001){
			n = 1000000;
			$(this).val('1000000'.replace(/\B(?=(?:\d{3})+(?!\d))/g, ","));
			return;
		}
        n.indexOf(".") >= 0 && n.length - n.indexOf(".") > 3 && $(this).val(parseFloat(Math.round(n * 100)) / 100);
        t = parseFloat(n) * parseFloat($(this).parent().prev().find("span.odd span").text()), $(this).next().find(".ToWin").html(en_cur + t.toFixed(0).replace(/\B(?=(?:\d{3})+(?!\d))/g, ",")), $(".multiple .stake0").val(""), $(".multiple .stake0").parent().prev().find(".ToWin").html("");
		$(this).val(n.replace(/\B(?=(?:\d{3})+(?!\d))/g, ","));
        calcTotal();
        updateWin();
    });
    $(".multiple .stake").unbind("keyup").keyup(function() {
        var n, t;
        $(".alertbox").addClass("hidden");
        n = $(this).val();
		n = n.replace(/,/g, '');
		var max_bet = $(this).hasClass("stake0") ? 1000000 : 5000000;
		if(n >= (max_bet + 1)){
			n = max_bet;
			$(this).val(max_bet.toString().replace(/\B(?=(?:\d{3})+(?!\d))/g, ","));
			return;
		}
        n.indexOf(".") >= 0 && n.length - n.indexOf(".") > 3 && $(this).val(parseFloat(Math.round(n * 100)) / 100);
        n === "NaN" && $(this).val('');
        $(this).hasClass("stake0") && ($(".betlist .stake").val(n.toString().replace(/\B(?=(?:\d{3})+(?!\d))/g, ",")), $(".betlist .stake").each(function() {
			var odd = $(this).parent().prev().find("span.odd span").text();
			odd = odd >= 100.01 ? 100 : odd;
            var n = parseFloat(n * odd);
            $(this).next().find(".ToWin").html(en_cur + n.toFixed(0).replace(/\B(?=(?:\d{3})+(?!\d))/g, ","))
        }));
		var total_odd = parseFloat($(this).parent().prev().find(".mulOdd").val());
		total_odd = total_odd >= 100.01 ? 100 : total_odd;
        t = parseFloat(n) * total_odd;
        $(this).parent().prev().find(".ToWin").html(en_cur + t.toFixed(0).replace(/\B(?=(?:\d{3})+(?!\d))/g, ","));
		$(this).val(n.replace(/\B(?=(?:\d{3})+(?!\d))/g, ","));
        calcTotal();
        updateWin();
    })
}

function updateMultiple() {
    if ($(".selectedodds div.betlist ul").not(".suspended").length > 1 && $(".selectedodds div.betlist ul").not(".suspended").length < 9) {
		$('#error_for_mix_form').slideUp(500);
        var n = [];
        $(".selectedodds div.betlist ul").not(".suspended").find(".odd").each(function() {
            n.push($.trim($(this).text().replace(/,/g, '')))
        });
        $("div.bettotal table.multiple tr").each(function(t) {
            var i = getMultipleOdds(t + 1, n),
                    u = $(this).find("input.stake").val().replace(/,/g, ''),
                    r;
            $(this).find(".mulOdd").val(i);
			i = i >= 100.01 ? 100 : i;
            r = i * u;
            $(this).find(".ToWin").text(en_cur + r.toFixed(0).replace(/\B(?=(?:\d{3})+(?!\d))/g, ","))
        })
    }else{
		if($(".selectedodds div.betlist ul").not(".suspended").length && $(".selectedodds div.betlist ul").not(".suspended").length >= 9){
			$('#error_for_mix_form').slideDown(500);
		}
	}
}

function getMultipleOdds(i, odds) {
    switch (i) {
        case 1:
            return calcSingles(odds);
        case 2:
            return calcDoubles(odds);
        case 3:
            return calcTrebles(odds);
		default:
			return eval('calc' + i + 'Folds(odds)');
    }
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
            return '&#1578;&#1705;&#1740; &#1607;&#1575;';
        case 2:
            return '&#1583;&#1608;&#1578;&#1575;&#1740;&#1740; &#1607;&#1575;';
        case 3:
            return '&#1587;&#1607; &#1578;&#1575;&#1740;&#1740; &#1607;&#1575;';
		default:
			return n + ' &#1578;&#1575;&#1740;&#1740; &#1607;&#1575;';
    }
}

function updateWin() {
    $(".betlist ul.bet").each(function() {
        var n = parseFloat($(this).find("span.odd span").text()),
				n = n >= 100.01 ? 100 : n,
                t = parseFloat($(this).find("input.stake").val().replace(/,/g, '')),
                i = n * t;
        $(this).find("span.ToWin").html(en_cur + i.toFixed(0).replace(/\B(?=(?:\d{3})+(?!\d))/g, ","))
    });
    updateMultiple();
    calcTotal()
}

function calcTotal() {
    var n, t;
    updateMultiple();
    $(".ToWin").each(function() {
        $(this).html() == en_cur + "NaN" && $(this).html(en_cur + "0")
    });
    n = 0;
    $(".betslip .bet").not(".suspended").find(".stake").each(function() {
        $.isNumeric(parseFloat($(this).val().replace(/,/g, ''))) && (n += parseFloat($(this).val().replace(/,/g, '')))
    });
    $(".multiple .stake").each(function() {
        $.isNumeric($(this).val().replace(/,/g, '')) && !$(this).hasClass("stake0") && (n += parseFloat($(this).val().replace(/,/g, '')) * parseInt($(this).parent().prev().prev().find("span").text().replace("x", "")))
    });
    n > 0 ? $(".placebet").not(".in-progress").removeClass("disabled") : $(".placebet").addClass("disabled");
    $(".totalstake").html(en_cur + n.toFixed(0).replace(/\B(?=(?:\d{3})+(?!\d))/g, ","));
    t = 0;
    $(".betslip .bet").not(".suspended").find(".ToWin").each(function() {
        !$(this).hasClass("ignore") && (t += parseInt($(this).text().replace(/,/g, "").replace(en_cur, "")))
    });
    $(".bettotal").find(".ToWin").each(function() {
        !$(this).hasClass("ignore") && (t += parseInt($(this).text().replace(/,/g, "").replace(en_cur, "")));
    });
    $(".totalwin").html(en_cur + t.toFixed(0).replace(/\B(?=(?:\d{3})+(?!\d))/g, ","))
}

function prepareButtonEvents() {
    $(".has-tip").frosty();
    $(".inplaybtn").unbind("click").click(function() {
        var s, f, e, o;
        if ($(".betlist ul.bet").length < 21 && ($(".alertbox").addClass("hidden"), !$(this).hasClass("selected") && !$(this).hasClass("passive-ma"))) {
            $(".nobet").hide();
            $(".bettotal").show();
            s = new Date;
            $(this).addClass("selected");
            var n = $(this).closest(".odddetails"),
			t = n.data("eventid"),
			i = n.data("marketid"),
			h = $(this).data("runnerid"),
			c = $(this).data("pick"),
			l = $(this).data("points");
            r = !$('input.host').length ? $(this).parent().parent().parent().find('.host').text() : $('input.host').val(),
            u = !$('input.guest').length ? $(this).parent().parent().parent().find('.guest').text() : $('input.guest').val(),
			t = (t == '' || t == 'undefined' || t == undefined || t == 'null' || t == null) ? $(this).data('eventid') : t,
			i = (i == '' || i == 'undefined' || i == undefined || i == 'null' || i == null) ? $(this).data('marketid') : i,
			f = !$(this).parent().parent().children(':first').hasClass('inplayheader') ? '&#1606;&#1578;&#1740;&#1580;&#1607; &#1605;&#1587;&#1575;&#1576;&#1602;&#1607;' : $(this).parent().parent().children(':first').children(':first').text(),
            e = '<b class="team1 ellipsis ellipsis2">' + r + '<\/b><br /><b class="team2 ellipsis ellipsis2">' + u + "<\/b>";
            o = $(this).find('.odd-rate').find('span').text() == '' ? $(this).find('span').text() : $(this).find('.odd-rate').find('span').text();
            $(".selectedodds div.betlist").append(createBet(t, e, o, c, f, i, h, l));
            saveBet2Cookie();
            renewDeleteEvent();
            prepareMultiple();
			updateMultiple();
        }
    })
}
function renewDeleteEvent() {
    $("ul.bet .delete").unbind("click").click(function() {
        var n, i, r, t, u;
        $(".alertbox").addClass("hidden");
        n = $(this).parent().parent();
        i = $.cookie("betslip");
        typeof i != "undefined" && (r = JSON.parse(i), findAndRemove(r, "runnerid", n.data("runnerid")), t = new Date, u = 30, t.setTime(t.getTime() + u * 6e4), $.cookie("betslip", JSON.stringify(r), {
            expires: t,
            path: "/"
        }));
        $(this).parent().parent().slideUp("slow", function() {
            var i = n.data("eventid"),
                    r = n.data("marketid"),
                    t = n.data("runnerid");
            $('[data-runnerid="' + t + '"]').removeClass("selected");
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
function secondsTimeSpanToMS(n, t) {
    if (t == 3)
        return '&#1608;&#1602;&#1578; &#1575;&#1590;&#1575;&#1601;&#1607;';
    if (t == 100)
        return '&#1576;&#1575;&#1586;&#1740; &#1578;&#1605;&#1575;&#1605; &#1588;&#1583;&#1607; &#1575;&#1587;&#1578;';
    if (t == 4)
        return '&#1576;&#1575;&#1586;&#1740; &#1588;&#1585;&#1608;&#1593; &#1606;&#1588;&#1583;&#1607; &#1575;&#1587;&#1578;';
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
		if($(this).val() != ''){
			if ($(this).val() == "HT") {
				$(this).prev().find('small').text(toFarsi(secondsTimeSpanToMS(n, 3)))
			} else if ($(this).val() == "94" || $(this).val() == "94:00") {
				$(this).prev().find('small').text(toFarsi(secondsTimeSpanToMS(n, 100)))
			} else if ($(this).val() == "NS") {
				$(this).prev().find('small').text(toFarsi(secondsTimeSpanToMS(n, 4)))
			} else {
				var secss = $(this).prev().find('small').text();
				var n = MStoSeconds(secss) + 1;
				if(isNaN(n)) {
					n = MStoSeconds($(this).val()) + 1;
				}
				n = n > 5640 ? 5640 : n;
				$(this).val(secondsTimeSpanToMS(n, 0));
				$(this).prev().find('small').text(toFarsi(secondsTimeSpanToMS(n, 0)))
			}
        }
    })
}
var en_cur = "";
var fa_cur = "";
var lang = "fa";
$(document).ready(function() {
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
        $(".odd-link").removeClass("selected");
        $("[data-runnerid]").removeClass("selected");
        calcTotal();
		updateMultiple();
        $(".nobet").show();
        $(".bettotal").hide();
        $(".placebet").removeClass("disabled in-progress").prop("disabled", !1)
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
		console.log(myItem.length);
        var jsonObject = {data: []};
        if (myItem.length == 1) {
            jsonObject.data.push({
                "match_id": myItem.data("eventid"),
                "runner_id": myItem.data("runnerid"),
                "pick": myItem.data("pick"),
                "stake": myItem.find("input.stake").val(),
                "odd": myItem.find("span.odd span").text(),
            });
            mix = '&#1578;&#1705;&#1740; &#1607;&#1575;-x1-' + myItem.find("input.stake").val();
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
			n = JSON.parse(n);
            $(".alertbox").removeClass("notice").removeClass("errorbox").removeClass("warning").removeClass("success");
            switch (n.result) {
                case "Login":
                    $(".alertbox").html((lang == "fa" ? "&#1576;&#1585;&#1575;&#1740; &#1579;&#1576;&#1578; &#1601;&#1585;&#1605; &#1576;&#1575;&#1740;&#1583; &#1575;&#1576;&#1578;&#1583;&#1575; &#1576;&#1575; &#1705;&#1575;&#1585;&#1576;&#1585;&#1740; &#1582;&#1608;&#1583; &#1608;&#1575;&#1585;&#1583; &#1587;&#1575;&#1740;&#1578; &#1588;&#1608;&#1740;&#1583;." : "You need to be logged in in order to place a bet.") + '<br /><br /><a class="Login" href="javascript:void(0)">' + (lang == "fa" ? "&#1608;&#1585;&#1608;&#1583;" : "Login") + "<\/a>").addClass("notice").removeClass("hidden");
                    $(".Login").unbind("click").click(function() {
                        window.location = base_url + "/users/login"
                    });
                    break;
                case "MinBet":
                    $(".alertbox").html(lang == "fa" ? "&#1581;&#1583;&#1575;&#1602;&#1604; &#1605;&#1576;&#1604;&#1594; &#1662;&#1740;&#1588; &#1576;&#1740;&#1606;&#1740; &#1777;&#1776;&#1776;&#1776; &#1578;&#1608;&#1605;&#1575;&#1606; &#1575;&#1587;&#1578;." : "The minimum bet is 1000 Tomans.").addClass("errorbox").removeClass("hidden");
                    break;
                case "MaxBet":
                    $(".alertbox").html(lang == "fa" ? "&#1581;&#1583;&#1575;&#1705;&#1579;&#1585; &#1605;&#1576;&#1604;&#1594; &#1662;&#1740;&#1588; &#1576;&#1740;&#1606;&#1740; &#1781;&#1605;&#1740;&#1604;&#1740;&#1608;&#1606; &#1578;&#1608;&#1605;&#1575;&#1606; &#1575;&#1587;&#1578;." : "The Maximum bet is 5000000 Tomans.").addClass("errorbox").removeClass("hidden");
                    break;
                case "MatchFuckedUp":
                    $(".alertbox").html(lang == "fa" ? "&#1583;&#1585; &#1579;&#1576;&#1578; &#1576;&#1575;&#1586;&#1740; &#1582;&#1591;&#1575;&#1740;&#1740; &#1585;&#1582; &#1583;&#1575;&#1583;." : "Error on insert bet").addClass("errorbox").removeClass("hidden");
                    $(".placebet").prop("disabled", !1);
                    $(".placebet").removeClass("disabled in-progress")
                    break;
                case "OddChanged":
                    $(".alertbox").html(lang == "fa" ? "&#1590;&#1585;&#1575;&#1740;&#1576; &#1662;&#1740;&#1588; &#1576;&#1740;&#1606;&#1740; &#1578;&#1594;&#1740;&#1740;&#1585; &#1705;&#1585;&#1583;&#1607; &#1575;&#1587;&#1578;. &#1605;&#1580;&#1583;&#1583; &#1578;&#1604;&#1575;&#1588; &#1705;&#1606;&#1740;&#1583;." : "The selected odds have been changed. Please try again.").addClass("errorbox").removeClass("hidden");
                    break;
                case "Suspend":
                    $(".alertbox").html(lang == "fa" ? "&#1576;&#1575;&#1586;&#1740; &#1594;&#1740;&#1585;&#1601;&#1593;&#1575;&#1604; &#1575;&#1587;&#1578;. &#1605;&#1580;&#1583;&#1583; &#1578;&#1604;&#1575;&#1588; &#1705;&#1606;&#1740;&#1583;." : "The selected odds have been suspended. Please try again.").addClass("errorbox").removeClass("hidden");
                    break;
                case "Expired":
                    $(".alertbox").html(lang == "fa" ? "&#1605;&#1607;&#1604;&#1578; &#1662;&#1740;&#1588; &#1576;&#1740;&#1606;&#1740; &#1576;&#1585;&#1575;&#1740; &#1605;&#1587;&#1575;&#1576;&#1602;&#1575;&#1578; &#1575;&#1606;&#1578;&#1582;&#1575;&#1576; &#1588;&#1583;&#1607; &#1578;&#1605;&#1575;&#1605; &#1588;&#1583;&#1607; &#1575;&#1587;&#1578;." : "This bet slip has been timed out.").addClass("errorbox").removeClass("hidden");
                    break;
                case "Expired2":
                    $(".alertbox").html(lang == "fa" ? "&#1605;&#1588;&#1705;&#1604;&#1740; &#1585;&#1582; &#1583;&#1575;&#1583;&#1607; &#1575;&#1587;&#1578;. &#1581;&#1580;&#1605; &#1576;&#1575;&#1604;&#1575;&#1740; &#1593;&#1605;&#1604;&#1740;&#1575;&#1578;." : "This bet slip has been timed out.").addClass("errorbox").removeClass("hidden");
                    break;
                case "LowBalance":
                    $(".alertbox").html((lang == "fa" ? "&#1605;&#1608;&#1580;&#1608;&#1583;&#1740; &#1581;&#1587;&#1575;&#1576; &#1588;&#1605;&#1575; &#1576;&#1585;&#1575;&#1740; &#1575;&#1740;&#1606; &#1662;&#1740;&#1588; &#1576;&#1740;&#1606;&#1740; &#1705;&#1575;&#1601;&#1740; &#1606;&#1740;&#1587;&#1578;. &#1575;&#1576;&#1578;&#1583;&#1575; &#1605;&#1608;&#1580;&#1608;&#1583;&#1740; &#1581;&#1587;&#1575;&#1576; &#1582;&#1608;&#1583; &#1585;&#1575; &#1575;&#1601;&#1586;&#1575;&#1740;&#1588; &#1583;&#1607;&#1740;&#1583; &#1608; &#1583;&#1608;&#1576;&#1575;&#1585;&#1607; &#1587;&#1593;&#1740; &#1705;&#1606;&#1740;&#1583;." : "You don't have sufficient funds in your account to place this bet. Deposit your account and try again.") + '<br /><br /><button class="closeAlert" href="javascript:void(0)">' + (lang == "fa" ? "&#1576;&#1587;&#1578;&#1606;" : "Close") + '<\/button><button class="gototopup" href="javascript:void(0)">' + (lang == "fa" ? "&#1575;&#1601;&#1586;&#1575;&#1740;&#1588; &#1605;&#1608;&#1580;&#1608;&#1583;&#1740;" : "Deposit") + "<\/button>").addClass("warning").removeClass("hidden");
                    $(".closeAlert").unbind("click").click(function() {
                        $(this).parent().addClass("hidden")
                    });
                    $(".gototopup").unbind("click").click(function() {
                        window.location = base_url + "payment/credit"
                    });
                    break;
                case "Error":
                case "Invalid":
                    $(".alertbox").html((lang == "fa" ? "&#1605;&#1578;&#1575;&#1587;&#1601;&#1575;&#1606;&#1607; &#1582;&#1591;&#1575;&#1740;&#1740; &#1583;&#1585; &#1607;&#1606;&#1711;&#1575;&#1605; &#1579;&#1576;&#1578; &#1601;&#1585;&#1605; &#1585;&#1582; &#1583;&#1575;&#1583;&#1607; &#1575;&#1587;&#1578;." : "Oops! Something went wrong.") + '<br /><button class="closeAlert" href="javascript:void(0)">' + (lang == "fa" ? "&#1587;&#1593;&#1740; &#1583;&#1608;&#1576;&#1575;&#1585;&#1607;" : "Try again") + "<\/button>").addClass("errorbox").removeClass("hidden");
                    $(".closeAlert").unbind("click").click(function() {
                        $(this).parent().addClass("hidden");
                        $(this).prop("disabled", !1).removeClass("disabled in-progress");
                    });
                    break;
                default:
                case "Success":
                    $(".deleteall").trigger("click");
                    $(".alertbox").html((lang == "fa" ? "&#1601;&#1585;&#1605; &#1588;&#1605;&#1575; &#1576;&#1575; &#1605;&#1608;&#1601;&#1602;&#1740;&#1578; &#1579;&#1576;&#1578; &#1588;&#1583;." : "Your bet has been placed successfully.") + '<br /><br /><button class="closeAlert" href="javascript:void(0)">' + (lang == "fa" ? "&#1576;&#1587;&#1578;&#1606;" : "Close") + '</button>').addClass("success").removeClass("hidden");
                    $(".closeAlert").unbind("click").click(function() {
                        $(this).parent().addClass("hidden")
                    });
                    $('.user-balance-place').html(n.new_cash);
                    $(".gotomybets").unbind("click").click(function() {
                        window.location = base_url + "bets/myrecords";
                    })
            }
            $(".placebet").prop("disabled", !1);
            $(".placebet").removeClass("disabled in-progress")
        }).fail(function(xhr, status, error) {
            $(".alertbox").html((lang == "fa" ? "&#1605;&#1578;&#1575;&#1587;&#1601;&#1575;&#1606;&#1607; &#1582;&#1591;&#1575;&#1740;&#1740; &#1583;&#1585; &#1607;&#1606;&#1711;&#1575;&#1605; &#1579;&#1576;&#1578; &#1601;&#1585;&#1605; &#1585;&#1582; &#1583;&#1575;&#1583;&#1607; &#1575;&#1587;&#1578;. &#1583;&#1608;&#1576;&#1575;&#1585;&#1607; &#1578;&#1604;&#1575;&#1588; &#1705;&#1606;&#1740;&#1583;." : "Oops! Something went wrong.") + '<br /><button class="closeAlert" href="javascript:void(0)">' + (lang == "fa" ? "&#1587;&#1593;&#1740; &#1583;&#1608;&#1576;&#1575;&#1585;&#1607;" : "Try again") + "<\/button>").addClass("errorbox").removeClass("hidden");
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
	loadBetslipCookie();
    $('#upcoming-select-id').on('change', function() {
        window.location.replace($(this).val());
    });
	if(jQuery('#search-box').length){
		jQuery('#search-box').on('keyup change', function(){
			var $value_search = jQuery(this).val().trim().toString().toLowerCase();
			if($value_search == '' || $value_search == null || $value_search == 'null' || $value_search == undefined || $value_search == 'undefined'){
				jQuery('.event-row-search').show();
				jQuery('.event-row-parent-search').show();
				return false;
			}else{
				jQuery('.event-row-search').each(function(){
					if($(this).text().trim().toString().toLowerCase().indexOf($value_search) == -1){
						$(this).hide();
					}else{
						$(this).show();
					}
				});
				jQuery('.event-row-parent-search').each(function(){
					var $disable_this_element = 0;
					jQuery(this).find('.event-row-search').each(function(){
						if(jQuery(this).is(':visible') || jQuery(this).css('display') == '' || jQuery(this).css('display') == 'inline' || jQuery(this).css('display') == 'block' || jQuery(this).css('display') == 'inline-block'){
							$disable_this_element++;
						}
					});
					if($disable_this_element >= 1){
						jQuery(this).show();
					}else{
						jQuery(this).hide();
					}
				});
				var $show_no_matches = 0;
				jQuery('.event-row-search').each(function(){
					if(jQuery(this).is(':visible')){
						$show_no_matches++;
					}
				});
				if($show_no_matches >= 1){
					jQuery('.no-matches-found-for-search').hide();
				}else{
					jQuery('.no-matches-found-for-search').show();
				}
				return true;
			}
		});
	}
})