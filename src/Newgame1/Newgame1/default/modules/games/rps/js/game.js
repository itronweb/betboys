function time_animate(_0xb679x2, _0xb679x3) {
    time_left = _0xb679x2, time_total = _0xb679x3, time_started = (new Date)['getTime'](), time_alert = !1, null == time_timer && (time_timer = setInterval(function() {
        var _0xb679x2 = time_left - ((new Date)['getTime']() - time_started);
        _0xb679x2 < 0 && (_0xb679x2 = 0);
        var _0xb679x3 = 100 * _0xb679x2 / time_total;
        _0xb679x3 > 100 && (_0xb679x3 = 100), _0xb679x2 < 2500 && !time_alert && (play_audio('time'), time_alert = !0), $('.cbturnload')['css']({
            width: _0xb679x3 + '%'
        })
    }, 30))
}

function getCookie(cname) {
    var name = cname + "=";
    var decodedCookie = decodeURIComponent(document.cookie);
    var ca = decodedCookie.split(';');
    for(var i = 0; i <ca.length; i++) {
        var c = ca[i];
        while (c.charAt(0) == ' ') {
            c = c.substring(1);
        }
        if (c.indexOf(name) == 0) {
            return c.substring(name.length, c.length);
        }
    }
    return "";
}

var assets = null,
    sounds_mute = !1,
    assets_element = function() {
        this['callback'] = null, this['assets_list'] = null, this['assets_total'] = 0, this['assets_map'] = new Object, this['load'] = function(_0xb679x2, _0xb679x3) {
            this['assets_list'] = _0xb679x2, this['assets_total'] = _0xb679x2['length'], this['callback'] = _0xb679x3, this['load_asset']()
        }, this['load_asset'] = function() {
            if (0 == this['assets_list']['length']) {
                return null != this['callback'] && this['callback'](!0, 100), !1
            };
            this['callback'](!1, this['percent']());
            var _0xb679x2 = this['assets_list']['pop']();
            'image' == _0xb679x2['type'] && (this['assets_map'][_0xb679x2['name']] = new Image, this['assets_map'][_0xb679x2['name']]['onload'] = function() {
                assets['load_asset']()
            }, this['assets_map'][_0xb679x2['name']]['src'] = _0xb679x2['file'])
        }, this['get'] = function(_0xb679x2) {
            return null != this['assets_map'][_0xb679x2] ? this['assets_map'][_0xb679x2] : null
        }, this['percent'] = function() {
            return parseInt(100 * (this['assets_total'] - this['assets_list']['length']) / this['assets_total'])
        }
    };
play_audio = function(_0xb679x2) {
    if (sounds_mute) {
        return !1
    };
    var _0xb679x3 = document['getElementById']('sound_' + _0xb679x2);
    return _0xb679x3['currentTime'] = 0, _0xb679x3['play'](), _0xb679x3
}, change_screen = function(_0xb679x2) {
    active_screen = _0xb679x2, $('.screen')['hide'](), $('#' + _0xb679x2)['show'](), 'lobby_screen' == _0xb679x2 && refresh_games_list()
}, fadein_screen = function(_0xb679x2) {
    active_screen = _0xb679x2, $('.screen')['hide'](), $('#' + _0xb679x2)['fadeIn'](), 'lobby_screen' == _0xb679x2 && refresh_games_list()
}, start_game = function(_0xb679x2) {
    setTimeout(function() {
        change_screen('loading_screen'), language = (new lang)['load'](function() {
            language['set_game'](), _0xb679x2()
        })
    }, 1e3)
}, createCookie = function(_0xb679x2, _0xb679x3) {
    var _0xb679x7 = new Date;
    _0xb679x7['setTime'](_0xb679x7['getTime']() + 2592e6);
    var _0xb679x8 = '; expires=' + _0xb679x7['toGMTString']();
    document['cookie'] = _0xb679x2 + '=' + _0xb679x3 + _0xb679x8 + '; path=/'
}, readCookie = function(_0xb679x2) {
    for (var _0xb679x3 = _0xb679x2 + '=', _0xb679x7 = document['cookie']['split'](';'), _0xb679x8 = 0; _0xb679x8 < _0xb679x7['length']; _0xb679x8++) {
        for (var _0xb679x9 = _0xb679x7[_0xb679x8];
            ' ' == _0xb679x9['charAt'](0);) {
            _0xb679x9 = _0xb679x9['substring'](1, _0xb679x9['length'])
        };
        if (0 == _0xb679x9['indexOf'](_0xb679x3)) {
            return _0xb679x9['substring'](_0xb679x3['length'], _0xb679x9['length'])
        }
    };
    return null
}, eraseCookie = function(_0xb679x2) {
    createCookie(_0xb679x2, '', -1)
}, apiRequest = function(_0xb679x2, _0xb679x3, _0xb679x7) {
    var _0xb679x8 = readCookie('language');
    null != _0xb679x8 && null == _0xb679x3['language'] && (_0xb679x3['language'] = _0xb679x8), _0xb679x3['lang'] = userLang, _0xb679x3['device'] = DEVICE_TYPE, _0xb679x3['game'] = 'rps', $['post'](API_URL + _0xb679x2, _0xb679x3)['done'](function(_0xb679x2) {
        if (null != _0xb679x7) {
            var _0xb679x3 = 'object' == typeof _0xb679x2 ? _0xb679x2 : JSON['parse'](_0xb679x2),
                _0xb679x8 = null != _0xb679x3['result'] ? _0xb679x3['result'] : 'null';
            _0xb679x7(_0xb679x8, _0xb679x3)
        }
    })['fail'](function(_0xb679x2, _0xb679x3, _0xb679x7) {
        top['location']['reload']()
    })
}, message = function(_0xb679x2) {
    var _0xb679x3 = null != language && null != language['data'] && null != language['data']['okey'] ? language['data']['okey'] : 'OK';
    swal({
        title: '',
        text: '<font style=\'font-size:20px;\'>' + _0xb679x2 + '</font>',
        confirmButtonText: _0xb679x3,
        html: !0
    })
};
var lang = function() {
    this['load'] = function(_0xb679x2) {
        var _0xb679x3 = new Object;
        _0xb679x3['lang'] = userLang;
        var _0xb679x7 = readCookie('language');
        return null != _0xb679x7 && (_0xb679x3['language'] = _0xb679x7), apiRequest('user/Newgame/language', _0xb679x3, function(_0xb679x3, _0xb679x7) {
            null != _0xb679x7['data'] && (language['data'] = _0xb679x7['data']), null != _0xb679x2 && _0xb679x2()
        }), this
    }, this['set_game'] = function() {
        $('.lang_31')['html'](this['data']['main_disconnect']), $('.lang_32')['html'](this['data']['main_connect_again']), $('.lang_46')['html'](this['data']['landscape']), $('.lang_47')['html'](this['data']['pasoor_searching']), $('.lang_48')['html'](this['data']['please_wait']), $('.lang_49')['html'](this['data']['cancel']), $('.lang_50')['html'](this['data']['zoomout']), $('.lang_53')['html'](this['data']['pasoor_loser']), $('.lang_54')['html'](this['data']['pasoor_winner']), $('.lang_55')['html'](this['data']['pasoor_close']), $('.lang_56')['html'](this['data']['chat_send']), $('.lang_70')['html'](this['data']['friends_list']), $('.lang_71')['html'](this['data']['double_option']), $('.lang_90')['html'](this['data']['rps_rock']), $('.lang_91')['html'](this['data']['rps_paper']), $('.lang_92')['html'](this['data']['rps_scissors']), null != this['data']['javascript'] && eval(this['data']['javascript'])
    }
};
full_chip_format = function(_0xb679x2) {
    return (_0xb679x2 / 1)['formatMoney'](MONEY_FORMAT[0], MONEY_FORMAT[1], MONEY_FORMAT[2]) + ' ' + language['data']['currency']
}, short_chip_format = function(_0xb679x2) {
    return (_0xb679x2 / 1)['formatMoney'](0, MONEY_FORMAT[1], MONEY_FORMAT[2]) + ' ' + language['data']['currency']
}, chip_format_no_symbol = function(_0xb679x2) {
    return (_0xb679x2 / 1)['formatMoney'](MONEY_FORMAT[0], MONEY_FORMAT[1], MONEY_FORMAT[2])
}, Number['prototype']['formatMoney'] = function(_0xb679x2, _0xb679x3, _0xb679x7) {
    var _0xb679x8 = this,
        _0xb679x2 = isNaN(_0xb679x2 = Math['abs'](_0xb679x2)) ? 2 : _0xb679x2,
        _0xb679x3 = void(0) == _0xb679x3 ? '.' : _0xb679x3,
        _0xb679x7 = void(0) == _0xb679x7 ? ',' : _0xb679x7,
        _0xb679x9 = _0xb679x8 < 0 ? '-' : '',
        _0xb679xb = parseInt(_0xb679x8 = Math['abs'](+_0xb679x8 || 0)['toFixed'](_0xb679x2)) + '',
        _0xb679xc = (_0xb679xc = _0xb679xb['length']) > 3 ? _0xb679xc % 3 : 0;
    return _0xb679x9 + (_0xb679xc ? _0xb679xb['substr'](0, _0xb679xc) + _0xb679x7 : '') + _0xb679xb['substr'](_0xb679xc)['replace'](/(\d{3})(?=\d)/g, '$1' + _0xb679x7) + (_0xb679x2 ? _0xb679x3 + Math['abs'](_0xb679x8 - _0xb679xb)['toFixed'](_0xb679x2)['slice'](2) : '')
};
var bet_selector_model = function() {
	
    this['prepare'] = function() {
        this['bets'] = null != user_data['bets'] ? user_data['bets'] : [],
		null == this['percent'] && (this['percent'] = Math['round'](this['bets']['length'] / 2)),
		this['setValue'](), 
		this['setBar']()
		
    }, this['plus'] = function() {
        this['percent'] += 1, 
		this['percent'] > this['bets']['length'] && (this['percent'] = this['bets']['length']), this['setValue'](), 
		this['setBar']()
		
    }, this['minus'] = function() {
        this['percent'] -= 1,
		this['percent'] < 1 && (this['percent'] = 1), 
		this['setValue'](), 
		this['setBar']()
		
    }, this['drag'] = function(_0xb679x2, _0xb679x3) {
        this['percent'] = Math['round'](this['bets']['length'] * _0xb679x2 / 100),
		this['percent'] < 1 && (this['percent'] = 1),
		this['setValue'](), 
		$('.bet_holder_bg_in')['css']({
			width: _0xb679x3
		})
		
    }, this['setValue'] = function() {
		console.log(_0xb679x2);
        var _0xb679x2 = short_chip_format(this['bets'][this['percent'] - 1])
        $('#play_button')['html'](language['data']['pasoor_play']['replace']('{amount}', _0xb679x2))
		
    }, this['setBar'] = function() {
		
        var _0xb679x2 = 80 * (this['percent'] - 1) / (this['bets']['length'] - 1);
        _0xb679x2 = 'lobby_screen' != active_screen ? _0xb679x2 / 100 * (0.7 * ($(window)['width']() > $(window)['height']() ? $(window)['width']() : $(window)['height']()) - 100) : $('.bet_holder')['width']() * (_0xb679x2 / 100),
		$('.bet_indicator')['css']({
			left: _0xb679x2
		});
        var _0xb679x3 = 95 * (this['percent'] - 1) / (this['bets']['length'] - 1);
        $('.bet_holder_bg_in')['css']({
            width: _0xb679x3 + '%'
        })
		
    }, this['getValue'] = function() {
        return this['bets'][this['percent'] - 1]
    }
}

clear_chat_panel = function() {
    $('#chat_button')['css']({
        color: '#fff'
    }), $('.chat_area')['hide']();
    var _0xb679x2 = '';
    for (i = 0; i < 40; i++) {
        _0xb679x2 += '<br />'
    };
    $('.chat_text_container')['html'](_0xb679x2)
}, add_to_chat_panel = function(_0xb679x2, _0xb679x3) {
    var _0xb679x7 = $('.chat_text_container')['html']();
    _0xb679x7 += '<span>' + _0xb679x2 + ':</span> ' + _0xb679x3 + '<br />', $('.chat_text_container')['html'](_0xb679x7), 'none' == $('.chat_area')['css']('display') && $('.chat_badge')['show'](), setTimeout(function() {
        $('.chat_text_container')['prop']('scrollTop', $('.chat_text_container')['prop']('scrollHeight'))
    }, 100)
}, open_chat_panel = function() {
    var _0xb679x2 = $(window)['height']();
    0 == _0xb679x2 && (_0xb679x2 = $(document)['height']());
    var _0xb679x3 = 0.95 * (_0xb679x2 - 60);
    $('.chat_text_container')['height'](_0xb679x3), $('.chat_badge')['hide'](), $('.chat_area')['fadeIn'](), $('.chat_text_container')['prop']('scrollTop', $('.chat_text_container')['prop']('scrollHeight'))
}, show_message = function(_0xb679x2) {
    $('.message_alert')['html'](_0xb679x2), $('.message_alert')['fadeIn'](), setTimeout(function() {
        $('.message_alert')['fadeOut']()
    }, 1500)
}, socket_connect = function(_0xb679x2, _0xb679x3) {
    null != socket_connection && 1 == socket_connection['readyState'] && (socket_connection['close'](), socket_connection = null), (socket_connection = new WebSocket(_0xb679x2, 'onopen'))['onopen'] = function(_0xb679x2) {
        null != _0xb679x3 && _0xb679x3(!0), _0xb679x3 = null
    }, socket_connection['onmessage'] = function(_0xb679x2) {
        var _0xb679x3 = JSON['parse'](_0xb679x2['data']);
        data_from_socket(_0xb679x3)
    }, socket_connection['onclose'] = function(_0xb679x2) {
        fadein_screen('disconnect_screen')
    }, socket_connection['onerror'] = function(_0xb679x2) {
        null != _0xb679x3 && _0xb679x3(!1), fadein_screen('disconnect_screen')
    }
}, socket_disconnect = function() {
    if (null == socket_connection || 1 != socket_connection['readyState']) {
        return !1
    };
    socket_connection['close']()
}, socket_send = function(_0xb679x2) {
    return null != socket_connection && 1 == socket_connection['readyState'] && (socket_connection['send'](JSON['stringify'](_0xb679x2)), !0)
}, connect_to_server = function(_0xb679x2) {
    socket_connect(_0xb679x2, function(_0xb679x2) {
        1 == _0xb679x2 ? socket_send({
            command: 'auth',
            uid: user_data['uid'],
            token: user_data['token'],
            game_id: user_data['game_id'],
        }) : fadein_screen('disconnect_screen')
    })
}, find_level_from_xp = function(_0xb679x2) {
    for (var _0xb679x3 = 1; _0xb679x3 <= 100; _0xb679x3++) {
        if (_0xb679x2 <= 5 * _0xb679x3 * _0xb679x3) {
            return _0xb679x3
        }
    };
    return 100
}, open_user_profile = function(_0xb679x2) {
    if (null == _0xb679x2['uid'] || _0xb679x2['uid'] == user_data['uid']) {
        return !1
    };
    var _0xb679x3 = '' != _0xb679x2['photo'] ? _0xb679x2['photo'] : assets['get']('placeholder')['src'],
        _0xb679x7 = is_my_friend(_0xb679x2['uid']) ? language['data']['profile_remove_friend'] : language['data']['profile_add_friend'],
        _0xb679x8 = (language['data']['profile_chips']['replace']('{amount}', full_chip_format(_0xb679x2['shown'])), language['data']['profile_level']['replace']('{level}', find_level_from_xp(_0xb679x2['level']))),
        _0xb679x9 = is_my_friend(_0xb679x2['uid']) ? '#AF0505' : '#1DA52E';
    swal({
        title: _0xb679x2['name'],
        imageUrl: _0xb679x3,
        text: '<font style=\'font-size:20px;\'>' + _0xb679x8 + '</font>',
        confirmButtonColor: _0xb679x9,
        confirmButtonText: _0xb679x7,
        cancelButtonText: language['data']['profile_close'],
        showCancelButton: !0,
        closeOnConfirm: !0,
        closeOnCancel: !0,
        allowOutsideClick: !0,
        html: !0
    }, function(_0xb679x3) {
        _0xb679x3 && friendship_proccess(_0xb679x2['uid'])
    })
}, friendship_proccess = function(_0xb679x2) {
    if (is_my_friend(_0xb679x2)) {
        for (key in user_data['friends']) {
            if (user_data['friends'][key]['uid'] == _0xb679x2) {
                user_data['friends']['splice'](key, 1);
                break
            }
        };
        socket_send({
            command: 'friends',
            type: 'remove',
            uid: _0xb679x2,
//			uid: user_data['uid'],
			token: user_data['token'],
			game_id: user_data['game_id'],
        })
    } else {
        user_data['friends']['push']({
            uid: _0xb679x2,
            name: ''
        }), socket_send({
            command: 'friends',
            type: 'add',
            uid: _0xb679x2,
//			uid: user_data['uid'],
			token: user_data['token'],
			game_id: user_data['game_id'],
        })
    }
}, is_my_friend = function(_0xb679x2) {
    if (null == user_data['friends']) {
        return !1
    };
    for (key in user_data['friends']) {
        if (user_data['friends'][key]['uid'] == _0xb679x2) {
            return !0
        }
    };
    return !1
}, open_friends_screen = function() {
    change_screen('loading_screen'), socket_send({
        command: 'friends',
        type: 'list',
		uid: user_data['uid'],
		token: user_data['token'],
		game_id: user_data['game_id'],
    })
}, show_friends_screen = function(_0xb679x2) {
    _0xb679x2['friends'] = _0xb679x2['friends']['sort'](function(_0xb679x2, _0xb679x3) {
        return _0xb679x3['code'] - _0xb679x2['code']
    });
    var _0xb679x3 = '<table class="support_table">';
    for (key in _0xb679x2['friends']) {
        var _0xb679x7 = '';
        '1' == _0xb679x2['friends'][key]['code'] && (_0xb679x7 = '<font color="#A91717">(' + language['data']['friends_offline'] + ')</font>'), '2' == _0xb679x2['friends'][key]['code'] && (_0xb679x7 = '<font color="#30DAE3">(' + language['data']['friends_online'] + ')</font>'), '3' == _0xb679x2['friends'][key]['code'] && (_0xb679x7 = '<font color="#3FFF28">(' + language['data']['friends_playing'] + ')</font>');
        var _0xb679x8 = '';
        '2' == _0xb679x2['friends'][key]['code'] && (_0xb679x8 = '<a href="javascript:;" class="clickable invite_button myButton" data="' + _0xb679x2['friends'][key]['uid'] + '">' + $('#play_button')['html']() + '</a>'), _0xb679x3 = _0xb679x3 + '<tr class="black_back">	<td valign="center" style="vertical-aligment: center !important;">		<div class="left margin_5">' + _0xb679x2['friends'][key]['name'] + ' &nbsp; ' + _0xb679x7 + '</div>		<div class="right margin_5">' + _0xb679x8 + '</div>		<div class="clear"></div>	</td></tr>'
    };
    _0xb679x3 += '</table>', 0 == _0xb679x2['friends']['length'] && (_0xb679x3 = '<table class="support_table">	<tr class="black_back">		<td><div class="margin_5">' + language['data']['has_no_friends'] + '</div></td>	</tr></table>'), $('#friends_list')['html'](_0xb679x3), change_screen('friends_screen'), $('.invite_button')['unbind']('click'), $('.invite_button')['click'](function(_0xb679x2) {
        _0xb679x2['preventDefault']();
        var _0xb679x3 = $(this)['attr']('data');
        change_screen('searching_screen'), socket_send({
            command: 'enter_game',
            invite: _0xb679x3,
            amount: bet_selector['getValue'](),
            double: double_game,
            uid: user_data['uid'],
            token: user_data['token'],
            game_id: user_data['game_id'],
        })
    })
}, friend_invited = function(_0xb679x2) {
    var _0xb679x3 = '' != _0xb679x2['photo'] ? _0xb679x2['photo'] : assets['get']('placeholder')['src'],
        _0xb679x7 = language['data']['invite_text']['replace']('{amount}', full_chip_format(_0xb679x2['amount']));
    null != _0xb679x2['double'] && 1 == _0xb679x2['double'] && (_0xb679x7 = language['data']['double_invite_text']['replace']('{amount}', full_chip_format(_0xb679x2['amount']))), _0xb679x7 = _0xb679x7['replace']('{name}', _0xb679x2['name']), swal({
        title: '',
        imageUrl: _0xb679x3,
        text: '<font style=\'font-size:20px;\'>' + _0xb679x7 + '</font>',
        confirmButtonColor: '#1DA52E',
        confirmButtonText: language['data']['invite_accept'],
        cancelButtonText: language['data']['invite_decline'],
        cancelButtonColor: '#AF0505',
        showCancelButton: !0,
        closeOnConfirm: !0,
        closeOnCancel: !0,
        allowOutsideClick: !0,
        html: !0
    }, function(_0xb679x3) {
        _0xb679x3 && (change_screen('searching_screen'), socket_send({
            command: 'enter_game',
            invited: _0xb679x2['uid'],
            amount: _0xb679x2['amount'],
            double: _0xb679x2['double'],
            uid: user_data['uid'],
            token: user_data['token'],
            game_id: user_data['game_id'],
        }))
    })
};
var userLang = navigator['language'] || navigator['userLanguage'],
    language, user_data = new Object,
    game_data = new Object,
    socket_connection, bet_selector, active_screen = '',
    double_game = !1;
if (void(0) === debug_level) {
    var debug_level = 0
};
game_win = function(_0xb679x2) {
    if (clear_chat_panel(), user_data['uid'] == _0xb679x2['uid_1'] && (user_data['chips'] = _0xb679x2['chips_1']), user_data['uid'] == _0xb679x2['uid_2'] && (user_data['chips'] = _0xb679x2['chips_2']), $('.top_left_chips')['html'](full_chip_format(user_data['chips'])), 'game_screen' == active_screen) {
        if (0 == _0xb679x2['winner_uid']) {
            return $('.lang_53')['html'](language['data']['pasoor_draw']), $('.result_for_winner')['hide'](), $('.result_for_loser')['show'](), $('.game_result')['fadeIn'](), !1
        };
        if (user_data['uid'] == _0xb679x2['winner_uid']) {
            $('.result_for_loser')['hide'](), $('.result_for_winner')['show']();
            var _0xb679x3 = language['data']['pasoor_win_amount']['replace']('{amount}', full_chip_format(_0xb679x2['amount']));
            $('.game_result_title3')['html'](_0xb679x3)
        } else {
            $('.lang_53')['html'](language['data']['pasoor_loser']), $('.result_for_winner')['hide'](), $('.result_for_loser')['show']()
        };
        $('.game_result')['fadeIn']()
    }
}, game_chat = function(_0xb679x2) {
    add_to_chat_panel(_0xb679x2['name'], _0xb679x2['text'])
}, game_error = function(_0xb679x2) {
    'searching_screen' == active_screen && fadein_screen('lobby_screen');
    var _0xb679x3 = _0xb679x2['message'];
    null != language['data'][_0xb679x3] && message(language['data'][_0xb679x3])
}, game_broadcast = function(_0xb679x2) {
    if ('chat' == _0xb679x2['type']) {
        var _0xb679x3 = $('.chat_text_container')['html']();
        _0xb679x3 += '<font color=\'yellow\' style=\'font-size: 18px; font-weight: bold;\'>' + _0xb679x2['message'] + '</font><br />', $('.chat_text_container')['html'](_0xb679x3), 'none' == $('.chat_area')['css']('display') && $('.chat_badge')['show'](), setTimeout(function() {
            $('.chat_text_container')['prop']('scrollTop', $('.chat_text_container')['prop']('scrollHeight'))
        }, 100)
    } else {
        message(_0xb679x2['message'])
    }
}, data_from_socket = function(_0xb679x2) {
    var _0xb679x3 = null != _0xb679x2['command'] ? _0xb679x2['command'] : '';
    	'auth' == _0xb679x3 && game_auth(_0xb679x2), 
		'not_found' == _0xb679x3 && game_not_found(_0xb679x2),
		'start_error' == _0xb679x3 && game_start_error(_0xb679x2),
		'game_started' == _0xb679x3 && game_game_started(_0xb679x2), 
		'select' == _0xb679x3 && game_select(_0xb679x2), 
		'played' == _0xb679x3 && game_played(_0xb679x2), 
		'game_turn' == _0xb679x3 && game_turn(_0xb679x2), 
		'game_status' == _0xb679x3 && game_game_status(_0xb679x2), 
		'win' == _0xb679x3 && game_win(_0xb679x2), 
		'chat' == _0xb679x3 && game_chat(_0xb679x2), 
		'error' == _0xb679x3 && game_error(_0xb679x2), 
		'broadcast' == _0xb679x3 && game_broadcast(_0xb679x2), 
		'friends' == _0xb679x3 && show_friends_screen(_0xb679x2), 
		'invite' == _0xb679x3 && friend_invited(_0xb679x2), 
		'games_list' == _0xb679x3 && games_list(_0xb679x2), 
		'cancelled' == _0xb679x3 && cancelled_game(_0xb679x2), 
		'double_accept' == _0xb679x3 && double_accept(_0xb679x2), 
		'double_offer' == _0xb679x3 && double_offer(_0xb679x2)
}, double_accept = function(_0xb679x2) {
    var _0xb679x3 = _0xb679x2['amount'];
    1 == _0xb679x2['double_level'] && (_0xb679x3 *= 2), 2 == _0xb679x2['double_level'] && (_0xb679x3 *= 4), $('.game_amount')['html'](full_chip_format(_0xb679x3))
},
	double_offer = function(_0xb679x2) {
    if ('USER1' == game_data['me'] && '1' == _0xb679x2['offered']) {
        return $('.button_double')['hide'](), !1
    };
    if ('USER2' == game_data['me'] && '2' == _0xb679x2['offered']) {
        return $('.button_double')['hide'](), !1
    };
    var _0xb679x3 = _0xb679x2['amount'];
	console.log(_0xb679x2);
    0 == _0xb679x2['double_level'] && (_0xb679x3 *= 2), 1 == _0xb679x2['double_level'] && (_0xb679x3 *= 4), swal({
        title: '',
        text: '<font style=\'font-size:20px;\'>' + language['data']['double_offer_accept']['replace']('{amount}', full_chip_format(_0xb679x3)) + '</font>',
        type: 'warning',
        showCancelButton: !0,
        confirmButtonColor: '#DD6B55',
        confirmButtonText: language['data']['accept'],
        cancelButtonText: language['data']['dont_accept'],
        closeOnConfirm: !0,
        html: !0,
        allowOutsideClick: !1,
        allowEscapeKey: !1,
        allowEnterKey: !1
    }, function(_0xb679x2) {
        _0xb679x2 ? socket_send({
            command: 'double_accept',
            uid: user_data['uid'],
            token: user_data['token'],
            game_id: user_data['game_id'],
        }) : socket_send({
            command: 'drawn',
            uid: user_data['uid'],
            token: user_data['token'],
            game_id: user_data['game_id'],
        })
    })
},
	games_list = function(_0xb679x2) {
    _0xb679x2['games']['sort'](function(_0xb679x2, _0xb679x3) {
        return _0xb679x3['amount'] - _0xb679x2['amount']
    });
    var _0xb679x3 = '',
        _0xb679x7 = 0;
    for (i in _0xb679x2['games']) {
        var _0xb679x8 = language['data']['game_waiting'];
        1 == _0xb679x2['games'][i]['double'] && (_0xb679x8 = language['data']['game_double_waiting']), _0xb679x8 = (_0xb679x8 = _0xb679x8['replace']('{amount}', '<span class=\'games-waiting-amount\'>' + full_chip_format(_0xb679x2['games'][i]['amount']) + '</span>'))['replace']('{name}', '<span class=\'games-waiting-name\'>' + _0xb679x2['games'][i]['name'] + '</span>');
        var _0xb679x9 = _0xb679x2['games'][i]['double'] ? 'double' : 'normal';
        if (_0xb679x3 = _0xb679x3 + _0xb679x8 + (' <a href=\'javascript:;\' class=\'clickable joinButton\' data=\'' + _0xb679x2['games'][i]['amount'] + '\' data-token=\'' + _0xb679x2['games'][i]['token'] + '\' data-double=\'' + _0xb679x9 + '\'>' + language['data']['join'] + '</a>') + '<br>', (_0xb679x7 += 1) >= 5) {
            break
        }
    };
    $('#waiting_players')['html'](_0xb679x3), $('.joinButton')['unbind']('click'), $('.joinButton')['click'](function() {
        var _0xb679x2 = $(this)['attr']('data'),
            _0xb679x3 = $(this)['attr']('data-double'),
            _0xb679x4 = $(this)['attr']('data-token');
		console.log(_0xb679x2);
		console.log(_0xb679x3);
        _0xb679x3 = 'double' == _0xb679x3, change_screen('searching_screen'), socket_send({
            command: 'enter_game',
            amount: _0xb679x2,
            double: _0xb679x3,
			uid: user_data['uid'],
            token: user_data['token'],
            game_id: user_data['game_id'],
			op_token: _0xb679x4,
        })
    })
},
	refresh_games_list = function() {
    $('#waiting_players')['html'](''), socket_send({
        command: 'games_list',
		uid: user_data['uid'],
		token: user_data['token'],
		game_id: user_data['game_id'],
    })
},
	cancelled_game = function(_0xb679x2) {
    change_screen('lobby_screen')
}, 
	game_auth = function(_0xb679x2) {
    user_data['uid'] = _0xb679x2['uid'], user_data['name'] = _0xb679x2['name'], user_data['photo'] = _0xb679x2['photo'], user_data['chips'] = _0xb679x2['chips'], user_data['currency'] = _0xb679x2['currency'], user_data['bets'] = _0xb679x2['bets'], user_data['level'] = _0xb679x2['level'], user_data['friends'] = _0xb679x2['friends'], '' != user_data['photo'] && (user_data['photo_image'] = new Image, user_data['photo_image']['onload'] = function() {
        $('#profile_photo')['attr']('src', user_data['photo'])
    }, user_data['photo_image']['onerror'] = function() {}, user_data['photo_image']['src'] = user_data['photo']);
//    var _0xb679x3 = '(' + find_level_from_xp(user_data['level']) + ') ' + user_data['name'];
    var _0xb679x3 =  user_data['name'];
    $('.top_left_name')['html'](_0xb679x3), $('.top_left_chips')['html'](full_chip_format(user_data['chips'])), bet_selector['prepare'](), change_screen('lobby_screen')
},
	game_not_found = function(_0xb679x2) {
    change_screen('lobby_screen'), message(language['data']['pasoor_not_found'])
},
	game_start_error = function(_0xb679x2) {
    change_screen('lobby_screen'), message(language['data']['error'])
},
	game_game_started = function(_0xb679x2) {
    console.log(_0xb679x2);
    if ($('.game_result')['hide'](), $('.chat_area')['hide'](), null != _0xb679x2['double'] && 1 == _0xb679x2['double'] && null != _0xb679x2['double_level']) {
		
        _0xb679x8 = _0xb679x2['amount'];
        1 == _0xb679x2['double_level'] && (_0xb679x8 *= 2), 2 == _0xb679x2['double_level'] && (_0xb679x8 *= 4), $('.game_amount')['html'](full_chip_format(_0xb679x8))
		
    } else {
		
        $('.game_amount')['html'](full_chip_format(_0xb679x2['amount']))
		
    };

    if ($('.top_text')['html'](language['data']['rps_total_game']['replace']('{count}', _0xb679x2['total_hand'])), game_data['me'] = _0xb679x2['uid_1'] == user_data['uid'] ? 'USER1' : 'USER2', $('.cbrock')['attr']('src', assets['get']('c_rock')['src']), $('.cbpaper')['attr']('src', assets['get']('c_paper')['src']), $('.cbsci')['attr']('src', assets['get']('c_sci')['src']), 3 == _0xb679x2['game_status'] ? (time_animate(_0xb679x2['turn_time'], _0xb679x2['turn_total']), $('.cbtxt')['html'](language['data']['rps_select_hand']), $('.cbtxt, .cbturn, .cbuttons')['removeClass']('invisible'), null != _0xb679x2['selected'] && _0xb679x2['selected'] > 0 && (1 == _0xb679x2['selected'] ? ($('.cbrock')['attr']('src', assets['get']('c_rock_sel')['src']), $('.cbtxt')['html'](language['data']['rps_selected_hand']['replace']('{name}', language['data']['rps_rock']))) : 2 == _0xb679x2['selected'] ? ($('.cbpaper')['attr']('src', assets['get']('c_paper_sel')['src']), $('.cbtxt')['html'](language['data']['rps_selected_hand']['replace']('{name}', language['data']['rps_paper']))) : 3 == _0xb679x2['selected'] && ($('.cbsci')['attr']('src', assets['get']('c_sci_sel')['src']), $('.cbtxt')['html'](language['data']['rps_selected_hand']['replace']('{name}', language['data']['rps_scissors'])))), $('.cbbutton')['unbind']('click'), $('.cbbutton')['click'](function() {
            var _0xb679x2 = $(this)['attr']('data');
            console.log(_0xb679x2);
            if ('1' != _0xb679x2 && '2' != _0xb679x2 && '3' != _0xb679x2) {
                return !1
            };
            socket_send({
                command: 'play',
                selected: _0xb679x2,
				uid: user_data['uid'],
				token: user_data['token'],
				game_id: user_data['game_id'],
            })
        })) : $('.cbtxt, .cbturn, .cbuttons')['addClass']('invisible'), $('.op_photo')['unbind']('click'), $('.me_photo, .op_photo')['attr']('src', assets['get']('placeholder')['src']), $('.left_hand')['attr']({
            src: assets['get']('g_rock')['src'],
            style: 'transform: rotate(0deg);'
        }), $('.right_hand')['attr']({
            src: assets['get']('g_rock_r')['src'],
            style: 'transform: rotate(0deg);'
        }), 'USER1' == game_data['me'] ? ($('.me_name')['html'](find_level_from_xp(_0xb679x2['level_1']) + ' - ' + _0xb679x2['name_1']), $('.op_name')['html'](find_level_from_xp(_0xb679x2['level_2']) + ' - ' + _0xb679x2['name_2']), $('.me_score')['html'](_0xb679x2['score_1']), $('.op_score')['html'](_0xb679x2['score_2']), $('.op_photo')['click'](function() {
            open_user_profile({
                uid: _0xb679x2['uid_2'],
                photo: _0xb679x2['photo_2'],
                shown: _0xb679x2['chips_2'],
                level: _0xb679x2['level_2'],
                name: _0xb679x2['name_2']
            })
        }), '' != _0xb679x2['photo_1'] && (user_data['tempphoto1'] = new Image, user_data['tempphoto1']['onload'] = function() {
            $('.me_photo')['attr']('src', _0xb679x2['photo_1'])
        }, user_data['tempphoto1']['onerror'] = function() {}, user_data['tempphoto1']['src'] = _0xb679x2['photo_1']), '' != _0xb679x2['photo_2'] && (user_data['tempphoto2'] = new Image, user_data['tempphoto2']['onload'] = function() {
            $('.op_photo')['attr']('src', _0xb679x2['photo_2'])
        }, user_data['tempphoto2']['onerror'] = function() {}, user_data['tempphoto2']['src'] = _0xb679x2['photo_2'])) : ($('.me_name')['html'](find_level_from_xp(_0xb679x2['level_2']) + ' - ' + _0xb679x2['name_2']), $('.op_name')['html'](find_level_from_xp(_0xb679x2['level_1']) + ' - ' + _0xb679x2['name_1']), $('.me_score')['html'](_0xb679x2['score_2']), $('.op_score')['html'](_0xb679x2['score_1']), $('.op_photo')['click'](function() {
            open_user_profile({
                uid: _0xb679x2['uid_1'],
                photo: _0xb679x2['photo_1'],
                shown: _0xb679x2['chips_1'],
                level: _0xb679x2['level_1'],
                name: _0xb679x2['name_1']
            })
        }), '' != _0xb679x2['photo_1'] && (user_data['tempphoto1'] = new Image, user_data['tempphoto1']['onload'] = function() {
            $('.op_photo')['attr']('src', _0xb679x2['photo_1'])
        }, user_data['tempphoto1']['onerror'] = function() {}, user_data['tempphoto1']['src'] = _0xb679x2['photo_1']), '' != _0xb679x2['photo_2'] && (user_data['tempphoto2'] = new Image, user_data['tempphoto2']['onload'] = function() {
            $('.me_photo')['attr']('src', _0xb679x2['photo_2'])
        }, user_data['tempphoto2']['onerror'] = function() {}, user_data['tempphoto2']['src'] = _0xb679x2['photo_2'])), $('.button_double')['hide'](), null != _0xb679x2['double'] && 1 == _0xb679x2['double']) {
        var _0xb679x3 = !0;
        'USER1' == game_data['me'] && null != _0xb679x2['double_uid_1'] && 1 == _0xb679x2['double_uid_1'] && (_0xb679x3 = !1), 'USER2' == game_data['me'] && null != _0xb679x2['double_uid_2'] && 1 == _0xb679x2['double_uid_2'] && (_0xb679x3 = !1), _0xb679x3 && $('.button_double')['show']()
    };
	
    if (clear_chat_panel(), change_screen('game_screen'), null != _0xb679x2['double_waiting'] && 1 == _0xb679x2['double_waiting']) {
		
        var _0xb679x7 = !1;
        if ('USER1' == game_data['me'] && 2 == _0xb679x2['double_offered'] && (_0xb679x7 = !0), 'USER2' == game_data['me'] && 1 == _0xb679x2['double_offered'] && (_0xb679x7 = !0), _0xb679x7) {
            var _0xb679x8 = _0xb679x2['amount'];
            0 == _0xb679x2['double_level'] && (_0xb679x8 *= 2), 1 == _0xb679x2['double_level'] && (_0xb679x8 *= 4), swal({
                title: '',
                text: '<font style=\'font-size:20px;\'>' + language['data']['double_offer_accept']['replace']('{amount}', full_chip_format(_0xb679x8)) + '</font>',
                type: 'warning',
                showCancelButton: !0,
                confirmButtonColor: '#DD6B55',
                confirmButtonText: language['data']['accept'],
                cancelButtonText: language['data']['dont_accept'],
                closeOnConfirm: !0,
                html: !0,
                allowOutsideClick: !1,
                allowEscapeKey: !1,
                allowEnterKey: !1
            }, function(_0xb679x2) {
                _0xb679x2 ? socket_send({
                    command: 'double_accept',
					uid: user_data['uid'],
					token: user_data['token'],
					game_id: user_data['game_id'],
                }) : socket_send({
                    command: 'drawn',
					uid: user_data['uid'],
					token: user_data['token'],
					game_id: user_data['game_id'],
                })
            })
        }
    }
	

},
	game_select = function(_0xb679x2) {
    game_turn(_0xb679x2);
    $('.cbrock')['attr']('src', assets['get']('c_rock')['src']), $('.cbpaper')['attr']('src', assets['get']('c_paper')['src']), $('.cbsci')['attr']('src', assets['get']('c_sci')['src']), 3 == _0xb679x2['game_status'] ? (play_audio('turn'), time_animate(_0xb679x2['turn_time'], _0xb679x2['turn_total']), $('.cbtxt')['html'](language['data']['rps_select_hand']), $('.cbtxt, .cbturn, .cbuttons')['removeClass']('invisible'), null != _0xb679x2['selected'] && _0xb679x2['selected'] > 0 && (1 == _0xb679x2['selected'] ? ($('.cbrock')['attr']('src', assets['get']('c_rock_sel')['src']), $('.cbtxt')['html'](language['data']['rps_selected_hand']['replace']('{name}', language['data']['rps_rock']))) : 2 == _0xb679x2['selected'] ? ($('.cbpaper')['attr']('src', assets['get']('c_paper_sel')['src']), $('.cbtxt')['html'](language['data']['rps_selected_hand']['replace']('{name}', language['data']['rps_paper']))) : 3 == _0xb679x2['selected'] && ($('.cbsci')['attr']('src', assets['get']('c_sci_sel')['src']), $('.cbtxt')['html'](language['data']['rps_selected_hand']['replace']('{name}', language['data']['rps_scissors'])))), $('.cbbutton')['unbind']('click'), $('.cbbutton')['click'](function() {
        var _0xb679x2 = $(this)['attr']('data');
        console.log('select');
        if ('1' != _0xb679x2 && '2' != _0xb679x2 && '3' != _0xb679x2) {
            return !1
        };
        socket_send({
            command: 'play',
            selected: _0xb679x2,
            uid: user_data['uid'],
            token: user_data['token'],
            game_id: user_data['game_id'],
        })
    })) : $('.cbtxt, .cbturn, .cbuttons')['addClass']('invisible')

}, 
	game_played = function(_0xb679x2) {
    $('.cbtxt, .cbturn, .cbuttons')['removeClass']('invisible'), $('.cbrock')['attr']('src', assets['get']('c_rock')['src']), $('.cbpaper')['attr']('src', assets['get']('c_paper')['src']), $('.cbsci')['attr']('src', assets['get']('c_sci')['src']), null != _0xb679x2['selected'] && _0xb679x2['selected'] > 0 && (1 == _0xb679x2['selected'] ? ($('.cbrock')['attr']('src', assets['get']('c_rock_sel')['src']), $('.cbtxt')['html'](language['data']['rps_selected_hand']['replace']('{name}', language['data']['rps_rock']))) : 2 == _0xb679x2['selected'] ? ($('.cbpaper')['attr']('src', assets['get']('c_paper_sel')['src']), $('.cbtxt')['html'](language['data']['rps_selected_hand']['replace']('{name}', language['data']['rps_paper']))) : 3 == _0xb679x2['selected'] && ($('.cbsci')['attr']('src', assets['get']('c_sci_sel')['src']), $('.cbtxt')['html'](language['data']['rps_selected_hand']['replace']('{name}', language['data']['rps_scissors']))))
};
var turn_end_timer = null,
    turn_end_me = 0,
    turn_end_op = 0,
    turn_end_win = 2;
game_turn = function(_0xb679x2) {
    console.log('111111111');
    $('.cbtxt, .cbturn')['addClass']('invisible'), $('.cbuttons')['removeClass']('invisible'), $('.cbbutton')['unbind']('click'), 'USER1' == game_data['me'] ? (turn_end_me = _0xb679x2['score_1'], turn_end_op = _0xb679x2['score_2'], turn_end_win = 1 == _0xb679x2['winner'] ? 'me' : 'op', start_animation(_0xb679x2['selected_1'], _0xb679x2['selected_2'])) : (turn_end_op = _0xb679x2['score_1'], turn_end_me = _0xb679x2['score_2'], turn_end_win = 2 == _0xb679x2['winner'] ? 'me' : 'op', start_animation(_0xb679x2['selected_2'], _0xb679x2['selected_1'])), 0 == _0xb679x2['winner'] && (turn_end_win = ''), null != turn_end_timer && clearInterval(turn_end_timer), turn_end_timer = setInterval(function() {
        (new Date)['getTime']() > animation_finish_time + 100 && (clearInterval(turn_end_timer), turn_end_timer = null, $('.me_score')['html'](turn_end_me), $('.op_score')['html'](turn_end_op), 'me' == turn_end_win && (play_audio('winned'), winner_efect('me')), 'op' == turn_end_win && (play_audio('lose'), winner_efect('op')))
    }, 100);
    game_played(_0xb679x2);
}, game_game_status = function(_0xb679x2) {
    game_game_started(_0xb679x2)
};
var winner_timer = null,
    winner_fin = 0,
    winner_who = '',
    winner_time = 0;
winner_efect = function(_0xb679x2) {
    null != winner_timer && clearInterval(winner_timer), winner_fin = (new Date)['getTime']() + 1200, winner_who = _0xb679x2, winner_time = 0, winner_timer = setInterval(function() {
        winner_time % 2 == 0 ? 'me' == winner_who ? ($('.me_score')['addClass']('gback'), $('.me_photo')['addClass']('gstroke')) : ($('.op_score')['addClass']('rback'), $('.op_photo')['addClass']('rstroke')) : ($('.gback')['removeClass']('gback'), $('.gstroke')['removeClass']('gstroke'), $('.rback')['removeClass']('rback'), $('.rstroke')['removeClass']('rstroke')), ((winner_time += 1) > 6 || (new Date)['getTime']() > winner_fin) && (clearInterval(winner_timer), winner_timer = null, $('.gback')['removeClass']('gback'), $('.gstroke')['removeClass']('gstroke'), $('.rback')['removeClass']('rback'), $('.rstroke')['removeClass']('rstroke'))
    }, 200)
}, init_buttons = function() {
    $('.back_to_lobby')['click'](function() {
        fadein_screen('lobby_screen')
    }), $('.chat_area_overlay')['click'](function() {
        $('.chat_area')['hide']()
    }), $('#chat_message_send')['click'](function() {
        var _0xb679x2 = $('#chat_message_text')['val']();
        if ('' == _0xb679x2) {
            return !1
        };
        $('#chat_message_text')['val'](''), socket_send({
            command: 'chat',
            text: _0xb679x2,
			uid: user_data['uid'],
			token: user_data['token'],
			game_id: user_data['game_id'],
        })
    }), double_game && $('#double_game_image')['css']({
        visibility: 'visible'
    }), $('#double_button')['click'](function() {
        double_game ? (double_game = !1, $('#double_game_image')['css']({
            visibility: 'hidden'
        })) : (double_game = !0, $('#double_game_image')['css']({
            visibility: 'visible'
        }))
    }), $('.home_button')['click'](function() {
        top['location'] = HOME_URL
    }), $('.refresh_button')['click'](function() {
        top['location'] = MAIN_URL
    }), $('#play_button')['click'](function() {
        change_screen('searching_screen'), socket_send({
            command: 'enter_game',
            amount: bet_selector['getValue'](),
            double: double_game,
			uid: user_data['uid'],
            token: user_data['token'],
            game_id: user_data['game_id'],
        })
    }), $('#cancel_button')['click'](function() {
        socket_send({
            command: 'cancel_game',
			uid: user_data['uid'],
			token: user_data['token'],
			game_id: user_data['game_id'],
        })
    }), $('.friends_button')['click'](function() {
        open_friends_screen()
    }), $('.button_chat')['click'](function() {
        open_chat_panel()
    }), $('.button_double')['click'](function() {
        double_game_offer()
    }), $('.button_exit')['click'](function() {
        leave_game()
    }), $('.game_result')['click'](function() {
        $('.game_result')['hide'](), change_screen('lobby_screen')
    });
    var _0xb679x2 = readCookie('rpssounds');
    sounds_mute = null != _0xb679x2 && 'mute' == _0xb679x2, $('.button_sounds')['click'](function() {
        sounds_mute ? (sounds_mute = !1, createCookie('rpssounds', ''), $('.button_sounds')['attr']({
            src: assets['get']('soundo')['src']
        })) : (sounds_mute = !0, createCookie('rpssounds', 'mute'), $('.button_sounds')['attr']({
            src: assets['get']('soundc')['src']
        }))
    }), bet_selector = new bet_selector_model, 
		$('.bet_selector_plus')['click'](function() {
        bet_selector['plus']();
    }), $('.bet_selector_minus')['click'](function() {
        bet_selector['minus']();
    }), $('.bet_indicator')['draggable']({
        axis: 'x',
        containment: '.bet_holder',
        scroll: !1,
        drag: function(_0xb679x2, _0xb679x3) {
            var _0xb679x7 = $('.bet_holder')['width']() - $('.bet_indicator')['width'](),
                _0xb679x8 = Math['round'](100 * _0xb679x3['position']['left'] / _0xb679x7);
            bet_selector['drag'](_0xb679x8, _0xb679x3['position']['left'] + 10);
			
        }
		
		
		
    }), $(window)['resize'](function() {
        resize_screen(), 'lobby_screen' == active_screen && fadein_screen('lobby_screen'), 'game_screen' == active_screen && fadein_screen('game_screen')
    })
}, leave_game = function() {
    swal({
        title: '',
        text: '<font style=\'font-size:20px;\'>' + language['data']['pasoor_exit_confirm'] + '</font>',
        type: 'warning',
        showCancelButton: !0,
        confirmButtonColor: '#DD6B55',
        confirmButtonText: language['data']['pasoor_exit_yes'],
        cancelButtonText: language['data']['pasoor_exit_no'],
        closeOnConfirm: !0,
        html: !0,
        allowOutsideClick: !1,
        allowEscapeKey: !1,
        allowEnterKey: !1
    }, function() {
        socket_send({
            command: 'drawn',
			uid: user_data['uid'],
			token: user_data['token'],
			game_id: user_data['game_id'],
        })
    })
}, double_game_offer = function() {
    swal({
        title: '',
        text: '<font style=\'font-size:20px;\'>' + language['data']['double_offer_confirm'] + '</font>',
        type: 'warning',
        showCancelButton: !0,
        confirmButtonColor: '#DD6B55',
        confirmButtonText: language['data']['pasoor_exit_yes'],
        cancelButtonText: language['data']['pasoor_exit_no'],
        closeOnConfirm: !0,
        html: !0,
        allowOutsideClick: !1,
        allowEscapeKey: !1,
        allowEnterKey: !1
    }, function() {
        socket_send({
            command: 'double_offer',
			uid: user_data['uid'],
			token: user_data['token'],
			game_id: user_data['game_id'],
        })
    })
}, init_game_conf = function() {
    resize_screen(), init_buttons();
    var _0xb679x1f = [{
        name: 'back',
        file: 'assets/back.png',
        type: 'image'
    }, {
        name: 'button_blue',
        file: 'assets/button_blue.png',
        type: 'image'
    }, {
        name: 'button_red',
        file: 'assets/button_red.png',
        type: 'image'
    }, {
        name: 'c_paper',
        file: 'assets/c_paper.png',
        type: 'image'
    }, {
        name: 'c_paper_sel',
        file: 'assets/c_paper_sel.png',
        type: 'image'
    }, {
        name: 'c_rock',
        file: 'assets/c_rock.png',
        type: 'image'
    }, {
        name: 'c_rock_sel',
        file: 'assets/c_rock_sel.png',
        type: 'image'
    }, {
        name: 'c_sci',
        file: 'assets/c_sci.png',
        type: 'image'
    }, {
        name: 'c_sci_sel',
        file: 'assets/c_sci_sel.png',
        type: 'image'
    }, {
        name: 'chat',
        file: 'assets/chat.png',
        type: 'image'
    }, {
        name: 'check',
        file: 'assets/check.png',
        type: 'image'
    }, {
        name: 'double',
        file: 'assets/double.png',
        type: 'image'
    }, {
        name: 'exit',
        file: 'assets/exit.png',
        type: 'image'
    }, {
        name: 'friends',
        file: 'assets/friends.png',
        type: 'image'
    }, {
        name: 'g_paper',
        file: 'assets/g_paper.png',
        type: 'image'
    }, {
        name: 'g_paper_r',
        file: 'assets/g_paper_r.png',
        type: 'image'
    }, {
        name: 'g_rock',
        file: 'assets/g_rock.png',
        type: 'image'
    }, {
        name: 'g_rock_r',
        file: 'assets/g_rock_r.png',
        type: 'image'
    }, {
        name: 'g_sci',
        file: 'assets/g_sci.png',
        type: 'image'
    }, {
        name: 'g_sci_r',
        file: 'assets/g_sci_r.png',
        type: 'image'
    }, {
        name: 'home',
        file: 'assets/home.png',
        type: 'image'
    }, {
        name: 'landscape',
        file: 'assets/landscape.png',
        type: 'image'
    }, {
        name: 'minus',
        file: 'assets/minus.png',
        type: 'image'
    }, {
        name: 'placeholder',
        file: 'assets/placeholder.png',
        type: 'image'
    }, {
        name: 'plus',
        file: 'assets/plus.png',
        type: 'image'
    }, {
        name: 'soundc',
        file: 'assets/soundc.png',
        type: 'image'
    }, {
        name: 'soundo',
        file: 'assets/soundo.png',
        type: 'image'
    }, {
        name: 'zoomout',
        file: 'assets/zoomout.png',
        type: 'image'
    }];
    for (i in _0xb679x1f) {
        _0xb679x1f[i]['file'] = GAME_FOLDER + _0xb679x1f[i]['file']
    };
    if ('undefined' != typeof ADDITIONAL_FILES) {
        for (ikl in ADDITIONAL_FILES) {
            var _0xb679x20 = ADDITIONAL_FILES[ikl],
                _0xb679x21 = !1;
            for (i in _0xb679x1f) {
                if (_0xb679x1f[i]['name'] == _0xb679x20['name']) {
                    _0xb679x1f[i]['file'] = _0xb679x20['file'], _0xb679x1f[i]['type'] = _0xb679x20['type'], _0xb679x21 = !0;
                    break
                }
            };
            _0xb679x21 || _0xb679x1f['push']({
                name: _0xb679x20['name'],
                file: _0xb679x20['file'],
                type: _0xb679x20['type']
            })
        }
    };
    start_game(function() {
		var code = getCookie('code');
		var game_id = getCookie('game');
        (assets = new assets_element)['load'](_0xb679x1f, function(_0xb679x22, _0xb679x23) {
            $('.loading_bar')['show'](), $('#loading_indicator')['width'](1.5 * _0xb679x23), _0xb679x22 && (sounds_mute && $('.button_sounds')['attr']({
                src: assets['get']('soundc')['src']
            }), apiRequest('user/Newgame/auth', {'code': code, 'game_id': game_id}, function(_0xb679x24, _0xb679x25) {
                'ok' == _0xb679x24 ? (user_data['uid'] = _0xb679x25['data']['uid'], user_data['game_id'] = _0xb679x25['data']['game_id'], user_data['token'] = _0xb679x25['data']['token'], null != _0xb679x25['data']['javascript'] && eval(_0xb679x25['data']['javascript']), connect_to_server(_0xb679x25['data']['address'])) : fadein_screen('disconnect_screen')
            }))
        })
    })
}, resize_screen = function() {
    var _0xb679x2 = $(window)['width'](),
        _0xb679x3 = $(window)['height']();
    0 == _0xb679x3 && (_0xb679x3 = $(document)['height']());
    var _0xb679x7 = _0xb679x3 / 10 > 100 ? 100 : Math['round'](_0xb679x3 / 10),
        _0xb679x8 = Math['round'](_0xb679x7 / 5);
    if ($('.game_screen_top')['height'](_0xb679x7), $('.game_screen_top .gstls')['css']({
            "\x6D\x61\x72\x67\x69\x6E\x2D\x6C\x65\x66\x74": _0xb679x8 + 'px',
            "\x6D\x61\x72\x67\x69\x6E\x2D\x74\x6F\x70": _0xb679x8 + 'px'
        }), $('.game_screen_top .gstrs')['css']({
            "\x6D\x61\x72\x67\x69\x6E\x2D\x72\x69\x67\x68\x74": _0xb679x8 + 'px',
            "\x6D\x61\x72\x67\x69\x6E\x2D\x74\x6F\x70": _0xb679x8 + 'px'
        }), _0xb679x2 / _0xb679x3 > 1.5) {
        var _0xb679x9 = Math['round'](300 * _0xb679x3 / 800),
            _0xb679xb = Math['round'](400 * _0xb679x9 / 300);
        $('.hand_container')['css']({
            width: _0xb679xb + 'px',
            height: _0xb679x9 + 'px'
        })
    } else {
        var _0xb679xb = Math['round'](400 * _0xb679x2 / 1200),
            _0xb679x9 = Math['round'](300 * _0xb679xb / 400);
        $('.hand_container')['css']({
            width: _0xb679xb + 'px',
            height: _0xb679x9 + 'px'
        })
    };
    var _0xb679xc = Math['round'](_0xb679x3 / 8),
        _0xb679x26 = Math['round'](_0xb679x3 / 8 * 0.8),
        _0xb679x27 = Math['round'](1.5 * (_0xb679xc - _0xb679x26)),
        _0xb679x28 = Math['round'](3 * _0xb679x26 / 10),
        _0xb679x29 = Math['round']((_0xb679x26 - _0xb679x28) / 4),
        _0xb679x2a = Math['round'](_0xb679x26 / 2),
        _0xb679x2b = Math['round']((_0xb679x27 + _0xb679xc / 2 + 1) / -1),
        _0xb679x2c = Math['round']((_0xb679x27 + _0xb679xc / 2 + 1) / 1);
    $('.score_point')['css']({
        width: _0xb679x26 + 'px',
        height: _0xb679xc + 'px',
        "\x70\x61\x64\x64\x69\x6E\x67\x2D\x74\x6F\x70": _0xb679x27 + 'px'
    }), $('.score_point .stxt')['css']({
        width: _0xb679x28 + 'px',
        height: _0xb679x28 + 'px',
        "\x66\x6F\x6E\x74\x2D\x73\x69\x7A\x65": _0xb679x28 + 'px',
        padding: _0xb679x29 + 'px',
        "\x62\x6F\x72\x64\x65\x72\x2D\x72\x61\x64\x69\x75\x73": _0xb679x2a + 'px'
    }), $('.spl')['css']({
        "\x70\x61\x64\x64\x69\x6E\x67\x2D\x72\x69\x67\x68\x74": _0xb679x27 + 'px'
    }), $('.spr')['css']({
        "\x70\x61\x64\x64\x69\x6E\x67\x2D\x6C\x65\x66\x74": _0xb679x27 + 'px'
    }), $('.spl, .spr')['css']({
        width: _0xb679xc + 'px',
        "\x6D\x61\x72\x67\x69\x6E\x2D\x62\x6F\x74\x74\x6F\x6D": Math['round'](1.2 * _0xb679x27) + 'px'
    }), $('.spnamel')['css']({
        "\x6D\x61\x72\x67\x69\x6E\x2D\x6C\x65\x66\x74": _0xb679x2b + 'px',
        "\x6D\x61\x72\x67\x69\x6E\x2D\x74\x6F\x70": Math['round'](0.3 * _0xb679x27) + 'px'
    }), $('.spnamer')['css']({
        "\x6D\x61\x72\x67\x69\x6E\x2D\x6C\x65\x66\x74": _0xb679x2c + 'px',
        "\x6D\x61\x72\x67\x69\x6E\x2D\x74\x6F\x70": Math['round'](0.3 * _0xb679x27) + 'px'
    }), $('.cbturn')['css']({
        height: Math['round'](1.2 * _0xb679x27) + 'px',
        "\x6D\x61\x72\x67\x69\x6E\x2D\x74\x6F\x70": Math['round'](0.5 * _0xb679x27) + 'px'
    }), $('.cbbutton')['css']({
        width: _0xb679xc + 'px',
        height: _0xb679xc + 'px',
        margin: Math['round'](0.5 * _0xb679x27) + 'px'
    })
};
var animation_timer = null,
    animation_finish_left = 0,
    animation_finish_right = 0,
    animation_finish_time = 0,
    animation_degree = 0,
    animation_speed = -5,
    animation_times = 0;
start_animation = function(_0xb679x2, _0xb679x3) {
    return $('.left_hand')['attr']({
        src: assets['get']('g_rock')['src']
    }), $('.right_hand')['attr']({
        src: assets['get']('g_rock_r')['src']
    }), $('.left_hand .right_hand')['attr']({
        style: 'transform: rotate(0deg);'
    }), animation_finish_left = _0xb679x2, animation_finish_right = _0xb679x3, animation_finish_time = (new Date)['getTime']() + 2250, animation_degree = 0, animation_speed = -5, animation_times = 0, null != animation_timer && clearInterval(animation_timer), animation_timer = setInterval(function() {
        0 == animation_degree && play_audio('hand'), ((animation_degree += animation_speed) > 0 || animation_degree < -50) && (animation_speed *= -1), $('.left_hand')['attr']({
            style: 'transform: rotate(' + animation_degree + 'deg);'
        }), $('.right_hand')['attr']({
            style: 'transform: rotate(' + -1 * animation_degree + 'deg);'
        });
        var _0xb679x2 = !1;
        if (0 == animation_degree && 5 == (animation_times += 1) && (_0xb679x2 = !0), (new Date)['getTime']() > animation_finish_time && (_0xb679x2 = !0), _0xb679x2) {
            null != animation_timer && clearInterval(animation_timer), animation_timer = null;
            var _0xb679x3 = 2 == animation_finish_left ? 'g_paper' : 'g_rock';
            3 == animation_finish_left && (_0xb679x3 = 'g_sci');
            var _0xb679x7 = 2 == animation_finish_right ? 'g_paper_r' : 'g_rock_r';
            3 == animation_finish_right && (_0xb679x7 = 'g_sci_r'), $('.left_hand')['attr']({
                src: assets['get'](_0xb679x3)['src'],
                style: 'transform: rotate(0deg);'
            }), $('.right_hand')['attr']({
                src: assets['get'](_0xb679x7)['src'],
                style: 'transform: rotate(0deg);'
            })
        }
    }, 30), animation_finish_time
};
var time_timer = null,
    time_total = 2e4,
    time_left = 0,
    time_started = 0,
    time_alert = !1;
$(document)['ready'](function() {
    init_game_conf()
})