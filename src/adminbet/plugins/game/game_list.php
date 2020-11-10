<?php 
   GLOBAL $content;
   $content['title']       = "لیست بازی ها";
   require('../../layouts/sidebar.php');
   if (isset($_GET['cat'])) {
   $day = GetSQLValueString($_GET['cat'], "int");
   $jsonResult = file_get_contents('../../../upload/API/soccer/day_'.$day.'.json');
   $content_json = json_decode($jsonResult,true);
   //$content_json = array_filter($content_json['data'], function ($data) {
   //    return ($data['status'] != 'CNCL' or  $data['status'] != 'FT');
   //});
   //
   //$size= count($content_json);
   
   //$size=sizeof($content_json->data);
   
   $data = $content_json['data'];
   $content_json = [];
   foreach($data as $entry){
    if($entry['status'] != 'CNCL' and $entry['status'] != 'FT')
        $content_json[] = $entry;
   }
   
   $size = sizeof($content_json);
   }
   $maxRows_rsservice = 20;
   $pageNum_rsservice = 0;
   if (isset($_GET['pageNum_rsservice'])) {
   $pageNum_rsservice = $_GET['pageNum_rsservice'];
   }
   $startRow_rsservice = $pageNum_rsservice * $maxRows_rsservice;
   if (isset($_GET['totalRows_rsservice'])) {
   $totalRows_rsservice = $_GET['totalRows_rsservice'];
   } else {
   $totalRows_rsservice = $size;
   }
   $totalPages_rsservice = ceil($totalRows_rsservice/$maxRows_rsservice)-1;
   $queryString_rsservice = "";
   if (!empty($_SERVER['QUERY_STRING'])) {
   $params = explode("&", $_SERVER['QUERY_STRING']);
   $newParams = array();
   foreach ($params as $param) {
    if (stristr($param, "pageNum_rsservice") == false && 
        stristr($param, "totalRows_rsservice") == false && 
        stristr($param, "sort") == false) {
      array_push($newParams, $param);
    }
   }
   if (count($newParams) != 0) {
    $queryString_rsservice = "&" . htmlentities(implode("&", $newParams));
   }
   }
   $queryString_rsservice = sprintf("&totalRows_rsservice=%d%s", $totalRows_rsservice, $queryString_rsservice);
   global $id;
   $id=$_POST['search'];
   ?>
<div class="panel-body">
   <div class="row">
      <?php 
         if(GetSQLValueString($_GET['add'], "int")==1){
         ?>
      <div class="alert alert-success alert-styled-left alert-arrow-left alert-bordered">
         <button type="button" class="close" data-dismiss="alert"><span>&times;</span><span class="sr-only">Close</span></button>
         <?php if(GetSQLValueString($_GET['fileedit'], "int")==1){ ?>
         ویرایش رکورد قبلی در فایل با موفقیت انجام شد 
         <?php }elseif(GetSQLValueString($_GET['fileeditnew'], "int")==1){ ?>
         ویرایش با درج رکورد جدید در فایل با موفقیت انجام شد 
         <?php }else{?>
         ویرایش با موفقیت انجام شد 
         <?php } ?>
      </div>
      <?php 
         }?>
      <legend>
      <?php                        
         function checkgamestatus($input)
         {
         switch($input)
         {
         case 0 : echo "غیرفعال";break;
         case 1 : echo "فعال";break;
         }
         }      ?>                         
      <form action="<?php echo KT_escapeAttribute(KT_getFullUri()); ?>" method="post" id="form1">
         <?php/*<div style="margin: 10px;">
            <label>جستجوی شناسه تیم:</label><input type="number" class="form-control " name="search" style="width: 15%;display: inline-block;"><input type="submit" name="searchbu" class="btn btn-primary btn-xs"></br>
         </div>*/?>
         <div class="table-responsive">
            <table class="table table-bordered">
               <thead>
                  <tr>
                     <th id="id">ردیف</th>
                     <th id="match_id">شناسه تیم</th>
                     <th id="home_team_name">نام تیم میزبان</th>
                     <th id="away_team_name">نام تیم مهمان</th>
                     <th id="starting_date_time">تاریخ شروع</th>
                     <th id="home_score">گل میزبان</th>
                     <th id="away_score">گل مهمان</th>
                     <th>عملیات</th>
                  </tr>
               </thead>
               <tbody>
                  <?php 
                     if(($size<1 and !(isset($_GET['cat'])))){
                     ?>
                  <tr>
                     <td colspan="8">رکوردی وجود ندارد</td>
                  </tr>
                  <?php }else{
                     if($pageNum_rsservice<=$totalPages_rsservice){
                     $max= $startRow_rsservice+$maxRows_rsservice;
                      if($max>$size){$max= $size;}
                     function p($arr){
                     $id=$_POST['search'];
                      if($arr['id']==$id){
                       return true;
                      }
                      return false;
                     }
                     if(isset($id) and $id !=""){
                       $values=array_filter($content_json,"p");
                     foreach($values as $value){
                     
                     ?>
                  <tr>
                     <td><?php echo $i+1;?></td>
                     <td><?php echo $match_id=$value['id']; ?></td>
                     <td><?php echo $hometeam=$value['homeTeam']['name'];?></td>
                     <td><?php echo $awayteam=$value['awayTeam']['name']; ?></td>
                     <td>
                        <?php $s=$value['starting_date']." ".$value['starting_time'];?>
                        <?php $y=substr($s,6,4);$mo=substr($s,3,2);$d=substr($s,0,2);$h=substr($s,11,2); $m=substr($s,14,2);$se="00"; ?>
                        <?php $time=mktime($h,$m,$se,$mo,$d,$y);
                           $tomonth=jdate('m',$time);
                           if($tomonth>6)
                           {
                           	$time+=12600;
                           }else{
                           	$time+=16200;
                           }
                           echo jdate('j F Y H:i:s ',$time)."</br>";
                            ?>
                     </td>
                     <td><?php echo $value['home_score']; ?></td>
                     <td><?php echo $value['away_score']; ?></td>
                     <?php 
                        mysqli_select_db($cn,$database_cn);
                        $query_Recordstatus = "select status from matches where id='$match_id'";
                        $Recordstatus = mysqli_query($cn,$query_Recordstatus) or die(mysqli_error($cn));
                        $totalrowstatus=mysqli_num_rows($Recordstatus);
                        $matchstatus=1;
                        if($totalrowstatus>0){
                         $row_Recordstatus = mysqli_fetch_assoc($Recordstatus);
                         $matchstatus= $row_Recordstatus['status'];
                         if($matchstatus==NULL or !isset($matchstatus) or $matchstatus==""){ $matchstatus=1;}
                        }
                        
                        ?>
                     <td>
                        <ul class="icons-list">
                           <?php if (in_array('game_code', $accccesslist)) {?>
                           <li class="text-brown-600"><a href="game_code.php?hometeam=<?php echo $hometeam.'&awayteam='.$awayteam;?>&id=<?php echo encrypt($value['id'],session_id()."game"); ?>" data-popup="tooltip" title="کد معادل"><i class="icon-pencil7"></i></a></li>
                           <?php }?>
                           <?php if (in_array('game_status', $accccesslist)) {?>
                           <!--
                              <li class="text-primary-600">
                              <a href="change_status.php?table=<?php echo encrypt('matches',session_id()."sts"); ?>&field=<?php echo encrypt('status',session_id()."sts"); ?>&id=<?php echo encrypt($match_id,session_id()."sts"); ?>" data-popup="tooltip" title="تغییر وضعیت"><?php checkgamestatus($matchstatus); ?></a></li>
                              -->
                           <li data-popup="tooltip" title="تغییر وضعیت" class="checkbox checkbox-switchery switchery-xs" style="margin-bottom: 14px;"><a href="change_status.php?table=<?php echo encrypt('matches',session_id()."sts"); ?>&field=<?php echo encrypt('status',session_id()."sts"); ?>&id=<?php echo encrypt($match_id,session_id()."sts"); ?>"><input type="checkbox" class="switchery" <?php if($matchstatus ==1) echo 'checked'; ?>></a></li>
                           <?php }?>
                        </ul>
                     </td>
                  </tr>
                  <?php  } }else{ 
                     for($i=$startRow_rsservice;$i<$max;$i++){
                     $value=$content_json[$i];
                     ?>
                  <tr>
                     <td><?php echo $i+1;?></td>
                     <td><?php echo $match_id=$value['id']; ?></td>
                     <td><?php echo $hometeam=$value['homeTeam']['name'];?></td>
                     <td><?php echo $awayteam=$value['awayTeam']['name']; ?></td>
                     <td>
                        <?php $s=$value['starting_date']." ".$value['starting_time'];?>
                        <?php $y=substr($s,6,4);$mo=substr($s,3,2);$d=substr($s,0,2);$h=substr($s,11,2); $m=substr($s,14,2);$se="00"; ?>
                        <?php $time=mktime($h,$m,$se,$mo,$d,$y);
                           $tomonth=jdate('m',$time);
                           if($tomonth>6)
                           {
                           	$time+=12600;
                           }else{
                           	$time+=16200;
                           }
                           echo jdate('j F Y H:i:s ',$time)."</br>";
                            ?>
                     </td>
                     <td><?php echo $value['home_score']; ?></td>
                     <td><?php echo $value['away_score']; ?></td>
                     <?php 
                        mysqli_select_db($cn,$database_cn);
                        $query_Recordstatus = "select status from matches where id='$match_id'";
                        $Recordstatus = mysqli_query($cn,$query_Recordstatus) or die(mysqli_error($cn));
                        $totalrowstatus=mysqli_num_rows($Recordstatus);
                        $matchstatus=1;
                        if($totalrowstatus>0){
                         $row_Recordstatus = mysqli_fetch_assoc($Recordstatus);
                         $matchstatus= $row_Recordstatus['status'];
                         if($matchstatus==NULL or !isset($matchstatus) or $matchstatus==""){ $matchstatus=1;}
                        }
                        
                        ?>
                     <td>
                        <ul class="icons-list">
                           <?php if (in_array('game_code', $accccesslist)) {?>
                           <li class="text-brown-600"><a href="game_code.php?hometeam=<?php echo $hometeam.'&awayteam='.$awayteam;?>&id=<?php echo encrypt($value['id'],session_id()."game"); ?>" data-popup="tooltip" title="کد کوتاه"><i class='fa fa-edit'></i></i></i></a></li>
                           <?php }?>
                           <?php if (in_array('game_status', $accccesslist)) {?>
                           <li><a href="change_status.php?table=<?php echo encrypt('matches',session_id()."sts"); ?>&field=<?php echo encrypt('status',session_id()."sts"); ?>&id=<?php echo encrypt($match_id,session_id()."sts"); ?>" data-popup="tooltip"><?= $matchstatus ==1 ? 'فعال' : 'غیرفعال' ?></a></li>
							<?php }?>
                        </ul>
                     </td>
                  </tr>
                  <?php //} 
                     } // }
                     
                     }} } ?>
               </tbody>
            </table>
         </div>
      </form>
      <br /><br />
      <div class="text-center">
         <!--
            <?php include("../../layouts/paging.php"); 
               echo pg('pageNum_rsservice', $pageNum_rsservice, $currentPage, $queryString_rsservice, $totalRows_rsservice);
               ?>
            --> <?php if(!(isset($id)) and $id ==""){?>
         <ul class="pagination">
            <?php if ($pageNum_rsservice > 0) { ?>
            <li  ><a href="<?php printf("%s?pageNum_rsservice=%d%s", $currentPage, max(0, $pageNum_rsservice - 1), $queryString_rsservice); ?>" data-popup="tooltip" title="قبلی">&lsaquo;</a></li>
            <?php } ?>
            <?php if ($pageNum_rsservice > 0) { // Show if not first page ?>
            <?php
               $safheghabli = $pageNum_rsservice -1;
               $i=$safheghabli-4;
               if($i<1)
               $i=0;
               while($i>=0 and $i<=$safheghabli){
               ?>
            <li><a  href="<?php printf("%s?pageNum_rsservice=%d%s", $currentPage,  $i, $queryString_rsservice); ?>"><?php echo $i+1; ?></a></li>
            <?php
               $i++;
               } ?>
            <?php } // Show if not first page ?>
            <li class="active"><a ><?php echo $pageNum_rsservice+1; ?></a></li>
            <?php if ($pageNum_rsservice < $totalPages_rsservice) { // Show if not last page ?>
            <?php
               $i=1;
               $safhebadi = $pageNum_rsservice + 1;
               while($i<5 and $safhebadi <= $totalPages_rsservice){
               ?>
            <li><a  href="<?php printf("%s?pageNum_rsservice=%d%s", $currentPage,  $safhebadi, $queryString_rsservice); ?>"><?php echo $safhebadi+1; ?></a></li>
            <?php
               $i++;$safhebadi++;
               } ?>
            <?php } ?>
            <?php if ($pageNum_rsservice < $totalPages_rsservice) { ?>
            <li><a  href="<?php printf("%s?pageNum_rsservice=%d%s", $currentPage, min($totalPages_rsservice, $pageNum_rsservice + 1), $queryString_rsservice); ?>" data-popup="tooltip" title="بعدی">&rsaquo;</a></li>
            <?php } ?>
         </ul>
         <?php } ?>
      </div>
   </div>
</div>
</div>
<?php require('../../layouts/footer.php');?>