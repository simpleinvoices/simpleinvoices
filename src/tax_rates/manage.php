<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<?php
include('./include/include_main.php');

#insert customer
$conn = mysql_connect("$db_host","$db_user","$db_password");
mysql_select_db("$db_name",$conn);


$sql = "select * from si_tax ORDER BY tax_description";

$result = mysql_query($sql, $conn) or die(mysql_error());
$number_of_rows = mysql_num_rows($result);


if (mysql_num_rows($result) == 0) {
	$display_block = "<p><em>{$LANG_no_tax_rates}.</em></p>";
}else{
	$display_block = <<<EOD

	<b>{$LANG_manage_tax_rates} ::
	<a href="./index.php?module=tax_rates&view=add">{$LANG_add_new_tax_rate}</a></b>
 <hr></hr>

<div id="sorting">
	<div>Sorting tables, please hold on...</div>
</div>

       <div id="browser">

<table width="100%" align="center" class="filterable sortable" id="large">
<tr class="sortHeader">
	<th class="noFilter">{$LANG_actions}</th>
	<th class="index_table">{$LANG_tax_id}</th>
	<th class="index_table">{$LANG_tax_description}</th>
	<th class="index_table">{$LANG_tax_percentage}</th>
	<th class="noFilter index_table">{$wording_for_enabledField}</th>
</tr>

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
