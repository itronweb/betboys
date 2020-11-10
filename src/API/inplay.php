<?php

require_once 'GoalServe.php';

$goalserve = new GoalServe();
    $goalserve->get_inplay();
	exit;

$time1 = time();

$direction = "../upload/API";
$log = "/log";

while ( time() - $time1 < 55 ){

	$first = time();
	$first_1 = date(" Y-m-d  h:i:sa ");
    $goalserve->get_inplay();
	$second = time();
	$first_2 = date(" Y-m-d  h:i:sa ");
	$times = 5-($second-$first);
	
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