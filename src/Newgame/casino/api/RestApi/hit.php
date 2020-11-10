<?php
/**
 * Created by PhpStorm.
 * User: ADMIN
 * Date: 18/07/2018
 * Time: 03:20 PM
 */

require_once 'game.php';
require_once 'lib/casino/blackjack.php';

$obj  = json_decode($_GET['data']);

$Data = [
	'uid'		=> $obj->uid,
	'token'		=> $obj->token,
	'game_id'	=> $obj->game_id,
	'amount'	=> $obj->game_data->bet,
	'dealer'	=> $obj->game_data->dealer,
	'user'		=> $obj->game_data->user,
];

$game = new Game();

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
	
	if($game_name === 'blackjack'){
		
		$user_bet = ['amount'	=> $Data['amount'] ];
		
		if ($game->check_this_bet_is_valid (array_sum($user_bet), $Data['uid'])){
			
			$blackjack = new Blackjack();
			
			$dealer = $Data['dealer'];
			$user = $Data['user'];
			
			$dealer_card_number = $blackjack->convert_card_data ( $dealer );
			$user_card_number = $blackjack->convert_card_data ( $user );
			
			$dealer_value = $blackjack->get_card_value( $dealer_card_number );
			$user_value = $blackjack->get_card_value( $user_card_number );
			
			
			$user_card = $blackjack->blackjack_get_random_card( $user_card_number );

//			$user_card = $blackjack->blackjack_get_random_card();
//			
			
			$dealer_value = $blackjack->get_card_value( $dealer_card_number );
			$user_value = $blackjack->get_card_value( array_merge($user_card_number, [$user_card['number']]));
			
			
			$winner = $blackjack->check_winner ( $user_value, $dealer_value );
		
			$amount = $blackjack->check_winner_bet ( $user_bet, $winner );
			
			
			
			$user = $game->get_user_cash( $Data['uid']);
			
			$return_array = array(
				"command"=> "hit",
				"chips"=> $user['user_cash'],
				"card"=> $user_card['card'],
//				"split_score"=> 2,
				"score"=> $user_card['value'],
//				"dealer_1"=> 12,
//				"dealer_1"=> 12,
			);
			
			if ( $blackjack->check_game_continue( $user_card['value'], $dealer_value ) != 'continue'){
				
				if ( $amount['win'] > 0 ){
					$game->update_user_cash_with_token($amount['win'], $Data['token']);
					$game->insert_transaction( 8 ,$Data['token'], $amount['win']);
				}
				else{
					$game->update_affiliate_user_cash($Data['uid'],(array_sum($user_bet)),$Data['game_id']);	
				}
				
				
				if ( $amount['side'] === 'user')
					$status = 'win';
				else if ( $amount['side'] === 'dealer')
					$status = 'lose';
				else if ( $amount['side'] === 0 )
					$status = 'push';
				
				
				$return_array['finish'] = true;
				$return_array['winner'] = true;
//				$return_array['dealer_blackjack'] =($winner == 'dealer_blackjack') ? 1 : 0;
//				$return_array['blackjack'] =($winner == 'user_blackjack') ? 1 : 0;
				$return_array['status'] = $status;
				$return_array['amount'] = $amount['win'];
				
			}
				
			
		}
		
		
	}
	elseif($game_name === 'baccarat'){
		$return_array = array(
			"command"=> "hit",
			"chips"=> $user['user_cash'],
			"user_card_1"=> 5,
			"user_card_2"=> 26,
			"user_card_3"=> 26,
			"banker_card_1"=>6,
			"banker_card_2"=> 26,
			"banker_card_3"=> 26,
			"amount"=> 0,
			"winner"=> 2,
	//		"user_score"=> 8,
		);
	}

}

echo json_encode($return_array);