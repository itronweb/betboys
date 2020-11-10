<?php
	//Database
	include 'Database.php';
	$db = dbInit( 'mysql:charset=utf8;host=' . 'localhost', 'betobal_bet', 'betobal_bet', '6MIqKubY' );
	
	//Module Config
	$blockchain_root = "https://blockchain.info/";
	$blockchain_receive_root = "https://api.blockchain.info/";
	$mysite_root = "http://vip90.sezar.world/gateway/";
	$secret = "8143847e-5798-4025-9e6e-3a0e1701998e";
	$my_xpub = "xpub6C7whYXEddK3Ls8Zhni4hTzxa2MLYoUEc3Go6gZJvVE7tQrgssqVvJ9XuVf4zeoZ5X2gcKXtPSF1TVv7UjZwYKWGeJ2R4YihZbPBcB2oC5s";
	$my_api_key = "67afc606-9b22-4408-b1a4-cdb02606c17f";
	$sezar_link	=	'http://sezar.world';
	
	//Functions
	function random_string()
	{
		$str = "ABCDEFGHIGKLMNOPQRSTUVWXYZ";
		$rand_str = "";
		for($i = 0; $i < 2; $i++)
		{
			$rand_str .= $str[mt_rand(0, strlen($str) - 1)];
		}
		$rand_str .= time();
		return $rand_str;
	}

	function sanitize_output ($var) {
		$var = htmlentities($var);
		return $var;
	}
	function getOnlinePrice( $tgju ) {

		$apiUrl = 'http://call.tgju.org/ajax.json';
		$priceName = $tgju;

		$ret = [
			'error'   => '',
			'price'   => 0
		];

		$data = file_get_contents( $apiUrl );
		if ( empty($data) ) $ret['error'] = 'Can not get data';

		else {
			$json = @json_decode( $data, true );
			$field = &$json['current'][$priceName];
			
			if ( empty($json) ) 
				$ret['error'] = 'Syntax error for API';
			
			elseif ( empty($json['current']) )
				$ret['error'] = 'Invalid JSON data received';
				
			elseif ( empty($field) || !is_array($field) )
				$ret['error'] = 'Content for ' . $priceName . ' not found';
				
			else {
				
				// Convert to integer
				$extract = intval( preg_replace('|[^0-9\-]|', '', $field['p']) );

				if ( empty($extract) ) $ret['error'] = 'Price was zero or not found';
				else $ret['price'] = $extract;
				
				// Also set the time stamp, if any
				if ( !empty($field['ts']) )
					$ret['ts'] = &$field['ts'];
				
			}
		}

		return $ret;

	}
	//price_dollar_rl
	$DollarP		= getOnlinePrice('price_dollar_rl');
	$DollarPrice	= $DollarP['price']/10;
	
	function SZROnlinePrice( $currency ) {
		switch( $currency ){
			case 'Dollar':
				$tgju_code	=	'price_dollar_rl';
				break;
			case 'euro':
				$tgju_code	=	'price_eur';
				break;
			case 'lir':
				$tgju_code	=	'price_try';
				break;
			case 'btc':
				$tgju_code	=	'crypto-bitcoin';
				break;
		}
		$CR		=	 getOnlinePrice($tgju_code);
		$CRCAL	=	 $CR['price']/10;
		return $CRCAL;
	}

?>
