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
	<link rel="shortcut icon" href="{$baseUrl}images/common/favicon.ico" />

{literal}
	<script type="text/javascript" src="{/literal}{$baseUrl}{literal}js/jquery-1.7.2.min.js"></script>
    <script type="text/javascript" src="{/literal}{$baseUrl}{literal}js/jquery-ui-1.8.18.custom.min.js"></script>
	<script type="text/javascript" src="{/literal}{$baseUrl}{literal}js/jquery.init.js"></script>
             
    <link rel="stylesheet" type="text/css" href="{/literal}{$baseUrl}{literal}css/default/jquery-ui/smoothness/jquery-ui-1.8.18.custom.css" media="all" />
	<!-- <link rel="stylesheet" type="text/css" href="{/literal}{$baseUrl}{literal}css/default/jquery-ui/default/default.dialog.css" media="all" /> -->
	<link rel="stylesheet" type="text/css" href="{/literal}{$baseUrl}{literal}css/default/print.css" media="print" />
	<!-- jQuery Files -->
	<script type="text/javascript" src="{/literal}{$baseUrl}{literal}js/cluetip/jquery.hoverIntent.minified.js"></script>
	<script type="text/javascript" src="{/literal}{$baseUrl}{literal}js/cluetip/jquery.cluetip.js"></script>
	<script type="text/javascript" src="{/literal}{$baseUrl}{literal}js/jquery.flexigrid.1.0b3.pack.js"></script>
	<script type="text/javascript" src="{/literal}{$baseUrl}{literal}js/jquery.plugins.js"></script>
	<script type="text/javascript" src="{/literal}{$baseUrl}{literal}js/wysiwyg/wysiwyg.modified.packed.js"></script>
	<script type="text/javascript" src="{/literal}{$baseUrl}{literal}js/jquery.livequery.pack.js"></script>
	{/literal}{$extension_jquery_files }{literal}
	
	<!-- AJAX Uploader script for people - billers -->
	<script type="text/javascript" src="{/literal}{$baseUrl}{literal}js/ajaxupload/ajaxupload.js"></script>
	
	{/literal}
	{include file="./jquery/jquery.functions.js.tpl"}
	{include file="./jquery/jquery.conf.js.tpl"}
	{literal}                                                           
	<link rel="stylesheet" type="text/css" href="{/literal}{$baseUrl}{literal}css/default/jquery-ui/tab-screen.css" media="all" />
	<link rel="stylesheet" type="text/css" href="{/literal}{$baseUrl}{literal}css/default/jquery-ui/tab_menu.css" media="all" />
	<link rel="stylesheet" type="text/css" href="{/literal}{$baseUrl}{literal}css/default/jquery-ui/tab.css" media="all" />
	<!--[if IE]>
	<link rel="stylesheet" type="text/css" href="{/literal}{$baseUrl}{literal}sys/extensions/tab_menu/templates/default/css/iehacks.css" media="all" />
	<![endif]-->

	<!--<script type="text/javascript" src="./js/jquery.conf.js.tpl"></script>-->
	<link rel="stylesheet" type="text/css" href="{/literal}{$baseUrl}{literal}css/default/flexigrid.css" />
	<link rel="stylesheet" type="text/css" href="{/literal}{$baseUrl}{literal}js/wysiwyg/wysiwyg.css" />
	<link rel="stylesheet" type="text/css" href="{/literal}{$baseUrl}{literal}css/default/jquery.plugins.css" title="default" media="screen" />
	<link rel="stylesheet" type="text/css" href="{/literal}{$baseUrl}{literal}js/rte/rte.css" />	
	<link rel="stylesheet" type="text/css" href="{/literal}{$baseUrl}{literal}js/cluetip/jquery.cluetip.css" />
	<link rel="stylesheet" type="text/css" href="{/literal}{$baseUrl}{literal}css/default/jquery-ui/default.css" media="all" />
	<link rel="stylesheet" type="text/css" href="{/literal}{$baseUrl}{literal}css/default/phpreports.css" media="all"/>

	<script type="text/javascript" src="{/literal}{$baseUrl}{literal}js/jquery.validationEngine.js"></script>
  <!-- Javascript for Flot Graphs -->
  <script type="text/javascript" src="{/literal}{$baseUrl}{literal}js/flot/jquery.flot.js"></script>
	
	{/literal}
	{if $config->debug->level == "All"}
	<link rel="stylesheet" type="text/css" href="{$baseUrl}lib/blackbirdjs/blackbird.css" />	
	<script type="text/javascript" src="{$baseUrl}lib/blackbirdjs/blackbird.js"></script>
	{/if}

</head>
<body>
<div class="si_grey_background"></div>

