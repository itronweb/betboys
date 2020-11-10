<?php
/**
 * Created by PhpStorm.
 * User: ADMIN
 * Date: 01/05/2018
 * Time: 09:29 AM
 */

namespace lib\classes;


class RestFormat
{
    const PLAIN = 'text/plain';
    const HTML  = 'text/html';
    const JSON  = 'application/json';
    const XML   = 'application/xml';

    /** @var array */
    static public $formats = array(
        'plain' => RestFormat::PLAIN,
        'txt'   => RestFormat::PLAIN,
        'html'  => RestFormat::HTML,
        'json'  => RestFormat::JSON,
        'xml'   => RestFormat::XML,
    );
}