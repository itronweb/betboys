<?php 
   GLOBAL $content;
   $content['title']       = "اطلاعات احراز هویت";
   require('../../layouts/sidebar.php');
   
   ?>
      <div class="row">
         <div class="w-100">
            <?php
				$row_verify	= dbGetRow( 'verify', ['id' => @$_GET['id']] );
				$row_User	= dbGetRow( 'users', ['id'=>@$_GET['userid']] );

                $name = trim(sanitize_output($row_User['first_name'] . ' ' . $row_User['last_name']));
                $user_id = $row_User['id'];
               
               if ( !empty($row_verify) ) {
            ?>
            <div class="col-md-12">
				<table class="table table-hover panel panel-view">
				<?php
					if($row_verify['status']==0) {
						$status	=	 "غیر فعال";
						$css	=	"label label-danger";
					} else if ($row_verify['status']==1){
						$status	=	  "فعال";
						$css	=	"label label-success";
					}
				?>
				<div class="col-12" style="width:100%;">
					<div class="panel panel-<?= $css; ?>">
						<div class="panel-heading notif-view">
							<span class="<?php echo $css ;?>">وضعیت کاربر : <?= $status; ?></span>
						</div>
					</div>
				</div>
					<tbody class="panel-body">

						<tr>
							<td>زمان ثبت درخواست</td>
							<td>
								<?php $s= str_replace("-","/",$row_verify['created_at']);?>
								<?php $s=miladi_to_jalali($row_verify['created_at'],"-"," "); ?>
								<?= substr($s,8,2)." ".monthname(substr($s,5,2)).substr($s,0,4)." "."- ".substr($s,11,9); ?>
							</td>
						</tr>
						<tr>
							<td>کاربر</td>
							<td>
								<img src="<?= $avatar; ?>" class="avatar">
								<a href="../users/user_manage.php?edit=<?= $row_User['id']; ?>">
									<?= $row_User['first_name']. ' ' . $row_User['last_name']; ?>
								</a>
							</td>
						</tr>
						<tr>
							<td><center><img src="http://<?= $_SERVER['SERVER_NAME'] .'/upload/other/verify/'.$row_verify['bank']; ?>" width="350"></center></td>
							<td><center><img src="http://<?= $_SERVER['SERVER_NAME'] .'/upload/other/verify/'.$row_verify['melli'];?>" width="350"></center></td>
						</tr>
					</tbody>
				</table>
				<div class="row">
					<div class="col-3">
					   <a href="verify_status.php?id=<?php echo $row_verify['id']. '&userid=' . $row_User['id'] . '&status=1' ;?>">
						   <div class="panel panel-success">
							  <div class="panel-heading notif-view">
								 <span>تأیید مدارک</span>
							  </div>
						   </div>
					   </a>
					</div>
					<?php if ($row_verify['status']==2){ } else { ?>
						<div class="col-3">
							<a href="verify_status.php?id=<?php echo $row_verify['id']. '&userid=' . $row_User['id'] . '&status=2' ;?>">
							   <div class="panel panel-danger">
								  <div class="panel-heading notif-view">
									 <span>عدم تطابق</span>
								  </div>
							   </div>
							</a>
						</div>
					<?php } ?>
				</div>
            </div>
            <?php } else { ?>
            <div class="col-md-6" style="width:100%;">
               <div class="panel panel-danger" style="background-color:#3a3c45;">
                  <div class="panel-heading">
                     <span>
                     خطا در خواندن درخواست تأیید هویت با ایدی : <?= @$_GET['id']; ?>
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