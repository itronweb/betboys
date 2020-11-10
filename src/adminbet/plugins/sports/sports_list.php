<?php 
   GLOBAL $content;
   $content['title']       = "رشته های ورزشی";
   require('../../layouts/sidebar.php');
	if ( isset($_GET['delete']) && !empty($_GET['delete']) ){
		$del_page = mysqli_query($cn,"DELETE FROM `sports` WHERE `id` = '".intval($_GET['delete'])."' LIMIT 1");
	if ( $del_page )
	{
		$status = '<div class="panel panel-success"><div class="panel-heading">رشته ورزشی با موفقیت حذف شد.</div></div>';
	}
	else
	{
		$status = '<div class="panel panel-danger"><div class="panel-heading">مشکلی در حذف رشته ورزشی به وجود آمده است.</div></div>';
	}
	}
   ?>
	<?php echo @$status; ?>
				<div class="row">
                        <div class="col-md-12">
                            <div class="panel table-responsive table-commerce panel-view">
                                <table id="basic-datatables" class="table table-striped table-hover">
                                    <thead>
                                        <tr>
                                            <th style="width:100px">
                                                <strong>کد رشته</strong>
                                            </th>
                                            <th>
                                                <strong>نام رشته ورزشی</strong>
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
										$row_sport = dbRead('sports');
										usort( $row_sport, function($a, $b) { return $a['id'] == $b['id'] ? 0 : $b['id'] - $a['id']; } );
										foreach ( $row_sport as $row_sportn ) { 
									?>
									
                                        <tr>
                                            <td><?php echo sanitize($row_sportn['sort']); ?></td>
                                            <td>
												<?= sanitize_output($row_sportn['name']) ?>
											</td>
											<?php
												if ($row_sportn['status'] != 1){	
													$status	=	'غیرفعال';
												}else{
													$status	=	'فعال';
												}
												?>
                                            <td>
												<a style="color:#FFF;" href="../../change_status.php?table=<?php echo encrypt('sports',session_id()."sts"); ?>&field=<?php echo encrypt('status',session_id()."sts"); ?>&id=<?php echo encrypt($row_sportn['id'],session_id()."sts"); ?>">
													<?= $status; ?>
												</a>
											</td>
                                            <td>
												<a href="sport_manage.php?edit=<?php echo sanitize($row_sportn['id']); ?>" data-popup="tooltip" title="ویرایش رشته ورزشی"><i class='fa fa-edit' style="color: #FFF;"></i></a>
												<a href="sports_list.php?delete=<?php echo sanitize($row_sportn['id']); ?>" onClick="return confirm('آیا مطمئنید می خواهید اطلاعات را حذف کنید ؟');" data-popup="tooltip" title="حذف رشته ورزشی"><i class='text-danger fa fa-trash'></i></a>
											</td>
                                        </tr>
										<?php } ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
<?php require('../../layouts/footer.php');?>