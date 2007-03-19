<?php
include_once('./include/include_main.php');

//stop the direct browsing to this file - let index.php handle which files get displayed
checkLogin();



$sql = "select * from {$tb_prefix}products ORDER BY prod_description";

$result = mysql_query($sql, $conn) or die(mysql_error());
$number_of_rows = mysql_num_rows($result);


if (mysql_num_rows($result) == 0) {
	$display_block = "<P><em>{$LANG_no_invoices}.</em></p>";
}else{
	$display_block = <<<EOD

<b>{$LANG_manage_products} :: <a href="index.php?module=products&view=add">{$LANG_add_new_product}</a></b>

 <hr></hr>

<table align="center" class="ricoLiveGrid" id="rico_product">
<colgroup>
<col style='width:10%;' />
<col style='width:10%;' />
<col style='width:50%;' />
<col style='width:20%;' />
<col style='width:10%;' />
</colgroup>
<thead>
<tr class="sortHeader">
<th class="noFilter sortable">{$LANG_actions}</th>
<th class="index_table sortable">{$LANG_product_id}</th>
<th class="index_table sortable">{$LANG_product_description}</th>
<th class="index_table sortable">{$LANG_product_unit_price}</th>
<th class="noFilter index_table sortable">{$wording_for_enabledField}</th>
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


getRicoLiveGrid("rico_product","{ type:'number', decPlaces:0, ClassName:'alignleft' },,{ type:'number', decPlaces:2, ClassName:'alignleft' }");

echo <<<EOD
<!--[if gte IE 5.5]>
<link rel="stylesheet" type="text/css" href="./src/include/css/iehacks.css" media="all"/>
<![endif]-->
EOD;

echo $display_block;

?>
