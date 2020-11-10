<?php 
   GLOBAL $content;
   $content['title']       = "درآمد کلی سایت";
   require('../../layouts/sidebar.php');
   
   mysqli_select_db($cn,$database_cn);
   // Make a transaction dispatcher instance
   
   if($_POST['starttime'] && $_POST['endtime'] ){
   	if (DateTime::createFromFormat('Y-m-d G:i:s',$_POST['starttime'] ) == FALSE) {
       $start_date = jalali_to_miladi(convertenN($_POST['starttime']),"-"," ");
   	if(DateTime::createFromFormat('Y-m-d G:i:s',$_POST['endtime'] ) == TRUE)
   		$end_date = $_POST['endtime'];
   	else $end_date = jalali_to_miladi(convertenN($_POST['endtime']),"-"," ");
   }elseif(DateTime::createFromFormat('Y-m-d G:i:s',$_POST['starttime'] ) == TRUE){
   	$start_date = $_POST['starttime'];	
   	if(DateTime::createFromFormat('Y-m-d G:i:s',$_POST['endtime'] ) == TRUE)
   		$end_date =$_POST['endtime'];
   	else $end_date = jalali_to_miladi(convertenN($_POST['endtime']),"-"," ");
   }
   
   $y=substr($start_date,0,4);$mo=substr($start_date,5,2);$d=substr($start_date,8,2);$h=substr($start_date,11,2); $m=substr($start_date,14,2);$se=substr($s,17,2); 
   $start_datetime=mktime($h,$m,$se,$mo,$d,$y);
   	
   	$y=substr($end_date,0,4);$mo=substr($end_date,5,2);$d=substr($end_date,8,2);$h=substr($end_date,11,2); $m=substr($end_date,14,2);$se=substr($s,17,2); 
   $end_datetime=mktime($h,$m,$se,$mo,$d,$y);
   
   
   $shamsi_start_date=jdate('j F Y ',$start_datetime);
   $shamsi_end_date=jdate('j F Y ',$end_datetime);
   }elseif($_POST['starttime']){
   	if (DateTime::createFromFormat('Y-m-d G:i:s',$_POST['starttime'] ) == FALSE) {
       $start_date = jalali_to_miladi(convertenN($_POST['starttime']),"-"," ");
   }elseif(DateTime::createFromFormat('Y-m-d G:i:s',$_POST['starttime'] ) == TRUE){
   	$start_date = $_POST['starttime'];	
   }
   
   $y=substr($start_date,0,4);$mo=substr($start_date,5,2);$d=substr($start_date,8,2);$h=substr($start_date,11,2); $m=substr($start_date,14,2);$se=substr($s,17,2); 
   $start_datetime=mktime($h,$m,$se,$mo,$d,$y);
   	
   
   
   $shamsi_start_date=jdate('j F Y ',$start_datetime);
   $end_date=date("Y-m-d");
   $s=date("Y-m-d H:i:s");
   $y=substr($s,0,4);$mo=substr($s,5,2);$d=substr($s,8,2);$h=substr($s,11,2); $m=substr($s,14,2);$se=substr($s,17,2); 
   $time=mktime($h,$m,$se,$mo,$d,$y);
   $shamsi_end_date=jdate('j F Y ',$time);
   }
   else{
   	$start_date=date("Y-m-d");
   	$end_date=date("Y-m-d");
   	$s=date("Y-m-d H:i:s");
   $y=substr($s,0,4);$mo=substr($s,5,2);$d=substr($s,8,2);$h=substr($s,11,2); $m=substr($s,14,2);$se=substr($s,17,2); 
   $time=mktime($h,$m,$se,$mo,$d,$y);
   $shamsi_start_date=jdate('j F Y ',$time);
   $shamsi_end_date=jdate('j F Y ',$time);
   }
   
   
   $start_date.=' 00:00:00.000000';
   $end_date.=' 23:59:59.000000';
   
   
   $query_Recordvarizi= "SELECT price,invoice_type FROM `transactions` WHERE created_at BETWEEN '$start_date ' AND '$end_date '  and status=1 ";
   $Recordvarizi = mysqli_query($cn,$query_Recordvarizi) or die(mysqli_error($cn));
   $row_Recordvarizi = mysqli_fetch_assoc($Recordvarizi);
   do{
   	$invoice_type=$row_Recordvarizi['invoice_type'];
   	$invoice_casino=substr($row_Recordvarizi['invoice_type'],0,1);
   	if ( $invoice_type == 1 ){
   		$sumVarizi += $row_Recordvarizi['price'];
   	}
   	elseif($invoice_type == 11 ){
   	$modiriyatDecreaseSum += $row_Recordvarizi['price'];
   	}
   	elseif($invoice_type == 10 ){
   	$modiriyatIncreaseSum += $row_Recordvarizi['price'];
   	}
   	elseif($invoice_type == 4 ){
   	$sumvarizihesab += $row_Recordvarizi['price'];
   	}elseif($invoice_type == 5 ){
   	$sumvarizisubuser_prematch += $row_Recordvarizi['price'];
   	}
   	elseif($invoice_type == 20 ){
   		$sumVarizi += $row_Recordvarizi['price'];
   	}
   	
   	//////////////////////// casino incomming  ///////////////////////////
   	elseif ( $invoice_casino == 7 ){
   		$sumVarizi_casino += $row_Recordvarizi['price'];//8899607
   	}
   	elseif($invoice_casino == 8){
   	$jayzeSum_casino += $row_Recordvarizi['price'];
   	}
   	elseif($invoice_casino == 9){
   	$sumvarizisubuser_casino += $row_Recordvarizi['price'];
       $betgiftCount_casino++;
   	}
   	
   	
   	//////////////////////// prematch incomming  ///////////////////////////
   	
   	if($invoice_type == 3 ){
   		$barsabtShartSum += $row_Recordvarizi['price'];
   	}
   
   	elseif($invoice_type == 2 or $invoice_type == 12){
   	$jayzeSum_prematch += $row_Recordvarizi['price'];
   	}elseif($invoice_type == 14 ){
   	$sumreturn_prematch +=$row_Recordvarizi['price'];
   	}elseif($invoice_type == 15 ){
   		$sumsemiwin_prematch += $row_Recordvarizi['price'];
   	}elseif($invoice_type == 16 ){
   		$sumsemiloose_prematch += $row_Recordvarizi['price'];
   		
   	}
   	
   }while($row_Recordvarizi = mysqli_fetch_assoc($Recordvarizi));
   
   $query_Recordstake = "SELECT status,stake,effective_odd,pay_stake_status,type FROM `bets` WHERE created_at BETWEEN '$start_date' AND '$end_date'  ";
   $Recordstake = mysqli_query($cn,$query_Recordstake) or die(mysqli_error($cn));
   $row_Recordstake = mysqli_fetch_assoc($Recordstake);
   $num_rowstake=mysqli_num_rows($Recordstake);
   $betcount=0;
   
   $betfaildcount=0;
   $betnotstatus=0;
   $sumnotstatus=0;
   if($num_rowstake>0){
   do{
       $betcount++;
   	$stake=$row_Recordstake['stake'];
       $bord=$row_Recordstake['stake']*$row_Recordstake['effective_odd'];
   $totalStake +=$row_Recordstake['stake'];
   if($row_Recordstake['status'] == 2){
   	$totalfailed +=$row_Recordstake['stake'];
       $betfaildcount++;
   }
   elseif($row_Recordstake['status'] == 0){
       $betnotstatus++;
       $sumnotstatus += $row_Recordstake['stake'] * $row_Recordstake['type'];
   }
   }while($row_Recordstake=mysqli_fetch_assoc($Recordstake));
    
   }
   
   
   
   
   $sumvarizisubuser = $sumvarizisubuser_casino + $sumvarizisubuser_prematch;
   
   $query_Recordstake = "SELECT status,stake,effective_odd,pay_stake_status FROM `bets` WHERE created_at BETWEEN '$start_date' AND '$end_date'  ";
   $Recordstake = mysqli_query($cn,$query_Recordstake) or die(mysqli_error($cn));
   $row_Recordstake = mysqli_fetch_assoc($Recordstake);
   $num_rowstake=mysqli_num_rows($Recordstake);
   $betcount=0;
   
   $betfaildcount=0;
   $betnotstatus=0;
   $sumnotstatus=0;
   if($num_rowstake>0){
   do{
       $betcount++;
   	$stake=$row_Recordstake['stake'];
       $bord=$row_Recordstake['stake']*$row_Recordstake['effective_odd'];
   $totalStake +=$row_Recordstake['stake'];
   if($row_Recordstake['status'] == 2){
   	$totalfailed +=$row_Recordstake['stake'];
       $betfaildcount++;
   }
   elseif($row_Recordstake['status'] == 0){
       $betnotstatus++;
       $sumnotstatus += $row_Recordstake['stake'];
   }
   	
   	
   	
   	
   }while($row_Recordstake=mysqli_fetch_assoc($Recordstake));
    
   }
   ?>
<link href="../../includes/skins/mxkollection3.css" rel="stylesheet" type="text/css" media="all" />
<style>
   .daramad td{
   width:50%;
   }
   .win{
   background-color:#84f1a694;
   }
   .win:hover{
   background-color:#48e07894!important;
   }
   .semiwin{
   background-color:#d1efe4;
   }
   .semiwin:hover{
   background-color:#54c39b!important;
   }
   .loose{
   background-color:#f99287;
   }
   .loose:hover{
   background-color:#fb6d5e!important;
   }
   .semiloose{
   background-color:#efd8d1;
   }
   .semiloose:hover{
   background-color:#f19d84!important;
   }
</style>
<!-- Content area -->
<div class="content">
   <!-- Main content -->
   <!-- 2 columns form -->
   <form id="form1" class="form-horizontal" method="post" action="<?php echo KT_escapeAttribute(KT_getFullUri()); ?>" enctype="multipart/form-data">
      <div class="panel panel-flat">
         <div class="panel-body">
            <div class="row">
               <div class="col-md-6">
                  <fieldset>
                     <div class="form-group">
                        <label class="col-lg-3 control-label" for="starttime">تاریخ شروع:</label>
                        <div class="col-lg-9">
                           <input type="text" name="starttime"  id="datepicker5" class="form-control input-xs flt-left" readonly  placeholder="برای انتخاب تاریخ کلیک کنید"  autocomplete="off" tabindex="28" />
                        </div>
                     </div>
                     <div class="form-group">
                        <label class="col-lg-3 control-label" for="endtime">تاریخ پایان:</label>
                        <div class="col-lg-9">
                           <input type="text" name="endtime" readonly placeholder="برای انتخاب تاریخ کلیک کنید"  id="datepicker6"  class="form-control input-xs flt-left" autocomplete="off" tabindex="28" />
                        </div>
                     </div>
                  </fieldset>
               </div>
            </div>
            <div class="text-right">
               <button id="KT_Insert1" name="KT_Insert1" type="submit" class="btn btn-primary" tabindex="12">نمایش <i class="icon-arrow-left13 position-right"></i></button>
               <button id="reset" name="reset" type="reset" class="btn btn-warning" tabindex="13">بازنویسی <i class="icon-reset position-right"></i></button>
            </div>
         </div>
      </div>
      <input type="hidden" name="referurl" value="<?php echo $_SERVER['HTTP_REFERER']; ?>" />
   </form>
   <div class="panel panel-flat">
      <div class="panel-body">
         <h3>
            <i class="icon-chevron-left text-success"></i> اطلاعات درآمد تاریخ  <?php echo $shamsi_start_date; ?> تا تاریخ <?php echo $shamsi_end_date; ?>:
         </h3>
         <table class="table table-hover daramad">
            <tbody>
               <tr>
                  <td>مجموع واریز ها(شارژ حساب)</td>
                  <td><?=pricef($sumVarizi);?></td>
               </tr>
               <tr>
                  <td>مجموع پرداختی به کاربران(واریزی)</td>
                  <td><?=pricef($sumvarizihesab);?></td>
               </tr>
               <tr>
                  <td>مجموع مبالغ افزایش حساب توسط مدیریت</td>
                  <td><?=pricef($modiriyatIncreaseSum);?></td>
               </tr>
               <tr>
                  <td>مجموع مبالغ کاهش حساب توسط مدیریت</td>
                  <td><?=pricef($modiriyatDecreaseSum);?></td>
               </tr>
               <tr >
                  <td>مجموع مبالغ واریز کارمزد </td>
                  <td><?=pricef($sumvarizisubuser);?></td>
               </tr>
               <tr>
                  <td>درآمد کازینو</td>
                  <td>
                     <?php $daramad_casino=$sumVarizi_casino-$jayzeSum_casino-$sumvarizisubuser_casino;?>
                     <label style="font-weight: bold;color:<?php if($daramad_casino>0)echo 'green';else echo 'red';?>"><?=pricef($daramad_casino);?></label>  
                  </td>
               </tr>
               <tr>
                  <td>درآمد پیش بینی </td>
                  <td>
                     <?php $daramad_prematch=$barsabtShartSum-$jayzeSum_prematch-$sumsemiloose_prematch-$sumsemiwin_prematch-$sumreturn_prematch-$sumvarizisubuser_prematch-$sumnotstatus;?>
                     <label style="font-weight: bold;color:<?php if($daramad_prematch>0)echo 'green';else echo 'red';?>"><?=pricef($daramad_prematch);?></label>   
                  </td>
               </tr>
            </tbody>
         </table>
         <?php $mojodi_users=$sumVarizi-$sumvarizihesab+$modiriyatIncreaseSum-$modiriyatDecreaseSum-$daramad_prematch-$daramad_casino;?>
         <div>
            <p>
               <label> موجودی حساب کل کاربران : </label>     
               <span style="font-size:10px;">
               مجموع واریز ها - مجموع پرداختی به کاربران + مجموع افزایش حساب توسط مدیریت - مجموع کاهش حساب توسط مدیریت-درآمد پیشبینی-درآمد کازینو 
               </span>=
               <label style="font-weight: bold;color:<?php if($mojodi_users>0)echo 'green';else echo 'red';?>"><?=pricef($mojodi_users);?></label>                   
            </p>
         </div>
         <div>
            <p>
               <strong>سود خالص سایت:</strong>
               <?php $daramad_site = $daramad_prematch + $daramad_casino;?>
               <!--                                <label style="font-weight: bold;color:<?php if($daramad_site>0)echo 'green';else echo 'red';?>"><?=pricef($daramad_site);?></label>-->
               <span style="font-size:10px;">
               درآمد پیشبینی+درآمد کازینو    </span>=
               <label style="font-weight: bold;color:<?php if($daramad_site>0)echo 'green';else echo 'red';?>"><?=pricef($daramad_site);?></label>                   
            </p>
         </div>
      </div>
   </div>
				<div class="col-md-6">
					<a href="prematch_incomming.php">
					   <div class="panel panel-danger">
						  <div class="panel-heading notif-view">
							 <span>
							 نمایش درآمد پیش بینی
							 </span>
						  </div>
					   </div>
				   </a>
				</div>
				<div class="col-md-6">
				   <a href="casino_incomming.php">
					   <div class="panel panel-danger">
						  <div class="panel-heading notif-view">
							 <span>
							 نمایش درآمد کازینو
							 </span>
						  </div>
					   </div>
				   </a>
				</div>
</div>
<?php require('../../layouts/footer.php');?>