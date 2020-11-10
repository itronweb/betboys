<head>
    <meta charset="utf-8" />
    <title>{setting name='site_name'}</title>
    <link href="{site_url}attachment/setting/favicon.ico" rel="shortcut icon" type="image/x-icon" />
    <meta name="viewport" content="width=device-width" />

    <meta property="og:type" content="{setting name='og:type'}" />


    <meta property="og:title" content="{setting name='og:title'}" />
    <meta property="og:description" content="{setting name='og:description'}" />
    <meta property="og:url" content="index.html" />


    <meta property="og:site_name" content="{setting name='og:site_name'}" />
    <meta name="description" content="{setting name='description'}" />

    <link href="{assets_url}/Content/bootstrap.css" rel="stylesheet" />
    <link href="{assets_url}/Content/bootstrap-rtl.min.css" rel="stylesheet" />
    <link href="{assets_url}/Content/owl.carousel.min.css" rel="stylesheet" />
	<link href="{assets_url}/Content/owl.theme.default.css" rel="stylesheet" />

    <link href="{assets_url}/Content/font-awesome.css" rel="stylesheet" type="text/css"/>
    <link href="{assets_url}/Content/accordion.css" rel="stylesheet" type="text/css"/>
    <link href="{assets_url}/Content/style.css" rel="stylesheet" />
    <link href="{assets_url}/Content/cssfa-v53031.css" rel="stylesheet" />
    <!--[if gte IE 9]>
      <style type="text/css">
        .gradient {
           filter: none;
        }
      </style>
    <![endif]-->
    <script src="{assets_url}/bundles/modernizr.js"></script>
    <script src="{assets_url}/bundles/jquery.min.js"></script>
    <script src="{assets_url}/bundles/bootstrap.min.js"></script>

    <!--
    <script src="{assets_url}/bundles/jqueryui.js"></script>
    <script src="{assets_url}/bundles/jquery.js"></script>
    -->
    <!--
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
    -->
    {header_css} {header_js}
    <!-- endbuild -->
    <script>
        var base_url = '{site_url()}';

    </script>
</head>
{if (isset($backColor))}
    {$body_class = $backColor}
{else}
    {$body_class = 'blue_theme' }
{/if}

<body class="dark_theme  win_rtl ">
    {include file="partials/header_menu.tpl"}
