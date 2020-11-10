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

//$re = new \lib\classes\Authentication();
//$cToken = $re->getBearerToken();
//
//$db->where("token_access", "$cToken");
//$cols= array("id");
//$db->get("user_token", null, $cols);
//
//if($db->count > 0) $REST->Authenticated = true; else $REST->Authenticated = false;

$Data = $REST->Processing();
$Data = json_decode($Data, true);

$game = new Game();

$game_name = 'all';





$game_name = ($Data['game_id'] == 5) ? 'rps' : 'roulette';
if ( $Data['game_id'] == 7 ){
	$game_name = 'crash';
}
else if ( $Data['game_id'] == 6 ){
	$game_name = 'pasoor';
}
else if ( $Data['game_id'] == 5 ){
    $game_name = 'rps';
}

else if ( $Data['game_id'] == 8 ){
    $game_name = 'backgammon';
}


	if($game_name == 'rps'){

		if ( $online_gamer_row = $game->get_my_and_op_row_with_my_token( $Data['token'] )){
			
			$my = $online_gamer_row['my'];
			$op = $online_gamer_row['op'];
			
			$game_status_continue = $game->check_game_status( $my, $op);
			
//			var_dump( $game_status_continue);
			if ( $game_status_continue === true ){
			
			$my_data = json_decode($my['card_data']);
			
			$my_select = $Data['selected'];
			
			$rps = new RPS();
			
			
			$rps->register_my_select_in_game_data($my['user_id'], $my_data, $my_select);
			
			$game->insert_game_data( $my['token'], json_encode($my_data));
			
			$waiting_staus = 3;
			$game->change_status( $my['token'], $waiting_staus );
			
			if ( $op_data =$game->check_status_waiting_op_play ( $op['token'], $waiting_staus )){
				
				$game_status = 2;
				$game->change_status( $op['token'], $game_status );
				
				$hand = $rps->check_select ( $my_data, $op_data );
				
				$game->insert_game_data( $my['token'], json_encode($my_data));
				
				$finish_game = $rps->check_continue_game($my_data, $op_data);
				
				if ( $finish_game === true ){
					
						$REST->responseArray = array(
				//			"command"=> "played",
				//			"command"       => "game_turn",
							'test'			=>  $my_data,
							'test1'			=>  $op_data,

							"command"       =>  "select",
							"selected"      =>  $Data['selected'],
							"score_1"       =>  $hand['score_1'],
							"score_2"       =>  $hand['score_2'],
							"winner"        =>  $hand['winner'],
							"selected_1"    =>  $hand['selected_1'],
							"selected_2"    =>  $hand['selected_2'],

							'game_status' 	  => 3,
//							'turn_time'     => 2000,
//							'turn_total'     => 4200,

				//

						);
				}
				else {
					
					$game_price = 10;
					
					$my = $game->get_id_with_token( $Data['token'] );
					
					$amount = $my['amount'] - ($my['amount'] * ($game_price/100));
					
					if ( $my['user_id'] == $finish_game ){
						$game->update_user_cash_with_token( $amount, $Data['token']);
						$game->insert_transaction( 8, $my['token'], $amount);
					}
					
					$game->change_status( $Data['token'], 0 );
//					$game->finished_game( $Data['token'], 'finish' );
					
					$REST->responseArray = array(
						"command"       =>  "win",
						"uid_1"			=>  ($side == 'my') ? $op_data->op : $my_data->op ,
						"uid_2"			=>  ($side == 'my') ? $my_data->op : $op_data->op   ,
						"winner_uid"    =>  $finish_game,
						"amount"        =>  $amount,
						"chips"			=>10000,

			//

					);
				}
				
				
			}
			else {
				$game_price = 10;
					
				$my = $game->get_id_with_token( $Data['token'] );

				$amount = $my['amount'] - ($my['amount'] * ($game_price/100));

				
				$game->update_user_cash_with_token( $amount, $Data['token']);
				$game->insert_transaction( 8, $my['token'], $amount);

				$game->change_status( $Data['token'], 0 );
				$game->change_status( $my_data->op_token, 10 );
//					$game->finished_game( $Data['token'], 'finish' );

				$REST->responseArray = array(
					"command"       =>  "win",
					"uid_1"			=>  ($side == 'my') ? $op_data->op : $my_data->op ,
					"uid_2"			=>  ($side == 'my') ? $my_data->op : $op_data->op   ,
					"winner_uid"    =>  $Data['uid'],
					"amount"        =>  $amount,
					"chips"			=>10000,

		//

				);
			}
//			$game->rps_waiting_for_get_op_response ( $game_data, $op['token'] );
			
				
			}
			else if ( $game_status_continue == 'lose' ){
				// bazande
				$game_price = 10;
				
				$amount = $my['amount'] - ($my['amount'] * ($game_price/100));
				
				$game->change_status( $Data['token'], 0 );
				
				$REST->responseArray = array(
					"command"       =>  "win",
					"uid_1"			=>  $my['user_id'] ,
					"uid_2"			=>  $op['user_id'] ,
					"winner_uid"    =>  $op['user_id'] ,
					"amount"        =>  $amount,
					"chips"			=>10000,

		//

				);
			}
			else if ( $game_status_continue == 'win' ){
				// bazande
				$game_price = 10;
					
				$my = $game->get_id_with_token( $Data['token'] );

				$amount = $my['amount'] - ($my['amount'] * ($game_price/100));

				
				$game->update_user_cash_with_token( $amount, $Data['token']);
				$game->insert_transaction( 51, $my['token'], $amount);

				$game->change_status( $Data['token'], 0 );
				
				$REST->responseArray = array(
					"command"       =>  "win",
					"uid_1"			=>  $my['user_id'] ,
					"uid_2"			=>  $op['user_id'],
					"winner_uid"    =>  $my['user_id'],
					"amount"        =>  $amount,
					"chips"			=>10000,

		//

				);
			}
			
			
			
			
		}
//	-------------------for game rps
		
	}elseif($game_name == 'crash'){
//	-------------------for game crash
		$REST->responseArray = array(
			"command"=> "play",
//			"command"=> "update",
//			"command"=> "game_status",
			"cashin"=> 1800,
			"cashout"=> 200,
			"amount"=> 125000,
			"uid"=> 3805,
			
			
			"name"=> "nghv",
			"status"=>"started",
			"chats"=>['lkjkl'],
			"crashes"=>['1'],
			"time"=>100,
//			"selected"=> 2,
//			
			
		);
	}elseif($game_name == 'backgammon'){
//	-------------------for game crash
		$REST->responseArray = array(
			"command"=> "play",
//			"command"=> "update",
//			"command"=> "game_status",
			"cashin"=> 1800,
			"cashout"=> 200,
			"amount"=> 125000,
			"uid"=> 3805,
			"posibilities" => "20;2623212429,24",
			
			"name"=> "nghv",
			"status"=>"started",
			"chats"=>['lkjkl'],
			"crashes"=>['1'],
			"time"=>100,
//			"selected"=> 2,
//			
			
		);
	}
	elseif ( $game_name == 'pasoor'){
		
		$REST->responseArray = array(
			"command"	=> "game_deal",
			"cards"		=> [51,50,49,48],
			"floor"		=> [1,2,3,4],
			"turn"		=> "WHITE",
			
			"posibilities" => ['0' => ['card' => 12 , 'floor' => [9] ],
							   '1' => ['card' => 8],
							   '2' => ['card' => 9, 'floor' => [14]],
							   '3' => ['card' => 7, 'floor' => [8]]
							   ],
			
			
		);
		
		
//		$REST->responseArray = array(
//			"command"=> "played",
//			"player"	=> "BLACK",
//			"card" 		=> $Data['card'],
//			'turn'		=> "WHITE",
//			'score_1'	=> 2,
//			'score_2'	=> 2,
//			'club_1'	=> 2,
//			'club_2'	=> 2,
//			'soor_1'	=> 2,
//			'soor_2'	=> 2,
////			"command"=> "update",
////			"command"=> "game_status",
//			"cashin"=> 1800,
//			"cashout"=> 200,
//			"amount"=> 125000,
//			"uid"=> 3805,
//			
//			
//			"name"=> "nghv",
//			"status"=>"started",
//			"chats"=>['lkjkl'],
//			"crashes"=>['1'],
//			"time"=>100,
////			"selected_card"=> $Data['card'],
//			"cards" => [12,9,8,7],
//			"posibilities" => ['0' => ['card' => 12 , 'floor' => [21] ],
//							   '1' => ['card' => 8],
//							   '2' => ['card' => 9, 'floor' => [21,20,8,5,30]],
//							   '3' => ['card' => 7, 'floor' => [20]]
//							   ],
//			"white_card" => [10,9,8,7],
//			"black_card" => [10,9,8,7],
//		
//			"op_cards" => [1,2,1,1],
//			"floor_cards" => [5,21,20,30],
//			"turn" => 'BLACK',
//			"status" => 4,
//			
//			
//		);
		
		if ( isset( $Data['floor']) ){
			$REST->responseArray['floor'] = $Data['floor'];
		}
	}
	
//}



echo $REST->RseponseToC();