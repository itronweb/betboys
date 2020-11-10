<?php
/* Smarty version 3.1.31, created on 2020-11-07 09:35:49
  from "/home/vip90/public_html/themes/default/SGBS-v3/modules/contacts/list_ticket.tpl" */

/* @var Smarty_Internal_Template $_smarty_tpl */
if ($_smarty_tpl->_decodeProperties($_smarty_tpl, array (
  'version' => '3.1.31',
  'unifunc' => 'content_5fa6393d858b47_86914940',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    'e657b0b2e5709a8b119ed0ca27b044e7c98a177b' => 
    array (
      0 => '/home/vip90/public_html/themes/default/SGBS-v3/modules/contacts/list_ticket.tpl',
      1 => 1604725976,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
  ),
),false)) {
function content_5fa6393d858b47_86914940 (Smarty_Internal_Template $_smarty_tpl) {
if (!is_callable('smarty_function_site_url')) require_once '/home/vip90/public_html/application/smarty/plugins/function.site_url.php';
if (!is_callable('smarty_function_jdate')) require_once '/home/vip90/public_html/application/smarty/plugins/function.jdate.php';
?>
<div class="more-pad"">
    <div class="cell" style="width:90%;">
        <div class="container">
            <div class="maincontent clearfix">
                <div class="content content-full box-pd">
                    <header class="clearfix">
                        <h1 class="mark-side">Support</h1>
                        <span class="optionKey">
                            <a class="newticket btn btn-sm" href="<?php echo smarty_function_site_url(array(),$_smarty_tpl);?>
contacts/tickets/new-ticket">Send a new ticket</a>
                            <a class="newticket  btn btn-sm  hidden" href="<?php echo smarty_function_site_url(array(),$_smarty_tpl);?>
contacts/tickets/ticket-list">Ticket history</a>
                        </span>
                    </header>
                    <section class="formbox mt-20 row_100 clearfix">
                        <div class="support_messages" style="display: block;">
                            <table class="leaguetable support">
                                <tbody>
                                    <tr>
                                        <th>Ticket ID</th>
                                        <th>Date & Time </th>
                                        <th>Ticket title</th>
                                        <th>Status</th>
                                    </tr>
                                    <?php
$_from = $_smarty_tpl->smarty->ext->_foreach->init($_smarty_tpl, $_smarty_tpl->tpl_vars['Tickets']->value, 'val');
if ($_from !== null) {
foreach ($_from as $_smarty_tpl->tpl_vars['val']->value) {
?>
                                        <tr>

                                            <td> <a href="<?php echo smarty_function_site_url(array(),$_smarty_tpl);?>
contacts/tickets/view-ticket/<?php echo $_smarty_tpl->tpl_vars['val']->value->id;?>
">TK-<?php echo $_smarty_tpl->tpl_vars['val']->value->id;?>
</a></td>

                                            <td>
                                                <a href="<?php echo smarty_function_site_url(array(),$_smarty_tpl);?>
contacts/tickets/view-ticket/<?php echo $_smarty_tpl->tpl_vars['val']->value->id;?>
"> <time datetime=""><?php echo smarty_function_jdate(array('format'=>'j F Y - h:i a','date'=>$_smarty_tpl->tpl_vars['val']->value->created_at),$_smarty_tpl);?>
</time></a>
                                            </td>
                                            <td><a href="<?php echo smarty_function_site_url(array(),$_smarty_tpl);?>
contacts/tickets/view-ticket/<?php echo $_smarty_tpl->tpl_vars['val']->value->id;?>
"><?php echo $_smarty_tpl->tpl_vars['val']->value->subject;?>
</a></td>
                                            <td>
                                                <?php if ($_smarty_tpl->tpl_vars['val']->value['status'] == 0) {?>
                                                   Waiting for an answer
                                                <?php } elseif ($_smarty_tpl->tpl_vars['val']->value['status'] == 1) {?>
                                                    Open
                                                <?php } else { ?>
                                                    Closed
                                                <?php }?>
                                            </td>
                                            </a>
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
                    <div class="row">
                        <p>
                            Create a ticket for each item and continue the relevant ticket until the problem is completely solved, and to expedite the troubleshooting, please avoid creating new and varied tickets.
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>

<?php }
}
