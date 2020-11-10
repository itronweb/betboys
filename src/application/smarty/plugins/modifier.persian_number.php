<?php

/*
 * Smarty plugin
 * -------------------------------------------------------------
 * File:     modifier.persian_number.php
 * Type:     modifier
 * Name:     con
 * Purpose:  convert numbers to persian character
 * -------------------------------------------------------------
 */

function smarty_modifier_persian_number($number) {
    $number = str_replace(array('0', '1', '2', '3', '4', '5', '6', '7', '8', '9'), array('۰', '۱', '۲', '۳', '۴', '۵', '۶', '۷', '۸', '۹'), $number);
    return $number ;
}
