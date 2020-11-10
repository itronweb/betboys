<?php 
//include ("../../../admin/includes/file/function.php");

if (!function_exists("GetSQLValueString")) {
function GetSQLValueString($theValue, $theType, $theDefinedValue = "", $theNotDefinedValue = "") 
{
  $theValue = get_magic_quotes_gpc() ? stripslashes($theValue) : $theValue;

/*   $theValue = function_exists("mysqli_real_escape_string") ? mysqli_real_escape_string($cn,$theValue) : mysqli_escape_string($cn,$theValue);
 */   
  switch ($theType) {
    case "text":
      $theValue = ($theValue != "") ? "'" . $theValue . "'" : "NULL";
      break;    
    case "long":
    case "int":
      $theValue = ($theValue != "") ? intval($theValue) : "NULL";
      break;
    case "double":
      $theValue = ($theValue != "") ? "'" . doubleval($theValue) . "'" : "NULL";
      break;
    case "date":
      $theValue = ($theValue != "") ? "'" . $theValue . "'" : "NULL";
      break;
    case "defined":
      $theValue = ($theValue != "") ? $theDefinedValue : $theNotDefinedValue;
      break;
  }
  return $theValue;
}
}

$id=GetSQLValueString($_GET['id'],"text");

include ("../../../admin/Connections/cn.php");
$burl="../../../admin/plugins/team/team_list.php";

mysqli_select_db($cn,$database_cn);

$query_rs1 = "SELECT * FROM `teams` WHERE  `id` =$id ORDER BY `id` desc";
$rs1 = mysqli_query($cn,$query_rs1) or die(mysqli_error($cn));
$row_rs1 = mysqli_fetch_assoc($rs1);

$fanameedit=trim($_GET['editvar']);
$teams_name_en=trim($row_rs1['teams_name_en']);
$teams_name_en=ucwords($teams_name_en);
$file='team_lang.php';
$start2 = "= '";
$end2 = "'";
$start = "['";
$end = "']";
$b=0;

$lines = file($file, FILE_IGNORE_NEW_LINES);
$searchword = $teams_name_en;
$matches = array_filter($lines, function($var) use ($searchword) { return preg_match("/\b$searchword\b/i", $var); });
//var_dump($matches);


echo $size=sizeof($matches);

if($size==1){
	foreach($matches as $x => $x_value) {
    echo "Key=" . $x . ", Value=" . $x_value;
    echo "<br>";
	}
	$before=$x_value;
	$fateam = getBetween($x_value,$start2,$end2);
	$fateam=trim($fateam);
	if($fanameedit==$fateam){
		header("Location:$burl?add=1");
	}
	else{
		$after="\$lang['".$teams_name_en."'] = '".$fanameedit."'; ";
		edit_line($file,$before,$after);
		header("Location: $burl?add=1&fileedit=1");
	}
}
elseif($size<1){
		$after="\n\$lang['".$teams_name_en."'] = '".$fanameedit."'; ";
	    file_put_contents($file,$after,FILE_APPEND);
		header("Location: $burl?add=1&fileeditnew=1");
}
elseif($size>1){
	var_dump($matches);
	 while (list($var, $val) = each($matches)) {
        ++$var;
//        $val = trim($val);
        print "Line $var: $val<br />";
//		$fateam = getBetween($val,$start2,$end2);
		$enteam = getBetween($val,$start,$end);
		 if($enteam==$teams_name_en){
			$b=1;
			$before=$val;
			$fateam = getBetween($val,$start2,$end2);
			$fateam=trim($fateam);
			if($fanameedit==$fateam){
				header("Location: $burl?add=1");
			}
			else{
				$after="\$lang['".$teams_name_en."'] = '".$fanameedit."'; ";
				edit_line($file,$before,$after);
				header("Location: $burl?add=1&fileedit=1");
			}
		 }

    }
			 if($b != 1){
			 	$after="\n\$lang['".$teams_name_en."'] = '".$fanameedit."'; ";
				file_put_contents($file,$after,FILE_APPEND);
				header("Location: $burl?add=1&fileeditnew=1");
		 }
}


function edit_line($file, $before,$after) {
    $lines = file($file, FILE_IGNORE_NEW_LINES);
    foreach($lines as $key => $line) {
        if($line === $before) $lines[$key]=$after;
    }
    $data = implode(PHP_EOL, $lines);
    file_put_contents($file, $data);	
}

function getBetween($content,$start,$end){
		$r = explode($start, $content);
		if (isset($r[1])){
			$r = explode($end, $r[1]);
			return $r[0];
		}
		return '';
}
?>
