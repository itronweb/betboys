<?php 
   GLOBAL $content;
   $content['title']       = "لیست بازی ها";
   require('../../layouts/sidebar.php');
   $hometeam=$_GET['hometeam'];
   $awayteam=$_GET['awayteam'];
   $id= decrypt($_GET['id'],session_id()."game");
   
   // Make a transaction dispatcher instance
   $tNGs = new tNG_dispatcher("");
   
   // Make unified connection variable
   $conn_cn = new KT_connection($cn, $database_cn);
   
   $id2=trim($id);
   mysqli_select_db($cn,$database_cn);
   $query_Recordstatus = "select * from matches where id='$id2'";
   $Recordstatus = mysqli_query($cn,$query_Recordstatus) or die(mysqli_error($cn));
   $totalrowstatus=mysqli_num_rows($Recordstatus);
   if($totalrowstatus>0){
   $upd_game = new tNG_update($conn_cn);
   $tNGs->addTransaction($upd_game);
   // Register triggers
   
   $upd_game->registerTrigger("STARTER", "Trigger_Default_Starter", 1, "POST", "KT_Update1");
   $upd_game->registerTrigger("END", "Trigger_Default_Redirect", 99,  $_POST['referurl']);
   
   // Add columns
   $upd_game->setTable("matches");
   $upd_game->addColumn("animation_code", "STRING_TYPE", "POST", "animation_code");
   $upd_game->setPrimaryKey("id", "STRING_TYPE", "VALUE", $id2);
   }
   else{
   $upd_game = new tNG_insert($conn_cn);
   $tNGs->addTransaction($upd_game);
   // Register triggers
   $id2=trim($id);
   $upd_game->registerTrigger("STARTER", "Trigger_Default_Starter", 1, "POST", "KT_Update1");
   $upd_game->registerTrigger("END", "Trigger_Default_Redirect", 99,  $_POST['referurl']);
   
   // Add columns
   $upd_game->setTable("matches");
   $upd_game->addColumn("animation_code", "STRING_TYPE", "POST", "animation_code");
   $upd_game->addColumn("id", "STRING_TYPE", "VALUE", $id2);
   }
   
   // Execute all the registered transactions
   $tNGs->executeTransactions();
   
   // Get the transaction recordset
   $rsgame = $tNGs->getRecordset("games");
   $row_rsgame = mysqli_fetch_assoc($rsgame);
   $totalRows_rsgame = mysqli_num_rows($rsgame);
   ?>
<div class="row">
   <!-- Content area -->
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
                  <h6 class="panel-title"><?php echo "  بازی ".$hometeam."-";?><?php echo $awayteam;?></h6>
                  </br>
                  <div class="col-md-6">
                     <fieldset>
                        <div class="form-group">
                           <label class="col-lg-3 control-label" for="animation_code">
                           کد معادل:</label>
                           <div class="col-lg-9">
                              <input type="number" name="animation_code" id="animation_code" value="<?php echo KT_escapeAttribute($row_rsgame['animation_code']); ?>" class="form-control" autocomplete="off" tabindex="1" />
                           </div>
                        </div>
                     </fieldset>
                  </div>
               </div>
               <div class="text-right">
                  <button id="KT_Update1" name="KT_Update1" type="submit" class="btn btn-primary" tabindex="6">ویرایش اطلاعات <i class="icon-arrow-left13 position-right"></i></button>
               </div>
            </div>
         </div>
         <input type="hidden" name="referurl" value="<?php echo $_SERVER['HTTP_REFERER'] ?>" />
      </form>
   </div>
</div>
<?php require('../../layouts/footer.php');?>