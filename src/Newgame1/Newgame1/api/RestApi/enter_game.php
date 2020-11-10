<?php
/**
 * Created by PhpStorm.
 * User: ADMIN
 * Date: 18/07/2018
 * Time: 03:20 PM
 */

require_once (__DIR__ . '/lib/classes/RestService.php');
require_once (__DIR__ . '/lib/connection/MysqliDb.php');
require_once (__DIR__ . '/lib/connection/cn.php');
require_once 'game.php';
require_once 'lib/Newgame/rps.php';
//require_once 'lib/Newgame/pasoor.php';

$REST = new \lib\classes\RestService();
$REST->CorsArray = array(
    "AllowOrigin"=> array("*"),
    "MaxAge"=> 10,
    "AllowCredentials"=> true,
//  "ExposeHeaders"=> array("Cache-Control", "Content-Language"),
    "AllowMethods"=> array("OPTIONS, GET, POST"),
    "AllowHeaders"=>array("Content-Type, charset, Authorization"),
    "ContentType"=> \lib\classes\RestService::getFormat()
);
$REST->Authorization = false;
$REST->method = array("POST");


$Data = $REST->Processing();
$Data = json_decode($Data, true);

$game = new Game();

$game_name = ($Data['game_id'] == 5) ? 'rps' : 'roulette';


if ( $game->check_token($Data['uid'], $Data['token']) ){

    if ( $Data['game_id'] == 6 ){
        $game_name = 'pasoor';
    }
    else if ( $Data['game_id'] == 5 ){
        $game_name = 'rps';
    }
    else if ( $Data['game_id'] == 8 ){
        $game_name = 'backgammon';
    }



    if ( $game_name == 'rps'){
		
        $amount = $Data['amount'];
		
		$game->check_status_for_enter_game ( $Data['uid'], $Data['game_id'] );

        if ($game->check_amount_bet_is_valid ($amount, $Data['token'])){
			
            if ($game->register_player_for_start_game($Data['uid'],$Data['token'], $Data['game_id'])){

				if ( isset( $Data['op_token'])){
					$op_user = $game->set_player ( $Data['token'], $Data['op_token']);
				}
				else{
					$op_user = $game->search_player( $Data['token']);	
				}
                

                if ( $op_user_data = $game->check_player( $op_user, $Data['game_id']) ){

                    $my_user = $game->get_user_cash( $Data['uid'] );
					
					$game->update_user_cash(-$amount, $Data['token']);
					$game->insert_transaction( 7 ,$Data['token'], $amount);
					
                    if ( $side = $game->check_player_side($Data['token'], $op_user['token'])){
						
						$side = ($side == 'my') ? 'my' : 'op';

						$total_hand = 3;
						
						$uid_1 = ( $side == 'my' ) ? $Data['uid'] : $op_user['user_id'];
						$uid_2 = ( $side == 'my' ) ? $op_user['user_id'] : $Data['uid'] ;
						
						$user_cash_1 = ( $side == 'my' ) ? $my_user['user_cash'] : $op_user_data['cash'];
						$user_cash_2 = ( $side == 'my' ) ? $op_user_data['cash'] : $my_user['user_cash'];
						
						$user_name_1 = ( $side == 'my' ) ? $my_user['user_name'] : $op_user_data['username'];
						
						$user_name_2 = ( $side == 'my' ) ? $op_user_data['username'] : $my_user['user_name'];
						
						$game_data = [ 	'side'		=> $side,
										'user_name' => $my_user['user_name'],
										'score'		=> 0,
										'select'	=> [],
									  	'op'		=> $op_user_data['id'],
									  	'op_token'	=> $op_user['token'],
								
									 ];
						
						$game_json = json_encode($game_data);
						$game->insert_game_data( $Data['token'], $game_json );
						
                        $REST->responseArray = array(
                            'side'      => $side,
                            "command"   => "game_status",
                            //			"command"=> "not_found",
                            //			"command"=> "not_enough_chips",
                            "amount"    => $Data['amount'],//poli ke vasat gozashtan
                           
                            
                            "total_hand"    => $total_hand,
                            "game_status"   => 3,
							
                            "turn_time"     => 2000,
                            "turn_total"    => 2200,
							
//                            "selected"      => 8,
                            

                            "uid_1"         => $uid_1 ,
                            "photo_1"       => '',
//                            "level_1"       => 4,
                            "chips_1"       => $user_cash_1,
                            "name_1"        => $user_name_1,
							"score_1"		=> 0,
							"score_2"		=> 0,
                            

                            "uid_2"         => $uid_2,
                            "photo_2"       => '',
//                            "level_2"       => 5,
                            "chips_2"       => $user_cash_2,
                            "name_2"        => $user_name_2,
                            
							
//							 "double"    => 1,
//							"double_uid_1"  => 2,
//							"double_uid_2"  => 2,
//							"double_level"  => 2,
//							"double_waiting" => 1,

                        );

                    }
                }
                else {
					
					$game->change_status(  $Data['token'], 0);
                    $REST->responseArray = array(
                        "command"=> "not_found",
                    );
                }

            }


        }
		else {
			// karber mojaz be vorud nabashad
			$REST->responseArray = array(
                        "command"=> "start_error",
                    );
		}
    }
	else if ( $game_name == 'pasoor'){
		
		if ($game->register_player_for_start_game($Data['uid'],$Data['token'], $Data['game_id'])){
			
			$op_user = $game->search_player( $Data['token']);
			
			if ( $op_user_data = $game->check_player( $op_user, $Data['game_id']) ){
				
				$my_user = $game->get_user_cash( $Data['uid'] );
				
				$pasoor = new Pasoor();
				
				if ( $side = $game->check_player_side($Data['token'], $op_user['token'])){
					
					if ( $side == 'my'){
						$my_hand = $pasoor->get_4_card( array('52') );
						$op_hand = $pasoor->get_4_card( $my_hand );
						$all_cards = array_merge( $my_hand, $op_hand );
						$floor = $pasoor->get_4_card( $all_cards );
						
						$array['game_name'] = $game_name;
						$array[$Data['uid']] = ['card' => $my_hand, 'score' => 0, 'sour' => 0 ];
						$array[$op_user['user_id']] = ['card' => $op_hand, 'score' => 0, 'sour' => 0 ];
						$array['floor'] = $floor;
						
						$data_card = json_encode( $array );
						
						$game->insert_data_card ( $Data['token'], $op_user['token'], $data_card);

					}
					else {
						$card = $game->get_data_card( $Data['token'] );
						$my_hand = $card['my_hand']->card;
						$floor = $card['floor'];
 					}
					
//					$posible = $pasoor->check_posibility ( $my_hand, $floor );					

					$REST->responseArray = array(
						'op_user' 	=> $card,
						"command"	=> "game_status",
			//			"command"=> "not_found",
			//			"command"=> "not_enough_chips",
						"amount"	=> $Data['amount'],//poli ke vasat gozashtan 
						
						"cards"		=> $my_hand,
						"floor_cards" => $floor,
						"op_cards"	=> 4,

						"double_level"=> 2,

						"double_waiting" => 1,

						"uid_1"		=> $Data['uid'],
						"photo_1"	=> '',
						"level_1"	=> 4,
						"chips_1"	=> $my_user['user_cash'],
						"name_1"	=> $my_user['user_name'],
						"double_uid_1"	=> 2,
			//			"score_2"=> 60000000,

						"uid_2"		=> $op_user_data['id'],
						"photo_2"	=> '',
						"level_2"	=> 0,
						"chips_2"	=> $op_user_data['cash'],
						"name_2"	=> $op_user_data['username'],
						"double_uid_2"	=> 2,
			//			"score_2"=> 25500000,

						"cards_left" => 1,
						
						"posibilities" => ['0' => ['card' => 12 , 'floor' => [21] ],
										   '1' => ['card' => 8],
										   '2' => ['card' => 9],
										   '3' => ['card' => 7, 'floor' => [20]]
										   ],
						"white_card" => [10,9,8,7],
						"black_card" => [10,9,8,7],


						
						"turn" 		=> 'BLACK',
						"status" 	=> 4,

					);

					if ( $Data['double'] === true )
						$REST->responseArray["double"] = 1;
				}
				
				
				
				
			}
			else {
				$REST->responseArray = array(
					"command"=> "not_found",
				);
			}		
			
		}	
		
	
	}
	
	else if ( $game_name == 'backgammon'){
	$REST->responseArray = array(
		
			"command"=> "game_status",
//			"command"=> "not_found",
//			"command"=> "not_enough_chips",
//			"places" => '1.W.2,12.W.5,17.W.3,19.W.5,24.B.2,13.B.5,8.B.3,6.B.5',
			"places" => '1.B.2,12.B.5,17.B.3,19.B.5,24.W.2,13.W.5,8.W.3,6.W.5',
			"turn" => 'BLACK',
//			"turn" => 'WHITE',
			"amount"=> 2500000,//poli ke vasat gozashtan 
		
//-----------------------AGE POR BASHE BAZI DOUBLE KHAHD SHOD 
//			"double"=> 0,//age bkhy shart double bashe  dar ghyr insort bayad  "double"=> '',
//			"double_level"=> 2,
//			"double_waiting" => 1,
//			"double_offered" => 1,
		
			"uid_1"=> 3085,
			"photo_1"=> '',
			"level_1"=> 4,
			"chips_1"=> 2000000,
			"name_1"=> 'eli',
			"double_uid_1"=> 2,
//			"score_1"=> 60000000,
			
			"uid_2"=> 1208,
			"photo_2"=> '',
			"level_2"=> 0,
			"chips_2"=> 6000000,
			"name_2"=> 'nghv',
			"double_uid_2"=> 2,
//			"score_2"=> 80000000,
		
			"dice_1" => '6',
			"dice_2" => '6',
//			"posibilities" => "23;25,2425262728",
			"posibilities" => "26;28.25",
//			"posibilities" => ['0' => ['card' => 12 , 'floor' => [21] ],
//							   '1' => ['card' => 8],
//							   '2' => ['card' => 9],
//							   '3' => ['card' => 7, 'floor' => [20]]
//							   ],
			"status" => 4,
			
		);
	}
	else {
		//	if($game_name == 'rps'){
	//-----------------------------for gam rps and pasoor
			$REST->responseArray = array(
				"command"=> "game_status",
	//			"command"=> "not_found",
	//			"command"=> "not_enough_chips",
				"amount"=> 2500000,//poli ke vasat gozashtan
				"double"=> 1,//age bkhy shart double bashe  dar ghyr insort bayad  "double"=> '',
				"double_level"=> 2,
				"total_hand"=> 3000,
				"game_status"=> 3,
				"turn_time"=> 200,
				"turn_total"=> 120,
				"selected"=> 8,
				"double_waiting" => 1,

				"uid_1"=> 3087,
				"photo_1"=> '',
				"level_1"=> 4,
				"chips_1"=> 2000000,
				"name_1"=> 'nghv',
				"double_uid_1"=> 2,

				"uid_2"=> 1207,
				"photo_2"=> '',
				"level_2"=> 5,
				"chips_2"=> 6000000,
				"name_2"=> 'mohammad',
				"double_uid_2"=> 2,


				"uid_3"=> 3085,
				"photo_3"=> '',
				"level_3"=> 4,
				"chips_3"=> 2000000,
				"name_3"=> 'eli',
				"double_uid_3"=> 2,

				"uid_0"=> 1208,
				"photo_0"=> '',
				"level_0"=> 5,
				"chips_0"=> 6000000,
				"name_0"=> 'mohaddese',
				"double_uid_4"=> 2,


				"hand"=> [1,2,13,15,14,45],
				"hokm"=> 3,

				"floor_1"=> 12,
				"floor_2"=> 14,
				"floor_3"=> 20,
				"floor_0"=> 8,
	//
				"score_0"=> 10,
				"score_2"=> 20,
				"score_1"=> 15,
				"score_3"=> 4,

			);
	//	}

	//}
	}

}






echo $REST->RseponseToC();