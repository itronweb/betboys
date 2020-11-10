<html>
<head>
	<?php require_once '../../api/RestApi/game.php';
	
	$game = new Game();
	
	
	$game->check_login();
	
	$game_id = $game->get_game_id('pasoor');
		
	$game->set_cookie('game', $game_id);
	if ( isset($_COOKIE['code']) ){
		$code = $_COOKIE['code'];
		$game->set_gamer_token( $game_id, $code );
	}
	
	?>
	<title>Pasoor</title>
	<meta charset="UTF-8" />
	<meta name="viewport" content="width=device-width, height=device-height, initial-scale=1.0" />
	<meta name="apple-mobile-web-app-capable" content="yes" />
	<meta name="format-detection" content="telephone=no">
	<meta name="msapplication-tap-highlight" content="no">
    <link href="../../../attachment/setting/favicon.ico" rel="shortcut icon" type="image/x-icon" />
	<link rel="stylesheet" href="../../default/modules/games/pasoor/css/reset.css" />
	<link rel="stylesheet" href="../../default/modules/games/pasoor/css/sweetalert.css" />
	<link rel="stylesheet" href="../../default/modules/games/pasoor/css/animation.css" />
	<link rel="stylesheet" href="../../default/modules/games/pasoor/css/style.css?000.027" />
	<link rel="stylesheet" href="../../default/modules/sezar/style.css" />
	
	<script type="text/javascript">
		var DEVICE_TYPE	= 'browser';
		var MONEY_FORMAT = [0,'.',','];
		var API_URL	= 'http://136.243.168.161:3000/api/';
		var GAME_FOLDER = '../../default/modules/games/pasoor/';
		var HOME_URL = '/games';
		var MAIN_URL = '../../games/pasoor/';

		var ADDITIONAL_FILES = [];
		ADDITIONAL_FILES["assets/board.png"]="../../image/aHR0cDovL2I5MGdhbWVzLnMzLmV1LWNlbnRyYWwtMS5hbWF6b25hd3MuY29tL2Jhbm5lcnMvMjAxNzExLzE1MTA2Nzg0MDYtMjg4MS03NDEzLnBuZ0A1ODBhY2NiYzcxNjFjMjM2ZjIzMDRiYzIwZWM5NTQ0MA%3D%3D";				
	</script>
    <script type="text/javascript" src="../../default/modules/games/pasoor/js/jquery.js"></script>
  	<script type="text/javascript" src="../../default/modules/games/pasoor/js/jquery-ui.js"></script>
  	<script type="text/javascript" src="../../default/modules/games/pasoor/js/jquery.ui.touch-punch.min.js"></script>
    <script type="text/javascript" src="../../default/modules/games/pasoor/js/jquery.maskedinput.js"></script>
    <script type="text/javascript" src="../../default/modules/games/pasoor/js/sweetalert.min.js"></script>
    <script type="text/javascript" src="../../default/modules/games/pasoor/js/game.min.js?000.012"></script>

    <style>
							#game_container { text-align: center !important; background: url(/image/aHR0cDovL2I5MGdhbWVzLnMzLmV1LWNlbnRyYWwtMS5hbWF6b25hd3MuY29tL2Jhbm5lcnMvMjAxNzExLzE1MTA2Nzg0MDYtMjg4MS03NDEzLnBuZ0A1ODBhY2NiYzcxNjFjMjM2ZjIzMDRiYzIwZWM5NTQ0MA%3D%3D) !important; background-repeat: no-repeat !important; background-size: contain !important; background-position: center center !important; }
					</style>

	<style>
		.doublepassive { opacity: 0.2; filter: alpha(opacity=20); }
	</style>

</head>
<body oncontextmenu="return false" style="position: fixed; top: 0px; left: 0px; width: 100%; height 100%; overflow: hidden;">

	<div style="pointer-events: none; width: 100%; height: 150%; position: absolute; left: 0px; top: 0px;"></div>
	
	<div class="background" style="position: fixed; width: 100%; height: 100%; top: 0px; left: 0px;"></div>
	<canvas id="canvas_holder" class="background_canvas" style="pointer-events: none;"></canvas>

	<div id="main_container" class="main_container">

		<!-- Splash screen -->
		<div id="splash_screen" class="screen">
			<table class="middle">
				<tr>
					<td><img src="../../default/modules/games/pasoor/assets/logo.png" class="logo"></td>
				</tr>
			</table>
		</div>

		<!-- Loading screen -->
		<div id="loading_screen" class="screen hidden">
			<table class="middle">
				<tr>
					<td align="center">
						<img src="../../default/modules/games/pasoor/assets/loading.png" class="loading"><br />
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
							<img src="../../default/modules/games/pasoor/assets/logo.png" height="150">
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
							<div class="left back_to_lobby clickable"><img src="../../default/modules/games/pasoor/assets/back.png"></div>
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

				<div class="top_left_panel">
					<div class="left"><img id="profile_photo" src="../../default/modules/games/pasoor/assets/placeholder.png" class="clickable settings_button" height="100%"></div>
					<div class="left margin_l10 clickable cashier_button">
						<div class="top_left_name">[Username]</div>
						<div class="top_left_chips">[Chips]</div>
					</div>
					<div class="clear"></div>
				</div>

				<div class="top_right_panel">
					<div class="right margin_r10"><img src="../../default/modules/games/pasoor/assets/home.png" height="100%" class="clickable home_button"></div>
					<div class="right margin_r10"><img src="../../default/modules/games/pasoor/assets/friends.png" height="100%" class="clickable friends_button"></div>
					<div class="clear"></div>
				</div>
							
				<div class="center_panel">
					<table class="middle">
						<tr>
							<td>
								<center>
									<table width="70%">
										<tr>
											<td width="50"><img src="../../default/modules/games/pasoor/assets/minus.png" width="50" class="clickable bet_selector_minus"></td>
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
											<td width="50"><img src="../../default/modules/games/pasoor/assets/plus.png" width="50" class="clickable bet_selector_plus"></td>
										</tr>
									</table>
									<!--<a href="javascript:;" id="play_button" class="clickable white_button margin_t35 medium_font">Play for 1.000 $</a>-->
									<a href="javascript:;" id="play_button" class="clickable margin_t35 medium_font myButton">Play for 1.000 $</a>									
									
									<div id="double_button_div"><a href="javascript:;" id="double_button" class="clickable margin_t15 myButtonDouble" style="font-size: 1.5vw !important;"><img id="double_game_image" src="../../default/modules/games/pasoor/assets/check.png" style="visibility: hiddimg src="../../default/modules/games/pasoor/assets/landscape.png" class="landscape_icon">
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
			<table class="landscape middle">
				<tr>
					<td id="game_container" align="center" width="86.95%">

						<table border="0" width="100%" height="100%" cellspacing="0" cellpadding="0">
							<tr height="20%">
								<td class="opp-hand-area">
									<img id="card-place-5" src="../../default/modules/games/pasoor/assets/cards/52.png" class="card_place">
									<img id="card-place-6" src="../../default/modules/games/pasoor/assets/cards/52.png" class="card_place">
									<img id="card-place-7" src="../../default/modules/games/pasoor/assets/cards/52.png" class="card_place">
									<img id="card-place-8" src="../../default/modules/games/pasoor/assets/cards/52.png" class="card_place">
								</td>
							</tr>
							<tr height="20%">
								<td>
									<img id="card-place-19" src="../../default/modules/games/pasoor/assets/cards/52.png" class="card_place">
									<img id="card-place-17" src="../../default/modules/games/pasoor/assets/cards/52.png" class="card_place">
									<img id="card-place-15" src="../../default/modules/games/pasoor/assets/cards/52.png" class="card_place">
									<img id="card-place-16" src="../../default/modules/games/pasoor/assets/cards/52.png" class="card_place">
									<img id="card-place-18" src="../../default/modules/games/pasoor/assets/cards/52.png" class="card_place">
								</td>
							</tr>
							<tr height="20%">
								<td>
									<img id="card-place-14" src="../../default/modules/games/pasoor/assets/cards/52.png" class="card_place">
									<img id="card-place-12" src="../../default/modules/games/pasoor/assets/cards/52.png" class="card_place">
									<img id="card-place-10" src="../../default/modules/games/pasoor/assets/cards/52.png" class="card_place">
									<img id="card-place-11" src="../../default/modules/games/pasoor/assets/cards/52.png" class="card_place">
									<img id="card-place-13" src="../../default/modules/games/pasoor/assets/cards/52.png" class="card_place">
								</td>
							</tr>
							<tr height="20%">
								<td>
									<img id="card-place-24" src="../../default/modules/games/pasoor/assets/cards/52.png" class="card_place">
									<img id="card-place-22" src="../../default/modules/games/pasoor/assets/cards/52.png" class="card_place">
									<img id="card-place-20" src="../../default/modules/games/pasoor/assets/cards/52.png" class="card_place">
									<img id="card-place-21" src="../../default/modules/games/pasoor/assets/cards/52.png" class="card_place">
									<img id="card-place-23" src="../../default/modules/games/pasoor/assets/cards/52.png" class="card_place">
								</td>
							</tr>
							<tr height="20%">
								<td class="my-hand-area" align="center">
									<img id="card-place-1" src="../../default/modules/games/pasoor/assets/cards/52.png" class="card_place">
									<img id="card-place-2" src="../../default/modules/games/pasoor/assets/cards/52.png" class="card_place">
									<img id="card-place-3" src="../../default/modules/games/pasoor/assets/cards/52.png" class="card_place">
									<img id="card-place-4" src="../../default/modules/games/pasoor/assets/cards/52.png" class="card_place">
								</td>
							</tr>
						</table>

						<img id="card-place-500" src="../../default/modules/games/pasoor/assets/cards/52.png" class="card_place" style="position: absolute; left: 42.5%; top: -200px; transform:translate(-50%,0);">
						<img id="card-place-501" src="../../default/modules/games/pasoor/assets/cards/52.png" class="card_place" style="position: absolute; left: 42.5%; top: 50px; transform:translate(-50%,0);">

						<img id="card-place-400" src="../../default/modules/games/pasoor/assets/cards/52.png" class="card_place" style="position: absolute; left: 42.5%; bottom: -200px; transform:translate(-50%,0);">
						<img id="card-place-401" src="../../default/modules/games/pasoor/assets/cards/52.png" class="card_place" style="position: absolute; left: 42.5%; bottom: 50px; transform:translate(-50%,0);">

						<img id="card-place-53" src="../../default/modules/games/pasoor/assets/cards/52.png" class="card_normal dealing-cards" style="position: absolute; left: 0px; top: 50%; transform:translate(0,-50%);">
						<img id="card-place-52" src="../../default/modules/games/pasoor/assets/cards/52.png" class="card_normal dealing-cards" style="position: absolute; left: 5px; top: 50%; transform:translate(0,-50%);">
						<img id="card-place-51" src="../../default/modules/games/pasoor/assets/cards/52.png" class="card_normal dealing-cards" style="position: absolute; left: 10px; top: 50%; transform:translate(0,-50%);">
						<img id="card-place-50" src="../../default/modules/games/pasoor/assets/cards/52.png" class="card_normal dealing-cards" style="position: absolute; left: 15px; top: 50%; transform:translate(0,-50%);">

						<div id="temp-card-container"></div>

					</td>
					<td id="panel_container" width="22%">
						<table class="middle">
							<tr height="10%">
								<td>
									<a href="javascript:;" id="exit_button" class="exit_button clickable round_5 lang_50">ÇIKIŞ</a>
								</td>
							</tr>
							<tr height="35%">
								<td>
									<div><img id="opponent_photo" class="clickable" src="../../default/modules/games/pasoor/assets/placeholder.png" width111="50%"></div>
									<div id="opponent_name" class="yellow_font bold_font user_info_row" style="max-width: 80px; overflow: hidden;">[name]</div>
									<div id="opponent_score" class="white_font user_info_row" style="max-width: 80px; overflow: hidden;">Score: 0</div>
									<div id="opponent_club" class="white_font user_info_row" style="max-width: 80px; overflow: hidden;">Clubs: 0</div>
									<div id="opponent_soor" class="white_font user_info_row" style="max-width: 80px; overflow: hidden;">Soor: 0</div>
									<center>
										<div class="turn_holder">
											<div align="left" class="turn_container opponent_turn">
												<div class="turn_bar"></div>
											</div>
										</div>
									</center>
								</td>
							</tr>
							<tr height="10%">
								<td align="left" valign="center">
									<div style="position: absolute; width: 100%; height: 100px; top: 0px; text-align: left;"><img id="double_icon" src="../../default/modules/games/pasoor/assets/double.png" class="clickable" style="margin-left: 0px; height: 24px;position: absolute; top: 15px; left: 15px; display: none;"></div>
									<img id="sound_icon" src="../../default/modules/games/pasoor/assets/sound_0.png" class="clickable" style="height: 24px; position: absolute; top: 15px; right: 15px;">
									<div id="game_amount_place" style="font-size: 1.2vw; color: #fff; display: inline-block; background: #000; padding: 4px; border-radius: 3px;">Amount</div>
								</td>
							</tr>
							<tr height="35%">
								<td>
									<div><img id="my_photo" src="../../default/modules/games/pasoor/assets/placeholder.png" width111="50%"></div>
									<div id="my_name" class="yellow_font bold_font user_info_row" style="max-width: 80px; overflow: hidden;">[name]</div>
									<div id="mine_score" class="white_font user_info_row" style="max-width: 80px; overflow: hidden;">Score: 0</div>
									<div id="mine_club" class="white_font user_info_row" style="max-width: 80px; overflow: hidden;">Clubs: 0</div>
									<div id="mine_soor" class="white_font user_info_row" style="max-width: 80px; overflow: hidden;">Soor: 0</div>
									<center>
										<div class="turn_holder">
											<div align="left" class="turn_container my_turn">
												<div class="turn_bar"></div>
											</div>
										</div>
									</center>
								</td>
							</tr>
							<tr height="10%">
								<td>
									<a href="javascript:;" id="chat_button" class="chat_button clickable round_5 lang_51">CHAT</a>
								</td>
							</tr>
						</table>
					</td>
				</tr>
			</table>
		</div>
			<table class="portrait middle">
				<tr>
					<td>
						<img src="../../default/modules/games/pasoor/assets/landscape.png" class="landscape_icon">
						<div class="white_font lang_46">Lütfen telefonunuzu yatay konuma getirin.</div>
					</td>
				</tr>
			</table>
						
			<div class="message_alert landscape round_5">Message</div>
			
			<div class="game_result landscape clickable">
				<div align="center" class="game_result_border">
					<div class="result_for_loser"><img src="../../default/modules/games/pasoor/assets/logo.png" height="40%" style="max-height: 150px;"></div>
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

	<!-- SES DOSYALARI -->
	<audio id="sound_card" controls class="hidden"><source src="../../default/modules/games/pasoor/assets/sounds/card.mp3" type="audio/mpeg"></audio>
	<audio id="sound_get" controls class="hidden"><source src="../../default/modules/games/pasoor/assets/sounds/get.mp3" type="audio/mpeg"></audio>
<script>
		if(window.self === window.top) {
			setInterval(function() {
				$.get("/ping");
			}, 30000);
		}
	</script>
	
	
</body>
</html>