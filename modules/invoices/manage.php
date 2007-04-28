<?php

//stop the direct browsing to this file - let index.php handle which files get displayed
checkLogin();


echo <<<EOD
<title>{$title} :: {$LANG['manage_invoices']}</title>
EOD;

#insert customer

$sql = "select * from {$tb_prefix}invoices ORDER BY inv_id desc";

$page_header = <<<EOD
<b>{$LANG['manage_invoices']}</b> ::
<a href="index.php?module=invoices&view=total">{$LANG['add_new_invoice']} - {$LANG['total_style']}</a> ::
<a href="index.php?module=invoices&view=itemised">{$LANG['add_new_invoice']} - {$LANG['itemised_style']}</a> ::
<a href="index.php?module=invoices&view=consulting">{$LANG['add_new_invoice']} - {$LANG['consulting_style']}</a>
<hr></hr>
EOD;
include('./modules/invoices/manage.inc.php');


getRicoLiveGrid("ex1","	{ type:'number', decPlaces:0, ClassName:'alignleft' },,,
	{ type:'number', decPlaces:2, ClassName:'alignleft' },
	{ type:'number', decPlaces:2, ClassName:'alignleft' }");


echo <<<EOD
<!--[if gte IE 5.5]>
<link rel="stylesheet" type="text/css" href="./modules/include/css/iehacks.css" media="all"/>
<![endif]-->
EOD;

echo $display_block;
?>
