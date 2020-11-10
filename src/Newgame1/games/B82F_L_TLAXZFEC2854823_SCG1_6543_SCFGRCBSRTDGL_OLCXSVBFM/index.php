<!DOCTYPE html>
<html>
<head>
	<?php require_once '../../api/RestApi/game.php';
	
		$game = new Game();


		$game->check_login();

		$game_id = $game->get_game_id('blackjack');
		$game->set_cookie('game', $game_id);
		if ( isset($_COOKIE['code']) ){
			$code = $_COOKIE['code'];
			$game->set_gamer_token( $game_id, $code );
		}
	
	?>
	<title>Blackjack</title>
	<meta charset="UTF-8" />
	<meta name="viewport" content="width=device-width, height=device-height, initial-scale=1.0" />
	<meta name="apple-mobile-web-app-capable" content="yes" />
	<meta name="format-detection" content="telephone=no">
	<meta name="msapplication-tap-highlight" content="no">
    <link href="../../../attachment/setting/favicon.ico" rel="shortcut icon" type="image/x-icon" />
	<link rel="stylesheet" href="../../default/modules/games/blackjack/css/reset.css" />
	<link rel="stylesheet" href="../../default/modules/games/blackjack/css/sweetalert.css" />
	<link rel="stylesheet" href="../../default/modules/games/blackjack/css/style.css?000.001" />
	<link rel="stylesheet" href="../../default/modules/sezar/style.css" />
	
	<script type="text/javascript">
		var DEVICE_TYPE	= 'browser';
		var MONEY_FORMAT = [0,'.',','];
		var API_URL	= 'http://136.243.255.205:3000/api/'; 
		var GAME_FOLDER = '../../default/modules/games/blackjack/';
		var HOME_URL = '/games';
		var MAIN_URL = '../../games/blackjack/';

		var ADDITIONAL_FILES = [];
										
	</script>
    <script type="text/javascript" src="../../default/modules/games/blackjack/js/jquery.js"></script>
  	<script type="text/javascript" src="../../default/modules/games/blackjack/js/jquery-ui.js"></script>
  	<script type="text/javascript" src="../../default/modules/games/blackjack/js/jquery.ui.touch-punch.min.js"></script>
    <script type="text/javascript" src="../../default/modules/games/blackjack/js/jquery.maskedinput.js"></script>
    <script type="text/javascript" src="../../default/modules/games/blackjack/js/sweetalert.min.js"></script>
    <script type="text/javascript" src="../../default/modules/games/blackjack/js/game.min.js?000.006"></script>

    <script>
		var lastTouchEnd = 0;
		document.documentElement.addEventListener('touchend', function (event) {
  			var now = (new Date()).getTime();
  			if(now - lastTouchEnd <= 300) event.preventDefault();
  			lastTouchEnd = now;
		}, false);
	</script>

	
</head>

<body oncontextmenu="return false">

	<div class="background"></div>
	<canvas id="canvas_holder" class="background_canvas"></canvas>

	<div style="width: 100%; height: 150%; position: absolute; left: 0px; top: 0px;"></div>

	<div id="main_container" class="main_container">

		<!-- Splash screen -->
		<div id="splash_screen" class="screen">
			<table class="middle">
				<tr>
					<td><img src="../../default/modules/games/blackjack/assets/logo.png" class="logo"></td>
				</tr>
			</table>
		</div>

		<!-- Loading screen -->
		<div id="loading_screen" class="screen hidden">
			<table class="middle">
				<tr>
					<td align="center">
						<img src="../../default/modules/games/blackjack/assets/loading.png" class="loading"><br />
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
							<img src="../../default/modules/games/blackjack/assets/main_logo.png" height="100">
							<div class="margin_10 white_font lang_31">Bağlantınız Koptu!</div>
							<a href="javascript:;" class="refresh_button red_button lang_32">Tekrar bağlan</a>
						</center>
					</td>
				</tr>
			</table>
		</div>

		<!-- Lobby screen -->
		<div id="lobby_screen" class="screen hidden">
			<div class="landscape relative">

				<div class="top_left_panel">
					<div class="left height_100"><img src="../../default/modules/games/blackjack/assets/placeholder.png" class="profile_photo clickable settings_button" height="100%"></div>
					<div class="left margin_l10 clickable cashier_button">
						<div class="top_left_name">[Username]</div>
						<div class="top_left_chips">[Chips]</div>
					</div>
					<div class="clear"></div>
				</div>

				<div class="top_right_panel">
					<div class="right margin_r10 height_100"><img src="../../default/modules/games/blackjack/assets/home.png" height="100%" class="clickable home_button"></div>
					<div class="clear"></div>
				</div>
				
				<div class="center_panel">
					<table class="middle">
						<tr>
							<td>
								<center>
									<div class="rules lang_56">
										Deste Adet: 6<br />
										Insurance: 1:2<br />
										Blackjack: 3:2<br />
									</div><br />
									<a href="javascript:;" id="play_button" class="clickable myButton margin_t15 lang_57 medium_font">Oyuna Başla</a>
								</center>
							</td>
						</tr>
					</table>
				</div>

				<div class="bottom_left_panel">
				</div>

				<div class="bottom_right_panel">
				</div>
				
			</div>
			<table class="portrait middle">
				<tr>
					<td>
						<img src="../../default/modules/games/blackjack/assets/landscape.png" class="landscape_icon">
						<div class="white_font lang_46">Lütfen telefonunuzu yatay konuma getirin.</div>
					</td>
				</tr>
			</table>
		</div>

		<!-- Game screen -->
		<div id="game_screen" class="screen hidden">
			<div class="landscape relative">
			
				<div class="background_holder"><img src="../../default/modules/games/blackjack/assets/table.jpg" class="background_holder_image" style=""></div>
				<div class="background_hover"></div>

				<div class="top_left_panel">
					<div class="left height_100"><img src="../../default/modules/games/blackjack/assets/placeholder.png" class="profile_photo" height="100%"></div>
					<div class="left margin_l10">
						<div class="top_left_name">[Username]</div>
						<div class="top_left_chips">[Chips]</div>
					</div>
					<div class="clear"></div>
				</div>

				<img class="deck_place" src="../../default/modules/games/blackjack/assets/deck.png">
				<img class="deck_card hidden" src="../../default/modules/games/blackjack/assets/cards/52.png">
				
				<div id="game_area" class="game_area"></div>
				<div id="dealer_score" class="control_bet_bigger score_amount dealer_score round_5">21</div>
				<div id="user_score" class="control_bet_bigger score_amount user_score round_5">21</div>
				<div id="split_score" class="control_bet_bigger score_amount split_score round_5">21</div>

				<div id="game_result" class="control_bet_bigger game_result hidden round_5">Tebrikler [x] kazandın!</div>
				
				<div class="footer_hover"></div>
				<div id="last_winner" class="last_winner hidden">[name] [x] kazandı</div>

				<div align="center" class="control_div">
					<table border="0" cellpadding="0" cellspacing="0" class="control_table">
						<tr height="33%">
							<td align="center" class="vb">
<!--
								<div class="bet_box left_box clickable" data="left">
									<div class="control_bet box_inside bet_color_gray lang_90">perfect pair</div>
									<div class="control_bet box_inside bet_color left_amount"></div>
								</div>
-->
								&nbsp;
								<div class="bet_box center_box clickable" data="center">
									<div class="control_bet box_inside bet_color_gray lang_91">bet amount</div>
									<div class="control_bet box_inside bet_color bet_amount"></div>
								</div>
								&nbsp;
<!--
								<div class="bet_box right_box clickable" data="right">
									<div class="control_bet box_inside bet_color bet_color_gray lang_92">21+3</div>
									<div class="control_bet box_inside bet_color right_amount"></div>
								</div>
-->
							</td>
						</tr>
						<tr height="33%">
							<td align="center" class="vb">

								<img id="decrease_button" class="clickable" src="../../default/modules/games/blackjack/assets/arrwl.png" style="padding: 10px; height: calc(100% - 20px);">

								<div class="chip_holder box round_10">
									<div class="chip_button first_chip chip_yellow clickable" data="1">1</div>
									<div class="chip_space"></div>
									<div class="chip_button second_chip chip_red clickable" data="5">5</div>
									<div class="chip_space"></div>
									<div class="chip_button third_chip chip_blue clickable" data="10">10</div>
									<div class="chip_space"></div>
									<div class="chip_button fourth_chip chip_green clickable" data="25">25</div>
									<div class="chip_space"></div>
									<div class="chip_button fifth_chip chip_gray clickable" data="50">50</div>
								</div>

								<img id="increase_button" class="clickable" src="../../default/modules/games/blackjack/assets/arrwr.png" style="padding: 10px; height: calc(100% - 20px);">

							</td>
						</tr>
						<tr height="34%">
							<td align="center" class="vm">
								<div class="main_control_units">
									<div class="chip_space"></div>
									<div id="exit_button" class="clickable control_button control_red lang_75">Çıkış Yap</div>
									<div class="chip_space"></div>
									<div id="deal_button" class="clickable control_button control_green lang_76">Kart Dağıt</div>
									<div class="chip_space"></div>
									<div id="bet_reset_button" class="hidden clickable control_button control_gray lang_77">Bahsi Sıfırla</div>
									<div class="hidden chip_space"></div>
								</div>
								<div class="game_control_units hidden">
									<div class="chip_space"></div>
									
<!--									<div id="insurance_button" class="hidden clickable control_button control_yellow lang_78">Insurance</div>-->
									
									<div class="chip_space"></div>
									
									<div id="hit_button" class="clickable control_button control_green lang_79">Hit</div>
									
									<div class="chip_space"></div>
									
									<div id="stand_button" class="hidden clickable control_button control_red lang_80">Stand</div>
									
									<div class="hidden chip_space"></div>
									
<!--
									<div id="double_button" class="clickable control_button control_yellow lang_81">Double</div>
									
									<div class="hidden chip_space"></div>
									<div id="split_button" class="hidden clickable control_button control_gray lang_82">Split</div>
-->
									
									<div class="hidden chip_space"></div>
								</div>
							</td>
						</tr>
					</table>

				</div>
				
			</div>
			<table class="portrait middle">
				<tr>
					<td>
						<img src="../../default/modules/games/blackjack/assets/landscape.png" class="landscape_icon">
						<div class="white_font lang_46">Lütfen telefonunuzu yatay konuma getirin.</div>
					</td>
				</tr>
			</table>
		</div>


	</div>

	<!-- SES DOSYALARI -->
	<audio id="sound_chip" controls class="hidden"><source src="../../default/modules/games/blackjack/assets/sounds/chip.mp3" type="audio/mpeg"></audio>
	<audio id="sound_card" controls class="hidden"><source src="../../default/modules/games/blackjack/assets/sounds/card.mp3" type="audio/mpeg"></audio>
	<audio id="sound_win" controls class="hidden"><source src="../../default/modules/games/blackjack/assets/sounds/win.mp3" type="audio/mpeg"></audio>

	<script>
		if(window.self === window.top) {
			setInterval(function() {
				$.get("/ping");
			}, 30000);
		}
	</script>

</body>
</html>