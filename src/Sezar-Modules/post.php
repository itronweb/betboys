<?php
	include '../admincp/includes/SezarFunctions/cfg.php';
	
	//Base URL
	$get			=	htmlentities($_GET['link']);
	$Query 			= 	dbGetRow('content_pages', ['slug'=>$get]);
	$settings		= 	dbGetRow('settings', ['id'=>8,'code'=>'theme']);
	$settings_title	=	dbGetRow('settings', ['id'=>2,'code'=>'site_name']);;
	if ( isset($Query) ){
		$title				=	$settings_title['value'];
		$post_title			=	$Query['name'];
		$post_content		= 	$Query['description'];
		$DIR				=	'../themes/default/'.$settings['value'].'/SezarCMS';
	}else{
		echo '<meta http-equiv="refresh" content="0;url=http://sezar.world/SGBS-Script">';
	}
	include	$DIR.'/index.php';
	 ?>