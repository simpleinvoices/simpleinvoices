<?php
include('./include/include_main.php');

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

<script language="javascript" type="text/javascript" src="include/tiny_mce/tiny_mce_src.js"></script>
<script language="javascript" type="text/javascript">
tinyMCE.init({
mode : "textareas",
        theme : "advanced",
        theme_advanced_buttons1 : "bold,italic,underline,separator,strikethrough,justifyleft,justifycenter,justifyright, justifyfull,bullist,numlist,undo,redo",
        theme_advanced_buttons2 : "",
        theme_advanced_buttons3 : "",
        theme_advanced_toolbar_location : "top",
        theme_advanced_toolbar_align : "left",
        extended_valid_elements : "a[name|href|target|title|onclick],img[class|src|border=0|alt|title|hspace|vspace|width|height|align|onmouseover|onmouseout|name],hr[class|width|size|noshade],font[face|size|color|style],span[class|align|style]"
});
</script>


<title><?php echo $title; echo " :: "; echo $lang_customer_add; ?></title>


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
<div id="header"><b><?php echo $lang_customer_add; ?></b> </div>

<table align=center>
<tr>
	<td><?php echo $lang_customer_name; ?></td><td><input type=text name="c_name" size=25></td>
</tr>
</tr>
	<td><?php echo $lang_customer_contact; ?></th><td><input type=text name="c_attention" size=25></td>
</tr>
<tr>
	<td><?php echo $lang_address; echo ": "; echo $lang_street;?></td><td><input type=text name="c_street_address" size=25></td>
</tr>
<tr>
	<td><?php echo $lang_address; echo ": "; echo $lang_city;?></td><td><input type=text name="c_city" size=25></td>
</tr>
<tr>
	<td><?php echo $lang_address; echo ": "; echo $lang_state;?></td><td><input type=text name="c_state" size=25></td>
</tr>
<tr>
	<td><?php echo $lang_address; echo ": "; echo $lang_zip;?></td><td><input type=text name="c_zip_code" size=25></td>
</tr>
<tr>
	<td><?php echo $lang_address; echo ": "; echo $lang_country; echo "("; echo $lang_optional; echo ")";  ?></td><td><input type=text name="c_country" size=75></td>
</tr>
<tr>
	<td><?php echo $lang_phone; ?></td><td><input type=text name="c_phone" size=25></td>
</tr>
<tr>
	<td><?php echo $lang_fax; ?></td><td><input type=text name="c_fax" size=25></td>
</tr>
<tr>
	<td><?php echo $lang_email; ?></td><td><input type=text name="c_email" size=25></td>
</tr>
<tr>    
                <td><?php echo $lang_notes; ?></td><td><textarea input type=text name='c_notes' rows=8 cols=50></textarea></td>
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







