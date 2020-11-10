<?php
/* Smarty version 3.1.31, created on 2020-11-07 12:32:29
  from "/home/vip90/public_html/themes/default/SGBS-v3/modules/users/changePass.tpl" */

/* @var Smarty_Internal_Template $_smarty_tpl */
if ($_smarty_tpl->_decodeProperties($_smarty_tpl, array (
  'version' => '3.1.31',
  'unifunc' => 'content_5fa662a596f898_84205677',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    '49849ea073bb6eaed43608890ec1f35318a7a2b2' => 
    array (
      0 => '/home/vip90/public_html/themes/default/SGBS-v3/modules/users/changePass.tpl',
      1 => 1604724130,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
    'file:partials/dashboard_menu.tpl' => 1,
  ),
),false)) {
function content_5fa662a596f898_84205677 (Smarty_Internal_Template $_smarty_tpl) {
if (!is_callable('smarty_function_site_url')) require_once '/home/vip90/public_html/application/smarty/plugins/function.site_url.php';
?>
<div>
    <div class="cell more-pad">
        <div class="container">
            <div class="maincontent">
                    <?php $_smarty_tpl->_subTemplateRender("file:partials/dashboard_menu.tpl", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, $_smarty_tpl->cache_lifetime, array(), 0, false);
?>

                <div class="col-lg-10 col-md-9 col-sm-12 col-xs-12 content content">
                    <header>
                        <h1 class="reg_head">Change your password</h1>
                    </header>
                    <section class="signupbox mt-20 change-pass">
                        <form action="<?php echo smarty_function_site_url(array(),$_smarty_tpl);?>
users/changePass" method="post">
                            <div class="signupform halfwidth">
                                <div>
                                    <input class="col-xs-12 input id" data-val="true" data-val-required="وارد کردن کلمه عبور فعلی الزامی است." id="OldPassword" name="OldPassword" placeholder="کلمه عبور فعلی" type="password">
                                    <span class="col-xs-12  field-validation-valid error_message" data-valmsg-for="OldPassword" data-valmsg-replace="true"></span>
                                </div>
                                <div>
                                    <input class="col-xs-12  input password" data-val="true" data-val-length="کلمه عبور جدید باید حداقل 6 حرف طول داشته باشد." data-val-length-max="100" data-val-length-min="6" data-val-required="وارد کردن کلمه عبور جدید الزامی است." id="NewPassword" name="NewPassword" placeholder="کلمه عبور جدید" type="password">
                                    <span class="col-xs-12  field-validation-valid error_message" data-valmsg-for="NewPassword" data-valmsg-replace="true"></span>
                                </div>
                                <div class="remeberme">
                                    <input class="col-xs-12 input password" data-val="true" data-val-equalto="کلمه عبور جدید و تکرار کلمه عبور جدید با هم یکسان نیستند." data-val-equalto-other="*.NewPassword" id="ConfirmPassword" name="ConfirmPassword" placeholder="تکرار کلمه عبور جدید" type="password">
                                    <span class="col-xs-12 field-validation-valid error_message" data-valmsg-for="ConfirmPassword" data-valmsg-replace="true"></span>
                                </div>
                               <div class="withdraw-btn">
                                    <input name="submitbtn" class="btn btn-lg btn-green " type="submit" value="Change your password">
                                </div>
                            </div>
                        </form>                       
                    </section>
                </div>
            </div>
        </div>
    </div>
<?php }
}
