<?php 
//define('ROOT_PATH',$_SERVER['DOCUMENT_ROOT']);

//require_once ROOT_PATH . '/4ubets/application/config/db.php';

class Blackjack {
	
	
	public function __construct(){
		
		
	}
	
	public function get_random_card ( $first=0 , $second=51 ){
		return mt_rand($first, $second);
	}
	
	public function change_number_to_card ( $number ){
		$div = $number%13 ;
		if ( 8<= $div && $div <=11)
			return 10;
		return ($number%13) + 2;
	}
	
	public function get_card_value( $arr ){
		$sum = 0;
		foreach ( $arr as $value ){
			if ($value<=10){
				$sum += $value;
			}
			else if ( $value == 14 ){
				$sum += ( $sum + 11 > 21 ) ? 1 : 11;
			}
		}
		
		return $sum;
	}
	
	public function game_winner ( $user, $bank ){
		if ( $user > $bank)
			return 1;
		else if ( $user < $bank )
			return 2;
		else
			return 0;
	}
	
	public function get_value_of_card( $value, $new_card = 0 ){
		$card_value = array();
		foreach ( $value as $item ){
			$card = $item['card_no'];
			
			if ( $card != 52 ){
				$card_number[] = $this->change_number_to_card( $card );
			}
		}
		if ( $new_card != 0 )
			$card_number[] = $new_card;
		
		return $this->get_card_value($card_number);
	}
	
	public function convert_card_data ( $value ){
		$card_number = array();
		foreach ( $value as $item ){
			$card = $item->card_no;
			
			if ( $card != 52 ){
				$card_number[] = $this->change_number_to_card( $card );
			}
		}
		
		return $card_number;
	}
	
	public function blackjack_get_random_card( $value = null ){
		$new_card = $this->get_random_card();
		$new_card_number = $this->change_number_to_card( $new_card );
		$value[] = $new_card_number;
		$new_card_value = $this->get_card_value( $value );
		
		return ['card' 		=> $new_card, 
				'number' 	=> $new_card_number,
			    'value'		=> $new_card_value
			   ];
		
	}
	
	public function check_get_dealer_card( $user, $dealer ){
		
		if ( $user < $dealer || $dealer >= 17 )
			return false;
		else if ( $dealer < 17 )
			return true;
	}
	
	public function blackjack_random_number(){
		
		$user_first = $this->get_random_card();
		$user_second = $this->get_random_card();
		$dealer_first = $this->get_random_card();
		
//		$user_first = 2;
//		$user_second = 21;
//		$dealer_first = 12;
		
		$user_1 = $this->change_number_to_card( $user_first );
		$user_2 = $this->change_number_to_card( $user_second );
		$dealer_1 = $this->change_number_to_card( $dealer_first );
		
		
		$user_card_value = $this->get_card_value( array($user_1, $user_2) );
		$dealer_card_value = $this->get_card_value( array($dealer_1) );
		
		$winner = false;
		$finished = false;
		if ( $user_card_value == 21 ){
			$winner = true;
			$finished = true;
		}
		else if ( $user_card_value > 21 ){
			$winner = false;
			$finished = true;
		}
		
		return ['user_first' 	=> $user_first, 
				'user_second'	=> $user_second,
				'dealer_first'	=> $dealer_first, 
				'winner' 		=> $winner,
				'finished'		=> $finished,
				'user_value'	=> $user_card_value,
				'dealer_value'	=> $dealer_card_value,
			   ];
	
	}
	
	public function check_win_in_2_card ( $card, $bet ){
		
		if (  $card['finished'] !== true )
			return ['win' => false];
		
		if ( $card['user_value'] == 21 ){
			$blackjack_bet = true;
			$win_amount = $bet['amount'] * 3/2;
			$win = true;
			return ['win'=>true, 'blackjack'=> $blackjack_bet, 'win_amount'=>$win_amount];
		}
		
		return ['win' => false];
	}
	
	public function check_winner ( $user, $dealer ){
		if ( $user > 21)
			return 'dealer_win';
		else if ( $dealer > 21 )
			return 'user_win';
		else if ( $user == $dealer)
			return 'tie';
		else if ( $user == 21 )
			return 'user_blackjack';
		else if ( $dealer == 21 )
			return 'dealer_blackjack';
		else if ( $user > $dealer)
			return 'user_win';
		else if ( $user < $dealer)
			return 'dealer_win';
	}
	
	public function check_winner_bet( $bet, $winner ){
		
		switch ( $winner ){
			case 'user_blackjack':{
				$side = 'user';
				$method = 'blackjack';
				break;
			}
			case 'dealer_blackjack':{
				$side = 'dealer';
				$method = 'blackjack';
				break;
			}
			case 'user_win':{
				$side = 'user';
				$method = 'standard';
				break;
			}
			case 'dealer_win':{
				$side = 'dealer';
				$method = 'standard';
				break;
			}
			case 'tie':{
				$side = 0;
				$method = 'tie';
				break;
			}
		}
		
		$method_name = $method . '_bet';
		
		$win_amount = $this->{$method_name}( $bet, $side );
		
		return ['side' => $side, 'win' => $win_amount ];
		
	}
	
	public function check_blckjack ( $card ){
		if ( $card['user_value'] == 21 )
			return true;
		
		return false;
		
	}
	
	public function check_split ( $card ){
		$user_1 = $this->change_number_to_card( $card['user_first'] );
		$user_2 = $this->change_number_to_card( $card['user_second'] );
		
		if ( $user_1 == $user_2 )
			return true;
	}
	
	public function check_insurance ( $card ){
		$dealer_1 = $this->change_number_to_card( $card['dealer_first'] );
		
		if ( $dealer_1 == 14 )
			return true;
	}
	
	public function check_game_continue ( $user, $dealer ){
		if ( $user > 21 || $dealer > 21 )
			return 'finished';
		else if ( $user == 21 || $dealer == 21 )
			return 'blackjack';
		
		return 'continue';
	}
	
	public function check_bets ( $bets, $user, $bank ){
		$win = 0;
		if ( $user > $bank ){
			$win = $bets['player'] * 2;
		}
		else if ( $user < $bank ){
			$win = $bets['bank'] * 2;
		}
		else if ( $user == $bank ){
			$win = $bet['tie'] * 9;
		}
		
		return $win;
		
	}
	
	public function blackjack_bet ( $bet, $side ){
		$win_amount = 0;
		if ( $side == 'user'){
			$win_amount = $bet['amount'] * 3/2;
		}
		
		return $win_amount;
	}
	
	public function standard_bet ( $bet, $side ){
		$win_amount = 0;
		if ( $side == 'user'){
			$win_amount = $bet['amount'] * 2;
		}
		
		return $win_amount;
	}
	
	public function tie_bet ( $bet, $side ){
		return $bet['amount'];
	}
	
}

?>