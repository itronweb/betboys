<?php 
    GLOBAL $content;
    $content['title']       = "درخواست های تأیید هویت";
    require('../../layouts/sidebar.php');

    // Make unified connection variable
    $conn_cn = new KT_connection($cn, $database_cn);
    $id= $_GET['id'];
    $cat=$_GET['cat'];
    // Filter
    $tfi_listrsverify = new TFI_TableFilter($conn_cn, "tfi_listrsverify");
    $tfi_listrsverify->addColumn("verify.id", "STRING_TYPE", "id", "%");
    $tfi_listrsverify->addColumn("verify.status", "NUMERIC_TYPE", "status", "=");
    $tfi_listrsverify->addColumn("verify.created_at", "STRING_TYPE", "created_at", "%");
    if($_GET['id'] ==""){
        $tfi_listrsverify->addColumn("users.first_name", "STRING_TYPE", "fname", "%");
        $tfi_listrsverify->addColumn("users.last_name", "STRING_TYPE", "lname", "%");
    }
    $tfi_listrsverify->Execute();

    // Sorter
    $tso_listrsverify = new TSO_TableSorter("rs1", "tso_listrsverify");

    if($_GET['id'] == ""){
        $tso_listrsverify->addColumn("verify.user_id");
    }
    $tso_listrsverify->addColumn("verify.id");
    $tso_listrsverify->addColumn("verify.created_at");
    $tso_listrsverify->addColumn("status");
    $tso_listrsverify->setDefault("verify.id DESC");
    $tso_listrsverify->Execute();

    // Change Show Per Page
    if(isset($_POST['verify_pagenum'])){
        @$_SESSION['verify_pagenum']=GetSQLValueString($_POST['verify_pagenum'], "int");
    } else if(KT_escapeAttribute(@$_SESSION['verify_pagenum'])==''){
        @$_SESSION['verify_pagenum']=10;
    }

    // Navigation
    $nav_listrsverify = new NAV_Regular("nav_listrsverify", "rs1", "../", $_SERVER['PHP_SELF'], KT_escapeAttribute(@$_SESSION['verify_pagenum']));

    //NeXTenesio3 Special List Recordset
    $maxRows_rg1 = $_SESSION['max_rows_nav_listrsverify'];
    $verify_page_num = 0;
    if (isset($_GET['verify_page_num'])) {
        $verify_page_num = $_GET['verify_page_num'];
    }
    $startRow_rg1 = $verify_page_num * $maxRows_rg1;

    // Defining List Recordset variable
    $NXTFilter_rs1 = "1=1";
    if (isset($_SESSION['filter_tfi_listrsverify'])) {
        $NXTFilter_rs1 = $_SESSION['filter_tfi_listrsverify'];
    }
    // Defining List Recordset variable
    $NXTSort_rs1 = "id DESC";
    if (isset($_SESSION['sorter_tso_listrsverify'])) {
        $NXTSort_rs1 = $_SESSION['sorter_tso_listrsverify'];
    }
    if($_GET['id'] !=""){
        $shartt=sprintf(" user_id = %s and ", GetSQLValueString($id, "int"));
    }
    mysqli_select_db($cn,$database_cn);
    $query_rs1 = "SELECT verify.*,users.first_name,users.last_name,users.email  FROM `verify` left join users on verify.user_id=users.id WHERE $shartt   {$NXTFilter_rs1}  ORDER BY  {$NXTSort_rs1} ";

    $query_limit_rs1 = sprintf("%s LIMIT %d, %d", $query_rs1, $startRow_rg1, $maxRows_rg1);
    $rs1 = mysqli_query($cn,$query_limit_rs1) or die(mysqli_error($cn));
    $row_rs1 = mysqli_fetch_assoc($rs1);

    if (isset($_GET['totalRows_rv1'])) {
        $totalRows_rv1 = $_GET['totalRows_rv1'];
    } else { 
        $all_rs1 = mysqli_query($cn, $query_rs1);
        $totalRows_rv1 = mysqli_num_rows($all_rs1);
    }
    $totalPages_rs1= ceil($totalRows_rv1/$maxRows_rg1)-1;

    $queryString_rs1  = "";
    if (!empty($_SERVER['QUERY_STRING'])) {
        $params = explode("&", $_SERVER['QUERY_STRING']);
        $newParams = array();
        foreach ($params as $param) {
        if (stristr($param, "verify_page_num") == false && 
            stristr($param, "totalRows_rv1") == false) {
            array_push($newParams, $param);
            }
        }
        if (count($newParams) != 0) {
            $queryString_rs1 = "&" . htmlentities(implode("&", $newParams));
        }
    }

    if($_GET['id'] != ""){  
    $query_Recordsetad1 = "SELECT * FROM `users` WHERE `id` = '".GetSQLValueString($id, "int")."'";
    $Recordad1 = mysqli_query($cn,$query_Recordsetad1) or die(mysqli_error($cn));
    $row_Recordadd1 = mysqli_fetch_assoc($Recordad1);

    }

    ?>
    <!-- Content area -->
    <div class="row">
    <!-- Main content -->
    <!-- 2 columns form -->                       
    <div class="panel panel-flat w-100">
        <div class="panel-body">
            <div class="row">
            <div class="pr-5 pl-5 w-100">
				<div class="form-group">
					<div class="col-lg-6 float-right">
						<label class="control-label text-center float-right" style="padding-top:8px;color:#FFF;">تعداد نمایش</label>
						<form name="formpgnum" id="formpgnum" action="<?php $_SERVER['PHP_SELF'];?>" method="post">
							<select style="float:left;width:50%;" name="verify_pagenum" id="verify_pagenum" class="form-control" onchange="submit();" tabindex="1">
								<option value="10" <?php if (!(strcmp('10', KT_escapeAttribute(@$_SESSION['verify_pagenum'])))) {echo "selected=\"selected\"";} ?>>10</option>
								<option value="15" <?php if (!(strcmp('15', KT_escapeAttribute(@$_SESSION['verify_pagenum'])))) {echo "selected=\"selected\"";} ?>>15</option>
								<option value="20" <?php if (!(strcmp('20', KT_escapeAttribute(@$_SESSION['verify_pagenum'])))) {echo "selected=\"selected\"";} ?>>20</option>
								<option value="30" <?php if (!(strcmp('30', KT_escapeAttribute(@$_SESSION['verify_pagenum'])))) {echo "selected=\"selected\"";} ?>>30</option>
								<option value="50" <?php if (!(strcmp('50', KT_escapeAttribute(@$_SESSION['verify_pagenum'])))) {echo "selected=\"selected\"";} ?>>50</option>
								<option value="100" <?php if (!(strcmp('100', KT_escapeAttribute(@$_SESSION['verify_pagenum'])))) {echo "selected=\"selected\"";} ?>>100</option>
							</select>
						</form>
					</div>
				</div>
                <!--[if !IE]>start  tabs<![endif]-->
            </div>
            <form action="<?php echo KT_escapeAttribute(KT_getFullUri()); ?>" method="post" id="form1" class="pr-3 pl-3 w-100">
                <div class="table-responsive w-100">
                    <table class="table table-bordered">
                        <thead>
							<tr>
								<th id="id"><a href="<?php echo $tso_listrsverify->getSortLink('verify.id'); ?>"> ID</a></th>
								<th id="user_id"><a href="<?php echo $tso_listrsverify->getSortLink('verify.user_id'); ?>">کاربر</a></th>
								<th id="created_at"><a href="<?php echo $tso_listrsverify->getSortLink('verify.created_at'); ?>"> تاریخ ثبت</a></th>
								<th id="updated_at"><a href="<?php echo $tso_listrsverify->getSortLink('verify.updated_at'); ?>"> تاریخ به روز رسانی</a></th>
								<th id="status"><a href="<?php echo $tso_listrsverify->getSortLink('status'); ?>"> وضعیت</a></th>
								<th>عملیات</th>
							</tr>
                        </thead>
                        <tbody>
                            <?php if ($totalRows_rv1 > 0) { // Show if recordset not empty ?>
                                <?php do { ?>
                                    <tr>
                                        <td><?php echo $row_rs1['id']; ?></td>
                                        <?php if($_GET['id'] == ""){ ?>
                                            <td>
                                                <?php 
                                                echo "<a href='../users/user_manage.php?edit=".$row_rs1['user_id']."'>".$row_rs1['first_name'].' '.$row_rs1['last_name'];
                                                ?>
                                            </td>
                                        <?php } ?>
                                        <td>
                                            <?php $s= str_replace("-","/",$row_rs1['created_at']);?>
                                            <?php $s=miladi_to_jalali($row_rs1['created_at'],"-"," "); ?>
                                            <?php echo substr($s,8,2)." "; ?><?php echo monthname(substr($s,5,2)); ?>
                                            <?php echo substr($s,0,4)." "; ?>
                                            <?php echo "- ".substr($s,11,9); ?>
                                        </td>
                                        <td>
                                            <?php $s= str_replace("-","/",$row_rs1['updated_at']);?>
                                            <?php $s=miladi_to_jalali($row_rs1['updated_at'],"-"," "); ?>
                                            <?php echo substr($s,8,2)." "; ?><?php echo monthname(substr($s,5,2)); ?>
                                            <?php echo substr($s,0,4)." "; ?>
                                            <?php echo "- ".substr($s,11,9); ?>
                                        </td>
                                        <td>
                                            <?php
                                                if ($row_rs1['status'] == 0){
                                                    echo "<span class='text-danger'>غیرفعال</span>";
                                                } else if($row_rs1['status'] == 1){
                                                    echo "<span class='text-success'>فعال</span></br>";
                                                } 
                                            ?>
                                        </td>
                                        <td>
                                            <center>
                                                <a href="verify_view.php?id=<?php echo $row_rs1['id']. '&userid=' . $row_rs1['user_id'];?>" class="btn btn-success btn-xs float-right">مشاهده درخواست</a>
                                            </center>
                                        </td>

                                        <?php if (@$_SESSION['has_filter_tfi_listrsverify'] == 1) {?>  
                                            <td></td>
                                        <?php } ?>
                                    </tr>
                                <?php } while ($row_rs1 = mysqli_fetch_assoc($rs1)); ?>
                            <?php } // Show if recordset not empty ?>
                        </tbody>
                    </table>
                    
                
                </div>
            </form>
            <div style="margin: 0 auto">
                <?php include("../../layouts/paging.php"); 
                    echo pg('verify_page_num', $verify_page_num, $currentPage, $queryString_rs1, $totalPages_rs1);
                    ?>
            </div>
            </div>
        </div>
    </div>
    </div>
</div>
<?php require('../../layouts/footer.php');?>