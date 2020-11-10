<?php 
   GLOBAL $content;
   $content['title']       = "تغییرات درگاه ها";
   require('../../layouts/sidebar.php');
	
$fields = array( 'name','user' ,'pass', 'api_key', 'paymethodid', 'extravalue', 'sort' ); 
$append = array( 'status' => 1 );
$_POST = array_merge($_POST, $append );
$fields = array_merge( $fields, array_keys($append) );
mysqli_query($cn,"SET NAMES 'utf8'");
if ( (@$_POST['go'] == "add") ) {
	$values = []; $fields_name = [];

	$data = []; foreach ($fields as $item) if (!isset($_POST[$item])) { unset($item); } else { $data[$item] = $_POST[$item]; }
	$add = dbAdd( 'gateway', $data );
	
    $status = ($add ? '<div class="panel panel-success"><div class="panel-heading">صفحه با موفقیت ایجاد شد.</div></div>' : '<div class="panel panel-danger"><div class="panel-heading">مشکلي در ساخت صفحه به وجود آمده است.</div></div>');
} elseif ( (@$_POST['go'] == "edit") ) {

	$data = []; foreach ($fields as $item) if (!isset($_POST[$item])) { unset($item); } else { $data[$item] = $_POST[$item]; }
	$edit = dbWrite( 'gateway', [ 'id' => $_POST['id'] ], $data );

    $status = ($edit ? '<div class="panel panel-warning"><div class="panel-heading">صفحه با موفقیت ویرایش شد.</div></div>' : '<div class="panel panel-danger"><div class="panel-heading">مشکلي در ويرايش صفحه به وجود آمده است.</div></div>');
}
if (isset($_GET['edit']) && ($_GET['edit'] !== "")) {
    $row_gateway    = @array_pop( dbRead( 'gateway',    ['id'=>sanitizeInt($_GET['edit'])], 1 ) );
}
?>
	<?php echo @$status; ?>
		<div class="row">
			<div class="col-sm-12">				
				<div class="panel panel-card recent-activites">
					<!-- Start .panel -->
					<div class="panel-heading">
						<div class="panel-actions">
							<a href="#" class="panel-action panel-action-toggle" data-panel-toggle=""></a>
							<a href="#" class="panel-action panel-action-dismiss" data-panel-dismiss=""></a>
						</div>
					</div>
					<form method="post" action="" class="panel-body">
						<div class="pull-left">
							<button type="submit" class="btn btn-primary button btn-pahn">
								<span class="fa fa-floppy-o"></span> ثبت
							</button>
						</div>
						<input type="hidden" name="go" value="<?php echo (isset($row_gateway)) ? "edit" : "add"; ?>">
						<input type="hidden" name="id" value="<?php echo @$row_gateway['id']; ?>">
						<section data-type="all" >
							<div class="form-group row">
								<div class="col-sm-3"><label class="control-label">نام درگاه</label>
								<input name="name" type="text" value="<?php echo @$row_gateway['name']; ?>" class="form-control"></div>
								<div class="col-sm-3"><label class="control-label">نام کاربری</label>
								<input name="user" type="text" value="<?php echo @$row_gateway['user']; ?>" class="form-control"></div>
								<div class="col-sm-3"><label class="control-label">پسورد</label>
								<input name="pass" type="text" value="<?php echo @$row_gateway['pass']; ?>" class="form-control"></div>
								<div class="col-sm-1"><label class="control-label">ترتیب</label>
								<input name="sort" type="text" value="<?php echo @$row_gateway['sort']; ?>" class="form-control"></div>	
							</div>
							<div class="form-group row">
								<div class="col-sm-4">
								<label class="control-label">نحوه پرداخت</label>
								<SELECT name="paymethodid" class="form-control fancy-select">
									<?php
										foreach ( dbRead('paymethod') as $Select ) {
											echo "<OPTION value='{$Select['id']}'" . ($Select['id'] == @$row_gateway['paymethodid'] ? " selected" : "") . ">{$Select['name_en']} - {$Select['name']}</OPTION>";
										} 
									?>
								</SELECT>
								</div>
							<div class="col-sm-5"><label class="control-label">کد API درگاه</label>
							<input name="api_key" type="text" value="<?php echo @$row_gateway['api_key']; ?>" class="form-control"></div>	
							<div class="col-sm-3"><label class="control-label">کد بیشتر</label>
							<input name="extravalue" type="text" value="<?php echo @$row_gateway['extravalue']; ?>" class="form-control"></div>	
							</div>							
						</section>
						
						<br><br>
					</form>
				</div>
				<!-- End .panel --> 
			</div>
		</div>
<?php require('../../layouts/footer.php');?>