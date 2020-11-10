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

function smarty_modifier_fa ( $text ) {
    if ( $text === '12' ) {
        $text = $text . '::';
    }
    if ( CI::$APP->lang->line(( string ) $text) )
        return CI::$APP->lang->line(( string ) $text);
    else
        return ( string ) $text;
}
