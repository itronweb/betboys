<?php

/*
 * Smarty plugin
 * -------------------------------------------------------------
 * File:     function.summary.php
 * Type:     function
 * Name:     assign
 * Purpose:  summary of a given text with limited character
 * -------------------------------------------------------------
 */

function smarty_function_summary($params, Smarty_Internal_Template $template) {
    return mb_substr(trim(str_replace('<br>', '<br />',
                                    strip_tags($params['text'], '<br>'))), 0,
                    $params['limit']) . '...';
}
