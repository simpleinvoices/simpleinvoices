<?php
include('./config/config.php'); 
include('./include/menu.php');
include("./lang/$language.inc.php");

/* validataion code */
include("./include/validation.php");

jsBegin();
jsFormValidationBegin("frmpost");
jsValidateRequired("c_name","Customer name");
jsFormValidationEnd();
jsEnd();

/* end validataion code */

#do the product enabled/disblaed drop down
$display_block_enabled = "<select name=\"c_enabled\">
<option value=\"1\" selected>$wording_for_enabledField</option>
<option value=\"0\">$wording_for_disabledField</option>
</select>";


?>

<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<script type="text/javascript" src="niftycube.js"></script>
<script type="text/javascript">
window.onload=function(){
Nifty("div#container");
Nifty("div#content,div#nav","same-height small");
Nifty("div#header,div#footer","small");
}
</script>

<title>Simple Invoices - Add customer</title>


</head>
<BODY>
<?php
$mid->printMenu('hormenu1');
$mid->printFooter();
?>

<link rel="stylesheet" type="text/css" href="themes/<?php echo $theme; ?>/tables.css">
<br>

<FORM name="frmpost" ACTION="insert_action.php" METHOD=POST onsubmit="return frmpost_Validator(this)">

<div id="container">
<div id="header"></div>

<table align=center>
<tr>
<td colspan=2 align=center><b>Customer to add</b></th>
<tr>
<td>Customer Name</td><td><input type=text name="c_name" size=25></td>
</tr>
</tr>
<td>Customer contact (Attn)</th><td><input type=text name="c_attention" size=25></td>
</tr>
<tr>
<td>Address: Street</td><td><input type=text name="c_street_address" size=25></td>
</tr>
<tr>
<td>Address: City</td><td><input type=text name="c_city" size=25></td>
</tr>
<tr>
<td>Address: State</td><td><input type=text name="c_state" size=25></td>
</tr>
<tr>
<td>Address: Zip</td><td><input type=text name="c_zip_code" size=25></td>
</tr>
<tr>
<td>Address: Country (optional)</td><td><input type=text name="c_country" size=75></td>
</tr>
<tr>
<td>Phone</td><td><input type=text name="c_phone" size=25></td>
</tr>
<tr>
<td>Fax</td><td><input type=text name="c_fax" size=25></td>
</tr>
<tr>
<td>Email</td><td><input type=text name="c_email" size=25></td>
</tr>
<tr>
<td><?php echo $wording_for_enabledField; ?></td><td><?php echo $display_block_enabled;?></td>
</tr>

</table>


<div id="footer">
	<input type=submit name="submit" value="Insert Customer">
	<input type=hidden name="op" value="insert_customer">
</div>

</div>
</div>
</FORM>
</BODY>
</HTML>







