<?php
chdir("../../../");
require("db.php");

$user_id = $_POST['uid'];
$lang = $_POST['language'];

$db = new DB();

$user_info = $db->select( 'users', '*', 'id', $user_id );

$obj = new stdClass();
$auth = new stdClass();
$data = new stdClass();

$obj->code = 200;
$obj->result = "ok";


$auth->aff = 0;
$auth->amount = $user_info['cash'];
$auth->bonus = $user_info['cash'];
$auth->channel = "0";
$auth->group = "0";
$auth->id = $user_info['id'];
$auth->language = $lang;
$auth->level = 1;
$auth->login = true;
$auth->name = $user_info['username'];
$auth->photo = "";
$auth->status = "active";
$auth->verification = "0";

$data->address = "ws://136.243.255.205:4000";
$data->from = "Go2Bet";
//$data->address = "wss://88.99.77.163:1337";
//$auth->getDatabase();
$data->bet = 100;
$data->bets = [];
$data->data = [];
$data->ip = "0.0.0.0";
$data->javascript = "";
$data->port = 100;
$data->token = "afegergregsgs!235sgWE";
$data->uid = $user_info['id'];

$obj->auth = $auth;
$obj->data = $data;

echo json_encode($obj);



?>