<?php require_once('../../checklogin.php'); 

// Load the common classes
require_once('../../includes/common/KT_common.php');

// Load the tNG classes
require_once('../../includes/tng/tNG.inc.php');
if($checkaccc==1)
{//start check access
// Make a transaction dispatcher instance
$id= decrypt($_GET['id'],session_id()."sprt");

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
$del_sprt->setTable("sports");
$del_sprt->setPrimaryKey("id", "NUMERIC_TYPE", "VALUE", $id);


// Execute all the registered transactions
$tNGs->executeTransactions();
}//end check access
 ?>