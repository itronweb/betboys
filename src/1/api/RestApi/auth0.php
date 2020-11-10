<?php
/**
 * Created by PhpStorm.
 * User: ADMIN
 * Date: 18/07/2018
 * Time: 03:20 PM
 */

require_once 'game.php';

$Data = [
	'code'	=> $_GET['code'],
	'game_id'	=> $_GET['game_id'],
];

$game = new Game();

$game_name= "all";

if ( $Data['game_id'] == 1 ){

$game_name = 'roulette';

}


//$Data = $game->get_data( $Data );

$user = $game->get_user_data_with_hash_id ( $Data['code'], $Data['game_id']);

$return_array = array(
		"test"	=> $user,
			"code"=>200,
			"result"=>"ok", 
			"data"=> array(
				"ip"=>"0.0.0.0",
				"port"=>100,
				"address"=>"ws://"136.243.255.205":3000/connect/",
				"uid"=> $user['user_id'],
				"token"=> $user['token'],
				"game_id"=> $Data['game_id'],
				"bet"=> [1000],
//				"bets"=>['1K', '5K', '10K', '25K' , '50K'],
//				"bets"=>[],
				"data"=>[],
				"javascript"=>""
			),
			"auth"=> array(
				"login"=>true,
				"id"=> $user['user_id'],
				"name"=> $user['user_name'],
				"photo"=>"",
				"status"=>"active",
				"language"=>"fa",
				"amount"=> $user['user_cash'],
				"bonus"=> 0,
				"aff"=> 0,
				"channel"=>0,
				"group"=>0,
				"level"=>1,
				"verification"=>0
	));

if($game_name == "roulette"){
	
	$return_array['bets'] = [];
}
else{
	$return_array['bets'] = ['1K', '5K', '10K', '25K' , '50K'];
}


echo json_encode($return_array);