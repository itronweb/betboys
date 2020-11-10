<?php
/* Smarty version 3.1.31, created on 2020-11-07 13:15:42
  from "/home/vip90/public_html/themes/default/SGBS-v3/modules/users/login.tpl" */

/* @var Smarty_Internal_Template $_smarty_tpl */
if ($_smarty_tpl->_decodeProperties($_smarty_tpl, array (
  'version' => '3.1.31',
  'unifunc' => 'content_5fa66cc6001ad9_18689912',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    '56cab1a22f48438c65912aed560615568b523111' => 
    array (
      0 => '/home/vip90/public_html/themes/default/SGBS-v3/modules/users/login.tpl',
      1 => 1604670910,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
  ),
),false)) {
function content_5fa66cc6001ad9_18689912 (Smarty_Internal_Template $_smarty_tpl) {
if (!is_callable('smarty_function_site_url')) require_once '/home/vip90/public_html/application/smarty/plugins/function.site_url.php';
?>
<div class="register body_fraim mainpadding SeZaR-honeycomb">
    <div class="container">
        <section class=" col-lg-6 col-lg-offset-3 col-md-6 col-md-offset-3  col-sm-12 col-xs-12 nopadding signupbox">
            <div class="signupbox-header"> <h1>Login to your account</h1> </div>
            <?php echo form_open();?>

            <div class="signupform">
                <div>
                    <input class="input full-width  id" data-val="true" data-val-email="Invalid E-mail" data-val-required="Input Email" id="UserName" name="email" placeholder="Email" type="text" value="" />
                    <span class="field-validation-valid error_message" data-valmsg-for="UserName" data-valmsg-replace="true"></span>
                </div>
                <div>
                    <input class="input full-width password" data-val="true" data-val-required="Password" id="Password" name="password" placeholder=" Password" type="password" />
                    <span class="field-validation-valid error_message" data-valmsg-for="Password" data-valmsg-replace="true"></span>
                </div>
                <div class="remeberme pull-right">
                    <input type="checkbox" id="RememberMe" name="remember_me" value="1"/> 
                    <label for="RememberMe">Remember me</label>
                </div>
                 <div class="extra pull-left">
                    <a href="<?php echo smarty_function_site_url(array(),$_smarty_tpl);?>
users/resetPassword">Forget password</a>
                </div>
                <span class="error_message">

                </span>
				<div class="row">
					<div class="col-md-8">
						<button class="btn btn-lg btn-green floatright" type="submit">Login</button>
					</div>
					<div class="col-md-4">
						<a class="btn btn-lg btn-yellow" href="<?php echo smarty_function_site_url(array(),$_smarty_tpl);?>
register">Sing up</a>
					</div>
				</div>
				
	</div>
            <?php echo form_close();?>
    
        </section>
    </div>

<?php }
}
