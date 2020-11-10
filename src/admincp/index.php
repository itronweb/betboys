<?php 
	GLOBAL $content;
	$content['title']       = "میزکار";
	include('layouts/sidebar.php');
?>
		<!-- end .page title-->
        <?php 
			$cnt=30;
			for($i=0;$i<=$cnt;$i++){
				$cdate=jdate('Y/m/d',strtotime('-'.$i.' days')); 

				mysqli_select_db($cn,$database_cn);
				$query_Recordsetcntr = "SELECT * FROM dailycounter WHERE date='$cdate'";
				$Recordsetcntr = mysqli_query($cn,$query_Recordsetcntr) or die(mysqli_error($cn));
				$row_Recordsetcntr = mysqli_fetch_assoc($Recordsetcntr);
				$totalRows_Recordsetcntr = mysqli_num_rows($Recordsetcntr);
				
				$pagecount=$row_Recordsetcntr['visitcount']; 
				
				if($pagecount=='') $pagecount=0;
				
				$ddat[$i]=$cdate;
				$dpcn[$i]=$pagecount;
				
				if($i<=7)
				  $wpagecount+=$pagecount;
				
				if($i<=30)
				  $mpagecount+=$pagecount;

				if($i==7)
				  $wedate=$cdate;
				else if($i==30)
				  $medate=$cdate;
			}
		?>    
		<div class="row">
		<!--col-->
		 <div class="col-sm-6 col-md-3 margin-b-30">
				<div class="tile">
					<div class="tile-title clearfix">
						<?= substr($ddat[0],8,2). ' ' .monthname(substr($ddat[0],5,2)).' '.substr($ddat[0],0,4); ?>
					</div>
					<!--.tile-title-->
					<div class="tile-body clearfix">
						<i class="fa fa-file-text"></i>
						<h4 class="pull-right">
							<?php echo $dpcn[0];?>
						</h4>
					</div>
					<!--.tile-body-->
					<div class="tile-footer">
						 <i>بازدید کاربران در امروز</i>
					</div>
					<!--.tile footer-->
				</div>
				<!-- .tile-->
			</div>
		<!--end .col-->
		<!--col-->
		 <div class="col-sm-6 col-md-3 margin-b-30">
				<div class="tile">
					<div class="tile-title clearfix">
						<?= substr($ddat[1],8,2). ' ' .monthname(substr($ddat[1],5,2)).' '.substr($ddat[1],0,4); ?>
					</div>
					<!--.tile-title-->
					<div class="tile-body clearfix">
						<i class="fa fa-file-text"></i>
						<h4 class="pull-right">
							<?php echo $dpcn[1];?>
						</h4>
					</div>
					<!--.tile-body-->
					<div class="tile-footer">
						 <i>بازدید کاربران در روز گذشته</i>
					</div>
					<!--.tile footer-->
				</div>
				<!-- .tile-->
			</div>
		<!--end .col-->
		<!--col-->
		 <div class="col-sm-6 col-md-3 margin-b-30">
				<div class="tile">
					<div class="tile-title clearfix">
						از تاریخ <?= substr($wedate,8,2).' '.monthname(substr($wedate,5,2)). ' ' .substr($wedate,0,4); ?>
					</div>
					<!--.tile-title-->
					<div class="tile-body clearfix">
						<i class="fa fa-file-text"></i>
						<h4 class="pull-right">
							<?php echo $wpagecount;?>
						</h4>
					</div>
					<!--.tile-body-->
					<div class="tile-footer">
						 <i>بازدید کاربران در 7 روز گذشته</i> 
					</div>
					<!--.tile footer-->
				</div>
				<!-- .tile-->
			</div>
		<!--end .col-->
		<!--col-->
		 <div class="col-sm-6 col-md-3 margin-b-30">
				<div class="tile">
					<div class="tile-title clearfix">
						از تاریخ <?= substr($medate,8,2).' '.monthname(substr($medate,5,2)). ' ' .substr($medate,0,4); ?>
					</div>
					<!--.tile-title-->
					<div class="tile-body clearfix">
						<i class="fa fa-file-text"></i>
						<h4 class="pull-right">
							<?php echo $mpagecount;?>
						</h4>
					</div>
					<!--.tile-body-->
					<div class="tile-footer">
						 <i>بازدید کاربران در 15 روز گذشته</i>
					</div>
					<!--.tile footer-->
				</div>
				<!-- .tile-->
			</div>
		<!--end .col-->
		</div>
		
		<!--Rows 2-->
		<div class="row">
		 <div class="col-md-4">
                            <div class="panel panel-card recent-activites">
                                <!-- Start .panel -->
                                <div class="panel-heading">
                                    <h4 class="panel-title"> آخرین کاربران عضو شده</h4>
                                    <div class="panel-actions">
                                        <a href="#" class="panel-action panel-action-toggle" data-panel-toggle=""></a>
                                        <a href="#" class="panel-action panel-action-dismiss" data-panel-dismiss=""></a>
                                    </div>
                                </div>
                                <div class="panel-body pad-0">
                                    <ul class="list-group">
										<?php
											//$today=date("Y-m-d");
											$row_NewUser = dbRead('users') ;
											usort( $row_NewUser, function($a, $b) { return $a['id'] == $b['id'] ? 0 : $b['id'] - $a['id']; } );
											foreach ( array_slice($row_NewUser, 0, 10) as $row_NewUsern ) {
												$row_insta = @getInsta( $row_NewUsern['instagram'] );
												$avatar    = !empty($row_insta['avatar']) ? $row_insta['avatar'] : 'https://sezarco.ir/sezar-cp/sezar-upload/sezar-avatars/default-avatar.png';
										?>
										<li class="list-group-item">
										
                                            <a href="plugins/users/user_manage.php?edit=<?= $row_NewUsern['id']; ?>"><img src="<?= $avatar; ?>" class="avatar"> <?= sanitize_output($row_NewUsern['first_name']). ' ' .sanitize_output($row_NewUsern['last_name']); ?></a>
                                        </li>
										<?php } ?>
                                    </ul>
                                </div>
                            </div><!-- End .panel -->                       
                        </div>
						
						<!-- Rows ticket --> 
						<div class="col-md-8">
                            <div class="panel panel-card recent-activites">
                                <!-- Start .panel -->
                                <div class="panel-heading">
                                    <h4 class="panel-title" data-panel-toggle=""> تیکت های جدید</h4>
                                    <div class="panel-actions">
                                        <a href="#" class="panel-action panel-action-toggle" data-panel-toggle=""></a>
                                        <a href="#" class="panel-action panel-action-dismiss" data-panel-dismiss=""></a>
                                    </div>
                                </div>
                                <div class="panel-body">
                                    <div class="table-responsive" style="overflow:hidden;">
                                        <table id="basic-datatables" class="table table-striped table-hover">
                                            <thead>
                                                <tr>
                                                    <th>شماره</th>
                                                    <th>موضوع</th>
                                                    <th>کاربر</th>
                                                    <th>وضعیت</th>
                                                </tr>
                                            </thead>
                                            <tbody>
												<?php
													mysqli_query($connect, "SET NAMES 'utf8'" );
													$Query = dbRead( 'tickets' ,    ['status'=>'0']);
													usort( $Query, function($a, $b) { return $a['id'] == $b['id'] ? 0 : $b['id'] - $a['id']; } );
													foreach ( array_slice($Query, 0, 8) as $row_tickets ) {
												?>
                                                <tr>
                                                    <td><?php echo sanitize_output($row_tickets['id']); ?></td>
														<td>
															<a href="plugins/tickets/tickets_view.php?id=<?= $row_tickets['id']; ?>" >
																<?= sanitize_output($row_tickets['subject']); ?>
															</a>
														</td>
													<td>
														<?php $row_usersender = dbGetRow('users', ['id'=>$row_tickets['user_id']]); ?>
														<?= sanitize_output($row_usersender['first_name']) . ' ' . sanitize_output($row_usersender['last_name']); ?>
													</td>
                                                    <td>
														<?php 
															$label_color = '';

															if ( stripos($row_tickets['is_active'], 'در انتظار پاسخ') !== false ) $label_color = 'btn-primary';
															if ( stripos($row_tickets['is_active'], 'منتظر پاسخ') !== false ) $label_color = 'label-info';
															if ( stripos($row_tickets['is_active'], 'در دست بررسی') !== false ) $label_color = 'label-warning';
															if ( stripos($row_tickets['is_active'], 'پاسخ داده شد') !== false ) $label_color = 'label-success';
															if ( stripos($row_tickets['is_active'], 'باز شده') !== false ) $label_color = 'label-warning';
															if ( stripos($row_tickets['is_active'], 'بسته شده') !== false ) $label_color = 'label-inverse';
														?>
														<span class="label <?= $label_color ?>">
															<?php echo sanitize_output($row_tickets['is_active']); ?>
														</span>
													</td>
                                                </tr> 
												<?php } ?>												
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
					</div>
<?php include('layouts/footer.php');?>