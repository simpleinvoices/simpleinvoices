<?php
include('./include/include_main.php');

//stop the direct browsing to this file - let index.php handle which files get displayed
checkLogin();



$sql = "select * from {$tb_prefix}payment_types ORDER BY pt_description";

$result = mysql_query($sql, $conn) or die(mysql_error());
$number_of_rows = mysql_num_rows($result);


if (mysql_num_rows($result) == 0) {
	$display_block = "<P><em>{$LANG_no_payment_types}.</em></p>";
} else {
	$display_block = <<<EOD

<b>{$LANG_manage_payment_types} :: <a href="index.php?module=payment_types&view=add">{$LANG_add_new_payment_type}</a></b>

<hr></hr>

<table align="center" class="ricoLiveGrid manage" id="rico_payment_types">
<colgroup>
<col style='width:15%;' />
<col style='width:15%;' />
<col style='width:40%;' />
<col style='width:20%;' />
</colgroup>
<thead>
<tr class="sortHeader">
<th class="noFilter sortable">{$LANG_actions}</th>
<th class="index_table sortable">{$LANG_payment_type_id}</th>
<th class="index_table sortable">{$LANG_description}</th>
<th class="noFilter index_table sortable">{$wording_for_enabledField}</th>
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

getRicoLiveGrid("rico_payment_types","{ type:'number', decPlaces:0, ClassName:'alignleft' }");
	echo $display_block; 
?>