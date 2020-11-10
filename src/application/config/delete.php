<?php 


$burl="../../admin/plugins/pages/pages_list.php";



$fanameedit=trim($_GET['editvar']);
$file='routes.php';
$start2 = "= '";
$end2 = "'";
$start = "['";
$end = "']";


$b=0;

$lines = file($file, FILE_IGNORE_NEW_LINES);
$searchword = $fanameedit;
$matches = array_filter($lines, function($var) use ($searchword) { return preg_match("/\b$searchword\b/i", $var); });

$size=sizeof($matches);

if($size==1){
	foreach($matches as $x => $x_value) {
    echo "Key=" . $x . ", Value=" . $x_value;
    echo "<br>";
	}
	$file_out = file($file);
	unset($file_out[$x]);
	file_put_contents($file, implode("", $file_out));



}
header("Location: $burl?add=1&filedelete=1");




?>
