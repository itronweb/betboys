<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

/**
 * Menu Navigation model
 * 
 * @author		Liran Tal <liran.tal@gmail.com>
 * @package		daloRADIUS
 * @subpackage	Menu Navigation module for CodeIgniter
 * @copyright	GPLv2
 *
 */
class Slider extends \Illuminate\Database\Eloquent\Model {

    /**
     *
     * @var string 
     */
    protected $table = "slider_images";
    protected $guarded = [''];

}
