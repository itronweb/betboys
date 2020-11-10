<html lang="en">
<head>
	<?php require_once '../../api/RestApi/game.php';
	
		$game = new Game();


		$game->check_login();

        $game_id = $game->get_game_id('rps');
		$game->set_cookie('game', $game_id);
		if ( isset($_COOKIE['code']) ){
			$code = $_COOKIE['code'];
//			$game->set_gamer_token( $game_id, $code );
			$token = $game->check_can_enter_game( $game_id, $code );
			if ( $token === false )
				header('location: ../../casino/index');
		}
	
	?>
	<title>Rock Paper Scissors</title>
	<meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, height=device-height, initial-scale=1.0, maximum-scale=1.0, user-scalable=0" />
	<meta name="apple-mobile-web-app-capable" content="yes" />
    <meta name="format-detection" content="telephone=no">
    <meta name="msapplication-tap-highlight" content="no">
    <link href="../../../attachment/setting/favicon.ico" rel="shortcut icon" type="image/x-icon" />
    <link rel="stylesheet" href="../../default/modules/games/rps/css/reset.css" />
    <link rel="stylesheet" href="../../default/modules/games/rps/css/sweetalert.css" />
    <link rel="stylesheet" href="../../default/modules/games/rps/css/animation.css" />
    <link rel="stylesheet" href="../../default/modules/games/rps/css/style.css?000.006" />
	<link rel="stylesheet" href="../../default/modules/sezar/style.css" />
	
    <script type="text/javascript">
        var DEVICE_TYPE = 'browser';
        var MONEY_FORMAT = [0,'.',','];
        var API_URL	= 'http://5.9.228.161:3001/api/';
		var GAME_FOLDER = '../../default/modules/games/rps/';
		var HOME_URL = '../../../bets/casino';
		var MAIN_URL = '../../games/rps/';

        var ADDITIONAL_FILES = [];

    </script>
    <script type="text/javascript" src="../../default/modules/games/rps/js/jquery.js"></script>
  	<script type="text/javascript" src="../../default/modules/games/rps/js/jquery-ui.js"></script>
  	<script type="text/javascript" src="../../default/modules/games/rps/js/jquery.ui.touch-punch.min.js"></script>
    <script type="text/javascript" src="../../default/modules/games/rps/js/sweetalert.min.js"></script>
    <script type="text/javascript" src="../../default/modules/games/rps/js/game.js?000.002"></script>
 
</head>
<body oncontextmenu="return false">

    <div style="pointer-events: none; width: 100%; height: 150%; position: absolute; left: 0px; top: 0px;"></div>
    <div class="background" style="position: fixed; width: 100%; height: 100%; top: 0px; left: 0px;"></div>
    
    <div id="main_container" class="main_container">

        <!-- Splash screen -->
        <div id="splash_screen" class="screen">
            <table class="middle">
                <tr>
                    <td><img src="../../default/modules/games/rps/assets/logo.png" class="logo"></td>
                </tr>
            </table>
        </div>

        <!-- Loading screen -->
        <div id="loading_screen" class="screen hidden">
            <table class="middle">
                <tr>
                    <td align="center">
                        <img src="../../default/modules/games/rps/assets/loading.png" class="loading"><br />
                        <center>
                            <div align="left" class="loading_bar hidden">
                                <div id="loading_indicator" class="bar"></div>
                            </div>
                        </center>
                    </td>
                </tr>
            </table>
        </div>

        <!-- Disconnect screen -->
        <div id="disconnect_screen" class="screen hidden">
            <table class="middle">
                <tr>
                    <td>
                        <center>
                            <img src="../../default/modules/games/rps/assets/logo.png" height="150">
                            <div class="margin_10 white_font lang_31">Bağlantınız Koptu!</div>
                            <a href="javascript:;" class="refresh_button red_button lang_32">Tekrar bağlan</a>
                        </center>
                    </td>
                </tr>
            </table>
        </div>

        <!-- Friends screen -->
        <div id="friends_screen" class="screen hidden">
            <table class="middle">
                <tr height="50">
                    <td>
                        <div class="top_bar">
                            <div class="left back_to_lobby clickable"><img src="../../default/modules/games/rps/assets/back.png"></div>
                            <div class="title"><span class="lang_70">Arkadaş Listesi</span></div>
                            <div class="clear"></div>
                        </div>
                    </td>
                </tr>
                <tr>
                    <td align="center">
                        <div align="center" class="form_area">
                            <center id="friends_list">
                                <table class="support_table">
                                    <tr class="black_back">
                                        <td>
                                            <div class="left margin_5">İsim</div>
                                            <div class="right margin_5">İsim</div>
                                            <div class="clear"></div>
                                        </td>
                                    </tr>
                                    <tr class="black_back">
                                        <td>
                                            <div class="margin_5">Hiç arkadaşınız bulunmuyor</div>
                                        </td>
                                    </tr>
                                </table>
                            </center>
                        </div>
                    </td>
                </tr>
            </table>
        </div>

        <!-- Lobby screen -->
        <div id="lobby_screen" class="screen hidden">
            <div class="landscape relative">
                <table class="zoomalert middle">
                    <tr>
                        <td>
                            <img src="../../default/modules/games/rps/assets/zoomout.png" class="landscape_icon">
                            <div class="white_font lang_50">Please zoom out your screen</div>
                        </td>
                    </tr>
                </table>
                <div class="zoomnormal">
                    
                   <div class="top_left_panel">
                        <div class="left"><img id="profile_photo" src="../../default/modules/games/rps/assets/placeholder.png" class="clickable settings_button" height="100%"></div>
                        <div class="left margin_l10 clickable cashier_button">
                            <div class="top_left_name">[Username]</div>
                            <div class="top_left_chips">[Chips]</div>
                        </div>
                        <div class="clear"></div>
                    </div>

                    <div class="top_right_panel">
                        <div class="right margin_r10"><img src="../../default/modules/games/rps/assets/home.png" height="100%" class="clickable home_button"></div>
						
<!--
                        <div class="right margin_r10"><img src="../../default/modules/games/rps/assets/friends.png" height="100%" class="clickable friends_button"></div>
						
-->
                        <div class="clear"></div>
                    </div>
                                
                    <div class="center_panel">
                        <table class="middle">
                            <tr>
                                <td>
                                    <center>
                                        <table width="70%">
                                            <tr>
                                                <td width="50"><img src="../../default/modules/games/rps/assets/minus.png" width="50" class="clickable bet_selector_minus"></td>
                                                <td>
                                                    <center>
                                                        <div class="bet_holder">
                                                            <div align="left" class="bet_holder_bg round_10">
                                                                <div class="bet_holder_bg_in round_10"></div>
                                                            </div>
                                                            <div align="left">
                                                                <div class="bet_indicator round_10"></div>
                                                            </div>
                                                        </div>
                                                    </center>
                                                </td>
                                                <td width="50"><img src="../../default/modules/games/rps/assets/plus.png" width="50" class="clickable bet_selector_plus"></td>
                                            </tr>
                                        </table>
                                        <!--<a href="javascript:;" id="play_button" class="clickable white_button margin_t35 medium_font">Play for 1.000 $</a>-->
                                        <a href="javascript:;" id="play_button" class="clickable margin_t35 medium_font myButton">Play for 1.000 $</a>                                  
                                        
<!--
                                        <div id="double_button_div"><a href="javascript:;" id="double_button" class="clickable margin_t15 myButtonDouble" style="font-size: 1.5vw !important;"><img id="double_game_image" src="../../default/modules/games/rps/assets/check.png" style="visibility: hidden; width: 1.5vw !important;">&nbsp;<span class="lang_71">Double Bet</span></a></div>
                                        
                                        <style>
                                            @media screen and (max-width: 1000px) {
                                                #double_button_div { display: inline-block; }
                                            }
                                        </style>
-->

                                    </center>
                                </td>
                            </tr>
                        </table>
                    </div>

                    <div class="bottom_left_panel">
                    </div>

                    <div class="bottom_right_panel">
                    </div>
                    
                    <center>
                        <div id="waiting_players" style="position: absolute; width: 100%; height: 25%; left: 0px; bottom: 0px; color: #fff; font-size: 1.5vw !important; line-height: 150%; overflow: hidden;"></div>
                    </center>  

                </div>               
            </div>
            <table class="portrait middle">
                <tr>
                    <td>
                        <img src="../../default/modules/games/rps/assets/landscape.png" class="landscape_icon">
                        <div class="white_font lang_46">Lütfen telefonunuzu yatay konuma getirin.</div>
                    </td>
                </tr>
            </table>
        </div>

        <!-- Searching screen -->
        <div id="searching_screen" class="screen hidden">
            <table class="middle">
                <tr>
                    <td>
                        <div id="cssload-contain">
                            <div class="cssload-wrap" id="cssload-wrap1">
                                <div class="cssload-ball" id="cssload-ball1"></div>
                            </div>
                            <div class="cssload-wrap" id="cssload-wrap2">
                                <div class="cssload-ball" id="cssload-ball2"></div>
                            </div>
                            <div class="cssload-wrap" id="cssload-wrap3">
                                <div class="cssload-ball" id="cssload-ball3"></div>
                            </div>
                            <div class="cssload-wrap" id="cssload-wrap4">
                                <div class="cssload-ball" id="cssload-ball4"></div>
                            </div>
                        </div>
                        <div class="green_font margin_t70 lang_47">Oynayacak oyuncu aranıyor</div>
                        <div class="white_font margin_t7 font_12 lang_48">lütfen bekleyin</div>
                        <div><a href="javascript:;" id="cancel_button" class="clickable white_button margin_t35 lang_49">İptal Et</a></div>
                    </td>
                </tr>
            </table>
        </div>

        <!-- Game screen -->
        <div id="game_screen" class="screen hidden">
            <div class="landscape" style="width: 100%; height: 100%; position: fixed; top: 0px; left: 0px; overflow: hidden; white-space: nowrap;">
                <table class="zoomalert middle">
                    <tr>
                        <td>
                            <img src="../../default/modules/games/rps/assets/zoomout.png" class="landscape_icon">
                            <div class="white_font lang_50">Please zoom out your screen</div>
                        </td>
                    </tr>
                </table>
                <div class="zoomnormal">

                    <div class="game_screen_top">
                        <div class="gstls"><img class="clickable button_exit" src="../../default/modules/games/rps/assets/exit.png" height="80%"></div>
						
<!--                        <div class="gstls"><img class="clickable button_double" src="../../default/modules/games/rps/assets/double.png" height="80%"></div>-->
						
                        <div class="gstrs"><img class="clickable button_sounds" src="../../default/modules/games/rps/assets/soundo.png" height="80%"></div>
						
<!--
                        <div class="gstrs"><div class="chat_badge"></div><img class="clickable button_chat" src="../../default/modules/games/rps/assets/chat.png" height="80%"></div>
						
-->
                        <div class="clear"></div>
                    </div>

                    <div class="handcl hand_container">
						<img class="left_hand" src="../../default/modules/games/rps/assets/g_rock.png">
					</div>
                    <div class="handcr hand_container">
						<img class="right_hand" src="../../default/modules/games/rps/assets/g_rock_r.png"
					</div>

                    <div class="top_text">Who become 10 win the game!</div>

                    <div class="score_board" align="center">
                        <div class="score_point"><div class="stxt me_score">0</div></div>
                        <div class="score_profile spl">
                            <div align="center"><img src="../../default/modules/games/rps/assets/placeholder.png" class="spimage me_photo"></div>
                            <div align="center" class="spnamel me_name">0 - Me</div>
                        </div>
                        <div class="score_profile spr">
                            <div align="center"><img src="../../default/modules/games/rps/assets/placeholder.png" class="clickable spimage op_photo"></div>
                            <div align="center" class="spnamer op_name">0 - Opp</div>
                        </div>
                        <div class="score_point"><div class="stxt op_score">0</div></div>
                        <div class="clear"></div>
                        <div align="center" class="game_amount">800 $</div>
                    </div>

                    <div class="control_bar" align="center">
                        <div class="cbtxt">SELECT YOUR HAND</div>
                        <div align="left" class="cbturn">
                            <div class="cbturnin">
                                <div class="cbturnload"></div>
                            </div>
                        </div>
                        <div class="cbuttons">
                            <div class="cbcontainer"><img src="../../default/modules/games/rps/assets/c_rock.png" class="clickable cbbutton cbrock" data="1"><br><span class="lang_90">Rock</span></div>
                            <div class="cbcontainer"><img src="../../default/modules/games/rps/assets/c_paper.png" class="clickable cbbutton cbpaper" data="2"><br><span class="lang_91">Paper</span></div>
                            <div class="cbcontainer"><img src="../../default/modules/games/rps/assets/c_sci.png" class="clickable cbbutton cbsci" data="3"><br><span class="lang_92">Scissors</span></div>
                        </div>
                    </div>

                </div>
            </div>
            <table class="portrait middle">
                <tr>
                    <td>
                        <img src="../../default/modules/games/rps/assets/landscape.png" class="landscape_icon">
                        <div class="white_font lang_46">Lütfen telefonunuzu yatay konuma getirin.</div>
                    </td>
                </tr>
            </table>
                        
            <div class="message_alert landscape round_5">Message</div>
            
            <div class="game_result landscape clickable">
                <div align="center" class="game_result_border">
                    <div class="result_for_loser"><img src="../../default/modules/games/rps/assets/logo.png" height="40%" style="max-height: 150px;"></div>
                    <div class="result_for_loser game_result_title1 round_10 lang_53">KAYBETTİN!</div>
                    <div class="result_for_winner game_result_title2 round_10 lang_54">TEBRİKLER!</div><span class="result_for_winner"><br /><br /></span>
                    <div class="result_for_winner game_result_title3">1.500,00 $ Ödül Kazandın!</div>
                    <div><a href="javascript:;" class="white_button margin_t15 medium_font lang_55">Kapat</a></div>
                </div>
            </div>

            <div class="chat_area landscape">
                <div class="chat_area_overlay clickable"></div>
                <div class="chat_table_container box">
                    <table class="middle white_font">
                        <tr>
                            <td>
                                <div class="chat_text_container clickable" style="word-break: break-all;">
                                </div>
                            </td>
                        </tr>
                        <tr height="60">
                            <td class="chat_buttons_container">
                                <center>
                                  <form method="post" action="#" onsubmit="$('#chat_message_send').click(); return false;" style="margin: 0px; padding: 0px;">
                                    <div class="hidden"><input type="submit" class="hidden"></div>
                                    <div class="first_div"><input type="text" id="chat_message_text"></div>
                                    <div class="second_div"><a href="javascript:;" id="chat_message_send" class="box clickable round_5 lang_56">Yolla</a></div>
                                    <div class="clear"></div>
                                  </form>
                                </center>
                            </td>
                        </tr>
                    </table>
                </div>
                <div class="clear"></div>
            </div>

        </div>

    </div>

    <style>
        .zoomalert { display: none; width: 100%; height: 100%; }
        .zoomnormal { width: 100%; height: 100%; }
        #zoomMeasureId { position: absolute; width: 100%; top: -1px; z-index: -1; visibility: hidden; }
    </style>
    <div id="zoomMeasureId"></div>

    <audio id="sound_lose"  controls="controls" class="hidden" style="display: none;"><source src="../../default/modules/games/rps/assets/sounds/lose.mp3" type="audio/mpeg"></audio>
    <audio id="sound_time"  controls="controls" class="hidden" style="display: none;"><source src="../../default/modules/games/rps/assets/sounds/time.mp3" type="audio/mpeg"></audio>
    <audio id="sound_turn"  controls="controls" class="hidden" style="display: none;"><source src="../../default/modules/games/rps/assets/sounds/turn.mp3" type="audio/mpeg"></audio>
    <audio id="sound_winned"  controls="controls" class="hidden" style="display: none;"><source src="../../default/modules/games/rps/assets/sounds/winned.mp3" type="audio/mpeg"></audio>    
    <audio id="sound_hand"  controls="controls" class="hidden" style="display: none;"><source src="../../default/modules/games/rps/assets/sounds/hand.mp3" type="audio/mpeg"></audio>    

    <script>
//        if(window.self === window.top) {
//            setInterval(function() {
//                $.get("../ping?token=E972IqGCC4nVM8oNcesx");
//            }, 3000);
//        }
//		
		if(window.self === window.top) {
            setInterval(function() {
                $.ajax({
					url : "../ping/index.php",
					type: "POST",
					dataType: "json",
					data: {
						token: "<?php echo $token; ?>",
					},
					headers: {
					},
					success: function() {
						
					},
					error : function(){
						
					},
				});
            }, 30000);
        }
    </script>

    <script>
        
        var zoomwas = "";

        function measure(){
            var measureWidthNode = document.getElementById('zoomMeasureId');
            var zoomed = Math.round(1000 * measureWidthNode.offsetWidth / window.innerWidth) / 1000;
            if(zoomed=="1") {

                if(zoomwas!=zoomed) {
                    zoomwas = zoomed;
                    $(".zoomalert").hide();
                    $(".zoomnormal").show();
                }

            } else {

                if(zoomwas!=zoomed) {
                    zoomwas = zoomed;
                    $(".zoomnormal").hide();
                    $(".zoomalert").show();
                }

            }
        }

        setInterval(function() {
            measure();
        }, 500);

        measure();

    </script>    

</body>
</html>