<?php 
   GLOBAL $content;
   $content['title']       = "درآمد پیشبینی سایت";
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
   $betgiftCount=0;
   $betcanceled=0;
   $betsemiloose=0;
   $betsemiwin=0;
   $betreturn=0;
   
   $query_Recordvarizi= "SELECT price,invoice_type FROM `transactions` WHERE created_at BETWEEN '$start_date ' AND '$end_date '  and status=1 ";
   $Recordvarizi = mysqli_query($cn,$query_Recordvarizi) or die(mysqli_error($cn));
   $row_Recordvarizi = mysqli_fetch_assoc($Recordvarizi);
   do{
   $invoice_type=$row_Recordvarizi['invoice_type'];
   
   if($invoice_type == 3 ){
   $barsabtShartSum += $row_Recordvarizi['price'];
   }elseif($invoice_type == 5 ){
   $sumvarizisubuser_prematch += $row_Recordvarizi['price'];
   }
   
   elseif($invoice_type == 2 or $invoice_type == 12){
   $jayzeSum += $row_Recordvarizi['price'];
    $betgiftCount++;
   }elseif($invoice_type == 14 ){
   $sumreturn +=$row_Recordvarizi['price'];
    $betreturn++;
   }elseif($invoice_type == 15 ){
   $sumsemiwin += $row_Recordvarizi['price'];
   $betsemiwin++;
   }elseif($invoice_type == 16 ){
   $sumsemiloose += $row_Recordvarizi['price'];
   $betsemiloose++;
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
   
   
   ?>
<div class="content">
   <!-- /page header -->
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
                     <td>مجموع مبالغ برداشت شده برای ثبت شرط</td>
                     <td><?=pricef($barsabtShartSum);?></td>
                  </tr>
                  <tr>
                     <td>تعداد شرط های(فرم) بسته شده </td>
                     <td><?=$betcount;?></td>
                  </tr>
                  <tr class="win">
                     <td>مجموع مبالغ شرط ها (فرم ها) ی برنده شده</td>
                     <td><?=pricef($jayzeSum);?></td>
                  </tr>
                  <tr class="win">
                     <td>تعداد شرط های(فرم) برنده شده </td>
                     <td><?=$betgiftCount;?></td>
                  </tr>
                  <tr class="loose">
                     <td>مجموع مبالغ شرط های(فرم) بازنده شده</td>
                     <td><?=pricef($totalfailed);?></td>
                  </tr>
                  <tr class="loose">
                     <td>تعداد شرط های(فرم) بازنده شده </td>
                     <td><?=$betfaildcount;?></td>
                  </tr>
                  <tr class="semiwin">
                     <td>مجموع  مبالغ  نیم برد شده</td>
                     <td><?=pricef($sumsemiwin);?></td>
                  </tr>
                  <tr class="semiwin">
                     <td>تعداد شرط های(فرم) نیم برد شده </td>
                     <td><?=$betsemiwin;?></td>
                  </tr>
                  <tr class="semiloose">
                     <td>مجموع  مبالغ  نیم باخت شده</td>
                     <td><?=pricef($sumsemiloose);?></td>
                  </tr>
                  <tr class="semiloose">
                     <td>تعداد شرط های(فرم) نیم باخت شده </td>
                     <td><?=$betsemiloose;?></td>
                  </tr>
                  <tr class="return">
                     <td>مجموع  مبالغ  استرداد شده</td>
                     <td><?=pricef($sumreturn);?></td>
                  </tr>
                  <tr class="return">
                     <td>تعداد شرط های(فرم) استرداد شده </td>
                     <td><?=$betreturn;?></td>
                  </tr>
                  <tr >
                     <td>مجموع  مبالغ  مشخص نشده</td>
                     <td><?=pricef($sumnotstatus);?></td>
                  </tr>
                  <tr >
                     <td>مجموع مبالغ واریز کارمزد </td>
                     <td><?=pricef($sumvarizisubuser_prematch);?></td>
                  </tr>
                  <tr>
                     <td>تعداد شرط های(فرم) مشخص نشده (در جریان) </td>
                     <td><?=$betnotstatus;?></td>
                  </tr>
               </tbody>
            </table>
            <?php $daramad=$barsabtShartSum-$jayzeSum-$sumsemiloose-$sumsemiwin-$sumreturn-$sumvarizisubuser_prematch;?>
            <div>
               <p>  <label> درآمد احتمالی: </label>     
                  <span style="font-size:10px;">
                  ( مجموع مبالغ برداشت شده برای ثبت شرط-مجموع مبالغ ( برنده+نیم برد+نیم باخت) شده-مجموع مبالغ واریز کارمزد-مجموع مبالغ استرداد شده)
                  </span>=
                  <label style="font-weight: bold;color:<?php if($daramad>0)echo 'green';else echo 'red';?>"><?=pricef($daramad);?></label>                   
               </p>
            </div>
            <?php $daramad=$barsabtShartSum-$jayzeSum-$sumsemiloose-$sumsemiwin-$sumreturn-$sumnotstatus-$sumvarizisubuser_prematch;?>
            <div>
               <p>  <label> درآمد قطعی: </label> 
                  <span style="font-size:10px;">
                  (مجموع مبالغ برداشت شده برای ثبت شرط-مجموع مبالغ ( برنده+نیم برد+نیم باخت) شده-مجموع مبالغ واریز کارمزد- مجموع مبالغ استرداد شده-مجموع مبالغ مشخص نشده)    </span>=
                  <label style="font-weight: bold;color:<?php if($daramad>0)echo 'green';else echo 'red';?>"><?=pricef($daramad);?></label>                   
               </p>
            </div>
         </div>
      </div>
   </div>
</div>
<?php require('../../layouts/footer.php');?>