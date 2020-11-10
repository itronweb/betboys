<?php 
//define('ROOT_PATH',$_SERVER['DOCUMENT_ROOT']);

//require_once ROOT_PATH . '/4ubets/application/config/db.php';

class RPS {
	
	
	public function __construct(){
		
		
	}
	
	public function get_random_card ( $first=0 , $second=2 ){
		return mt_rand($first, $second);
	}
	
	public function register_my_select_in_game_data( $user_id, &$game_data, $select){
		
		$game_data->select[] = $select;
		return $game_data;
		
	}
	
	public function check_select ( &$my_data, &$op_data ){
		
		$select_1_array = ( $my_data->side == 'my' ) ? $my_data->select : $op_data->select ;
		$select_2_array = ( $my_data->side == 'my' ) ? $op_data->select : $my_data->select;
		
		if ( sizeof($select_1_array) != sizeof($select_2_array))
			return false;
		
		$select_1 = end( $select_1_array );
		$select_2 = end( $select_2_array );
		
		$winner = $this->check_winner ( $select_1, $select_2 );
		
		if ( $winner != 3 ){
			
			if ( $winner == 2 ){
				if ($my_data->side == 'my'){
//					$op_data->score += 1;
					$win = 'op';	
				}
				else{
//					$my_data->score += 1;
					$win = 'my';	
				}	
			}
			else if ( $winner == 1 ){
				if ($my_data->side == 'my'){
//					$my_data->score += 1;
					$win = 'my';	
				}
				else{
//					$op_data->score += 1;
					$win = 'op';	
				}	
			}
			
		}	
		else{
			$win = null;
		}
		
		$this->check_score( $my_data, $op_data );
		
//		$finished = $this->finished_game ( $my_data->select );
		
		$score_1 = ( $my_data->side == 'my' ) ? $my_data->score : $op_data->score ;
		$score_2 = ( $my_data->side == 'my' ) ? $op_data->score : $my_data->score;
		return ['selected_1' 	=> $select_1,
			    'selected_2'	=> $select_2,
			    'winner'		=> $win,
				'score_1'		=> $score_1,
				'score_2'		=> $score_2,
//				'finish'		=> $finished,
			   ];
		
		
		
	}
	
	public function check_winner ( $select_1, $select_2 ){
		
		if ( $select_1 == $select_2 ){
			return 3;
		}
		
		$select_1 += 1;
		if ( $select_1 > 3 )
			$select_1 -= 3;
		
		if ( $select_1 == $select_2 )
			return 2;
		
		return 1;
	}
	
	public function check_score( &$my, &$op ){
		$score_1 = $score_2 = 0;
		
		for( $i=0; $i< sizeof($my->select); $i++ ){
			$win = $this->check_winner( $my->select[$i], $op->select[$i] );
//			var_dump( $win );
			if ( $win == 1 )
				$score_1 +=1;
			elseif ( $win == 2 )
				$score_2 +=1;
		}
		
		$my->score = $score_1;
		$op->score = $score_2;
		
		return 1;
	}
	
	public function finished_game( $array ){
		
		$total_hand = 3;
		
		if ( sizeof( $array ) >= $total_hand )
			return true;
		
		return false;
		
		
		
	}
	
	public function winner_id ($my_data, $op_data ){
		
		if( $my_data->score > $op_data->score )
			return $op_data->op;
		else if ( $my_data->score < $op_data->score )
			return $my_data->op;
		else
			return 1;
	}
	
	public function check_continue_game( $my_data, $op_data ){
	
		if ( $this->finished_game( $my_data->select ) ){
			
			$finished = $this->winner_id( $my_data, $op_data);
			
			if ( $finished == 1 )
				return true;
			
			return $finished;
			
		}
		
		return true;
		
	}

}

?>