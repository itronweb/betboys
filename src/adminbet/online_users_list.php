  <?php  
//require_once('checklogin.php'); 
// Load the common classes
require_once('includes/common/KT_common.php');

// Load the required classes

require_once('includes/nav/NAV.php');

// Make unified connection variable
$conn_cn = new KT_connection($cn, $database_cn);

// Defining List Recordset variable
mysqli_select_db($cn,$database_cn);

$today=date("Y-m-d");
$query_rs2 = "SELECT distinct ip,user FROM useronline ";
$rs2 = mysqli_query($cn,$query_rs2) or die(mysqli_error($cn));
$row_rs2 = mysqli_fetch_assoc($rs2);
$totalRows_rs2=mysqli_num_rows($rs2);

?>

                       <div class="col-lg-6">
                        <div class="panel panel-flat">
                            <div class="panel-heading">
                                <h5 class="panel-title">لیست کاربران آنلاین (<?php if($totalRows_rs2>0){echo $totalRows_rs2 ;} ?>)</h5>
                                <div class="heading-elements">
                                    <ul class="icons-list">
                                        <li><a data-action="collapse"></a></li>
                                        <li><a data-action="reload"></a></li>
                                        <li><a data-action="close"></a></li>
                                    </ul>
                                </div>
                            </div>
                            <div class="panel-body" style="padding: 0px;">
                                <div class="table-responsive">
                                    <table class="table text-nowrap">
                                        <thead>
                                        <tr>
                                            <th>کاربر</th>
                                            <th class="col-md-2">ایمیل</th>
											<th class="col-md-2">IP</th>
											<th class="col-md-2">وضعیت</th>
                                            <th class="text-center" style="width: 20px;"><i class="icon-arrow-down12"></i>عملیات</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                         <?php if ($totalRows_rs2 == 0) { // Show if recordset empty ?>
                                          <tr>
                                            <td colspan="10">اطلاعاتی یافت نشد .</td>
                                          </tr>
                                          <?php } // Show if recordset empty ?>
                                   <?php if ($totalRows_rs2 > 0) { // Show if recordset not empty ?>
                                          <?php do { 
	if(is_numeric($row_rs2['user'])){
											$query_rs11 = "SELECT * FROM users  where id=".$row_rs2['user'];$rs11 = mysqli_query($cn,$query_rs11) or die(mysqli_error($cn));$row_rs11 = mysqli_fetch_assoc($rs11);$totalRows_rs11=mysqli_num_rows($rs11);}
											?>
                                        <tr>
                                            <td>
                                              
                                                <div class="media-left">
                                                    <div class=""><?php if(is_numeric($row_rs2['user'])){echo "<a href='plugins/bets/bets_list.php?id=".encrypt($row_rs11['id'],session_id()."user")." target='_blank' '>".$row_rs11['id']." </a>";}else{echo "مهمان";}
														
														?>
                                                    
                                                   </div>
                                                    <div class="text-muted text-size-small">
                                                        <span class="status-mark border-blue position-left"></span>
                                                    <?php if(is_numeric($row_rs2['user'])){echo "<a href='plugins/bets/bets_list.php?id=".encrypt($row_rs11['id'],session_id()."user")." target='_blank' '>".$row_rs11['first_name'].' '.$row_rs11['last_name']." </a>";}else{echo "مهمان";}?>
                                                    </div>
                                                </div>
                                            </td>
                                            <td><span class="text-muted"><?php if(is_numeric($row_rs2['user'])){echo $row_rs11['email'];} ?></span></td>
									 <td><span class="text-muted"><a href="https://www.ip2location.com/<?php echo $row_rs2['ip']; ?>"><?php echo $row_rs2['ip']; ?></a></span></td>
                                            
                                            <td>
												<?php if(is_numeric($row_rs2['user'])){ ?>
                                            	 <li data-popup="tooltip" title="تغییر وضعیت" class="checkbox checkbox-switchery switchery-xs disp"><a href="change_status.php?table=<?php echo encrypt('users',session_id()."sts"); ?>&field=<?php echo encrypt('status',session_id()."sts"); ?>&id=<?php echo encrypt($row_rs11['id'],session_id()."sts"); ?>"><input type="checkbox" class="switchery" <?php if($row_rs11['status'] ==1) echo 'checked'; ?>></a></li>
												<?php } ?>
                                            </td>
                                            <td class="text-center">
												<?php if(is_numeric($row_rs2['user'])){ ?>
                                                <ul class="icons-list">
                                                    <li class="dropdown">
                                                        <a href="#" class="dropdown-toggle" data-toggle="dropdown"><i class="icon-menu7"></i></a>
                                                        <ul class="dropdown-menu dropdown-menu-right">
                                                           <?php if (in_array('users_edit', $accccesslist)) {?>
															<li><a class="text-primary-600" href="plugins/users/users_edit.php?id=<?php echo encrypt($row_rs11['id'],session_id()."user"); ?>"><i class="icon-pencil7"></i> ویرایش</a></li>
															<?php }?>

															<?php if (in_array('users_delete', $accccesslist)) {?>
															<li><a class="text-danger-600" href="plugins/users/users_delete.php?id=<?php echo encrypt($row_rs11['id'],session_id()."user"); ?>" onClick="return confirm('آیا مطمئنید می خواهید اطلاعات را حذف کنید ؟');" ><i class="icon-trash"></i> حذف</a></li>
															<?php }?>
                                                          
                                                        </ul>
                                                    </li>
                                                </ul>
												<?php } ?>
                                            </td>
                                        </tr>
                                        <?php } while ($row_rs2 = mysqli_fetch_assoc($rs2)); ?>
                                          <?php } // Show if recordset not empty ?>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
															