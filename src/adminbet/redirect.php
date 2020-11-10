<?php
require_once('checklogin.php'); 

@session_start();

if (isset($_GET['page'])) {
  $page = GetSQLValueString($_GET['page'], "int");
}

if($page==1)
  $url='../plugins/news/cat_add.php';
  
header('location: '.$url);
exit;
?>