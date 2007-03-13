<?php
include_once('./include/include_main.php');

//stop the direct browsing to this file - let index.php handle which files get displayed
if (!defined("BROWSE")) {
   echo "You Cannot Access This Script Directly, Have a Nice Day.";
   exit();
}


/* validataion code */
include("./include/validation.php");

jsBegin();
jsFormValidationBegin("frmpost");
jsValidateRequired("c_name",$LANG_customer_name);
jsFormValidationEnd();
jsEnd();

/* end validataion code */

#do the product enabled/disblaed drop down
$display_block_enabled = "<select name=\"c_enabled\">
<option value=\"1\" selected>$wording_for_enabledField</option>
<option value=\"0\">$wording_for_disabledField</option>
</select>";

#get custom field labels
$customer_custom_field_label1 = get_custom_field_label("customer_cf1");
$customer_custom_field_label2 = get_custom_field_label("customer_cf2");
$customer_custom_field_label3 = get_custom_field_label("customer_cf3");
$customer_custom_field_label4 = get_custom_field_label("customer_cf4");


echo <<<EOD

</head>
<BODY>

<FORM name="frmpost" ACTION="index.php?module=customers&view=save" METHOD=POST onsubmit="return frmpost_Validator(this)">
<div id="top"><b>{$LANG_customer_add}</b> </div>
 <hr></hr>
<table align=center>
<tr>
	<td class="details_screen">{$LANG_customer_name}</td><td><input type=text name="c_name" size=25></td>
</tr>
</tr>
	<td class="details_screen">{$LANG_customer_contact} <a href="./documentation/info_pages/customer_contact.html" rel="ibox&height=400"><img src="./images/common/help-small.png"></img></a></td><td><input type=text name="c_attention" size=25></td>
</tr>
<tr>
	<td class="details_screen">{$LANG_street}</td><td><input type=text name="c_street_address" size=25></td>
</tr>
<tr>
	<td class="details_screen">{$LANG_street2} <a href="./documentation/info_pages/street2.html" rel="ibox&height=400"><img src="./images/common/help-small.png"></img></a></td><td><input type=text name="c_street_address2" size=25></td>
</tr>
<tr>
	<td class="details_screen">{$LANG_city}</td><td><input type=text name="c_city" size=25></td>
</tr>
<tr>
	<td class="details_screen">{$LANG_state}</td><td><input type=text name="c_state" size=25></td>
</tr>
<tr>
	<td class="details_screen">{$LANG_zip}</td><td><input type=text name="c_zip_code" size=25></td>
</tr>
<tr>
	<td class="details_screen">{$LANG_country} ({$LANG_optional})</td><td><input type=text name="c_country" size=50></td>
</tr>
<tr>
	<td class="details_screen">{$LANG_phone}</td><td><input type=text name="c_phone" size=25></td>
</tr>
<tr>
	<td class="details_screen">{$LANG_mobile_phone}</td><td><input type=text name="c_mobile_phone" size=25></td>
</tr>
<tr>
	<td class="details_screen">{$LANG_fax}</td><td><input type=text name="c_fax" size=25></td>
</tr>
<tr>
	<td class="details_screen">{$LANG_email}</td><td><input type=text name="c_email" size=25></td>
</tr>
<tr>
	<td class="details_screen">{$customer_custom_field_label1} <a href="./documentation/info_pages/custom_fields.html" rel="ibox&height=400"><img src="./images/common/help-small.png"></img></a></td><td><input type=text name="c_custom_field1" size=25></td>
</tr>
<tr>
	<td class="details_screen">{$customer_custom_field_label2} <a href="./documentation/info_pages/custom_fields.html" rel="ibox&height=400"><img src="./images/common/help-small.png"></img></a></td><td><input type=text name="c_custom_field2" size=25></td>
</tr>
<tr>
	<td class="details_screen">{$customer_custom_field_label3} <a href="./documentation/info_pages/custom_fields.html" rel="ibox&height=400"><img src="./images/common/help-small.png"></img></a></td><td><input type=text name="c_custom_field3" size=25></td>
</tr>
<tr>
	<td class="details_screen">{$customer_custom_field_label4} <a href="./documentation/info_pages/custom_fields.html" rel="ibox&height=400"><img src="./images/common/help-small.png"></img></a></td><td><input type=text name="c_custom_field4" size=25></td>
</tr>
<tr>    
	<td class="details_screen">{$LANG_notes}</td><td><textarea name='c_notes' rows=8 cols=50></textarea></td>
</tr>
<tr>
<td class="details_screen">{$wording_for_enabledField}</td><td>{$display_block_enabled}</td>
</tr>

</table>
<hr></hr>
	<input type=submit name="submit" value="{$LANG_insert_customer}">
	<input type=hidden name="op" value="insert_customer">

EOD;

?>

<!-- ./src/include/design/footer.inc.php gets called here by controller srcipt -->
