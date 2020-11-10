<?php 
   GLOBAL $content;
   $content['title']       = "تنظیمات API ورزشی";
   require('../../layouts/sidebar.php');
	if ( isset($_GET['delete']) && !empty($_GET['delete']) ){
		$del_ticket = mysqli_query($cn,"DELETE FROM `teams` WHERE `id` = '".intval($_GET['delete'])."' LIMIT 1");
	if ( $del_ticket )
	{
		$status = '<div class="panel panel-success"><div class="panel-heading">API با موفقیت حذف شد.</div></div>';
	}
	else
	{
		$status = '<div class="panel panel-danger"><div class="panel-heading">مشکلی در حذف API به وجود آمده است.</div></div>';
	}
	}
   ?>
   <?= @$status; ?>
				<div class="row">
                        <div class="col-md-12">
                            <div class="panel table-responsive table-commerce panel-view">
                             <a href="api_edit.php"><button class="btn btn-primary">ثبت API جدید</button></a>
                                <table id="basic-datatables" class="table table-striped table-hover">
                                    <thead>
                                        <tr>
                                            <th style="width:120px">
                                                <strong>ایدی</strong>
                                            </th>
                                            <th>
                                                <strong>کد API</strong>
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
										$row_api = dbRead('api_token');
										usort( $row_api, function($a, $b) { return $a['id'] == $b['id'] ? 0 : $b['id'] - $a['id']; } );
										foreach ( $row_api as $row_apin ) { 
									?>
                                        <tr>
                                            <td><?php echo sanitize($row_apin['id']); ?></td>
                                            <td><?php echo $row_apin['api_token']; ?></td>
                                            <td>
												<?php
													if($row_apin['status']	!= 1){
														$status	=	'غیرفعال';
													}else{
														$status	=	'فعال';
														}
														echo $status;
												?>
											</td>
                                            <td>
												<a href="api_edit.php?edit=<?= $row_apin['id']; ?>" data-popup="tooltip" title="ویرایش API"><i class='fa fa-edit' style="color: #FFF;"></i></a>
												<a href="api_list.php?delete=<?= $row_apin['id']; ?>" onClick="return confirm('آیا مطمئنید می خواهید  API را حذف کنید؟');" data-popup="tooltip" title="حذف API"><i class='fa fa-trash' style="color: #ff7f7f;"></i></a>
											</td>
                                        </tr>
									<?php } ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
<?php require('../../layouts/footer.php');?>