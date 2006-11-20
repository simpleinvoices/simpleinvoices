<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<?php
#table
include('./include/include_main.php'); 
include("./include/validation.php");
echo <<<EOD
<title>{$title} :: {$LANG_biller_details}</title>
<link rel="stylesheet" type="text/css" href="themes/{$theme}/tables.css">

EOD;
/*validation code*/
jsBegin();
jsFormValidationBegin("frmpost");
jsValidateRequired("b_name",$LANG_biller_name);
jsFormValidationEnd();
jsEnd();
/*end validation code*/


#get the invoice id
$biller_id = $_GET[submit];


#Info from DB print
$conn = mysql_connect("$db_host","$db_user","$db_password");
mysql_select_db("$db_name",$conn);


#biller query
$print_biller = "SELECT * FROM si_biller WHERE b_id = $biller_id";
$result_print_biller = mysql_query($print_biller, $conn) or die(mysql_error());


while ($Array = mysql_fetch_array($result_print_biller) ) {
		$b_idField = $Array['b_id'];
		$b_mobile_phoneField = $Array['b_mobile_phone'];
		$b_nameField = $Array['b_name'];
		$b_street_addressField = $Array['b_street_address'];
		$b_street_address2Field = $Array['b_street_address2'];
		$b_cityField = $Array['b_city'];
		$b_stateField = $Array['b_state'];
		$b_zip_codeField = $Array['b_zip_code'];
		$b_countryField = $Array['b_country'];
		$b_phoneField = $Array['b_phone'];
		$b_faxField = $Array['b_fax'];
		$b_emailField = $Array['b_email'];
		$b_co_logoField = $Array['b_co_logo'];
		$b_co_footerField = $Array['b_co_footer'];
		$b_notesField = $Array['b_notes'];
		$b_custom_field1Field = $Array['b_custom_field1'];
		$b_custom_field2Field = $Array['b_custom_field2'];
		$b_custom_field3Field = $Array['b_custom_field3'];
		$b_custom_field4Field = $Array['b_custom_field4'];
		$b_enabledField = $Array['b_enabled'];

		if ($b_enabledField == 1) {
			$wording_for_enabled = $wording_for_enabledField;
		} else {
			$wording_for_enabled = $wording_for_disabledField;
		}

};

/*drop down list code for invoice logo */

$dirname="logo";
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

$display_block_logo_list .= "<option selected value='$b_co_logoField' style=\"font-weight: bold\">$b_co_logoField</option>";

foreach ( $files as $var )
{
	$display_block_logo_list .= "<option>{$var}</option>";
}
$display_block_logo_list .= "</select>";

/*end logo stuff */

#get custom field labels
$biller_custom_field_label1 = get_custom_field_label(biller_cf1);
$biller_custom_field_label2 = get_custom_field_label(biller_cf2);
$biller_custom_field_label3 = get_custom_field_label(biller_cf3);
$biller_custom_field_label4 = get_custom_field_label(biller_cf4);


if ($_GET['action'] == "view") {

	$display_block = <<<EOD

	<div id="header"><b>{$LANG_biller}</b> ::
	<a href="?submit={$b_idField}&action=edit">{$LANG_edit}</a></div>

	<table align="center">
	<tr>
		<td class="details_screen">{$LANG_biller_id}</td><td>{$b_idField}</td>
	</tr>
	<tr>
		<td class="details_screen">{$LANG_biller_name}</td><td>{$b_nameField}</td>
	</tr>
	<tr>
		<td class="details_screen">{$LANG_address_street}</td><td>{$b_street_addressField}</td>
	</tr>
	<tr>
		<td class="details_screen">{$LANG_address_street} 2 - CHANGE</td><td>{$b_street_address2Field}</td>
	</tr>
	<tr>
		<td class="details_screen">{$LANG_city}</td><td>{$b_cityField}</td>
	</tr>
	<tr>
		<td class="details_screen">{$LANG_zip}</td><td>{$b_zip_codeField}</td>
	</tr>
	<tr>
		<td class="details_screen">{$LANG_state}</td><td>{$b_stateField}</td>
	</tr>
	<tr>
		<td class="details_screen">{$LANG_country}</td><td>{$b_countryField}</td>
	</tr>
	<tr>
		<td class="details_screen">{$LANG_mobile_phone}</td><td>{$b_mobile_phoneField}</td>
	</tr>
	<tr>
		<td class="details_screen">{$LANG_phone}</td><td>{$b_phoneField}</td>
	</tr>
	<tr>
		<td class="details_screen">{$LANG_fax}</td><td>{$b_faxField}</td>
	</tr>	
	<tr>
		<td class="details_screen">{$LANG_email}</td><td>{$b_emailField}</td>
	</tr>	
	<tr>
		<td class="details_screen">{$biller_custom_field_label1} <a href="./documentation/text/custom_fields.html" class="greybox">*</a></td>
		<td>{$b_custom_field1Field}</td>
	</tr>
	<tr>
		<td class="details_screen">{$biller_custom_field_label2} <a href="./documentation/text/custom_fields.html" class="greybox">*</a></td>
		<td>{$b_custom_field2Field}</td>
	</tr>
	<tr>
		<td class="details_screen">{$biller_custom_field_label3} <a href="./documentation/text/custom_fields.html" class="greybox">*</a></td>
		<td>{$b_custom_field3Field}</td>
	</tr>
	<tr>
		<td class="details_screen">{$biller_custom_field_label4} <a href="./documentation/text/custom_fields.html" class="greybox">*</a></td>
		<td>{$b_custom_field4Field}</td>
	</tr>
	<tr>
		<td class="details_screen">{$LANG_logo_file}</td><td>{$b_co_logoField}</td>
	</tr>	
	<tr>
		<td class="details_screen">{$LANG_invoice_footer}</td><td>{$b_co_footerField}</td>
	</tr>
	<tr>
		<td class="details_screen">{$LANG_notes}</td><td>{$b_notesField}</td>
	</tr>
	<tr>
		<td class="details_screen">{$wording_for_enabledField}</td><td>{$wording_for_enabled}</td>
	</tr>
	</table>

EOD;

$footer = <<<EOD

<div id="footer"><a href="?submit={$b_idField}&action=edit">{$LANG_edit}</a></div>

EOD;

}

else if ($_GET['action'] == "edit") {

#do the product enabled/disblaed drop down
$display_block_enabled = "<select name=\"b_enabled\">
<option value=\"$b_enabledField\" selected style=\"font-weight: bold\">$wording_for_enabled</option>
<option value=\"1\">$wording_for_enabledField</option>
<option value=\"0\">$wording_for_disabledField</option>
</select>";

$display_block = <<<EOD
	<div id="header"><b>{$LANG_biller}</b></div>
	<table align="center">
	<tr>
		<td class="details_screen">{$LANG_biller_id}</td><td>{$b_idField}</td>
	</tr>
	<tr>
		<td class="details_screen">{$LANG_biller_name}</td>
		<td><input type=text name="b_name" value="{$b_nameField}" size=50 /></td>
	</tr>
	<tr>
		<td class="details_screen">{$LANG_address_street}</td>
		<td><input type=text name="b_street_address" value="{$b_street_addressField}" size=50 /></td>
	</tr>
	<tr>
		<td class="details_screen">{$LANG_address_street}2-CHANGE</td>
		<td><input type=text name="b_street_address2" value="{$b_street_address2Field}" size=50 /></td>
	</tr>
	<tr>
		<td class="details_screen">{$LANG_city}</td>
		<td><input type=text name="b_city" value="{$b_cityField}" size=50 /></td>
	</tr>
	<tr>
		<td class="details_screen">{$LANG_zip}</td>
		<td><input type=text name="b_zip_code" value="{$b_zip_codeField}" size=50 /></td>
	</tr>
	<tr>
		<td class="details_screen">{$LANG_state}</td>
		<td><input type=text name="b_state" value="{$b_stateField}" size=50 /></td>
	</tr>
	<tr>
		<td class="details_screen">{$LANG_country}</td>
		<td><input type=text name="b_country" value="{$b_countryField}" size=50 /></td>
	</tr>
	<tr>
		<td class="details_screen">{$LANG_mobile_phone}</td>
		<td><input type=text name="b_mobile_phone" value="{$b_mobile_phoneField}" size=50 /></td>
	</tr>
	<tr>
		<td class="details_screen">{$LANG_phone}</td>
		<td><input type=text name="b_phone" value="{$b_phoneField}" size=50 /></td>
	</tr>
	<tr>
		<td class="details_screen">{$LANG_fax}</td>
		<td><input type=text name="b_fax" value="{$b_faxField}" size=50 /></td>
	</tr>
	<tr>
		<td class="details_screen">{$LANG_email}</td>
		<td><input type=text name="b_email" value="{$b_emailField}" size=50 /></td>
	</tr>
	<tr>
		<td class="details_screen">{$biller_custom_field_label1} <a href="./documentation/text/custom_fields.html" class="greybox">*</a></td>
		<td><input type=text name="b_custom_field1" value="{$b_custom_field1Field}" size=50 </td>
	</tr>
	<tr>
		<td class="details_screen">{$biller_custom_field_label2} <a href="./documentation/text/custom_fields.html" class="greybox">*</a></td>
		<td><input type=text name="b_custom_field2" value="{$b_custom_field2Field}" size=50 </td>
	</tr>
	<tr>
		<td class="details_screen">{$biller_custom_field_label3} <a href="./documentation/text/custom_fields.html" class="greybox">*</a></td>
		<td><input type=text name="b_custom_field3" value="{$b_custom_field3Field}" size=50 </td>
	</tr>
	<tr>
		<td class="details_screen">{$biller_custom_field_label4} <a href="./documentation/text/custom_fields.html" class="greybox">*</a></td>
		<td><input type=text name="b_custom_field4" value="{$b_custom_field4Field}" size=50 </td>
	</tr>
	<tr>
		<td class="details_screen">{$LANG_logo_file}
		<a href="documentation/text/insert_biller_text.html" class="greybox">{$LANG_note}</a></td>
		<td>{$display_block_logo_list}</td> 
	</tr>
	<tr>
		<td class="details_screen">{$LANG_invoice_footer}</td>
		<td><textarea name="b_co_footer" rows=4 cols=50>{$b_co_footerField}</textarea></td>
	</tr>
	<tr>		
		<td class="details_screen">{$LANG_notes}</td>
		<td><textarea name="b_notes" rows=8 cols=50>{$b_notesField}</textarea></td>
	</tr>
	<tr>
		<td class="details_screen">{$wording_for_enabledField}</td>
		<td>{$display_block_enabled}</td>
	</tr>
	</table>
</div>

EOD;

$footer = <<<EOD

<p><input type="submit" name="cancel" value="{$LANG_cancel}" />
<input type="submit" name="save_biller" value="{$LANG_save} {$LANG_biller}" />
<input type="hidden" name="op" value="edit_biller" /></p>

EOD;

}

?>
    <script type="text/javascript" src="./include/jquery.js"></script>
    <script type="text/javascript" src="./include/jquery.greybox.js"></script>
    <script type="text/javascript" src="./include/jquery.greybox.conf.js"></script>

    <link rel="stylesheet" type="text/css" href="themes/<?php echo $theme; ?>/tables.css" media="all"/>

<script type="text/javascript" src="niftycube.js"></script>
<script type="text/javascript">
window.onload=function(){
Nifty("div#container");
Nifty("div#content,div#nav","same-height small");
Nifty("div#header,div#footer","small");
}
</script>
<script language="javascript" type="text/javascript" src="include/tiny_mce/tiny_mce_src.js"></script>
<script language="javascript" type="text/javascript" src="include/tiny-mce.conf.js"></script>
</head>

<body>

<?php
$mid->printMenu('hormenu1');
$mid->printFooter();
echo <<<EOD

<br>
<form name="frmpost" action="insert_action.php?submit={$_GET['submit']}"
 method="post" onsubmit="return frmpost_Validator(this)">
<div id="container">
{$display_block}
<div id="footer">
{$footer}

EOD;
?>
</div>
</div>
</form>
</body>
</html>
