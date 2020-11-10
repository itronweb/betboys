<?php

if ( !defined('BASEPATH') )
    exit('No direct script access allowed');
/*
 *  ======================================= 
 * Soccerama library for codeigniter
 *  
 *  @license   Protected 
 *  @since     1395-06 
 *  
 *  
 *  =======================================  
 */
require_once APPPATH . "modules/bets/third_party/soccerama/src/Saeednavaro/Soccerama/BaseSoccerama.php";

class soccerama extends BaseSoccerama {

    public function __construct () {
        parent::__construct();
        error_reporting(E_ALL);
    }

}
