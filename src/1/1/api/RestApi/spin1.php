<?php
/**
 * Created by PhpStorm.
 * User: ADMIN
 * Date: 18/07/2018
 * Time: 03:20 PM
 */

require_once 'game.php';
require_once 'lib/casino/roulette.php';
require_once 'lib/casino/slot.php';


$obj  = json_decode($_GET['data']);
$game = new Game();

$Data = [
	'uid'		=> $obj->uid,
	'token'		=> $obj->token,
	'game_id'	=> $obj->game_id,
];


if ( $Data['game_id'] == 1 ){
	$game_name = 'roulette';
}
elseif ( $Data['game_id'] == 4 ){
	$game_name = 'slot';
}
else if ( $Data['game_id'] == 5 ){
	$game_name = 'rsp';
}
else if ( $Data['game_id'] == 6 ){
	$game_name = 'pasoor';
}
else if ( $Data['game_id'] == 7 ){
	$game_name = 'crash';
}
	

$not_enough = false;

if ( $game->check_token($Data['uid'], $Data['token']) ){
	
	$user = $game->get_user_cash( $Data['uid']);
	
	
	if($game_name === 'roulette' ){ 
		
		$roulette = new Roulette();
	//	

		$Data['bets'] = $obj->bets;
		
		$win_number = $roulette->roulette_radom_number();
		$bets = $roulette->check_bets( $Data['bets'], $win_number);

		if ( $game->check_this_bet_is_valid ($bets['all_bets'], $Data['uid']) ){
			
			$user_cash = $game->get_user_cash( $Data['uid']);

			$game->register_amount_for_roulette( $Data['token'], $bets['all_bets']);

			$game->update_user_cash_with_token(-$bets['all_bets'], $Data['token']);
			$game->insert_transaction( 7 ,$Data['token'], $bets['all_bets']);

			if ( $bets['win_bets'] > 0 ){

				$game->update_user_cash_with_token($bets['win_bets'], $Data['token']);
				$game->insert_transaction( 8 ,$Data['token'], $bets['win_bets']);
			}

			

			if ( $user = $game->get_user_cash( $Data['uid'])){

				$return_array = array(
					"command"=> "spin",
					"chips"=> $user['user_cash'],
					"number"=> $win_number,
					"amount" => $bets['win_bets'],
				);


			}
			

		}
		else {
			$not_enough = true;
		}
	
	
	}
	elseif($game_name === 'slot'){

		$slot = new Slot();
		
		$Data['amount'] = $obj->amount;
		$Data['line'] = $obj->line;
		
		$bets = $Data['amount'] * $Data['line'];

        $old_data = $game->get_slot_game_data( $Data['token'] );

        $free_game = $slot->check_free_game( $old_data) ? true : false;
		if ( $game->check_this_bet_is_valid_with_token( $bets, $Data['token']) || $free_game === true ) {

			$user_cash = $game->get_user_cash_with_token( $Data['token']);
			
			$game->register_amount_in_online_gamer( $Data['token'], $bets);
			
			$slot_number = $slot->get_rondom_number();

            $line = ( $free_game === true ) ? 5 : $Data['line'];

			$win = $slot->check_slot_number( $slot_number, $Data['amount'], $line );

            $game_data = $slot->set_game_data( $win, $old_data );

            $game->insert_slot_game_data( $Data['token'], $game_data );
            $game_data = $game->get_slot_game_data( $Data['token'] );

            if ( $free_game === false ){
                $game->update_user_cash_with_token(-$bets, $Data['token']);
			    $game->insert_transaction( 7 ,$Data['token'], $bets);
            }
            else if ( $free_game === true ){
                $game->insert_transaction( 92 ,$Data['token'], $bets);
            }
//
			
			
			// check knm user borde
			// if win ? register transaction :  ;
			if ( $win['win'] > 0 ){
				
				$game->update_user_cash_with_token($win['win'], $Data['token']);
				$game->insert_transaction( 8 ,$Data['token'], $win['win']);
			}

            $user = $game->get_user_cash_with_token( $Data['token']);

			$return_array = array(
				"command"	=> "spin",
//				"win_id"	=> 3,
//                "win"       => [ 0 => ['line'=>1], 1 => ['line'=>2],],
                "win"       => empty( $win['win_line']) ? [] :  $win['win_line'],


//				"win"		=> [],
				"bonus"		=> ( $win['free'] == 0 ) ? 0 : 1,
				"free"		=> $game_data->free,
//				"jackpot"	=> 10000,
				"amount"	=>  $win['win'],
				"chips"     => $user['user_cash'],
				"sound"     => [
					"pause" =>3, 
				],
				"first_line"	=> $slot_number['first_line'],
				"second_line" 	=> $slot_number['second_line'],
				"third_line" 	=> $slot_number['third_line'],
			);
			
		}
		else {
			$not_enough = true;
		}
		
		
	}
	
	if ( $not_enough === true ){
		$return_array = array(
					"message" => "not_enough_chips", 
					"command" => "error",
				);
	}
	

}
else {
	
	$return_array = array(
				"message" => "expire time", 
				"command" => "error",
			);

	
}

echo json_encode($return_array);