<?php 
   GLOBAL $content;
   $content['title']       = "تنظیمات سایت";
   require('../../layouts/sidebar.php');
   ?>
	<?php echo @$status; ?>
				<div class="row">
                        <div class="col-md-12">
                            <div class="panel table-responsive table-commerce panel-view">
                                <table id="basic-datatables" class="table table-striped table-hover">
                                    <thead>
                                        <tr>
                                            <th style="width:120px">
                                                <strong>کد کانفیگ</strong>
                                            </th>
                                            <th>
                                                <strong>عنوان</strong>
                                            </th>
                                            <th style="width:100px">
                                                <strong>مقدار</strong>
                                            </th>
                                            <th>
                                                <strong>اخرین تغییر</strong>
                                            </th>
                                            <th>
                                                <strong>تغییرات</strong>
                                            </th>
                                        </tr>
                                    </thead>
                                    <tbody>
									<?php
										$row_config = dbRead('settings');
										usort( $row_config, function($a, $b) { return $a['id'] == $b['id'] ? 0 : $b['id'] - $a['id']; } );
										foreach ( $row_config as $row_confign ) { 
									?>
									
                                        <tr>
                                            <td><?php echo sanitize($row_confign['id']); ?></td>
                                            <td>
												<?= sanitize_output($row_confign['name']) ?>
											</td>
                                            <td>
												<?= sanitize_output($row_confign['value']) ?>
											</td>
                                            <td>
											  <?php $s= str_replace("-","/",$row_confign['updated_at']);?>
											  <?php $s=miladi_to_jalali($row_confign['updated_at'],"-"," "); ?>
											  <?= substr($s,8,2)." ".monthname(substr($s,5,2)). ' ' .substr($s,0,4). ' ' .substr($s,11,9); ?>
											</td>
                                            <td>
												<a href="settings_edit.php?edit=<?php echo sanitize($row_confign['id']); ?>" data-popup="tooltip" title="ویرایش کانفیگ"><i class='fa fa-edit' style="color: #FFF;"></i></a>
											</td>
                                        </tr>
										<?php } ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
<?php require('../../layouts/footer.php');?>