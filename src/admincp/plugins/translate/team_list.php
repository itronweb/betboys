<?php 
   GLOBAL $content;
   $content['title']       = "مترجم خودکار تیم ها";
   require('../../layouts/sidebar.php');
	if ( isset($_GET['delete']) && !empty($_GET['delete']) ){
		$del_ticket = mysqli_query($cn,"DELETE FROM `teams` WHERE `id` = '".intval($_GET['delete'])."' LIMIT 1");
	if ( $del_ticket )
	{
		$status = '<div class="panel panel-success"><div class="panel-heading">تیم با موفقیت حذف شد.</div></div>';
	}
	else
	{
		$status = '<div class="panel panel-danger"><div class="panel-heading">مشکلی در حذف تیم به وجود آمده است.</div></div>';
	}
	}
   ?>
   <?= @$status; ?>
				<div class="row">
                        <div class="col-md-12">
                            <div class="panel table-responsive table-commerce panel-view">
                             <a href="team_edit.php"><button class="btn btn-primary">ثبت ترجمه جدید</button></a>
                                <table id="basic-datatables" class="table table-striped table-hover">
                                    <thead>
                                        <tr>
                                            <th style="width:120px">
                                                <strong>ایدی تیم</strong>
                                            </th>
                                            <th>
                                                <strong>نام انگلیسی تیم</strong>
                                            </th>
                                            <th>
                                                <strong>نام فارسی تیم</strong>
                                            </th>
                                            <th>
                                                <strong>تغییرات</strong>
                                            </th>
                                        </tr>
                                    </thead>
                                    <tbody>
									<?php
										$row_team = dbRead('teams');
										usort( $row_team, function($a, $b) { return $a['id'] == $b['id'] ? 0 : $b['id'] - $a['id']; } );
										foreach ( $row_team as $row_teamn ) { 
									?>
                                        <tr>
                                            <td><?php echo sanitize($row_teamn['teams_id']); ?></td>
                                            <td><?php echo $row_teamn['teams_name_en']; ?></td>
                                            <td><?php echo $row_teamn['teams_name_fa']; ?></td>
                                            <td>
												<a href="team_edit.php?edit=<?= $row_teamn['id']; ?>" data-popup="tooltip" title="ویرایش تیم"><i class='fa fa-edit' style="color: #FFF;"></i></a>
												<a href="team_list.php?delete=<?= $row_teamn['id']; ?>" onClick="return confirm('آیا مطمئنید می خواهید  ترجمه را حذف کنید؟');" data-popup="tooltip" title="حذف تیم"><i class='fa fa-trash' style="color: #ff7f7f;"></i></a>
											</td>
                                        </tr>
									<?php } ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
<?php require('../../layouts/footer.php');?>