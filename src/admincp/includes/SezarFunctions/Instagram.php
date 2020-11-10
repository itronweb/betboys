<?php

	/*
	 * ================================
	 * Instagram Account Data Lib
	 * ================================
	 *
	 * Requires:	PHP v5.6.0 and above, openssl lib enabled, safe_mode off.
	 * Filename:	getIgJson.php
	 * Version:		v1.2.0 - Apr 2018
	 * Author:		DRS David Soft <David@Refoua.me>
	 * Description: Functions used to fetch and parse Instagram's
	 *				internal JSON structure data.
	 *
	 * WARNING: This work is protected by copyright laws.
	 *          any unauthorized duplication, modification
	 *			or usage might result in illegal law infringement.
	 *
	 */
	 
	define ("__author__", 'David@Refoua.me');
	
	$API = [
		'accPage'	=>	"https://www.instagram.com/%s/",
	];
	
	function error ($msg, $code = 500) {
		// http_response_code ($code);
		// echo "<b>Error:</b> $msg";
		//error_log($msg);
		trigger_error($msg);
	}
	
	function getPage ($url, $headers = []) {
		
		// Create a stream
		$opts = array (
			'http'	=>	array (
				'method'	=>	"GET",
				'header'	=>	implode(@$headers, "\r\n")
			)
		);

		$context = stream_context_create($opts);

		// Open the file using the HTTP headers set above
		$file = trim( @file_get_contents( $url, false, $context ) );
		
		if ( empty($file) ) error("Could not open remote file to read.", 500);
		else return $file;
		
	}

	$regex_pattern = '@<\s*script\b[^\<\>]*\>[^\<\>]*_sharedData\s*\=\s*(?P<sharedData>[^\<\>]*)\s*\;*\s*<\s*\/*\s*script\b[^\<\>]*\>@iU';
	
	function getSharedData ($url) {
		global $regex_pattern;
	    $allowed = ini_get('allow_url_fopen');
	    if ( empty($allowed) ) error("Server error: allow_url_fopen is disabled!");
		else $data = trim( file_get_contents($url) );
		if ( empty($data) ) error("Could not open remote file to read.", 500);
		else if ( preg_match( $regex_pattern, $data, $result) ) {
			$data = json_decode($result['sharedData'], true);
			$entry = &$data['entry_data'];
			if ( empty($entry) || empty($entry['ProfilePage']) ) error("Invalid JSON syntax.");
			else {
				$ret = array_pop($entry['ProfilePage']);
				if ( is_array($ret) ) return $ret;
				else error("Profile Page entry data not found.");
			}
		}
		else error("Can not find the required pattern!");
	}
	 
	function getInstaJson ( $username ) {
		GLOBAL $API;
		$user = [];
		
		$username = trim($username);
		
		if ( empty($username) )
			error("No username were specified.", 400);
		
		// Get the account JSON data
		$data = getSharedData( str_replace('%s', $username, $API['accPage']) );

		$ig = &$data['graphql'];

		// Validate retrieved data
		if ( empty($ig['user']) )
			error("Invalid syntax received.", 500);
		
		else {
			
			$user = &$ig['user'];
			
			$about = [];
			$info  = [];
			$count = [];
			$posts = [];
			
			/** Process basic account information */
			$about['id']          = $user['id'];
			$about['username']    = $user['username'];
			$about['name']        = $user['full_name'];
			//$about['bio']         = $user['biography'];
			$about['url']         = $user['external_url'];
			$about['avatar']      = $user['profile_pic_url_hd'];
			$about['thumb']       = $user['profile_pic_url'];
			
			/** Is the account private and/or verified? */
			$info['is_private']   = empty($user['is_private'])  ? false : true;
			$info['is_verified']  = empty($user['is_verified']) ? false : true;
			
			/** Get the followers and following count */
			$count['followers']   = $user['edge_followed_by']['count'];
			$count['following']   = $user['edge_follow']['count'];
			
			/** Parse media as posts */
			/*
			foreach ( $user['edge_owner_to_timeline_media']['edges'] as $edge ) {

				if ( $node = $edge['node'] )
				{

					$id = intval($node['id']);
					
					# TODO: proccess values before posting
					$posts [$id]= [
						'id'			=> $node['id'],
						'code'			=> $node['code'],
						'owner'			=> $node['owner'],
						'date'			=> $node['date'],
						'dimensions'	=> $node['dimensions'],
						'is_video'		=> $node['is_video'],
						'thumbnail_src'	=> $node['thumbnail_src'],
						'display_src'	=> $node['display_src'],
						'comments'		=> $node['comments'],
						'likes'			=> $node['likes']
					];

				}

			}
			*/
			
			/** Bind the processed arrays into the user */
			$user = [
				'about' => &$about,
				'info'  => &$info,
				'count' => &$count,
				'posts'  => &$posts
			];
			
		}
		
		return $user;
		
	}
?>