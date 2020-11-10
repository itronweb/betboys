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

	$game_name = ($Data['game_id'] == 5) ? 'rps' : 'roulette';

	if($game_name == 'rps'){
//	-------------------for game rps
		$REST->responseArray = array(
			"command"=> "double_accept",
//			"double_offered"=> 1,
			"double_level"=> 1,
			"amount"=> 20000,
//			
			
		);
	}
	
//}



echo $REST->RseponseToC();