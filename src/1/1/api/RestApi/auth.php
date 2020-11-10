<?php
/**
 * Created by PhpStorm.
 * User: ADMIN
 * Date: 18/07/2018
 * Time: 03:20 PM
 */

require_once 'game.php';

	
function numberformat ($dig, $sep = ',', $lim = 3) {
	$dig = (string) round($dig); $str = "";
	for ($i = ($len = strlen($dig)) - 1; $i>=0; $i--) $str = (($len - $i) %$lim == 0 && $i>0 ? $sep : '') . $dig{$i} . $str;
	return $str;
}

$obj  = json_decode($_GET['data']);

$game = new Game();

$Data = [
	'uid'	=> $obj->uid,
	'token'	=> $obj->token,
	'game_id'	=> $obj->game_id,
];


$game_name= "all";

if ( $Data['game_id'] == 1 ){

$game_name = 'roulette';

}else if ( $Data['game_id'] == 2 ){

$game_name = 'baccarat';

}else if ( $Data['game_id'] == 3 ){

$game_name = 'blackjack';

}else if ( $Data['game_id'] == 4 ){

	$game_name = 'slot';

}else if ( $Data['game_id'] == 5 ){

	$game_name = 'rps';

}else if ( $Data['game_id'] == 6 ){

	$game_name = 'backgammon';

}else if ( $Data['game_id'] == 7 ){

	$game_name = 'crash';

}else if ( $Data['game_id'] == 8 ){

	$game_name = 'pasoor';

}


if ( $game->check_token($Data['uid'], $Data['token']) ){
	
	if ( $user = $game->get_user_cash( $Data['uid'])){
		
		if( $game_name == "all" || $game_name == "roulette" || $game_name == "blackjack" || $game_name == "baccarat" ){

			$return_array = array(
				"command"=>"auth",
				"uid"=> $Data['uid'],
				"chips"=> $user['user_cash'],
				"name"=> $user['user_name'],
				"photo"=>"",
				"currency"=>0,
			);


		}
		elseif( $game_name == "slot"){

				$return_array = array(
//					"command"=>"broadcast",
//					"message"=>"error1258",//har errori ke bnvisi ba in commend miyad 
					
					"command"=>"auth",
					"uid"=> $Data['uid'],
					"chips"=> $user['user_cash'],
					"name"=> $user['user_name'],
					"photo"=>"",
					"currency"=>0,
					"friends"=>5,
					"level"=>2,
				);

			}
		elseif( $game_name == "pasoor"){

				$return_array = array(
					"command"=>"auth",
					"uid"=> $Data['uid'],
					"chips"=> $user['user_cash'],
					"name"=> $user['user_name'],
					"photo"=>"",
					"currency"=>0,
					"friends"=>5,
					"level"=>2,
				);

			}
		elseif( $game_name == "rps"){

				$return_array = array(
					"command"=>"auth",
					"uid"=> $Data['uid'],
					"chips"=> $user['user_cash'],
					"name"=> $user['user_name'],
					"photo"=>"",
					"currency"=>0,
					"friends"=>5,
					"level"=>2,
					"bets"=>[1000,2000,3000,4000,5000,6000,7000,8000,9000,10000],
				);

		}
		elseif( $game_name == "backgammon"){

				$return_array = array(
					"command"=>"auth",
					"uid"=> $Data['uid'],
					"chips"=> $user['user_cash'],
					"name"=> $user['user_name'],
					"photo"=>"",
					"currency"=>0,
					"friends"=>5,
					"level"=>2,
					"bets"=>[1000,2000,3000,4000,5000,6000,7000,8000,9000,10000],
				);

		}
		elseif( $game_name == "crash"){

				$return_array = array(
					"command"=>"auth",
					"uid"=> $Data['uid'],
					"chips"=> $user['user_cash'],
					"name"=> $user['user_name'],
					"photo"=>"",
					"currency"=>0,
					"friends"=>5,
					"level"=>2,
					"bets"=>[1000,2000,3000,4000,5000,6000,7000,8000,9000,10000],
					"admin"=>"",
					"default_amount"=>180000,
				);
			}
	}
	
	
}


echo json_encode($return_array);