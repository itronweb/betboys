<?php 
   GLOBAL $content;
   $content['title']       = "لیست صفحات";
   require('../../layouts/sidebar.php');
	if ( isset($_GET['delete']) && !empty($_GET['delete']) ){
		$del_page = mysqli_query($cn,"DELETE FROM `content_pages` WHERE `id` = '".intval($_GET['delete'])."' LIMIT 1");
	if ( $del_page )
	{
		$status = '<div class="panel panel-success"><div class="panel-heading">صفحه با موفقیت حذف شد.</div></div>';
	}
	else
	{
		$status = '<div class="panel panel-danger"><div class="panel-heading">مشکلی در حذف صفحه به وجود آمده است.</div></div>';
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
                                            <th style="width:80px">
                                                <strong>ایدی</strong>
                                            </th>
                                            <th>
                                                <strong>نام</strong>
                                            </th>
                                            <th>
                                                <strong>لینک کوتاه</strong>
                                            </th>
                                            <th>
                                                <strong>آخرین ویرایش</strong>
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
										$row_page = dbRead('content_pages');
										usort( $row_page, function($a, $b) { return $a['id'] == $b['id'] ? 0 : $b['id'] - $a['id']; } );
										foreach ( $row_page as $row_pagen ) { 
									?>
									
                                        <tr>
                                            <td><?php echo sanitize($row_pagen['id']); ?></td>
                                            <td>
												<?= sanitize_output($row_pagen['name']) ?>
											</td>
                                            <td>
												<?= sanitize_output($row_pagen['slug']) ?>
											</td>
                                            <td>
											<?php
												if (!empty($row_pagen['updated_at'])){
													$s= str_replace("-","/",$row_pagen['updated_at']);
													$s=miladi_to_jalali($row_pagen['updated_at'],"-"," ");
													echo substr($s,8,2)." ".monthname(substr($s,5,2)).substr($s,0,4)." "."- ".substr($s,11,9);
												}else{
													echo'تعریف نشده';
												}
											?>
											</td>
											<?php
												if ($row_pagen['status'] != 1){	
													$status	=	'غیرفعال';
												}else{
													$status	=	'فعال';
												}
												?>
                                            <td>
												<a style="color:#FFF;" href="../../change_status.php?table=<?php echo encrypt('content_pages',session_id()."sts"); ?>&field=<?php echo encrypt('status',session_id()."sts"); ?>&id=<?php echo encrypt($row_pagen['id'],session_id()."sts"); ?>">
													<?= $status; ?>
												</a>
											</td>
                                            <td>
												<a href="pages_edit.php?edit=<?php echo sanitize($row_pagen['id']); ?>" data-popup="tooltip" title="ویرایش صفحه"><i class='fa fa-edit' style="color: #FFF;"></i></a>
												<a href="pages_list.php?delete=<?php echo sanitize($row_pagen['id']); ?>" onClick="return confirm('آیا مطمئنید می خواهید اطلاعات را حذف کنید ؟');" data-popup="tooltip" title="حذف صفحه"><i class='text-danger fa fa-trash'></i></a>
												<a href="../../../<?php echo $row_pagen['slug']; ?>" target="_blank" data-popup="tooltip" title="نمایش صفحه"><i class='text-success fa fa-search'></i></a>
											</td>
                                        </tr>
										<?php } ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
<?php require('../../layouts/footer.php');?>