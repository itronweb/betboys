<?php 

require_once('../application/config/db.php');

$direction = "../upload/API/";
$team_address = "team_name/";

$db = new DB();

$sports = $db->multi_select('sports', '*', ['1'], ['1']);

foreach ( $sports as $key=>$value ){
	
	$sport_name = $value['name_en'];
	
	var_dump( $sport_name );
	
	$dest_url = $direction.$sport_name."/team_name.json";
	
	$file_array = [ 'day_0', 'day_1', 'day_2', 'day_3', 'inplay', 'results'];
	foreach( $file_array as $file ){
		
		$team_name = new stdClass();
		
		if ( file_exists($dest_url) )
			$team_name = json_decode(file_get_contents($dest_url));

		$source_url = $direction.$sport_name."/$file.json";

		if ( file_exists($source_url) ){
			var_dump( $source_url);
			$json = json_decode(file_get_contents($source_url));

			if ( !empty( $json )){

				foreach ( $json->data as $item ){

					$home = $item->homeTeam;
					$away = $item->awayTeam;

					if ( !isset($team_name->{$home->id}) ){

						$team_name->{$home->id} = new stdClass();
						$team_name->{$home->id}->id = $home->id;
						$team_name->{$home->id}->name_en = $home->name;
						$team_name->{$home->id}->name_fa = '';
						$team_name->{$home->id}->sort = '';
						

					}
					else if ( !isset($team_name->{$away->id}) ) {
						$team_name->{$away->id} = new stdClass();
						$team_name->{$away->id}->id = $away->id;
						$team_name->{$away->id}->name_en = $away->name;
						$team_name->{$away->id}->name_fa = '';
						$team_name->{$away->id}->sort = '';
					}
					else if ( isset( $team_name->{$home->id} ) ){
						if ( !isset($team_name->{$home->id}->id) )
							$team_name->{$home->id}->id = $home->id;
						if ( !isset($team_name->{$home->id}->sort) )
							$team_name->{$home->id}->sort = '';
					}
					else if ( isset($team_name->{$away->id}) ){
						if ( !isset($team_name->{$away->id}->id ) )
							$team_name->{$away->id}->id = $away->id;
						if ( !isset($team_name->{$away->id}->sort) )
							$team_name->{$away->id}->sort = '';
						
						
					}
				}
				file_put_contents($dest_url,json_encode($team_name));
			
			}
			
		}
		
	}
	
	
	
}

?>