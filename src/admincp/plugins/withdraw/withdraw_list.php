<?php 
   GLOBAL $content;
   $content['title']       = "لیست درخواست وجه کاربران";
   require('../../layouts/sidebar.php');
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
                                                <strong>مبلغ درخواستی</strong>
                                            </th>
                                            <th>
                                                <strong>وضعیت پرداخت</strong>
                                            </th>
                                            <th>
                                                <strong>زمان ارسال</strong>
                                            </th>
                                            <th>
                                                <strong>تغییرات</strong>
                                            </th>
                                        </tr>
                                    </thead>
                                    <tbody>
									<?php
										$row_withdraw = dbRead('withdraw');
										usort( $row_withdraw, function($a, $b) { return $a['id'] == $b['id'] ? 0 : $b['id'] - $a['id']; } );
										foreach ( array_slice($row_withdraw, 0, 10) as $row_withdrawn ) { 
									?>
									
                                        <tr>
                                            <td><?php echo sanitize($row_withdrawn['id']); ?></td>
                                            <td>
												<?php	
													$row_usersender = dbGetRow('users', ['id'=>$row_withdrawn['user_id']]);		
													$row_insta = @getInsta( $row_usersender['instagram'] );
													$avatar    = !empty($row_insta['avatar']) ? $row_insta['avatar'] : 'https://sezarco.ir/sezar-cp/sezar-upload/sezar-avatars/default-avatar.png';
												?>
												<a href="../users/user_manage.php?edit=<?= $row_withdrawn['user_id']; ?>"><img src="<?= $avatar; ?>" class="avatar"> <?= sanitize_output($row_usersender['first_name']) . ' ' . sanitize_output($row_usersender['last_name']); ?></a>
											</td>
                                            <td><?= numberformat($row_withdrawn['amount']); ?> تومان</td>
											<?php
												if($row_withdrawn['status']==0) {
														$status	=	 "پرداخت نشده";
														$css	=	"danger";
													}
												   else if ($row_withdrawn['status']==1){
														$status	=	  "پرداخت شده";
														$css	=	"success";
													}
												   else if ($row_withdrawn['status']==2){
														$status	=	 "صف پرداخت";
														$css	=	"violet";
													}
												   else if ($row_withdrawn['status']==3){
														$status	=	 "لغو درخواست کاربر";
														$css	=	"orange";
												   }
												?>
                                            <td>
													<span class="label label-<?= $css; ?>" style="font-size:12px;text-align:center;"><?= $status; ?></span>
											</td>
                                            <td>
											  <?php $s= str_replace("-","/",$row_withdrawn['created_at']);?>
											  <?php $s=miladi_to_jalali($row_withdrawn['created_at'],"-"," "); ?>
											  <?= substr($s,8,2)." ".monthname(substr($s,5,2)). ' ' .substr($s,0,4). ' ' .substr($s,11,9); ?>
											</td>
                                            <td>
												<a href="withdraw_view.php?id=<?= $row_withdrawn['id']; ?>">
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