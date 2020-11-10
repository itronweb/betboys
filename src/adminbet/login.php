<?php
// 		Sezar Bet Script
// 	Scripted By MoeinGhafourian
//		 wWw.SezarCo.IR
@session_start();

require_once('Connections/cn.php');
require_once('includes/file/jdf.php');
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
                $theValue = KT_escapeForSql($theValue, 'STRING_TYPE');
                break;
            case "long":
            case "int":
                $theValue = ($theValue != "") ? intval($theValue) : "NULL";
                $theValue = KT_escapeForSql($theValue, 'NUMERIC_TYPE');
                break;
            case "double":
                $theValue = ($theValue != "") ? "'" . doubleval($theValue) . "'" : "NULL";
                $theValue = KT_escapeForSql($theValue, 'DOUBLE_TYPE');
                break;
            case "date":
                //      $theValue = ($theValue != "") ? "'" . $theValue . "'" : "NULL";
                $theValue = KT_escapeForSql($theValue, 'DATE_TYPE');
                break;
            case "defined":
                $theValue = ($theValue != "") ? $theDefinedValue : $theNotDefinedValue;
                $theValue = KT_escapeFieldName($theValue);
                break;
        }
        return $theValue;
    }
}

$err             = 0;
$colname_rscheck = "-1";
if (isset($_POST['username']) and $_POST['username'] != "" and isset($_POST['password']) and $_POST['password'] != "") {
    $colname_rscheck  = $_POST['username'];
    $colnamea_rscheck = sha1(md5($_POST['password']));
    
    mysqli_select_db($cn, $database_cn);
    $query_rscheck = sprintf("SELECT * FROM `admin` WHERE `user` = %s and pass=%s", GetSQLValueString($colname_rscheck, "text"), GetSQLValueString($colnamea_rscheck, "text"));
    $rscheck = mysqli_query($cn, $query_rscheck) or die(mysql_error($cn));
    $row_rscheck       = mysqli_fetch_assoc($rscheck);
    $totalRows_rscheck = mysqli_num_rows($rscheck);
    if ($totalRows_rscheck == 0)
        $err = 1;
    elseif ($totalRows_rscheck > 0) {
        if ($row_rscheck['status'] == 1) {
            $webid = $row_rscheck['portalid'];
            mysqli_select_db($cn, $database_cn);
            $query_rsweblogset = "SELECT * FROM setting WHERE id = $webid";
            $rsweblogset = mysqli_query($cn, $query_rsweblogset) or die(mysqli_error($cn));
            $row_rsweblogset       = mysqli_fetch_assoc($rsweblogset);
            $totalRows_rsweblogset = mysqli_num_rows($rsweblogset);
            
            $_SESSION["auser"]          = $colname_rscheck;
            $_SESSION["apass"]          = $colnamea_rscheck;
            $_SESSION["achkpass"]       = $colnamea_rscheck;
            $_SESSION["securicheckadm"] = session_id();
            $_SESSION["lastlogintime"]  = $row_rscheck['lastlogin'];
            $_SESSION["lastloginip"]    = $row_rscheck['lastip'];
            $_SESSION["portalid"]       = $row_rscheck['portalid'];
            $_SESSION["aname"]          = $row_rscheck['name'];
            $_SESSION["aid"]            = $row_rscheck['id'];
            $_SESSION["acountry"]       = $row_rsweblogset['country'];
            $_SESSION["aostan"]         = $row_rsweblogset['ostan'];
            $_SESSION["ashahrestan"]    = $row_rsweblogset['shahrestan'];
            $_SESSION["abakhsh"]        = $row_rsweblogset['bakhsh'];
            $_SESSION["ashahr"]         = $row_rsweblogset['shahr'];
            $_SESSION["aabadi"]         = $row_rsweblogset['abadi'];
            $_SESSION["adomain"]        = $row_rsweblogset['domain'];
            $_SESSION["awebsite"]       = "sezarco.ir";
            
            $colname_rsuplog = "-1";
            if (isset($_POST['username'])) {
                $colname_rsuplog = $_POST['username'];
            }
            mysqli_select_db($cn, $database_cn);
            $query_rsuplog = sprintf("update `admin` set lastip=%s,lastlogin=%s WHERE `user` = %s", GetSQLValueString($_SERVER['REMOTE_ADDR'], "text"), GetSQLValueString(jdate("Y/m/d H:i"), "text"), GetSQLValueString($colname_rsuplog, "text"));
            $rsuplog = mysqli_query($cn, $query_rsuplog) or die(mysqli_error($cn));
            
            
            echo "<script>window.location='index.php';</script>";
            header('Location: index.php');
            exit;
        } else {
            $err = a;
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en" dir="rtl">
   <head>
      <meta charset="utf-8">
	<?php include('layouts/seo_root.php');?>
      <meta http-equiv="X-UA-Compatible" content="IE=edge">
      <meta name="viewport" content="width=device-width, initial-scale=1">
      <!-- The above o meta tags must come first in the head; any other head content must come after these tags -->
      <title>کنترل پنل مدیریت</title>
      <!-- Bootstrap -->
      <link href="<?= $Sezar_Theme_Link ?>\bootstrap\css\bootstrap.min.css" rel="stylesheet">
      <link href="<?= $Sezar_Theme_Link ?>\css\waves.min.css" type="text/css" rel="stylesheet">
      <link rel="stylesheet" href="<?= $Sezar_Theme_Link ?>\css\nanoscroller.css">
      <link href="<?= $Sezar_Theme_Link ?>\css\menu-light.css" type="text/css" rel="stylesheet">
      <link href="<?= $Sezar_Theme_Link ?>\css\style.css" type="text/css" rel="stylesheet">
      <link href="<?= $Sezar_Theme_Link ?>\font-awesome\css\font-awesome.min.css" rel="stylesheet">
      <!-- HTMLi shim and Respond.js for IE8 support of HTMLi elements and media queries -->
      <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
      <!--[if lt IE 9]>
      <script src="https://oss.maxcdn.com/htmlishiv/o.7.a/htmlishiv.min.js"></script>
      <script src="https://oss.maxcdn.com/respond/1.u.a/respond.min.js"></script>
      <![endif]-->
   </head>
   <body class="account honeycomb-background bg-video">
      <div class="container">
             <video id="vid" autobuffer="" autoplay="" loop="" muted="">
                 <source src="https://sezarco.ir/sezar-cp/sezar-upload/video/football.mpu" type="video/mpu">
             <source src="https://sezarco.ir/sezar-cp/sezar-upload/video/football.webm" type="video/webm">
             Your browser does not support the video tag.
             </video>
         <div class="row">
            <div class="account-col text-center">
  	<p style="color:#FFFF00;">ورود به پنل مدیران سایت!</p>
                <?php if($err==1 || $err==a){?>
                    <?php if($err==1){?>
						<p style="color:#FFFF00;">vipscriptfull!</p>
					<?php }?>
					 <?php if($err==a){?>
						<p style="color:#FFFF00;">امنیت تحت تیم حفاظتی vipscriptfull!</p>
					 <?php }?>
				 <?php }?>    
				
                        <form action="login.php" class="m-t" role="form" name="form1" method="POST" dir="ltr">
                            <div class="form-group">
                                <input type="text" class="form-control" required="required" name="username" placeholder="نام کاربری">
                            </div>
                            <div class="form-group">
                                <input type="password" class="form-control" required="required" name="password" placeholder="رمز ورود">
                            </div>
                            <input type="submit" name="login" class="btn btn-primary btn-block " value="ورود"></input>
                            <p style="direction:rtl;color:#FFFF00;">لایسنس برای دامنه taibet فعال شد</a> </p>
                            <p style="direction:rtl;color:#FFFF00;"> </p>
                        </form>

            </div>
         </div>
      </div>
      <script type="text/javascript" src="<?= $Sezar_Theme_Link ?>\js\jquery.min.js"></script>
      <script type="text/javascript" src="<?= $Sezar_Theme_Link ?>\bootstrap\js\bootstrap.min.js"></script>
      <script src="<?= $Sezar_Theme_Link ?>\js\pace.min.js"></script>
   </body>
</html>