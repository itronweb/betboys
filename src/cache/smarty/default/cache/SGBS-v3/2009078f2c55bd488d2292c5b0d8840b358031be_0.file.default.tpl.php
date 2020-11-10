<?php
/* Smarty version 3.1.31, created on 2020-11-07 14:19:29
  from "/home/vip90/public_html/themes/default/SGBS-v3/layout/default.tpl" */

/* @var Smarty_Internal_Template $_smarty_tpl */
if ($_smarty_tpl->_decodeProperties($_smarty_tpl, array (
  'version' => '3.1.31',
  'unifunc' => 'content_5fa67bb9787816_45216689',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    '2009078f2c55bd488d2292c5b0d8840b358031be' => 
    array (
      0 => '/home/vip90/public_html/themes/default/SGBS-v3/layout/default.tpl',
      1 => 1542240734,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
    'file:partials/header.tpl' => 1,
    'file:partials/footer.tpl' => 1,
  ),
),false)) {
function content_5fa67bb9787816_45216689 (Smarty_Internal_Template $_smarty_tpl) {
?>
<!DOCTYPE html>
<html lang="en">

    <?php $_smarty_tpl->_subTemplateRender("file:partials/header.tpl", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, $_smarty_tpl->cache_lifetime, array(), 0, false);
?>

    <!-- End header -->
    <!-- Main Wrapper -->

    <?php if (($_smarty_tpl->tpl_vars['system_message']->value != '')) {?>
        <div class="container box-msg">
            <div class="">
                <div class="">
                    <div class=" <?php if ($_smarty_tpl->tpl_vars['system_message']->value['type'] == 'success') {?>
                         <?php echo 'msgsuccess';?>

                    <?php } elseif ($_smarty_tpl->tpl_vars['system_message']->value['type'] == 'warning') {?>
                        <?php echo 'msgwarning';?>

                    <?php } elseif ($_smarty_tpl->tpl_vars['system_message']->value['type'] == 'fail') {?> 
                        <?php echo 'msgerror';?>

                    <?php }?>">


                    <h4><i class="fa fa-
                                                                          <?php if ($_smarty_tpl->tpl_vars['system_message']->value['type'] == 'success') {?>
                                                                              <?php echo 'check-square-o';?>

                                                                          <?php } elseif ($_smarty_tpl->tpl_vars['system_message']->value['type'] == 'warning') {?>
                                                                              <?php echo 'warning';?>

                                                                          <?php } elseif ($_smarty_tpl->tpl_vars['system_message']->value['type'] == 'fail') {?> 
                                                                              <?php echo 'remove';?>

                                                                          <?php }?>
                                                                          fa-2x" style="vertical-align: middle;"></i>
                        <span class="semi-bold" style="padding-top: 9px ! important; font-size: 13px; border-bottom: 4px dashed rgb(221, 221, 221); margin-bottom: 9px ! important;"><?php echo $_smarty_tpl->tpl_vars['system_message']->value['title'];?>
</span>
                    </h4>

                    <p> <?php echo $_smarty_tpl->tpl_vars['system_message']->value['message'];?>
</p>
                </div>
            </div>
        </div>
    </div>


    <?php }?>
        <?php echo $_smarty_tpl->tpl_vars['content']->value;?>

        <?php $_smarty_tpl->_subTemplateRender("file:partials/footer.tpl", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, $_smarty_tpl->cache_lifetime, array(), 0, false);
?>

    </html><?php }
}
