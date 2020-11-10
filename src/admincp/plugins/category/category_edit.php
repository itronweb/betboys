<?php 
require_once('../../checklogin.php'); 

// Load the common classes
require_once('../../includes/common/KT_common.php');

// Load the tNG classes
require_once('../../includes/tng/tNG.inc.php');

$id= decrypt($_GET['id'],session_id()."cat");

// Make a transaction dispatcher instance
$tNGs = new tNG_dispatcher("");

// Make unified connection variable
$conn_cn = new KT_connection($cn, $database_cn);

// Start trigger
$formValidation = new tNG_FormValidation();
$formValidation->addField("code", true, "text", "", "", "", "لطفا کد را وارد نمایید.");
$formValidation->addField("name", true, "text", "", "", "", "لطفا عنوان را وارد نمایید.");
$formValidation->addField("level", true, "text", "", "", "", "لطفا سطح را وارد نمایید.");
$formValidation->addField("memtype", true, "text", "", "", "", "لطفا نوع اعضا را وارد نمایید.");
$formValidation->addField("contenttype", true, "text", "", "", "", "لطفا نوع مطلب را وارد نمایید.");
$formValidation->addField("portalid", true, "text", "", "", "", "لطفا پورتال را وارد نمایید.");
$formValidation->addField("sort", true, "text", "", "", "", "لطفا مرتب سازی را وارد نمایید.");
$formValidation->addField("status", true, "text", "", "", "", "لطفا وضعیت را مشخص نمایید.");
$tNGs->prepareValidation($formValidation);
// End trigger


//start Trigger_ImageUpload trigger
//remove this line if you want to edit the code by hand 
$ImgMaxSize=1500;
$ImgAllowedExtensions="gif, jpg, jpe, jpeg, png";

function Trigger_ImageUpload(&$tNG) {
  global $ImgMaxSize, $ImgAllowedExtensions;
  
  $uploadObj = new tNG_ImageUpload($tNG);
  $uploadObj->setFormFieldName("image");
  $uploadObj->setDbFieldName("image");
  $uploadObj->setFolder("../../../attachment/category/");
  $uploadObj->setResize("true", 600, 600);
  $uploadObj->setMaxSize($ImgMaxSize);
  $uploadObj->setAllowedExtensions($ImgAllowedExtensions);
  $uploadObj->setRename("auto");
  return $uploadObj->Execute();
}
//end Trigger_ImageUpload trigger


//start Trigger_ImageUpload trigger
//remove this line if you want to edit the code by hand 
$HdrMaxSize=1500;
$HdrAllowedExtensions="gif, jpg, jpe, jpeg, png";

function Trigger_HeaderUpload(&$tNG) {
  global $HdrMaxSize, $HdrAllowedExtensions;
  
  $uploadObj = new tNG_ImageUpload($tNG);
  $uploadObj->setFormFieldName("header");
  $uploadObj->setDbFieldName("header");
  $uploadObj->setFolder("../../../attachment/category/");
  $uploadObj->setResize("true", 600, 600);
  $uploadObj->setMaxSize($HdrMaxSize);
  $uploadObj->setAllowedExtensions($HdrAllowedExtensions);
  $uploadObj->setRename("auto");
  return $uploadObj->Execute();
}
//end Trigger_ImageUpload trigger




mysqli_select_db($cn,$database_cn);
$query_Recordsetpid = "SELECT * FROM setting";
$Recordsetpid = mysqli_query($cn,$query_Recordsetpid) or die(mysqli_error($cn));
$row_Recordsetpid = mysqli_fetch_assoc($Recordsetpid);
$totalRows_Recordsetpid = mysqli_num_rows($Recordsetpid);


mysqli_select_db($cn,$database_cn);
$query_Recordsetctt = "SELECT * FROM contenttype WHERE status='1'";
$Recordsetctt = mysqli_query($cn,$query_Recordsetctt) or die(mysqli_error($cn));
$row_Recordsetctt = mysqli_fetch_assoc($Recordsetctt);
$totalRows_Recordsetctt = mysqli_num_rows($Recordsetctt);


mysqli_select_db($cn,$database_cn);
$query_Recordsetlvl = "SELECT code, name FROM category ORDER BY code DESC";
$Recordsetlvl = mysqli_query($cn,$query_Recordsetlvl) or die(mysqli_error($cn));
$row_Recordsetlvl = mysqli_fetch_assoc($Recordsetlvl);
$totalRows_Recordsetlvl = mysqli_num_rows($Recordsetlvl);


// Make an update transaction instance
$upd_cat = new tNG_update($conn_cn);
$tNGs->addTransaction($upd_cat);

// Register triggers
$upd_cat->registerTrigger("STARTER", "Trigger_Default_Starter", 1, "POST", "KT_Update1");
$upd_cat->registerTrigger("BEFORE", "Trigger_Default_FormValidation", 10, $formValidation);
$upd_cat->registerTrigger("END", "Trigger_Default_Redirect", 99, $_POST['referurl']);
$upd_cat->registerTrigger("AFTER", "Trigger_ImageUpload", 97);
$upd_cat->registerTrigger("AFTER", "Trigger_HeaderUpload", 97);

// Add columns
$upd_cat->setTable("category");
$upd_cat->addColumn("code", "STRING_TYPE", "POST", "code");
$upd_cat->addColumn("pagename", "STRING_TYPE", "POST", "pagename");
$upd_cat->addColumn("name", "STRING_TYPE", "POST", "name");
$upd_cat->addColumn("type", "STRING_TYPE", "VALUE", "1");
$upd_cat->addColumn("level", "NUMERIC_TYPE", "POST", "level");
$upd_cat->addColumn("description", "STRING_TYPE", "POST", "description");
$upd_cat->addColumn("header", "FILE_TYPE", "FILES", "header");
$upd_cat->addColumn("image", "FILE_TYPE", "FILES", "image");
$upd_cat->addColumn("style", "STRING_TYPE", "POST", "style");
$upd_cat->addColumn("contenttype", "NUMERIC_TYPE", "POST", "contenttype");
$upd_cat->addColumn("keywords", "STRING_TYPE", "POST", "keywords");
$upd_cat->addColumn("memtype", "STRING_TYPE", "POST", "memtype");
$upd_cat->addColumn("portalid", "NUMERIC_TYPE", "POST", "portalid");
$upd_cat->addColumn("showinmenu", "CHECKBOX_1_0_TYPE", "POST", "showinmenu", "0");
$upd_cat->addColumn("showinhome", "CHECKBOX_1_0_TYPE", "POST", "showinhome", "0");
$upd_cat->addColumn("sort", "NUMERIC_TYPE", "POST", "sort");
$upd_cat->addColumn("status", "STRING_TYPE", "POST", "status");
$upd_cat->setPrimaryKey("id", "NUMERIC_TYPE", "VALUE", $id);

// Execute all the registered transactions
$tNGs->executeTransactions();

// Get the transaction recordset
$rscat = $tNGs->getRecordset("category");
$row_rscat = mysqli_fetch_assoc($rscat);
$totalRows_rscat = mysqli_num_rows($rscat);
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

	<script type="text/javascript" src="../../ckeditor/ckeditor.js"></script>
	<script type="text/javascript" src="assets/js/category_page.js"></script>

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

									<div class="col-md-6">
										<fieldset>
											<div class="form-group">
												<label class="col-lg-3 control-label" for="code">کد:</label>
												<div class="col-lg-9">
													<input type="text" name="code" id="code" value="<?php echo KT_escapeAttribute($row_rscat['code']); ?>" class="form-control" autocomplete="off" tabindex="1" />

<?php echo $tNGs->displayFieldHint("code");?> <?php echo $tNGs->displayFieldError("category", "code"); ?>    
												</div>
											</div>

                                            <div class="form-group">
												<label class="col-lg-3 control-label" for="pagename">نام صفحه:</label>
												<div class="col-lg-9">
													<input type="text" name="pagename" id="pagename" value="<?php echo KT_escapeAttribute($row_rscat['pagename']); ?>" class="form-control" autocomplete="off" tabindex="3" />
												</div>
                                            </div>
                                            
											<div class="form-group">
												<label class="col-lg-3 control-label" for="level">سطح:</label>
												<div class="col-lg-9">
<select name="level" id="level" class="form-control" tabindex="5">
                                <option value=""></option>
                                <option value="0" <?php if (!(strcmp(0, $row_rscat['level']))) {echo "selected=\"selected\"";} ?>>گروه اصلی</option>
                                  <?php
								  if($totalRows_Recordsetlvl>0){
do { 
$categorytree = "";	getCategoryTree($cn,'category',$row_Recordsetlvl['code']); 
?>
                                  <option value="<?php echo $row_Recordsetlvl['code']?>" <?php if (!(strcmp($row_Recordsetlvl['code'], $row_rscat['level']))) {echo "selected=\"selected\"";} ?>><?php echo $categorytree;?></option>
                                  <?php
} while ($row_Recordsetlvl = mysqli_fetch_assoc($Recordsetlvl));
  $rows = mysqli_num_rows($Recordsetlvl);
  if($rows > 0) {
      mysqli_data_seek($Recordsetlvl, 0);
	  $row_Recordsetlvl = mysqli_fetch_assoc($Recordsetlvl);
  }
  
  }
?>                                    
                                  </select>
<?php echo $tNGs->displayFieldHint("level");?> <?php echo $tNGs->displayFieldError("category", "level"); ?>    
												</div>
											</div>
                                            
                                            <div class="form-group">
												<label class="col-lg-3 control-label" for="contenttype">نوع مطلب:</label>
												<div class="col-lg-9">
									                            <select name="contenttype" id="contenttype" class="form-control" tabindex="7">
            <option value=""></option>
              <?php
do {  
?>
              <option value="<?php echo $row_Recordsetctt['id']?>" <?php if (!(strcmp($row_Recordsetctt['id'], $row_rscat['contenttype']))) {echo "selected=\"selected\"";} ?>><?php echo $row_Recordsetctt['name']?></option>
              <?php
} while ($row_Recordsetctt = mysqli_fetch_assoc($Recordsetctt));
  $rows = mysql_num_rows($Recordsetctt);
  if($rows > 0) {
      mysqli_data_seek($Recordsetctt, 0);
	  $row_Recordsetctt = mysqli_fetch_assoc($Recordsetctt);
  }
?>
									                            </select>
<?php echo $tNGs->displayFieldHint("contenttype");?> <?php echo $tNGs->displayFieldError("category", "contenttype"); ?>    
												</div>
											</div>
                                            
                                            <div class="form-group">
												<label class="col-lg-3 control-label" for="header">هدر :</label>
												<div class="media-body">
													<input name="header" id="header" type="file" class="file-styled" value="" tabindex="9" />
                                                    <span class="help-block">فرمتهای مجاز: <?php echo $HdrAllowedExtensions; ?>. حداکثر اندازه فایل: <?php echo $HdrMaxSize/1000; ?>Mb</span>
 												</div>
                                                <div class="media-right">
													<img src="../../<?php if($row_rscat['header']) echo '../attachment/category/'. KT_escapeAttribute($row_rscat['header']); else echo 'assets/images/placeholder.jpg';?>" style="width: 58px; height: 58px; border-radius: 2px;" alt="" />
												</div>
											</div>

											<div class="form-group">
												<div class="col-lg-12">
													<div class="checkbox">
													<label for="showinhome">در صفحه اصلی نمایش داده شود.
														<input name="showinhome" id="showinhome" type="checkbox" class="styled" value="1" <?php if (!(strcmp(KT_escapeAttribute($row_rscat['showinhome']),"1"))) {echo "checked";} ?> tabindex="11" />
													</label>
													</div>
												</div>
											</div>

										</fieldset>
									</div>

									<div class="col-md-6">
										<fieldset>
											<div class="form-group">
												<label class="col-lg-3 control-label" for="name">عنوان:</label>
												<div class="col-lg-9">
													<input type="text" name="name" id="name" value="<?php echo KT_escapeAttribute($row_rscat['name']); ?>" class="form-control" autocomplete="off" tabindex="2" />
<?php echo $tNGs->displayFieldHint("name");?> <?php echo $tNGs->displayFieldError("category", "name"); ?>    
												</div>
                                            </div>

											<div class="form-group">
												<label class="col-lg-3 control-label" for="style">استایل:</label>
												<div class="col-lg-9">
                                                    <input type="text" name="style" id="style" value="<?php echo KT_escapeAttribute($row_rscat['style']); ?>" class="form-control" autocomplete="off" tabindex="4" />
                                                    
												</div>
											</div>
                                            
                                            <div class="form-group">
												<label class="col-lg-3 control-label" for="memtype">نوع اعضا:</label>
												<div class="col-lg-9">
									                            <select name="memtype" id="memtype" class="form-control" tabindex="6">
									                                <option value=""></option> 
									                                <option value="0" <?php if (!(strcmp(0, $row_rscat['memtype']))) {echo "selected=\"selected\"";} ?>>همه</option> 
									                                <option value="1" <?php if (!(strcmp(1, $row_rscat['memtype']))) {echo "selected=\"selected\"";} ?>>اعضا</option> 
									                            </select>
<?php echo $tNGs->displayFieldHint("memtype");?> <?php echo $tNGs->displayFieldError("category", "memtype"); ?>    
												</div>
											</div>
                                            
                                            <div class="form-group">
												<label class="col-lg-3 control-label" for="portalid">پورتال:</label>
												<div class="col-lg-9">
									                            <select name="portalid" id="portalid" class="form-control" tabindex="8">
            <option value=""></option>
              <?php
do {  
?>
              <option value="<?php echo $row_Recordsetpid['id']?>"<?php if (!(strcmp($row_Recordsetpid['id'], $row_rscat['portalid']))) {echo "selected=\"selected\"";} ?>><?php echo $row_Recordsetpid['name']?></option>
              <?php
} while ($row_Recordsetpid = mysqli_fetch_assoc($Recordsetpid));
  $rows = mysql_num_rows($Recordsetpid);
  if($rows > 0) {
      mysqli_data_seek($Recordsetpid, 0);
	  $row_Recordsetpid = mysqli_fetch_assoc($Recordsetpid);
  }
?>
									                            </select>
<?php echo $tNGs->displayFieldHint("portalid");?> <?php echo $tNGs->displayFieldError("category", "portalid"); ?>    
												</div>
											</div>

                                            <div class="form-group">
												<label class="col-lg-3 control-label" for="image">تصویر :</label>
												<div class="media-body">
													<input name="image" id="image" type="file" class="file-styled" value="" tabindex="10" />
                                                    <span class="help-block">فرمتهای مجاز: <?php echo $ImgAllowedExtensions; ?>. حداکثر اندازه فایل: <?php echo $ImgMaxSize/1000; ?>Mb</span>
 												</div>
                                                <div class="media-right">
													<img src="../../<?php if($row_rscat['image']) echo '../attachment/category/'. KT_escapeAttribute($row_rscat['image']); else echo 'assets/images/placeholder.jpg';?>" style="width: 58px; height: 58px; border-radius: 2px;" alt="" />
												</div>
											</div>

                                            <div class="form-group">
												<div class="col-lg-12">
                                                    <div class="checkbox">
													<label for="showinmenu">در منو نمایش داده شود.
														<input name="showinmenu" id="showinmenu" type="checkbox" class="styled" value="1" <?php if (!(strcmp(KT_escapeAttribute($row_rscat['showinmenu']),"1"))) {echo "checked";} ?> tabindex="12" />
													</label>
													</div>
												</div>
											</div>
                                            
										</fieldset>
									</div>
                                    
                                    <div class="col-md-12">
										<fieldset>
											<div class="form-group">
												<label class="col-lg-1 control-label" for="description">توضیحات:</label>
												<div class="col-lg-11">
													<textarea name="description" dir="rtl" id="description" rows="4" cols="4" tabindex="13"><?php echo KT_escapeAttribute($row_rscat['description']); ?></textarea>
												</div>
											</div>

											<div class="form-group">
												<label class="col-lg-2 control-label" for="keywords">کلمات کلیدی:</label>
												<div class="col-lg-10">
                                                    <input name="keywords" id="keywords" type="text" class="form-control tokenfield" value="<?php echo KT_escapeAttribute($row_rscat['keywords']); ?>" autocomplete="off" tabindex="14" />
												</div>
											</div>

										</fieldset>
									</div>

                                    <div class="col-md-6">
										<fieldset>
                                            <div class="form-group">
												<label class="col-lg-3 control-label" for="sort">مرتب سازی:</label>
												<div class="col-lg-9">
                                                    <input type="text" name="sort" id="sort" value="<?php echo KT_escapeAttribute($row_rscat['sort']); ?>" class="form-control" autocomplete="off" tabindex="15" />
<?php echo $tNGs->displayFieldHint("sort");?> <?php echo $tNGs->displayFieldError("category", "sort"); ?>    
												</div>
											</div>
										</fieldset>
									</div>
                                    
                                    <div class="col-md-6">
										<fieldset>
                                        
											<div class="form-group">
												<label class="col-lg-3 control-label" for="status">وضعیت:</label>
												<div class="col-lg-9">
									                            <label class="radio-inline">
											<input name="status" id="status" type="radio" class="styled" value="1" <?php if (!(strcmp(1, KT_escapeAttribute($row_rscat['status'])))) {echo 'checked="checked"';} ?> tabindex="16" />
											فعال
										</label>

										<label class="radio-inline">
											<input name="status" id="status" type="radio" class="styled" value="0" <?php if (!(strcmp(0, (int)KT_escapeAttribute($row_rscat['status'])))) {echo 'checked="checked"';} ?> tabindex="16" />
											غیرفعال
										</label>
												</div>
											</div>

										</fieldset>
									</div>
                                    
                                    <div class="col-md-12">
                                    <div class="form-group">
										<div class="col-lg-12">
											<div class="input-group">
												<span class="input-group-btn bg-slate-700"><button id="copy_btn" class="btn btn-icon bg-slate-700"  type="button" data-popup="tooltip" title="کپی" tabindex="17"><i class="icon-copy3"></i></button></span>
												<div id="copy_url" class="form-control bg-slate text-right flt-left">contents.php?cat=<?php echo $row_rscat['code']; ?></div>
												<span class="input-group-addon bg-slate-700"><i class="icon-sphere"></i></span>
											</div>
										</div>
									</div>
                                    </div>
                                    
							</div>
								<div class="text-right">
                                    <button id="KT_Update1" name="KT_Update1" type="submit" class="btn btn-primary" tabindex="18">ویرایش اطلاعات <i class="icon-arrow-left13 position-right"></i></button>
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
<script>
$( "#copy_btn" ).click(function() {
  var ss=$('#copy_url').html();
  var $temp = $("<input>");
  $("body").append($temp);
  $temp.val(ss).select();
  document.execCommand("copy");
  $temp.remove();
});  
</script>                                    

</body>
</html>
                            
<?php
mysqli_free_result($rs1);
?>

