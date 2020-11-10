<?php  
   GLOBAL $content;
   $content['title']       = "ارسال پیام به کاربران";
   require('../../layouts/sidebar.php');
   
   if($_GET['id']!=""){
   $id= decrypt($_GET['id'],session_id()."user");
   }else{
   $id=0;	
   }
   
   // Make a transaction dispatcher instance
   $tNGs = new tNG_dispatcher("");
   
   // Make unified connection variable
   $conn_cn = new KT_connection($cn, $database_cn);
   
   // Start trigger
   $formValidation = new tNG_FormValidation();
   $formValidation->addField("title", true, "text", "", "", "", "لطفا عنوان پیام را وارد نمایید.");
   $formValidation->addField("text", true, "text", "", "", "", "لطفا متن پیام را وارد نمایید.");
   
   $tNGs->prepareValidation($formValidation);
   // End trigger
   // Make an update transaction instance
   $ins_mess = new tNG_insert($conn_cn);
   $tNGs->addTransaction($ins_mess);
   
   // Register triggers
   $ins_mess->registerTrigger("STARTER", "Trigger_Default_Starter", 1, "POST", "KT_Insert1");
   $ins_mess->registerTrigger("BEFORE", "Trigger_Default_FormValidation", 10, $formValidation);
   $ins_mess->registerTrigger("END", "Trigger_Default_Redirect", 99,$_SERVER['HTTP_REFERER']);
   
   // Add columns
   $ins_mess->setTable("message");
   $ins_mess->addColumn("title", "STRING_TYPE", "POST", "title");
   $ins_mess->addColumn("sender", "STRING_TYPE", "VALUE", "ADMIN");
   $ins_mess->addColumn("date", "STRING_TYPE", "VALUE", date("Y-m-d H:i:s"));
   $ins_mess->addColumn("text", "STRING_TYPE", "POST", "text");
   $ins_mess->addColumn("status", "STRING_TYPE", "VALUE", 1);
   $ins_mess->addColumn("user_id", "NUMERIC_TYPE", "VALUE", $id);
   $ins_mess->setPrimaryKey("id", "NUMERIC_TYPE");
   
   // Execute all the registered transactions
   $tNGs->executeTransactions();
   
   // Get the transaction recordset
   $rscmtv = $tNGs->getRecordset("message");
   $row_rscmtv = mysqli_fetch_assoc($rscmtv);
   $totalRows_rscmtv = mysqli_num_rows($rscmtv);
   
   ?>
<div class="row">
   <!-- Content area -->
   <div class="content">
      <!-- Main content -->
      <?php
         echo $tNGs->getErrorMsg();
         				
         				mysqli_select_db($cn,$database_cn);
         				$query_rs1 = "SELECT * FROM message WHERE user_id='$id'";
         				$rs1 = mysqli_query($cn,$query_rs1) or die(mysqli_error($cn));
         				$row_rs1 = mysqli_fetch_assoc($rs1); 
         				$num_row= mysqli_num_rows($rs1);
         				if($num_row>0){
         ?>
      <!-- 2 columns form -->
      <div class="panel panel-flat">
         <div class="panel-body">
            <?php
               $query_Recordsetad1 = "SELECT * FROM `users` WHERE `id` = '".$row_rs1['user_id']."'";
               $Recordad1 = mysqli_query($cn,$query_Recordsetad1) or die(mysqli_error($cn));
               $row_Recordadd1 = mysqli_fetch_assoc($Recordad1);
               ?>
            <h3>
               <i class="icon-chevron-left text-success"></i> پیام های  شما به	
               <?php if($_GET['id']!=""){ ?>
               <a href="../bets/bets_list.php?id=<?php echo encrypt($row_Recordadd1['id'],session_id()."user"); ?>"><?php echo $row_Recordadd1['first_name']." ".$row_Recordadd1['last_name']; ?></a>
               <?php }else{echo "همه اعضا";} ?>
            </h3>
            <hr>
            <?php $c=0; do{ $c++;?>
            <div class="badge badge-info"><?php echo $c;?></div>
            <div class="label label-primary">
               عنوان پیام:
            </div>
            <h3 style="display:inline;color: #FFF;font-size: 20px;padding: 10px;">
               <?= '    '. $row_rs1['title'];?>
            </h3>
            <label class=""> ||||||||
            تاریخ ارسال:
            <?php $s= str_replace("-","/",$row_rs1['date']);?>
            <?php $s=miladi_to_jalali($row_rs1['date'],"-"," "); ?>
            <?php echo substr($s,8,2)." "; ?><?php echo monthname(substr($s,5,2)); ?>
            <?php echo substr($s,0,4)." "; ?>
            <?php echo "- ".substr($s,11,9); ?>
            </label>
            <?php
               if($_GET['id']!=""){
               if($row_rs1['read']==1){?>
            <div class="icon-checkmark text-success" data-popup="tooltip" title="خوانده شد"></div>
            <?php }else{?>
            <div class="icon-checkmark text-warning" data-popup="tooltip" title="خوانده نشده"></div>
            <?php } }?>
            <a href="../../change_status.php?table=<?php echo encrypt('message',session_id()."sts"); ?>&field=<?php echo encrypt('status',session_id()."sts"); ?>&id=<?php echo encrypt($row_rs1['id'],session_id()."sts"); ?>" data-popup="tooltip" title="تغییر وضعیت">
				<button class="btn btn-primary" tabindex="9"><?php checkstatus($row_rs1['status']); ?> <i class="icon-arrow-left13 position-right"></i></button>
			</a>
            <a href="message_delete.php?id=<?php echo encrypt($row_rs1['id'],session_id()."mess"); ?>" onClick="return confirm('آیا مطمئنید می خواهید پیام را حذف کنید ؟');" data-popup="tooltip" title="حذف"><i class=" text-danger-600 icon-trash"></i></a>
            <?php 
               ?>
            </br>
            <p class="label label-flat label-rounded border-success text-success-600">
               متن پیام:
            </p>
            <div>
               <p>
                  <?php echo $row_rs1['text'];?>
               </p>
            </div>
            <hr>
            <?php }while($row_rs1=mysqli_fetch_assoc($rs1))?>
         </div>
      </div>
      <?php }  ?>
      <form id="form1" class="form-horizontal" method="post" action="<?php echo KT_escapeAttribute(KT_getFullUri()); ?>" enctype="multipart/form-data">
         <div class="panel panel-flat">
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
                           <label class="col-lg-3 control-label" for="title"> عنوان پیام:</label>
                           <div class="col-lg-9">
                              <input type="text" name="title" id="title" value="<?php echo KT_escapeAttribute($row_rsfaq['title']); ?>" class="form-control" autocomplete="off" tabindex="1" />
                           </div>
                        </div>
                     </fieldset>
                  </div>
                  <div class="col-md-11">
                     <fieldset>
                        <div class="form-group">
                           <label class="col-lg-1 control-label" for="text">متن پیام:</label>
                           <div class="col-lg-11">
                              <textarea class="col-md-7" name="text" dir="rtl" id="text" rows="4" cols="4" tabindex="5"><?php echo KT_escapeAttribute($row_rsfaq['text']); ?></textarea>
                           </div>
                        </div>
                     </fieldset>
                  </div>
               </div>
               <div class="text-right">
                  <button id="KT_Insert1" name="KT_Insert1" type="submit" class="btn btn-primary" tabindex="9">ثبت اطلاعات <i class="icon-arrow-left13 position-right"></i></button>
                  <button id="reset" name="reset" type="reset" class="btn btn-warning" tabindex="10">بازنویسی <i class="icon-reset position-right"></i></button>
               </div>
            </div>
         </div>
      </form>
   </div>
</div>
<?php require('../../layouts/footer.php');?>
