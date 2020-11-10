<?php

defined('BASEPATH') OR exit('No direct script access allowed');

/*

 * 
  |--------------------------------------------------------------------------
  | Pagination Class settings
  |--------------------------------------------------------------------------
  |
  | the default configs of pagination class
  | you can change these settings in your controller
  | */

$config["uri_segment"] = 3;
// Number of items you intend to show per page.
$config["per_page"] = 50;

//The number of "digit" links you would like before and after the selected page number. 
//For example, the number 2 will place two digits on either side, 
//as in the example links at the very top of this page.
$config['num_links'] = 3;

$config['first_link'] = false;
$config['first_tag_open'] = ' ';
$config['first_tag_close'] = '';

$config['last_link'] = false;
$config['last_tag_open'] = '';
$config['last_tag_close'] = '';

$config['num_tag_open'] = '';
$config['num_tag_close'] = '';
// Open tag for CURRENT link.
$config['cur_tag_open'] = '<a href="#" class="active2">';
// Close tag for CURRENT link.
$config['cur_tag_close'] = '</a>';
// Open tag for next link.
$config['next_tag_open'] = '<a>';
// Close tag for next link.
$config['next_tag_close'] = '</a>';
// By clicking on performing NEXT pagination.
$config['next_link'] = ' <i class="fa fa-chevron-right"></i> ';
// By clicking on performing PREVIOUS pagination.
$config['prev_link'] = ' <i class="fa fa-chevron-left"></i> ';
// Open tag for prev link.
$config['prev_tag_open'] = '';
// Close tag for prev link.
$config['prev_tag_close'] = '';

// Set base_url for every links
// $page = ($this->uri->segment(4)) ? $this->uri->segment(4) : 0;
//$config["base_url"] = base_url() . "backoffice/controller/index";
// Set total rows in the result set you are creating pagination for.
//$config["total_rows"] = $total_row;
// Use pagination number for anchor URL.
$config['use_page_numbers'] = TRUE;

//Set that how many number of pages you want to view.
//$config['num_links'] = $total_row;


// To initialize "$config" array and set to pagination library.
//$this->pagination->initialize($config);

// Create link.
//$this->pagination->create_links();
