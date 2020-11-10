<?php 

require_once('db.php');
require_once('config_api_address.php');

$db = new DB();

class Api_function{
	
	// Get all content name with ID by id & contentName
	public function getContentNameWithIDDirect ( $id , $contentName ){
		$address = new Config_api_address();
		$url = $address->get_address($address->{$contentName});
		$url = str_replace( '{id}' , $id , $url );
		
		$content = file_get_contents( $url );
		$content_json = json_decode( $content );
		$method = "insert_$contentName";
		if($contentName == 'teams'){
			$this->insertTeams($content_json->data);	
		}
		
		return $content_json->data->name;
	}
	
	public function getContentWithID( $id , $contentName , $returnName){
		$address = new Config_api_address();
		$url = $address->get_address($address->{$contentName});
		$url = str_replace( '{id}' , $id , $url );
		
		$content = file_get_contents( $url );
		$content_json = json_decode( $content );
		$method = "insert_$contentName";
		
		return $content_json->data->{$returnName};
	}
	
	public function getContentNameWithID ( $id , $contentName ){
		$db = new DB();
		$query = "SELECT {$contentName}_name_en FROM $contentName WHERE {$contentName}_id=$id";
		$result = $db->cn->query($query);
		if($result->num_rows == 0){
			return $this->getContentNameWithIDDirect( $id , $contentName );
		}else{
			while( $row = $result->fetch_assoc()){
				return $row["{$contentName}_name_en"];
			}
		}
//		$address = new Config_api_address();
//		$url = $address->get_address($address->{$contentName});
//		$url = str_replace( '{id}' , $id , $url );
//		
//		$content = file_get_contents( $url );
//		$content_json = json_decode( $content );
//		
//		return $content_json->data->name;
	}
	
	// Get continent by id
	public function getContinentWithID( $id ){
		
		$address = new Config_api_address();
		
		$url = $address->get_continent_by_id.$address->api_token;
		$url = str_replace('{id}',$id,$url);

		$content = file_get_contents($url);

		$continent_json = json_decode($content);

		return $continent_json->data->name;
	}
	
	// Get country by id
	public function getCountryWithID( $id ){
		
		$address = new Config_api_address();
		$url = $address->get_address($address->get_country_by_id);
		$url = str_replace('{id}',$id,$url);
		
		$content = file_get_contents($url);
		$contry_json = json_decode($content);
		return $contry_json->data->name;
		
	}
	
	public function insertTeams( $obj ){
			$db = new DB();
		
			$query_insert = "INSERT INTO teams (teams_id,legacy_id,teams_name_en,twitter,country_id, national_team,founded,logo_path,venue_id) VALUES (?,?,?,?,?,?,?,?,?)";

			$stmt = $db->cn->prepare($query_insert);
			
			$stmt->bind_param("iissiiisi",
							  $obj->id,
							  $obj->legacy_id,
							  $obj->name,
							  $obj->twitter,
							  $obj->country_id,
							  $obj->national_team,
							  $obj->founded,
							  $obj->logo_path,
							  $obj->venue_id
							 );

			$test = $stmt->execute();
			if($test === false)
				die('execute() failed: ' . htmlspecialchars($stmt->error));
				
	}
	
	public function getPreMatchOddsWithFixturBookmaker( $fixture_id , $bookmaker_id='2'){
		$address = new Config_api_address();
		$url = $address->get_address($address->get_prematch_odds_with_fixture_bookmaker);
		$url = str_replace('{fixture_id}',$fixture_id,$url);
		$url = str_replace('{bookmaker_id}',$bookmaker_id,$url);
		$content = file_get_contents($url);
		$content_json = json_decode($content);
//		var_dump($content);
	
	}
	
	public function content( $id , $contentName ){
		$address = new Config_api_address();
		$url = $address->get_address($address->{$contentName});
		
		$url = str_replace( '{id}' , $id , $url );
		var_dump($url);
		$content = file_get_contents( $url );
		$content_json = json_decode( $content );
		
		return $content_json->data;
	}
	
	public function preMatchOdds( $fixture_id ){
		
	}
}

?>