<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<?php
include('./include/include_main.php');
/* validataion code */
include("./include/validation.php");
echo <<<EOD
<title>Simple Invoices :: {$LANG_add_biller}</title>
<link rel="stylesheet" type="text/css" href="themes/{$theme}/tables.css" media="all"/>
<link rel="stylesheet" type="text/css" href="themes/{$theme}/jquery.thickbox.css" media="all"/>

EOD;
jsBegin();
jsFormValidationBegin("frmpost");
jsValidateRequired("b_name",$LANG_biller_name);
jsFormValidationEnd();
jsEnd();

/* end validataion code */

/*drop down list code for invoice logo */


$dirname="images/logo";
   $ext = array("jpg", "png", "jpeg", "gif");
   $files = array();
   if($handle = opendir($dirname)) {
       while(false !== ($file = readdir($handle)))
           for($i=0;$i<sizeof($ext);$i++)
               if(stristr($file, ".".$ext[$i])) //NOT case sensitive: OK with JpeG, JPG, ecc.
                   $files[] = $file;
       closedir($handle);
   }

sort($files);



$display_block_logo_list = "<select name=\"b_co_logo\">";
$display_block_logo_list .= "<option selected value=\"_default_blank_logo.png\" style=\"font-weight: bold\">_default_blank_logo.png</option>";

foreach ($files as $var)
{
	$display_block_logo_list .= "<option>$var</option>";
}
$display_block_logo_list .= "</select>";

/*end logo stuff */

#do the product enabled/disblaed drop down
$display_block_enabled = "<select name=\"b_enabled\">
<option value=\"1\" selected>$wording_for_enabledField</option>
<option value=\"0\">$wording_for_disabledField</option>
</select>";

#get custom field labels
$biller_custom_field_label1 = get_custom_field_label(biller_cf1,'.');
$biller_custom_field_label2 = get_custom_field_label(biller_cf2,'.');
$biller_custom_field_label3 = get_custom_field_label(biller_cf3,'.');
$biller_custom_field_label4 = get_custom_field_label(biller_cf4,'.');

?>
<script type="text/javascript" src="niftycube.js"></script>
<script type="text/javascript">
window.onload=function(){
Nifty("div#container");
Nifty("div#subheader");
Nifty("div#content,div#nav","same-height small");
Nifty("div#header,div#footer","small");
}
</script>
<script language="javascript" type="text/javascript" src="include/tiny_mce/tiny_mce_src.js"></script>
<script language="javascript" type="text/javascript" src="include/tiny-mce.conf.js"></script>
<script type="text/javascript" src="./include/jquery.js"></script>
<script type="text/javascript" src="./include/jquery.thickbox.js"></script>
</head>

<BODY>

<?php
$mid->printMenu('hormenu1');
$mid->printFooter();
echo <<<EOD

<br>

<FORM name="frmpost" ACTION="index.php?module=billers&view=save" METHOD=POST onsubmit="return frmpost_Validator(this)">
<div id="container">

<div id="header"><b>{$LANG_biller_to_add}</b></table>

</div>
<div id="subheader">

<table align="center">
	<tr>
		<td>{$LANG_biller_name}</td><td><input type=text name="b_name" size=25></td>
	</tr>
	<tr>
		<td>{$LANG_street}</td><td><input type=text name="b_street_address" size=25></td>
	</tr>
	<tr>
		<td>{$LANG_street2} <a href="./documentation/text/street2.html?keepThis=true&TB_iframe=true&height=300&width=500" title="Info :: Street address 2" class="thickbox">*</a></td><td><input type=text name="b_street_address2" size=25></td>
	</tr>
	<tr>
		<td>{$LANG_city}</td><td><input type=text name="b_city" size=25></td>
	</tr>
	<tr>
		<td>{$LANG_state}</td><td><input type=text name="b_state" size=25></td>
	</tr>
	<tr>
		<td>{$LANG_zip}</td><td><input type=text name="b_zip_code" size=25></td>
	</tr>
	<tr>
		<td>{$LANG_country} ({$LANG_optional})</td><td><input type=text name="b_country" size=75></td>
	</tr>
	<tr>
		<td>{$LANG_phone}</td><td><input type=text name="b_phone" size=25></td>
	</tr>
	<tr>
		<td>{$LANG_mobile_phone}</td><td><input type=text name="b_mobile_phone" size=25></td>
	</tr>
	<tr>
		<td>{$LANG_fax}</td><td><input type=text name="b_fax" size=25></td>
	</tr>
	<tr>
		<td>{$LANG_email}</td><td><input type=text name="b_email" size=25></td>
	</tr>
	<tr>
		<td>{$biller_custom_field_label1} <a href="./documentation/text/custom_fields.html?keepThis=true&TB_iframe=true&height=300&width=500" title="Info :: Custom fields" class="thickbox">*</a></td><td><input type=text name="b_custom_field1" size=25></td>
	</tr>
	<tr>
		<td>{$biller_custom_field_label2} <a href="./documentation/text/custom_fields.html?keepThis=true&TB_iframe=true&height=300&width=500" title="Info :: Custom fields" class="thickbox">*</a></td><td><input type=text name="b_custom_field2" size=25></td>
	</tr>
	<tr>
		<td>{$biller_custom_field_label3} <a href="./documentation/text/custom_fields.html?keepThis=true&TB_iframe=true&height=300&width=500" title="Info :: Custom fields" class="thickbox">*</a></td><td><input type=text name="b_custom_field3" size=25></td>
	</tr>
	<tr>
		<td>{$biller_custom_field_label4} <a href="./documentation/text/custom_fields.html?keepThis=true&TB_iframe=true&height=300&width=500" title="Info :: Custom fields" class="thickbox">*</a></td><td><input type=text name="b_custom_field4" size=25></td>
	</tr>
	<tr>
		<td>{$LANG_logo_file} <a href="./documentation/text/insert_biller_text.html?keepThis=true&TB_iframe=true&height=300&width=500" title="Info :: Custom fields" class="thickbox">*</a></td><td>{$display_block_logo_list}</td>
	</tr>
	<tr>
		<td>{$LANG_invoice_footer}</td><td><textarea input type=text name="b_co_footer" rows=4 cols=50></textarea></td>
	</tr>
	<tr>
    <td>{$LANG_notes}</td><td><textarea input type=text name="b_notes" rows=8 cols=50></textarea></td>
	</tr>

	<tr>
		<td>{$wording_for_enabledField}</td><td>{$display_block_enabled}</td>
	</tr>

</table>


</div>
<div id="footer">
	<input type="submit" name="submit" value="{$LANG_insert_biller}" />
	<input type="hidden" name="action" value="insert_biller" />
</div>

EOD;
?>

</FORM>
</BODY>
</HTML>
