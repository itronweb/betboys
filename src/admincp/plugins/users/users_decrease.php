<?php 
   GLOBAL $content;
   $content['title']       = "کاهش موجودی کاربر";
   require('../../layouts/sidebar.php');
   
   $id= decrypt($_GET['id'],session_id()."user");
   
   // Make a transaction dispatcher instance
   $tNGs = new tNG_dispatcher("");
   
   // Make unified connection variable
   $conn_cn = new KT_connection($cn, $database_cn);
   
   // Start trigger
   $formValidation = new tNG_FormValidation();
   $formValidation->addField("cash", true, "text", "", "", "", "لطفا قیمت را وارد نمایید.");
   $tNGs->prepareValidation($formValidation);
   // End trigger
   
   // Make an update transaction instance
   $upd_adm = new tNG_update($conn_cn);
   $tNGs->addTransaction($upd_adm);
   
   // Register triggers
   $upd_adm->registerTrigger("STARTER", "Trigger_Default_Starter", 1, "POST", "KT_Update1");
   $upd_adm->registerTrigger("BEFORE", "Trigger_Default_FormValidation", 10, $formValidation);
   
   mysqli_select_db($cn,$database_cn);
   $query_Recordsetadmid = "SELECT * FROM `users` WHERE `status` = '1' and `id`='".$id."'";
   $Recordsetadmid = mysqli_query($cn,$query_Recordsetadmid) or die(mysqli_error($cn));
   $row_Recordsetadmid = mysqli_fetch_assoc($Recordsetadmid);
   $totalRows_Recordsetadmid = mysqli_num_rows($Recordsetadmid);
   // Add columns
   $price=$row_Recordsetadmid['cash'];
   $price=$price-$_POST['cash'];
   $upd_adm->setTable("`users`");
   $upd_adm->addColumn("cash", "NUMERIC_TYPE", "VALUE", $price);
   $upd_adm->addColumn("updated_at", "STRING_TYPE", "VALUE", date("Y-m-d H:i:s"));
   $upd_adm->setPrimaryKey("id", "NUMERIC_TYPE", "VALUE", $id);
   
   // Execute all the registered transactions
   
   // Get the transaction recordset
   $ins_adm = new tNG_insert($conn_cn);
   $tNGs->addTransaction($ins_adm);
   $ins_adm->registerTrigger("STARTER", "Trigger_Default_Starter", 1, "POST", "KT_Update1");
   $ins_adm->registerTrigger("END", "Trigger_Default_Redirect", 99, $_SERVER['HTTP_REFERER']);
   // Add columns
   if($_POST['description']){
   $desc=$_POST['description'];
   }
   else{
   $desc="کاهش شارژ حساب توسط مدیریت";
   }
   $price=$row_Recordsetadmid['cash'];
   $price=$price-$_POST['cash'];
   $ins_adm->setTable("`transactions`");
   $ins_adm->addColumn("price",  "NUMERIC_TYPE", "POST", "cash");
   $ins_adm->addColumn("user_id", "NUMERIC_TYPE", "VALUE", $id);
   $ins_adm->addColumn("invoice_type", "NUMERIC_TYPE", "VALUE",11);
   $ins_adm->addColumn("description", "STRING_TYPE", "VALUE",$desc);
   $ins_adm->addColumn("trans_id", "STRING_TYPE", "VALUE",'ADMIN_DECREASE' . time());
   $ins_adm->addColumn("cash", "NUMERIC_TYPE", "VALUE", $price);
   $ins_adm->addColumn("created_at", "STRING_TYPE", "VALUE", date("Y-m-d H:i:s"));
   $ins_adm->addColumn("updated_at", "STRING_TYPE", "VALUE", date("Y-m-d H:i:s"));
   $ins_adm->addColumn("status", "NUMERIC_TYPE", "VALUE","1");
   $ins_adm->setPrimaryKey("id", "NUMERIC_TYPE");
   
   // Execute all the registered transactions
   $tNGs->executeTransactions();
   
   $rsadm = $tNGs->getRecordset("`users`");
   $row_rsadm = mysqli_fetch_assoc($rsadm);
   $totalRows_rsadm = mysqli_num_rows($rsadm);
   ?>
<?php
   echo $tNGs->getErrorMsg();
   ?>
<div class="row">
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
               <div class="col-md-6">
                  <fieldset>
                     <div class="form-group">
                        <label class="col-lg-3 control-label" for="cash">مبلغ:</label>
                        <div class="col-lg-9">
                           <input type="text" name="cash" id="cash"  class="form-control" tabindex="1" autocomplete="off" />
                           <?php echo $tNGs->displayFieldHint("cash");?> <?php echo $tNGs->displayFieldError("`users`", "cash"); ?>    
                        </div>
                     </div>
                     <div class="form-group">
                        <label class="col-lg-3 control-label" for="description"> شرح تراکنش:</label>
                        <div class="col-lg-9">
                           <input type="text" name="description" id="description"  class="form-control" tabindex="1" autocomplete="off" />
                           <?php echo $tNGs->displayFieldHint("description");?> <?php echo $tNGs->displayFieldError("`users`", "description"); ?>    
                        </div>
                     </div>
                  </fieldset>
               </div>
            </div>
            <div class="text-right">
               <button id="KT_Update1" name="KT_Update1" type="submit" class="btn btn-primary" tabindex="10">ویرایش اطلاعات <i class="icon-arrow-left13 position-right"></i></button>
            </div>
         </div>
      </div>
   </form>
</div>
<?php require('../../layouts/footer.php');?>