<?php
/* Smarty version 3.1.31, created on 2020-11-07 14:19:29
  from "/home/vip90/public_html/themes/default/SGBS-v3/partials/header_menu.tpl" */

/* @var Smarty_Internal_Template $_smarty_tpl */
if ($_smarty_tpl->_decodeProperties($_smarty_tpl, array (
  'version' => '3.1.31',
  'unifunc' => 'content_5fa67bb97ab755_52314782',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    '1d74411a99fa6fea1829dca02b9982409d661fd9' => 
    array (
      0 => '/home/vip90/public_html/themes/default/SGBS-v3/partials/header_menu.tpl',
      1 => 1604733081,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
  ),
),false)) {
function content_5fa67bb97ab755_52314782 (Smarty_Internal_Template $_smarty_tpl) {
if (!is_callable('smarty_function_assets_url')) require_once '/home/vip90/public_html/application/smarty/plugins/function.assets_url.php';
if (!is_callable('smarty_function_site_url')) require_once '/home/vip90/public_html/application/smarty/plugins/function.site_url.php';
if (!is_callable('smarty_modifier_price_format')) require_once '/home/vip90/public_html/application/smarty/plugins/modifier.price_format.php';
?>
<center>
		<div class="header desktop">
			<div class="head-top-place-holder"></div>
			<div class="head-top">
				<div class="pr15">
					<div class="left mt5"><a href="/"><img src="<?php echo smarty_function_assets_url(array(),$_smarty_tpl);?>
/images/logo.png" height="75"></a></div>
					<div class="left ml15 mt10"><a href="<?php echo smarty_function_site_url(array(),$_smarty_tpl);?>
contacts/tickets/ticket-list"><img src="<?php echo smarty_function_assets_url(array(),$_smarty_tpl);?>
/images/support1.png" class="mini-icon"></a></div>
					<div class="left ml10 mt10"><a href="<?php echo smarty_function_site_url(array(),$_smarty_tpl);?>
bets/myrecords"><img src="<?php echo smarty_function_assets_url(array(),$_smarty_tpl);?>
/images/bonus.png" class="mini-icon-yellow"></a></div> 
					<div class="left ml10 mt10"><a href="<?php echo smarty_function_site_url(array(),$_smarty_tpl);?>
contacts/tickets/ticket-list"><img src="<?php echo smarty_function_assets_url(array(),$_smarty_tpl);?>
/images/help.png" class="mini-icon"></a></div>

					<div class="right">
					<?php if (!$_smarty_tpl->tpl_vars['is_logged_in']->value) {?>
							<a href="<?php echo smarty_function_site_url(array(),$_smarty_tpl);?>
users/login" class="link">Login </a>
							<a href="<?php echo smarty_function_site_url(array(),$_smarty_tpl);?>
users/register" class="link"> Sign up</a>
					<?php } else { ?>
							<a href="<?php echo smarty_function_site_url(array(),$_smarty_tpl);?>
dashboard" class="link"><?php echo $_smarty_tpl->tpl_vars['user']->value->first_name;?>
  <?php echo $_smarty_tpl->tpl_vars['user']->value->last_name;?>
</a>
							<a href="<?php echo smarty_function_site_url(array(),$_smarty_tpl);?>
payment/credit" class="link"><?php echo smarty_modifier_price_format($_smarty_tpl->tpl_vars['user']->value->cash);?>
</a>
							<a href="<?php echo smarty_function_site_url(array(),$_smarty_tpl);?>
users/logout" class="link">Exit</a>
					<?php }?>
					</div>

					<div class="clear"></div>
				</div>
			</div>
			<div class="head-bottom">
				<div class="pr15">
					<span class="hijri_cal pull-left hide">     11/06/2020  <div class="digitalclock"></div> </span>
					<div class="left top-bar desktop">
						<div class="container inline">
							<a href="<?php echo smarty_function_site_url(array(),$_smarty_tpl);?>
bets/inplayBet">Sport (comming soon)</a>
							<a href="<?php echo smarty_function_site_url(array(),$_smarty_tpl);?>
bets/upcoming" class="">             </a>
							<a href="<?php echo smarty_function_site_url(array(),$_smarty_tpl);?>
bets/casino" class="">Decentralized casino</a>
							<a href="<?php echo smarty_function_site_url(array(),$_smarty_tpl);?>
Newgame/games/roulette" class="">  Roulette     </a>		
							<a href="<?php echo smarty_function_site_url(array(),$_smarty_tpl);?>
Newgame/games/baccarat/" class="">     Baccarat    </a>
														<a href="/newGames/games/crash" class="">CRASH</a>
							<a href="<?php echo smarty_function_site_url(array(),$_smarty_tpl);?>
Newgame/games/slot/" class="">   Slot     </a>		
							<a href="<?php echo smarty_function_site_url(array(),$_smarty_tpl);?>
contacts/tickets/ticket-list" class="">Support</a>
							<a href="<?php echo smarty_function_site_url(array(),$_smarty_tpl);?>
users/representation" class="">   Referral     </a>
						</div>
					</div>
					<div class="clear"></div>
				</div>
			</div>
		</div>

		<?php echo '<script'; ?>
>
			$(document).ready(function() {
				setInterval(function() {
					var d = new Date();
					var _hou = d.getHours();
					var _min = d.getMinutes();
					var _sec = d.getSeconds();
					_hou = _hou<10?('0'+_hou):(_hou);
					_min = _min<10?('0'+_min):(_min);
					_sec = _sec<10?('0'+_sec):(_sec);
					$(".js-clock-place").html(_hou+":"+_min+":"+_sec);
				}, 1000);
			});
		<?php echo '</script'; ?>
>
    <?php echo '<script'; ?>
>
        $(document).ready(function () {
            $(document).ready(function () {
                var sideslider = $('[data-toggle=collapse-side]');
                var sel = sideslider.attr('data-target');
                var sel2 = sideslider.attr('data-target-2');
                sideslider.click(function (event) {
                    $(sel).toggleClass('in');
                    $(sel2).toggleClass('out');
                });
            });
        });
    <?php echo '</script'; ?>
>

		<div class="header container mobile mobile-bar">
			<div class="icon"><a href="javascript:;" class="mobile-menu-action fa fa-bars"></a></div>
			<div class="icon"></div>
			<div class="logo"><a href="/default"><img src="<?php echo smarty_function_assets_url(array(),$_smarty_tpl);?>
/images/logo.png" class="mt5" height="35"></a></div>
			<div class="icon"></div>
			<div class="icon"></div>
			<div class="clear"></div>
		</div>
		<div class="mobile mobile-bar-holder"></div>

	</center>

	<div class="mobile-menu mobile">
		<div class="buttons">
			<a style="border-color:#CCC; border-width:1; background-color:#0e610f;" href="<?php echo smarty_function_site_url(array(),$_smarty_tpl);?>
users/login">Login to account   </a>
			<a href="<?php echo smarty_function_site_url(array(),$_smarty_tpl);?>
users/register" class="signup-button">sign up</a>
		</div>

		
		<div class="items">
			<a href="<?php echo smarty_function_site_url(array(),$_smarty_tpl);?>
">HOME</a>
			<a href="<?php echo smarty_function_site_url(array(),$_smarty_tpl);?>
bets/inplayBet">     </a>
			<a href="<?php echo smarty_function_site_url(array(),$_smarty_tpl);?>
bets/upcoming" class="">          </a>
			<a href="<?php echo smarty_function_site_url(array(),$_smarty_tpl);?>
bets/casino" class="">   Decentralized casino       </a>
			<a href="Newgame/games/roulette" class="">Roulette</a>		
			<a href="<?php echo smarty_function_site_url(array(),$_smarty_tpl);?>
/Newgame/games/baccarat" class="">Baccarat</a>
			<a href="<?php echo smarty_function_site_url(array(),$_smarty_tpl);?>
/Newgame/games/slot" class="">Slot</a>		
			<a href="<?php echo smarty_function_site_url(array(),$_smarty_tpl);?>
contacts/tickets/ticket-list" class="">Support</a>
			<a href="<?php echo smarty_function_site_url(array(),$_smarty_tpl);?>
users/representation" class="">Referral plan</a>
		</div>

	</div>
<?php }
}
