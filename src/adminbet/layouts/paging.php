<?php function pg($title_pageNum_rs1,$pageNum_rs1, $currentPage, $queryString_rs1, $totalPages_rs1){?>
<ul class="pagination">
    <?php 
   	  if ($pageNum_rs1 > 0) 
	  { // Show if not first page ?>
        <li><a href="<?php printf("%s?%s=%d%s", $currentPage, $title_pageNum_rs1, 0, $queryString_rs1); ?>" data-popup="tooltip" title="اول">&lsaquo;&lsaquo;</a> </li>
        <li><a href="<?php printf("%s?%s=%d%s", $currentPage, $title_pageNum_rs1, max(0, $pageNum_rs1 - 1), $queryString_rs1); ?>" data-popup="tooltip" title="قبلی">&lsaquo;</a> </li>
<?php } // Show if not first page ?>


      <?php
   $i=1;
   $safheghabli = $pageNum_rs1 -1;
    while($i<5 and $safheghabli >=0){
   ?><li><a href="<?php printf("%s?%s=%d%s", $currentPage, $title_pageNum_rs1, $i-1, $queryString_rs1); ?>"><?php echo $i; ?></a></li>
    <?php 
   $i++;$safheghabli--;
   } ?>


                        
                        <li class="active"><a href=""><?php echo $pageNum_rs1+1; ?></a></li>
                                                
                        <?php if ($pageNum_rs1 < $totalPages_rs1) { // Show if not last page ?> 
   <?php
   $i=1;
   $safhebadi = $pageNum_rs1 + 1;
    while($i<5 and $safhebadi <= $totalPages_rs1){
   ?>
                        <li><a href="<?php printf("%s?%s=%d%s", $currentPage, $title_pageNum_rs1, $safhebadi, $queryString_rs1); ?>"><?php echo $safhebadi+1; ?></a></li><?php 
   $i++;$safhebadi++;
   } 
   }?>
   
                    <?php if ($pageNum_rs1 < $totalPages_rs1) { // Show if not last page ?>
          <li><a href="<?php printf("%s?%s=%d%s", $currentPage, $title_pageNum_rs1, min($totalPages_rs1, $pageNum_rs1 + 1), $queryString_rs1); ?>" data-popup="tooltip" title="بعدی">&rsaquo;</a> </li>
          <li><a href="<?php printf("%s?%s=%d%s", $currentPage, $title_pageNum_rs1, $totalPages_rs1, $queryString_rs1); ?>" data-popup="tooltip" title="آخر">&rsaquo;&rsaquo;</a> </li>
          <?php } // Show if not last page ?>
								</ul>
        <?php }?>