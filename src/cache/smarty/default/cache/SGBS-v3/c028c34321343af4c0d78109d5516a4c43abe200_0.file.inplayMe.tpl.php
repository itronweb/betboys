<?php
/* Smarty version 3.1.31, created on 2020-11-07 12:46:16
  from "/home/vip90/public_html/themes/default/SGBS-v3/modules/bets/inplayMe.tpl" */

/* @var Smarty_Internal_Template $_smarty_tpl */
if ($_smarty_tpl->_decodeProperties($_smarty_tpl, array (
  'version' => '3.1.31',
  'unifunc' => 'content_5fa665e03b96a1_41501040',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    'c028c34321343af4c0d78109d5516a4c43abe200' => 
    array (
      0 => '/home/vip90/public_html/themes/default/SGBS-v3/modules/bets/inplayMe.tpl',
      1 => 1604725880,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
  ),
),false)) {
function content_5fa665e03b96a1_41501040 (Smarty_Internal_Template $_smarty_tpl) {
if (!is_callable('smarty_function_add_js')) require_once '/home/vip90/public_html/application/smarty/plugins/function.add_js.php';
if (!is_callable('smarty_function_site_url')) require_once '/home/vip90/public_html/application/smarty/plugins/function.site_url.php';
if (!is_callable('smarty_modifier_con')) require_once '/home/vip90/public_html/application/smarty/plugins/modifier.con.php';
?>
<!--<?php echo smarty_function_add_js(array('file'=>'bundles/updates.js','part'=>'footer'),$_smarty_tpl);?>
-->
<div> 
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
			    responsiveClass:true,
                responsive: {
                    1200: {
                        items: 12
                    },
                    992: {
                        items: 8
                    },
                    768: {
                        items: 6
                    },
                    0: {
                        items: 4
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
                        items: 2
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
			
//			var url = window.location.pathname;
//			var urlSplit = url.split('/');
//			var sport = urlSplit[5];
//
//			if( sport != null ){
//
			
        });
    <?php echo '</script'; ?>
>
  <input id="page-name" value="<?php echo $_smarty_tpl->tpl_vars['pageName']->value;?>
" type="hidden">
  <!------------------------ predict buttom end -------------------->
  <div class="cell">
  <div class="body_fraim maincontent clearfix">
  <ul class="col-xs-12 nopadding odds inplay inplaybet res-inplay" id="top">
    <li class="col-lg-2 col-md-2 col-sm-12 col-xs-12 hide-on-sm nopadding">
      <ul class="side-sports animated">
        <h3>ورزش ها</h3>
       		
		<?php
$_from = $_smarty_tpl->smarty->ext->_foreach->init($_smarty_tpl, $_smarty_tpl->tpl_vars['upcoming_soccer_type']->value, 'soccer_type_value');
if ($_from !== null) {
foreach ($_from as $_smarty_tpl->tpl_vars['soccer_type_value']->value) {
?>
			<li class="has-children "  data-sportcat="<?php echo $_smarty_tpl->tpl_vars['soccer_type_value']->value['en'];?>
">
				<a href="<?php echo smarty_function_site_url(array(),$_smarty_tpl);?>
bets/upComing/0/<?php echo $_smarty_tpl->tpl_vars['soccer_type_value']->value['en'];?>
">
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
    <li class="col-lg-7 col-md-7 col-sm-12 col-xs-12 nopadding">
      <div class="col-xs-12 nopadding tab-div">
        <ul class="tab_switch_block tabSwitch">
          <li class="inplay active "> <a href="<?php echo smarty_function_site_url(array(),$_smarty_tpl);?>
bets/inplayBet">پیش بینی زنده</a> </li>
          <li class="upcoming"> <a href="<?php echo smarty_function_site_url(array(),$_smarty_tpl);?>
bets/upComing">پیش بینی Openی ها</a> </li>
          
          <li> <a  href="<?php echo smarty_function_site_url(array(),$_smarty_tpl);?>
bets/myrecords">سابقه پیش بینی ها</a> </li>
        </ul>
      </div>
      <!------------------------  sport slider -------------------->
      <div class="col-xs-12 owl-carousel owl-theme nopadding sports-slider">
        <div class="item active all"> 
			<a href="javascript:void(0)">
				<img class="img-responsive" src="<?php echo smarty_function_site_url(array(),$_smarty_tpl);?>
/attachment/sport_logo/cup.png" alt="عکس 1">
          <div class="slider-text">
            <p>همه ورزش ها</p>
            <p><?php echo array_sum($_smarty_tpl->tpl_vars['soccer_number']->value);?>
</p>
          </div>
          </a> </div>
        <?php
$_from = $_smarty_tpl->smarty->ext->_foreach->init($_smarty_tpl, $_smarty_tpl->tpl_vars['soccer_type']->value, 'soccer_type_value');
if ($_from !== null) {
foreach ($_from as $_smarty_tpl->tpl_vars['soccer_type_value']->value) {
?>
		
        <div class="item" data-sportcat="<?php echo $_smarty_tpl->tpl_vars['soccer_type_value']->value['en'];?>
"> <a href="javascript:void(0)"> <img src="<?php echo smarty_function_site_url(array(),$_smarty_tpl);?>
/attachment/sport_logo/<?php echo $_smarty_tpl->tpl_vars['soccer_type_value']->value['logo'];?>
" alt="<?php echo $_smarty_tpl->tpl_vars['soccer_type_value']->value['fa'];?>
">
          <div class="slider-text">
            <p><?php echo $_smarty_tpl->tpl_vars['soccer_type_value']->value['fa'];?>
</p>
            <p><?php echo $_smarty_tpl->tpl_vars['soccer_number']->value[$_smarty_tpl->tpl_vars['soccer_type_value']->value['en']];?>
</p>
          </div>
          </a> </div>
        <?php
}
}
$_smarty_tpl->smarty->ext->_foreach->restore($_smarty_tpl, 1);
?>
 
        <!--partner_item1 end-->
 
      </div>
      <!------------------------ End sport slider --------------------> 
      <i class="fa-loader fa fa-spinner fa-pulse" style="display: none;"></i>
      <div class="panel-group star-checkbox inplaytable nohover" id="accordion" role="tablist" aria-multiselectable="true"> 
        
        <!--------------------------------------------------------------------- --> 
        
        <?php $_smarty_tpl->_assignInScope('j', 0);
?>
        <?php $_smarty_tpl->_assignInScope('k', 0);
?>
        
        <?php
$_from = $_smarty_tpl->smarty->ext->_foreach->init($_smarty_tpl, $_smarty_tpl->tpl_vars['competition']->value, 'soccer_value', false, 'soccer_key');
if ($_from !== null) {
foreach ($_from as $_smarty_tpl->tpl_vars['soccer_key']->value => $_smarty_tpl->tpl_vars['soccer_value']->value) {
?>
        
        <?php if (sizeof($_smarty_tpl->tpl_vars['soccer_value']->value) > 0) {?>
        <div class="col-xs-12 panel nopadding sport-type" id="accordion1" data-sport="<?php echo $_smarty_tpl->tpl_vars['soccer_key']->value;?>
">
          <div class="col-xs-12 panel-heading sport-cat"  data-toggle="collapse" data-target="#<?php echo $_smarty_tpl->tpl_vars['soccer_key']->value;?>
" data-parent="#accordion1" data-soccer-type="<?php echo $_smarty_tpl->tpl_vars['soccer_key']->value;?>
">
            <h4 class="panel-title">
              <a role="button" data-toggle="collapse" data-parent="#accordion1" href="#<?php echo $_smarty_tpl->tpl_vars['soccer_key']->value;?>
" aria-expanded="true" aria-controls="collapseOne" class="match">
                <b class="mark-header"><?php echo $_smarty_tpl->tpl_vars['soccer_type']->value[$_smarty_tpl->tpl_vars['soccer_key']->value]['fa'];?>
 </b>
                <span>(<?php echo $_smarty_tpl->tpl_vars['soccer_number']->value[$_smarty_tpl->tpl_vars['soccer_key']->value];?>
)</span>
              </a>
            </h4>
          </div>
          <div  id="<?php echo $_smarty_tpl->tpl_vars['soccer_key']->value;?>
" class="col-xs-12 clearfix panel-collapse collapse in nopadding" role="tabpanel" aria-labelledby="headingOne"> 
            <!--------------------------start inner pannel --------------> 
            <div class="col-xs-12 panel nopadding" id="inner-panel">
            <?php
$_from = $_smarty_tpl->smarty->ext->_foreach->init($_smarty_tpl, $_smarty_tpl->tpl_vars['soccer_value']->value, 'matches1', false, 'key');
if ($_from !== null) {
foreach ($_from as $_smarty_tpl->tpl_vars['key']->value => $_smarty_tpl->tpl_vars['matches1']->value) {
?>
            
				<?php if (!empty($_smarty_tpl->tpl_vars['matches1']->value)) {?>
              <?php
$_from = $_smarty_tpl->smarty->ext->_foreach->init($_smarty_tpl, $_smarty_tpl->tpl_vars['matches1']->value, 'matches2', false, 'key2');
if ($_from !== null) {
foreach ($_from as $_smarty_tpl->tpl_vars['key2']->value => $_smarty_tpl->tpl_vars['matches2']->value) {
?>
              

              <div class="col-xs-12 panel-heading inplayheader inplayheaders" data-leagueid="<?php echo $_smarty_tpl->tpl_vars['matches2']->value->competition->id;?>
" data-toggle="collapse" data-target="#c<?php echo $_smarty_tpl->tpl_vars['k']->value;?>
" data-parent="#inner-panel" data-rankid="<?php echo $_smarty_tpl->tpl_vars['matches2']->value->competition->id;?>
">
              <h4 class="panel-title"> <a role="button" data-toggle="collapse" data-parent="#inner-panel" href="#c<?php echo $_smarty_tpl->tpl_vars['k']->value;?>
" aria-expanded="true" aria-controls="collapseOne" class="match"><b><?php echo $_smarty_tpl->tpl_vars['matches2']->value->competition->name;?>
</b></a> </h4>
            </div>
            <?php
break 1;?>
          <?php
}
}
$_smarty_tpl->smarty->ext->_foreach->restore($_smarty_tpl, 1);
?>

          <?php $_smarty_tpl->_assignInScope('i', 0);
?>
          <div  id="c<?php echo $_smarty_tpl->tpl_vars['k']->value++;?>
" class="col-xs-12 clearfix panel-collapse collapse in nopadding" role="tabpanel" aria-labelledby="headingOne"> <?php
$_from = $_smarty_tpl->smarty->ext->_foreach->init($_smarty_tpl, $_smarty_tpl->tpl_vars['matches1']->value, 'match_team', false, 'key1');
if ($_from !== null) {
foreach ($_from as $_smarty_tpl->tpl_vars['key1']->value => $_smarty_tpl->tpl_vars['match_team']->value) {
?>
            
            <?php if (!empty($_smarty_tpl->tpl_vars['match_team']->value->odds->data)) {?>
            <?php if (!($_smarty_tpl->tpl_vars['match_team']->value->status == 'LIVE' || $_smarty_tpl->tpl_vars['match_team']->value->status == 'HT')) {?>
            <?php $_smarty_tpl->_assignInScope('hidden', 'hidden');
?>
            <?php } else { ?>
            <?php $_smarty_tpl->_assignInScope('hidden', '');
?>
            <?php }?>
            <div data-eventid="<?php echo $_smarty_tpl->tpl_vars['match_team']->value->id;?>
" data-marketid="" class="col-xs-12 panel-body odddetails <?php echo $_smarty_tpl->tpl_vars['hidden']->value;?>
" leagueid="<?php echo $_smarty_tpl->tpl_vars['match_team']->value->competition->id;?>
" sport-type="<?php echo $_smarty_tpl->tpl_vars['soccer_key']->value;?>
">
              <div class="col-lg-6 col-md-12 col-xs-12 p5 inner-pad">
                <div class="col-md-10 col-xs-12 nopadding">
                  <b class="host ellipsis"><?php echo $_smarty_tpl->tpl_vars['match_team']->value->homeTeam->name;?>
</b>
                  <span class="inplayscore"><?php echo $_smarty_tpl->tpl_vars['match_team']->value->home_score;?>
 - <?php echo $_smarty_tpl->tpl_vars['match_team']->value->away_score;?>
</span>
                  <b class="guest ellipsis"><?php echo $_smarty_tpl->tpl_vars['match_team']->value->awayTeam->name;?>
</b>
                  <span class="inplaytime pull-left" style="direction: rtl">' <?php echo $_smarty_tpl->tpl_vars['match_team']->value->minute;?>
</span>
                  <input class="eninplaytime" type="hidden" value="<?php echo $_smarty_tpl->tpl_vars['match_team']->value->period;?>
">
                </div>
              </div>
              <div class="col-lg-5 col-md-12 col-xs-12 nopadding ">
                <div class='eventodds'> <span class="eventsuspended hidden">غیر فعال</span>
                  <ul class='mlodds'>
                    <?php $_smarty_tpl->_assignInScope('label', array('1','X','2'));
?>
                    <?php
$_from = $_smarty_tpl->smarty->ext->_foreach->init($_smarty_tpl, $_smarty_tpl->tpl_vars['label']->value, 'value');
if ($_from !== null) {
foreach ($_from as $_smarty_tpl->tpl_vars['value']->value) {
?>
                    <?php
$_smarty_tpl->tpl_vars['i'] = new Smarty_Variable(null, $_smarty_tpl->isRenderingCache);
$_smarty_tpl->tpl_vars['i']->value = 0;
if ($_smarty_tpl->tpl_vars['i']->value < 3) {
for ($_foo=true;$_smarty_tpl->tpl_vars['i']->value < 3; $_smarty_tpl->tpl_vars['i']->value++) {
?>
                    <?php if ($_smarty_tpl->tpl_vars['value']->value == $_smarty_tpl->tpl_vars['match_team']->value->odds->data[0]->types->data[0]->odds->data[$_smarty_tpl->tpl_vars['i']->value]->label) {?>
                    <li data-runnerid="<?php echo smarty_modifier_con($_smarty_tpl->tpl_vars['match_team']->value->id,'-',$_smarty_tpl->tpl_vars['match_team']->value->odds->data[0]->bookmaker_id,'-1x2-',$_smarty_tpl->tpl_vars['match_team']->value->odds->data[0]->types->data[0]->odds->data[$_smarty_tpl->tpl_vars['i']->value]->label);?>
"
						data-pick="<?php echo $_smarty_tpl->tpl_vars['match_team']->value->odds->data[0]->types->data[0]->odds->data[$_smarty_tpl->tpl_vars['i']->value]->label;?>
" data-points='' data-bet-sport="<?php echo $_smarty_tpl->tpl_vars['soccer_key']->value;?>
" class="col-sm-4 col-xs-4 inplaybtn eventodd"> <strong class="col-xs-2 nopadding text-right"> <?php if ($_smarty_tpl->tpl_vars['value']->value == '1') {?>
                      1
                      <?php } elseif ($_smarty_tpl->tpl_vars['value']->value == '2') {?>
                      2
                      <?php } else { ?>
                      x
                      <?php }?> </strong> 
                      <!--                                                                                    <i class="fa fa-caret-up up-arrow"></i>--> 
                      <span class="col-xs-10 nopadding text-left"> <?php if ($_smarty_tpl->tpl_vars['match_team']->value->odds->data[0]->types->data[0]->odds->data[$_smarty_tpl->tpl_vars['i']->value]->suspend == 0) {?>
                      <?php echo $_smarty_tpl->tpl_vars['match_team']->value->odds->data[0]->types->data[0]->odds->data[$_smarty_tpl->tpl_vars['i']->value]->value;?>

                      <?php } else { ?>
                      00.00
                      <?php }?> </span> </li>
                    <?php }?>
                    <?php }
}
?>

                    <?php
}
}
$_smarty_tpl->smarty->ext->_foreach->restore($_smarty_tpl, 1);
?>

                  </ul>
                </div>
              </div>
              <div class="col-xs-1 more-bet"> <a class="has-tip fa fa-plus-circle more" title="شرط های بیشتر" href="<?php echo smarty_function_site_url(array(),$_smarty_tpl);?>
bets/InplayOdds/<?php echo $_smarty_tpl->tpl_vars['match_team']->value->id;?>
/<?php echo $_smarty_tpl->tpl_vars['soccer_key']->value;?>
"></a> </div>
            </div>
            <?php $_smarty_tpl->_assignInScope('i', $_smarty_tpl->tpl_vars['i']->value+1);
?>
            <?php }?>
            <?php
}
}
$_smarty_tpl->smarty->ext->_foreach->restore($_smarty_tpl, 1);
?>
 </div>
          <?php } else { ?>
          <div class="col-xs-12"> هیچ مسابقه ای برگزار نخواهد شد. </div>
          <?php }?>
          
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

    <input id="lastupdate" type="hidden" value="<?php echo $_smarty_tpl->tpl_vars['lastUpdated']->value;?>
">
    </div>
    </li>
    <li id="predict" class="col-lg-3 col-md-3 col-sm-12 col-xs-12 nopadding inplay-controller">
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
              <li>برد احتمالی (TRX) <span class="totalwin">0</span></li>
            </ul>
            <div class="livescore">
              <div>
                <button class="totobutton smallbutton placebet disabled">ثبت شرط</button>
              </div>
              <div class="del"> <a class="deleteall" href="javascript:void(0)">حذف همه</a></div>
            </div>
          </div>
        </div>
        <div class="alertbox alertbox2 col-lg-12 col-md-12 col-xs-12 idden"></div>
      </div>
    </li>
  </ul>
</div><?php }
}
