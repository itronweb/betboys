<?php 
	//
	// echo 'New Version Coming Soon...';
	// exit;
	// Sezar Bet Script's API
	$ApiDIR				=		'/upload/API/';
	
	//API Directories
	$sport_soccer		=		'soccer';
	
	//Execute Codes
	//Soccer Directory Execute
	// file_put_contents($sport_soccer .	"/inplay.json" 		, file_get_contents('http://5.9.249.60:236/SezarAPI/inplay.php?sport=soccer'));

	$time1 = time();
	while ( time() - $time1 < 55 ){

		$first = time();
		$first_1 = date(" Y-m-d  h:i:sa ");	
		file_put_contents($sport_soccer .	"/inplay.json" 		, file_get_contents('http://5.9.249.60:236/SezarAPI/inplay.php?sport=soccer'));
		$second = time();
		$first_2 = date(" Y-m-d  h:i:sa ");
		$times = 5-($second-$first);
		
		if ( $times > 0 )
			sleep( $times );
	}
?>