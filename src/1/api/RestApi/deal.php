<?php
/**
 * Created by PhpStorm.
 * User: ADMIN
 * Date: 18/07/2018
 * Time: 03:20 PM
 */

require_once 'game.php';
require_once 'lib/casino/baccarat.php';
require_once 'lib/casino/blackjack.php';

$obj  = json_decode($_GET['data']);

$Data = [
	'uid'		=> $obj->uid,
	'token'		=> $obj->token,
	'game_id'	=> $obj->game_id,
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
	
	
//	$game_name = ($Data['game_id'] == 2) ? 'baccarat' : 'blackjack';
//	
	if($game_name === 'blackjack'){
		
		$Data['amount'] = $obj->amount;
		
		$user_bet = [ 'amount'=>$Data['amount'] ];
		
		if ($game->check_this_bet_is_valid (array_sum($user_bet), $Data['uid'])){
			
			$blackjack = new Blackjack();
			
			$card = $blackjack->blackjack_random_number();
			
			$win = $blackjack->check_win_in_2_card ( $card, $user_bet );
			
			$game->register_amount_for_roulette( $Data['token'], array_sum($user_bet));
			
			$game->update_user_cash_with_token(-(array_sum($user_bet)), $Data['token']);
			$game->insert_transaction( 7 ,$Data['token'], array_sum($user_bet));
			
			if ( $win['win'] !== false ){
				$win_amount = ( isset($win['win_amount'])) ? $win['win_amount'] : $win['blackjack'];
				$game->update_user_cash_with_token($win_amount, $Data['token']);
				$game->insert_transaction( 8 ,$Data['token'], $win_amount);
			}
			else {
				if (  $card['finished'] === true )
					$game->update_affiliate_user_cash($Data['uid'],(array_sum($user_bet)),$Data['game_id']);
				
			}
			
			$user = $game->get_user_cash( $Data['uid']);
			
			$return_array = array(
				"command"		=> "deal",
				"chips"			=> $user['user_cash'],
				"bet" 			=> $Data['amount'],
				
				"user_first"	=> $card['user_first'],
				"user_second"	=> $card['user_second'],
				"dealer_first"	=> $card['dealer_first'],
				'user_score' 	=> $card['user_value'],
				'dealer_score' 	=> $card['dealer_value'],
				'double'		=> true,

				"amount"		=> ($win['win'] !== false) ? $win['win_amount'] : 0 ,
				"blackjack"		=> ($win['win'] !== false) ? $win['blackjack'] : 0 ,
				
//				"left_amount"	=> ($win['win'] !== false) ? $win['left_amount'] : 0 ,  // borde left
//				"right_amount"	=> ($win['win'] !== false) ? $win['right_amount'] : 0 , // borde right
			);
			if ( $card['finished'] === true)
				$REST->responseArray['finish'] = true;
			
			if ( $blackjack->check_split($card) )
				$REST->responseArray['split'] = true;
			
			if ( $blackjack->check_insurance($card) )
				$REST->responseArray['insurance'] = true;
		}
		
		
	}
	elseif($game_name === 'baccarat'){
		
		$user_bet = ['banker' => $obj->banker ,
					 'player' => $obj->player , 
					 'tie' 	  => $obj->tie
					];
		
		if ($game->check_this_bet_is_valid (array_sum($user_bet), $Data['uid'])){
			
			$baccarat = new Baccarat();
			
			$card = $baccarat->baccarat_random_number();
			
			$win = $baccarat->check_bets( $user_bet, $card['user_value'], $card['bank_value']);
			
//			var_dump( $win );
			$game->register_amount_for_roulette( $Data['token'], array_sum($user_bet));
			
			$game->update_user_cash_with_token(-(array_sum($user_bet)), $Data['token']);
			$game->insert_transaction( 7 ,$Data['token'], array_sum($user_bet));
			
			if ( $win != 0 ){
				$game->update_user_cash_with_token($win, $Data['token']);
				$game->insert_transaction( 8 ,$Data['token'], $win);
			}
			else {
				
				$game->update_affiliate_user_cash($Data['uid'],(array_sum($user_bet)),$Data['game_id']);
			}
			
			$user = $game->get_user_cash( $Data['uid']);

			
//			var_dump( $card );
			
			$return_array = array(
			"command"=> "deal",
			"chips"=> $user['user_cash'],
			"user_card_1"=> $card['user_card_1'],
			"user_card_2"=> $card['user_card_2'],
			"user_card_3"=> $card['user_card_3'],
				
			"banker_card_1"=> $card['bank_card_1'],
			"banker_card_2"=> $card['bank_card_2'],
			"banker_card_3"=> $card['bank_card_3'],
			"amount"=> $win,
			"winner"=> $card['winner'],
			"user_value"=> $card['user_value'],
			"bank_value"=> $card['bank_value'],
//			"user_score"=> 8,
		);
			
		}
		
		
	}

}



echo json_encode($return_array);