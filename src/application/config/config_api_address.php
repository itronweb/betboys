<?php 

require_once('db.php');

class Config_api_address{
	
//	public $api_token = "2AQvcO9dfKtRlZCx95PicAwJghGXOVC2d8YnWEdIFxQNVv9dKHhfz9U0Kqcs";
//	public $api_token = "kSLGrxDaSXfeMh5sb1xSDviFqRNXXtYjjZrL2fpLd39dHf2ibewuzCbqsJSM";
	public $first_api_token = "qpRG0zZeO7YGIsTkZ2t3CIHGSiyWMoUP9gk6AdU5z110mTZ6SZq3T79qsFxe";
	public $second_api_token = "jZ4sBQQIFxHK56f45zWHQEGkPmzdZniuHn7xwKi542uA9PpGAihUIrr6FagQ";
	public $third_api_token = "w9iypugF8vsbdzap0MTgzoXMqM4oysNKollp96fdgYTuNLPO6DddkFXbsIuV";
	public $api_token = "jZ4sBQQIFxHK56f45zWHQEGkPmzdZniuHn7xwKi542uA9PpGAihUIrr6FagQ";
	public $_1 = "N69VA5zj8aQvL2HFsdRSo8yHAfmuzngryahAnySULzlShhrwYBLWFt5hHztS";
	public $_2 = "E4ap1sJu9sjdAnODUNIzeJu1WJ6H0QmF1POIdSCQdD8f7HV2HVMYD6GJRoPQ";
	public $_3 = "jZ4sBQQIFxHK56f45zWHQEGkPmzdZniuHn7xwKi542uA9PpGAihUIrr6FagQ";
	public $_4 = "w9iypugF8vsbdzap0MTgzoXMqM4oysNKollp96fdgYTuNLPO6DddkFXbsIuV";
	public $_5 = "3vi9MMLQ2XEHF7X3Eon02d2ezBbN2ZgHJSZBrclv1GgJ8qpUNh1rO4JosrL9";
	public $_6 = "YRN4MqQ1kjb83qqqZ79QB6BNwMYIWHajxeMW6HL9VNvQRxkjuyYze9Ua0aGp";

//	public $api_token = "G4mvhhCt0MZl3UtzVwYGiqW6ijdbaJ0ar1nEJp40iAcWbM42eEeTDT3t6TP3";
	
	// Continent
	public $get_all_continent_url = "https://soccer.sportmonks.com/api/v2.0/continents?api_token={api}";
	public $get_continent_by_id = "https://soccer.sportmonks.com/api/v2.0/continents/{id}?api_token={api}";
	
	// Country
	public $get_country = "https://soccer.sportmonks.com/api/v2.0/countries?api_token={api}";
	public $country = "https://soccer.sportmonks.com/api/v2.0/countries/{id}?api_token={api}";
	
	// 	leagues
	public $get_leagues = "https://soccer.sportmonks.com/api/v2.0/leagues?page=1&api_token={api}&include=country,seasons,season";
	public $leagues = "https://soccer.sportmonks.com/api/v2.0/leagues/{id}?api_token={api}";
	
	// Seasons
	public $get_seasons = "https://soccer.sportmonks.com/api/v2.0/seasons?page=1&api_token={api}";
	public $seasons = "https://soccer.sportmonks.com/api/v2.0/seasons/{id}?api_token={api}";
	
	// Team
	public $get_team_by_season = "https://soccer.sportmonks.com/api/v2.0/teams/season/{id}?api_token={api}";
	public $teams = "https://soccer.sportmonks.com/api/v2.0/teams/{id}?api_token={api}";
	
	// Pre-match odds
	public $get_prematch_odds_with_fixture_bookmaker = "https://soccer.sportmonks.com/api/v2.0/odds/fixture/{fixture_id}/bookmaker/{bookmaker_id}?api_token={api}";
	
	// Fixture
	public $fixture_between_date = "https://soccer.sportmonks.com/api/v2.0/fixtures/between/{from}/{to}?api_token={api}";
	public $fixture_date = "https://soccer.sportmonks.com/api/v2.0/fixtures/date/{date}?page=1&api_token={api}&include=league,localTeam,visitorTeam,odds,inplay,cards,corners,stats";
	
	// Inplay
	public $inplay = "https://soccer.sportmonks.com/api/v2.0/odds/inplay/fixture/{id}?api_token={api}";
	
	// Odds
	public $odds = "https://soccer.sportmonks.com/api/v2.0/odds/fixture/{id}/bookmaker/2?api_token={api}";
//	public $odds = "https://soccer.sportmonks.com/api/v2.0/odds/fixture/{id}?api_token=";
	
	public $live_now = "https://soccer.sportmonks.com/api/v2.0/livescores/now?api_token={api}&include=league,localTeam,visitorTeam,odds,inplay,cards,corners,stats";
	
	// Standing
	public $standing = "https://soccer.sportmonks.com/api/v2.0/standings/season/{id}?api_token={api}";
	
	public function __construct(){
		$db = new DB();
		$query = "SELECT * FROM api_token WHERE status = 1 ORDER BY id ";
		
		$result = $db->cn->query($query);
		if($result->num_rows > 0){
			foreach($result as $key=>$value){
				$api = "_".++$key;
				$this->{$api} = $value['api_token'];
			}
		}
		
	}
	
	// return address with api_token
	public function get_address( $address , $i ){
		$api = "_$i";
		$adr = str_replace('{api}',$this->{$api},$address);
		return $adr;
	}
	
	public function get_second_address( $address ){
		$adr = str_replace('{api}',$this->second_api_token,$address);
		return $adr;
	}
	
	public function get_live_now ( $address ){
		
	}

}


?>