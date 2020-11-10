<?php
/**
 * Created by PhpStorm.
 * User: ADMIN
 * Date: 18/07/2018
 * Time: 03:20 PM
 */

require_once 'game.php';
require_once 'lib/Newgame/blackjack.php';


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

if ( $game->check_token($Data['uid'], $Data['token']) ){
	
	$user_bet = ['amount'	=>$Data['amount'] ];
	
	
	if ($game->check_this_bet_is_valid (array_sum($user_bet), $Data['uid'])){
		
		$blackjack = new Blackjack();
		
		$dealer = $Data['dealer'];
		$user = $Data['user'];
		
		$dealer_card_number = $blackjack->convert_card_data ( $dealer );
		$user_card_number = $blackjack->convert_card_data ( $user );
		
		$dealer_value = $blackjack->get_card_value( $dealer_card_number );
		$user_value = $blackjack->get_card_value( $user_card_number );
		$dealer_test = array();
		$dealer_cards = array();
		
		while( $blackjack->check_get_dealer_card ( $user_value, $dealer_value ) ){
			
			$dealer_card = $blackjack->blackjack_get_random_card( $dealer_card_number );
			
			
			$dealer_cards[] = $dealer_card['card'];
			$dealer_card_number[] = $dealer_card['number'];
			$dealer_value = $dealer_card['value'];
			$dealer_test[] = $dealer_value;
//			var_dump($dealer_value);

			
		}
		
		
		$winner = $blackjack->check_winner ( $user_value, $dealer_value );
		
		$amount = $blackjack->check_winner_bet ( $user_bet, $winner );
		
		if ( $amount['win'] > 0 ){
			$game->update_user_cash_with_token($amount['win'], $Data['token']);
			$game->insert_transaction( 8 ,$Data['token'], $amount['win']);
		}
		else{
			$game->update_affiliate_user_cash($Data['uid'],(array_sum($user_bet)),$Data['game_id']);
		}
		
//		var_dump( $amount );
		$user = $game->get_user_cash( $Data['uid']);
		
		if ( $amount['side'] === 'user')
			$status = 'win';
		else if ( $amount['side'] === 'dealer')
			$status = 'lose';
		else if ( $amount['side'] === 0 )
			$status = 'push';
	
		$return_array = array(
			"test" => $dealer_test,
			"command"			=> "stand",
			"chips"				=> $user['user_cash'],
			"card"				=> 10,
//			"split_score"=> 2,
//			"score"				=> 15,
//			"turn"				=> 15,
//			"dealer_1"			=> $dealer_card['card'],
			"dealer_score"		=> $dealer_value,
			"user_score"		=> $user_value,
			"dealer_blackjack"	=> ($winner == 'dealer_blackjack') ? 1 : 0,
			"blackjack"			=> ($winner == 'user_blackjack') ? 1 : 0,
//			"split_blackjack"=> 1,
//			"turn" => 'split',
			"finish"			=> true,
			'status'			=> $status,// win, lose, push
			"amount"			=> $amount['win'],
//			'split_status'	=> 'win',// lose, push
		
		);
		
		$dealer_number = sizeof( $dealer_cards );
		
//		if ( $dealer_number > 2 ){
//			$REST->responseArray['turn'] = 'split';
//			$REST->responseArray["dealer_2"] = 12;
			for( $i=0; $i<$dealer_number; $i++){
				$j =$i+1;
				$return_array["dealer_$j"] = $dealer_cards[$i];
				$test[] = $dealer_card_number[$i];
			}
				
//		}
			
//		
	}
	

}

echo json_encode($return_array);