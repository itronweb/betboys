<?php 
   GLOBAL $content;
   $content['title']       = "تنظیمات اسلایدر ها";
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
                                                <strong>ایدی</strong>
                                            </th>
                                            <th>
                                                <strong>موقعیت اسلایدر</strong>
                                            </th>
                                            <th>
                                                <strong>متن عکس</strong>
                                            </th>
                                            <th>
                                                <strong>تغییرات</strong>
                                            </th>
                                        </tr>
                                    </thead>
                                    <tbody>
									<?php
										$row_slider = dbRead('slideshow');
										usort( $row_slider, function($a, $b) { return $a['id'] == $b['id'] ? 0 : $b['id'] - $a['id']; } );
										foreach ( $row_slider as $row_slidern ) { 
									?>
									
                                        <tr>
                                            <td><?php echo sanitize($row_slidern['id']); ?></td>
                                            <td>
												<?= sanitize_output($row_slidern['description']) ?>
											</td>
                                            <td>
												<?= sanitize_output($row_slidern['title']) ?>
											</td>
                                            <td>
												<a href="slider_manage.php?edit=<?php echo sanitize($row_slidern['id']); ?>" data-popup="tooltip" title="ویرایش اسلایدر"><i class='fa fa-edit'></i></a>
											</td>
                                        </tr>
										<?php } ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
<?php require('../../layouts/footer.php');?>