<?php
use GuzzleHttp\Client;
use MarkWilson\XmlToJson\XmlToJsonConverter;
require_once APPPATH . 'config/db.php';
require_once "calculateBet.php";

//require __DIR__.DIRECTORY_SEPARATOR."..".DIRECTORY_SEPARATOR."base.php";

class Api extends CI_Controller {

    public $CI;
    private $destination = "http://pishgu.com/demo/upload/API/";
    private $client;

	
	public $live_bet = [ 'Last Team to Score', 
						'First Team To Score', 
						'1st Goal',
						'2nd Goal',
						'3rd Goal',
						'4th Goal',
						'5th Goal',
						'6th Goal',
						 ];
	
    public function __construct () {
        parent::__construct();
        $this->CI = & get_instance();

        $this->client = new Client();
        
        $this->output->delete_cache();
        date_default_timezone_set('Asia/Tehran');

        if ( !$this->CI->input->is_cli_request() ):
//            die('Access Denied from the HTTP Requests');
        endif;
        
    }
	
	public function convertSpaceToUnderline($data){
		$data = str_replace( '/', '_', implode('_',explode(' ',$data)));
		return str_replace('-','_',$data);
	}
	
	public function Calculate () {
		
		$db = new DB();
		
		$sport = $db->multi_select('sports','*',['is_result'],['1'],'sort ASC');

		foreach ( $sport as $sport_value ){
			$url = API_DIR . $sport_value['name_en'] . "/results.json";
			
			if ( !file_exists($url) ):
				continue;	
			endif;
			
			$matches = json_decode(file_get_contents( $url ));
			$sortedByMatchID = array();
			
			if ( !isset( $matches->data ) ):
				continue;
			endif;
			
			foreach ( $matches->data as $item ) {
				$sortedByMatchID[$item->id] = $item;
			}
			
			ksort($sortedByMatchID , SORT_NUMERIC);
			
			$this->load->eloquent('Bet');
        	$this->load->eloquent('Bet_form');

			$whereID = array();
			
			$this->updateMatchStatus($whereID, $sortedByMatchID);
			
			$Bet_forms = Bet_form::whereIn('match_id' , $whereID)->get();
			
			foreach ( $Bet_forms as $form ){
			
				
				if ( $form->bet->status == 0 && $form->bet->type == 1 ){
				
					$this->calculateBet( $sortedByMatchID, $form );
				}
				else if ( $form->bet->type != 1 ){
					$this->calculateMixBet( $sortedByMatchID, $form );
				}
			
			
			
			}
			
			
			
			
		}
		
		$mix_id = $this->calculate_mix_bet();
		
		var_dump( $mix_id );
		$mix_bet = Bet_form::whereIn('bets_id' , $mix_id)->get();
		var_dump( $mix_bet );
		foreach ( $mix_bet as $form ){	
			
			$this->calculateMixBets( $sortedByMatchID, $form );
		}
		
		
	}
	
	public function updateMatchStatus ( &$whereID, $sortedByMatchID) {
		
		$this->load->eloquent('Bet');
		$this->load->eloquent('Bet_form');
		
		
		foreach ( $sortedByMatchID as $match_id => $match ){
			
			if ( $match->status == 'FT' || $match->status == 'Finished'|| $match->status == 'TO BE FIXED'){
				
				Bet_form::where('match_id' , $match_id)->update([
                    'status' => 'FT' ,
                    'home_score_ft' => $match->home_score ,
                    'away_score_ft' => $match->away_score ,
                ]);
                $whereID[] = $match_id;
			}
			elseif ( $match->status == 'AET' OR $match->status == 'FT_PEN' OR $match->status == 'ET' ) {

                $ftt = explode("-" , $match->ft_score);

                Bet_form::where('match_id' , $match_id)->update([
                    'status' => 'FT' ,
                    'home_score_ft' => $ftt[0] ,
                    'away_score_ft' => $ftt[1] ,

                ]);
                $whereID[] = $match_id;
            }
            elseif ( $match->status == 'POSTP' OR $match->status == 'CANCL' OR $match->status == 'Cancelled' OR $match->status == 'Deleted' OR $match->status == 'ABAN' ) {
                Bet_form::where('match_id' , $match_id)->update(['odd' => 1 , 'status' => $match->status] );
                $whereID[] = $match_id;
            }
			else {
				
			}
		}
		
	}
	
	public function calculateBet( $sortedByMatchID, $form ){
		$calcBet = new calculateBet();

		if ( $form->status == 'FT' || $form->status == 'Finished'|| $form->status == 'TO BE FIXED' ){
			$win = $calcBet->checkWin( $sortedByMatchID, $form );
			
		}
		else if (( $form->status == 'CNCL' || $form->status == 'Deleted' )){
			$win = 3;
		}
		else {
		    var_dump( $form->match_id );
		    var_dump( $form->soccer_type );
            
			$win = 10;
        }

		
		var_dump($win);
		
		$this->calculateResult ( $win, $form );
		
	}
	
	public function calculateMixBet ( $sortedByMatchID, $form ){
		
		$calcBet = new calculateBet();
		
		$forms = $form->bet->bet_form;
		
		var_dump( $form );
		
		if ( $form->status == 'FT' OR $form->status == 'FT_PEN' ) {
					
// determine the result of Mix bets
			$win = $calcBet->checkWin( $sortedByMatchID, $form);
			var_dump( $win );
			
			if ( $win === true || $win == 1 ) {
				$form->update(['result_status' => 1]);
			}
			else if ( $win == 3 || $win == 4 || $win == 5 ){
				$form->update(['result_status' => $win]);
			}
			else if ( $win != 10 ) {
				$form->update(['result_status' => 2]);
			}

			var_dump( 'is mix ' );
//			if ( $this->isMixMatchesFinalTime($forms ) ) {
//				
//				$result=$this->DeterminationResult($forms , $sortedByMatchID);
//
//				var_dump( 'mix bet');
//				var_dump( $result );
//				if ( $result == 1 ) {
//					// Deposite the stake of bet to the user's account
//					$this->depositStake($form->bet);
//					// status = 1 : wining and settled
//					$form->bet->update(array( 'status' => 1, 'pay_stake_status'=>1 ));
//				}
//				else if ( $result == 2 ) {
//					$form->bet->update(array( 'status' => 2 ));
//					$this->affiliate($form);
//				}
//			}
//			else {
//	// not all finaled		
////				continue;
//			}
		}
		else if ( $form->status == 'CNCL' || $form->status == 'Deleted' ){
			$form->bet->update(
				array(
					'effective_odd' => $form->bet->effective_odd / $form->odd ,
					'status' => 3
				)
			);
			$form->update(array(
				'result_status' => 3 ,
				'odd' => 1 ,
				'status' => 'FT'
			));
//			if ( $this->isMixMatchesFinalTime($forms) ) {
//				
//				$result=$this->DeterminationResult($forms , $sortedByMatchID);
//
//				if ( $result == 1 ) {
//					// Deposite the stake of bet to the user's account
//					$this->depositStake($form->bet);
//					// status = 1 : wining and settled
//					$form->bet->update(array( 'status' => 1, 'pay_stake_status'=>1 ));
//				}
//				else if ( $result == 2 ) {
//					$form->bet->update(array( 'status' => 2 ));
//					$this->affiliate($form);
//				}
//			}
		}
		else {
			var_dump('1111111');
//			continue;
		}
	}
	
	public function calculateMixBets ( $sortedByMatchID, $form ){
		
		$calcBet = new calculateBet();
		
		$forms = $form->bet->bet_form;
		
		var_dump( $form );
		
		if ( $form->status == 'FT' OR $form->status == 'FT_PEN' ) {
					
// determine the result of Mix bets
//			$win = $calcBet->checkWin( $sortedByMatchID, $form);
//			var_dump( $win );
//			
//			if ( $win === true || $win != 2 ) {
//				$form->update(['result_status' => 1]);
//			}
//			else if ( $win != 10 ) {
//				$form->update(['result_status' => 2]);
//			}
//
//			var_dump( 'is mix ' );
			if ( $this->isMixMatchesFinalTime($forms ) ) {
				
				$result=$this->DeterminationResult($forms , $sortedByMatchID);

				var_dump( 'mix bet');
				var_dump( $result );
				if ( $result == 1 ) {
					// Deposite the stake of bet to the user's account
					$this->depositStake($form->bet);
					// status = 1 : wining and settled
					$form->bet->update(array( 'status' => 1, 'pay_stake_status'=>1 ));
				}
				else if ( $result == 2 ) {
					$form->bet->update(array( 'status' => 2 ));
					$this->affiliate($form);
				}
			}
			else {
	// not all finaled		
//				continue;
			}
		}
		else if ( $form->status == 'CNCL' || $form->status == 'Deleted' ){
//			$form->bet->update(
//				array(
//					'effective_odd' => $form->bet->effective_odd / $form->odd ,
//					'status' => 3
//				)
//			);
//			$form->update(array(
//				'result_status' => 3 ,
//				'odd' => 1 ,
//				'status' => 'FT'
//			));
			if ( $this->isMixMatchesFinalTime($forms) ) {
				
				$result=$this->DeterminationResult($forms , $sortedByMatchID);

				if ( $result == 1 ) {
					// Deposite the stake of bet to the user's account
					$this->depositStake($form->bet);
					// status = 1 : wining and settled
					$form->bet->update(array( 'status' => 1, 'pay_stake_status'=>1 ));
				}
				else if ( $result == 2 ) {
					$form->bet->update(array( 'status' => 2 ));
					$this->affiliate($form);
				}
			}
		}
		else {
			var_dump('1111111');
//			continue;
		}
	}
	
	public function calculateMix ( ){
		
	}
	
	public function calculateResult ( $win, $form ){
		var_dump('calculateResult');
		var_dump($win);
		 if ( $win !== false ){
			 if ( $win === true || $win == 1 ){
				 if($this->depositStake($form->bet)){
					$this->updateStatus( $form, 1, 1, 1);
				}
			 }
			 else if ( $win == 3 ){
				 // cancel bet
				 $this->cancelBet( $form->bet );
				 $this->updateStatus( $form, 3, 3, 1);
			 	}
			else if ( $win == 4 ){
				 // nim bord
//				 $this->cancelBet( $form->bet );
//				 $this->updateStatus( $form, 4, 4, 1);
			}
			else if ( $win == 5 ){
				 // nim bakht
//				 $this->cancelBet( $form->bet );
//				 $this->updateStatus( $form, 5, 5, 1);
			}
			else if ( $win == 2 ){
				 // be moaref
				$this->affiliate($form);
			 	$this->updateStatus( $form, 2, 2, 0);
			 }
		 }
		else {
			 // be moaref
			$this->affiliate($form);
			$this->updateStatus( $form, 2, 2, 0);
		 }
	}
	
	public function updateStatus( $form, $result_status=0, $status=0, $pay_stake_status=0){
		$form->update(array(
			'result_status' => $result_status ,
		));
		$form->bet->update(array(
			'status' => $status,
			'pay_stake_status' => $pay_stake_status,
			
		));
	}
	
	public function calculate_mix_bet( ){
		
		$db = new DB();
		
		$mix = $db->get_mix_bet();
		
		if ( $mix == 0)
			return 0;
		
		$iterator = new RecursiveIteratorIterator(new RecursiveArrayIterator($mix));
		$mix_id = iterator_to_array($iterator,false);
		
		if ( $mix_id == 0 )
			return 0;
		
		return $mix_id;
		
		
	}
	
	public function UpcomingResult(){
		$matches = json_decode(file_get_contents(API_DIR . "upcoming/results.json"));
//		$matches = json_decode(file_get_contents(API_DIR . "home/resultsToday.json"));
		
		$sortedByMatchID = array();
		
		foreach ( $matches->data as $item ) {
			$sortedByMatchID[$item->id] = $item;
        }
		
		ksort($sortedByMatchID , SORT_NUMERIC);
		
		$this->load->eloquent('Bet');
        $this->load->eloquent('Bet_form');
		
		$whereID = array();
		
		foreach ( $sortedByMatchID as $match_id => $match ){
			
			if ( $match->status == 'FT' ){
				Bet_form::where('match_id' , $match_id)->update([
                    'status' => $match->status ,
                    'home_score_ft' => $match->home_score ,
                    'away_score_ft' => $match->away_score ,
                ]);
                $whereID[] = $match_id;
			}
			elseif ( $match->status == 'AET' OR $match->status == 'FT_PEN' OR $match->status == 'ET' ) {

                $ftt = explode("-" , $match->ft_score);

                Bet_form::where('match_id' , $match_id)->update([
                    'status' => 'FT' ,
                    'home_score_ft' => $ftt[0] ,
                    'away_score_ft' => $ftt[1] ,

                ]);
                $whereID[] = $match_id;
            }
            elseif ( $match->status == 'POSTP' OR $match->status == 'CANCL' OR $match->status == 'Deleted' OR $match->status == 'ABAN' ) {
                Bet_form::where('match_id' , $match_id)->update(array( 'odd' => 1 , 'status' => $match->status ));
                $whereID[] = $match_id;
            }
		}
		
		$Bet_forms = Bet_form::whereIn('match_id' , $whereID)->get();
		
		foreach ( $Bet_forms as $form ){
			
			if ( $form->bet->status != 0 ):
                continue;
            endif;
			
			if ( $form->bet->type == 1 ) {
					
				 if ( $form->status == 'FT' ){
					 
					 $win = $this->checkWin( $sortedByMatchID, $form );
					var_dump($win);
//					 if ( $win !== false ){
//						 if ( $win === true ){
//							 if($this->depositStake($form->bet)){
//								$form->update(array(
//								'result_status' => 1 ,
//								));
//								$form->bet->update(array(
//									'pay_stake_status' => 1,
//									'status' => 1
//								));
//							}
//						 }
//						 else if ( $win == 3 ){
//							 // cancel bet
//							 $this->cancelBet( $form->bet );
//							 $form->update(array(
//								'result_status' => 3 ,
//								));
//								$form->bet->update(array(
//									'pay_stake_status' => 1,
//									'status' => 3
//								));
//						 }
//					 }
//					 else {
//					  	$form->update(array(
//                            'result_status' => 2 ,
//                        ));
//                        $form->bet->update(array(
//                            'status' => 2
//                        ));
//						 // be moaref
//                        $this->affiliate($form);
//					 }
					 
					 
				 }
				else if ( $form->status == 'CNCL' || $form->status == 'Deleted' ){
					$form->update(array(
                        'result_status' => 3 ,
                    ));
                    $form->bet->update(array(
                        'effective_odd' => 1 ,
                        'status' => 3
                    ));
                    $this->depositStake($form->bet);
				}
				 
			}
			else {
				$forms = $form->bet->bet_form;
				
				if ( $form->status == 'FT' OR $form->status == 'FT_PEN' ) {

// determine the result of Mix bets
                        $win = $this->RowDeterminationResult($form , $sortedByMatchID);
                        if ( $win == true || $win == 1 ) {
                            $form->update([
                                'result_status' => 1
                            ]);
                        }
                        else {
                            $form->update([
                                'result_status' => 2
                            ]);
                        }

                        if ( $this->isMixMatchesFinalTime($forms , $sortedByMatchID) ) {
                            if ( $this->DeterminationResult($forms , $sortedByMatchID) ) {
// Deposite the stake of bet to the user's account
                                $this->depositStake($form->bet);
// status = 1 : wining and settled
                                $form->bet->update(array( 'status' => 1 ));
                            }
                            else {
                                $form->bet->update(array( 'status' => 2 ));
                                $this->affiliate($form);
                            }
                        }
                        else {
// not all finaled
                            continue;
                        }
                    }
					else if ( $form->status == 'CNCL' || $form->status == 'Deleted' ){
						$form->bet->update(
                            array(
                                'effective_odd' => $form->bet->effective_odd / $form->odd ,
								'status' => 3
                            )
						);
						$form->update(array(
							'result_status' => 3 ,
							'odd' => 1 ,
							'status' => 'FT'
						));
						if ( $this->isMixMatchesFinalTime($forms , $sortedByMatchID) ) {
							if ( $this->DeterminationResult($forms , $sortedByMatchID) ) {
	// Deposite the stake of bet to the user's account
								$this->depositStake($form->bet);
	// status = 1 : wining and settled
								$form->bet->update(array( 'status' => 1 ));
							}
							else {
								$form->bet->update(array( 'status' => 2 ));
								$this->affiliate($form);
							}
						}
					}
                    else {
                        continue;
                    }
			}
			
			
		}
		
		
	}
	
	public function checkWin ( $match, $form ){
		var_dump($form->bet_type);
		$label = 'check_'.$this->convertSpaceToUnderline(trim($form->bet_type));
//		var_dump($label);
//		$form->bet_type = ltrim( $form->bet_type, 'live_');
		if( method_exists($this, $label))
			return $this->{$label}( $match, $form );
		else
			return 10;
	}
	
	//////////////////// Live /////////////////////////
	
	
	/////////////////////////////////////
	
	
    /**
     * Check for final result of Bets 
     */
    public function checkResultUpComing ( $matches ) {

        $this->output->delete_cache();
        
        $resultInclude = array(
            'homeTeam' , 'awayTeam' , 'odds'
        );

        $sortedByMatchID = array();

        /**
         * organize and sort the data structure of matches
         */
//		var_dump($matches);
        foreach ( $matches->data as $item ) {
			if((isset($item->winning_odds_calculated) && $item->winning_odds_calculated == true && $item->status != 'NS'))
            	$sortedByMatchID[$item->id] = $item;
        }
        ksort($sortedByMatchID , SORT_NUMERIC);


        $this->load->eloquent('Bet');
        $this->load->eloquent('Bet_form');

        $whereID = array();
        foreach ( $sortedByMatchID as $match_id => $match ):
//			var_dump($match);
            if ( $match->status == 'FT' ) {
//				var_dump($match_id);
// we must know each matche's updated status for checking the bet's result specialy for mix types
                Bet_form::where('match_id' , $match_id)->update([
                    'status' => $match->status ,
                    'home_score_ft' => $match->home_score ,
                    'away_score_ft' => $match->away_score ,
                ]);
                $whereID[] = $match_id;
            }
            elseif ( $match->status == 'AET' OR $match->status == 'FT_PEN' OR $match->status == 'ET' ) {

                $ftt = explode("-" , $match->ft_score);

                Bet_form::where('match_id' , $match_id)->update([
                    'status' => 'FT' ,
                    'home_score_ft' => $ftt[0] ,
                    'away_score_ft' => $ftt[1] ,
                ]);
                $whereID[] = $match_id;
            }
            elseif ( $match->status == 'POSTP' OR $match->status == 'CANCL' OR $match->status == 'DELETED' OR $match->status == 'ABAN' ) {
                Bet_form::where('match_id' , $match_id)->update(array( 'odd' => 1 , 'status' => $match->status ));
                $whereID[] = $match_id;
            }
//		var_dump($match->status);
//		var_dump($match_id);
        endforeach;
		
		$Bet_forms = Bet_form::whereIn('match_id' , $whereID)
// ->where('bet_type' , "1x2")
                ->get();

        foreach ( $Bet_forms as $form ):
// single bet
		
        if ( $form->bet->status != 0 ):
                continue;
            endif;

            if ( $form->bet->type == 1 ) {
                if ( $form->status == 'CANCL' OR $match->status == 'POSTP' OR $form->status == 'DELETED' OR $form->status == 'INT' OR $match->status == 'ABAN' ) {

                    $form->update(array(
                        'result_status' => 1 ,
                    ));
                    $form->bet->update(array(
                        'effective_odd' => 1 ,
                        'status' => 1
                    ));
                    $this->depositStake($form->bet);
                }
                else {
                    $win = $this->RowDeterminationResult($form , $sortedByMatchID);
//					var_dump($win);

                    if ( $win ) {
//						var_dump($win);
                        
// Deposite the stake of bet to the user's account
						if($win == 1){
							if($this->cancelBet($form->bet)){
								$form->update(array(
								'result_status' => 1 ,
								));
								$form->bet->update(array(
									'pay_stake_status' => 1,
									'status' => 1
								));
							}	
						}
						else{
							if($this->depositStake($form->bet)){
								$form->update(array(
								'result_status' => 1 ,
								));
								$form->bet->update(array(
									'pay_stake_status' => 1,
									'status' => 1
								));
							}	
						}
                        
                    }
                    else {
                        $form->update(array(
                            'result_status' => 2 ,
                        ));
                        $form->bet->update(array(
                            'status' => 2
                        ));
                        $this->affiliate($form);
                    }
                }
            }
// For mix bets
            else {
                $forms = $form->bet->bet_form;

                if ( $form->status == 'CANCL' OR $match->status == 'ABAN' OR $match->status == 'POSTP' OR $form->status == 'DELETED' OR $form->status == 'INT' OR $match->status == 'DELAYED' OR $match->status == 'AWARDED' ) {

                    $form->bet->update(
                            array(
                                'effective_odd' => $form->bet->effective_odd / $form->odd ,
                            )
                    );
                    $form->update(array(
                        'result_status' => 1 ,
                        'odd' => 1 ,
                        'status' => 'FT'
                    ));
                    if ( $this->isMixMatchesFinalTime($forms , $sortedByMatchID) ) {
                        if ( $this->DeterminationResult($forms , $sortedByMatchID) ) {
// Deposite the stake of bet to the user's account
                            $this->depositStake($form->bet);
// status = 1 : wining and settled
                            $form->bet->update(array( 'status' => 1 ));
                        }
                        else {
                            $form->bet->update(array( 'status' => 2 ));
                            $this->affiliate($form);
                        }
                    }
                }
                else {
                    if ( $form->status == 'FT' OR $form->status == 'FT_PEN' ) {

// determine the result of Mix bets
                        $win = $this->RowDeterminationResult($form , $sortedByMatchID);
                        if ( $win == true || $win == 1 ) {
                            $form->update([
                                'result_status' => 1
                            ]);
                        }
                        else {
                            $form->update([
                                'result_status' => 2
                            ]);
                        }

                        if ( $this->isMixMatchesFinalTime($forms , $sortedByMatchID) ) {
                            if ( $this->DeterminationResult($forms , $sortedByMatchID) ) {
// Deposite the stake of bet to the user's account
                                $this->depositStake($form->bet);
// status = 1 : wining and settled
                                $form->bet->update(array( 'status' => 1 ));
                            }
                            else {
                                $form->bet->update(array( 'status' => 2 ));
                                $this->affiliate($form);
                            }
                        }
                        else {
// not all finaled
                            continue;
                        }
                    }
                    else {
                        continue;
                    }
                }
            }
        endforeach;
    }

    public function checkResultMatch ( $match_id , $user_id ) {

        $this->load->eloquent('Bet');
        $this->load->eloquent('Bet_form');

        $Bets = Bet_form::where('match_id' , $match_id)->get();
        foreach ( $Bets as $form ):
            if ( $form->bet->status == 1 ):
                continue;
            endif;
            $this->checkResultUpComingId($match_id , null , true);
        endforeach;
        redirect(site_url(ADMIN_PATH . '/bets/bets/view/' . $user_id));
    }

    /**
     * Check for final result of Bets 
     */
    public function checkResultUpComingId ( $id , $user_id = null , $bulk = false ) {

        $resultInclude = array(
            'localTeam' , 'visitorTeam' , 'odds'
        );
        $matches = $this->CI->soccerama->matches($resultInclude)->byId($id);
//dd($matches);
        $sortedByMatchID = array();
        /**
         * organize and sort the data structure of matches
         */
        $sortedByMatchID[$matches->id] = $matches;
        ksort($sortedByMatchID , SORT_NUMERIC);

        $this->load->eloquent('Bet');
        $this->load->eloquent('Bet_form');
        $whereID = array();
        foreach ( $sortedByMatchID as $match_id => $match ):
            if ( $match->status == 'FT' ) {
// we must know each matche's updated status for checking the bet's result specialy for mix types
                Bet_form::where('match_id' , $match_id)->update([
                    'status' => $match->status ,
                    'home_score_ft' => $match->home_score ,
                    'away_score_ft' => $match->away_score ,
                ]);
                $whereID[] = $match_id;
            }
            elseif ( $match->status == 'AET' OR $match->status == 'FT_PEN' OR $match->status == 'ET' ) {

                $ftt = explode("-" , $match->ft_score);

                Bet_form::where('match_id' , $match_id)->update([
                    'status' => 'FT' ,
                    'home_score_ft' => $ftt[0] ,
                    'away_score_ft' => $ftt[1] ,
                ]);
                $whereID[] = $match_id;
            }
            elseif ( $match->status == 'POSTP' OR $match->status == 'ABAN' OR $match->status == 'CANCL' OR $match->status == 'DELETED' ) {
//                Bet_form::where('match_id' , $match_id)->update(array( 'odd' => 1 , 'status' => $match->status ));
                $whereID[] = $match_id;
            }
        endforeach;

        $Bet_forms = Bet_form::whereIn('match_id' , $whereID)
// ->where('bet_type' , "1x2")
                ->get();
        foreach ( $Bet_forms as $form ):
// single bet
            if ( $form->bet->status == 1 ):
                continue;
            endif;
            if ( $form->bet->type == 1 ) {

                if ( $form->status == 'CANCL' OR $match->status == 'ABAN' OR $match->status == 'POSTP' OR $form->status == 'DELETED' OR $form->status == 'INT' ) {

                    $form->update(array(
                        'result_status' => 1 ,
                    ));
                    $form->bet->update(array(
                        'effective_odd' => 1 ,
                        'status' => 1
                    ));
                    $this->depositStake($form->bet);
                }
                else {
                    $win = $this->RowDeterminationResult($form , $sortedByMatchID);
                    if ( $win ) {
                        $form->update(array(
                            'result_status' => 1 ,
                        ));
                        $form->bet->update(array(
                            'status' => 1
                        ));
// Deposite the stake of bet to the user's account
                        $this->depositStake($form->bet);
                    }
                    else {
                        $form->update(array(
                            'result_status' => 2 ,
                        ));
                        $form->bet->update(array(
                            'status' => 2
                        ));
                    }
                }
            }
// For mix bets
            else {
                $forms = $form->bet->bet_form;

                if ( $form->status == 'CANCL' OR $match->status == 'POSTP' OR $form->status == 'DELETED' OR $form->status == 'INT' OR $match->status == 'DELAYED' OR $match->status == 'ABAN' OR $match->status == 'AWARDED' ) {

                    $form->bet->update(
                            array(
                                'effective_odd' => $form->bet->effective_odd / $form->odd ,
                            )
                    );

                    $form->update(array(
                        'result_status' => 1 ,
                        'odd' => 1 ,
                        'status' => 'FT'
                    ));
                }
                else {
                    if ( $form->status == 'FT' OR $form->status == 'FT_PEN' ) {


// determine the result of Mix bets
                        $win = $this->RowDeterminationResult($form , $sortedByMatchID);

                        if ( $win == true ) {
                            $form->update([
                                'result_status' => 1
                            ]);
                        }
                        else {
                            $form->update([
                                'result_status' => 2
                            ]);
                            continue;
                        }

                        if ( $this->isMixMatchesFinalTimeManual($forms , $sortedByMatchID) ) {
                            if ( $this->DeterminationResult($forms , $sortedByMatchID) ) {
// Deposite the stake of bet to the user's account
                                $this->depositStake($form->bet);
// status = 1 : wining and settled
                                $form->bet->update(array( 'status' => 1 ));
                            }
                            else {
                                $form->bet->update(array( 'status' => 2 ));
                            }
                        }
                        else {
// not all finaled
                            continue;
                        }
                    }
                    else {
                        continue;
                    }
                }
            }
        endforeach;
        if ( $user_id AND $bulk === false )
            redirect(site_url(ADMIN_PATH . '/bets/bets/view/' . $user_id));
        elseif ( $bulk )
            return true;
    }

    /**
     * Check for final result of Bets 
     */
    public function checkResultInplay () {
        $matches = $this->getInplayOddsOnline();

        $sortedByMatchID = array();

        /**
         * organize and sort the data structure of matches
         */
        foreach ( $matches as $item ) {
            if ( !property_exists($item , "start_time") OR ! property_exists($item , "state") ) {
                $item->start_time = "";
                $item->state = "";
            }
            $mkTimeMatch = mktime(date('H' , strtotime($item->start_time)) , date('i' , strtotime($item->start_time)) , date('s' , strtotime($item->start_time)) , date('m' , strtotime($item->start_time)) , date('d' , strtotime($item->start_time)) , date('Y' , strtotime($item->start_time)));

            $diff = time() - $mkTimeMatch;
            if ( ( $diff > 3400 && $diff < 70000 && $item->break_point > 0 && $item->state != 1015 && $item->break_point != 120 ) OR ( $item->state == 1017 OR $item->break_point == 90) ):
                $sortedByMatchID[$item->id] = $item;
            endif;
        }

        ksort($sortedByMatchID , SORT_NUMERIC);

        $this->load->eloquent('Bet');
        $this->load->eloquent('Bet_form');

        $whereID = array();

        foreach ( $sortedByMatchID as $match_id => $match ):
// we must know each matche's updated status for checking the bet's result specialy for mix types
            $result = explode('-' , $sortedByMatchID[$match_id]->result);
            Bet_form::where('match_id' , $match_id)->update([
                'status' => 'FT' ,
                'home_score_ft' => $match->teams->home->goals ,
                'away_score_ft' => $match->teams->away->goals ,
            ]);
            $whereID[] = $match_id;
        endforeach;
        $Bet_forms = Bet_form::whereIn('match_id' , $whereID)
                ->where('bookmaker_id' , 404)
                ->get();
        foreach ( $Bet_forms as $form ):
// single bet
            if ( $form->bet->status != 0 ):
                continue;
            endif;
            $result = [$sortedByMatchID[$form->match_id]->teams->home->goals , $sortedByMatchID[$form->match_id]->teams->away->goals ];

            if ( $form->bet->type == 1 ) {

                if ( $form->bet_type == '1x2' OR $form->bet_type == 'Fulltime Result' ) {
                    $whatsup = $this->inPlayRowDetermineResult($form , $result);
                }
                else {
// other types of bets
                    $whatsup = $this->detectWinnerOtherOdds($form , $result);
                }
                if ( $whatsup == 1 ) {

                    $form->update(array(
                        'result_status' => 1 ,
                    ));
                    $form->bet->update(array(
                        'status' => 1
                    ));
// Deposite the stake of bet to the user's account
                    $this->depositStake($form->bet);
                }
// draw no bet
                elseif ( $whatsup == 2 ) {

                    $form->update(array(
                        'result_status' => 1 ,
                    ));
                    $form->bet->update(array(
                        'status' => 1 ,
                        'effective_odd' => 1
                    ));
// Deposite the stake of bet to the user's account
                    $this->depositStake($form->bet);
                }
                else {
                    $form->update(array(
                        'result_status' => 2 ,
                    ));
                    $form->bet->update(array(
                        'status' => 2
                    ));
                    $this->affiliate($form);
                }
            }
// For mix bets
            else {

                $forms = $form->bet->bet_form;

                $win = false;
// determine the result of Mix bets
//if The Fulltime Result is the type of bet
                if ( $form->bet_type == '1x2' OR $form->bet_type == 'Fulltime Result' ) {
                    $win = $this->inPlayRowDetermineResult($form , $result);
                }// other types of bets
                else {
                    $win = $this->detectWinnerOtherOdds($form , $result);
                }
                if ( $win == 1 ) {
                    $form->update([
                        'result_status' => 1
                    ]);
                }
                elseif ( $win == 2 ) {

                    $form->update(array(
                        'result_status' => 1 ,
                    ));
                    $form->bet->update(array(
                        'effective_odd' => $form->bet->effective_odd / $form->odd ,
                    ));
                }
                else {
                    $form->update([
                        'result_status' => 2
                    ]);
                }
                if ( $this->allInplaySolved($forms) ) {
                    if ( $this->isInplayMixMatchesWon($forms) ) {
// Deposite the stake of bet to the user's account
                        $this->depositStake($form->bet);
// status = 1 : wining and settled
                        $form->bet->update(array( 'status' => 1 ));
                    }
                    else {
                        $this->affiliate($form);
                        $form->bet->update(array( 'status' => 2 ));
                    }
                }
                else {
                    continue;
                }
            }
        endforeach;
    }

    /**
     * detect Winner Other Odds
     * @param type $form
     * @param type $result
     * @return boolean
     */
    public function detectWinnerOtherOddsbyID ( $id ) {
        $this->load->eloquent('Bet');
        $this->load->eloquent('Bet_form');
        $form = Bet_form::where('match_id' , $id)->first();
        $result = array( $form->home_score_ft , $form->away_score_ft );
        $win = false;

        if ( $result[0] > $result[1] ) {
            $winner = $form->home_team;
        }
        elseif ( $result[0] < $result[1] ) {
            $winner = $form->away_team;
        }
        else
            $winner = 'Draw';
//dump($winner);
//dump($form->pick);

        if ( $form->bet_type == 'Match Goals' ) {
            $goals = ( int ) $result[0] + ( int ) $result[1];
            $OverUnder = explode(' ' , $form->pick);
            if ( ( float ) $OverUnder[1] < ( float ) $goals OR ( float ) $OverUnder[1] == ( float ) $goals )
                $matchGoalResult = 'Over ' . $OverUnder[1];
            else
                $matchGoalResult = 'Under ' . $OverUnder[1];
            if ( $matchGoalResult == $form->pick )
                $win = 1;
            else
                $win = false;
        }elseif ( $form->bet_type == 'Double Chance' ) {
            if ( strpos($form->pick , $winner) === false )
                $win = false;
            else
                $win = 1;
        }elseif ( $form->bet_type == 'Draw No Bet' ) {
            if ( $winner == $form->pick )
                $win = 1;
            elseif ( $winner == 'Draw' )
                $win = 2;
            else
                $win = false;
        }
        elseif ( $form->bet_type == 'Goals Odd/Even' ) {
            $oddOrEven = (( int ) $result[0] + ( int ) $result[1]) % 2;
            if ( $oddOrEven == 0 AND $form->pick == 'Even' )
                $win = true;
            elseif ( $oddOrEven == 1 AND $form->pick == 'Odd' )
                $win = 1;
            else
                $win = false;
        }
        elseif ( $form->bet_type == 'Final Score' ) {
            $result_picked = explode('-' , $form->pick);
            if ( $result[0] == $result_picked[0] AND $result [1] == $result_picked[1] )
                $win = 1;
            else
                $win = false;
        }
        return $win;
    }
	
    public function detectWinnerOtherOdds ( $form , $result ) {
        $win = false;

        if ( $result[0] > $result[1] ) {
            $winner = $form->home_team;
			$winners = 'Home';
			$total_label = '1';
        }
        elseif ( $result[0] < $result[1] ) {
            $winner = $form->away_team;
			$winners = 'Away';
			$total_label = '2';
        }
        else{
			$winner = 'Draw';
			$total_label = 'X';
		}
            
		
		if ( $form->bet_type == '1X2'){
			if($form->pick == $total_label)
				$win = 1;
			else
				$win = false;
		}
        else if ( $form->bet_type == 'Match Goals' ) {
//			$win = $this->checkOverUnderLabel($form->pick,$result[0],$result[1]);
//            $goals = ( int ) $result[0] + ( int ) $result[1];

//            $OverUnder = explode(' ' , $form->pick);
//			var_dump(( float ) $OverUnder[2]);
//            if ( ( float ) $OverUnder[2] < ( float ) $goals OR ( float ) $OverUnder[2] == ( float ) $goals )
//                $matchGoalResult = 'Over : ' . $OverUnder[2];
//            else
//                $matchGoalResult = 'Under : ' . $OverUnder[2];
//			var_dump($matchGoalResult);
//			var_dump($form->pick);
//            if ( $matchGoalResult == $form->pick )
//                $win = 1;
//            else
//                $win = false;
        }elseif ( $form->bet_type == 'Double Chance' ) {
            if ( strpos($form->pick , $winner) !== false || strpos($form->pick , $winners) !== false )
                $win = 1;
            else
                $win = false;
        } elseif ( $form->bet_type == 'Result / Both Teams To Score' ) {

            if ( strpos($form->pick , $winner) === false OR ( ($result[0] == 0 OR $result [1] == 0) AND strpos('& Yes' , $form->pick)) OR ( ($result[0] > 0 OR $result [1] > 0) AND strpos('& No' , $form->pick)) )
                $win = false;
            elseif ( strpos($form->pick , $winner) !== false AND ( ($result[0] == 0 OR $result [1] == 0) AND strpos('& No' , $form->pick)) )
                $win = 1;
            elseif ( strpos($form->pick , $winner) !== false AND ( ($result[0] > 0 AND $result [1] > 0) AND strpos('& Yes' , $form->pick)) )
                $win = 1;
            elseif ( strpos($form->pick , $winner) !== false AND ( ($result[0] == 0 AND $result [1] == 0) AND strpos('& No' , $form->pick)) )
                $win = 1;
        }elseif ( $form->bet_type == 'Draw No Bet' ) {
            if ( $winner == $form->pick )
                $win = 1;
            elseif ( $winner == 'Draw' )
                $win = 2;
            else
                $win = false;
        }
        elseif ( $form->bet_type == 'Goals Odd/Even' ) {
            $oddOrEven = (( int ) $result[0] + ( int ) $result[1]) % 2;
            if ( $oddOrEven == 0 AND $form->pick == 'Even' )
                $win = 1;
            elseif ( $oddOrEven == 1 AND $form->pick == 'Odd' )
                $win = 1;
            else
                $win = false;
        }
        elseif ( $form->bet_type == 'Final Score' ) {
            $result_picked = explode('-' , $form->pick);
            if ( $result[0] == $result_picked[0] AND $result [1] == $result_picked[1] )
                $win = 1;
            else
                $win = false;
        }
		var_dump($win);
        return $win;
    }

    /**
     * detect that all matches is finaled or not.
     * @param type $forms
     * @return boolean
     */
    public function allInplaySolved ( $forms ) {
        $status = false;
        foreach ( $forms as $val ):
            if ( $val->result_status != 0 )
                $status = true;
            else
                return false;
        endforeach;
        return $status;
    }

    /**
     * Check for final time status for the given matches
     * @param type $form
     * @param array $result the home team and away team goals
     * @return boolean
     */
    public function inPlayRowDetermineResult ( $form , $result ) {

        if ( ( int ) $result[0] > ( int ) $result[1] ) {
            $winnerOddLabel = 1;
            $winnerTeam = $form->home_team;
        }
        elseif ( ( int ) $result[0] < ( int ) $result[1] ) {
            $winnerOddLabel = 2;
            $winnerTeam = $form->away_team;
        }
        else {
            $winnerOddLabel = 'X';
            $winnerTeam = 'مساوی';
        }
        if ( ( $form->bet_type == '1x2' AND $form->odd_label == $winnerOddLabel) OR ( $form->bet_type == 'Fulltime Result' AND $form->pick == $winnerTeam ) ) {
            return 1;
        }
        else {
            return false;
        }
    }

    /**
     * Check for final time status for the given matches
     * @param type $forms
     * @return boolean
     */
    public function isMixMatchesFinalTime ( $forms ) {
        $status = false;
        foreach ( $forms as $val ):
		
			var_dump($val->bet->status);
			var_dump($val->result_status);
			var_dump($val->status);
            if ( ( $val->bet->status == 0 AND $val->result_status != 0 ) AND ( $val->status == 'FT' OR $val->status == 'FT_PEN' OR $val->status == 'CANCL' OR $val->status == 'Deleted' ) ) {
                $status = true;
            }
            else {

                return false;
            }
        endforeach;
        return $status;
    }

    /**
     * Check for final time status for the given matches
     * @param type $forms
     * @return boolean
     */
    public function isMixMatchesFinalTimeManual ( $forms ) {
        $status = false;
        foreach ( $forms as $val ):
            if ( ( $val->bet->status != 1 AND $val->result_status != 0 ) AND ( $val->status == 'FT' OR $val->status == 'FT_PEN' OR $val->status == 'CANCL' ) ) {
                $status = true;
            }
            else {
                return false;
            }
        endforeach;
        return $status;
    }

    /**
     * Check for final time status for the given matches
     * @param type $forms
     * @return boolean
     */
    public function isInplayMixMatchesWon ( $forms ) {
        $status = false;
        foreach ( $forms as $val ):
            if ( $val->result_status == 1 ) {
                $status = true;
            }
            elseif ( $val->result_status == 2 OR $val->result_status == 0 ) {
                return false;
            }
        endforeach;
        return $status;
    }

    /**
     * The result of all odds
     * @param type $forms
     * @param type $sortedByMatchID
     * @return boolean
     */
    public function DeterminationResult ( $forms , $sortedByMatchID ) {

		var_dump( 'DeterminationResult' );
        $win = false;
//        $this->load->eloquent('Bet_form');
        foreach ( $forms as $row ) {

            if ( $row->result_status == 1 )
                $win = 1;
            elseif ( $row->result_status == 2 ) {
                return 2;
            }
			else {
				return 0;
			}


            /* $odd = $sortedByMatchID[$row->match_id]->odds->data[0]->types->data[0]->odds->data;
              $resultOddWinnerIndex = $this->searchArrayForKey('winning' , true , $odd);
              if ( $resultOddWinnerIndex == null )
              $label = $this->winingOdd($form);
              else
              $label = $odd[$resultOddWinnerIndex]->label;
              if ( $row->odd_label == $label ) {
              $win = true;
              }
              else {
              return false;
              } */
        }
        return $win;
    }

    /**
     * The result of all odds
     * @param type $forms
     * @param type $sortedByMatchID
     * @return boolean
     */
	
	
	
	public function checkRestLabel($form,$home_ft,$away_ft){
		$home = (int) $home_ft - (int) $form->home_score_live;
		$away = (int) $away_ft - (int) $form->away_score_live;
		
		if ( $home > $away )
			$win = 'Home';
		elseif ( $home < $away)
			$win = 'Away';
		else
			$win = 'Draw';
		
		if( $form->pick == $win )
			return true;
		return false;
	}
	
    public function RowDeterminationResult ( $form , $sortedByMatchID ) {
//		var_dump($form);
		
		
//		$form->bookmaker_id = $form['attributes']['bookmaker_id'];
//		$form->match_id = $form['attributes']['match_id'];
//		$form->bet_type = $form['attributes']['bet_type'];
//		var_dump($form);
         $win = 0 ;
        $this->load->eloquent('Bet_form');
		if($form->bookmaker_id!=404){
        $odd = $sortedByMatchID[$form->match_id]->odds->data[0]->types->data[0]->odds->data;
//			var_dump($odd);
        $resultOddWinnerIndex = $this->searchArrayForKey('winning' , true , $odd);
		}
		
		/////////////////////
//		var_dump($win);
		$result = [$sortedByMatchID[$form->match_id]->home_score,$sortedByMatchID[$form->match_id]->away_score];
		if ( $form->bet_type == 'Match Goals' ) {
			$win = $this->checkOverUnderLabel($form->pick,$result[0],$result[1]);
		}
		else if ($form->bet_type == 'First Half Goals'){
			if($this->checkOverUnderLabel($form->pick,$form->home_score_live,$form->away_score_live))
				$win = false;
			else{
				$half_result = explode('-',$sortedByMatchID[$form->match_id]->ht_score);
				$win = $this->checkOverUnderLabel($form->pick,$half_result[0],$half_result[1]);
			}
		}
		else if( $form->bet_type == '1X2 Rest'){
//			var_dump($form);
			$win = $this->checkRestLabel($form,$form->home_score_ft,$form->away_score_ft);
		}
		elseif ( $form->bet_type == '1X2 Rest 1st Half'){
			$half_result = explode('-',$sortedByMatchID[$form->match_id]->ht_score);
			$win = $this->checkRestLabel($form,$half_result[0],$half_result[1]);
		}
		elseif ( $form->bet_type == '1X2' ) {
            $label = $this->winingOdd($form);
            if ( $form->odd_label == $label )
                $win = true;
            else
                $win = false;
        }
		elseif ( $form->bet_type == 'Double Chance' ) {
            if ( $form->pick == 12 AND $this->winingOdd($form) != 'X' ) {
                $win = true;
            }
            elseif ( $form->pick == '1X' AND ( $this->winingOdd($form) == 'X' OR $this->winingOdd($form) == 1) )
                $win = true;
            elseif ( $form->pick == 'X2' AND ( $this->winingOdd($form) == 'X' OR $this->winingOdd($form) == 2) )
                $win = true;
            else
                $win = false;
        }

        elseif ( $form->bet_type == 'HT/FT Double' ) {

            $h_result = explode('-' , $sortedByMatchID[$form->match_id]->ht_score);
            $f_result = explode('-' , $sortedByMatchID[$form->match_id]->ft_score);

            $pickk = explode('/' , $form->pick);
            if ( $h_result[0] > $h_result[1] )
                $h_winner = $form->home_team;
            elseif ( $h_result[0] < $h_result[1] ) {
                $h_winner = $form->away_team;
            }
            else
                $h_winner = "Draw";


            if ( $f_result[0] > $f_result[1] )
                $f_winner = $form->home_team;
            elseif ( $f_result[0] < $f_result[1] ) {
                $f_winner = $form->away_team;
            }
            else
                $f_winner = "Draw";


            if ( ($pickk[0] == $h_winner) AND ( $pickk[1] == $f_winner) )
                $win = true;
            else
                $win = false;
        }
        elseif ( $form->bet_type == 'Both Teams to Score' ) {
            if ( $form->home_score_ft > 0 AND $form->away_score_ft > 0 AND $form->pick == 'Yes' )
                $win = true;
            elseif ( ( $form->home_score_ft == 0 OR $form->away_score_ft == 0) AND $form->pick == 'No' )
                $win = true;
            else
                $win = false;
        }
        elseif ( $form->bet_type == '1x2 1st Half' ) {
            $h_result = explode('-' , $sortedByMatchID[$form->match_id]->ht_score);
            if ( $h_result[0] > $h_result[1] AND $form->home_team == $form->pick )
                $win = true;
            elseif ( $h_result[0] < $h_result[1] AND $form->away_team == $form->pick )
                $win = true;
            elseif ( ($h_result[0] == $h_result[1]) AND ( ($form->pick == 'Draw') OR ( $form->pick == 'X')) )
                $win = true;
            else
                $win = false;
        }
		else{
			$whatsup = $this->detectWinnerOtherOdds($form , $result);
			if($whatsup == 1 || $whatsup ==2)
				$win = true;
			else if ( $whatsup == false);
				$win = false;
		}
		
		if($win != 0)
			return $win;
		
		$find_odd = false;
		foreach($sortedByMatchID[$form->match_id]->odds->data[0]->types->data as $key=>$value){
			
			if($value->type == $form->bet_type){
				$find_odd = true;
				foreach($value->odds->data as $key1=>$value1){
					if($form->odd_label == $value1->label && (isset($value1->winning) && $value1->winning == true)){
						$win = true;
						break;
					}
				}
			}
			if($find_odd == true)
				break;
			
		}
		
		if($find_odd == true)
			return($wins);
		
		
		////////////////////////
		return $win;
		
		/////////////////////////////////////////
        if ( $form->bet_type == '1X2' ) {
            $label = $this->winingOdd($form);
            if ( $form->odd_label == $label )
                $win = true;
            else
                $win = false;
        }
// detect Double chance odds
        elseif ( $form->bet_type == 'Double Chance' ) {
            if ( $form->pick == 12 AND $this->winingOdd($form) != 'X' ) {
                $win = true;
            }
            elseif ( $form->pick == '1X' AND ( $this->winingOdd($form) == 'X' OR $this->winingOdd($form) == 1) )
                $win = true;
            elseif ( $form->pick == 'X2' AND ( $this->winingOdd($form) == 'X' OR $this->winingOdd($form) == 2) )
                $win = true;
            else
                $win = false;
        }

        elseif ( $form->bet_type == 'HT/FT Double' ) {

            $h_result = explode('-' , $sortedByMatchID[$form->match_id]->ht_score);
            $f_result = explode('-' , $sortedByMatchID[$form->match_id]->ft_score);

            $pickk = explode('/' , $form->pick);
            if ( $h_result[0] > $h_result[1] )
                $h_winner = $form->home_team;
            elseif ( $h_result[0] < $h_result[1] ) {
                $h_winner = $form->away_team;
            }
            else
                $h_winner = "Draw";


            if ( $f_result[0] > $f_result[1] )
                $f_winner = $form->home_team;
            elseif ( $f_result[0] < $f_result[1] ) {
                $f_winner = $form->away_team;
            }
            else
                $f_winner = "Draw";


            if ( ($pickk[0] == $h_winner) AND ( $pickk[1] == $f_winner) )
                $win = true;
            else
                $win = false;
        }
        elseif ( $form->bet_type == 'Both Teams to Score' ) {
            if ( $form->home_score_ft > 0 AND $form->away_score_ft > 0 AND $form->pick == 'Yes' )
                $win = true;
            elseif ( ( $form->home_score_ft == 0 OR $form->away_score_ft == 0) AND $form->pick == 'No' )
                $win = true;
            else
                $win = false;
        }
        elseif ( $form->bet_type == '1x2 1st Half' ) {
            $h_result = explode('-' , $sortedByMatchID[$form->match_id]->ht_score);
            if ( $h_result[0] > $h_result[1] AND $form->home_team == $form->pick )
                $win = true;
            elseif ( $h_result[0] < $h_result[1] AND $form->away_team == $form->pick )
                $win = true;
            elseif ( ($h_result[0] == $h_result[1]) AND ( ($form->pick == 'Draw') OR ( $form->pick == 'X')) )
                $win = true;
            else
                $win = false;
        }
		elseif ( $form->bet_type == 'Over/Under' ) {
			if(isset($odd[$resultOddWinnerIndex]->label)){
				if( strcmp($form->pick,$odd[$resultOddWinnerIndex]->label) == 0 )
					$win = true;
			}
		}else {
			if(isset($odd[$resultOddWinnerIndex]->label)){
				if( strcmp($form->pick,$odd[$resultOddWinnerIndex]->label) == 0 )
					$win = true;
			}
		}



        return $win;
    }

    public function winingOdd ( $form ) {
        $h_score = ( int ) $form->home_score_ft;
        $a_score = ( int ) $form->away_score_ft;
        if ( $h_score > $a_score ) {
            return 1;
        }
        elseif ( $a_score > $h_score ) {
            return 2;
        }
        elseif ( $a_score == $h_score ) {
            return 'X';
        }
    }

    public function affiliate ( $form ) {
        $this->CI->load->eloquent('users/affiliate');
//		var_dump($form);
//		$form->bets_user_id = $form->attributes->bets_user_id;
        $aff_user = Affiliate::where('invited_user_id' , $form->bets_user_id)->first();

		$db = new DB();
		if ( empty( $aff_user ) ){
			return true;
		}
		
		$aff_percent = $db->select( 'users', 'affiliate_percent', 'id', $aff_user->user_id);
		
		if ( empty( $aff_percent[0]['affiliate_percent'] ) ){
			$aff_percent = $db->select( 'settings', 'value', 'code', 'affiliate');
			$aff = $aff_percent[0]['value'];
		}
		else{
			$aff = $aff_percent[0]['affiliate_percent'];
		}
		
		$aff_percent = ( $aff / 100 );
        if ( $aff_user ) {
			
            $this->CI->load->sentinel();
            $UserModel = $this->CI->sentinel->getUserRepository();
            $user = $UserModel->find($aff_user->user_id);
            $cash = $user->cash;
			
			if ( !empty($user) ){
				$user->update(array(
					'cash' => $cash + ($form->bet->stake * $aff_percent)
				));

				$this->CI->load->eloquent('payment/Transaction');
				Transaction::create([
					'trans_id' => $user->id . $form->bets_user_id . $form->bet->id ,
					'price' => ($form->bet->stake * $aff_percent) ,
					'invoice_type' => 5 ,
					'status' => 1 ,
					'cash' => $cash + ($form->bet->stake * $aff_percent) ,
					'user_id' => $user->id ,
					'description' =>
					"واریز کارمزد باخت شرط کاربر زیر مجموعه به شناسه " . $form->bets_user_id . ' و شرط با شناسه ' . $form->bets_id ,
				]);
			}
            
        }

        return true;
    }

    /**
     * Utility function for search in array
     *  @param string $type
     * @param a rray $arra y
     * @return null || int
     */
    function searchArrayForKey ( $key , $value , &$array ) {
        foreach ( $array as $index => $val ) {
            if ( $val->$key == $value ) {
                return $index;
            }
        }
        return null;
    }

    /**
     * Deposite The Winning Stake into the user's account
     * @param type $Bet
     * @return boolean
     */
    public function depositStake ( $Bet ) {
//		var_dump($Bet);
        $this->CI->load->sentinel();
        $UserModel = $this->CI->sentinel->getUserRepository();
        $user = $UserModel->find($Bet->user_id);
        $cash = $user->cash;
        if ( $Bet->effective_odd > 100 )
            $Bet->effective_odd = 100;
        $user->update(array(
            'cash' => $cash + ($Bet->stake * $Bet->effective_odd)
        ));

        $this->CI->load->eloquent('payment/Transaction');
        Transaction::create([
            'trans_id' => $user->id . $Bet->id ,
            'price' => ($Bet->stake * $Bet->effective_odd) ,
            'invoice_type' => 2 ,
            'status' => 1 ,
            'cash' => $cash + ($Bet->stake * $Bet->effective_odd) ,
            'user_id' => $user->id ,
            'description' => 'واریز مبلغ برد شرط به شناسه ' . $Bet->id ,
        ]);

        return true;
    }
	
	
	public function cancelBet ( $Bet ) {
		 $this->CI->load->sentinel();
        $UserModel = $this->CI->sentinel->getUserRepository();
        $user = $UserModel->find($Bet->user_id);
        $cash = $user->cash;
       
        $user->update(array(
            'cash' => $cash + ($Bet->stake)
        ));

        $this->CI->load->eloquent('payment/Transaction');
        Transaction::create([
            'trans_id' => $user->id . $Bet->id ,
            'price' => ($Bet->stake) ,
            'invoice_type' => 14 ,
            'status' => 1 ,
            'cash' => $cash + ($Bet->stake * $Bet->effective_odd) ,
            'user_id' => $user->id ,
            'description' => 'واریز اصل پول به دلیل فسخ شرط به شناسه ' . $Bet->id ,
        ]);

        return true;
	}

//dellllllllllllllllllllllllll

    public function checkResultUpComingIddel ( $id , $user_id = null , $bulk = false ) {

        $resultInclude = array(
            'homeTeam' , 'awayTeam' , 'odds'
        );
        //$matches = $this->CI->soccerama->matches($resultInclude)->byId($id);
//dd($matches);
        $sortedByMatchID = array();
        /**
         * organize and sort the data structure of matches
         */
        // $sortedByMatchID[$id] = $matches;
        // ksort($sortedByMatchID , SORT_NUMERIC);

        $this->load->eloquent('Bet');
        $this->load->eloquent('Bet_form');
        $whereID = array();
        // foreach ( $sortedByMatchID as $match_id => $match ):
        //elseif ( $match->status == 'POSTP' OR $match->status == 'ABAN' OR $match->status == 'CANCL' OR $match->status == 'DELETED' ) {
//                Bet_form::where('match_id' , $match_id)->update(array( 'odd' => 1 , 'status' => $match->status ));
        $whereID[] = $id;
        //}
        //endforeach;

        $Bet_forms = Bet_form::whereIn('match_id' , $whereID)
// ->where('bet_type' , "1x2")
                ->get();
        foreach ( $Bet_forms as $form ):
// single bet
            if ( $form->bet->status == 1 ):
                continue;
            endif;
            if ( $form->bet->type == 1 ) {

                // if ( $form->status == 'CANCL' OR $match->status == 'ABAN' OR $match->status == 'POSTP' OR $form->status == 'DELETED' OR $form->status == 'INT' ) {

                $form->update(array(
                    'result_status' => 1 ,
                ));
                $form->bet->update(array(
                    'effective_odd' => 1 ,
                    'status' => 1
                ));
                $this->depositStake($form->bet);
                // }
            }
// For mix bets
            else {
                $forms = $form->bet->bet_form;

                //if ( $form->status == 'CANCL' OR $match->status == 'POSTP' OR $form->status == 'DELETED' OR $form->status == 'INT' OR $match->status == 'DELAYED' OR $match->status == 'ABAN' OR $match->status == 'AWARDED' ) {

                $form->bet->update(
                        array(
                            'effective_odd' => $form->bet->effective_odd / $form->odd ,
                        )
                );

                $form->update(array(
                    'result_status' => 1 ,
                    'odd' => 1 ,
                    'status' => 'FT'
                ));
                if ( $this->isMixMatchesFinalTimeManual($forms , $sortedByMatchID) ) {
                    if ( $this->dastibebin($forms) ) {
// Deposite the stake of bet to the user's account
                        $this->depositStake($form->bet);
// status = 1 : wining and settled
                        $form->bet->update(array( 'status' => 1 ));
                    }
                    else {
                        $form->bet->update(array( 'status' => 2 ));
                    }
                }
            }

        endforeach;
        if ( $user_id AND $bulk === false )
            redirect(site_url(ADMIN_PATH . '/bets/bets/view/' . $user_id));
        elseif ( $bulk )
            return true;
    }

    public function dastibebin ( $forms ) {
        $status = false;
        foreach ( $forms as $val ):
            if ( $val->result_status == 1 ) {
                $status = true;
            }
            else {
                return false;
            }
        endforeach;
        return $status;
    }

/// dastiiiiiiiii


    public function checkResultUpComingIddasti ( $id , $home , $away ) {

        $resultInclude = array(
            'homeTeam' , 'awayTeam' , 'odds'
        );

        $sortedByMatchID = array();


        $this->load->eloquent('Bet');
        $this->load->eloquent('Bet_form');
        $whereID = array();



        Bet_form::where('match_id' , $id)->update([
            'status' => 'FT' ,
            'home_score_ft' => $home ,
            'away_score_ft' => $away ,
        ]);
        $whereID[] = $id;



        $Bet_forms = Bet_form::whereIn('match_id' , $whereID)
// ->where('bet_type' , "1x2")
                ->get();
        foreach ( $Bet_forms as $form ):
// single bet
            if ( $form->bet->status == 1 ):
                continue;
            endif;
            if ( $form->bet->type == 1 ) {

                if ( $form->status == 'CANCL' OR $match->status == 'ABAN' OR $match->status == 'POSTP' OR $form->status == 'DELETED' OR $form->status == 'INT' ) {

                    $form->update(array(
                        'result_status' => 1 ,
                    ));
                    $form->bet->update(array(
                        'effective_odd' => 1 ,
                        'status' => 1
                    ));
                    $this->depositStake($form->bet);
                }
                else {
                    $win = $this->RowDeterminationResult($form , $sortedByMatchID);
                    if ( $win ) {
                        $form->update(array(
                            'result_status' => 1 ,
                        ));
                        $form->bet->update(array(
                            'status' => 1
                        ));
// Deposite the stake of bet to the user's account
                        $this->depositStake($form->bet);
                    }
                    else {
                        $form->update(array(
                            'result_status' => 2 ,
                        ));
                        $form->bet->update(array(
                            'status' => 2
                        ));
                    }
                }
            }
// For mix bets
            else {
                $forms = $form->bet->bet_form;

                if ( $form->status == 'CANCL' OR $match->status == 'POSTP' OR $form->status == 'DELETED' OR $form->status == 'INT' OR $match->status == 'DELAYED' OR $match->status == 'ABAN' OR $match->status == 'AWARDED' ) {

                    $form->bet->update(
                            array(
                                'effective_odd' => $form->bet->effective_odd / $form->odd ,
                            )
                    );

                    $form->update(array(
                        'result_status' => 1 ,
                        'odd' => 1 ,
                        'status' => 'FT'
                    ));
                }
                else {
                    if ( $form->status == 'FT' OR $form->status == 'FT_PEN' ) {


// determine the result of Mix bets
                        $win = $this->RowDeterminationResult($form , $sortedByMatchID);

                        if ( $win == true ) {
                            $form->update([
                                'result_status' => 1
                            ]);
                        }
                        else {
                            $form->update([
                                'result_status' => 2
                            ]);
                            continue;
                        }

                        if ( $this->isMixMatchesFinalTimeManual($forms , $sortedByMatchID) ) {
                            if ( $this->DeterminationResult($forms , $sortedByMatchID) ) {
// Deposite the stake of bet to the user's account
                                $this->depositStake($form->bet);
// status = 1 : wining and settled
                                $form->bet->update(array( 'status' => 1 ));
                            }
                            else {
                                $form->bet->update(array( 'status' => 2 ));
                            }
                        }
                        else {
// not all finaled
                            continue;
                        }
                    }
                    else {
                        continue;
                    }
                }
            }
        endforeach;
        if ( $user_id AND $bulk === false )
            redirect(site_url(ADMIN_PATH . '/bets/bets/view/' . $user_id));
    }
	public function checkResultUpComingIddasti2 ( $id , $home , $away,$hometeam ) {

        $resultInclude = array(
            'homeTeam' , 'awayTeam' , 'odds'
        );

        $sortedByMatchID = array();


        $this->load->eloquent('Bet');
        $this->load->eloquent('Bet_form');

        $whereID = array();

$hometeam = str_replace('%20',' ',$hometeam);

        Bet_form::where('match_id' , $id)->where('home_team' , $hometeam) ->update([
            'status' => 'FT' ,
            'home_score_ft' => $home ,
            'away_score_ft' => $away ,
        ]);
        $whereID[] = $id;

//$hometeam=urldecode($hometeam);



        $Bet_forms = Bet_form::whereIn('match_id' , $whereID)
 ->where('home_team' , $hometeam)
                ->get();
        foreach ( $Bet_forms as $form ):
// single bet
            if ( $form->bet->status == 1 ):
                continue;
            endif;
            if ( $form->bet->type == 1 ) {

                if (1==0 ){
                }
                else {
                    $win = $this->RowDeterminationResult($form , $sortedByMatchID);
                    if ( $win ) {
                        $form->update(array(
                            'result_status' => 1 ,
                        ));
                        $form->bet->update(array(
                            'status' => 1
                        ));
// Deposite the stake of bet to the user's account
                        $this->depositStake($form->bet);
                    }
                    else {
                        $form->update(array(
                            'result_status' => 2 ,
                        ));
                        $form->bet->update(array(
                            'status' => 2
                        ));
                    }
                }
            }
// For mix bets
            else {
                $forms = $form->bet->bet_form;

                if (1==2){}
                else {
                    if ( $form->status == 'FT' OR $form->status == 'FT_PEN' ) {


// determine the result of Mix bets
                        $win = $this->RowDeterminationResult($form , $sortedByMatchID);

                        if ( $win == true ) {
                            $form->update([
                                'result_status' => 1
                            ]);
                        }
                        else {
                            $form->update([
                                'result_status' => 2
                            ]);
                            continue;
                        }

                        if ( $this->isMixMatchesFinalTimeManual($forms , $sortedByMatchID) ) {
                            if ( $this->DeterminationResult($forms , $sortedByMatchID) ) {
// Deposite the stake of bet to the user's account
                                $this->depositStake($form->bet);
// status = 1 : wining and settled
                                $form->bet->update(array( 'status' => 1 ));
                            }
                            else {
                                $form->bet->update(array( 'status' => 2 ));
                            }
                        }
                        else {
// not all finaled
                            continue;
                        }
                    }
                    else {
                        continue;
                    }
                }
            }
        endforeach;
    
    }


    public function gettime() {
        echo date('H:i:s');
    }

}
