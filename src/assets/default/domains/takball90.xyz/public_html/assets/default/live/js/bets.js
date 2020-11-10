    var stinl;
    var UpdateBetSlip_Req;
    var UpdateLive;

    // start set time out
    function startTimer() {

    stinl = setInterval(function() {
        update_dynamic();
    }, 12000);

    }
    // end start set time out

    // stop set time out
    function stopTimer() {

    window.clearInterval(stinl); //pause

    }
    // end stop set time out

    // add .00 function
    (function($) {
      $.fn.currencyFormat = function() {
        this.each(function(i) {
            $(this).change(function(e) {
                if (isNaN(parseFloat(this.value))) return;
                this.value = parseFloat(this.value).toFixed(2);
            });
        });
        return this; //for chaining
      }
   })(jQuery);
   // end add .00 function

   // drop down odds calc
   function up_down(select, odd) {
       
    res = null;

    get_text = $.trim($(document).find(select).text());

    if (get_text) {

        if (odd > get_text) {
            res = "up-m";
        }

        if (odd < get_text) {
            res = "down-m";
        }

    }
    
    return res;

    }
    // end drop down odds calc
    
    
    // get user balance
    function GetUserBalance() {

            $.ajax(match_options("url") + "profile/Balance", {
                type: "GET",
                dataType: "html",
                success: function(data) {

                    $(".user_balance").text(data);

                }
            });
       
    }
    // end get user balance
    

    //clear dot space and underline
    function clear(string) {

      string = string.replace(/\s+/g, '-').toLowerCase();
      string = string.toString().replace(/\./g, '-');
      string = string.toString().replace(/\_/g, '-');
      return string;

    }
    // end clear dot space and underline
    

    // sports menu add active class
    $(document).on("click", '.sports_menu', function() {

       $(this).toggleClass("active", 2000);

    });
    // end sports menu add active class


    // check sports menu open or close
    function check_menu(id) {

        res = null;
        if ($($.trim(id)).hasClass("active")) {
            res = "active";
        }

        return res;


    }
    // end check sports menu open or close

    // live category menu
    function live_category(data) {

        $(".total-count-live").text(data.COUNT.ALL);

        L = '';
        LL = '';

        $.each(data.SCL, function(key, row) {

            var ctegory = match_options(key).split('|');


			L += '<a href="' + match_options("url") + 'sports/livecategory/' + key + '" title="' + key + '" class="' + key.replace(/\_/g, '') + '">' + ctegory[1] + '</a>';
			LL += '<a href="' + match_options("url") + 'sports/livecategory/' + key + '" title="' + key + '"><span class="sport-icon-m ' + key.replace(/\_/g, '') + '"><i class="games-count-view-m">' + data.COUNT[key] + '</i></span><p>' + ctegory[1] + '</p></a>';


        });

        $('#live_category').html(L);
        $('#live_category2').html(LL);

    }
    // end live category menu

    //update betslip
    function update_dynamic() {

        var count = $("#count_betslip").text();

        if (count == "0") {
            return false;
        }


        bets = localStorage.getItem('bets');

        UpdateBetSlip_Req = jQuery.ajax(match_options("url") + "sports/UpdateBetSlip", {
            type: "POST",
            dataType: "json",
            data: {
                events: bets
            },
            headers: {
                Token: match_options("ajax_token")
            },
            success: function(data) {


                localStorage.setItem("bets", JSON.stringify(data));
                showEventsBetSlip();

            }
        });

    }
    // end update betslip



    //save data to local storage
    function SavebetSlip(data) {
        if (localStorage.getItem('bets')) {
            local = JSON.parse(localStorage.getItem('bets'));
            local.push(data);
        } else {
            local = [data];
        }

        localStorage.setItem("bets", JSON.stringify(local));
        ///console.log(local);
        return local;
    }
    //end save data to local storage



    //update data to local storage
    function updatebetSlip(key, data) {
        if (localStorage.getItem('bets')) {
            var updatebetslip = JSON.parse(localStorage.getItem('bets'));
            updatebetslip[key] = data;
            localStorage.setItem("bets", JSON.stringify(updatebetslip));
        }
    }
    //end update data to local storage




    //set selected odds
    function setslectedODDS() {

        if (localStorage.getItem('bets')) {
            var odds = JSON.parse(localStorage.getItem('bets'));

            $.each(odds, function(key, row) {

                $("div[event_id='" + row.event_id + "'][type='" + row.type + "'][row='" + row.row + "']").addClass("selected");


            });

        }

    }
    //end set selected odds



    //count events in betslip
    function CountbetSlip() {

        if (localStorage.getItem('bets')) {
            var countbetslip = $(JSON.parse(localStorage.getItem('bets'))).length;
            if (countbetslip == "0") {
                window.localStorage.removeItem('betslip_type');
                $("#bet_amount").val("");

                $('.main-wrapper').removeClass('has-bet-slip');
                $('.betslip-balance-view-m').addClass('hidden');

            } else {

                $('.main-wrapper').addClass('has-bet-slip');
                $('.betslip-balance-view-m').removeClass('hidden');

            }
            $(".count_betslip").text(countbetslip);
            $("#count_betslip").text(countbetslip)

        } else {

            $(".count_betslip").text(0);
            $("#count_betslip").text(0);


            $('.main-wrapper').removeClass('has-bet-slip');
            $('.betslip-balance-view-m').addClass('hidden');

        }
    }
    //end count events in betslip



    //betslip amount 
    function betamount() {

        amount = $("#bet_amount").val();
        odd = $("#total_rate").text();
        cul = amount * odd;
        if (match_options("currency") !== "IRT") {
            $("#amount_win").text(Number(cul).toFixed(2));
        } else {
            $("#amount_win").text(Math.round(cul));
        }

    }
    // end betslip amount 


    //check single or multi betslip type
    function singleORmulti() {

        if (localStorage.getItem('betslip_type') && localStorage.getItem('betslip_type') == "single_bet" || localStorage.getItem('betslip_type') == "multi_bet") {

            if (localStorage.getItem('betslip_type') == "single_bet") {
                $("#multi").removeClass("active");
                $("#single").addClass("active");
                $("#bet_amount").prop({
                    disabled: true
                });
            }

            if (localStorage.getItem('betslip_type') == "multi_bet") {
                $("#single").removeClass("active");
                $("#multi").addClass("active");
                $("#bet_amount").prop({
                    disabled: false
                });
            }




        } else {

            $("#bet_amount").prop({
                disabled: false
            });
            bet_slip_number = $("#count_betslip").text();
            if (bet_slip_number == "1") {
                localStorage.setItem("betslip_type", "single");
            }
            if (bet_slip_number > "1") {
                localStorage.setItem("betslip_type", "multi");
            }
            if (bet_slip_number == "0") {
                window.localStorage.removeItem('betslip_type');
            }
            if (localStorage.getItem('betslip_type') && localStorage.getItem('betslip_type') == "single") {
                $("#multi").removeClass("active");
                $("#single").addClass("active");
            }
            if (localStorage.getItem('betslip_type') && localStorage.getItem('betslip_type') == "multi") {
                $("#single").removeClass("active");
                $("#multi").addClass("active");
            }


        }


        if (!localStorage.getItem('betslip_type')) {
            $("#multi").removeClass("active");
            $("#single").addClass("active");
        }


    }
    //end check single or multi betslip type



//show events in betslip
//show events in betslip
function showEventsBetSlip() {
    if (localStorage.getItem('bets')) {
        var betslipEvents = JSON.parse(localStorage.getItem('bets'));

        $('.coupon-matches').empty();
        var total_odd = [];
        var total_oddamount = [];
        $.each(betslipEvents, function(key, row) {

            var is_live = row.is_bet.split('|');
            var select = row.select.split('|');
            var select_dec = select['1'].split(' ');
            var select_dec1 = match_options(select_dec['0']);

            if (select_dec['1']) {
                var select_dec2 = " " + select_dec['1'];
            } else {
                var select_dec2 = "";
            }

            if (row.amount) {
                amount = row.amount;
            } else {
                amount = "";
            }

            o = '<div class="single-event-contain-m coupon-match">';
            o += '<div class="team-name-view-b-m">';
            if (row.odd == "0" || !row.odd) {
            o += '<div class="icon-status-view-m event-deleted "></div>';    
            }
            o += '<a href="javascript:;">' + row.event_name + '</a><a href="javascript:;" class="event-remove-b-m remove-bet" key="' + key + '" event_id="' + row.event_id + '"></a>';
            o += '</div>';
            o += '<div class="market-full-info-m">';
            o += '<ul><li>';
            o += '<p class="bet-pick-name-m">' + match_options(select['0']) + ' : (' + select_dec1 + select_dec2 + ')</p>';
            o += '</li><li>';
            if (row.odd == "0" || !row.odd) {
            o += '<span class="change-price-m">' + row.odd + '</span>';
            } else {
            o += '<span class="price-view-m">' + row.odd + '</span>';    
            }
            o += '</li></ul></div>';
            if (localStorage.getItem('betslip_type') && localStorage.getItem('betslip_type') == "single_bet") {
                o += '<div class="single-bet-m">';
                o += '<div class="stake-form-b-m">';
                o += '<ul><li><div class="mini-table-b-m"><ul><li><div class="single-form-item">';
                o += '<input placeholder="' + match_options("single_amount") + '" name="single-' + key + '" row="' + key + '" class="bet-amount" value="' + amount + '" type="text">';
                o += '</div></li></ul></div></li></ul></div></div>';
            }
            o += '</div>';

            $('.coupon-matches').append(o);

            if (row.odd && row.odd !== "" && row.odd !== "0") {
                total_odd.push(row.odd);
                total_oddamount.push(amount);
            }

        });

        if (localStorage.getItem('betslip_type') == "single_bet") {

            sum = 0;
            $.each(total_oddamount, function() {
                sum += parseFloat(this) || 0;
            });



            if (match_options("currency") !== "IRT") {

                $("#bet_amount").val(parseFloat(sum).toFixed(2));

            } else {

                $("#bet_amount").val(Math.round(sum));

            }
        }


        if (total_odd.length > 0) {
            $("#total_rate").text(total_odd.reduce(function(a, b) {
                return (a * b).toFixed(2);
            }));
        } else {

            $("#total_rate").text((0).toFixed(2));
        }

        betamount();
        singleORmulti();
    } else {
        $('.coupon-matches').empty();
    }
}
// end show events in betslip



// betslip
$(document).ready(function() {

    // add bet
    $(document).on("click", ".ADDBET", function(e) {


        if ($(this).hasClass("selected")) {
            return false;
        }
        
        if (UpdateBetSlip_Req) {
            
        UpdateBetSlip_Req.abort();
        
        }
        
        if (UpdateLive) {
            
        UpdateLive.abort();
        
        }

        stopTimer();

        type = $(this).attr('type');
        event_id = $(this).attr('event_id');
        row = $(this).attr('row');
        data_odd = $(this).attr('data');

        updatebet = false;
        if (localStorage.getItem('bets')) {
            var countbetslip = $(JSON.parse(localStorage.getItem('bets')));
            var countbetslip_num = $(JSON.parse(localStorage.getItem('bets'))).length;

            if (countbetslip_num > match_options("HIGHEST_TICKET") - 1) {
                swal({
                    title: match_options("error"),
                    text: match_options("highest_ticket_error"),
                    type: 'error',
                    showConfirmButton: false,
                });

                return false;
            }


            $.each(countbetslip, function(k, event) {
                if (event.event_id == event_id) {
                    updatebet = true;
                    updatekey = k;
                    if ($("div[event_id='" + event_id + "']").hasClass("selected")) {
                        $("div[event_id='" + event_id + "']").removeClass("selected");
                    }
                }
            });

        }

        if ($(this).hasClass("selected")) {
            return false;
        }
        $(this).addClass("selected");

        $.ajax(match_options("url") + "sports/AddBet", {
            type: "POST",
            dataType: "json",
            data: {
                type: type,
                id: event_id,
                row: row,
                data: data_odd
            },
            headers: {
                Token: match_options("ajax_token")
            },
            success: function(data) {
                if (updatebet) {
                    updatebetSlip(updatekey, data);
                } else {
                    SavebetSlip(data);
                }
                CountbetSlip();
                betamount();
                singleORmulti();
                showEventsBetSlip();

            },
            error: function() {

                swal({
                    title: match_options("error"),
                    text: match_options("add_error"),
                    type: 'error',
                    showConfirmButton: false,
                });
            }
        });

        startTimer();


    });
    // end add bet


    // clear bets in betslip
    $(".clear-bets").click(function() {

        if (UpdateBetSlip_Req) {
            
        UpdateBetSlip_Req.abort();
        
        }

        stopTimer();
        window.localStorage.removeItem('bets');
        window.localStorage.removeItem('betslip_type');
        $("#total_rate").text((0).toFixed(2));
        $("#amount_win").text((0).toFixed(2));
        $("#bet_amount").val("");
        $(".ADDBET").removeClass("selected");
        CountbetSlip();
        singleORmulti();
        showEventsBetSlip();
        startTimer();
    });
    // end clear bets in betslip


    // remove event on betslip
    $(document).on("click", ".remove-bet", function(e) {

        if (UpdateBetSlip_Req) {
            
        UpdateBetSlip_Req.abort();
        
        }

        stopTimer();

        key = $(this).attr('key');
        event_id = $(this).attr('event_id');

        var updatebetslip = JSON.parse(localStorage.getItem('bets'));
        updatebetslip.splice(key, 1);
        localStorage.setItem("bets", JSON.stringify(updatebetslip));

        if ($("div[event_id='" + event_id + "']").hasClass("selected")) {
            $("div[event_id='" + event_id + "']").removeClass("selected");
        }


        CountbetSlip();
        showEventsBetSlip();
        singleORmulti();

        startTimer();

    });
    // end remove event on betslip



    // keyup bet amonut single mode calc
    $(document).on("keyup", ".bet-amount", function(e) {
        
        while (!/^(([0-9]+)((\.|,)([0-9]{0,2}))?)?$/.test($(this).val())) {
            $(this).val($(this).val().slice(0, -1));
        }
        
        if (match_options("currency") !== "IRT") {
            $(this).currencyFormat();
        }
        
        roww = $(this).attr('row');
        
        if (match_options("currency") !== "IRT") {
            
            vall = $(this).val();
            var val = parseFloat(vall).toFixed(2);
            
        } else {
            
            val = $(this).val();
            
        }

        if (localStorage.getItem('bets')) {
            var updatebetslip = JSON.parse(localStorage.getItem('bets'));
            updatebetslip[roww]['amount'] = val;
            localStorage.setItem("bets", JSON.stringify(updatebetslip));
        }

        if (localStorage.getItem('bets')) {
            var betslipEvents = JSON.parse(localStorage.getItem('bets'));
            
            var total_oddamount = [];
            $.each(betslipEvents, function(key, row) {


                if (row.amount) {
                    amount = row.amount;
                } else {
                    amount = "0"
                }

                total_oddamount.push(amount);

            });

            if (localStorage.getItem('betslip_type') == "single_bet") {

                sum = 0;
                $.each(total_oddamount, function() {
                    sum += parseFloat(this) || 0;
                });


                if (match_options("currency") !== "IRT") {
                    $("#bet_amount").val(sum.toFixed(2));
                } else {
                    $("#bet_amount").val(sum);
                }

            }

        }

        betamount();

    });
    // end keyup bet amonut single mode calc


    // bet amount calc
    $("#bet_amount").keyup(function(e) {
        while (!/^(([0-9]+)((\.|,)([0-9]{0,2}))?)?$/.test($('#bet_amount').val())) {
            $('#bet_amount').val($('#bet_amount').val().slice(0, -1));
        }
        if (match_options("currency") !== "IRT") {
            $(this).currencyFormat();
        }
        betamount();
    });
    // end bet amount calc


    // if click single bet
    $("#single").click(function() {
        localStorage.setItem("betslip_type", "single_bet");
        showEventsBetSlip();
        setslectedODDS();
        singleORmulti();

    });
    // end if click single bet
    
    
    // if click multi bet
    $("#multi").click(function() {
        localStorage.setItem("betslip_type", "multi_bet");
        showEventsBetSlip();
        setslectedODDS();
        singleORmulti();
    });
    // end if click multi bet


    // place bet add to bet slip
    $(document).on("click", "#place_bet", function(e) {

        if (!localStorage.getItem('bets')) {
            swal({
                title: match_options("error"),
                text: match_options("count_error"),
                type: 'error',
                showConfirmButton: false,
            });
            return false;
        }

        var IS_JSON = true;
        try {
            var json = $.parseJSON(localStorage.getItem('bets'));
        } catch (err) {
            IS_JSON = false;
        }

        if (!IS_JSON) {
            swal({
                title: match_options("error"),
                text: match_options("add_error"),
                type: 'error',
                showConfirmButton: false,
            });
            return false;
        }
        var amount = $("#bet_amount").val();
        var countbetslip = $(JSON.parse(localStorage.getItem('bets'))).length;

        if (match_options("user_login") == "0") {
            swal({
                title: match_options("error"),
                text: match_options("user_login_error"),
                type: 'error',
                showConfirmButton: false,
            });
            return false;
        }


        if (countbetslip == "0") {
            swal({
                title: match_options("error"),
                text: match_options("count_error"),
                type: 'error',
                showConfirmButton: false,
            });
            return false;
        }

        if (localStorage.getItem('betslip_type') == "single_bet") {

            var odds = JSON.parse(localStorage.getItem('bets'));
            var check = true;
            $.each(odds, function(key, row) {

                if (!row.amount || row.amount == "") {

                    swal({
                        title: match_options("error"),
                        text: match_options("single_bet_error_submit") + " > " + row.event_name,
                        type: 'error',
                        showConfirmButton: false,
                    });
                    check = false;
                    return false;

                }

                if (row.amount) {
                    if (row.amount < match_options("LOWEST_STAKE") && match_options("LOWEST_STAKE") !== false) {
                        swal({
                            title: match_options("error"),
                            text: match_options("lowest_stake_error") + " > " + row.event_name,
                            type: 'error',
                            showConfirmButton: false,
                        });
                        check = false;
                        return false;

                    }

                    if (row.amount > match_options("HIGHEST_STAKE") && match_options("HIGHEST_STAKE") !== false) {
                        swal({
                            title: match_options("error"),
                            text: match_options("highest_stake_error") + " > " + row.event_name,
                            type: 'error',
                            showConfirmButton: false,
                        });
                        check = false;
                        return false;
                    }
                }

                if (!row.odd || row.odd == "" || row.odd == "0") {

                    swal({
                        title: match_options("error"),
                        text: match_options("remove_disabled_events_betslip") + " > " + row.event_name,
                        type: 'error',
                        showConfirmButton: false,
                    });
                    check = false;
                    return false;

                }

            });

            if (!check) {
                return false;
            }

        } else {

            if (amount < match_options("LOWEST_STAKE") && match_options("LOWEST_STAKE") !== false) {
                swal({
                    title: match_options("error"),
                    text: match_options("lowest_stake_error"),
                    type: 'error',
                    showConfirmButton: false,
                });
                return false;
            }


            if (amount > match_options("HIGHEST_STAKE") && match_options("HIGHEST_STAKE") !== false) {
                swal({
                    title: match_options("error"),
                    text: match_options("highest_stake_error"),
                    type: 'error',
                    showConfirmButton: false,
                });
                return false;
            }


        }


        if (localStorage.getItem('betslip_type') == "multi_bet" || localStorage.getItem('betslip_type') == "multi") {


            if (countbetslip < match_options("LOWEST_TICKET")) {
                swal({
                    title: match_options("error"),
                    text: match_options("lowest_ticket_error"),
                    type: 'error',
                    showConfirmButton: false,
                });
                return false;
            }


            if (countbetslip > match_options("HIGHEST_TICKET")) {
                swal({
                    title: match_options("error"),
                    text: match_options("highest_ticket_error"),
                    type: 'error',
                    showConfirmButton: false,
                });
                return false;
            }

        }

        $.ajax(match_options("url") + "sports/PlaceBet", {
            type: "POST",
            dataType: "html",
            data: {
                bets: localStorage.getItem('bets'),
                type: localStorage.getItem('betslip_type'),
                amount: amount
            },
            headers: {
                Token: match_options("ajax_token")
            },
            success: function(data) {

                if (data == 1) {
                    swal({
                        title: match_options("success"),
                        text: match_options("place_bet_suc"),
                        type: 'success',
                        showConfirmButton: false,
                    });
                    window.localStorage.removeItem('bets');
                    window.localStorage.removeItem('betslip_type');
                    $("#total_rate").text((0).toFixed(2));
                    $("#amount_win").text((0).toFixed(2));
                    $("#bet_amount").val("");
                    $(".ADDBET").removeClass("selected");
                    CountbetSlip();
                    singleORmulti();
                    showEventsBetSlip();
                    GetUserBalance();

                } else {
                    swal({
                        title: match_options("error"),
                        text: data,
                        type: 'error',
                        showConfirmButton: false,
                    });

                }

            },
            error: function() {


                swal({
                    title: match_options("error"),
                    text: match_options("add_error"),
                    type: 'error',
                    showConfirmButton: false,
                });

            }
        });

    });
    // end place bet add to bet slip
    

    // add .00 to input
    $("input[num='1']").keyup(function(e) {
        while (!/^(([0-9]+)((\.|,)([0-9]{0,2}))?)?$/.test($(this).val())) {
            $(this).val($(this).val().slice(0, -1));
        }
        if (match_options("currency") !== "IRT") {
            $(this).currencyFormat();
        }
    });
    // end add .00 to input

    CountbetSlip();
    showEventsBetSlip();
    setslectedODDS();
    singleORmulti();
    startTimer();


    // check if clicked single bet in betslip input
    $(document).on('focus', '.bet-amount', function() {
        stopTimer(); //pause
    });


    
    $(document).on('focusout', '.bet-amount', function() {

        startTimer();

    });
    // end check if clicked single bet in betslip input

   
    // set cookie in jquery
    function createCookie(name, value, days) {
        var expires;

        if (days) {
            var date = new Date();
            date.setTime(date.getTime() + (days * 24 * 60 * 60 * 1000));
            expires = "; expires=" + date.toGMTString();
        } else {
            expires = "";
        }
        document.cookie = encodeURIComponent(name) + "=" + encodeURIComponent(value) + expires + "; path=/";
    }
    // end set cookie in jquery

    // read cookie in jquery
    function readCookie(name) {
        var nameEQ = encodeURIComponent(name) + "=";
        var ca = document.cookie.split(';');
        for (var i = 0; i < ca.length; i++) {
            var c = ca[i];
            while (c.charAt(0) === ' ')
                c = c.substring(1, c.length);
            if (c.indexOf(nameEQ) === 0)
                return decodeURIComponent(c.substring(nameEQ.length, c.length));
        }
        return null;
    }
    // end read cookie in jquery
    
    
    // remove cookie in jquery
    function eraseCookie(name) {
        createCookie(name, "", -1);
    }
    // end remove cookie in jquery


    // time zone in jquery
    if (match_options("lang") !== "FA") {

        if (typeof(readCookie("TimeZone")) == "undefined" || readCookie("TimeZone") == null || readCookie("TimeZone") == "") {
            createCookie("TimeZone", moment.tz.guess(), "30");
        }

        if (typeof(readCookie("TimeZone")) != "undefined" && readCookie("TimeZone") !== null && readCookie("TimeZone") !== "") {
            moment.tz.setDefault(readCookie("TimeZone"));
            $('.moment_date_time').each(function() {


                var date_time = $(this).attr("time");
                var current = moment.tz(date_time, "UTC");
                var currentt = current.clone().tz(readCookie("TimeZone"));
                var dateString = currentt.format("YYYY-MM-DD HH:mm");
                $(this).text(dateString);

            });

            $('.moment_date').each(function() {


                var date_time = $(this).attr("time");
                var current = moment.tz(date_time, "UTC");
                var currentt = current.clone().tz(readCookie("TimeZone"));
                var dateString = currentt.format("YY-MM-DD");
                $(this).text(dateString);

            });

            $('.moment_time').each(function() {


                var date_time = $(this).attr("time");
                var current = moment.tz(date_time, "UTC");
                var currentt = current.clone().tz(readCookie("TimeZone"));
                var dateString = currentt.format("HH:mm");
                $(this).text(dateString);

            });

            setInterval(function() {
                var CurrentDate = moment().tz(readCookie("TimeZone")).format("HH:mm:ss (Z)");
                $(".clock").text(CurrentDate);
            }, 1000);
        } else {

            setInterval(function() {
                var CurrentDate = moment().format("HH:mm:ss (Z)");
                $(".clock").text(CurrentDate);
            }, 1000);


        }


    } else {

        setInterval(function() {
            var CurrentDate = moment().format("HH:mm:ss (Z)");
            $(".clock").text(CurrentDate);
        }, 1000);

    } 
    // end time zone in jquery



    // time zone and clock
    setInterval(function() {
        $('.tztime').each(function() {


            var zone = $(this).attr("zone");

            if (readCookie("TimeZone") == zone) {
                $(this).parent().css({
                    'background': 'rgba(235, 235, 235, 0.9)'
                });

            }

            var zonelistDate = moment().tz(zone).format("HH:mm:ss");
            $(this).text(zonelistDate);

        });
    }, 1000);
    // end time zone and clock



    // select date category
    $(function() {

        $("#selectdate").change(function() {
            var cat = $(this).attr('cat');
            var date = $(this).val();
            window.location.replace(match_options("url") + "sports/category/" + cat + "/date/" + date);

        });
    });
    // end select date category


    // select bouns and get bouns data
    $(function() {

        $("#selectbouns").change(function() {

            var id = $(this).val();

            if (id == "0") {
                return false;
            }

            $.ajax(match_options("url") + "deposit/BounsSelect", {
                type: "POST",
                dataType: "json",
                data: {
                    id: id
                },
                success: function(data) {

                    $("#min_amount").text(data.minimum_amount);
                    $("#max_amount").text(data.maximum_amount);
                    $("#percent").text(" -  " + data.percent + "%");

                },
                error: function() {

                    $("#selectbouns").val("0");
                    
                    swal({
                        title: match_options("error"),
                        text: data.responseText,
                        type: 'error',
                        showConfirmButton: false,
                    });

                }
            });




        });
    });
    // end select bouns and get bouns data


    // check signup show card number input
    $(function() {

        $("#signupCurrency").change(function() {

            var currency = $(this).val();

            if (currency != "IRT") {

                $("#card-number").css('cssText', 'display: none !important');

            } else {

                $("#card-number").css('cssText', 'display: block !important');

            }



        });
    });
    // end check signup show card number input
    
});