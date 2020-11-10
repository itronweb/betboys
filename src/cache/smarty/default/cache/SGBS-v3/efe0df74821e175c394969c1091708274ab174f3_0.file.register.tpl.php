<?php
/* Smarty version 3.1.31, created on 2020-11-07 09:58:29
  from "/home/vip90/public_html/themes/default/SGBS-v3/modules/users/register.tpl" */

/* @var Smarty_Internal_Template $_smarty_tpl */
if ($_smarty_tpl->_decodeProperties($_smarty_tpl, array (
  'version' => '3.1.31',
  'unifunc' => 'content_5fa63e8d44ce13_16042984',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    'efe0df74821e175c394969c1091708274ab174f3' => 
    array (
      0 => '/home/vip90/public_html/themes/default/SGBS-v3/modules/users/register.tpl',
      1 => 1604670836,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
  ),
),false)) {
function content_5fa63e8d44ce13_16042984 (Smarty_Internal_Template $_smarty_tpl) {
?>
<div class="register body_fraim mainpadding SeZaR-honeycomb" role="main">
    <div class="container">
            <section class="col-lg-6 col-lg-offset-3 col-md-6 col-md-offset-3  col-sm-12 col-xs-12 nopadding signupbox">
                <div class="signupbox-header"> <h1>sign up to start</h1></div>
                <?php echo form_open($_smarty_tpl->tpl_vars['action']->value);?>

                <div class="signupform">
                    <div>
                        <input class="input full-width" data-val="true" data-val-required=" Enter your name" id="FirstName" name="first_name" placeholder="Name" type="text" value="">
                        <span class="field-validation-valid error_message" data-valmsg-for="FirstName" data-valmsg-replace="true"></span>
                    </div>
                    <div>
                        <input class="input full-width" data-val="true" data-val-required="Enter your last name" id="LastName" name="last_name" placeholder="Last Name" type="text" value="">
                        <span class="field-validation-valid error_message" data-valmsg-for="LastName" data-valmsg-replace="true"></span>
                    </div>
                    <div>
                        <input class="input full-width" data-val="true" data-val-email="Invalid Email" data-val-required="Enter your username" id="UserName" name="email" placeholder="Email" type="text" value="">
                        <span class="field-validation-valid error_message" data-valmsg-for="UserName" data-valmsg-replace="true"></span>
                    </div>
                    <div>
                        <input class="input full-width" data-val="true" data-val-required="  enter you mobile number" id="MobileNo" name="mobile" placeholder="Mobile No " type="text" value="">
                        <span class="field-validation-valid error_message" data-valmsg-for="MobileNo" data-valmsg-replace="true"></span>
                    </div>
                    <div>
                        <input class="input full-width" data-val="true" id="MobileNo" name="instagram" placeholder="Instagram ID " type="text" value="">
                        <span class="field-validation-valid error_message" data-valmsg-for="instagram" data-valmsg-replace="true"></span>
                    </div>
                    <div>
                        <input class="input full-width" data-val="true" data-val-length="Password." data-val-length-max="100" data-val-length-min="6" data-val-required="enter password" id="Password" name="password" placeholder="Password " type="password">
                        <span class="field-validation-valid error_message" data-valmsg-for="password" data-valmsg-replace="true"></span>
                    </div>
                    <div>
                        <input class="input full-width" data-val="true" data-val-length="                     " data-val-length-max="100" data-val-length-min="6" data-val-required="  .Repeat password" id="Password" name="confirmPassword" placeholder=" Repeat password " type="password">
                        <span class="field-validation-valid error_message" data-valmsg-for="password" data-valmsg-replace="true"></span>
                    </div>
                    <div class="remeberme">
                        <input type="checkbox" id="newsletter" name="newsletter" value="1"/> 
                        <label for="newsletter">      Subscribe to decentralized newsletter     </label>
                    </div>
                    <div>
                        <input class="btn btn-lg btn-green floatright" name="submit_btn" type="submit" value="Sign up ">
                    </div>
                </div>
                <?php echo form_close();?>

            </section>
    </div>
<?php }
}
