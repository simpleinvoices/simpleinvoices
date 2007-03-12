<?php
include('./include/include_main.php');

//stop the direct browsing to this file - let index.php handle which files get displayed
if (!defined("BROWSE")) {
   echo "You Cannot Access This Script Directly, Have a Nice Day.";
   exit();
}

?>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<?php

$conn = mysql_connect("$db_host","$db_user","$db_password");
mysql_select_db("$db_name",$conn);


$sql = "select * from {$tb_prefix}payment_types ORDER BY pt_description";

$result = mysql_query($sql, $conn) or die(mysql_error());
$number_of_rows = mysql_num_rows($result);


if (mysql_num_rows($result) == 0) {
	$display_block = "<P><em>{$LANG_no_payment_types}.</em></p>";
} else {
	$display_block = <<<EOD

<b>{$LANG_manage_payment_types} :: <a href="index.php?module=payment_types&view=add">{$LANG_add_new_payment_type}</a></b>

<hr></hr>

<!-- IE hack so that the table fits on the pages -->
<!--[if gte IE 5.5]>
<link rel="stylesheet" type="text/css" href="./src/include/css/iehacks.css" media="all"/>
<![endif]-->

<table align="center" class="ricoLiveGrid" id="rico_payment_types">
<colgroup>
<col style='width:15%;' />
<col style='width:15%;' />
<col style='width:40%;' />
<col style='width:20%;' />
</colgroup>
<thead>
<tr class="sortHeader">
<th class="noFilter">{$LANG_actions}</th>
<th class="index_table">{$LANG_payment_type_id}</th>
<th class="index_table">{$LANG_description}</th>
<th class="noFilter index_table">{$wording_for_enabledField}</th>
</tr>
</thead>
EOD;

	while ($Array = mysql_fetch_array($result)) {
		$pt_idField = $Array['pt_id'];
		$pt_descriptionField = $Array['pt_description'];
		$pt_enabledField = $Array['pt_enabled'];

		if ($pt_enabledField == 1) {
			$wording_for_enabled = $wording_for_enabledField;
		} else {
			$wording_for_enabled = $wording_for_disabledField;
	  }

		$display_block .= <<<EOD
	<tr class="index_table">
	<td class="index_table"><a class="index_table"
	 href="index.php?module=payment_types&view=details&submit={$pt_idField}&action=view">{$LANG_view}</a> ::
	<a class="index_table"
	 href="index.php?module=payment_types&view=details&submit={$pt_idField}&action=edit">{$LANG_edit}</a> </td>
	<td class="index_table">{$pt_idField}</td>
	<td class="index_table">{$pt_descriptionField}</td>
	<td class="index_table">{$wording_for_enabled}</td>
	</tr>

EOD;
	
	}
	$display_block .= "</table>\n";
}

?>

<script type="text/javascript" src="include/jquery.js"></script>

<? 
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
	{ type:'number', decPlaces:0, ClassName:'alignleft' }
 ]
  };
  var menuopts = <? GridSettingsMenu(); ?>;
  new Rico.LiveGrid ('rico_payment_types', new Rico.GridMenu(menuopts), new Rico.Buffer.Base($('rico_payment_types').tBodies[0]), opts);
});
</script>
</head>

<body>

<?php 
	echo $display_block; 
?>
<!-- ./src/include/design/footer.inc.php gets called here by controller srcipt -->
