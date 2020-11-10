<?php 
//define('ROOT_PATH',$_SERVER['DOCUMENT_ROOT']);

//require_once ROOT_PATH . '/4ubets/application/config/db.php';

class Baccarat {
	
	
	public function __construct(){
		
		
	}
	
	public function get_random_card ( $first=0 , $second=51 ){
		return mt_rand($first, $second);
	}
	
	public function change_number_to_card ( $number ){
		$div = $number%13 ;
		if ( 8<= $div && $div <=11)
			return 0;
		return ($number%13 == 12) ? 1 : ($number%13) + 2;
	}
	
	public function get_card_value( $arr ){
		$sum = 0;
		foreach ( $arr as $value ){
			if ($value<10)
				$sum += $value;
		}
		if ( $sum >= 10 )
			$sum -= 10;
		
		return $sum;
	}
	
	public function check_banker_get_3rd_card ( $user_3, $bank_value ){
		
		if ( $bank_value < 3 )
			return true;
		else if ( $bank_value == 3 && $user_3 != 8 )
			return true;
		else if ( $bank_value == 4 && ( 1 < $user_3 && $user_3 < 8 ) )
			return true;
		else if ( $bank_value == 5 && ( 3 < $user_3 && $user_3 < 8 ) )
			return true;
		else if ( $bank_value == 6 && ( $user_3==6 || $user_3 ==7) )
			return true;
		else 
			return false;
		
	}
	
	public function check_winner_cantinue_3_card ( $user, $bank){
		if ( $user>7 || $bank>7 || $user == $bank)
			return false;
		
		return true;
	}
	
	public function game_winner ( $user, $bank ){
		if ( $user > $bank)
			return 1;
		else if ( $user < $bank )
			return 2;
		else
			return 0;
	}
	
	
	
	public function baccarat_random_number(){
		
		$user_card_3 = $bank_card_3 = -1;
		
		$user_card_1 = $this->get_random_card();
		$user_card_2 = $this->get_random_card();
		$bank_card_1 = $this->get_random_card();
		$bank_card_2 = $this->get_random_card();
		
		
		$user_1 = $this->change_number_to_card( $user_card_1 );
		$user_2 = $this->change_number_to_card( $user_card_2 );
		$bank_1 = $this->change_number_to_card( $bank_card_1 );
		$bank_2 = $this->change_number_to_card( $bank_card_2 );
		
		$user_card_value = $this->get_card_value( array($user_1, $user_2) );
		$bank_card_value = $this->get_card_value( array($bank_1, $bank_2) );
		
		$winner = false;
		
		if ( $this->check_winner_cantinue_3_card ( $user_card_value, $bank_card_value) ){
			$get_bank_3rd_card = false;
			if ( $user_card_value < 6 ){
				$user_card_3 = $this->get_random_card();
				$user_3 = $this->change_number_to_card( $user_card_3 );
				$user_card_value = $this->get_card_value( array($user_card_value, $user_3) );

				if ( $this->check_banker_get_3rd_card ( $user_3, $bank_card_value ) )
					$get_bank_3rd_card = true;
				
			}
			
			if ( $bank_card_value < 5 || $get_bank_3rd_card === true ){
				$bank_card_3 = $this->get_random_card();
				$bank_3 = $this->change_number_to_card( $bank_card_3 );
				$bank_card_value = $this->get_card_value( array($bank_card_value, $bank_3) );
			}

		}
			
		
		
		return [ 'user_card_1' => $user_card_1, 
				'user_card_2'=>$user_card_2,
				'user_card_3'=> ($user_card_3 == -1) ? null : $user_card_3,
				
				'bank_card_1' => $bank_card_1, 
				'bank_card_2' =>$bank_card_2,
				'bank_card_3' => ($bank_card_3 == -1) ? null : $bank_card_3,
				
				'winner' => $this->game_winner( $user_card_value, $bank_card_value),
				'user_value' => $user_card_value,
				'bank_value' => $bank_card_value,
				
			   ];
		
	}
	
	public function check_bets ( $bets, $user, $bank ){
		$win = 0;
		if ( $user > $bank ){
			$win = $bets['player'] * 2;
		}
		else if ( $user < $bank ){
			$win = $bets['banker'] * 2;
		}
		else if ( $user == $bank ){
			$win = $bets['tie'] * 9;
		}
		
		return $win;
		
	}
	
	public function check_position_0 ( $amount, $place, $win_place){
		return $this->straight_bet( $amount, $place, $win_place );
	}
	
	public function check_position_1 ( $amount, $place, $win_place ){
		
		return $this->street_bet( $amount, $place, $win_place );
		
	}
	
	public function check_position_2 ( $amount, $place, $win_place ){
		$div = $place%3;
		
		if ( $div == 1 ){
			return $this->line_bet ( $amount, $place, $win_place, 2 );
		}
		else if ( $div == 0 || $div == 2 ){
			return $this->corner_bet( $amount, $place, $win_place );
		}
	}
	
	public function check_position_3 ( $amount, $place, $win_place ){
		return $this->split_bet ( $amount, $place, $win_place );
	}
	
	public function check_position_4 ( $amount, $place, $win_place ){
		
		return $this->line_bet ( $amount, $place, $win_place, 4 );
	}
	
	public function straight_bet ( $amount, $place, $win_place ){
		$win_mul = 0;
		
		if ( $place <= 36 && $win_place == $place ){
			$win_mul = 36 ;
		}
		else if ( $place > 36 && $place < 40 ){
			$div = $place%3;
			$num_array = [];
			for( $number=0; $number<=12; $number++)
				$num_array[] = ($number*3)+$div;
			
			if ( array_search( $win_place,$num_array) !== false ){
				$win_mul = 3;
			}
		}
		else if ( $place>39 && $place<43 ){
			$div = ($place%3 == 0 ) ? 3 : $place%3;
			$num_array = [];
			for ( $number=(($div-1)*12); $number<($div*12); $number++)
				$num_array[] = $number;
			
			if ( array_search( $win_place,$num_array) !== false ){
				$win_mul = 3;
			}
		}
		else if ( $place == 43 || $place == 44 ){
			$div = ($place%3 == 0 ) ? 2 : $place%3;
			$num_array = [];
			for ( $number=(($div-1)*18); $number<($div*18); $number++)
				$num_array[] = $number;
			
			if ( array_search( $win_place,$num_array) !== false ){
				$win_mul = 2;
			}
		}
		else if ( $place == 45 || $place == 46 ){
			$div = $place%2;
			$win_place = $win_place%2;
			
			if ( $div != $win_place)
				$win_mul = 2;
		}
		else if ( $place == 47 ){
			if ( array_search( $win_place, $this->red ) !== false )
				$win_mul = 2;
		}
		else if ( $place == 48 ){
			if ( array_search( $win_place, $this->red ) === false )
				$win_mul = 2;
		}
		
		return $amount * $win_mul;
	}
	
	public function street_bet ( $amount, $place, $win_place ){
		$win_mul = 0;
		$num_array = [];
		$div = $place/3;
		
		for( $i=0; $i<3; $i++){
			$num_array[] = ($div*3)+$i;
		}
		
		if ( array_search( $win_place, $num_array ) !== false )
				$win_mul = 12;
		
		return $amount * $win_mul;
	}
	
	public function line_bet ( $amount, $place, $win_place, $position ){
		$num_array = array();
//		var_dump( int ($place/3) );
		$div[0] = intval($place/3);
		$div[1] = $div[0]-1;
		
		foreach ( $div as $dives ){
			for ( $i=1; $i<4; $i++){
				if( $position == 2 )
					$num_array[] = ($dives*3)+$i;
				else if ( $position == 4 )
					$num_array[] = ($dives*3)-($i-1);
			}
				
		}
		
		
		
		if ( array_search( $win_place, $num_array ) !== false )
			$win_mul = 12;
		
		return $amount * $win_mul;
		
		
	}
	
	public function corner_bet ( $amount, $place, $win_place ){
		$num_array = [$place, $place-1, $place-3, $place-4 ];
		
		if ( array_search( $win_place, $num_array ) !== false )
			$win_mul = 9;
		
		return $amount * $win_mul;
	}
	
	public function split_bet ( $amount, $place, $win_place ){
		$num_array = [$place, $place-3];
		
		if ( array_search( $win_place, $num_array ) !== false )
			$win_mul = 18;
		
		return $amount * $win_mul;
		
	}
}

?>