<?php
/**
 * Created by PhpStorm.
 * User: ADMIN
 * Date: 28/04/2018
 * Time: 01:23 PM
 */

namespace lib\classes;


class Authentication
{
    private $_Headers;

    /**
     * Get hearder Authorization
     * */
    public function __construct()
    {
        $this->_Headers = null;
        if (isset($_SERVER['Authorization'])) {
            $this->_Headers = trim($_SERVER["Authorization"]);
        }
        else if (isset($_SERVER['HTTP_AUTHORIZATION'])) { //Nginx or fast CGI
            $this->_Headers = trim($_SERVER["HTTP_AUTHORIZATION"]);
        } elseif (function_exists('apache_request_headers')) {
            $requestHeaders = apache_request_headers();
            // Server-side fix for bug in old Android versions (a nice side-effect of this fix means we don't care about capitalization for Authorization)
            $requestHeaders = array_combine(array_map('ucwords', array_keys($requestHeaders)), array_values($requestHeaders));
            //print_r($requestHeaders);
            if (isset($requestHeaders['Authorization'])) {
                $this->_Headers = trim($requestHeaders['Authorization']);
            }
        }
        return $this->_Headers;
    }
    /**
     * get access token from header
     * */
    public function getBearerToken()
    {
        $headers = $this->_Headers;
        // HEADER: Get the access token from the header
        if (!empty($headers)) {
            if (preg_match('/Bearer\s(\S+)/', $headers, $matches)) {
                return $matches[1];
            }
        }
        return null;
    }
}