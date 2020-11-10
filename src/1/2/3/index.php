<?php


  error_reporting(0);
  ini_set('display_errors',0);
  define('ROOT_PATH', $_SERVER['DOCUMENT_ROOT']);
  require_once '../../../1/api/RestApi/game.php';
  $game = new Game();
  $game->check_login();
  $db = new DB();
  $_SESSION['user_id'] = (int) $_COOKIE["uid"];
  $user_id = $game->check_uid();
  $admini = $db->select('admin', '*', 'portalid', $_SESSION['user_id']);
  setcookie('uid', $_SESSION['user_id']);
  if(isset($_SESSION['user_id'])){
    $game->set_cookie('uid', $user_id);
  }
  $game_id = $game->get_game_id('crash');
  $game->set_cookie('language', "pe");
  function numberformat($dig, $sep = ',', $lim = 3){
    $dig = (string) round($dig);
    $str = "";
    for($i = ($len = strlen($dig)) - 1; $i >= 0; $i--) $str = (($len - $i) % $lim == 0 && $i > 0? $sep: '') . $dig{$i} . $str;
    return $str;
  }

  $Sezar_CHIPS = 'http://betboys.xyz/admincp/includes/SezarFunctions/SZR_Udata.php?id=' . $_SESSION['user_id'];
  $get_chips = 'Balance ' . numberformat(file_get_contents($Sezar_CHIPS)) . ' TRX ';
  $get_cash = file_get_contents($Sezar_CHIPS);
  if(empty($get_cash)){
  header("Location: /payment/credit");
   
  }
  // if ( empty($user_id) ){
  // header("Location: / ");
  // }
  //
  /*
  if($_SESSION['language'] == "pe")
  {
    $game->set_cookie('language', "pe");
  }
  else
  {
    $game->set_cookie('language', "en");
  }

  if ( isset($_COOKIE['code']) ){
    $code = $_COOKIE['code'];
    $game->set_gamer_token( $game_id, $code );
  }
  */
?>
<html lang="en">
<head>
  <title>Crash</title>
  <meta charset="UTF-8"/>
  <meta name="viewport" content="width=device-width, height=device-height, initial-scale=1.0, maximum-scale=1.0, user-scalable=0"/>
  <meta name="apple-mobile-web-app-capable" content="yes"/>
  <meta name="format-detection" content="telephone=no">
  <meta name="msapplication-tap-highlight" content="no">
  <link href="/favicon.ico" rel="shortcut icon" type="image/x-icon"/>
  <link rel="shortcut icon" href="/1/default/modules/games/crash/assets/favicon.png" type="image/x-icon"/>
  <link rel="stylesheet" href="/1/default/assets/css/reset.css"/>
  <link rel="stylesheet" href="/1/default/assets/css/rangeslider.css"/>
  <link rel="stylesheet" href="/1/default/assets/css/sweetalert.css"/>
  <link rel="stylesheet" href="/1/default/modules/games/crash/css/style.css?000.002"/>

  <script type="text/javascript">
    var DEVICE_TYPE = 'browser';
    var MONEY_FORMAT = [0, '.', ','];
    var API_URL = 'http://136.243.255.205:3000/api/';
    var API_URL = '/1/api/';
    var GAME_FOLDER = '/1/default/modules/games/crash/';
    var HOME_URL = '/games';
    var MAIN_URL = '/';

    var ADDITIONAL_FILES = [];

  </script>
  <script type="text/javascript" src="/1/default/assets/js/no-rightclick.js"></script>

  <script type="text/javascript" src="/1/default/assets/js/jquery.js"></script>
  <script type="text/javascript" src="/1/default/assets/js/rangeslider.min.js"></script>
  <script type="text/javascript" src="/1/default/assets/js/sweetalert.min.js"></script>
  <script type="text/javascript" src="/1/default/modules/games/crash/js/game.min.js?v=<?php echo gmdate("YmdHis"); ?>"></script>

</head>
<body class="body-tag" oncontextmenu1="return false">

<div class="background"></div>

<div id="main_container" class="main_container">

  <!-- Splash screen -->
  <div id="splash_screen" class="screen">
    <table class="middle">
      <tr>
        <td>
          <img src="/1/default/modules/games/crash/assets/logo.png" class="logo">
        </td>
      </tr>
    </table>
  </div>

  <!-- Loading screen -->
  <div id="loading_screen" class="screen hidden">
    <table class="middle">
      <tr>
        <td align="center">
          <img src="/1/default/modules/games/crash/assets/loading.png" class="loading"><br/>
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
            <img src="/1/default/modules/games/crash/assets/logo.png" height="100">
            <div class="margin_10 lang_31">Bağlantınız Koptu!</div>
            <a href="javascript:;" class="refresh_button red_button lang_32">Tekrar bağlan</a>
          </center>
        </td>
      </tr>
    </table>
  </div>
  <!-- Lobby screen -->
  <div id="lobby_screen" class="screen hidden">
    <div class="top_left_panel">
      <div class="left height_100">
       <img src="/1/default/modules/games/crash/assets/placeholder.png" class="profile_photo clickable settings_button" height="100%">
				
      					<div class="left margin_l10 clickable cashier_button">

	  </div> </div>
      <div class="left margin_l10 clickable cashier_button">
        <div class="top_left_name user-name">[Username]</div>
        <div class="top_left_chips"><?= $get_chips ?></div>
      </div>
      <div class="clear"></div>
    </div>

    <div class="top_right_panel">
      <div class="right margin_r10 height_100">
        <img src="/1/default/modules/games/crash/assets/home.png" height="100%" class="clickable home_button">
      </div>
      <div class="clear"></div>
    </div>

    <div class="center_panel">
      <table class="middle">
        <tr>
          <td>
            <center>
              <a href="javascript:;" id="play_button" class="clickable myButton margin_t15 lang_57 medium_font">Oyuna Başla</a><br><br>
              <div class="rules">
                <a href="/Sezar-Modules/post.php?link=crash-rules" target="_blank" class="lang_60">How to play?</a>
              </div>
              <br>
              <div class="rules-fair">
                <a href="/Sezar-Modules/post.php?link=crash-fair" class="lang_61" target="_blank">Is this game fair?</a>
              </div>
            </center>
          </td>
        </tr>
      </table>
    </div>


    <div class="bottom_left_panel"></div>

    <div class="bottom_right_panel"></div>

  </div>

  <!-- Game screen -->
  <div id="game_screen" class="screen hidden" style="background: #fff;">

    <div class="top-bar">
      <div class="top-link user-name" style="float: left; margin-left: 16px;">UserName</div>
      <div class="top-link">
        <a href="javascript:;" class="cashout-button active lang_62">Exit</a>
      </div>
      <div class="top-link " id="SZRUID">
        <div id="UCASH"><?= $get_chips ?></div>
      </div>
      <div class="clear"></div>
    </div>
    <div class="left-container">
      <div class="game-graph">
     
      <div id="graph-container" class="graph-container"> 
          <canvas id="graph"></canvas>
        </div>
       <div class="desktop"  style="margin-left:70px;position:absolute;z-index:10px;left:0px;top:60px;">
	  <h3 class="noselect" style="font-family:tahoma;">Top Winner</h3>
	  <p class="noselect" style="color:#228B22;font-size:14px;font-family:tahoma;">842,540,000 20.25@ TRX @sanitiego</p>
    <p class="noselect" style="color:#8B0000;font-size:14px;font-family:tahoma;">533,180,000 18.08@ TRX @Philip</p>
    <p class="noselect" style="color:#1E90FF;font-size:14px;font-family:tahoma;">289,230,000 16.18@ TRX @andy</p>

	  </div>
    <div class="clear mobile"  style="margin-left:40px;position:absolute;z-index:10px;left:0px;top:0px;">
	  <h3 class="noselect" style="font-family:tahoma;">Top Winner</h3>
	  <p class="noselect" style="color:#228B22	;font-size:12px;font-family:tahoma;">842,540,000 20.25@ TRX @sanitiego</p>
    <p class="noselect" style="color:#8B0000;font-size:12px;font-family:tahoma;">533,180,000 18.08@ TRX @Philip</p>
    <p class="noselect" style="color:#1E90FF;font-size:12px;font-family:tahoma;">289,230,000 16.18@ TRX @andy</p>

	  </div>
      </div>
      <div class="clear mobile"></div>
      <div class="game-controls">
        <div class="desktop">
          <div class="tab-link lang_63">BET</div>
          <div class="clear"></div>
        </div>
        <div class="bet-widget">
          <div class="title lang_64">Amount</div>
          <div class="text-box game-amount-box">
            <div class="box-1">
              <input type="text" class="game-amount" value="1,000">
              <a href="javascript:;" class="make-double" style="display: block; cursor: pointer; position: relative; float: right; margin-right: 5px; margin-top: -24px; width: 26px; height: 20px; line-height: 20px; border-radius: 10px; text-align: center; background: #ddd; color: #666; font-size: 10px; text-decoration: none;">2X</a>
              <div class="clear"></div>
            </div>
            <div class="box-1-text lang_80">tomen</div>
            <div class="clear"></div>
          </div>
          <div class="title lang_65">Auto Cash Out</div>
          <div class="text-box cashout-amount-box">
            <div class="box-2" style="width: 80px;">
              <input type="text" class="cashout-amount" value="4.00">
            </div>
            <div class="box-2-text" style="width: calc(100% - 80px); background: #fff;">
              <div style="padding-top: 10px; padding-left: 5px; padding-right: 5px;">
                <input type="range" class="range-slide" value="2" min="1.01" max="20" step="0.01"/>
              </div>
            </div>
            <div class="clear"></div>
          </div>
          <a href="javascript:;" class="place-bet lang_66">Place Bet</a>
          <?php if($admini){ ?>
            <a href="javascript:;" style="font-weight: 500;display: inline-block;line-height: 150%;padding: 10px 0px;border-radius: 10px;background: #e58929;margin-top: 20px;font-size: 22px;color: #fff;text-decoration: none;border-bottom: 3px solid #383838;width: 90%;white-space: nowrap;overflow: auto;text-overflow: ellipsis;" class="bustNow">Bust Now</a>
          <?php } ?>
          <span class="mobile"><br><br></span>
        </div>
      </div>
      <div class="clear"></div>

      <div class="game-bottom">
        <div class="tab-container desktop">
          <a href="javascript:;" class="history-button tab-div-2 tab-active">
            <div class="tab-inner-div">
			
              <span class="lang_67">HISTROY</span>
            </div>
          </a>
          <a href="javascript:;" class="chat-button tab-div-2">
            <div class="tab-inner-div">
			
              <span class="lang_68">CHAT</span>
			  
            </div>
          </a>
        </div>
		
        <div class="tab-container mobile">
          <a href="javascript:;" class="history-button tab-div-3 tab-active">
            <div class="tab-inner-div">
              <span class="lang_67">HISTROY</span>
            </div>
          </a>
          <a href="javascript:;" class="chat-button tab-div-3">
		  
            <div class="tab-inner-div">
			<div class="crash-game-notification" style="margin: 5px; font-size: 12px; position: absolute; width: calc(50% - 10px); background: rgba(255,0,0,0.8); border: 1px solid #000; color: #fff;">
                                <div style="float: left; width: calc(20% - 30px); padding: 5px; line-height: 150%;">                                 </div>
                                <div class="crash-game-notification-close" style="color: #000; float: left; width: 30px; padding: 10px; font-weight: bold; cursor: pointer;">X</div>
                            </div>
              <span class="lang_68">CHAT</span>
			  
            </div>
          </a>
          <a href="javascript:;" class="players-button tab-div-3">
            <div class="tab-inner-div">
              <span class="lang_69">PLAYERS</span>
            </div>
          </a>
        </div>
        <div class="other-panels">
          <div class="user-container hidden">
            <div class="table-header">
              <div class="col col-1 lang_70">USER</div>
              <div class="col col-2">@</div>
              <div class="col col-3 lang_71">BET</div>
              <div class="col col-5 lang_72">PROFIT</div>
              <div class="clear"></div>
            </div>
            <div class="table-body users-list-container">

            </div>
            <div class="table-footer">
              <div class="left yellow-bar"></div>
              <div class="left green-bar"></div>
              <div class="left red-bar"></div>
              <div class="clear"></div>
            </div>
          </div>
		  
          <div class="history-container">
		  
            <div class="table-header">
			
              <div class="col h-col-1 lang_73">CRASH</div>
              <div class="col h-col-2">@</div>

              <div  class="col h-col-3 lang_71">BET</div>
              <div class="col h-col-4 lang_72">PROFIT</div>
              <div class="col h-col-5 lang_74">MD5</div>
              <div class="col h-col-6 lang_75">HASH</div>
              <div class="clear"></div>
            </div>
            <div class="table-body">

            </div>
            <div class="table-footer">
              <div class="left yellow-bar"></div>
              <div class="left green-bar"></div>
              <div class="left red-bar"></div>
              <div class="clear"></div>
            </div>
          </div>
        </div>
        <div class="chat-panel hidden">
		<div class="crash-game-notification" style="margin-left: 46px; font-size: 12px; position: absolute; width: calc(100% - 10px); background: rgba(255,0,0,0.8); border: 1px solid #000; color: #fff;width:320px;">
                                <div style="float: left; width: calc(100% - 30px); padding: 5px; line-height: 150%;">                                                   </div>
                                <div class="crash-game-notification-close" style="color: #000; float: left; width: 30px; padding: 10px; font-weight: bold; cursor: pointer;">X</div>
                            </div>
							
          <div class="container">
            <div class="chat-container">

            </div>
          </div>
          <div class="chat-text">
            <form method="post" action="#null" onSubmit="$('.chat-send').click(); return false;">
              <input type="text" class="chat-input" id="chat-input-text" value=""><input type="submit" value="" style="display: none;">
              <a href="javascript:;" class="chat-send"><img src="/1/default/modules/games/crash/assets/send.png?" height="24"></a>
            </form>
          </div>
        </div>
        <div class="clear"></div>
      </div>
    </div>
    <div class="right-container desktop">
      <div class="table-header">
        <div class="col col-1 lang_70">USER</div>
        <div class="col col-2">@</div>
        <div class="col col-3 lang_71">BET</div>
        <div class="col col-5 lang_72">PROFIT</div>
        <div class="clear"></div>
      </div>
      <div class="table-body users-list-container">

      </div>
      <div class="table-footer">
	  
        <div class="left red-bar status-red-bar"></div>
        <div class="left yellow-bar status-yellow-bar"></div>
        <div class="left green-bar status-green-bar"></div>
        <div class="clear"></div>
      </div>
    </div>
    <div class="clear"></div>

  </div>


</div>

<script>
  // $(document).ready(function () {
  // $(".chat-panel").addClass('hidden');
  // }
  setInterval("SZRUID()", 1000);

  function SZRUID() {
    $('#SZRUID').load(location.href + ' #UCASH');
  }

  if (window.self === window.top) {
    setInterval(function () {
      $.get("/ping");
    }, 30000);
  }

</script>

</body>
</html>