<?php 

require_once APPPATH . 'config/db.php';


class Soccer_result{
	
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
	
	public function check_clean_sheet( $pick, $score, $team ){
		
		$i = ( $team == 'home' ) ? 1 : 0;
		
		if( !empty( $score ) ){
			$win = ( $score[$i] == 0 ) ? 'Yes' : 'No';
			
			return ( $win == $pick ) ? true : false;
		}
		
		return 10;
	}
	
	public function check_score( $pick, $score, $team ){
		$i = ( $team == 'home' ) ? 0 : 1;
		
		if( !empty( $score ) ){
			$win = ( $score[$i] > 0 ) ? 'Yes' : 'No';
			return ( $win == $pick ) ? true : false;
		}
		
		return 10;
	}
	
	public function check_both_score ( $pick, $home, $away) {
		if( $home === true && $away === true )
			return true;
		else if ( ($home === true || $away === true) && $pick == 'No' )
			return true;
		else if ( $home === false || $away === false )
			return false;
		
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
	
	public function check_handicap_3_way( $pick, $score ){
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
		else if ( $pick0 == 'Draw'){
			$team1 = $score[1]+$pick1;
			$team2 = $score[0];
		}
		
		if ( $pick0 == 'Draw'){
			if ( $team1 - $team2 == 0 )
				return true;
			else
				return false;
		}
		else if ( $team1 - $team2 > 0 )
			return true;
		else if ( $team1 - $team2 <= 0 )
			return false;
		
		
		return 10;
	
	}
	
	
	public function check_exact_goals ( $pick, $score ){
	    $pick = strtolower(trim($pick));
		$array_score = [ "$score goals", "$score goal","+$score goals", "+$score goal" ];
		
		if ( strpos($pick,'more') !== false){
			$picks = explode(' ',$pick);
			if ( $score >= $picks[1])
				$array_score[] = "more $picks[1]";
		}
			
		
		if ( $score == 0 ){
			$array_score[] = 'no goal';
			$array_score[] = '0';
		}	
		else
			$array_score[] = $score;
		
		return ( in_array($pick, $array_score) ) ? true : false;
	}
	
	public function check_number_goals ( $match, $form, $first, $number ){
		if( !isset( $match[$form->match_id]->events->goals ) )
			return 10;
		
		if( !isset($match[$form->match_id]->events->goals) && $number == 1 )
			$win_array = ["No goal"];
		else if ( ! isset( $match[$form->match_id]->events->goals ) )
			return 10;
		else {
			$goals = $match[$form->match_id]->events->goals;
			$first_goals = $goals[0];
			$result = explode('-',$first_goals->result);
			if( array_sum( $result ) == $number )
				$win_array = [ $firs_goals->team, $first_goals->team_name ];
			else if ( array_sum( $result) == ( $number - 1 ) )
				$win_array = [ "No goal"];
			else
				return 10;
		}
		
		foreach ( $win_array as $win ){
			if( strpos( $form->pick, $win_array[0]) !== false )
			return true;
		}
		return false;
	}
	
	public function get_second_half_result( $match, $form ){
		$ht = explode('-', $match[$form->match_id]->ht_score);
		$ft = explode('-', $match[$form->match_id]->ft_score);
		$second_half[0] = (int) $ft[0] - (int) $ht[0];
		$second_half[1] = (int) $ft[1] - (int) $ht[1];
		
		return $second_half;
	}
	
	/////////////// Live ////////////////////////////////////////
	
	public function check_live_last_team_to_score ( $match, $form ){
		
		return $this->get_score_number( $match, $form, 100 );
//		return $this->check_live_goals( $match, $form, 20 );
	}
	
	public function check_live_first_team_to_score ( $match, $form ){
		return $this->get_score_number( $match, $form, 1 );
//		return $this->check_live_goals( $match, $form, 1 );
	}

	public function check_team_to_score_first ( $match, $form ){
		return $this->get_score_number( $match, $form, 1 );
//		return $this->check_live_goals( $match, $form, 1 );
	}
	
	public function check_live_1st_goal ( $match, $form ){
		return $this->get_score_number( $match, $form, 1 );
//		return $this->check_live_goals( $match, $form, 1 );
	}
	
	public function check_live_2nd_goal ( $match, $form ){
		return $this->get_score_number( $match, $form, 2 );
//		return $this->check_live_goals( $match, $form, 2 );
	}
	
	public function check_live_3rd_goal ( $match, $form ){
		return $this->get_score_number( $match, $form, 3 );
//		return $this->check_live_goals( $match, $form, 3 );
	}
	
	public function check_live_4th_goal ( $match, $form ){
		return $this->get_score_number( $match, $form, 4 );
//		return $this->check_live_goals( $match, $form, 4 );
	}
	
	public function check_live_5th_goal ( $match, $form ){
		return $this->get_score_number( $match, $form, 5 );
//		return $this->check_live_goals( $match, $form, 5 );
	}
	
	public function check_live_6th_goal ( $match, $form ){
		return $this->get_score_number( $match, $form, 6 );
//		return $this->check_live_goals( $match, $form, 6 );
	}
	
	public function check_live_goals ( $match, $form, $number ){
		
		if( !isset($match[$form->match_id]))
			return 10;
		
		$result = new stdClass();
		$this->get_live_score($result, $match, $form );
		
		$change_result = $this->check_change_live_score ( $result, $number );
		
		if ( $change_result == 10 )
			return 10;
		$check_change_result = $this->check_change_live_result ( $form, $change_result, $result);
		
		return $check_change_result;
	}
	
	public function get_live_score ( &$result, $match, $form ){
		
		$result->new_home = $match[$form->match_id]->home_score;
		$result->new_away = $match[$form->match_id]->away_score;
		$result->live_home = $form->home_score_live;
		$result->live_away = $form->away_score_live;
		$result->old_home = $form->home_score;
		$result->old_away = $form->away_score;
		
		return $result;
	}
	
	public function check_change_live_score ( $result, $number ){
		
		if( $result->new_home + $result->new_away > $number )
			return 10;
		else if ( $result->new_home + $result->new_away == $number ){
			if ( ($result->new_home == $result->live_home) && ($result->new_away == $result->live_away) )
				return 3;
			else if ( $result->new_home == $result->old_home && $result->new_away == $result->old_away)
				return 10;

			if ( $result->new_home > $result->live_home )
				$win[] = 'Home';

			if ( $result->new_away > $result->live_away )
				$win[] = 'Away';
			
		}
		else if ( $result->new_home + $result->new_away < $number ){
			if ( ($result->new_home == 0 ) && ($result->new_away == 0 ) )
				$win[] = 'No Goals FT';
			else if ( $result->new_home == $result->old_home && $result->new_away == $result->old_away)
				return 10;

			if ( $result->new_home > $result->live_home && $result->new_home > $result->old_home )
				$win[] = 'Home FT';

			if ( $result->new_away > $result->live_away && $result->new_away > $result->old_away )
				$win[] = 'Away FT';
			
		}
		
		
		if ( sizeof( $win ) > 1 )
			return 10;
		else if ( sizeof( $win ) == 0 )
			return 10;
		
		return $win[0];
		
	}
	
	public function check_change_live_result( $form, $change, $result ){
		// if no change
		if ( strpos( trim($change), 'No') !== false ){
			if( strpos( $form->pick, 'No') !== false ){
				$form->update( array( 'goal_score' => 1 ) );
			}
			else {
				$form->update( array( 'goal_score' => 0 ) );
			}
			return 10;
		} // if last team to score selected
		else if ( strpos( trim($change), 'FT') !== false ){
			if ( strpos( trim($change), $form->pick ) !== false )
				$form->update( array( 'goal_score' => 1 ) );
			else
				$form->update( array( 'goal_score' => 0 ) );
			
			$form->update( array( 
							'home_score' => $result->new_home,
							'away_score' => $result->new_away,
						) );
			
			return 10;
		}
		// if such 1st goal selected
		if ( strpos( $form->pick, trim($change) ) !== false ){
			$form->update( array( 
							'goal_score' => 1,
							'home_score' => $result->new_home,
							'away_score' => $result->new_away,
						) );
			return true;
		}
			
		
		
		$form->update( array( 
							'goal_score' => 0 ,
							'home_score' => $result->new_home,
							'away_score' => $result->new_away,
						) );
		return false;
		
		
	}

	
	
	////////////////////////  1X2 function /////////////////////////////
	
	public function check_1X2 ( $match, $form ){
		$ft = explode('-', $match[$form->match_id]->ft_score);
		
		return $this->check_win_result( $form->pick, $ft[0], $ft[1] );
	}
	
	public function check_To_Win_2nd_Half( $match , $form ){
		$ht = explode('-', $match[$form->match_id]->ht_score);
		$ft = explode('-', $match[$form->match_id]->ft_score);
		$home_score_2nd_half = (int) $ft[0] - (int) $ht[0];
		$away_score_2nd_half = (int) $ft[1] - (int) $ht[1];
		$pick = $form->pick;
		
		return $this->check_win_result( $pick, $home_score_2nd_half, $away_score_2nd_half );
	}
	
	public function check_2nd_half_winner ( $match, $form ){
		$ht = explode('-', $match[$form->match_id]->ht_score);
		$ft = explode('-', $match[$form->match_id]->ft_score);
		$home_score_2nd_half = (int) $ft[0] - (int) $ht[0];
		$away_score_2nd_half = (int) $ft[1] - (int) $ht[1];
		$pick = $form->pick;
		
		return $this->check_win_result( $pick, $home_score_2nd_half, $away_score_2nd_half );
	}
	
	public function check_1x2_1st_half ( $match, $form ){
		
		return $this->check_Half_Time_Result( $match, $form );
        
	}
	
	public function check_Half_Time_Result( $match, $form ){
		$ht = explode('-' , $match[$form->match_id]->ht_score);
		
		return $this->check_win_result( $form->pick, trim($ht[0],'['), trim($ht[1],']') );
	}
	
	public function check_1st_half_winner ( $match, $form ){
		$ht = explode('-', $match[$form->match_id]->ht_score);
		
		return $this->check_win_result( $form->pick, $ht[0], $ht[1] );
	}
	
	public function check_1X2_rest ( $match, $form ){
		$ft = explode('-',$match[$form->match_id]->ft_score);
		$win = $this->checkRestLabel($form, $ft[0], $ft[1]);
		
		return $win;
	}
	
	public function check_1x2_rest_1st_half( $match, $form ){
	
		$ht = explode('-',$match[$form->match_id]->ht_score);
		$win = $this->checkRestLabel($form, $ht[0], $ht[1]);
		
		return $win;
		
	}
	
	////////////////////// over under function  //////////////////////////////
	
	public function check_over_under ( $match, $form ){
		$ft = explode ( '-', $match[$form->match_id]->ft_score );
		
		return $this->check_over_under_label( $form->pick, $ft[0], $ft[1] );
	}
	
	public function check_match_goals ( $match, $form ){
		
		$pick = $form->pick;
		$ft = explode('-', $match[$form->match_id]->ft_score);
		
		if( !empty($ft) )

			return $this->check_over_under_label( $pick, $ft[0], $ft[1] );
		
		return 10;
	}
	
	public function check_first_half_goals( $match, $form ){
		
		$ht = explode( '-', $match[$form->match_id]->ht_score );
		$win = $this->check_over_under_label( $form->pick, $ht[0], $ht[1] );
		
		return $win;
	}
	
	public function check_goals_over_under( $match, $form ){
		$ft = explode ( '-', $match[$form->match_id]->ft_score );
		
		return $this->check_over_under_label( $form->pick, $ft[0], $ft[1] );
	}

	public function check_over_under_goals( $match, $form ){
		$ft = explode ( '-', $match[$form->match_id]->ft_score );

		return $this->check_over_under_label( $form->pick, $ft[0], $ft[1] );
	}
	
	public function check_goals_over_under_1st_half( $match, $form ){
		$ht = explode ( '-', $match[$form->match_id]->ht_score );
		
		return $this->check_over_under_label( $form->pick, $ht[0], $ht[1] );
	}
	
	public function check_goals_over_under_2nd_half( $match, $form ){
		$second_half = $this->get_second_half_result( $match, $form );
		
		return $this->check_over_under_label( $form->pick, $second_half[0], $second_half[1] );
	}
	
	public function check_total( $match, $form ){
		$ft = explode('-', $match[$form->match_id]->ft_score);
		
		return $this->check_over_under_label( $form->pick, $ft[0], $ft[1] );
	}
	
	public function check_total__home( $match, $form ){
		$ft = explode('-', $match[$form->match_id]->ft_score);
		
		return $this->check_over_under_label( $form->pick, $ft[0], 0 );
	}
	
	public function check_total__away( $match, $form ){
		$ft = explode('-', $match[$form->match_id]->ft_score);
		
		return $this->check_over_under_label( $form->pick, 0, $ft[1] );
	}
	
	
	////////////////// both team to score function ///////////////////////////
	
	public function check_both_teams_to_score( $match, $form ){
		$ft = explode( '-', $match[$form->match_id]->ft_score );
		
		$home_score = $this->check_score($form->pick, $ft, 'home');
		$away_score = $this->check_score($form->pick, $ft, 'away');
		
		return $this->check_both_score( $form->pick, $home_score, $away_score );
		
	}
	
	public function check_both_teams_to_score_in_1st_half( $match, $form ){
		
		$ht = explode( '-', $match[$form->match_id]->ht_score );
		
		$home_score = $this->check_score($form->pick, $ht, 'home');
		$away_score = $this->check_score($form->pick, $ht, 'away');
		
		return $this->check_both_score( $form->pick, $home_score, $away_score );
		
		if( $home_score === true && $away_score === true )
			return true;
		else if ( $home_score === false || $away_score === false )
			return false;
		
		return 10;
		
	}
	
	public function check_both_teams_to_score_in_2nd_half( $match, $form ){
		
		$second = $this->get_second_half_result( $match, $form );
		
		$home_score = $this->check_score($form->pick, $second, 'home');
		$away_score = $this->check_score($form->pick, $second, 'away');
		
		return $this->check_both_score( $form->pick, $home_score, $away_score );
		
		if( $home_score === true && $away_score === true )
			return true;
		else if ( $home_score === false || $away_score === false )
			return false;
		
		return 10;
		
	}
	
	public function check_both_teams_to_score_1st_half( $match, $form ){
		
		$ht = explode( '-', $match[$form->match_id]->ht_score );
		
		$home_score = $this->check_score($form->pick, $ht, 'home');
		$away_score = $this->check_score($form->pick, $ht, 'away');
		
		return $this->check_both_score( $form->pick, $home_score, $away_score );
		
		if( $home_score === true && $away_score === true )
			return true;
		else if ( $home_score === false || $away_score === false )
			return false;
		
		return 10;
		
	}
	
	public function check_both_teams_to_score_2nd_half( $match, $form ){
		
		$second = $this->get_second_half_result( $match, $form );
		
		$home_score = $this->check_score($form->pick, $second, 'home');
		$away_score = $this->check_score($form->pick, $second, 'away');
		
		return $this->check_both_score( $form->pick, $home_score, $away_score );
		
		if( $home_score === true && $away_score === true )
			return true;
		else if ( $home_score === false || $away_score === false )
			return false;
		
		return 10;
		
	}
	
	public function check_both_teams_to_score__2nd_half( $match, $form ){
		$second_half = $this->get_second_half_result( $match, $form );
		
		$home_score = $this->check_score($form->pick, $second_half, 'home');
		$away_score = $this->check_score($form->pick, $second_half, 'away');
		
		if( $home_score === true && $away_score === true )
			return true;
		else if ( $home_score === false || $away_score === false )
			return false;
		
		return 10;
	}
	
	public function check_both_teams_to_score__1st_half( $match, $form ){
		$ht = explode('-', $match[$form->match_id]->ht_score);
		
		$home_score = $this->check_score($form->pick, $ht, 'home');
		$away_score = $this->check_score($form->pick, $ht, 'away');
		
		if( $home_score === true && $away_score === true )
			return true;
		else if ( $home_score === false || $away_score === false )
			return false;
		
		return 10;
	}
	
	public function check_home_team_score_a_goal( $match, $form ){
		$ft = explode('-', $match[$form->match_id]->ft_score);
		
		return $this->check_score($form->pick, $ft, 'home');
	}
	
	public function check_away_team_score_a_goal( $match, $form ){
		$ft = explode('-', $match[$form->match_id]->ft_score);
		
		return $this->check_score($form->pick, $ft, 'away');
	}
	
	public function check_home_to_score_in_2nd_half ( $match, $form ){
		$second_half = $this->get_second_half_result( $match, $form );
		
		$home_score = $this->check_score($form->pick, $second_half, 'home');
		
		if( $home_score === true )
			return true;
		else if ( $home_score === false )
			return false;
		
		return 10;
	}
	
	public function check_away_to_score_in_2nd_half ( $match, $form ){
		$second_half = $this->get_second_half_result( $match, $form );
		
		$home_score = $this->check_score($form->pick, $second_half, 'away');
		
		if( $home_score === true )
			return true;
		else if ( $home_score === false )
			return false;
		
		return 10;
	}
	
	/////////////////////// clean sheet function /////////////////////////
	
	public function check_clean_sheet__home( $match, $form ){
		$ft = explode ( '-', $match[$form->match_id]->ft_score );
		return $this->check_clean_sheet( $form->pick, $ft, 'home' );
	}

	public function check_clean_sheet_home( $match, $form ){
		$ft = explode ( '-', $match[$form->match_id]->ft_score );
		return $this->check_clean_sheet( $form->pick, $ft, 'home' );
	}
	
	public function check_home_team_clean_sheet( $match, $form ){
		$ft = explode ( '-', $match[$form->match_id]->ft_score );
		return $this->check_clean_sheet( $form->pick, $ft, 'home' );
	}

	public function check_clean_sheet__away( $match, $form ){
		$ft = explode ( '-', $match[$form->match_id]->ft_score );
		return $this->check_clean_sheet( $form->pick, $ft, 'away' );
	}

	public function check_clean_sheet_away( $match, $form ){
		$ft = explode ( '-', $match[$form->match_id]->ft_score );
		return $this->check_clean_sheet( $form->pick, $ft, 'away' );
	}
	
	public function check_away_team_clean_sheet( $match, $form ){
		$ft = explode ( '-', $match[$form->match_id]->ft_score );
		return $this->check_clean_sheet( $form->pick, $ft, 'away' );
	}
	
	/////////////////////// win to nil function //////////////////////
	
	public function check_win_to_nill( $match, $form ){
		$ft = explode ( '-', $match[$form->match_id]->ft_score );
		$pick = $form->pick;
		
		$win = $this->check_win_result( $pick, $ft[0], $ft[1] );
		$clean_sheet = $this->check_clean_sheet( 'Yes', $ft, strtolower($pick));
		
		if( $win === true AND $clean_sheet === true )
			return true;
		else if ( $win === false || $clean_sheet === false)
			return false;
		
		return 10;
	}
	
	public function check_win_to_nil( $match, $form ){
		$ft = explode ( '-', $match[$form->match_id]->ft_score );
		$pick = $form->pick;
		
		$win = $this->check_win_result( $pick, $ft[0], $ft[1] );
		$clean_sheet = $this->check_clean_sheet( 'Yes', $ft, strtolower($pick));
		
		if( $win === true AND $clean_sheet === true )
			return true;
		else if ( $win === false || $clean_sheet === false)
			return false;
		
		return 10;
	}
	
	public function check_win_to_nill__home( $match, $form ){
		$ft = explode ( '-', $match[$form->match_id]->ft_score );
		
		$win = $this->check_win_result( 'Home', $ft[0], $ft[1] );
		$clean_sheet = $this->check_clean_sheet( 'Yes', $ft, 'home');
		
		if( $win === true AND $clean_sheet === true )
			return true;
		else if ( $win === false || $clean_sheet === false)
			return false;
		
		return 10;
	}
	
	public function check_win_to_nill__away( $match, $form ){
		$ft = explode ( '-', $match[$form->match_id]->ft_score );
		
		$win = $this->check_win_result( 'Away', $ft[0], $ft[1] );
		$clean_sheet = $this->check_clean_sheet( 'Yes', $ft, 'away');
		
		if( $win === true AND $clean_sheet === true )
			return true;
		else if ( $win === false || $clean_sheet === false)
			return false;
		
		return 10;
	}
	
	public function check_win_to_nill_home( $match, $form ){
		$ft = explode ( '-', $match[$form->match_id]->ft_score );
		
		$win = $this->check_win_result( 'Home', $ft[0], $ft[1] );
		$clean_sheet = $this->check_clean_sheet( 'Yes', $ft, 'home');
		
		if( $win === true AND $clean_sheet === true )
			return true;
		else if ( $win === false || $clean_sheet === false)
			return false;
		
		return 10;
	}
	
	public function check_win_to_nill_away( $match, $form ){
		$ft = explode ( '-', $match[$form->match_id]->ft_score );
		
		$win = $this->check_win_result( 'Away', $ft[0], $ft[1] );
		$clean_sheet = $this->check_clean_sheet( 'Yes', $ft, 'away');
		
		if( $win === true AND $clean_sheet === true )
			return true;
		else if ( $win === false || $clean_sheet === false)
			return false;
		
		return 10;
	}
	
	public function check_win_to_nil_home( $match, $form ){
		$ft = explode ( '-', $match[$form->match_id]->ft_score );
		
		$win = $this->check_win_result( 'Home', $ft[0], $ft[1] );
		$clean_sheet = $this->check_clean_sheet( 'Yes', $ft, 'home');
		
		if( $win === true AND $clean_sheet === true )
			return true;
		else if ( $win === false || $clean_sheet === false)
			return false;
		
		return 10;
	}
	
	public function check_win_to_nil_away( $match, $form ){
		$ft = explode ( '-', $match[$form->match_id]->ft_score );
		
		$win = $this->check_win_result( 'Away', $ft[0], $ft[1] );
		$clean_sheet = $this->check_clean_sheet( 'Yes', $ft, 'away');
		
		if( $win === true AND $clean_sheet === true )
			return true;
		else if ( $win === false || $clean_sheet === false)
			return false;
		
		return 10;
	}
	
	//////////////////// odd even function ////////////////////////////
	
	public function check_goals_odd_even ( $match, $form ){
		$ft = explode('-', $match[$form->match_id]->ft_score);
		
		return $this->check_odd_evens( $form->pick, $ft[0]+$ft[1] );
	}
	
	public function check_odd_even ( $match, $form ){
		$ft = explode('-', $match[$form->match_id]->ft_score);
		
		return $this->check_odd_evens( $form->pick, $ft[0]+$ft[1] );
	}
	
	public function check_odd_even_first_half ( $match, $form ){
		$ht = explode('-', $match[$form->match_id]->ht_score);
		
		return $this->check_odd_evens( $form->pick, $ht[0]+$ht[1] );
	}
	
	public function check_home_odd_even ( $match, $form ){
		$ft = explode('-', $match[$form->match_id]->ft_score);
		
		return $this->check_odd_evens( $form->pick, $ft[0] );
	}
	
	public function check_away_odd_even ( $match, $form ){
		$ft = explode('-', $match[$form->match_id]->ft_score);
		
		return $this->check_odd_evens( $form->pick, $ft[1] );
	}
	
	
	/////////////////////// Correct score function ////////////////////////////
	
	public function check_final_score ( $match, $form ){
		$pick = $form->pick;
		$score = implode('-',explode('-',$match[$form->match_id]->ft_score));
		
		return ( $pick == $score ) ? true : false;
	}
	
	public function check_correct_score ( $match, $form ){
		$pick = $form->pick;
		$score[] = implode('-',explode('-',$match[$form->match_id]->ft_score));
		$score[] = implode(':',explode('-',$match[$form->match_id]->ft_score));
		
		return ( in_array( $pick, $score ) ) ? true : false;
	}
	
	public function check_correct_score_1st_half ( $match, $form ){
		$pick = $form->pick;
		$score = implode(':',explode('-',$match[$form->match_id]->ht_score));
		
		return ( $pick == $score ) ? true : false;
	}
	
	public function check_correct_score_2nd_half ( $match, $form ){
		$ht = explode('-', $match[$form->match_id]->ht_score);
		$ft = explode('-', $match[$form->match_id]->ft_score);
		$home_score_2nd_half = (int) $ft[0] - (int) $ht[0];
		$away_score_2nd_half = (int) $ft[1] - (int) $ft[1];
		$pick = $form->pick;
		$score = $home_score_2nd_half.":".$away_score_2nd_half;
		
		return ( $pick == $score ) ? true : false;
	}
	
	public function check_exact_goals_number ( $match, $form ){
		$ft = explode('-', $match[$form->match_id]->ft_score);
		
		return $this->check_exact_goals ( $form->pick, array_sum($ft) );
	}
	
	public function check_home_team_exact_goals_number ( $match, $form ){
		$ft = explode('-', $match[$form->match_id]->ft_score);
		var_dump( $form->pick );
		var_dump( $ft[0]);
		return $this->check_exact_goals ( $form->pick, $ft[0] );
	}
	
	public function check_away_team_exact_goals_number ( $match, $form ){
		$ft = explode('-', $match[$form->match_id]->ft_score);
		
		return $this->check_exact_goals ( $form->pick, $ft[1] );
	}
	
	public function check_home_exact_goals ( $match, $form ){
		$ft = explode('-', $match[$form->match_id]->ft_score);
		var_dump($ft);
        var_dump($form->pick);
		return $this->check_exact_goals ( $form->pick, $ft[0] );
	}
	
	public function check_away_exact_goals ( $match, $form ){
		$ft = explode('-', $match[$form->match_id]->ft_score);
		
		return $this->check_exact_goals ( $form->pick, $ft[1] );
	}
	
	public function check_1st_half_exact_goals_number( $match, $form ){
		$ht = explode('-', $match[$form->match_id]->ht_score);
		
		return $this->check_exact_goals ( $form->pick, array_sum($ht) );
	}
	
	public function check_2nd_half_exact_goals_number( $match, $form ){
		$second_half = $this->get_second_half_result( $match, $form );
		
		return $this->check_exact_goals ( $form->pick, array_sum($second_half) );
	}
	
	///////////////////////// Handicap function //////////////////
	
	public function check_handicap_result ( $match, $form ){
		$ft = explode('-', $match[$form->match_id]->ft_score);
		
		return $this->check_handicap_3_way($form->pick, $ft);
		
		return 10;
		
	}
	
	public function check_europian_handicap( $match, $form ){
		$ft = explode('-', $match[$form->match_id]->ft_score);
		
		return $this->check_handicap_3_way($form->pick, $ft);
		
//		return 10;
	}
	
	/////////////////////// double chance function ///////////////////
	
	public function check_double_chance ( $match, $form ){
		$ft = explode('-', $match[$form->match_id]->ft_score);
		
		$pick = explode( ' or ',$form->pick);
		if ( $form->pick == $pick[0] ){
			$pick = explode( '/',$form->pick);
		}
		
		$result1 = $this->check_win_result( trim($pick[0]), $ft[0], $ft[1] );
		$result2 = $this->check_win_result( trim($pick[1]), $ft[0], $ft[1] );
		
		if ( $result1 === true || $result2 === true )
			return true;
		else if ( $result1 === false && $result2 === false )
			return false;
		
		return 10;
	}
	
	public function check_double_chance_1st_half ( $match, $form ){
		$ht = explode('-', $match[$form->match_id]->ht_score);
		
		$pick = explode( ' or ',$form->pick);
		if ( $form->pick == $pick[0] ){
			$pick = explode( '/',$form->pick);
		}
		
		$result1 = $this->check_win_result( trim($pick[0]), $ht[0], $ht[1] );
		$result2 = $this->check_win_result( trim($pick[1]), $ht[0], $ht[1] );
		
		if ( $result1 === true || $result2 === true )
			return true;
		else if ( $result1 === false && $result2 === false )
			return false;
		
		return 10;
	}
	
	public function check_double_chance_2nd_half ( $match, $form ){
		$second = $this->get_second_half_result( $match, $form );
		
		$pick = explode( ' or ',$form->pick);
		if ( $form->pick == $pick[0] ){
			$pick = explode( '/',$form->pick);
		}
		
		$result1 = $this->check_win_result( trim($pick[0]), $second[0], $second[1] );
		$result2 = $this->check_win_result( trim($pick[1]), $second[0], $second[1] );
		
		if ( $result1 === true || $result2 === true )
			return true;
		else if ( $result1 === false && $result2 === false )
			return false;
		
		return 10;
	}
	
	////////////// check Goals after match /////////////////////
	
	public function check_live_bet_after_match ( $match, $form ){
		$matches = $match[$form->match_id];
		if($matches->home_score == $form->home_score && $matches->away_score == $form->away_score){
			if( $form->goal_score == 1 )
				return true;
			else if ( $form->goal_score == 0 )
				return false;
		}
		
		if( $matches->home_score > $form->home_score )
			$win[] = 'Home';
		if( $matches->away_score > $form->away_score )
			$win[] = 'Away';
		
		if( sizeof( $win )> 1 )
			return 10;
		var_dump($win);
		if( strpos( $win[0], $form->pick) !== false )
			return true;
		return false;
	}
	
	public function check_last_team_to_score( $match, $form ){
		/// check shavad
		return $this->get_score_number( $match, $form, 100 );
		
	}
	
	public function check_1st_goal ( $match, $form ){
		return $this->get_score_number( $match, $form, 1 );
	}
	
	public function check_2nd_goal ( $match, $form ){
		return $this->get_score_number( $match, $form, 2 );
	}
	
	public function check_3rd_goal ( $match, $form ){
		return $this->get_score_number( $match, $form, 3 );
	}
	
	public function check_4th_goal ( $match, $form ){
		return $this->get_score_number( $match, $form, 4 );
	}
	
	public function check_5th_goal ( $match, $form ){
		return $this->get_score_number( $match, $form, 5 );
	}
	
	public function check_6th_goal ( $match, $form ){
		return $this->get_score_number( $match, $form, 6 );
	}
	
	public function check_7th_goal ( $match, $form ){
		return $this->get_score_number( $match, $form, 7 );
	}
	
	public function check_8th_goal ( $match, $form ){
		return $this->get_score_number( $match, $form, 8 );
	}
	
	public function check_9th_goal ( $match, $form ){
		return $this->get_score_number( $match, $form, 9 );
	}
	
	public function get_score_number ( $match, $form, $number ){
		
		if ( isset( $match[$form->match_id]->events) ){
			$goal = isset( $match[$form->match_id]->events)?$match[$form->match_id]->events : null;
			$result = $this->get_result_from_json( $goal->goals, $number );
		}
		else
			$result  = $this->get_result ( $match, $form, $number );
		
		var_dump( $result );
		var_dump( $form->pick);
		
		
		if ( $result == 10 )
			return 10;
		else if ( $result == 'No Goal' || empty($result))
			return (strpos( $form->pick, 'No' ) !== false) ? true : false;
		
		$match_result = explode( '-', $result['result'] );
		
		if ( $number == 100 ){
			if ( array_sum( $match_result ) == 0 && strpos( $form->pick, 'No' ) !== false )
				return true;
			else if ( strpos( $form->pick, $result['side']) !== false  )
				return true;
			else
				return false;	
		}
		
		else if ( array_sum($match_result) == $number ){
			if ( strpos( $form->pick, $result['side'] ) !== false )
				return true;
			else
				return false;
		}
		
		return 10;
		
		
		
		
		
	}
	
	public function get_result ( $match, $form, $number ){
		$id = $form->match_id;
		$pick = $form->pick;
		
		$db = new DB();
		var_dump($id);
		$query = "SELECT * FROM online_result WHERE match_id = '$id' ORDER BY id ASC";
		
		$result = $db->get_query( $query );
		$length = sizeof( $result );
		
		$status = ( !isset($match[ $form->match_id]) ) ? 'FT' : $match[$form->match_id]->status;
		
		if( $number != 100 ){
			if ( isset( $result[ $number ] ) )
				return $result [ $number ];
			else
				return 'No Goal';
			
		}
		else if ( $number == 100 && $status == 'FT' ){
			return $result [ $length-1 ];
		}
		
		return 10;
	}
	
	public function get_result_from_json ( $result, $number ){
		$length = sizeof( $result );
		
		if( $number != 100 ){
			if ( isset( $result[ $number ] ) ){
				$result [$number-1]->side = $result [$number-1]->team;
				return (array) $result [ $number-1 ];
			}
				
			else
				return 'No Goal';
			
		}
		else if ( $number == 100){
			$result [$length-1]->side = $result [$length-1]->team;
			return (array) $result [ $length-1 ];
		}
		
		return 10;
		
		
	}
	
	///////////////////////////////////////////////
	
	public function check_draw_no_bet( $match, $form ){
		$ft = explode('-', $match[$form->match_id]->ft_score);
		
		if( $ft[0] == $ft[1] )
			return 3; // cancel bet
		else if ( $ft[0] != $ft[1] )
			return $this->check_win_result( $form->pick, $ft[0], $ft[1] );
		else
			return 10; 
	}
	
	public function check_highest_scoring_half( $match, $form ){
		$ht = explode('-', $match[$form->match_id]->ht_score);
		$second_h = $this->get_second_half_result( $match, $form );
		$pick = $form->pick;
		
		$first_half = array_sum( $ht );
		$second_half = array_sum( $second_h );
		
		$win_label = array();
		if( $first_half > $second_half )
			$win_label = ['1st Half'];
		else if ( $first_half < $second_half )
			$win_label = ['2nd Half'];
		else if ( $first_half == $second_half )
			$win_label = ['Draw'];
		
		if( !empty( $win_label ) )
			return ( in_array( $pick, $win_label ) ) ? true : false;
		
		return 10;
		
	}
	
	public function check_results_both_teams_to_score( $match, $form ){
		$ft = explode('-', $match[$form->match_id]->ft_score);
		$pick = explode('/',$form->pick);
		
		$result = $this->check_win_result($pick[0], $ft[0], $ft[1]);
		
		$home_score = $this->check_score( $pick[1], $ft, 'home');
		$away_score = $this->check_score( $pick[1], $ft, 'away');

		if( $result === true && $this->check_both_score( $pick[1], $home_score, $away_score ) )
			return true;
		else if ( $result === false || !$this->check_both_score( $pick[1], $home_score, $away_score ) )
			return false;
		
		return 10;
	}
	
	public function check_result_total_goals( $match, $form ){
		$ft = explode('-', $match[$form->match_id]->ft_score);
		$pick = explode('/', $form->pick );
		
		$result = $this->check_win_result($pick[0], $ft[0], $ft[1]);
		$total_golas = $this->check_over_under_label( $pick[1], $ft[0], $ft[1])	;
		
		if( $result === true && $total_golas === true )
			return true;
		else if ( $result === false || $total_golas === false )
			return false;
		
		return 10;
	
	}
	
	public function check_half_time_full_time ( $match, $form ){
		$ht = explode('-', $match[$form->match_id]->ht_score);
		$ft = explode('-', $match[$form->match_id]->ft_score);
//		$second_half = $this->get_second_half_result( $match, $form );
		
		$pick = explode( '-', $form->pick );
		
		$ht_win = $this->check_win_result($pick[0],$ht[0],$ht[1]);
		$second_win = $this->check_win_result($pick[1],$ft[0],$ft[1]);
		
		if( $ht_win === true && $second_win === true )
			return true;
		else if ( $ht_win === false || $second_win === false )
			return false;
		
		return 10;
	}
	
	public function check_win_both_halves ( $match, $form ){
		$ht = $ht = explode('-', $match[$form->match_id]->ht_score);
		$second_half = $this->get_second_half_result( $match, $form );
		$pick = $form->pick;
		
		$ht_win = $this->check_win_result($pick,$ht[0],$ht[1]);
		$second_win = $this->check_win_result($pick,$second_half[0],$second_half[1]);
		
		if( $ht_win === true && $second_win === true )
			return true;
		else if ( $ht_win === false || $second_win === false )
			return false;
		
		return 10;
	}
	
	public function check_home_win_both_halves ( $match, $form ){
		$ht = $ht = explode('-', $match[$form->match_id]->ht_score);
		$second_half = $this->get_second_half_result( $match, $form );
		$pick = $form->pick;
		
		$ht_win = $this->check_score( $pick, $ht, 'home');
		$second_win = $this->check_score( $pick, $second_half, 'home');
		
		if( $ht_win === true && $second_win === true )
			return true;
		else if ( $ht_win === false || $second_win === false )
			return false;
		
		return 10;
	}
	
	public function check_away_win_both_halves ( $match, $form ){
		$ht = $ht = explode('-', $match[$form->match_id]->ht_score);
		$second_half = $this->get_second_half_result( $match, $form );
		$pick = $form->pick;
		
		$ht_win = $this->check_score( $pick, $ht, 'away');
		$second_win = $this->check_score( $pick, $second_half, 'away');
		
		if( $ht_win === true && $second_win === true )
			return true;
		else if ( $ht_win === false || $second_win === false )
			return false;
		
		return 10;
	}
	
	public function check_to_win_either_half ( $match, $form ){
		$ht =  explode('-', $match[$form->match_id]->ht_score);
		$second_half = $this->get_second_half_result( $match, $form );
		
		$ht_win = $this->check_win_result($form->pick,$ht[0],$ht[1]);
		$second_win = $this->check_win_result($form->pick,$second_half[0],$second_half[1]);
		
		if( $ht_win === true || $second_win === true )
			return true;
		else if ( $ht_win === false && $second_win === false )
			return false;
		
		return 10;
		
	}
	
	///////////////////////////////////////////
	
	public function checkOverUnderLabel($pick,$home_score,$away_score){
		$goals = ( int ) $home_score + ( int ) $away_score;
		
		$OverUnder = explode(' ' , $pick);
//		var_dump(( float ) $OverUnder[2]);
		if( ( float ) $OverUnder[2] == ( float ) $goals )
			return 1;
		if ( ( float ) $OverUnder[2] < ( float ) $goals )
			$matchGoalResult = 'Over : ' . $OverUnder[2];
		else
			$matchGoalResult = 'Under : ' . $OverUnder[2];
//		var_dump($matchGoalResult);
//		var_dump($pick);
		if ( $matchGoalResult == $pick )
			return true;
		else
			return false;
	}
	

	
}
?>