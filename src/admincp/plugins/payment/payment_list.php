<?php 
   GLOBAL $content;
   $content['title']       = "لیست پرداختی های کاربران";
   require('../../layouts/sidebar.php');
   
   // Make unified connection variable
   $conn_cn = new KT_connection($cn, $database_cn);
   $id= decrypt($_GET['id'],session_id()."user");
   $cat=$_GET['cat'];
   // Filter
   $tfi_listrspay = new TFI_TableFilter($conn_cn, "tfi_listrspay");
   $tfi_listrspay->addColumn("transactions.id", "STRING_TYPE", "id", "%");
   $tfi_listrspay->addColumn("transactions.description", "STRING_TYPE", "description", "%");
   $tfi_listrspay->addColumn("transactions.price", "NUMERIC_TYPE", "price", "=");
   $tfi_listrspay->addColumn("transactions.status", "NUMERIC_TYPE", "status", "=");
   //$tfi_listrspay->addColumn("transactions.invoice_type", "NUMERIC_TYPE", "invoice_type", "=");
   if($_GET['cat'] =="3"){
   $tfi_listrspay->addColumn("transactions.invoice_type", "STRING_TYPE", "invoice_type", "%");
   }else{
   $tfi_listrspay->addColumn("transactions.invoice_type", "NUMERIC_TYPE", "invoice_type", "=");
   }
   $tfi_listrspay->addColumn("transactions.created_at", "STRING_TYPE", "created_at", "%");
   if($_GET['id'] ==""){
   $tfi_listrspay->addColumn("users.first_name", "STRING_TYPE", "fname", "%");
   $tfi_listrspay->addColumn("users.last_name", "STRING_TYPE", "lname", "%");
   }
   $tfi_listrspay->Execute();
   
   // Sorter
   $tso_listrspay = new TSO_TableSorter("rs1", "tso_listrspay");
   
   if($_GET['id'] == ""){
   $tso_listrspay->addColumn("transactions.user_id");}
   $tso_listrspay->addColumn("transactions.id");
   $tso_listrspay->addColumn("invoice_type");
   $tso_listrspay->addColumn("description");
   $tso_listrspay->addColumn("price");
   $tso_listrspay->addColumn("transactions.created_at");
   $tso_listrspay->addColumn("status");
   $tso_listrspay->setDefault("transactions.id DESC");
   $tso_listrspay->Execute();
   
   // Change Show Per Page
   if(isset($_POST['tfi_listrspay_pgnum']))
   @$_SESSION['tfi_listrspay_pgnum']=GetSQLValueString($_POST['tfi_listrspay_pgnum'], "int");
   else if(KT_escapeAttribute(@$_SESSION['tfi_listrspay_pgnum'])=='')
   @$_SESSION['tfi_listrspay_pgnum']=10;
   
   // Navigation
   $nav_listrspay = new NAV_Regular("nav_listrspay", "rs1", "../", $_SERVER['PHP_SELF'], KT_escapeAttribute(@$_SESSION['tfi_listrspay_pgnum']));
   
   //NeXTenesio3 Special List Recordset
   $maxRows_rs1 = $_SESSION['max_rows_nav_listrspay'];
   $pageNum_rs1 = 0;
   if (isset($_GET['pageNum_rs1'])) {
   $pageNum_rs1 = $_GET['pageNum_rs1'];
   }
   $startRow_rs1 = $pageNum_rs1 * $maxRows_rs1;
   
   // Defining List Recordset variable
   $NXTFilter_rs1 = "1=1";
   if (isset($_SESSION['filter_tfi_listrspay'])) {
   $NXTFilter_rs1 = $_SESSION['filter_tfi_listrspay'];
   }
   // Defining List Recordset variable
   $NXTSort_rs1 = "id DESC";
   if (isset($_SESSION['sorter_tso_listrspay'])) {
   $NXTSort_rs1 = $_SESSION['sorter_tso_listrspay'];
   }
   if($_GET['id'] !=""){
   $shartt=sprintf(" user_id = %s and ", GetSQLValueString($id, "int"));
   }
   if($_GET['cat'] ==1){
   $shartt .=" (invoice_type=2 or invoice_type=3 or invoice_type=12 or invoice_type=13 or invoice_type=14 or invoice_type=15 or invoice_type=16) and ";
   }elseif($_GET['cat'] ==2){
   $shartt .=" (invoice_type=20) and ";
   }elseif($_GET['cat'] ==3){
   $shartt .=" invoice_type BETWEEN 70 AND 92 and ";
   }
   mysqli_select_db($cn,$database_cn);
   $query_rs1 = "SELECT transactions.*,users.first_name,users.last_name,users.email  FROM `transactions` left join users on transactions.user_id=users.id WHERE  $shartt   {$NXTFilter_rs1}  ORDER BY  {$NXTSort_rs1} ";
   
   $query_limit_rs1 = sprintf("%s LIMIT %d, %d", $query_rs1, $startRow_rs1, $maxRows_rs1);
   $rs1 = mysqli_query($cn,$query_limit_rs1) or die(mysqli_error($cn));
   $row_rs1 = mysqli_fetch_assoc($rs1);
   
   if (isset($_GET['totalRows_rs1'])) {
   $totalRows_rs1 = $_GET['totalRows_rs1'];
   } else { 
   $all_rs1 = mysqli_query($cn, $query_rs1);
   $totalRows_rs1 = mysqli_num_rows($all_rs1);
   }
   $totalPages_rs1= ceil($totalRows_rs1/$maxRows_rs1)-1;
   
   $queryString_rs1  = "";
   if (!empty($_SERVER['QUERY_STRING'])) {
   $params = explode("&", $_SERVER['QUERY_STRING']);
   $newParams = array();
   foreach ($params as $param) {
    if (stristr($param, "pageNum_rs1") == false && 
        stristr($param, "totalRows_rs1") == false) {
      array_push($newParams, $param);
    }
   }
   if (count($newParams) != 0) {
    $queryString_rs1 = "&" . htmlentities(implode("&", $newParams));
   }
   }
   
   if($_GET['id'] != ""){  
   $query_Recordsetad1 = "SELECT * FROM `users` WHERE `id` = '".GetSQLValueString($id, "int")."'";
   $Recordad1 = mysqli_query($cn,$query_Recordsetad1) or die(mysqli_error($cn));
   $row_Recordadd1 = mysqli_fetch_assoc($Recordad1);
   
   }
   
   ?>
<!-- Content area -->
<div class="row">
   <!-- Main content -->
   <!-- 2 columns form -->                       
   <div class="panel panel-flat">
      <div class="panel-body">
         <div class="row">
            <legend>
            <div class="btn-group btn-group-justified">
               <a href="<?php echo $nav_listrspay->getShowAllLink(); ?>" class="btn bg-slate-700"><i class="icon-stack2"></i> <?php echo NXT_getResource("Show"); ?>
               <?php 
                  if (@$_GET['show_all_nav_listrspay'] == 1) 
                      echo $_SESSION['default_max_rows_nav_listrspay']; 
                  else  
                      echo NXT_getResource("all");  
                     
                  echo ' '.NXT_getResource("records"); ?></a>
               <!--[if !IE]>start  tabs<![endif]-->
               <?php 
                  if (@$_SESSION['has_filter_tfi_listrspay'] == 1) {
                  ?>
               <a href="<?php echo $tfi_listrspay->getResetFilterLink(); ?>" class="btn bg-slate-700"><i class="icon-search4"></i> <?php echo NXT_getResource("Reset filter"); ?></a>
               <?php 
                  } else { ?>
               <a href="<?php echo $tfi_listrspay->getShowFilterLink(); ?>" class="btn bg-slate-700"><i class="icon-search4"></i> <?php echo NXT_getResource("Show filter"); ?></a>
               <?php } ?>
               <div class="btn bg-slate-700">
                  <div class="form-group">
                     <label class="col-lg-2 control-label text-center" style="padding-top:8px;color:#FFF;">تعداد نمایش</label>
                     <div class="col-lg-10">
                        <form name="formpgnum" id="formpgnum" action="<?php $_SERVER['PHP_SELF'];?>" method="post">
                           <select style="float:left;width:50%;" name="tfi_listrspay_pgnum" id="tfi_listrspay_pgnum" class="form-control" onchange="submit();" tabindex="1">
                              <option value="10" <?php if (!(strcmp('10', KT_escapeAttribute(@$_SESSION['tfi_listrspay_pgnum'])))) {echo "selected=\"selected\"";} ?>>10</option>
                              <option value="15" <?php if (!(strcmp('15', KT_escapeAttribute(@$_SESSION['tfi_listrspay_pgnum'])))) {echo "selected=\"selected\"";} ?>>15</option>
                              <option value="20" <?php if (!(strcmp('20', KT_escapeAttribute(@$_SESSION['tfi_listrspay_pgnum'])))) {echo "selected=\"selected\"";} ?>>20</option>
                              <option value="30" <?php if (!(strcmp('30', KT_escapeAttribute(@$_SESSION['tfi_listrspay_pgnum'])))) {echo "selected=\"selected\"";} ?>>30</option>
                              <option value="50" <?php if (!(strcmp('50', KT_escapeAttribute(@$_SESSION['tfi_listrspay_pgnum'])))) {echo "selected=\"selected\"";} ?>>50</option>
                              <option value="100" <?php if (!(strcmp('100', KT_escapeAttribute(@$_SESSION['tfi_listrspay_pgnum'])))) {echo "selected=\"selected\"";} ?>>100</option>
                           </select>
                        </form>
                     </div>
                  </div>
               </div>
            </div>
            <form action="<?php echo KT_escapeAttribute(KT_getFullUri()); ?>" method="post" id="form1">
               <div class="table-responsive">
                  <table class="table table-bordered">
                     <thead>
                        <tr>
                           <th id="id"><a href="<?php echo $tso_listrspay->getSortLink('transactions.id'); ?>"> شناسه داخلی تراکنش</a></th>
                           <?php if($_GET['id'] == ""){ ?>
                           <th id="user_id">
                              <a href="<?php echo $tso_listrspay->getSortLink('transactions.user_id'); ?>">
                              کاربر
                              </a>
                           </th>
                           <?php } ?>
                           <th id="price"><a href="<?php echo $tso_listrspay->getSortLink('price'); ?>">مبلغ پرداختی </a></th>
                           <th id="description"><a href="<?php echo $tso_listrspay->getSortLink('description'); ?>">شرح</a></th>
                           <th id="invoice_type"><a href="<?php echo $tso_listrspay->getSortLink('invoice_type'); ?>">نوع</a></th>
                           <th id="created_at"><a href="<?php echo $tso_listrspay->getSortLink('transactions.created_at'); ?>"> تاریخ ثبت</a></th>
                           <th id="status"><a href="<?php echo $tso_listrspay->getSortLink('status'); ?>"> وضعیت</a></th>
                           <?php if ($totalRows_rs1>0 || @$_SESSION['has_filter_tfi_listrspay'] == 1) { ?>
                           <?php if (@$_SESSION['has_filter_tfi_listrspay'] == 1) {
                              ?>  
                           <th></th>
                           <?php } ?>
                           <?php }?>
                        </tr>
                        <?php 
                           // Show IF Conditional region3
                           if (@$_SESSION['has_filter_tfi_listrspay'] == 1) {
                           ?>
                        <tr>
                           <td><input type="text" name="tfi_listrspay_id" id="tfi_listrspay_id" class="form-control" value="<?php echo KT_escapeAttribute(@$_SESSION['tfi_listrspay_id']); ?>" maxlength="20" tabindex="1" size="12" /></td>
                           <?php if($_GET['id'] == ""){ ?> 
                           <td><input type="text" name="tfi_listrspay_fname" id="tfi_listrspay_fname" class="form-control w-50 col-md-6" placeholder="نام" value="<?php echo KT_escapeAttribute(@$_SESSION['tfi_listrspay_fname']); ?>" maxlength="20" tabindex="1" size="12" />
                              <input type="text" name="tfi_listrspay_lname" id="tfi_listrspay_lname" class="form-control w-50 col-md-6" placeholder="نام خانوادگی" value="<?php echo KT_escapeAttribute(@$_SESSION['tfi_listrspay_lname']); ?>" maxlength="20" tabindex="1" size="12" />
                           </td>
                           <?php } ?>
                           <td><input type="text" name="tfi_listrspay_price" id="tfi_listrspay_price" class="form-control text-right flt-left number" value="<?php echo KT_escapeAttribute(@$_SESSION['tfi_listrspay_price']); ?>" maxlength="20" tabindex="2" size="12" /></td>
                           <td><input type="text" name="tfi_listrspay_description" id="tfi_listrspay_description" class="form-control text-right" value="<?php echo KT_escapeAttribute(@$_SESSION['tfi_listrspay_description']); ?>" maxlength="20" tabindex="2" size="12" /></td>
                           <td>
                              <select name="tfi_listrspay_invoice_type" id="tfi_listrspay_invoice_type" class="form-control" tabindex="5">
                                 <?php if($_GET['cat'] =="1"){ ?>
                                 <option value="" <?php if (!(strcmp("", KT_escapeAttribute(@$_SESSION['tfi_listrspay_invoice_type'])))) {echo "selected=\"selected\"";} ?>></option>
                                 <option value="2" <?php if (!(strcmp(2, KT_escapeAttribute(@$_SESSION['tfi_listrspay_invoice_type'])))) {echo "selected=\"selected\"";} ?>>واریز برد شرط</option>
                                 <option value="3" <?php if (!(strcmp(3, KT_escapeAttribute(@$_SESSION['tfi_listrspay_invoice_type'])))) {echo "selected=\"selected\"";} ?>>برداشت برای ثبت شرط</option>
                                 <option value="12" <?php if (!(strcmp(12, KT_escapeAttribute(@$_SESSION['tfi_listrspay_invoice_type'])))) {echo "selected=\"selected\"";} ?>>تغییر وضعیت شرط به برد</option>
                                 <option value="13" <?php if (!(strcmp(13, KT_escapeAttribute(@$_SESSION['tfi_listrspay_invoice_type'])))) {echo "selected=\"selected\"";} ?>> تغییر وضعیت شرط به باخت</option>
                                 <option value="14" <?php if (!(strcmp(14, KT_escapeAttribute(@$_SESSION['tfi_listrspay_invoice_type'])))) {echo "selected=\"selected\"";} ?>>تغییر وضعیت شرط به استرداد</option>
                                 <option value="15" <?php if (!(strcmp(15, KT_escapeAttribute(@$_SESSION['tfi_listrspay_invoice_type'])))) {echo "selected=\"selected\"";} ?>>تغییر وضعیت شرط به نیم برد</option>
                                 <option value="16" <?php if (!(strcmp(16, KT_escapeAttribute(@$_SESSION['tfi_listrspay_invoice_type'])))) {echo "selected=\"selected\"";} ?>>  تغییر وضعیت شرط به نیم باخت</option>
                                 <?php }elseif($_GET['cat'] =="2"){?>
                                 <option value="20" <?php if (!(strcmp(20, KT_escapeAttribute(@$_SESSION['tfi_listrspay_invoice_type'])))) {echo "selected=\"selected\"";} ?>>شارژ  کارت به کارت حساب</option>
                                 <?php }elseif($_GET['cat'] =="3"){?>
                                 <option value="" <?php if (!(strcmp("", KT_escapeAttribute(@$_SESSION['tfi_listrspay_invoice_type'])))) {echo "selected=\"selected\"";} ?>></option>
                                 <option value="7" <?php if (!(strcmp(7, KT_escapeAttribute(@$_SESSION['tfi_listrspay_invoice_type'])))) {echo "selected=\"selected\"";} ?>>شروع بازی</option>
                                 <option value="8" <?php if (!(strcmp(8, KT_escapeAttribute(@$_SESSION['tfi_listrspay_invoice_type'])))) {echo "selected=\"selected\"";} ?>> برد بازی</option>
                                 <?php }
                                    else{ ?>
                                 <option value="" <?php if (!(strcmp("", KT_escapeAttribute(@$_SESSION['tfi_listrspay_invoice_type'])))) {echo "selected=\"selected\"";} ?>></option>
                                 <option value="1" <?php if (!(strcmp(1, KT_escapeAttribute(@$_SESSION['tfi_listrspay_invoice_type'])))) {echo "selected=\"selected\"";} ?>>شارژ حساب</option>
                                 <option value="20" <?php if (!(strcmp(20, KT_escapeAttribute(@$_SESSION['tfi_listrspay_invoice_type'])))) {echo "selected=\"selected\"";} ?>>شارژ  کارت به کارت حساب</option>
                                 <option value="21" <?php if (!(strcmp(21, KT_escapeAttribute(@$_SESSION['tfi_listrspay_invoice_type'])))) {echo "selected=\"selected\"";} ?>>برگشت وجه لغو درخواست  </option>
                                 <option value="2" <?php if (!(strcmp(2, KT_escapeAttribute(@$_SESSION['tfi_listrspay_invoice_type'])))) {echo "selected=\"selected\"";} ?>>واریز برد شرط</option>
                                 <option value="3" <?php if (!(strcmp(3, KT_escapeAttribute(@$_SESSION['tfi_listrspay_invoice_type'])))) {echo "selected=\"selected\"";} ?>>برداشت برای ثبت شرط</option>
                                 <option value="4" <?php if (!(strcmp(4, KT_escapeAttribute(@$_SESSION['tfi_listrspay_invoice_type'])))) {echo "selected=\"selected\"";} ?>>درخواست وجه</option>
                                 <option value="5" <?php if (!(strcmp(5, KT_escapeAttribute(@$_SESSION['tfi_listrspay_invoice_type'])))) {echo "selected=\"selected\"";} ?>>واریز کارمزد کاربر زیرمجموعه</option>
                                 <option value="10" <?php if (!(strcmp(10, KT_escapeAttribute(@$_SESSION['tfi_listrspay_invoice_type'])))) {echo "selected=\"selected\"";} ?>>واریز توسط مدیریت</option>
                                 <option value="11" <?php if (!(strcmp(11, KT_escapeAttribute(@$_SESSION['tfi_listrspay_invoice_type'])))) {echo "selected=\"selected\"";} ?>>برداشت توسط مدیریت</option>
                                 <option value="12" <?php if (!(strcmp(12, KT_escapeAttribute(@$_SESSION['tfi_listrspay_invoice_type'])))) {echo "selected=\"selected\"";} ?>>تغییر وضعیت شرط به برد</option>
                                 <option value="13" <?php if (!(strcmp(13, KT_escapeAttribute(@$_SESSION['tfi_listrspay_invoice_type'])))) {echo "selected=\"selected\"";} ?>> تغییر وضعیت شرط به باخت</option>
                                 <option value="14" <?php if (!(strcmp(14, KT_escapeAttribute(@$_SESSION['tfi_listrspay_invoice_type'])))) {echo "selected=\"selected\"";} ?>>تغییر وضعیت شرط به استرداد</option>
                                 <option value="15" <?php if (!(strcmp(15, KT_escapeAttribute(@$_SESSION['tfi_listrspay_invoice_type'])))) {echo "selected=\"selected\"";} ?>>تغییر وضعیت شرط به نیم برد</option>
                                 <option value="16" <?php if (!(strcmp(16, KT_escapeAttribute(@$_SESSION['tfi_listrspay_invoice_type'])))) {echo "selected=\"selected\"";} ?>>  تغییر وضعیت شرط به نیم باخت</option>
                                 <?php } ?>
                              </select>
                           </td>
                           <td></td>
                           <td>
                              <select name="tfi_listrspay_status" id="tfi_listrspay_status" class="form-control" tabindex="5">
                                 <option value="" <?php if (!(strcmp("", KT_escapeAttribute(@$_SESSION['tfi_listrspay_status'])))) {echo "selected=\"selected\"";} ?>></option>
                                 <option value="1" <?php if (!(strcmp(1, KT_escapeAttribute(@$_SESSION['tfi_listrspay_status'])))) {echo "selected=\"selected\"";} ?>>پرداخت موفق</option>
                                 <option value="0" <?php if (!(strcmp(0, KT_escapeAttribute(@$_SESSION['tfi_listrspay_status'])))) {echo "selected=\"selected\"";} ?>>پرداخت ناموفق</option>
                              </select>
                           </td>
                           <td><input type="submit" name="tfi_listrspay" class="btn btn-primary btn-xs" value="<?php echo NXT_getResource("Filter"); ?>" tabindex="6" /></td>
                        </tr>
                        <?php } 
                           // endif Conditional region3
                           ?>
                     </thead>
                     <tbody>
                        <?php if ($totalRows_rs1 == 0) { // Show if recordset empty ?>
                        <tr>
                           <td colspan="<?php if (@$_SESSION['has_filter_tfi_listrspay'] == 1) echo '8'; else echo '9'; ?>">اطلاعاتی یافت نشد .</td>
                        </tr>
                        <?php } // Show if recordset empty ?>
                        <?php if ($totalRows_rs1 > 0) { // Show if recordset not empty ?>
                        <?php do { ?>
                        <tr>
                           <td ><?php echo $row_rs1['id']; ?></td>
                           <?php if($_GET['id'] == ""){ ?>
                           <td style='direction:ltr;'><?php 
							$row_user = dbRead('users', ['id'=>decrypt( $row_rs1['user_id'], session_id() . "user" )]) ;
                              echo "<a href='../users/s_users_edit.php?id=".$row_user['id']."'>".$row_rs1['first_name'].' '.$row_rs1['last_name'];
                              						  ?></td>
                           <?php } ?>
                           <td><?php echo pricef($row_rs1['price']); ?></td>
                           <td><?php echo $row_rs1['description'];
                              if($row_rs1['invoice_type']==1){
                               echo "</br><label style='direction:ltr'>".$row_rs1['pay_code']."</label>";
                              }
                              ?></td>
                           <td><?php
                              $invoic_casino = $row_rs1['invoice_type'];
                              if($invoic_casino >= 70){
                              	if(strlen($invoic_casino) >= 2){
                              		$invoice_type_1 = substr($invoic_casino,0,1);
                              		$invoice_type_2 = substr($invoic_casino,1,2);
                              	}
                              	if ( $invoice_type_1 == 7 ){
                              		echo "شروع بازی";
                              	}elseif( $invoice_type_1 == 8 ){
                              		echo "برد بازی";
                              	}
                              }else{
                              	
                              	if($row_rs1['invoice_type']==1)
                              		echo "شارژ حساب";
                              	else if ($row_rs1['invoice_type']==2)
                              		echo "واریز برد شرط";
                              	else if ($row_rs1['invoice_type']==3)
                              		 echo "برداشت برای ثبت شرط";
                              	else if ($row_rs1['invoice_type']==4)
                              		echo "درخواست وجه";
                              	else if ($row_rs1['invoice_type']==5)
                              		echo "واریز کارمزد کاربر زیرمجموعه";
                              	else if ($row_rs1['invoice_type']==10)
                              	   echo "واریز توسط مدیریت";
                              	else if ($row_rs1['invoice_type']==11)
                              		echo "برداشت توسط مدیریت";
                              	else if ($row_rs1['invoice_type']==12)
                              		echo "تغییر وضعیت شرط به برد";
                              	else if ($row_rs1['invoice_type']==13)
                              		echo " تغییر وضعیت شرط به باخت";
                              	else if ($row_rs1['invoice_type']==14)
                              		echo "تغییر وضعیت شرط به استرداد";
                              	else if ($row_rs1['invoice_type']==15)
                              		echo "  تغییر وضعیت شرط به نیم برد";
                              	else if ($row_rs1['invoice_type']==16)
                              		echo "تغییر وضعیت شرط به نیم باخت";
                              	else if ($row_rs1['invoice_type']==20)
                              		echo "شارژ کارت به کارت حساب";
                              	else if ($row_rs1['invoice_type']==21)
                              		echo "برگشت لغو درخواست وجه";
                              	
                              }
                              ?>
                           </td>
                           <td>
                              <?php $s= str_replace("-","/",$row_rs1['created_at']);?>
                              <?php $s=miladi_to_jalali($row_rs1['created_at'],"-"," "); ?>
                              <?php echo substr($s,8,2)." "; ?><?php echo monthname(substr($s,5,2)); ?>
                              <?php echo substr($s,0,4)." "; ?>
                              <?php echo "- ".substr($s,11,9); ?>
                           </td>
                           <td><?php if ($row_rs1['status'] == 0){
                              echo "<span class='label label-danger'>پرداخت ناموفق</span>";
                              }
                              else if($row_rs1['status'] == 1){
                              echo "<span style='color:#FFF'>پرداخت موفق</span></br>";?>
                              <?php } else if($row_rs1['status'] == 2){
                                 echo "<span style='color:red'>پرداخت لغو شد</span></br>";?>
                              <?php } 
                                 if($row_rs1['invoice_type']==20){?>
                              <?php if(in_array('payment_view', $accccesslist)){?>
                              </br><a href="payment_view.php?id=<?php echo encrypt($row_rs1['id'],session_id()."paym"); ?>" data-popup="tooltip" >تغییر وضعیت</a>
                              <?php }?>
                              <?php  }?>
                           </td>
                           <?php if (@$_SESSION['has_filter_tfi_listrspay'] == 1) {
                              ?>  
                           <td></td>
                           <?php } ?>
                        </tr>
                        <?php } while ($row_rs1 = mysqli_fetch_assoc($rs1)); ?>
                        <?php } // Show if recordset not empty ?>
                     </tbody>
                  </table>
				  
				<div class="col-md-4">
				   <a href="?cat=3">
					   <div class="panel panel-danger">
						  <div class="panel-heading notif-view">
							 <span>
							 تراکنش های مالی کازینو
							 </span>
						  </div>
					   </div>
				   </a>
				</div>
				<div class="col-md-4">
				   <a href="?cat=2">
					   <div class="panel panel-danger">
						  <div class="panel-heading notif-view">
							 <span>
							 تراکنش های کارت به کارت
							 </span>
						  </div>
					   </div>
				   </a>
				</div>
				<div class="col-md-4">
				   <a href="?cat=1">
					   <div class="panel panel-danger">
						  <div class="panel-heading notif-view">
							 <span>
							 تراکنش های مالی شرط ها
							 </span>
						  </div>
					   </div>
				   </a>
				</div>
				
               </div>
            </form>
            <br /><br />
            <div class="text-center">
               <?php include("../../layouts/paging.php"); 
                  echo pg('pageNum_rs1', $pageNum_rs1, $currentPage, $queryString_rs1, $totalPages_rs1);
                  ?>
            </div>
         </div>
      </div>
   </div>
</div>
</div>
<?php require('../../layouts/footer.php');?>