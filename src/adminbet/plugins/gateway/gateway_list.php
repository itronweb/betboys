<?php 
   GLOBAL $content;
   $content['title']       = "درگاه های پرداخت";
   require('../../layouts/sidebar.php');
	if ( isset($_GET['delete']) && !empty($_GET['delete']) ){
		$del_page = mysqli_query($cn,"DELETE FROM `gateway` WHERE `id` = '".intval($_GET['delete'])."' LIMIT 1");
	if ( $del_page )
	{
		$status = '<div class="panel panel-success"><div class="panel-heading">درگاه با موفقیت حذف شد.</div></div>';
	}
	else
	{
		$status = '<div class="panel panel-danger"><div class="panel-heading">مشکلی در حذف درگاه به وجود آمده است.</div></div>';
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
                                                <strong>نام درگاه</strong>
                                            </th>
                                            <th>
                                                <strong>نحوه پرداخت</strong>
                                            </th>
                                            <th>
                                                <strong>ترتیب</strong>
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
										$row_gateway = dbRead('gateway');
										usort( $row_gateway, function($a, $b) { return $a['id'] == $b['id'] ? 0 : $b['id'] - $a['id']; } );
										foreach ( $row_gateway as $row_gatewayn ) { 
									?>
									
                                        <tr>
                                            <td><?php echo sanitize($row_gatewayn['id']); ?></td>
                                            <td>
												<?= sanitize_output($row_gatewayn['name']) ?>
											</td>
                                            <td>
												<?php $row_paymethod= dbGetRow('paymethod', ['id'=>$row_gatewayn['paymethodid']]); ?>
												<?php echo sanitize_output($row_paymethod['name']); ?>
											</td>
                                            <td>
												<?= sanitize_output($row_gatewayn['sort']) ?>
											</td>
											<?php
												if ($row_gatewayn['status'] != 1){	
													$status	=	'غیرفعال';
												}else{
													$status	=	'فعال';
												}
												?>
                                            <td>
												<a style="color:#FFF;" href="../../change_status.php?table=<?php echo encrypt('gateway',session_id()."sts"); ?>&field=<?php echo encrypt('status',session_id()."sts"); ?>&id=<?php echo encrypt($row_gatewayn['id'],session_id()."sts"); ?>">
													<?= $status; ?>
												</a>
											</td>
                                            <td>
												<a href="gateway_manage.php?edit=<?php echo sanitize($row_gatewayn['id']); ?>" data-popup="tooltip" title="ویرایش درگاه"><i class='fa fa-edit' style="color: #FFF;"></i></a>
												<?php/*<a href="gateway_list.php?delete=<?php echo sanitize($row_gatewayn['id']); ?>" onClick="return confirm('آیا مطمئنید می خواهید اطلاعات را حذف کنید ؟');" data-popup="tooltip" title="حذف درگاه"><i class='text-danger fa fa-trash'></i></a>*/?>
											</td>
                                        </tr>
										<?php } ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
<?php require('../../layouts/footer.php');?>