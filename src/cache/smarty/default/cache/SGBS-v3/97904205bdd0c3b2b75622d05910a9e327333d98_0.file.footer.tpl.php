<?php
/* Smarty version 3.1.31, created on 2020-11-07 14:19:29
  from "/home/vip90/public_html/themes/default/SGBS-v3/partials/footer.tpl" */

/* @var Smarty_Internal_Template $_smarty_tpl */
if ($_smarty_tpl->_decodeProperties($_smarty_tpl, array (
  'version' => '3.1.31',
  'unifunc' => 'content_5fa67bb97b6f35_99157593',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    '97904205bdd0c3b2b75622d05910a9e327333d98' => 
    array (
      0 => '/home/vip90/public_html/themes/default/SGBS-v3/partials/footer.tpl',
      1 => 1604696352,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
  ),
),false)) {
function content_5fa67bb97b6f35_99157593 (Smarty_Internal_Template $_smarty_tpl) {
if (!is_callable('smarty_function_site_url')) require_once '/home/vip90/public_html/application/smarty/plugins/function.site_url.php';
if (!is_callable('smarty_function_setting')) require_once '/home/vip90/public_html/application/modules/settings/smarty/plugins/function.setting.php';
if (!is_callable('smarty_function_assets_url')) require_once '/home/vip90/public_html/application/smarty/plugins/function.assets_url.php';
if (!is_callable('smarty_function_footer_js')) require_once '/home/vip90/public_html/application/smarty/plugins/function.footer_js.php';
?>
</div>
</section>
	<div class="mobile mobile-footer-bar">
		<a href="<?php echo smarty_function_site_url(array(),$_smarty_tpl);?>
" class="sport active">Home</a>

		<a href="<?php echo smarty_function_site_url(array(),$_smarty_tpl);?>
bets/casino" class="casino-m ">Decentralized online casino</a>
		<a href="<?php echo smarty_function_site_url(array(),$_smarty_tpl);?>
dashboard" class="account ">Profile</a>
	</div>
<footer class="col-lg-12 col-md-12 col-xs-12 footertop clearfix">
    <div class="col-md-4 col-sm-4 col-xs-12 footer_right">
        <h3 class="footer_head">General terms and conditions</h3>
        <ul class="gen_hel">
            <?php
$_from = $_smarty_tpl->smarty->ext->_foreach->init($_smarty_tpl, $_smarty_tpl->tpl_vars['footer_right']->value, 'right');
if ($_from !== null) {
foreach ($_from as $_smarty_tpl->tpl_vars['right']->value) {
?>
                <li><a href="<?php echo smarty_function_site_url(array(),$_smarty_tpl);
echo $_smarty_tpl->tpl_vars['right']->value->target;?>
"><?php echo $_smarty_tpl->tpl_vars['right']->value->title;?>
</a></li>
                <?php
}
}
$_smarty_tpl->smarty->ext->_foreach->restore($_smarty_tpl, 1);
?>

        </ul>
    </div>
    <div class="col-md-4 col-sm-4 col-xs-12 footer_center">
        <h3 class="footer_head">Links</h3>
        <ul class="gen_hel">
            <?php
$_from = $_smarty_tpl->smarty->ext->_foreach->init($_smarty_tpl, $_smarty_tpl->tpl_vars['footer_mid']->value, 'middle');
if ($_from !== null) {
foreach ($_from as $_smarty_tpl->tpl_vars['middle']->value) {
?>
                <li><a href="<?php echo $_smarty_tpl->tpl_vars['middle']->value->target;?>
"><?php echo $_smarty_tpl->tpl_vars['middle']->value->title;?>
</a></li>

            <?php
}
}
$_smarty_tpl->smarty->ext->_foreach->restore($_smarty_tpl, 1);
?>

        </ul>
    </div>
    <div class="col-md-4 col-sm-4 col-xs-12 footer_left">
        <h3 class="footer_head">Social media    </h3>
        <ul class="social" style="margin:auto !important;">
            <!--            <li class="fac"><a href="# " target="_blank"></a></li>-->
            <li class="ins"><a href="https://instagram.com/<?php echo smarty_function_setting(array('name'=>'instagram'),$_smarty_tpl);?>
" target="_blank"></a></li>
            <li class="tel"><a href="https://t.me/<?php echo smarty_function_setting(array('name'=>'telegram'),$_smarty_tpl);?>
" target="_blank"></a></li>
            <!--            <li class="goo"><a href="#" target="_blank"></a></li>-->
        </ul>
    </div>

<div class="copy_right">
    <div class="container">
        <a href="<?php echo smarty_function_site_url(array(),$_smarty_tpl);?>
" target="_blank"><?php echo date('Y');?>
 &#169; <?php echo smarty_function_setting(array('name'=>'footer'),$_smarty_tpl);?>
</a>
    </div>
    <div class="container" style="color:#999;">
       Designed and supported by <a href="                  " title="       " target="_blank"> TronTower LTD</a>
    </div>
</div>
</footer>



</div>
<?php echo '<script'; ?>
>
    $('.dropdown-toggle').dropdown();
<?php echo '</script'; ?>
>
<?php echo '<script'; ?>
 src="<?php echo smarty_function_assets_url(array(),$_smarty_tpl);?>
/bundles/ajax.js"><?php echo '</script'; ?>
>
<?php echo '<script'; ?>
 src="<?php echo smarty_function_assets_url(array(),$_smarty_tpl);?>
/bundles/jquery.chiz.js"><?php echo '</script'; ?>
>

<?php echo '<script'; ?>
 src="<?php echo smarty_function_assets_url(array(),$_smarty_tpl);?>
/bundles/jqueryui.js"><?php echo '</script'; ?>
>
<?php echo smarty_function_footer_js(array(),$_smarty_tpl);?>


<!--<?php echo '<script'; ?>
 src="<?php echo smarty_function_assets_url(array(),$_smarty_tpl);?>
/bundles/updates.js"><?php echo '</script'; ?>
>-->
<?php echo '<script'; ?>
 src="<?php echo smarty_function_assets_url(array(),$_smarty_tpl);?>
/bundles/sport.js"><?php echo '</script'; ?>
>
<?php echo '<script'; ?>
 src="<?php echo smarty_function_assets_url(array(),$_smarty_tpl);?>
/bundles/accordian.js" type="text/javascript"><?php echo '</script'; ?>
>
<?php echo '<script'; ?>
 src="<?php echo smarty_function_assets_url(array(),$_smarty_tpl);?>
/bundles/owl.carousel.min.js" type="text/javascript"><?php echo '</script'; ?>
>
</body><?php }
}
