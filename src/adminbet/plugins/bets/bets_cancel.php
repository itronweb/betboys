<?php require_once('../../checklogin.php'); 

// Load the common classes
require_once('../../includes/common/KT_common.php');

// Load the tNG classes
require_once('../../includes/tng/tNG.inc.php');
if($checkaccc==1)
{//start check access
// Make a transaction dispatcher instance
$id= decrypt($_GET['id'],session_id()."bets");
$work=$_GET['work'];
$betid=GetSQLValueString($id, "int");
$query_Recordsetad1 = "SELECT user_id,status,stake,effective_odd FROM `bets` WHERE `id` = '".$betid."'";
$Recordad1 = mysqli_query($cn,$query_Recordsetad1) or die(mysqli_error($cn));
$row_Recordadd1 = mysqli_fetch_assoc($Recordad1);
$userid=$row_Recordadd1['user_id'];
$effective_odd=$row_Recordadd1['effective_odd'];
$status=$row_Recordadd1['status'];
$stake=$row_Recordadd1['stake'];
	
$query_Recordsetad1 = "SELECT cash FROM `users` WHERE `id` = '".GetSQLValueString($userid, "int")."'";
$Recordad1 = mysqli_query($cn,$query_Recordsetad1) or die(mysqli_error($cn));
$row_Recordadd1 = mysqli_fetch_assoc($Recordad1);
$cash=$row_Recordadd1['cash'];
	$oldcash=$cash;
$bord=$effective_odd*$stake;
$nimbord=($effective_odd/2)+0.50;
if($work!=$status){
	
	//check if user invited status 0 all affiliate
	$query_Recordsetad1u = "SELECT user_id  FROM `affiliate` WHERE `invited_user_id` = '".GetSQLValueString($userid, "int")."'";
	$Recordad1u = mysqli_query($cn,$query_Recordsetad1u) or die(mysqli_error($cn));
	$row_Recordadd1u = mysqli_fetch_assoc($Recordad1u);
	$row_Recordadd1u_num = mysqli_num_rows($Recordad1u);
	if($row_Recordadd1u_num>0){
		$user_id_aff=trim($row_Recordadd1u['user_id']);
		$tran_id_aff=trim($user_id_aff.$userid.$betid);
		
		$tNGs3 = new tNG_dispatcher("");

		$conn_cn3 = new KT_connection($cn, $database_cn);

		$upd_trans_aff = new tNG_update($conn_cn3);
		$tNGs3->addTransaction($upd_trans_aff);

		$upd_trans_aff->registerTrigger("STARTER", "Trigger_Default_Starter", 1, "GET", "id");

		$upd_trans_aff->setTable("transactions");
		$upd_trans_aff->addColumn("status", "NUMERIC_TYPE", "VALUE","0");
		$upd_trans_aff->setPrimaryKey("trans_id", "STRING_TYPE", "VALUE", $tran_id_aff);
		
		$query_Recordsetad1u_t = "SELECT price FROM `transactions` WHERE `trans_id` = '".$tran_id_aff."'";
		$Recordad1u_t = mysqli_query($cn,$query_Recordsetad1u_t) or die(mysqli_error($cn));
		$row_Recordadd1u_t = mysqli_fetch_assoc($Recordad1u_t);
		
		$query_Recordsetad1u_u = "SELECT cash FROM `users` WHERE `id` = '".$user_id_aff."'";
		$Recordad1u_u = mysqli_query($cn,$query_Recordsetad1u_u) or die(mysqli_error($cn));
		$row_Recordadd1u_u = mysqli_fetch_assoc($Recordad1u_u);
		
		$newcashret=$row_Recordadd1u_u['cash']-$row_Recordadd1u_t['price'];
		
		$upd_user_aff_ret = new tNG_update($conn_cn3);
		$tNGs3->addTransaction($upd_user_aff_ret);

		$upd_user_aff_ret->registerTrigger("STARTER", "Trigger_Default_Starter", 2, "GET", "id");

		$upd_user_aff_ret->setTable("users");
		$upd_user_aff_ret->addColumn("cash", "DOUBLE_TYPE", "VALUE", $newcashret);
		$upd_user_aff_ret->addColumn("updated_at", "STRING_TYPE", "VALUE", date("Y-m-d H:i:s"));
		$upd_user_aff_ret->setPrimaryKey("id", "NUMERIC_TYPE", "VALUE", $user_id_aff);
		
		$tNGs3->executeTransactions();
		echo $tNGs3->getErrorMsg();
	}
	
	$desc=" تغییر وضعیت توسط مدیریت:";
	$desc.=" شرط شناسه $betid ";
	if($status==1){
//	$cash=($cash-$bord)+$stake;
	$cash=($cash-$bord);
	$desc.=" برد به ";
	}elseif($status==2){
		$cash=$cash;
		$desc.=" باخت به ";
	}elseif($status==3){
	$cash=$cash-$stake;
		$desc.=" استرداد به ";
	}elseif($status==4){
		$cash=$cash-($nimbord*$stake);
		$desc.=" نیم برد به ";
	}
	elseif($status==5){
		$cash=$cash-($stake/2);
		$desc.=" نیم باخت به ";

	}elseif($status==0){
		$cash=$cash;
		$desc.=" مشخص نشده به ";

	}
	$firstcash=$cash;
	if($work==1){
		$newcash=$cash+$bord;
		$desc.=" برد";
		$t=12;
	}elseif($work==2){
		
		if($row_Recordadd1u_num>0){
				
			
				
				$query_Recordsetad1u_a = "SELECT cash,affiliate_percent FROM `users` WHERE `id` = '".$user_id_aff."'";
				$Recordad1u_a = mysqli_query($cn,$query_Recordsetad1u_a) or die(mysqli_error($cn));
				$row_Recordadd1u_a = mysqli_fetch_assoc($Recordad1u_a);
				
				if($row_Recordadd1u_a['affiliate_percent'] and $row_Recordadd1u_a['affiliate_percent']!="" and $row_Recordadd1u_a['affiliate_percent'] != NULL){
					$percent= $row_Recordadd1u_a['affiliate_percent'];
				}else{
					$query_affiliate_per= "SELECT value FROM `settings` WHERE code='affiliate'";
					$affiliate_per = mysqli_query($cn,$query_affiliate_per) or die(mysqli_error($cn));
					$row_affiliate_per = mysqli_fetch_assoc($affiliate_per);
					$percent= $row_affiliate_per['value'];
				}
				$descrip_aff="واریز کارمزد باخت شرط کاربر زیر مجموعه به شناسه $userid"."و شرط با شناسه $betid   و درصد $percent";
				$percent=$percent/100;
				$price_aff=$stake*$percent;
				
				$cashu_a=$row_Recordadd1u_a['cash'];
				
				
				
				$newcash_aff=$cashu_a+$price_aff;
				
				$tNGs2 = new tNG_dispatcher("");

				// Make unified connection variable
				$conn_cn2 = new KT_connection($cn, $database_cn);
				
				$upd_user_aff = new tNG_update($conn_cn2);
				$tNGs2->addTransaction($upd_user_aff);

				$upd_user_aff->registerTrigger("STARTER", "Trigger_Default_Starter", 2, "GET", "id");

				$upd_user_aff->setTable("users");
				$upd_user_aff->addColumn("cash", "DOUBLE_TYPE", "VALUE", $newcash_aff);
				$upd_user_aff->addColumn("updated_at", "STRING_TYPE", "VALUE", date("Y-m-d H:i:s"));
				$upd_user_aff->setPrimaryKey("id", "NUMERIC_TYPE", "VALUE", $user_id_aff);
				
				
				$ins_trans_aff = new tNG_insert($conn_cn2);
				$tNGs2->addTransaction($ins_trans_aff);

				$ins_trans_aff->registerTrigger("STARTER", "Trigger_Default_Starter", 3, "GET", "id");

				$ins_trans_aff->setTable("transactions");
				$ins_trans_aff->addColumn("invoice_type", "NUMERIC_TYPE", "VALUE", 5);
				$ins_trans_aff->addColumn("user_id", "STRING_TYPE", "VALUE", $user_id_aff);
				$ins_trans_aff->addColumn("created_at", "STRING_TYPE", "VALUE", date("Y-m-d H:i:s"));
				$ins_trans_aff->addColumn("trans_id", "STRING_TYPE",  "VALUE",$tran_id_aff);
				$ins_trans_aff->addColumn("description", "STRING_TYPE", "VALUE",$descrip_aff);
				$ins_trans_aff->addColumn("updated_at", "STRING_TYPE", "VALUE", date("Y-m-d H:i:s"));
				$ins_trans_aff->addColumn("price",  "NUMERIC_TYPE", "VALUE", $price_aff);
				$ins_trans_aff->addColumn("cash",  "NUMERIC_TYPE", "VALUE", $newcash_aff);
				$ins_trans_aff->addColumn("status", "NUMERIC_TYPE", "VALUE","1");
				$ins_trans_aff->setPrimaryKey("id", "NUMERIC_TYPE");
				$tNGs2->executeTransactions();
echo $tNGs2->getErrorMsg();

			
		}
	
		
		$newcash=$cash;
		$desc.=" باخت ";
		
		$t=13;
	}elseif($work==3){
		$newcash=$cash+$stake;
		$desc.=" استرداد ";
		$t=14;
	}
	elseif($work==4){
		$newcash=$cash+($nimbord*$stake);
		$desc.=" نیم برد ";
		$t=15;
	}
	elseif($work==5){
		$newcash=$cash+($stake/2);
		$desc.=" نیم باخت ";
		$t=16;
	}
}elseif($work==$status){
	$newcash=$cash;
}
$price=$newcash-$firstcash;

	

$tNGs = new tNG_dispatcher("");

// Make unified connection variable
$conn_cn = new KT_connection($cn, $database_cn);

//start Trigger_ImgDelete trigger
//remove this line if you want to edit the code by hand 

// Make an instance of the transaction object
$del_ctns = new tNG_update($conn_cn);
$tNGs->addTransaction($del_ctns);
// Register triggers
$del_ctns->registerTrigger("STARTER", "Trigger_Default_Starter", 1, "GET", "id");

// Add columns
$del_ctns->setTable("bets");
$del_ctns->addColumn("status", "STRING_TYPE", "VALUE", $work);
if($work!=$status){
$del_ctns->addColumn("updated_at", "STRING_TYPE", "VALUE", date("Y-m-d H:i:s"));
if($price>0){
$del_ctns->addColumn("pay_stake_status", "STRING_TYPE", "VALUE", 1);
}else{
$del_ctns->addColumn("pay_stake_status", "STRING_TYPE", "VALUE", 0);
}
}
$del_ctns->setPrimaryKey("id", "NUMERIC_TYPE", "VALUE", $betid);
	
$upd_ctns = new tNG_update($conn_cn);
$tNGs->addTransaction($upd_ctns);
	
$upd_ctns->registerTrigger("STARTER", "Trigger_Default_Starter", 2, "GET", "id");
	
$upd_ctns->setTable("bet_form");
$upd_ctns->addColumn("result_status", "STRING_TYPE", "VALUE", $work);
if($work!=$status){
$upd_ctns->addColumn("updated_at", "STRING_TYPE", "VALUE", date("Y-m-d H:i:s"));
}
$upd_ctns->setPrimaryKey("bets_id", "NUMERIC_TYPE", "VALUE", $betid);
	
$upd_user = new tNG_update($conn_cn);
$tNGs->addTransaction($upd_user);
	
$upd_user->registerTrigger("STARTER", "Trigger_Default_Starter", 3, "GET", "id");
if($work==$status){
$upd_user->registerTrigger("END", "Trigger_Default_Redirect", 99, $_SERVER['HTTP_REFERER']);
}
	
$upd_user->setTable("users");
$upd_user->addColumn("cash", "DOUBLE_TYPE", "VALUE", $newcash);
if($work!=$status){
$upd_user->addColumn("updated_at", "STRING_TYPE", "VALUE", date("Y-m-d H:i:s"));
}
$upd_user->setPrimaryKey("id", "NUMERIC_TYPE", "VALUE", $userid);

if($work!=$status){
$price=abs($price);
$tran_id=trim($userid.$betid);
$upd_trans = new tNG_update($conn_cn);
$tNGs->addTransaction($upd_trans);
	
$upd_trans->registerTrigger("STARTER", "Trigger_Default_Starter", 4, "GET", "id");
	
$upd_trans->setTable("transactions");
$upd_trans->addColumn("status", "NUMERIC_TYPE", "VALUE","0");
$upd_trans->setPrimaryKey("trans_id", "STRING_TYPE", "VALUE", $tran_id);

    
$ins_trans = new tNG_insert($conn_cn);
$tNGs->addTransaction($ins_trans);
	
$ins_trans->registerTrigger("STARTER", "Trigger_Default_Starter", 5, "GET", "id");
$ins_trans->registerTrigger("END", "Trigger_Default_Redirect", 99, $_SERVER['HTTP_REFERER']);

$ins_trans->setTable("transactions");
$ins_trans->addColumn("invoice_type", "NUMERIC_TYPE", "VALUE", $t);
$ins_trans->addColumn("user_id", "STRING_TYPE", "VALUE", $userid);
$ins_trans->addColumn("created_at", "STRING_TYPE", "VALUE", date("Y-m-d H:i:s"));
$ins_trans->addColumn("trans_id", "STRING_TYPE",  "VALUE",$tran_id);
$ins_trans->addColumn("description", "STRING_TYPE", "VALUE",$desc);
$ins_trans->addColumn("updated_at", "STRING_TYPE", "VALUE", date("Y-m-d H:i:s"));
$ins_trans->addColumn("price",  "NUMERIC_TYPE", "VALUE", $price);
$ins_trans->addColumn("cash",  "NUMERIC_TYPE", "VALUE", $newcash);
$ins_trans->addColumn("status", "NUMERIC_TYPE", "VALUE","1");
$ins_trans->setPrimaryKey("id", "NUMERIC_TYPE");
}
    
// Execute all the registered transactions
$tNGs->executeTransactions();
echo $tNGs->getErrorMsg();
}//end check access
 ?>