<?php
/* Smarty version 3.1.31, created on 2020-11-07 13:15:45
  from "/home/vip90/public_html/themes/default/SGBS-v3/modules/dashboard/dashboard.tpl" */

/* @var Smarty_Internal_Template $_smarty_tpl */
if ($_smarty_tpl->_decodeProperties($_smarty_tpl, array (
  'version' => '3.1.31',
  'unifunc' => 'content_5fa66cc9a4ad55_23961206',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    '052a97c98ce6fb024a9ed9c637ec99255f021380' => 
    array (
      0 => '/home/vip90/public_html/themes/default/SGBS-v3/modules/dashboard/dashboard.tpl',
      1 => 1604726414,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
    'file:partials/dashboard_menu.tpl' => 1,
  ),
),false)) {
function content_5fa66cc9a4ad55_23961206 (Smarty_Internal_Template $_smarty_tpl) {
if (!is_callable('smarty_modifier_persian_number')) require_once '/home/vip90/public_html/application/smarty/plugins/modifier.persian_number.php';
if (!is_callable('smarty_modifier_price_format')) require_once '/home/vip90/public_html/application/smarty/plugins/modifier.price_format.php';
?>
<div class="more-pad">
    <div class="cell">
        <div class="container">
            <div class="maincontent">
                <?php $_smarty_tpl->_subTemplateRender("file:partials/dashboard_menu.tpl", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, $_smarty_tpl->cache_lifetime, array(), 0, false);
?>

                <div class="col-lg-10 col-md-9 col-sm-12 col-xs-12 content">
                    <header>
                        <h1 class="reg_head">Account Summary</h1>
                    </header>
                    <div class="report">
                        <div class="col-lg-6 col-md-6 col-xs-12">
                            <div class="inner-card">
                                <span class="report-title">User ID</span>
                                <span class="report-data"><?php echo smarty_modifier_persian_number($_smarty_tpl->tpl_vars['user']->value->id);?>
</span>
                            </div>
                        </div>
                        <div class="col-lg-6 col-md-6 col-xs-12">
                            <div class="inner-card">
                                <span class="report-title">Balace </span>
                                <span class="report-data"><?php echo smarty_modifier_price_format($_smarty_tpl->tpl_vars['user']->value->cash);?>
</span>
                            </div>
                        </div>
                        <div class="col-lg-6 col-md-6 col-xs-12">
                            <div class="inner-card">
                                <span class="report-title">Total bets</span>
                                <span class="report-data"><?php echo smarty_modifier_persian_number($_smarty_tpl->tpl_vars['totalBetCount']->value);?>
 (<?php echo smarty_modifier_price_format($_smarty_tpl->tpl_vars['totalStake']->value);?>
)</span>
                            </div>
                        </div>
                        <div class="col-lg-6 col-md-6 col-xs-12">
                            <div class="inner-card">
                                <span class="report-title">Total Prizes</span>
                                <span class="report-data"><?php echo smarty_modifier_persian_number($_smarty_tpl->tpl_vars['giftCount']->value);?>
 (<?php echo smarty_modifier_price_format($_smarty_tpl->tpl_vars['totalGift']->value);?>
)</span>
                            </div>
                        </div>
                        <div class="col-lg-6 col-md-6 col-xs-12">
                            <div class="inner-card">
                                <span class="report-title">Total deposit</span>
                                <span class="report-data"><?php echo smarty_modifier_price_format($_smarty_tpl->tpl_vars['creditSum']->value);?>
</span>
                            </div>
                        </div>
                        <div class="col-lg-6 col-md-6 col-xs-12">
                            <div class="inner-card">
                                <span class="report-title">Total withdraw</span>
                                <span class="report-data"><?php echo smarty_modifier_price_format($_smarty_tpl->tpl_vars['withdrawSum']->value);?>
</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
<?php }
}
