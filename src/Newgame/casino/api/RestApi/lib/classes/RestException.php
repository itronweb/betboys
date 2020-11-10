<?php
/**
 * Created by PhpStorm.
 * User: ADMIN
 * Date: 01/05/2018
 * Time: 03:06 PM
 */

namespace lib\classes;

use Exception;

class RestException extends Exception
{
    public function __construct($code, $message = null) {
        parent::__construct($message, $code);
    }
}