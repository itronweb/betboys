<?php
/* Smarty version 3.1.31, created on 2020-11-07 14:19:29
  from "/home/vip90/public_html/themes/default/SGBS-v3/partials/header.tpl" */

/* @var Smarty_Internal_Template $_smarty_tpl */
if ($_smarty_tpl->_decodeProperties($_smarty_tpl, array (
  'version' => '3.1.31',
  'unifunc' => 'content_5fa67bb978e9f1_40190720',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    '5519fd64420e7fea043a07c27eaca1d0dcc1af39' => 
    array (
      0 => '/home/vip90/public_html/themes/default/SGBS-v3/partials/header.tpl',
      1 => 1604724622,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
    'file:partials/header_menu.tpl' => 1,
  ),
),false)) {
function content_5fa67bb978e9f1_40190720 (Smarty_Internal_Template $_smarty_tpl) {
if (!is_callable('smarty_function_setting')) require_once '/home/vip90/public_html/application/modules/settings/smarty/plugins/function.setting.php';
if (!is_callable('smarty_function_site_url')) require_once '/home/vip90/public_html/application/smarty/plugins/function.site_url.php';
if (!is_callable('smarty_function_assets_url')) require_once '/home/vip90/public_html/application/smarty/plugins/function.assets_url.php';
if (!is_callable('smarty_function_header_css')) require_once '/home/vip90/public_html/application/smarty/plugins/function.header_css.php';
if (!is_callable('smarty_function_header_js')) require_once '/home/vip90/public_html/application/smarty/plugins/function.header_js.php';
?>
<head>
    <meta charset="utf-8" />
    <title><?php echo smarty_function_setting(array('name'=>'site_name'),$_smarty_tpl);?>
</title>
    <link href="<?php echo smarty_function_site_url(array(),$_smarty_tpl);?>
attachment/setting/favicon.ico" rel="shortcut icon" type="image/x-icon" />
    <meta name="viewport" content="width=device-width" />

    <meta property="og:type" content="<?php echo smarty_function_setting(array('name'=>'og:type'),$_smarty_tpl);?>
" />


    <meta property="og:title" content="<?php echo smarty_function_setting(array('name'=>'og:title'),$_smarty_tpl);?>
" />
    <meta property="og:description" content="<?php echo smarty_function_setting(array('name'=>'og:description'),$_smarty_tpl);?>
" />
    <meta property="og:url" content="index.html" />


    <meta property="og:site_name" content="<?php echo smarty_function_setting(array('name'=>'og:site_name'),$_smarty_tpl);?>
" />
    <meta name="description" content="<?php echo smarty_function_setting(array('name'=>'description'),$_smarty_tpl);?>
" />

    <link href="<?php echo smarty_function_assets_url(array(),$_smarty_tpl);?>
/Content/bootstrap.css" rel="stylesheet" />
    <link href="<?php echo smarty_function_assets_url(array(),$_smarty_tpl);?>
/Content/bootstrap-rtl.min.css" rel="stylesheet" />
    <link href="<?php echo smarty_function_assets_url(array(),$_smarty_tpl);?>
/Content/owl.carousel.min.css" rel="stylesheet" />
	<link href="<?php echo smarty_function_assets_url(array(),$_smarty_tpl);?>
/Content/owl.theme.default.css" rel="stylesheet" />

    <link href="<?php echo smarty_function_assets_url(array(),$_smarty_tpl);?>
/Content/font-awesome.css" rel="stylesheet" type="text/css"/>
    <link href="<?php echo smarty_function_assets_url(array(),$_smarty_tpl);?>
/Content/accordion.css" rel="stylesheet" type="text/css"/>
    <link href="<?php echo smarty_function_assets_url(array(),$_smarty_tpl);?>
/Content/style.css" rel="stylesheet" />
    <link href="<?php echo smarty_function_assets_url(array(),$_smarty_tpl);?>
/Content/cssfa-v53031.css" rel="stylesheet" />
    <!--[if gte IE 9]>
      <style type="text/css">
        .gradient {
           filter: none;
        }
      </style>
    <![endif]-->
    <?php echo '<script'; ?>
 src="<?php echo smarty_function_assets_url(array(),$_smarty_tpl);?>
/bundles/modernizr.js"><?php echo '</script'; ?>
>
    <?php echo '<script'; ?>
 src="<?php echo smarty_function_assets_url(array(),$_smarty_tpl);?>
/bundles/jquery.min.js"><?php echo '</script'; ?>
>
    <?php echo '<script'; ?>
 src="<?php echo smarty_function_assets_url(array(),$_smarty_tpl);?>
/bundles/bootstrap.min.js"><?php echo '</script'; ?>
>

    <!--
    <?php echo '<script'; ?>
 src="<?php echo smarty_function_assets_url(array(),$_smarty_tpl);?>
/bundles/jqueryui.js"><?php echo '</script'; ?>
>
    <?php echo '<script'; ?>
 src="<?php echo smarty_function_assets_url(array(),$_smarty_tpl);?>
/bundles/jquery.js"><?php echo '</script'; ?>
>
    -->
    <!--
    <?php echo '<script'; ?>
 src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"><?php echo '</script'; ?>
>
  <?php echo '<script'; ?>
 src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"><?php echo '</script'; ?>
>
    -->
    <?php echo smarty_function_header_css(array(),$_smarty_tpl);?>
 <?php echo smarty_function_header_js(array(),$_smarty_tpl);?>

    <!-- endbuild -->
    <?php echo '<script'; ?>
>
        var base_url = '<?php echo site_url();?>
';

    <?php echo '</script'; ?>
>
</head>
<?php if ((isset($_smarty_tpl->tpl_vars['backColor']->value))) {?>
    <?php $_smarty_tpl->_assignInScope('body_class', $_smarty_tpl->tpl_vars['backColor']->value);
} else { ?>
    <?php $_smarty_tpl->_assignInScope('body_class', 'blue_theme');
}?>

<body class="dark_theme  win_rtl ">
    <?php $_smarty_tpl->_subTemplateRender("file:partials/header_menu.tpl", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, $_smarty_tpl->cache_lifetime, array(), 0, false);
?>

<?php }
}
