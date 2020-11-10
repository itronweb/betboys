<?php
require_once('../../config.php');
error_reporting(E_ALL);
ini_set('display_errors', 'On');
$today_date = date('Y-m-d', strtotime('+0 day'));
$tommorow_1_date = date('Y-m-d', strtotime('+1 day'));
$tommorow_2_date = date('Y-m-d', strtotime('+2 day'));
$tommorow_3_date = date('Y-m-d', strtotime('+3 day'));
$tommorow_4_date = date('Y-m-d', strtotime('+4 day'));
$tommorow_5_date = date('Y-m-d', strtotime('+5 day'));
$tommorow_6_date = date('Y-m-d', strtotime('+6 day'));
$upcoming_today = file_get_contents('https://soccer.sportmonks.com/api/v2.0/fixtures/between/' . $today_date . '/' . $today_date . '/?api_token=' . API_KEY . '&include=league,visitorTeam,localTeam,odds&tz=' . TIMEZONE);
$upComing_tommorow_0_date = file_get_contents('https://soccer.sportmonks.com/api/v2.0/fixtures/between/' . $tommorow_0_date . '/' . $tommorow_0_date . '/?api_token=' . API_KEY . '&include=league,visitorTeam,localTeam,odds&tz=' . TIMEZONE);
$upComing_tommorow_1_date = file_get_contents('https://soccer.sportmonks.com/api/v2.0/fixtures/between/' . $tommorow_1_date . '/' . $tommorow_1_date . '/?api_token=' . API_KEY . '&include=league,visitorTeam,localTeam,odds&tz=' . TIMEZONE);
$upComing_tommorow_2_date = file_get_contents('https://soccer.sportmonks.com/api/v2.0/fixtures/between/' . $tommorow_2_date . '/' . $tommorow_2_date . '/?api_token=' . API_KEY . '&include=league,visitorTeam,localTeam,odds&tz=' . TIMEZONE);
$upComing_tommorow_3_date = file_get_contents('https://soccer.sportmonks.com/api/v2.0/fixtures/between/' . $tommorow_3_date . '/' . $tommorow_3_date . '/?api_token=' . API_KEY . '&include=league,visitorTeam,localTeam,odds&tz=' . TIMEZONE);
$upComing_tommorow_4_date = file_get_contents('https://soccer.sportmonks.com/api/v2.0/fixtures/between/' . $tommorow_4_date . '/' . $tommorow_4_date . '/?api_token=' . API_KEY . '&include=league,visitorTeam,localTeam,odds&tz=' . TIMEZONE);
$upComing_tommorow_5_date = file_get_contents('https://soccer.sportmonks.com/api/v2.0/fixtures/between/' . $tommorow_5_date . '/' . $tommorow_5_date . '/?api_token=' . API_KEY . '&include=league,visitorTeam,localTeam,odds&tz=' . TIMEZONE);
$upComing_tommorow_6_date = file_get_contents('https://soccer.sportmonks.com/api/v2.0/fixtures/between/' . $tommorow_6_date . '/' . $tommorow_6_date . '/?api_token=' . API_KEY . '&include=league,visitorTeam,localTeam,odds&tz=' . TIMEZONE);
$other_site_api = file_get_contents('http://b45b.com/sport/live');
$_API_Path = APPPATH . 'logs' . DS . 'API' . DS;
file_put_contents($_API_Path . 'upComing_0.txt', $upcoming_today);
file_put_contents($_API_Path . 'upComing_1.txt', $upComing_tommorow_1_date);
file_put_contents($_API_Path . 'upComing_2.txt', $upComing_tommorow_2_date);
file_put_contents($_API_Path . 'upComing_3.txt', $upComing_tommorow_3_date);
file_put_contents($_API_Path . 'upComing_4.txt', $upComing_tommorow_4_date);
file_put_contents($_API_Path . 'upComing_5.txt', $upComing_tommorow_5_date);
file_put_contents($_API_Path . 'upComing_6.txt', $upComing_tommorow_6_date);
file_put_contents($_API_Path . 'other_site.txt', $other_site_api);
# ---------------------------------------------------------------------------------- #
?>
