<?php 
//define('ROOT_PATH',$_SERVER['DOCUMENT_ROOT']);

//require_once ROOT_PATH . '/4ubets/application/config/db.php';

class Slot {
	
	public $first_array = ['first_line', 'second_line', 'third_line'];
	public $second_array = ['first', 'second', 'third', 'fourth', 'fifth'];
	
	public function __construct(){
		
		
	}
	
	public function slot_random_number ( $first=0 , $second=12 ){
		return mt_rand($first, $second);
	}
	
	
	public function get_rondom_number ( $slot_size = 5, $slot_number_1 = 0, $slot_number_2=12){
		
		$slot_number = array();
		$first_array = ['first_line', 'second_line', 'third_line'];
		$second_array = ['first', 'second', 'third', 'fourth', 'fifth'];
		for( $i=0; $i<3; $i++ ){
			for( $j=0; $j<$slot_size; $j++){
				$a = $first_array[$i];
				$b = $second_array[$j];
				$slot_number[$a][$b] = $this->slot_random_number( $slot_number_1, $slot_number_2);
			}
		}

//		$slot_number['second_line'] = ['first'=>3,'second'=>4,'third'=>4,'fourth'=>4,'fifth'=>1];
		return $slot_number;
		
	}

	public function check_free_game( &$game_data ){

        if ( isset( $game_data->free)){
            if ( $game_data->free > 0 ){
                $game_data->free -= 1;
                return true;
            }
        }

        return false;
    }

	public function set_game_data ( $win, &$old_data ){

	    if ( isset($old_data->free) ){
	        $win = ['free' => $win['free'] + $old_data->free];
        }
        else {
            $win = [ 'free' => $win['free'] ];
        }

        return $win;
    }
	
	public function check_slot_number ( $slot_number, $amount, $line ){
		
		$win = 0;
		$free = 0;
		for ( $i=1; $i<=$line; $i++ ){
		    $win_amount = 0;
			$method_name = "check_line_$i";
            $check_win = $this->{$method_name} ( $slot_number, $amount);

            $free += $check_win['free'];
            $win_amount = $check_win['win'];

            if ( $win_amount > 0 ){
                $win_array['win_line'][] = ['line' => $i];
                $win += $win_amount;
            }

		}
		$win_array['win'] = $win;
        $win_array['free'] = $free;
		return $win_array;
	}
	
	public function check_same_number( $number_array, $amount ){
		
		$repeat = true;
		$repeat_number = array();
		$win = 0;
        $free = 0;
		while( $repeat === true ){
			$repeat = false;
			
			foreach( $number_array as $key=>$value){
				if ( isset($number_array[$key+1])){
					
					if ( $value == $number_array[$key+1]){
						$repeat = true;
						if ( isset($repeat_number[$value]) ){
							
							if ( $repeat_number[$value]['last_key'] == $key){
								$repeat_number[$value]['repeat']++;
								$repeat_number[$value]['last_key'] = $key+1;
							}
							else {
								$repeat_number[-$value] = [ 'number'=> $value, 'repeat' => 2, 'last_key' => $key+1 ];
							}
						}
						else {
							$repeat_number[$value] = [ 'number'=> $value, 'repeat' => 2, 'last_key' => $key+1 ];
						}
						unset( $number_array[$key]);
					}
				}
				
			}
		}
		
		foreach ( $repeat_number as $key=>$value ){	
			$method_name = "check_symbol_" . $value['number'];
			
			if ( $value['repeat'] < 0 )
				$value['repeat'] = ( $value['repeat'] * -1 );
			
			$win_amount = $this->{$method_name}( $amount, $value['repeat'] );

            if ( isset($win_amount['free'])){
                $win += $win_amount['win'];
                $free += $win_amount['free'];
            }
            else {
                $win += $win_amount;
            }


		}

		$win_array['free'] = $free;
        $win_array['win'] = $win;
		return $win_array;
		
	}
	
	//////////////////// check line /////////////////////////
	
	public function check_line_1 ( $slot_number, $amount ){
		$first_array = $this->first_array;
		$second_array = $this->second_array;
		
		$number_array = array();
		for( $i = 0; $i<sizeof($second_array); $i++){
			$a = $second_array[$i];
			
			$number_array[] = $slot_number['second_line'][$a];
		}
		
		return $this->check_same_number( $number_array, $amount );
		
	}
	
	public function check_line_2 ( $slot_number, $amount ){
		$first_array = $this->first_array;
		$second_array = $this->second_array;
		
		$number_array = array();
		for( $i = 0; $i<sizeof($second_array); $i++){
			$a = $second_array[$i];
			
			$number_array[] = $slot_number['first_line'][$a];
		}
		
		return $this->check_same_number( $number_array, $amount );
		
	}
	
	public function check_line_3 ( $slot_number, $amount ){
		$first_array = $this->first_array;
		$second_array = $this->second_array;
		
		$number_array = array();
		for( $i = 0; $i<sizeof($second_array); $i++){
			$a = $second_array[$i];
			
			$number_array[] = $slot_number['third_line'][$a];
		}
		
		return $this->check_same_number( $number_array, $amount );
		
	}
	
	public function check_line_4 ( $slot_number, $amount ){
		$first_array = $this->first_array;
		$second_array = $this->second_array;
		
		$number_array = [ $slot_number['first_line']['first'],
						 $slot_number['second_line']['second'],
						 $slot_number['third_line']['third'],
						 $slot_number['second_line']['fourth'],
						 $slot_number['first_line']['fifth'],
						];
		
		return $this->check_same_number( $number_array, $amount );
		
	}
	
	public function check_line_5 ( $slot_number, $amount ){
		$first_array = $this->first_array;
		$second_array = $this->second_array;
		
		$number_array = [ $slot_number['third_line']['first'],
						 $slot_number['second_line']['second'],
						 $slot_number['first_line']['third'],
						 $slot_number['second_line']['fourth'],
						 $slot_number['third_line']['fifth'],
						];
		
		return $this->check_same_number( $number_array, $amount );
		
	}
	
	public function check_line_6 ( $slot_number, $amount ){
		$first_array = $this->first_array;
		$second_array = $this->second_array;
		
		$number_array = [ $slot_number['second_line']['first'],
						 $slot_number['first_line']['second'],
						 $slot_number['second_line']['third'],
						 $slot_number['first_line']['fourth'],
						 $slot_number['second_line']['fifth'],
						];
		
		return $this->check_same_number( $number_array, $amount );
		
	}
	
	public function check_line_7 ( $slot_number, $amount ){
		$first_array = $this->first_array;
		$second_array = $this->second_array;
		
		$number_array = [ $slot_number['second_line']['first'],
						 $slot_number['third_line']['second'],
						 $slot_number['second_line']['third'],
						 $slot_number['third_line']['fourth'],
						 $slot_number['second_line']['fifth'],
						];
		
		return $this->check_same_number( $number_array, $amount );
		
	}
	
	public function check_line_8 ( $slot_number, $amount ){
		$first_array = $this->first_array;
		$second_array = $this->second_array;
		
		$number_array = [ $slot_number['first_line']['first'],
						 $slot_number['first_line']['second'],
						 $slot_number['second_line']['third'],
						 $slot_number['third_line']['fourth'],
						 $slot_number['third_line']['fifth'],
						];
		
		return $this->check_same_number( $number_array, $amount );
		
	}
	
	public function check_line_9 ( $slot_number, $amount ){
		$first_array = $this->first_array;
		$second_array = $this->second_array;
		
		$number_array = [ $slot_number['third_line']['first'],
						 $slot_number['third_line']['second'],
						 $slot_number['second_line']['third'],
						 $slot_number['first_line']['fourth'],
						 $slot_number['first_line']['fifth'],
						];
		
		return $this->check_same_number( $number_array, $amount );
		
	}
	
	public function check_line_10 ( $slot_number, $amount ){
		$first_array = $this->first_array;
		$second_array = $this->second_array;
		
		$number_array = [ $slot_number['second_line']['first'],
						 $slot_number['third_line']['second'],
						 $slot_number['second_line']['third'],
						 $slot_number['first_line']['fourth'],
						 $slot_number['first_line']['fifth'],
						];
		
		return $this->check_same_number( $number_array, $amount );
		
	}
	
	public function check_line_11 ( $slot_number, $amount ){
		$first_array = $this->first_array;
		$second_array = $this->second_array;
		
		$number_array = [ $slot_number['second_line']['first'],
						 $slot_number['first_line']['second'],
						 $slot_number['second_line']['third'],
						 $slot_number['third_line']['fourth'],
						 $slot_number['second_line']['fifth'],
						];
		
		return $this->check_same_number( $number_array, $amount );
		
	}
	
	public function check_line_12 ( $slot_number, $amount ){
		$first_array = $this->first_array;
		$second_array = $this->second_array;
		
		$number_array = [ $slot_number['first_line']['first'],
						 $slot_number['second_line']['second'],
						 $slot_number['second_line']['third'],
						 $slot_number['second_line']['fourth'],
						 $slot_number['first_line']['fifth'],
						];
		
		return $this->check_same_number( $number_array, $amount );
		
	}
	
	public function check_line_13 ( $slot_number, $amount ){
		$first_array = $this->first_array;
		$second_array = $this->second_array;
		
		$number_array = [ $slot_number['third_line']['first'],
						 $slot_number['second_line']['second'],
						 $slot_number['second_line']['third'],
						 $slot_number['second_line']['fourth'],
						 $slot_number['third_line']['fifth'],
						];
		
		return $this->check_same_number( $number_array, $amount );
		
	}
	
	public function check_line_14 ( $slot_number, $amount ){
		$first_array = $this->first_array;
		$second_array = $this->second_array;
		
		$number_array = [ $slot_number['first_line']['first'],
						 $slot_number['second_line']['second'],
						 $slot_number['third_line']['third'],
						 $slot_number['second_line']['fourth'],
						 $slot_number['third_line']['fifth'],
						];
		
		return $this->check_same_number( $number_array, $amount );
		
	}
	
	public function check_line_15 ( $slot_number, $amount ){
		$first_array = $this->first_array;
		$second_array = $this->second_array;
		
		$number_array = [ $slot_number['third_line']['first'],
						 $slot_number['second_line']['second'],
						 $slot_number['third_line']['third'],
						 $slot_number['second_line']['fourth'],
						 $slot_number['third_line']['fifth'],
						];
		
		return $this->check_same_number( $number_array, $amount );
		
	}
	
	public function check_line_16 ( $slot_number, $amount ){
		$first_array = $this->first_array;
		$second_array = $this->second_array;
		
		$number_array = [ $slot_number['second_line']['first'],
						 $slot_number['second_line']['second'],
						 $slot_number['first_line']['third'],
						 $slot_number['second_line']['fourth'],
						 $slot_number['second_line']['fifth'],
						];
		
		return $this->check_same_number( $number_array, $amount );
		
	}
	
	public function check_line_17 ( $slot_number, $amount ){
		$first_array = $this->first_array;
		$second_array = $this->second_array;
		
		$number_array = [ $slot_number['second_line']['first'],
						 $slot_number['second_line']['second'],
						 $slot_number['third_line']['third'],
						 $slot_number['second_line']['fourth'],
						 $slot_number['second_line']['fifth'],
						];
		
		return $this->check_same_number( $number_array, $amount );
		
	}
	
	public function check_line_18 ( $slot_number, $amount ){
		$first_array = $this->first_array;
		$second_array = $this->second_array;
		
		$number_array = [ $slot_number['first_line']['first'],
						 $slot_number['first_line']['second'],
						 $slot_number['third_line']['third'],
						 $slot_number['first_line']['fourth'],
						 $slot_number['first_line']['fifth'],
						];
		
		return $this->check_same_number( $number_array, $amount );
		
	}
	
	public function check_line_19 ( $slot_number, $amount ){
		$first_array = $this->first_array;
		$second_array = $this->second_array;
		
		$number_array = [ $slot_number['third_line']['first'],
						 $slot_number['third_line']['second'],
						 $slot_number['first_line']['third'],
						 $slot_number['third_line']['fourth'],
						 $slot_number['third_line']['fifth'],
						];
		
		return $this->check_same_number( $number_array, $amount );
		
	}
	
	public function check_line_20 ( $slot_number, $amount ){
		$first_array = $this->first_array;
		$second_array = $this->second_array;
		
		$number_array = [ $slot_number['first_line']['first'],
						 $slot_number['third_line']['second'],
						 $slot_number['third_line']['third'],
						 $slot_number['third_line']['fourth'],
						 $slot_number['first_line']['fifth'],
						];
		
		return $this->check_same_number( $number_array, $amount );
		
	}
	
	
	/////////////////// symbol value ////////////////////////////
	
	public function check_symbol_0( $amount, $repeat ){
		
		$symbol_value = [
			'5'	=> 1000,
			'4'	=> 250,
			'3' => 60,
			'2'	=> 7
		];
		
		if ( isset($symbol_value[$repeat]) ){
			return $amount * $symbol_value[$repeat];
		}
		
		return 0;
	}
	
	public function check_symbol_1( $amount, $repeat ){
		
		$symbol_value = [
			'5'	=> 1000,
			'4'	=> 125,
			'3' => 50,
			'2'	=> 5
		];
		
		if ( isset($symbol_value[$repeat]) ){
			return $amount * $symbol_value[$repeat];
		}
		
		return 0;
	}
	
	public function check_symbol_2( $amount, $repeat ){
		
		$symbol_value = [
			'5'	=> 200,
			'4'	=> 25,
			'3' => 15,
			'2'	=> 2
		];
		
		if ( isset($symbol_value[$repeat]) ){
			return $amount * $symbol_value[$repeat];
		}
		
		return 0;
	}
	
	public function check_symbol_3( $amount, $repeat ){
		
		$symbol_value = [
			'5'	=> 500,
			'4'	=> 60,
			'3' => 30,
			'2'	=> 2
		];
		
		if ( isset($symbol_value[$repeat]) ){
			return $amount * $symbol_value[$repeat];
		}
		
		return 0;
	}
	
	public function check_symbol_4( $amount, $repeat ){
		
		$symbol_value = [
			'5'	=> 20,
			'4'	=> 2,
			'3' => 1,
		];
		
		if ( isset($symbol_value[$repeat]) ){

			return [ 'free' => 5 , 'win' => $amount * $symbol_value[$repeat]];
		}
		
		return 0;
	}
	
	public function check_symbol_5( $amount, $repeat ){
		
		$symbol_value = [
			'5'	=> 100,
			'4'	=> 15,
			'3' => 5,
		];
		
		if ( isset($symbol_value[$repeat]) ){
			return $amount * $symbol_value[$repeat];
		}
		
		return 0;
	}
	
	public function check_symbol_6( $amount, $repeat ){
		
		$symbol_value = [
			'5'	=> 100,
			'4'	=> 15,
			'3' => 5,
		];
		
		if ( isset($symbol_value[$repeat]) ){
			return $amount * $symbol_value[$repeat];
		}
		
		return 0;
	}
	
	public function check_symbol_7( $amount, $repeat ){
		
		$symbol_value = [
			'5'	=> 40,
			'4'	=> 7,
			'3' => 3,
		];
		
		if ( isset($symbol_value[$repeat]) ){
			return $amount * $symbol_value[$repeat];
		}
		
		return 0;
	}
	
	public function check_symbol_8( $amount, $repeat ){
		
		$symbol_value = [
			'5'	=> 40,
			'4'	=> 7,
			'3' => 3,
		];
		
		if ( isset($symbol_value[$repeat]) ){
			return $amount * $symbol_value[$repeat];
		}
		
		return 0;
	}
	
	public function check_symbol_9( $amount, $repeat ){
		
		$symbol_value = [
			'5'	=> 30,
			'4'	=> 6,
			'3' => 2,
		];
		
		if ( isset($symbol_value[$repeat]) ){
			return $amount * $symbol_value[$repeat];
		}
		
		return 0;
	}
	
	public function check_symbol_10( $amount, $repeat ){
		
		$symbol_value = [
			'5'	=> 30,
			'4'	=> 6,
			'3' => 2,
		];
		
		if ( isset($symbol_value[$repeat]) ){
			return $amount * $symbol_value[$repeat];
		}
		
		return 0;
	}
	
	public function check_symbol_11( $amount, $repeat ){
		
		$symbol_value = [
			'5'	=> 20,
			'4'	=> 5,
			'3' => 1,
		];
		
		if ( isset($symbol_value[$repeat]) ){
			return $amount * $symbol_value[$repeat];
		}
		
		return 0;
	}
	
	public function check_symbol_12( $amount, $repeat ){
		
		$symbol_value = [
			'5'	=> 20,
			'4'	=> 5,
			'3' => 1,
		];
		
		if ( isset($symbol_value[$repeat]) ){
			return $amount * $symbol_value[$repeat];
		}
		
		return 0;
	}
	
	
}

?>