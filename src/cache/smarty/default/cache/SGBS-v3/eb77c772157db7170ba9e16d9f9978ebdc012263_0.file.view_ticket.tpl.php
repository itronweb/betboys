<?php
/* Smarty version 3.1.31, created on 2020-11-07 09:48:58
  from "/home/vip90/public_html/themes/default/SGBS-v3/modules/contacts/view_ticket.tpl" */

/* @var Smarty_Internal_Template $_smarty_tpl */
if ($_smarty_tpl->_decodeProperties($_smarty_tpl, array (
  'version' => '3.1.31',
  'unifunc' => 'content_5fa63c525b1ca1_64446791',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    'eb77c772157db7170ba9e16d9f9978ebdc012263' => 
    array (
      0 => '/home/vip90/public_html/themes/default/SGBS-v3/modules/contacts/view_ticket.tpl',
      1 => 1604726088,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
  ),
),false)) {
function content_5fa63c525b1ca1_64446791 (Smarty_Internal_Template $_smarty_tpl) {
if (!is_callable('smarty_function_jdate')) require_once '/home/vip90/public_html/application/smarty/plugins/function.jdate.php';
if (!is_callable('smarty_function_assets_url')) require_once '/home/vip90/public_html/application/smarty/plugins/function.assets_url.php';
?>
<div>
<div class="cell more-pad">
  <div class="container">
    <div class="maincontent clearfix">
      <div class="content content-full text-black">
        <header class="clearfix">
          <h1 class="mark-side">Support</h1>
          <div class="row">
            <ul class="p10">
              <li> <b>Ticket ID :</b> TK-<?php echo $_smarty_tpl->tpl_vars['Ticket']->value->id;?>
 </li>
              <li> <b>Ticket title :</b> <?php echo $_smarty_tpl->tpl_vars['Ticket']->value->subject;?>
 </li>
              <li> <b>زمان ثبت :</b> <?php echo smarty_function_jdate(array('format'=>'j F Y - H:i','date'=>$_smarty_tpl->tpl_vars['Ticket']->value->created_at),$_smarty_tpl);?>
 </li>
            </ul>
          </div>
        </header>
        <section class="formbox row_100 clearfix">
                        <div class="col-xs-12 ticketdetails row">
                                <?php
$_from = $_smarty_tpl->smarty->ext->_foreach->init($_smarty_tpl, $_smarty_tpl->tpl_vars['Ticket']->value->replies, 'val');
if ($_from !== null) {
foreach ($_from as $_smarty_tpl->tpl_vars['val']->value) {
?>
                                    <div class="row mt10">
										 <?php if ($_smarty_tpl->tpl_vars['val']->value['user_id'] == $_smarty_tpl->tpl_vars['logged_in_user_id']->value) {?>
                                        <div class="col-md-2 col-xs-4">
                                           
                                                <img width="100" style="border-radius: 45%;padding-bottom:5px;margin-left: 15px;float:right;" src="<?php echo $_smarty_tpl->tpl_vars['Insta']->value;?>
" data="<?php echo smarty_function_assets_url(array(),$_smarty_tpl);?>
/images/user-white.png">
                                                <ul class="ticket_reply">
													<li><?php echo $_smarty_tpl->tpl_vars['nameusr']->value;?>
</li>
													<li><?php echo smarty_function_jdate(array('format'=>'j F Y - H:i','date'=>$_smarty_tpl->tpl_vars['val']->value->created_at),$_smarty_tpl);?>
</li>
												</ul>
                                            
                                        </div>
										<?php }?>
                                        <div class="col-md-10 col-xs-8">
                                            <div class="<?php if ($_smarty_tpl->tpl_vars['val']->value['user_id'] == $_smarty_tpl->tpl_vars['logged_in_user_id']->value) {?>bubble<?php } else { ?>bubblereply<?php }?>">
                                                <?php echo $_smarty_tpl->tpl_vars['val']->value->content;?>

                                            </div>

                                        </div>
                                     <?php if ($_smarty_tpl->tpl_vars['val']->value['user_id'] != $_smarty_tpl->tpl_vars['logged_in_user_id']->value) {?>
                                        <div class="col-md-2 col-xs-4">
                                                <img width="100" style="border-radius: 45%;padding-bottom:5px;float:left;" src="<?php echo smarty_function_assets_url(array(),$_smarty_tpl);?>
/images/opr.png">
												<ul class="ticket_reply">
													<li>Support</li>
													<li><?php echo smarty_function_jdate(array('format'=>'j F Y - H:i','date'=>$_smarty_tpl->tpl_vars['val']->value->created_at),$_smarty_tpl);?>
</li>
												</ul>
                                        </div>
										  <?php }?>
							</div>
                                <?php
}
}
$_smarty_tpl->smarty->ext->_foreach->restore($_smarty_tpl, 1);
?>

			 </div>              
       
          <form action="<?php echo $_smarty_tpl->tpl_vars['action']->value;?>
" method="post">
            <div class="col-xs-12 support_form createform">
              <div class="halfwidth">
                <div class="wrapsignupinput">
                  <textarea class="input textarea" cols="20" data-val="true" data-val-required="وارد کردن متن تیکت الزامی است." id="Message" name="content" placeholder="متن تیکت" rows="2"></textarea>
                </div>
                <div class="mt10">
                  <input class="btn btn-lg btn-yellow" type="submit" value="Send">
                </div>
              </div>
            </div>
          </form>
          </section>
          <div class="row">
            <p> Userان گرامی توجه داشته باشند، برای هر مورد یک تیکت ایجاد نمایید و تا حل شدن کامل مشکل، تیکت مربوطه را ادامه دهید و جهت تسریع در رفع مشکلات Userی لطفا از ایجاد تیکت های جدید و متنوع اجتناب فرمایید. </p>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
<?php }
}
