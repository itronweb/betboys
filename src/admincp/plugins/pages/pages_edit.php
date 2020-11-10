<?php 
   GLOBAL $content;
   $content['title']       = "ویرایش صفحه";
   require('../../layouts/sidebar.php');
	
$fields = array( 'name','description' ,'slug', 'compiler' ); 
$append = array( 'status' => 1 ,'updated_at'=>date("Y-m-d H:i:s"));
$_POST = array_merge($_POST, $append );
$fields = array_merge( $fields, array_keys($append) );
mysqli_query($cn,"SET NAMES 'utf8'");
if ( (@$_POST['go'] == "add") ) {
	$values = []; $fields_name = [];

	$data = []; foreach ($fields as $item) if (!isset($_POST[$item])) { unset($item); } else { $data[$item] = $_POST[$item]; }
	$add = dbAdd( 'content_pages', $data );
	
    $status = ($add ? '<div class="panel panel-success"><div class="panel-heading">صفحه با موفقیت ایجاد شد.</div></div>' : '<div class="panel panel-danger"><div class="panel-heading">مشکلي در ساخت صفحه به وجود آمده است.</div></div>');
} elseif ( (@$_POST['go'] == "edit") ) {

	$data = []; foreach ($fields as $item) if (!isset($_POST[$item])) { unset($item); } else { $data[$item] = $_POST[$item]; }
	$edit = dbWrite( 'content_pages', [ 'id' => $_POST['id'] ], $data );

    $status = ($edit ? '<div class="panel panel-warning"><div class="panel-heading">صفحه با موفقیت ویرایش شد.</div></div>' : '<div class="panel panel-danger"><div class="panel-heading">مشکلي در ويرايش صفحه به وجود آمده است.</div></div>');
}
if (isset($_GET['edit']) && ($_GET['edit'] !== "")) {
    $row_pages    = @array_pop( dbRead( 'content_pages',    ['id'=>sanitizeInt($_GET['edit'])], 1 ) );
}
?>
<style>
	.note-editable {
		padding: 0;
		overflow:auto;
	}
</style>
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
						<input type="hidden" name="go" value="<?php echo (isset($row_pages)) ? "edit" : "add"; ?>">
						<input type="hidden" name="id" value="<?php echo @$row_pages['id']; ?>">
						<section data-type="all" >
							<div class="form-group row">
								<div class="col-sm-3"><label class="control-label">نام صفحه</label>
								<input name="name" type="text" value="<?php echo @$row_pages['name']; ?>" class="form-control"></div>
								<div class="col-sm-3"><label class="control-label">لینک کوتاه</label>
								<input name="slug" type="text" value="<?php echo @$row_pages['slug']; ?>" class="form-control"></div>
								<div class="col-sm-3">
								<label class="control-label">وضعیت حساب</label>
								<SELECT name="compiler" class="form-control">
									<?php
										$Types = array ('smarty','none');
										foreach ($Types as $Select) {
											if($Select != 'smarty'){
												$status	=	'قالب متن ساده';
											}else{
												$status	=	'موتور متن اسمارتی';
											}
										  echo "<OPTION value='$Select'" . ($Select == @$row_pages['compiler'] ? " selected" : "") . ">$status</OPTIN>";
										}
										?>
									</SELECT>
								</div>
							</div>	
							<div class="form-group row">
								<div class="panel-body pad-0">
									<textarea class="summernote" name="description">
										<?php echo $row_pages['description']; ?>
									</textarea>
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