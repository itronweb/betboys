<?php

	define('ROOT_PATH',$_SERVER['DOCUMENT_ROOT']);
	require_once '../../api/RestApi/game.php';
	// date_default_timezone_set("Asia/Tehran");

	$maxItems = 1;


	$after_id = 0;

	if ( isset($_REQUEST['after_id']) ) {

		$input_str = intval($_REQUEST['after_id']);

		if ( $input_str > 0 && $input_str < INF )
			$after_id = $input_str;

	}
	
	$Query = mysqli_query($connect_nex, "SELECT * FROM `instagram_history` WHERE `id` > $after_id ORDER BY `id` DESC LIMIT 0,$maxItems");
	$rows = array();

	$result = [];


	while ( $row_nex = mysqli_fetch_array($Query) ) { 
		
		$account_id      = sanitize_text($row_nex['account_id']);
		$account_idsql   = "SELECT * FROM instagram_accounts WHERE id = '$account_id'";
		$account_idquery = mysqli_query($connect_nex, $account_idsql);
		$account_idsget  = mysqli_fetch_assoc($account_idquery);
		
		switch ($row_nex['type']) {
			case "like":
				$pk = 'https://www.instagram.com/p/' . $row_nex['pk'];
				$classes = 'is--like';
				$verb = "لایک کرد";
				$what = "پست";
				break;
			case "comment":
				$pk = 'https://www.instagram.com/p/' . $row_nex['pk'];
				$classes = 'is--comment';
				$verb = "کامنت گذاشت";
				$what = "پست";
				break;
			case "follow":
				$pk = 'https://www.instagram.com/' . $row_nex['pk'];
				$classes = 'is--follow';
				$verb = "فالو کرد";
				$what = "کاربر";
				break;
			case "unfollow":
				$pk = 'https://www.instagram.com/'. $row_nex['pk'];
				$classes = 'is--unfollow';
				$verb = "آنفالو کرد";
				$what = "کاربر";
				break;
			default:
				$username = 'panel-default';
				$verb = "فعالیت تعریف نشده است !!!";
		}


		$node = [
			'id'       => $row_nex['id'],
			'username' => $account_idsget['username'],
			'what'     => $what,
			'verb'     => $verb,
			'pk'       => $pk,
			'classes'  => $classes,
			'avatar'   => @getCachedImg($account_idsget['avatar']),
			'age'      => time_elapsed_string($row_nex['created']),
			'time'     => $row_nex['created'],
			'ts'       => strtotime($row_nex['created'])
		];
		
		//$result []= $node;
		array_unshift( $result, $node );


	}



	// Take it easy ;) <c> David@Refoua.me -> Aug 2018
	header('Content-Type: application/json; charset=utf-8');
	echo json_encode( [ "maxItems" => $maxItems, "data" => $result, "now" => time() ], JSON_PRETTY_PRINT );
	exit;
	