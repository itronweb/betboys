<?php 
require_once('../../checklogin.php'); 
if($checkaccc==1)
{//start check access
$colname_rs1 = "-1";
if (isset($_GET['id']) and isset($_GET['table']) and isset($_GET['field'])) {
  $colname_rs1 = trim(decrypt($_GET['id'],session_id()."hsts"));
  $field = trim(decrypt($_GET['field'],session_id()."hsts"));
  $table = trim(decrypt($_GET['table'],session_id()."hsts"));
}
mysqli_select_db($cn,$database_cn);
$query_rs1 = sprintf("SELECT id, `$field` FROM $table WHERE id = %s", GetSQLValueString($colname_rs1, "text"));
$rs1 = mysqli_query($cn,$query_rs1) or die(mysqli_error($cn));
$row_rs1 = mysqli_fetch_assoc($rs1);
$totalRows_rs1 = mysqli_num_rows($rs1);
if($totalRows_rs1>0){
if($row_rs1[$field]==1)
$newstatus=0;
elseif($row_rs1[$field]==0 and isset($row_rs1[$field]))
$newstatus=1;
elseif($row_rs1[$field]==NULL or !(isset($row_rs1[$field])) or $row_rs1[$field]==""){ $newstatus=1;}

$query_rs1 = sprintf("update $table  set `$field`='$newstatus' WHERE id = %s", GetSQLValueString($colname_rs1, "text"));
$rs1 = mysqli_query($cn,$query_rs1) or die(mysqli_error($cn));
echo "<script>window.location='".$_SERVER['HTTP_REFERER']."';</script>";
}elseif($totalRows_rs1==0){
	$newstatus=1;
    $query_RecordstatusInsert = "insert into $table (`id`, `result`) VALUES (".GetSQLValueString($colname_rs1, "text").",$newstatus)";
    $RecordstatusInsert = mysqli_query($cn,$query_RecordstatusInsert) or die(mysqli_error($cn));
	echo "<script>window.location='".$_SERVER['HTTP_REFERER']."';</script>";
}	
}////check access
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
</head>

<body>

</body>
</html>

