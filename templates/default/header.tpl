<?xml version="1.0" encoding="utf-8"?>
<!DOCTYPE html
		PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
		"http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<title>{$LANG.simple_invoices}</title>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<meta http-equiv="Content-Script-Type" content="text/javascript" />
	<meta http-equiv="Content-Style-Type" content="text/css" />
	<meta name="robots" content="noindex, nofollow" />
	<link rel="shortcut icon" href="./images/common/favicon.ico" />

{literal}
	<script type="text/javascript" src="./include/jquery/jquery-1.2.6.min.js"></script>
	<script type="text/javascript" src="./include/jquery/jquery.init.js"></script>

	<link rel="stylesheet" type="text/css" href="./templates/default/css/jquery-ui/default/default.dialog.css" media="all" />
	<link rel="stylesheet" type="text/css" href="./templates/default/css/print.css" media="print" />
	<!-- jQuery Files -->
	<script type="text/javascript" src="./include/jquery/jquery-ui-personalized-1.6rc2.packed.js"></script>	
	<script type="text/javascript" src="./include/jquery/cluetip/jquery.hoverIntent.minified.js"></script>
	<script type="text/javascript" src="./include/jquery/cluetip/jquery.cluetip.js"></script>
	<script type="text/javascript" src="./include/jquery/jquery.flexigrid.1.0b3.pack.js"></script>
	{/literal}{$extension_jquery_files }{literal}
	<script type="text/javascript" src="./include/jquery/jquery.plugins.js"></script>
	<script type="text/javascript" src="./include/jquery/wysiwyg/wysiwyg.modified.packed.js"></script>
	<script type="text/javascript" src="./include/jquery/jquery.livequery.pack.js"></script>
	{/literal}
	{include file='../include/jquery/jquery.functions.js.tpl'}
	{include file='../include/jquery/jquery.conf.js.tpl'}
	{literal}
	<link rel="stylesheet" type="text/css" href="./templates/default/css/jquery-ui/tab-screen.css" media="all" />
	<link rel="stylesheet" type="text/css" href="./templates/default/css/jquery-ui/tab_menu.css" media="all" />
	<link rel="stylesheet" type="text/css" href="./templates/default/css/jquery-ui/tab.css" media="all" />
	<!--[if IE]>
	<link rel="stylesheet" type="text/css" href="./extensions/tab_menu/templates/default/css/iehacks.css" media="all" />
	<![endif]-->

	<!--<script type="text/javascript" src="./include/jquery/jquery.conf.js.tpl"></script>-->
	<link rel="stylesheet" type="text/css" href="./templates/default/css/flexigrid.css" />
	<link rel="stylesheet" type="text/css" href="./include/jquery/wysiwyg/wysiwyg.css" />
	<link rel="stylesheet" type="text/css" href="./include/jquery/jquery.plugins.css" title="default" media="screen" />
	<link rel="stylesheet" type="text/css" href="./include/jquery/rte/rte.css" />	
	<link rel="stylesheet" type="text/css" href="./include/jquery/cluetip/jquery.cluetip.css" />
	<link rel="stylesheet" type="text/css" href="./templates/default/css/jquery-ui/default.css" media="all" />
	<link rel="stylesheet" type="text/css" href="./templates/default/css/phpreports.css" media="all"/>
	{/literal}
	{if $config->debug->level == "All"}
	<link rel="stylesheet" type="text/css" href="./library/blackbirdjs/blackbird.css" />	
	<script type="text/javascript" src="./library/blackbirdjs/blackbird.js"></script>
	{/if}
	{literal}
	<script type="text/javascript" src="./include/jquery/jquery.validationEngine.js"></script>
    {/literal}

</head>
<body>
<div class="si_grey_background"></div>

