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

function smarty_modifier_con() {
    return implode(func_get_args());
}