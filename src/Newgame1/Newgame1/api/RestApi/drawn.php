<?php
/**
 * Created by PhpStorm.
 * User: ADMIN
 * Date: 18/07/2018
 * Time: 03:20 PM
 */

require_once (__DIR__ . '/lib/classes/RestService.php');
require_once (__DIR__ . '/lib/connection/MysqliDb.php');
require_once (__DIR__ . '/lib/connection/cn.php');
require_once 'game.php';
require_once 'lib/Newgame/rps.php';

$REST = new \lib\classes\RestService();
$REST->CorsArray = array(
    "AllowOrigin"=> array("*"),
    "MaxAge"=> 10,
    "AllowCredentials"=> true,
//  "ExposeHeaders"=> array("Cache-Control", "Content-Language"),
    "AllowMethods"=> array("OPTIONS, GET, POST"),
    "AllowHeaders"=>array("Content-Type, charset, Authorization"),
    "ContentType"=> \lib\classes\RestService::getFormat()
);
$REST->Authorization = false;
$REST->method = array("POST");


$Data = $REST->Processing();
$Data = json_decode($Data, true);


$game = new Game();

	$game_name = ($Data['game_id'] == 5) ? 'rps' : 'roulette';

if ( $game_name == 'rps'){
	
	$rps = new RPS();
	
	$online_gamer_row = $game->get_my_and_op_row_with_my_token( $Data['token'] );
	
	$my = $online_gamer_row['my'];
	$op = $online_gamer_row['op'];
	$game->change_status($my['token'], 0 );
	
	if ( $op['status'] == 2 || $op['status'] == 3 )
		$game->change_status($op['token'], 11 );
	
	
}
//------------------for game rps
$REST->responseArray = array(
    "command"=>"cancelled",
	"chips"		=> 100000,
);

echo $REST->RseponseToC();