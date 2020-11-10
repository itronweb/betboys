

<?php  
require_once('../../checklogin.php'); 

// Load the common classes
require_once('../../includes/common/KT_common.php');

// Load the tNG classes
require_once('../../includes/tng/tNG.inc.php');

// Load the required classes
require_once('../../includes/tfi/TFI.php');
require_once('../../includes/tso/TSO.php');
require_once('../../includes/nav/NAV.php');



$tNGs = new tNG_dispatcher("");

// Make unified connection variable
$conn_cn = new KT_connection($cn, $database_cn);


// Start trigger
$formValidation = new tNG_FormValidation();
$formValidation->addField("game_name", true, "text", "", "", "", "لطفا نام ورزش را انتخاب نمایید.");
$tNGs->prepareValidation($formValidation);
// End trigger


// Make an insert transaction instance
$ins_teamgame = new tNG_insert($conn_cn);
$tNGs->addTransaction($ins_teamgame);
// Register triggers
$ins_teamgame->registerTrigger("STARTER", "Trigger_Default_Starter", 1, "POST", "KT_Insert1");
$ins_teamgame->registerTrigger("BEFORE", "Trigger_Default_FormValidation", 10, $formValidation);
$ins_teamgame->registerTrigger("END", "Trigger_Default_Redirect", 99,  $_POST['referurl']);


mysqli_select_db($cn, $database_cn);
$query_rsgamename = "SELECT * FROM sports where status='1' ORDER BY name";
$rsgamename = mysqli_query($cn, $query_rsgamename) or die("Invalid Parametr!");
$row_rsgamename = mysqli_fetch_assoc($rsgamename);
$totalRows_rsgamename = mysqli_num_rows($rsgamename);




if(isset($_POST['game_name'])){
	$game_name = $_SESSION['teamgame_name'] = $_POST['game_name'];
}elseif(isset($_GET['game_name'])){
	$game_name = $_SESSION['teamgame_name'] = $_GET['game_name'];
}elseif(isset($_SESSION['teamgame_name'])){
	$game_name = $_SESSION['teamgame_name'] ;
}else{
	$game_name = 'soccer';
}


$url = '../../../upload/API/'.$game_name.'/team_name.json';
$jsonResult = file_get_contents('../../../upload/API/'.$game_name.'/team_name.json');
$content_json = json_decode($jsonResult);



	
///---------------------------------list 

$size = count((array)$content_json);
//$maxRows_rsservice = $_SESSION['max_rows_nav_listrsteamgame'];

if(isset($_SESSION['max_rows_nav_listrsteamgame']) and $_SESSION['max_rows_nav_listrsteamgame'] != null){
	$maxRows_rsservice = $_SESSION['max_rows_nav_listrsteamgame'];
}else{
	$maxRows_rsservice = $_SESSION['max_rows_nav_listrsteamgame']=10;
}


if(isset($_POST['numshow'])){
	$maxRows_rsservice = $_SESSION['max_rows_nav_listrsteamgame'] = $_POST['numshow'];
}
$pageNum_rsservice = 0;
if (isset($_GET['pageNum_rsservice'])) {
$pageNum_rsservice = $_GET['pageNum_rsservice'];
}
$startRow_rsservice = $pageNum_rsservice * $maxRows_rsservice;
$endRow_rsservice = $startRow_rsservice+$maxRows_rsservice;
if (isset($_GET['totalRows_rsservice'])) {
$totalRows_rsservice = $_GET['totalRows_rsservice'];
} else {
  $totalRows_rsservice = $size;
}
$totalPages_rsservice = ceil($totalRows_rsservice/$maxRows_rsservice)-1;
$queryString_rsservice = "";
if (!empty($_SERVER['QUERY_STRING'])) {
  $params = explode("&", $_SERVER['QUERY_STRING']);
  $newParams = array();
  foreach ($params as $param) {
    if (stristr($param, "pageNum_rsservice") == false && 
        stristr($param, "totalRows_rsservice") == false && 
        stristr($param, "sort") == false) {
      array_push($newParams, $param);
    }
  }
  if (count($newParams) != 0) {
    $queryString_rsservice = "&" . htmlentities(implode("&", $newParams));
  }
}
$queryString_rsservice = sprintf("&totalRows_rsservice=%d%s", $totalRows_rsservice, $queryString_rsservice);
global $id;
global $name_en_search;
global $name_fa_search;
$id=$_POST['numsearch'];
$name_en_search=$_POST['name_en_search'];
$name_fa_search=$_POST['name_fa_search'];


//-----------------edit 


	if(isset($_POST['key']) && isset($_POST['name_en']) ){
		$post_key = $_POST['key'];
		$data = $content_json ;
		if (isset($data->{$post_key})){
				
			$data->{$post_key}->name_en = $_POST['name_en'];
			$data->{$post_key}->name_fa = $_POST['name_fa'];

			$newJsonString = json_encode($data);
			file_put_contents($url, $newJsonString);
//			$content_json = $data ;
		}
	}




?>

<!DOCTYPE html>
<html lang="fa" dir="rtl">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />

	<?php include('../../layouts/seo.php');?>
    
	<!-- Theme JS files -->
	<script type="text/javascript" src="../../assets/js/core/app.js"></script>
	<script type="text/javascript" src="../../assets/js/plugins/forms/styling/uniform.min.js"></script>

<!--	<script type="text/javascript" src="../../assets/js/plugins/forms/selects/bootstrap_select.min.js"></script>-->
	<script type="text/javascript" src="../../assets/js/plugins/forms/selects/select2.min.js"></script>

	<script type="text/javascript" src="../../assets/js/plugins/forms/inputs/touchspin.min.js"></script>

	<script type="text/javascript" src="assets/js/teamgame_page.js"></script>

    <title><?php echo $stitle; ?></title>
    
	<script src="../../includes/common/js/base.js" type="text/javascript"></script>
    <script src="../../includes/common/js/utility.js" type="text/javascript"></script>
    
    <script src="../../includes/nxt/scripts/list.js" type="text/javascript"></script>
    <script src="../../includes/nxt/scripts/list.js.php" type="text/javascript"></script>
    
	
	<style>
		.form-control:focus {
			color: #495057;
			background-color: #fff;
			border: 1px solid #a2a2a2;
			outline: 0;
			box-shadow: 0 0 0 .2rem rgba(135, 135, 135, 0.3);
		}	
		.table-responsive .form-control{
			border: none;
		}

	</style>
	
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
            
 <?php
	echo $tNGs->getErrorMsg();
					
?>
					<!-- 2 columns form -->
                    <form id="form01" class="form-horizontal" method="post" action="teamgame_list.php" enctype="multipart/form-data">
                    
						<div class="panel panel-flat">
							<div class="panel-heading">
								<h5 class="panel-title"><?php echo "انتخاب ورزش"; ?></h5>
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

									<div class="col-md-6">
										<fieldset>
					
                                        	<div class="form-group">
                                                <label class="col-lg-3 control-label" for="game_name">نام ورزش :</label>
                                                <div class="col-lg-9">
                                                    <select name="game_name" id="game_name"  class="select-search form-control" tabindex="3">
        <option value=""></option>
          <?php
if($totalRows_rsgamename>0) {			  
    do {  
    ?>
          <option value="<?php echo $row_rsgamename['name_en']?>"><?php echo $row_rsgamename['name']?></option>
          <?php
    } while ($row_rsgamename = mysqli_fetch_assoc($rsgamename));
    $rows = mysql_num_rows($rsgamename);
    if($rows > 0) {
    mysqli_data_seek($rsgamename, 0);
    $row_rsgamename = mysqli_fetch_assoc($rsgamename);
    }
}
    ?>
                                                    </select>
                                                </div>
                                            </div>                                        

										</fieldset>
									</div>
									
										

							</div>
								<div class="text-right">
                                    <button id="KT_Insert1" name="KT_Insert1" type="submit" class="btn btn-primary" tabindex="7">ثبت اطلاعات <i class="icon-arrow-left13 position-right"></i></button>
                                    <button id="reset" name="reset" type="reset" class="btn btn-warning" tabindex="8">بازنویسی <i class="icon-reset position-right"></i></button>
								</div>
						</div>
                        </div>
					</form>
                        
                    </div>
					<!-- /2 columns form -->
	
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

                    			
    
 <form action="<?php echo KT_escapeAttribute(KT_getFullUri()); ?>" method="post" id="form0">

	  <div  class="col-md-12" style="margin: 10px;">
		
		  
		  
		<div class="col-md-6">
			<fieldset>
				<div class="form-group  col-xs-12">
					<label class="col-lg-3 control-label" for="numsearch" style="margin-top: 0.5em;">شناسه تیم:</label>	
					<div class=" col-lg-9">
						<input type="text" class="form-control" name="numsearch" value = "<?php if(isset($_POST['numsearch']) && $_POST['numsearch'] != ""){echo($_POST['numsearch']);} ?>">
					</div>
					
				</div>
			</fieldset>
		</div>
		<div class="col-md-6">
			<fieldset>
				<div class="form-group  col-xs-12">
					<label class="col-lg-3 control-label" for="name_en_search" style="margin-top: 0.5em;">نام لاتین:</label>
					<div class=" col-lg-9">
						<input type="text" class="form-control" name="name_en_search" value = "<?php if(isset($_POST['name_en_search']) && $_POST['name_en_search'] != ""){echo($_POST['name_en_search']);} ?>">
					</div>
					
				</div>
			</fieldset>
		</div>
		<div class="col-md-6">
			<fieldset>
				<div class="form-group  col-xs-12">
					<label class="col-lg-3 control-label" for="name_fa_search" style="margin-top: 0.5em;">نام فارسی:</label>
					<div class=" col-lg-9">
						<input type="text" class="form-control" name="name_fa_search" value = "<?php if(isset($_POST['name_fa_search']) && $_POST['name_fa_search'] != ""){echo($_POST['name_fa_search']);} ?>" >
					</div>
					
				</div>
			</fieldset>
			
			<input type="hidden" class="form-control " name="game_name" value="<?= $game_name ?>" >
			
		</div>
		  
		<div class="col-md-6">
			<fieldset>
				<div class="form-group  col-xs-12">
					<label class="col-lg-3 control-label" for="numshow" style="margin-top: 0.5em;">تعداد نمایش :</label>
					<div class=" col-lg-9">
						
						  <select style="" name="numshow"  class="form-control"  tabindex="4">
							  <?php
	if(isset($maxRows_rsservice) and $maxRows_rsservice != ""){ ?>
							  	<option value="<?Php echo $maxRows_rsservice;?>" selected="selected" ><?Php echo $maxRows_rsservice;?></option>
							  <?php }else{ ?>
							  <option value="" selected="true" disabled="disabled">انتخاب کنید</option>
							  <?php }?>
							<option value="10" >10</option>
							<option value="15" >15</option>
							<option value="20" >20</option>
							<option value="30" >30</option>
							<option value="50" >50</option>
							<option value="100" >100</option>
						  </select>

					</div>
					
				</div>
			</fieldset>
			
			<input type="hidden" class="form-control " name="game_name" value="<?= $game_name ?>" >
			
		</div>
		
		<div class="col-md-12">
			<fieldset >
				<div class="form-group">
		 			<input type="submit" name="searchbu" class="btn btn-primary btn-xs " style = "float: left;margin-top: 1em;" value="جستجو">
		 		</div>
			</fieldset>
	 	</div>
		</br>
	 </div>
	 

	<div class="table-responsive col-md-12" style="margin-bottom: 2em;">


		<table class="table table-bordered">
			<thead>
				<tr>
					<th id="id">ردیف</th>
					<th id="match_id">شناسه تیم</th>
					<th id="team_name_en">نام لاتین تیم </th>
					<th id="team_name_fa">نام فارسی تیم </th>
					<th>عملیات</th>
				</tr>
			</thead>
			 <tbody>
				 <?php 
					  if($size<1 ){
				?>
						  <tr>
							<td colspan="8">رکوردی وجود ندارد</td>
						  </tr>
					  <?php }else{
						  if($pageNum_rsservice<=$totalPages_rsservice){
							$max= $startRow_rsservice+$maxRows_rsservice;
							  if($max>$size){$max= $size;}

						  
							if(isset($id) and $id !="" ){
								if (isset($content_json->{$id})){
									$values = new stdClass();
									$values->{$id} = $content_json->{$id};
									$values->{$id}->name_en = $content_json->{$id}->name_en;
									$values->{$id}->name_fa = $content_json->{$id}->name_fa;

								}
							}
							elseif(isset($name_en_search) and $name_en_search !=""){
								
								foreach($content_json as $key=>$value){
								
									if($value->name_en == $name_en_search){
										$values = new stdClass();
										$values->{$key} = $content_json->{$key};
										$values->{$key}->name_en = $content_json->{$key}->name_en;
										$values->{$key}->name_fa = $content_json->{$key}->name_fa;
																	
									}
								}
							}
							elseif(isset($name_fa_search) and $name_fa_search !=""){
								foreach($content_json as $key=>$value){
									if($value->name_fa == $name_fa_search){
										$values = new stdClass();
										$values->{$key} = $content_json->{$key};
										$values->{$key}->name_en = $content_json->{$key}->name_en;
										$values->{$key}->name_fa = $content_json->{$key}->name_fa;
									}
								}
							}else
								$values = $content_json;

						  
							  $i = 1 ;
							  if($values != null && isset($values)){
								  
								foreach($values as $key=>$value){

									if($startRow_rsservice+1 <= $i and $i <= $endRow_rsservice ){
							?>
							 <tr>
<form action="<?php echo KT_escapeAttribute(KT_getFullUri()); ?>" method="post" id="<?php echo $i; ?>">
							  <td><?php echo $i; ?></td>
							  <td><?php echo $key; ?>
									 <input type="text" class="hidden" name="key" value = "<?php echo $key ; ?>" >
								  <input type="text" class="hidden" name="game_name" value = "<?php echo $game_name ; ?>" >
							  </td>
							  <td><?php $name_en = $value->name_en;?> 
								  <input type="text" class="form-control" name="name_en" value = "<?php echo $name_en ; ?>" >
							  </td>
								 
							  <td><?php $name_fa = $value->name_fa; ?>
								<input type="text" class="form-control" name="name_fa" value = "<?php echo $name_fa ; ?>" > 
								 
							   </td>

								<td>
				<ul class="icons-list">
				<?php if (in_array('teamname_edit', $accccesslist)) {?>
				<li class="text-blue-800">
					
						
						<button type="submit" style="background: transparent;border: none;" data-popup="tooltip" title="ویرایش نام فارسی ">
							<i class="icon-pencil7"></i>
						</button>
						
				</li>
				<?php }?>

<!--pageNum_rsservice=3&totalRows_rsservice=377-->
				</ul>
				</td>	
	
		</form>
							</tr>

							<?php }
								$i++;
								}
								  
							  }else{ ?>

								<tr>
									<td colspan="8">رکوردی وجود ندارد</td>
								</tr>
										
							  <?php }
					}
				}?>  
			 </tbody>
		</table>



	</div>

									
</form>
<br /><br />

								
                    <div class="text-center">
<!--
						<?php include("../../layouts/paging.php"); 
						echo pg('pageNum_rsservice', $pageNum_rsservice, $currentPage, $queryString_rsservice, $totalRows_rsservice);
						?>

--> <?php if(!(isset($id)) || $id ==""  || strlen($id) == 0 ){?>
                   <ul class="pagination">
						<?php if ($pageNum_rsservice > 0) { ?>
						<li  ><a href="<?php printf("%s?pageNum_rsservice=%d%s", $currentPage, max(0, $pageNum_rsservice - 1), $queryString_rsservice); ?>" data-popup="tooltip" title="قبلی">&lsaquo;</a></li>
						<?php } ?>
						<?php if ($pageNum_rsservice > 0) { // Show if not first page ?>
						<?php

					   $safheghabli = $pageNum_rsservice -1;
					   $i=$safheghabli-4;
					   if($i<1)
					   $i=0;
						while($i>=0 and $i<=$safheghabli){
					   ?>
						<li><a  href="<?php printf("%s?pageNum_rsservice=%d%s", $currentPage,  $i, $queryString_rsservice); ?>"><?php echo $i+1; ?></a></li>
						 <?php
					   $i++;
					   } ?>
						 <?php } // Show if not first page ?>
						<li class="active"><a ><?php echo $pageNum_rsservice+1; ?></a></li>
						 <?php if ($pageNum_rsservice < $totalPages_rsservice) { // Show if not last page ?>
					   <?php
					   $i=1;
					   $safhebadi = $pageNum_rsservice + 1;
						while($i<5 and $safhebadi <= $totalPages_rsservice){
					   ?>
						<li><a  href="<?php printf("%s?pageNum_rsservice=%d%s", $currentPage,  $safhebadi, $queryString_rsservice); ?>"><?php echo $safhebadi+1; ?></a></li>
						<?php
					   $i++;$safhebadi++;
					   } ?>
					   <?php } ?>
					   <?php if ($pageNum_rsservice < $totalPages_rsservice) { ?>
						<li><a  href="<?php printf("%s?pageNum_rsservice=%d%s", $currentPage, min($totalPages_rsservice, $pageNum_rsservice + 1), $queryString_rsservice); ?>" data-popup="tooltip" title="بعدی">&rsaquo;</a></li>
						<?php } ?>

						</ul><?php } ?>
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
</html>