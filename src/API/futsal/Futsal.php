<?php 

class Futsal{
	
	
	/////////////////////////////
	
	public function __construct(){
		
	}
	
	
	public function get_content( $api_token){
		var_dump( $api_token );
	}
	
	public function JSONGoalserve( $value, $match, $odds ){
		
//		var_dump( $odds['data'][0]['types']['data'][0]['odds']);
		
		$home = $match->localteam;
		$away = $match->awayteam;
	
//		$status = $this->checkStatusGoalServe($match->attributes->status);
//		if( isset($match->events) )
//			$events = $this->createEvents( $match );
//		else
//			$events = null;
		
		
		$home_goal = $this->convertZero($home->attributes->totalscore);
		$away_goal = $this->convertZero($away->attributes->totalscore);
		
		$json = [
				'sport' => 'futsal',
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
						't1' => $this->convertZero($home->attributes->t1),
						't2' => $this->convertZero($home->attributes->t2),
						'ot' => $this->convertZero($home->attributes->ot),
						'p' => $this->convertZero($home->attributes->p),
						'stats' => null ,
						'score' => $this->convertZero($home->attributes->totalscore),
						],
				'awayTeam' => [
						'id' => $this->convertZero($away->attributes->id),
						'name' => $this->convertZero($away->attributes->name),
						't1' => $this->convertZero($away->attributes->t1),
						't2' => $this->convertZero($home->attributes->t2),
						'ot' => $this->convertZero($away->attributes->ot),
						'p' => $this->convertZero($away->attributes->p),
						'stats' => null ,
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
		var_dump($json);
		return $json;
	}
	
	
	public function checkGoalNumber ( $goal ){
		return $goal;
	}
	
	public function getStats ( $stats, $type ){
		foreach ( $stats as $value ){
			if ( strtolower($value->name) == "i$type" ){
				return $value;
			}
		}
	}
	
	public function getResult ( $score ){
		$score_array = explode(',', $score);
		$scores = array();
		foreach ( $score_array as $value ){
			$scores[] = $value;
		}
		return $scores;
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

	
}
?>