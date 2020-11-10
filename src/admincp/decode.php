<?php

require_once('Connections/cn.php'); 
require_once('includes/file/jdf.php'); 
//SQL Injection protection
require_once('includes/common/KT_common.php');
//42ad7a500a1b44587349ec6b5a1793e739d805fd
  $colname2_rscheck = sha1(md5("236360"));
  echo $colname2_rscheck;
  ?>