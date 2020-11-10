<?php 
   GLOBAL $content;
   $content['title']       = "نمایش تیکت کاربر";
   require('../../layouts/sidebar.php');
   
   ?>
      <div class="row">

                        <div>
                            <?php
								$row_ticket     = dbGetRow( 'tickets', ['id' => @$_GET['id']] );
								$row_poster     = dbGetRow( 'users', ['id'=>@$row_ticket['user_id']] );

								$row_insta = @getInsta( $row_poster['instagram'] );
								$avatar    = !empty($row_insta['avatar']) ? $row_insta['avatar'] : '/sezar-cp/sezar-upload/sezar-avatars/default-avatar.png';
								$name = trim(sanitize_output($row_User['first'] . ' ' . $row_User['last']));
								$user_id = $row_User['id'];
								   
                                if ( !empty($row_ticket) ) {
                            ?>
							<div class="col-md-12">
								<table class="table table-hover panel panel-view">
									<tbody class="panel-body">
										<tr>
											<td>زمان ثبت تیکت</td>
											<td>
												<?php $s= str_replace("-","/",$row_ticket['created_at']);?>
												<?php $s=miladi_to_jalali($row_ticket['created_at'],"-"," "); ?>
												<?= substr($s,8,2)." ".monthname(substr($s,5,2)).substr($s,0,4)." "."- ".substr($s,11,9); ?>
											</td>
										</tr>
										<tr>
											<td>تیکت شماره</td>
											<td><?= $row_ticket['id']; ?></td>
										</tr>
										<tr>
											<td>ارسال شده توسط کاربر</td>
											<td><img src="<?= $avatar; ?>" class="avatar"><a href="../users/user_manage.php?edit=<?= $row_poster['id']; ?>"> <?= $row_poster['first_name']. ' ' . $row_poster['last_name']; ?></a></td>
										</tr>
										<tr>
											<td>وضعیت</td>
											<td> 
											  <?php
												if($row_ticket['status'] == 0){
													$status	=	'خوانده نشده';
												}elseif($row_ticket['status'] == 1){
													$status	=	'باز ';
												}
												else{
													$status	=	'بسته شده';
												}
												echo 'تیکت مورد نظر ' . $status . ' است';
												?>
											</td>
										</tr>
										<tr>
											<td>موضوع</td>
											<td> <?= $row_ticket['subject']; ?></td>
										</tr>
									</tbody>
								</table>
							</div>

                            <?php
                                if ( !empty($_POST['ticket_msg']) )
                                {
                                    $success = dbAdd( 'ticket_replies', ['ticket_id'=>$row_ticket['id'], 'created_at'=>date("Y-m-d H:i:s"), 'content'=>$_POST['ticket_msg'], 'user_id'=>$row_admin['id'], 'admin'=>1] );

                                    dbWrite( 'tickets', ['id'=>$row_ticket['id']], ['status'=>1, 'updated_at'=>date("Y-m-d H:i:s")] );
									
                                    //Sms( 214, ['ticket_id'=>$row_ticket['id'], 'ticket_name'=>$row_ticket['subject'], 'comment_msg'=>$_POST['ticket_msg'], 'user_id'=>$row_usern['id'], 'poster_id'=>$row_poster['id'] ] ); // poster is ticket owner

                                    if ( $success ) { ?>
                                        <div class="col-md-6" style="width:100%;">
                                            <div class="panel panel-success" style="background-color:#3a3c45;">
                                                <div class="panel-heading">
                                                    <span class="">
                                                        موفقیت
                                                    </span>
                                                </div>
                                                <div class="panel-body">
                                                    <h3>پاسخ شما با موفقیت در این تیکت ذخیره شد.</h3>
                                                </div>
                                            </div>
                                        </div>
                                    <?php } else { ?>
                                        <div class="col-md-6" style="width:100%;">
                                            <div class="panel panel-danger" style="background-color:#3a3c45;">
                                                <div class="panel-heading">
                                                    <span class="">
                                                        خطا
                                                    </span>
                                                </div>
                                                <div class="panel-body">
                                                    <h3>مشکلی در تبت این تیکت پیش آمد!</h3>
                                                </div>
                                            </div>
                                        </div>
                                    <?php } 
                                }
                            ?>

                            <?php
                                $Query = mysqli_query($cn, "SELECT * FROM `ticket_replies` WHERE `ticket_id` = '".intval(@$_GET['id'])."' ORDER BY id");
                                while ( $row_ticketcomment = @mysqli_fetch_array($Query) ) { 
								//if ($row_ticketcomment['admin'] == 0){
                                    $row_ticketuser = dbGetRow( 'users', ['id'=>@$row_ticketcomment['user_id']] );
                                    $row_usern = dbGetRow( 'admin',       ['id'=>@$row_ticketcomment['user_id']] );
										if ($row_ticketcomment['admin'] == 0){
											$insta	=	$row_ticketuser['instagram'];
											$name = trim(sanitize_output($row_ticketuser['first_name'] . ' ' . $row_ticketuser['last_name']));
										}else{
											$insta	=	$row_usern['image'];
											$name	=	$row_usern['name'];
										}
                                    $row_insta = @getInsta( $insta );
                                    $avatar    = !empty($row_insta['avatar']) ? $row_insta['avatar'] : 'https://sezarco.ir/sezar-cp/sezar-upload/sezar-avatars/default-avatar.png';
                                    $user_id = $row_ticketuser['id'];
                                    if ( empty($name) ) $name = 'کاربر نامشخص';

                                    $colors = [
                                        0 => 'panel-info',
                                        1 => 'panel-success',
                                        2 => 'panel-primary'
                                    ];

                            ?>
                            <div class="col-md-12" style="width:80%; float:<?= $row_ticketcomment['admin'] == 1 ? 'right' : 'left' ?>">
                                <div class="panel" style="background-color:#3a3c45;">
                                    <div class="panel-heading">
                                        <img src="<?= $avatar; ?>" style="margin-left: 10px; max-width: 24px; border-radius: 80%; transform: scale(2.5) translate(10px, 0px);" />
                                        <span>
                                            <a <?= $row_ticketcomment['admin'] == 1 ? 'admin' : 'href="../users/user_manage.php?edit='.$user_id.'"' ?> style="color: #fff">
												<?= $name; ?>
											</a>
                                            <small> (فالوور : <?= numberformat($row_insta['followers']); ?>) </small>
                                            گفته:
                                        </span>
                                        <span class="pull-left">
											<?php $s= str_replace("-","/",$row_ticketcomment['created_at']);?>
											<?php $s=miladi_to_jalali($row_ticketcomment['created_at'],"-"," "); ?>
											<?= substr($s,8,2)." ".monthname(substr($s,5,2)).substr($s,0,4)." "."- ".substr($s,11,9); ?>
                                        </span>
                                    </div>
                                    <div class="panel-body">
                                        <p> <b>متن پیام:</b> <br/> <?= nl2br( sanitize_output($row_ticketcomment['content']) ); ?>  </p>
                                    </div>
                                </div>
                            </div>
                            <?php }?>

                            <?php if ( strpos( $row_ticket['status'], '2' ) === false ) { ?>
                                    <div class="col-md-12 compose_form" style="clear: both; width: 100%">
                                        <form role="form" action="" method="POST">
                                            <div class="form-group">
                                                <?php
                                                    $row_insta = @getInsta( $row_admin['image'] );
                                                    $avatar    = !empty($row_insta['avatar']) ? $row_insta['avatar'] : 'https://sezarco.ir/sezar-cp/sezar-upload/sezar-avatars/default-avatar.png';
                                                ?>
                                                <img src="<?= $avatar; ?>" style="border-radius: 45%; max-width: 64px; float: right;" />
                                                <h3 style="display: inline-block; margin: 15px 10px; margin-top: 5px;">
                                                    <span style="display: block; margin: 5px 0;"><?= sanitize_output( $row_admin['name'] ); ?></span>
													<span style="display: block; opacity: .6; font-size: 80%;">فالوور : <?= numberformat($row_insta['followers']); ?></span>
                                                </h3>
                                            </div>
                                            <div class="form-group">
                                                <textarea name="ticket_msg" placeholder="متن پیام..." class="form-control" rows="5"></textarea>
                                            </div>
                                            <div class="form-group">
                                                <button type="submit" class="btn btn-primary btn-lg">ارسال</button>
                                                <button type="button" class="btn btn-danger btn-lg" onclick="if (confirm('Are You Sure؟')) window.location = 'tickets_list.php?close=<?php echo $row_ticket['id']; ?>';">بستن تیکت</button>    
                                            </div>
                                        </form>
                                    </div>
                            <?php } else { ?>

                            <div class="col-md-12" style="width: 100%; clear: both">
                                <div class="panel panel-warning" style="background-color:#3a3c45;">
                                    <div class="panel-heading">
                                        <span class="">
                                            توجه:
                                        </span>
                                    </div>
                                    <div class="panel-body">
                                        <strong style="font-weight: bold; display: block;">این تیکت بسته شده است.</strong>
                                            <a onclick="if (confirm('Are You Sure؟')) window.location = 'tickets_list.php?open=<?php echo $row_ticket['id']; ?>';" href="#">باز کردن مجدد تیکت</a>
                                    </div>
                                </div>
                            </div>
                            <?php } ?>
                            <?php } else { ?>
                            <div class="col-md-12" style="width: 100%; clear: both">
                                <div class="panel panel-danger" style="background-color:#3a3c45;">
                                    <div class="panel-heading">
                                        <span class="">
                                            خطا در خواندن تیکت با آیدی <?= $_GET['id']; ?>
                                        </span>
                                    </div>
                                    <div class="panel-body">
                                        <h3>متأسفانه تیکتی با آیدی درخواستی پیدا نشد</h3>
                                    </div>
                                </div>
                            </div>

                            <?php } ?>

                    </div>
                </div>
<?php require('../../layouts/footer.php');?>
