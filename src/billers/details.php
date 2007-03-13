<?php
include_once('./include/include_main.php');

//stop the direct browsing to this file - let index.php handle which files get displayed
if (!defined("BROWSE")) {
   echo "You Cannot Access This Script Directly, Have a Nice Day.";
   exit();
}


include("./html/header.html");

#table
include("./include/validation.php");

/*validation code*/
jsBegin();
jsFormValidationBegin("frmpost");
jsValidateRequired("b_name",$LANG_biller_name);
jsFormValidationEnd();
jsEnd();
/*end validation code*/


#get the invoice id
$biller_id = $_GET['submit'];


#Info from DB print
$conn = mysql_connect("$db_host","$db_user","$db_password");
mysql_select_db("$db_name",$conn);


#biller query
$print_biller = "SELECT * FROM {$tb_prefix}biller WHERE b_id = $biller_id";
$result_print_biller = mysql_query($print_biller, $conn) or die(mysql_error());


$biller = mysql_fetch_array($result_print_biller);
$biller['wording_for_enabled'] = $biller['b_enabled']==1?$wording_for_enabledField:$wording_for_disabledField;

/*while ($Array = mysql_fetch_array($result_print_biller) ) {

		if ($biller['b_enabled'] == 1) {
			$wording_for_enabled = $wording_for_enabledField;
		} else {
			$wording_for_enabled = $wording_for_disabledField;
		}

};*/

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

$display_block_logo_list = <<<EOD
<select name="b_co_logo">
	<option selected value="$biller[b_co_logo]" style="font-weight:bold;">$biller[b_co_logo]</option>
EOD;

foreach ( $files as $var )
{
	$display_block_logo_list .= "<option>{$var}</option>";
}
$display_block_logo_list .= "</select>";

/*end logo stuff */

#get custom field labels
$biller_custom_field_label1 = get_custom_field_label("biller_cf1");
$biller_custom_field_label2 = get_custom_field_label("biller_cf2");
$biller_custom_field_label3 = get_custom_field_label("biller_cf3");
$biller_custom_field_label4 = get_custom_field_label("biller_cf4");


if ($_GET['action'] == "view") {

	$display_block = <<<EOD
	<b>{$LANG_biller} :: <a href="index.php?module=billers&view=details&submit=$biller[b_id]&action=edit">{$LANG_edit}</a></b>
 <hr></hr>

	<table align="center">
	<tr>
		<td class="details_screen">{$LANG_biller_id}</td><td>$biller[b_id]</td>
	</tr>
	<tr>
		<td class="details_screen">{$LANG_biller_name}</td><td>$biller[b_name]</td>
	</tr>
	<tr>
		<td class="details_screen">{$LANG_street}</td><td>$biller[b_street_address]</td>
	</tr>
	<tr>
		<td class="details_screen">{$LANG_street2} <a href="./documentation/info_pages/street2.html" rel="ibox&height=400"><img src="./images/common/help-small.png"></img></a></td><td>$biller[b_street_address2]</td>
	</tr>
	<tr>
		<td class="details_screen">{$LANG_city}</td><td>$biller[b_city]</td>
	</tr>
	<tr>
		<td class="details_screen">{$LANG_zip}</td><td>$biller[b_zip_code]</td>
	</tr>
	<tr>
		<td class="details_screen">{$LANG_state}</td><td>$biller[b_state]</td>
	</tr>
	<tr>
		<td class="details_screen">{$LANG_country}</td><td>$biller[b_country]</td>
	</tr>
	<tr>
		<td class="details_screen">{$LANG_mobile_phone}</td><td>$biller[b_mobile_phone]</td>
	</tr>
	<tr>
		<td class="details_screen">{$LANG_phone}</td><td>$biller[b_phone]</td>
	</tr>
	<tr>
		<td class="details_screen">{$LANG_fax}</td><td>$biller[b_fax]</td>
	</tr>	
	<tr>
		<td class="details_screen">{$LANG_email}</td><td>$biller[b_email]</td>
	</tr>	
	<tr>
		<td class="details_screen">{$biller_custom_field_label1} <a href="./documentation/info_pages/custom_fields.html" rel="ibox&height=400"><img src="./images/common/help-small.png"></img></a></td>
		<td>$biller[b_custom_field1]</td>
	</tr>
	<tr>
		<td class="details_screen">{$biller_custom_field_label2} <a href="./documentation/info_pages/custom_fields.html" rel="ibox&height=400"><img src="./images/common/help-small.png"></img></a></td>
		<td>$biller[b_custom_field2]</td>
	</tr>
	<tr>
		<td class="details_screen">{$biller_custom_field_label3} <a href="./documentation/info_pages/custom_fields.html" rel="ibox&height=400"><img src="./images/common/help-small.png"></img></a></td>
		<td>$biller[b_custom_field3]</td>
	</tr>
	<tr>
		<td class="details_screen">{$biller_custom_field_label4} <a href="./documentation/info_pages/custom_fields.html" rel="ibox&height=400"><img src="./images/common/help-small.png"></img></a></td>
		<td>$biller[b_custom_field1]</td>
	</tr>
	<tr>
		<td class="details_screen">{$LANG_logo_file} <a href="documentation/info_pages/insert_biller_text.html" rel="ibox&height=400"><img src="./images/common/help-small.png"></img></a></td><td>$biller[b_co_logo]</td>
	</tr>	
	<tr>
		<td class="details_screen">{$LANG_invoice_footer}</td><td>$biller[b_co_footer]</td>
	</tr>
	<tr>
		<td class="details_screen">{$LANG_notes}</td><td>$biller[b_notes]</td>
	</tr>
	<tr>
		<td class="details_screen">{$wording_for_enabledField}</td><td>$biller[wording_for_enabled]</td>
	</tr>
	</table>

EOD;

$footer = <<<EOD
<hr></hr>
<a href="?submit=$biller[b_id]&action=edit">{$LANG_edit}</a>

EOD;

}

else if ($_GET['action'] == "edit") {

#do the product enabled/disblaed drop down
$display_block_enabled = <<<EOD
<select name="b_enabled">
<option value="$biller[b_enabled]" selected style="font-weight: bold;">$biller[wording_for_enabled]</option>
<option value="1">$wording_for_enabledField</option>
<option value="0">$wording_for_disabledField</option>
</select>
EOD;

$display_block = <<<EOD

	<b>{$LANG_biller_edit}</b>
 <hr></hr>
	<table align="center">
	<tr>
		<td class="details_screen">{$LANG_biller_id}</td><td>$biller[b_id]</td>
	</tr>
	<tr>
		<td class="details_screen">{$LANG_biller_name}</td>
		<td><input type=text name="b_name" value="$biller[b_name]" size=50 /></td>
	</tr>
	<tr>
		<td class="details_screen">{$LANG_street}</td>
		<td><input type=text name="b_street_address" value="$biller[b_street_address]" size=50 /></td>
	</tr>
	<tr>
		<td class="details_screen">{$LANG_street2} <a href="./documentation/info_pages/street2.html" rel="ibox&height=400"><img src="./images/common/help-small.png"></img></a></td>
		<td><input type=text name="b_street_address2" value="$biller[b_street_address2]" size=50 /></td>
	</tr>
	<tr>
		<td class="details_screen">{$LANG_city}</td>
		<td><input type=text name="b_city" value="$biller[b_city]" size=50 /></td>
	</tr>
	<tr>
		<td class="details_screen">{$LANG_zip}</td>
		<td><input type=text name="b_zip_code" value="$biller[b_zip_code]" size=50 /></td>
	</tr>
	<tr>
		<td class="details_screen">{$LANG_state}</td>
		<td><input type=text name="b_state" value="$biller[b_state]" size=50 /></td>
	</tr>
	<tr>
		<td class="details_screen">{$LANG_country}</td>
		<td><input type=text name="b_country" value="$biller[b_coutry]" size=50 /></td>
	</tr>
	<tr>
		<td class="details_screen">{$LANG_mobile_phone}</td>
		<td><input type=text name="b_mobile_phone" value="$biller[b_mobile_phone]" size=50 /></td>
	</tr>
	<tr>
		<td class="details_screen">{$LANG_phone}</td>
		<td><input type=text name="b_phone" value="$biller[b_phone]" size=50 /></td>
	</tr>
	<tr>
		<td class="details_screen">{$LANG_fax}</td>
		<td><input type=text name="b_fax" value="$biller[b_fax]" size=50 /></td>
	</tr>
	<tr>
		<td class="details_screen">{$LANG_email}</td>
		<td><input type=text name="b_email" value="$biller[b_email]" size=50 /></td>
	</tr>
	<tr>
		<td class="details_screen">{$biller_custom_field_label1} <a href="./documentation/info_pages/custom_fields.html" rel="ibox&height=400"><img src="./images/common/help-small.png"></img></a></td>
		<td><input type=text name="b_custom_field1" value="$biller[b_custom_field1]" size=50 </td>
	</tr>
	<tr>
		<td class="details_screen">{$biller_custom_field_label2} <a href="./documentation/info_pages/custom_fields.html" rel="ibox&height=400"><img src="./images/common/help-small.png"></img></a></td>
		<td><input type=text name="b_custom_field2" value="$biller[b_custom_field2]" size=50 </td>
	</tr>
	<tr>
		<td class="details_screen">{$biller_custom_field_label3} <a href="./documentation/info_pages/custom_fields.html" rel="ibox&height=400"><img src="./images/common/help-small.png"></img></a></td>
		<td><input type=text name="b_custom_field3" value="$biller[b_custom_field3]" size=50 </td>
	</tr>
	<tr>
		<td class="details_screen">{$biller_custom_field_label4} <a href="./documentation/info_pages/custom_fields.html" rel="ibox&height=400"><img src="./images/common/help-small.png"></img></a></td>
		<td><input type=text name="b_custom_field4" value="$biller[b_custom_field4]" size=50 </td>
	</tr>
	<tr>
		<td class="details_screen">{$LANG_logo_file}
		<a href="documentation/info_pages/insert_biller_text.html" rel="ibox&height=400"><img src="./images/common/help-small.png"></img></a></td>
		<td>{$display_block_logo_list}</td> 
	</tr>
	<tr>
		<td class="details_screen">{$LANG_invoice_footer}</td>
		<td><textarea name="b_co_footer" rows=4 cols=50>$biller[b_co_footer]</textarea></td>
	</tr>
	<tr>		
		<td class="details_screen">{$LANG_notes}</td>
		<td><textarea name="b_notes" rows=8 cols=50>$biller[b_notes]</textarea></td>
	</tr>
	<tr>
		<td class="details_screen">{$wording_for_enabledField}</td>
		<td>{$display_block_enabled}</td>
	</tr>
	</table>

EOD;

$footer = <<<EOD
<hr></hr>
<input type="submit" name="cancel" value="{$LANG_cancel}" />
<input type="submit" name="save_biller" value="{$LANG_save_biller}" />
<input type="hidden" name="op" value="edit_biller" />

EOD;
}

?>
    <script type="text/javascript" src="./include/jquery.js"></script>

<script language="javascript" type="text/javascript" src="include/tiny_mce/tiny_mce_src.js"></script>
<script language="javascript" type="text/javascript" src="include/tiny-mce.conf.js"></script>
</head>

<body>

<?php

echo <<<EOD

<form name="frmpost" action="index.php?module=billers&view=save&submit={$_GET['submit']}" method="post" onsubmit="return frmpost_Validator(this)">
{$display_block}
{$footer}

EOD;
?>
</form>
<!-- ./src/include/design/footer.inc.php gets called here by controller srcipt -->
