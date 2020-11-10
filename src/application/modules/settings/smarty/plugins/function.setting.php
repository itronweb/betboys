<?php

/*
 * Smarty plugin
 * -------------------------------------------------------------
 * File:     function.setting.php
 * Type:     function
 * Name:     assign
 * Purpose:  assign a value to a template variable
 * -------------------------------------------------------------
 */

function smarty_function_setting($params, Smarty_Internal_Template $template) {
    if (!key_exists('name', $params))
        return false;
    return CI::$APP->plugins->locate('settings:get_param', $params, null, null);
}
