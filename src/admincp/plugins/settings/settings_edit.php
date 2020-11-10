<?php 
   GLOBAL $content;
   $content['title']       = "تغییر کانفیگ وبسایت";
   require('../../layouts/sidebar.php');
	
$fields = array( 'value' ); 
$append = array( 'updated_at' => date("Y-m-d H:i:s") );
$_POST = array_merge($_POST, $append );
$fields = array_merge( $fields, array_keys($append) );
mysqli_query($cn,"SET NAMES 'utf8'");
if ( (@$_POST['go'] == "edit") ) {

	$data = []; foreach ($fields as $item) if (!isset($_POST[$item])) { unset($item); } else { $data[$item] = $_POST[$item]; }
	$edit = dbWrite( 'settings', [ 'id' => $_POST['id'] ], $data );

    $status = ($edit ? '<div class="panel panel-warning"><div class="panel-heading">کانفیگ با موفقیت ویرایش شد.</div></div>' : '<div class="panel panel-danger"><div class="panel-heading">مشکلي در ويرايش کانفیگ به وجود آمده است.</div></div>');
}
if (isset($_GET['edit']) && ($_GET['edit'] !== "")) {
    $row_config    = @array_pop( dbRead( 'settings',    ['id'=>sanitizeInt($_GET['edit'])], 1 ) );
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
						<input type="hidden" name="go" value="edit">
						<input type="hidden" name="id" value="<?php echo @$row_config['id']; ?>">
						<section data-type="all" >
							<div class="form-group row">
								<div class="col-sm-8"><label class="control-label"><?php echo @$row_config['name']; ?></label>
								<textarea style="height: 150px;" name="value" type="text" class="form-control"><?php echo @$row_config['value']; ?></textarea></div>
							</div>
						</section>
						
						<br><br>
					</form>
				</div>
				<!-- End .panel --> 
			</div>
		</div>
<?php require('../../layouts/footer.php');?>