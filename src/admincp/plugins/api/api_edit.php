<?php 
	GLOBAL $content;
	$content['title']       = "تنظیمات API ورزشی";
	require('../../layouts/sidebar.php');
	
$fields = array( 'api_token','status' ); 
mysqli_query($cn,"SET NAMES 'utf8'");
if ( (@$_POST['go'] == "add") ) {
	$values = []; $fields_name = [];

	$data = []; foreach ($fields as $item) if (!isset($_POST[$item])) { unset($item); } else { $data[$item] = $_POST[$item]; }
	$add = dbAdd( 'api_token', $data );
	
    $status = ($add ? '<div class="panel panel-success"><div class="panel-heading">تیم با موفقیت ایجاد شد.</div></div>' : '<div class="panel panel-danger"><div class="panel-heading">مشکلي در ساخت تیم به وجود آمده است.</div></div>');
} elseif ( (@$_POST['go'] == "edit") ) {

	$data = []; foreach ($fields as $item) if (!isset($_POST[$item])) { unset($item); } else { $data[$item] = $_POST[$item]; }
	$edit = dbWrite( 'api_token', [ 'id' => $_POST['id'] ], $data );

    $status = ($edit ? '<div class="panel panel-warning"><div class="panel-heading">تیم با موفقیت ویرایش شد.</div></div>' : '<div class="panel panel-danger"><div class="panel-heading">مشکلي در ويرايش تیم به وجود آمده است.</div></div>');
}
if (isset($_GET['edit']) && ($_GET['edit'] !== "")) {
    $row_api_token    = @array_pop( dbRead( 'api_token',    ['id'=>sanitizeInt($_GET['edit'])], 1 ) );
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
						<input type="hidden" name="go" value="<?php echo (isset($row_api_token)) ? "edit" : "add"; ?>">
						<input type="hidden" name="id" value="<?php echo @$row_api_token['id']; ?>">
						<section data-type="all" >
							<div class="form-group row">
								<div class="col-sm-5"><label class="control-label">کد API</label>
								<input name="api_token" type="text" value="<?php echo @$row_api_token['api_token']; ?>" class="form-control"></div>
								<div class="col-sm-3"><label class="control-label">نام فارسی تیم</label>
								<SELECT name="status" class="form-control">
									<?php
										$Types = array ('1','2');
										foreach ($Types as $Select) {
											if($Select != 1){
												$status	=	'غیرفعال';
											}else{
												$status	=	'فعال';
											}
										  echo "<OPTION value='$Select'" . ($Select == @$row_api_token['status'] ? " selected" : "") . ">$status</OPTIN>";
										}
										?>
									</SELECT>
							</div>				
						</section>
						<br><br>
					</form>
				</div>
				<!-- End .panel --> 
			</div>
		</div>
<?php require('../../layouts/footer.php');?>