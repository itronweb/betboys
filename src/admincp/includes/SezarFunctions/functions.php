<?php

// Define the CMS_ROOT path for include() and require() operations
define( 'CMS_ROOT', rtrim(realpath( __DIR__ . '/../' ), '/') . '/' );

// Instagram API 
	require_once __DIR__ . '/Instagram.php';
// Image caching utility
	require_once __DIR__ . '/Cache_img.php';
//JDATE Function 
	require_once __DIR__ . '/jdf.php';
//DatabaseConnect
	mysqli_query( $connect, "SET NAMES 'utf8'" );
	include __DIR__ . '/Database.php';
	$db = dbInit( 'mysql:charset=utf8;host=' . $hostname_cn, $database_cn, $username_cn, $password_cn );
	
// Load the common classes
	require_once __DIR__ . '/../common/KT_common.php';

// Load the tNG classes
	require_once __DIR__ . '/../tng/tNG.inc.php';

// Load the required classes
	require_once __DIR__ . '/../tfi/TFI.php';
	require_once __DIR__ . '/../tso/TSO.php';
	require_once __DIR__ . '/../nav/NAV.php';
	require_once __DIR__ . '/../wdg/WDG.php';

//Manage Database Rows
	//$row_usern       = dbGetRow( 'users',       ['name'=>@$_SESSION['name']] );
	$row_admin       = dbGetRow( 'admin',       ['user'=>@$_SESSION['auser']] );

// Sanitize numbers only
function sanitize ($var) {
	$var = (int) preg_replace('@[^\d]@iU', '', $var);
	return $var;
}

// Sanitize text inputted to db
function sanitize_text ($var) {
    global $connect;
	$var = mysqli_real_escape_string( $connect, $var);
	return $var;
}

// Sanitize text outputted from db
function sanitize_output ($var) {
	$var = htmlentities($var);
	return $var;
}

function numberformat ($dig, $sep = ',', $lim = 3) {
	$dig = (string) round($dig); $str = "";
	for ($i = ($len = strlen($dig)) - 1; $i>=0; $i--) $str = (($len - $i) %$lim == 0 && $i>0 ? $sep : '') . $dig{$i} . $str;
	return $str;
}

function limitword($string, $limit){
    $words = explode(" ",$string);
    $output = implode(" ",array_splice($words,0,$limit));
    return $output;
}
// $ret = getInstaJson( 'DRSDavidSoft' );

function utf8ize($d) {
	if (is_array($d)) 
		foreach ($d as $k => $v) 
			$d[$k] = utf8ize($v);

	 else if(is_object($d))
		foreach ($d as $k => $v) 
			$d->$k = utf8ize($v);
			
	 else if(is_int($d)||is_bool($d))
		return ($d);

	 else if(is_string($d))
		return utf8_encode($d);

	return $d;
}
// Instagram Functions	
function getInsta( $username ) {

	global $db;

	# Process request
	$username = trim( @$username ); // Example username: DRSDavidSoft
	$result   = dbRead( 'ig_accounts', ['username'=>strtolower($username)] );
	
	$lastUpdate = (time() - intval(@$result[0]['date_lastupdate']));
	
	if (
		(count($result) === 0) ||	// Not found on the database; new user
		($lastUpdate > (60*60) )		// Found, but the last update date is older than 5 minutes
	) {
		$user  = getInstaJson( $username );
		$about = &$user['about'];
		$info  = &$user['info'];
		$count = &$user['count'];

		if ( empty($user) )
			error("Can not get Instagram account data.", 500);

		else {

			$resultId = dbRead( 'ig_accounts', ['id'=>$about['id']] );
			$rowData = [
				'id'               => $about['id'],
				'username'         => $about['username'],
				'name'             => $about['name'],
				'bio'              => $about['bio'],
				'url'              => $about['url'],
				'avatar'           => $about['avatar'],
				'thumb'            => $about['thumb'],
				'is_private'       => empty($about['is_private'])  ? 0 : 1,
				'is_verified'      => empty($about['is_verified']) ? 0 : 1,
				'followers'        => intval($count['followers']),
				'following'        => intval($count['following']),
				'date_lastupdate'  => time()
			];
			
			if ( empty($rowData['id']) ) return false;
			
			if ( count($resultId) === 0 ) {
				$success = dbAdd( 'ig_accounts', $rowData );
			} else {
				$rowData = utf8ize( $rowData );
				$success = dbWrite( 'ig_accounts', ['id'=>$about['id']], $rowData );
			}
			
			if ( $success === false ) error("Can not write to the database.", 500);

		}
		
	}
	
	$account = dbGetRow( 'ig_accounts', ['username'=>strtolower($username)] );

	return $account;
	
}

?>