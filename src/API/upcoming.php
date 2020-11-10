<?php 


require_once 'GoalServe.php';

$goalserve = new GoalServe();

$day = (isset($_GET['day'])) ? $_GET['day'] : 0 ;
$goalserve->get_upcoming( $day );


?>