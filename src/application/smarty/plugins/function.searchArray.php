<?php

/*
 * Smarty plugin
 * -------------------------------------------------------------
 * File:     function.jdate.php
 * Type:     function
 * Name:     jdate
 * Purpose:  georgian date to jalali 
 * -------------------------------------------------------------
 */

function smarty_function_searchArray ( $params , Smarty_Internal_Template $template ) {
    $key = $params['key'];
    foreach ( $params['array'] as $index => $val ) {
        if ( $val->$key == $params['val'] ) {
            return $index;
        }
    }
    return null;
}
