<?php 
	GLOBAL $content;
	$content['title']       = "تغییر ترجمه تیم ها";
	require('../../layouts/sidebar.php');
	
$fields = array( 'teams_id','teams_name_en', 'teams_name_fa' ); 
mysqli_query($cn,"SET NAMES 'utf8'");
if ( (@$_POST['go'] == "add") ) {
	$values = []; $fields_name = [];

	$data = []; foreach ($fields as $item) if (!isset($_POST[$item])) { unset($item); } else { $data[$item] = $_POST[$item]; }
	$add = dbAdd( 'teams', $data );
	
    $status = ($add ? '<div class="panel panel-success"><div class="panel-heading">تیم با موفقیت ایجاد شد.</div></div>' : '<div class="panel panel-danger"><div class="panel-heading">مشکلي در ساخت تیم به وجود آمده است.</div></div>');
} elseif ( (@$_POST['go'] == "edit") ) {

	$data = []; foreach ($fields as $item) if (!isset($_POST[$item])) { unset($item); } else { $data[$item] = $_POST[$item]; }
	$edit = dbWrite( 'teams', [ 'id' => $_POST['id'] ], $data );

    $status = ($edit ? '<div class="panel panel-warning"><div class="panel-heading">تیم با موفقیت ویرایش شد.</div></div>' : '<div class="panel panel-danger"><div class="panel-heading">مشکلي در ويرايش تیم به وجود آمده است.</div></div>');
}
if (isset($_GET['edit']) && ($_GET['edit'] !== "")) {
    $row_teams    = @array_pop( dbRead( 'teams',    ['id'=>sanitizeInt($_GET['edit'])], 1 ) );
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
						<input type="hidden" name="go" value="<?php echo (isset($row_teams)) ? "edit" : "add"; ?>">
						<input type="hidden" name="id" value="<?php echo @$row_teams['id']; ?>">
						<section data-type="all" >
							<div class="form-group row">
								<div class="col-sm-3"><label class="control-label">نام انگلیسی تیم</label>
								<input name="teams_name_en" type="text" value="<?php echo @$row_teams['teams_name_en']; ?>" class="form-control"></div>
								<div class="col-sm-3"><label class="control-label">نام فارسی تیم</label>
								<input name="teams_name_fa" type="text" value="<?php echo @$row_teams['teams_name_fa']; ?>" class="form-control"></div>
								<label class="control-label">ایدی تیم</label>
								<div class="col-sm-3 input-group m-b">
								<input type="text" name="teams_id" value="<?php echo @$row_teams['teams_id']; ?>" placeholder="ایدی تیم در API" class="form-control">
								</div>
							</div>				
						</section>
						<br><br>
					</form>
				</div>
				<!-- End .panel --> 
			</div>
		</div>
<?php require('../../layouts/footer.php');?>