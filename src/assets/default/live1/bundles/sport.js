function number_format(number, separator = ',') {
    if (separator == '' || typeof separator == null || separator == 'undefined' || separator == undefined || typeof separator == undefined || separator == 'null') {
        delete separator;
        var separator = ',';
    }
    var string = number.toString().trim();
    var string_replace = string.replace(/\B(?=(?:\d{3})+(?!\d))/g, separator);
    return (string_replace);
}

function check_bets_runnerid() {
    var cookies = TkStarFreamWork.cookie('cookie_bets');
    if (typeof cookies != 'undefined') {
        try {
            var cookies_json = JSON.parse(cookies);
            if (cookies_json.length >= 1) {
                for (var i = 0; i < cookies_json.length; i++) {
                    var selected_bet = cookies_json[i];
                    TkStarFreamWork('[data-runnerid="' + selected_bet.runnerid + '"]').not('ul.bet').addClass('selected');
                }
                return true;
            } else {
                return false;
            }
        } catch (error) {
            return false;
        }
    } else {
        return false;
    }
}

function bets_cookies_load() {
    var cookies = TkStarFreamWork.cookie('cookie_bets');
    if (typeof cookies != 'undefined') {
        try {
            var cookies_json = JSON.parse(cookies);
            if (cookies_json.length >= 1) {
                TkStarFreamWork('.bettotal').show();
                var selected_bets = '';
                for (var i = 0; i < cookies_json.length; i++) {
                    var selected_bet = cookies_json[i];
                    var bet_title = '<b class="team border-radius">' + selected_bet.team1 + '</b><br><b class="team border-radius">' + selected_bet.team2 + '</b>';
                    selected_bets += bet_make(selected_bet.eventid, bet_title, selected_bet.odd, selected_bet.pick, selected_bet.market, selected_bet.marketid, selected_bet.runnerid, selected_bet.points);
                }
                TkStarFreamWork('.user-selected-odds div.bets-element').append(selected_bets);
                multiple_inputs();
                delete_all_bets();
                delete_bets_event();
                betPlace();
                calculate_total_win();
                var multiple = multiple_odds();
                TkStarFreamWork('.multiple').html(multiple);
                TkStarFreamWork('.has-tip').each(function() {
                    TkStarFreamWork(this).tooltip({
                        html: true
                    });
                });
                TkStarFreamWork('.no-exisitings-bet').hide();
                TkStarFreamWork('.change-bet-type').show();
                TkStarFreamWork('.slip-active').click();
                return true;
            } else {
                TkStarFreamWork('.change-bet-type').hide();
                TkStarFreamWork('.no-exisitings-bet').show();
                TkStarFreamWork('.bettotal').hide();
                return false;
            }
        } catch (error) {
            TkStarFreamWork('.change-bet-type').hide();
            TkStarFreamWork('.no-exisitings-bet').show();
            TkStarFreamWork('.bettotal').hide();
            return false;
        }
    } else {
        TkStarFreamWork('.change-bet-type').hide();
        TkStarFreamWork('.no-exisitings-bet').show();
        TkStarFreamWork('.bettotal').hide();
        return false;
    }
}

function bet_make(event_id, bet_title, odd, pick, market, marketid, runner_id, points) {
    var bet_odd = parseFloat(odd).toFixed(2);
    var output = '<ul data-eventid="' + event_id + '" data-pick="' + pick + '" data-points="' + points + '" data-marketid="' + marketid + '" data-runnerid="' + runner_id + '" class="bet"><li style="overflow:hidden"><span class="fa fa-trash delete-bet has-tip" title="&#1581;&#1584;&#1601;"></span>' + bet_title + '</li><li>' + market + '</li><li><span class="pick"><div class="border-radius left-align">&#1575;&#1606;&#1578;&#1582;&#1575;&#1576;: ' + pick + '</div><div class="ltrinput points left-align margin-left-5px">' + points + '</div></span><span class="odd"><span>' + bet_odd + '</span><i class="marginright"></i></span></li><li class="singles-input-li"><input class="input stake" type="text" placeholder="0" /><span>&#1605;&#1576;&#1604;&#1594; &#1576;&#1585;&#1583;: <span class="user-win-amount" style="float: initial !important;">0</span> &#1578;&#1608;&#1605;&#1575;&#1606;</span></li></ul>';
    TkStarFreamWork('[data-runnerid="' + runner_id + '"]').addClass('selected');
    var bets_count = TkStarFreamWork('.mobile-bar .slip-count-badge').text();
    bets_count = parseInt(bets_count);
    bets_count++;
    if (bets_count >= 1) {
        TkStarFreamWork('.mobile-bar .slip-count-badge').text(bets_count).show();
    } else {
        TkStarFreamWork('.mobile-bar .slip-count-badge').text('0').hide();
    }
    if (bets_count >= 1) {
        TkStarFreamWork('.change-bet-type').show();
        TkStarFreamWork('.slip-active').click();
    } else {
        TkStarFreamWork('.change-bet-type').hide();
    }
    multiple_inputs();
    return (output);
}

function in_array(array, search_key, search_value) {
    var result = false;
    TkStarFreamWork.each(array, function(index, value) {
        if (value[search_key] == search_value) {
            result = true;
        }
    });
    return (result);
}

function array_slice_remove(n, t, i) {
    TkStarFreamWork.each(n, function(r, u) {
        if (u[t] == i)
            return n.splice(r, 1), !1
    });
    return n;
}

function cookies_bet() {
    var cookies = TkStarFreamWork.cookie('cookie_bets');
    if (cookies !== 'undefined' || typeof cookies !== undefined || cookies !== null || cookies !== 'null') {
        cookies = '[]';
    }
    try {
        var cookies_json = JSON.parse(cookies);
        TkStarFreamWork('.bets-element ul.bet').each(function() {
            var this_element = TkStarFreamWork(this);
            var runner_id = this_element.data('runnerid');
            if (!in_array(cookies_json, 'runnerid', runner_id)) {
                var new_index = {};
                new_index.eventid = this_element.data('eventid');
                new_index.marketid = this_element.data('marketid');
                new_index.runnerid = runner_id;
                new_index.pick = this_element.data('pick');
                new_index.points = this_element.data('points');
                new_index.market = this_element.find('li:nth-child(2)').text().toString().trim();
                new_index.team1 = this_element.find('b.team1').text().toString().trim();
                new_index.team2 = this_element.find('b.team2').text().toString().trim();
                new_index.odd = this_element.find('span.odd span').text().toString().trim();
                new_index.stake = this_element.find('input.stake').val();
                cookies_json.push(new_index);
            }
        });
        var new_cookies = JSON.stringify(cookies_json);
        var date = new Date;
        var time = date.getTime();
        date.setTime(time + 18e5);
        TkStarFreamWork.cookie('cookie_bets', new_cookies, {
            expires: date,
            path: '/'
        });
        return (new_cookies);
    } catch (error) {
        return false;
    }
}

function multiple_odds() {
    var result = '';
    var array = [];
    TkStarFreamWork('.user-selected-odds div.bets-element ul').find('.odd').each(function() {
        array.push(TkStarFreamWork(this).text().toString().trim());
    });
    TkStarFreamWork('.user-selected-odds div.bets-element ul').each(function() {
        var eventid = TkStarFreamWork(this).data('eventid');
        if (TkStarFreamWork('.bets-element ul[data-eventid="' + eventid + '"]').length >= 1) {
            return false;
        }
    });
    TkStarFreamWork('.user-selected-odds div.bets-element ul').each(function(index) {
        if (index >= 8) {
            return true;
        }
        var index_plused = parseInt(index + 1);
        var display = index == 0 ? 'auto' : 'none';
        var mix_class = index == 0 ? 'single-form-tr' : 'mix-form-tr';
        result += '<tr style="display: ' + display + ';" class="' + mix_class + '"><td>' + multiple_type_name(index_plused) + ' (<span>x' + multiple_bets_count(index, array) + '</span>)</td>';
        if (index == 0) {
            result += '<td><input class="multiple_odd_input" type="hidden" value="' + multiple_bets(index_plused, array) + '" /><span class="user-win-amount ignore" style="float: initial !important;">0</span></td>';
        } else {
            result += '<td><input class="multiple_odd_input" type="hidden" value="' + multiple_bets(index_plused, array) + '" /><span class="user-win-amount" style="float: initial !important;">0</span></td>';
        }
        result += '<td><input type="text" class="input stake stake-' + index + '" placeholder="0" /></td>';
        result += '</tr>';
    });
    return (result);
}

function change_forms(form_type) {
    var bets_length = parseInt(TkStarFreamWork('.user-selected-odds div.bets-element ul').length);
    TkStarFreamWork('.stake-0').val('0').trigger('change');
    TkStarFreamWork('.stake-1').val('0').trigger('change');
    TkStarFreamWork('.stake-2').val('0').trigger('change');
    TkStarFreamWork('.stake-3').val('0').trigger('change');
    TkStarFreamWork('.stake-4').val('0').trigger('change');
    TkStarFreamWork('.stake-5').val('0').trigger('change');
    TkStarFreamWork('.stake-6').val('0').trigger('change');
    TkStarFreamWork('.stake-7').val('0').trigger('change');
    switch (form_type) {
        case ('singles'):
            TkStarFreamWork('.slip-singles').addClass('slip-active');
            TkStarFreamWork('.slip-mixes').removeClass('slip-active');
            TkStarFreamWork('.singles-input-li').css('display', 'auto');
            TkStarFreamWork('.single-form-tr').css('display', 'auto');
            TkStarFreamWork('.mix-form-tr').css('display', 'none');
            break;
        case ('mix'):
            if (bets_length <= 8 && bets_length >= 2) {
                TkStarFreamWork('.slip-singles').removeClass('slip-active');
                TkStarFreamWork('.slip-mixes').addClass('slip-active');
                TkStarFreamWork('.singles-input-li').css('display', 'none');
                TkStarFreamWork('.single-form-tr').css('display', 'none');
                TkStarFreamWork('.mix-form-tr').css('display', 'auto');
            } else if (bets_length == 1) {
                TkStarFreamWork('.slip-singles').addClass('slip-active');
                TkStarFreamWork('.slip-mixes').removeClass('slip-active');
                TkStarFreamWork('.singles-input-li').css('display', 'auto');
                TkStarFreamWork('.single-form-tr').css('display', 'auto');
                TkStarFreamWork('.mix-form-tr').css('display', 'none');
                swal({
                    title: '&#1582;&#1591;&#1575;&#1740;&#1740; &#1585;&#1582; &#1583;&#1575;&#1583;&#1607; &#1575;&#1587;&#1578; !',
                    text: '&#1576;&#1585;&#1575;&#1740; &#1579;&#1576;&#1578; &#1588;&#1585;&#1591; &#1605;&#1740;&#1705;&#1587; &#1605;&#1740; &#1576;&#1575;&#1740;&#1587;&#1578; &#1576;&#1740;&#1588;&#1578;&#1585; &#1575;&#1586; &#1740;&#1705; &#1588;&#1585;&#1591; &#1575;&#1606;&#1578;&#1582;&#1575;&#1576; &#1705;&#1606;&#1740;&#1583;',
                    type: 'error',
                    showCancelButton: false,
                    confirmButtonClass: 'btn-danger',
                    confirmButtonText: '<i class="fa fa-check-square"></i> &#1576;&#1587;&#1740;&#1575;&#1585; &#1582;&#1576;'
                });
            } else {
                TkStarFreamWork('.slip-singles').addClass('slip-active');
                TkStarFreamWork('.slip-mixes').removeClass('slip-active');
                TkStarFreamWork('.singles-input-li').css('display', 'auto');
                TkStarFreamWork('.single-form-tr').css('display', 'auto');
                TkStarFreamWork('.mix-form-tr').css('display', 'none');
                swal({
                    title: '&#1582;&#1591;&#1575;&#1740;&#1740; &#1585;&#1582; &#1583;&#1575;&#1583;&#1607; &#1575;&#1587;&#1578; !',
                    text: '&#1575;&#1605;&#1705;&#1575;&#1606; &#1579;&#1576;&#1578; &#1588;&#1585;&#1591; &#1605;&#1740;&#1705;&#1587; &#1576;&#1740;&#1588;&#1578;&#1585; &#1575;&#1586; 8 &#1578;&#1575;&#1740;&#1740; &#1608;&#1580;&#1608;&#1583; &#1606;&#1583;&#1575;&#1585;&#1583;. &#1588;&#1605;&#1575; ' + bets_length + ' &#1588;&#1585;&#1591; &#1575;&#1606;&#1578;&#1582;&#1575;&#1576; &#1705;&#1585;&#1583;&#1607; &#1575;&#1740;&#1583;. &#1576;&#1585;&#1575;&#1740; &#1579;&#1576;&#1578; &#1588;&#1585;&#1591; &#1605;&#1740;&#1705;&#1587; &#1604;&#1591;&#1601;&#1575; 8 &#1588;&#1585;&#1591; &#1575;&#1606;&#1578;&#1582;&#1575;&#1576; &#1705;&#1606;&#1740;&#1583;',
                    type: 'error',
                    showCancelButton: false,
                    confirmButtonClass: 'btn-danger',
                    confirmButtonText: '<i class="fa fa-check-square"></i> &#1576;&#1587;&#1740;&#1575;&#1585; &#1582;&#1576;'
                });
            }
            break;
    }
}

function multiple_inputs() {
    TkStarFreamWork('.bets-element .stake').unbind('keyup').unbind('keydown').unbind('change').on('keyup keydown change', function() {
        var this_element = TkStarFreamWork(this);
        var amount = this_element.val().toString().trim();
        amount = amount.replace(/,/g, '');
        if (amount >= 1000001) {
            delete amount;
            var amount = 1000000;
            var number_format_amount = number_format(amount, ',');
            this_element.val(number_format_amount).trigger('change');
            return false;
        }
        amount = parseInt(amount);
        var number_format_amount = number_format(amount, ',');
        var bet_odd = this_element.parent().prev().find('span.odd span').text().toString().trim();
        bet_odd = parseFloat(bet_odd);
        bet_odd = bet_odd.toFixed(2);
        var win_amount = bet_odd * amount;
        win_amount = parseInt(win_amount);
        win_amount = win_amount.toFixed(0);
        if (!isNaN(win_amount) && win_amount >= 0 && win_amount !== '0') {
            var number_format_win_amount = number_format(win_amount, ',');
            this_element.next().find('span.user-win-amount').html(number_format_win_amount);
            TkStarFreamWork('.multiple .stake-0').val('');
            TkStarFreamWork('.multiple .stake-0').parent().prev().find('span.user-win-amount').html('');
            TkStarFreamWork(this).val(number_format_amount);
        }
        multiple_updates();
        calculate_total_win();
    });
    TkStarFreamWork('.multiple .stake').unbind('keyup').unbind('keydown').unbind('change').on('keyup keydown change', function() {
        var this_element = TkStarFreamWork(this);
        var amount = this_element.val().toString().trim();
        var max_bet = this_element.hasClass('stake-0') ? 1000001 : 5000001;
        amount = amount.replace(/,/g, '');
        if (amount >= max_bet) {
            delete amount;
            var amount = parseInt(max_bet - 1);
            var number_format_amount = number_format(amount, ',');
            this_element.val(number_format_amount).trigger('change');
            delete number_format_amount;
        }
        amount = parseInt(amount);
        var number_format_amount = number_format(amount, ',');
        if (this_element.hasClass('stake-0')) {
            TkStarFreamWork('.bets-element .stake').val((!isNaN(number_format_amount.replace(/,/g, '')) && number_format_amount.replace(/,/g, '') >= 0 && number_format_amount.replace(/,/g, '') != '0') ? number_format_amount : 0);
            TkStarFreamWork('.bets-element .stake').each(function() {
                var this_element_second = TkStarFreamWork(this);
                var odd = this_element_second.parent().prev().find('span.odd span').text().toString().trim();
                var max_odd = odd >= 100.01 ? 100 : odd;
                max_odd = parseFloat(max_odd);
                max_odd = max_odd.toFixed(2);
                var win_amount = amount * max_odd;
                win_amount = parseFloat(win_amount);
                win_amount = win_amount.toFixed(0);
                if (!isNaN(win_amount) && win_amount >= 0 && win_amount !== '0') {
                    var number_format_win_amount = number_format(win_amount, ',');
                    this_element_second.next().find('span.user-win-amount').html(number_format_win_amount);
                    this_element_second.val(number_format_amount);
                }
            });
            this_element.val((!isNaN(number_format_amount.replace(/,/g, '')) && number_format_amount.replace(/,/g, '') >= 0 && number_format_amount.replace(/,/g, '') != '0') ? number_format_amount : 0);
        } else {
            var total_odd_input = this_element.parent().prev().find('.multiple_odd_input').val();
            var total_odd = parseFloat(total_odd_input);
            var max_total_odd = total_odd >= 100.01 ? 100 : total_odd;
            max_total_odd = parseFloat(max_total_odd);
            max_total_odd = max_total_odd.toFixed(2);
            var win_amount = amount * max_total_odd;
            win_amount = parseInt(win_amount);
            win_amount = win_amount.toFixed(0);
            this_element.parent().prev().find('span.user-win-amount').html((!isNaN(number_format_amount.replace(/,/g, '')) && number_format_amount.replace(/,/g, '') >= 0 && number_format_amount.replace(/,/g, '') != '0') ? number_format_amount : 0);
            this_element.val((!isNaN(number_format_amount.replace(/,/g, '')) && number_format_amount.replace(/,/g, '') >= 0 && number_format_amount.replace(/,/g, '') != '0') ? number_format_amount : 0);
        }
        multiple_updates();
        calculate_total_win();
    });
    return true;
}

function multiple_updates() {
    multiple_inputs();
    if (TkStarFreamWork('.user-selected-odds div.bets-element ul').length >= 1 && TkStarFreamWork('.user-selected-odds div.bets-element ul').length <= 8) {
        var array = [];
        TkStarFreamWork('.user-selected-odds div.bets-element ul').find('.odd').each(function() {
            var this_element = TkStarFreamWork(this);
            var odd = this_element.text().toString().trim();
            array.push(odd.replace(/,/g, ''));
        });
        TkStarFreamWork('div.bettotal table.multiple tr').each(function(index) {
            var this_element = TkStarFreamWork(this);
            var multiple_odd = multiple_bets(index + 1, array);
            var user_bet = this_element.find('input.stake').val().replace(/,/g, '');
            this_element.find('.multiple_odd_input').val(multiple_odd);
            multiple_odd = multiple_odd >= 100.01 ? 100 : multiple_odd;
            var win_amount = parseInt(multiple_odd * user_bet);
            var number_format_win_amount = number_format(win_amount, ',');
            this_element.find('span.user-win-amount').text(number_format_win_amount);
        });
        return true;
    } else {
        return false;
    }
}

function multiple_bets(count_calc, odds) {
    switch (count_calc) {
        case ('1'):
        case (1):
            var result = calc1Folds(odds);
            break;
        case ('2'):
        case (2):
            var result = calc2Folds(odds);
            break;
        case ('3'):
        case (3):
            var result = calc3Folds(odds);
            break;
        case ('4'):
        case (4):
            var result = calc4Folds(odds);
            break;
        case ('5'):
        case (5):
            var result = calc5Folds(odds);
            break;
        case ('6'):
        case (6):
            var result = calc6Folds(odds);
            break;
        case ('7'):
        case (7):
            var result = calc7Folds(odds);
            break;
        case ('8'):
        case (8):
            var result = calc8Folds(odds);
            break;
        default:
            var result = '';
            break;
    }
    return (result);
}

function multiple_bets_count(index, array) {
    var count = array.length;
    var check = multiple_bets_count_check(count);
    var index_plused = parseInt(index + 1);
    var total_bets = parseInt(multiple_bets_count_check(index_plused) * multiple_bets_count_check(count - index_plused));
    var result = parseInt(check / total_bets);
    return (result);
}

function multiple_bets_count_check(count) {
    if (count <= 1) {
        var result = 1;
    } else {
        var result = count * multiple_bets_count_check(count - 1);
    }
    return (result);
}

function multiple_type_name(multiple_count) {
    switch (multiple_count) {
        case ('1'):
        case (1):
            var result = '&#1578;&#1705;&#1740; &#1607;&#1575;';
            break;
        case ('2'):
        case (2):
            var result = '&#1583;&#1608; &#1578;&#1575;&#1740;&#1740; &#1607;&#1575;';
            break;
        case ('3'):
        case (3):
            var result = '&#1587;&#1607; &#1578;&#1575;&#1740;&#1740; &#1607;&#1575;';
            break;
        case ('4'):
        case (4):
            var result = '&#1670;&#1607;&#1575;&#1585; &#1578;&#1575;&#1740;&#1740; &#1607;&#1575;';
            break;
        case ('5'):
        case (5):
            var result = '&#1662;&#1606;&#1580; &#1578;&#1575;&#1740;&#1740; &#1607;&#1575;';
            break;
        case ('6'):
        case (6):
            var result = '&#1588;&#1588; &#1578;&#1575;&#1740;&#1740; &#1607;&#1575; ';
            break;
        case ('7'):
        case (7):
            var result = '&#1607;&#1601;&#1578; &#1578;&#1575;&#1740;&#1740; &#1607;&#1575; ';
            break;
        case ('8'):
        case (8):
            var result = '&#1607;&#1588;&#1578; &#1578;&#1575;&#1740;&#1740; &#1607;&#1575;';
            break;
        default:
            var result = '&#1606;&#1575;&#1605;&#1588;&#1582;&#1589; !';
            break;
    }
    return (result);
}

function calculate_total_win() {
    var amount = 0;
    TkStarFreamWork('.user-bets .bet').find('.stake').each(function() {
        var this_element = TkStarFreamWork(this);
        var this_amount = this_element.val().toString().trim();
        this_amount = this_amount.replace(/,/g, '');
        this_amount = parseFloat(this_amount);
        if (TkStarFreamWork.isNumeric(this_amount)) {
            amount = parseInt(amount + this_amount);
        }
    });
    TkStarFreamWork('.multiple .stake').each(function() {
        var this_element = TkStarFreamWork(this);
        var this_amount = this_element.val().toString().trim();
        this_amount = this_amount.replace(/,/g, '');
        this_amount = parseFloat(this_amount);
        if (TkStarFreamWork.isNumeric(this_amount)) {
            if (!this_element.hasClass('stake-0')) {
                var this_odd = this_element.parent().prev().prev().find('span').text().toString().trim();
                this_odd = this_odd.replace(/,/g, '');
                this_odd = this_odd.replace(/x/g, '');
                amount = parseInt(amount + parseInt(this_amount * this_odd));
            }
        }
    });
    amount = amount.toFixed(0);
    var number_format_amount = number_format(amount, ',');
    TkStarFreamWork('.totalstake').html(number_format_amount);
    var win_amount = 0;
    TkStarFreamWork('.user-bets .bet').find('span.user-win-amount').each(function() {
        var this_element = TkStarFreamWork(this);
        if (!this_element.hasClass('ignore')) {
            var this_win_amount = this_element.text().toString().trim();
            this_win_amount = this_win_amount.replace(/,/g, '');
            this_win_amount = parseInt(this_win_amount);
            win_amount = parseInt(win_amount + this_win_amount);
        }
    });
    TkStarFreamWork('.bettotal').find('span.user-win-amount').each(function() {
        var this_element = TkStarFreamWork(this);
        if (!this_element.hasClass('ignore')) {
            if (!this_element.parent().next().find('input.stake').hasClass('stake-0')) {
                var this_win_amount = this_element.text().toString().trim();
                this_win_amount = this_win_amount.replace(/,/g, '');
                this_win_amount = parseInt(this_win_amount);
                win_amount = parseInt(win_amount + this_win_amount);
            }
        }
    });
    win_amount = win_amount.toFixed(0);
    var number_format_win_amount = number_format(win_amount, ',');
    TkStarFreamWork('.totalwin').html(number_format_win_amount);
    return true;
}

function bets_odds_buttons() {
    TkStarFreamWork(".inplaybtn").unbind("click").click(function() {
        var f, e, o;
        if (TkStarFreamWork(this).hasClass('odd-alls')) {
            window.location = TkStarFreamWork(this).prop('href');
            return false;
        } else {
            if (TkStarFreamWork(".bets-element ul.bet").length < 21  && (!TkStarFreamWork(this).hasClass("selected") && !TkStarFreamWork(this).hasClass("passive-ma"))) {
                TkStarFreamWork(".bettotal").show();
                TkStarFreamWork(this).addClass("selected");
                var n = TkStarFreamWork(this).closest(".odddetails"),
                    t = TkStarFreamWork(this).data("eventid"),
                    i = n.data("marketid"),
                    h = TkStarFreamWork(this).data("runnerid"),
                    c = TkStarFreamWork(this).data("pick"),
                    l = TkStarFreamWork(this).data("points");
                r = !TkStarFreamWork('input.host').length ? TkStarFreamWork(this).parent().parent().parent().find('.host').text() : TkStarFreamWork('input.host').val(), u = !TkStarFreamWork('input.guest').length ? TkStarFreamWork(this).parent().parent().parent().find('.guest').text() : TkStarFreamWork('input.guest').val(), t = (t == '' || t == 'undefined' || t == undefined || t == 'null' || t == null) ? TkStarFreamWork(this).data('eventid') : t, i = (i == '' || i == 'undefined' || i == undefined || i == 'null' || i == null) ? TkStarFreamWork(this).data('marketid') : i, f = !TkStarFreamWork(this).parent().parent().children(':first').hasClass('inplayheader') ? '&#1606;&#1578;&#1740;&#1580;&#1607; &#1605;&#1587;&#1575;&#1576;&#1602;&#1607;' : TkStarFreamWork(this).parent().parent().children(':first').children(':first').text(), e = '<b class="team1 border-radius">' + r + '<\/b><br /><b class="team2 border-radius">' + u + "<\/b>";
                o = TkStarFreamWork(this).find('.odd-rate').find('span').text() == '' ? TkStarFreamWork(this).find('span').text() : TkStarFreamWork(this).find('.odd-rate').find('span').text();
                TkStarFreamWork('ul.bet').each(function() {
                    var runnerid_each = TkStarFreamWork(this).data('runnerid');
                    runnerid_each = runnerid_each.split('-');
                    var real_f = f == '&#1606;&#1578;&#1740;&#1580;&#1607; &#1605;&#1587;&#1575;&#1576;&#1602;&#1607;' ? '3Way Result' : f;
                    if (runnerid_each[0] == t) {
                        TkStarFreamWork('[data-runnerid="' + TkStarFreamWork(this).data('runnerid') + '"').removeClass('selected');
                        TkStarFreamWork(this).remove();
                    }
                });
                TkStarFreamWork(".user-selected-odds div.bets-element").append(bet_make(t, e, o, c, f, i, h, l));
                cookies_bet();
                delete_bets_event();
                delete_all_bets();
                betPlace();
                calculate_total_win();
                var multiple = multiple_odds();
                TkStarFreamWork('.multiple').html(multiple);
                multiple_inputs();
                TkStarFreamWork(".no-exisitings-bet").hide();
                TkStarFreamWork('.change-bet-type').show();
                TkStarFreamWork('.slip-active').click();
            }
        }
    });
    TkStarFreamWork('.has-tip').each(function() {
        TkStarFreamWork(this).tooltip({
            html: true
        });
    });
}

function delete_bets_event() {
    TkStarFreamWork('.delete-bet').unbind('click').click(function() {
        var this_element = TkStarFreamWork(this).parent().parent();
        var cookies = TkStarFreamWork.cookie('cookie_bets');
        cookies = (cookies == '' || cookies == 'undefined' || cookies == undefined || cookies == 'null' || cookies == null) ? '{}' : cookies;
        var cookies_json = JSON.parse(cookies);
        var runner_id = this_element.data('runnerid');
        var new_cookies_json = array_slice_remove(cookies_json, 'runnerid', runner_id);
        var new_cookies = JSON.stringify(new_cookies_json);
        var date_time = new Date;
        date_time.setTime(parseInt(date_time.getTime() + parseInt(30 * 6e4)));
        TkStarFreamWork.cookie('cookie_bets', new_cookies, {
            expires: date_time,
            path: '/'
        });
        TkStarFreamWork(this).parent().parent().toggle('slow', function() {
            var second_runner_id = this_element.data('runnerid');
            TkStarFreamWork('[data-runnerid="' + second_runner_id + '"]').removeClass('selected');
            TkStarFreamWork(this).remove();
            multiple_inputs();
            calculate_total_win();
            var multiple = multiple_odds();
            TkStarFreamWork('.multiple').html(multiple);
            var bets_count = TkStarFreamWork('.mobile-bar .slip-count-badge').text();
            bets_count = parseInt(bets_count);
            bets_count--;
            if (bets_count <= 0) {
                TkStarFreamWork('.change-bet-type').hide();
                TkStarFreamWork('.no-exisitings-bet').show();
                TkStarFreamWork('.bettotal').hide();
                TkStarFreamWork('.mobile-bar .slip-count-badge').text('0').hide();
            } else {
                TkStarFreamWork('.change-bet-type').show();
                TkStarFreamWork('.slip-active').click();
                TkStarFreamWork('.no-exisitings-bet').hide();
                TkStarFreamWork('.bettotal').show();
                TkStarFreamWork('.mobile-bar .slip-count-badge').text(bets_count).show();
            }
        });
    })
}

function persian_converter(number) {
    var translate = '';
    var string_number = number.toString().trim();
    for (var i = 0; i < string_number.length; i++) {
        var char_code_number = string_number.charCodeAt(i);
        if (char_code_number >= 48 && char_code_number <= 57) {
            var char_code = parseInt(char_code_number + 1728);
            translate = translate + String.fromCharCode(char_code);
        } else {
            translate = translate + String.fromCharCode(char_code_number);
        }
    }
    return (translate);
}

function moreBets_timers() {
    if (TkStarFreamWork('.eninplaytime').length >= 1) {
        TkStarFreamWork('.eninplaytime').each(function() {
            var this_element = TkStarFreamWork(this);
            var this_value = this_element.val().toString().trim();
            if (this_value !== '' && this_value !== null && this_value !== 'null' && this_value !== undefined && this_value !== 'undefined') {
                var split = this_value.split(':');
                var minute = parseInt(split[0]);
                var second = parseInt(split[1]);
                if (minute >= 94 && second <= 0) {
                    var result = '&#1576;&#1575;&#1586;&#1740; &#1578;&#1605;&#1575;&#1605; &#1588;&#1583;&#1607; &#1575;&#1587;&#1578;';
                    var result_input = '94:00';
                } else if (minute == '45' && second <= 0) {
                    var result = '&#1608;&#1602;&#1578; &#1575;&#1590;&#1575;&#1601;&#1607; &#1606;&#1740;&#1605;&#1607; &#1575;&#1608;&#1604;';
                    var result_input = '45:00';
                } else if (minute <= 0 && second <= 0) {
                    var result = '&#1576;&#1575;&#1586;&#1740; &#1588;&#1585;&#1608;&#1593; &#1606;&#1588;&#1583;&#1607; &#1575;&#1587;&#1578;';
                    var result_input = '00:00';
                } else {
                    second++;
                    if (second >= 60) {
                        minute++;
                        delete second;
                        var second = 0;
                    }
                    if (minute >= 94) {
                        delete minute;
                        delete second;
                        var minute = 94;
                        var second = 0;
                    }
                    if (minute == 94 && second == 0) {
                        var result = '&#1576;&#1575;&#1586;&#1740; &#1578;&#1605;&#1575;&#1605; &#1588;&#1583;&#1607; &#1575;&#1587;&#1578;';
                        var result_input = '94:00';
                    } else if (minute == 45 && second == 0) {
                        var result = '&#1608;&#1602;&#1578; &#1575;&#1590;&#1575;&#1601;&#1607; &#1606;&#1740;&#1605;&#1607; &#1575;&#1608;&#1604;';
                        var result_input = '45:00';
                    } else {
                        minute = minute.toString().trim();
                        minute = minute.length <= 1 ? '0' + minute : minute;
                        second = second.toString().trim();
                        second = second.length <= 1 ? '0' + second : second;
                        var result = minute + ':' + second;
                        var result_input = result;
                    }
                }
                TkStarFreamWork(this).val(result_input);
                TkStarFreamWork(this).prev().find('small').text(result);
            } else {
                TkStarFreamWork(this).val('');
                TkStarFreamWork(this).prev().find('small').text('&#1583;&#1585; &#1581;&#1575;&#1604; &#1576;&#1575;&#1585;&#1711;&#1584;&#1575;&#1585;&#1740;');
            }
        });
        return true;
    } else {
        return false;
    }
}

function inplay_timers() {
    if (TkStarFreamWork('.live-event-time').length >= 1) {
        TkStarFreamWork('.live-event-time').each(function() {
            var this_element = TkStarFreamWork(this);
            var this_value = this_element.html().toString().trim();
            if (this_value == '<i class="fa fa-clock-o" style="margin-left: 10px !important;"></i> &#1576;&#1575;&#1586;&#1740; &#1578;&#1605;&#1575;&#1605; &#1588;&#1583;&#1607; &#1575;&#1587;&#1578;') {
                this_element.html('<i class="fa fa-clock-o" style="margin-left: 10px !important;"></i> &#1576;&#1575;&#1586;&#1740; &#1578;&#1605;&#1575;&#1605; &#1588;&#1583;&#1607; &#1575;&#1587;&#1578;');
            } else if (this_value == '<i class="fa fa-clock-o" style="margin-left: 10px !important;"></i> &#1608;&#1602;&#1578; &#1575;&#1590;&#1575;&#1601;&#1607; &#1606;&#1740;&#1605;&#1607; &#1575;&#1608;&#1604;') {
                this_element.html('<i class="fa fa-clock-o" style="margin-left: 10px !important;"></i> &#1608;&#1602;&#1578; &#1575;&#1590;&#1575;&#1601;&#1607; &#1606;&#1740;&#1605;&#1607; &#1575;&#1608;&#1604;');
            } else if (this_value == '<i class="fa fa-clock-o" style="margin-left: 10px !important;"></i> &#1576;&#1575;&#1586;&#1740; &#1583;&#1585; &#1581;&#1575;&#1604; &#1588;&#1585;&#1608;&#1593; &#1575;&#1587;&#1578;') {
                this_element.html('<i class="fa fa-clock-o" style="margin-left: 10px !important;"></i> &#1576;&#1575;&#1586;&#1740; &#1583;&#1585; &#1581;&#1575;&#1604; &#1588;&#1585;&#1608;&#1593; &#1575;&#1587;&#1578;');
            } else {
                if (this_value !== '' && this_value !== null && this_value !== 'null' && this_value !== undefined && this_value !== 'undefined') {
                    var new_this_value = this_value.replace('<i class="fa fa-clock-o" style="margin-left: 10px !important;"></i> ', '');
                    var split = new_this_value.split(':');
                    var minute = parseInt(split[0]);
                    var second = parseInt(split[1]);
                    if (minute >= 94 && second <= 0) {
                        var result = '<i class="fa fa-clock-o" style="margin-left: 10px !important;"></i> &#1576;&#1575;&#1586;&#1740; &#1578;&#1605;&#1575;&#1605; &#1588;&#1583;&#1607; &#1575;&#1587;&#1578;';
                    } else if (minute == '45' && second <= 0) {
                        var result = '<i class="fa fa-clock-o" style="margin-left: 10px !important;"></i> &#1608;&#1602;&#1578; &#1575;&#1590;&#1575;&#1601;&#1607; &#1606;&#1740;&#1605;&#1607; &#1575;&#1608;&#1604;';
                    } else if (minute <= 0 && second <= 0) {
                        var result = '<i class="fa fa-clock-o" style="margin-left: 10px !important;"></i> &#1576;&#1575;&#1586;&#1740; &#1583;&#1585; &#1581;&#1575;&#1604; &#1588;&#1585;&#1608;&#1593; &#1575;&#1587;&#1578;';
                    } else {
                        second++;
                        if (second >= 60) {
                            minute++;
                            delete second;
                            var second = 0;
                        }
                        if (minute >= 94) {
                            delete minute;
                            delete second;
                            var minute = 94;
                            var second = 0;
                        }
                        if (minute == 94 && second == 0) {
                            var result = '<i class="fa fa-clock-o" style="margin-left: 10px !important;"></i> &#1576;&#1575;&#1586;&#1740; &#1578;&#1605;&#1575;&#1605; &#1588;&#1583;&#1607; &#1575;&#1587;&#1578;';
                        } else if (minute == 45 && second == 0) {
                            var result = '<i class="fa fa-clock-o" style="margin-left: 10px !important;"></i> &#1608;&#1602;&#1578; &#1575;&#1590;&#1575;&#1601;&#1607; &#1606;&#1740;&#1605;&#1607; &#1575;&#1608;&#1604;';
                        } else {
                            minute = minute.toString().trim();
                            minute = minute.length <= 1 ? '0' + minute : minute;
                            second = second.toString().trim();
                            second = second.length <= 1 ? '0' + second : second;
                            var result = '<i class="fa fa-clock-o" style="margin-left: 10px !important;"></i> ' + minute + ':' + second;
                        }
                    }
                    this_element.html(result);
                } else {
                    this_element.html('<i class="fa fa-clock-o" style="margin-left: 10px !important;"></i> &#1576;&#1575;&#1586;&#1740; &#1583;&#1585; &#1581;&#1575;&#1604; &#1588;&#1585;&#1608;&#1593; &#1575;&#1587;&#1578;');
                }
            }
        });
        return true;
    } else {
        return false;
    }
}

function betPlace() {
    TkStarFreamWork('.placebet').unbind('click').click(function(n) {
        var this_element = TkStarFreamWork(this);
        var my_forms = {
            data: []
        };
        var my_bets = TkStarFreamWork('ul.bet');
        if (my_bets.length == 1) {
            var amount = my_bets.find('input.stake').val().toString().trim();
            var my_mix_data = '&#1578;&#1705;&#1740; &#1607;&#1575;-x1-' + amount;
            my_forms.data.push({
                'match_id': my_bets.data('eventid'),
                'runner_id': my_bets.data('runnerid'),
                'pick': my_bets.data('pick'),
                'stake': my_bets.find('input.stake').val(),
                'odd': my_bets.find('span.odd span').text()
            });
        } else {
            var my_mix_data = '';
            var total = TkStarFreamWork('.multiple tr');
            var total_length = parseInt(total.length - 1);
            TkStarFreamWork('.multiple tr').each(function(index) {
                var this_element_second = TkStarFreamWork(this);
                var bet = this_element_second.find('td:eq(0)').html().toString().trim();
                var bet_split = bet.split(' (');
                var first_split = bet_split[0];
                var second_split = bet_split[1];
                var second_split_strip = second_split.replace('<span>', '').replace('<\/span>)', '');
                var amount = this_element_second.find('.stake').val().toString().trim();
                var amount_check = amount == '' ? '0' : amount;
                if (index === total_length) {
                    my_mix_data += first_split + '-' + second_split_strip + '-' + amount_check
                } else {
                    my_mix_data += first_split + '-' + second_split_strip + '-' + amount_check + '/'
                }
            });
            my_bets.each(function(index) {
                var this_element_second = TkStarFreamWork(this);
                my_forms.data.push({
                    'match_id': this_element_second.data('eventid'),
                    'runner_id': this_element_second.data('runnerid'),
                    'pick': this_element_second.data('pick'),
                    'stake': this_element_second.find('input.stake').val(),
                    'odd': this_element_second.find('span.odd span').text()
                });
            });
        }
        var bet_force = TkStarFreamWork('#bet_force').is(':checked') ? 'true' : 'false';
        this_element.attr('disabled', true);
        this_element.addClass('disabled in-progress');
        this_element.html('<i class="fa fa-pulse fa-spinner"></i> &#1583;&#1585; &#1581;&#1575;&#1604; &#1579;&#1576;&#1578; ...');
        TkStarFreamWork.ajax({
            type: 'POST',
            url: base_url + 'bets/betPlace',
            data: {
                bet_force: bet_force,
                my_forms: my_forms,
                my_mix_data: my_mix_data
            },
            success: function(response) {
                this_element.attr('disabled', false);
                this_element.removeClass('disabled').removeClass('in-progress');

        TkStarFreamWork.removeCookie('cookie_bets', {
            path: '/'
        });
        TkStarFreamWork('.mobile-bar .slip-count-badge').text('0').hide();
        TkStarFreamWork('.user-selected-odds div.bets-element ul').each(function() {
            var this_element = TkStarFreamWork(this);
            var class_name = this_element.attr('class');
            var class_name_split = class_name.split(' ');
            TkStarFreamWork('.odds td').removeClass(class_name_split[1]);
        });
        TkStarFreamWork('.bets-element ul').remove();
        TkStarFreamWork('.odd-link').removeClass('selected');
        TkStarFreamWork('[data-runnerid]').removeClass('selected');
        TkStarFreamWork('.change-bet-type').hide();
        TkStarFreamWork('.no-exisitings-bet').show();
        TkStarFreamWork('.bettotal').hide();
        TkStarFreamWork('.placebet').prop('disabled', false);
        var multiple = multiple_odds();
        TkStarFreamWork('.multiple').html(multiple);
        multiple_updates();
        calculate_total_win();
        
                this_element.html('&#1579;&#1576;&#1578; &#1588;&#1585;&#1591;');
                try {
                    var response_clear = response;
                    response_clear = encodeURIComponent(response_clear);
                    response_clear = response_clear.toString().trim();
                    response_clear = response_clear.replace(/\%00/g, '');
                    response_clear = decodeURIComponent(response_clear);
                    var json = JSON.parse(response_clear);
                    var result = json.result;
                    switch (result) {
                        case ('login'):
                            swal({
                                title: '&#1582;&#1591;&#1575;&#1740;&#1740; &#1585;&#1582; &#1583;&#1575;&#1583;&#1607; &#1575;&#1587;&#1578; !',
                                text: '&#1576;&#1585;&#1575;&#1740; &#1579;&#1576;&#1578; &#1588;&#1585;&#1591; &#1608;&#1575;&#1585;&#1583; &#1581;&#1587;&#1575;&#1576; &#1705;&#1575;&#1585;&#1576;&#1585;&#1740; &#1588;&#1608;&#1740;&#1583;',
                                type: 'error',
                                showCancelButton: true,
                                confirmButtonClass: 'btn-primary',
                                confirmButtonText: '<i class="fa fa-sign-in"></i> &#1608;&#1585;&#1608;&#1583; &#1576;&#1607; &#1581;&#1587;&#1575;&#1576; &#1705;&#1575;&#1585;&#1576;&#1585;&#1740;',
                                cancelButtonClass: 'btn-danger',
                                cancelButtonText: '<i class="fa fa-check-square"></i> &#1576;&#1587;&#1740;&#1575;&#1585; &#1582;&#1576;'
                            }, function(loginClick) {
                                if (loginClick) {
                                    window.location = base_url + 'users/login';
                                }
                            });
                            break;
                        case ('zero_balance'):
                            swal({
                                title: '&#1582;&#1591;&#1575;&#1740;&#1740; &#1585;&#1582; &#1583;&#1575;&#1583;&#1607; &#1575;&#1587;&#1578; !',
                                text: '&#1605;&#1608;&#1580;&#1608;&#1583;&#1740; &#1581;&#1587;&#1575;&#1576; &#1588;&#1605;&#1575; &#1589;&#1601;&#1585; &#1575;&#1587;&#1578;',
                                type: 'error',
                                showCancelButton: true,
                                confirmButtonClass: 'btn-primary',
                                confirmButtonText: '<i class="fa fa-money"></i> &#1575;&#1601;&#1586;&#1575;&#1740;&#1588; &#1605;&#1608;&#1580;&#1608;&#1583;&#1740; &#1581;&#1587;&#1575;&#1576;',
                                cancelButtonClass: 'btn-danger',
                                cancelButtonText: '<i class="fa fa-check-square"></i> &#1576;&#1587;&#1740;&#1575;&#1585; &#1582;&#1576;'
                            }, function(loginClick) {
                                if (loginClick) {
                                    window.location = base_url + 'payment/credit';
                                }
                            });
                            break;
                        case ('minimute_bet'):
                            swal({
                                title: '&#1582;&#1591;&#1575;&#1740;&#1740; &#1585;&#1582; &#1583;&#1575;&#1583;&#1607; &#1575;&#1587;&#1578; !',
                                text: '&#1581;&#1583;&#1575;&#1602;&#1604; &#1605;&#1576;&#1604;&#1594; &#1576;&#1585;&#1575;&#1740; &#1579;&#1576;&#1578; &#1588;&#1585;&#1591; 1,000 &#1578;&#1608;&#1605;&#1575;&#1606; &#1605;&#1740; &#1576;&#1575;&#1588;&#1583;',
                                type: 'error',
                                showCancelButton: false,
                                confirmButtonClass: 'btn-primary',
                                confirmButtonText: '<i class="fa fa-check-square"></i> &#1576;&#1587;&#1740;&#1575;&#1585; &#1582;&#1576;'
                            });
                            break;
                        case ('low_balance'):
                            swal({
                                title: '&#1582;&#1591;&#1575;&#1740;&#1740; &#1585;&#1582; &#1583;&#1575;&#1583;&#1607; &#1575;&#1587;&#1578; !',
                                text: '&#1605;&#1608;&#1580;&#1608;&#1583;&#1740; &#1588;&#1605;&#1575; &#1576;&#1585;&#1575;&#1740; &#1579;&#1576;&#1578; &#1575;&#1740;&#1606; &#1605;&#1576;&#1604;&#1594; &#1705;&#1575;&#1601;&#1740; &#1606;&#1605;&#1740; &#1576;&#1575;&#1588;&#1583;',
                                type: 'error',
                                showCancelButton: true,
                                confirmButtonClass: 'btn-primary',
                                confirmButtonText: '<i class="fa fa-money"></i> &#1575;&#1601;&#1586;&#1575;&#1740;&#1588; &#1605;&#1608;&#1580;&#1608;&#1583;&#1740; &#1581;&#1587;&#1575;&#1576;',
                                cancelButtonClass: 'btn-danger',
                                cancelButtonText: '<i class="fa fa-check-square"></i> &#1576;&#1587;&#1740;&#1575;&#1585; &#1582;&#1576;'
                            }, function(loginClick) {
                                if (loginClick) {
                                    window.location = base_url + 'payment/credit';
                                }
                            });
                            break;
                        case ('finished_match'):
                            swal({
                                title: '&#1582;&#1591;&#1575;&#1740;&#1740; &#1585;&#1582; &#1583;&#1575;&#1583;&#1607; &#1575;&#1587;&#1578; !',
                                text: '&#1740;&#1705;&#1740; &#1575;&#1586; &#1576;&#1575;&#1586;&#1740; &#1607;&#1575;&#1740; &#1575;&#1606;&#1578;&#1582;&#1575;&#1576;&#1740; &#1588;&#1605;&#1575; &#1576;&#1607; &#1662;&#1575;&#1740;&#1575;&#1606; &#1585;&#1587;&#1740;&#1583;&#1607; &#1575;&#1587;&#1578; &#1608; &#1575;&#1605;&#1705;&#1575;&#1606; &#1579;&#1576;&#1578; &#1588;&#1585;&#1591; &#1576;&#1585;&#1575;&#1740; &#1570;&#1606; &#1608;&#1580;&#1608;&#1583; &#1606;&#1583;&#1575;&#1585;&#1583;',
                                type: 'error',
                                showCancelButton: false,
                                confirmButtonClass: 'btn-danger',
                                confirmButtonText: '<i class="fa fa-check-square"></i> &#1576;&#1587;&#1740;&#1575;&#1585; &#1582;&#1576;'
                            });
                            break;
                        case ('odd_change'):
                            swal({
                                title: '&#1582;&#1591;&#1575;&#1740;&#1740; &#1585;&#1582; &#1583;&#1575;&#1583;&#1607; &#1575;&#1587;&#1578; !',
                                text: '&#1605;&#1578;&#1575;&#1587;&#1601;&#1575;&#1606;&#1607; &#1740;&#1705;&#1740; &#1575;&#1586; &#1590;&#1585;&#1575;&#1740;&#1576; &#1601;&#1585;&#1605; &#1662;&#1740;&#1588; &#1576;&#1740;&#1606;&#1740; &#1588;&#1605;&#1575; &#1578;&#1594;&#1740;&#1740;&#1585; &#1705;&#1585;&#1583;&#1607; &#1575;&#1587;&#1578;. &#1604;&#1591;&#1601;&#1575; &#1605;&#1580;&#1583;&#1583;&#1575; &#1601;&#1585;&#1605; &#1662;&#1740;&#1588; &#1576;&#1740;&#1606;&#1740; &#1585;&#1575; &#1578;&#1606;&#1592;&#1740;&#1605; &#1705;&#1606;&#1740;&#1583; &#1740;&#1575; &#1583;&#1585; &#1589;&#1608;&#1585;&#1578; &#1578;&#1605;&#1575;&#1740;&#1604; &#1711;&#1586;&#1740;&#1606;&#1607; "&#1583;&#1585; &#1589;&#1608;&#1585;&#1578; &#1578;&#1594;&#1740;&#1740;&#1585; &#1590;&#1585;&#1740;&#1576; &#1601;&#1585;&#1605; &#1662;&#1740;&#1588; &#1576;&#1740;&#1606;&#1740; &#1579;&#1576;&#1578; &#1588;&#1608;&#1583;" &#1585;&#1575; &#1601;&#1593;&#1575;&#1604; &#1705;&#1606;&#1740;&#1583;',
                                type: 'error',
                                showCancelButton: false,
                                confirmButtonClass: 'btn-danger',
                                confirmButtonText: '<i class="fa fa-check-square"></i> &#1576;&#1587;&#1740;&#1575;&#1585; &#1582;&#1576;'
                            });
                            break;
                        case ('success'):
                            swal({
                                title: '&#1605;&#1608;&#1601;&#1602;&#1740;&#1578; &#1570;&#1605;&#1740;&#1586; !',
                                text: '&#1662;&#1740;&#1588; &#1576;&#1740;&#1606;&#1740; &#1588;&#1605;&#1575; &#1576;&#1575; &#1605;&#1608;&#1601;&#1602;&#1740;&#1578; &#1579;&#1576;&#1578; &#1588;&#1583;',
                                type: 'success',
                                showCancelButton: true,
                                confirmButtonClass: 'btn-primary',
                                confirmButtonText: '<i class="fa fa-list"></i> &#1605;&#1588;&#1575;&#1607;&#1583;&#1607; &#1662;&#1740;&#1588; &#1576;&#1740;&#1606;&#1740; &#1607;&#1575;&#1740; &#1605;&#1606;',
                                cancelButtonClass: 'btn-success',
                                cancelButtonText: '<i class="fa fa-check-square"></i> &#1576;&#1587;&#1740;&#1575;&#1585; &#1582;&#1576;'
                            }, function(loginClick) {
                                if (loginClick) {
                                    window.location = base_url + 'bets/myrecords';
                                }
                            });
                            break;

                        default:
                            swal({
                                title: '&#1582;&#1591;&#1575;&#1740; &#1606;&#1575;&#1605;&#1588;&#1582;&#1589; !',
                                text: result,
                                type: 'warning',
                                showCancelButton: false,
                                confirmButtonClass: 'btn-warning',
                                confirmButtonText: '<i class="fa fa-check-square"></i> &#1576;&#1587;&#1740;&#1575;&#1585; &#1582;&#1576;'
                            });
                            break;
                    }
                } catch (error) {
                    swal({
                        title: '&#1582;&#1591;&#1575;&#1740; &#1606;&#1575;&#1605;&#1588;&#1582;&#1589; !',
                        text: '&#1582;&#1591;&#1575;&#1740;&#1740; &#1607;&#1606;&#1711;&#1575;&#1605; &#1575;&#1585;&#1587;&#1575;&#1604; &#1583;&#1585;&#1582;&#1608;&#1575;&#1587;&#1578; &#1585;&#1582; &#1583;&#1575;&#1583;&#1607; &#1575;&#1587;&#1578; ! &#1604;&#1591;&#1601;&#1575; &#1670;&#1606;&#1583; &#1583;&#1602;&#1740;&#1602;&#1607; &#1583;&#1740;&#1711;&#1585; &#1605;&#1580;&#1583;&#1583;&#1575; &#1578;&#1604;&#1575;&#1588; &#1601;&#1585;&#1605;&#1575;&#1740;&#1740;&#1583;',
                        type: 'warning',
                        confirmButtonClass: 'btn-warning',
                        confirmButtonText: '<i class="fa fa-check-square"></i> &#1576;&#1587;&#1740;&#1575;&#1585; &#1582;&#1576;'
                    });
                }
                return true;
            },
            error: function() {
                this_element.attr('disabled', false);
                this_element.removeClass('disabled').removeClass('in-progress');
                this_element.html('&#1579;&#1576;&#1578; &#1588;&#1585;&#1591;');
                swal({
                    title: '&#1582;&#1591;&#1575;&#1740; &#1606;&#1575;&#1605;&#1588;&#1582;&#1589; !',
                    text: '&#1582;&#1591;&#1575;&#1740;&#1740; &#1607;&#1606;&#1711;&#1575;&#1605; &#1575;&#1585;&#1587;&#1575;&#1604; &#1583;&#1585;&#1582;&#1608;&#1575;&#1587;&#1578; &#1585;&#1582; &#1583;&#1575;&#1583;&#1607; &#1575;&#1587;&#1578; ! &#1604;&#1591;&#1601;&#1575; &#1670;&#1606;&#1583; &#1583;&#1602;&#1740;&#1602;&#1607; &#1583;&#1740;&#1711;&#1585; &#1605;&#1580;&#1583;&#1583;&#1575; &#1578;&#1604;&#1575;&#1588; &#1601;&#1585;&#1605;&#1575;&#1740;&#1740;&#1583;',
                    type: 'warning',
                    confirmButtonClass: 'btn-warning',
                    confirmButtonText: '<i class="fa fa-check-square"></i> &#1576;&#1587;&#1740;&#1575;&#1585; &#1582;&#1576;'
                });
                return true;
            },
            timeout: 60000
        });
    });
}

function delete_all_bets() {
    TkStarFreamWork('.delete_all_bets').click(function() {
        TkStarFreamWork.removeCookie('cookie_bets', {
            path: '/'
        });
        TkStarFreamWork('.mobile-bar .slip-count-badge').text('0').hide();
        TkStarFreamWork('.user-selected-odds div.bets-element ul').each(function() {
            var this_element = TkStarFreamWork(this);
            var class_name = this_element.attr('class');
            var class_name_split = class_name.split(' ');
            TkStarFreamWork('.odds td').removeClass(class_name_split[1]);
        });
        TkStarFreamWork('.bets-element ul').remove();
        TkStarFreamWork('.odd-link').removeClass('selected');
        TkStarFreamWork('[data-runnerid]').removeClass('selected');
        TkStarFreamWork('.change-bet-type').hide();
        TkStarFreamWork('.no-exisitings-bet').show();
        TkStarFreamWork('.bettotal').hide();
        TkStarFreamWork('.placebet').prop('disabled', false);
        var multiple = multiple_odds();
        TkStarFreamWork('.multiple').html(multiple);
        multiple_updates();
        calculate_total_win();
        return true;
    });
}

function change_user_bets_location() {
    var width = TkStarFreamWork(window).width();
    if (width <= 979) {
        if (TkStarFreamWork('#second_bets_div').html() == '') {
            var user_bets = TkStarFreamWork('#first_bets_div').html();
            TkStarFreamWork('#second_bets_div').html(user_bets);
            TkStarFreamWork('#first_bets_div').html('');
        }
    } else {
        if (TkStarFreamWork('#first_bets_div').html() == '') {
            var user_bets = TkStarFreamWork('#second_bets_div').html();
            TkStarFreamWork('#first_bets_div').html(user_bets);
            TkStarFreamWork('#second_bets_div').html('');
        }
    }
    TkStarFreamWork('.has-tip').each(function() {
        TkStarFreamWork(this).tooltip({
            html: true
        });
    });
    multiple_inputs();
    delete_all_bets();
    delete_bets_event();
    betPlace();
    return true;
}
TkStarFreamWork(document).ready(function() {
    bets_cookies_load();
    bets_odds_buttons();
    change_user_bets_location();
    TkStarFreamWork(window).resize(function() {
        change_user_bets_location();
    });
    if (TkStarFreamWork('#search-box').length) {
        TkStarFreamWork('#search-box').on('keyup change', function() {
            var value_search = TkStarFreamWork(this).val().toString().trim().toLowerCase();
            if (value_search == '' || value_search == null || value_search == 'null' || value_search == undefined || value_search == 'undefined') {
                TkStarFreamWork('.event-row-search').show();
                TkStarFreamWork('.event-row-parent-search').show();
                return false;
            } else {
                TkStarFreamWork('.event-row-search').each(function() {
                    if (TkStarFreamWork(this).parent().parent().children(':first').text().toString().trim().toLowerCase().indexOf(value_search) !== -1) {
                        TkStarFreamWork(this).show();
                    } else {
                        if (TkStarFreamWork(this).text().toString().trim().toLowerCase().indexOf(value_search) !== -1) {
                            TkStarFreamWork(this).show();
                        } else {
                            TkStarFreamWork(this).hide();
                        }
                    }
                });
                TkStarFreamWork('.event-row-parent-search').each(function() {
                    if (TkStarFreamWork(this).find('.yellow').text().toString().trim().toLowerCase().indexOf(value_search) !== -1) {
                        TkStarFreamWork(this).show();
                        TkStarFreamWork(this).find('.event-row-search').show();
                    } else {
                        var disable_this_element = 0;
                        TkStarFreamWork(this).find('.event-row-search').each(function() {
                            if (TkStarFreamWork(this).is(':visible') || TkStarFreamWork(this).css('display') == '' || TkStarFreamWork(this).css('display') == 'inline' || TkStarFreamWork(this).css('display') == 'block' || TkStarFreamWork(this).css('display') == 'inline-block') {
                                disable_this_element++;
                            }
                        });
                        if (disable_this_element >= 1) {
                            TkStarFreamWork(this).show();
                        } else {
                            TkStarFreamWork(this).hide();
                        }
                    }
                });
                var show_no_matches = 0;
                TkStarFreamWork('.event-row-search').each(function() {
                    if (TkStarFreamWork(this).is(':visible')) {
                        show_no_matches++;
                    }
                });
                if (show_no_matches >= 1) {
                    TkStarFreamWork('.no-matches-found-for-search').hide();
                } else {
                    TkStarFreamWork('.no-matches-found-for-search').show();
                }
                return true;
            }
        });
    }

});