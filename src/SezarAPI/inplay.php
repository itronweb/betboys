<?php
//Base URL
	$get		=	$_GET['sport'];
	$goalserve	=	'http://inplay.goalserve.com/inplay-'.$get.'.json';
	$get_sportjson	=	@file_get_contents($goalserve);
	echo $get_sportjson;
?>