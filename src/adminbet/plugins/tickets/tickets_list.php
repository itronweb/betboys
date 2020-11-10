<?php 
   GLOBAL $content;
   $content['title']       = "لیست تیکت ها";
   require('../../layouts/sidebar.php');
if ( isset($_GET['open']) && !empty($_GET['open']) )
{
	$opn = dbWrite( 'tickets', ['id'=>$_GET['open']], ['status'=>'1'] );
	if ( $opn )
	{
		$status = '<div class="panel panel-success"><div class="panel-heading">تیکت با موفقیت به وضعیت باز تغییر پیدا کرد.</div></div>';
	}
	else
	{
		$status = '<div class="panel panel-danger"><div class="panel-heading">مشکلی در تغییر حالت تیکت به وجود آمده است.</div></div>';
	}
}
if ( isset($_GET['close']) && !empty($_GET['close']) )
{
	$cls = dbWrite( 'tickets', ['id'=>$_GET['close']], ['status'=>'2'] );
	if ( $cls )
	{
		//Sms( 216, ['ticket_id'=>$_GET['close'], 'ticket_name'=>dbGetRow('tickets', ['id'=>$_GET['close']])['subject'] ] );
		$status = '<div class="panel panel-success"><div class="panel-heading">تیکت با موفقیت به وضعیت بسته تغییر پیدا کرد.</div></div>';
	}
	else
	{
		$status = '<div class="panel panel-danger"><div class="panel-heading">مشکلی در تغییر حالت تیکت به وجود آمده است.</div></div>';
	}
}
   ?>
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
                                                <strong>کاربر</strong>
                                            </th>
                                            <th>
                                                <strong>موضوع پیام</strong>
                                            </th>
                                            <th>
                                                <strong>زمان ارسال</strong>
                                            </th>
                                            <th>
                                                <strong>تاریخ آخرین پاسخ</strong>
                                            </th>
                                            <th>
                                                <strong>وضعیت</strong>
                                            </th>
                                            <th>
                                                <strong>تغییرات</strong>
                                            </th>
                                        </tr>
                                    </thead>
                                    <tbody>
									<?php
										$row_ticket = dbRead('tickets');
										usort( $row_ticket, function($a, $b) { return $a['id'] == $b['id'] ? 0 : $b['id'] - $a['id']; } );
										foreach ( $row_ticket as $row_ticketn ) { 
									?>
                                        <tr>
                                            <td><?php echo sanitize($row_ticketn['id']); ?></td>
                                            <td>
												<?php	
													$row_usersender = dbGetRow('users', ['id'=>$row_ticketn['user_id']]);		
													$row_insta = @getInsta( $row_usersender['instagram'] );
													$avatar    = !empty($row_insta['avatar']) ? $row_insta['avatar'] : 'https://sezarco.ir/sezar-cp/sezar-upload/sezar-avatars/default-avatar.png';
												?>
												<a href="../users/user_manage.php?edit=<?= $row_ticketn['user_id']; ?>"><img src="<?= $avatar; ?>" class="avatar"> <?= sanitize_output($row_usersender['first_name']) . ' ' . sanitize_output($row_usersender['last_name']); ?> </a>
											</td>
                                            <td><?= $row_ticketn['subject']; ?></td>
                                            <td>
											  <?php $s= str_replace("-","/",$row_ticketn['created_at']);?>
											  <?php $s=miladi_to_jalali($row_ticketn['created_at'],"-"," "); ?>
											  <?= substr($s,8,2)." ".monthname(substr($s,5,2)). ' ' .substr($s,0,4). ' ' .substr($s,11,9); ?>
											</td>
                                            <td>
											  <?php $s= str_replace("-","/",$row_ticketn['updated_at']);?>
											  <?php $s=miladi_to_jalali($row_ticketn['updated_at'],"-"," "); ?>
											  <?= substr($s,8,2)." ".monthname(substr($s,5,2)). ' ' .substr($s,0,4). ' ' .substr($s,11,9); ?>
											</td>
                                            <td>
											  <?php
												if($row_ticketn['status'] == 0){
													$status	=	'خوانده نشده';
												}elseif($row_ticketn['status'] == 1){
													$status	=	'باز ';
												}
												else{
													$status	=	'بسته شده';
												}
												echo $status;
												?>
											</td>
                                            <td>
												<a href="tickets_view.php?id=<?= $row_ticketn['id']; ?>">
													<span class="label label-success" style="font-size:12px;text-align:center;">نمایش درخواست</span>
												</a>
											</td>
                                        </tr>
										<?php } ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
<?php require('../../layouts/footer.php');?>