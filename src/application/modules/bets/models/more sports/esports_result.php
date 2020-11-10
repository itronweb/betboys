<?php 

require_once APPPATH . 'config/db.php';


class esports_result{
	
	/*
	
	inplay :
	
	
	
	upcoming:
	
	Home/Away (map 1)
	Home/Away (map 2)
	Home/Away (map 3)
	Total Maps
	Maps Handicap
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
	
	public function correct_score ( $pick, $ft ){
		$score[] = implode('-',explode('-',$ft));
		$score[] = implode(':',explode('-',$ft));
		
		return ( in_array( $pick, $score ) ) ? true : false;
	}
	
	////////////////////////  1X2 function /////////////////////////////
	
	
	public function check_1X2 ( $match, $form ){

		$ft = explode('-', $match[$form->match_id]->ft_score);
		
		return $this->check_win_result( $form->pick, $ft[0], $ft[1] );
	}
	
	
	
	//////////////////////// correct score ///////////////////////////////
	
	public function check_correct_score ( $match, $form ){
		$pick = $form->pick;
		$ft = $match[$form->match_id]->ft_score;
		
		return $this->correct_score( $pick, $ft );
	}
	
	
	
	

	
}
?>