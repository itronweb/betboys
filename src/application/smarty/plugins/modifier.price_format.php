<?php

/*
 * Smarty plugin
 * -------------------------------------------------------------
 * File:     modifier.con.php
 * Type:     modifier
 * Name:     con
 * Purpose:  concatenation strings
 * -------------------------------------------------------------
 */

function smarty_modifier_price_format($number) {
    $number = str_replace(array('0', '1', '2', '3', '4', '5', '6', '7', '8', '9'), array('۰', '۱', '۲', '۳', '۴', '۵', '۶', '۷', '۸', '۹'), number_format($number));
    return $number . ' تومان';
}
