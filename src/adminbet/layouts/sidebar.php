<?php
	include 			__DIR__ . '/../checklogin_root.php';
	require_once		__DIR__ . '/../includes/SezarFunctions/functions.php';
	//Title & Description Creator
    GLOBAL $header;
    $header['title'] = 'کنترل پنل' . ( empty($content['title']) ? '' : ' - ' . $content['title'] );
?>
<!DOCTYPE html>
<html lang="en" dir="rtl">
    <head><meta http-equiv="Content-Type" content="text/html; charset=utf-8">
        
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta id="viewport" name="viewport" content="width=device-width, user-scalable=no, initial-scale=1, minimum-scale=1, maximum-scale=1" />
        <!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->
        <title><?php echo $header['title']; ?></title>

        <!-- Bootstrap -->
        <link href="<?php echo $Sezar_Theme_Link ?>bootstrap-rtl-master/dist/css/bootstrap-rtl.min.css" rel="stylesheet">
        <link href="<?= $Sezar_Theme_Link ?>css/waves.min.css" type="text/css" rel="stylesheet">
        <link rel="stylesheet" href="<?= $Sezar_Theme_Link ?>css/nanoscroller.css">
        <link href="<?= $Sezar_Theme_Link ?>css/menu-light.css" type="text/css" rel="stylesheet">
        <link href="<?= $Sezar_Theme_Link ?>font-awesome/css/font-awesome.min.css" rel="stylesheet">
        <link rel="stylesheet" href="<?= $Sezar_Theme_Link ?>css/bootstrap-slider.min.css">
        <link href="<?= $Sezar_Theme_Link ?>css/style.css" type="text/css" rel="stylesheet">

        <!-- SGBS CSS -->
		<link rel="stylesheet" type="text/css" href="<?= $Sezar_Theme_Link ?>css/SGBS/persian-datepicker.css">
		<link rel="stylesheet" type="text/css" href="<?= $Sezar_Theme_Link ?>css/SGBS/persian-datepicker-cheerup.min.css">

        <!-- SezarIcon -->
		<link rel="icon" href="/favicon.ico" type="image/x-icon" />
		<link rel="shortcut icon" href="../favicon.ico" type="image/x-icon" />

        <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
        <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
        <!--[if lt IE 9]>
          <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
          <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
        <![endif]-->
		<style>
			.w-100{
				width:100%;
			}
		</style>
    </head>
    <body>
        <!-- Static navbar -->

        <nav class="navbar navbar-default yamm navbar-fixed-top">
            <div class="container-fluid">
                <button type="button" class="navbar-minimalize minimalize-styl-2  pull-left "><i class="fa fa-bars"></i></button>
                <div class="navbar-header">
                    <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar" aria-expanded="false" aria-controls="navbar">
                        <span class="sr-only">Toggle navigation</span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                    </button>
<img src="http://tubobet.pw/assets/default/live/images/main_logo.png" class="mt5" height="50">
                </div>
				<div id="navbar" class="navbar-collapse collapse">
                    <ul class="nav navbar-nav">

                        <li class="dropdown">
                            <a href="#" class="dropdown-toggle button-wave waves-effect waves-button waves-light" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">دسترسي سريع <span class="caret"></span></a>
                            <ul class="dropdown-menu">
                                <li><a href="../index.php">  صفحه اصلي سایت بیتینو</a></li>
                                <li role="separator" class="divider"></li>
                                <li class="dropdown-header"> دسترسي سريع به سایت بیتینو</li>
                                <li><a href="/" target="blank"> نمایش سایت بیتینو</a></li>
                            </ul>
                        </li>

                    </ul>

                    
                    <div class="navbar-brand" style="font-size: 14px;margin: auto;" title="زمان ورود به پنل بیتینو: <?php echo $_SESSION["lastlogintime"]; ?>">آخرین ای پی مدیریت : <?php echo $_SESSION["lastloginip"]; ?></div>

                    <ul class="nav navbar-nav navbar-right navbar-top-drops">
							<?php 
								mysqli_select_db($database_cn, $cn);
								$query_rstrans = "SELECT id,user_id,created_at FROM transactions where invoice_type=20 and `status` = 0 ORDER BY id DESC";
								$rstrans = mysqli_query($cn,$query_rstrans) or die(mysqli_error($cn));
								$row_rstrans = mysqli_fetch_assoc($rstrans);
								$totalRows_rstrans = mysqli_num_rows($rstrans); 
							?>
                        <li class="dropdown">
						<a href="#" class="dropdown-toggle button-wave waves-effect waves-button waves-light" data-toggle="dropdown"><i class="fa fa-sack-dollar"></i>  <span class="badge badge-xs badge-warning"><?php if($totalRows_rstrans>0) {?><?php echo $totalRows_rstrans;?><?php }?></span>  </a>
                            <ul class="dropdown-menu dropdown-lg">
                                <li class="notify-title">
                                    پرداختی های جدید
                                </li>
								<?php 
									$ei=1;
									if ($totalRows_rstrans > 0) { // Show if recordset not empty 
										do { 
											if($ei==1) 
											  $pt='primary';
											else if($ei==2) 
											  $pt='warning';
											else if($ei==3) 
											  $pt='info';
											else if($ei==4) 
											  $pt='success';
								?>
                                <li class="clearfix"> 
                                    <a href="#<?php echo $nav_path;?>plugins/payment/payment_view.php?id=<?= $row_rstrans['id'] ?>">
                                        <i class="fa fa-credit-card pull-right" style="display: inline-block; margin: 5px"></i>
                                        <?php
											$user_id=$row_rstrans['user_id'];
											$query_rsuser = "SELECT first_name,last_name from users  where id=$user_id";
											$rsuser = mysqli_query($cn,$query_rsuser) or die(mysqli_error($cn));
											$row_rsuser = mysqli_fetch_assoc($rsuser);
										?>
										<span class="block"><?php echo $row_rsuser['first_name']." ".$row_rsuser['last_name']; ?></span>
                                        <span class="media-body">
                                            <span style="font-size: 100%">sss<?php echo $row_rstrans['account_holder']; ?></span>
                                            <em>
												<?php $s=miladi_to_jalali($row_rstrans['created_at'],"-"," "); ?>
												<?php echo substr($s,8,2)." "; ?><?php echo monthname(substr($s,5,2)); ?>
												<?php echo substr($s,0,4)." "; ?>
												<?php echo "- ".substr($s,11,9); ?>
											</em>
                                        </span>
                                    </a>
                                </li>
								<?php 
									if($ei==4)
										$ei=1;
									else
										$ei++;
									} while ($row_rstrans = mysqli_fetch_assoc($rstrans)); 	
															
								} // Show if recordset not empty 
								?>       
                                                                
                                <li class="read-more"><a href="<?php echo $nav_path;?>plugins/payment/payment_list.php?cat=2"><i class="fa fa-angle-left"></i> نمایش همه</a></li>
                            </ul>
                        </li>
						<?php 
							mysqli_select_db($database_cn, $cn);
							$query_rswith = "SELECT id, account_holder, status, created_at FROM withdraw where withdraw.`status` = 2 ORDER BY id DESC";
							$rswith = mysqli_query($cn,$query_rswith) or die(mysqli_error($cn));
							$row_rswith = mysqli_fetch_assoc($rswith);
							$totalRows_rswith = mysqli_num_rows($rswith); 
						?>
                        <li class="dropdown">
						<a href="#" class="dropdown-toggle button-wave waves-effect waves-button waves-light" data-toggle="dropdown"><i class="fa fa-credit-card"></i>  <span class="badge badge-xs badge-warning"><?php if($totalRows_rswith>0) {?><?php echo $totalRows_rswith;?><?php }?></span>  </a>
                            <ul class="dropdown-menu dropdown-lg">
                                <li class="notify-title">
                                    درخواست وجه جدید
                                </li>
								  <?php 
									$ei=1;
									if ($totalRows_rswith > 0) { // Show if recordset not empty 
										do { 
											
											if($ei==1) 
											  $pt='primary';
											else if($ei==2) 
											  $pt='warning';
											else if($ei==3) 
											  $pt='info';
											else if($ei==4) 
											  $pt='success';
									?>                                                          
                                <li class="clearfix">
                                    <a href="<?php echo $nav_path;?>plugins/withdraw/withdraw_view.php?id=<?= $row_rswith['id'] ?>">
                                        <i class="fa fa-credit-card pull-right" style="display: inline-block; margin: 5px"></i>
                                        <span class="block"><?php echo $row_rswith['subject']; ?></span>
                                        <span class="media-body">
                                            <span style="font-size: 100%"><?php echo $row_rswith['account_holder']; ?></span>
                                            <em>
												<?php $s=miladi_to_jalali($row_rswith['created_at'],"-"," "); ?>
												<?php echo substr($s,8,2)." "; ?><?php echo monthname(substr($s,5,2)); ?>
												<?php echo substr($s,0,4)." "; ?>
												<?php echo "- ".substr($s,11,9); ?>
											</em>
                                        </span>
                                    </a>
                                </li>
										
								<?php 
										if($ei==4)
											$ei=1;
										else
											$ei++;
										} while ($row_rswith = mysqli_fetch_assoc($rswith)); 	
																
								} // Show if recordset not empty 
								?>          
                                <li class="read-more"><a href="<?php echo $nav_path;?>plugins/withdraw/withdraw_list.php"><i class="fa fa-angle-left"></i> نمایش همه درخواست ها</a></li>
                            </ul>
                        </li>
						<?php 
							mysqli_select_db($database_cn, $cn);
							$query_rstick = "SELECT id, subject, status, created_at FROM tickets where tickets.`status` = 0 ORDER BY id DESC";
							$rstick = mysqli_query($cn,$query_rstick) or die(mysqli_error($cn));
							$row_rstick = mysqli_fetch_assoc($rstick);
							$totalRows_rstick = mysqli_num_rows($rstick); 
						?>
                        <li class="dropdown">
						<a href="#" class="dropdown-toggle button-wave waves-effect waves-button waves-light" data-toggle="dropdown"><i class="fa fa-comments"></i>  <span class="badge badge-xs badge-warning"><?php if($totalRows_rstick>0) {?><?php echo $totalRows_rstick;?><?php }?></span>  </a>
                            <ul class="dropdown-menu dropdown-lg">
                                <li class="notify-title">
                                    تیکت جدید
                                </li>
								  <?php 
									$ei=1;
									if ($totalRows_rstick > 0) { // Show if recordset not empty 
										do { 
											
											if($ei==1) 
											  $pt='primary';
											else if($ei==2) 
											  $pt='warning';
											else if($ei==3) 
											  $pt='info';
											else if($ei==4) 
											  $pt='success';
									?>                                                         
                                <li class="clearfix">
                                    <a href="<?php echo $nav_path;?>plugins/tickets/tickets_view.php?id=<?= $row_rstick['id']; ?>">
                                        <i class="fa fa-comments pull-right" style="display: inline-block; margin: 5px"></i>
                                        <span class="block"><?php echo $row_rstick['subject']; ?></span>
                                        <span class="media-body">
                                            <span style="font-size: 100%"><?php echo $row_rstick['title']; ?></span>
                                            <em>
												<?php $s= str_replace("-","/",$row_rs1['created_at']);?>
												<?php $s=miladi_to_jalali($row_rstick['created_at'],"-"," "); ?>
												<?php echo substr($s,8,2)." "; ?><?php echo monthname(substr($s,5,2)); ?>
												<?php echo substr($s,0,4)." "; ?>
												<?php echo "- ".substr($s,11,9); ?>
											</em>
                                        </span>
                                    </a>
                                </li>
										
								<?php 
										if($ei==4)
											$ei=1;
										else
											$ei++;
										} while ($row_rstick = mysqli_fetch_assoc($rstick)); 	
																
								} // Show if recordset not empty 
								?>              
                                <li class="read-more"><a href="<?php echo $nav_path;?>plugins/tickets/tickets_list.php"><i class="fa fa-angle-left"></i>  نمایش همه تیکت ها کاربران توبت نود</a></li>
                            </ul>
                        </li>
						<?php 
							mysqli_select_db($database_cn, $cn);
							$query_vstick = "SELECT id FROM verify where `status` = 0 ORDER BY id DESC";
							$vstick = mysqli_query($cn,$query_vstick) or die(mysqli_error($cn));
							$row_vstick = mysqli_fetch_assoc($vstick);
							$totalRows_vstick = mysqli_num_rows($vstick); 
						?>
						<li class="dropdown">
							<a href="#" class="dropdown-toggle button-wave waves-effect waves-button waves-light" data-toggle="dropdown">
								<i class="fa fa-user"></i>
								<span class="badge badge-xs badge-warning">
									<?php if($totalRows_vstick>0) {?>
										<?php echo $totalRows_vstick;?>
									<?php }?>
								</span>
							</a>
                            <ul class="dropdown-menu dropdown-lg">
                                <li class="read-more"><a href="<?php echo $nav_path;?>plugins/verify/verify_list.php"><i class="fa fa-angle-left"></i> نمایش درخواست ها</a></li>
                            </ul>
						</li>

                    </ul>
                </div>
            </div><!--/.container-fluid -->
        </nav>
        <section class="page">
            <nav class="navbar-aside navbar-static-side" role="navigation">
                <div class="sidebar-collapse nano">
                    <div class="nano-content">
                        <ul class="nav metismenu" id="side-menu">
                            <li class="nav-header">
                                <div class="dropdown side-profile text-left"> 
                                    <span style="display: block;">
									<?php
										$row_insta = @getInsta( $row_admin['image'] );
										$avatar    = !empty($row_insta['avatar']) ? $row_insta['avatar'] : $Sezar_Theme_Link .'images/admin.png';
										?>
                                        <img alt="image" title="" src="<?= $avatar;?>" width="64">
                                    </span>
                                    <a data-toggle="dropdown" class="dropdown-toggle" href="#">
                                        <span class="clear" style="display: block;"> 
                                            <span class="block m-t-xs"> <strong class="font-bold"><?php echo $_SESSION["aname"]; ?>  <b class="caret"></b></strong> </span>
                                        </span>
                                    </a>
                                    <ul class="dropdown-menu animated fadeInRight m-t-xs">
                                        <li><a href="<?php echo $nav_path;?>change_pass.php"><i class="fa fa-user"></i>تغییر کلمه عبور</a></li>
                                        <li class="divider"></li>
                                        <li><a href="<?php echo $nav_path;?>logout.php"><i class="fa fa-key"></i>خروج</a></li>
                                    </ul>
                                </div>
                            </li>
								<?php
									$geturl =  explode("/", $_SERVER['PHP_SELF']);
									$endgeturl = end($geturl);
									$curpage=str_replace('.php','',$endgeturl);

									$query_rslcurcat = "SELECT cat FROM admin_levellist WHERE status = '1' AND page='$curpage' ORDER BY sort ASC";
									$rslcurcat = mysqli_query($cn,$query_rslcurcat) or die(mysqli_error($cn));
									$row_rslcurcat = mysqli_fetch_assoc($rslcurcat);
									$totalRows_rslcurcat = mysqli_num_rows($rslcurcat);
									$curcat=$row_rslcurcat['cat'];
								?>
							<li class="active">
								<a href="<?php echo $nav_path;?>index.php"><i class="fa fa-th-large"></i> <span class="nav-label">ميزکار سایت بیتینو</span></a>
							</li>
							 <?php
								for($yyy=0;$yyy<count($accccesslist);$yyy++)
								$accccesslistyy[]="'".$accccesslist[$yyy]."'";
								$finalaccliiistt=implode(",",$accccesslistyy);
																	
								mysqli_select_db($cn,$database_cn);
								$query_rslcat = "SELECT id, name, style FROM admin_levelcat WHERE status = '1' and id in (SELECT cat FROM admin_levellist WHERE page in ($finalaccliiistt) group by cat) ORDER BY sort ASC";
								$rslcat = mysqli_query($cn,$query_rslcat) or die(mysqli_error($cn));
								$row_rslcat = mysqli_fetch_assoc($rslcat);
								$totalRows_rslcat = mysqli_num_rows($rslcat);
																	
																	if($totalRows_rslcat>0)
																	{
																	 do {
																	$boxcat=$row_rslcat['id'];
																	mysqli_select_db($cn,$database_cn);
								$query_rsboxitem = "SELECT name, path, page, icon, extraparam, showinmenu FROM admin_levellist WHERE cat = $boxcat and status='1' ORDER BY sort ASC";
								$rsboxitem = mysqli_query($cn,$query_rsboxitem) or die(mysqli_error($cn));
								$row_rsboxitem = mysqli_fetch_assoc($rsboxitem);
								$totalRows_rsboxitem = mysqli_num_rows($rsboxitem);

								$geturl =  explode("/", $_SERVER['PHP_SELF']);
								$endgeturl = end($geturl);
								$curpage=str_replace('.php','',$endgeturl);

								if($totalRows_rsboxitem >0){
							?>
							<li class="">
								<a href="#">
								<i class="fa fa-cogs <?php echo $row_rslcat['style']; ?>"></i> <span class="nav-label"><?php echo $row_rslcat['name']; ?></span>	<span class="fa arrow"></span></a>
								<ul class="nav nav-second-level collapse" aria-expanded="false" style="height: 0px;">
									<?php 
								do { 
									if(strcmp($curpage,$row_rsboxitem['page'])==0) { 
										if($nav_path) 
										  $cur_pageurl = '../'.$row_rsboxitem['path'].'/'.$row_rsboxitem['page'].'.php'; 
										else 
										  $cur_pageurl = 'plugins/'.$row_rsboxitem['path'].'/'.$row_rsboxitem['page'].'.php'; 
										
										$cur_pagename=$row_rsboxitem['name']; 
										$cur_pagecat=$row_rslcat['name']; 
									}

								   if (in_array($row_rsboxitem['page'], $accccesslist) && $row_rsboxitem['showinmenu']=='1') {
                                ?>
									<li>
										<a href="<?php if($nav_path) echo '../'.$row_rsboxitem['path'].'/'.$row_rsboxitem['page']; else echo 'plugins/'.$row_rsboxitem['path'].'/'.$row_rsboxitem['page']; ?>.php<?php echo $row_rsboxitem['extraparam']; ?>"><?php echo $row_rsboxitem['name']; ?></a>
									</li>
								<?php 
									}
									} while ($row_rsboxitem = mysqli_fetch_assoc($rsboxitem)); 
								?>
								</ul>
							</li>
							<?php
								  }
								   } while ($row_rslcat = mysqli_fetch_assoc($rslcat));
								  } 
							?>
							
                        </ul>
                    </div>
                </div>
            </nav>
			<div id="wrapper">
				<div class="content-wrapper container">
				<?php include __DIR__ . '/breadcrumb.php'; ?>
<?php

	// ----------------------------------------------------------------------
	
	/*
	foreach ( ['getLicense', 'licenseError', 'getLicense'] as $funcName )
		if ( function_exists($funcName) ) {
			http_response_code (500);
			echo ("<strong>Sezar License Fault:</strong> The check functions are already defined.");
			die('Termination of the customer script.');
		}
	*/

	function licenseError ($msg, $code = 500) {
		http_response_code ($code);
		echo "<b>خطا :</b> $msg<br/>\n";
		echo '
		    <div style="direction:rtl;">انتقال به <a id="redirect_dir" href="https://sezarco.ir/">کمپانی وی ای پی سکریپت</a> در <span id="timer_obj">15</span> ثانیه...</div>
            <script>
            	function redirectStep() {
            		var timerObj = document.getElementById("timer_obj"),
            			timerVal = parseInt(timerObj.innerHTML.trim());
            		
            		if ( !timerObj || isNaN(timerVal) )
            			alert("Timer redirect fault");
            		else {
            			if ( --timerVal < 0 )
            				document.getElementById("redirect_dir").click();
            			else {
            				document.getElementById("timer_obj").innerHTML = timerVal;
            				window.clearTimeout(window.redirectId); window.redirectId = 
            				window.redirectId = window.setTimeout(redirectStep, 1000);
            			}
            		}
            	}
            	
            	redirectStep();
            </script>
		';
		error_log($msg);
		exit;
	}

	function getLicense ($url) {
		$data = trim( @file_get_contents($url) );
		if ( empty($data) ) licenseError("Could not connect to Sezar License check server.", 500);
		else $data = @json_decode($data, true);
		if ( empty($data) ) licenseError("Syntax error for Sezar License file.", 500);
		else return $data;
	}
	
	function checkLicense () {
	    
	    $check_URL = "https://sezar.world/sezar-cp/inc/json_license.php?domain={$_SERVER['SERVER_NAME']}";
	    
	    if ( empty($_SERVER['SERVER_NAME']) ) licenseError("Customer's server domain was not found.", 500);
	    $request = getLicense($check_URL);
	    
	    if ( !isset($request['success']) ) licenseError("This does not seem to be a Sezar License file.", 500);
	    else {
	        $domain_name = $request['request'];
	        
	        $is_valid = !empty($request['success']);
	        if ( $is_valid ) {
	            // License is OK
				
				echo '<sezar::license><!-- wWw.SezarCo.IR --></sezar::license>';
				
				return true;
	        }
	        else {
	            // License is not VALID
	            
	            if ( !isset($request['details']) ) {
	                licenseError("لایسنسی برای دامنه  `{$domain_name}` در سزار ثبت نشده است.");
	            }
	            else
	            {
	                $is_active = !empty($request['details']['is_active']);
	                licenseError("لایسنس دامنه `{$domain_name}` در سرور حضراتغیرفعال است.");
	            }
				
				return false;
	            
	        }
	        
	    }
		
		return NULL;
	    
	}
	
//	if ( checkLicense() != TRUE ) die();
	
	// ----------------------------------------------------------------------
	
?>