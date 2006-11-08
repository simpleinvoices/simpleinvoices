<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />

<?php
include('./include/include_main.php');
include('./include/validation.php');

jsBegin();
jsFormValidationBegin("frmpost");
jsValidateRequired("p_description",$LANG_description);
jsFormValidationEnd();
jsEnd();

#do the product enabled/disblaed drop down
$display_block_enabled = "<select name=\"pref_enabled\">
<option value=\"1\" selected>$wording_for_enabledField</option>
<option value=\"0\">$wording_for_disabledField</option>
</select>";



?>

<?php include('./include/menu.php');
echo <<<EOD
    <script type="text/javascript" src="./include/jquery.js"></script>
    <script type="text/javascript" src="./include/greybox.js"></script>
    <link rel="stylesheet" type="text/css" href="themes/{$theme}/tables.css" media="all"/>
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

<title>Simple Invoices :: {$LANG_add_invoice_preference}
</title>
<link rel="stylesheet" type="text/css" href="themes/{$theme}/tables.css">
</head>
<BODY>

EOD;
$mid->printMenu('hormenu1');
$mid->printFooter();
echo <<<EOD

<br>

<FORM name="frmpost" ACTION="insert_action.php" METHOD=POST onsubmit="return frmpost_Validator(this)">


<div id="container">
<div id="header">

<table align=center>
	<tr>
		<td colspan=2 align=center><b>{$LANG_invoice_preference_to_add}</b></td>
	</tr>	
</table>

</div id="header">
<div id="subheader">

<table align=center>
<tr>
	<td>{$LANG_description} <a href="text/inv_pref_description.html" class="greybox">*</a></td>
	<td><input type=text name="p_description" size=25></td>
</tr>
<tr>
	<td>{$LANG_currency_sign} <a href="text/inv_pref_currency_sign.html" class="greybox">*</a></td>
	<td><input type=text name="p_currency_sign" size=25></td>
</tr>
<tr>
	<td>{$LANG_invoice_heading} <a href="text/inv_pref_invoice_heading.html" class="greybox">*</a></td>
	<td><input type=text name="p_inv_heading" size=50></td>
</tr>
<tr>
	<td>{$LANG_invoice_wording}
	<a href="text/inv_pref_invoice_wording.html" class="greybox">*</a></td>
	<td><input type=text name="p_inv_wording" size=50></td>
</tr>
<tr>
	<td>{$LANG_invoice_detail_heading}
	<a href="text/inv_pref_invoice_detail_heading.html" class="greybox">*</a></td>
	<td><input type=text name="p_inv_detail_heading" size=50></td>
</tr>
<tr>
	<td>{$LANG_invoice_detail_line}
	<a href="text/inv_pref_invoice_detail_line.html" class="greybox">*</a></td>
	<td><input type=text name="p_inv_detail_line" size=75></td>
</tr>
<tr>
	<td>{$LANG_invoice_payment_method}
	<a href="text/inv_pref_invoice_payment_method.html" class="greybox">*</a></td>
	<td><input type=text name="p_inv_payment_method" size=50></td>
</tr>
<tr>
	<td>{$LANG_invoice_payment_line_1_name}
	<a href="text/inv_pref_payment_line1_name.html" class="greybox">*</a></td>
	<td><input type=text name="p_inv_payment_line1_name" size=50></td>
</tr>
<tr>
	<td>{$LANG_invoice_payment_line_1_value}
	<a href="text/inv_pref_payment_line1_value.html" class="greybox">*</a></td>
	<td><input type=text name="p_inv_payment_line1_value" size=50></td>
</tr>
<tr>
	<td>{$LANG_invoice_payment_line_2_name}
	<a href="text/inv_pref_payment_line2_name.html" class="greybox">*</a></td>
	<td><input type=text name="p_inv_payment_line2_name" size=50></td>
</tr>
<tr>
	<td>{$LANG_invoice_payment_line_2_value}
	<a href="text/inv_pref_payment_line2_value.html" class="greybox">*</a></td>
	<td><input type=text name="p_inv_payment_line2_value" size=50></td>
</tr>
<tr>
	<td>{$wording_for_enabledField}
	<a href="text/inv_pref_invoice_enabled.html" class="greybox">*</a></td>
	<td>{$display_block_enabled}</td>
</tr>
</table>
</div>

<div id="footer">
	<input type=submit name="submit" value="{$LANG_insert_preference}">
	<input type=hidden name="op" value="insert_preference">
</div>

EOD;
?>
</div>
</FORM>
</BODY>
</HTML>