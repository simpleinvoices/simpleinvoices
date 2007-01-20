<?php
include_once('./include/include_main.php');
?>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<?php
/* validataion code */
include("./include/validation.php");
echo <<<EOD
<link rel="stylesheet" type="text/css" href="header1.css" media="all"/>
<link rel="stylesheet" type="text/css" href="header2.css" media="all"/>
<link rel="stylesheet" type="text/css" href="screen.css" media="all"/>
<link rel="stylesheet" type="text/css" href="print.css" media="print"/>
<link rel="stylesheet" type="text/css" href="blue.css" media="screen"/>
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
$biller_custom_field_label1 = get_custom_field_label(biller_cf1);
$biller_custom_field_label2 = get_custom_field_label(biller_cf2);
$biller_custom_field_label3 = get_custom_field_label(biller_cf3);
$biller_custom_field_label4 = get_custom_field_label(biller_cf4);

?>
<script language="javascript" type="text/javascript" src="include/tiny_mce/tiny_mce_src.js"></script>
<script language="javascript" type="text/javascript" src="include/tiny-mce.conf.js"></script>
<script type="text/javascript" src="./include/jquery.js"></script>
<script type="text/javascript" src="./include/jquery.thickbox.js"></script>
<!--[if gte IE 5.5]>
<script language="JavaScript" src="dhtml.js" type="text/JavaScript"></script>
<![endif]-->
</head>

<BODY>

<?php


echo <<<EOD
<FORM name="frmpost" ACTION="index.php?module=billers&view=save" METHOD=POST onsubmit="return frmpost_Validator(this)">
<b>{$LANG_biller_to_add}</b>
    <hr></hr>
      <div id="browser">
<table align="center" >
	<tr>
		<td class="details_screen">{$LANG_biller_name}</td><td><input type=text name="b_name" size=25></td>
	</tr>
	<tr>
		<td class="details_screen">{$LANG_street}</td><td><input type=text name="b_street_address" size=25></td>
	</tr>
	<tr>
		<td class="details_screen">{$LANG_street2} <a href="./documentation/info_pages/street2.html?keepThis=true&TB_iframe=true&height=300&width=500" title="Info :: Street address 2" class="thickbox"><img src="./images/common/help-small.png"></img></a></td><td><input type=text name="b_street_address2" size=25></td>
	</tr>
	<tr>
		<td class="details_screen">{$LANG_city}</td><td><input type=text name="b_city" size=25></td>
	</tr>
	<tr>
		<td class="details_screen">{$LANG_state}</td><td><input type=text name="b_state" size=25></td>
	</tr>
	<tr>
		<td class="details_screen">{$LANG_zip}</td><td><input type=text name="b_zip_code" size=25></td>
	</tr>
	<tr>
		<td class="details_screen">{$LANG_country} ({$LANG_optional})</td><td><input type=text name="b_country" size=50></td>
	</tr>
	<tr>
		<td class="details_screen">{$LANG_phone}</td><td><input type=text name="b_phone" size=25></td>
	</tr>
	<tr>
		<td class="details_screen">{$LANG_mobile_phone}</td><td><input type=text name="b_mobile_phone" size=25></td>
	</tr>
	<tr>
		<td class="details_screen">{$LANG_fax}</td><td><input type=text name="b_fax" size=25></td>
	</tr>
	<tr>
		<td class="details_screen">{$LANG_email}</td><td><input type=text name="b_email" size=25></td>
	</tr>
	<tr>
		<td class="details_screen">{$biller_custom_field_label1} <a href="./documentation/info_pages/custom_fields.html?keepThis=true&TB_iframe=true&height=300&width=500" title="Info :: Custom fields" class="thickbox"><img src="./images/common/help-small.png"></img></a></td><td><input type=text name="b_custom_field1" size=25></td>
	</tr>
	<tr>
		<td class="details_screen">{$biller_custom_field_label2} <a href="./documentation/info_pages/custom_fields.html?keepThis=true&TB_iframe=true&height=300&width=500" title="Info :: Custom fields" class="thickbox"><img src="./images/common/help-small.png"></img></a></td><td><input type=text name="b_custom_field2" size=25></td>
	</tr>
	<tr>
		<td class="details_screen">{$biller_custom_field_label3} <a href="./documentation/info_pages/custom_fields.html?keepThis=true&TB_iframe=true&height=300&width=500" title="Info :: Custom fields" class="thickbox"><img src="./images/common/help-small.png"></img></a></td><td><input type=text name="b_custom_field3" size=25></td>
	</tr>
	<tr>
		<td class="details_screen">{$biller_custom_field_label4} <a href="./documentation/info_pages/custom_fields.html?keepThis=true&TB_iframe=true&height=300&width=500" title="Info :: Custom fields" class="thickbox"><img src="./images/common/help-small.png"></img></a></td><td><input type=text name="b_custom_field4" size=25></td>
	</tr>
	<tr>
		<td class="details_screen">{$LANG_logo_file} <a href="./documentation/info_pages/insert_biller_text.html?keepThis=true&TB_iframe=true&height=300&width=500" title="Info :: Custom fields" class="thickbox"><img src="./images/common/help-small.png"></img></a></td><td>{$display_block_logo_list}</td>
	</tr>
	<tr>
		<td class="details_screen">{$LANG_invoice_footer}</td><td><textarea input type=text name="b_co_footer" rows=4 cols=50></textarea></td>
	</tr>
	<tr>
		<td class="details_screen">{$LANG_notes}</td><td><textarea input type=text name="b_notes" rows=8 cols=50></textarea></td>
	</tr>

	<tr>
		<td class="details_screen">{$wording_for_enabledField}</td><td>{$display_block_enabled}</td>
	</tr>


	</div>
	</div>

        </div>
	</tbody>
</table>
<hr></hr>
	<input type="submit" name="submit" value="{$LANG_insert_biller}" />
	<input type="hidden" name="op" value="insert_biller" />
EOD;
include("footer.inc.php");
?>

</FORM>
</BODY>
</HTML>
