<?php

require_once 'GoalServe.php';

$goalserve = new GoalServe();

$time1 = time();

$direction = "../upload/API";
$log = "/log";
$delay_time = 5;
$full_time = 60;

$max_time = $full_time - $delay_time;


$soccer_name = 'soccer';


if ( isset($_GET['soccer'])){
	
	$soccer_name = $_GET['soccer'];
	
}

while ( time() - $time1 < $max_time ){

	$first = time();
	$first_1 = date(" Y-m-d  h:i:sa ");
    $goalserve->get_inplay_by_soccer( $soccer_name );
	$second = time();
	$first_2 = date(" Y-m-d  h:i:sa ");
	$times = 5-($second-$first);
	
	if ( $first - $second > $delay_time ){
		$delay_time = $first - $second + 1;
		$max_time = $full_time - $delay_time;
	}
	
	if ( $times > 0 )
		sleep( $times );
	
	
	$time = "\r\n" . " Start : " . $first_1 . "      ". " Get_reponse : " . $first_2 ;
	$test = fopen($direction.$log."/inplay_log.txt", "a") or die("Unable to open file!");
	fwrite($test, $time);

	//$time_1 = time();
}

$time = "\n" . "------------------------------------------------------------" ;
$test = fopen($direction.$log."/inplay_log.txt", "a") or die("Unable to open file!");
fwrite($test, $time);



?>