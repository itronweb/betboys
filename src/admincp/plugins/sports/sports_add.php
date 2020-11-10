<?php 
require_once('../../checklogin.php'); 

// Load the common classes
require_once('../../includes/common/KT_common.php');

// Load the tNG classes
require_once('../../includes/tng/tNG.inc.php');

// Make a transaction dispatcher instance
$tNGs = new tNG_dispatcher("");

// Make unified connection variable
$conn_cn = new KT_connection($cn, $database_cn);

// Start trigger
$formValidation = new tNG_FormValidation();
$formValidation->addField("name_en", true, "text", "", "", "", "لطفا نام را وارد نمایید.");
$formValidation->addField("name", true, "text", "", "", "", "لطفا نام را وارد نمایید.");

$formValidation->addField("name_inplay", true, "text", "", "", "", "لطفا آدرس پیش بینی زنده را وارد نمایید.");
$formValidation->addField("name_prematch", true, "text", "", "", "", "لطفا آدرس پیش بینی قبل از بازی را وارد نمایید.");
$formValidation->addField("name_result", true, "text", "", "", "", "لطفا آدرس نتیجه بازی را وارد نمایید.");

$formValidation->addField("is_inplay", true, "text", "", "", "", "لطفا وضعیت پیش بینی زنده را انتخاب نمایید.");
$formValidation->addField("is_upcoming", true, "text", "", "", "", "لطفا وضعیت پیش بینی قبل بازی را وارد نمایید.");

$formValidation->addField("default_bookmaker", true, "text", "", "", "", "لطفا bookmaker پیش فرض را انتحاب کنید.");
$formValidation->addField("sort", true, "text", "", "", "", "لطفا مرتب سازی را وارد نمایید.");
$formValidation->addField("logo", true, "text", "", "", "", "لطفا  تصویر ورزش را وارد نمایید.");
$formValidation->addField("status", true, "text", "", "", "", "لطفا وضعیت را مشخص نمایید.");
$tNGs->prepareValidation($formValidation);
// End trigger

//start Trigger_ImageUpload trigger
//remove this line if you want to edit the code by hand 
$ImgMaxSize=1500;
$ImgAllowedExtensions="png";

function Trigger_ImageUpload(&$tNG) {
  global $ImgMaxSize, $ImgAllowedExtensions;
  
  $uploadObj = new tNG_ImageUpload($tNG);
  $uploadObj->setFormFieldName("logo");
  $uploadObj->setDbFieldName("logo");
  $uploadObj->setFolder("../../../attachment/sport_logo/");
  $uploadObj->setResize("true", 600, 600);
  $uploadObj->setMaxSize($ImgMaxSize);
  $uploadObj->setAllowedExtensions($ImgAllowedExtensions);
  $uploadObj->setRename("auto");
  return $uploadObj->Execute();
}
//end Trigger_ImageUpload trigger



mysqli_select_db($cn,$database_cn);
$query_Recordsetsprt = sprintf("SELECT max(sort) as maxsort FROM sports");
$Recordsetsprt = mysqli_query($cn,$query_Recordsetsprt) or die(mysqli_error($cn));
$row_Recordsetsprt = mysqli_fetch_assoc($Recordsetsprt);

$name = strtolower($_POST['name']);
$name_en = strtolower($_POST['name_en']);
$name_inplay = strtolower($_POST['name_inplay']);
$name_prematch = strtolower($_POST['name_prematch']);
$name_result = strtolower($_POST['name_result']);
$default_bookmaker = strtolower($_POST['default_bookmaker']);



// Make an insert transaction instance
$ins_sprt = new tNG_insert($conn_cn);
$tNGs->addTransaction($ins_sprt);
// Register triggers
$ins_sprt->registerTrigger("STARTER", "Trigger_Default_Starter", 1, "POST", "KT_Insert1");
$ins_sprt->registerTrigger("BEFORE", "Trigger_Default_FormValidation", 10, $formValidation);
$ins_sprt->registerTrigger("END", "Trigger_Default_Redirect", 99, "sports_list.php");
$ins_sprt->registerTrigger("AFTER", "Trigger_ImageUpload", 97);


// Add columns
$ins_sprt->setTable("sports");
$ins_sprt->addColumn("name", "STRING_TYPE", "VALUE" , $name);
$ins_sprt->addColumn("name_en", "STRING_TYPE", "VALUE", $name_en);
$ins_sprt->addColumn("name_inplay", "STRING_TYPE", "VALUE", $name_inplay);
$ins_sprt->addColumn("name_prematch", "STRING_TYPE","VALUE", $name_prematch);
$ins_sprt->addColumn("name_result", "STRING_TYPE", "VALUE", $name_result);
$ins_sprt->addColumn("is_inplay", "STRING_TYPE", "POST", "is_inplay");
$ins_sprt->addColumn("is_upcoming", "STRING_TYPE", "POST", "is_upcoming");
$ins_sprt->addColumn("is_result", "STRING_TYPE", "VALUE", "1");
$ins_sprt->addColumn("default_bookmaker", "STRING_TYPE", "VALUE", $default_bookmaker);
$ins_sprt->addColumn("logo", "FILE_TYPE", "FILES", "logo");
$ins_sprt->addColumn("sort", "NUMERIC_TYPE", "POST", "sort");
$ins_sprt->addColumn("status", "STRING_TYPE", "POST", "status");
$ins_sprt->setPrimaryKey("id", "NUMERIC_TYPE");

// Execute all the registered transactions
$tNGs->executeTransactions();

// Get the transaction recordset
$rssprt = $tNGs->getRecordset("sports");
$row_rssprt = mysqli_fetch_assoc($rssprt);
$totalRows_rssprt = mysqli_num_rows($rssprt);
?>

<!DOCTYPE html>
<html lang="fa" dir="rtl">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />

	<?php include('../../layouts/seo.php');?>
    
	<!-- Theme JS files -->
	<script type="text/javascript" src="../../assets/js/core/app.js"></script>
	<script type="text/javascript" src="../../assets/js/plugins/forms/styling/uniform.min.js"></script>

	<script type="text/javascript" src="../../assets/js/plugins/forms/selects/bootstrap_select.min.js"></script>
	<script type="text/javascript" src="../../assets/js/plugins/forms/selects/select2.min.js"></script>

<!--
	<script type="text/javascript" src="../../assets/js/plugins/styling/uniform.min.js"></script>
	
-->
	<script type="text/javascript" src="../../assets/js/plugins/forms/tags/tagsinput.min.js"></script>
	<script type="text/javascript" src="../../assets/js/plugins/forms/tags/tokenfield.min.js"></script>

	<script type="text/javascript" src="../../assets/js/plugins/forms/inputs/touchspin.min.js"></script>

<script type="text/javascript" src="../../assets/js/plugins/jquery.maskedinput.js"></script>

<!--	<script type="text/javascript" src="../../assets/js/plugins/uploaders/fileinput.mint.js"></script>-->
	
	
	<script type="text/javascript" src="assets/js/sports_page.js"></script>

    <title><?php echo $stitle; ?></title>
    
    <link href="../../includes/skins/mxkollection3.css" rel="stylesheet" type="text/css" media="all" />
    <script src="../../includes/common/js/base.js" type="text/javascript"></script>
    <script src="../../includes/common/js/utility.js" type="text/javascript"></script>
    <?php echo $tNGs->displayValidationRules();?>
</head>

<body>


	<!-- Main navbar -->
	<?php include('../../layouts/mainnav.php');?>
	<!-- /main navbar -->


	<!-- Page container -->
	<div class="page-container">

		<!-- Page content -->
		<div class="page-content">

			<!-- Main sidebar -->
			<?php include('../../layouts/sidebar.php');?>
			<!-- /main sidebar -->


<div class="content-wrapper">

				<!-- Page header -->
				<div class="page-header">
					<div class="page-header-content">
						<div class="page-title">
							<h4><i class="icon-arrow-right6 position-left"></i> <?php echo $cur_pagecat; ?></h4>
						</div>
					</div>

					<div class="breadcrumb-line">
						<ul class="breadcrumb">
							<li><a href="<?php echo $nav_path.'index.php';?>"><i class="icon-home2 position-left"></i> داشبورد</a></li>
							<li><a href="<?php echo $cur_pageurl; ?>"><?php echo $cur_pagecat; ?></a></li>
							<li class="active"><?php echo $cur_pagename; ?></li>
						</ul>
					</div>
				</div>
				<!-- /page header -->


				<!-- Content area -->
				<div class="content">

			<!-- Main content -->
            
 <?php
	echo $tNGs->getErrorMsg();
?>
					<!-- 2 columns form -->
					<form id="form1" class="form-horizontal" method="post" action="<?php echo KT_escapeAttribute(KT_getFullUri()); ?>" enctype="multipart/form-data">
							
						<div class="panel panel-flat">
							<div class="panel-heading">
								<h5 class="panel-title"><?php echo $cur_pagename; ?></h5>
								<div class="heading-elements">
									<ul class="icons-list">
				                		<li><a data-action="collapse"></a></li>
				                		<li><a data-action="reload"></a></li>
				                		<li><a data-action="close"></a></li>
				                	</ul>
			                	</div>
							</div>



							<div class="panel-body">
								<div class="row">

<?php 
if(GetSQLValueString($_GET['add'], "int")==1){
?>
									<div class="alert alert-success alert-styled-left alert-arrow-left alert-bordered">
										<button type="button" class="close" data-dismiss="alert"><span>&times;</span><span class="sr-only">Close</span></button>
										 درج با موفقیت انجام شد 
								    </div>
<?php 
}?>
						
									<div class="col-md-6">
										<fieldset>
											<div class="form-group">
												<label class="col-lg-4 control-label" for="name">نام:</label>
												<div class="col-lg-8">
													<input type="text" name="name" id="name" value="<?php echo KT_escapeAttribute($row_rssprt['name']); ?>" class="form-control" autocomplete="off" tabindex="1" />
												</div>
											</div>
										</fieldset>
									</div>
									
                                    <div class="col-md-6">
										<fieldset>
                                        	
                                                <div class="form-group">
                                                    <label class="col-lg-4 control-label" for="name_en">نام انگلیسی:</label>
                                                    <div class="col-lg-8">
                                                        <input type="text" name="name_en" id="name_en" value="<?php echo KT_escapeAttribute($row_rssprt['name_en']); ?>" class="form-control text-right flt-left" autocomplete="off" tabindex="2" />
                                                    </div>
                                                </div>				
										</fieldset>
									</div>
									
                                    <div class="col-md-6">
										<fieldset>
                                        	
                                                <div class="form-group">
                                                    <label class="col-lg-4 control-label" for="default_bookmaker">bookmaker پیش فرض:</label>
                                                    <div class="col-lg-8">
                                                        <input type="text" name="default_bookmaker" id="default_bookmaker" value="<?php echo KT_escapeAttribute($row_rssprt['default_bookmaker']); ?>" class="form-control text-right flt-left" autocomplete="off" tabindex="3" placeholder = "bookmaker1-bookmaker2"/> 
														<span class="help-block">bokmaker را با (-) از هم جدا کنید.</span>
                                                    </div>
                                                </div>				
										</fieldset>
									</div>
									
									<div class="col-md-6">
										<fieldset>
											<div class="form-group">
												<label class="col-lg-4 control-label" for="name_inplay">url پیش بینی زنده:</label>
												<div class="col-lg-8">
													<input type="text" name="name_inplay" id="name_inplay" value="<?php echo KT_escapeAttribute($row_rssprt['name_inplay']); ?>" class="form-control" autocomplete="off" tabindex="4" />
												</div>
											</div>
										</fieldset>
									</div>
									
									<div class="col-md-12"></div>
										 
									
									<div class="col-md-6">
										<fieldset>
											<div class="form-group">
												<label class="col-lg-4 control-label" for="name_prematch">url پیش بینی قبل بازی:</label>
												<div class="col-lg-8">
													<input type="text" name="name_prematch" id="name_prematch" value="<?php echo KT_escapeAttribute($row_rssprt['name_prematch']); ?>" class="form-control" autocomplete="off" tabindex="5" />
												</div>
											</div>
										</fieldset>
									</div>
									
									<div class="col-md-6">
										<fieldset>
											<div class="form-group">
												<label class="col-lg-4 control-label" for="name_result">url نتیجه بازی:</label>
												<div class="col-lg-8">
													<input type="text" name="name_result" id="name_result" value="<?php echo KT_escapeAttribute($row_rssprt['name_result']); ?>" class="form-control" autocomplete="off" tabindex="6" />
												</div>
											</div>
										</fieldset>
									</div>
									
									<div class="col-md-12"></div>
									
									<div class="col-md-6">
										<fieldset>
											<div class="form-group">
												<label class="col-lg-4 control-label" for="logo">تصویر بازی:</label>
												<div class="col-lg-8">
													<input name="logo" id="logo" type="file" class="file-styled" value="" tabindex="7" />
													
<!--													<input type="file" class="bootstrap-uploader">-->

													 <span class="help-block">فرمتهای مجاز: <?php echo $ImgAllowedExtensions; ?>. حداکثر اندازه فایل: <?php echo $ImgMaxSize/1000; ?>Mb</span>
												</div>
											</div>
										</fieldset>
									</div>

									<div class="col-md-6">
										<fieldset>
											
                                                <div class="form-group">
                                                    <label class="col-lg-4 control-label" for="sort">مرتب سازی:</label>
                                                    <div class="col-lg-8">
                                                        <input type="text" name="sort" id="sort" value="<?php echo KT_escapeAttribute(++$row_Recordsetsprt['maxsort']); ?>" class="touchspin-empty text-center number" autocomplete="off" tabindex="8" />
                                                    </div>
                                                </div> 

										</fieldset>
									</div>
									
									<div class="col-md-12"></div>
									
									<div class="col-md-6">
										<fieldset>
											
											<div class="form-group">
                                                <label class="col-lg-4 control-label" for="is_upcoming">قبل بازی:</label>
                                                <div class="col-lg-8">
                                                    <label class="radio-inline">
                                                        <input name="is_upcoming" id="is_upcoming" type="radio" class="styled" value="1" <?php if (!(strcmp(1, KT_escapeAttribute($row_rssprt['is_upcoming'])))) {echo 'checked="checked"';} ?> tabindex="9" />
                                                        فعال
                                                    </label>
                                                    <label class="radio-inline">
                                                        <input name="is_upcoming" id="is_upcoming" type="radio" class="styled" value="0" checked="checked" <?php if (!(strcmp(0, KT_escapeAttribute($row_rssprt['is_upcoming'])))) {echo 'checked="checked"';} ?> tabindex="10" />
                                                        غیرفعال
                                                    </label>
                                                </div>
                                            </div>				
										</fieldset>
									</div>
									
									
									<div class="col-md-6">
										<fieldset>
											
											<div class="form-group">
                                                <label class="col-lg-4 control-label" for="is_inplay">زنده:</label>
                                                <div class="col-lg-8">
                                                    <label class="radio-inline">
                                                        <input name="is_inplay" id="is_inplay" type="radio" class="styled" value="1" <?php if (!(strcmp(1, KT_escapeAttribute($row_rssprt['is_inplay'])))) {echo 'checked="checked"';} ?> tabindex="11" />
                                                        فعال
                                                    </label>
                                                    <label class="radio-inline">
                                                        <input name="is_inplay" id="is_inplay" type="radio" class="styled" value="0" checked="checked" <?php if (!(strcmp(0, KT_escapeAttribute($row_rssprt['is_inplay'])))) {echo 'checked="checked"';} ?> tabindex="12" />
                                                        غیرفعال
                                                    </label>
                                                </div>
                                            </div>				
										</fieldset>
									</div>
									
									
									
									<div class="col-md-6">
										<fieldset>
											
                                            <div class="form-group">
                                                <label class="col-lg-4 control-label" for="status">وضعیت:</label>
                                                <div class="col-lg-8">
                                                    <label class="radio-inline">
                                                        <input name="status" id="status" type="radio" class="styled" value="1" checked="checked" <?php if (!(strcmp(1, KT_escapeAttribute($row_rssprt['status'])))) {echo 'checked="checked"';} ?> tabindex="13" />
                                                        فعال
                                                    </label>
                                                    <label class="radio-inline">
                                                        <input name="status" id="status" type="radio" class="styled" value="0" <?php if (!(strcmp(0, KT_escapeAttribute($row_rssprt['status'])))) {echo 'checked="checked"';} ?> tabindex="14" />
                                                        غیرفعال
                                                    </label>
                                                </div>
                                            </div>  				
										</fieldset>
									</div>
							</div>
								<div class="text-right">
                                    <button id="KT_Insert1" name="KT_Insert1" type="submit" class="btn btn-primary" tabindex="7">ثبت اطلاعات <i class="icon-arrow-left13 position-right"></i></button>
                                    <button id="reset" name="reset" type="reset" class="btn btn-warning" tabindex="8">بازنویسی <i class="icon-reset position-right"></i></button>
								</div>
						</div>
                        </div>
					</form>
                        
                    </div>
					<!-- /2 columns form -->
			
			<!-- /main content -->

					<!-- Footer -->
                    <?php include('../../layouts/footer.php');?>
					<!-- /footer -->

				</div>
				<!-- /content area -->

			</div>

		</div>
		<!-- /page content -->

	</div>
	<!-- /page container -->

</body>
</html>
                            
<?php
mysqli_free_result($rs1);
?>

