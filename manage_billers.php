<?php

include('./config/config.php');
include("./lang/$language.inc.php");

#insert customer
$conn = mysql_connect( $db_host, $db_user, $db_password );
mysql_select_db( $db_name, $conn );


$sql = 'SELECT * FROM si_biller';

$result = mysql_query($sql, $conn) or die(mysql_error());
$number_of_rows = mysql_num_rows($result);


if (mysql_num_rows($result) == 0) {
$display_block = "<P><em>$mb_no_invoices.</em></p>";
}else{
$display_block = "

<div id=\"sorting\">
       <div>Sorting tables, please hold on...</div>
</div>


<table width=100% class=\"filterable sortable\" id=large align=center>
<div id=header><b>$mb_page_header</b> :: <a href='insert_biller.php'>$mb_actions_new_biller</a></div>
<tr class=\"sortHeader\">
<th class=\"noFilter\">$mb_table_action</th>
<th class=\" index_table\">$mb_table_biller_id</th>
<th class=\"index_table\">$mb_table_biller_name</th>
<th class=\"index_table\">$mb_table_phone</th>
<th class=\"index_table\">$mb_table_mobile_phone</th>
<th class=\"index_table\">$mb_table_email</th>
<th class=\"selectFilter index_table\">$wording_for_enabledField &nbsp;&nbsp;&nbsp;&nbsp;&nbsp; &nbsp; </th>
</tr>";

while ($Array = mysql_fetch_array($result)) {
	$b_idField = $Array['b_id'];
	$b_mobile_phoneField = $Array['b_mobile_phone'];
	$b_nameField = $Array['b_name'];
	$b_phoneField = $Array['b_phone'];
	$b_emailField = $Array['b_email'];
	$b_enabledField = $Array['b_enabled'];
	
        if ($b_enabledField == 1) {
                $wording_for_enabled = $wording_for_enabledField;
        } else {
                $wording_for_enabled = $wording_for_disabledField;
        }



	$display_block .= "
	<tr class='index_table'>
	<td class='index_table'><a class='index_table' href='biller_details.php?submit=$b_idField&action=view'>$mb_actions_view</a> :: <a class='index_table' href='biller_details.php?submit=$b_idField&action=edit'>$mb_actions_edit</a></td>
	<td class='index_table'>$b_idField</td>
	<td class='index_table'>$b_nameField</td>
	<td class='index_table'>$b_phoneField</td>
	<td class='index_table'>$b_mobile_phoneField</td>
	<td class='index_table'>$b_emailField</td>
	<td class='index_table'>$wording_for_enabled</td>
	</tr>";

                
	
		}
		

        $display_block .="</table>";
}



?>
<html>
<head>
<?php include('./include/menu.php'); ?>
<?php
$mid->printMenu('hormenu1');
$mid->printFooter();
?>

<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
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


<title><?php echo $title; echo $mb_page_title;?></title>
<?php include('./config/config.php'); ?>
</head>
<body>

<link rel="stylesheet" type="text/css" href="themes/<?php echo $theme; ?>/tables.css">
<br>
<div id="container">
<?php echo $display_block; ?>
<div id="footer"></div>
</div>
</div>

</body>

