<?php
/**
 * Created by PhpStorm.
 * User: ADMIN
 * Date: 18/07/2018
 * Time: 03:20 PM
 */

require_once 'game.php';

$obj  = json_decode($_GET['data']);
$game = new Game();


$Data = [
	'uid'	=> $obj->uid,
	'token'	=> $obj->token,
	'game_id'	=> $obj->game_id,
];


$min_bet = 100;

$game_name = 'all';


if ( $Data['game_id'] == 1 ){

$game_name = 'roulette';

}else if ( $Data['game_id'] == 2 ){

$game_name = 'baccarat';

}else if ( $Data['game_id'] == 3 ){

$game_name = 'blackjack';

}else if ( $Data['game_id'] == 4 ){

	$game_name = 'slot';

}else if ( $Data['game_id'] == 7 ){

$game_name = 'crash';
}else if ( $Data['game_id'] == 8 ){

	$game_name = 'backgammon';

}else if ( $Data['game_id'] == 9 ){

	$game_name = 'rps';
	}
$user = $game->get_user_cash( $Data['uid']);

if ( $game->check_token($Data['uid'], $Data['token']) ){
	
     	
	
	
	if ( $user['user_cash'] > $min_bet ){
		if ( $game_name == 'crash'){
			$return_array = array(
				"command"=>"game_status",
				"players"=>[
					['amount'=> 120000 ,'uid'=> '3805' ,'push'=> '32' ,'sort'=> '1' ],
					['amount'=> 132000 ,'uid'=> '3654' ,'push'=> '65' ,'sort'=> '2' ],
					['amount'=> 185000 ,'uid'=> '2587' ,'push'=> '09' ,'sort'=> '4' ],
				 ],
				"md5"=>['eddb8cd3bfee6569eca07dec15631e87'],
				"test"=>$Data['game_id'],
			"status"=>"started",
//				"status"=>"busted", //vaghti ke shart baste shod 
				"status"=>"waiting",
				"chats"=>['lkjkl'],
				"crashes"=>['1'],
				"time"=>100,
			);

		}
	elseif( $game_name == "backgammon"){

				$return_array = array(
					"command"=>"auth",
					"uid"=> $Data['uid'],
					"chips"=> $user['user_cash'],
					"name"=> $user['user_name'],
					"photo"=>$Insta['Sezar_Avatar'],
					"currency"=>0,
					"friends"=>5,
					"level"=>2,
					"bets"=>[1000,2000,3000,4000,5000,6000,7000,8000,9000,10000],
				);

		}
		elseif( $game_name == 'slot') {
			$return_array = array(
				"command"   => "cash_in",
				"jackpot"   => 0,
				"chips"     => $user['user_cash'],

			);
			
		}
		elseif( $game_name == 'blackjack') {
			$return_array = array(
				"command"=>"cash_in",
				"chips"=>$user['user_cash'],

			);
			
		}
		elseif( $game_name == 'baccarat') {
			$return_array = array(
				"command"=>"cash_in",
				"chips"=>$user['user_cash'],

			);
			
		}
		elseif( $game_name == 'roulette') {
			
			$return_array = array(
				"command"=>"cash_in",
				"chips"=>$user['user_cash'],
//				"hot"=>[ 1 => ['percent'=>20],2=> ['percent'=>20],3,4,5],

			);
			
		}
		
		
	}
	else{
		$return_array = array(
			"message" => "not_enough_chips", 
			"command" => "error",
		);
	}
}


echo json_encode($return_array);