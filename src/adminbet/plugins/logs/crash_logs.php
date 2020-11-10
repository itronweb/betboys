<?php 
   GLOBAL $content;
   $content['title']       = "وضعیت بازی انفجار";
   require('../../layouts/sidebar.php');
   ?>
<style>

</style>
                <div class="content-wrapper container">
				<?php
					if($_GET['id'] !=""){ 
						$row_user = dbRead('users',  ['id'=>decrypt( $_GET[ 'id' ], session_id() . "user" )], 1) ;
						foreach ( array_slice($row_user, 0, 10) as $row_usern ) {
							$row_insta = @getInsta( $row_usern['instagram'] );
							$avatar    = !empty($row_insta['avatar']) ? $row_insta['avatar'] : 'https://sezarco.ir/sezar-cp/sezar-upload/sezar-avatars/default-avatar.png';
					?>
   <div class="panel panel-flat">  
      <div class="panel-body">
         <h3> 
            <i class="icon-chevron-left text-success"></i> آمار کلی اکانت کاربر <img class="avatar" src="<?= $avatar; ?>"> <?= $row_usern['first_name']. ' ' . $row_usern['last_name']?> :
         </h3>
         <table class="table table-hover">
            <tbody>
               <tr>
                  <td>موجودی حساب‌</td>
                  <td>
                     <?=numberformat($row_usern['cash']);?> تومان
                  </td>
               </tr>
            </tbody>
         </table>
      </div>
   </div>
   <?php } ?>
   <?php } ?>
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
                                                <strong>زمان ثبت شرط</strong>
                                            </th>
                                            <th>
                                                <strong>مبلغ</strong>
                                            </th>
                                            <th>
                                                <strong>ضریب</strong>
                                            </th>
                                            <th>
                                                <strong>مبلغ برد</strong>
                                            </th>
                                        </tr>
                                    </thead>
                                    <tbody>
									<?php
										if($_GET['id']){
											$row_bet = dbRead('bets', ['user_id'=>decrypt( $_GET[ 'id' ], session_id() . "user" )]) ;
										}
										else{
											$row_bet = dbRead('bets');
										}
										usort( $row_bet, function($a, $b) { return $a['id'] == $b['id'] ? 0 : $b['id'] - $a['id']; } );
										foreach ( array_slice($row_bet, 0, 10) as $row_betn ) { 
									?>
                                        <tr>
                                            <td><?php echo sanitize($row_betn['id']); ?></td>
                                            <td>
												<?php
													$row_usersender = dbGetRow('users', ['id'=>$row_betn['user_id']]);		
													$row_insta = @getInsta( $row_usersender['instagram'] );
													$avatar    = !empty($row_insta['avatar']) ? $row_insta['avatar'] : 'https://sezarco.ir/sezar-cp/sezar-upload/sezar-avatars/default-avatar.png';
												?>
												<a href="../users/user_manage.php?edit=<?= $row_betn['user_id']; ?>"><img src="<?= $avatar; ?>" class="avatar"> <?= sanitize_output($row_usersender['first_name']) . ' ' . sanitize_output($row_usersender['last_name']); ?></a>
											</td>
                                            <td>
											  <?php $s= str_replace("-","/",$row_betn['created_at']);?>
											  <?php $s=miladi_to_jalali($row_betn['created_at'],"-"," "); ?>
											  <?= substr($s,8,2)." ".monthname(substr($s,5,2)). ' ' .substr($s,0,4). ' ' .substr($s,11,9); ?>
											</td>
                                            <td><?= numberformat($row_betn['stake']); ?> تومان</td>
                                            <td><?= $row_betn['effective_odd']; ?></td>
                                            <td>
											<?php
												$win_stake	=	$row_betn['stake'] * $row_betn['effective_odd'];
												$win_stake	=	numberformat($win_stake);
												if ($row_betn['status'] == 0){
													$status	=	'نامعلوم';
													$finally_stake = $status;
												}
												if ($row_betn['status'] == 1){
													$status	=	'برد';
													$finally_stake = $win_stake;
												}
												if ($row_betn['status'] == 2){
													$status	=	'باخت';
													$finally_stake = $row_betn['stake'];
												}
												if ($row_betn['status'] == 3){
													$status	=	'استرداد';
													$finally_stake = $row_betn['stake'];
												}
												if ($row_betn['status'] == 4){
													$status	=	'نیم برد';
													$finally_stake = (($row_betn['effective_odd']/2)+0.50)*$row_betn['stake'];
												}
												if ($row_betn['status'] == 5){
													$status	=	'نیم باخت';
													$finally_stake = $row_betn['stake']/2;
												}
												?>
												<?php if($row_betn['status'] != 0 ){ ?>
												<li>مبلغ : <?= $finally_stake; ?> تومان</li>
												<?php } ?>
												<li>وضعیت : <?= $status; ?></li>
												<a href="bets_cancel.php?work=5&id=<?php echo encrypt($row_betn['id'],session_id()."bets");?>" onClick="return confirm('آیا مطمئنید می خواهید  شرط را نیم باخت کنید ؟');" data-popup="tooltip" title="نیم باخت"><i class='text-danger fa fa-circle-thin'></i></a>
												<a href="bets_cancel.php?work=2&id=<?php echo encrypt($row_betn['id'],session_id()."bets");?>" onClick="return confirm('آیا مطمئنید می خواهید  شرط را باخت کنید ؟');" data-popup="tooltip" title="باخت"><i class='text-danger fa fa-circle'></i></a>
												<a href="bets_cancel.php?work=1&id=<?php echo encrypt($row_betn['id'],session_id()."bets");?>" onClick="return confirm('آیا مطمئنید می خواهید  شرط را برد کنید ؟');" data-popup="tooltip" title="برد"><i class='text-success fa fa-star'></i></a>
												<a href="bets_cancel.php?work=4&id=<?php echo encrypt($row_betn['id'],session_id()."bets");?>" onClick="return confirm('آیا مطمئنید می خواهید  شرط را نیم برد  کنید ؟');" data-popup="tooltip" title="نیم برد"><i class='text-success fa fa-star-half-o'></i></a>
												<a href="bets_cancel.php?work=3&id=<?php echo encrypt($row_betn['id'],session_id()."bets");?>" onClick="return confirm('آیا مطمئنید می خواهید شرط را استرداد  کنید ؟');" data-popup="tooltip" title="استرداد"><i class='text-primary icon-drawer-out'></i></a>
											</td>
                                        </tr>
										<?php } ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
               </div>
<?php require('../../layouts/footer.php');?>