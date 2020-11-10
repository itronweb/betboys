<?php
/**
 * Created by PhpStorm.
 * User: ADMIN
 * Date: 18/07/2018
 * Time: 03:20 PM
 */

require_once 'game.php';

$obj  = json_decode($_GET['data']);

$Data = [
	'uid'		=> $obj->uid,
	'token'		=> $obj->token,
	'game_id'	=> $obj->game_id,
];

$game = new Game();


$game_name = 'all';

if ( $Data['game_id'] == 7 ){
	$game_name = 'crash';
}


$user = $game->get_user_cash( $Data['uid']);

if ( $game_name == 'crash'){
	//- ----------------- for game crash 
	$return_array = array(
				 "command"=>"cash_out",
				 "chips"=> $user['user_cash'],
			);


}
else {	
	$return_array = array(
	//    "status" => 200, "msg" => \lib\classes\RestService::$codes['200'],
		"command"=>"cash_out"
	);
}

echo json_encode($return_array);