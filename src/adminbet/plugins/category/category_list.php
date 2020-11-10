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
$tfi_listrscat = new TFI_TableFilter($conn_cn, "tfi_listrscat");
$tfi_listrscat->addColumn("code", "STRING_TYPE", "code", "%");
$tfi_listrscat->addColumn("name", "STRING_TYPE", "name", "%");
$tfi_listrscat->addColumn("level", "STRING_TYPE", "level", "%");
$tfi_listrscat->addColumn("contenttype", "STRING_TYPE", "contenttype", "%");
$tfi_listrscat->addColumn("portalid", "NUMERIC_TYPE", "portalid", "=");
$tfi_listrscat->addColumn("status", "NUMERIC_TYPE", "status", "=");
$tfi_listrscat->Execute();

// Sorter
$tso_listrscat = new TSO_TableSorter("rs1", "tso_listrscat");
$tso_listrscat->addColumn("code");
$tso_listrscat->addColumn("name");
$tso_listrscat->addColumn("level");
$tso_listrscat->addColumn("contenttype");
$tso_listrscat->addColumn("portalid");
$tso_listrscat->addColumn("status");
$tso_listrscat->setDefault("code DESC");
$tso_listrscat->Execute();

// Change Show Per Page
if(isset($_POST['tfi_listrscat_pgnum']))
  @$_SESSION['tfi_listrscat_pgnum']=GetSQLValueString($_POST['tfi_listrscat_pgnum'], "int");
else if(KT_escapeAttribute(@$_SESSION['tfi_listrscat_pgnum'])=='')
  @$_SESSION['tfi_listrscat_pgnum']=10;

// Navigation
$nav_listrscat = new NAV_Regular("nav_listrscat", "rs1", "../", $_SERVER['PHP_SELF'], KT_escapeAttribute(@$_SESSION['tfi_listrscat_pgnum']));

//NeXTenesio3 Special List Recordset
$maxRows_rs1 = $_SESSION['max_rows_nav_listrscat'];
$pageNum_rs1 = 0;
if (isset($_GET['pageNum_rs1'])) {
  $pageNum_rs1 = $_GET['pageNum_rs1'];
}
$startRow_rs1 = $pageNum_rs1 * $maxRows_rs1;

// Defining List Recordset variable
$NXTFilter_rs1 = "1=1";
if (isset($_SESSION['filter_tfi_listrscat'])) {
  $NXTFilter_rs1 = $_SESSION['filter_tfi_listrscat'];
}
// Defining List Recordset variable
$NXTSort_rs1 = "code DESC";
if (isset($_SESSION['sorter_tso_listrscat'])) {
  $NXTSort_rs1 = $_SESSION['sorter_tso_listrscat'];
}
mysqli_select_db($cn,$database_cn);

$query_rs1 = "SELECT * FROM category WHERE  {$NXTFilter_rs1}  ORDER BY  {$NXTSort_rs1} ";
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

$nav_listrscat->checkBoundries();
$queryString_rs1 = sprintf("&totalRows_rs1=%d%s", $totalRows_rs1, $queryString_rs1);
if(isset($_GET['pageNum_rs1']) and $_GET['pageNum_rs1']!="")
	$radif=$_GET['pageNum_rs1']*$maxRows_rs1+1;
else
	$radif=1;

mysqli_select_db($cn,$database_cn);
$query_Recordsetlvl = "SELECT code, name FROM category ORDER BY code DESC";
$Recordsetlvl = mysqli_query($cn,$query_Recordsetlvl) or die(mysqli_error($cn));
$row_Recordsetlvl = mysqli_fetch_assoc($Recordsetlvl);
$totalRows_Recordsetlvl = mysqli_num_rows($Recordsetlvl);
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

	<script type="text/javascript" src="assets/js/category_page.js"></script>

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
									  <a href="category_add.php" class="btn bg-slate-700"><i class="icon-pencil3"></i> ثبت رکورد جدید</a>
                                      <a href="<?php echo $nav_listrscat->getShowAllLink(); ?>" class="btn bg-slate-700"><i class="icon-stack2"></i> <?php echo NXT_getResource("Show"); ?>
                                      <?php 
                                      if (@$_GET['show_all_nav_listrscat'] == 1) 
                                          echo $_SESSION['default_max_rows_nav_listrscat']; 
                                      else  
                                          echo NXT_getResource("all");  
                                         
                                      echo ' '.NXT_getResource("records"); ?></a>
                                      
                                      <!--[if !IE]>start  tabs<![endif]-->
                        
                                      <?php 
                                      if (@$_SESSION['has_filter_tfi_listrscat'] == 1) {
                                      ?>
                                      <a href="<?php echo $tfi_listrscat->getResetFilterLink(); ?>" class="btn bg-slate-700"><i class="icon-search4"></i> <?php echo NXT_getResource("Reset filter"); ?></a>
                                      <?php 
                                      } else { ?>
                                          <a href="<?php echo $tfi_listrscat->getShowFilterLink(); ?>" class="btn bg-slate-700"><i class="icon-search4"></i> <?php echo NXT_getResource("Show filter"); ?></a>
                                <?php } ?>



                                      <div class="btn bg-slate-700">                                            
                                            <div class="form-group">
												<label class="col-lg-2 control-label text-center" style="padding-top:8px">تعداد نمایش</label>
												<div class="col-lg-10">
                                    <form name="formpgnum" id="formpgnum" action="<?php $_SERVER['PHP_SELF'];?>" method="post">
                                      <select style="float:left;width:50%;" name="tfi_listrscat_pgnum" id="tfi_listrscat_pgnum" class="form-control" onchange="submit();" tabindex="1">
                                      	<option value="10" <?php if (!(strcmp('10', KT_escapeAttribute(@$_SESSION['tfi_listrscat_pgnum'])))) {echo "selected=\"selected\"";} ?>>10</option>
                                      	<option value="15" <?php if (!(strcmp('15', KT_escapeAttribute(@$_SESSION['tfi_listrscat_pgnum'])))) {echo "selected=\"selected\"";} ?>>15</option>
                                      	<option value="20" <?php if (!(strcmp('20', KT_escapeAttribute(@$_SESSION['tfi_listrscat_pgnum'])))) {echo "selected=\"selected\"";} ?>>20</option>
                                      	<option value="30" <?php if (!(strcmp('30', KT_escapeAttribute(@$_SESSION['tfi_listrscat_pgnum'])))) {echo "selected=\"selected\"";} ?>>30</option>
                                      	<option value="50" <?php if (!(strcmp('50', KT_escapeAttribute(@$_SESSION['tfi_listrscat_pgnum'])))) {echo "selected=\"selected\"";} ?>>50</option>
                                      	<option value="100" <?php if (!(strcmp('100', KT_escapeAttribute(@$_SESSION['tfi_listrscat_pgnum'])))) {echo "selected=\"selected\"";} ?>>100</option>
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
										<th id="code"><a href="<?php echo $tso_listrscat->getSortLink('code'); ?>">کد</a></th>
										<th id="name"><a href="<?php echo $tso_listrscat->getSortLink('name'); ?>">عنوان</a></th>
										<th id="level"><a href="<?php echo $tso_listrscat->getSortLink('level'); ?>">سطح</a></th>
										<th id="contenttype"><a href="<?php echo $tso_listrscat->getSortLink('contenttype'); ?>">نوع مطلب</a></th>
										<th id="portalid"><a href="<?php echo $tso_listrscat->getSortLink('portalid'); ?>">نوع پورتال</a></th>
										<th id="status"><a href="<?php echo $tso_listrscat->getSortLink('status'); ?>">وضعیت</a></th>
										<?php if ($totalRows_rs1>0 || @$_SESSION['has_filter_tfi_listrscat'] == 1) { ?>
                                        <th></th>
                                        <?php }?>
									</tr>
                                        <?php 
  // Show IF Conditional region3
  if (@$_SESSION['has_filter_tfi_listrscat'] == 1) {
?>
                                          <tr>
                                            <td><input type="text" name="tfi_listrscat_code" id="tfi_listrscat_code" class="form-control" value="<?php echo KT_escapeAttribute(@$_SESSION['tfi_listrscat_code']); ?>" maxlength="20" autocomplete="off" tabindex="2" /></td>
                                            <td><input type="text" name="tfi_listrscat_name" id="tfi_listrscat_name" class="form-control" value="<?php echo KT_escapeAttribute(@$_SESSION['tfi_listrscat_name']); ?>" maxlength="20" autocomplete="off" tabindex="3" /></td>
                                            <td>
<select name="tfi_listrscat_level" id="tfi_listrscat_level" class="form-control" tabindex="4">
                                <option value=""></option>
                                  <?php
								  if($totalRows_Recordsetlvl>0){
if(true)
{?>
  <option value="0" <?php if (!(strcmp('0', KT_escapeAttribute(@$_SESSION['tfi_listrscat_level'])))) {echo "selected=\"selected\"";} ?>>گروه اصلی</option>
<?php }
do { 
$categorytree = "";	getCategoryTree($cn,'category',$row_Recordsetlvl['code']); 
?>
                                  <option value="<?php echo $row_Recordsetlvl['code']?>" <?php if (!(strcmp($row_Recordsetlvl['code'], KT_escapeAttribute(@$_SESSION['tfi_listrscat_level'])))) {echo "selected=\"selected\"";} ?>><?php echo $categorytree;?></option>
                                  <?php
} while ($row_Recordsetlvl = mysqli_fetch_assoc($Recordsetlvl));
  $rows = mysqli_num_rows($Recordsetlvl);
  if($rows > 0) {
      mysqli_data_seek($Recordsetlvl, 0);
	  $row_Recordsetlvl = mysqli_fetch_assoc($Recordsetlvl);
  }
  
  }
?>                                    
                                  </select>                                            </td>
                                            <td>
<?php 
mysqli_select_db($cn,$database_cn);
$query_rstfinamecontent = "SELECT id,name FROM contenttype";
$rstfinamecontent = mysqli_query($cn,$query_rstfinamecontent) or die(mysqli_error($cn));
$row_rstfinamecontent = mysqli_fetch_assoc($rstfinamecontent);
?>											  
<select name="tfi_listrscat_contenttype" id="tfi_listrscat_contenttype" class="form-control" tabindex="5">
            <option value="" selected="selected"></option>
              <?php
do {  
?>
              <option value="<?php echo $row_rstfinamecontent['id']?>" <?php if (!(strcmp($row_rstfinamecontent['id'], KT_escapeAttribute(@$_SESSION['tfi_listrscat_contenttype'])))) {echo "selected=\"selected\"";} ?>><?php echo $row_rstfinamecontent['name']?></option>
              <?php
} while ($row_rstfinamecontent = mysqli_fetch_assoc($rstfinamecontent));
  $rows = mysql_num_rows($rstfinamecontent);
  if($rows > 0) {
      mysqli_data_seek($rstfinamecontent, 0);
	  $row_rstfinamecontent = mysqli_fetch_assoc($rstfinamecontent);
  }
?>
									                            </select>
                                            </td>
                                            <td>
<select name="tfi_listrscat_portalid" id="tfi_listrscat_portalid" class="form-control" tabindex="6">
            <option value="" selected="selected"></option>
              <?php
mysqli_select_db($cn,$database_cn);
$query_rstfinameportal = "SELECT id,name FROM setting";
$rstfinameportal = mysqli_query($cn,$query_rstfinameportal) or die(mysqli_error($cn));
$row_rstfinameportal = mysqli_fetch_assoc($rstfinameportal);
											  
			  
do {  
?>
              <option value="<?php echo $row_rstfinameportal['id']?>" <?php if (!(strcmp($row_rstfinameportal['id'], KT_escapeAttribute(@$_SESSION['tfi_listrscat_portalid'])))) {echo "selected=\"selected\"";} ?>><?php echo $row_rstfinameportal['name']?></option>
              <?php
} while ($row_rstfinameportal = mysqli_fetch_assoc($rstfinameportal));
  $rows = mysql_num_rows($rstfinameportal);
  if($rows > 0) {
      mysqli_data_seek($rstfinameportal, 0);
	  $row_rstfinameportal = mysqli_fetch_assoc($rstfinameportal);
  }
?>
									                            </select>                                            </td>
                                            <td><select name="tfi_listrscat_status" id="tfi_listrscat_status" class="form-control" tabindex="7">
                                              <option value="" <?php if (!(strcmp("", KT_escapeAttribute(@$_SESSION['tfi_listrscat_status'])))) {echo "selected=\"selected\"";} ?>></option>
                                              <option value="1" <?php if (!(strcmp(1, KT_escapeAttribute(@$_SESSION['tfi_listrscat_status'])))) {echo "selected=\"selected\"";} ?>>فعال</option>
                                              <option value="0" <?php if (!(strcmp(0, KT_escapeAttribute(@$_SESSION['tfi_listrscat_status'])))) {echo "selected=\"selected\"";} ?>>غیرفعال</option>
                                              </select>                                            </td>
                                            <td><input type="submit" name="tfi_listrscat" class="btn btn-primary btn-xs" value="<?php echo NXT_getResource("Filter"); ?>" tabindex="8" /></td>
                                          </tr>
                                          <?php } 
  // endif Conditional region3
?>
                                      </thead>
                                      <tbody>
                                        <?php if ($totalRows_rs1 == 0) { // Show if recordset empty ?>
                                          <tr>
                                            <td colspan="<?php if (@$_SESSION['has_filter_tfi_listrscat'] == 1) echo '7'; else echo '6'; ?>">اطلاعاتی یافت نشد.</td>
                                          </tr>
                                          <?php } // Show if recordset empty ?>
                                        <?php if ($totalRows_rs1 > 0) { // Show if recordset not empty ?>
                                          <?php do { ?>
                                            <tr>
                                              <td><?php echo KT_FormatForList($row_rs1['code'], 20); ?></td>
                                              <td><?php echo $row_rs1['name']; ?></td>
                                              <td>
<?php 
$b=$row_rs1['level']; 

if($b=='0')
	echo 'گروه اصلی';
else
{   
	$categorytree = "";	
	getCategoryTree($cn,'category',$b);
	echo $categorytree;
}
?>											  
                                              </td>
                                              <td>
<?php 
$b=$row_rs1['contenttype']; 
mysqli_select_db($cn,$database_cn);
$rsnamecontent = mysqli_query($cn,"SELECT name FROM contenttype where id = '$b'") or die(mysqli_error($cn));
$row_rsnamecontent = mysqli_fetch_assoc($rsnamecontent);
echo $row_rsnamecontent['name']; 
?>											  
                                              </td>
                                              <td>
<?php 
$b=$row_rs1['portalid']; 
mysqli_select_db($cn,$database_cn);
$rsnameportal = mysqli_query($cn,"SELECT name FROM setting where id = '$b'") or die(mysqli_error($cn));
$row_rsnameportal = mysqli_fetch_assoc($rsnameportal);
echo $row_rsnameportal['name']; 
?>											  
                                              </td>
                                              <td>
                                              <a href="../../change_status.php?table=<?php echo encrypt('category',session_id()."sts"); ?>&field=<?php echo encrypt('status',session_id()."sts"); ?>&id=<?php echo encrypt($row_rs1['id'],session_id()."sts"); ?>" data-popup="tooltip" title="تغییر وضعیت"><?php checkstatus($row_rs1['status']); ?></a>
                                              </td>
                                              <td>
                                                <ul class="icons-list">
													<?php if (in_array('category_edit', $accccesslist)) {?>
                                                    <li class="text-primary-600"><a href="category_edit.php?id=<?php echo encrypt($row_rs1['id'],session_id()."cat"); ?>" data-popup="tooltip" title="ویرایش"><i class="icon-pencil7"></i></a></li>
                                                    <?php }?>
                                                    
                                                    <?php if($row_rs1['type']==1 && in_array('category_delete', $accccesslist)){?><li class="text-danger-600"><a href="category_delete.php?id=<?php echo encrypt($row_rs1['id'],session_id()."cat"); ?>" onClick="return confirm('آیا مطمئنید می خواهید اطلاعات را حذف کنید ؟');" data-popup="tooltip" title="حذف"><i class="icon-trash"></i></a></li>
                                                    <?php }?>
    
                                                    <li class="text-teal-600"><a href="../../../contents.php?cat=<?php echo $row_rs1['code']; ?>" target="_blank" data-popup="tooltip" title="نمایش"><i class="icon-search4"></i></a></li>
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

