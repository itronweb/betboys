<?php 

require_once('../application/config/db.php');
require_once('config/Config.php');
require_once('tennis/Tennis.php');
require_once('soccer/Soccer.php');
require_once('basket/Basket.php');
require_once('hockey/Hockey.php');
require_once('handball/Handball.php');
require_once('volleyball/Volleyball.php');
require_once('football/Football.php');
require_once('baseball/Baseball.php');
require_once('cricket/Cricket.php');
require_once('rugby/Rugby.php');
require_once('boxing/Boxing.php');
require_once('esports/Esports.php');
require_once('futsal/Futsal.php');

class GoalServe{
	
	public $db;
	
	////////////// URL ///////////////
	
	public $soccer_upcoming_url = "https://www.goalserve.com/getfeed/{api}/getodds/soccer?cat=soccer_10&date_start={date_start}";
	
	public $soccer_result_url = "https://www.goalserve.com/getfeed/{api}/soccernew/home";
	
	public $soccer_result_by_day_url = "https://www.goalserve.com/getfeed/{api}/soccernew/d-{day}";
	
	///////////////////Tennis /////////////////////////////////
	///////// f25a790d024e48d98c5debd88477c875 ////////////////
	
	public $tennis_upcoming_url = "https://www.goalserve.com/getfeed/{api}/getodds/soccer?cat=tennis_10&date_start={date_start}";
	
	public $tennis_result_url = "https://www.goalserve.com/getfeed/{api}/tennis_scores/home";
	
	public $tennis_result_by_day_url = "https://www.goalserve.com/getfeed/{api}/tennis_scores/d-{day}";
	
	///////////////////////////////
	/* Prematch URL in Goalserve.com */
	public $goalServe_upcoming = "https://www.goalserve.com/getfeed/{api}/getodds/soccer?cat={soccer}_10&date_start={date_start}&date_end={date_start}";
	
	public $goalServe_results_home = "https://www.goalserve.com/getfeed/{api}/{soccer}/home";
	public $goalServe_results_day = "https://www.goalserve.com/getfeed/{api}/{soccer}/d-{day}";
	
	/////////////////////////////
	
	public $handicap_array_change_sign = ['Handicap Result', 
										  'Handicap Result 1st Half',
										  '3-Way Handicap',
										  'Europian Handicap',
										  '1st Half 3-Way Handicap', 
										  'Asian Handicap',
										  'Asian Handicap First Half'
										 ];
	
	public $label_array = ['Match_Winner' => 'fulltime_result',
						'Goals_Over_Under' =>'match_goals',
						'Exact_Goals_Number' =>'Exact_Goals_Number',
						'Total___Home' =>'Total__Home',
						'Total___Away' =>'Total__Away',
						'Asian_Handicap' =>'Asian_Handicap',
						'Handicap_Result' =>'Handicap_Result',
					    '3-Way Handicap' => 'Europian_Handicap',  
						'Home_Away' =>'Home_Away',
						'Away_Odd_Even' =>'Away_Odd_Even',
						'Double_Chance' =>'double_chance',
						'HT_FT_Double' =>'HT_FT_Double',
						'Correct_Score' =>'Correct_Score',
						'Correct_Score_1st_Half' =>'Correct_Score_1st_Half',
						'Team_To_Score_First' =>'Team_To_Score_First',
						'Corners_Over_Under' =>'Corners_Over_Under',
								'draw_no_bet' =>'Draw_No_Bet',
								
						
						'Clean_Sheet__Home' =>'Clean_Sheet__Home',
						'Clean_Sheet__Away' =>'Clean_Sheet__Away',
						'Both_Teams_To_Score' =>'Both_Teams_To_Score',
						'Win_To_Nil' =>'Win_To_Nil',
						'Win_to_Nil__Home' =>'Win_to_Nil__Home',
						'Win_to_Nil__Away' =>'Win_to_Nil__Away',
						'Win_Both_Halves' =>'Win_Both_Halves',
						'To_Win_Either_Half' =>'To_Win_Either_Half',
						'Away_Team_Exact_Goals_Number' =>'Away_Team_Exact_Goals_Number',
						'To_Qualify' =>'To_Qualify',
						'2nd_Half_Exact_Goals_Number' =>'2nd_Half_Exact_Goals_Number',
						'Results_Both_Teams_To_Score' =>'Results/Both_Teams_To_Score',
						'Result_Total_Goals' =>'Result/Total_Goals',
						'Home_Team_Score_a_Goal' =>'Home_Team_Score_a_Goal',
						'Away_Team_Score_a_Goal' =>'Away_Team_Score_a_Goal',
						'Corners_1x2' =>'Corners_1x2',
						'Correct_Score_2nd_Half' =>'Correct_Score_2nd_Half',
						'Goals_Over_Under_2nd_Half' =>'Goals_Over/Under_2nd_Half',
						'Handicap_Result_1st_Half' =>'Handicap_Result_1st_Half',
						'Odd_Even_1st_Half' =>'Odd/Even_1st_Half',
						'Home_Team_Exact_Goals_Number' =>'Home_Team_Exact_Goals_Number',
						'1st_Half_Exact_Goals_Number' =>'1st_Half_Exact_Goals_Number',
						'Double_Chance__1st_Half' =>'Double_Chance_-_1st_Half',
						'Odd_Even' =>'Odd/Even',
						'Team_To_Score_Last' =>'Team_To_Score_Last',
						'Double_Chance__2nd_Half' =>'Double_Chance_-_2nd_Half',
						'Both_Teams_To_Score__1st_Half' =>'Both_Teams_To_Score_-_1st_Half',
						'Home_Odd_Even' =>'Home_Odd/Even',
						'Asian_Handicap_First_Half' =>'Asian_Handicap_First_Half',
						'Both_Teams_To_Score__2nd_Half' =>'Both_Teams_To_Score_-_2nd_Half',
						'Home_win_both_halves' =>'Home_win_both_halves',
						'Away_win_both_halves' =>'Away_win_both_halves',
						'First_10_min_Winner' =>'First_10_min_Winner',
						   'Clean_Sheet___Home' => 'Clean_Sheet___Home',
						   'Clean_Sheet___Away' => 'Clean_Sheet___away',
						   'Win_to_Nil___Home' => 'Win_to_Nil___Home',
						   'Win_to_Nil___Away' => 'Win_to_Nil___Away',
						 'Both_Teams_To_Score___1st_Half' => 'Both_Teams_To_Score___1st_Half',
						   'Both_Teams_To_Score___2nd_Half' => 'Both_Teams_To_Score___2nd_Half',
						   'Double_Chance___1st_Half' => 'Double_Chance___1st_Half',
						   'Double_Chance___2nd_Half' => 'Double_Chance___2nd_Half ',
						
						
						/* 1st half */
						'1st_Half_Winner' =>'1st_Half_Winner',
						'2nd_Half_Winner' =>'2nd_Half_Winner',
						'Goals_Over_Under_1st_Half' =>'Goals_Over/Under_1st_Half',
						'Highest_Scoring_Half' =>'Highest_Scoring_Half',
//								'1_6' =>'First Half Goals',
//								'1_7' =>'1st Half Asian Corners',
//								'1_8' =>'Half Time Result'
						   
						   
						  'Over_Under' => 'Over_Under',
							   ];
	/*     */
	public $change_label = [
		'Match Winner' => '1X2',
		'Goals Over/Under' =>'Over/Under',
		'3-Way Handicap' => 'Europian Handicap',  
		
	];
	
	
//	public $soccer_array = [ 'soccer', 'tennis', 'basket' ];
	public $result_array = ['soccer' => 'soccernew', 'tennis'=>'tennis_scores'  ];
	public $soccer_type;
	public $soccer_name;
	public $tennis;
	public $soccer;
	public $basket;
	public $hockey;
	public $handball;
	public $volleyball;
	public $football;
	public $baseball;
	public $cricket;
	public $rugby;
	public $boxing;
	public $esports;
	public $futsal;
	
	public $direction = "../upload/API/";
	
	public $bookmaker_sort = [ 'bet365', '10Bet' ];
	
	public $config;
	
	
	/////////////////////////////
	
	public function __construct(){
		$this->db = new DB();
		$this->config = new ConfigGoalServe();
		$this->tennis = new Tennis();
		$this->soccer = new Soccer();
		$this->basket = new Basket();
		$this->hockey = new Hockey();
		$this->handball = new Handball();
		$this->volleyball = new Volleyball();
		$this->football = new Football();
		$this->baseball = new Baseball();
		$this->cricket = new Cricket();
		$this->rugby = new Rugby();
		$this->boxing = new Boxing();
		$this->esports = new Esports();
		$this->futsal = new Futsal();
	}
	/*
	* 
	*
	*
	**/
	public function get_token( ){
		$db = new DB();
		
		$db->_mysqli->where( 'status', 1 );
		$api = $db->_mysqli->get( 'api_token', null, 'api_token' );
		
//		$api = $db->select('api_token', 'api_token', 'status', '1');
		
		if ( isset( $api[0] ) )
			return $api[0]['api_token'];
		
		return false;
	}
	/*
	* 
	*
	*
	**/
	public function get_content ( $token, $url, $type='0'){
		
		if( ! $token )
			return false;
		$url = str_replace( '{api}', $token, $url );
		var_dump( $url );
		$xml_file = simplexml_load_file($url);
		
		if ( $xml_file ){
			$convert_xml_to_string = $xml_file->asXML();
		
			if ($content = json_decode($this->convertXMLtoJSONfromString($convert_xml_to_string))){
				return $content;
			}
		}
		
		return false;
	}
	/*
	* 
	*
	*
	**/
	public function convertXMLtoJSONfromString($fileContents){
		
		$fileContents = str_replace(array("\n", "\r", "\t","'"), '', $fileContents);

		$fileContents = trim(str_replace('"', "'", $fileContents));

		$fileContents = stripslashes($fileContents);
		
		$simpleXml = simplexml_load_string($fileContents);

		$json = json_encode($simpleXml);
		
		$json = str_replace(array("@"),'',$json);

		return $json;

	}
	/*
	* 
	*
	*
	**/
	public function convertSpaceToUnderline($data){
		$data = str_replace( '/', '_', implode('_',explode(' ',$data)));
		return str_replace('-','_',$data);
//		return implode('_',explode(' ',$data));
//		return str_replace('-','_',$remove_space);
	}
	/*
	* 
	*
	*
	**/
	public function changeSign($label,$handicap){
		$label_array = ['Draw','Away'];
		return (in_array($label,$label_array) ? $handicap*-1 : $handicap);
	}
	
	public function checkMatchStatus ( $match, $type ){
		// az array upcoming_status va results_status check mikone
		$array = $type."_status";
		
		if( in_array( trim(strtolower($match->status)), $this->config->{$array} ))
			return true;
		
		// if tuye array nabud dasti timo check mikne
		$upcoming_true = $this->checkMatchDate( $this->getMatchTime($match) );
		if ( $type == 'upcoming' && $upcoming_true )
			return true;
//		else if ( $type == 'results' && !$upcoming_true )
//			return true;
		
		return false;
	}
	
	public function checkMatchDate ( $match_time ){
		
		$start_date = date('d.m.Y');
		$start_time = date('H:i');
		
		if ( ($start_date < $match_time['start_date']) || ($start_date==$match_time['start_date'] && $start_time < $match_time['start_time']) )
				return true;
			else
				return false;
	}
	/*
	* 
	*
	*
	**/
	public function getMatchTime( $match ){
		$date = ['formatted_date','date'];
		$start_date = null;
		foreach ( $date as $value ){
			if( isset($match->{$value}) ){
				$match_time['start_date'] = $match->{$value};
				break;
			}
				
		}
		
		$match_time['start_time'] = $match->time;
		
		return $match_time;
			
	}
	
	public function jsonDecode($json, $assoc = false){
	    $ret = json_decode($json, $assoc);
	    if ($error = json_last_error())
	    {
	        $errorReference = [
	            JSON_ERROR_DEPTH => 'The maximum stack depth has been exceeded.',
	            JSON_ERROR_STATE_MISMATCH => 'Invalid or malformed JSON.',
	            JSON_ERROR_CTRL_CHAR => 'Control character error, possibly incorrectly encoded.',
	            JSON_ERROR_SYNTAX => 'Syntax error.',
	            JSON_ERROR_UTF8 => 'Malformed UTF-8 characters, possibly incorrectly encoded.',
	            JSON_ERROR_RECURSION => 'One or more recursive references in the value to be encoded.',
	            JSON_ERROR_INF_OR_NAN => 'One or more NAN or INF values in the value to be encoded.',
	            JSON_ERROR_UNSUPPORTED_TYPE => 'A value of a type that cannot be encoded was given.',
	        ];
	        $errStr = isset($errorReference[$error]) ? $errorReference[$error] : "Unknown error ($error)";
	        return $errStr;
	        // throw new \Exception("JSON decode error ($error): $errStr");
	    }
	    return $ret;
	}

	public function remove_utf8_bom($text){
		    $bom = pack('H*','EFBBBF');
		    $text = preg_replace("/^$bom/", '', $text);
		    return $text;
	}
	
	//////////////////// Start Upcoming /////////////////////////
	/*
	* 
	*
	*
	**/
	public function get_upcoming ( $day=0 ){
		
		$soccer_array = $this->config->upcoming_sport;
		
		foreach ( $soccer_array as $soccer ){
			$this->soccer_type = $soccer['name_prematch'];
			$this->soccer_name = $soccer["name_en"];
			if ( is_dir($this->soccer_type) ){
				
				
				/* get prematch url */
				$url = $this->goalServe_upcoming;
				/* replace data to prematch url */
				$date = date("d.m.Y",strtotime("+$day days"));
				$url = str_replace(['{soccer}','{date_start}'], [$this->soccer_type,$date], $url);
				
				/* get content from url */
				$this->{$this->soccer_type}->get_content($this->get_token());
				
				
				if ( $content = $this->get_content($this->get_token(), $url) ){
					
					$json = array();
					if ( sizeof( $content->category ) > 1 ){
						foreach( $content->category as $key=>$value){
							$this->createUpcomingJson( $json, $value );
						}
					}
					else {
						$this->createUpcomingJson( $json, $content->category );
					}
					$dir = $this->direction.$this->soccer_name;
					if ( !is_dir($dir) )
						mkdir( $dir );
					file_put_contents("$dir/day_$day.json",json_encode($json));

	//				$this->{$soccer}->get_content( $this->get_token(), $url );
				}
				else {
					echo "<br/>";
					var_dump( $this->soccer_type );
					echo "<br/>";
				}
//					return false;
				
//				var_dump( $content );
//				die();
				
			}
			else {
				var_dump('222222');
			}
		}
		
	}
	
	public function createUpcomingJson( &$json, $value ){
		
		if ( !isset($value->matches->match->attributes)){
			foreach( $value->matches->match as $match ){
				$this->upcomingJSON( $json, $value, $match );
			}
		}
		else{
			$match = $value->matches->match;
			$this->upcomingJSON( $json, $value, $match );
		}
	}
	
	public function upcomingJSON ( &$json, $value, $match ){
		$match_time = $this->getMatchTime( $match->attributes );
		
		if ( $this->checkMatchStatus($match->attributes, 'upcoming') ){
			
			$converted_odd = $this->convertOddstoGoalServe( $match->odds );
		
			$odds = $this->createSubOddsArray($converted_odd);
			
			$json['data'][] = $this->{$this->soccer_type}->JSONGoalserve($value,$match,$odds);
		}
		
		
//		$json['data'][] = $this->{$soccer}->JSONGoalserve($value,$match,$converted_odd);
		
	}
	
	public function convertOddstoGoalServe( $odds ){
		$created_odds = new stdClass();
		
		if( !isset($odds->type) )
			return null;
		$i=0;
		if ( isset($odds->type->attributes) ){
			$this->createOdds( $created_odds, $odds->type, $i );
		}
		else {
			foreach ( $odds->type as $key=>$values ){
				$this->createOdds( $created_odds, $values,$i );
				$i++;
			}
		}
		return $created_odds;
		
	}
	
	public function createOdds ( &$created_odds, $values, $i ){
		$config = $this->config;
		$has_total = $config->has_total;
		$has_handicap = $config->has_handicap;
		$value = $this->getDefualtBookmaker($values);
		
		if ( $values->attributes->stop == 'False' && $value ){
			$handicap = false;
			$total = false;
			$odd_label = $values->attributes->value;
			$change_label = "change_label_$this->soccer_type";
			
			$odd_label = (isset($config->{$change_label}[$odd_label])) ? $config->{$change_label}[$odd_label] : $odd_label;
			
			if(in_array($odd_label,$has_total)){
				$total = true;
				$odd = $value->total;
			}
			elseif(in_array($odd_label,$has_handicap)){
				$handicap = true;
				$odd = $value->handicap;
			}
			else{
				if(!isset($value->odd)){
					var_dump($odd_label);
					var_dump($value);
					var_dump('odd not exist');
				}
				else
					$odd = $value->odd;

			}

			$created_odds->{$i} = new stdClass();
			$created_odds->{$i} = $this->createOddsGoalServe($odd,$odd_label,$total,$handicap);
		}
	}
	
	public function createSubOddsArray($odds){
		$config = $this->config;
		$bet_array = array();
		$type_array = array();
		$not_empty = false;
		$types = array();
		$fulltime_result = false;
		
		if( empty($odds) ){
			// create bet for fulltime result with suspend 1
			$subodds = $this->create_empty_sub_odds();
			$types['data'][] = ['type' => '1x2',
								'bookmaker_test' => 'bet',
								'odds' => $subodds,
								];
		}
		else{
			
			foreach( $odds as $key=>$value){
				
				$label = $value->name;
				if($label == '1x2')
					$fulltime_result = true;
				
				// check first odd is full time result if not create empty odd
				if( $fulltime_result == false ){
					$subodds = $this->create_empty_sub_odds();
					$types['data'][] = ['type' => '1x2',
										'bookmaker_test' => 'bet',
										'odds' => $subodds,
										];
					$fulltime_result = true;
				}
				$remove_label = "remove_label_" . $this->soccer_type;
				
				if ( !in_array($label, $config->{$remove_label} ) ){
					$odds_function = "odds_label_$label"."_$this->soccer_type";
					if(method_exists($this, $odds_function)){
						
						$subodds = $this->{$odds_function}($value);
//                        var_dump($subodds);
					}
					else 
						$subodds = $this->create_odds($value);
					
						$types['data'][] = ['type' => $label,
										'bookmaker_test' => 'bet',
										'odds' => $subodds,
										];
				}
				
				
			}

		}
		
		// if have not odds create default 1x2 odds with value 0 suspend 1
		if(empty($types)){
			$types['data'][] = ['type' => '1x2',
								'bookmaker_test' => 'bet',
								'odds' => $this->create_empty_sub_odds(),
								];
		}
$odds = ['data' => [[
						'bookmaker_id' => '2',
						'name' => 'bet365',
						'types' => $types
						] ]
				];
		return($odds);
		
		
	}
	
	
	
	////////////////// Start results /////////////////////////
	public function get_results ( $day=0 ){
		$config = $this->config;
		$soccer_array = $config->result_sport;
		$inplay_array = $config->inplay_sport;
		$iterator = new RecursiveIteratorIterator(new RecursiveArrayIterator($inplay_array));
		$inplay_array = iterator_to_array($iterator,false);
//		var_dump( $soccer_array );
		foreach ( $soccer_array as $soccer ){
			
			$this->soccer_type = $soccer['name_result'];
			$this->soccer_name = $soccer["name_en"];
			
			if ( is_dir($this->soccer_name) ){
				
				$json = array();
				
				$this->get_result_xml( $json, $day);
				
				if ( in_array( $this->soccer_name, $inplay_array) )
					$this->get_result_json ( $json );
				
					
				file_put_contents("$this->direction$this->soccer_name/results.json",json_encode($json));
				
			}
		}
		
	}
	
	public function get_result_xml ( &$json, $day ){
		$config = $this->config;
		
		$db = new DB();
		
		
//		$created_at = ['created_at', date("Y-m-d 00-00-00",strtotime("-4 days")),date("Y-m-d 00-00-00",strtotime("+5 days"))];
////		$match = $db->multi_select('bet_form','match_id',['result_status','soccer_type'],['LIVE', '0', $this->soccer_name],'id DESC',null, $created_at);
//	
//		$query = "SELECT match_id FROM bet_form WHERE result_status = '0' AND soccer_type = '$this->soccer_name' AND $created_at[0] BETWEEN '$created_at[1]' AND '$created_at[2]'";
//	
//		
//		$match = $db->get_query( $query );
		
		$created_at = [date("Y-m-d 00-00-00",strtotime("-4 days")),date("Y-m-d 00-00-00",strtotime("+5 days"))];
		
		$db->_mysqli->where( 'result_status', 0 );
		$db->_mysqli->where( 'soccer_type', $this->soccer_name );
		$db->_mysqli->where( 'created_at', $created_at, 'between' );
		$match = $db->_mysqli->get( 'bet_form');

		if ( $db->_mysqli->count == 0 )
			return;
		
		$iterator = new RecursiveIteratorIterator(new RecursiveArrayIterator($match));
		$find_match = array_unique(iterator_to_array($iterator,false));
		
	
		for( $day = 0; $day<4; $day++ ){
			
			$url =($day==0) ? $config->xml_results_home: $config->xml_results_day;
			$url = str_replace(['{soccer}','{day}'],[$this->soccer_type,$day],$url);

	//				$this->{$this->soccer_name}->get_content($this->get_token());

			if ( !$content = $this->get_content($this->get_token(), $url) )
				return false;

			
			if ( isset($content->category) ){
			
				if ( isset($content->category->attributes)){
					$this->createResultsJson( $json, $content->category, $find_match );
				}
				else {
					foreach( $content->category as $key=>$value){

						$this->createResultsJson( $json, $value, $find_match );
					}
				}

			}
			else if (isset( $content->match )){
				
				foreach ( $content->match as $key=>$value ){
					$this->resultsJSON( $json, $content, $value, $find_match );
				}
			}
			else {
				
			}
		}
	}	
	
	public function get_result_json ( &$json ){
		$config = $this->config;
		$db = new DB();
		
		
//		$created_at = ['created_at', date("Y-m-d 00-00-00",strtotime("-4 days")),date("Y-m-d 00-00-00",strtotime("+5 days"))];
//
//		$query = "SELECT match_id FROM bet_form WHERE result_status = '0' AND soccer_type = '$this->soccer_name' AND $created_at[0] BETWEEN '$created_at[1]' AND '$created_at[2]'";
		
//		$match = $db->get_query( $query );
		
		$created_at = [date("Y-m-d 00-00-00",strtotime("-4 days")),date("Y-m-d 00-00-00",strtotime("+5 days"))];
		
		$db->_mysqli->where( 'result_status', 0 );
		$db->_mysqli->where( 'soccer_type', $this->soccer_name );
		$db->_mysqli->where( 'created_at', $created_at, 'between' );
		$match = $db->_mysqli->get( 'bet_form');

		if ( $db->_mysqli->count > 0 ){
			$iterator = new RecursiveIteratorIterator(new RecursiveArrayIterator($match));
			$match = array_unique(iterator_to_array($iterator,false));
			
			$file_name_array = ['results' ];
//			$file_name_array = ['results', 'info', 'stats','team_info' ];
			$date = date('Ym');
			$url = $config->json_result;
//			var_dump($match);
			foreach ( $match as $match_id ){
				
				$new_file = new stdClass();
				foreach ( $file_name_array as $file_name ){
					$name = "$file_name.json";

					$new_url = str_replace(['{date}','{id}','{name}'],[$date,$match_id,$name],$url);
					$content = null;
					
					////////////////Host //////////////////

					
					$test1= $this->remove_utf8_bom( $test );
					
					$content = file_get_contents($new_url);
					
					if ( !empty($content) ){
						$content_json = json_decode($this->remove_utf8_bom( $content ));
						$new_file->{$file_name} = $content_json;
					}
	//				////////////////Local //////////////////
//					$new_url = "result/$file_name.json";
//					$new_file->{$file_name} = json_decode(file_get_contents($new_url));
				
				}
				
				if ( isset($new_file->results) ){
					$new_file->results->id = $match_id;
					$json['data'][] = $this->{$this->soccer_name}->createJSONForResults($new_file, null );	
				}
				
//				$json['data'][][$match_id] = $new_file;

				
			}
//			var_dump( end($json['data']));
		}
			
			
		
		
		
		
	}
	
	public function get_inplay ( ){
		$config = $this->config;
		$soccer_array = $config->inplay_sport;
		
		foreach ( $soccer_array as $soccer ){
		
			$this->soccer_type = $soccer['name_inplay'];
			$this->soccer_name = $soccer["name_en"];
			
			if ( is_dir( $this->soccer_type ) ){
		
				
				$url = str_replace('{soccer}', $this->soccer_type, $config->soccer_inplay_url);
				
				$this->{$this->soccer_type}->get_content($this->get_token());
				
				
				if ( !$content =json_decode(file_get_contents( $url ) ))
					return false;
//				file_put_contents(  "$this->soccer_type.json", json_encode($content ) );

//				$content = json_decode(file_get_contents("$this->soccer_type.json"));
				
				$json = array();
				foreach( $content->events as $key=>$value){
					$core = $value->core;
					$info = $value->info;
					$stats = $value->stats;
					$odds = $value->odds;
					
					if ( in_array(trim(strtolower($info->period)), $config->inplay_stats) ){
						$converted_odd = $this->convertOddstoSubOddsArray( $odds );
						
						$this->sortSubOddsArray( $converted_odd );

						$odd = $this->createSubOddsArray( $converted_odd );

//                        $other_data = $this->get_inplay_json( $value->info->id );
						
//						if ( !empty($other_data) ){
//							if ( isset($other_data->results) )
//								$value->results = $other_data->results;	  
//						}
//                        $value->stats1 = $other_data->stats;
                        
						$json['data'][] = $this->{$this->soccer_type}->createJSON($value, $odd );
						
					}
					else{
						
					}
				}

				file_put_contents("$this->direction$this->soccer_name/inplay.json",json_encode($json));
				echo json_last_error_msg();
				
			}
		}
	}
	
	public function get_inplay_by_soccer ( $soccer_name ){
		$config = $this->config;
//		$soccer_array = $config->inplay_sport;
		
		$soccer = $config->get_soccer_config( $soccer_name );
		
		if ( $soccer == 0 )
			return 0;
		
//		foreach ( $soccer_array as $soccer ){
		
			$this->soccer_type = $soccer['name_inplay'];
			$this->soccer_name = $soccer["name_en"];
			
			if ( is_dir( $this->soccer_type ) ){
		
				
				$url = str_replace('{soccer}', $this->soccer_type, $config->soccer_inplay_url);
				
				$this->{$this->soccer_type}->get_content($this->get_token());
				
				
				if ( !$content =json_decode(file_get_contents( $url ) ))
					return false;
//				file_put_contents(  "$this->soccer_type.json", json_encode($content ) );

//				$content = json_decode(file_get_contents("$this->soccer_type.json"));
				
				$json = array();
				foreach( $content->events as $key=>$value){
					$core = $value->core;
					$info = $value->info;
					$stats = $value->stats;
					$odds = $value->odds;
					
					if ( in_array(trim(strtolower($info->period)), $config->inplay_stats) ){
						$converted_odd = $this->convertOddstoSubOddsArray( $odds );
						
						$this->sortSubOddsArray( $converted_odd );

						$odd = $this->createSubOddsArray( $converted_odd );

//                        $other_data = $this->get_inplay_json( $value->info->id );
						
//						if ( !empty($other_data) ){
//							if ( isset($other_data->results) )
//								$value->results = $other_data->results;	  
//						}
//                        $value->stats1 = $other_data->stats;
                        
						$json['data'][] = $this->{$this->soccer_type}->createJSON($value, $odd );
						
					}
					else{
						
					}
				}

				file_put_contents("$this->direction$this->soccer_name/inplay.json",json_encode($json));
				echo json_last_error_msg();
				
			}
//		}
	}

    public function get_inplay_json ( $match_id ){
        $config = $this->config;

//            $file_name_array = ['info', 'odds', 'stats', 'team_info', 'results' ];
            //$file_name_array = ['stats', 'team_info', 'results' ];
		$file_name_array = ['stats', 'results' ];

            $date = date('Ym');
            $url = $config->json_result;


                $new_file = new stdClass();
                foreach ( $file_name_array as $file_name ){
                    $name = "$file_name.json";

                    $new_url = str_replace(['{date}','{id}','{name}'],[$date,$match_id,$name],$url);
                    $content = null;

                    ////////////////Host //////////////////
                    $content = json_decode(file_get_contents($new_url));
					
					

                    if ( !empty($content) )
                        $new_file->{$file_name} = $content;
                    //				////////////////Local //////////////////
//					$new_url = "result/$file_name.json";
//					$new_file->{$file_name} = json_decode(file_get_contents($new_url));

                }

//				$json['data'][][$match_id] = $new_file;

        return $new_file;

    }
	
	public function convertShortNametoLabel ( $name ){
		return str_replace([' ','/','-',',','?','(',')'],['_','_','','_','','',''], trim(strtolower($name)));
	}
	
	public function create_correct_label_format ( $label ){
		
		$label = str_replace( '(Away)' , '', $label );
		
		if ( strpos( $label, 'Over') !== false || strpos($label, 'Under') !== false ){
			
			$label = str_replace( ['-', '+'] , [''], $label );
			
			for( $i=0; $i<20; $i++){
			
				for($j=$i; $j-$i<=1; ){
					$j+=0.25;
					$a = number_format($i,1);
					$b = $j;
					$label = str_replace( "$a,$b", '', $label);
					$a += 0.25;
					$label = str_replace( "$a,$b", '', $label);
					$a += 0.25;
					$label = str_replace( "$a,$b", '', $label);
					$a += 0.25;
					$label = str_replace( "$a,$b", '', $label);

				}
			}
			
			for ( $i=0; $i<20;){
				$label = str_replace( "$i", '', $label);
				$i+= 0.5;
			}

			$label = str_replace( '.0' , '', $label );
			$label = str_replace( '.' , '', $label );
			$label = str_replace( ':' , '', $label );
			
			
		}

		
			return trim($label);
		
	}
	
	public function convertOddstoSubOddsArray ( $odds ){
		$odd = new stdClass();
		$change_label = $this->config->{"change_label_$this->soccer_type"};
		foreach ( $odds as $value ){
			
			if ( isset($change_label[$value->short_name]) )	
				$value->short_name = $change_label[$value->short_name];
			
			$name = $this->convertShortNametoLabel( $value->short_name );
			$odd->{$name} = new stdClass();

			$odd->{$name}->id = $value->id;
			$odd->{$name}->name = $value->short_name;
			$odd->{$name}->suspend = $value->suspend;
			foreach( $value->participants as $participants_value ){
				
				if($label = $this->convertShortNametoLabel( $participants_value->short_name )){
					
					$new = $this->create_correct_label_format ( $participants_value->short_name );
					
					$odd->{$name}->{$label} = new stdClass();
					$odd->{$name}->{$label}->id = $participants_value->id;
					$odd->{$name}->{$label}->name = $new;
					$odd->{$name}->{$label}->value = $participants_value->value_eu;
					$odd->{$name}->{$label}->extra = $participants_value->handicap;
					$odd->{$name}->{$label}->suspend = $participants_value->suspend;
				}
				
				
			}
		}
		
		return $odd;
	}
	
	public function sortSubOddsArray ( &$odds ){
		$i = 0;
		$convert_odds = $odds;
		$odds = new stdClass();
		
		foreach ( $convert_odds as $key=>$value ){
			if ( $i == 0 && strtolower($key) == '1x2'){
				$odds = $convert_odds;
				return true;
			}
			elseif ( $key == '1x2' ){
				$odds->{$key} = new stdClass();
				$odds->{$key} = $value;
				unset( $convert_odds->{$key} );
				
				break;
			}
			
			$i++;
		}
		
		foreach ( $convert_odds as $key=>$value ){
			$odds->{$key} = new stdClass();
			$odds->{$key} = $value;
		}
		return true;
	}
	
	
	/*
	* 
	*
	*
	**/
	
	
	public function createResultsJson( &$json, $value, $find_match ){
		
		if ( isset($value->matches) ){
			if ( !isset($value->matches->match->attributes)){

					foreach( $value->matches->match as $match ){
						$this->resultsJSON( $json, $value, $match, $find_match );
					}
			}
			else{
				$match = $value->matches->match;
				$this->resultsJSON( $json, $value, $match, $find_match );
			}
		}
		else {
			if ( !isset($value->match->attributes) ){
				foreach( $value->match as $match ){
					$this->resultsJSON( $json, $value, $match, $find_match );
				}
			}
			else{
				$match = $value->match;
				$this->resultsJSON( $json, $value, $match, $find_match );
			}
		}
	}
	/*
	* 
	*
	*
	**/
	
	
	public function resultsJSON ( &$json, $value, $match, $find_match ){
		
		$match_time = $this->getMatchTime( $match->attributes );
		
		$start_date = date('d.m.Y');
		$start_time = date('H:i');
		
		
		if ( in_array( $match->attributes->id, $find_match)){
		
			if ( $this->checkMatchStatus($match->attributes, 'results') ){
	//			var_dump( $match->attributes->status );
				$odds = null;
				$json['data'][] = $this->{$this->soccer_name}->JSONGoalserve($value,$match,$odds);
			}
			
		} 
		
		
		
//		$json['data'][] = $this->{$soccer}->JSONGoalserve($value,$match,$converted_odd);
		
	}
	/*
	* 
	*
	*
	**/
	
	/*
	* 
	*
	*
	**/
	
	/*
	* 
	*
	*
	**/
	public function getbet365OddsGoalServe($value){
		
		if( isset($value->bookmaker) && sizeof($value->bookmaker) == 1){
//			if ( $value->bookmaker->attributes->name == 'bet365' )
				return $value->bookmaker;
		}
	   else{
			foreach($value->bookmaker as $keys=>$item){
				if($item->attributes->name == 'bet365'){
					return $item;
				}
			}
		   
		}
		return false;
	}
	
	public function getDefualtBookmaker ( $value ){
		
		$bookmaker = $this->config->defualt_bookmaker[$this->soccer_name];
		
		foreach( $bookmaker as $bookmaker_value ){
		
			foreach ( $value->bookmaker as $keys=>$item ){
				
				if ( sizeof($value->bookmaker) == 1 && $keys == 'attributes'){
					$result = $value->bookmaker;
					$search = strtolower($item->name);
				}
				else if ( sizeof($value->bookmaker) > 1 ){
					$result = $value->bookmaker[$keys];
					$search = strtolower( $item->attributes->name );
				}
				
				if ( $search == $bookmaker_value )
					return $result;
				
				
			}
		}
		return false;
		
	}
	/*
	* 
	*
	*
	**/
	public function createOddsGoalServe( $odds, $odd_label, $total, $handicap ){
		
		$odd = new stdClass();
		$odd->id = null;
		$odd->name = $odd_label;
		$odd->suspend = 0;
		
		if($handicap || $total){
			// if have multi handicap label
			
			if(sizeof($odds) > 1){
				foreach($odds as $key=>$item){
					$extra = $item->attributes->name;
					$suspend = ($item->attributes->stop == 'False') ? 0 : 1;
					if ( isset($item->odd) ){
						$this->createSubOddsGoalServe($odd,$item->odd,$extra,$suspend,$key);		
					}
					
				}
			}
			else{
				$extra = $odds->attributes->name;
				
				$suspend = ($odds->attributes->stop == 'False') ? 0 : 1;
				$this->createSubOddsGoalServe($odd,$odds->odd,$extra,$suspend,0);
				
			}
			
				
		}
		else{
			$extra = 0;
			if(isset($odds->attributes)){
				if(!isset($odds->attributes->stop)){
					$suspend = 0;
					$new_odds = $odds;
				}	
				else{
					$suspend = ($odds->attributes->stop == 'False') ? 0 : 1;
					$new_odds = $odds->odd;
				}
			}
			else{
				$suspend = 0;
				$new_odds = $odds;
			}
			$this->createSubOddsGoalServe($odd,$new_odds,$extra,$suspend,0);
		}
		return $odd;
	}
	/*
	* 
	*
	*
	**/
	public function createSubOddsGoalServe(&$odd,$odds,$extra,$suspend,$i){
		$j = 0;
		
		$handicap_array = $this->handicap_array_change_sign;
		$repeat_label = array();
		if(sizeof($odds) > 1) {
			foreach($odds as $keys=>$items){
				$new_key = ($i*2)+$j;
				$j++;
		
				$name = $items->attributes->name;
				if( ! in_array($name, $repeat_label ) ){
					$repeat_label[] = $name;
					
					$odd->{$new_key} = new stdClass();
					$odd->{$new_key}->name = $name;
					$odd->{$new_key}->value = $items->attributes->value;
					$odd->{$new_key}->extra = $extra;
					$odd->{$new_key}->suspend = $suspend;	
				}
				
				
			}
		}
		else{
			$new_key = 0;
			
			$odd->{$new_key} = new stdClass();
			$odd->{$new_key}->name = $odds->attributes->name;
			$odd->{$new_key}->value = $odds->attributes->value;
			$odd->{$new_key}->extra = $extra;
			$odd->{$new_key}->suspend = $suspend;	
		}
		
//		var_dump($repeat_label);
		

	}
	/*
	* 
	*
	*
	**/
	
	/*
	* 
	*
	*
	**/
	public function create_empty_sub_odds(){
		$subodds_array = array();
		$labels = ['home'=>'1','draw'=>'X','away'=>'2'];
		foreach($labels as $value){
			$subodds_array['data'][] = ['label' => $value,
											'value' => 0,
											'suspend' => 1,
											'winning' => false,
											'handicap' =>0,
											'total' => 0
										   ];
		}
		return $subodds_array;
	}
	/*
	* 
	*
	*
	**/
	public function create_odds($odds){
		$subodds_array = array();
		$handicap_array = $this->handicap_array_change_sign;
	
		foreach($odds as $key=>$item){
			
			if(isset($item->value)){
				$extra = (isset($item->extra)) ? $item->extra : 0;
				
				if( in_array($odds->name,$handicap_array)){
					$handicap_sign = $this->changeSign($item->name,$extra);
					$label = $item->name . " : " . $handicap_sign;
					$item->total = $item->handicap = $handicap_sign;
					
				}
				else{
					$label = ($extra == 0) ? $item->name : $item->name." : ".$extra;
					$item->total = $item->handicap = $extra;
				}

				$subodds_array['data'][] = ['label' => $label,
										'value' =>number_format( $item->value,2),
										'suspend' => $item->suspend ,
										'winning' => false,
										'handicap' => $item->handicap,
										'total' => $item->total
									   ];
			}			
		}
		
		return $subodds_array;
	}
	
	
	/////////////////////////////////////////////////////
	public function get_prematch_result ( $day ){
		
		if( ! $api = $this->get_token() )
				return false;
		
		$url = str_replace('{day}', $day, $this->result_by_day_url );
		$url = str_replace('{api}', $api['api_token'], $url );
		
		$goal = file_get_contents($url);
			
		$xml = '../upload/API/results.xml';
		file_put_contents($xml,$goal);

		return json_decode($this->convertXMLtoJSON($xml));
	}
	
	public function get_inplay_stats_event ( $page, $id ){
		
		$date = date('Ym');
		$name = $page.".json";
//		$url = "https://78.46.64.77:1337/service/storage/201805/5afaacd11d1422f5718b4567/stats.json";
		$url = "https://78.46.64.77:1337/service/storage/$date/$id/$name";
		
		return json_decode(file_get_contents($url));
		
		$name = $page.'.json';
		return json_decode(file_get_contents($name));
	}
	
	public function checkGoalNumber( $data ){
		if( is_numeric($data) )
			return $data;
		
		return 0;
	}
	
	
	
	public function checkStatusGoalServe($status){
		$status_array = ['FT','HT','NS','Live'];
		if(in_array($status,$status_array))
			return $status;
		if($status > date('H:i'))
			return 'NS';
		else if ( $status < date("H:i",strtotime("+2 Hour")))
			return 'FT';
		return 'No status';
	}
	
	
	
	public function convertXMLtoJSON($url){
		
		$fileContents= file_get_contents($url);

		$fileContents = str_replace(array("\n", "\r", "\t","'"), '', $fileContents);

		$fileContents = trim(str_replace('"', "'", $fileContents));

		$fileContents = stripslashes($fileContents);
		
		$simpleXml = simplexml_load_string($fileContents);

		$json = json_encode($simpleXml);
		
		$json = str_replace(array("@"),'',$json);

		return $json;

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
	
	public function createResults ( $results ){
		
		if ( empty( $results ) )
			return 0-0 ;
		
		return implode( '-', explode(' - ', trim(trim($results, '['), ']'))) ;
	}
	
	/* XML convert */
	
	//////////////////// Get Upcoming ///////////////////////
	
	/* 
	*	Call createJSONGoalServe : check match have is FT or Not 
	*
	*
	*
	*
	*
	**/
	
	
	public function createJSONGoalServe(&$json,$value,$page){
			
		$date = date('H:i');
		if( $page == 'results' ){
			$odds = null;
			
			if( isset($value->matches->match->ht) ){
				if($this->checkStatusGoalServe($value->matches->match->attributes->status)=='FT')
					$json['data'][] = $this->JSONGoalserve($value,$value->matches->match,$odds);
			}	
			else{
				foreach($value->matches->match as $item){
					if($this->checkStatusGoalServe($item->attributes->status) == 'FT')
						$json['data'][] = $this->JSONGoalserve($value,$item,$odds);
				}
					
			}
		}
		else if ( $page == 'upcoming' ){
			
			if(isset($value->matches->match->odds->type)){

				if( $value->matches->match->attributes->time > $date ){
					$converted_odd = $this->convertOddstoGoalServe($value->matches->match->odds->type);

					$odds = $this->createSubOddsArray($converted_odd);

					$json['data'][] = $this->JSONGoalserve($value,$value->matches->match,$odds);
				}
			}
			else{
				foreach($value->matches->match as $key=>$item){
					if( $item->attributes->time > $date ){
						$converted_odd = $this->convertOddstoGoalServe($item->odds->type);

						$odds = $this->createSubOddsArray($converted_odd);

						$json['data'][] = $this->JSONGoalserve($value,$item,$odds);
					}
				}
			}
		}
	
	}
	
	
	
	public function convertOddstoGoalServes($odds){
		$created_odds = new stdClass();
		
		
		$has_total = ['Goals Over/Under','Corners Over Under','Goals Over/Under 1st Half','Total - Home','Total - Away','Result/Total Goals','Goals Over/Under 2nd Half'];
		$has_handicap = ['Asian Handicap','Handicap_Result','Handicap Result','Handicap Result 1st Half','Asian Handicap First Half'];
		foreach($odds as $key=>$values){
			
			$value = $this->getbet365OddsGoalServe($values);
			
			if($values->attributes->stop == 'False' && $value){
				$handicap = false;
				$total = false;
				$odd_label = $values->attributes->value;
				
				$odd_label = (in_array($odd_label,$this->change_label)) ? $this->change_label[$odd_label] : $odd_label;
				if(in_array($odd_label,$has_total)){
					$total = true;
					$odd = $value->total;
				}
				elseif(in_array($odd_label,$has_handicap)){
					$handicap = true;
					$odd = $value->handicap;
				}
				else{
					if(!isset($value->odd)){
						var_dump($odd_label);
						var_dump($value);
						var_dump('odd not exist');
					}
					else
						$odd = $value->odd;
				}
				
				$label = $this->convertSpaceToUnderline($odd_label);
				$created_odds->{$this->label_array[$label]} = new stdClass();
				$created_odds->{$this->label_array[$label]} = $this->createOddsGoalServe($odd,$odd_label,$total,$handicap);
				
			}
			
		}
		
		return $created_odds;
	}
	
	public function getbet365OddsGoalServes($value){
		
		die();
		if(sizeof($value->bookmaker) == 1){
			return $value->bookmaker;
		}
	   else{
			foreach($value->bookmaker as $keys=>$item){
				if($item->attributes->name == 'bet365'){
					return $item;
				}
			}
		   
		}
		return false;
	}
	
	public function createOddsGoalServes($odds,$label,$total,$handicap){
		$odd = new stdClass();
		$odd->id = null;
		$odd->name = $label;
		$odd->suspend = 0;
		
		if($handicap || $total){
			// if have multi handicap label
			
			if(sizeof($odds) > 1){
				foreach($odds as $key=>$item){
					$extra = $item->attributes->name;
					$suspend = ($item->attributes->stop == 'False') ? 0 : 1;
					
					$this->createSubOddsGoalServe($odd,$item->odd,$extra,$suspend,$key);	
				}
			}
			else{
				$extra = $odds->attributes->name;
				
				$suspend = ($odds->attributes->stop == 'False') ? 0 : 1;
				$this->createSubOddsGoalServe($odd,$odds->odd,$extra,$suspend,0);
				
			}
			
				
		}
		elseif($total){
			var_dump('total');
		}
		else{
			$extra = 0;
			if(isset($odds->attributes)){
				if(!isset($odds->attributes->stop)){
					$suspend = 0;
					$new_odds = $odds;
				}	
				else{
					$suspend = ($odds->attributes->stop == 'False') ? 0 : 1;
					$new_odds = $odds->odd;
				}
			}
			else{
//				var_dump($odds);
				$suspend = 0;
				$new_odds = $odds;
			}
			$this->createSubOddsGoalServe($odd,$new_odds,$extra,$suspend,0);

		}
		return $odd;
	}
	
	public function createSubOddsGoalServes($odd,$odds,$extra,$suspend,$i){
		$j = 0;
		
		$handicap_array = $this->handicap_array_change_sign;
		$repeat_label = array();
		if(sizeof($odds) > 1) {
			foreach($odds as $keys=>$items){
				$new_key = ($i*2)+$j;
				$j++;
		
				$name = $items->attributes->name;
				if( ! in_array($name, $repeat_label ) ){
					$repeat_label[] = $name;
					
					$odd->{$new_key} = new stdClass();
					$odd->{$new_key}->name = $name;
					$odd->{$new_key}->value = $items->attributes->value;
					$odd->{$new_key}->extra = $extra;
					$odd->{$new_key}->suspend = $suspend;	
				}
				
				
			}
		}
		else{
			$new_key = 0;
			
			$odd->{$new_key} = new stdClass();
			$odd->{$new_key}->name = $odds->attributes->name;
			$odd->{$new_key}->value = $odds->attributes->value;
			$odd->{$new_key}->extra = $extra;
			$odd->{$new_key}->suspend = $suspend;	
		}
		
//		var_dump($repeat_label);
		

	}
	
	public function createSubOddsArrayGoalServe($odds){
		$bet_array = array();
		$type_array = array();
		$not_empty = false;
		$types = array();
		$fulltime_result = false;
		
		if( empty($odds) ){
			// create bet for fulltime result with suspend 1
			$subodds = $this->create_empty_sub_odds();
			$types['data'][] = ['type' => '1x2',
								'bookmaker_test' => 'bet',
								'odds' => $subodds,
								];
		}
		else{
			foreach( $odds as $key=>$value){
				$label_array = ['fulltime_result' => '1x2',
							   ];
				
				
				if($key == 'fulltime_result')
						$fulltime_result = true;
				
				// check first odd is full time result if not create empty odd
				if( $fulltime_result == false ){
					$subodds = $this->create_empty_sub_odds();
					$types['data'][] = ['type' => '1x2',
										'bookmaker_test' => 'bet',
										'odds' => $subodds,
										];
					$fulltime_result = true;
				}
				
				if(array_key_exists($key,$label_array)){
					$subodds = $this->{"odds_label_".$key."_goal_serve"}($value);
					$label = $label_array[$key];
				}
				else{
					$subodds = $this->create_odds($value);
					$label = $value->name;
				}
				$types['data'][] = ['type' => $label,
								'bookmaker_test' => 'bet',
								'odds' => $subodds,
								];
			}

		}
		
		// if have not odds create default 1x2 odds with value 0 suspend 1
		if(empty($types)){
			$types['data'][] = ['type' => '1x2',
								'bookmaker_test' => 'bet',
								'odds' => $this->create_empty_sub_odds(),
								];
		}
		$odds = ['data' => [[
						'bookmaker_id' => '2',
						'name' => 'bet365',
						'types' => $types
						] ]
				];
		return($odds);
		
		
	}
	
	public function JSONGoalserve($value,$match,$odds){
		$ht = $match->ht;
		$home = $match->localteam;
		$away = $match->visitorteam;
		$status = $this->checkStatusGoalServe($match->attributes->status);
		if( isset($match->events) )
			$events = $this->createEvents( $match );
		else
			$events = null;
		
		
		$home_goal = $this->checkGoalNumber($home->attributes->goals);
		$away_goal = $this->checkGoalNumber($away->attributes->goals);
		
		$json = [
				'API_id' =>$match->attributes->id,
				'id' => $match->attributes->id,
				'weather' => null,
				'temperature' => null,
				'commentary' => null,
				'ht_score' => $ht->attributes->score,
				'ft_score' => $home_goal."-".$away_goal,
				'et_score' => null,
				'home_team_id' => null,
				'away_team_id' => null,
				'home_score' => $home_goal,
				'away_score' =>  $away_goal,
				'home_score_penalties' => null,
				'away_score_penalties' =>  null,

				'formation' => [
						'home' => null ,
						'away' => null
						],
				'date_time_tba' => $match->attributes->formatted_date,
				'spectators' => null,
				'starting_date' => $match->attributes->formatted_date,
				'starting_time' => $match->attributes->time,
				'timestamp' => $match->attributes->time,
				'status' => $status, /*$info->state_name,*/
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
				
				'events' => $events,
			
				'homeTeam' => [
						'id' => $home->attributes->id,
						'legacy_id' => null,
						'name' => $home->attributes->name,
						'logo' => null,
						'twitter' => null,
						'country_id' => null,	
						'national_team' => null,
						'founded' => null,
						'venue_id' => null,
						'coach_id' => null,
						'chairman_id' => null,
						'yellow_cards' => 0,
						'red_cards' =>0,
						'corners' => 0,
						],
				'awayTeam' => [
						'id' => $away->attributes->id,
						'legacy_id' => null,
						'name' => $away->attributes->name,
						'logo' => null,
						'twitter' => null,
						'country_id' => null,	
						'national_team' => null,
						'founded' => null,
						'venue_id' => null,
						'coach_id' => null,
						'chairman_id' => null,
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
	
	
	
	/* JSON */
	
	public function createJSON($value){

		$odds = $this->createSubOddsArray($value->odds);
		
		$info = $value->info;
		$stats = $value->stats;
		$stats1 = ['yellowcards' => $stats->home->yellowcards,
				   'red_cards' => $stats->home->redcards,
				   'corners' => $stats->home->corners,
				  ];
		$stats2 = ['yellowcards' => $stats->away->yellowcards,
				   'red_cards' => $stats->away->redcards,
				   'corners' => $stats->away->corners,
				  ];
		
		$status_name = ( $info->period == 'Finished') ? 'FT' : 'LIVE';
		
		$this->check_live_match_goals( $value );
			
		$json = [
				'API_id' =>$info->event_id,
				'id' => $info->id,
				'weather' => null,
				'temperature' => null,
				'commentary' => null,
				'ht_score' => $stats->home->ht_result.'-'.$stats->away->ht_result,
				'ft_score' => implode('-',explode(':',$info->score)),
				'et_score' => null,
				'home_team_id' => null,
				'away_team_id' => null,
				'home_score' => $stats->home->goals,
				'away_score' =>  $stats->away->goals,
				'home_score_penalties' => $stats->home->penalties,
				'away_score_penalties' =>  $stats->away->penalties,

				'formation' => [
						'home' => null ,
						'away' => null
						],
				'date_time_tba' => $info->start_time,
				'spectators' => null,
				'starting_date' => explode(' ',$info->start_time)[0],
				'starting_time' => explode(' ',$info->start_time)[1],
				'timestamp' => $info->start_time,
				'status' => $status_name, /*$info->state_name,*/
				'minute' => $info->minute,
				'second' => 0,
				'injury_time' => null,
				'extra_time' => null,
				'competition_id' => $this->convertSpaceToUnderline($info->league),
				'venue_id' => null,
				'season_id' => null,
				'round_id' => null,
				'stage_id' => null,
				'referee_id' => null,
				'aggregate' => null,
				'placeholder' => false,
				'deleted' => $info->blocked,
				'result_only' => null,
				'winning_odds_calculated' => false,

				'homeTeam' => [
						'id' => $this->convertSpaceToUnderline($stats->home->name),
						'legacy_id' => null,
						'name' => $stats->home->name,
						'logo' => null,
						'twitter' => null,
						'country_id' => null,	
						'national_team' => null,
						'founded' => null,
						'venue_id' => null,
						'coach_id' => null,
						'chairman_id' => null,
						'yellow_cards' => $stats->home->yellowcards,
						'red_cards' =>$stats->home->redcards,
						'corners' => $stats->home->corners,
						'stats' => $stats1,
						],
				'awayTeam' => [
						'id' => $this->convertSpaceToUnderline($stats->away->name),
						'legacy_id' => null,
						'name' => $stats->away->name,
						'logo' => null,
						'twitter' => null,
						'country_id' => null,	
						'national_team' => null,
						'founded' => null,
						'venue_id' => null,
						'coach_id' => null,
						'chairman_id' => null,
						'yellow_cards' => $stats->away->yellowcards,
						'red_cards' =>$stats->away->redcards,
						'corners' => $stats->away->corners,
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
	
	public function createOddsArray($bookmaker_name,$bet_array){
		
		$odd_array = [ 'data' => [
								[ 'bookmaker_id' => 2,
							  'name' => $bookmaker_name,
							  'types' => ['data' => $bet_array]
								]
							]
						];
		
		return $odd_array;
	}
	
	
	
	/* gets the data from a URL */
	public function get_data($url) {
		$ch = curl_init();
		$timeout = 5;
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, $timeout);
		$data = curl_exec($ch);
		curl_close($ch);
		return $data;
	}
	
	public function getUpcoming($day=0){
		
		$direction = "../upload/API";
		$home = "/home";
		$inPlay = "/inPlay";
		$upcoming = "/upcoming";
		$json_matches = array();
		
		$content = $this->get_content('upcoming',$day);
		
			if(!$content)
				return(false);
		
//			$content_json = json_decode($content);

//			$page = $content_json->meta->pagination->total_pages;
			
			$json = array();
			foreach( $content->category as $key=>$value){
				
				$odd_array = array();
				$bet_array = array();	
				$type_array = array();
//				if($value->info->period != 'Finished' || $value->info->state_name != "Fulltime")
					
					$this->createJSONGoalServe($json,$value,'upcoming');
				
//					$json['data'][] = $this->createJSONGoalServe($value);
//
			
			}
			
		file_put_contents("$direction$upcoming/day_$day.json",json_encode($json));
		
		
		return true;
		
	}
	
	public function getresults($day=0){
		
		$direction = "../upload/API";
		$home = "/home";
		$inPlay = "/inPlay";
		$upcoming = "/upcoming";
		$json_matches = array();
		
		$content = $this->get_content('results',$day);
		
			if(!$content)
				return(false);
		
//			$content_json = json_decode($content);

//			$page = $content_json->meta->pagination->total_pages;
			
			$json = array();
		
			if ( !empty($content->category) ){
				foreach( $content->category as $key=>$value){

					$odd_array = array();
					$bet_array = array();	
					$type_array = array();
					
	//				if($value->info->period != 'Finished' || $value->info->state_name != "Fulltime")
						
						$this->createJSONGoalServe($json,$value,'results');
					

	//					$json['data'][] = $this->createJSONGoalServe($value);
	//

				}
			}
		
		for ( $i=1; $i<3; $i++ ){
			
			$content = $this->get_prematch_result( $i );
			
			if ( !$content )
				return false;
			
			if ( !empty( $content->category ) ){
				foreach( $content->category as $key=>$value){
					
					$odd_array = array();
					$bet_array = array();	
					$type_array = array();
				
					$this->createJSONGoalServe($json,$value,'results');
				}
			}
		}
		
			
		// get from inplay.json
			$content = $this->get_content('inplay',$day);
			
			if ( !empty($content) ){
				foreach( $content->events as $key=>$value){
					
					if($value->info->period == 'Finished' || $value->info->state_name == "Fulltime")
						$json['data'][] = $this->createJSON($value);
				}
		
			}
			
		// get from db
		$db = new DB();
		
//		$query = " SELECT DISTINCT match_id FROM bet_form WHERE result_status = '0' ";
//		$match = $db->get_query($query);
		
		$param = Array( 0 );
		$query = "SELECT DISTINCT match_id FROM bet_form WHERE result_status = ? ";
		$match = $db->_mysqli->rawQuery( $query, $param );
		
//		$match = $db->select('bet_form','DISTINCT match_id','result_status',0);
		
		$iterator = new RecursiveIteratorIterator(new RecursiveArrayIterator($match));
		$match_id = iterator_to_array($iterator,false);
		
		foreach( $match_id as $key=>$value ){
			
			$stats = $this->get_inplay_stats_event( 'stats', $value );
			$info = $this->get_inplay_stats_event( 'event', $value );
			
			if ( ! empty( $info ) ){
				
				$event = new stdClass();
				$event->{$value} = new stdClass();
				$event->{$value}->info = $info;
	//			$event->{$value}->info->blocked = false;
				$event->{$value}->stats = $stats;
				$event->{$value}->odds = array();

				$json['data'][] = $this->createJSON($event->{$value});
			}
			
		}
	
		
		file_put_contents("$direction$upcoming/results$day.json",json_encode($json));
		file_put_contents("$direction$upcoming/results.json",json_encode($json));
		
		
		return true;
		
	}
	
	public function getInplay(){
		
		$direction = "../upload/API";
		$home = "/home";
		$inPlay = "/inPlay";
		$upcoming = "/upcoming";
		$json_matches = array();
		
		$content = $this->get_content('inplay');
			
			if(!$content)
				return(false);
			
//			$content_json = json_decode($content);

//			$page = $content_json->meta->pagination->total_pages;
			
			$json = array();
			foreach( $content->events as $key=>$value){
				
				$info = $value->info;
				$stats = $value->stats;
				$odds = $value->odds;
			
				$odd_array = array();
				$bet_array = array();	
				$type_array = array();
				if($value->info->period != 'Finished' || $value->info->state_name != "Fulltime")
					$json['data'][] = $this->createJSON($value);

			
			}
			
		file_put_contents("$direction$inPlay/matches.json",json_encode($json));
		
		
		return true;
		
	}
	
	public function getEvent(){
		
		$direction = "../upload/API";
		$home = "/home";
		$inPlay = "/inPlay";
		$upcoming = "/upcoming";
		$json_matches = array();
		
		$content = $this->get_content('events');
		
			if(!$content)
				return(false);
		
//			$content_json = json_decode($content);

//			$page = $content_json->meta->pagination->total_pages;
			
			$json = array();
			foreach( $content->category as $key=>$value){
				
				$odd_array = array();
				$bet_array = array();	
				$type_array = array();
//				if($value->info->period != 'Finished' || $value->info->state_name != "Fulltime")
					
					$this->createJSONGoalServe($json,$value);
				
//					$json['data'][] = $this->createJSONGoalServe($value);
//
			
			}
			
		file_put_contents("$direction$upcoming/day_$day.json",json_encode($json));
		
		
		return true;
		
	}
	
	public function check_live_match_goals( $match ){
		
		$db = new DB();
		$match_id = $match->info->id;
		$result = implode('-',explode( ":" ,$match->info->score));
//		$query = "SELECT result FROM online_result WHERE match_id = '$match_id' ORDER BY id DESC LIMIT 1 ";
//		$match_exist = $db->get_query( $query );
		
		$db->_mysqli->where( 'match_id', $match_id );
		$db->_mysqli->orderBy( 'id', 'desc' );
		$match_exist = $db->_mysqli->get( 'online_result', null, 'result' );
		
		$side = 'No Goal';
		$status = $this->checkStatusGoalServe( $match->info->status );
		$minute = isset( $match->info->minute ) ? $match->info->minute : 0 ;
		if ( $match_exist != 0){
			
			if ( $result == $match_exist[0]['result'] )
				return;
			
			$new_result = explode( '-', $result );
			$old_result = explode( '-', $match_exist[0]['result']);
			
			if ( $new_result[0] > $old_result[0] && $new_result[1] > $old_result[1] )
				$side = 'unknown';
			else if( $new_result[0] > $old_result[0] )
				$side = 'Home';
			else if ( $new_result[1] > $old_result[1] )
				$side = 'Away';
		}
		
		$query = "INSERT INTO online_result (match_id, type, result, minute, status, side) VALUES ('$match_id','goal','$result', '$minute','$status','$side') ";
			
		$insert = $db->get_insert_query( $query );
			
	}
	
	/* create odds function */
	
	public function odds_create($odds,$labels,$type){
		$subodds_array = array();
		$handicap_array = $this->handicap_array_change_sign;
		
		foreach($labels as $key=>$item){
			
			if(isset($odds->{$key})){
				if( $type == 'normal' ){
					$label = $item;
					$total = $handicap = 0;
				}
				else {
					$extra = $odds->{$key}->extra;
					
					$handicap_sign = (in_array($odds->name,$handicap_array)) ? $this->changeSign($item,$extra) : $extra;
					
					$label = $item . " : " . $handicap_sign;
					$total = $handicap = $handicap_sign;
					
				}
				
				$value = $odds->{$key}->value;
				$suspend = $odds->$key->suspend;
			}
			elseif($odds->name == "1x2"){
				$label = $item ;
				$total = $handicap = $value = 0;
				$suspend = 1;
			}
			$subodds_array['data'][] = ['label' => $label,
										'value' => number_format($value,2),
										'suspend' => $suspend ,
										'winning' => false,
										'handicap' => $handicap,
										'total' => $total
									   ];
			
			
			
		}
		return $subodds_array;
	}
	
	
	/* label function */
	
	
	
	public function odds_label_1X2_soccer($odds){

		$odds_label = array_keys((array) $odds);
		$labels = [$odds_label[3] => '1', $odds_label[4] => 'X', $odds_label[5]=>'2'];
		
		return $this->odds_create($odds,$labels,'normal');
	}
	
	public function odds_label_1X2_basket($odds){

		var_dump( 'odds label basket ');
		$odds_label = array_keys((array) $odds);
		$home = true;
		$away = true;
		if ( !isset( $odds_label[5] ) ){
			$odds_labels = $odds_label;

			$odds_label[3] = '_1';
			$odds_label[4] = '_X';
			$odds_label[5] = '_2';

			foreach ( $odds_labels as $labels ){
				
				if ( isset($odds->{$labels}->name) ){
					
					if ( $odds->{$labels}->name == 'Home' || $odds->{$labels}->name == '1' )
						$odds_label[3] = $labels;
					else if ($odds->{$labels}->name == 'Away' || $odds->{$labels}->name == '2')
						$odds_label[5] = $labels;
					else if ($odds->{$labels}->name == 'Draw' || $odds->{$labels}->name == 'X')
						$odds_label[4] = $labels;
					else if ( $home === true ){
						$odds_label[3] = $labels;
						$home = false;
					}
					else if ( $away === true){
						$odds_label[5] = $labels;
						$away = false;
					}
					
					
					
				}
			}

			
			}
		
		$labels = [$odds_label[3] => '1', $odds_label[4] => 'X', $odds_label[5]=>'2'];
		var_dump( $labels );
		return $this->odds_create($odds,$labels,'normal');
	}
	
	public function odds_label_1X2_tennis($odds){
		
		$odds_labels = $odds_label = array_keys((array) $odds);
		
		$odds_label[3] = '_1';
		$odds_label[4] = '_2';
	
		foreach ( $odds_labels as $labels ){
			if ( isset($odds->{$labels}->name) ){
				if ( $odds->{$labels}->name == 'Home' || $odds->{$labels}->name == '1' )
					$odds_label[3] = $labels;
				else if ($odds->{$labels}->name == 'Away' || $odds->{$labels}->name == '2')
					$odds_label[4] = $labels;
			}
		}
		
		$odds_label[5] = isset($odds_label[4]) ? $odds_label[4] : null;
		$odds_label[4] = null;
		
		$labels = [$odds_label[3] => '1', $odds_label[4] => 'X', $odds_label[5] => '2'];
		
		return $this->odds_create($odds,$labels,'normal');
	}
	
	public function odds_label_1X2_handball($odds){
		
		$odds_labels = $odds_label = array_keys((array) $odds);
		
		$odds_label[3] = '_1';
		$odds_label[4] = '_2';
	
		foreach ( $odds_labels as $labels ){
			if ( isset($odds->{$labels}->name) ){
				if ( $odds->{$labels}->name == 'Home' || $odds->{$labels}->name == '1' )
					$odds_label[3] = $labels;
				else if ($odds->{$labels}->name == 'Away' || $odds->{$labels}->name == '2')
					$odds_label[4] = $labels;
			}
		}
		
		$odds_label[5] = isset($odds_label[4]) ? $odds_label[4] : null;
		$odds_label[4] = null;
		
		$labels = [$odds_label[3] => '1', $odds_label[4] => 'X', $odds_label[5] => '2'];
		
		return $this->odds_create($odds,$labels,'normal');
	}
	
	public function odds_label_1X2_volleyball($odds){
		
		$odds_labels = $odds_label = array_keys((array) $odds);
		
		$odds_label[3] = '_1';
		$odds_label[4] = '_2';
	
		foreach ( $odds_labels as $labels ){
			if ( isset($odds->{$labels}->name) ){
				if ( $odds->{$labels}->name == 'Home' || $odds->{$labels}->name == '1' )
					$odds_label[3] = $labels;
				else if ($odds->{$labels}->name == 'Away' || $odds->{$labels}->name == '2')
					$odds_label[4] = $labels;
			}
		}
		
		$odds_label[5] = isset($odds_label[4]) ? $odds_label[4] : null;
		$odds_label[4] = null;
		
		$labels = [$odds_label[3] => '1', $odds_label[4] => 'X', $odds_label[5] => '2'];
		
		return $this->odds_create($odds,$labels,'normal');
	}
	
	public function odds_label_1X2_football($odds){
		
		$odds_labels = $odds_label = array_keys((array) $odds);
		
		$odds_label[3] = '_1';
		$odds_label[4] = '_2';
	
		foreach ( $odds_labels as $labels ){
			if ( isset($odds->{$labels}->name) ){
				if ( $odds->{$labels}->name == 'Home' || $odds->{$labels}->name == '1' )
					$odds_label[3] = $labels;
				else if ($odds->{$labels}->name == 'Away' || $odds->{$labels}->name == '2')
					$odds_label[4] = $labels;
			}
		}
		
		$odds_label[5] = isset($odds_label[4]) ? $odds_label[4] : null;
		$odds_label[4] = null;
		
		$labels = [$odds_label[3] => '1', $odds_label[4] => 'X', $odds_label[5] => '2'];
		
		return $this->odds_create($odds,$labels,'normal');
	}
	
	public function odds_label_1X2_baseball($odds){
		
		$odds_labels = $odds_label = array_keys((array) $odds);
		
		$odds_label[3] = '_1';
		$odds_label[4] = '_2';
	
		foreach ( $odds_labels as $labels ){
			if ( isset($odds->{$labels}->name) ){
				if ( $odds->{$labels}->name == 'Home' || $odds->{$labels}->name == '1' )
					$odds_label[3] = $labels;
				else if ($odds->{$labels}->name == 'Away' || $odds->{$labels}->name == '2')
					$odds_label[4] = $labels;
			}
		}
		
		$odds_label[5] = isset($odds_label[4]) ? $odds_label[4] : null;
		$odds_label[4] = null;
		
		$labels = [$odds_label[3] => '1', $odds_label[4] => 'X', $odds_label[5] => '2'];
		
		return $this->odds_create($odds,$labels,'normal');
	}
	
	public function odds_label_1X2_cricket($odds){
		
		$odds_labels = $odds_label = array_keys((array) $odds);
		
		$odds_label[3] = '_1';
		$odds_label[4] = '_2';
	
		foreach ( $odds_labels as $labels ){
			if ( isset($odds->{$labels}->name) ){
				if ( $odds->{$labels}->name == 'Home' || $odds->{$labels}->name == '1' )
					$odds_label[3] = $labels;
				else if ($odds->{$labels}->name == 'Away' || $odds->{$labels}->name == '2')
					$odds_label[4] = $labels;
			}
		}
		
		$odds_label[5] = isset($odds_label[4]) ? $odds_label[4] : null;
		$odds_label[4] = null;
		
		$labels = [$odds_label[3] => '1', $odds_label[4] => 'X', $odds_label[5] => '2'];
		
		return $this->odds_create($odds,$labels,'normal');
	}
	
	public function odds_label_1X2_rugby($odds){
		
		$odds_labels = $odds_label = array_keys((array) $odds);
		
		$odds_label[3] = '_1';
		$odds_label[4] = '_2';
	
		foreach ( $odds_labels as $labels ){
			if ( isset($odds->{$labels}->name) ){
				if ( $odds->{$labels}->name == 'Home' || $odds->{$labels}->name == '1' )
					$odds_label[3] = $labels;
				else if ($odds->{$labels}->name == 'Away' || $odds->{$labels}->name == '2')
					$odds_label[4] = $labels;
			}
		}
		
		$odds_label[5] = isset($odds_label[4]) ? $odds_label[4] : null;
		$odds_label[4] = null;
		
		$labels = [$odds_label[3] => '1', $odds_label[4] => 'X', $odds_label[5] => '2'];
		
		return $this->odds_create($odds,$labels,'normal');
	}
	
	public function odds_label_1X2_boxing($odds){
		
		$odds_labels = $odds_label = array_keys((array) $odds);
		
		$odds_label[3] = '_1';
		$odds_label[4] = '_2';
	
		foreach ( $odds_labels as $labels ){
			if ( isset($odds->{$labels}->name) ){
				if ( $odds->{$labels}->name == 'Home' || $odds->{$labels}->name == '1' )
					$odds_label[3] = $labels;
				else if ($odds->{$labels}->name == 'Away' || $odds->{$labels}->name == '2')
					$odds_label[4] = $labels;
			}
		}
		
		$odds_label[5] = isset($odds_label[4]) ? $odds_label[4] : null;
		$odds_label[4] = null;
		
		$labels = [$odds_label[3] => '1', $odds_label[4] => 'X', $odds_label[5] => '2'];
		
		return $this->odds_create($odds,$labels,'normal');
	}
	
	public function odds_label_1X2_esports($odds){
		
		$odds_labels = $odds_label = array_keys((array) $odds);
		
		$odds_label[3] = '_1';
		$odds_label[4] = '_2';
	
		foreach ( $odds_labels as $labels ){
			if ( isset($odds->{$labels}->name) ){
				if ( $odds->{$labels}->name == 'Home' || $odds->{$labels}->name == '1' )
					$odds_label[3] = $labels;
				else if ($odds->{$labels}->name == 'Away' || $odds->{$labels}->name == '2')
					$odds_label[4] = $labels;
			}
		}
		
		$odds_label[5] = isset($odds_label[4]) ? $odds_label[4] : null;
		$odds_label[4] = null;
		
		$labels = [$odds_label[3] => '1', $odds_label[4] => 'X', $odds_label[5] => '2'];
		
		return $this->odds_create($odds,$labels,'normal');
	}
	
	public function odds_label_1X2_futsal($odds){
		
		$odds_labels = $odds_label = array_keys((array) $odds);
		
		$odds_label[3] = '_1';
		$odds_label[4] = '_2';
	
		foreach ( $odds_labels as $labels ){
			if ( isset($odds->{$labels}->name) ){
				if ( $odds->{$labels}->name == 'Home' || $odds->{$labels}->name == '1' )
					$odds_label[3] = $labels;
				else if ($odds->{$labels}->name == 'Away' || $odds->{$labels}->name == '2')
					$odds_label[4] = $labels;
			}
		}
		
		$odds_label[5] = isset($odds_label[4]) ? $odds_label[4] : null;
		$odds_label[4] = null;
		
		$labels = [$odds_label[3] => '1', $odds_label[4] => 'X', $odds_label[5] => '2'];
		
		return $this->odds_create($odds,$labels,'normal');
	}
	
	public function check_1x2_label ( $label, $label_type ){
		$array = [ 'home' => ['Home', 1, 'home'],
				   'away' => ['Away', 1, 'away'],
				   'draw' => ['Draw', 'X', 'draw']
				 ];
		if ( in_array($label, $array[$label_type]) )
			return true;
		return false;
	}
	
	
//	public function odds_label_match_goals($odds){
//		
//		$labels = ['over'=>"Over",'under'=>'Under'];
//		
//		return $this->odds_create($odds,$labels,'over/under');
//	}
//	
//	public function odds_label_3_way_handicap($odds){
//		
//		$labels = ['home'=>"Home",'draw'=>'Draw', 'away'=>'Away'];
//		
//		return $this->odds_create($odds,$labels,'handicap');
//	}
//	
//	public function odds_label_1st_half_3_way_handicap($odds){
//		
//		$labels = ['home'=>"Home",'draw'=>'Draw', 'away'=>'Away'];
//		
//		return $this->odds_create($odds,$labels,'handicap');
//	}
//	
//	public function odds_label_double_chance($odds){
//		
//		$labels = ['home_or_draw'=>"Home/Draw",'away_or_draw'=>'Away/draw','home_or_away'=>'Home/Away'];
//		
//		return $this->odds_create($odds,$labels,'normal');
//	}
//	
//	public function odds_label_draw_no_bet($odds){
//		
//		$labels = ['home'=>"Home",'draw'=>'Draw','away'=>'Away'];
//		
//		return $this->odds_create($odds,$labels,'normal');
//	}
	
	
	
}
?> 