<?php
include_once('./include/include_main.php');
?>
<html>
<head>
<?php

#manage products
$conn = mysql_connect("$db_host","$db_user","$db_password");
mysql_select_db("$db_name",$conn);


$sql = "select * from si_custom_fields ORDER BY cf_id";

$result = mysql_query($sql, $conn) or die(mysql_error());
$number_of_rows = mysql_num_rows($result);


if (mysql_num_rows($result) == 0) {
	$display_block = "<P><em>{$LANG_no_invoices}.</em></p>";
}else{
	$display_block = <<<EOD

<div id="sorting">
	<div>Sorting tables, please hold on...</div>
</div>

<b>{$LANG_manage_custom_fields}</b>
 <hr></hr>
       <div id="browser">

<table width="100%" align="center" class="filterable sortable" id="large">
<tr class="sortHeader">
<th class="noFilter">{$LANG_actions}</th>
<th class="index_table">{$LANG_id}</th>
<th class="index_table">{$LANG_custom_field}</th>
<th class="index_table">{$LANG_custom_label}</th>
</tr>

EOD;

while ($Array = mysql_fetch_array($result)) {
	$cf_idField = $Array['cf_id'];
	$cf_custom_fieldField = $Array['cf_custom_field'];
	$cf_custom_labelField = $Array['cf_custom_label'];
	//get the nice name of the custom field
	$custom_field_name = get_custom_field_name($cf_custom_fieldField);

	$display_block .= <<<EOD
	<tr class="index_table">
	<td class="index_table">
	<a class="index_table" href="index.php?module=custom_fields&view=details&submit={$cf_idField}&action=view">{$LANG_view}</a> ::
	<a class="index_table" href="index.php?module=custom_fields&view=details&submit={$cf_idField}&action=edit">{$LANG_edit}</a> </td>
	<td class="index_table">{$cf_idField}</td>
	<td class="index_table">{$custom_field_name}</td>
	<td class="index_table">{$cf_custom_labelField}</td>
	</tr>

EOD;
	}
	$display_block .= "</table>";
}

?>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />

<script type="text/javascript" src="include/doFilter.js"></script>

<script type="text/javascript" src="include/jquery.js"></script>
<script type="text/javascript" src="include/jquery.tablesorter.js"></script>
<script type="text/javascript" src="include/jquery.tablesorter.conf.js"></script>

</head>

<body>

<br>

<?php 
	echo $display_block;
	include("footer.inc.php");
?>

</body>
</html>
