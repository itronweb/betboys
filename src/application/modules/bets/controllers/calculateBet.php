<?php 

require_once APPPATH . 'config/db.php';

class calculateBet{
	
	public $sport;
	
	
	public function __construct(){
		$db = new DB();
		
	}
	
	public function convertSpaceToUnderline($data){
		$data = str_replace( '/', '_', implode('_',explode(' ',$data)));
		return str_replace('-','_',$data);
	}
	
	public function checkWin ( $match, $form ){
		
		$className = $form->soccer_type . "_result";
		
		require_once 'sport_result/'.$className.'.php';
		
		$sport_class = new $className();
		
		$label = 'check_'.$this->convertSpaceToUnderline(trim($form->bet_type));
//		$label = 'checkWin';
		var_dump($form->soccer_type);
		if( method_exists($sport_class, $label))
			return $sport_class->{$label}( $match, $form );
		else
			return 10;
	}
	
	
	
	///////////////////////// Calculate Odds ///////////////////////////////
	
	

	
}
?>