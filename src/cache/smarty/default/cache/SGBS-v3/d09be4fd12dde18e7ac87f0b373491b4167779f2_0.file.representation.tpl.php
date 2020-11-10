<?php
/* Smarty version 3.1.31, created on 2020-11-07 12:31:44
  from "/home/vip90/public_html/themes/default/SGBS-v3/modules/users/representation.tpl" */

/* @var Smarty_Internal_Template $_smarty_tpl */
if ($_smarty_tpl->_decodeProperties($_smarty_tpl, array (
  'version' => '3.1.31',
  'unifunc' => 'content_5fa662788e6963_14269555',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    'd09be4fd12dde18e7ac87f0b373491b4167779f2' => 
    array (
      0 => '/home/vip90/public_html/themes/default/SGBS-v3/modules/users/representation.tpl',
      1 => 1604724102,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
    'file:partials/dashboard_menu.tpl' => 1,
  ),
),false)) {
function content_5fa662788e6963_14269555 (Smarty_Internal_Template $_smarty_tpl) {
if (!is_callable('smarty_function_site_url')) require_once '/home/vip90/public_html/application/smarty/plugins/function.site_url.php';
if (!is_callable('smarty_function_assets_url')) require_once '/home/vip90/public_html/application/smarty/plugins/function.assets_url.php';
if (!is_callable('smarty_modifier_persian_number')) require_once '/home/vip90/public_html/application/smarty/plugins/modifier.persian_number.php';
if (!is_callable('smarty_modifier_price_format')) require_once '/home/vip90/public_html/application/smarty/plugins/modifier.price_format.php';
?>
<div class="more-pad">
    <div class="cell">
        <div class="container">
            <div class="maincontent">
                <?php $_smarty_tpl->_subTemplateRender("file:partials/dashboard_menu.tpl", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, $_smarty_tpl->cache_lifetime, array(), 0, false);
?>

                <div class="col-lg-10 col-md-9 col-sm-12 col-xs-12 content">
                    <header>
                        <h1 class="reg_head">Referrals</h1>
                    </header>
                    <div class="row back-color">
                        <p>
   Those who are interested in acting as a site representative and attracting new users to the site can use the site representation or referral plan.
                             To use this scheme you can use the registration link and HTML code
                             Which is intended to place site banners on other websites.
                             Any user who registers on the site by clicking on these links will be your subset and you will receive for his activity on the commission site.
                        </p>
                        <br>
                        <h2>  How to calculate the commission  </h2>
                        <p>
                            <span class="stepbubble">۱</span>
             Each representative receives 15% of the site profit from each subset as a commission.
                        </p>
                        <p>
                            <span class="stepbubble">۲</span>

The representative commission is paid to the representative for life.
                        </p>
                        <p>
                            <span class="stepbubble">۳</span>
The team reserves the right to change the commission percentage in the future.                        </p>
                        <br>
                        <p>
     Below you can find your unique registration link.
                             You can also use the available HTML code if you want to place banners on your website or blog.
                        </p>
                        <p>



                        </p>
                        <ul class="inviteoptions">

                            <li><a class="registrationlink sprite-link" href="javascript:void(0)">       Your referral link            </a></li>

                        </ul>
                        <div class="inviteoptions" style="display: block;">

                            <div class="registrationlink" style="display: block;">

                                <input type="text" readonly value="<?php echo smarty_function_site_url(array(),$_smarty_tpl);?>
users/register/<?php echo $_smarty_tpl->tpl_vars['user']->value->id;?>
">
                            </div>
                            <div class="htmlcode hidden" style="display: none;">
                                <p>
You can put the following HTML code in your blog or website.


</p>
                                <textarea readonly>&lt;a style="line-height: 0; font: 0; color: transparent; display: block; width: 728px; height: 90px; background: url(<?php echo smarty_function_assets_url(array(),$_smarty_tpl);?>
/Images/Banners/leaderboardfa.png);" href="<?php echo smarty_function_site_url(array(),$_smarty_tpl);?>
users/register/<?php echo $_smarty_tpl->tpl_vars['user']->value->id;?>
"&gt;                 The first decentralized casino on  Tron blockchain  &lt;/a&gt;</textarea>
                                <img style="width:515px;margin-top:10px" src="<?php echo smarty_function_assets_url(array(),$_smarty_tpl);?>
/Images/Banners/leaderboardfa.png">

                                <textarea readonly style="margin-top: 10px">&lt;a style="line-height: 0; font: 0; color: transparent; display: block; width: 336px; height: 280px; background: url(http://landabet.com/demo/assets/default/bet2016/Images/Banners/LargeRectanglefa.gif);" href="<?php echo smarty_function_site_url(array(),$_smarty_tpl);?>
users/register/<?php echo $_smarty_tpl->tpl_vars['user']->value->id;?>
"&gt;  The first decentralized casino on  Tron blockchain&lt;/a&gt;</textarea>
                                <img style="width:336px;margin-top:10px" src="/Images/Banners/LargeRectanglefa.gif">

                            </div>
                        </div>
                        <br>
                        <?php if ($_smarty_tpl->tpl_vars['sub_count']->value) {?>
                            <div class="report">
                                <div>
                                    <span class="report-title">Number of referral users</span>
                                    <span class="report-data"><?php echo smarty_modifier_persian_number($_smarty_tpl->tpl_vars['sub_count']->value);?>
</span>
                                </div>
                                <div>
                                    <span class="report-title">Your total income</span>
                                    <span class="report-data"><?php echo smarty_modifier_price_format($_smarty_tpl->tpl_vars['affSum']->value);?>
</span>
                                </div>
                            </div>
                        <?php }?>
                    </div>
                </div>
            </div>
        </div>
    </div><?php }
}
