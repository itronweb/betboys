<?php 
//define('ROOT_PATH',$_SERVER['DOCUMENT_ROOT']);

require_once ROOT_PATH . '/V3SERFDSX2:12/db.php';

//session_start();

class Game {
	
	public $hashid;
	
	public function __construct(){
		
		
	}
		
	public function check_code ( $data ){
		if ( !isset($data['code']) )
			return false;
		
		if ( empty($data['code']) )
			return false;
		
	}
	public function check_login(){
		
		if(isset($_SESSION['user_id'])){return true;}
		
		if ( !isset( $_COOKIE['uid']) )
		{
			header('location: /');
		}

		
		$db = new DB();
		
		$username = $_COOKIE['un'];
		$uid = $_COOKIE['uid'];
			
		$is_exist = $db->multi_select( 'users', 'id', ['user_id','user_username'], [$uid,$username]);
		
		if ( $is_exist == 0 )
		{
		    header('location: /');
		}

	}
	public function check_uid(){
		return $_COOKIE["SZRUID"];
	}
	/*
	public function set_gamer_token ( $game_id ,$codes ){
		
//		$code = $this->decode_hash_id( $codes );
		$code = 3805;
		
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
	*/
	public function get_game_id ( $game_name ){
		
		$db = new DB();
		
		$id = $db->select( 'newCasino', 'id', 'name_en', $game_name );
		
		if ( $id == 0 )
		{
			return false;
		}
			
		return $id['id'];
			
	}
	
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
	
	public function set_cookie ( $name, $value, $time = 0 ){
		if ( $time == 0 )
			$time = time() + 3600 ;
		setcookie( $name , $value, $time);
	}
	
}

?>