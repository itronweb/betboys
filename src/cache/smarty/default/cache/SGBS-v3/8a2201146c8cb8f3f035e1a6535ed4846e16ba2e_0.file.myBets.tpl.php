<?php
/* Smarty version 3.1.31, created on 2020-11-06 10:44:33
  from "/home/vip90/public_html/themes/default/SGBS-v3/modules/bets/myBets.tpl" */

/* @var Smarty_Internal_Template $_smarty_tpl */
if ($_smarty_tpl->_decodeProperties($_smarty_tpl, array (
  'version' => '3.1.31',
  'unifunc' => 'content_5fa4f7d9bff7d3_81965845',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    '8a2201146c8cb8f3f035e1a6535ed4846e16ba2e' => 
    array (
      0 => '/home/vip90/public_html/themes/default/SGBS-v3/modules/bets/myBets.tpl',
      1 => 1538809936,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
  ),
),false)) {
function content_5fa4f7d9bff7d3_81965845 (Smarty_Internal_Template $_smarty_tpl) {
if (!is_callable('smarty_function_site_url')) require_once '/home/vip90/public_html/application/smarty/plugins/function.site_url.php';
if (!is_callable('smarty_function_jdate')) require_once '/home/vip90/public_html/application/smarty/plugins/function.jdate.php';
if (!is_callable('smarty_modifier_fa')) require_once '/home/vip90/public_html/application/smarty/plugins/modifier.fa.php';
if (!is_callable('smarty_modifier_persian_number')) require_once '/home/vip90/public_html/application/smarty/plugins/modifier.persian_number.php';
?>
<div class="more-pad">
    <div class="cell" >
        <div class="container">
            <div class="body_fraim">

                <div class="col-xs-12 nopadding tab-div">
                    <ul class="tab_switch_block tabSwitch">
                        <li class="inplay">
                            <a href="<?php echo smarty_function_site_url(array(),$_smarty_tpl);?>
bets/inplayBet">پیش بینی زنده</a>
                        </li>
                        <li class="upcoming">
                            <a href="<?php echo smarty_function_site_url(array(),$_smarty_tpl);?>
bets/upComing">بازی های امروز</a>
                        </li>
                        
                        <li class=" active">
                            <a  href="<?php echo smarty_function_site_url(array(),$_smarty_tpl);?>
bets/myrecords">سابقه پیش بینی ها</a>
                        </li>
                    </ul>
                </div>

                <div class="responsive_tbl star-checkbox maincontent clearfix ">
                
                        <table class="table" >
                            <caption>شرط های من</caption>
                            <thead>
                                <tr>
                                    <th scope="col"> زمان </th>
                                    <th scope="col"> نوع </th>
                                    <th scope="col"> ورزش </th>
                                    <th scope="col"> میزبان </th>
                                    <th scope="col"> میهمان </th>
                                    <th scope="col"> نوع شرط </th>
                                    <th scope="col"> انتخاب </th>
                                    <th scope="col">نتیجه </th>
                                    <th scope="col"> مبلغ (تومان)</th>
                                    <th scope="col"> ضریب</th>
                                    <th scope="col" width="11%">مبلغ برد (تومان) </th>
                                    <th scope="col"> جزییات بیشتر</th>
                                </tr>
                            </thead>
                            <tbody>
    						<?php if (!empty($_smarty_tpl->tpl_vars['myBet']->value)) {?>
                                 <?php
$_from = $_smarty_tpl->smarty->ext->_foreach->init($_smarty_tpl, $_smarty_tpl->tpl_vars['myBet']->value, 'values');
if ($_from !== null) {
foreach ($_from as $_smarty_tpl->tpl_vars['values']->value) {
?>
                                  <?php $_smarty_tpl->_assignInScope('length', sizeof($_smarty_tpl->tpl_vars['values']->value));
?>
								
                                   <?php
$_from = $_smarty_tpl->smarty->ext->_foreach->init($_smarty_tpl, $_smarty_tpl->tpl_vars['values']->value, 'val', false, 'key');
if ($_from !== null) {
foreach ($_from as $_smarty_tpl->tpl_vars['key']->value => $_smarty_tpl->tpl_vars['val']->value) {
?>
										<?php if ($_smarty_tpl->tpl_vars['key']->value != $_smarty_tpl->tpl_vars['length']->value-1) {?>
											<?php $_smarty_tpl->_assignInScope('class_tr', 'mix-game');
?>
										<?php } else { ?>
											<?php $_smarty_tpl->_assignInScope('class_tr', '');
?>
										<?php }?>
                                   
											<tr class= "<?php echo $_smarty_tpl->tpl_vars['class_tr']->value;?>
">

												<td data-label="زمان">
													<?php echo smarty_function_jdate(array('format'=>'j F Y - H:i','date'=>$_smarty_tpl->tpl_vars['val']->value['created']),$_smarty_tpl);?>

												</td>
												<td data-label="نوع">
													<?php if ($_smarty_tpl->tpl_vars['val']->value['type'] == 1) {?>
														تکی
													<?php } else { ?>
														میکس  <?php echo $_smarty_tpl->tpl_vars['val']->value['type'];?>
 تایی
													<?php }?>
												</td>
												<td data-label="ورزش">
													<?php echo $_smarty_tpl->tpl_vars['sport_type']->value[$_smarty_tpl->tpl_vars['val']->value['soccer_type']]['fa'];?>

												</td>


												<td data-label="شرط">
													<span >
														<?php echo smarty_modifier_fa($_smarty_tpl->tpl_vars['val']->value['home_team']);?>

													</span>
												</td>
												<td>
													<span>
														<?php echo smarty_modifier_fa($_smarty_tpl->tpl_vars['val']->value['away_team']);?>

													</span>
												</td>
												<td>
													<?php echo $_smarty_tpl->tpl_vars['val']->value['bet_type'];?>

												</td>
												<td data-label="انتخاب"><?php echo smarty_modifier_fa($_smarty_tpl->tpl_vars['val']->value['pick']);?>
 </td>
												<td data-label="نتیجه" style=" direction: rtl;">
													<span style="width:100%;text-align:center" class="bold"><?php echo smarty_modifier_persian_number($_smarty_tpl->tpl_vars['val']->value['home_score_ft']);?>
 - <?php echo smarty_modifier_persian_number($_smarty_tpl->tpl_vars['val']->value['away_score_ft']);?>
</span>
												</td>
												<td data-label="مبلغ (تومان)">
													<?php echo smarty_modifier_persian_number(number_format($_smarty_tpl->tpl_vars['val']->value['stake']));?>

												</td>
												<td data-label="ضریب">
													<?php echo smarty_modifier_persian_number($_smarty_tpl->tpl_vars['val']->value['effective_odd']);?>

												</td>
												<td data-label="مبلغ برد (تومان)" class="prizeTD">
													<span style="color:#FECE01">
														<b>

															<?php if ($_smarty_tpl->tpl_vars['val']->value['result_status'] == 0) {?>
																مشخص نشده
															<?php } elseif ($_smarty_tpl->tpl_vars['val']->value['result_status'] == 1) {?>
																<?php $_smarty_tpl->_assignInScope('winning', $_smarty_tpl->tpl_vars['val']->value['stake']*$_smarty_tpl->tpl_vars['val']->value['odd']);
?> 
																<span style="color:green">  <?php echo smarty_modifier_persian_number(number_format($_smarty_tpl->tpl_vars['winning']->value));?>
 (برد)</span>
															<?php } elseif ($_smarty_tpl->tpl_vars['val']->value['result_status'] == 2) {?>
																<span style="color:red"><?php echo smarty_modifier_persian_number(0);?>
 (باخت)</span> 
															<?php } elseif ($_smarty_tpl->tpl_vars['val']->value['result_status'] == 3) {?>
																<span ><?php echo smarty_modifier_persian_number($_smarty_tpl->tpl_vars['val']->value['stake']);?>
</span>
															<?php } elseif ($_smarty_tpl->tpl_vars['val']->value['result_status'] == 4) {?>
																<?php $_smarty_tpl->_assignInScope('winning', ($_smarty_tpl->tpl_vars['val']->value['stake']*((($_smarty_tpl->tpl_vars['val']->value['odd']-1)/2)+1)));
?>
																<span style="color:green">
																<?php echo smarty_modifier_persian_number(number_format($_smarty_tpl->tpl_vars['winning']->value));?>
 (نیم برد) </span>
															<?php } elseif ($_smarty_tpl->tpl_vars['val']->value['result_status'] == 5) {?>
																<span style="color:red"><?php echo $_smarty_tpl->tpl_vars['val']->value['stake']/2;?>
 (نیم باخت)</span>
															<?php }?>
														</b>
													</span>
												</td>
												<?php if ($_smarty_tpl->tpl_vars['key']->value == 0) {?>
												<td rowspan="<?php echo $_smarty_tpl->tpl_vars['values']->value[0]['type'];?>
" class="link" data-label="جزییات بیشتر" onclick="window.location = base_url + 'bets/BetDetail/<?php echo $_smarty_tpl->tpl_vars['val']->value['bets_id'];?>
'">بیشتر</td>
												<?php }?>

											</tr>
                                     	<?php
}
}
$_smarty_tpl->smarty->ext->_foreach->restore($_smarty_tpl, 1);
?>

                           		 <?php
}
}
$_smarty_tpl->smarty->ext->_foreach->restore($_smarty_tpl, 1);
?>


								<?php } else { ?>
							<tr>
								<td colspan="12">
									محتوایی جهت نمایش وجود ندارد.
								</td> 
							</tr>
                    <?php }?>
								
                            </tbody>
                        </table>
					
						

                </div>
            </div>
        </div>
    </div>
    <?php echo '<script'; ?>
>
        $(document).ready(function () {
            $('ul.tabSwitch li').click(function () {
                $('ul.tabSwitch  li.active').removeClass('active');
                $(this).addClass('active');
            });
        });
    <?php echo '</script'; ?>
><?php }
}
