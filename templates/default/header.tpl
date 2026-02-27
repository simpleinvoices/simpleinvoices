<!DOCTYPE html>
<html lang="en" data-bs-theme="light">
<head>
{strip}
	{include file='hooks.tpl'}
	{assign var='tmp_lang_module' value="title_module_`$module`"}{assign var='tmp_lang_module' value=$LANG.$tmp_lang_module|default:$LANG.$module|default:$module}
	{assign var='tmp_lang_view' value="title_view_`$view`"}{assign var='tmp_lang_view' value=$LANG.$tmp_lang_view|default:$LANG.$view|default:$view}
	{$smarty.capture.hook_head_start}
{/strip}
	<meta charset="utf-8" />
	<meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover" />
	<meta name="robots" content="noindex, nofollow" />
	<title>{$tmp_lang_module} : {$tmp_lang_view} - {$LANG.simple_invoices}</title>
	<link rel="shortcut icon" href="./images/common/favicon.ico" />

	{* Inter font (used by Tabler) *}
	<link rel="preconnect" href="https://fonts.googleapis.com" />
	<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
	<link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet" />

	{* Tabler.io - Bootstrap 5 admin dashboard *}
	<link href="https://preview.tabler.io/dist/css/tabler.min.css" rel="stylesheet" />
	<link href="https://cdn.jsdelivr.net/npm/@tabler/icons-webfont@latest/dist/tabler-icons.min.css" rel="stylesheet" />

{literal}
	<link rel="stylesheet" type="text/css" href="./include/jquery/jquery.plugins.css" title="default" media="screen" />
	<link rel="stylesheet" type="text/css" href="./include/jquery/rte/rte.css" />
	<link rel="stylesheet" type="text/css" href="./templates/default/css/main.css" media="all"/>
	<link rel="stylesheet" type="text/css" href="./templates/default/css/tabler-overrides.css" media="all"/>
	<link rel="stylesheet" type="text/css" href="./templates/default/css/print.css" media="print" />
{/literal}

	<script type="text/javascript" src="./include/jquery/jquery-1.2.6.min.js"></script>
	<script src="https://preview.tabler.io/dist/js/tabler.min.js"></script>
	<script type="text/javascript" src="./include/jquery/jquery.init.js"></script>
	<script type="text/javascript" src="./include/jquery/si-tabler-grid.js"></script>
	<script type="text/javascript" src="./include/jquery/si-bootstrap.js"></script>
	<script type="text/javascript" src="https://cdn.jsdelivr.net/npm/hugerte@1.0.10/hugerte.min.js"></script>
	<script src="https://cdn.jsdelivr.net/npm/litepicker@2.0.12/dist/litepicker.js"></script>
	<script type="text/javascript" src="./include/jquery/si-litepicker.js"></script>
	<script type="text/javascript" src="./include/jquery/si-autocomplete.js"></script>
	{$extension_jquery_files}
	{include file='jquery.functions.js.tpl'}
	{include file='jquery.conf.js.tpl'}
	<script type="text/javascript" src="./include/jquery/si-validate.js"></script>

	{$smarty.capture.hook_head_end}
</head>
<body class="body_si body_module_{$module} body_view_{$view}">
{$smarty.capture.hook_body_start}
<div class="page">

	{* Top navbar *}
	<header class="navbar navbar-expand-md d-print-none">
		<div class="container-xl">
			<button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#sidebar-menu" aria-controls="sidebar-menu" aria-expanded="false" aria-label="Toggle navigation">
				<span class="navbar-toggler-icon"></span>
			</button>
			<h1 class="navbar-brand navbar-brand-autodark d-none-navbar-horizontal pe-0 pe-md-3">
				<a href="index.php?module=index&amp;view=index">{$LANG.simple_invoices}</a>
			</h1>
			<div class="navbar-nav flex-row order-md-last">
				<a href="http://www.simpleinvoices.org/help" target="_blank" class="nav-link">{$LANG.help}</a>
				{if $config->authentication->enabled == 1}
					{if $smarty.session.Zend_Auth.id == null}
						<a href="index.php?module=auth&amp;view=login" class="nav-link">{$LANG.login}</a>
					{else}
						<span class="nav-link">{$LANG.hello} {$smarty.session.Zend_Auth.email|htmlsafe}</span>
						{if $smarty.session.Zend_Auth.domain_id <> 1}
							<span class="nav-link text-muted">Domain: {$smarty.session.Zend_Auth.domain_id}</span>
						{/if}
						<a href="index.php?module=auth&amp;view=logout" class="nav-link">{$LANG.logout}</a>
					{/if}
				{/if}
			</div>
		</div>
	</header>
