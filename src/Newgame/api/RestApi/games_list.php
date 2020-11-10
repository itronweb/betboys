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

$REST = new \lib\classes\RestService();
$REST->CorsArray = array(
    "AllowOrigin"=> array("*"),
    "MaxAge"=> 10,
    "AllowCredentials"=> true,
    "AllowMethods"=> array("OPTIONS, GET, POST"),
    "AllowHeaders"=>array("Content-Type, charset, Authorization"),
    "ContentType"=> \lib\classes\RestService::getFormat()
);
$REST->Authorization = false;
$REST->method = array("POST");

$Data = $REST->Processing();
$Data = json_decode($Data, true);

$game = new Game();

//	$user = $game->get_user_cash( $Data['uid']);
//	$game_name = ($Data['game_id'] == 5) ? 'rps' : 'roulette';
	
if ( $Data['game_id'] == 5 ){
	$game_name = 'rps';
}
	


if ( $game_name == 'rps'){
	
	
	if ( $max_chips = $game->check_user_cash( $Data['token'])){
		$waiting_list=$game->get_user_list_is_waiting($Data['token'],$Data['game_id'], $max_chips);
		
		if($waiting_list=$game->get_user_list_is_waiting($Data['token'],$Data['game_id'], $max_chips)){
		
			$REST->responseArray = array(
//-----------------for games rps and pasoor
				"command"=> "games_list",
				"games"		=> $waiting_list,
//				"games" => [
//					["amount" => 100000, "double"=> false, "name"=> "test1", "token" => 'fmbjvC2gH9sI1MsgbTu1'],
//					["amount" => 100000, "double"=> true, "name"=> "test1"],
//
//
//				]
			);
		}
		
	}
	
	
}
else{
	$REST->responseArray = array(
//-----------------for games rps and pasoor
//			"command"=> "games_list",
//			"games" => [
//				["amount" => 100000, "double"=> false, "name"=> "test1", "token" => 'fmbjvC2gH9sI1MsgbTu1'],
//				["amount" => 100000, "double"=> true, "name"=> "test1"],
//				
//				
//			]
		);
}
		



echo $REST->RseponseToC();