<?php
/* Smarty version 3.1.31, created on 2020-11-06 14:23:07
  from "/home/vip90/public_html/themes/default/SGBS-v3/modules/bets/upComing.tpl" */

/* @var Smarty_Internal_Template $_smarty_tpl */
if ($_smarty_tpl->_decodeProperties($_smarty_tpl, array (
  'version' => '3.1.31',
  'unifunc' => 'content_5fa52b13f18335_24279733',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    '398904c45c1ce42c7e4dc0a220c6ee44e8f7982e' => 
    array (
      0 => '/home/vip90/public_html/themes/default/SGBS-v3/modules/bets/upComing.tpl',
      1 => 1542268498,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
  ),
),false)) {
function content_5fa52b13f18335_24279733 (Smarty_Internal_Template $_smarty_tpl) {
if (!is_callable('smarty_function_site_url')) require_once '/home/vip90/public_html/application/smarty/plugins/function.site_url.php';
if (!is_callable('smarty_function_jdate')) require_once '/home/vip90/public_html/application/smarty/plugins/function.jdate.php';
if (!is_callable('smarty_modifier_persian_number')) require_once '/home/vip90/public_html/application/smarty/plugins/modifier.persian_number.php';
if (!is_callable('smarty_function_searchArray')) require_once '/home/vip90/public_html/application/smarty/plugins/function.searchArray.php';
if (!is_callable('smarty_function_gmtfa')) require_once '/home/vip90/public_html/application/smarty/plugins/function.gmtfa.php';
if (!is_callable('smarty_modifier_con')) require_once '/home/vip90/public_html/application/smarty/plugins/modifier.con.php';
?>
<div class="cotainer">
<!------------------------ predict buttom start -------------------->
<div class="predict-div">
  <button class="btn predict-btn"><i class="fa fa-file-text-o"></i>
  <div class="notification"></div>
  </button>
  <button class="btn go-top-btn"> <i class="fa fa-chevron-up"></i></button>
</div>        
  <?php echo '<script'; ?>
>
        $(document).ready(function () {
            $(".predict-btn").click(function () {
                $('html, body').animate({
                    scrollTop: $("#predict").offset().top
                }, 1);
            });
            $(".go-top-btn").click(function () {
                $('html, body').animate({
                    scrollTop: $("#top").offset().top
                }, 1);
            });
            $('ul.tabSwitch li').click(function () {
                $('ul.tabSwitch  li.active').removeClass('active');
                $(this).addClass('active');
            });
            $('.owl-carousel.sports-slider').owlCarousel({
                rtl: true,
				dots:false,
				mouseDrag : true,
                touchDrag : true,
                nav: true,
                navText: ["<i class='fa fa-chevron-right'></i>", "<i class='fa fa-chevron-left'></i>"],
				URLhashListener:true,
                autoplayHoverPause:true,
                startPosition: 'URLHash',
			    responsiveClass:true,
                responsive: {
                    1200: {
                        items: 14
                    },
                    992: {
                        items: 8
                    },
                    768: {
                        items: 6
                    },
                    0: {
                        items: 6
                    }
                }
            });
          $('.owl-carousel.side-slider').owlCarousel({
                rtl: true,
			  	loop:true,
				autoplay:true,
				autoplayTimeout:5000,
			    autoplayHoverPause:true,
			    dots:true,
			    mouseDrag : true,
                touchDrag : true,
			  items:1
            });
			    $('.owl-carousel.top-slider').owlCarousel({
                rtl: true,
			  	loop:true,
				autoplay:true,
				autoplayTimeout:3000,
				autoplayHoverPause:true,
			    dots:true,
			    mouseDrag : true,
                touchDrag : true,
                nav: true,
                navText: ["<i class='fa fa-chevron-right'></i>", "<i class='fa fa-chevron-left'></i>"],
			    responsiveClass:true,
                responsive: {
                    1200: {
                        items: 2
                    },
					
                    992: {
                        items: 2,
//						center:true
                    },
                    768: {
                        items:2
                    },
                    0: {
                        items: 1
                    }
                }
            });
			
			$(".sports-slider .item").click(function(){
				 $('.item.active').removeClass('active');
                $(this).addClass('active');
		     var sport =$(this).attr("data-sportcat");
		     $('div[data-sport]').addClass("hide");
				$('div[data-sport=' + sport + ']').removeClass("hide");
			});
				$(".item.all").click(function(){
				$('div[data-sport]').removeClass("hide");
			});
			
			
			// if click item in aside right go this sport
			
		
			$(".side-sports li").click(function(){
					 var sport =$(this).attr("data-sportcat");
				$(".side-sports li.active").removeClass("active");
				$("this").addClass("active");
//				       $('html, body').animate({
//                         scrollTop: $(".sports-slider  #predict").offset().top
//                         }, 1);
					 $('.sports-slider .item.active').removeClass('active');
                	 $('[data-sportcat=' + sport + ']').addClass('active');
		     		 $('div[data-sport]').addClass("hide");
				     $('div[data-sport=' + sport + ']').removeClass("hide");
		});
			
				// if in inplayme page to upcoming in aside right go this sport
			var url = window.location.pathname;
			var urlSplit = url.split('/');
			var sport = urlSplit[5];
			if( sport != null ){
				if($('div[data-sportcat=' + sport + ']')){
					var taginfo = $('div[data-sportcat=' + sport + ']')
					$('div[data-sportcat=' + sport + ']').addClass("active");
					$(".sports-slider .item.all").removeClass("active");
					var sportType =$(taginfo).attr("data-sportcat");
					$('div[data-sport]').addClass("hide");
					$('div[data-sport=' + sportType + ']').removeClass("hide");
					
				}else{
						
				}
			}
			
      });
    <?php echo '</script'; ?>
> 

<!------------------------ predict buttom end -------------------->

<div class="body_fraim maincontent clearfix"> 
  <!--        <header class="tabheader PreTopShorting">-->
  <div class=""> 
    <ul class="col-xs-12 nopadding odds inplay inplaybet res-inplay">
  <li class="col-lg-2 col-md-2 col-sm-12 col-xs-12 hide-on-sm nopadding">
      <ul class="side-sports animated">
        <h3>ورزش ها</h3>
       		
		<?php
$_from = $_smarty_tpl->smarty->ext->_foreach->init($_smarty_tpl, $_smarty_tpl->tpl_vars['soccer_type']->value, 'soccer_type_value');
if ($_from !== null) {
foreach ($_from as $_smarty_tpl->tpl_vars['soccer_type_value']->value) {
?>
			<li class="" data-sportcat="<?php echo $_smarty_tpl->tpl_vars['soccer_type_value']->value['en'];?>
">
				<a href="#<?php echo $_smarty_tpl->tpl_vars['soccer_type_value']->value['en'];?>
s">
					<img class="" width="20px" height="20px" src="<?php echo smarty_function_site_url(array(),$_smarty_tpl);?>
/attachment/sport_logo/<?php echo $_smarty_tpl->tpl_vars['soccer_type_value']->value['logo'];?>
" alt="<?php echo $_smarty_tpl->tpl_vars['soccer_type_value']->value['fa'];?>
">
            		<label><?php echo $_smarty_tpl->tpl_vars['soccer_type_value']->value['fa'];?>
</label>
       			</a> 
			</li>
        <?php
}
}
$_smarty_tpl->smarty->ext->_foreach->restore($_smarty_tpl, 1);
?>
 
      </ul>
    </li>
		
      <li class="col-lg-7 col-md-7 col-sm-12 col-xs-12 panel-group nopadding ">
		<!----------------------------End top slider-------------------------->

      <div class="col-xs-12 nopadding tab-div">
        <ul class="tab_switch_block tabSwitch">
          <li class="inplay "> <a href="<?php echo smarty_function_site_url(array(),$_smarty_tpl);?>
bets/inplayBet">پیش بینی زنده</a> </li>
          <li class="upcoming  active"> <a href="<?php echo smarty_function_site_url(array(),$_smarty_tpl);?>
bets/upComing">پیش بینی بازی ها</a> </li>
          
          <li> <a  href="<?php echo smarty_function_site_url(array(),$_smarty_tpl);?>
bets/myrecords">سابقه پیش بینی ها</a> </li>
        </ul>
      </div>
		  
      <!------------------------  sport slider -------------------->
		  
      <div class="col-xs-12 owl-carousel nopadding sports-slider">
        <div class="item all active">
			<a href="javascript:void(0)">
				<img class="img-responsive" src="<?php echo smarty_function_site_url(array(),$_smarty_tpl);?>
/attachment/sport_logo/cup.png" alt="عکس 1">

				<div class="slider-text">
					<p>همه ورزش ها</p>
					<p><?php echo array_sum($_smarty_tpl->tpl_vars['soccer_number']->value);?>
</p>
			    </div>
            </a> 
		 </div>
		  
        <!--partner_item1 end-->
        <?php
$_from = $_smarty_tpl->smarty->ext->_foreach->init($_smarty_tpl, $_smarty_tpl->tpl_vars['soccer_type']->value, 'soccer_type_value');
if ($_from !== null) {
foreach ($_from as $_smarty_tpl->tpl_vars['soccer_type_value']->value) {
?>
        <div class="item"  data-hash="<?php echo $_smarty_tpl->tpl_vars['soccer_type_value']->value['en'];?>
s" data-sportcat="<?php echo $_smarty_tpl->tpl_vars['soccer_type_value']->value['en'];?>
"> 
			<a href="javascript:void(0)"> 
				<img src="<?php echo smarty_function_site_url(array(),$_smarty_tpl);?>
/attachment/sport_logo/<?php echo $_smarty_tpl->tpl_vars['soccer_type_value']->value['logo'];?>
" alt="$soccer_type_value">
				<div class="slider-text">
					<p><?php echo $_smarty_tpl->tpl_vars['soccer_type_value']->value['fa'];?>
</p>
					<p><?php echo $_smarty_tpl->tpl_vars['soccer_number']->value[$_smarty_tpl->tpl_vars['soccer_type_value']->value['en']];?>
</p>
          		</div>
          	</a> 
		 </div>
        <?php
}
}
$_smarty_tpl->smarty->ext->_foreach->restore($_smarty_tpl, 1);
?>
 
		  
		  
         <!--partner_item1 end-->
        
      </div>
      <!------------------------ End sport slider -------------------->
		  		
			
      <div class="col-xs-12 inplaytable nohover inner-headder">
        <div class="branchheader" id="top"> <span class="match"> <b><?php echo smarty_function_jdate(array('format'=>'j F (l) ','date'=>"+".((string)$_smarty_tpl->tpl_vars['day']->value)." day",'second_date'=>gmdate('Y-m-d')),$_smarty_tpl);?>
</b> (<span class="totalevents"><?php echo smarty_modifier_persian_number($_smarty_tpl->tpl_vars['count']->value);?>
</span>) </span>
          <select class="upcoming-select" id="upcoming-select-id">
            <option <?php if ($_smarty_tpl->tpl_vars['day']->value == 0) {?>selected=""<?php }?> value="<?php echo smarty_function_site_url(array(),$_smarty_tpl);?>
bets/upComing"> <?php echo smarty_function_jdate(array('format'=>'j F (l) ','date'=>'+0 day','second_date'=>gmdate('Y-m-d')),$_smarty_tpl);?>
 </option>
            <option <?php if ($_smarty_tpl->tpl_vars['day']->value == 1) {?>selected=""<?php }?>  value="<?php echo smarty_function_site_url(array(),$_smarty_tpl);?>
bets/upComing/1"> <?php echo smarty_function_jdate(array('format'=>'j F (l) ','date'=>'+1 day','second_date'=>gmdate('Y-m-d')),$_smarty_tpl);?>
 </option>
            <option <?php if ($_smarty_tpl->tpl_vars['day']->value == 2) {?>selected=""<?php }?>  value="<?php echo smarty_function_site_url(array(),$_smarty_tpl);?>
bets/upComing/2"> <?php echo smarty_function_jdate(array('format'=>'j F (l) ','date'=>'+2 day','second_date'=>gmdate('Y-m-d')),$_smarty_tpl);?>
 </option>
            <option <?php if ($_smarty_tpl->tpl_vars['day']->value == 3) {?>selected=""<?php }?>  value="<?php echo smarty_function_site_url(array(),$_smarty_tpl);?>
bets/upComing/3"> <?php echo smarty_function_jdate(array('format'=>'j F (l) ','date'=>'+3 day','second_date'=>gmdate('Y-m-d')),$_smarty_tpl);?>
 </option>
            <option <?php if ($_smarty_tpl->tpl_vars['day']->value == 4) {?>selected=""<?php }?>  value="<?php echo smarty_function_site_url(array(),$_smarty_tpl);?>
bets/upComing/4"> <?php echo smarty_function_jdate(array('format'=>'j F (l) ','date'=>'+4 day','second_date'=>gmdate('Y-m-d')),$_smarty_tpl);?>
 </option>
          </select>
        </div>
      </div>
		  
<!-----------------------main panel----------------------->
       <?php
$_from = $_smarty_tpl->smarty->ext->_foreach->init($_smarty_tpl, $_smarty_tpl->tpl_vars['competition']->value, 'soccer_value', false, 'soccer_key');
if ($_from !== null) {
foreach ($_from as $_smarty_tpl->tpl_vars['soccer_key']->value => $_smarty_tpl->tpl_vars['soccer_value']->value) {
?>
       	<?php if (sizeof($_smarty_tpl->tpl_vars['soccer_value']->value) > 0) {?>
        <div class="col-xs-12 panel nopadding" id="<?php echo $_smarty_tpl->tpl_vars['soccer_key']->value;?>
" data-sport="<?php echo $_smarty_tpl->tpl_vars['soccer_key']->value;?>
">
        
			<div class="col-xs-12 panel-heading sport-cat"  data-toggle="collapse" data-target="#mainpanel-<?php echo $_smarty_tpl->tpl_vars['soccer_key']->value;?>
" data-parent="#<?php echo $_smarty_tpl->tpl_vars['soccer_key']->value;?>
">
				<h4 class="panel-title"> 
				<a role="button" data-toggle="collapse" data-parent="#<?php echo $_smarty_tpl->tpl_vars['soccer_key']->value;?>
" href="#mainpanel-<?php echo $_smarty_tpl->tpl_vars['soccer_key']->value;?>
" aria-expanded="true" aria-controls="collapseOne" class="match">
					<b class="mark-header"><?php echo $_smarty_tpl->tpl_vars['soccer_type']->value[$_smarty_tpl->tpl_vars['soccer_key']->value]['fa'];?>
  (<?php echo $_smarty_tpl->tpl_vars['soccer_number']->value[$_smarty_tpl->tpl_vars['soccer_key']->value];?>
)</b>
				</a> 
				</h4>
			</div>
         	
          	<div id="mainpanel-<?php echo $_smarty_tpl->tpl_vars['soccer_key']->value;?>
"  class="col-xs-12 clearfix panel-collapse collapse in nopadding" role="tabpanel" aria-labelledby="headingOne"> 
            <!--------------------------start inner pannel --------------> 
            <div class="col-xs-12 panel nopadding" id="inner-panel-<?php echo $_smarty_tpl->tpl_vars['soccer_key']->value;?>
">
		  <?php $_smarty_tpl->_assignInScope('k', 0);
?>
        <?php
$_from = $_smarty_tpl->smarty->ext->_foreach->init($_smarty_tpl, $_smarty_tpl->tpl_vars['soccer_value']->value, 'val', false, 'key');
if ($_from !== null) {
foreach ($_from as $_smarty_tpl->tpl_vars['key']->value => $_smarty_tpl->tpl_vars['val']->value) {
?>
        
        <?php $_smarty_tpl->_assignInScope('leagues_array', reset($_smarty_tpl->tpl_vars['val']->value));
?>
        <div class="col-xs-12 panel-heading inplayheader p5"  data-toggle="collapse" data-target="#<?php echo $_smarty_tpl->tpl_vars['soccer_key']->value;
echo $_smarty_tpl->tpl_vars['k']->value;?>
" data-parent="#inner-panel-<?php echo $_smarty_tpl->tpl_vars['soccer_key']->value;?>
" data-leagueid="<?php echo $_smarty_tpl->tpl_vars['leagues_array']->value->competition->id;?>
" data-rankid="<?php echo $_smarty_tpl->tpl_vars['leagues_array']->value->competition->id;?>
">
        	<div class="col-sm-6 col-xs-12 panel-title"> 
				<span role="button" data-toggle="collapse" data-parent="#inner-panel-<?php echo $_smarty_tpl->tpl_vars['soccer_key']->value;?>
" href="#<?php echo $_smarty_tpl->tpl_vars['soccer_key']->value;
echo $_smarty_tpl->tpl_vars['k']->value;?>
" aria-expanded="true" aria-controls="collapseOne" class="match">
					<b><?php echo $_smarty_tpl->tpl_vars['leagues_array']->value->competition->name;?>
</b>
				</span>
       		</div>
        </div>
        <div id="<?php echo $_smarty_tpl->tpl_vars['soccer_key']->value;
echo $_smarty_tpl->tpl_vars['k']->value++;?>
" class="col-xs-12 clearfix panel-collapse collapse in nopadding" role="tabpanel" aria-labelledby="headingOne"> <?php
$_from = $_smarty_tpl->smarty->ext->_foreach->init($_smarty_tpl, $_smarty_tpl->tpl_vars['val']->value, 'match');
if ($_from !== null) {
foreach ($_from as $_smarty_tpl->tpl_vars['match']->value) {
?>
         
          <?php ob_start();
echo smarty_function_searchArray(array('key'=>'type','val'=>'1x2','array'=>$_smarty_tpl->tpl_vars['match']->value->odds->data[0]->types->data),$_smarty_tpl);
$_prefixVariable1=ob_get_clean();
if ($_prefixVariable1 != null) {?>
          <div data-eventid="<?php echo $_smarty_tpl->tpl_vars['match']->value->id;?>
" class="col-xs-12 odddetails" data-leagueid="<?php echo $_smarty_tpl->tpl_vars['match']->value->competition->id;?>
" >
            <div class="col-sm-6 col-xs-11 p5">
				<span class="fa fa-futbol-o timer"></span>
				<div class="col-xs-8 col-xs-9 nopadding">
					<p class="col-xs-12"><b class="host ellipsis"><?php echo $_smarty_tpl->tpl_vars['match']->value->homeTeam->name;?>
</b></p>
					<p class="col-xs-12"><b class="guest ellipsis"><?php echo $_smarty_tpl->tpl_vars['match']->value->awayTeam->name;?>
</b> </p>
				</div>
				<span class="inplaytime2 pull-left"> <?php echo smarty_function_gmtfa(array('time'=>$_smarty_tpl->tpl_vars['match']->value->starting_time,'format'=>smarty_modifier_persian_number('H:i')),$_smarty_tpl);?>
</span> 
			  </div>
            <div class="col-sm-5 col-xs-12 nopadding">
              <div class="eventodds"> <span class="eventsuspended hidden">غیر فعال</span>
                <ul class="mlodds">
				 	<?php $_smarty_tpl->_assignInScope('label', array('1','X','2'));
?>
                   <?php $_smarty_tpl->_assignInScope('label_array', array(array('1','Home'),array('X','Draw'),array('2','Away')));
?>
                    
                    <?php
$_smarty_tpl->tpl_vars['i'] = new Smarty_Variable(null, $_smarty_tpl->isRenderingCache);
$_smarty_tpl->tpl_vars['i']->value = 0;
if ($_smarty_tpl->tpl_vars['i']->value < 3) {
for ($_foo=true;$_smarty_tpl->tpl_vars['i']->value < 3; $_smarty_tpl->tpl_vars['i']->value++) {
?>
                    <?php if (in_array($_smarty_tpl->tpl_vars['match']->value->odds->data[0]->types->data[0]->odds->data[$_smarty_tpl->tpl_vars['i']->value]->label,$_smarty_tpl->tpl_vars['label_array']->value[$_smarty_tpl->tpl_vars['i']->value])) {?>
					
					
                    <li data-runnerid="<?php echo smarty_modifier_con($_smarty_tpl->tpl_vars['match']->value->id,'-',$_smarty_tpl->tpl_vars['match']->value->odds->data[0]->bookmaker_id,'-1x2-',$_smarty_tpl->tpl_vars['match']->value->odds->data[0]->types->data[0]->odds->data[$_smarty_tpl->tpl_vars['i']->value]->label);?>
"
						data-pick="<?php echo $_smarty_tpl->tpl_vars['match']->value->odds->data[0]->types->data[0]->odds->data[$_smarty_tpl->tpl_vars['i']->value]->label;?>
" data-points='' data-bet-sport="<?php echo $_smarty_tpl->tpl_vars['soccer_key']->value;?>
" class="col-sm-4 col-xs-4 inplaybtn eventodd"> <strong class="col-xs-2 nopadding text-right"> 
						
                     <?php if ($_smarty_tpl->tpl_vars['label_array']->value[$_smarty_tpl->tpl_vars['i']->value][0] == '1' || $_smarty_tpl->tpl_vars['label_array']->value[$_smarty_tpl->tpl_vars['i']->value][1] == 'Home') {?>
                      1
                     <?php } elseif ($_smarty_tpl->tpl_vars['label_array']->value[$_smarty_tpl->tpl_vars['i']->value][0] == '2' || $_smarty_tpl->tpl_vars['label_array']->value[$_smarty_tpl->tpl_vars['i']->value][1] == 'Away') {?>
                      2
                     <?php } else { ?>
                      x
                     <?php }?> </strong> 
                      <!--                                                                                    <i class="fa fa-caret-up up-arrow"></i>--> 
                      <span class="col-xs-10 nopadding text-left"> 
                      <?php if ($_smarty_tpl->tpl_vars['match']->value->odds->data[0]->types->data[0]->odds->data[$_smarty_tpl->tpl_vars['i']->value]->suspend == 0) {?>
                      	<?php echo $_smarty_tpl->tpl_vars['match']->value->odds->data[0]->types->data[0]->odds->data[$_smarty_tpl->tpl_vars['i']->value]->value;?>

                      <?php } else { ?>
                     	00.00
                      <?php }?> </span> </li>
                    <?php }?>
                    <?php }
}
?>

                    
                 
<!--
                 
                  <?php ob_start();
echo smarty_function_searchArray(array('key'=>'type','val'=>'1x2','array'=>$_smarty_tpl->tpl_vars['match']->value->odds->data[0]->types->data),$_smarty_tpl);
$_prefixVariable2=ob_get_clean();
$_smarty_tpl->_assignInScope('myArray', $_smarty_tpl->tpl_vars['match']->value->odds->data[0]->types->data[$_prefixVariable2]);
?>
                  
                  <li  class="col-xs-4 inplaybtn  eventodd" data-runnerid="<?php ob_start();
echo smarty_function_searchArray(array('key'=>'label','val'=>'1','array'=>$_smarty_tpl->tpl_vars['myArray']->value->odds->data),$_smarty_tpl);
$_prefixVariable3=ob_get_clean();
ob_start();
echo $_smarty_tpl->tpl_vars['myArray']->value->odds->data[$_prefixVariable3]->label;
$_prefixVariable4=ob_get_clean();
echo smarty_modifier_con($_smarty_tpl->tpl_vars['match']->value->id,'-',$_smarty_tpl->tpl_vars['match']->value->odds->data[0]->bookmaker_id,'-1x2-',$_prefixVariable4);?>
" data-bet-sport="<?php echo $_smarty_tpl->tpl_vars['soccer_key']->value;?>
"  data-pick="1"  data-pick="<?php echo $_smarty_tpl->tpl_vars['match']->value->homeTeam->name;?>
" data-points="">
                  <i> </i> <strong class="col-xs-2 nopadding text-right"> 1 </strong> <span class="col-xs-10 text-left nopadding">
					  <?php if (isset($_smarty_tpl->tpl_vars['homeOdd']->value)) {?>
                  <?php echo $_smarty_tpl->tpl_vars['homeOdd']->value;?>

                  <?php } else { ?>
                  <?php ob_start();
echo smarty_function_searchArray(array('key'=>'label','val'=>'1','array'=>$_smarty_tpl->tpl_vars['myArray']->value->odds->data),$_smarty_tpl);
$_prefixVariable5=ob_get_clean();
echo $_smarty_tpl->tpl_vars['myArray']->value->odds->data[$_prefixVariable5]->value;?>
    
                  <?php }?> </span>
                  </li>
					
					<?php ob_start();
echo smarty_function_searchArray(array('key'=>'label','val'=>'X','array'=>$_smarty_tpl->tpl_vars['myArray']->value->odds->data),$_smarty_tpl);
$_prefixVariable6=ob_get_clean();
$_smarty_tpl->_assignInScope('x', $_prefixVariable6);
?>
					<?php if (empty($_smarty_tpl->tpl_vars['x']->value)) {?>
					<?php ob_start();
echo smarty_function_searchArray(array('key'=>'label','val'=>'Draw','array'=>$_smarty_tpl->tpl_vars['myArray']->value->odds->data),$_smarty_tpl);
$_prefixVariable7=ob_get_clean();
$_smarty_tpl->_assignInScope('x', $_prefixVariable7);
?>
					<?php }?>
                  <li class="col-xs-4 inplaybtn  eventodd" data-runnerid="<?php ob_start();
echo $_smarty_tpl->tpl_vars['myArray']->value->odds->data[$_smarty_tpl->tpl_vars['x']->value]->label;
$_prefixVariable8=ob_get_clean();
echo smarty_modifier_con($_smarty_tpl->tpl_vars['match']->value->id,'-',$_smarty_tpl->tpl_vars['match']->value->odds->data[0]->bookmaker_id,'-1x2-',$_prefixVariable8);?>
" data-bet-sport="<?php echo $_smarty_tpl->tpl_vars['soccer_key']->value;?>
"  data-pick="X" data-points=""> <i></i> <strong class="col-xs-2 nopadding text-right"> x</strong> <span  class="col-xs-10 text-left nopadding"> <?php if (isset($_smarty_tpl->tpl_vars['drawOdd']->value)) {?>
                    <?php echo $_smarty_tpl->tpl_vars['drawOdd']->value;?>

                    <?php } else { ?>
                    <?php echo $_smarty_tpl->tpl_vars['myArray']->value->odds->data[$_smarty_tpl->tpl_vars['x']->value]->value;?>
    
                    <?php }?> </span> </li>
                  <li class="col-xs-4 inplaybtn  eventodd" data-runnerid="<?php ob_start();
echo smarty_function_searchArray(array('key'=>'label','val'=>'2','array'=>$_smarty_tpl->tpl_vars['myArray']->value->odds->data),$_smarty_tpl);
$_prefixVariable9=ob_get_clean();
ob_start();
echo $_smarty_tpl->tpl_vars['myArray']->value->odds->data[$_prefixVariable9]->label;
$_prefixVariable10=ob_get_clean();
echo smarty_modifier_con($_smarty_tpl->tpl_vars['match']->value->id,'-',$_smarty_tpl->tpl_vars['match']->value->odds->data[0]->bookmaker_id,'-1x2-',$_prefixVariable10);?>
" data-bet-sport="<?php echo $_smarty_tpl->tpl_vars['soccer_key']->value;?>
" data-pick="2" data-points=""> <i></i> <strong class="col-xs-2 nopadding text-right"> 2 </strong> <span  class="col-xs-10 text-left nopadding"> <?php if (isset($_smarty_tpl->tpl_vars['awayOdd']->value)) {?>
                    <?php echo $_smarty_tpl->tpl_vars['awayOdd']->value;?>

                    <?php } else { ?>
                    <?php ob_start();
echo smarty_function_searchArray(array('key'=>'label','val'=>'2','array'=>$_smarty_tpl->tpl_vars['myArray']->value->odds->data),$_smarty_tpl);
$_prefixVariable11=ob_get_clean();
echo $_smarty_tpl->tpl_vars['myArray']->value->odds->data[$_prefixVariable11]->value;?>
    
                    <?php }?> </span> </li>
-->
                </ul>
              </div>
            </div>
            <div class="col-sm-1 col-xs-1 more-bet"> <a class="has-tip fa fa-plus-circle more" href="<?php echo smarty_function_site_url(array(),$_smarty_tpl);?>
bets/preEvents/<?php echo $_smarty_tpl->tpl_vars['match']->value->id;?>
/<?php echo $_smarty_tpl->tpl_vars['soccer_key']->value;?>
" data-original-title="شرط های بیشتر"></a> </div>
          </div>
          <?php }?>
          
          <?php
}
}
$_smarty_tpl->smarty->ext->_foreach->restore($_smarty_tpl, 1);
?>
 
				</div>
        <?php
}
}
$_smarty_tpl->smarty->ext->_foreach->restore($_smarty_tpl, 1);
?>
 
			  </div>
			</div>
		  </div>
    	<?php }?>
     	<?php
}
}
$_smarty_tpl->smarty->ext->_foreach->restore($_smarty_tpl, 1);
?>

      <i class="fa-loader fa fa-spinner fa-pulse" style="display: none;"></i>
      </li>
      <li  id="predict"  class="col-lg-3 col-md-3 col-sm-12 col-xs-12 nopadding inplay-controller">
        <div class="col-lg-12 col-md-12 col-xs-12 nopadding">
         <div>
          <table class="livescore betslip">
            <tbody>
              <tr>
                <th>پیش بینی های من</th>
              </tr>
              <tr>
                <td><div class="nobet"> هنوز هیچ شرطی بسته نشده است. برای پیش بینی بروی ضریب مورد نظر خود کلیک کنید. </div>
                  <div class="selectedodds">
                    <div class="betlist"> </div>
                  </div></td>
              </tr>
            </tbody>
          </table>
          <div class="bettotal">
            <table class="livescore multiple">
            </table>
            <ul class="bettotal">
              <li>مجموع مبالغ <span class="totalstake">0</span></li>
              <li>برد احتمالی (تومان) <span class="totalwin">0</span></li>
            </ul>
            <div class="livescore">
              <div>
                <button class="totobutton smallbutton placebet disabled">ثبت شرط</button>
              </div>
              <div class="del"> <a class="deleteall" href="javascript:void(0)">حذف همه</a></div>
            </div>
          </div>
        </div>
          <div style="clear:both"></div>
          <div class="alertbox alertbox2 hidden"></div>
        </div>
      </li>
    </ul>
  </div>
</div><?php }
}
