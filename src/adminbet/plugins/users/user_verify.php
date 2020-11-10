<?php
	require_once('../../checklogin.php'); 
	// Load the common classes
	require_once('../../includes/common/KT_common.php');
	// Load the tNG classes
	require_once('../../includes/tng/tNG.inc.php');
	//start check access
	// Make a transaction dispatcher instance
	$id = $_GET['id'];
	$userid = GetSQLValueString($_GET['userid'], "int");
	$status = GetSQLValueString($_GET['status'], "int");


	$tNGs = new tNG_dispatcher("");

	// Make unified connection variable
	$conn_cn = new KT_connection($cn, $database_cn);



	$status = '2';
	$upd_adm = new tNG_update($conn_cn);
	$tNGs->addTransaction($upd_adm);

	// Register triggers
	$upd_adm->registerTrigger("STARTER", "Trigger_Default_Starter", 1, "GET", "id");

	// Add columns
	$upd_adm->setTable("`users`");
	$upd_adm->addColumn("status", "NUMERIC_TYPE", "VALUE", $status);
	$upd_adm->addColumn("updated_at", "STRING_TYPE", "VALUE", date("Y-m-d H:i:s"));
	$upd_adm->setPrimaryKey("id", "NUMERIC_TYPE", "VALUE", $id);
			
	header("Location:$admin_address/plugins/users/user_manage.php?edit=$id");

	// Execute all the registered transactions
	$tNGs->executeTransactions();
	//end check access

	echo $tNGs->getErrorMsg();
    
?>