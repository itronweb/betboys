<!DOCTYPE html>
<html>
<head>
	<?php require_once '../../api/RestApi/game.php';
	
		$game = new Game();


		$game->check_login();

        $game_id = $game->get_game_id('slot');
		$game->set_cookie('game', $game_id);
		if ( isset($_COOKIE['code']) ){
			$code = $_COOKIE['code'];
			$game->set_gamer_token( $game_id, $code );
		}
	
	?>
	<title>Slot</title>
	<meta charset="UTF-8" />
	<meta name="viewport" content="width=device-width, height=device-height, initial-scale=1.0" />
	<meta name="apple-mobile-web-app-capable" content="yes" />
	<meta name="format-detection" content="telephone=no">
	<meta name="msapplication-tap-highlight" content="no">
    <link href="../../../attachment/setting/favicon.ico" rel="shortcut icon" type="image/x-icon" />
	<link rel="stylesheet" href="../../default/modules/games/slot/css/reset.css" />
	<link rel="stylesheet" href="../../default/modules/games/slot/css/sweetalert.css" />
	<link rel="stylesheet" href="../../default/modules/games/slot/css/style.css" />
	<link rel="stylesheet" href="../../default/modules/sezar/style.css" />
	
	<script type="text/javascript">
		var DEVICE_TYPE	= 'browser';
		var MONEY_FORMAT = [0,'.',','];
		var API_URL	= 'http://136.243.255.205:3000/api/';
		var GAME_FOLDER = '../../default/modules/games/slot/';
		var HOME_URL = '/games';
		var MAIN_URL = '../../games/slot/';
		var ADDITIONAL_FILES = [];
								
	</script>
    <script type="text/javascript" src="../../default/modules/games/slot/js/jquery.js"></script>
  	<script type="text/javascript" src="../../default/modules/games/slot/js/jquery-ui.js"></script>
  	<script type="text/javascript" src="../../default/modules/games/slot/js/jquery.ui.touch-punch.min.js"></script>
    <script type="text/javascript" src="../../default/modules/games/slot/js/jquery.maskedinput.js"></script>
    <script type="text/javascript" src="../../default/modules/games/slot/js/sweetalert.min.js"></script>
    <script type="text/javascript" src="../../default/modules/games/slot/js/game.min.js?000.002"></script>

    <script>
		var lastTouchEnd = 0;
		document.documentElement.addEventListener('touchend', function (event) {
  			var now = (new Date()).getTime();
  			if(now - lastTouchEnd <= 300) event.preventDefault();
  			lastTouchEnd = now;
		}, false);
		document.addEventListener('gesturestart', function (e) {
			//e.preventDefault();
		});
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
					<td><img src="../../default/modules/games/slot/assets/logo.png?0" class="logo"></td>
				</tr>
			</table>
		</div>

		<!-- Loading screen -->
		<div id="loading_screen" class="screen hidden">
			<table class="middle">
				<tr>
					<td align="center">
						<img src="../../default/modules/games/slot/assets/loading.png" class="loading"><br />
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
							<img src="../../default/modules/games/slot/assets/main_logo.png?0" height="100">
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
					<div class="left height_100"><img src="../../default/modules/games/slot/assets/placeholder.png" class="profile_photo clickable settings_button" height="100%"></div>
					<div class="left margin_l10 clickable cashier_button">
						<div class="top_left_name">[Username]</div>
						<div class="top_left_chips">[Chips]</div>
					</div>
					<div class="clear"></div>
				</div>

				<div class="top_right_panel">
					<div class="right margin_r10 height_100"><img src="../../default/modules/games/slot/assets/home.png" height="100%" class="clickable home_button"></div>
					<div class="clear"></div>
				</div>
				
				<div class="center_panel">
					<table class="middle">
						<tr>
							<td>
								<center class="game_lobby_center">
									<img src="../../default/modules/games/slot/assets/main_logo.png?0" height="50%"><br />
									<div style="transform: translate(-50%,-50%); position: absolute; top: 75%; left: 50%;"><a href="javascript:;" id="play_button" class="clickable myButton lang_57 medium_font">Oyuna Başla</a></div>
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
						<img src="../../default/modules/games/slot/assets/landscape.png" class="landscape_icon">
						<div class="white_font lang_46">Lütfen telefonunuzu yatay konuma getirin.</div>
					</td>
				</tr>
			</table>
		</div>

		<!-- Game screen -->
		<div id="game_screen" class="screen hidden">
			<div class="landscape relative">
				<div id="canvas_container" align="center"></div>
				<div id="last_winner" class="last_winner hidden">[name] [x] kazandı</div>
			</div>
			<table class="portrait middle">
				<tr>
					<td>
						<img src="../../default/modules/games/slot/assets/landscape.png" class="landscape_icon">
						<div class="white_font lang_46">Lütfen telefonunuzu yatay konuma getirin.</div>
					</td>
				</tr>
			</table>
		</div>

	</div>

	<!-- SES DOSYALARI -->
	<audio id="sound_bone" controls class="hidden"><source src="../../default/modules/games/slot/assets/sounds/bone.ogg" type="audio/ogg"></audio>
	<audio id="sound_cant" controls class="hidden"><source src="../../default/modules/games/slot/assets/sounds/cant.ogg" type="audio/ogg"></audio>
	<audio id="sound_gamea" controls class="hidden"><source src="../../default/modules/games/slot/assets/sounds/gamea.ogg" type="audio/ogg"></audio>
	<audio id="sound_get" controls class="hidden"><source src="../../default/modules/games/slot/assets/sounds/get.ogg" type="audio/ogg"></audio>
	<audio id="sound_hohoho" controls class="hidden"><source src="../../default/modules/games/slot/assets/sounds/hohoho.ogg" type="audio/ogg"></audio>
	<audio id="sound_rolls" controls class="hidden"><source src="../../default/modules/games/slot/assets/sounds/rolls.ogg" type="audio/ogg"></audio>
	<audio id="sound_stoproll" controls class="hidden"><source src="../../default/modules/games/slot/assets/sounds/stoproll.ogg" type="audio/ogg"></audio>

	<script>
		if(window.self === window.top) {
			setInterval(function() {
				$.get("/ping");
			}, 30000);
		}
	</script>

</body>
</html>