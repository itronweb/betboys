<?php
	include 'Database.php';
	$db = dbInit( 'mysql:charset=utf8;host=' . 'localhost', 'vip90_sgbs', 'vip90_sgbs', 'Kmcu80!9' );
	$cn = @mysqli_connect($server, $username, $password, $db_name);
	?>