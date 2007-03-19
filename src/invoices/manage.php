<?php
include_once('./include/include_main.php');

//stop the direct browsing to this file - let index.php handle which files get displayed
checkLogin();


echo <<<EOD
<title>{$title} :: {$LANG_manage_invoices}</title>
EOD;

#insert customer

$sql = "select * from {$tb_prefix}invoices ORDER BY inv_id desc";

$page_header = <<<EOD
<b>{$LANG_manage_invoices}</b> ::
<a href="index.php?module=invoices&view=total">{$LANG_add_new_invoice} - $LANG[total_style]</a> ::
<a href="index.php?module=invoices&view=itemised">{$LANG_add_new_invoice} - {$LANG_itemised_style}</a> ::
<a href="index.php?module=invoices&view=consulting">{$LANG_add_new_invoice} - {$LANG_consulting_style}</a>
<hr></hr>
EOD;
include('./src/invoices/manage.inc.php');


getRicoLiveGrid("ex1","	{ type:'number', decPlaces:0, ClassName:'alignleft' },,,
	{ type:'number', decPlaces:2, ClassName:'alignleft' },
	{ type:'number', decPlaces:2, ClassName:'alignleft' }");

echo $display_block;
?>
