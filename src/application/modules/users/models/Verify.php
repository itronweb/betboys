<?php

    if (!defined('BASEPATH'))
        exit('No direct script access allowed');

    /**
     * Verify model
     * 
     * @author		AQAFree <aqafree@gmail.com>
     * @package		daloRADIUS
     * @subpackage	Verify module for CodeIgniter
     * @copyright	GPLv2
     *
     */
    class Verify extends \Illuminate\Database\Eloquent\Model {

        /**
         *
         * @var string 
         */
        protected $table = "verify";
        protected $guarded = [''];

    }
