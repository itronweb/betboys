<?php
/* Smarty version 3.1.31, created on 2020-11-07 12:36:03
  from "/home/vip90/public_html/themes/default/SGBS-v3/modules/payment/transactions.tpl" */

/* @var Smarty_Internal_Template $_smarty_tpl */
if ($_smarty_tpl->_decodeProperties($_smarty_tpl, array (
  'version' => '3.1.31',
  'unifunc' => 'content_5fa6637be905d6_71678552',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    '69b765fdaf5a820d4180b624b07c5a52b77abf5d' => 
    array (
      0 => '/home/vip90/public_html/themes/default/SGBS-v3/modules/payment/transactions.tpl',
      1 => 1604697114,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
    'file:partials/dashboard_menu.tpl' => 1,
  ),
),false)) {
function content_5fa6637be905d6_71678552 (Smarty_Internal_Template $_smarty_tpl) {
if (!is_callable('smarty_function_jdate')) require_once '/home/vip90/public_html/application/smarty/plugins/function.jdate.php';
if (!is_callable('smarty_modifier_price_format')) require_once '/home/vip90/public_html/application/smarty/plugins/modifier.price_format.php';
?>
 <div>
    <div class="cell more-pad">
        <div class="container">
            <div class="maincontent clearfix">
                <?php $_smarty_tpl->_subTemplateRender('file:partials/dashboard_menu.tpl', $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, $_smarty_tpl->cache_lifetime, array(), 0, false);
?>

                <div class="col-lg-10 col-md-9 col-sm-12 col-xs-12 content transaction">
                    <header>
                        <h1>Transaction history</h1>
                    </header>
                    <div class="responsive_tbl">
                        <table class="table nopointer">
                            <thead>
                                <tr>
                                    <th width="20%" scope="col"> Transaction tracking number </th>
                                    <th width="20%" scope="col">Transaction   Time  </th>
                                    <th width="20%" scope="col"> Type of transaction </th>
                                    <th width="20%" scope="col"> Tron amont (TRX)   </th>
                                    <th width="20%" scope="col"> Balace  </th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if ($_smarty_tpl->tpl_vars['transactions']->value->isEmpty()) {?>
                                    <tr>
                                        <td>No record found</td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                    </tr>
                                <?php } else { ?>
                                    <?php
$_from = $_smarty_tpl->smarty->ext->_foreach->init($_smarty_tpl, $_smarty_tpl->tpl_vars['transactions']->value, 'val');
if ($_from !== null) {
foreach ($_from as $_smarty_tpl->tpl_vars['val']->value) {
?> 
                                        <tr>
                                            <td data-label="Transaction tracking number ">
                                                <?php echo $_smarty_tpl->tpl_vars['val']->value['trans_id'];?>

                                            </td>
                                            <td data-label="   Time of transaction    " >
                                                <?php echo smarty_function_jdate(array('format'=>'j F Y','date'=>$_smarty_tpl->tpl_vars['val']->value['created_at']),$_smarty_tpl);?>

                                            </td>
                                            <td data-label="Type of transaction ">
                                                <b><?php echo $_smarty_tpl->tpl_vars['val']->value->description;?>
</b>
                                            </td>
                                            <td data-label="  Amount (TRX) " style="font-weight:bold">
                                                <?php echo smarty_modifier_price_format($_smarty_tpl->tpl_vars['val']->value['price']);?>
                        
                                            </td>
                                            <td data-label="Balace " style="font-weight:bold">
                                                <?php echo smarty_modifier_price_format($_smarty_tpl->tpl_vars['val']->value['cash']);?>

                                            </td>
                                        </tr>
                                    <?php
}
}
$_smarty_tpl->smarty->ext->_foreach->restore($_smarty_tpl, 1);
?>

                                <?php }?>
                            </tbody>
                        </table>
                    </div>
                </div>

            </div>
        </div>
    </div>
<?php }
}
