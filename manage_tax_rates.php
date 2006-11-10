<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<?php
include('./include/include_main.php');
echo <<<EOD
<title>{$title} :: {$LANG_manage_tax_rates}</title>
<link rel="stylesheet" type="text/css" href="themes/{$theme}/tables.css" />

EOD;
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

<div id="sorting">
	<div>Sorting tables, please hold on...</div>
</div>

<table width="100%" align="center" class="filterable sortable" id="large">
	<div id="header"><b>{$LANG_manage_tax_rates}</b> ::
	<a href="insert_tax_rate.php">{$LANG_add_new_tax_rate}</a></div></th>
<tr class="sortHeader">
	<th class="noFilter">{$LANG_actions}</th>
	<th class="index_table">{$LANG_tax_id}</th>
	<th class="index_table">{$LANG_tax_description}</th>
	<th class="index_table">{$LANG_tax_percentage}</th>
	<th class="selectFilter index_table">{$wording_for_enabledField} &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</th>
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
		href="tax_rate_details.php?submit={$tax_idField}&action=view">{$LANG_view}</a> ::
		<a class="index_table"
		 href="tax_rate_details.php?submit={$tax_idField}&action=edit">{$LANG_edit}</a></td>
		<td class="index_table">{$tax_idField}</td>
		<td class="index_table">{$tax_descriptionField}</td>
		<td class="index_table">{$tax_percentageField}</td>
		<td class="index_table">{$wording_for_enabled}</td>
		</tr>

EOD;

	}
	$display_block .= "</table>";
}


$mid->printMenu('hormenu1');
$mid->printFooter();
?>
<script type="text/javascript" src="include/doFilter.js"></script>

<script type="text/javascript" src="include/jquery.js"></script>
<script type="text/javascript" src="include/tablesorter.js"></script>

<script type="text/javascript">
$(document).ready(function() {
	$("table#large").tableSorter({
		sortClassAsc: 'sortUp', // class name for asc sorting action
		sortClassDesc: 'sortDown', // class name for desc sorting action
		highlightClass: ['highlight'], // class name for sort column highlighting.
		//stripingRowClass: ['even','odd'],
		//alternateRowClass: ['odd','even'],
		headerClass: 'largeHeaders', // class name for headers (th's)
		disableHeader: [0], // disable column can be a string / number or array containing string or number.
		dateFormat: 'dd/mm/yyyy' // set date format for non iso dates default us, in this case override and set uk-format
	})
});
$(document).sortStart(function(){
	$("div#sorting").show();
}).sortStop(function(){
	$("div#sorting").hide();
});
</script>

<script type="text/javascript" src="niftycube.js"></script>
<script type="text/javascript">
window.onload=function(){
Nifty("div#container");
Nifty("div#content,div#nav","same-height small");
Nifty("div#header,div#footer","small");
}
</script>
</head>

<body>

<br>
<div id="container">
<?php echo $display_block; ?>
<div id="footer"></div>
</div>
</div>

</body>
</html>