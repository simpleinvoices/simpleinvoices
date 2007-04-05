<?php

//stop the direct browsing to this file - let index.php handle which files get displayed
checkLogin();


$sql = "select * from {$tb_prefix}custom_fields ORDER BY cf_custom_field";

$result = mysql_query($sql, $conn) or die(mysql_error());
$number_of_rows = mysql_num_rows($result);


if (mysql_num_rows($result) == 0) {
	$display_block = "<P><em>{$LANG_no_invoices}.</em></p>";
} else{
	$display_block = <<<EOD


<b>{$LANG_manage_custom_fields}</b><br>
<a href="./src/documentation/info_pages/what_are_custom_fields.html" rel="gb_page_center[450, 450]">{$LANG_what_are_custom_fields}<img src="./images/common/help-small.png"></img></a> :: <a href="./src/documentation/info_pages/manage_custom_fields.html" rel="gb_page_center[450, 450]">{$LANG_whats_this_page_about}<img src="./images/common/help-small.png"></img></a>
 <hr></hr>

<table align="center" class="ricoLiveGrid manage" id="rico_custom_fields">
<colgroup>
<col style='width:10%;' />
<col style='width:10%;' />
<col style='width:40%;' />
<col style='width:40%;' />
</colgroup>
<thead>
<tr class="sortHeader">
<th class="noFilter sortable">{$LANG_actions}</th>
<th class="index_table sortable">{$LANG_id}</th>
<th class="index_table sortable">{$LANG_custom_field}</th>
<th class="index_table sortable">{$LANG_custom_label}</th>
</tr>
</thead>
EOD;

	while ($cf = mysql_fetch_array($result)) {

		//get the nice name of the custom field
		$cf['filed_name'] = get_custom_field_name($cf['cf_custom_field']);

		$display_block .= <<<EOD
	<tr class="index_table">
	<td class="index_table">
	<a class="index_table" href="index.php?module=custom_fields&view=details&submit={$cf['cf_id']}&action=view">{$LANG_view}</a> ::
	<a class="index_table" href="index.php?module=custom_fields&view=details&submit={$cf['cf_id']}&action=edit">{$LANG_edit}</a> </td>
	<td class="index_table">{$cf['cf_id']}</td>
	<td class="index_table">{$cf['filed_name']}</td>
	<td class="index_table">{$cf['cf_custom_label']}</td>
	</tr>

EOD;
	}
	$display_block .= "</table>";
}


getRicoLiveGrid("rico_custom_fields","{ type:'number', decPlaces:0, ClassName:'alignleft' }");

echo $display_block;
?>
