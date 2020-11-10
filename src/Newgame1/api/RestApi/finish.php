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
//  "ExposeHeaders"=> array("Cache-Control", "Content-Language"),
    "AllowMethods"=> array("OPTIONS, GET, POST"),
    "AllowHeaders"=>array("Content-Type, charset, Authorization"),
    "ContentType"=> \lib\classes\RestService::getFormat()
);
$REST->Authorization = false;
$REST->method = array("POST");

//$re = new \lib\classes\Authentication();
//$cToken = $re->getBearerToken();
//
//$db->where("token_access", "$cToken");
//$cols= array("id");
//$db->get("user_token", null, $cols);
//
//if($db->count > 0) $REST->Authenticated = true; else $REST->Authenticated = false;

$Data = $REST->Processing();
$Data = json_decode($Data, true);

$game = new Game();

$game_name = 'all';





$game_name = ($Data['game_id'] == 5) ? 'rps' : 'roulette';
if ( $Data['game_id'] == 7 ){
	$game_name = 'crash';
}


	if($game_name == 'rps'){
//	-------------------for game rps
		$REST->responseArray = array(
			"command"=> "played",
			"selected"=> 2,
//			
			
		);
	}elseif($game_name == 'crash'){
//	-------------------for game crash
		$REST->responseArray = array(
			"command"=> "game_status",
			"cashin"=> 1800,
			"cashout"=> 200,
			"amount"=> 125000,
			"uid"=> 3805,
			"name"=> "nghv",
			"status"=>"started",
			"chats"=>['lkjkl'],
			"crashes"=>['1'],
			"time"=>100,
//			"command"=> "play",
//			"selected"=> 2,
//			
			
		);
	}
	
//}



echo $REST->RseponseToC();