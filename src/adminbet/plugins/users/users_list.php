<?php 
   GLOBAL $content;
   $content['title']       = "کاربران";
   require('../../layouts/sidebar.php');
?>
				<div class="row">
                        <div class="col-md-12">
                            <div class="panel table-responsive table-commerce panel-view">
                                <table id="basic-datatables" class="table table-striped table-hover">
                                    <thead>
                                        <tr>
                                            <th style="width:80px">
                                                <strong>ایدی</strong>
                                            </th>
                                            <th>
                                                <strong>نام و نام خانوادگی</strong>
                                            </th>
                                            <th>
                                                <strong>ایمیل</strong>
                                            </th>
                                            <th>
                                                <strong>موجودی حساب</strong>
                                            </th>
                                            <th>
                                                <strong>وضعیت اکانت</strong>
                                            </th>
                                            <th>
                                                <strong>تغییرات</strong>
                                            </th>
                                        </tr>
                                    </thead>
                                    <tbody>
									<?php
										if($_GET['id']){
											$row_user = dbRead('users', ['user_id'=>decrypt( $_GET[ 'id' ], session_id() . "user" )]) ;
										}
										else{
											$row_user = dbRead('users');
										}
										usort( $row_user, function($a, $b) { return $a['id'] == $b['id'] ? 0 : $b['id'] - $a['id']; } );
										foreach ( $row_user as $row_usern ) { 
									?>
									
                                        <tr>
                                            <td><?php echo sanitize($row_usern['id']); ?></td>
                                            <td>
												<?php	
													// $row_insta = @getInsta( $row_usern['instagram'] );
													// $avatar    = !empty($row_insta['avatar']) ? $row_insta['avatar'] : 'https://sezarco.ir/sezar-cp/sezar-upload/sezar-avatars/default-avatar.png';
												?>
												<a href="../users/user_manage.php?edit=<?= $row_usern['id']; ?>"><?= sanitize_output($row_usern['first_name']) . ' ' . sanitize_output($row_usern['last_name']); ?></a>
											</td>
                                            <td>
											  <?= sanitize_output($row_usern['email']) ?>
											</td>
                                            <td><?= numberformat($row_usern['cash']); ?> تومان</td>
    											<?php
    												if ($row_usern['status'] != 1){	
    													$status	=	'غیرفعال';
    												}else{
    													$status	=	'فعال';
    												}
												?>
                                            <td>
												<a href="../../change_status.php?table=<?php echo encrypt('users',session_id()."sts"); ?>&field=<?php echo encrypt('status',session_id()."sts"); ?>&id=<?php echo encrypt($row_usern['id'],session_id()."sts"); ?>">
													<?php echo $status; ?>
												</a>
											</td>
                                            <td>
												<a href="user_manage.php?edit=<?= $row_usern['id']; ?>" data-popup="tooltip" title="ویرایش کاربر"><i class='fa fa-edit'></i></a>
												<a href="users_increase.php?id=<?php echo encrypt($row_usern['id'],session_id()."user"); ?>" data-popup="tooltip" title="افزایش اعتبار کاربر"><i class='text-success fa fa-arrow-up'></i></a>
												<a href="users_decrease.php?id=<?php echo encrypt($row_usern['id'],session_id()."user"); ?>" data-popup="tooltip" title="کاهش اعتبار کاربر"><i class='text-danger fa fa-arrow-down'></i></a>
												<a href="../bets/bets_list.php?id=<?php echo encrypt($row_usern['id'],session_id()."user"); ?>" data-popup="tooltip" title="اطلاعات شرط ها"><i class='text-primary icon-drawer-out'></i></a>
												<a href="../payment/payment_list.php?id=<?php echo encrypt($row_usern['id'],session_id()."user"); ?>" data-popup="tooltip" title="تراکنش ها"><i class='text-primary icon-drawer-out'></i></a>
											</td>
                                        </tr>
										<?php } ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
<?php require('../../layouts/footer.php');?>