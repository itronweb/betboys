<?php 
require_once('Connections/cn.php'); 

date_default_timezone_set('Asia/Tehran');
include ("includes/file/jdf.php");

include ("includes/file/function.php");

//SQL Injection protection
require_once('includes/common/KT_common.php');
if (!function_exists("GetSQLValueString")) {
function GetSQLValueString($theValue, $theType, $theDefinedValue = "", $theNotDefinedValue = "") 
{
  $theValue = get_magic_quotes_gpc() ? stripslashes($theValue) : $theValue;

//   $theValue = function_exists("mysqli_real_escape_string") ? mysqli_real_escape_string($cn,$theValue) : mysqli_escape_string($cn,$theValue);
  
  switch ($theType) {
    case "text":
      //$theValue = ($theValue != "") ? "'" . $theValue . "'" : "NULL";
		$theValue =   KT_escapeForSql($theValue,'STRING_TYPE');
      break;    
    case "long":
    case "int":
      $theValue = ($theValue != "") ? intval($theValue) : "NULL";
		$theValue =    KT_escapeForSql($theValue,'NUMERIC_TYPE');
      break;
    case "double":
      $theValue = ($theValue != "") ? "'" . doubleval($theValue) . "'" : "NULL";
		$theValue =   KT_escapeForSql($theValue,'DOUBLE_TYPE');
      break;
    case "date":
//      $theValue = ($theValue != "") ? "'" . $theValue . "'" : "NULL";
		 $theValue =  KT_escapeForSql($theValue,'DATE_TYPE');
      break;
    case "defined":
      $theValue = ($theValue != "") ? $theDefinedValue : $theNotDefinedValue;
		 $theValue =  KT_escapeFieldName($theValue);
      break;
  }
  return $theValue;
}
}

 
 @session_start();
if ($_SESSION["achkpass"]==$_SESSION["apass"] and $_SESSION["securicheckadm"]==session_id() and $_SESSION["awebsite"]==$_SESSION["adomain"])    {
$condition_portal=" and portalid=".$_SESSION['portalid']."  ";
$condition_getdata_portal=" and (portalid=".$_SESSION['portalid']." or portalid='-1')  ";

$user_name = "-1";
if (isset($_SESSION['auser'])) {
  $user_name = $_SESSION['auser'];
}
mysqli_select_db($cn,$database_cn);
$query_rschmemdet = sprintf("SELECT id,levelaccess,name,image FROM `admin` WHERE `user` = %s", GetSQLValueString($user_name, "text"));
$rschmemdet = mysqli_query($cn,$query_rschmemdet) or die(mysqli_error($cn));
$row_rschmemdet = mysqli_fetch_assoc($rschmemdet);
$totalRows_rschmemdet = mysqli_num_rows($rschmemdet);

$admin_image=$row_rschmemdet['image'];

if(strpos($_SERVER['PHP_SELF'],'plugins')>0)
  $nav_path='../../';
else
  $nav_path='';

$accccesslist=explode(",",$row_rschmemdet['levelaccess']); 
$accccesslist[]="index";
$accccesslist[]="functiondata";
$accccesslist[]="function";
$accccesslist[]="dontacc";
$checkaccc=1;
$pagename= explode("/", $_SERVER['PHP_SELF']);
$tedadpager=count($pagename);
$pagename2= $pagename[$tedadpager-1];
$pagename= explode(".php", $pagename2);
$finalpagename= $pagename[0];
	if (!in_array($finalpagename, $accccesslist)) {
	$checkaccc=0;	
	header('Location: ../../dontaccess.php?ref='.$_SERVER['HTTP_REFERER']);
	echo "<script>
	window.location= '../../dontaccess.php?ref=".$_SERVER['HTTP_REFERER']."';
	</script>";
	//exit;
	} 
	
}
else
{
	header('Location: ../../login.php');
	echo "<script>
	window.location= '../../login.php';
	</script>";
	exit;
}
 
?>