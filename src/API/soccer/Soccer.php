<?php 

class Soccer{
	
	
	/////////////////////////////
	
	public function __construct(){
		
	}
	
	
	public function get_content( $api_token){
		var_dump( $api_token );
	}
	
	public function JSONGoalserve( $value, $match, $odds ){

		$this->convertHTGoals($ht , $match->ht->attributes->score );
		
		$home = $match->localteam;
		$away = $match->visitorteam;
		///////////////// Match Status //////////////
		$status = $this->checkStatusGoalServe($match->attributes->status, $match->attributes->formatted_date);	
		
		///////////// Match Events /////////////////
		if( isset($match->events) )
			$events = $this->createEvents( $match );
		else
			$events = null;
		///////////// Match Goal Number //////////////////
		$home_goal = $this->checkGoalNumber($home->attributes->goals);
		$away_goal = $this->checkGoalNumber($away->attributes->goals);
		
		$json = [
				'sport' => 'soccer',
				'API_id' =>$match->attributes->id,
				'id' => $match->attributes->id,
				'ht_score' => $ht,
				'ft_score' => $home_goal."-".$away_goal,
				'et_score' => null,
				'home_team_id' => $home->attributes->id,
				'away_team_id' => $away->attributes->id,
				'home_score' => $home_goal,
				'away_score' =>  $away_goal,
				'home_score_penalties' => null,
				'away_score_penalties' =>  null,

				'date_time_tba' => $match->attributes->formatted_date,
				'starting_date' => $match->attributes->formatted_date,
				'starting_time' => $match->attributes->time,
				'timestamp' => $match->attributes->time,
				'status' => $status, /*$info->state_name,*/
				'minute' => 0,
				'second' => 0,
				'injury_time' => null,
				'extra_time' => null,
				'competition_id' => $value->attributes->id,
				'winning_odds_calculated' => false,
				
				'events' => $events,
			
				'homeTeam' => [
						'id' => $this->convertZero($home->attributes->id),
						'legacy_id' => null,
						'name' => $this->convertZero($home->attributes->name),
						'logo' => null,
						'stats' => null,
						'yellow_cards' => 0,
						'red_cards' =>0,
						'corners' => 0,
						],
				'awayTeam' => [
						'id' => $this->convertZero($away->attributes->id),
						'legacy_id' => null,
						'name' => $this->convertZero($away->attributes->name),
						'stats' => null,
						'yellow_cards' => 0,
						'red_cards' =>0,
						'corners' => 0,
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
	
	public function checkStatusGoalServe($status, $date){
		$status_array = ['FT','HT','NS','Live'];
		$ft_array = ['Finished'];
		$ns_array = ['Not Started'];
		
		if(in_array($status,$status_array))
			return $status;
		else if(in_array($status,$ns_array))
			return 'NS';
		else if(in_array($status,$ft_array))
			return 'FT';
		else if ( $date > date('d.m.Y') )
			return 'NS';
		if( $date == date('d.m.Y') && $status > date('H:i'))
			return 'NS';
		else if ( $date == date('d.m.Y') && $status < date("H:i",strtotime("+2 Hour")))
			return 'FT';
		return 'No status';
	}
	
	public function createEvents( $match ){
		$events_converted = new stdClass();
		if( !isset( $match->events->event ) )
			return null;
		$event = $match->events->event;
		$home_team_name = $match->localteam->attributes->name;
		$away_team_name = $match->visitorteam->attributes->name;
		
		foreach( $event as $key_event=>$value_events ){
			if( ! isset( $value_events->attributes) )
				return null;
			$events = $value_events->attributes;
			if( $events->type == 'goal' ){
				$extra_min = isset($events->extra_min) ? $events->extra_min : 0;
				$goals = new stdClass();
				$goals->team = ( $events->team == 'localteam' ) ? 'Home' : 'Away';
				$goals->team_name = ( $events->team == 'localteam' ) ? $home_team_name : $away_team_name;
				$goals->minute = $events->minute + $extra_min ;
				$goals->result = $this->createResults( $events->result );
				
				$events_converted->goals[] = $goals;
			}
		}
		
		if( isset( $events_converted->goals ) )
			return $events_converted;
		return null;
		
		
	}
	
	public function checkGoalNumber( $data ){
		if( is_numeric($data) )
			return $data;
		
		return 0;
	}
	
	public function createResults ( $results ){
		
		if ( empty( $results ) )
			return 0-0 ;
		
		return implode( '-', explode(' - ', trim(trim($results, '['), ']'))) ;
	}
	
	public function createJSON($value, $odds){
		
		
		$info = $value->info;
		
//		$team = $this->getStats( $value->stats, 'team' );
		$team = $this->getTeamName( $info->name );
		
		if ( isset ($value->results) ){
		
			$ht = $this->getMatchResult( $value->results, '1');
			$ft = $this->getMatchResult( $value->results, '2');
			$goals = $this->getStatsFromResults( $value->results, 'goals');
			$corner = $this->getStatsFromResults( $value->results, 'corners');
			$penalty = $this->getStatsFromResults( $value->results, 'penalties');
			$yellowcard = $this->getStatsFromResults( $value->results, 'yellowcards');
			$redcard = $this->getStatsFromResults( $value->results, 'redcards');
		}
		else {
		
			$ft = $this->getStats( $value->stats, 'goal' );
			$ht = $this->getStats( $value->stats, 'halftime' );
			$penalty = $this->getStats( $value->stats, 'penalty' );
			$yellowcard = $this->getStats( $value->stats, 'yellowcard' );
			$corner = $this->getStats( $value->stats, 'corner' );
			$redcard = $this->getStats( $value->stats, 'redcard' );
		
		}
		
		

//		$ft = $this->getStats( $value->stats, 'goal' );
//		$ht = $this->getStats( $value->stats, 'halftime' );
//		$penalty = $this->getStats( $value->stats, 'penalty' );
//		$yellowcard = $this->getStats( $value->stats, 'yellowcard' );
//		$corner = $this->getStats( $value->stats, 'corner' );
//		$redcard = $this->getStats( $value->stats, 'redcard' );
		
		
		
		
		$stats1 = ['yellowcards' => $yellowcard->home,
				   'red_cards' => $redcard->home,
				   'corners' => $corner->home,
				  ];
		$stats2 = ['yellowcards' => $yellowcard->away,
				   'red_cards' => $redcard->away,
				   'corners' => $corner->away,
				  ];
		
		if ( isset( $value->results ) ){
			$a = 0;
		$status_name = $this->checkMatchStatusNew($value->results->results->{$a}->time_status);	
		}
		else {
			$status_name = ( $info->period == 'Finished') ? 'FT' : 'LIVE';
			if ( $status_name == 'LIVE'){
				$status_name = ( $info->state == '1017' ) ? 'FT' : 'LIVE';
			}
		}
		
		
		
		$team->home = str_replace(["'", "."],' ', $team->home);
		$team->away = str_replace(["'", "."],' ', $team->away);
		$info->league = str_replace(["'", "."],' ', $info->league);
		
		
		$team->home = str_replace("'", '', iconv('utf-8', 'ascii//TRANSLIT', $team->home));
		$team->away = str_replace("'", '', iconv('utf-8', 'ascii//TRANSLIT', $team->away));
		$info->league = str_replace("'", '', iconv('utf-8', 'ascii//TRANSLIT', $info->league));
		
		
//		$this->check_live_match_goals( $value );
			
		$json = [
				'sport' =>'soccer',
//                'stats' => $value->stats1,
				'API_id' =>$info->id,
				'id' => $info->id,
				'ht_score' => $ht->home.'-'.$ht->away,
				'ft_score' => implode('-',explode(':',$info->score)),
				'et_score' => null,
				'home_team_id' => null,
				'away_team_id' => null,
				'home_score' => $ft->home,
				'away_score' =>  $ft->away,
				'home_score_penalties' => $penalty->home,
				'away_score_penalties' => $penalty->away,
 
				'formation' => [
						'home' => null ,
						'away' => null
						],
				'date_time_tba' => $info->start_time,
				'spectators' => null,
				'starting_date' => $info->start_date,
				'starting_time' => $info->start_time,
				'timestamp' => $info->start_time,
				'status' => $status_name, /*$info->state_name,*/
				'period' => $info->period, /*$info->state_name,*/
				'minute' => $info->minute,
				'second' => 0,
				'injury_time' => null,
				'extra_time' => null,
				'competition_id' => $this->convertSpaceToUnderline($info->league),
				
				'deleted' => isset($value->core->stopped) ? $value->core->stopped : 0,
				'result_only' => null,
				'winning_odds_calculated' => false,

				'homeTeam' => [
						'id' => $this->convertSpaceToUnderline($team->home),
						'legacy_id' => null,
						'name' => $team->home,
						'logo' => null,
						'yellow_cards' => $yellowcard->home,
						'red_cards' =>$redcard->home,
						'corners' => $corner->home,
						'stats' => $stats1,
						],
				'awayTeam' => [
						'id' => $this->convertSpaceToUnderline($team->away),
						'legacy_id' => null,
						'name' => $team->away,
						'logo' => null,
						'yellow_cards' => $yellowcard->away,
						'red_cards' =>$redcard->away,
						'corners' => $corner->away,
						'stats'=> $stats2,
						],
				'odds' => $odds,

				'competition' => [
							'id' => $this->convertSpaceToUnderline($info->league),
							'cup' => null,
							'country_id' => null,
							'name' => $info->league,
							'active' => true,
						]
				];	
		
		
		return $json;
	}
	
	public function createJSONForResults($value, $odds){
		
		
		if ( $value->results->success == 1 ){
			var_dump('success');
			$id = $value->results->id;
		}
		
		
		$first = '0';
		$results = $value->results->results->{$first};
		
		
//		$team = $this->getStats( $value->stats, 'team' );

		
		$team = $this->getTeamNameResults ( $results );
		
		$ht = $this->getMatchResult( $results, '1');
		$ft = $this->getMatchResult( $results, '2');
		
		$goals = $this->getStatsFromResults( $results, 'goals');
		$corner = $this->getStatsFromResults( $results, 'corners');
		$penalty = $this->getStatsFromResults( $results, 'penalties');
		$yellowcard = $this->getStatsFromResults( $results, 'yellowcards');
		$redcard = $this->getStatsFromResults( $results, 'redcards');

	
		
		$stats1 = ['yellowcards' => $yellowcard->home,
				   'red_cards' => $redcard->home,
				   'corners' => $corner->home,
				  ];
		$stats2 = ['yellowcards' => $yellowcard->away,
				   'red_cards' => $redcard->away,
				   'corners' => $corner->away,
				  ];
	
		$status_name = $this->checkMatchStatusNew($results->time_status);	
	
		
		$team->home = str_replace(["'", "."],' ', $team->home);
		$team->away = str_replace(["'", "."],' ', $team->away);
		$results->league->name = str_replace(["'", "."],' ', $results->league->name);
		
		
		$team->home = str_replace("'", '', iconv('utf-8', 'ascii//TRANSLIT', $team->home));
		$team->away = str_replace("'", '', iconv('utf-8', 'ascii//TRANSLIT', $team->away));
		$results->league->name = str_replace("'", '', iconv('utf-8', 'ascii//TRANSLIT', $results->league->name));
		
//		$this->check_live_match_goals( $value );
			
		$json = [
				'sport' =>'soccer',
//                'stats' => $value->stats1,
				'API_id' =>$id,
				'id' => $id,
				'ht_score' => $ht->home.'-'.$ht->away,
				'ft_score' => implode('-',explode(':', $results->ss)),
				'et_score' => null,
				'home_team_id' => null,
				'away_team_id' => null,
				'home_score' => $ft->home,
				'away_score' =>  $ft->away,
				'home_score_penalties' => $penalty->home,
				'away_score_penalties' => $penalty->away,
 
				'formation' => [
						'home' => null ,
						'away' => null
						],
				'date_time_tba' => $results->time,
				'spectators' => null,
				'starting_date' => null,
				'starting_time' => null,
				'timestamp' => $results->time,
				'status' => $status_name, /*$info->state_name,*/
				'period' => $status_name, /*$info->state_name,*/
				'minute' => 0,
				'second' => 0,
				'injury_time' => null,
				'extra_time' => null,
				'competition_id' => $this->convertSpaceToUnderline($results->league->name),
				
				'deleted' => null,
				'result_only' => null,
				'winning_odds_calculated' => false,

				'homeTeam' => [
						'id' => null,
						'legacy_id' => null,
						'name' => $team->home,
						'logo' => null,
						'yellow_cards' => $yellowcard->home,
						'red_cards' =>$redcard->home,
						'corners' => $corner->home,
						'stats' => $stats1,
						],
				'awayTeam' => [
						'id' => null,
						'legacy_id' => null,
						'name' => $team->away,
						'logo' => null,
						'yellow_cards' => $yellowcard->away,
						'red_cards' =>$redcard->away,
						'corners' => $corner->away,
						'stats'=> $stats2,
						],
				'odds' => null,

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
	
	public function getStats ( $stats, $type ){
		
		foreach ( $stats as $value ){
			if ( strtolower($value->name) == "i$type" ){
				return $value;
			}
		}
	}
	
	public function getMatchResult ( $results, $type ){
		
		$score = $results->scores;
		
		$new_class = new stdClass();
		$new_class->home = $score->{$type}->home;
		$new_class->away = $score->{$type}->away;
		
		return $new_class;
		
	}
	
	public function getStatsFromResults ( $results, $type ){
		$first = 0;
		$second = 1;
		
		$stats = $results->stats;
		$new_class = new stdClass();
		foreach ( $stats as $key=>$value ){	
			if ( strtolower($key) == $type ){
				$new_class->home = $value->{$first};
				$new_class->away = $value->{$second};
				return $new_class;
			}
		}
		
	}
	
	public function convertSpaceToUnderline ( $name ){
		return str_replace([' ','/','-',',','?'],['_','_','','_',''], trim(strtolower($name)));
	}
	
	public function convertHTGoals ( &$ht, $ht_score ){
		
		$ht = ltrim($ht_score, '[');
		$ht = rtrim($ht, ']');
		if ( empty($ht) || $ht == '-')
			$ht= '0-0';
	}
		
	public function convertZero ( $number ){
		if ( $number == '' )
			return '0';
		return $number;
	}

	public function getTeamName ( $name ){

	    $team = explode('vs', $name);
        $return_value = new stdClass();
        $return_value->home = trim( $team[0]);
        $return_value->away = trim( $team[1]);

        return $return_value;

    }
	
	public function getTeamNameResults ( $results ){
		
		$return_value = new stdClass();
		$return_value->home = trim( $results->home->name);
		$return_value->away = trim( $results->away->name);
		
		return $return_value;
		
		
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
//		var_dump( $result );
//		var_dump( $status[$result]);
		return $status[ $result ];
	}
	

	
}
?>