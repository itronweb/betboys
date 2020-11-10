<?php require_once('../../checklogin.php'); 
// Load the common classes
require_once('../../includes/common/KT_common.php');
// Load the tNG classes
require_once('../../includes/tng/tNG.inc.php');
//start check access
// Make a transaction dispatcher instance
$id= decrypt($_GET['id'],session_id()."paym");
$status=GetSQLValueString($_GET['status'], "int");
$tNGs = new tNG_dispatcher("");
// Make unified connection variable
$query_Recordsetad1 = "SELECT user_id,price,status FROM `transactions` WHERE `id` = '".GetSQLValueString($id, "int")."'";
$Recordad1 = mysqli_query($cn,$query_Recordsetad1) or die(mysqli_error($cn));
$row_Recordadd1 = mysqli_fetch_assoc($Recordad1);
$user_id=$row_Recordadd1['user_id'];

$query_recordusers = "SELECT cash FROM `users` WHERE `id` = '".GetSQLValueString($user_id, "int")."'";
$recordusers= mysqli_query($cn,$query_recordusers) or die(mysqli_error($cn));
$row_recordusers = mysqli_fetch_assoc($recordusers);

$oldstatus=$row_Recordadd1['status'];
if($oldstatus==1){
	if($status==2 or $status==0){
	$cash=$row_recordusers['cash']-$row_Recordadd1['price'];
	}
}elseif($oldstatus==0){
	if($status==1){
	$cash=$row_recordusers['cash']+$row_Recordadd1['price'];
	}elseif($status==2){
	$cash=$row_recordusers['cash'];
	}
}elseif($oldstatus==2){
	if($status==1){
	$cash=$row_recordusers['cash']+$row_Recordadd1['price'];
	}elseif($status==0){
	$cash=$row_recordusers['cash'];
	}
}
//if($status==1){
//$cash=$row_recordusers['cash']+$row_Recordadd1['price'];
//}elseif($status==0){
//$cash=$row_recordusers['cash']-$row_Recordadd1['price'];	
//}


$conn_cn = new KT_connection($cn, $database_cn);
$upd_adm = new tNG_update($conn_cn);
$tNGs->addTransaction($upd_adm);
// Register triggers
$upd_adm->registerTrigger("STARTER", "Trigger_Default_Starter", 1, "GET", "id");

// Add columns
$upd_adm->setTable("`transactions`");
$upd_adm->addColumn("status", "NUMERIC_TYPE", "VALUE", $status);
$upd_adm->addColumn("updated_at", "STRING_TYPE", "VALUE", date("Y-m-d H:i:s"));
$upd_adm->setPrimaryKey("id", "NUMERIC_TYPE", "VALUE", $id);
// Execute all the registered transactions

$upd_cash = new tNG_update($conn_cn);
$tNGs->addTransaction($upd_cash);
// Register triggers
$upd_cash->registerTrigger("STARTER", "Trigger_Default_Starter", 1, "GET", "id");
$upd_cash->registerTrigger("END", "Trigger_Default_Redirect", 99, "payment_list.php?cat=2");
// Add columns
$upd_cash->setTable("`users`");
$upd_cash->addColumn("cash", "STRING_TYPE", "VALUE", $cash);
$upd_cash->setPrimaryKey("id", "NUMERIC_TYPE", "VALUE", $user_id);

$tNGs->executeTransactions();
//end check access
echo $tNGs->getErrorMsg();
?>