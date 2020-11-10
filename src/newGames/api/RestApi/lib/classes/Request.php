<?php
/**
 * Created by PhpStorm.
 * User: ADMIN
 * Date: 28/04/2018
 * Time: 02:18 PM
 */

namespace lib\classes;


class Request
{
     private $requestURI ='';



     public

     function  __construct(){
         $this->requestURI =  explode('/' ,$_SERVER['REQUEST_URI']);
         return end($this->requestURI);
     }


}