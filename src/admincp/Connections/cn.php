<?php
error_reporting(1);

$hostname_cn = "localhost";
$database_cn = "vip90_v3";
$username_cn = "vip90_v3";
$password_cn = "tXT83*f3";

//Sezar Config
$Sezar_Theme_DIR			=	'https://sezarco.ir/sezar-cp/sezar-themes/';
$Sezar_Theme_Name			=	'cp-light-blue-rtl';
$Sezar_Theme_Link			=	$Sezar_Theme_DIR . $Sezar_Theme_Name . '/';	

// Create connection
$cn = mysqli_connect($hostname_cn, $username_cn, $password_cn, $database_cn) or trigger_error(mysqli_error($database_cn),E_USER_ERROR);
// Check connection
if (!$cn) {
    die("Connection failed: " . mysqli_connect_error());
}

mysqli_query($cn,"SET character_set_results = 'utf8', character_set_client = 'utf8', character_set_connection = 'utf8', character_set_database = 'utf8', character_set_server = 'utf8'");  

?>
