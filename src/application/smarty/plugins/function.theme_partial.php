<?php

/*
 * Smarty plugin
 * -------------------------------------------------------------
 * File:     function.theme_partial.php
 * Type:     function
 * Name:     assign
 * Purpose:  assign a value to a template variable
 * -------------------------------------------------------------
 */

function smarty_function_theme_partial($params, Smarty_Internal_Template $template) {
    if(!empty($params))
        return CI::$APP->smart->theme_partial() . '/' . $params['file'] . '.tpl';
    else
        return CI::$APP->smart->theme_partial();
        
}
