<?php
/* Smarty version 3.1.31, created on 2020-11-07 14:19:29
  from "/home/vip90/public_html/themes/default/SGBS-v3/modules/bets/Main.tpl" */

/* @var Smarty_Internal_Template $_smarty_tpl */
if ($_smarty_tpl->_decodeProperties($_smarty_tpl, array (
  'version' => '3.1.31',
  'unifunc' => 'content_5fa67bb977d5f5_80116104',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    '7f8e32e6c2e63bf8c2e767db461756f8d7793d4e' => 
    array (
      0 => '/home/vip90/public_html/themes/default/SGBS-v3/modules/bets/Main.tpl',
      1 => 1604725186,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
  ),
),false)) {
function content_5fa67bb977d5f5_80116104 (Smarty_Internal_Template $_smarty_tpl) {
if (!is_callable('smarty_function_searchArray')) require_once '/home/vip90/public_html/application/smarty/plugins/function.searchArray.php';
if (!is_callable('smarty_function_assets_url')) require_once '/home/vip90/public_html/application/smarty/plugins/function.assets_url.php';
if (!is_callable('smarty_function_site_url')) require_once '/home/vip90/public_html/application/smarty/plugins/function.site_url.php';
?>
   <?php if (!empty($_smarty_tpl->tpl_vars['matches']->value)) {?>
    <?php $_smarty_tpl->_assignInScope('i', 0);
?>
    <?php
$_from = $_smarty_tpl->smarty->ext->_foreach->init($_smarty_tpl, $_smarty_tpl->tpl_vars['matches']->value, 'match_team');
if ($_from !== null) {
foreach ($_from as $_smarty_tpl->tpl_vars['match_team']->value) {
?>
        <?php ob_start();
echo smarty_function_searchArray(array('key'=>'type','val'=>'1x2','array'=>$_smarty_tpl->tpl_vars['match_team']->value->odds->data[0]->types->data),$_smarty_tpl);
$_prefixVariable1=ob_get_clean();
if (!empty($_smarty_tpl->tpl_vars['match_team']->value->odds->data) && $_smarty_tpl->tpl_vars['match_team']->value->minute == 0 && $_prefixVariable1 != null && ($_smarty_tpl->tpl_vars['match_team']->value->status != 'FT' || $_smarty_tpl->tpl_vars['matche_team']->value->status != 'FT_PEN')) {?>
            <?php $_smarty_tpl->_assignInScope('i', $_smarty_tpl->tpl_vars['i']->value+1);
?>
        <?php }?>
    <?php
}
}
$_smarty_tpl->smarty->ext->_foreach->restore($_smarty_tpl, 1);
?>

<?php }?>
<div>
    <?php echo '<script'; ?>
 src="<?php echo smarty_function_assets_url(array(),$_smarty_tpl);?>
/Content/MENU.JS"><?php echo '</script'; ?>
>
    <div class="main-splash" data="0">
		
		<div class="left splash-container">
    <?php echo '<script'; ?>
 src="<?php echo smarty_function_assets_url(array(),$_smarty_tpl);?>
/Content/slider.js"><?php echo '</script'; ?>
>
			<section id="slider" class="container">
		<ul class="slider-wrapper">
		<?php
$_from = $_smarty_tpl->smarty->ext->_foreach->init($_smarty_tpl, $_smarty_tpl->tpl_vars['slideshow']->value, 'value_slide', false, 'key_slide');
if ($_from !== null) {
foreach ($_from as $_smarty_tpl->tpl_vars['key_slide']->value => $_smarty_tpl->tpl_vars['value_slide']->value) {
?>
		<li class="current-slide">
			<img src="<?php echo smarty_function_site_url(array(),$_smarty_tpl);?>
attachment/slideshow/<?php echo $_smarty_tpl->tpl_vars['value_slide']->value['image'];?>
" alt="<?php echo $_smarty_tpl->tpl_vars['value_slide']->value['title'];?>
">
		</li>
		<?php
}
}
$_smarty_tpl->smarty->ext->_foreach->restore($_smarty_tpl, 1);
?>

 

		</ul>
		<!-- Sombras -->
		<div class="slider-shadow"></div>
		
	</section>

		</div>

		<div class="left thumb-container">

			<a href="<?php echo smarty_function_site_url(array(),$_smarty_tpl);?>
bets/inplayBet">
				<img src="<?php echo smarty_function_assets_url(array(),$_smarty_tpl);?>
/images/mfootball.png"><br>
				Sport(Coming soon)		</a>
			<a href="/newGames/games/crash">
				<img src="<?php echo smarty_function_assets_url(array(),$_smarty_tpl);?>
/images/mpoker.png"><br>
		CRASH			</a>
			<a href="<?php echo smarty_function_site_url(array(),$_smarty_tpl);?>
help">
				<img src="<?php echo smarty_function_assets_url(array(),$_smarty_tpl);?>
/images/mbonus.png"><br>
				Help		</a>

		</div>

		<div class="clear"></div>

		
		<div class="main-games-box">

			
			<a href="<?php echo smarty_function_site_url(array(),$_smarty_tpl);?>
casino/games/roulette/" class="game-link">
				<img src="<?php echo smarty_function_assets_url(array(),$_smarty_tpl);?>
/images/rol.jpg">
			<div class="game-link-title"> Crash  </div>
			<!--	<div class="game-link-sub">    Users  online</div>
			</a>-->

			
			<a href="<?php echo smarty_function_site_url(array(),$_smarty_tpl);?>
casino/games/baccarat/" class="game-link game-middle">
				<img src="<?php echo smarty_function_assets_url(array(),$_smarty_tpl);?>
/images/baca.jpg">
				<!--<div class="game-link-title">Baccarat</div>
				<div class="game-link-sub">    Users online</div>-->
			</a>

			
			<a href="<?php echo smarty_function_site_url(array(),$_smarty_tpl);?>
casino/games/slot/" class="game-link">
				<img src="<?php echo smarty_function_assets_url(array(),$_smarty_tpl);?>
/images/slot.jpg">
				<!--<div class="game-link-title">Black Jack </div>
				<div class="game-link-sub">    Users online</div>-->
			</a>

			
		
		
			
			<div class="clear"></div>

		</div>

		
	</div>
</div>
<?php echo '<script'; ?>
 src="<?php echo smarty_function_assets_url(array(),$_smarty_tpl);?>
/bundles/owl.carousel.min.js" type="text/javascript"><?php echo '</script'; ?>
>
<?php echo '<script'; ?>
>
                        $(document).ready(function () {
                            $('.owl-carousel').owlCarousel({
								loop:true,
								touchDrag:true,
								pullDrag:true,
								mouseDrag:true,
                                rtl: true,
                                autoplay: true,
                                autoplayTimeout:3000,
                                autoplayHoverPause:true,
                                items: 1,
                         
                            });

                            $('.root-slider-indicator a').click(function () {
                                $('.root-slider-indicator a.active').removeClass('active');
                                $(this).addClass('active');
                            });
                        });
<?php echo '</script'; ?>
>
<?php }
}
