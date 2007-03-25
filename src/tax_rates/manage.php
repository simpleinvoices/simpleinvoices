<?php

//stop the direct browsing to this file - let index.php handle which files get displayed
checkLogin();



$sql = "SELECT * FROM {$tb_prefix}tax ORDER BY tax_description";

$result = mysql_query($sql, $conn) or die(mysql_error());
$number_of_rows = mysql_num_rows($result);


if (mysql_num_rows($result) == 0) {
	$display_block = "<p><em>{$LANG_no_tax_rates}.</em></p>";
}else{
	$display_block = <<<EOD

	<b>{$LANG_manage_tax_rates} ::
	<a href="./index.php?module=tax_rates&view=add">{$LANG_add_new_tax_rate}</a></b>
 <hr></hr>


<table align="center" class="ricoLiveGrid" id="rico_tax_rates">
<colgroup>
<col style='width:10%;' />
<col style='width:10%;' />
<col style='width:30%;' />
<col style='width:10%;' />
<col style='width:15%;' />
</colgroup>
<thead>
<tr class="sortHeader">
	<th class="noFilter sortable">{$LANG_actions}</th>
	<th class="index_table sortable">{$LANG_tax_id}</th>
	<th class="index_table sortable">{$LANG_tax_description}</th>
	<th class="index_table sortable">{$LANG_tax_percentage}</th>
	<th class="noFilter index_table sortable">{$wording_for_enabledField}</th>
</tr>
</thead>
EOD;

	while ($Array = mysql_fetch_array($result)) {
		$tax_idField = $Array['tax_id'];
		$tax_descriptionField = $Array['tax_description'];
		$tax_percentageField = $Array['tax_percentage'];
		$tax_enabledField = $Array['tax_enabled'];

		if ($tax_enabledField == 1) {
			$wording_for_enabled = $wording_for_enabledField;
		} else {
			$wording_for_enabled = $wording_for_disabledField;
		}

		$display_block .= <<<EOD
		<tr class="index_table">
		<td class="index_table">
		<a class="index_table"
		href="./index.php?module=tax_rates&view=details&submit={$tax_idField}&action=view">{$LANG_view}</a> ::
		<a class="index_table"
		 href="./index.php?module=tax_rates&view=details&submit={$tax_idField}&action=edit">{$LANG_edit}</a></td>
		<td class="index_table">{$tax_idField}</td>
		<td class="index_table">{$tax_descriptionField}</td>
		<td class="index_table">{$tax_percentageField}</td>
		<td class="index_table">{$wording_for_enabled}</td>
		</tr>

EOD;

	}
	$display_block .= "</table>";
}

getRicoLiveGrid("rico_tax_rates","{ type:'number', decPlaces:0, ClassName:'alignleft' },
	,
	{ type:'number', decPlaces:2, ClassName:'alignleft' }");

echo <<<EOD
<!--[if gte IE 5.5]>
<link rel="stylesheet" type="text/css" href="./src/include/css/iehacks.css" media="all"/>
<![endif]-->
EOD;

echo $display_block; 
?>
