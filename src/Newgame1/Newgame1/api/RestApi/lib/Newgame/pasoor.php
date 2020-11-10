<?php 
//define('ROOT_PATH',$_SERVER['DOCUMENT_ROOT']);

//require_once ROOT_PATH . '/4ubets/application/config/db.php';

class Pasoor {
	
	
	public function __construct(){
		
		
	}
	
	public function get_random_card ( $first=0 , $second=51 ){
		return mt_rand($first, $second);
	}
	
	public function get_4_card ( $number_array ){
		
		$card = array();
		
		
		
		while( sizeof($card) < 4 ){
			$random_number = $this->get_random_card();
			
			if ( array_search( $random_number, $number_array) === false ){
				$card[] = $random_number;
				$number_array[] = $random_number;
			}
		}
		
		return $card;
		
	}
	
	public function change_card_to_number( $card ){
		
		$div = $card % 13;
		
		if ( $div == 12 )
			return 1;
		
		return $div + 2;
	}
	
	public function check_posibility ( $hand_card, $floor){
		
		foreach ( $hand_card as $hand ){
			$i = 0;
			$floor_array = [];
			$hand_number = $this->change_card_to_number($hand);
			
			$floors = sort($floor);
			for ( $i=0; $i < sizeof($floors); $i++ ){
				
				if ( $)
				
				for ($j=$i; $j<sizeof($floors); $j++){
					$a = $this->change_card_to_number($floors[$i]);
					$b = $this->change_card_to_number($floors[$j]);
					
					if ( $this->check_three_number($a,$b,$hand_number) ){
						$floor_array[] = [$floors[$i], $floors[$j]];
					}
				}
			}
		}
		
	}
	
	public function check_two_number ( $a, $hand ){
		if ( $a == $b && $a > 11 )
			return true;
		else if ( ($a==11 || $b==11) && ($a<12 || $b<12))
			return true;
		else if ( $a+$b == 11 )
			return true;
		else
			return false;
	}
	
	public function check_three_number ( $a, $b, $hand ){
		
		
		
		
	}
	
}

?>