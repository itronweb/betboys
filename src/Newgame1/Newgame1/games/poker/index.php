<!DOCTYPE html>
<html>
<head>
	<?php require_once '../../api/RestApi/game.php';
	
	$game = new Game();
	
	
	$game->check_login();
	
	$game_id = $game->get_game_id('poker');
	$game->set_cookie('game', $game_id);
	if ( isset($_COOKIE['code']) ){
		$code = $_COOKIE['code'];
		$game->set_gamer_token( $game_id, $code );
	}
	
	?>
	<title>Poker</title>
	<meta charset="UTF-8" />
	<meta name="viewport" content="width=device-width, height=device-height, initial-scale=1.0, maximum-scale=1.0, user-scalable=0" />
	<meta name="apple-mobile-web-app-capable" content="yes" />
    <link href="../../../attachment/setting/favicon.ico" rel="shortcut icon" type="image/x-icon" />
	<link rel="stylesheet" href="../../default/modules/games/poker/css/sweetalert.css" />
	<link rel="stylesheet" href="../../default/modules/games/poker/css/style.pc.css?00" />
	<link rel="stylesheet" href="../../default/modules/sezar/style.css" />
	
	<script type="text/javascript">
		var OS_NAME = 'web';
		var MOBILE_DEVICE = false;
		var MONEY_FORMAT = [0,'.',','];
		var api_url	= 'http://136.243.168.161:3001/api/';
                var API_URL	= 'http://127.0.0.1:3000/api/';
		var game_folder = '../../default/modules/games/poker/';
		var home_url = 'http://pokyman.mobi/games';
		var main_url = '../../games/poker/';

		var ADDITIONAL_FILES = [];
		ADDITIONAL_FILES["assets/table.png?0"]="../../image/aHR0cDovL2I5MGdhbWVzLnMzLmV1LWNlbnRyYWwtMS5hbWF6b25hd3MuY29tL2Jhbm5lcnMvMjAxNzExLzE1MTA1MTk1NjgtNDcyNC03NTcxLnBuZ0A2OGIxNDEyMDk5MjA2NWEyNDE5ZTUxNGU3NDJlYjNmZg%3D%3D";
	</script>
    <script type="text/javascript" src="../../default/modules/games/poker/js/jquery.js"></script>
  	<script type="text/javascript" src="../../default/modules/games/poker/js/jquery-ui.js"></script>
  	<script type="text/javascript" src="../../default/modules/games/poker/js/jquery.ui.touch-punch.min.js"></script>
  	<script type="text/javascript" src="../../default/modules/games/poker/js/jquery.maskedinput.js"></script>
    <script type="text/javascript" src="../../default/modules/games/poker/js/sweetalert.min.js"></script>
	<script type="text/javascript" src="../../default/modules/games/poker/js/game.min.js?000.007"></script>
	<script>
		$("#pc_container").height($(window).height());
	</script>

	<style>
							</style>

</head>
<body style="overflow: visible !important;" oncontextmenu="return false">

	<center>
		<!-- BACKGROUND -->
		<div style="position: fixed; top: 0px; left: 50%; transform: translate(-50%, 0); width: 100%; max-width: 1400px; background: rgba(0,0,0,0.4); min-height: 100%; height: 100%;">
		</div>
	
		<div id="pc_container" style="position: absolute; top: 0px; left: 50%; transform: translate(-50%, 0); width: 100%; max-width: 1400px; height: 100%;">		
		
			<!-- SPLASH EKRANI -->
			<div id="splash_screen" class="screen splash_style">
				<span></span>
				<img src="../../default/modules/games/poker/images/logo.png" height="25%">
			</div>

			<!-- LOADING EKRANI -->
			<div id="loading_screen" class="screen splash_style hidden">
				<table border="0" width="100%" height="100%">
					<tr>
						<td align="center">
							<img src="../../default/modules/games/poker/images/loading.svg" height="25%">
							<div class="loading_bar" align="left" style="display: none; width: 150px; height:25px; border: 1px solid #fff;">
								<div class="loading_bar_pro" style="float: left; width: 0px; height: 21px; margin: 2px; background: #fff;"></div>
							</div>
						</td>
					</tr>
				</table>
			</div>

			<!-- OYUN CANVAS ALANI -->
			<div id="main_container" class="screen splash_style hidden">

			</div>

			<!-- DISCONNECT EKRANI -->
			<div id="disconnect_screen" class="screen form_style hidden">
				<div style="padding-top: 5px;"></div>
				<div class="margin_top_20"><img class="logo" src="../../default/modules/games/poker/images/logo.png"></div>
				<center><div class="margin_top_20 lang_17" style="color: #fff; font-size: 24px;">Bağlantınız Koptu!</div></center>
				<div class="margin_top_20"><input id="connect_again" class="light_button button lang_18" type="button" value="Tekrar bağlan"></div>
			</div>

			<!-- MASA LİSTESİ EKRANI -->
			<div id="tables_screen" class="screen form_style hidden">
				<!-- form top bar -->
				<div class="top_bar">
					<div class="left_icon get_table_list"><img src="../../default/modules/games/poker/images/refresh.png" style="max-height: 64px; max-width: 64px;"></div>
					<div class="right_icon close_table_list"><img src="../../default/modules/games/poker/images/close.png" style="max-height: 64px; max-width: 64px;"></div>
					<div class="clear"></div>
				</div>
				<div class="form_container vertical_padding" style="position: static;">
					<center>
						<div id="table_list" class="table_list">
							<div class="head_div">
								<div class="title"><span class="lang_20">Bahisler</span></div>
								<div class="infos2 lang_21">Min/Max</div>
								<div class="infos_user2 lang_22">Kişi</div>
								<div class="clear"></div>
							</div>
							<div class="head_div">
								<div class="title" style="padding-left: 10px; font-size: 14px; width: 100%; color: yellow;">
									<label><input type="checkbox" id="hideempty" value="1"> <span class="lang_75">Hide Empty Tables</span></label>
								</div>
								<div class="clear"></div>
							</div>
							<div id="table_list_container" class="sub_list" style="width: 90%;">

							</div>
						</div>
					</center>
				</div>
			</div>

			<!-- ARKADAŞ LİSTESİ EKRANI -->
			<div id="friends_screen" class="screen form_style hidden">
				<!-- form top bar -->
				<div class="top_bar">
					<div class="right_icon close_friend_list"><img src="../../default/modules/games/poker/images/close.png" style="max-height: 64px; max-width: 64px;"></div>
					<div class="clear"></div>
				</div>
				<div class="form_container vertical_padding" style="position: static;">
					<center>
						<div class="table_list">
							<div class="head_div">
								<div class="title"><span class="lang_70">Arkadaş Listesi</span></div>
								<div class="clear"></div>
							</div>
							<div id="friend_list_container" class="sub_list" style="width: 90%;">
								Hiç arkadaşınız bulunmuyor
							</div>
						</div>
					</center>
				</div>
			</div>

			<!-- OYUNA OTURMA EKRANI -->
			<div id="cashin_screen" class="screen form_style cashin_screen hidden">
				
				<div class="top_bar">
					<div class="right_icon table_cashin_close"><img src="../../default/modules/games/poker/images/close.png" style="max-height: 64px; max-width: 64px;"></div>
					<div class="clear"></div>
				</div>

				<center>
					<div class="margin_top_10 title lang_23">Masaya Otur</div>
					<div id="cashin_value" class="margin_top_10 title">500 $</div>

					<div class="scroll_container">
						<div class="scroll_margin"><img src="../../default/modules/games/poker/images/minus.png" class="img_left table_cashin_minus" style="max-height: 64px; max-width: 64px;"></div>
						
						<div id="cashin_holder" class="indicator_holder">
							<div class="indicator_back">
								<div class="indicator_padding">
									<div class="round_10 black_line"></div>
								</div>
							</div>
							<div class="indicator_div"><div id="cashin_draggable" class="indicator"></div></div>
						</div>

						<div class="scroll_margin"><img src="../../default/modules/games/poker/images/plus.png" class="img_right table_cashin_plus" style="max-height: 64px; max-width: 64px;"></div>
					</div>

					<div class="info_div">
						<div class="info_left"><span id="cashin_min_sit">10 $</span><br /><span class="info_blue lang_24">Min</span></div>
						<div class="info_right"><span id="cashin_max_sit">500 $</span><br /><span class="info_blue lang_25">Max</span></div>
						<div class="clear"></div>
					</div>

					<div class="margin_top_10 font12"><span class="lang_26">Toplam Fişiniz</span>: <span id="cashin_total">500 $</span></div>
					<div class="margin_top_10"><input class="table_cashin_sit light_button button lang_27 " type="button" value="Masaya Otur"></div>

				</center>

			</div>

			<!-- ARTTIRMA EKRANI -->
			<div id="raise_panel" class="hidden raise_panel">
				<div id="raise_container" class="container">
					<div id="raise_indicator" class="indicator"></div>
					<div class="indicator_back">
						<div align="center" class="indicator_padding">
								<div class="round_10 indicator_white"></div>
						</div>
					</div>
				</div>
			</div>

			<!-- CHAT EKRANI -->
			<div id="chat_screen" class="screen hidden chat_screen">
				<div class="overlay chat_screen_overlay"></div>
				<div class="table_container">
					<table cellpadding="0" cellspacing="0">
						<tr>
							<td class="td1">
								<div id="chat_container" class="td1div">
								</div>
							</td>
						</tr>
						<tr>
							<td class="td2">
								<div class="td2div">
									<input type="text" id="chat_text_box" class="text_box">
									<input type="button" value="Yolla" class="chat_screen_send text_button lang_28">
								</div>
							</td>
						</tr>
					</table>
				</div>
				<div class="clear"></div>
			</div>
			
			<!-- SES DOSYALARI -->
			<audio id="sound_call"  controls="controls" class="hidden"><source src="../../default/modules/games/poker/assets/sounds/call.mp3" type="audio/mpeg"></audio>
			<audio id="sound_card"  controls="controls" class="hidden"><source src="../../default/modules/games/poker/assets/sounds/card.mp3" type="audio/mpeg"></audio>
			<audio id="sound_check"  controls="controls" class="hidden"><source src="../../default/modules/games/poker/assets/sounds/check.mp3" type="audio/mpeg"></audio>
			<audio id="sound_deal"  controls="controls" class="hidden"><source src="../../default/modules/games/poker/assets/sounds/deal.mp3" type="audio/mpeg"></audio>
			<audio id="sound_fold"  controls="controls" class="hidden"><source src="../../default/modules/games/poker/assets/sounds/fold.mp3" type="audio/mpeg"></audio>
			<audio id="sound_raise"  controls="controls" class="hidden"><source src="../../default/modules/games/poker/assets/sounds/raise.mp3" type="audio/mpeg"></audio>
			<audio id="sound_turn"  controls="controls" class="hidden"><source src="../../default/modules/games/poker/assets/sounds/turn.mp3" type="audio/mpeg"></audio>
			<audio id="sound_win"  controls="controls" class="hidden"><source src="../../default/modules/games/poker/assets/sounds/win.mp3" type="audio/mpeg"></audio>

		</div>
	</center>

	<script>
		if(window.self === window.top) {
			setInterval(function() {
				$.get("/ping");
			}, 30000);
		}
	</script>
	
</body>
</html>