<?php
/**
 * User: Mohammad Moradi
 * Date: 28/04/2018
 * Time: 03:15 PM
 */

namespace lib\classes;

require_once (__DIR__ . '/CorsHeader.php');
require_once (__DIR__ . '/Authentication.php');
require_once (__DIR__ . '/Crypto.php');
require_once (__DIR__ . '/RestFormat.php');
require_once (__DIR__ . '/RestException.php');

use Exception;

class RestService
{
    public $CorsArray = false;
    public $method;
    public $Authorization;
    public $Authenticated;
    public $responseArray;
    public $responseType;
    private $ClientContentType;


    public function __construct()
    {
        $this->getFormat();
        $this->responseType = RestService::getFormat();
    }

    public function __destruct()
    {
        //cached and map
    }


    private function corsHandler()
    {
        try{
            new \lib\classes\CorsHeader($this->CorsArray);

            if ($this->CorsArray === false)
            {
                throw new RestException('403', RestService::$codes['403']);
            }
        }
        catch (RestException $ex)
        {
            return $ex->getMessage();
        }
    }

    public function authHandler()
    {
         if ($this->Authorization === true){
             $res = new \lib\classes\Authentication();
             return $res->getBearerToken();
         }else{
             $this->Authorization = false;
         }
    }

    public function Processing()
    {
        $this->ClientContentType = $this->getFormat();

        if($this->corsHandler() !== RestService::$codes['403']) {
            $this->CorsStatus = true;
            if (in_array(strtoupper($this->getMethod()), $this->method)) {
                if ($this->Authenticated === true || $this->Authorization === false) {
                    if ($this->ClientContentType == \lib\classes\RestFormat::JSON) {
                        return $Data = file_get_contents("php://input");
//                        $crypto = new \lib\classes\Crypto();
//                       $crypto->en = $Data;
                        return $crypto->cr_decode();
                    } elseif ($this->ClientContentType == \lib\classes\RestFormat::PLAIN || $this->ClientContentType == \lib\classes\RestFormat::HTML) {
//                    $Data = file_get_contents("php://input");
//                    return $Data;
                    }
                } else {
                    $this->Authenticated = false;
                }
            } else {
                $this->method = false;
            }
        } else{
            $this->CorsArray = false;
        }
    }

    public function RseponseToC()
    {
        try {
            if ($this->CorsArray === false)
                throw new RestException('403', RestService::$codes['403']);
            elseif($this->method === false)
                throw new RestException('405', RestService::$codes['405']);
            elseif ($this->Authenticated === false)
                throw new RestException('401', RestService::$codes['401']);
            else{
                if(!in_array('GET', $this->method ) && $this->responseType ==  \lib\classes\RestFormat::JSON)
                {
                    return $res = json_encode($this->responseArray, JSON_UNESCAPED_UNICODE);
//                    $crypto = new \lib\classes\Crypto();
//                    $crypto->r = $res;
//                    $crypto->n = time();
//                    return $crypto->cr_encode();
                }
                elseif (in_array('GET', $this->method) && $this->responseType ==  \lib\classes\RestFormat::PLAIN){
                    return json_encode($this->responseArray, JSON_UNESCAPED_UNICODE);
                }
            }

        }
        catch (RestException $ex)
        {
            return $ex->getMessage();
        }


    }

    static public function getFormat()
    {
        $cf = $_SERVER['CONTENT_TYPE'];
        switch ($cf)
        {
            case \lib\classes\RestFormat::PLAIN:
                $format = \lib\classes\RestFormat::PLAIN;
                break;
            case \lib\classes\RestFormat::JSON:
                $format = \lib\classes\RestFormat::JSON;
                break;
            case \lib\classes\RestFormat::HTML:
                $format = \lib\classes\RestFormat::HTML;
                break;
            case \lib\classes\RestFormat::XML:
                $format = \lib\classes\RestFormat::XML;
                break;
            default:
                $format = \lib\classes\RestFormat::PLAIN;
        }
        return $format;
    }
    private function getMethod()
    {
        $method = $_SERVER['REQUEST_METHOD'];
        $override = isset($_SERVER['HTTP_X_HTTP_METHOD_OVERRIDE']) ? $_SERVER['HTTP_X_HTTP_METHOD_OVERRIDE'] : (isset($_GET['method']) ? $_GET['method'] : '');
        if ($method == 'POST' && strtoupper($override) == 'PUT') {
            $method = 'PUT';
        } else if ($method == 'POST' && strtoupper($override) == 'DELETE') {
            $method = 'DELETE';
        } else if ($method == 'POST' && strtoupper($override) == 'PATCH') {
            $method = 'PATCH';
        }
        return $method;
    }

     static public $codes = array(
        '100' => 'Continue',
        '200' => 'OK',
        '201' => 'Created',
        '202' => 'Accepted',
        '203' => 'Non-Authoritative Information',
        '204' => 'No Content',
        '205' => 'Reset Content',
        '206' => 'Partial Content',
        '300' => 'Multiple Choices',
        '301' => 'Moved Permanently',
        '302' => 'Found',
        '303' => 'See Other',
        '304' => 'Not Modified',
        '305' => 'Use Proxy',
        '307' => 'Temporary Redirect',
        '400' => 'Bad Request',
        '401' => 'Unauthorized',
        '402' => 'Payment Required',
        '403' => 'Forbidden',
        '404' => 'Not Found',
        '405' => 'Method Not Allowed',
        '406' => 'Not Acceptable',
        '409' => 'Conflict',
        '410' => 'Gone',
        '411' => 'Length Required',
        '412' => 'Precondition Failed',
        '413' => 'Request Entity Too Large',
        '414' => 'Request-URI Too Long',
        '415' => 'Unsupported Media Type',
        '416' => 'Requested Range Not Satisfiable',
        '417' => 'Expectation Failed',
        '500' => 'Internal Server Error',
        '501' => 'Not Implemented',
        '503' => 'Service Unavailable'
    );
}