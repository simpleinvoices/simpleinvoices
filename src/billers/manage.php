<?php
include('./include/include_main.php');

#insert customer
$conn = mysql_connect( $db_host, $db_user, $db_password );
mysql_select_db( $db_name, $conn );


$sql = 'SELECT * FROM si_biller ORDER BY b_name';

$result = mysql_query($sql, $conn) or die(mysql_error());
$number_of_rows = mysql_num_rows($result);


if (mysql_num_rows($result) == 0) {
$display_block = "<P><em>$mb_no_invoices.</em></p>";
}else{
$display_block = "
<b>$mb_page_header :: <a href='index.php?module=billers&view=add'>$mb_actions_new_biller</a></b>
 <hr></hr>
       <div id='browser'>


<div id=\"sorting\">
       <div>Sorting tables, please hold on...</div>
</div>


<table width=100% class=\"filterable sortable\" id=large align=center>
<tr class=\"sortHeader\">
<th class=\"noFilter\">$mb_table_action</th>
<th class=\" index_table\">$mb_table_biller_id</th>
<th class=\"index_table\">$mb_table_biller_name</th>
<th class=\"index_table\">$mb_table_phone</th>
<th class=\"index_table\">$mb_table_mobile_phone</th>
<th class=\"index_table\">$mb_table_email</th>
<th class=\"noFilter index_table\">$wording_for_enabledField</th>
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
	<td class='index_table'><a class='index_table' href='index.php?module=billers&view=details&submit=$b_idField&action=view'>$mb_actions_view</a> :: <a class='index_table' href='index.php?module=billers&view=details&submit=$b_idField&action=edit'>$mb_actions_edit</a></td>
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
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />

<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<script type="text/javascript" src="include/doFilter.js"></script>

<script type="text/javascript" src="include/jquery.js"></script>
<script type="text/javascript" src="include/jquery.tablesorter.js"></script>
<script type="text/javascript" src="include/jquery.tablesorter.conf.js"></script>

<?php include('./config/config.php'); ?>
</head>
<body>

<?php 
	echo $display_block; 
	include("footer.inc.php");
?>
</body>
</html>
