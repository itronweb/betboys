<?php

if ( !defined('BASEPATH') )
    exit('No direct script access allowed');

/**
 * Description of Zahedipal
 *
 */
class Pay {

//	public $api_key = "10a88223cfa9051f666858a4ff1d3bcf";
//	public $zarrinPal_key = "4fa5977c-4806-11e8-8ec8-005056a205be";

    public function __construct () {
        error_reporting(E_ALL);
    }

	function request($amount,$redirect,$api_key,$domain){

		try{

			$ch = curl_init();
//			$api_key = $this->api_key;
			curl_setopt($ch,CURLOPT_URL,"$domain/invoice/request");
			curl_setopt($ch,CURLOPT_POSTFIELDS,"api_key=$api_key&amount=$amount&return_url=$redirect");
			curl_setopt($ch,CURLOPT_SSL_VERIFYPEER, FALSE);
			curl_setopt($ch,CURLOPT_RETURNTRANSFER,true);

			$res = curl_exec($ch);
			curl_close($ch);
			if( !$res )
				return false;
		}
		catch ( Exception $e ){
			return false;
		}
        return $res;
    }

	function check($inv_key,$api_key,$domain){
        $ch = curl_init();
       	curl_setopt($ch,CURLOPT_URL,$domain.'/invoice/check/'.$inv_key);
        curl_setopt($ch,CURLOPT_POSTFIELDS,"api_key=$api_key");
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
        curl_setopt($ch,CURLOPT_RETURNTRANSFER,true);
        $res = curl_exec($ch);
        curl_close($ch);
        return $res;
    }
    function zpCurl ( $data = array() , $verify = false ) {
        try {
            $r = ($verify == true) ? 'verify' : 'create';

            $ch = curl_init();
            curl_setopt($ch , CURLOPT_URL , "http://panel.mihanwebpay.com/api/{$r}/");
            curl_setopt($ch , CURLOPT_POSTFIELDS , http_build_query($data));
            curl_setopt($ch , CURLOPT_SSL_VERIFYPEER , 0);
            curl_setopt($ch , CURLOPT_RETURNTRANSFER , 1);
            curl_setopt($ch , CURLOPT_CONNECTTIMEOUT , 20);
            $res = curl_exec($ch);
            curl_close($ch);
            if ( !$res )
                return false;
        } catch ( Exception $e ) {
            return false;
        }
        return $res;
    }

    function zpErr ( $res = '' ) {
        switch ( $res ) {
            case '-1' : $prompt = "مبلغ نمیتواند خالی باشد.";
                break;
            case '-2' : $prompt = "کد پین درگاه نمیتواند خالی باشد.";
                break;
            case '-3' : $prompt = "callback نمیتواند خالی باشد.";
                break;
            case '-4' : $prompt = "مبلغ را به صورت عددی وارد کنید.";
                break;
            case '-5' : $prompt = "مبلغ باید بزرگتر از 100 تومان باشد.";
                break;
            case '-6' : $prompt = "کد پین درگاه اشتباه است.";
                break;
            case '-7' : $prompt = "آی پی درگاه اشتباه است.";
                break;
            case '-8' : $prompt = "شماره تراکنش نمیتواند خالی باشد.";
                break;
            case '-9' : $prompt = "تراکنش مورد نظر پیدا نشد.";
                break;
            case '-10' : $prompt = "کد پین درگاه با درگاه تراکنش مطابقت ندارد.";
                break;
            case '-11' : $prompt = "مبلغ وارد شده با مبلغ تراکنش برابری ندارد.";
                break;
            case '-12' : $prompt = "بانک وارد شده اشتباه میباشد.";
                break;
            default : $prompt = "خطای نامشخص!";
        }
        $err = "<meta charset=UTF-8>";
        $err .= "خطا ({$res}) : {$prompt}";
        return $err;
    }

	function zarrinpall_request($data,$redirect){

		$client = new SoapClient('https://crown.sezarco.ir/pg/services/WebGate/wsdl', ['encoding' => 'UTF-8']);

		$result = $client->PaymentRequest(
		[
		'MerchantID' => $this->zarrinPal_key,
		'Amount' => $data['amount'],
		'Description' => $data['description'],
		'Email' => '',
		'Mobile' => '',
		'CallbackURL' => $redirect,
		]
		);
		if ($result->Status == 100)
			return $result;
		return false;


	}

	function zarrinpall_check($data,$amount){

		if ($data['Status'] == 'OK') {

			$client = new SoapClient('https://crown.sezarco.ir/pg/services/WebGate/wsdl', ['encoding' => 'UTF-8']);

			$result = $client->PaymentVerification(
			[
			'MerchantID' => $this->zarrinPal_key,
			'Authority' => $data['Authority'],
			'Amount' => $amount,
			]
			);

			return $result;

		} else {
			return false;
		}
	}
	

}
