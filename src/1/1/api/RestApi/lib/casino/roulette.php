<?php 
//define('ROOT_PATH',$_SERVER['DOCUMENT_ROOT']);

//require_once ROOT_PATH . '/4ubets/application/config/db.php';

class Roulette {
	
	public $red = [1,3,5,7,9,12,14,16,18,19,21,23,25,27,30,32,34,36];
	
	public function __construct(){
		
		
	}
	
	public function roulette_radom_number ( $first=0 , $second=36 ){
		return mt_rand($first, $second);
	}
	
	public function check_bets ( $bets, $win_place ){
		$sum = 0;
		$win_cash = 0;
		foreach ( $bets as $value){
			
			$sum += $value->amount;
			$func_name = "check_position_" . $value->position;
//		
			$win_cash+=$this->{$func_name}($value->amount,$value->place, $win_place);

		}
		
		return [ 'all_bets'=> $sum, 
				'win_bets'=> $win_cash,
				'place' => $value->place,
			   ];
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