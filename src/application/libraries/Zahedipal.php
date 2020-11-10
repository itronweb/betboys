<?php

if ( !defined('BASEPATH') )
    exit('No direct script access allowed');

/**
 * Description of Zahedipal
 *
 */
class Zahedipal {

    public function __construct () {
        error_reporting(E_ALL);
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

}
