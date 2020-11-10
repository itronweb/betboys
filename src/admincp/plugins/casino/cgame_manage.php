<?php 
   GLOBAL $content;
   $content['title']       = "تغییرات بازی های کازینو";
   require('../../layouts/sidebar.php');
	
$fields = array( 'name_fa' ,'name_en' ,'url', 'image', 'min_amount', 'max_amount', 'percent_play', 'multi_player', 'sort' ); 
$append = array( 'status' => 1 );
$_POST = array_merge($_POST, $append );
$fields = array_merge( $fields, array_keys($append) );
mysqli_query($cn,"SET NAMES 'utf8'");
if ( (@$_POST['go'] == "add") ) {
	$values = []; $fields_name = [];

	$data = []; foreach ($fields as $item) if (!isset($_POST[$item])) { unset($item); } else { $data[$item] = $_POST[$item]; }
	$add = dbAdd( 'casino', $data );
	
    $status = ($add ? '<div class="panel panel-success"><div class="panel-heading">بازی کازینو با موفقیت ایجاد شد.</div></div>' : '<div class="panel panel-danger"><div class="panel-heading">مشکلي در ساخت بازی کازینو به وجود آمده است.</div></div>');
} elseif ( (@$_POST['go'] == "edit") ) {

	$data = []; foreach ($fields as $item) if (!isset($_POST[$item])) { unset($item); } else { $data[$item] = $_POST[$item]; }
	$edit = dbWrite( 'casino', [ 'id' => $_POST['id'] ], $data );

    $status = ($edit ? '<div class="panel panel-warning"><div class="panel-heading">بازی کازینو با موفقیت ویرایش شد.</div></div>' : '<div class="panel panel-danger"><div class="panel-heading">مشکلي در ويرايش بازی کازینو به وجود آمده است.</div></div>');
}
if (isset($_GET['edit']) && ($_GET['edit'] !== "")) {
    $row_game    = @array_pop( dbRead( 'casino',    ['id'=>sanitizeInt($_GET['edit'])], 1 ) );
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
						<input type="hidden" name="go" value="<?php echo (isset($row_game)) ? "edit" : "add"; ?>">
						<input type="hidden" name="id" value="<?php echo @$row_game['id']; ?>">
						<section data-type="all" >
							<div class="form-group row">
								<div class="col-sm-3"><label class="control-label">نام فارسی</label>
								<input name="name_fa" type="text" value="<?php echo @$row_game['name_fa']; ?>" class="form-control"></div>
								<div class="col-sm-3"><label class="control-label">نام انگلیسی</label>
								<input name="name_en" type="text" value="<?php echo @$row_game['name_en']; ?>" class="form-control"></div>
								<div class="col-sm-1"><label class="control-label">ترتیب</label>
								<input name="sort" type="text" value="<?php echo @$row_game['sort']; ?>" class="form-control"></div>	
							</div>
							<div class="form-group row">
								<div class="col-sm-3"><label class="control-label">حداقل مبلغ شرط</label>
								<input name="min_amount" type="text" value="<?php echo @$row_game['min_amount']; ?>" class="form-control"></div>
								<div class="col-sm-3"><label class="control-label">حداکثر مبلغ شرط</label>
								<input name="max_amount" type="text" value="<?php echo @$row_game['max_amount']; ?>" class="form-control"></div>
								<div class="col-sm-3"><label class="control-label">درصد بازی</label>
								<input name="percent_play" type="text" value="<?php echo @$row_game['percent_play']; ?>" class="form-control"></div>	
							</div>
							<div class="form-group row">
								<div class="col-sm-2">
								<label class="control-label">چند کاربره</label>
								<SELECT name="multi_player" class="form-control">
									<?php
										$Types = array ('0','1');
										foreach ($Types as $Select) {
											if($Select != 0){
												$status	=	'تک نفره';
											}else{
												$status	=	'چند نفره';
											}
										  echo "<OPTION value='$Select'" . ($Select == @$row_game['multi_player'] ? " selected" : "") . ">بازی $status</OPTIN>";
										}
										?>
									</SELECT>
								</div>
								<div class="col-sm-3"><label class="control-label">مسیر بازی</label>
								<input name="url" type="text" value="<?php echo @$row_game['url']; ?>" class="form-control"></div>
								<div class="col-sm-4"><label class="control-label">عکس بازی</label>
								<input name="image" type="text" value="<?php echo @$row_game['image']; ?>" class="form-control"></div>
							</div>							
						</section>
						
						<br><br>
					</form>
				</div>
				<!-- End .panel --> 
			</div>
		</div>
<?php require('../../layouts/footer.php');?>