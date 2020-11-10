<?php
//Base URL
	$date 	= &$_GET['date'];
	$id 	= &$_GET['id'];
	 $goalserve	=	'http://inplay.goalserve.com/results/'.$date.'/'.$id.'.json';
	 $get_sportjson	=	@file_get_contents($goalserve);
	 echo $get_sportjson;

?>