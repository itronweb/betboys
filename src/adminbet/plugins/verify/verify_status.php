<?php
	require_once('../../checklogin.php'); 
	// Load the common classes
	require_once('../../includes/common/KT_common.php');
	// Load the tNG classes
	require_once('../../includes/tng/tNG.inc.php');
	//start check access
	// Make a transaction dispatcher instance
	$id = $_GET['id'];
	$userid = $_GET['userid'];
	$status = $_GET['status'];
	mysqli_select_db($cn,$database_cn);


	$tNGs = new tNG_dispatcher("");

	// Make unified connection variable
	$conn_cn = new KT_connection($cn, $database_cn);

	$query_Recordsetad1u_a = "SELECT status FROM `users` WHERE `id` = '".$userid."'";
	$Recordad1u_a = mysqli_query($cn,$query_Recordsetad1u_a) or die(mysqli_error($cn));
	$row_Recordadd1u_a = mysqli_fetch_assoc($Recordad1u_a);

	if($status==1){
		$verify_status = 1;
		$user_status = 1;

		$upd_adm = new tNG_update($conn_cn);
		$tNGs->addTransaction($upd_adm);

		// Register triggers
		$upd_adm->registerTrigger("STARTER", "Trigger_Default_Starter", 1, "GET", "id");

		// Add columns
		$upd_adm->setTable("`verify`");
		$upd_adm->addColumn("status", "NUMERIC_TYPE", "VALUE", $verify_status);
		$upd_adm->addColumn("updated_at", "STRING_TYPE", "VALUE", date("Y-m-d H:i:s"));
		$upd_adm->setPrimaryKey("id", "NUMERIC_TYPE", "VALUE", $id);



		$upd_cash = new tNG_update($conn_cn);
		$tNGs->addTransaction($upd_cash);

		// Register triggers
		$upd_cash->registerTrigger("STARTER", "Trigger_Default_Starter", 3, "GET", "id");
		$upd_cash->registerTrigger("END", "Trigger_Default_Redirect", 99, $_SERVER['HTTP_REFERER']);

		// Add columns
		$upd_cash->setTable("`users`");
		$upd_cash->addColumn("status", "STRING_TYPE", "VALUE", $user_status);
		$upd_cash->addColumn("updated_at", "STRING_TYPE", "VALUE", date("Y-m-d H:i:s"));
		$upd_cash->setPrimaryKey("id", "STRING_TYPE", "VALUE", $userid);

	} elseif($status==2){

		$tNGs = new tNG_dispatcher("");

        // Make unified connection variable
        $conn_cn = new KT_connection($cn, $database_cn);

        // Make an instance of the transaction object
        $del_sprt = new tNG_delete($conn_cn);
        $tNGs->addTransaction($del_sprt);
        // Register triggers
        $del_sprt->registerTrigger("STARTER", "Trigger_Default_Starter", 1, "GET", "id");
        $del_sprt->registerTrigger("END", "Trigger_Default_Redirect", 99, $_SERVER['HTTP_REFERER']);
        // Add columns
        $del_sprt->setTable("verify");
        $del_sprt->setPrimaryKey("id", "NUMERIC_TYPE", "VALUE", $id);


	}
	header("Location:$admin_address/plugins/verify/verify_list.php");

	// Execute all the registered transactions
	$tNGs->executeTransactions();
	//end check access

	echo $tNGs->getErrorMsg();
    
?>