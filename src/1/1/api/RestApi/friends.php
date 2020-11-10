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


$Data = $REST->Processing();
$Data = json_decode($Data, true);

$game = new Game();

//	$game_name = ($Data['game_id'] == 5) ? 'rps' : 'roulette';

//	if($game_name == 'rps'){
//	-------------------for game rps and passor
		$REST->responseArray = array(
			"command"=>"friends",
			"friends"=>[3512,9875,9258,1256] // fek konm bayad id friends ro inja bezari 
//			
			
		);
//	}
	
//}



echo $REST->RseponseToC();