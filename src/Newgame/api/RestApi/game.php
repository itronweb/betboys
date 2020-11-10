<?php 
define('ROOT_PATH',$_SERVER['DOCUMENT_ROOT']);

require_once ROOT_PATH . '/application/config/db.php';
require_once ROOT_PATH . '/adminbet/includes/SezarFunctions/Database.php';
require_once ROOT_PATH . '/adminbet/includes/SezarFunctions/Instagram.php';

session_start();

class Game {
	
	public $hashid;
	
	public function __construct(){
//		get_user_data_with_hash_id
//		$this->hashid = new Hashids('Ghorayshi');
		
	}
	
	public function check_code ( $data ){
		if ( !isset($data['code']) )
			return false;
		
		if ( empty($data['code']) )
			return false;
		
	}
	
	public function check_login(){
		
		if ( !isset( $_COOKIE['code']) )
			header('location: ../../../bets/games');
		
		$db = new DB();
		$game_token = $db->multi_select( 'users', 'id', ['game_token','status'], [$_COOKIE['code'] , '1']);
		
		if ( $game_token == 0 )
			header('location: ../../../users/logout');
	}
	
	public function check_this_bet_is_valid( $bets, $user_id){
		$user = $this->get_user_cash( $user_id );
		
		if ( $bets < $user['user_cash'])
			return true;
		
		return false;
	}
	
	public function check_this_bet_is_valid_with_token( $bets, $token){
		
		$user_row = $this->get_id_with_token( $token );
		
		$user_id = $user_row['user_id'];
		
		$user = $this->get_user_cash( $user_id );
		
		if ( $bets < $user['user_cash'])
			return true;
		
		return false;
	}
	
	
	
	public function decode_hash_id ( $id ){
		
		$db = new DB();
		
		$user_id = $db->select( 'users', 'id', 'game_token', $id);
		
		if ( $user_id == 0 )
			return false;
		
		return $user_id[0]['id'];
		
	}
	
	public function set_cookie ( $name, $value, $time = 0 ){
		if ( $time == 0 )
			$time = time() + 3600 ;
		setcookie( $name , $value, $time);
	}
	
	public function get_user_data_with_hash_id ( $code , $game_id ){
		
		$db = new DB();
		
		$user = $db->multi_select('users', '*', ['game_token','status'], [$code, '1']);
		
		$token = $db->multi_select('online_gamer', 'token', ['game_id','user_id'], [$game_id,$user[0]['id']]);
		
		return ['user_id'=>$user[0]['id'],
				'user_name'=>$user[0]['username'],
				'user_cash'=>$user[0]['cash'],  
				'token' => $token[0]['token'],
			   ];
		
	}
	
	public function get_percent_play ( $game_id ){
		
		$db = new DB();
		
		$casino = $db->multi_select('casino', 'percent_play', ['id','status'], [$game_id, '1']);
		
		if ( $casino != 0 )
			return $casino[0]['percent_play'];
		
		return 0;
		
	}
	
	public function get_user_invited ( $user_id ){
		
		$db = new DB();
		
		$affiliate = $db->multi_select('affiliate', '*', ['invited_user_id'], [$user_id]);
		
		if ( $affiliate != 0 )
			return $affiliate[0]['user_id'];
		
		return 0;
	}
	
	public function update_affiliate_user_cash ( $user_id, $amount, $game_id ){
		
		$percent = $this->get_percent_play( $game_id );
		
		if ( $percent == 0 )
			return 0;
		
		$parrent_user_id = $this->get_user_invited( $user_id );
		
		if ( $parrent_user_id == 0 )
			return 0;
		
		
		$affiliate_amount = ( $amount * $percent ) / 100;
		
		$this->update_user_cash( $affiliate_amount, $parrent_user_id);
		$this->insert_transaction( 9 , null, $affiliate_amount, $parrent_user_id, $game_id, $user_id);
		
		
		
	}
	public function get_user_cash ( $id ){
		$db = new DB();
		$user = $db->multi_select('users', 'username, cash, instagram', ['id','status'], [$id, '1']);
		
		if ( $user == 0 )
			return false;
		
		return ['user_name'=> $user[0]['username'], 'user_cash' => $user[0]['cash'], 'user_insta' => $user[0]['instagram']];
	}
	public function get_user_insta ( $userAvatar ){
		$db = new DB();
		$insta = $db->multi_select('ig_accounts', 'avatar', ['username'], [$userAvatar]);
		
		if ( $insta == 0 )
			return false;
		
		return ['Sezar_Avatar'=> $insta[0]['avatar']];
	}
	
	public function get_user_cash_with_token ( $token ){
		
		$user_row = $this->get_id_with_token( $token );
		
		$id = $user_row['user_id'];
		
		return $this->get_user_cash( $id );
		
	}
	
	public function get_data ( $str ){
		$data = array();
		$arr = explode('&', $str );
		
		foreach ( $arr as $value ){
			$item = explode( '=', $value );
			$data[$item[0]]=$item[1];
		}
		
		return $data;
	}
	
	public function set_gamer_token ( $game_id ,$codes ){
		
		$code = $this->decode_hash_id( $codes );
//		$code = 3805;
		
		$db = new DB();
		
		
		$is_exist = $db->multi_select( 'online_gamer', 'id', ['game_id','user_id'], [$game_id,$code]);
		
		
		if ( $is_exist == 0 ){
			$token = $this->random_token();
			$query = "INSERT INTO online_gamer (game_id,user_id, token) VALUES ('$game_id', '$code', '$token')";
			
			$value = [ 'game_id' => [ (int)$game_id, 'i'],
					 	'user_id'	=> [ $code, 's'],
					  	'token'		=> [ $token, 's']
					 ];
			
			$db->insert( 'online_gamer', $value );
			
//			$db->get_insert_query( $query );
			
		}
		else {
			$token = $this->random_token();
			$query = "UPDATE online_gamer SET token = '$token' WHERE game_id='$game_id' AND user_id = '$code'";

			$db->get_insert_query( $query );
			
			return true;
		}
		
	}
	
	
	
	
	
	public function update_user_cash_with_token ( $amount, $token ){
		
		$user = $this->get_id_with_token( $token );
		
		if ( $user == 0 )
			return false;
		
//		if ( $amount == $user['amount'] || ($amount*-1) == $user['amount'] || $amount < $user['amount'])
		$this->update_user_cash( $amount, $user['user_id']);
	}

	
	public function update_user_cash ( $amount, $user_id ){
		
		$db = new DB();
		$user = $this->get_user_cash( $user_id );
		
		$value = [ 'cash' => [$user['user_cash']+$amount, 'i'] ];
		$where = [ 'id' => [ $user_id, 'i'] ];
		
		$db->update( 'users', $value, $where );
		
//		$db->update( 'users', 'cash', $user['user_cash']+$amount, 'id', $user_id );
	}
	
	public function insert_transaction1 ( $invoice_type, $user_id, $amount){
		
		$db = new DB();
		
		$user = $this->get_user_cash( $user_id );
		
		if ( $invoice_type == 50 ){
			$description = "start_game";
		}
		else if ( $invoice_type == 51 ){
			$description = "Pay win game";
		}
		
		$value = ['price'		=>[ $amount, 'i' ], 
						 'invoice_type'	=> [ $invoice_type , 'i' ], 
						 'description' 	=> [ $description , 's'],
						 'user_id' 		=> [ $user_id, 'i'],
						 'trans_id' 	=> ['1111111', 's'], 
						 'cash' 		=> [ $user['user_cash'], 'i'],
						 'pay_code' 	=> [ '11111111', 's'],
						 'status' 		=> [1, 'i']
						];
//		$column_value = [$amount, 50, 'شروع بازی', $user_id, '1561455', $user['user_cash'], '1561455', 1];
//		$column_type = ['i','i','s','i','s','i','s','i'];
//		
		$db->insert( 'transactions', $value );
	}
	
	public function insert_transaction ( $invoice_type, $token = null, $amount, $user_id = null, $game_id = null, $affiliate_user_id = null){//7 .'game_id'
		
		$db = new DB();
		
		if ( empty( $user_id ) ){
			
			$users = $this->get_id_with_token( $token );
		
			$user_id = $users['user_id'];
			
			if ( empty( $game_id ) )
				$game_id = $users['game_id'];
		}
		
	
		$user = $this->get_user_cash( $user_id );
		
		$game = $this->get_game_name( $game_id );
		
		if ( $invoice_type == 7 ){
			$description = "شروع بازی : " . $game['name_fa'];
		}
		else if ( $invoice_type == 8 ){
			$description = "برد بازی " . $game['name_fa'];
		}
		else if ( $invoice_type == 9 ){
			$description = "کارمزد بازی " . $game['name_fa'] . " از کاربر " . $affiliate_user_id;
		}
		else if ( $invoice_type == 192 ){
		    $description = " جایزه بازی اسلات - رایگان";
        }
		
		$trans_id = $user_id . time() . $game['id'];
		$invoice_type = $invoice_type.$game_id;
		
		$value = ['price'		=>[ $amount, 'i' ], 
						 'invoice_type'	=> [ $invoice_type, 'i' ], 
						 'description' 	=> [ $description , 's'],
						 'user_id' 		=> [ $user_id, 'i'],
						 'trans_id' 	=> [ $trans_id, 's'], 
						 'cash' 		=> [ $user['user_cash'], 'i'],
						 'pay_code' 	=> [ $trans_id, 's'],
						 'status' 		=> [1, 'i']
						];
//		$column_value = [$amount, 50, 'شروع بازی', $user_id, '1561455', $user['user_cash'], '1561455', 1];
//		$column_type = ['i','i','s','i','s','i','s','i'];
//		
		$db->insert( 'transactions', $value );
	}
	
	
	public function get_game_name ( $game_id ){
		
		$db = new DB();
		
		$game = $db->select( 'casino', '*', 'id', $game_id );
		
		if ( $game == 0 )
			return false;
		
		return $game[0];
		
	}
	
	public function get_game_id ( $game_name ){
		
		$db = new DB();
		
		$id = $db->select( 'casino', 'id', 'name_en', $game_name );
		
		if ( $id == 0 )
			return false;
		
		return $id[0]['id'];
			
	}
	
	
	////////////////////////////////////////////////////////
	
	public function random_token() {
		$alphabet = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ1234567890';
		$pass = array(); 
		$alphaLength = strlen($alphabet) - 1; 
		for ($i = 0; $i < 20; $i++) {
			$n = rand(0, $alphaLength);
			$pass[] = $alphabet[$n];
		}
		return implode($pass); 
}
	
	public function check_can_enter_game ( $game_id, $game_token ){
		
		$db = new DB();
		
		$id = $this->decode_hash_id( $game_token );
		
		$is_exist = $db->multi_select( 'online_gamer', 'id,status', ['game_id','user_id'], [$game_id,$id]);
		
		if ( $is_exist == 0 ){
			$token = $this->random_token();
			$value = [ 'game_id' 	=> [ $game_id, 'i'],
					 	'user_id'	=> [ $id, 's'],
					  	'token'		=> [ $token, 's'],
					  	'status'	=> [ 0 , 'i'], // page ghabl az bazi
					  	'time_start'=> [ time(), 's'],
					 ];
			
			$db->insert( 'online_gamer', $value);
			
			
		}
		else if ( $is_exist[0]['status'] == 99 || $is_exist[0]['status'] == 0 || $is_exist[0]['status'] == 10 || $is_exist[0]['status'] == 11 ) {
			$token = $this->random_token();
			
			$value = [ 'token'		=> [$token, 's'],
					   'status'		=> [0, 'i'],
					   'time_start'	=> [ time(), 's'],
					 ];
			$where = ['game_id'	=> [ $game_id, 'i'],
					  'user_id'	=> [ $id, 's'],
					 ];
			$db->update ( 'online_gamer', $value, $where);
		}
		else if ( $is_exist[0]['status'] == 2 ){
			$this->check_status_for_enter_game( $id, $game_id);
		}
		else{
			return false;
		}
		
		return $token;
		
	}
	
	public function check_token ( $user_id, $token){
		
		$db = new DB();
		
		$is_exist = $db->multi_select( 'online_gamer', '*', ['user_id', 'token'], [$user_id, $token] );
		
		if ( $is_exist == 0 )
			return false;
		return true;
	}
	
	public function check_amount_bet_is_valid( $amount, $token){
		
		$row = $this->get_id_with_token( $token );
		
		if ( $row == 0 )
			return false;
		
		$user = $this->get_user_cash( $row['user_id'] );
		
		if ( $amount < $user['user_cash']){
			
			if ( $this->insert_amount_bet_online_gamer( $amount, $token))
				return true;
		}
		
		return false;
	}
	
	public function insert_amount_bet_online_gamer ( $amount, $token ){
		
		$id = $this->get_id_with_token( $token );
		
		if ( $id == 0 )
			return false;
		
		if ( $id['status'] != 0 )
			return false;
		
		$db = new DB();
		
		$value = [ 'amount' 	=> [ $amount, 'i'],
				 	'status'	=> [ 2 , 'i'],
				 	'time_start'=> [ time() , 's'],
				 ];
		$where = [ 'token'	=> [ $token, 's']];
		
		$db->update( 'online_gamer', $value, $where);
		
		return true;
		
	}
	
	
	
	/////////////////////// game list ////////////////////////
	
	public function check_user_cash ( $token ){
		
		$row = $this->get_id_with_token( $token );
		
		if ( $row == 0 )
			return false;
		
		
		$user = $this->get_user_cash( $row['user_id']);
		
		if ( $user == 0 )
			return false;
		
		return $user['user_cash'];
	}
	
	public function get_user_list_is_waiting ( $token, $game_id, $max_chips){
		
		$row = $this->get_id_with_token( $token );
		
		if ( $row == 0 )
			return false;
		else if ( $row['status'] != 0 )
			return false;
		
		$db = new DB();
		
		$waiting = $db->multi_select( 'online_gamer', '*', ['game_id', 'status'], [ $game_id, 1]);
		
		if ( $waiting == 0)
			return false;
		
		$waiting_list = array();
		foreach ( $waiting as $key=>$value){
			
			if ( $value['token'] != $token ){
				$user = $this->get_user_cash( $value['user_id'] );
				
				if ( $user != 0 ){
					
					if ( $user['user_cash'] < $max_chips){
					$waiting_list[] = [ 'amount' 	=> $value['amount'],
										'double'	=> false,
										'name'		=> $user['user_name'],
										'token'		=> $value['token'],
									  ];	
					}
					
				}
				
			}
			
		}
		
		return $waiting_list;
		
	}
	
	
	///////////////// multi player game ////////////////////////
	
	public function check_player_is_exist ( $user_id, $token, $game_id ){
		
		$db = new DB();
		
		$is_exist = $db->multi_select( 'online_gamer', 'id', ['user_id','token','game_id'], [$user_id, $token, $game_id]);
		
		if ( $is_exist == 0 )
			return false;
		
		return $is_exist[0]['id'];
	}
	
	public function get_id_with_token ( $token ){
		$db = new DB();
		
		$is_exist = $db->multi_select( 'online_gamer', '*', ['token'], [ $token]);
		
		if ( $is_exist == 0 )
			return false;
		
		return $is_exist[0];
	}
	
	public function get_id_with_user_id_game_id ( $user_id, $game_id ){
		$db = new DB();
		
		$is_exist = $db->multi_select( 'online_gamer', '*', ['user_id','game_id'], [ $user_id, $game_id]);
		
		if ( $is_exist == 0 )
			return false;
		
		return $is_exist[0];
	}
	
	public function register_player_for_start_game ( $user_id, $token, $game_id){
		
		$db = new DB();
		
		if ( $id = $this->check_player_is_exist($user_id, $token, $game_id) ){
			$value = ['status' => [1, 'i'], 'time_start' => [time(), 's']];
			$where = [ 'id' => [$id, 'i'] ];

			$db->update( 'online_gamer', $value, $where);
			return true;
		}
		
		return false;
		
	}
	
	public function get_op_data_with_my_token( $token ){
		
		$db = new DB();
		
		$my_user =  $this->get_id_with_token($token);
		
//		var_dump($my_user);
		if( $my_user['op_user_id'] == 0 )
			return false;
		
		$op_user = $db->multi_select('online_gamer', '*', ['user_id','game_id','op_user_id'], [$my_user['op_user_id'], $my_user['game_id'], $my_user['user_id']]);
//		var_dump($op_user);
		if ( $op_user == 0 )
			return false;
		
		return $op_user[0];
	}
	
	public function select_op_user( $my_user_old ){
		$db = new DB();
		
		$my_user = $this->get_id_with_token($my_user_old['token']);
		if ( $my_user['status'] == 1 ){
			
			$user = $db->multi_select ( 'online_gamer', '*', ['game_id', 'status', 'amount'], [$my_user['game_id'], 1, $my_user['amount']]);

			if ( $user == 0 )
				return 0;

			foreach ( $user as $key=>$value){
				if ( $my_user['id'] != $value['id'])
					return $value;
			}
		}
		else if ( $my_user['status'] == 2 ){
		
//			var_dump( $my_user );
			return $this->get_op_data_with_my_token( $my_user['token'] );
		}
		
		return 0;
		
	}
	
	public function insert_op_user ( $my_user, $op_user ){
		
		$db = new DB();
		
		$value = [
			'op_user_id' 	=> [ (int) $op_user['user_id'], 'i'],
			'status'		=> [ 2, 'i'],
		];
		$where = [ 'id' => [(int) $my_user['id'], 'i']];
		
		if ($db->update( 'online_gamer', $value, $where ) )
			return true;
		
		return false;
	}
	
	public function search_player ( $token ){
		$db = new DB();
		
		if ( $my_user = $this->get_id_with_token( $token ) ){
			$time_1 = time();
			$time_2 = time();
			while ( $time_2 - $time_1 < 60  ){
				
				$op_user = $this->select_op_user( $my_user );
				
				if ( $op_user != 0 ){
					$insert_my = $this->insert_op_user( $my_user, $op_user);
					$insert_op = $this->insert_op_user( $op_user, $my_user);

					return $op_user;
				}
				
				sleep(2);
				$time_2 = time();
 
			}

		}
		
		return false;
		
	}
	
	public function check_player ( $op_user, $game_id ){
		
		$db = new DB();
		
		$is_exist = $db->multi_select( 'online_gamer', 'id', ['user_id','game_id','token'], [$op_user['user_id'], $game_id, $op_user['token']]);
		
		if ( $is_exist == 0 )
			return false;
		
		$user = $db->multi_select( 'users', '*', ['id', 'status'], [$op_user['user_id'], 1]);
		if ( $user == 0 )
			return false;
		
		return $user[0];
	}
	
	public function check_player_side ( $my_token, $op_token){
		
		$db = new DB();
		$my_id = $this->get_id_with_token( $my_token );
		$op_id = $this->get_id_with_token( $op_token );
		
		if ( $my_id== 0 || $op_id == 0)
			return false;
		
		if ( $my_id['id'] < $op_id['id'])
			return 'my';
		
		return 'op';
	}
	
	public function get_id_from_token ( $token ){
		$db = new DB();
		$id = $db->multi_select( 'online_gamer', 'id', ['token'], [ $token]);
		
		if ( $id == 0)
			return false;
		
		return $id[0]['id'];
	}
	
	public function insert_data_card ( $my_token, $op_token, $data_card ){
		
		$db = new DB();
		
		$value = ['card_data' => [$data_card , 's']];
		
		$my_where = [ 'token' => [$my_token, 's']];
		$op_where = [ 'token' => [$op_token, 's']];
		
		$db->update( 'online_gamer', $value, $my_where );
		$db->update( 'online_gamer', $value, $op_where );
		
	}
	
	public function insert_game_data ( $token, $game_data ){
		$db = new DB();
		
		$value = ['card_data' => [$game_data , 's']];
		
		$where = [ 'token' => [$token, 's']];
		
		$db->update( 'online_gamer', $value, $where );
	}
	
	public function get_data_card ( $my_token ){
		
		$db = new DB();
		
		$value = ['token' => $my_token ];
		
		$card = $db->select( 'online_gamer', 'card_data,user_id', 'token', $my_token );
		
		$card_data = json_decode( $card[0]['card_data'] );
		
		return ['my_hand' 	=> $card_data->{$card[0]['user_id']},
			   'floor' 		=> $card_data->floor,
			   ];
	}
	
	
	////////////////////////////// enter game //////////////////////
	
	public function set_player( $my_token, $op_token ){
		
		$my_user = $this->get_id_with_token( $my_token );
		$op_user = $this->get_id_with_token( $op_token );
		
		if ( $my_user == 0 || $op_user == 0 )
			return false;
		
		$insert_my = $this->insert_op_user( $my_user, $op_user);
		$insert_op = $this->insert_op_user( $op_user, $my_user);
		
		return $op_user;
		
	}
	
	
	///////////////////////////// finish game /////////////////
	
	public function finished_game ( $token, $type ){
		
		if ( $type == 'finish'){
			
			$this->complete_game ( $token );
		}
		return true;
	}
	
	public function complete_game ( $token ){
		
		$my = $this->get_id_with_token( $token );
		
		$this->reset_game( $token );
		
//		if ( isset( $my['op_user_id'] )){
//			
//			$op = $this->get_user_with_user_id_game_id_op_user_id( $my['op_user_id'], $my['game_id'], $my['user_id']);
//			
//			$this->reset_game( $op['token']);
//		}
		
		return true;
	}
	
	public function reset_game ( $token ){
		
		$db = new DB();
		
		$value = [
//				  'op_user_id' 	=> [ null, 'i'],
				  'card_data'	=> [ null, 's'],
				  'amount'		=> [ 0 , 'i'],
				  'time_start'	=> [time(), 's'],
				  'status'		=> [ 0, 'i'],
				 ];
		
		$where = [ 'token' => [ $token, 's'] ];
		
		$db->update( 'online_gamer', $value, $where);
		
		return true;
		
	}
	
	public function get_user_with_user_id_game_id_op_user_id ( $user_id, $game_id, $op_user_id){
		
		$db = new DB();
		
		$user = $db->multi_select( 'online_gamer', '*', ['user_id', 'game_id', 'op_user_id'], [ $user_id, $game_id, $op_user_id]);
		
		if ( $user == 0 )
			return false;
		
		return $user[0];
	}
	
	///////////////////////////////////////
	
	public function get_my_and_op_row_with_my_token ( $my_token ){
		
		$db = new DB();
		
		$my = $db->multi_select( 'online_gamer', '*', ['token'], [$my_token]);
		
		if ( $my == 0 )
			return false;
		
		$my_id = $my[0]['user_id'];
		$game_id = $my[0]['game_id'];
		$op_user_id = $my[0]['op_user_id'];
		
		$op = $db->multi_select ( 'online_gamer', '*', ['user_id', 'game_id', 'op_user_id'], [$op_user_id, $game_id, $my_id]);
		
		if ( $op == 0 )
			return false;
		
		return ['my' => $my[0], 'op'=>$op[0] ];
		
	}
	
	public function change_status ( $token, $status ){
		
		$db = new DB();
		
		$value = [ 'status' => [ $status, 'i'] ];
		$where = ['token'	=> [ $token, 's'] ];
		
		$db->update( 'online_gamer', $value, $where);
	}
	
	public function check_status_waiting_op_play ( $token, $status ){
		
		$db = new DB();
		
		$time_1 = time();
		$time_2 = time();
		
		while ( $time_2 - $time_1 < 60 ){
			
			$data = $db->multi_select('online_gamer','card_data',['token','status'],[$token, $status]);
			
			if ( $data != 0 )
				return json_decode($data[0]['card_data']);
			
			sleep(3);
			$time_2 = time();
		}
		
		return false;
	}
	
	
	
	////////////////////////// RPS Function ////////////////////////////
	
	public function rps_register_my_select( $token, $user_id, $select ){
		
		$id = $this->get_id_with_token( $token );
		
	}


	///////////////////////// slot function ///////////////////////////

    public function get_slot_game_data ( $token ){

        $db = new DB();

        $card_data = $db->select( 'online_gamer', 'card_data', 'token', $token);

        if ( $card_data == 0 )
            return false;

        return json_decode($card_data[0]['card_data']);
    }

    public function insert_slot_game_data( $token, $data ){

        $id = $this->get_id_with_token( $token );

        if ( $id == 0 )
            return false;

        $db = new DB();

        $value = ['card_data' => [ json_encode($data), 's']];
        $where = [ 'token' => [ $token, 's']];

        $db->update( 'online_gamer', $value, $where );

        return true;
    }
	
	
	
	/////////////////////////// ping /////////////////////////////////
	
	
	
	public function update_ping ( $token ){
		
		$db = new DB();
		
		$value = ['time_start' => [time() , 's']];
		$where = [ 'token'	=> [ $token, 's']];
		
		$db->update( 'online_gamer', $value, $where);
	}
	
//	public function delete_

	
	//////////////////////////// continue game /////////////////
	
	public function check_game_status ( $my, $op ){
		$continue = false;
		
		if ( $my['status'] == 2 && ($op['status'] == 2 || $op['status'] == 3) ){
			$continue = true;
		}
		else if ( $my['status'] == 10 ){
			$continue = 'lose';
		}
		else if ( $my['status'] == 11 ){
			$continue = 'win';
		}
		
		return $continue;
	}
	
	public function check_status_for_enter_game ( $user_id, $game_id ){
		
		$my = $this->get_id_with_user_id_game_id( $user_id, $game_id );
		
		if ( $my != 0 ){
			
			if ( $my['status'] == 2 ){
				$op = $this->get_id_with_user_id_game_id( $my['op_user_id'], $game_id);
				
				if ( $op['status'] == 2 ){
					$this->change_status( $op['token'], 11);
					$this->change_status( $my['token'], 0 );
				} 
			}
		}
		
		
	}
	
	
	/////////////////////////// cash in ///////////////////////
	
	public function register_amount_for_roulette ( $token, $amount ){
		
		$db = new DB();
		
		$value = [ 'amount' 	=> [ $amount, 'i'],
				 	'status'	=> [2, 'i'],
				 ];
		$where = [ 'token' => [ $token, 's']];
		
		$db->update( 'online_gamer', $value, $where );
		
	}
	
	public function register_amount_in_online_gamer ( $token, $amount ){
		
		$db = new DB();
		
		$value = [ 'amount' 	=> [ $amount, 'i'],
				 	'status'	=> [2, 'i'],
				 ];
		$where = [ 'token' => [ $token, 's']];
		
		$db->update( 'online_gamer', $value, $where );
		
	}
	
	
	
	//////////////////////////////////////////////////////
	
	public function waiting_function ( $token ){
		
		$my_row = $this->get_id_with_token( $token );
		
		$time_1 = time();
		$time_2 = time();
		
		while ( time() - $time_1 < 20 ){
			
			$op_row = $this->get_op_data_with_my_token( $token );	
			
			if ( $op_row['status'] == 3 )
				return true;
		}
		
		return false;
		
		
		
		
		
		
	}
	
	
	
	
	
}

?>