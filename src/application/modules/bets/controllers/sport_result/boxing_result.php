<?php 

require_once APPPATH . 'config/db.php';


class Boxing_result{
	
	/*
	
	*/
	
	public function __construct(){
		$db = new DB();
		
		
	}
	
	public function convertSpaceToUnderline($data){
		$data = str_replace( '/', '_', implode('_',explode(' ',$data)));
		return str_replace('-','_',$data);
	}
	
	public function checkWin ( $match, $form ){
		var_dump($form->bet_type);
		
		
		$label = 'check_'.$this->convertSpaceToUnderline(trim($form->bet_type));
		var_dump( $form->soccer_type);
		var_dump( $label );
		if( method_exists($this, $label))
			return $this->{$label}( $match, $form );
		else
			return 10;
	}
	
	
	
	
	///////////////////////// Calculate Odds ///////////////////////////////
	
	
	public function check_win_result( $pick, $home_score, $away_score){
		$pick = trim($pick);
		$win_label = array();
		
		if( $home_score > $away_score ){
			$win_label = ['Home','home' , '1'];
		}
		else if ( $home_score < $away_score ){
			$win_label = ['Away','away', '2'];
		}
		else if ( $home_score == $away_score ){
			$win_label = ['Draw','draw', 'X'];
		}
		var_dump(in_array($pick, $win_label));
		
		if( !empty($win_label) ){
			return in_array($pick, $win_label) ? true : false;
		}
		
		return 10;
	}
	
	public function check_over_under_label($pick,$home_score,$away_score){
		$goals = ( int ) $home_score + ( int ) $away_score;
		
		$OverUnder = explode(' ' , $pick);
		
		if( ( float ) $OverUnder[2] == ( float ) $goals )
			return 3;
		else if ( (float)$goals==(float)$OverUnder[2]+0.25){
			$matchGoalResult = [ 4 => "Over : $OverUnder[2]", 5 => "Under : $OverUnder[2]" ];
		}
		else if ( (float)$goals==(float)$OverUnder[2]-0.25 ){
			$matchGoalResult = [ 5 => "Over : $OverUnder[2]", 4 => "Under : $OverUnder[2]" ];
		}
		else if ( ( float ) $OverUnder[2] < ( float ) $goals ){
			$matchGoalResult = [ 1 => 'Over : ' . $OverUnder[2] ];
		}
		else if ( ( float ) $OverUnder[2] > ( float ) $goals ){
			$matchGoalResult = [ 1 => 'Under : ' . $OverUnder[2] ];
		}
		else 
			return false;
			
		$win = array_search( $pick, $matchGoalResult);
		var_dump( $pick );
		var_dump( $win );
		if ( $win == 1 )
			return true;
		else if ( $win === false )
			return false;
		else
			return $win;
				
	
	}
	
	////////////////////////  1X2 function /////////////////////////////
	
	
	public function check_1X2 ( $match, $form ){

		$ft = explode('-', $match[$form->match_id]->ft_score);
		
		return $this->check_win_result( $form->pick, $ft[0], $ft[1] );
	}
	
	
	
	
	///////////////////////// over under function ////////////////////////
	
	public function check_over_under ( $match, $form ){
		$home = $match[$form->match_id]->homeTeam->score;
		$away = $match[$form->match_id]->awayTeam->score;
		
		return $this->check_over_under_label( $form->pick, $home, $away );
	}
	
	
	

	
}
?>