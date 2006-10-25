<?php

include("./include/validation.php");
include('./include/validation.php');

jsBegin();
jsFormValidationBegin("frmpost");
jsValidateRequired("pt_description","Payment type description");
jsFormValidationEnd();
jsEnd();



#get the invoice id
$payment_type_id = $_GET['submit'];


#Info from DB print
$conn = mysql_connect("$db_host","$db_user","$db_password");
mysql_select_db("$db_name",$conn);



#customer query
$print_payment_type = "SELECT * FROM si_payment_types WHERE pt_id = $payment_type_id";
$result_payment_type = mysql_query($print_payment_type, $conn) or die(mysql_error());


while ($Array = mysql_fetch_array($result_payment_type) ) {
                $pt_idField = $Array['pt_id'];
                $pt_descriptionField = $Array['pt_description'];
	        $pt_enabledField = $Array['pt_enabled'];

        	if ($pt_enabledField == 1) {
	                $wording_for_enabled = $wording_for_enabledField;	
	        } else {
	                $wording_for_enabled = $wording_for_disabledField;
	        }

};


if ($_GET[action] === 'view') {

$display_block =  "
	
	<table align=center>
	<tr>
		<td colspan=2 align=center><i>Payment Type</i></td>
	</tr>	
	<tr>
		<td class='details_screen'>Payment Type ID</td><td>$pt_idField</td>
	</tr>
	<tr>
		<td class='details_screen'>Description</td><td>$pt_descriptionField</td>
	</tr>
        <tr>
                <td class='details_screen'>$wording_for_enabledField</td><td>$wording_for_enabled</td>
        </tr>
	</table>
";

$footer =  "

<div id='footer'><a href='?submit=$pt_idField&action=edit'>Edit</a></div>
";

}


else if ($_GET[action] === 'edit') {

#do the product enabled/disblaed drop down
$display_block_enabled = "<select name=\"pt_enabled\">
<option value=\"$pt_enabledField\" selected style=\"font-weight: bold\">$wording_for_enabled</option>
<option value=\"1\">$wording_for_enabledField</option>
<option value=\"0\">$wording_for_disabledField</option>
</select>";

$display_block =  "

        <table align=center>
        <tr>
                <td colspan=2 align=center><i>Payment Type</i></td>
        </tr>
        <tr>
                <td class='details_screen'>Payment Type ID</td><td>$pt_idField</td>
        </tr>
        <tr>
                <td class='details_screen'>Description</td><td><input type=text name='pt_description' value='$pt_descriptionField' size=50></td>
        </tr>
        <tr>
                <td class='details_screen'>$wording_for_enabledField </td><td>$display_block_enabled</td>
        </tr>
        </table>

";

$footer =  "

<p><input type=submit name='action' value='Cancel'>
<input type=submit name='action' value='Save Payment Type'> <input type=hidden name='op' value='edit_payment_type'></p>
";


}




?>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<?php include('./include/menu.php'); ?>
<script type="text/javascript" src="niftycube.js"></script>
<script type="text/javascript">
window.onload=function(){
Nifty("div#container");
Nifty("div#content,div#nav","same-height small");
Nifty("div#header,div#footer","small");
}
</script>
<title>Simple Invoices - Tax rate details</title>
<?php include('./config/config.php'); ?>
</head>
<body>
<?php
$mid->printMenu('hormenu1');
$mid->printFooter();
?>

<link rel="stylesheet" type="text/css" href="themes/<?php echo $theme; ?>/tables.css">
<br>
<form name="frmpost" action="insert_action.php?submit=<?php echo $_GET['submit'];?>" method=post onsubmit="return frmpost_Validator(this)">
<div id="container">
<div id="header"></div>
<?php echo $display_block; ?>
<div id="footer">
<?php echo $footer; ?>
</div>
</div>
</body>
</html>




