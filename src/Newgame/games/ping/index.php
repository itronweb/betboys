<?php 
require_once '../../api/RestApi/game.php';
	
$game = new Game();



if (isset ( $_POST['token'])){
	
	$token = $_POST['token'];
	
	$user_row = $game->get_id_with_token( $token );
	
	if ( $user_row['status'] == 0 ){
		$game->update_ping( $token );	
	}
	else if ( time() - $user_row['time_start'] > 10 && ($user_row['status'] == 10 || $user_row['status'] == 11 )){
		
	}
	
	
}

//if (isset( $_GET['token'])){
	//$game->update_ping( $_GET['token']);
//}
		

?>