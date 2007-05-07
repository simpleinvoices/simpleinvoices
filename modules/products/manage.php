<?php

//stop the direct browsing to this file - let index.php handle which files get displayed
checkLogin();



$sql = "select * from {$tb_prefix}products ORDER BY description";

$result = mysql_query($sql, $conn) or die(mysql_error());
$number_of_rows = mysql_num_rows($result);


if (mysql_num_rows($result) == 0) {
	$display_block = "<P><em>{$LANG['no_invoices']}.</em></p>";
}else{
	$display_block = <<<EOD

<b>{$LANG['manage_products']} :: <a href="index.php?module=products&view=add">{$LANG['add_new_product']}</a></b>

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
<th class="noFilter sortable">{$LANG['actions']}</th>
<th class="index_table sortable">{$LANG['product_id']}</th>
<th class="index_table sortable">{$LANG['product_description']}</th>
<th class="index_table sortable">{$LANG['product_unit_price']}</th>
<th class="noFilter index_table sortable">{$LANG['enabled']}</th>
</tr>
</thead>
EOD;

while ($prod = mysql_fetch_array($result)) {
	/*
	$prod_idField = $Array['id'];
	$prod_descriptionField = $Array['description'];
	$prod_enabledField = $Array['enabled'];
	$prod_unit_priceField = $Array['unit_price'];
	*/
	if ($prod['enabled'] == 1) {
		$wording_for_enabled = $LANG['enabled'];
	} else {
		$wording_for_enabled = $LANG['disabled'];
	}

	$display_block .= <<<EOD
	<tr class="index_table">
	<td class="index_table">
	<a class="index_table"
	 href="index.php?module=products&view=details&submit={$prod['id']}&action=view">{$LANG['view']}</a> ::
	<a class="index_table"
	 href="index.php?module=products&view=details&submit={$prod['id']}&action=edit">{$LANG['edit']}</a> </td>
	<td class="index_table">{$prod['id']}</td>
	<td class="index_table">{$prod['description']}</td>
	<td class="index_table">{$prod['unit_price']}</td>
	<td class="index_table">{$LANG['enabled']}</td>
	</tr>

EOD;
	}
	$display_block .= "</table>";
}


getRicoLiveGrid("rico_product","{ type:'number', decPlaces:0, ClassName:'alignleft' },,{ type:'number', decPlaces:2, ClassName:'alignleft' }");

echo <<<EOD
<!--[if gte IE 5.5]>
<link rel="stylesheet" type="text/css" href="./modules/include/css/iehacks.css" media="all"/>
<![endif]-->
EOD;

echo $display_block;

?>
