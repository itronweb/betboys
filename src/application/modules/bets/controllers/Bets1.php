<?php

(defined('BASEPATH')) OR exit('No direct script access allowed');

/**
 * Content_field_types Controller
 *
 * @copyright   Copyright (c) 2015
 * @license     MIT License
 */
require_once 'FoldTrait.php';
require_once 'Api.php';
require_once APPPATH . 'config/db.php';
require_once APPPATH . 'config/api_function.php';
require_once APPPATH . 'config/config_api_address.php';
require_once APPPATH . 'smarty/plugins/modifier.fa.php';


use MarkWilson\XmlToJson\XmlToJsonConverter;

class Bets extends Public_Controller {

    use FoldTrait;
	
	private $destination = "http://pishgu.com/demo/upload/API/";

	
    public function index () {
//		$url = "/bets/index";
//		redirect($url);
		
		$db = new DB;
		$sport = $db->select('sport','*','flag','1');
		
		///////////////////
		$league_name = $db->select('league_tables','*');
//				var_dump($league_name);
		$league = array();
		foreach($league_name as $key=>$value){
			$league[$value['continent']][] = $value ;
		}
		
		/////////////////
		
		$slideshow = $db->select('slideshow','*','status','1');
				 /////////////////////////////
		 $db = new DB(); 
		$slideshow = $db->multi_select('slideshow','*',['status','category'],['1','0'],'sort ASC');
		
		function multiarray_sort( $a , $b ){
			if($a['sort'] > $b['sort'])
				return true;
			return false;
		}
		usort($slideshow,'multiarray_sort');
		
		/////////////////casino games
		
		$casino_games = $db->select('casino','*','status','1');
				 /////////////////////////////
		
		$casino_games = $db->multi_select('casino','*',['status'],['1'],'sort ASC limit 6');
		
		usort($casino_games,'multiarray_sort');
		
		
		//////////////////
		$query = 'SELECT * FROM advertise WHERE status = 1 AND category = 2';
		$banner_right = $db->get_query($query);
		
		//////////////////
		$query = 'SELECT * FROM advertise WHERE status = 1 AND category = 1';
		$banner_left = $db->get_query($query);
		
		////////////////////
		
		/* Result today from db */
		$match_number = 20;
		$query = "SELECT id FROM matches WHERE result = 1";
		$result_today_id = $db->get_query($query);
		
//		$results = json_decode(file_get_contents(API_DIR . "upcoming/results.json"));
//		$resultsToday = array();
		
//		$iterator = new RecursiveIteratorIterator(new RecursiveArrayIterator($result_today_id));
//		$result_today_id = iterator_to_array($iterator,false);
		
//		if ( isset( $results->data ) ){
//			foreach($results->data as $key=>$value){
//
//				if(in_array($value->id,$result_today_id)){
//					$resultsToday[] = $value; 
//				}
//
//			}
//		}
		
		/* End of Result today from json file */
		
		
		/*   Result today From json file */
//		$match_number = 20;
//		$results = json_decode(file_get_contents(API_DIR . "upcoming/results0.json"));
//		$resultsToday1 = array();
//		function filter_array($results){
//				if($results->status == 'FT')
//					return true;
//				else
//					return false;
//		}
//		$resultsToday = array_filter($results->data,"filter_array");
//		
//		if( count($resultsToday) < $match_number ){
//			$results = json_decode(file_get_contents(API_DIR . "upcoming/results1.json"));
//			function filter_array1($results){
//					if($results->status == 'FT')
//						return true;
//					else
//						return false;
//			}
//			$resultsToday1 = array_filter($results->data,"filter_array1");
//			$resultsToday1 = array_slice(array_reverse($resultsToday1),0,($match_number-count($resultsToday)));
//			
//		}
//		$resultsToday = array_merge(array_reverse($resultsToday),$resultsToday1);
		
		/* End of Result today from json file */
		
		//////////////////////////////////////////
        $date = gmdate('Y-m-d H:i:s' , time());
        $LowInbound = gmdate('H:i:s');
		
        $finalObject = array();
		
		if(!isset($_SESSION['totalStake']))
			$_SESSION['totalStake'] = null;
		
        $this->smart->assign([
            'matches' => array_slice($finalObject , 0 , 15) ,
//			'matches' => array() ,
//            'resultsToday' => $resultsToday,
			'totalStake' => $this->getTotalStake(),
			'sport' => $sport,
			'league' => $league,
			'userTest' => $this->getUser(),
			'slideshow' => $slideshow,
			'casino_games' => $casino_games,
			'banner_right' => $banner_right,
			'banner_left' => $banner_left,
        ]);
        $this->smart->view('index');
    }
	
	public function select_leage_table(  ){
		$db = new DB();
		$league_name = $db->select('leagues','*');
		$league = array();
		foreach($league_name as $key=>$item){
			if(empty($item['country_name_en']))
				$item['country_name_en'] = ' ';
			$league[$item['leagues_id']]['country'] = (!empty($item['country_name_fa'])) ? $item['country_name_fa'] : $item['country_name_en'];
			$league[$item['leagues_id']]['name'] = (!empty($item['leagues_name_fa'])) ? $item['leagues_name_fa'] : $item['leagues_name_en'];
		}
		return($league);
	}
	
	
	public function select_leage_order_sort($type ,$match){
		$db = new DB();
		$league_name = $db->multi_select('leagues','leagues_id',['type'],[$type],'sort ASC');
		//var_dump($league_name);
		$sort = array();
		if(isset($league_name) && $league_name>0){
			foreach($league_name as $key=>$value){
				if(isset($match[$value['leagues_id']])){
					$sort[$value['leagues_id']] = $match[$value['leagues_id']];
					unset($match[$value['leagues_id']]);
				}
			}
		}
		foreach($match as $key=>$value){
			$sort[$key] = $value;
		}

		return($sort);
	}

	public function set_team_name( $teams, &$item ){
		
		if ( empty( $teams )) 
			return ;
		
		$home = $item->homeTeam->id;
		$away = $item->awayTeam->id;
		
		if ( isset($teams->{$home}) ){
			if ( $teams->{$home}->name_fa != '')
				$item->homeTeam->show_name = $teams->{$home}->name_fa;	
			else
				$item->homeTeam->show_name = $item->homeTeam->name;
		}
		else {
			$item->homeTeam->show_name = $item->homeTeam->name;
		}
		
		if ( isset($teams->{$away}) ){
			if ( $teams->{$away}->name_fa != '')
				$item->awayTeam->show_name = $teams->{$away}->name_fa;
			else
				$item->awayTeam->show_name = $item->awayTeam->name;
			
		}
		else{
			$item->awayTeam->show_name = $item->awayTeam->name;
		}
		
		
//		$item->awayTeam->name = 111111;
	}
	
	public function set_team_name_with_id ( $soccer_name, $team_id, $defualt_name ){
		

		 $team_name = API_DIR . "$soccer_name/team_name.json";
		 if ( file_exists($team_name) )
			$teams = json_decode(file_get_contents($team_name));
		 else 
			$teams = null;

		
		if ( isset($teams->{$team_id}) ){
			if ( $teams->{$team_id}->name_fa != '')
				return $teams->{$team_id}->name_fa;	
			else
				return $defualt_name;
		}
		else {
			return $defualt_name;
		}
		
		
		
	}
	
	public function get_soccer($type){
		$type = 'is_'.$type;
		 $db = new DB();

		$query =  "SELECT name_en , name ,logo FROM `sports` where $type = 1 and status = 1  ORDER BY `sports`.`sort` ASC";
	$result_sport = $db->get_query($query );
	 $sport;
		foreach ( $result_sport as $key=>$value){
			$sport[$value['name_en']] = [ 'en' => $value['name_en'],
							'fa' => $value['name'],
							'logo' => $value['logo']
					   ];
			}
		return($sport);
}

		
	
	public function changeBackColor(){
		if(isset($_POST)){
			$i = 1;
			for($i=1 ; $i<5 ; $i++){
				if(isset($_POST["color$i"])){
					$_SESSION['color'] = $_POST["colors$i"];
					break;
				}
			}
		}
//		$_SESSION['color'] = $_POST['colors'];
//		var_dump($_SERVER);
		$url = $_POST['requested_url'];
//		var_dump($url);
		redirect($url);
//		header("location : $url");
		
	}
	
	public function changeFavoriteOdds(){
		if(isset($_POST)){
			$is_exist = false;
			if(!isset($_SESSION['favorite'])){
				$_SESSION['favorite'] = '';
			}
				if($_SESSION['favorite'] != ''){
					$favorite = explode('-',$_SESSION['favorite']);
					
					foreach($favorite as $key=>$value){
						if( $value == $_POST['id']){
							unset($favorite[$key]);
							$is_exist = true;
							break;
						}
					}
					$_SESSION['favorite'] = implode('-',$favorite);
				}
				if(!$is_exist)
						$_SESSION['favorite'] .= $_POST['id'] . "-";
			
		//	$_SESSION['favorite'] .= $_POST['id'] . "-";
		//	$_SESSION['favorite'] = '';
//			var_dump($_SESSION['favorite']);
//			var_dump($_POST['id']);
		}
//		$_SESSION['color'] = $_POST['colors'];
//		var_dump($_SERVER);
//		$url = $_POST['requested_url'];
//		var_dump($url);
//		redirect(site_url().'/bets/inplayBet');
//		header("location : $url");
//		var_dump($_POST['tr']);
//		foreach(explode('-',$_SESSION['favorite']) as $item){
			echo $_POST['tr'];
//		}
		
	}

	public function convertTime ( &$h, &$m ){
		if ( $m < 0 ){
			$h -= 1;
			$m = 60 + $m;
		}
		if ($h<0 ){
		    $h += 24;
        }
	}
	
	public function getDiffTime ( &$hour_dif, &$min_dif ){
		$this_hour = jdate('H');
		$this_min = jdate('i');
		
		$hour_dif =  $this_hour - gmdate('H');
		$min_dif = $this_min - gmdate('i')  ;
		$this->convertTime( $hour_dif, $min_dif );
		
	}
	
	public function getDay ( &$day ){
		$this_hour = jdate('H');
		$this_min = jdate('i');
		
		$this->getDiffTime ( $hour_dif, $min_dif);
		
		$revert_h = 0 - $hour_dif;
		$revert_m = 0 - $min_dif;
		$this->convertTime( $revert_h, $revert_m );
		
		$h_dif = ( $this_hour - $hour_dif );
		$m_dif = ( $this_min - $min_dif );
		
		$this->convertTime( $h_dif, $m_dif );
		
		$this_time = new DateTime($h_dif.":".$m_dif);
		$dif_time = new DateTime("$revert_h:$revert_m");

		if ($this_time->diff($dif_time)->format('%R') == '-') {
		  $day++;
		}
	}
	
	public function checkDate ( $match_time, $match_date, $day_time, $type ){
		$today = gmdate('d.m.Y');
		$this_time = gmdate('H:i');
		
		if ( $type == 0 ){
			// bazihaye emruz type 0
			if ( $today==$match_date && $match_time<$day_time && $this_time<$match_time )
				return true;
			else
				return false;
		}
		else if ( $type == -1){
			if ( $match_time > $day_time )
				return true;
			else
				return false;
		}
		else if ( $type == 1 && $today < $match_date ){
			return true;
		}
		
		return false;
	}
	
    public function searchMatch (&$sortedByCompetition, $matches, $search, $status, $animation){
		
		if(isset($matches->data)){
			foreach( $matches->data as $key => $item){
//					
				if((isset($status[$item->id]) && $status[$item->id] == 1) || (!isset($status[$item->id]))){

					$teams = [trim(strtolower(smarty_modifier_fa($item->homeTeam->name))),
							 trim(strtolower($item->homeTeam->name)),
							  trim(strtolower(smarty_modifier_fa($item->awayTeam->name))),
							  trim(strtolower($item->awayTeam->name))
							 ];

					foreach( $teams as $teams_value ){
						if ( strpos( $teams_value, $search) !== false ){
							$match_number++;
							$item->animation_code = (isset($animation[$item->id])) ? $animation[$item->id] : '';
							$sortedByCompetition[$item->competition->id][$key] = $item;
							break;
						}
					}

				}


			}	
		}
		
	}
	
	public function getMatches (&$sortedByCompetition, $matches, $status,$animation,$day,$teams=null){
		$date = gmdate('Y-m-d H:i:s' , time());
		$LowInbound = gmdate('H:i:s');
		
		$this->getDiffTime( $hour, $min );
		$hour = (0-$hour);
		$min = (0-$min);
		$this->convertTime($hour,$min);
		$day_time = "$hour:$min";
		
		$match_number = 0;
		if(isset($matches->data)){
		foreach ( $matches->data as $key => $item ) {
			$status = isset($status[$item->id]) ? $status[$item->id] : 1;
			$match_start_time = $item->starting_time;
			$match_start_date = $item->starting_date;
			
			$stats = $this->checkDate($match_start_time, $match_start_date, $day_time, $day);
			
			if ( $stats ){
				$match_number++;
				$item->animation_code = (isset($animation[$item->id])) ? $animation[$item->id] : '';
				
				if ( !empty($teams))
					$this->set_team_name( $teams, $item );
				$sortedByCompetition[$item->competition->id][$key] = $item;
			}
			
			}
		}
		return $match_number;
	}
	
	public function upComing ( $day = 0, $default_sport = '' ) {
		
		$soccer_type = $this->get_soccer('upComing');
       	$soccer;
	    $soccer_number = array();
		$count = 0;
		
		//////////////// bazdid //////////////////
//		$this->load->helper('jdf');
		$this->counter();
		
		 /////////////////////////////
		 $db = new DB(); 
		$slideshow = $db->multi_select('slideshow','*',['status','category'],['1','3'],'sort ASC');
		
		 
		 $slideshow_ads = $db->multi_select('slideshow','*',['status','category'],['1','4'],'sort ASC');
				
	
		$days = $day;
		 ///////////////////////////////

		$this->getDay( $day );

        if ( $day > 4 ):
            $day = 4;
        endif;
		$db = new DB();
		$today_matches = $db->select('matches','*');
		
		$status = array();
		$animation = array();
		$match_staticts = array();
		if( !empty($today_matches) ){
			foreach($today_matches as $key=>$value){
			$status[$value['id']] = $value['status'];
			$animation[$value['id']] = $value['animation_code'];
			}
		}
		
		
		
        $newMatches = array();
		
		
		foreach( $soccer_type as $soccer_value ) {
			
			$soccer_name = $soccer_value['en'];
			
			$url = API_DIR . "$soccer_name/day_$day.json";
			if( file_exists($url) )
			 	$matches = json_decode(file_get_contents($url));
			else
				$matches = null;
			
			 //////////////// read team name ///////////////
			 $team_name = API_DIR . "$soccer_name/team_name.json";
			 if ( file_exists($team_name) )
			 	$teams = json_decode(file_get_contents($team_name));
			 else 
				$teams = null;
			 
			/////////////////////////////////////////////////
			
			
			if($day != 0){
				$yesterday = $day-1;
				$url = API_DIR . "$soccer_name/day_$yesterday.json";
				if( file_exists($url))
					$yesterday_matches = json_decode(file_get_contents($url));
				else
					$yesterday_matches = null;
			}
			if ( empty($matches) && empty($yesterday_matches) ){
				$soccer_number[$soccer_name] = 0;
				$soccer[$soccer_name] = null;
			}
			
			
			$sortedByCompetition = array();
			$match_number = 0;

			$date = gmdate('Y-m-d H:i:s' , time());
			$LowInbound = gmdate('H:i:s');
			
			if($this->input->post('search') != null){

			$search = trim(strtolower($this->input->post('search')));
				
			if($day != 0 && isset($yesterday_matches->data)){
				$match_number+=$this->searchMatch($sortedByCompetition,$yesterday_matches,$search,$status, $animation);
			}
			
			$match_number+=$this->searchMatch($sortedByCompetition,$yesterday_matches,$search,$status,$animation );
				
			}
			else{
				if($day != 0){
					// bazihaye diruz ba type -1
					$match_number+=$this->getMatches($sortedByCompetition,$yesterday_matches,$status,$animation,-1,$teams);
					// bazihaye ruzhaye ayande ba type 1
					$match_number+=$this->getMatches($sortedByCompetition,$matches,$status,$animation,1,$teams);
				}
				else {
					// bazihaye emruz ba type 0
				$match_number+=$this->getMatches($sortedByCompetition,$matches,$status,$animation,0,$teams);
				}
				
			
				
			}
			ksort($sortedByCompetition , SORT_NUMERIC);
			
			$sortedByCompetition = $this->select_leage_order_sort('upcoming' ,$sortedByCompetition);
			
			$soccer_number[$soccer_name] = $match_number;
			$soccer[$soccer_name] = $sortedByCompetition;
			
		}
		$count = 0;
        $this->smart->assign([
			'soccer_type' => $soccer_type,
			'competition' => $soccer,
			'soccer_number' => $soccer_number,
            'day' => $days ,
            'sport' => $default_sport ,
            'count' => $count ,
			'slideshow' => $slideshow,
			'slideshow_ads' => $slideshow_ads,
			'totalStake' => $this->getTotalStake(),
			'league' => $this->select_leage_table(),
        ]);

        $this->smart->view('upComing');
    }
	
    /**
     * Ajax for server side response
     */
    public function getUpcomming () {
        $matchesToday = json_decode(file_get_contents(API_DIR . "home/matches.json"));
        $matches = json_decode(file_get_contents(API_DIR . "home/matches_teams.json"));
        $odds = json_decode(file_get_contents(API_DIR . "home/odds.json"));
        $competitions = json_decode(file_get_contents(API_DIR . "home/competition.json"));
        $this->smart->assign([
            'matchesToday' => $matchesToday ,
            'matches' => $matches ,
            'competitions' => $competitions ,
            'odds' => !empty($odds) ? $odds : false ,
        ]);
    }

	 public function updateOdds ( $soccer = '' ) {

        $matches = $this->getInplayOddsOnline();
		 $updateDiff = 0;

//	die(json_encode(array( 'data' => 'inplayOdds' , 'lastUpdate' => $soccer)));
//	die(json_encode(array( 'data' => 'inplayOdds' , 'lastUpdate' => $matches)));
//	die(json_encode(array( 'data' => 'inplayOdds' , 'lastUpdate' => $this->input->post('Matches'))));
	
		 
		 if($this->input->post('Matches') !== null){
			 
			 $Ids = $this->input->post('Matches');
			 $matche_ids = explode('&' , $Ids);
			 
			 if($matche_ids[0] === 'undefined'){
				 die(json_encode(array( 'data' => 'inplayOdds' , 'lastUpdate' => time() )));
//				 die(json_encode(array( 'data' => $data , 'lastUpdate' => time() )));
			 }
		 }

        $sortedById = array();
	
        if($matches != null){
//			
//			die(json_encode(array( 'data' => 'inplayOdds' , 'lastUpdate' => $matches)));
			foreach ( $matches as $items ) {
				
				if ( empty($items) ):
					continue;
				endif;
				
                foreach ($items as $key=>$item){
                    if ( $soccer != 'all' ){
                        if ( $this->input->post('Matches') == $item->id )
                            $sortedById[$item->id] = $item;
                    }
                    else {
//                    if(in_array($item->id,$matche_ids)){
                        $sortedById[$item->id] = $item;
                        unset($matches[$key]);
//                    }
                    }

                }
				
            }

			ksort($sortedById , SORT_STRING);
			//$data = array();
		 	die(json_encode(array( 'data' => $sortedById , 'lastUpdate' => time() )));
		}
		 
		 
        die(json_encode(array( 'data' => 'NoUpdate' , 'lastUpdate' => time() )));
    }
	
	public function getInplayOddsOnline ( $soccer = 'all' ,$backup_api = false ) {

        $base = $this->config->config['base_url'];
        $soccer_type = ($soccer == 'all') ? $this->get_soccer("result") : $soccer;
		$matchesToday = array();
        foreach ( $soccer_type as $soccer_value ){
			$soccer_values = $soccer_value['en'] ;
			
            $url = "$base/upload/API/$soccer_values/inplay.json";
            $json = file_get_contents($url);
            $inPlayData = json_decode($json);
            if(empty($inPlayData->data))
                $matchesToday[] = null;
            else{
                $matchesToday[] = $inPlayData->data;
            }
        }

        return $matchesToday;
    }
	
	public function getInplayOddOnline ( $match_id ) {
         
       	$base = $this->config->config['base_url']; 
		$url = "$base/upload/API/soccer/inplay.json";

		$json = file_get_contents($url);
        $inPlayData = json_decode($json);
		$thisOdds = array();
		foreach($inPlayData->data as $key=>$value){
			if($value->id == $match_id){
				$thisOdds = $value;
				return $value;
			}
				
		}
		if(empty($inPlayData))
			$matchesToday = null;
		else
			$matchesToday = $inPlayData->data;

        return $matchesToday;
    }
	
	
	
	
	 public function inplayBet () {
		$soccer_type = $this->get_soccer('inplay');
		$upcoming_soccer_type = $this->get_soccer('upcoming');
       	$soccer;
		$all_match = 0;
	    $soccer_number = array();
		 //////////////// bazdid //////////////////
//		$this->load->helper('jdf');
		$this->counter();
		 
		 /////////////////////////////
		 $db = new DB(); 
		$slideshow = $db->multi_select('slideshow','*',['status','category'],['1','3'],'sort ASC');
		
		 
		 $slideshow_ads = $db->multi_select('slideshow','*',['status','category'],['1','4'],'sort ASC');

		 
		 ///////////////////////////////
	    
		$today_matches = $db->select('matches','*');
		 
		 $status = array();
		 $animation = array();
		 if(isset($today_matches[0])){
			foreach($today_matches as $key=>$value){
				$status[$value['id']] = $value['status'];
				$animation[$value['id']] = $value['animation_code'];
			}
		 }
		 
		 foreach( $soccer_type as $soccer_value ){
			$soccer_name = $soccer_value['en'];
			 
			 ///////////////// read inplay //////////////
			 $url = API_DIR . "$soccer_name/inplay.json";
			 if ( file_exists($url) )
			 	$ArchiveMatches = json_decode(file_get_contents($url));
			 else 
				$ArchiveMatches = null;
			 
			 //////////////// read team name ///////////////
			 $team_name = API_DIR . "$soccer_name/team_name.json";
			 if ( file_exists($team_name) )
			 	$teams = json_decode(file_get_contents($team_name));
			 else 
				$teams = null;
			 
			 /////////////////////////////////////////////////
			 if ( empty($ArchiveMatches) ){
				 $soccer[$soccer_name] = null;
				 $soccer_number[$soccer_name] = 0;
			 }
				 
			 $matches = array();
			 $sortedByCompetition = array();
			 if(isset($ArchiveMatches->data)){
				$match_number = 0;
				foreach ( $ArchiveMatches->data as $key => $item ) {
					
					if((isset($status[$item->id]) && $status[$item->id] == 1) || (!isset($status[$item->id]))){
						$item->animation_code = (isset($animation[$item->id])) ? $animation[$item->id] : '';
						
						/////////////// set team name  ///////////////
						$this->set_team_name( $teams, $item );
						//////////////////////////////////////////////
						
						$sortedByCompetition[$item->competition->id][$key] = $item;
						$match_number++;
					}
					
					ksort($sortedByCompetition , SORT_NUMERIC);
					
					$sortedByCompetition = $this->select_leage_order_sort('inplay' ,$sortedByCompetition);
					
					$soccer_number[$soccer_name] = $match_number;
					$soccer[$soccer_name] = $sortedByCompetition;
				}
			}
		 }
		 
//		 $favorite = (isset($_SESSION['favorite'])) ? $_SESSION['favorite'] : ''; 
//		 if(isset($ArchiveMatches->data))
//			 $count = count($ArchiveMatches->data);
//		 else
//			 $count = 0;
		 
        $this->smart->assign([
                    'matches' => $matches,
					'soccer_type' => $soccer_type,
					'upcoming_soccer_type' => $upcoming_soccer_type,
					'competition' => $soccer,
					'soccer_number' => $soccer_number,
					'slideshow' => $slideshow,
					'slideshow_ads' => $slideshow_ads,
					'totalStake' => $this->getTotalStake(),
					'lastUpdated' => time(),
					'pageName' => 'inplayMe',
					'base_url' => $this->config->config['base_url'],
//					'favorite' => $favorite,
//					'count' => $count,
					'league' => $this->select_leage_table(),
                ]);
        $this->smart->view('inplayMe');
    }

    public function inplayBets () {
        $resultInclude = array(
            'homeTeam' , 'awayTeam'
        );
        // $resultsToday = $this->soccerama->livescores($resultInclude)->now();

        $matches = json_decode(file_get_contents(API_DIR . "inPlay/matches.json"));

        $this->smart->assign([
                    'matches' => $matches,
					'totalStake' => $this->getTotalStake(),
                ]);
        $this->smart->view('inplayMe');

    }
    /**
     * Other types of odds for a specific match
     * @param int $match_id
     */
    public function preEvents ( $match_id, $soccer='soccer' ) {

		//////////////// bazdid //////////////////
//		$this->load->helper('jdf');
		$this->counter();
		 ///////////////////////////////
		 $db = new DB();
		$today_matches = $db->select('matches','*','id',$match_id);
		
		$animation_code = 0;
		if($today_matches != 0){
			 if($today_matches[0]['status'] === '0')
				 $this->inplayBet();
			$animation_code = $today_matches[0]['animation_code']; 
		 }
		
		$odds = array();
		
		for($i=0;$i<4;$i++){
			$url = API_DIR . "$soccer/day_$i.json";
			if ( file_exists($url)){
				$matchesToday = json_decode(file_get_contents($url));
				
				if(!empty($matchesToday)){
					$match_index = $this->searchArrayForKey('id' , $match_id , $matchesToday->data);
					
				if( $match_index !== null ){
					if($match_index !== null)
						break;
					}
				}
			}
		}
		
		if ( $match_index !== null ){
			if ( !empty($matchesToday->data[$match_index]) ) {
            $odds = $matchesToday->data[$match_index]->odds->data[0]->types->data;
            $this->smart->assign([
                'odds' => $odds ,
            ]);
        	}
			
			 if(isset($matchesToday->data[$match_index])){
				 
				 $today_match = $matchesToday->data[$match_index];
				 
				 $home = $today_match->homeTeam;
				 $away = $today_match->awayTeam;
				 
				 $home_show_name = $this->set_team_name_with_id($soccer, $home->id, $home->name );
				 $away_show_name = $this->set_team_name_with_id($soccer, $away->id, $away->name );
				 
				 $today_match->homeTeam->show_name = $home_show_name;
				 $today_match->awayTeam->show_name = $away_show_name;
				 
				$this->smart->assign([
				'matches' => $today_match ,
				'totalStake' => $this->getTotalStake(),
				'pageName' => 'preEvents',
				'lastUpdated' => time(),
				'animation_code' => $animation_code,
				'soccer_type' => $soccer,
				'sport_upcoming_type' => $this->get_soccer('upComing')
				]);
				$this->smart->view('preEvents');
				}
			die();
		}
		$this->upComing();
	
        
    }

    /**
     * Other types of odds for a specific match
     * @param int $match_id
     */
    public function InplayOdds ( $match_id , $soccer = 'soccer' ) {

		//////////////// bazdid //////////////////
//		$this->load->helper('jdf');
		$this->counter();
		 ///////////////////////////////
		
		 $db = new DB();
		$today_matches = $db->select('matches','*','id',$match_id);
		 
		$animation_code = 0;
		if($today_matches != 0){
			 if($today_matches[0]['status'] === '0')
				 $this->inplayBet();
			$animation_code = $today_matches[0]['animation_code']; 
		 }
		
		
        $matchesToday = json_decode(file_get_contents(API_DIR . "$soccer/inplay.json"));
		
		if ( empty( $matchesToday ) )
			$this->inplayBet();
        $odds = array();
        $match_index = $this->searchArrayForKey('id' , $match_id , $matchesToday->data);
        if ( !empty($matchesToday->data[$match_index]) ) {

            $odds = $matchesToday->data[$match_index]->odds->data[0]->types->data;
            $this->smart->assign([
                'odds' => $odds ,
            ]);
        }
        //dd( $matchesToday );
        //echo $match_index.'---------';
        //echo '<pre>';
        //print_r($matchesToday->data);
        //echo '</pre>';
        //die;
		
        if( $match_index !== null){
			
			$today_match = $matchesToday->data[$match_index];
				 
			 $home = $today_match->homeTeam;
			 $away = $today_match->awayTeam;

			 $home_show_name = $this->set_team_name_with_id($soccer, $home->id, $home->name );
			 $away_show_name = $this->set_team_name_with_id($soccer, $away->id, $away->name );

			 $today_match->homeTeam->show_name = $home_show_name;
			 $today_match->awayTeam->show_name = $away_show_name;
			
			$this->smart->assign([
				'matches' => $today_match ,
				'pageName' => 'inplayOdds',
				'lastUpdated' => time(),
				'totalStake' => $this->getTotalStake(),
				'animation_code' => $animation_code,
				'soccer_type' => $soccer,
				'sport_type' => $this->get_soccer('inplay'),
				'sport_upcoming_type' => $this->get_soccer('upcoming')
			]);
        	$this->smart->view('preevents');	
		}else{
			$this->inplayBet();
		}
        
    }
	
	public function updateInplay(){
		$this->smart->assign([
            'pageName' => 'updateInplay',
			'lastUpdated' => time(),
        ]);
        $this->smart->view('updateInplay');
	}
	
	
	public function getContentForInsertBet( &$sortedMatches, $soccer_type, $file_name ){
		$url = API_DIR . $soccer_type . "/" . $file_name . ".json";
		
		if ( file_exists( $url ) ){
			$matches = json_decode(file_get_contents( $url ));
			$sortedMatches = array();
			if( isset($matches->data) ){
				foreach ( $matches->data as $key => $item ) {
					$sortedMatches[$item->id] = $item;
				}
			}
		}
		
	}

    /**
     * Inserting user's bet into the database
     */
	
	public function insertBet () {
 		
        if ( !isset($this->user->id) ):
            die(json_encode(array( 'result' => 'Login' )));
        endif;
        $this->checkAuth(true);
        
        $bet = array();
        $bet = $this->input->post('forms');
		$bets_key = $this->input->post('key');
//		var_dump( $bet );
        $include = array(
            'odds' ,
            'homeTeam' ,
            'awayTeam' ,
        );
        $UserCash = $this->__getUserCash();
        if ( $UserCash == 0 ):
            die(json_encode(array( 'result' => 'LowBalance' )));
        endif;

        $mix_data = explode('/' , $this->input->post('mix_data'));

        /**
         * Calculating Total Stake and check for user balance
         */
        $totalStake = 0;
		
        foreach ( $mix_data as $mix_key => $data ):
            $values = explode('-' , $data);
            if ( count($values) < 3 )
                continue;
            $stake = ( int ) $values[2];
            $bet_count = ( int ) str_replace('x' , '' , $values[1]);
            $totalStake += $stake * $bet_count;
        endforeach;
		
        //dd($totalStake);
		
		
		
	
        if ( ( int ) $totalStake < 1000 ):
            die(json_encode(array( 'result' => 'MinBet' )));
        endif;
        if ( ( int ) $totalStake > 5000000 ):
            die(json_encode(array( 'result' => 'MaxBet' )));
        endif;
        // if user balance is low than total stake
        if ( $UserCash < ( int ) $totalStake ):
            die(json_encode(array( 'result' => 'LowBalance' )));
        endif;
        // Get Each Synced Match Information from API

//sleep(10);

        $sortedMatches = array();
		$sport_type =  $bet['data'][0]['sport_type'];
		
        foreach ( $bet['data'] as $key => $row ):
            $odd_data = explode('-' , $row['runner_id']);
            $match_id = $row['match_id'];
            $bookMaker_id = $odd_data[1];
			$soccer_type = $row['sport_type'];
		
			if ( $sport_type != $soccer_type )
				die(json_encode(array( 'result' => 'Multi_sport' )));
            if ( $match_id == null ):
                die(json_encode(array( 'result' => 'Expired' )));
            endif;
		
			$insert_bet_file = ['inplay','day_0','day_1','day_2','day_3','day_4'];
			$cancel_bet_period = ['canceled', 'interupted'];
			$match = array();
			$Expire = false;
			foreach ( $insert_bet_file as $file_name ){
				
				$this->getContentForInsertBet($sortedMatches, $soccer_type, $file_name);
				ksort($sortedMatches , SORT_STRING);
				
//				var_dump($sortedMatches);
//				var_dump(key_exists($match_id, $sortedMatches) );
				
				if ( key_exists($match_id, $sortedMatches) ){
					
		
					$match = $sortedMatches[$match_id];
					
					$matchOdds[] = $sortedMatches[$match_id];
					// check mikne vaziate bazi cancel ya halate cancel nabashe
					if ( isset($match->period)){
						if (in_array(strtolower($match->period), $cancel_bet_period))
							$Expire = true;
						else
							$Expire = false;
					}
					
				}
			}
//		var_dump($matchOdds);
			if ( empty($matchOdds) )
				die(json_encode(array( 'result' => 'MatchFuckedUp' )));
			else if ( $Expire === true )
				die(json_encode(array( 'result' => 'Expired' )));
        endforeach;

        // Check for update
		//var_dump('Check for Update');
        if ( $this->checkBetForUpdate($bet['data'] , $matchOdds) ) {
            // Create bet , insert to the database
			//var_dump('Insert to DB');
            $this->createBet($bet['data'] , $matchOdds , $mix_data , $totalStake, $bets_key);
        }
        else {
			//var_dump('Odd Changing');
            die(json_encode(array( 'result' => 'OddChanged' )));
        }
    }
	
    /**
     * Check the specific match odd for updating
     */
	
	public function checkBetForUpdate ( $bet , $matchOdd ) {
        $result = true;
        foreach ( $bet as $key => $row ):
		
            $odd_data = explode('-' , $row['runner_id']);
            $match_id = $row['match_id'];
            $bookMaker_id = $odd_data[1];
            $odd_type = $odd_data[2];
            $odd_label = $odd_data[3];
            $odd_label = $row['pick'];
		
            // from soccerama
			//var_dump('Start check');
			
            if ( $bookMaker_id != 404 ) {
                $bookMaker_idx = $this->searchArrayForKey('bookmaker_id' , $bookMaker_id , $matchOdd[$key]->odds->data);
                
				$typeKey = $this->searchArrayForKey('type' , $odd_type , $matchOdd[$key]->odds->data[$bookMaker_idx]->types->data);
				
                $labelKey = $this->searchArrayForKey('label' , $odd_label , $matchOdd[$key]->odds->data[$bookMaker_idx]->types->data[$typeKey]->odds->data);

				$mainOddObjec = $matchOdd[$key]->odds->data[$bookMaker_idx]->types->data[$typeKey]->odds->data[$labelKey];

				if ( $mainOddObjec->suspend == 0 ){
					if ( $mainOddObjec->value == ( float ) number_format(( float ) $row['odd'] , 2 , '.' , '') ):
						$result = true;
						continue;
					endif;
				}
                $result = false;
				
            }
        endforeach;
        return $result;
    }
	

    /**
     * Insert into the database
     * @param type $bet
     * @param type $matchOdd
     */
	
	public function createBet ( $bet , $matchOdd , $mix_data , $totalStake, $bets_key = null ) {
        $this->load->eloquent('Bet');
        $this->load->eloquent('Bet_form');
        /**
         * check for changing odd type and label
         */
        foreach ( $bet as $key => $row ):

            $odd_data = explode('-' , $row['runner_id']);
            $bookMaker_id = $odd_data[1];
            $odd_type = $odd_data[2];
            $odd_label = $odd_data [3];
            $odd_label = $row['pick'];
			$soccer_type = $row['sport_type'];

			$bookMaker_idx = $this->searchArrayForKey('bookmaker_id' , $bookMaker_id , $matchOdd[$key]->odds->data);
			$typeKey = $this->searchArrayForKey('type' , $odd_type , $matchOdd[$key]->odds->data[$bookMaker_idx]->types->data);
			$labelKey = $this->searchArrayForKey('label' , $odd_label , $matchOdd[$key]->odds->data [$bookMaker_idx]->types->data[$typeKey]->odds->data);
			if ( $typeKey === null OR $labelKey === null ) {
				die(json_encode(array( 'result' => 'Sikiuzmi!' )));
			}

        endforeach;
		

        /**
         * mix data
         */
        foreach ( $mix_data as $mix_key => $data ):

            $values = explode('-' , $data);
            $stake = $values[2];
            if ( $stake == 0 OR $stake == '' )
                continue;
            $type = str_replace('ها' , '' , $values[0]);

            if ( $values[0] == 'تکی ها' )
                $form_count = 1;
            else
                $form_count = ( int ) str_replace(' تایی ها' , '' , $values[0]);
            $bet_count = ( int ) str_replace('x' , '' , $values[1]);
            /**
             * call appropriate function
             */
            $func_name = 'calc' . $form_count . 'Fold';
            $form_indexs_array = call_user_func_array(array( $this , $func_name ) , array( $bet ));

            /**
             * foreach Bet (Parent Table) 
             */
            foreach ( $form_indexs_array[0] as $bet_key => $row ):
                $eff_odd = ( float ) number_format(( float ) $form_indexs_array[1][$bet_key] , 2 , '.' , '');
                $Bet_data = [
                    'stake' => $stake ,
                    'user_id' => $this->user->id ,
                    'type' => $form_count ,
                    'effective_odd' => $eff_odd ,
                ];
                $Bet = Bet::create($Bet_data);
                /**
                 * For each forms related to the parent Bet Object
                 */
		
                foreach ( $row as $bet_form_row ):
                    $for_label = explode('-' , $bet[$bet_form_row - 1]['runner_id']);
					
                    $matchOddKeyForTeamName = $this->searchArrayForKey('id' , $bet[$bet_form_row - 1]['match_id'] , $matchOdd);
                    // From Soccerama
                    
					$homeTeamName = $matchOdd[$matchOddKeyForTeamName]->homeTeam->name;
					$awayTeamName = $matchOdd[$matchOddKeyForTeamName]->awayTeam->name;
					$status = $matchOdd[$matchOddKeyForTeamName]->status;
					$home_score = (int)$matchOdd[$matchOddKeyForTeamName]->home_score;
					$away_score = (int)$matchOdd[$matchOddKeyForTeamName]->away_score;
					$match_time = $matchOdd[$matchOddKeyForTeamName]->minute;
					$home_score_live = (int)$matchOdd[$matchOddKeyForTeamName]->home_score;
					$away_score_live = (int)$matchOdd[$matchOddKeyForTeamName]->away_score;
                    
                    $Bet_form = Bet_form::create(
                                    [
                                        'match_id' => $bet[$bet_form_row - 1]['match_id'] ,
                                        'odd' => ( float ) number_format(( float ) $bet[$bet_form_row - 1]['odd'] , 2 , '.' , '') ,
                                        'home_team' => addslashes($homeTeamName) ,
                                        'away_team' => addslashes($awayTeamName) ,
                                        'bet_type' => $for_label[2] ,
										'soccer_type' => $soccer_type,
                                        'bookmaker_id' => $for_label[1] ,
                                        'home_score' => $home_score ,
                                        'away_score' => $away_score ,
										'home_score_live' => $home_score_live ,
                                        'away_score_live' => $away_score_live ,
										'match_time' => $match_time,
                                        'status' => $status ,
                                        'odd_label' => $for_label[3] ,
                                        'pick' => $bet[$bet_form_row - 1]['pick'] ,
                                        'bets_id' => $Bet->id ,
                                        'bets_user_id' => $this->user->id ,
                                        'type' => $type ,
										'bet_keys' => $bets_key,
                                    ]
                    );
                endforeach;
            endforeach;
        endforeach;
//		var_dump('befor update user cash');
        $this->__updateUserCash($totalStake , $Bet->id);

        die(json_encode(array( 'result' => 'Success' , 'new_cash' => $this->price_format($this->__getUserCash()) )));
    }
	
	public function getUserID (){
		
		
		if ( !isset($this->user->id) ):
            die(json_encode(array( 'result' => 'Login' )));
        endif;
        $this->checkAuth(true);
		
		
		echo $this->user->id;
		
		
	}
	
	public function checkKeyIsExist ( ){
		
		
		$user_id = $this->input->post('user_id');
		$bet_keys = $this->input->post('keys');
		
		$db = new DB();
		
		$bets = $db->multi_select( 'bet_form', '*', ['bets_user_id', 'bet_keys', 'result_status'], [$user_id, $bet_keys, 0] );
		
		if ( $bets == 0 )
			echo '0';
		else
			echo '1';
		
		
//		echo $bets_key;
		
		
	}
	
    public function createBet1 ( $bet , $matchOdd , $mix_data , $totalStake ) {
        $this->load->eloquent('Bet');
        $this->load->eloquent('Bet_form');
        /**
         * check for changing odd type and label
         */
        foreach ( $bet as $key => $row ):

            $odd_data = explode('-' , $row['runner_id']);
            $bookMaker_id = $odd_data[1];
            $odd_type = $odd_data[2];
            $odd_label = $odd_data [3];
            $odd_label = $row['pick'];

            if ( $bookMaker_id != 404 ) {
                $bookMaker_idx = $this->searchArrayForKey('bookmaker_id' , $bookMaker_id , $matchOdd[$key]->odds->data);
                $typeKey = $this->searchArrayForKey('type' , $odd_type , $matchOdd[$key]->odds->data[$bookMaker_idx]->types->data);
                $labelKey = $this->searchArrayForKey('label' , $odd_label , $matchOdd[$key]->odds->data [$bookMaker_idx]->types->data[$typeKey]->odds->data);
                if ( $typeKey === null OR $labelKey === null ) {
                    die(json_encode(array( 'result' => 'Sikiuzmi!' )));
                }
            }
        endforeach;

        /**
         * mix data
         */
        foreach ( $mix_data as $mix_key => $data ):

            $values = explode('-' , $data);
            $stake = $values[2];
            if ( $stake == 0 OR $stake == '' )
                continue;
            $type = str_replace('ها' , '' , $values[0]);

            if ( $values[0] == 'تکی ها' )
                $form_count = 1;
            else
                $form_count = ( int ) str_replace(' تایی ها' , '' , $values[0]);
            $bet_count = ( int ) str_replace('x' , '' , $values[1]);
            /**
             * call appropriate function
             */
            $func_name = 'calc' . $form_count . 'Fold';
            $form_indexs_array = call_user_func_array(array( $this , $func_name ) , array( $bet ));

            /**
             * foreach Bet (Parent Table) 
             */
            foreach ( $form_indexs_array[0] as $bet_key => $row ):
                $eff_odd = ( float ) number_format(( float ) $form_indexs_array[1][$bet_key] , 2 , '.' , '');
                $Bet_data = [
                    'stake' => $stake ,
                    'user_id' => $this->user->id ,
                    'type' => $form_count ,
                    'effective_odd' => $eff_odd ,
                ];
                $Bet = Bet::create($Bet_data);
                /**
                 * For each forms related to the parent Bet Object
                 */
                foreach ( $row as $bet_form_row ):
                    $for_label = explode('-' , $bet[$bet_form_row - 1]['runner_id']);

                    $matchOddKeyForTeamName = $this->searchArrayForKey('id' , $bet[$bet_form_row - 1]['match_id'] , $matchOdd);
                    // From Soccerama
                    if ( $for_label[1] != 404 ) {
                        $homeTeamName = $matchOdd[$matchOddKeyForTeamName]->homeTeam->name;
                        $awayTeamName = $matchOdd[$matchOddKeyForTeamName]->awayTeam->name;
                        $status = $matchOdd[$matchOddKeyForTeamName]->status;
                        $home_score = $matchOdd[$matchOddKeyForTeamName]->home_score;
                        $away_score = $matchOdd[$matchOddKeyForTeamName]->away_score;
						$match_time = $matchOdd[$matchOddKeyForTeamName]->minute;
						$home_score_live = $matchOdd[$matchOddKeyForTeamName]->home_score;
						$away_score_live = $matchOdd[$matchOddKeyForTeamName]->away_score;
                    }// From GoalServer
                    else {
                        $homeTeamName = $matchOdd[$matchOddKeyForTeamName]->teams->home->name;
                        $awayTeamName = $matchOdd[$matchOddKeyForTeamName]->teams->away->name;
                        $status = 'LIVE';
                        $scores = explode('-' , $matchOdd[$matchOddKeyForTeamName]->result);
                        $home_score = isset($scores[0]) ? $scores[0] : 0;
                        $away_score = isset($scores[1]) ? $scores[1] : 0;
                    }
                    $Bet_form = Bet_form::create(
                                    [
                                        'match_id' => $bet[$bet_form_row - 1]['match_id'] ,
                                        'odd' => ( float ) number_format(( float ) $bet[$bet_form_row - 1]['odd'] , 2 , '.' , '') ,
                                        'home_team' => addslashes($homeTeamName) ,
                                        'away_team' => addslashes($awayTeamName) ,
                                        'bet_type' => $for_label[2] ,
                                        'bookmaker_id' => $for_label[1] ,
                                        'home_score' => $home_score ,
                                        'away_score' => $away_score ,
										'home_score_live' => $home_score_live ,
                                        'away_score_live' => $away_score_live ,
										'match_time' => $match_time,
                                        'status' => $status ,
                                        'odd_label' => $for_label[3] ,
                                        'pick' => $bet[$bet_form_row - 1]['pick'] ,
                                        'bets_id' => $Bet->id ,
                                        'bets_user_id' => $this->user->id ,
                                        'type' => $type ,
                                    ]
                    );
                endforeach;
            endforeach;
        endforeach;
//		var_dump('befor update user cash');
        $this->__updateUserCash($totalStake , $Bet->id);

        die(json_encode(array( 'result' => 'Success' , 'new_cash' => $this->price_format($this->__getUserCash()) )));
    }

    
	public function myRecords ( $page = 0 ) {
		$db = new DB;
		$bet = $db->select('bet_form','*','bets_user_id',$this->user['id']);
		$bets = $db->joinTwoTableMyBet('bet_form','bets','bets_id','bets.id','bets_user_id',$this->user['id']);
        $this->checkAuth(true);
        $this->load->eloquent('Bet');
        $this->load->eloquent('Bet_form');

        $myRecords = Bet::where('user_id' , $this->user->id)->take($this->config->item('per_page'))->skip($this->config->item('per_page') * ($page ))->orderBy('id' , 'desc')->get();

        //Pagination configs
        $config["base_url"] = site_url() . "bets/myRecords";

        $config['last_link'] = false;

        $config['first_link'] = false;
        $config["total_rows"] = $myRecords->count();
        $config["uri_segment"] = 3;
		
		$sort = array();
		if ( $bets != 0 ){
			foreach ( $bets as $key=>$value )
				$sort[$value['id']][] = $value;
			
		}
	
        // Now init the configs for pagination class
        $this->pagination->initialize($config);
        $this->smart->assign([
            'myRecords' => $myRecords ,
			'myBet' => $sort,
			'sport_type'=> $this->get_soccer("result"),
            'title' => 'پیش بینی های من' ,
            'paging' => $this->pagination->create_links() ,
			'totalStake' => $this->getTotalStake(),
        ]);
        $this->smart->view('myBets');
    }
	
	public function BetHolding ( $page = 0){
		$db = new DB;
		$this->checkAuth(true);
        $this->load->eloquent('Bet');
        $this->load->eloquent('Bet_form');

		$betHolding = Bet::where('user_id' , $this->user->id)->take($this->config->item('per_page'))->skip($this->config->item('per_page') * ($page ))->orderBy('id' , 'desc')->get();

		$betHolding = $db->joinTwoTableBet('bet_form','bets','bets_id','bets.id','bets_user_id',$this->user->id);
//		$betHolding = Bet::where('user_id' , $this->user->id)->where('status','0')->orderBy('id','desc')->get();
		
//		var_dump($bets);
		
		// return stake
		$this->smart->assign([
            'betHolding' => $betHolding ,
            'title' => 'شرط های جاری' ,
			'sport_type'=> $this->get_soccer("result"),
            'paging' => $this->pagination->create_links() ,
			'totalStake' => $this->getTotalStake(),
        ]);
        $this->smart->view('betHolding');
	}

    public function BetDetail ( $bet_id ) {

        $this->checkAuth(true);
        $this->load->eloquent('Bet');
        $this->load->eloquent('Bet_form');

        $betRecord = Bet::where('user_id' , $this->user->id)->where('id' , $bet_id)->first();
//        $betRecord = Bet::join('bet_form' , 'bets.id=bets_id')->where('id' , $bet_id);

        $this->smart->assign([
            'betRecord' => $betRecord ,
            'title' => 'جزيیات پیش بینی' ,
			'totalStake' => $this->getTotalStake(),
        ]);
        $this->smart->view('betDetail');
    }
	
	public function casino (){
		$this->checkAuth(true);
		
		
		$db = new DB();
		$casino_games = $db->multi_select('casino','*',['status'],['1'],'sort ASC' );
//		
//		$value = ['game_id'=> [1,'i'], 'user_id'=>['3800','s'], 'token'=>['test2','s']];
//		$where = ['id'=>[33,'i'], 'user_id'=> ['3805','s']];
//		$db->update( 'online_gamer', $value, $where);
//		$db->insert( 'online_gamer', $value );
//		
        $this->smart->assign([
			'casino_games' => $casino_games,
			'totalStake' => $this->getTotalStake(),
        ]);
        $this->smart->view('casino');
	}

}
