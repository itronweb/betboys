<?php
/* Smarty version 3.1.31, created on 2020-11-07 13:15:48
  from "/home/vip90/public_html/themes/default/SGBS-v3/partials/dashboard_menu.tpl" */

/* @var Smarty_Internal_Template $_smarty_tpl */
if ($_smarty_tpl->_decodeProperties($_smarty_tpl, array (
  'version' => '3.1.31',
  'unifunc' => 'content_5fa66ccc6dbed8_27804719',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    'ba3dcd58fd4e850bfc2fffcded585d1636065a31' => 
    array (
      0 => '/home/vip90/public_html/themes/default/SGBS-v3/partials/dashboard_menu.tpl',
      1 => 1604724130,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
  ),
),false)) {
function content_5fa66ccc6dbed8_27804719 (Smarty_Internal_Template $_smarty_tpl) {
if (!is_callable('smarty_function_site_url')) require_once '/home/vip90/public_html/application/smarty/plugins/function.site_url.php';
?>

<div class="col-lg-2 col-md-3 col-sm-12 col-xs-12 menu">
    <ul class="default_menu">
        <li class="index">
            <a href="<?php echo smarty_function_site_url(array(),$_smarty_tpl);?>
dashboard">Account Summary</a>
        </li>
        <li class="transactions">
            <a href="<?php echo smarty_function_site_url(array(),$_smarty_tpl);?>
payment/transactions">Transaction history</a>
        </li>
        <li class="top-up">
            <a href="<?php echo smarty_function_site_url(array(),$_smarty_tpl);?>
payment/credit">Deposit</a>
        </li>
        <li class="withdraw">
            <a href="<?php echo smarty_function_site_url(array(),$_smarty_tpl);?>
users/Withdraw">Withdraw</a>
        </li>
  
        <li class="withdraw">
            <a href="<?php echo smarty_function_site_url(array(),$_smarty_tpl);?>
users/representation">Referrals</a>
        </li>
  
        <li class="manage">
            <a href="<?php echo smarty_function_site_url(array(),$_smarty_tpl);?>
users/changePass">Change your password</a>
        </li>

    </ul>

</div><?php }
}
