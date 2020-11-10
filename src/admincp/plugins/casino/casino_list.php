<?php 
   GLOBAL $content;
   $content['title']       = "بازی های کازینو";
   require('../../layouts/sidebar.php');
	if ( isset($_GET['delete']) && !empty($_GET['delete']) ){
		$del_page = mysqli_query($cn,"DELETE FROM `casino` WHERE `id` = '".intval($_GET['delete'])."' LIMIT 1");
	if ( $del_page )
	{
		$status = '<div class="panel panel-success"><div class="panel-heading">بازی کازینو با موفقیت حذف شد.</div></div>';
	}
	else
	{
		$status = '<div class="panel panel-danger"><div class="panel-heading">مشکلی در حذف بازی کازینو به وجود آمده است.</div></div>';
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
                                                <strong>کد بازی</strong>
                                            </th>
                                            <th>
                                                <strong>نام بازی</strong>
                                            </th>
                                            <th>
                                                <strong>تعداد بازیکن</strong>
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
										$row_game = dbRead('casino');
										usort( $row_game, function($a, $b) { return $a['id'] == $b['id'] ? 0 : $b['id'] - $a['id']; } );
										foreach ( $row_game as $row_gamen ) { 
									?>
									
                                        <tr>
                                            <td><?php echo sanitize($row_gamen['sort']); ?></td>
                                            <td>
												<?= sanitize_output($row_gamen['name_fa']) ?>
											</td>
											<?php
												if ($row_gamen['multi_player'] != 1){	
													$status	=	'چند نفره';
												}else{
													$status	=	'تک نفره';
												}
												?>
                                            <td>
												<?= sanitize_output($status); ?>
											</td>
											<?php
												if ($row_gamen['status'] != 1){	
													$status	=	'غیرفعال';
												}else{
													$status	=	'فعال';
												}
												?>
                                            <td>
												<a style="color:#FFF;" href="../../change_status.php?table=<?php echo encrypt('casino',session_id()."sts"); ?>&field=<?php echo encrypt('status',session_id()."sts"); ?>&id=<?php echo encrypt($row_gamen['id'],session_id()."sts"); ?>">
													<?= $status; ?>
												</a>
											</td>
                                            <td>
												<a href="cgame_manage.php?edit=<?php echo sanitize($row_gamen['id']); ?>" data-popup="tooltip" title="ویرایش بازی کازینو"><i class='fa fa-edit' style="color: #FFF;"></i></a>
												<a href="casino_list.php?delete=<?php echo sanitize($row_gamen['id']); ?>" onClick="return confirm('آیا مطمئنید می خواهید اطلاعات را حذف کنید ؟');" data-popup="tooltip" title="حذف بازی کازینو"><i class='text-danger fa fa-trash'></i></a>
											</td>
                                        </tr>
										<?php } ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
<?php require('../../layouts/footer.php');?>