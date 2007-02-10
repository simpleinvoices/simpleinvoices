<?php
include_once('./include/include_main.php');
?>

<html>
<head>
<?php

#manage products
$conn = mysql_connect("$db_host","$db_user","$db_password");
mysql_select_db("$db_name",$conn);


$sql = "select * from si_products ORDER BY prod_description";

$result = mysql_query($sql, $conn) or die(mysql_error());
$number_of_rows = mysql_num_rows($result);


if (mysql_num_rows($result) == 0) {
	$display_block = "<P><em>{$LANG_no_invoices}.</em></p>";
}else{
	$display_block = <<<EOD

<b>{$LANG_manage_products} :: <a href="index.php?module=products&view=add">{$LANG_add_new_product}</a></b>

 <hr></hr>
       <div id="browser"

<div id="sorting">
</div>

<table width="97%" align="center" class="ricoLiveGrid" id="rico_product">
<colgroup>
<col style='width:10%;' />
<col style='width:10%;' />
<col style='width:40%;' />
<col style='width:20%;' />
<col style='width:10%;' />
<col style='width:10%;' />
</colgroup>
<thead>
<tr class="sortHeader">
<th class="noFilter">{$LANG_actions}</th>
<th class="index_table">{$LANG_product_id}</th>
<th class="index_table">{$LANG_product_description}</th>
<th class="index_table">{$LANG_product_unit_price}</th>
<th class="noFilter index_table">{$wording_for_enabledField}</th>
</tr>
</thead>
EOD;

while ($Array = mysql_fetch_array($result)) {
	$prod_idField = $Array['prod_id'];
	$prod_descriptionField = $Array['prod_description'];
	$prod_enabledField = $Array['prod_enabled'];
	$prod_unit_priceField = $Array['prod_unit_price'];
	
	if ($prod_enabledField == 1) {
		$wording_for_enabled = $wording_for_enabledField;
	} else {
		$wording_for_enabled = $wording_for_disabledField;
	}

	$display_block .= <<<EOD
	<tr class="index_table">
	<td class="index_table">
	<a class="index_table"
	 href="index.php?module=products&view=details&submit={$prod_idField}&action=view">{$LANG_view}</a> ::
	<a class="index_table"
	 href="index.php?module=products&view=details&submit={$prod_idField}&action=edit">{$LANG_edit}</a> </td>
	<td class="index_table">{$prod_idField}</td>
	<td class="index_table">{$prod_descriptionField}</td>
	<td class="index_table">{$prod_unit_priceField}</td>
	<td class="index_table">{$wording_for_enabled}</td>
	</tr>

EOD;
	}
	$display_block .= "</table>";
}

?>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />


<script type="text/javascript" src="include/jquery.js"></script>

<? 
require "lgplus/php/chklang.php";
require "lgplus/php/settings.php";
?>

<script src="lgplus/js/rico.js" type="text/javascript"></script>
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
	{ type:'number', decPlaces:2, ClassName:'alignleft' }
 ]
  };
  var menuopts = <? GridSettingsMenu(); ?>;
  new Rico.LiveGrid ('rico_product', new Rico.GridMenu(menuopts), new Rico.Buffer.Base($('rico_product').tBodies[0]), opts);
});
</script>

</head>

<body>

<?php echo $display_block; ?>
<!-- ./src/include/design/footer.inc.php gets called here by controller srcipt -->
