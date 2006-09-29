<?php
include('./config/config.php');
include("./lang/$language.inc.php");
include('./include/validation.php');

jsBegin();
jsFormValidationBegin("frmpost");
jsValidateRequired("p_description","Description");
jsFormValidationEnd();
jsEnd();

#do the product enabled/disblaed drop down
$display_block_enabled = "<select name=\"pref_enabled\">
<option value=\"1\" selected>$wording_for_enabledField</option>
<option value=\"0\">$wording_for_disabledField</option>
</select>";



?>

<html>
<head>
<?php include('./include/menu.php'); ?>

    <script type="text/javascript" src="./include/jquery.js"></script>
    <script type="text/javascript" src="./include/greybox.js"></script>
    <link rel="stylesheet" type="text/css" href="themes/<?php echo $theme; ?>/tables.css" media="all"/>
    <script type="text/javascript">
      var GB_ANIMATION = true;
      $(document).ready(function(){
        $("a.greybox").click(function(){
          var t = this.title || $(this).text() || this.href;
          GB_show(t,this.href,470,600);
          return false;
        });
      });
    </script>


<script type="text/javascript" src="niftycube.js"></script>
<script type="text/javascript">
window.onload=function(){
Nifty("div#container");
Nifty("div#subheader");
Nifty("div#content,div#nav","same-height small");
Nifty("div#header,div#footer","small");
}
</script>

<title>Simple Invoices - Add invoice preference
</title>
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
<div id="header">

<table align=center>
	<tr>
		<td colspan=2 align=center><b>Invoice preference to add</b></td>
	</tr>	
</table>

</div id="header">
<div id="subheader">

<table align=center>
         <tr>
                <td>Description <a href="text/inv_pref_description.html" class="greybox">*</a></td><td><input type=text name="p_description" size=25></td>
            </tr>
                <tr>
                        <td>Currency sign <a href="text/inv_pref_currency_sign.html" class="greybox">*</a></td><td><input type=text name="p_currency_sign" size=25></td>
                </tr>
                <tr>
                        <td>Invoice heading <a href="text/inv_pref_invoice_heading.html" class="greybox">*</a></td><td><input type=text name="p_inv_heading" size=50></td>
                </tr>
                <tr>
                        <td>Invoice wording <a href="text/inv_pref_invoice_wording.html" class="greybox">*</a></td><td><input type=text name="p_inv_wording" size=50></td>
                </tr>
                <tr>
                        <td>Invoice detail heading <a href="text/inv_pref_invoice_detail_heading.html" class="greybox">*</a></td><td><input type=text name="p_inv_detail_heading" size=50></td>
                </tr>
                <tr>
                        <td>Invoice detail line <a href="text/inv_pref_invoice_detail_line.html" class="greybox">*</a></td><td><input type=text name="p_inv_detail_line" size=75></td>
                </tr>
                <tr>
                        <td>Invoice payment method <a href="text/inv_pref_invoice_payment_method.html" class="greybox">*</a></td><td><input type=text name="p_inv_payment_method" size=50></td>
                </tr>
                <tr>
                        <td>Invoice payment line 1 name <a href="text/inv_pref_payment_line1_name.html" class="greybox">*</a></td><td><input type=text name="p_inv_payment_line1_name" size=50></td>
                </tr>
                <tr>
                        <td>Invoice payment line 1 value <a href="text/inv_pref_payment_line1_value.html" class="greybox">*</a></td><td><input type=text name="p_inv_payment_line1_value" size=50></td>
                </tr>
                <tr>
                        <td>Invoice payment line 2 name <a href="text/inv_pref_payment_line2_name.html" class="greybox">*</a></td><td><input type=text name="p_inv_payment_line2_name" size=50></td>
                </tr>
                <tr>
                        <td>Invoice payment line 2 value <a href="text/inv_pref_payment_line2_value.html" class="greybox">*</a></td><td><input type=text name="p_inv_payment_line2_value" size=50></td>
                </tr>
		<tr>
			<td><?php echo $wording_for_enabledField; ?> <a href="text/inv_pref_invoice_enabled.html" class="greybox">*</a></td><td><?php echo $display_block_enabled;?></td>
		</tr>
</table>
</div>

<div id="footer">
	<input type=submit name="submit" value="Insert Preference">
	<input type=hidden name="op" value="insert_preference">
</div>

</div>
</FORM>
</BODY>
</HTML>







