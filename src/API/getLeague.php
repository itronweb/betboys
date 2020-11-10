<?php 

require_once('../application/config/db.php');

$direction = "../upload/API/";
$league_address = "league_name/";

$db = new DB();

$sports = $db->multi_select('sports', '*', ['1'], ['1']);

foreach ( $sports as $key=>$value ){
	
	$sport_name = $value['name_en'];
	
	var_dump( $sport_name );
	
	$dest_url = $direction.$sport_name."/league_name.json";
	
	$file_array = [ 'day_0', 'day_1', 'day_2', 'day_3', 'inplay', 'results'];
	foreach( $file_array as $file ){
		
		$league_name = new stdClass();
		
		if ( file_exists($dest_url) )
			$league_name = json_decode(file_get_contents($dest_url));

		$source_url = $direction.$sport_name."/$file.json";

		if ( file_exists($source_url) ){
			
			$json = json_decode(file_get_contents($source_url));

			if ( !empty( $json )){

				foreach ( $json->data as $item ){
					
					$name_league = $item->competition->name;
					$id_league = $item->competition->id;
					if ( !isset($league_name->{$id_league}) ){

						$league_name->{$id_league} = new stdClass();
						$league_name->{$id_league}->id = $id_league;
						$league_name->{$id_league}->name_en = $name_league;
						$league_name->{$id_league}->name_fa = '';
						$league_name->{$id_league}->sort = '';

					}
					else if ( isset($league_name->{$id_league})){
						
						if ( !isset($league_name->{$id_league}->id))
							$league_name->{$id_league}->id = $id_league;
						if ( !isset($league_name->{$id_league}->sort) )
							$league_name->{$id_league}->sort = '';
					}
				}
				file_put_contents($dest_url,json_encode($league_name));
			
			}
			
		}
		
	}
	
	
	
}

?>