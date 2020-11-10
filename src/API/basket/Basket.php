<?php 

class Basket{
	
	
	/////////////////////////////
	
	public function __construct(){
		
	}
	
	
	public function get_content( $api_token){
		var_dump( $api_token );
	}
	
	public function JSONGoalserve( $value, $match, $odds ){
		
		$home = $match->localteam;
		$away = $match->awayteam;
	
//		$status = $this->checkStatusGoalServe($match->attributes->status);
//		if( isset($match->events) )
//			$events = $this->createEvents( $match );
//		else
//			$events = null;
		
		
		$home_goal = $this->checkGoalNumber($home->attributes->totalscore);
		$away_goal = $this->checkGoalNumber($away->attributes->totalscore);
		
		$json = [
				'sport' => 'basket',
				'API_id' =>$match->attributes->id,
				'id' => $match->attributes->id,
//				'ht_score' => $ht->attributes->score,
				'ft_score' => $home_goal."-".$away_goal,
				'et_score' => null,
				'home_team_id' => $home->attributes->id,
				'away_team_id' => $away->attributes->id,
				'home_score' => $home_goal,
				'away_score' =>  $away_goal,

				'date_time_tba' => $match->attributes->date,
				'starting_date' => $match->attributes->date,
				'starting_time' => $match->attributes->time,
				'timestamp' => $match->attributes->time,
				'status' => $match->attributes->status, /*$info->state_name,*/
				'minute' => 0,
				'second' => 0,
				'injury_time' => null,
				'extra_time' => null,
				'competition_id' => $value->attributes->id,
				'venue_id' => null,
				'season_id' => null,
				'round_id' => null,
				'stage_id' => null,
				'referee_id' => null,
				'aggregate' => null,
				'placeholder' => false,
				'deleted' => null,
				'result_only' => null,
				'winning_odds_calculated' => false,
				
//				'events' => $events,
			
				'homeTeam' => [
						'id' => $this->convertZero($home->attributes->id),
						'name' => $this->convertZero($home->attributes->name),
						's1' => $this->convertZero($home->attributes->q1),
						's2' => $this->convertZero($home->attributes->q2),
						's3' => $this->convertZero($home->attributes->q3),
						's4' => $this->convertZero($home->attributes->q4),
						'ot' => $this->convertZero($home->attributes->ot),
						'stats' => null,
						'score' => $this->convertZero($home->attributes->totalscore),
						],
				'awayTeam' => [
						'id' => $this->convertZero($away->attributes->id),
						'name' => $this->convertZero($away->attributes->name),
						's1' => $this->convertZero($away->attributes->q1),
						's2' => $this->convertZero($away->attributes->q2),
						's3' => $this->convertZero($away->attributes->q3),
						's4' => $this->convertZero($away->attributes->q4),
						'ot' => $this->convertZero($away->attributes->ot),
						'stats' => null,
						'score' => $this->convertZero($away->attributes->totalscore),
						],
				'odds' => $odds,

				'competition' => [
							'id' => $value->attributes->id,
							'cup' => null,
							'country_id' => null,
							'name' => $value->attributes->name,
							'active' => true,
						]
				];	
		
		return $json;
	}
	
	
	public function checkGoalNumber ( $goal ){
		return $goal;
	}
	
	public function createJSON($value, $odds){
		$info = $value->info;
		$stats = $this->getAllStates( $value->stats);
//		var_dump( $stats );
		$score = $this->getResult( $info->score );
		
		$ft[0] = $this->convertToNumber($stats['T']->home);
		$ft[1] = $this->convertToNumber($stats['T']->away);
		
		$ht[0] = $this->convertToNumber($stats['Half']->home);
		$ht[1] = $this->convertToNumber($stats['Half']->away);
		
		$player_name = explode( 'vs', $info->name );
		$home_score = $this->getScore( $score, 0 );
		$away_score = $this->getScore( $score, 1 );
		$time = explode(':', $info->minute );
		

		if ( isset( $value->results ) ){
		$status_name = $this->checkMatchStatusNew($value->results->results[0]->time_status);	
		}
		else {
			$status_name = ( $info->period == 'Finished') ? 'FT' : 'LIVE';
			if ( $status_name == 'LIVE'){
				$status_name = ( $info->state == '1084' ) ? 'FT' : 'LIVE';
			}
		}
		
		$json = [
				'sport' => 'basket',
				'API_id' =>$info->id,
				'id' => $info->id,
				'ht_score' => $ht[0]."-".$ht[1],
				'ft_score' => $ft[0]."-".$ft[1],
				'et_score' => null,
				'home_team_id' => trim( $player_name[0] ),
				'away_team_id' => trim( $player_name[1] ),
				'home_score' => $ft[0],
				'away_score' =>  $ft[1],

				'starting_date' => $info->start_date,
				'starting_time' => $info->start_time,
				'period' => $info->period, /*$info->state_name,*/
				'status' => $status_name, /*$info->state_name,*/
				'state' => $state,
				'minute' => $time[0],
				'second' => 0,
				'injury_time' => null,
				'extra_time' => null,
				'competition_id' => $this->convertSpaceToUnderline( $info->league ),
				'deleted' => null,
				'result_only' => null,
				'winning_odds_calculated' => false,
				
//				'events' => $events,
			
				'homeTeam' => [
						'id' => trim( $player_name[0] ),
						'name' => trim( $stats['ITeam']->home ),
						's1' => $this->convertToNumber($stats[1]->home),
						's2' => $this->convertToNumber($stats[2]->home),
						's3' => $this->convertToNumber($stats[3]->home),
						's4' => $this->convertToNumber($stats[4]->home),
						'ot' => $this->convertToNumber($stats['OT']->home),
						't' => $this->convertToNumber($stats['T']->home),
						'stats' => null,
						'score' =>$ft[0],
						],
				'awayTeam' => [
						'id' => trim( $player_name[1] ),
						'name' => trim( $stats['ITeam']->away ),
						's1' => $this->convertToNumber($stats[1]->away),
						's2' => $this->convertToNumber($stats[2]->away),
						's3' => $this->convertToNumber($stats[3]->away),
						's4' => $this->convertToNumber($stats[4]->away),
						'ot' => $this->convertToNumber($stats['OT']->away),
						't' => $this->convertToNumber($stats['T']->away),
						'stats' => null,
						'score' => $ft[1],
						],
				'odds' => $odds,

				'competition' => [
							'id' => $this->convertSpaceToUnderline( $info->league ),
							'cup' => null,
							'country_id' => null,
							'name' => $info->league,
							'active' => true,
						]
				];	
		
		return $json;
	}
	
	
	///////////////// results //////////////
	public function createJSONForResults($value, $odds){
		
		if ( $value->results->success == 1 ){
			$id = $value->results->id;
		}
		
		
		$first = '0';
		$results = $value->results->results->{$first};
		
		
//		$team = $this->getStats( $value->stats, 'team' );

		
		$team = $this->getTeamNameResults ( $results );
		
		$set_1 = $this->getMatchResult( $results, '1');
		$set_2 = $this->getMatchResult( $results, '2');
		$set_3 = $this->getMatchResult( $results, '3');
		$set_4 = $this->getMatchResult( $results, '4');
		$set_5 = $this->getMatchResult( $results, '5');
		
//		aces
//		double_faults
//		win_1st_serve
//		break_point_conversions
		
//		$corner = $this->getStatsFromResults( $results, 'corners');
		
	
		$status_name = $this->checkMatchStatusNew($results->time_status);	
		
		$team->home->name = str_replace(["'", "."],' ', $team->home->name);
		$team->away->name = str_replace(["'", "."],' ', $team->away->name);
		$results->league->name = str_replace(["'", "."],' ', $results->league->name);
		
		
		$team->home->name = str_replace("'", '', iconv('utf-8', 'ascii//TRANSLIT', $team->home->name));
		$team->away->name = str_replace("'", '', iconv('utf-8', 'ascii//TRANSLIT', $team->away->name));
		$results->league->name = str_replace("'", '', iconv('utf-8', 'ascii//TRANSLIT', $results->league->name));
		
//		$ft = $this->getTennisScores( $set_1, $set_2, $set_3, $set_4, $set_5);
//		$this->check_live_match_goals( $value );
		
		$ft = explode('-', $results->ss);
		
		$json = [
				'sport' => 'basket',
				'API_id' =>$id,
				'id' => $id,
				'ht_score' => $ft[0]."-".$ft[1],
				'ft_score' => null,
				'et_score' => null,
				'home_team_id' => $team->home->id,
				'away_team_id' => $team->away->id,
				'home_score' => $ft[0],
				'away_score' =>  $ft[1],

				'starting_date' => $results->time,
				'starting_time' => $results->time,
				'period' => $status_name, /*$info->state_name,*/
				'status' => $status_name, /*$info->state_name,*/
				'state' => null,
				'minute' => 0,
				'second' => 0,
				'injury_time' => null,
				'extra_time' => null,
				'competition_id' => $results->league->id,
				'deleted' => null,
				'result_only' => null,
				'winning_odds_calculated' => false,
				
//				'events' => $events,
			
				'homeTeam' => [
						'id' => trim( $team->home->id ),
						'name' => trim( $team->away->name ),
						's1' => $set_1->home,
						's2' => $set_2->home,
						's3' => $set_3->home,
						's4' => $set_4->home,
//						'ot' => $this->convertToNumber($stats['OT']->home),
//						't' => $this->convertToNumber($stats['T']->home),
						'stats' => null,
						'score' => $ft[0],
						],
				'awayTeam' => [
						'id' => trim( $team->away->id ),
						'name' => trim( $stats['ITeam']->away ),
						's1' => $set_1->away,
						's2' => $set_2->away,
						's3' => $set_3->away,
						's4' => $set_4->away,
//						'ot' => $this->convertToNumber($stats['OT']->away),
//						't' => $this->convertToNumber($stats['T']->away),
						'stats' => null,
						'score' => $ft[1],
						],
//				'odds' => $odds,

				'competition' => [
							'id' => $results->league->id,
							'cup' => null,
							'country_id' => null,
							'name' => $results->league->name,
							'active' => true,
						]
				];	
	
		return $json;
	}
	
	public function getTeamNameResults ( $results ){
		
		$return_value = new stdClass();
		$return_value->home->name 	= trim( $results->home->name);
		$return_value->away->name 	= trim( $results->away->name);
		$return_value->home->id		= $results->home->id;
		$return_value->away->id 	= $results->away->id;
		
		return $return_value;
		
		
	}
	
	public function getMatchResult ( $results, $type ){
		
		$score = $results->scores;
		
		$new_class = new stdClass();
		$new_class->home = isset($score->{$type}->home) ? $score->{$type}->home : 0;
		$new_class->away = isset($score->{$type}->away) ? $score->{$type}->away : 0;
		
		return $new_class;
		
	}
	
	////////////////////////////////////////////////////////////
	
	public function getStats ( $stats, $type ){
		foreach ( $stats as $value ){
			if ( strtolower($value->name) == "i$type" ){
				return $value;
			}
		}
	}
	
	public function getAllStates ( $stats ){
		$result = array();
		foreach ( $stats as $value ){
			$result[$value->name] = $value;
		}
		return $result;
	}
	
	public function getResult ( $score ){
		$score_array = explode(',', $score);
		$scores = array();
		foreach ( $score_array as $value ){
			$scores[] = $value;
		}
		return $scores;
	}
	
	public function convertToNumber ( $number ){
		if ( $number == '')
			return 0;
		return $number;
		
	}
	
	public function getNumberWinSet ( $score ){
		$home_win = 0;
		$away_win = 0;
		foreach ( $score as $value ){
			$sets_goal = explode(':', $value );
			if ( $sets_goal[0] > $sets_goal[1] )
				$home_win++;
			else if ( $sets_goal[0] < $sets_goal[1])
				$away_win++;
		}
		return [ $home_win, $away_win ];
	}
	
	public function getScore ( $score, $team ){
		$score_array = array();
		foreach ( $score as $value ){
			$scores =  explode(':', $value );
			$score_array[] = $scores[$team];
		}
		return $score_array;
	}
	
	public function convertSpaceToUnderline ( $name ){
		return str_replace([' ','/','-',',','?'],['_','_','','_',''], trim(strtolower($name)));
	}
	
	public function convertZero ( $number ){
		if ( $number == '' )
			return '0';
		return $number;
	}

	public function checkMatchStatusNew ( $result ){
		$status = [
			"0"  => "Not Started",
			"1"  => "LIVE", /* InPlay */
			"2"  => "TO BE FIXED",
			"3"  => "FT", /* Ended */
			"4"  => "Postponed",
			"5"  => "Cancelled",
			"6"  => "Walkover",
			"7"  => "Interrupted",
			"8"  => "Abandoned",
			"9"  => "Retired",
			"99"  => "Removed",
		];
		
		return $status[ $result ];
	}
}
?>