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

EOD;
jsBegin();
jsFormValidationBegin("frmpost");
jsValidateRequired("b_name",$LANG_biller_name);
jsFormValidationEnd();
jsEnd();

/* end validataion code */

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
<script type="text/javascript" src="./include/greybox.js"></script>
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
</head>

<BODY>

<?php
$mid->printMenu('hormenu1');
$mid->printFooter();
echo <<<EOD

<br>

<FORM name="frmpost" ACTION="insert_action.php" METHOD=POST onsubmit="return frmpost_Validator(this)">
<div id="container">

<div id="header">
<table align=center>
	<tr>
		<th colspan="2" align="center"><b>&nbsp;{$LANG_biller_to_add}&nbsp;</b></th>
	</tr>
</table>

</div>
<div id="subheader">

<table align="center">
	<tr>
		<td>{$LANG_biller_name}</td><td><input type=text name="b_name" size=25></td>
	</tr>
	<tr>
		<td>{$LANG_address_street}</td><td><input type=text name="b_street_address" size=25></td>
	</tr>
	<tr>
		<td>{$LANG_address_street}2-CHANGE</td><td><input type=text name="b_street_address2" size=25></td>
	</tr>
	<tr>
		<td>{$LANG_address_city}</td><td><input type=text name="b_city" size=25></td>
	</tr>
	<tr>
		<td>{$LANG_address_state}</td><td><input type=text name="b_state" size=25></td>
	</tr>
	<tr>
		<td>{$LANG_address_zip}</td><td><input type=text name="b_zip_code" size=25></td>
	</tr>
	<tr>
		<td>{$LANG_address_country} ({$LANG_optional})</td><td><input type=text name="b_country" size=75></td>
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
		<td>Custom field 1 - CHANGE</td><td><input type=text name="b_custom_field1" size=25></td>
	</tr>
	<tr>
		<td>Custom field 2 - CHANGE</td><td><input type=text name="b_custom_field2" size=25></td>
	</tr>
	<tr>
		<td>Custom field 3 - CHANGE</td><td><input type=text name="b_custom_field3" size=25></td>
	</tr>
	<tr>
		<td>Custom field 4 - CHANGE</td><td><input type=text name="b_custom_field4" size=25></td>
	</tr>
	<tr>
		<td>{$LANG_logo_file} <a href="./documentation/text/insert_biller_text.html" class="greybox">{$lang_note}</a></td><td>{$display_block_logo_list}</td>
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
	<input type="hidden" name="op" value="insert_biller" />
</div>

EOD;
?>

</FORM>
</BODY>
</HTML>
