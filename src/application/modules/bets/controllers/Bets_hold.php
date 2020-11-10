<?php

(defined('BASEPATH')) OR exit('No direct script access allowed');

/**
 * Content_field_types Controller
 *
 * @copyright   Copyright (c) 2015
 * @license     MIT License
 */
require_once 'FoldTrait.php';

use MarkWilson\XmlToJson\XmlToJsonConverter;

class Bets extends Public_Controller {

    use FoldTrait;

    public function index () {
        $matchesToday = json_decode(file_get_contents(API_DIR . "home/matches.json"));
        $resultsToday = json_decode(file_get_contents(API_DIR . "home/resultsToday.json"));

        $date = gmdate('Y-m-d H:i:s' , time());
        $LowInbound = gmdate('H:i:s');

        $finalObject = array();
        foreach ( $matchesToday->data as $key => $item ):
            if ( $LowInbound < $item->starting_time )
                $finalObject[$item->starting_time] = $item;
        endforeach;
        ksort($finalObject , SORT_ASC);
//        print_r($this->soccerama->matches(['homeTeam','awayTeam'])->byDate('2017-02-20','2017-02-21'));
//        die;
        $this->smart->assign([
            'matches' => array_slice($finalObject , 0 , 15) ,
            'resultsToday' => $resultsToday ,
        ]);
        $this->smart->view('index');
    }

    public function upComing ( $day = 0 ) {

        if ( $day > 4 ):
            $day = 4;
        endif;
        $matches = json_decode(file_get_contents(API_DIR . "upcoming/day_$day.json"));

        $sortedByCompetition = array();

        $date = gmdate('Y-m-d H:i:s' , time());
        $LowInbound = gmdate('H:i:s');

        foreach ( $matches->data as $key => $item ) {
            //if($limit > 0){
            if ( $day == 0 && $LowInbound < $item->starting_time )
                $sortedByCompetition[$item->competition->name][$key] = $item;
            elseif ( $date > 0 )
                $sortedByCompetition[$item->competition->name][$key] = $item;
            // }
            //$limit--;
        }
        ksort($sortedByCompetition , SORT_STRING);

        $this->smart->assign([
            'CompetitionMatches' => $sortedByCompetition ,
            'day' => $day ,
            'count' => count($matches->data) ,
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

    public function updateOdds () {
        $matches = $this->getInplayOddsOnline();
        $Ids = $this->input->post('Matches');
        $lastUpdated = $this->input->post('LastUpdate');
//        if ( $lastUpdated == $matches->ts )
//            die(json_encode(array( 'data' => $data )));
        $updateDiff = time() - $lastUpdated;
        // if first server is temprory down for over 30 seconds, using the backup server api
        if ( $updateDiff > 30 )
            $matches = $this->getInplayOddsOnline(true);
        $matche_ids = explode('&' , $Ids);

        $sortedById = array();

        foreach ( $matches->sport->match as $item ) {
            $sortedById[$item->id] = $item;
        }
        ksort($sortedById , SORT_STRING);
        $data = array();
        foreach ( $matche_ids as $match_id ):
            if ( !key_exists($match_id , $sortedById) )
                continue;
            if ( $sortedById[$match_id]->teams->odds != "" AND isset($sortedById[$match_id]->teams->odds->odd->id) ) {
                $FulltimeId = $sortedById[$match_id]->teams->odds->odd->id;
            }
            else if ( $sortedById[$match_id]->teams->odds != "" AND isset($sortedById[$match_id]->teams->odds->odd[0]->id) ) {
                $FulltimeId = $sortedById[$match_id]->teams->odds->odd[0]->id;
            }
            else
                $FulltimeId = 0;
            if ( key_exists($match_id , $sortedById) and $sortedById[$match_id]->teams->odds != "" AND $FulltimeId == 1777 AND $sortedById[$match_id]->period != "Finished" AND $sortedById[$match_id]->teams->odds != "Not Started" ):
                $data[$match_id] = $sortedById[$match_id];
            endif;
        endforeach;

        die(json_encode(array( 'data' => $data , 'lastUpdate' => $matches->ts )));
    }

    public function getInplayOddsOnline ( $backup_api = false ) {
          // set feed URL
//        if ( $backup_api )
//            $url = 'http://feeds.realbet.me/feeds/api/inplay_soccer.xml';
//        else
        $url = 'http://eu-betting.com/feeds/inplay_soccer.xml';
//        
        // $url = 'http://164.40.250.2:1337/feeds/api/inplay_soccer.xml';
        // $url = 'http://eu-betting.com/feeds/inplay_soccer.xml';
        // $url = 'http://feeds.realbet.me/feeds/api/inplay_soccer.xml';
        // read feed into SimpleXML object
        libxml_use_internal_errors(true);
        $sxml = simplexml_load_file($url);
//        if ( !$sxml ) {
//            $url = 'http://feeds.realbet.me/feeds/api/inplay_soccer.xml';
//            libxml_use_internal_errors(true);
//            $sxml = simplexml_load_file($url);
//            if ( !$sxml ) {
//                $url = 'http://eu-betting.com/feeds/inplay_soccer.xml';
//                libxml_use_internal_errors(true);
//                $sxml = simplexml_load_file($url);
//            }
//        }
//        if ( $url == 'http://eu-betting.com/feeds/inplay_soccer.xml' AND ! $sxml ) {
//
//            $url = 'http://feeds.realbet.me/feeds/api/inplay_soccer.xml';
//            // read feed into SimpleXML object
//            libxml_use_internal_errors(true);
//            $sxml = simplexml_load_file($url);
//        }

        /* AJAX check  */
        if ( !$sxml AND ! empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest' ) {
//            $this->getInplayOddsOnline();
            /* special ajax here */
            die(json_encode(array( 'result' => 'Error' )));
        }
        elseif ( !$sxml ) {
            // redirect(site_url('bets/inplayBet'));
            exit('no xml');
        }

        $converter = new XmlToJsonConverter();
        $json = $converter->convert($sxml);
        $inPlayData = json_decode($json);
        if ( $inPlayData->feeds->sport->match AND intval($inPlayData->feeds->ts) > (time() - 20000) )
            $matchesToday = $inPlayData->feeds;
        else
            $matchesToday = [ ];

        return $matchesToday;
    }

    public function inplayBet () {
        // $matches = $this->getInplayOddsOnline();
        // //dd($matches);
        // $sortedByLeague = array();
        // foreach ( $matches->sport->match as $key => $match ):
        //     $sortedByLeague[$match->league][$key] = $match;
        // endforeach;
        // ksort($sortedByLeague , SORT_STRING);
        // $this->smart->assign([
        //     'sortedByLeague' => $sortedByLeague ,
        //     'lastUpdated' => time(),
        // ]);

        // $this->smart->view('inplayBet');

        $resultInclude = array(
            'homeTeam' , 'awayTeam'
        );
        // $resultsToday = $this->soccerama->livescores($resultInclude)->now();

        $matches = json_decode(file_get_contents(API_DIR . "inPlay/matches.json"));

        $this->smart->assign([
                    'matches' => $matches
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
                    'matches' => $matches
                ]);
        $this->smart->view('inplayMe');

    }
    /**
     * Other types of odds for a specific match
     * @param int $match_id
     */
    public function preEvents ( $match_id ) {

        $matchesToday = json_decode(file_get_contents(API_DIR . "home/matches.json"));
        $odds = array();
        $match_index = $this->searchArrayForKey('id' , ( int ) $match_id , $matchesToday->data);
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
        
        $this->smart->assign([
            'matches' => $matchesToday->data[$match_index] ,
        ]);
        $this->smart->view('preEvents');
    }

    /**
     * Other types of odds for a specific match
     * @param int $match_id
     */
    public function InplayOdds ( $match_id ) {

        //$matches = $this->getInplayOddsOnline();
        // $matches = json_decode(file_get_contents(API_DIR . "inPlay/matches.json"));

        // $sortedById = array();
        // foreach ( $matches->sport->match as $item ) {
        //     $sortedById[$item->id] = $item;
        // }
        // ksort($sortedById , SORT_STRING);


        // if ( key_exists($match_id , $sortedById) ) {
        //     $odds = $sortedById[$match_id]->teams->odds->odd;
        // }
        // else {
        //     show_404();
        // }


        // $this->smart->assign([
        //     'odds' => $odds ,
        // ]);
        // $this->smart->assign([
        //     'matches' => $sortedById[$match_id] ,
        //     'lastUpdated' => $matches->ts ,
        // ]);
        // $this->smart->view('InplayOdds');

        $matchesToday = json_decode(file_get_contents(API_DIR . "inPlay/matches.json"));
        $odds = array();
        $match_index = $this->searchArrayForKey('id' , ( int ) $match_id , $matchesToday->data);
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
        
        $this->smart->assign([
            'matches' => $matchesToday->data[$match_index] ,
        ]);
        $this->smart->view('preEvents');
    }

    /**
     * Inserting user's bet into the database
     */
    public function insertBet () {
 
   
                
        if ( !isset($this->user->id) ):
            die(json_encode(array( 'result' => 'Login' )));
        endif;
        $this->checkAuth(true);
        /**
         * Formation of data Posted by XHR
         * data = [
         *   'runner_id' => [match_id|int]-[bookmaker_id|int]-[odd_type|varchar]-[odd|varchar(label)],
         *   'match_id' => [match_id|int],
         *   'odd' => [odd|float],
         *   'stake' => [priceOfStake|int]
         * ]
         */
        $bet = array();
        $bet = $this->input->post('forms');
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


        $matches = json_decode(file_get_contents(API_DIR . "home/matches.json"));
        $sortedMatches = array();
        foreach ( $matches->data as $key => $item ) {
            $sortedMatches[$item->id] = $item;
        }
        ksort($sortedMatches , SORT_STRING);
        foreach ( $bet['data'] as $key => $row ):
            $odd_data = explode('-' , $row['runner_id']);
            $match_id = $row['match_id'];
            $bookMaker_id = $odd_data[1];
            if ( $match_id == null ):
                die(json_encode(array( 'result' => 'Expired' )));
            endif;
            // call to the API
            if ( key_exists($match_id , $sortedMatches) )
                $matchOdds[$key] = $sortedMatches[$match_id];
            else {
                /**
                 * search for match id in the cached file
                 */
                for ( $day = 0; $day < 5; $day++ ):
                    $matchesDay = json_decode(file_get_contents(API_DIR . "upcoming/day_$day.json"));
                    $sortedMatchesDay = array();
                    foreach ( $matchesDay->data as $key2 => $item ) {
                        $sortedMatchesDay[$item->id] = $item;
                    }
                    ksort($sortedMatchesDay , SORT_STRING);
                    if ( key_exists($match_id , $sortedMatchesDay) ) {
                        $matchOdds[$key] = $sortedMatchesDay[$match_id];
                        break;
                    }
                endfor;
                // if the match id not found, then get from api
                if ( empty($matchOdds[$key]) AND $bookMaker_id != 404 ) {
                    $matchOdds[$key] = $this->soccerama->matches($include)->byId($match_id);
                }// Inplay Matches
                elseif ( empty($matchOdds[$key]) AND $bookMaker_id == 404 ) {
                    //////////////////ezafiiiiiii saeed
                     die(json_encode(array( 'result' => 'Expired' )));
                    $matchesDay = $this->getInplayOddsOnline();
                    $updateDiff = time() - $matchesDay->ts;
                    // if first server is temprory down for over 30 seconds, using the backup server api
                    if ( $updateDiff > 30 )
                        $matchesDay = $this->getInplayOddsOnline(true);
                    $sortedMatchesDay = array();
                    foreach ( $matchesDay->sport->match as $item ) {
                        $sortedMatchesDay[$item->id] = $item;
                    }
                    ksort($sortedMatchesDay , SORT_NUMERIC);
                    $match_time = $sortedMatchesDay[$match_id]->time;
                    if ( $sortedMatchesDay[$match_id]->period == 'Finished' AND $match_time > 1 ) ///////// expire basteeee saeeeed
                        $Expire = true;
                    else
                        $Expire = false;
                    if ( /* 1 OR */!key_exists($match_id , $sortedMatchesDay) OR $sortedMatchesDay[$match_id] == null OR $Expire ):
//                        die(json_encode(array( 'result' => 'Expired2' )));
                        die(json_encode(array( 'result' => 'Expired' )));
                    endif;
                    $matchOdds[$key] = $sortedMatchesDay[$match_id];
                }
                elseif ( empty($matchOdds[$key]) )
                    die(json_encode(array( 'result' => 'MatchFuckedUp' )));
            }
        endforeach;

        // Check for update
        if ( $this->checkBetForUpdate($bet['data'] , $matchOdds) ) {
            // Create bet , insert to the database
            $this->createBet($bet['data'] , $matchOdds , $mix_data , $totalStake);
        }
        else {
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
            // from soccerama
            if ( $bookMaker_id != 404 ) {
        
                if ( $matchOdd[$key]->home_score != 0 OR $matchOdd[$key]->away_score != 0 OR $matchOdd[$key]->status != 'NS' ) {
                    die(json_encode(array( 'result' => 'Expired' )));
                }
                $bookMaker_idx = $this->searchArrayForKey('bookmaker_id' , $bookMaker_id , $matchOdd[$key]->odds->data);
                $typeKey = $this->searchArrayForKey('type' , $odd_type , $matchOdd[$key]->odds->data[$bookMaker_idx]->types->data);
                $labelKey = $this->searchArrayForKey('label' , $odd_label , $matchOdd[$key]->odds->data[$bookMaker_idx]->types->data[$typeKey]->odds->data);
                $mainOddObjec = $matchOdd[$key]->odds->data[$bookMaker_idx]->types->data[$typeKey]->odds->data[$labelKey];

                if ( $matchOdd[$key]->status == 'FT' OR $matchOdd[$key]->status == 'FT_PEN' ):
                    die(json_encode(array( 'result' => 'Expired' )));
                endif;
                if ( $mainOddObjec->value == ( float ) number_format(( float ) $row['odd'] , 2 , '.' , '') ):
                    $result = true;
                    continue;
                endif;
                $result = false;
                // from golaserver
            }else {

                $oddLabelTitle = 'name';
                if ( $odd_type == '1x2' ):
                    $odd_type = 'Fulltime Result';
                    $oddLabelTitle = 'name';
                endif;
                $typeKey = $this->searchArrayForKey('name' , $odd_type , $matchOdd[$key]->teams->odds->odd);
                if ( $typeKey === null ):
                    die(json_encode(array( 'result' => 'OddChanged' )));
                endif;
                if ( $odd_label == 1 ) {
                    $newLabel = 'Home';
                }
                elseif ( $odd_label == 'X' ) {
                    $newLabel = 'Draw';
                }
                elseif ( $odd_label == 2 ) {
                    $newLabel = 'Away';
                }
                $labelKey = $this->searchArrayForKey($oddLabelTitle , $newLabel , $matchOdd[$key]->teams->odds->odd[$typeKey]->type);
                $mainOddObjec = $matchOdd[$key]->teams->odds->odd[$typeKey]->type[$labelKey];
                if ( $mainOddObjec->suspend == 1 ):
                    die(json_encode(array( 'result' => 'Suspend' )));
                endif;
                if ( $mainOddObjec->odd == ( float ) number_format(( float ) $row['odd'] , 2 , '.' , '') ) {
                    $result = true;
                    continue;
                }
//                else
                $result = false;
//                else {
//                    $row['odd'] = $mainOddObjec->odd;
//                }
            }
        endforeach;
        return $result;
    }

    /**
     * Insert into the database
     * @param type $bet
     * @param type $matchOdd
     */
    public function createBet ( $bet , $matchOdd , $mix_data , $totalStake ) {
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
        $this->__updateUserCash($totalStake , $Bet->id);

        die(json_encode(array( 'result' => 'Success' , 'new_cash' => $this->price_format($this->__getUserCash()) )));
    }

    public function myRecords ( $page = 0 ) {
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
        // Now init the configs for pagination class
        $this->pagination->initialize($config);
        $this->smart->assign([
            'myRecords' => $myRecords ,
            'title' => 'پیش بینی های من' ,
            'paging' => $this->pagination->create_links() ,
        ]);
        $this->smart->view('myBets');
    }

    public function BetDetail ( $bet_id ) {

        $this->checkAuth(true);
        $this->load->eloquent('Bet');
        $this->load->eloquent('Bet_form');

        $betRecord = Bet::where('user_id' , $this->user->id)->where('id' , $bet_id)->first();

        $this->smart->assign([
            'betRecord' => $betRecord ,
            'title' => 'جزيیات پیش بینی' ,
        ]);
        $this->smart->view('betDetail');
    }

}
