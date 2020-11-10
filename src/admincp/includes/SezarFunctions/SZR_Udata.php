<?php
// error_reporting(-1);
// ini_set('display_errors' , 1);
$Headers = array (
	'Access-Control-Allow-Origin: *',
	'Access-Control-Allow-Methods: POST, GET, OPTIONS',
	'Access-Control-Max-Age: 1000',
	'Pragma: public', 'Expires: -1',
	'Cache-Control: public, must-revalidate, post-check=0, pre-check=0',
	'Content-Type: text/javascript',
);

foreach ($Headers as $Str) header ($Str);

	// Sezar Config
	include 'cfg.php';
	//Base URL
	$get		=	$_GET['id'];
	$Query = dbGetRow('users', ['id'=>$get]);
	// echo '<div id="secret">'.$Query['cash'].'</div>';
	echo $Query['cash'];
?>