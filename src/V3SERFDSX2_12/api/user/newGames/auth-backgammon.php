<?php
chdir("../../../");
include("db.php");

$user_id = $_POST['uid'];
$lang = $_POST['language'];

$db = new DB();

$user_info = $db->select( 'users', '*', 'user_id', $user_id );

$obj = new stdClass();
$auth = new stdClass();
$data = new stdClass();

$obj->code = 200;
$obj->result = "ok";


$auth->aff = 0;
$auth->amount = $user_info['user_point'];
$auth->bonus = $user_info['user_box'];
$auth->channel = "0";
$auth->group = "0";
$auth->id = $user_info['user_id'];
$auth->language = $lang;
$auth->level = 1;
$auth->login = true;
$auth->name = $user_info['user_username'];
$auth->photo = "";
$auth->status = "active";
$auth->verification = "0";
//$auth->getValue = 10000;
$data->address = "wss://".$_SERVER['HTTP_HOST'].":3001";
$data->from = "Go2Bet";
//$data->address = "wss://78.47.147.101:8443";
$data->bet = 100;
$data->bets = [];
$data->data = [];
//$data->
$data->ip = "0.0.0.0";
$data->javascript = "";
$data->port = 100;
$data->token = "O9AcdEasf35h67jh5";
$data->uid = $user_info['user_id'];

$obj->auth = $auth;
$obj->data = $data;

echo json_encode($obj);



?>

