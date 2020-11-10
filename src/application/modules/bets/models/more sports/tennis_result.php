<?php 

require_once APPPATH . 'config/db.php';


class Tennis_result{
	
	/*
	
	inplay :
	
	
	// remove label
	Point Winner - Point 2, 15th Game
	Point Winner - Point 6, 16th Game
	15th Game Winner
	16th Game Winner
	15th Game to Deuce
	16th Game to Deuce
	15th Game Score
	16th Game Score
	1st Point Winner 15th Game
	Next Two Games - Either Game to Deuce
	Next Two Games - Winner
	Next Break of Serve
	Alternative Match Handicap
	
	upcoming:
	
	Handicap Result
	Over/Under by Games in Match
	Asian Handicap (Games)
	
	
	//// remove label 
	Set Betting
	Tie-break
	Set / Match
	Tie-break (1st Set)
	
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
	
	public function correct_score ( $pick, $ft ){
		$score[] = implode('-',explode('-',$ft));
		$score[] = implode(':',explode('-',$ft));
		
		return ( in_array( $pick, $score ) ) ? true : false;
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

    public function check_set_1_winner ( $match, $form ){
		$home = $match[$form->match_id]->homeTeam->s1;
		$away = $match[$form->match_id]->homeTeam->s1;
		
		return $this->check_win_result( $form->pick, $home, $away );
	}
	
	public function check_set_2_winner ( $match, $form ){
		$home = $match[$form->match_id]->homeTeam->s2;
		$away = $match[$form->match_id]->homeTeam->s2;
		
		return $this->check_win_result( $form->pick, $home, $away );
	}
	
	public function check_set_3_winner ( $match, $form ){
		$home = $match[$form->match_id]->homeTeam->s3;
		$away = $match[$form->match_id]->homeTeam->s3;
		
		return $this->check_win_result( $form->pick, $home, $away );
	}
	
	public function check_set_4_winner ( $match, $form ){
		$home = $match[$form->match_id]->homeTeam->s4;
		$away = $match[$form->match_id]->homeTeam->s4;
		
		return $this->check_win_result( $form->pick, $home, $away );
	}
	
	public function check_set_5_winner ( $match, $form ){
		$home = $match[$form->match_id]->homeTeam->s5;
		$away = $match[$form->match_id]->homeTeam->s5;
		
		return $this->check_win_result( $form->pick, $home, $away );
	}

	
	///////////////////////// over under function ////////////////////
	
	public function check_over_under ( $match, $form ){
		$home = $match[$form->match_id]->homeTeam->score;
		$away = $match[$form->match_id]->awayTeam->score;
		
		return $this->check_over_under_label( $form->pick, $home, $away );
	}
	
	public function check_over_under_1st_set ( $match, $form ){
		$home = $match[$form->match_id]->homeTeam->s1;
		$away = $match[$form->match_id]->awayTeam->s1;
		
		return $this->check_over_under_label( $form->pick, $home, $away );
	}
	
	public function check_over_under_2nd_set ( $match, $form ){
		$home = $match[$form->match_id]->homeTeam->s2;
		$away = $match[$form->match_id]->awayTeam->s2;
		
		return $this->check_over_under_label( $form->pick, $home, $away );
	}
	
	public function check_over_under_3rd_set ( $match, $form ){
		$home = $match[$form->match_id]->homeTeam->s3;
		$away = $match[$form->match_id]->awayTeam->s3;
		
		return $this->check_over_under_label( $form->pick, $home, $away );
	}
	
	public function check_over_under_4th_set ( $match, $form ){
		$home = $match[$form->match_id]->homeTeam->s4;
		$away = $match[$form->match_id]->awayTeam->s4;
		
		return $this->check_over_under_label( $form->pick, $home, $away );
	}
	
	public function check_over_under_5th_set ( $match, $form ){
		$home = $match[$form->match_id]->homeTeam->s5;
		$away = $match[$form->match_id]->awayTeam->s5;
		
		return $this->check_over_under_label( $form->pick, $home, $away );
	}
	
	
	//////////////////////// Home away function ///////////////////

	public function check_home_away ( $match, $form ){
		$home = $match[$form->match_id]->homeTeam->score;
		$away = $match[$form->match_id]->awayTeam->score;
		
		return $this->check_win_result( $form->pick, $home, $away );
	}
	
	public function check_home_away_1st_set ( $match, $form ){
		$home = $match[$form->match_id]->homeTeam->s1;
		$away = $match[$form->match_id]->awayTeam->s1;
		
		return $this->check_win_result( $form->pick, $home, $away );
	}
	
	public function check_home_away_2nd_set ( $match, $form ){
		$home = $match[$form->match_id]->homeTeam->s2;
		$away = $match[$form->match_id]->awayTeam->s2;
		
		return $this->check_win_result( $form->pick, $home, $away );
	}
	
	public function check_home_away_3rd_set ( $match, $form ){
		$home = $match[$form->match_id]->homeTeam->s3;
		$away = $match[$form->match_id]->awayTeam->s3;
		
		return $this->check_win_result( $form->pick, $home, $away );
	}
	
	public function check_home_away_4th_set ( $match, $form ){
		$home = $match[$form->match_id]->homeTeam->s4;
		$away = $match[$form->match_id]->awayTeam->s4;
		
		return $this->check_win_result( $form->pick, $home, $away );
	}
	
	public function check_home_away_5th_set ( $match, $form ){
		$home = $match[$form->match_id]->homeTeam->s5;
		$away = $match[$form->match_id]->awayTeam->s5;
		
		return $this->check_win_result( $form->pick, $home, $away );
	}
	
	/////////////////////// correct score function //////////////////
	
	public function check_correct_score ( $match, $form ){
		$pick = $form->pick;
		$ft = $match[$form->match_id]->ft_score;
		
		return $this->correct_score( $pick, $ft );
	}
	
	public function check_correct_score_1st_half( $match, $form ){
		$pick = $form->pick;
		$point[0] = $match[$form->match_id]->ft_score;
		return 10;
		return $this->correct_score( $pick, $ft );
	}
	
	//////////////////////// handicap function /////////////////////////////
	
	public function check_asian_handicap ( $match, $form ){
		$ft = explode('-', $match[$form->match_id]->ft_score);
		
		return $this->check_handicap_2_way($form->pick, $ft);
		
		return 10;
		
	}
	
}
?>