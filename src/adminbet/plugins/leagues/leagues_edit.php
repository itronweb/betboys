<?php 
require_once('../../checklogin.php'); 

// Load the common classes
require_once('../../includes/common/KT_common.php');

// Load the tNG classes
require_once('../../includes/tng/tNG.inc.php');

$id= decrypt($_GET['id'],session_id()."leag");

// Make a transaction dispatcher instance
$tNGs = new tNG_dispatcher("");

// Make unified connection variable
$conn_cn = new KT_connection($cn, $database_cn);

// Start trigger
$formValidation = new tNG_FormValidation();
$formValidation->addField("leagues_name_fa", true, "text", "", "", "", "لطفا نام فارسی لیگ را وارد نمایید.");
$formValidation->addField("country_name_fa", true, "text", "", "", "", "لطفا نام فارسی کشور را وارد نمایید.");
$formValidation->addField("sort", true, "text", "", "", "", "لطفا مرتب سازی را وارد نمایید.");
$tNGs->prepareValidation($formValidation);
// End trigger

// Make an update transaction instance
$upd_adm = new tNG_update($conn_cn);
$tNGs->addTransaction($upd_adm);

// Register triggers
$upd_adm->registerTrigger("STARTER", "Trigger_Default_Starter", 1, "POST", "KT_Update1");
$upd_adm->registerTrigger("BEFORE", "Trigger_Default_FormValidation", 10, $formValidation);

// Add columns
$upd_adm->setTable("`leagues`");
$upd_adm->addColumn("leagues_name_fa", "STRING_TYPE", "POST", "leagues_name_fa");
$upd_adm->addColumn("country_name_fa", "STRING_TYPE", "POST", "country_name_fa");
$upd_adm->addColumn("country_name_en", "STRING_TYPE", "POST", "country_name_en");
$upd_adm->addColumn("leagues_name_en", "STRING_TYPE", "POST", "leagues_name_en");
$upd_adm->addColumn("sort", "NUMERIC_TYPE", "POST", "sort");
$upd_adm->addColumn("country_id", "STRING_TYPE", "POST", "country_id");
$upd_adm->setPrimaryKey("id", "NUMERIC_TYPE", "VALUE", $id);

$upd_cou = new tNG_update($conn_cn);
$tNGs->addTransaction($upd_cou);

// Register triggers
$upd_cou->registerTrigger("STARTER", "Trigger_Default_Starter", 1, "POST", "KT_Update1");

// Add columns
$upd_cou->setTable("`country`");
$upd_cou->addColumn("country_name_fa", "STRING_TYPE", "POST", "country_name_fa");
$upd_cou->setPrimaryKey("country_id", "NUMERIC_TYPE", "POST", "country_id");

$upd_allc = new tNG_update($conn_cn);
$tNGs->addTransaction($upd_allc);

// Register triggers
$upd_allc->registerTrigger("STARTER", "Trigger_Default_Starter", 1, "POST", "KT_Update1");
$upd_allc->registerTrigger("END", "Trigger_Default_Redirect", 99, $_POST['referurl']);

// Add columns
$upd_allc->setTable("`leagues`");
$upd_allc->addColumn("country_name_fa", "STRING_TYPE", "POST", "country_name_fa");
$upd_allc ->addColumn("country_name_en", "STRING_TYPE", "POST", "country_name_en");
$upd_allc->setPrimaryKey("country_id", "NUMERIC_TYPE", "POST", "country_id");



// Execute all the registered transactions
$tNGs->executeTransactions();

// Get the transaction recordset
$rsadm = $tNGs->getRecordset("`leagues`");
$row_rsadm = mysqli_fetch_assoc($rsadm);
$totalRows_rsadm = mysqli_num_rows($rsadm);
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

	<script type="text/javascript" src="../../assets/js/plugins/forms/tags/tagsinput.min.js"></script>
	<script type="text/javascript" src="../../assets/js/plugins/forms/tags/tokenfield.min.js"></script>
	<script type="text/javascript" src="../../assets/js/plugins/forms/inputs/touchspin.min.js"></script>

	<script type="text/javascript" src="../../ckeditor/ckeditor.js"></script>
	<script type="text/javascript" src="assets/js/leagues_page.js"></script>

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
										 ویرایش با موفقیت انجام شد 
								    </div>
<?php 
}?>
<h6 class="panel-title"><?php echo "  لیگ ".$row_rsadm['leagues_name_en']." - کشور";?><?php echo $row_rsadm['country_name_en'];?></h6><br>
									<div class="col-md-6">
										<fieldset>
											
                                            
                                        	<div class="form-group">
												<label class="col-lg-3 control-label" for="leagues_name_fa">نام فارسی لیگ:</label>
												<div class="col-lg-9">
													<input type="text" name="leagues_name_fa" id="leagues_name_fa" value="<?php echo KT_escapeAttribute($row_rsadm['leagues_name_fa']); ?>" class="form-control " autocomplete="off" tabindex="3" />

<?php echo $tNGs->displayFieldHint("leagues_name_fa");?> <?php echo $tNGs->displayFieldError("`leagues`", "leagues_name_fa"); ?>    
												</div>
											</div>
                                           <div class="form-group">
												<label class="col-lg-3 control-label" for="country_name_fa">نام فارسی کشور:</label>
												<div class="col-lg-9">
													<input type="text" name="country_name_fa" id="country_name_fa" value="<?php echo KT_escapeAttribute($row_rsadm['country_name_fa']); ?>" class="form-control " autocomplete="off" tabindex="3" />

<?php echo $tNGs->displayFieldHint("country_name_fa");?> <?php echo $tNGs->displayFieldError("`leagues`", "country_name_fa"); ?>    
												</div>
											</div>
											
											<div class="form-group">
												<label class="col-lg-3 control-label" for="sort">مرتب سازی:</label>
												<div class="col-lg-9">
													<input type="text" name="sort" id="sort" value="<?php echo KT_escapeAttribute($row_rsadm['sort']); ?>" class="touchspin-empty text-center number" autocomplete="off" tabindex="4" />
												</div>
											</div>
											
											
                                            <input type="hidden" name="country_name_en" id="country_name_en" value="<?php echo KT_escapeAttribute($row_rsadm['country_name_en']); ?>" />
                                            <input type="hidden" name="leagues_name_en" id="leagues_name_en" value="<?php echo KT_escapeAttribute($row_rsadm['leagues_name_en']); ?>" />
											<input type="hidden" name="country_id" id="country_id" value="<?php echo KT_escapeAttribute($row_rsadm['country_id']); ?>" />
											</div>
                                          
                                           
                                            
                                            
										</fieldset>
									</div>

								<div class="text-right">
                                    <button id="KT_Update1" name="KT_Update1" type="submit" class="btn btn-primary" tabindex="5">ویرایش اطلاعات <i class="icon-arrow-left13 position-right"></i></button>
								</div>
								
								
							</div>
								
						</div>
                        </div>
                        <input type="hidden" name="referurl" value="<?php echo $_SERVER['HTTP_REFERER'] ?>" />
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

