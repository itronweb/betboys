<?php 
   GLOBAL $content;
   $content['title']       = "درآمد کازینو سایت";
   require('../../layouts/sidebar.php');
   
   mysqli_select_db($cn, $database_cn);
   
   $query_casino = "SELECT * FROM casino where status='1' ORDER BY id asc";
   $casino = mysqli_query($cn, $query_casino) or die(mysqli_error($cn));
   $row_casino = mysqli_fetch_assoc($casino);
   $totalRows_casino = mysqli_num_rows($casino);
   
   $casino_start = $casino_win = '1=1';
   
   
   if(isset($_POST['casino_filter'])) {
   	$casino_ft=implode(',',$_POST['casino_filter']);
   	$casino_start ='(invoice_type = 7'.implode(' OR invoice_type = 7',$_POST['casino_filter']).")";
   	$casino_win ='(invoice_type = 8'.implode(' OR invoice_type = 8',$_POST['casino_filter']).")";
   
   }
   
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
   
   $y=substr($start_date,0,4);
   $mo=substr($start_date,5,2);
   $d=substr($start_date,8,2);
   $h=substr($start_date,11,2); 
   $m=substr($start_date,14,2);
   $se=substr($s,17,2); 
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
   $y=substr($s,0,4);
   $mo=substr($s,5,2);
   $d=substr($s,8,2);
   $h=substr($s,11,2); 
   $m=substr($s,14,2);
   $se=substr($s,17,2); 
   $time=mktime($h,$m,$se,$mo,$d,$y);
   $shamsi_start_date=jdate('j F Y ',$time);
   $shamsi_end_date=jdate('j F Y ',$time);
   }
   
   
   $start_date.=' 00:00:00.000000';
   $end_date.=' 23:59:59.000000';
   $betgiftCount=0;
   
   
   $query_Recordvarizi= "SELECT price,invoice_type FROM `transactions` WHERE created_at BETWEEN '$start_date ' AND '$end_date '  and status=1 and ($casino_start  OR $casino_win)";
   
   $Recordvarizi = mysqli_query($cn,$query_Recordvarizi) or die(mysqli_error($cn));
   $row_Recordvarizi = mysqli_fetch_assoc($Recordvarizi);
   
   
   do{
   	
   	$invoice_type=substr($row_Recordvarizi['invoice_type'],0,1);
   	if ( $invoice_type == 7 ){
   		$sumVarizi += $row_Recordvarizi['price'];//8899607
   	}
   	elseif($invoice_type == 8){
   	$jayzeSum += $row_Recordvarizi['price'];
       $betgiftCount++;
   	}
   	elseif($invoice_type == 9){
   	
   	$sumvarizisubuser_casino += $row_Recordvarizi['price'];
       $betgiftCount_casino++;
   	}
   	
   }while($row_Recordvarizi = mysqli_fetch_assoc($Recordvarizi));
   
   
   
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
<div class="content">
   <form id="form1" class="form-horizontal" method="post" action="<?php echo KT_escapeAttribute(KT_getFullUri()); ?>" enctype="multipart/form-data">
      <div class="panel panel-flat">
         <div class="panel-body">
            <div class="row">
               <div class="col-md-6">
                  <fieldset>
                     <div class="form-group">
                        <label class="col-lg-3 control-label" for="casino_filter">انتخاب کازینو:</label>
                        <div class="multi-select-full col-lg-9" style="padding:  0;padding-right: 2%;width: 73%;">
                           <select id="casino_filter" name="casino_filter[]" class="multiselect-select-all-filtering" multiple="multiple" style="display: none;" tabindex="1">
                              <?php do { ?>
                              <option value="<?php echo $row_casino['id']; ?>"  ><?php echo $row_casino['name_fa']; ?></option>
                              <?php } while ($row_casino = mysqli_fetch_assoc($casino)); ?>
                           </select>
                        </div>
                     </div>
                     <div class="form-group">
                        <label class="col-lg-3 control-label" for="starttime">تاریخ شروع:</label>
                        <div class="col-lg-9">
                           <input type="text" name="starttime"  id="datepicker5" class="form-control input-xs flt-left" readonly  placeholder="برای انتخاب تاریخ کلیک کنید"  autocomplete="off" tabindex="2" />
                        </div>
                     </div>
                     <div class="form-group">
                        <label class="col-lg-3 control-label" for="endtime">تاریخ پایان:</label>
                        <div class="col-lg-9">
                           <input type="text" name="endtime" readonly placeholder="برای انتخاب تاریخ کلیک کنید"  id="datepicker6"  class="form-control input-xs flt-left" autocomplete="off" tabindex="3" />
                        </div>
                     </div>
                  </fieldset>
               </div>
            </div>
            <div class="text-right">
               <button id="KT_Insert1" name="KT_Insert1" type="submit" class="btn btn-primary" tabindex="4">نمایش <i class="icon-arrow-left13 position-right"></i></button>
               <button id="reset" name="reset" type="reset" class="btn btn-warning" tabindex="5">بازنویسی <i class="icon-reset position-right"></i></button>
            </div>
         </div>
      </div>
      <input type="hidden" name="referurl" value="<?php echo $_SERVER['HTTP_REFERER']; ?>" />
   </form>
   <div class="panel panel-flat">
      <div class="panel-body">
         <h3>
            <i class="icon-chevron-left text-success"></i> اطلاعات درآمد تاریخ  <?php echo $shamsi_start_date; ?> تا تاریخ <?php echo $shamsi_end_date; ?>
            <?php if (!empty($casino_ft)){?>
            <span class="label label-default label-rounded">بازی های :
            <?php $casino_ft = explode(',', $casino_ft) ;
               foreach($casino_ft as $casino_fts){
               $query_casino = "SELECT name_fa FROM casino where status='1' AND id = $casino_fts  ORDER BY id asc";
               $casino = mysqli_query($cn, $query_casino) or die(mysqli_error($cn));
               $row_casino = mysqli_fetch_assoc($casino);
                 
               ?>
            <span class="badge badge-danger"><?php echo $row_casino['name_fa']; ?></span>
            <?php } ?>
            </span>
            <?php }else {?>
            <span class="label label-default label-rounded">تمام بازی ها 
            </span>
            <?php } ?>
         </h3>
         <table class="table table-hover daramad">
            <tbody>
               <tr>
                  <td>پرداختی کاربران</td>
                  <td><?=pricef($sumVarizi);?></td>
               </tr>
               <tr class="">
                  <td> برد کاربران</td>
                  <td><?=pricef($jayzeSum);?></td>
               </tr>
               <tr class="">
                  <td>مجموع مبالغ واریز کارمزد </td>
                  <td><?=pricef($sumvarizisubuser_casino);?></td>
               </tr>
               <tr class="">
                  <td>تعداد شرط های برنده شده </td>
                  <td><?=$betgiftCount;?></td>
               </tr>
               <!--
                  <tr>
                  	<td>مجموع مبالغ واریزی از کارمزد زیرمجموعه:</td>
                  	<td >
                  		<?php echo pricef($sumvarizisubuser);?>
                  		<span class="badge badge-danger"> غیر فعال</span>
                  	</td>
                  </tr>
                  -->
            </tbody>
         </table>
         <?php $daramad=$sumVarizi-$jayzeSum-$sumvarizisubuser_casino;?>  
         <div>
            <p>  <label> درآمد کازینو: </label>     
               <span style="font-size:10px;">
               پرداختی کاربران - برد کاربران - مجموع مبالغ واریز کارمزد
               </span>=
               <label style="font-weight: bold;color:<?php if($daramad>0)echo 'green';else echo 'red';?>"><?=pricef($daramad);?></label>                   
            </p>
         </div>
      </div>
   </div>
</div>
<?php require('../../layouts/footer.php');?>