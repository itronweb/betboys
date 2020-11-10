<?php
/* Smarty version 3.1.31, created on 2020-11-07 12:47:51
  from "/home/vip90/public_html/themes/default/SGBS-v3/modules/users/Withdraw.tpl" */

/* @var Smarty_Internal_Template $_smarty_tpl */
if ($_smarty_tpl->_decodeProperties($_smarty_tpl, array (
  'version' => '3.1.31',
  'unifunc' => 'content_5fa6663fe4c370_93319007',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    '5d59eb0e13ab82a8c46e717896814cdaef3bc326' => 
    array (
      0 => '/home/vip90/public_html/themes/default/SGBS-v3/modules/users/Withdraw.tpl',
      1 => 1604726562,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
    'file:partials/dashboard_menu.tpl' => 1,
  ),
),false)) {
function content_5fa6663fe4c370_93319007 (Smarty_Internal_Template $_smarty_tpl) {
if (!is_callable('smarty_modifier_price_format')) require_once '/home/vip90/public_html/application/smarty/plugins/modifier.price_format.php';
if (!is_callable('smarty_function_jdate')) require_once '/home/vip90/public_html/application/smarty/plugins/function.jdate.php';
if (!is_callable('smarty_function_site_url')) require_once '/home/vip90/public_html/application/smarty/plugins/function.site_url.php';
?>
<div>
    <div class="cell more-pad">
        <div class="container">
            <div class="maincontent">
                <?php $_smarty_tpl->_subTemplateRender("file:partials/dashboard_menu.tpl", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, $_smarty_tpl->cache_lifetime, array(), 0, false);
?>

                <div class="content col-lg-10 col-md-9 col-sm-12 col-xs-12">
                    <header>
                        <h1 class="reg_head">&#1583;&#1585;&#1740;&#1575;&#1601;&#1578; &#1580;&#1575;&#1740;&#1586;&#1607;</h1>
                    </header>
                    <div class="row topup-form">

                        <div class="col-xs-12 clearfix withdraw-top">
                            <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
                                <ul class="text-black">
                                    <li>
                                        &#1581;&#1583;&#1575;&#1602;&#1604; &#1605;&#1576;&#1604;&#1594; &#1602;&#1575;&#1576;&#1604; &#1576;&#1585;&#1583;&#1575;&#1588;&#1578; 50000 &#1578;&#1608;&#1605;&#1575;&#1606; &#1605;&#1740; &#1576;&#1575;&#1588;&#1583;.
                                    </li>
                                    <li>
                                     Prizes are deposited in the form of a smart system (TRX).<br>
                                    </li>
                                    <li>
                                        &#1662;&#1585; &#1705;&#1585;&#1583;&#1606; &#1605;&#1608;&#1575;&#1585;&#1583; &#1587;&#1578;&#1575;&#1585;&#1607; &#1583;&#1575;&#1585; &#1575;&#1604;&#1586;&#1575;&#1605;&#1740; &#1575;&#1587;&#1578;
                                    </li>
                                </ul>
                            </div>

                            <div class="report col-lg-6 col-md-6 col-sm-12 col-xs-12">
                                <div class="col-lg-10 col-md-10 col-sm-12 col-xs-12 withdraw-price">
                                    <span class="report-title">&#1605;&#1576;&#1604;&#1594; &#1602;&#1575;&#1576;&#1604; &#1576;&#1585;&#1583;&#1575;&#1588;&#1578;</span>
                                    <span class="report-data"><?php echo smarty_modifier_price_format($_smarty_tpl->tpl_vars['cash']->value);?>
</span>
                                </div>
                            </div>
                        </div>
                        <div class="clearboth"></div>
                        <section class=" topup-content">
                            <div class="topup-form" style="margin-bottom:10px;">
                                <section class="sitebox">
                                    <div class="btn"></div>
                                    <form action="<?php echo $_smarty_tpl->tpl_vars['action']->value;?>
" method="post">
                                        <div class="siteform">
                                            <div class="col-xs-12">
                                                <label class="label col-md-3 col-xs-12" for="Amount">&#1605;&#1576;&#1604;&#1594; &#1576;&#1607; &#1578;&#1608;&#1605;&#1575;&#1606; *</label>
                                                <input class="input col-md-9 col-xs-12 ltrinput centre" data-val="true" data-val-number="The field &#1605;&#1576;&#1604;&#1594; &#1576;&#1607; &#1578;&#1608;&#1605;&#1575;&#1606; must be a number." data-val-range="You have only 1340 available." data-val-range-max="1340" data-val-range-min="50000" data-val-regex="&#1605;&#1576;&#1604;&#1594; &#1576;&#1607; &#1578;&#1608;&#1605;&#1575;&#1606; &#1576;&#1575;&#1740;&#1583; &#1576;&#1575; &#1601;&#1585;&#1605;&#1578; &#1583;&#1585;&#1587;&#1578; &#1608;&#1575;&#1585;&#1583; &#1588;&#1608;&#1583;. " data-val-regex-pattern="^\d+$" data-val-required="&#1608;&#1575;&#1585;&#1583; &#1705;&#1585;&#1583;&#1606; &#1605;&#1576;&#1604;&#1594; &#1576;&#1607; &#1578;&#1608;&#1605;&#1575;&#1606; &#1575;&#1604;&#1586;&#1575;&#1605;&#1740; &#1575;&#1587;&#1578;." id="Amount" name="amount" value="" type="text">
                                                <p><span></span> <span id="change_amount_tron" /></span> </p>
                                                <span class="col-xs-12 field-validation-valid error_message" data-valmsg-for="Amount" data-valmsg-replace="true"><?php echo form_error('amount');?>
</span>
                                                <span class="col-xs-12 error_message max_error"></span>
                                            </div>
                                            
                                            <div class="col-xs-12" >
                                                <label class="label col-md-3 col-xs-12" for='tron_address'>Address Wallet</label>
                                                <input class="input col-md-9 col-xs-12  ltrinput centre" id="tron_address" name="tron_address" value="" type="text">
                                            </div>
                                            <div class="withdraw-btn">
                                                <input class="btn btn-green btn-lg floatright" value="&#1583;&#1585;&#1582;&#1608;&#1575;&#1587;&#1578;" type="submit" name="submit_withdraw">
                                            </div>
                                        </div>
                                    </form>                           
                                </section>
                            </div>
                        </section>

                        <section class="formbox row_100 clearfix">
                            <div class="support_messages" style="display: block;">
                                <table class="leaguetable support">
                                    <tbody>
                                        <tr>
                                            <th>شناسه درخواست</th>   
											<th>تاریخ درخواست</th>
                                            <th>مبلغ درخواستی</th>
                                            <th>Status</th>
                                            <th>عملیات</th>
                                        </tr>
                                        <?php
$_from = $_smarty_tpl->smarty->ext->_foreach->init($_smarty_tpl, $_smarty_tpl->tpl_vars['withdrawList']->value, 'val');
if ($_from !== null) {
foreach ($_from as $_smarty_tpl->tpl_vars['val']->value) {
?>
                                            <tr>
                                                <td>    
                                                    <?php echo $_smarty_tpl->tpl_vars['val']->value->id;?>

                                                </td>
                                                <td>
                                                    <time datetime="">
                                                        <?php echo smarty_function_jdate(array('format'=>'j F Y - h:i a','date'=>$_smarty_tpl->tpl_vars['val']->value->created_at),$_smarty_tpl);?>

                                                    </time>
                                                </td>
                                                <td>
                                                    <?php echo smarty_modifier_price_format($_smarty_tpl->tpl_vars['val']->value->amount);?>

                                                </td>
                                                <td>
                                                    <?php if ($_smarty_tpl->tpl_vars['val']->value->status == 0) {?>
                                                         <span class="text-info">  
															 لغو شده از طرف مدیر
														 </span>
                                                    <?php } elseif ($_smarty_tpl->tpl_vars['val']->value->status == 1) {?>
                                                         <span class="text-info"> 
															 پرداخت شد
														 </span>
                                                    <?php } elseif ($_smarty_tpl->tpl_vars['val']->value->status == 2) {?>
                                                       <span class="text-info">  
														   درحال بررسی
													   </span>
                                                    <?php } elseif ($_smarty_tpl->tpl_vars['val']->value->status == 3) {?>
                                                       <span class="text-info"> 
														    لغو شده از طرف User
														</span>
                                                    <?php }?>
                                                </td>
												
                                                <td>
													<?php if ($_smarty_tpl->tpl_vars['val']->value->status == 2) {?>
                                                    <a href="<?php echo smarty_function_site_url(array(),$_smarty_tpl);?>
/users/Withdraw/<?php echo $_smarty_tpl->tpl_vars['val']->value->id;?>
" class="btn btn-danger text-white ">
														 لغو درخواست از طرف User
													</a>
													<?php }?>
                                                </td>
                                            </tr>
                                        <?php
}
}
$_smarty_tpl->smarty->ext->_foreach->restore($_smarty_tpl, 1);
?>

                                    </tbody>
                                </table>
                            </div>
                        </section>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <?php echo '<script'; ?>
>
        $(document).ready(function () {
            $('#Amount').keyup( function(){

                var change_amount 	= <?php echo $_smarty_tpl->tpl_vars['ratio_tron']->value;?>
,
                    amount 			= $(this).val(),
                    change_unit		= amount / change_amount;

//				$('#'+ change_amount_id).val( change_unit );
                $('#change_amount_tron').html( change_unit + ' ترون ' );



            })
        });
    <?php echo '</script'; ?>
><?php }
}
