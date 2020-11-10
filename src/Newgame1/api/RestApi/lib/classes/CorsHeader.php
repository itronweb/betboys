<?php

/**
 * Created by PhpStorm.
 * User: Mohammad Moradi
 * Date: 28/04/2018
 * Time: 08:31 PM
 */

namespace lib\classes;


class CorsHeader
{
    public $allowedOrigin ;
    private $AllowMethods ;
    private $AllowHeaders ;
    private $AllowMaxAge ;
    private $MaxAge ;
    private $AllowCredentials;
    private $ExposeHeaders;
    private $ContentType;

    public function __construct($config = false)
    {
        if(is_array($config)){
            foreach($config as $key => $val){
                if($key == 'AllowOrigin'){
                    $this->setAllowOrigin($val);
                }
                elseif($key == 'MaxAge'){
                    $this->setMaxAge($val);
                }
                elseif($key == 'AllowCredentials'){
                    $this->setAllowCredentials($val);
                }
                elseif($key == 'ExposeHeaders'){
                    $this->setExposeHeaders($val);
                }
                elseif($key == 'AllowMethods'){
                    $this->setAllowMethods($val);
                }
                elseif($key == 'AllowHeaders'){
                    $this->setAllowHeaders($val);
                }
                elseif ($key == 'ContentType'){
                    $this->setContentType($val);
                }
                else{
                    //ignore anything else
                }

            }
        }
    }

    private function setAllowOrigin($url = false) {
        $AlloWOr = '';
        $co = ',';
        if( $url === false){
            $this->allowedOrigin = false;
        }
        else{
            if(is_array($url)) {
                if(in_array("*", $url)) $co = '';
                $this->allowedOrigin = $url;
                foreach ($this->allowedOrigin as $Orig) {
                    $AlloWOr .= "$Orig"."$co";
                }
                header("Access-Control-Allow-Origin: $AlloWOr");
            }
        }
    }

    private function setContentType($ct = false)
    {
        if($ct != false){
            header("Content-Type: $ct");
        }
        else
        {
            $this->ContentType = false;
        }
  }
    private function setMaxAge($seconds = false)
    {

        if($seconds === false){
            $this->MaxAge = false;
        }
        elseif(is_int($seconds)){
            header("Access-Control-Max-Age: $seconds");
        }
        else{
            $this->MaxAge = false;
        }
    }

    private function setAllowCredentials($allowed = false)
    {
        if(is_bool($allowed)){
            header("Access-Control-Allow-Credential: $allowed");
        }
        else{
            $this->AllowCredentials = false;
        }
    }

    private function setExposeHeaders($headers = false)
    {
        $expose_headers_header ='';
        if(is_array($headers)){
            foreach($headers as $header){
                $this->ExposeHeaders[] = $header;
            }
            $this->ExposeHeaders = array_unique($this->ExposeHeaders);
            foreach($this->ExposeHeaders as $expose){
                $expose_headers_header .= strtolower($expose) . ", ";
            }

            $expose_headers_header = rtrim($expose_headers_header, ',');

           header("Access-Control-Expose-Headers: $expose_headers_header");
        }
        else{
            $this->ExposeHeaders = false;
        }
    }

    private function setAllowMethods($methods = false){
        $allow_methods_header = '';
        if(is_array($methods)){
            $this->AllowMethods = $methods;
            $this->AllowMethods = array_unique($this->AllowMethods);
        }
        else{
            $this->AllowMethods = true;

        }
        if($this->AllowMethods === false){
            return false;
        }
        elseif($this->AllowMethods === true){
            $response["Access-Control-Allow-Methods"] = $_SERVER['HTTP_ACCESS_CONTROL_REQUEST_METHOD'];
        }
        elseif(is_array($this->AllowMethods)){
            foreach($this->AllowMethods as $hdr){
                $allow_methods_header .= "$hdr, ";
            }

            $allow_methods_header = rtrim($allow_methods_header, ',');
            header("Access-Control-Allow-Methods: $allow_methods_header");
        }
        else{
            return false;
        }
    }

    private function setAllowHeaders($headers = false){
        $allow_headers_header = '';
        if(is_array($headers)){
            foreach($headers as $header){
                $this->AllowHeaders[] = strtolower($header);
            }
            $this->AllowHeaders = array_unique($this->AllowHeaders);
            foreach($this->AllowHeaders as $hdr){
                $allow_headers_header .= strtolower($hdr) . ", ";
            }

            $allow_headers_header = rtrim($allow_headers_header, ',');
            header("Access-Control-Allow-Headers: $allow_headers_header");
        }
        else{
            $this->AllowHeaders = true;
        }
    }
}

?>