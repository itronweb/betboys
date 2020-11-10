<?php

include 'include.php';

	$invoice_id = $_GET['invoice_id'];
	$transaction_hash = $_GET['transaction_hash'];
	$value_in_btc = $_GET['value'] / 100000000;

	$row_invoice	=	dbGetRow( 'invoices',       ['invoice_id'=>@$invoice_id] );
	$my_address 	= 	$row_invoice['address'];

if ($_GET['address'] != $my_address) {
    echo 'Incorrect Receiving Address';
  return;
}

if ($_GET['secret'] != $secret) {
  echo 'Invalid Secret';
  return;
}

if ($_GET['confirmations'] >= 3) {
  //Edit the invoice to Paid Status in database
	$amount = $value_in_btc * SZROnlinePrice('btc') * SZROnlinePrice('Dollar');
	dbWrite( 'transactions', ['trans_id'=>$invoice_id], ['price' => $amount] );
	dbWrite( 'invoices', ['invoice_id'=>$invoice_id], ['status'=>2, 'hash'=>$transaction_hash, 'value'=>$value_in_btc, 'price'=>$amount] );

  if($result) {
	   echo "*ok*"; 
  }
} else {
  //Waiting for confirmations
  //Edit Invoice to pending payment
	dbWrite( 'transactions', ['trans_id'=>$invoice_id], ['status' => 3] );
	dbWrite( 'invoices', ['invoice_id'=>$invoice_id], ['status'=>1, 'hash'=>$transaction_hash, 'value'=>$value_in_btc] );

   echo "Waiting for confirmations";
}

?>