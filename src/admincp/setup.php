<?php 
@session_start();

require_once('Connections/cn.php'); 
require_once('includes/file/jdf.php'); 
require_once('includes/file/function.php'); 
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

// Load the tNG classes
require_once('includes/tng/tNG.inc.php');

// Make a transaction dispatcher instance
$tNGs = new tNG_dispatcher("");

// Make unified connection variable
$conn_cn = new KT_connection($cn, $database_cn);

// Start trigger
$formValidation = new tNG_FormValidation();
$formValidation->addField("domain", true, "text", "", "", "", "لطفا نام دامنه را وارد نمایید.");
$formValidation->addField("createdate", true, "text", "", "", "", "لطفا تاریخ ایجاد را وارد نمایید.");
$formValidation->addField("name", true, "text", "", "", "", "لطفا نام پورتال را وارد نمایید.");
$tNGs->prepareValidation($formValidation);
// End trigger


if($_POST['tblslc']!='') {
	
	$tblslc=$_POST['tblslc'];
	
	for($i=0;$i<count($tblslc);$i++) {
		$cat=$tblslc[$i];
		
		mysqli_select_db($cn, $database_cn);
		$query_rslevellst = "SELECT path, cat FROM admin_levellist where cat=$cat GROUP BY path";
		$rslevellst = mysqli_query($cn, $query_rslevellst) or die("Invalid Parametr!");
		$row_rslevellst = mysqli_fetch_assoc($rslevellst);
		$totalRows_rslevellst = mysqli_num_rows($rslevellst);
		
		do {
			
			if($row_rslevellst['path']!='') {
				$cat=$row_rslevellst['cat'];
				
			  mysqli_select_db($cn, $database_cn);
			  $query_rsdrop= 'DROP TABLE '.$row_rslevellst['path'];
			  $rsdrop = mysqli_query($cn, $query_rsdrop) ;
			  
			  
			  mysqli_select_db($cn, $database_cn);
			  $query_rsadmincat= "UPDATE admin_levelcat SET status='0' WHERE id=$cat";
			  $rsadmincat = mysqli_query($cn, $query_rsadmincat) ;


			  mysqli_select_db($cn, $database_cn);
			  $query_rsadminlst= "UPDATE admin_levellist SET status='0' WHERE cat=$cat";
			  $rsadminlst = mysqli_query($cn, $query_rsadminlst) ;


			  $src='plugins/'.$row_rslevellst['path'].'/';
			  rrmdir($src);
			}
		
		} while ($row_rslevellst = mysqli_fetch_assoc($rslevellst));
	}
	
		exit;

}

// Make an update transaction instance
$upd_stp = new tNG_update($conn_cn);
$tNGs->addTransaction($upd_stp);

// Register triggers
$upd_stp->registerTrigger("STARTER", "Trigger_Default_Starter", 1, "POST", "KT_Update1");
$upd_stp->registerTrigger("BEFORE", "Trigger_Default_FormValidation", 10, $formValidation);
$upd_stp->registerTrigger("END", "Trigger_Default_Redirect", 99, "setup.php");

// Add columns
$upd_stp->setTable("`setting`");
$upd_stp->addColumn("domain", "STRING_TYPE", "POST", "domain");
$upd_stp->addColumn("createdate", "STRING_TYPE", "POST", "createdate");
$upd_stp->addColumn("name", "STRING_TYPE", "POST", "name");
$upd_stp->setPrimaryKey("id", "NUMERIC_TYPE", "VALUE", 0);

// Execute all the registered transactions
$tNGs->executeTransactions();

// Get the transaction recordset
$rsstp = $tNGs->getRecordset("`setting`");
$row_rsstp = mysqli_fetch_assoc($rsstp);
$totalRows_rsstp = mysqli_num_rows($rsstp);


mysqli_select_db($cn,$database_cn);
$query_Recordsetadmcat = "SELECT * FROM admin_levelcat";
$Recordsetadmcat = mysqli_query($cn,$query_Recordsetadmcat) or die(mysqli_error($cn));
$row_Recordsetadmcat = mysqli_fetch_assoc($Recordsetadmcat);
$totalRows_Recordsetadmcat = mysqli_num_rows($Recordsetadmcat);

?>
<!DOCTYPE html>
<html lang="en" dir="rtl">
<head>
	<meta charset="utf-8">
	<?php include('layouts/seo_root.php');?>

	<!-- Theme JS files -->
	<script type="text/javascript" src="assets/js/plugins/forms/styling/uniform.min.js"></script>

	<script type="text/javascript" src="assets/js/core/app.js"></script>
	<script type="text/javascript" src="assets/js/pages/login.js"></script>
	<!-- /theme JS files -->

</head>

<body>

	<!-- Main navbar -->
	<div class="navbar navbar-inverse">
		<div class="navbar-header">
			

			<ul class="nav navbar-nav pull-right visible-xs-block">
				<li><a data-toggle="collapse" data-target="#navbar-mobile"><i class="icon-tree5"></i></a></li>
			</ul>
		</div>

		<div class="navbar-collapse collapse" id="navbar-mobile">
			<ul class="nav navbar-nav navbar-right">
				<li>
					<a href="../">
						<i class="icon-display4"></i> <span class="visible-xs-inline-block position-right"> نمایش وب سایت</span>
					</a>
				</li>
			</ul>
		</div>
	</div>
	<!-- /main navbar -->


	<!-- Page container -->
	<div class="page-container login-container">

		<!-- Page content -->
		<div class="page-content">

			<!-- Main content -->
			<div class="content-wrapper">

				<!-- Content area -->
				<div class="content">

					<!-- Registration form -->
                     <?php 
					 echo $tNGs->getErrorMsg();
					 ?>

					<form id="form1" method="post" action="<?php echo KT_escapeAttribute(KT_getFullUri()); ?>" enctype="multipart/form-data">
						<div class="row">
							<div class="col-lg-6 col-lg-offset-3">
								<div class="panel registration-form">
									<div class="panel-body">
										<div class="text-center">
											<div class="icon-object border-primary text-primary"><i class="icon-hammer-wrench"></i></div>
											<h5 class="content-group-lg">تنظیمات اولیه کنترل پنل</h5>
										</div>

										<div class="form-group has-feedback">
											<input id="domain" name="domain" type="text" class="form-control text-right" placeholder="Domain Name" value="<?php if($row_rsstp['domain']!='') echo KT_escapeAttribute($row_rsstp['domain']); ?>" />
											<div class="form-control-feedback">
												<i class="icon-earth text-muted"></i>
											</div>
										</div>

										<div class="row">
											<div class="col-md-6">
												<div class="form-group has-feedback has-feedback-left">
													<input id="name" name="name" type="text" class="form-control" placeholder="نام پورتال" value="<?php if($row_rsstp['name']!='') echo KT_escapeAttribute($row_rsstp['name']); else echo 'پورتال اصلی'; ?>" />
													<div class="form-control-feedback">
														<i class="icon-file-text text-muted"></i>
													</div>
												</div>
											</div>

											<div class="col-md-6">
												<div class="form-group has-feedback">
													<input id="createdate" name="createdate" type="text" class="form-control text-right" placeholder="Create Date" value="<?php if($row_rsstp['createdate']!='') echo KT_escapeAttribute($row_rsstp['createdate']); else echo jdate('Y/m/d'); ?>" />
													<div class="form-control-feedback">
														<i class="icon-calendar text-muted"></i>
													</div>
												</div>
											</div>
										</div>
                                        
                                        <div class="row">
											<div class="col-md-12">
                                            	<label class="text-danger">
													در صورت نیاز به حذف پلاگین، آن را انتخاب نمایید:  
												</label>
                                            </div>
                                        </div>

<?php 
$listdbtables = array_column(mysqli_fetch_all($cn->query('SHOW TABLES')),0);

/* do {  
echo $row_Recordsetadmcat['name'];
} while ($row_Recordsetadmcat = mysqli_fetch_assoc($Recordsetadmcat));
 */

										//for($i=0;$i<=count($listdbtables);$i+=2) {
											do { ?>
											<div class="col-md-6">
                                                <div class="form-group">
                                                    <div class="checkbox">
                                                        <label>
                                                            <input id="tblslc" name="tblslc[]" type="checkbox" class="styled" value="<?php echo $row_Recordsetadmcat['id']; ?>" />
                                                            <?php echo $row_Recordsetadmcat['name']; ?>
                                                        </label>
                                                    </div>
                                                </div>
                                            </div>
<?php /*                                             <?php if($listdbtables[$i+1]!='') {?>
											<div class="col-md-6">
                                                <div class="form-group">
                                                    <div class="checkbox">
                                                        <label>
                                                            <input type="checkbox" class="styled" checked="checked">
                                                            <?php echo $listdbtables[$i+1]; ?>
                                                        </label>
                                                    </div>
                                                </div>
                                            </div>
                                            <?php } ?>
 */?><?php } while ($row_Recordsetadmcat = mysqli_fetch_assoc($Recordsetadmcat));?>
                                        
										

										
										<div class="col-md-12">
                                            <div class="text-right">
                                                <button id="KT_Update1" name="KT_Update1" type="submit" class="btn bg-primary btn-labeled btn-labeled-right ml-10"><b><i class="icon-plus3"></i></b> ثبت تنظیمات</button>
                                            </div>
                                        </div>    
									</div>
								</div>
							</div>
						</div>
					</form>
					<!-- /registration form -->


					<!-- Footer -->
					<?php include('layouts/footer.php');?>
					<!-- /footer -->

				</div>
				<!-- /content area -->

			</div>
			<!-- /main content -->

		</div>
		<!-- /page content -->

	</div>
	<!-- /page container -->

</body>
</html>
