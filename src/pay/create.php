<?php

//Proxy to the api/receive method in order to not reveal the callback URL
include 'include.php';
 
$invoice_id = sanitize_output($_GET['invoice_id']);
// $row_token = dbGetRow('tokens', ['token'=>sanitize_output($_GET['token'])]); 
$user_id	= sanitize_output($_GET['user_id']);
$callback_url = $mysite_root . "callback.php?invoice_id=" . $invoice_id . "&secret=" . $secret;

$price_in_usd = 0;
$price_in_btc = file_get_contents($blockchain_root . "tobtc?currency=USD&value=" . $price_in_usd);

$resp = file_get_contents($blockchain_receive_root . "v2/receive?key=" . $my_api_key . "&callback=" . urlencode($callback_url) . "&xpub=" . $my_xpub . "&gap_limit=300");
$response = json_decode($resp);

//ADD the invoice in database
	dbAdd( 'transactions' , ['trans_id'=>$invoice_id , 'invoice_type'=>2366, 'user_id'=>$user_id, 'description'=>'افزایش اعتبار توسط بیت کوین', 'status'=>0 ] );
	$result = dbAdd  ( 'invoices', ['invoice_id'=>$invoice_id, 'user_id'=>$user_id, 'price'=>$price_in_usd,'price_btc'=>$price_in_btc, 'date'=>time(), 'address'=>$response->address, 'status'=>0] );
// Status Field Details
// Number 0 	== Pending
// Number 1 	== Waiting
// Number 2		== Paid
// Number -1	== Not Paid

print json_encode(array('input_address' => $response->address ));


?>