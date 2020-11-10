<?php 

require_once APPPATH . 'config/db.php';


class Basket_result{
	
	/*
	
	inplay :
	
	
	
	upcoming:
	Asian Handicap
	over/under
	
	Double Chance
	
	Asian Handicap First Half
	Asian Handicap 2nd Qtr
	Asian Handicap 3rd Qtr
	Asian Handicap 4th Qtr
	Overtime
	Odd/Even (Including OT)
	
	
	
	1x2 Margin
	Asian Handicap 1st Qtr
	Asian Handicap (2nd Half)
	
	Highest Scoring Quarter
	
	
	
	3Way Result - 1st Qtr
	3Way Result - 2nd Qtr
	3Way Result - 3rd Qtr
	3Way Result - 4th Qtr
	
	
	
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
	
	public function check_odd_evens( $pick, $score ){
		$win_label = array();
		
		if( ( $score%2 ) == 0 )
			$win_label = ['Even'];
		else if ( $score%2 == 1 )
			$win_label = ['Odd'];
		
		if( !empty($win_label) )
			return in_array($pick, $win_label) ? true : false;
		
		return 10;
	}
	
	public function double_chance ( $picks, $home_score, $away_score ){
		
		$pick = explode( ' or ',$picks);
		
		if ( $form->pick == $pick[0] ){
			$pick = explode( '/',$form->pick);
		}
		
		$result1 = $this->check_win_result( trim($pick[0]), $home_score, $away_score );
		$result2 = $this->check_win_result( trim($pick[1]), $home_score, $away_score );
		
		if ( $result1 === true || $result2 === true )
			return true;
		else if ( $result1 === false && $result2 === false )
			return false;
		
		return 10;
	}
	
	////////////////////////  1X2 function /////////////////////////////
	
	
	public function check_1X2 ( $match, $form ){

		$ft = explode('-', $match[$form->match_id]->ft_score);
		
		return $this->check_win_result( $form->pick, $ft[0], $ft[1] );
	}
	
	public function check_3way_result ( $match, $form ){
		$home = $match[$form->match_id]->homeTeam->score;
		$away = $match[$form->match_id]->awayTeam->score;
		
		return $this->check_win_result( $form->pick, $home, $away );
	}
	
	public function check_1st_half_3way_result ( $match, $form ){
		$home = $match[$form->match_id]->homeTeam->s1;
		$away = $match[$form->match_id]->awayTeam->s1;
		
		return $this->check_win_result( $form->pick, $home, $away );
	}
	
	public function check_2nd_half_3way_result ( $match, $form ){
		$home = $match[$form->match_id]->homeTeam->s2;
		$away = $match[$form->match_id]->awayTeam->s2;
		
		return $this->check_win_result( $form->pick, $home, $away );
	}
	
	public function check_3rd_half_3way_result ( $match, $form ){
		$home = $match[$form->match_id]->homeTeam->s3;
		$away = $match[$form->match_id]->awayTeam->s3;
		
		return $this->check_win_result( $form->pick, $home, $away );
	}
	
	public function check_4th_half_3way_result ( $match, $form ){
		$home = $match[$form->match_id]->homeTeam->s4;
		$away = $match[$form->match_id]->awayTeam->s4;
		
		return $this->check_win_result( $form->pick, $home, $away );
	}
	
	public function check_3way_result_1st_qtr ( $match, $form ){
		$home = $match[$form->match_id]->homeTeam->s1;
		$away = $match[$form->match_id]->awayTeam->s1;
		
		return $this->check_win_result( $form->pick, $home, $away );
	}
	
	public function check_3way_result_2nd_qtr ( $match, $form ){
		$home = $match[$form->match_id]->homeTeam->s2;
		$away = $match[$form->match_id]->awayTeam->s2;
		
		return $this->check_win_result( $form->pick, $home, $away );
	}
	
	public function check_3way_result_3rd_qtr ( $match, $form ){
		$home = $match[$form->match_id]->homeTeam->s3;
		$away = $match[$form->match_id]->awayTeam->s3;
		
		return $this->check_win_result( $form->pick, $home, $away );
	}
	
	public function check_3way_result_4th_qtr ( $match, $form ){
		$home = $match[$form->match_id]->homeTeam->s4;
		$away = $match[$form->match_id]->awayTeam->s4;
		
		return $this->check_win_result( $form->pick, $home, $away );
	}
	
	
	////////////////////////// over under function //////////////////////////
	
	public function check_over_under ( $match, $form ){
		$home = $match[$form->match_id]->homeTeam->score;
		$away = $match[$form->match_id]->awayTeam->score;
		
		return $this->check_over_under_label( $form->pick, $home, $away );
	}
	
	public function check_over_under_1st_half ( $match, $form ){
		$home1 = $match[$form->match_id]->homeTeam->s1;
		$away1 = $match[$form->match_id]->awayTeam->s1;
		$home2 = $match[$form->match_id]->homeTeam->s2;
		$away2 = $match[$form->match_id]->awayTeam->s2;
		
		return $this->check_over_under_label( $form->pick, $home1+$home2, $away1+$away2 );
	}
	
	public function check_over_under_2nd_half ( $match, $form ){
		$home3 = $match[$form->match_id]->homeTeam->s3;
		$away3 = $match[$form->match_id]->awayTeam->s3;
		$home4 = $match[$form->match_id]->homeTeam->s4;
		$away4 = $match[$form->match_id]->awayTeam->s4;
		
		return $this->check_over_under_label( $form->pick, $home3+$home4, $away3+$away4 );
	}
	
	public function check_over_under_1st_qtr ( $match, $form ){
		$home = $match[$form->match_id]->homeTeam->s1;
		$away = $match[$form->match_id]->awayTeam->s1;
		
		
		return $this->check_over_under_label( $form->pick, $home, $away );
	}
	
	public function check_over_under_2nd_qtr ( $match, $form ){
		$home = $match[$form->match_id]->homeTeam->s2;
		$away = $match[$form->match_id]->awayTeam->s2;
		
		
		return $this->check_over_under_label( $form->pick, $home, $away );
	}
	
	public function check_over_under_3rd_qtr ( $match, $form ){
		$home = $match[$form->match_id]->homeTeam->s3;
		$away = $match[$form->match_id]->awayTeam->s3;
		
		
		return $this->check_over_under_label( $form->pick, $home, $away );
	}
	
	public function check_over_under_4th_qtr ( $match, $form ){
		$home = $match[$form->match_id]->homeTeam->s4;
		$away = $match[$form->match_id]->awayTeam->s4;
		
		
		return $this->check_over_under_label( $form->pick, $home, $away );
	}
	
	public function check_total_home ( $match, $form ){
		$home = $match[$form->match_id]->homeTeam->score;
		$away = $match[$form->match_id]->awayTeam->score;
		
		return $this->check_over_under_label( $form->pick, $home, 0 );
	}
	
	public function check_total_away ( $match, $form ){
		$home = $match[$form->match_id]->homeTeam->score;
		$away = $match[$form->match_id]->awayTeam->score;
		
		return $this->check_over_under_label( $form->pick, 0, $away );
	}
	
	public function check_home_team_total_goals_1st_half ( $match, $form ){
		$home = $match[$form->match_id]->homeTeam->s1;
		$home += $match[$form->match_id]->homeTeam->s2;
		
		return $this->check_over_under_label( $form->pick, $home, 0 );
	}
	
	public function check_home_team_total_goals_2nd_half ( $match, $form ){
		$home = $match[$form->match_id]->homeTeam->s3;
		$home += $match[$form->match_id]->homeTeam->s4;
		
		return $this->check_over_under_label( $form->pick, $home, 0 );
	}
	
	public function check_away_team_total_goals_1st_half ( $match, $form ){
		$away = $match[$form->match_id]->awayTeam->s1;
		$away += $match[$form->match_id]->awayTeam->s2;
		
		return $this->check_over_under_label( $form->pick, 0, $away );
	}
	
	public function check_away_team_total_goals_2nd_half ( $match, $form ){
		$away = $match[$form->match_id]->awayTeam->s3;
		$away += $match[$form->match_id]->awayTeam->s4;
		
		return $this->check_over_under_label( $form->pick, 0, $away );
	}
	
	public function check_home_team_total_points_1st_quarter ( $match, $form ){
		$home = $match[$form->match_id]->homeTeam->s1;
		
		return $this->check_over_under_label( $form->pick, $home, 0 );
	}
	
	public function check_home_team_total_points_2nd_quarter ( $match, $form ){
		$home = $match[$form->match_id]->homeTeam->s2;
		
		return $this->check_over_under_label( $form->pick, $home, 0 );
	}
	
	public function check_home_team_total_points_3rd_quarter ( $match, $form ){
		$home = $match[$form->match_id]->homeTeam->s3;
		
		return $this->check_over_under_label( $form->pick, $home, 0 );
	}
	
	public function check_home_team_total_points_4th_quarter ( $match, $form ){
		$home = $match[$form->match_id]->homeTeam->s4;
		
		return $this->check_over_under_label( $form->pick, $home, 0 );
	}
	
	public function check_away_team_total_points_1st_quarter ( $match, $form ){
		$away = $match[$form->match_id]->awayTeam->s1;
		
		return $this->check_over_under_label( $form->pick, 0, $away );
	}
	
	public function check_away_team_total_points_2nd_quarter ( $match, $form ){
		$away = $match[$form->match_id]->awayTeam->s2;
		
		return $this->check_over_under_label( $form->pick, 0, $away );
	}
	
	public function check_away_team_total_points_3rd_quarter ( $match, $form ){
		$away = $match[$form->match_id]->awayTeam->s3;
		
		return $this->check_over_under_label( $form->pick, 0, $away );
	}
	
	public function check_away_team_total_points_4th_quarter ( $match, $form ){
		$away = $match[$form->match_id]->awayTeam->s4;
		
		return $this->check_over_under_label( $form->pick, 0, $away );
	}
	
	
	/////////////////////// odd even function //////////////////////////
	
	public function check_odd_even ( $match, $form ){
		$home = $match[$form->match_id]->homeTeam->score;
		$away = $match[$form->match_id]->awayTeam->score;
		
		return $this->check_odd_evens( $form->pick, $home+$away );
	}
	
	public function check_odd_even_1st_half ( $match, $form ){
		$points = $match[$form->match_id]->homeTeam->s1;
		$points += $match[$form->match_id]->awayTeam->s1;
		$points += $match[$form->match_id]->homeTeam->s2;
		$points += $match[$form->match_id]->awayTeam->s2;
		
		return $this->check_odd_evens( $form->pick, $points );
	}
	
	public function check_odd_even_2nd_half ( $match, $form ){
		$points = $match[$form->match_id]->homeTeam->s3;
		$points += $match[$form->match_id]->awayTeam->s3;
		$points += $match[$form->match_id]->homeTeam->s4;
		$points += $match[$form->match_id]->awayTeam->s4;
		
		return $this->check_odd_evens( $form->pick, $points );
	}
	
	public function check_odd_even_1st_quarter ( $match, $form ){
		$points = $match[$form->match_id]->homeTeam->s1;
		$points += $match[$form->match_id]->awayTeam->s1;
		
		return $this->check_odd_evens( $form->pick, $points );
	}
	
	public function check_odd_even_2nd_quarter ( $match, $form ){
		$points = $match[$form->match_id]->homeTeam->s2;
		$points += $match[$form->match_id]->awayTeam->s2;
		
		return $this->check_odd_evens( $form->pick, $points );
	}
	
	public function check_odd_even_3rd_quarter ( $match, $form ){
		$points = $match[$form->match_id]->homeTeam->s3;
		$points += $match[$form->match_id]->awayTeam->s3;
		
		return $this->check_odd_evens( $form->pick, $points );
	}
	
	public function check_odd_even_4th_quarter ( $match, $form ){
		$points = $match[$form->match_id]->homeTeam->s4;
		$points += $match[$form->match_id]->awayTeam->s4;
		
		return $this->check_odd_evens( $form->pick, $points );
	}
	
	public function check_home_odd_even ( $match, $form ){
		$points = $match[$form->match_id]->homeTeam->score;
		
		return $this->check_odd_evens( $form->pick, $points );
	}
	
	public function check_away_odd_even ( $match, $form ){
		$points = $match[$form->match_id]->awayTeam->score;
		
		return $this->check_odd_evens( $form->pick, $points );
	}

	public function check_home_odd_even_1st_half ( $match, $form ){
		$points = $match[$form->match_id]->homeTeam->s1;
		$points += $match[$form->match_id]->homeTeam->s2;
		
		return $this->check_odd_evens( $form->pick, $points );
	}
	
	public function check_home_odd_even_2nd_half ( $match, $form ){
		$points = $match[$form->match_id]->homeTeam->s3;
		$points += $match[$form->match_id]->homeTeam->s4;
		
		return $this->check_odd_evens( $form->pick, $points );
	}
	
	public function check_away_odd_even_1st_half ( $match, $form ){
		$points = $match[$form->match_id]->awayTeam->s1;
		$points += $match[$form->match_id]->awayTeam->s2;
		
		return $this->check_odd_evens( $form->pick, $points );
	}
	
	public function check_away_odd_even_2nd_half ( $match, $form ){
		$points = $match[$form->match_id]->awayTeam->s3;
		$points += $match[$form->match_id]->awayTeam->s4;
		
		return $this->check_odd_evens( $form->pick, $points );
	}
	
	public function check_home_odd_even_1st_quarter ( $match, $form ){
		$points = $match[$form->match_id]->homeTeam->s1;
		
		return $this->check_odd_evens( $form->pick, $points );
	}
	
	public function check_home_odd_even_2nd_quarter ( $match, $form ){
		$points = $match[$form->match_id]->homeTeam->s2;
		
		return $this->check_odd_evens( $form->pick, $points );
	}
	
	public function check_home_odd_even_3rd_quarter ( $match, $form ){
		$points = $match[$form->match_id]->homeTeam->s3;
		
		return $this->check_odd_evens( $form->pick, $points );
	}
	
	public function check_home_odd_even_4th_quarter ( $match, $form ){
		$points = $match[$form->match_id]->homeTeam->s4;
		
		return $this->check_odd_evens( $form->pick, $points );
	}
	
	public function check_away_odd_even_1st_quarter ( $match, $form ){
		$points = $match[$form->match_id]->awayTeam->s1;
		
		return $this->check_odd_evens( $form->pick, $points );
	}
	
	public function check_away_odd_even_2nd_quarter ( $match, $form ){
		$points = $match[$form->match_id]->awayTeam->s2;
		
		return $this->check_odd_evens( $form->pick, $points );
	}
	
	public function check_away_odd_even_3rd_quarter ( $match, $form ){
		$points = $match[$form->match_id]->awayTeam->s3;
		
		return $this->check_odd_evens( $form->pick, $points );
	}
	
	public function check_away_odd_even_4th_quarter ( $match, $form ){
		$points = $match[$form->match_id]->awayTeam->s4;
		
		return $this->check_odd_evens( $form->pick, $points );
	}
	

	///////////////////////// Double Chance function ///////////////////
	
	public function check_double_chance ( $match, $form ){
		$home = $match[$form->match_id]->homeTeam->score;
		$away = $match[$form->match_id]->awayTeam->score;
		
		return $this->double_chance( $form->pick, $home, $away );
	}
	
	public function check_double_chance_1st_half ( $match, $form ){
		$home = $match[$form->match_id]->homeTeam->s1;
		$away = $match[$form->match_id]->awayTeam->s1;
		$home += $match[$form->match_id]->homeTeam->s2;
		$away += $match[$form->match_id]->awayTeam->s2;
		
		return $this->double_chance( $form->pick, $home, $away );
	}
	
	public function check_double_chance_1st_quarter ( $match, $form ){
		$home = $match[$form->match_id]->homeTeam->s1;
		$away = $match[$form->match_id]->awayTeam->s1;
		
		return $this->double_chance( $form->pick, $home, $away );
	}
	
	public function check_double_chance_2nd_quarter ( $match, $form ){
		$home = $match[$form->match_id]->homeTeam->s2;
		$away = $match[$form->match_id]->awayTeam->s2;
		
		return $this->double_chance( $form->pick, $home, $away );
	}
	
	public function check_double_chance_3rd_quarter ( $match, $form ){
		$home = $match[$form->match_id]->homeTeam->s3;
		$away = $match[$form->match_id]->awayTeam->s3;
		
		return $this->double_chance( $form->pick, $home, $away );
	}
	
	public function check_double_chance_4th_quarter ( $match, $form ){
		$home = $match[$form->match_id]->homeTeam->s4;
		$away = $match[$form->match_id]->awayTeam->s4;
		
		return $this->double_chance( $form->pick, $home, $away );
	}
	
	
	//////////////////////// handicap function /////////////////////////////
	
	public function check_asian_handicap ( $match, $form ){
		$ft = explode('-', $match[$form->match_id]->ft_score);
		
		return $this->check_handicap_2_way($form->pick, $ft);
		
		return 10;
		
	}
	

}
	
	
	
	