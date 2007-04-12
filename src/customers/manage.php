<?php


//stop the direct browsing to this file - let index.php handle which files get displayed
checkLogin();

$sql = "SELECT * FROM {$tb_prefix}customers ORDER BY c_name";

$result = mysql_query($sql, $conn) or die(mysql_error());


if (mysql_num_rows($result) == 0) {
	$display_block = "<P><em>{$LANG['no_customers']}.</em></p>";
} else {
	$display_block = <<<EOD


<b>{$LANG['manage_customers']} :: <a href="index.php?module=customers&view=add">{$LANG['customer_add']}</a></b>
<hr></hr>

<table align="center" id="rico_customer" class="ricoLiveGrid manage">
<colgroup>
<col style='width:10%;' />
<col style='width:5%;' />
<col style='width:25%;' />
<col style='width:15%;' />
<col style='width:15%;' />
<col style='width:15%;' />
</colgroup>
<thead>
<tr class="sortHeader">
<th class="noFilter sortable">{$LANG['actions']}</th>
<th class="index_table sortable">{$LANG['customer_id']}</th>
<th class="index_table sortable">{$LANG['customer_name']}</th>
<!--
<th class="index_table">{$LANG['phone']}</th>
-->
<th class="index_table sortable">{$LANG['total']}</th>
<!--
<th class="index_table">{$LANG['paid']}</th>
-->
<th class="index_table sortable">{$LANG['owing']}</th>
<th class="noFilter index_table sortable">{$wording_for_enabledField}</th>
</tr>
</thead>
EOD;

while ($customer = mysql_fetch_array($result)) {

		if ($customer['c_enabled'] == 1) {
			$wording_for_enabled = $wording_for_enabledField;
		} else {
			$wording_for_enabled = $wording_for_disabledField;
		}

		#invoice total calc - start
		$invoice['total'] = calc_customer_total($customer['c_id'] );
		#invoice total calc - end

		#amount paid calc - start
		$invoice['paid'] = calc_customer_paid($customer['c_id']);
		#amount paid calc - end

		#amount owing calc - start
		$invoice['owing'] = $invoice['total'] - $invoice['paid'];
		#amount owing calc - end

		$display_block .= <<<EOD
	<tr class="index_table">
	<td class="index_table"><a class="index_table"
	 href="index.php?module=customers&view=details&submit={$customer['c_id']}&action=view">{$LANG['view']}</a> ::
	<a class="index_table"
	 href="index.php?module=customers&view=details&submit={$customer['c_id']}&action=edit">{$LANG['edit']}</a> </td>
	<td class="index_table">{$customer['c_id']}</td>
	<td class="index_table">{$customer['c_name']}</td>
	<!--
	<td class="index_table">{$customer['c_phone']}</td>
	-->
	<td class="index_table">{$invoice['total']}</td>
	<!--
	<td class="index_table">{$invoice['paid']}</td>
	-->
	<td class="index_table">{$invoice['owing']}</td>
	<td class="index_table">{$wording_for_enabled}</td>
	</tr>

EOD;
	}
	$display_block .= "</table>";
}

getRicoLiveGrid("rico_customer","	{ type:'number', decPlaces:0, ClassName:'alignleft' },,{ type:'number', decPlaces:2, ClassName:'alignleft' },{ type:'number', decPlaces:2, ClassName:'alignleft' }");


echo $display_block;

?>
