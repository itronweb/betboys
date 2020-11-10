<?php  
require_once('../../checklogin.php'); 

// Load the common classes
require_once('../../includes/common/KT_common.php');

// Load the required classes
require_once('../../includes/tfi/TFI.php');
require_once('../../includes/tso/TSO.php');
require_once('../../includes/nav/NAV.php');

// Make unified connection variable
$conn_cn = new KT_connection($cn, $database_cn);

// Filter
$tfi_listrsleagues = new TFI_TableFilter($conn_cn, "tfi_listrsleagues");
$tfi_listrsleagues->addColumn("id", "STRING_TYPE", "id", "%");
$tfi_listrsleagues->addColumn("leagues_id", "STRING_TYPE", "leagues_id", "%");
$tfi_listrsleagues->addColumn("leagues_name_en", "STRING_TYPE", "leagues_name_en", "%");
$tfi_listrsleagues->addColumn("leagues_name_fa", "STRING_TYPE", "leagues_name_fa", "%");
$tfi_listrsleagues->addColumn("country_name_en", "STRING_TYPE", "country_name_en", "%");
$tfi_listrsleagues->addColumn("country_name_fa", "STRING_TYPE", "country_name_fa", "%");
$tfi_listrsleagues->addColumn("sort", "NUMERIC_TYPE", "sort", "=");
$tfi_listrsleagues->Execute();

// Sorter
$tso_listrsleagues = new TSO_TableSorter("rs1", "tso_listrsleagues");
$tso_listrsleagues->addColumn("id");
$tso_listrsleagues->addColumn("leagues_id");
$tso_listrsleagues->addColumn("leagues_name_en");
$tso_listrsleagues->addColumn("leagues_name_fa");
$tso_listrsleagues->addColumn("country_name_en");
$tso_listrsleagues->addColumn("country_name_fa");
$tso_listrsleagues->addColumn("sort");
//$tso_listrsleagues->setDefault("id ASC");
$tso_listrsleagues->setDefault("-sort DESC");
$tso_listrsleagues->Execute();

// Change Show Per Page
if(isset($_POST['tfi_listrsleagues_pgnum']))
  @$_SESSION['tfi_listrsleagues_pgnum']=GetSQLValueString($_POST['tfi_listrsleagues_pgnum'], "int");
else if(KT_escapeAttribute(@$_SESSION['tfi_listrsleagues_pgnum'])=='')
  @$_SESSION['tfi_listrsleagues_pgnum']=10;

// Navigation
$nav_listrsleagues = new NAV_Regular("nav_listrsleagues", "rs1", "../", $_SERVER['PHP_SELF'], KT_escapeAttribute(@$_SESSION['tfi_listrsleagues_pgnum']));

//NeXTenesio3 Special List Recordset
$maxRows_rs1 = $_SESSION['max_rows_nav_listrsleagues'];
$pageNum_rs1 = 0;
if (isset($_GET['pageNum_rs1'])) {
  $pageNum_rs1 = $_GET['pageNum_rs1'];
}
$startRow_rs1 = $pageNum_rs1 * $maxRows_rs1;

// Defining List Recordset variable
$NXTFilter_rs1 = "1=1";
if (isset($_SESSION['filter_tfi_listrsleagues'])) {
  $NXTFilter_rs1 = $_SESSION['filter_tfi_listrsleagues'];
}
// Defining List Recordset variable
//$NXTSort_rs1 = "id DESC";
$NXTSort_rs1 = "-sort DESC";
if (isset($_SESSION['sorter_tso_listrsleagues'])) {
  $NXTSort_rs1 = $_SESSION['sorter_tso_listrsleagues'];
}
mysqli_select_db($cn,$database_cn);

$query_rs1 = "SELECT * FROM `leagues` WHERE  {$NXTFilter_rs1}  ORDER BY  {$NXTSort_rs1} ";
$query_limit_rs1 = sprintf("%s LIMIT %d, %d", $query_rs1, $startRow_rs1, $maxRows_rs1);
$rs1 = mysqli_query($cn,$query_limit_rs1) or die(mysqli_error($cn));
$row_rs1 = mysqli_fetch_assoc($rs1);

if (isset($_GET['totalRows_rs1'])) {
  $totalRows_rs1 = $_GET['totalRows_rs1'];
} else {
  $all_rs1 = mysqli_query($cn,$query_rs1);
  $totalRows_rs1 = mysqli_num_rows($all_rs1);
}
$totalPages_rs1 = ceil($totalRows_rs1/$maxRows_rs1)-1;
//End NeXTenesio3 Special List Recordset

$nav_listrsleagues->checkBoundries();
$queryString_rs1 = sprintf("&totalRows_rs1=%d%s", $totalRows_rs1, $queryString_rs1);
if(isset($_GET['pageNum_rs1']) and $_GET['pageNum_rs1']!="")
	$radif=$_GET['pageNum_rs1']*$maxRows_rs1+1;
else
	$radif=1;


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

	<script type="text/javascript" src="assets/js/leaguesin_page.js"></script>

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
							<li><a href="<?php echo $cur_pageurl; ?>"><?php echo $cur_pagecat; ?></a></li>
							<li class="active"><?php echo $cur_pagename; ?></li>
						</ul>
					</div>
				</div>
				<!-- /page header -->


				<!-- Content area -->
				<div class="content">

			<!-- Main content -->
            
					<!-- 2 columns form -->
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
					
                    			<legend>
                                  <div class="btn-group btn-group-justified">
									 
                                      <a href="<?php echo $nav_listrsleagues->getShowAllLink(); ?>" class="btn bg-slate-700"><i class="icon-stack2"></i> <?php echo NXT_getResource("Show"); ?>
                                      <?php 
                                      if (@$_GET['show_all_nav_listrsleagues'] == 1) 
                                          echo $_SESSION['default_max_rows_nav_listrsleagues']; 
                                      else  
                                          echo NXT_getResource("all");  
                                         
                                      echo ' '.NXT_getResource("records"); ?></a>
                                      
                                      <!--[if !IE]>start  tabs<![endif]-->
                        
                                      <?php 
                                      if (@$_SESSION['has_filter_tfi_listrsleagues'] == 1) {
                                      ?>
                                      <a href="<?php echo $tfi_listrsleagues->getResetFilterLink(); ?>" class="btn bg-slate-700"><i class="icon-search4"></i> <?php echo NXT_getResource("Reset filter"); ?></a>
                                      <?php 
                                      } else { ?>
                                          <a href="<?php echo $tfi_listrsleagues->getShowFilterLink(); ?>" class="btn bg-slate-700"><i class="icon-search4"></i> <?php echo NXT_getResource("Show filter"); ?></a>
                                <?php } ?>



                                      <div class="btn bg-slate-700">                                            
                                            <div class="form-group">
												<label class="col-lg-2 control-label text-center" style="padding-top:8px">تعداد نمایش</label>
												<div class="col-lg-10">
                                    <form name="formpgnum" id="formpgnum" action="<?php $_SERVER['PHP_SELF'];?>" method="post">
                                      <select style="float:left;width:50%;" name="tfi_listrsleagues_pgnum" id="tfi_listrsleagues_pgnum" class="form-control" onchange="submit();" tabindex="1">
                                      	<option value="10" <?php if (!(strcmp('10', KT_escapeAttribute(@$_SESSION['tfi_listrsleagues_pgnum'])))) {echo "selected=\"selected\"";} ?>>10</option>
                                      	<option value="15" <?php if (!(strcmp('15', KT_escapeAttribute(@$_SESSION['tfi_listrsleagues_pgnum'])))) {echo "selected=\"selected\"";} ?>>15</option>
                                      	<option value="20" <?php if (!(strcmp('20', KT_escapeAttribute(@$_SESSION['tfi_listrsleagues_pgnum'])))) {echo "selected=\"selected\"";} ?>>20</option>
                                      	<option value="30" <?php if (!(strcmp('30', KT_escapeAttribute(@$_SESSION['tfi_listrsleagues_pgnum'])))) {echo "selected=\"selected\"";} ?>>30</option>
                                      	<option value="50" <?php if (!(strcmp('50', KT_escapeAttribute(@$_SESSION['tfi_listrsleagues_pgnum'])))) {echo "selected=\"selected\"";} ?>>50</option>
                                      	<option value="100" <?php if (!(strcmp('100', KT_escapeAttribute(@$_SESSION['tfi_listrsleagues_pgnum'])))) {echo "selected=\"selected\"";} ?>>100</option>
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
										<th id="sort"><a href="<?php echo $tso_listrsleagues->getSortLink('sort'); ?>">شماره</a></th>
										<th id="leagues_id"><a href="<?php echo $tso_listrsleagues->getSortLink('leagues_id'); ?>">کد لیگ</a></th>
										<th id="leagues_name_en"><a href="<?php echo $tso_listrsleagues->getSortLink('leagues_name_en'); ?>">نام انگلیسی لیگ</a></th>
										<th id="leagues_name_fa"><a href="<?php echo $tso_listrsleagues->getSortLink('leagues_name_fa'); ?>">نام فارسی لیگ</a></th>
										<th id="country_name_en"><a href="<?php echo $tso_listrsleagues->getSortLink('country_name_en'); ?>">نام انگلیسی کشور</a></th>
										<th id="country_name_fa"><a href="<?php echo $tso_listrsleagues->getSortLink('country_name_fa'); ?>">نام فارسی کشور</a></th>
<!--										<th id="sort"><a href="<?php echo $tso_listrsleagues->getSortLink('sort'); ?>">مرتب سازی </a></th>-->
										
										<?php if ($totalRows_rs1>0 || @$_SESSION['has_filter_tfi_listrsleagues'] == 1) { ?>
                                        <th></th>
                                        <?php }?>
									</tr>
                                        <?php 
  // Show IF Conditional region3
  if (@$_SESSION['has_filter_tfi_listrsleagues'] == 1) {
?>
                                          <tr>
                                            <td><input type="text" name="tfi_listrsleagues_sort" id="tfi_listrsleagues_sort" class="form-control number" value="<?php echo KT_escapeAttribute(@$_SESSION['tfi_listrsleagues_sort']); ?>" maxlength="20" tabindex="1" size="12" /></td>
											  
                                            <td><input type="text" name="tfi_listrsleagues_leagues_id" id="tfi_listrsleagues_leagues_id" class="form-control number" value="<?php echo KT_escapeAttribute(@$_SESSION['tfi_listrsleagues_leagues_id']); ?>" maxlength="20" tabindex="1" size="12" /></td>
											  
                                            <td><input type="text" name="tfi_listrsleagues_leagues_name_en" id="tfi_listrsleagues_leagues_name_en" class="form-control text-right flt-left " value="<?php echo KT_escapeAttribute(@$_SESSION['tfi_listrsleagues_leagues_name_en']); ?>" maxlength="20" tabindex="2" size="12" /></td>
                                            <td><input type="text" name="tfi_listrsleagues_leagues_name_fa" id="tfi_listrsleagues_leagues_name_fa" class="form-control" value="<?php echo KT_escapeAttribute(@$_SESSION['tfi_listrsleagues_leagues_name_fa']); ?>" maxlength="20" tabindex="2" size="12" /></td>
                                            <td><input type="text" name="tfi_listrsleagues_country_name_en" id="tfi_listrsleagues_country_name_en" class="form-control text-right flt-left" value="<?php echo KT_escapeAttribute(@$_SESSION['tfi_listrsleagues_country_name_en']); ?>" maxlength="20" tabindex="2" size="12" /></td>
                                            <td><input type="text" name="tfi_listrsleagues_country_name_fa" id="tfi_listrsleagues_country_name_fa" class="form-control" value="<?php echo KT_escapeAttribute(@$_SESSION['tfi_listrsleagues_country_name_fa']); ?>" maxlength="20" tabindex="2" size="12" /></td>
											  
                                            <td><input type="submit" name="tfi_listrsleagues" class="btn btn-primary btn-xs" value="<?php echo NXT_getResource("Filter"); ?>" tabindex="6" /></td>
										    
                                          </tr>
                                          <?php } 
  // endif Conditional region3
?>
                                      </thead>
                                      <tbody>
                                        <?php if ($totalRows_rs1 == 0) { // Show if recordset empty ?>
                                           <tr>
                                           <td colspan="<?php if (@$_SESSION['has_filter_tfi_listrsleagues'] == 1) echo '7'; else echo '6'; ?>">اطلاعاتی یافت نشد .</td>
                                          </tr>
                                          <?php } // Show if recordset empty ?>
                                        <?php if ($totalRows_rs1 > 0) { // Show if recordset not empty ?>
                                          <?php do { ?>
                                            <tr>
                                              <td><?php echo $row_rs1['sort']; ?></td>
                                              <td><?php echo $row_rs1['leagues_id']; ?></td>
                                              <td><?php echo $row_rs1['leagues_name_en']; ?></td>
                                              <td><?php echo $row_rs1['leagues_name_fa']; ?></td>
                                              <td><?php echo $row_rs1['country_name_en']; ?></td>
                                              <td><?php echo $row_rs1['country_name_fa']; ?></td>
                                             
                                              <td>
                                                <ul class="icons-list">
													<?php if (in_array('leagues_edit', $accccesslist)) {?>
                                                    <li class="text-primary-600"><a href="leagues_edit.php?id=<?php echo encrypt($row_rs1['id'],session_id()."leag"); ?>" title="ویرایش" data-popup="tooltip"><i class="icon-pencil7"></i></a></li>
                                                    <?php }?>
                                                </ul>
                                            </td>
                                            </tr>
                                            <?php } while ($row_rs1 = mysqli_fetch_assoc($rs1)); ?>
                                          <?php } // Show if recordset not empty ?>
                                      </tbody>
                                    </table>
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

