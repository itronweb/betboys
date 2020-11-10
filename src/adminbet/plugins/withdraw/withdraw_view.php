<?php 
   GLOBAL $content;
   $content['title']       = "نمایش واریزی کاربر";
   require('../../layouts/sidebar.php');
   
   ?>
      <div class="row">
         <div>
            <?php
               $row_withdraw    = dbGetRow( 'withdraw', ['id' => @$_GET['id']] );
               $row_User      = dbGetRow( 'users', ['id'=>@$row_withdraw['user_id']] );
               
               $row_insta = @getInsta( $row_User['instagram'] );
               $avatar    = !empty($row_insta['avatar']) ? $row_insta['avatar'] : '/sezar-cp/sezar-upload/sezar-avatars/default-avatar.png';
               $name = trim(sanitize_output($row_User['first'] . ' ' . $row_User['last']));
               $user_id = $row_User['id'];
               
               if ( !empty($row_withdraw) ) {
               ?>
            <div class="col-md-12">
				<table class="table table-hover panel panel-view">
				<?php
					if($row_withdraw['status']==0) {
						$status	=	 "پرداخت نشده";
						$css	=	"danger";
					}
				   else if ($row_withdraw['status']==1){
						$status	=	  "پرداخت شده";
						$css	=	"success";
					}
				   else if ($row_withdraw['status']==2){
						$status	=	 "صف پرداخت";
						$css	=	"violet";
					}
				   else if ($row_withdraw['status']==3){
						$status	=	 "لغو درخواست کاربر";
						$css	=	"orange";
				   }
				?>
				<div class="col-md-6" style="width:100%;">
				   <div class="panel panel-<?= $css; ?>">
					  <div class="panel-heading notif-view">
						 <span>
						 وضعیت درخواست واریزی کاربر : <?= $status; ?>
						 </span>
					  </div>
				   </div>
				</div>
					<tbody class="panel-body">
						<tr>
							<td>زمان ثبت درخواست</td>
							<td>
								<?php $s= str_replace("-","/",$row_withdraw['created_at']);?>
								<?php $s=miladi_to_jalali($row_withdraw['created_at'],"-"," "); ?>
								<?= substr($s,8,2)." ".monthname(substr($s,5,2)).substr($s,0,4)." "."- ".substr($s,11,9); ?>
							</td>
						</tr>
						<tr>
							<td>کاربر</td>
							<td><img src="<?= $avatar; ?>" class="avatar"><a href="../users/user_manage.php?edit=<?= $row_User['id']; ?>"> <?= $row_User['first_name']. ' ' . $row_User['last_name']; ?></a></td>
						</tr>
						<tr>
							<td>نام صاحب حساب</td>
							<td> <?= $row_withdraw['account_holder']; ?></td>
						</tr>
						<tr>
							<td>مبلغ</td>
							<td> <?= numberformat($row_withdraw['amount']) . ' تومان'; ?></td>
						</tr>
						<tr>
							<td>نام بانک</td>
							<td> <?= $row_withdraw['bank_name']; ?></td>
						</tr>
						<tr>
							<td>شماره حساب</td>
							<td> <?= $row_withdraw['account_number']; ?></td>
						</tr>
						<tr>
							<td>شماره کارت</td>
							<td> <?= $row_withdraw['card_no']; ?></td>
						</tr>
						<tr>
							<td>شماره شبا</td>
							<td> <?= $row_withdraw['sheba']; ?></td>
						</tr>
						<tr>
							<td>شماره وب مانی</td>
							<td>
							<?php
								if($row_withdraw['webmoney'] == ""){
										echo 'حساب وب مانی تعریف نشده است';
									}else{
										echo $row_withdraw['webmoney'];
										};
							?>
							</td>
						</tr>
					</tbody>
				</table>
				<div class="col-md-6">
					<a href="withdraw_status.php?status=0&id=<?= $row_withdraw['id']; ?>">
					   <div class="panel panel-danger">
						  <div class="panel-heading notif-view">
							 <span>
							 ثبت به عنوان پرداخت نشده
							 </span>
						  </div>
					   </div>
				   </a>
				</div>
				<div class="col-md-6">
				   <a href="withdraw_status.php?status=1&id=<?= $row_withdraw['id']; ?> ">
					   <div class="panel panel-success">
						  <div class="panel-heading notif-view">
							 <span>
							 ثبت به عنوان پرداخت شده
							 </span>
						  </div>
					   </div>
				   </a>
				</div>
            </div>
            <?php } else { ?>
            <div class="col-md-6" style="width:100%;">
               <div class="panel panel-danger" style="background-color:#3a3c45;">
                  <div class="panel-heading">
                     <span>
                     خطا در خواندن درخواست برداشت با ایدی : <?= @$_GET['id']; ?>
                     </span>
                  </div>
                  <div class="panel-body">
                     <h3>متاسفانه درخواستی با ایدی مورد نظر شما وجود ندارد</h3>
                  </div>
               </div>
            </div>
            <?php } ?>
         </div>
      </div>
<?php require('../../layouts/footer.php');?>