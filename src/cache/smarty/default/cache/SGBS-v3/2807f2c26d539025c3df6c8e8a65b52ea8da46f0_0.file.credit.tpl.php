<?php
/* Smarty version 3.1.31, created on 2020-11-07 13:15:48
  from "/home/vip90/public_html/themes/default/SGBS-v3/modules/payment/credit.tpl" */

/* @var Smarty_Internal_Template $_smarty_tpl */
if ($_smarty_tpl->_decodeProperties($_smarty_tpl, array (
  'version' => '3.1.31',
  'unifunc' => 'content_5fa66ccc6d72e6_44138791',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    '2807f2c26d539025c3df6c8e8a65b52ea8da46f0' => 
    array (
      0 => '/home/vip90/public_html/themes/default/SGBS-v3/modules/payment/credit.tpl',
      1 => 1604741825,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
    'file:partials/dashboard_menu.tpl' => 1,
  ),
),false)) {
function content_5fa66ccc6d72e6_44138791 (Smarty_Internal_Template $_smarty_tpl) {
if (!is_callable('smarty_function_site_url')) require_once '/home/vip90/public_html/application/smarty/plugins/function.site_url.php';
if (!is_callable('smarty_function_assets_url')) require_once '/home/vip90/public_html/application/smarty/plugins/function.assets_url.php';
?>
<div class="more-pad">
    <div class="cell">
        <div class="container">
            <div class="maincontent">
                <?php $_smarty_tpl->_subTemplateRender("file:partials/dashboard_menu.tpl", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, $_smarty_tpl->cache_lifetime, array(), 0, false);
?>

                <div class="col-lg-10 col-md-9 col-sm-12 col-xs-12 content">
                    <header class="LandingMatchShow">
                        <h1>Deposit</h1>
                    </header>
                    <?php if (isset($_smarty_tpl->tpl_vars['error']->value)) {?>
                        <p style="color: red"><?php echo $_smarty_tpl->tpl_vars['error']->value;?>
</p>
                    <?php }?>
                    <section class="topup-content">

                        <p class="description text-black">To pay the Amount of TRX, enter the form below and press the Deposit key.</p>
                        <div class="topup-form clearfix">
                            <div>
								<div style="direction: ltr; text-align: center">
									<div>Your TRON Wallet: <code id="publicAddressState"></code></div>
									<div>Your TRON Balance: <code id="publicAddressBalance"></code></div>
									<div>Contract verified  address: : <code><a href="" id="ContractAddreslbl"></a></code></div>

								</div>
								<form action="<?php echo smarty_function_site_url(array(),$_smarty_tpl);?>
payment/credit" method="post" id="credit_from">

								<div class="col-xs-12 centre">
                                    	<label class="col-lg-2 col-md-2 col-sm-12 col-xs-12 label" for="ptype"> Select payment method</label>
										<select name="ptype" class="col-lg-8 col-md-8 col-sm-12 col-xs-12 amountinput mt-15" id="ptype">
											<option class="method_type" value="0" data-method = "0">Select payment method</option>
											<?php
$_from = $_smarty_tpl->smarty->ext->_foreach->init($_smarty_tpl, $_smarty_tpl->tpl_vars['getway']->value, 'getway_value');
if ($_from !== null) {
foreach ($_from as $_smarty_tpl->tpl_vars['getway_value']->value) {
?>
											<option class="method_type" value="<?php echo $_smarty_tpl->tpl_vars['getway_value']->value['id'];?>
-<?php echo $_smarty_tpl->tpl_vars['getway_value']->value['paymethodid'];?>
" data-method = "<?php echo $_smarty_tpl->tpl_vars['getway_value']->value['paymethodid'];?>
">
												<?php echo $_smarty_tpl->tpl_vars['getway_value']->value['name'];?>

											</option>
											<?php
}
}
$_smarty_tpl->smarty->ext->_foreach->restore($_smarty_tpl, 1);
?>

										</select>
                                	</div>
									
						
									<?php
$_from = $_smarty_tpl->smarty->ext->_foreach->init($_smarty_tpl, $_smarty_tpl->tpl_vars['unit_amount']->value, 'unit', false, 'key');
if ($_from !== null) {
foreach ($_from as $_smarty_tpl->tpl_vars['key']->value => $_smarty_tpl->tpl_vars['unit']->value) {
?>
									<div class="col-xs-12 centre amountinput amount_box hidden <?php echo $_smarty_tpl->tpl_vars['key']->value;?>
 ">
										<label class="col-lg-2 col-md-2  col-sm-12 col-xs-12 label" for="Amount_<?php echo $_smarty_tpl->tpl_vars['key']->value;?>
" id="labelText_<?php echo $_smarty_tpl->tpl_vars['key']->value;?>
">Amont of   <?php echo $_smarty_tpl->tpl_vars['unit']->value['name_fa'];?>
</label>
										<input autocomplete="off" class="col-lg-8 col-md-8  col-sm-12 col-xs-12 input ltrinput centre base_amount" data-val="true" data-val-number="The field مبلغ به <?php echo $_smarty_tpl->tpl_vars['unit']->value['name_fa'];?>
 must be a number." data-val-range=" Minimum amont to Drpost is   <?php echo $_smarty_tpl->tpl_vars['unit']->value['name_fa'];
echo $_smarty_tpl->tpl_vars['unit']->value['min_amount'];?>
 ." data-val-range-max="2147483647" data-val-range-min="<?php echo $_smarty_tpl->tpl_vars['unit']->value['min_amount'];?>
" data-val-regex="مبلغ به <?php echo $_smarty_tpl->tpl_vars['unit']->value['name_fa'];?>
 باید با فرمت درست وارد شود. " data-val-regex-pattern="^\d+$" data-amount="amount_<?php echo $_smarty_tpl->tpl_vars['key']->value;?>
" data-change-amount="change_amount_<?php echo $_smarty_tpl->tpl_vars['key']->value;?>
" data-val-required="وارد کردن مبلغ به <?php echo $_smarty_tpl->tpl_vars['unit']->value['name_fa'];?>
 الزامی است." id="Amount_<?php echo $_smarty_tpl->tpl_vars['key']->value;?>
" name="amount_<?php echo $_smarty_tpl->tpl_vars['key']->value;?>
" type="text" value="">
										<br>
									<br>
										<p ><span> Deposit</span> <span id="change_amount_<?php echo $_smarty_tpl->tpl_vars['key']->value;?>
" style="margin-right: 40px" ></span> </p>
										<input class='hidden' autocomplete="off" class="col-lg-8 col-md-8  col-sm-12 col-xs-12 input ltrinput centre" id="amount_<?php echo $_smarty_tpl->tpl_vars['key']->value;?>
" type="text" value="<?php echo $_smarty_tpl->tpl_vars['unit']->value['amount'];?>
">

										<span class="field-validation-valid error_message" data-valmsg-for="Amount" data-valmsg-replace="true"></span>
										<span class="error_message max_error" id="tronError<?php echo $_smarty_tpl->tpl_vars['key']->value;?>
"></span>
                                	</div>
									<?php
}
}
$_smarty_tpl->smarty->ext->_foreach->restore($_smarty_tpl, 1);
?>

									<input autocomplete="off" id="tron_id" name="tron_id" type="hidden" value="">
									<div class="col-xs-12 centre">
                                        <input class="btn  floatright credit-btn submitbtn" id="btnsubmit" type="submit" value="پرداخت">
                                    </div>
                                </form>  
                            </div>
                        </div>
                    </section>
                </div>
            </div>
        </div>
    </div>

	<?php echo '<script'; ?>
>
        $(document).ready(function () {
			$(".submitbtn").addClass('hidden');
			$(".tronGate").addClass('hidden');
            $('#ptype').change(function(){
                var method = $(this).children(":selected").attr("data-method");
				if ( method != '0' ){
					$(".amount_box").addClass('hidden');
                    $("."+ method).removeClass('hidden');
                }else{
                    $(".amount_box").addClass('hidden');
                }
                if ( method == '624' ){
					$(".submitbtn").removeClass('hidden');
					$(".tronGate").removeClass('hidden');
                }else{
					$(".submitbtn").addClass('hidden');
                    $(".tronGate").addClass('hidden');
                }
            });
			
			$('.base_amount').keyup( function(){
				var amount_id 		= $(this).attr('data-amount'),
					change_amount_id= $(this).attr('data-change-amount'),
					change_amount 	= $('#'+ amount_id).val(),
					amount 			= $(this).val(),
					change_unit		= amount * change_amount;
				$('#'+ change_amount_id).html( change_unit + ' IRT ' );

			})
        });
<?php echo '</script'; ?>
>

	<?php echo '<script'; ?>
 src="<?php echo smarty_function_assets_url(array(),$_smarty_tpl);?>
/Scripts/tron/core.js"><?php echo '</script'; ?>
>
	<?php echo '<script'; ?>
 src="<?php echo smarty_function_assets_url(array(),$_smarty_tpl);?>
/Scripts/tron/init.js?v=<?php echo time();?>
"><?php echo '</script'; ?>
>
<?php }
}
