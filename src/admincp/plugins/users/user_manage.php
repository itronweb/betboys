<?php 
	GLOBAL $content;
	$content['title']       = "تغییرات کاربری";
	require('../../layouts/sidebar.php');
	require('pass.php');
	$pass = new pass();
	
$fields = array( 'first_name', 'last_name', 'cash', 'instagram', 'mobile', 'email', 'username' ,'status' ); 
$append = array( 'password' => $pass->hash($_POST['password']) );
$_POST = array_merge($_POST, $append );
$fields = array_merge( $fields, array_keys($append) );
mysqli_query($cn,"SET NAMES 'utf8'");
if ( (@$_POST['go'] == "add") ) {
	$values = []; $fields_name = [];

	$data = []; foreach ($fields as $item) if (!isset($_POST[$item])) { unset($item); } else { $data[$item] = $_POST[$item]; }
	$add = dbAdd( 'users', $data );
	
    $status = ($add ? '<div class="panel panel-success"><div class="panel-heading">کاربر با موفقيت ايجاد شد.</div></div>' : '<div class="panel panel-danger"><div class="panel-heading">مشکلي در ساخت کاربر به وجود آمده است.</div></div>');
} elseif ( (@$_POST['go'] == "edit") ) {

	$data = []; foreach ($fields as $item) if (!isset($_POST[$item])) { unset($item); } else { $data[$item] = $_POST[$item]; }
	$edit = dbWrite( 'users', [ 'id' => $_POST['id'] ], $data );

    $status = ($edit ? '<div class="panel panel-warning"><div class="panel-heading">کاربر با موفقيت ويرايش شد.</div></div>' : '<div class="panel panel-danger"><div class="panel-heading">مشکلي در ويرايش کاربر به وجود آمده است.</div></div>');
}
if (isset($_GET['edit']) && ($_GET['edit'] !== "")) {
    $row_users    = @array_pop( dbRead( 'users',    ['id'=>sanitizeInt($_GET['edit'])], 1 ) );
}
?>
	<?php echo @$status; ?>
		<div class="row">
			<div class="col-sm-12">	
					<?php
						$row_insta = @getInsta( $row_users['instagram'] );
						$avatar    = !empty($row_insta['avatar']) ? $row_insta['avatar'] : 'https://sezarco.ir/sezar-cp/sezar-upload/sezar-avatars/default-avatar.png';
							?>			
						<img src="<?= $avatar; ?>" style="display: block;margin-left: 10px;margin: auto !important; max-width: 50px; border-radius: 80%; transform: scale(2.5) translate(10px, 0px);">
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
							<?php if ( !empty($_GET['edit']) ) { ?>
							<a type="button" href="#" class="btn btn-default button btn-pahn">
								<span class="fa fa-money"></span> موجودی حساب : <?= $row_users['cash']; ?>
							</a>
							<?php } ?>
							<button type="submit" class="btn btn-primary button btn-pahn">
								<span class="fa fa-floppy-o"></span> ثبت
							</button>
							<!--
							<button type="submit" class="btn btn-danger button btn-pahn">
								<span class="fa fa-close"></span> اخراج
							</button>
							-->
						</div>
						<input type="hidden" name="go" value="<?php echo (isset($row_users)) ? "edit" : "add"; ?>">
						<input type="hidden" name="id" value="<?php echo @$row_users['id']; ?>">
						<section data-type="all" >
							<div class="form-group row">
								<div class="col-sm-3"><label class="control-label">نام کاربر</label>
								<input name="first_name" type="text" value="<?php echo @$row_users['first_name']; ?>" class="form-control"></div>
								<div class="col-sm-3"><label class="control-label">نام خانوادگي کاربر</label>
								<input name="last_name" type="text" value="<?php echo @$row_users['last_name']; ?>" class="form-control"></div>
								<label class="control-label">ایدی اینستاگرام</label>
								<div class="col-sm-3 input-group m-b">
								<input type="text" name="instagram" value="<?php echo @$row_users['instagram']; ?>" placeholder="ایدی اینستاگرام کاربر" class="form-control">
								<span class="input-group-addon">@</span></div>
							</div>
							<div class="form-group row">
								<div class="col-sm-3">
									<label class="control-label">نام کاربری</label>
									<input name="username" type="text" value="<?php echo @$row_users['username']; ?>" class="form-control">
								</div>
								<div class="col-sm-3">
									<label class="control-label">پسورد جدید</label>
									<input name="password" type="password" value="" class="form-control">
								</div>
								<div class="col-sm-5">
								<label class="control-label">ايميل</label><input style="text-align:left;" name="email" type="text" value="<?php echo @$row_users['email']; ?>" class="form-control"></div>
							</div>
							<div class="form-group row">
								<div class="col-sm-3">
								<label class="control-label">موبايل کاربر</label><input name="mobile" type="text" value="<?php echo @$row_users['mobile']; ?>" class="form-control"></div>
								<div class="col-sm-3">
								<label class="control-label">وضعیت حساب</label><SELECT name="status" class="form-control">
									<?php
										$Types = array ('1','2');
										foreach ($Types as $Select) {
											if($Select != 1){
												$status	=	'غیرفعال';
											}else{
												$status	=	'فعال';
											}
										  echo "<OPTION value='$Select'" . ($Select == @$row_users['status'] ? " selected" : "") . ">$status</OPTIN>";
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
		<h2>آمار شرط های کاربر </h2>
					<div class="row">
                        <div class="col-md-12">
                            <div class="table-responsive table-commerce panel-view">
                                <table id="basic-datatables" class="table table-striped table-hover">
                                    <thead>
                                        <tr>
                                            <th style="width:80px">
                                                <strong>ایدی</strong>
                                            </th>
                                            <th>
                                                <strong>زمان ثبت شرط</strong>
                                            </th>
                                            <th>
                                                <strong>مبلغ</strong>
                                            </th>
                                            <th>
                                                <strong>ضریب</strong>
                                            </th>
                                            <th>
                                                <strong>مبلغ برد</strong>
                                            </th>
                                        </tr>
                                    </thead>
                                    <tbody>
									<?php
										$row_bet = dbRead('bets', ['user_id'=>$_GET[ 'edit' ]]) ;
										usort( $row_bet, function($a, $b) { return $a['id'] == $b['id'] ? 0 : $b['id'] - $a['id']; } );
										foreach ( $row_bet as $row_betn ) { 
									?>
                                        <tr>
                                            <td><?= $row_betn['id']; ?></td>
                                            <td>
											  <?php $s= str_replace("-","/",$row_betn['created_at']);?>
											  <?php $s=miladi_to_jalali($row_betn['created_at'],"-"," "); ?>
											  <?= substr($s,8,2)." ".monthname(substr($s,5,2)). ' ' .substr($s,0,4). ' ' .substr($s,11,9); ?>
											</td>
                                            <td><?= numberformat($row_betn['stake']); ?> تومان</td>
                                            <td><?= $row_betn['effective_odd']; ?></td>
                                            <td>
											<?php
												$win_stake	=	$row_betn['stake'] * $row_betn['effective_odd'];
												$win_stake	=	numberformat($win_stake);
												if ($row_betn['status'] == 0){
													$status	=	'نامعلوم';
													$finally_stake = $status;
												}
												if ($row_betn['status'] == 1){
													$status	=	'برد';
													$finally_stake = $win_stake;
												}
												if ($row_betn['status'] == 2){
													$status	=	'باخت';
													$finally_stake = $row_betn['stake'];
												}
												if ($row_betn['status'] == 3){
													$status	=	'استرداد';
													$finally_stake = $row_betn['stake'];
												}
												if ($row_betn['status'] == 4){
													$status	=	'نیم برد';
													$finally_stake = (($row_betn['effective_odd']/2)+0.50)*$row_betn['stake'];
												}
												if ($row_betn['status'] == 5){
													$status	=	'نیم باخت';
													$finally_stake = $row_betn['stake']/2;
												}
												?>
												<?php if($row_betn['status'] != 0 ){ ?>
												<li>مبلغ : <?= $finally_stake; ?> تومان</li>
												<?php } ?>
												<li>وضعیت : <?= $status; ?></li>
												<a href="../bets/bets_cancel.php?work=5&id=<?php echo encrypt($row_betn['id'],session_id()."bets");?>" onClick="return confirm('آیا مطمئنید می خواهید  شرط را نیم باخت کنید ؟');" data-popup="tooltip" title="نیم باخت"><i class='text-danger fa fa-circle-thin'></i></a>
												<a href="../bets/bets_cancel.php?work=2&id=<?php echo encrypt($row_betn['id'],session_id()."bets");?>" onClick="return confirm('آیا مطمئنید می خواهید  شرط را باخت کنید ؟');" data-popup="tooltip" title="باخت"><i class='text-danger fa fa-circle'></i></a>
												<a href="../bets/bets_cancel.php?work=1&id=<?php echo encrypt($row_betn['id'],session_id()."bets");?>" onClick="return confirm('آیا مطمئنید می خواهید  شرط را برد کنید ؟');" data-popup="tooltip" title="برد"><i class='text-success fa fa-star'></i></a>
												<a href="../bets/bets_cancel.php?work=4&id=<?php echo encrypt($row_betn['id'],session_id()."bets");?>" onClick="return confirm('آیا مطمئنید می خواهید  شرط را نیم برد  کنید ؟');" data-popup="tooltip" title="نیم برد"><i class='text-success fa fa-star-half-o'></i></a>
												<a href="../bets/bets_cancel.php?work=3&id=<?php echo encrypt($row_betn['id'],session_id()."bets");?>" onClick="return confirm('آیا مطمئنید می خواهید شرط را استرداد  کنید ؟');" data-popup="tooltip" title="استرداد"><i class='text-primary icon-drawer-out'></i></a>
											</td>
                                        </tr>
										<?php } ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
<?php require('../../layouts/footer.php');?>