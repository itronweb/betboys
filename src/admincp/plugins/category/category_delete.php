<?php require_once('../../checklogin.php'); 

// Load the common classes
require_once('../../includes/common/KT_common.php');

// Load the tNG classes
require_once('../../includes/tng/tNG.inc.php');
if($checkaccc==1)
{//start check access
// Make a transaction dispatcher instance
$id= decrypt($_GET['id'],session_id()."cat");

$tNGs = new tNG_dispatcher("");

// Make unified connection variable
$conn_cn = new KT_connection($cn, $database_cn);

//start Trigger_ImgDelete trigger
//remove this line if you want to edit the code by hand 
function Trigger_ImgDelete(&$tNG) {
  $deleteObj = new tNG_FileDelete($tNG);
  $deleteObj->setFolder("../../../attachment/category/");
  $deleteObj->setDbFieldName("image");
  return $deleteObj->Execute();
}
//end Trigger_ImgDelete trigger

//start Trigger_HdrDelete trigger
//remove this line if you want to edit the code by hand 
function Trigger_HdrDelete(&$tNG) {
  $deleteObj = new tNG_FileDelete($tNG);
  $deleteObj->setFolder("../../../attachment/category/");
  $deleteObj->setDbFieldName("header");
  return $deleteObj->Execute();
}
//end Trigger_ImgDelete trigger

// Make an instance of the transaction object
$del_cat = new tNG_delete($conn_cn);
$tNGs->addTransaction($del_cat);
// Register triggers
$del_cat->registerTrigger("STARTER", "Trigger_Default_Starter", 1, "GET", "id");
$del_cat->registerTrigger("END", "Trigger_Default_Redirect", 99, $_SERVER['HTTP_REFERER']);
$del_cat->registerTrigger("AFTER", "Trigger_ImgDelete", 98);
$del_cat->registerTrigger("AFTER", "Trigger_HdrDelete", 98);

// Add columns
$del_cat->setTable("category");
$del_cat->setPrimaryKey("id", "NUMERIC_TYPE", "VALUE", $id);


// Execute all the registered transactions
$tNGs->executeTransactions();
}//end check access
 ?>