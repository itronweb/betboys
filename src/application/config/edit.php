<?php 


$burl="../../admin/plugins/pages/pages_list.php";



$fanameedit=trim($_GET['editvar']);
$file='routes.php';
$after="\n\$route['".$fanameedit."'] = 'content'; ";
file_put_contents($file, $after,FILE_APPEND);
header("Location: $burl?add=1&fileeditnew=1");




?>
