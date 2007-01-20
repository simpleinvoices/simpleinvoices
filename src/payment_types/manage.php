<?php
include('./include/include_main.php');
?>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<?php

$conn = mysql_connect("$db_host","$db_user","$db_password");
mysql_select_db("$db_name",$conn);


$sql = "select * from si_payment_types ORDER BY pt_description";

$result = mysql_query($sql, $conn) or die(mysql_error());
$number_of_rows = mysql_num_rows($result);


if (mysql_num_rows($result) == 0) {
	$display_block = "<P><em>{$LANG_no_payment_types}.</em></p>";
} else {
	$display_block = <<<EOD

<div id="sorting">
	<div>Sorting tables, please hold on...</div>
</div>

<b>{$LANG_manage_payment_types} :: <a href="index.php?module=payment_types&view=add">{$LANG_add_new_payment_type}</a></b>

<hr></hr>
       <div id="browser">

<table width="100%" align="center" class="filterable sortable" id="large">
<tr class="sortHeader">
<th class="noFilter">{$LANG_actions}</th>
<th class="index_table">{$LANG_payment_type_id}</th>
<th class="index_table">{$LANG_description}</th>
<th class="noFilter index_table">{$wording_for_enabledField}</th>
</tr>

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
<script type="text/javascript" src="include/doFilter.js"></script>

<script type="text/javascript" src="include/jquery.js"></script>
<script type="text/javascript" src="include/jquery.tablesorter.js"></script>
<script type="text/javascript" src="include/jquery.tablesorter.conf.js"></script>

</head>

<body>

<?php 
	echo $display_block; 

	include("footer.inc.php");
?>

</body>
</html>
