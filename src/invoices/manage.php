<?php
include_once('./include/include_main.php');

//stop the direct browsing to this file - let index.php handle which files get displayed
if (!defined("BROWSE")) {
   echo "You Cannot Access This Script Directly, Have a Nice Day.";
   exit();
}

?>

<html>
<head>

<?php
echo <<<EOD
<title>{$title} :: {$LANG_manage_invoices}</title>
EOD;
#insert customer
$conn = mysql_connect("$db_host","$db_user","$db_password");
mysql_select_db("$db_name",$conn);


$sql = "select * from {$tb_prefix}invoices ORDER BY inv_id desc";

$page_header = <<<EOD
<b>{$LANG_manage_invoices}</b> ::
<a href="index.php?module=invoices&view=total">{$LANG_add_new_invoice} - {$LANG_total_style}</a> ::
<a href="index.php?module=invoices&view=itemised">{$LANG_add_new_invoice} - {$LANG_itemised_style}</a> ::
<a href="index.php?module=invoices&view=consulting">{$LANG_add_new_invoice} - {$LANG_consulting_style}</a>
<hr></hr>
EOD;
include('./src/invoices/manage.inc.php');

?>

<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />


<script type="text/javascript" src="include/jquery.js"></script>

<script type="text/javascript" src="./src/include/js/ibox.js"></script>
<link rel="stylesheet" href="./src/include/css/ibox.css" type="text/css"  media="screen"/>

<?php
require "./src/include/js/lgplus/php/chklang.php";
require "./src/include/js/lgplus/php/settings.php";
?>

<script src="./src/include/js/lgplus/js/rico.js" type="text/javascript"></script>
<script type='text/javascript'>
Rico.loadModule('LiveGrid');
Rico.loadModule('LiveGridMenu');

<?
setStyle();
setLang();
?>

Rico.onLoad( function() {
  var opts = {  
    <? GridSettingsScript(); ?>,
    columnSpecs   : [ 
	,
	{ type:'number', decPlaces:0, ClassName:'alignleft' },
	,
	,
	{ type:'number', decPlaces:2, ClassName:'alignleft' },
	{ type:'number', decPlaces:2, ClassName:'alignleft' }

 ]
  };
  var menuopts = <? GridSettingsMenu(); ?>;
  new Rico.LiveGrid ('ex1', new Rico.GridMenu(menuopts), new Rico.Buffer.Base($('ex1').tBodies[0]), opts);
});
</script>
</head>

<body>


<?php echo $display_block; ?>
<a href="./documentation/info_pages/manage_invoices.html" rel="ibox&height=400"><img src="./images/common/help-small.png"></img><?php echo $LANG_help_manage_invoices; ?></a>
<!-- ./src/include/design/footer.inc.php gets called here by controller srcipt -->
