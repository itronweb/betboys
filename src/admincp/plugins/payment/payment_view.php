<?php  
require_once('../../checklogin.php'); 

// Load the common classes
require_once('../../includes/common/KT_common.php');

// Load the required classes
require_once('../../includes/tfi/TFI.php');
require_once('../../includes/tso/TSO.php');
require_once('../../includes/nav/NAV.php');
//require_once('../../includes/file/jdf.php');
// Make unified connection variable
$conn_cn = new KT_connection($cn, $database_cn);
$id= decrypt($_GET['id'],session_id()."paym");

// Defining List Recordset variable
$NXTSort_rs1 = "id DESC";
if (isset($_SESSION['sorter_tso_listrsbets'])) {
  $NXTSort_rs1 = $_SESSION['sorter_tso_listrsbets'];
}
if($_GET['id'] !=""){
	$shartt=sprintf(" `id` = %s ", GetSQLValueString($id, "int"));
}
mysqli_select_db($cn,$database_cn);
$query_rs1 = "SELECT * FROM `transactions` WHERE $shartt ORDER BY  `id` desc";
$rs1 = mysqli_query($cn,$query_rs1) or die(mysqli_error($cn));
$row_rs1 = mysqli_fetch_assoc($rs1);

if($_GET['id'] != ""){  
$query_Recordsetad1 = "SELECT * FROM `users` WHERE `id` = '".$row_rs1['user_id']."'";
$Recordad1 = mysqli_query($cn,$query_Recordsetad1) or die(mysqli_error($cn));
$row_Recordadd1 = mysqli_fetch_assoc($Recordad1);

?>

<!DOCTYPE html>
<html lang="fa" dir="rtl">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />

	<?php include('../../layouts/seo.php');?>
    
	<!-- Theme JS files -->
	<script type="text/javascript" src="../../assets/js/core/app.js"></script>
	<script type="text/javascript" src="../../assets/js/plugins/forms/styling/uniform.min.js"></script>

	<script type="text/javascript" src="../../assets/js/plugins/forms/selects/bootstrap_select.min.js"></script>
	<script type="text/javascript" src="../../assets/js/plugins/forms/selects/select2.min.js"></script>

	<script type="text/javascript" src="assets/js/users_page.js"></script>

    <title><?php echo $stitle; ?></title>
    
	<script src="../../includes/common/js/base.js" type="text/javascript"></script>
    <script src="../../includes/common/js/utility.js" type="text/javascript"></script>
    
    <script src="../../includes/nxt/scripts/list.js" type="text/javascript"></script>
    <script src="../../includes/nxt/scripts/list.js.php" type="text/javascript"></script>
    <script type="text/javascript">
		$NXT_LIST_SETTINGS = {
		  duplicate_buttons: false,
		  duplicate_navigation: false,
		  row_effects: false,
		  show_as_buttons: false,
		  record_counter: false
		}
    </script>
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
							<li><?php echo $cur_pagecat; ?></li>
							<li class="active"><?php echo $cur_pagename; ?></li>
						</ul>
					</div>
				</div>
				<!-- /page header -->


				<!-- Content area -->
				<div class="content">

			<!-- Main content -->
            
					<!-- 2 columns form -->
                    <?php if ( $row_rs1['status'] == 1 ) {
						$labell = 'ناموفق';
					}
					else {
						$labell = 'موفق';
					}?>
      
                       <div class="panel panel-flat">
                      	<div class="panel-body">
							<?php if($row_rs1['status'] != 2){?>
							<a href="payment_status.php?status=<?php if ($row_rs1['status'] == 1){ ?>0<?php } elseif($row_rs1['status'] == 0 ){?>1<?php }?>&id=<?php echo encrypt($row_rs1['id'],session_id()."paym"); ?>" class="label label-<?php if ($row_rs1['status'] == 1){ ?>danger<?php } else{?>success<?php }?>" onClick="return confirm('آیا مطمئنید می خواهید  تراکنش را <?=$labell;?> کنید ؟');"  >ثبت بعنوان <?= $labell;?></a>
							<a href="payment_status.php?status=2&id=<?php echo encrypt($row_rs1['id'],session_id()."paym"); ?>" class="label label-danger" onClick="return confirm('آیا مطمئنید می خواهید  تراکنش را لغو  کنید ؟');"  >لغو در خواست </a>
							<?php }elseif($row_rs1['status'] == 2){?>
							<a href="payment_status.php?status=0&id=<?php echo encrypt($row_rs1['id'],session_id()."paym"); ?>" class="label label-danger" onClick="return confirm('آیا مطمئنید می خواهید  تراکنش را ناموفق کنید ؟');"  >ثبت بعنوان ناموفق </a>
							<a href="payment_status.php?status=1&id=<?php echo encrypt($row_rs1['id'],session_id()."paym"); ?>" class="label label-success" onClick="return confirm('آیا مطمئنید می خواهید  تراکنش را موفق کنید ؟');"  >ثبت بعنوان موفق</a>
							<?php } ?>
									<p class="">
										تاریخ ثبت
										<label class="text-primary"> <?php $s= str_replace("-","/",$row_rs1['created_at']);?>
                                              <?php $s=miladi_to_jalali($row_rs1['created_at'],"-"," "); ?>
                                             <?php echo substr($s,8,2)." "; ?><?php echo monthname(substr($s,5,2)); ?>
                                            <?php echo substr($s,0,4)." "; ?>
                                            <?php echo "- ".substr($s,11,9); ?></label>
									<hr>
									<p>

									<h3>
										<i class="icon-chevron-left text-success"></i> اطلاعات کارت به کارت:
									</h3>
									<table class="table table-hover">
										<tbody>
											<tr>
												<td>مبلغ واریزی‌</td>
												<td><?php echo pricef($row_rs1['price']);?></td>
											</tr>
											<tr>
												<td>نام کاربر</td>
												<td>
													<?php 
      echo "<a href='../bets/bets_list.php?id=".encrypt($row_rs1['user_id'],session_id()."user")."'>".$row_Recordadd1['first_name'].' '.$row_Recordadd1['last_name'];
												  ?>
													</td>
											</tr>
											
											<tr>
												<td>شماره کارت</td>
												<td><?php echo $row_rs1['card_number'];?></td>
											</tr>
											<tr>
												<td>شناسه داخلی تراکنش</td>
												<td><?php echo $row_rs1['id'];?></td>
											</tr>
											<tr>
												<td>شناسه پرداخت</td>
												<td><?php echo $row_rs1['pay_code'];?></td>
											</tr>

										</tbody>
									</table>

									<hr>
								
					</div>
			    </div>
                      <?php } ?>
</div>
                        
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

