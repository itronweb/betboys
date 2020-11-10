<!DOCTYPE html>
<html>
<head>
	<?php require_once '../../api/RestApi/game.php';
	
	$game = new Game();
	
	
	$game->check_login();

	$game_id = $game->get_game_id('baccarat');
	//$game_id = 2;
	$game->set_cookie('game', $game_id);
	if ( isset($_COOKIE['code']) ){
		$code = $_COOKIE['code'];
		$game->set_gamer_token( $game_id , $code );
	}
	
	?>
	<title>Baccarat</title>
	<meta charset="UTF-8" />
	<meta name="viewport" content="width=device-width, height=device-height, initial-scale=1.0" />
	<meta name="apple-mobile-web-app-capable" content="yes" />
	<meta name="format-detection" content="telephone=no">
	<meta name="msapplication-tap-highlight" content="no">
    <link href="../../../attachment/setting/favicon.ico" rel="shortcut icon" type="image/x-icon" />
	<link rel="stylesheet" href="../../default/modules/games/baccarat/css/reset.css" />
	<link rel="stylesheet" href="../../default/modules/games/baccarat/css/sweetalert.css" />
	<link rel="stylesheet" href="../../default/modules/games/baccarat/css/style.css?000.001" />
	<link rel="stylesheet" href="../../default/modules/sezar/style.css" />
	
	<script type="text/javascript">
		var DEVICE_TYPE	= 'browser';
		var MONEY_FORMAT = [0,'.',','];
		var API_URL	= 'http://136.243.255.205:3000/api/';
		var GAME_FOLDER = '../../default/modules/games/baccarat/';
		var HOME_URL = '/games';
		var MAIN_URL = '../../games/baccarat/';
		var ADDITIONAL_FILES = [];
											</script>
    <script type="text/javascript" src="../../default/modules/games/baccarat/js/jquery.js"></script>
  	<script type="text/javascript" src="../../default/modules/games/baccarat/js/jquery-ui.js"></script>
  	<script type="text/javascript" src="../../default/modules/games/baccarat/js/jquery.ui.touch-punch.min.js"></script>
    <script type="text/javascript" src="../../default/modules/games/baccarat/js/jquery.maskedinput.js"></script>
    <script type="text/javascript" src="../../default/modules/games/baccarat/js/sweetalert.min.js"></script>
    <script type="text/javascript" src="../../default/modules/games/baccarat/js/game.min.js?0006"></script>

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
					<td><img src="../../default/modules/games/baccarat/assets/logo.png" class="logo"></td>
				</tr>
			</table>
		</div>

		<!-- Loading screen -->
		<div id="loading_screen" class="screen hidden">
			<table class="middle">
				<tr>
					<td align="center">
						<img src="../../default/modules/games/baccarat/assets/loading.png" class="loading"><br />
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
							<img src="../../default/modules/games/baccarat/assets/main_logo.png" height="100">
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
					<div class="left height_100"><img src="../../default/modules/games/baccarat/assets/placeholder.png" class="profile_photo clickable settings_button" height="100%"></div>
					<div class="left margin_l10 clickable cashier_button">
						<div class="top_left_name">[Username]</div>
						<div class="top_left_chips">[Chips]</div>
					</div>
					<div class="clear"></div>
				</div>

				<div class="top_right_panel">
					<div class="right margin_r10 height_100"><img src="../../default/modules/games/baccarat/assets/home.png" height="100%" class="clickable home_button"></div>
					<div class="clear"></div>
				</div>
				
				<div class="center_panel">
					<table class="middle">
						<tr>
							<td>
								<center>
									<div class="rules lang_56">
										Tie Pays 9 for 1<br />
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
						<img src="../../default/modules/games/baccarat/assets/landscape.png" class="landscape_icon">
						<div class="white_font lang_46">Lütfen telefonunuzu yatay konuma getirin.</div>
					</td>
				</tr>
			</table>
		</div>

		<!-- Game screen -->
		<div id="game_screen" class="screen hidden">
			<div class="landscape relative">
			
				<div class="background_holder"><img src="../../default/modules/games/baccarat/assets/table3.jpg" class="background_holder_image" style=""></div>
				<div class="background_hover"></div>

				<div class="top_left_panel">
					<div class="left height_100"><img src="../../default/modules/games/baccarat/assets/placeholder.png" class="profile_photo" height="100%"></div>
					<div class="left margin_l10">
						<div class="top_left_name">[Username]</div>
						<div class="top_left_chips">[Chips]</div>
					</div>
					<div class="clear"></div>
				</div>

				<img class="deck_place" src="../../default/modules/games/baccarat/assets/deck.png">
				<img class="deck_card hidden" src="../../default/modules/games/baccarat/assets/cards/52.png">
				
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
								<div class="bet_box left_box clickable" data="left">
									<div class="control_bet box_inside bet_color_gray lang_90">Tie</div>
									<div class="control_bet box_inside bet_color left_amount"></div>
								</div>
								&nbsp;
								<div class="bet_box right_box clickable" data="right">
									<div class="control_bet box_inside bet_color bet_color_gray lang_92">Banker</div>
									<div class="control_bet box_inside bet_color right_amount"></div>
								</div>
								&nbsp;
								<div class="bet_box center_box clickable" data="center">
									<div class="control_bet box_inside bet_color_gray lang_91">Player</div>
									<div class="control_bet box_inside bet_color bet_amount"></div>
								</div>
							</td>
						</tr>
						<tr height="33%">
							<td align="center" class="vb">

								<img id="decrease_button" class="clickable" src="../../default/modules/games/baccarat/assets/arrwl.png" style="padding: 10px; height: calc(100% - 20px);">

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

								<img id="increase_button" class="clickable" src="../../default/modules/games/baccarat/assets/arrwr.png" style="padding: 10px; height: calc(100% - 20px);">

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
							</td>
						</tr>
					</table>

				</div>
				
			</div>
			<table class="portrait middle">
				<tr>
					<td>
						<img src="../../default/modules/games/baccarat/assets/landscape.png" class="landscape_icon">
						<div class="white_font lang_46">Lütfen telefonunuzu yatay konuma getirin.</div>
					</td>
				</tr>
			</table>
		</div>


	</div>

	<!-- SES DOSYALARI -->
	<audio id="sound_chip" controls class="hidden"><source src="../../default/modules/games/baccarat/assets/sounds/chip.mp3" type="audio/mpeg"></audio>
	<audio id="sound_card" controls class="hidden"><source src="../../default/modules/games/baccarat/assets/sounds/card.mp3" type="audio/mpeg"></audio>
	<audio id="sound_win" controls class="hidden"><source src="../../default/modules/games/baccarat/assets/sounds/win.mp3" type="audio/mpeg"></audio>

	<script>
		if(window.self === window.top) {
			setInterval(function() {
				$.get("/ping");
			}, 30000);
		}
	</script>

</body>
</html>