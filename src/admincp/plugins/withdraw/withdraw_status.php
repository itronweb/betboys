<?php
require_once('../../checklogin.php'); 
// Load the common classes
require_once('../../includes/common/KT_common.php');
// Load the tNG classes
require_once('../../includes/tng/tNG.inc.php');
//start check access
// Make a transaction dispatcher instance
$id= $_GET['id'];
$status=GetSQLValueString($_GET['status'], "int");

$tNGs = new tNG_dispatcher("");

// Make unified connection variable
$conn_cn = new KT_connection($cn, $database_cn);


$query_Recordsetad1 = "SELECT pay_code,user_id,amount,status FROM `withdraw` WHERE `id` = '".GetSQLValueString($id, "int")."'";
$Recordad1 = mysqli_query($cn,$query_Recordsetad1) or die(mysqli_error($cn));
$row_Recordadd1 = mysqli_fetch_assoc($Recordad1);
$totalpayrecord=mysqli_num_rows($Recordad1);
$pay_code=$row_Recordadd1['pay_code'];


$query_Recordsetad1u_a = "SELECT cash FROM `users` WHERE `id` = '".$row_Recordadd1['user_id']."'";
$Recordad1u_a = mysqli_query($cn,$query_Recordsetad1u_a) or die(mysqli_error($cn));
$row_Recordadd1u_a = mysqli_fetch_assoc($Recordad1u_a);

$oldcashret=$row_Recordadd1u_a['cash']+$row_Recordadd1['amount'];
	if($status==1){
		$newcashret=$row_Recordadd1u_a['cash'];
		$transstatus=1;
	}elseif($status==0){
		$newcashret=$oldcashret;
		$transstatus=2;
	}




$upd_adm = new tNG_update($conn_cn);
$tNGs->addTransaction($upd_adm);

// Register triggers
$upd_adm->registerTrigger("STARTER", "Trigger_Default_Starter", 1, "GET", "id");

// Add columns
$upd_adm->setTable("`withdraw`");
$upd_adm->addColumn("status", "NUMERIC_TYPE", "VALUE", $status);
$upd_adm->addColumn("updated_at", "STRING_TYPE", "VALUE", date("Y-m-d H:i:s"));
$upd_adm->setPrimaryKey("id", "NUMERIC_TYPE", "VALUE", $id);
//if($status!=2){
$upd_trans = new tNG_update($conn_cn);
$tNGs->addTransaction($upd_trans);

// Register triggers
$upd_trans->registerTrigger("STARTER", "Trigger_Default_Starter", 2, "GET", "id");


// Add columns
$upd_trans->setTable("`transactions`");
$upd_trans->addColumn("status", "NUMERIC_TYPE", "VALUE", $transstatus);
$upd_trans->addColumn("updated_at", "STRING_TYPE", "VALUE", date("Y-m-d H:i:s"));
$upd_trans->setPrimaryKey("pay_code", "STRING_TYPE", "VALUE", $pay_code);

if($transstatus==2){
$insert_trans = new tNG_insert($conn_cn);
$tNGs->addTransaction($insert_trans);

// Register triggers
$insert_trans->registerTrigger("STARTER", "Trigger_Default_Starter", 3, "GET", "id");

// Add columns
$insert_trans->setTable("`transactions`");
$insert_trans->addColumn("status", "NUMERIC_TYPE", "VALUE", $transstatus);
$insert_trans->addColumn("pay_code", "STRING_TYPE", "VALUE", time().$row_Recordadd1['user_id']);
$insert_trans->addColumn("updated_at", "STRING_TYPE", "VALUE", date("Y-m-d H:i:s"));
$insert_trans->addColumn("created_at", "STRING_TYPE", "VALUE", date("Y-m-d H:i:s"));
$insert_trans->addColumn("cash", "STRING_TYPE", "VALUE", $newcashret);
$insert_trans->addColumn("user_id", "STRING_TYPE", "VALUE", $row_Recordadd1['user_id']);
$insert_trans->addColumn("description", "STRING_TYPE", "VALUE", "برگشت درخواست جایزه از طرف مدیر");
$insert_trans->addColumn("invoice_type", "STRING_TYPE", "VALUE", 4);
$insert_trans->addColumn("price", "STRING_TYPE", "VALUE", $row_Recordadd1['amount']);
$insert_trans->setPrimaryKey("id", "NUMERIC_TYPE");
}
$upd_cash = new tNG_update($conn_cn);
$tNGs->addTransaction($upd_cash);

// Register triggers
$upd_cash->registerTrigger("STARTER", "Trigger_Default_Starter", 3, "GET", "id");
$upd_cash->registerTrigger("END", "Trigger_Default_Redirect", 99, $_SERVER['HTTP_REFERER']);

// Add columns
$upd_cash->setTable("`users`");
$upd_cash->addColumn("cash", "STRING_TYPE", "VALUE", $newcashret);
$upd_cash->addColumn("updated_at", "STRING_TYPE", "VALUE", date("Y-m-d H:i:s"));
$upd_cash->setPrimaryKey("id", "STRING_TYPE", "VALUE", $row_Recordadd1['user_id']);
		


// Execute all the registered transactions
$tNGs->executeTransactions();
//end check access

echo $tNGs->getErrorMsg();

 ?>