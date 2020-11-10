<?php
/*
 * Smarty plugin
 * -------------------------------------------------------------
 * File:     function.site_url.php
 * Type:     function
 * Name:     assign
 * Purpose:  assign a value to a template variable
 * -------------------------------------------------------------
 */
function smarty_function_site_url($params, Smarty_Internal_Template $template)
{
    return base_url();
}