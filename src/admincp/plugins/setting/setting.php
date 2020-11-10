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
$formValidation->addField("title", true, "text", "", "", "", "لطفا عنوان سایت را وارد نمایید.");
$formValidation->addField("email", false, "text", "email", "", "", "ایمیل را درست وارد نمایید.");
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
  $uploadObj->setFormFieldName("logo");
  $uploadObj->setDbFieldName("logo");
  $uploadObj->setFolder("../../../attachment/setting/");
  $uploadObj->setResize("true", 600, 600);
  $uploadObj->setMaxSize($ImgMaxSize);
  $uploadObj->setAllowedExtensions($ImgAllowedExtensions);
  $uploadObj->setRename("auto");
  return $uploadObj->Execute();
}
//end Trigger_ImageUpload trigger


//start Trigger_ImageUpload trigger
//remove this line if you want to edit the code by hand 
$Img2MaxSize=1500;
$Img2AllowedExtensions="gif, jpg, jpe, jpeg, png";

function Trigger_ImageUpload2(&$tNG) {
  global $Img2MaxSize, $Img2AllowedExtensions;
  
  $uploadObj = new tNG_ImageUpload($tNG);
  $uploadObj->setFormFieldName("logo2");
  $uploadObj->setDbFieldName("logo2");
  $uploadObj->setFolder("../../../attachment/setting/");
  $uploadObj->setResize("true", 600, 600);
  $uploadObj->setMaxSize($Img2MaxSize);
  $uploadObj->setAllowedExtensions($Img2AllowedExtensions);
  $uploadObj->setRename("auto");
  return $uploadObj->Execute();
}
//end Trigger_ImageUpload trigger


//start Trigger_ImageUpload trigger
//remove this line if you want to edit the code by hand 
$Img3MaxSize=1500;
$Img3AllowedExtensions="ico";

function Trigger_ImageUpload3(&$tNG) {
  global $Img3MaxSize, $Img3AllowedExtensions;
  
  $uploadObj = new tNG_ImageUpload($tNG);
  $uploadObj->setFormFieldName("favicon");
  $uploadObj->setDbFieldName("favicon");
  $uploadObj->setFolder("../../../attachment/setting/");
  $uploadObj->setMaxSize($Img3MaxSize);
  $uploadObj->setAllowedExtensions($Img3AllowedExtensions);
  $uploadObj->setRename("auto");
  return $uploadObj->Execute();
}
//end Trigger_ImageUpload trigger



// Make an update transaction instance
$upd_sett = new tNG_update($conn_cn);
$tNGs->addTransaction($upd_sett);
// Register triggers
$upd_sett->registerTrigger("STARTER", "Trigger_Default_Starter", 1, "POST", "KT_Update1");
$upd_sett->registerTrigger("BEFORE", "Trigger_Default_FormValidation", 10, $formValidation);
$upd_sett->registerTrigger("END", "Trigger_Default_Redirect", 99, "setting.php?add=1");
$upd_sett->registerTrigger("AFTER", "Trigger_ImageUpload", 97);
$upd_sett->registerTrigger("AFTER", "Trigger_ImageUpload2", 97);
$upd_sett->registerTrigger("AFTER", "Trigger_ImageUpload3", 97);

// Add columns
$upd_sett->setTable("setting");
$upd_sett->addColumn("title", "STRING_TYPE", "POST", "title");
$upd_sett->addColumn("author", "STRING_TYPE", "POST", "author");
$upd_sett->addColumn("copyright", "STRING_TYPE", "POST", "copyright");
$upd_sett->addColumn("keywords", "STRING_TYPE", "POST", "keywords");
$upd_sett->addColumn("description", "STRING_TYPE", "POST", "description");
$upd_sett->addColumn("script", "STRING_TYPE", "POST", "script");
$upd_sett->addColumn("enamad", "STRING_TYPE", "POST", "enamad");
$upd_sett->addColumn("samandehi", "STRING_TYPE", "POST", "samandehi");
$upd_sett->addColumn("contact", "STRING_TYPE", "VALUE", nl2br($_POST['contact']));
$upd_sett->addColumn("tel", "STRING_TYPE", "POST", "tel");
$upd_sett->addColumn("fax", "STRING_TYPE", "POST", "fax");
$upd_sett->addColumn("email", "STRING_TYPE", "POST", "email");
$upd_sett->addColumn("skype", "STRING_TYPE", "POST", "skype");
$upd_sett->addColumn("telegram", "STRING_TYPE", "POST", "telegram");
$upd_sett->addColumn("linkedin", "STRING_TYPE", "POST", "linkedin");
$upd_sett->addColumn("twitter", "STRING_TYPE", "POST", "twitter");
$upd_sett->addColumn("facebook", "STRING_TYPE", "POST", "facebook");
$upd_sett->addColumn("instagram", "STRING_TYPE", "POST", "instagram");
$upd_sett->addColumn("googleplus", "STRING_TYPE", "POST", "googleplus");
$upd_sett->addColumn("googlemap", "STRING_TYPE", "POST", "googlemap");
$upd_sett->addColumn("aparat", "STRING_TYPE", "POST", "aparat");
$upd_sett->addColumn("lenzor", "STRING_TYPE", "POST", "lenzor");
$upd_sett->addColumn("logo", "FILE_TYPE", "FILES", "logo");
$upd_sett->addColumn("logo2", "FILE_TYPE", "FILES", "logo2");
$upd_sett->addColumn("favicon", "FILE_TYPE", "FILES", "favicon");
$upd_sett->addColumn("status", "STRING_TYPE", "POST", "status");
$upd_sett->setPrimaryKey("id", "NUMERIC_TYPE", "SESSION", "portalid");

// Execute all the registered transactions
$tNGs->executeTransactions();

// Get the transaction recordset
$rssett = $tNGs->getRecordset("setting");
$row_rssett = mysqli_fetch_assoc($rssett);
$totalRows_rssett = mysqli_num_rows($rssett);
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
	<script type="text/javascript" src="assets/js/setting_page.js"></script>

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

                                <div class="col-md-12">
									<div class="col-md-6">
										<fieldset>
											<div class="form-group">
												<label class="col-lg-3 control-label" for="title">عنوان سایت:</label>
												<div class="col-lg-9">
													<input type="text" name="title" id="title" value="<?php echo KT_escapeAttribute($row_rssett['title']); ?>" class="form-control" autocomplete="off" tabindex="1" />
												</div>
											</div>

											<div class="form-group">
												<label class="col-lg-3 control-label" for="email">ایمیل:</label>
												<div class="col-lg-9">
													<input type="text" name="email" id="email" value="<?php echo KT_escapeAttribute($row_rssett['email']); ?>" class="form-control text-right flt-left" autocomplete="off" tabindex="3" />
												</div>
											</div>
                                            
                                            <div class="form-group">
												<label class="col-lg-3 control-label" for="fax">فکس:</label>
												<div class="col-lg-9">
													<input type="text" name="fax" id="fax" value="<?php echo KT_escapeAttribute($row_rssett['fax']); ?>" class="form-control tokenfield text-right flt-left" autocomplete="off" tabindex="4" />
												</div>
											</div>
										</fieldset>
									</div>
                                
									<div class="col-md-6">
										<fieldset>

											<div class="form-group">
												<label class="col-lg-3 control-label" for="author">نویسنده:</label>
												<div class="col-lg-9">
                                                    <input type="text" name="author" id="author" value="<?php echo KT_escapeAttribute($row_rssett['author']); ?>" class="form-control" autocomplete="off" tabindex="2" />
                                                    
												</div>
											</div>
                                            
											<div class="form-group">
												<label class="col-lg-3 control-label" for="tel">شماره تماس:</label>
												<div class="col-lg-9">
													<input type="text" name="tel" id="tel" value="<?php echo KT_escapeAttribute($row_rssett['tel']); ?>" class="form-control tokenfield text-right flt-left" autocomplete="off" tabindex="4" />
												</div>
											</div>
										</fieldset>
									</div>
                                </div>
                                
                                <div class="col-md-12">
									<div class="col-md-6">
										<fieldset>
                                            <div class="form-group">
												<label class="col-lg-3 control-label" for="logo">لوگو :</label>
												<div class="media-body">
													<input name="logo" id="logo" type="file" class="file-styled" value="" tabindex="5" />
                                                    <span class="help-block">فرمتهای مجاز: <?php echo $ImgAllowedExtensions; ?>. حداکثر اندازه فایل: <?php echo $ImgMaxSize/1000; ?>Mb</span>
 												</div>
                                                <div class="media-right">
													<img src="../../<?php if($row_rssett['logo']) echo '../attachment/setting/'. KT_escapeAttribute($row_rssett['logo']); else echo 'assets/images/placeholder.jpg';?>" style="width: 58px; height: 58px; border-radius: 2px;" alt="" />
												</div>
											</div>
                                            
                                            <div class="form-group">
												<label class="col-lg-3 control-label" for="favicon">آیکون :</label>
												<div class="media-body">
													<input name="favicon" id="favicon" type="file" class="file-styled" value="" tabindex="7" />
                                                    <span class="help-block">فرمتهای مجاز: <?php echo $Img3AllowedExtensions; ?>. حداکثر اندازه فایل: <?php echo $Img3MaxSize/1000; ?>Mb</span>
 												</div>
                                                <div class="media-right">
													<img src="../../<?php if($row_rssett['favicon']) echo '../attachment/setting/'. KT_escapeAttribute($row_rssett['favicon']); else echo 'assets/images/placeholder.jpg';?>" style="width: 58px; height: 58px; border-radius: 2px;" alt="" />
												</div>
											</div>
                                            
										</fieldset>
									</div>
                                
									<div class="col-md-6">
										<fieldset>
                                            <div class="form-group">
												<label class="col-lg-3 control-label" for="logo2">لوگو2 :</label>
												<div class="media-body">
													<input name="logo2" id="logo2" type="file" class="file-styled" value="" tabindex="6" />
                                                    <span class="help-block">فرمتهای مجاز: <?php echo $Img2AllowedExtensions; ?>. حداکثر اندازه فایل: <?php echo $Img2MaxSize/1000; ?>Mb</span>
 												</div>
                                                <div class="media-right">
													<img src="../../<?php if($row_rssett['logo2']) echo '../attachment/setting/'. KT_escapeAttribute($row_rssett['logo2']); else echo 'assets/images/placeholder.jpg';?>" style="width: 58px; height: 58px; border-radius: 2px;" alt="" />
												</div>
											</div>
                                            
										</fieldset>
									</div>
                                </div>
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label class="col-lg-1 control-label" for="copyright">کپی رایت:</label>
                                            <div class="col-lg-11">
                                                <textarea name="copyright" id="copyright" class="form-control" rows="3" tabindex="8"><?php echo KT_escapeAttribute($row_rssett['copyright']); ?></textarea>
                                            </div>
                                        </div>
                                        
                                        <div class="form-group">
                                            <label class="col-lg-1 control-label" for="contact">اطلاعات تماس:</label>
                                            <div class="col-lg-11">
                                                <textarea name="contact" id="contact" class="form-control" rows="3" tabindex="9"><?php echo KT_escapeAttribute($row_rssett['contact']); ?></textarea>
                                            </div>
                                        </div>
                                        
                                        <div class="form-group">
                                            <label class="col-lg-1 control-label" for="description">شرح سایت:</label>
                                            <div class="col-lg-11">
                                                <textarea name="description" id="description" class="form-control" rows="3" tabindex="10"><?php echo KT_escapeAttribute($row_rssett['description']); ?></textarea>
                                            </div>
                                        </div>
                                        
                                        <div class="form-group">
                                            <label class="col-lg-2 control-label" for="keywords">کلمات کلیدی:</label>
                                            <div class="col-lg-10">
                                                <input name="keywords" id="keywords" type="text" class="form-control tokenfield" value="<?php echo KT_escapeAttribute($row_rssett['keywords']); ?>" autocomplete="off" tabindex="11" />
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <div class="col-md-12">
                                    	<div class="col-md-6">
                                            <div class="form-group">
												<label class="col-lg-3 control-label" for="telegram">تلگرام:</label>
												<div class="col-lg-9">
                                                    <input type="text" name="telegram" id="telegram" value="<?php echo KT_escapeAttribute($row_rssett['telegram']); ?>" class="form-control text-right flt-left" autocomplete="off" tabindex="12" />
												</div>
											</div>

                                            <div class="form-group">
												<label class="col-lg-3 control-label" for="author">فیسبوک:</label>
												<div class="col-lg-9">
                                                    <input type="text" name="facebook" id="facebook" value="<?php echo KT_escapeAttribute($row_rssett['facebook']); ?>" class="form-control text-right flt-left" autocomplete="off" tabindex="14" />
												</div>
											</div>

                                            <div class="form-group">
												<label class="col-lg-3 control-label" for="googleplus">گوگل پلاس:</label>
												<div class="col-lg-9">
                                                    <input type="text" name="googleplus" id="googleplus" value="<?php echo KT_escapeAttribute($row_rssett['googleplus']); ?>" class="form-control text-right flt-left" autocomplete="off" tabindex="16" />
												</div>
											</div>

                                            <div class="form-group">
												<label class="col-lg-3 control-label" for="author">اسکایپ:</label>
												<div class="col-lg-9">
                                                    <input type="text" name="skype" id="skype" value="<?php echo KT_escapeAttribute($row_rssett['skype']); ?>" class="form-control text-right flt-left" autocomplete="off" tabindex="18" />
												</div>
											</div>

                                            <div class="form-group">
												<label class="col-lg-3 control-label" for="lenzor">لنزور:</label>
												<div class="col-lg-9">
                                                    <input type="text" name="lenzor" id="lenzor" value="<?php echo KT_escapeAttribute($row_rssett['lenzor']); ?>" class="form-control text-right flt-left" autocomplete="off" tabindex="20" />
												</div>
											</div>
                                        </div>
                                        
                                    	<div class="col-md-6">
                                            <div class="form-group">
												<label class="col-lg-3 control-label" for="instagram">اینستاگرام:</label>
												<div class="col-lg-9">
                                                    <input type="text" name="instagram" id="instagram" value="<?php echo KT_escapeAttribute($row_rssett['instagram']); ?>" class="form-control text-right flt-left" autocomplete="off" tabindex="13" />
												</div>
											</div>

                                            <div class="form-group">
												<label class="col-lg-3 control-label" for="twitter">توییتر:</label>
												<div class="col-lg-9">
                                                    <input type="text" name="twitter" id="twitter" value="<?php echo KT_escapeAttribute($row_rssett['twitter']); ?>" class="form-control text-right flt-left" autocomplete="off" tabindex="15" />
												</div>
											</div>

                                            <div class="form-group">
												<label class="col-lg-3 control-label" for="linkedin">لینکدین:</label>
												<div class="col-lg-9">
                                                    <input type="text" name="linkedin" id="linkedin" value="<?php echo KT_escapeAttribute($row_rssett['linkedin']); ?>" class="form-control text-right flt-left" autocomplete="off" tabindex="17" />
												</div>
											</div>

                                            <div class="form-group">
												<label class="col-lg-3 control-label" for="aparat">آپارات:</label>
												<div class="col-lg-9">
                                                    <input type="text" name="aparat" id="aparat" value="<?php echo KT_escapeAttribute($row_rssett['aparat']); ?>" class="form-control text-right flt-left" autocomplete="off" tabindex="19" />
												</div>
											</div>

                                            <div class="form-group">
												<label class="col-lg-3 control-label" for="googlemap">آدرس نقشه گوگل:</label>
												<div class="col-lg-9">
                                                    <input type="text" name="googlemap" id="googlemap" value="<?php echo KT_escapeAttribute($row_rssett['googlemap']); ?>" class="form-control text-right flt-left" autocomplete="off" tabindex="21" />
												</div>
											</div>
                                        </div>    
                                    </div>
                                    
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label class="col-lg-1 control-label" for="script">اسکریپت:</label>
                                            <div class="col-lg-11">
                                                <textarea name="script" id="script" class="form-control flt-left text-right" rows="5" tabindex="8"><?php echo KT_escapeAttribute($row_rssett['script']); ?></textarea>
                                            </div>
                                        </div>
                                        
                                        <div class="form-group">
                                            <label class="col-lg-1 control-label" for="enamad">ای نماد:</label>
                                            <div class="col-lg-11">
                                                <textarea name="enamad" id="enamad" class="form-control flt-left text-right" rows="5" tabindex="9"><?php echo KT_escapeAttribute($row_rssett['enamad']); ?></textarea>
                                            </div>
                                        </div>
                                        
                                        <div class="form-group">
                                            <label class="col-lg-1 control-label" for="samandehi">ساماندهی:</label>
                                            <div class="col-lg-11">
                                                <textarea name="samandehi" id="samandehi" class="form-control flt-left text-right" rows="5" tabindex="10"><?php echo KT_escapeAttribute($row_rssett['samandehi']); ?></textarea>
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <div class="col-md-6">
										<fieldset>
											<div class="form-group">
												<label class="col-lg-3 control-label" for="status">وضعیت:</label>
												<div class="col-lg-9">
									                            <label class="radio-inline">
											<input name="status" id="status" type="radio" class="styled" value="1" <?php if (!(strcmp(1, KT_escapeAttribute($row_rssett['status'])))) {echo 'checked="checked"';} ?> tabindex="22" />
											فعال
										</label>

										<label class="radio-inline">
											<input name="status" id="status" type="radio" class="styled" value="0" <?php if (!(strcmp(0, KT_escapeAttribute($row_rssett['status'])))) {echo 'checked="checked"';} ?> />
											غیرفعال
										</label>
												</div>
											</div>
										</fieldset>
									</div>
                                    
                                    
							</div>
								<div class="text-right">
                                    <button id="KT_Update1" name="KT_Update1" type="submit" class="btn btn-primary" tabindex="23">ویرایش اطلاعات <i class="icon-arrow-left13 position-right"></i></button>
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

