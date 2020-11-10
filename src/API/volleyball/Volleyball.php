<?php 

class Volleyball{
	
	
	/////////////////////////////
	
	public function __construct(){
		
	}
	
	
	public function get_content( $api_token){
		var_dump( $api_token );
	}
	
	public function JSONGoalserve( $value, $match, $odds ){
		
//		var_dump( $odds['data'][0]['types']['data'][0]['odds'] );
//		var_dump( $match );
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
				'sport' => 'volleyball',
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
						'totalscore' => $this->convertZero($home->attributes->totalscore),
						's1' => $this->convertZero($home->attributes->s1),
						's2' => $this->convertZero($home->attributes->s2),
						's3' => $this->convertZero($home->attributes->s3),
						's4' => $this->convertZero($home->attributes->s4),
						's5' => $this->convertZero($home->attributes->s5),
						'score' => $this->convertZero($home->attributes->totalscore),
						'stats' => null,
						],
				'awayTeam' => [
						'id' => $this->convertZero($away->attributes->id),
						'name' => $this->convertZero($away->attributes->name),
						'totalscore' => $this->convertZero($away->attributes->totalscore),
						's1' => $this->convertZero($away->attributes->s1),
						's2' => $this->convertZero($away->attributes->s2),
						's3' => $this->convertZero($away->attributes->s3),
						's4' => $this->convertZero($away->attributes->s4),
						's5' => $this->convertZero($away->attributes->s5),
						'score' => $this->convertZero($away->attributes->totalscore),
						'stats' => null,
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
		
		$score = $this->getResult( $info->score );
		$ft = $this->getNumberWinSet ( $score );
		$player_name = explode( 'vs', $info->name );
		$home_score = $this->getScore( $score, 0 );
		$away_score = $this->getScore( $score, 1 );
//		var_dump( $value );
		$json = [
				'sport' => 'volleyball',
				'API_id' =>$info->id,
				'id' => $info->id,
//				'ht_score' => $ht->attributes->score,
				'ft_score' => $ft[0]."-".$ft[1],
				'et_score' => null,
				'home_team_id' => trim( $player_name[0] ),
				'away_team_id' => trim( $player_name[1] ),
				'home_score' => $ft[0],
				'away_score' =>  $ft[1],

				'starting_date' => $info->start_date,
				'starting_time' => $info->start_time,
				'period' => $info->period, /*$info->state_name,*/
				'status' => 'LIVE', /*$info->state_name,*/
				'minute' => 0,
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
						'name' => trim( $player_name[0] ),
						's1' => isset($home_score[0]) ? $home_score[0] : 0,
						's2' => isset($home_score[1]) ? $home_score[1] : 0,
						's3' => isset($home_score[2]) ? $home_score[2] : 0,
						's4' => isset($home_score[3]) ? $home_score[3] : 0,
						's5' => isset($home_score[4]) ? $home_score[4] : 0,
						'score' =>$ft[0],
						'stats' => null,
						],
				'awayTeam' => [
						'id' => trim( $player_name[1] ),
						'name' => trim( $player_name[1] ),
						's1' => isset($home_score[0]) ? $home_score[0] : 0,
						's2' => isset($home_score[1]) ? $home_score[1] : 0,
						's3' => isset($home_score[2]) ? $home_score[2] : 0,
						's4' => isset($home_score[3]) ? $home_score[3] : 0,
						's5' => isset($home_score[4]) ? $home_score[4] : 0,
						'score' => $ft[1],
						'stats' => null,
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