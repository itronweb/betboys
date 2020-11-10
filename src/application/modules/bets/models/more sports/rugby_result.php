<?php 

require_once APPPATH . 'config/db.php';


class Rugby_result{
	
	/*
	
	inplay :
	
	
	
	upcoming:
	Asian Handicap
	HT/FT Double
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
	
	public function check_handicap_2_way( $pick, $score ){
		$picks = explode(':',$pick);
		$pick0 = trim($picks[0]);
		$pick1 = trim($picks[1]);
		$win = 0;
		
		if( $pick0 == 'Home' ){
			$team1 = $score[0]+$pick1;
			$team2 = $score[1];
		}
		else if ( $pick0 == 'Away'){
			$team1 = $score[1]+$pick1;
			$team2 = $score[0];
		}
		
		if ( $team1 - $team2 == 0 ){
			$win = 3;
		}
		else if ( $team1 - $team2 == 0.25 ){
			$win = 4;
		}
		else if ( $team1 - $team2 == -0.25 ){
			$win = 5;
		}
		else if ( $team1 - $team2 > 0.25 ){
			$win = 1;
		}
		else if ( $team1 - $team2 < -0.25 ){
			$win = 2;
		}
		
		var_dump( $win );
		if( $win !== 0 )
			return $win;
		
		return 10;
		
		if ( $picks[0] == 'Home' ){
			if ( $score[0] + $picks[1] > $score[1] )
				return true;
			else if ( $score[0] + $picks[1] == $score[1] )
				return 3;
			else if ( $score[0] + $picks[1] < $score[1] )
				return false;
		}
		else if ( $picks[0] == 'Away' ){
			if ( $score[1] + $picks[1] > $score[0] )
				return true;
			else if ( $score[1] + $picks[1] == $score[0] )
				return 3;
			else if ( $score[1] + $picks[1] < $score[0] )
				return false;
		}
		
	}
	
	////////////////////////  1X2 function /////////////////////////////
	
	
	public function check_1X2 ( $match, $form ){
		
		$ft = explode('-', $match[$form->match_id]->ft_score);
		
		return $this->check_win_result( $form->pick, $ft[0], $ft[1] );
	}
	
	//////////////////////// handicap function /////////////////////////////
	
	public function check_asian_handicap ( $match, $form ){
		$ft = explode('-', $match[$form->match_id]->ft_score);
		
		return $this->check_handicap_2_way($form->pick, $ft);
		
		return 10;
		
	}
	

	
}
?>