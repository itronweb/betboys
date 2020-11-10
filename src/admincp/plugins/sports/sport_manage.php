<?php 
   GLOBAL $content;
   $content['title']       = "تغییرات رشته های ورزشی";
   require('../../layouts/sidebar.php');
	
$fields = array( 'name' ,'name_en' ,'name_prematch', 'name_inplay', 'name_result', 'is_inplay', 'is_upcoming', 'is_result', 'sort' ); 
$append = array( 'status' => 1 );
$_POST = array_merge($_POST, $append );
$fields = array_merge( $fields, array_keys($append) );
mysqli_query($cn,"SET NAMES 'utf8'");
if ( (@$_POST['go'] == "add") ) {
	$values = []; $fields_name = [];

	$data = []; foreach ($fields as $item) if (!isset($_POST[$item])) { unset($item); } else { $data[$item] = $_POST[$item]; }
	$add = dbAdd( 'sports', $data );
	
    $status = ($add ? '<div class="panel panel-success"><div class="panel-heading">رشته ورزشی با موفقیت ایجاد شد.</div></div>' : '<div class="panel panel-danger"><div class="panel-heading">مشکلي در ساخت رشته ورزشی به وجود آمده است.</div></div>');
} elseif ( (@$_POST['go'] == "edit") ) {

	$data = []; foreach ($fields as $item) if (!isset($_POST[$item])) { unset($item); } else { $data[$item] = $_POST[$item]; }
	$edit = dbWrite( 'sports', [ 'id' => $_POST['id'] ], $data );

    $status = ($edit ? '<div class="panel panel-warning"><div class="panel-heading">رشته ورزشی با موفقیت ویرایش شد.</div></div>' : '<div class="panel panel-danger"><div class="panel-heading">مشکلي در ويرايش رشته ورزشی به وجود آمده است.</div></div>');
}
if (isset($_GET['edit']) && ($_GET['edit'] !== "")) {
    $row_sports    = @array_pop( dbRead( 'sports',    ['id'=>sanitizeInt($_GET['edit'])], 1 ) );
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
						<input type="hidden" name="go" value="<?php echo (isset($row_sports)) ? "edit" : "add"; ?>">
						<input type="hidden" name="id" value="<?php echo @$row_sports['id']; ?>">
						<section data-type="all" >
							<div class="form-group row">
								<div class="col-sm-3"><label class="control-label">نام فارسی</label>
								<input name="name" type="text" value="<?php echo @$row_sports['name']; ?>" class="form-control"></div>
								<div class="col-sm-3"><label class="control-label">نام انگلیسی</label>
								<input name="name_en" type="text" value="<?php echo @$row_sports['name_en']; ?>" class="form-control"></div>
								<div class="col-sm-1"><label class="control-label">ترتیب</label>
								<input name="sort" type="text" value="<?php echo @$row_sports['sort']; ?>" class="form-control"></div>	
							</div>
							<div class="form-group row">
								<div class="col-sm-3"><label class="control-label">کد پیش بینی زنده</label>
								<input name="name_inplay" type="text" value="<?php echo @$row_sports['name_inplay']; ?>" class="form-control"></div>
								<div class="col-sm-3"><label class="control-label">کد پیش بینی قبل از بازی</label>
								<input name="name_prematch" type="text" value="<?php echo @$row_sports['name_prematch']; ?>" class="form-control"></div>
								<div class="col-sm-3"><label class="control-label">کد نتیجه بازی</label>
								<input name="name_result" type="text" value="<?php echo @$row_sports['name_result']; ?>" class="form-control"></div>	
							</div>
							<div class="form-group row">
								<div class="col-sm-2">
								<label class="control-label">پیش بینی زنده</label>
								<SELECT name="is_inplay" class="form-control">
									<?php
										$Types = array ('1','2');
										foreach ($Types as $Select) {
											if($Select != 1){
												$status	=	'غیرفعال';
											}else{
												$status	=	'فعال';
											}
										  echo "<OPTION value='$Select'" . ($Select == @$row_sports['is_inplay'] ? " selected" : "") . ">$status</OPTIN>";
										}
										?>
									</SELECT>
								</div>
								<div class="col-sm-2">
								<label class="control-label">پیش بینی قبل از بازی</label>
								<SELECT name="upcoming" class="form-control">
									<?php
										$Types = array ('1','2');
										foreach ($Types as $Select) {
											if($Select != 1){
												$status	=	'غیرفعال';
											}else{
												$status	=	'فعال';
											}
										  echo "<OPTION value='$Select'" . ($Select == @$row_sports['is_upcoming'] ? " selected" : "") . ">$status</OPTIN>";
										}
										?>
									</SELECT>
								</div>
								<div class="col-sm-2">
								<label class="control-label">خروجی نتایج</label>
								<SELECT name="is_result" class="form-control">
									<?php
										$Types = array ('1','2');
										foreach ($Types as $Select) {
											if($Select != 1){
												$status	=	'غیرفعال';
											}else{
												$status	=	'فعال';
											}
										  echo "<OPTION value='$Select'" . ($Select == @$row_sports['is_result'] ? " selected" : "") . ">$status</OPTIN>";
										}
										?>
									</SELECT>
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