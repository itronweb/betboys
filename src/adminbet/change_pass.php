<?php 
   GLOBAL $content;
   $content['title']       = "تغییر پسورد مدیریت";
   include('layouts/sidebar.php');
   
   // Make a transaction dispatcher instance
   $tNGs = new tNG_dispatcher("");
   
   // Make unified connection variable
   $conn_cn = new KT_connection($cn, $database_cn);
   
   // Start trigger
   $formValidation = new tNG_FormValidation();
   $formValidation->addField("oldpassword", true, "text", "", "", "", "لطفا رمز عبور فعلی را وارد نمایید.");
   $formValidation->addField("newpass", true, "text", "", "", "", "لطفا رمز عبور جدید را وارد نمایید.");
   $formValidation->addField("repassword", true, "text", "", "", "", "لطفا تکرار رمز عبور جدید را وارد نمایید.");
   $tNGs->prepareValidation($formValidation);
   // End trigger
   
   mysqli_select_db($cn,$database_cn);
   $query_Recordsetpss = sprintf("SELECT pass FROM admin WHERE `user`=%s",GetSQLValueString($user_name, "text"));
   $Recordsetpss = mysqli_query($cn,$query_Recordsetpss) or die(mysqli_error($cn));
   $row_Recordsetpss = mysqli_fetch_assoc($Recordsetpss);
   $totalRows_Recordsetpss = mysqli_num_rows($Recordsetpss);
   
   $err_pss=0;
   
   if($row_Recordsetpss['pass']==sha1(md5($_POST['oldpassword'])))
   {
   if($_POST['newpass']==$_POST['repassword'])
   	$password = sha1(md5($_POST['newpass']));
   
   // Make an update transaction instance
   $upd_adm = new tNG_update($conn_cn);
   $tNGs->addTransaction($upd_adm);
   
   // Register triggers
   $upd_adm->registerTrigger("STARTER", "Trigger_Default_Starter", 1, "POST", "KT_Update1");
   $upd_adm->registerTrigger("BEFORE", "Trigger_Default_FormValidation", 10, $formValidation);
   $upd_adm->registerTrigger("END", "Trigger_Default_Redirect", 99, 'change_pass.php?add=1');
   
   // Add columns
   $upd_adm->setTable("`admin`");
   $upd_adm->addColumn("pass", "STRING_TYPE", "VALUE", $password);
   $upd_adm->setPrimaryKey("user", "STRING_TYPE", "VALUE", $user_name);
   
   // Execute all the registered transactions
   $tNGs->executeTransactions();
   
   // Get the transaction recordset
   $rsadm = $tNGs->getRecordset("`admin`");
   $row_rsadm = mysqli_fetch_assoc($rsadm);
   $totalRows_rsadm = mysqli_num_rows($rsadm);
   }
   else if(isset($_POST['newpass']))
    $err_pss=1;
   
   ?>
<div class="content">
   <!-- Main content -->
   <?php
      echo $tNGs->getErrorMsg();
      ?>
   <!-- 2 columns form -->
   <form id="form1" class="form-horizontal" method="post" action="<?php echo KT_escapeAttribute(KT_getFullUri()); ?>" enctype="multipart/form-data">
      <div class="panel panel-flat">
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
               <?php 
                  if($err_pss==1){
                  ?>
               <div class="alert alert-danger alert-styled-left alert-arrow-left alert-bordered">
                  <button type="button" class="close" data-dismiss="alert"><span>&times;</span><span class="sr-only">Close</span></button>
                  رمز عبور فعلی شما صحیح نمی باشد. 
               </div>
               <?php 
                  }?>
							<div class="form-group row">
								<div class="col-sm-3"><label class="control-label">رمز عبور فعلی:</label>
								<input name="oldpassword" type="text" value="<?php echo @$row_pages['name']; ?>" class="form-control"></div>
									<?php echo $tNGs->displayFieldHint("oldpassword");?>    
								<div class="col-sm-3"><label class="control-label">رمز عبور جدید:</label>
								<input name="newpass" type="text" value="<?php echo @$row_pages['slug']; ?>" class="form-control"></div>
									<?php echo $tNGs->displayFieldHint("newpass");?>   
								<div class="col-sm-3"><label class="control-label">تکرار رمز عبور جدید:</label>
								<input name="repassword" type="text" value="<?php echo @$row_pages['slug']; ?>" class="form-control"></div>
									<?php echo $tNGs->displayFieldHint("repassword");?> 
								<div class="col-sm-3" style="margin-top:6px;">
								<label class="control-label"></label>
								   <button id="KT_Update1" name="KT_Update1" type="submit" class="form-control btn btn-primary">تایید</button>
								</div>
							</div>
            </div>
         </div>
      </div>
   </form>
</div>
<?php include('layouts/footer.php');?>