<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<?php
include('./include/include_main.php');
echo <<<EOD
<title>{$title} :: {$LANG_manage_payment_types}</title>
<link rel="stylesheet" type="text/css" href="themes/{$theme}/tables.css">

EOD;

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

<table width="100%" align="center" class="filterable sortable" id="large">
<div id="header"><b>{$LANG_manage_payment_types}</b> ::
<a href="insert_payment_type.php"{$LANG_add_new_payment_type}</a></div>
<tr class="sortHeader">
<th class="noFilter">{$LANG_actions}</th>
<th class="index_table">{$LANG_payment_type_id}</th>
<th class="index_table">{$LANG_description}</th>
<th class="selectFilter index_table">{$wording_for_enabledField} &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</th>
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
	 href="payment_type_details.php?submit={$pt_idField}&action=view">{$LANG_view}</a> ::
	<a class="index_table"
	 href="payment_type_details.php?submit={$pt_idField}&action=edit">{$LANG_edit}</a> </td>
	<td class="index_table">{$pt_idField}</td>
	<td class="index_table">{$pt_descriptionField}</td>
	<td class="index_table">{$wording_for_enabled}</td>
	</tr>

EOD;
	
	}
	$display_block .= "</table>\n";
}

$mid->printMenu('hormenu1');
$mid->printFooter();
?>
<script type="text/javascript" src="include/doFilter.js"></script>

<script type="text/javascript" src="include/jquery.js"></script>
<script type="text/javascript" src="include/jquery.tablesorter.js"></script>

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
