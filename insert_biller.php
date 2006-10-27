<?php
include('./include/include_main.php');
/* validataion code */
include("./include/validation.php");

jsBegin();
jsFormValidationBegin("frmpost");
jsValidateRequired("b_name","Biller name");
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

foreach ( $files as $var )
{
$display_block_logo_list .= "<option>";
$display_block_logo_list .= $var;
$display_block_logo_list .= "</option>";
}
$display_block_logo_list .= "</select>";

/*end logo stuff */

#do the product enabled/disblaed drop down
$display_block_enabled = "<select name=\"b_enabled\">
<option value=\"1\" selected>$wording_for_enabledField</option>
<option value=\"0\">$wording_for_disabledField</option>
</select>";







?>

<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />

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

</head>
<title>Simple Invoices - Add biller
</title>
<?php include('./config/config.php'); ?>

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
		<td colspan=2 align=center><B>Biller to add</b></th>
	</tr>
</table>

</div id="header">
<div id="subheader">

<table align=center>
	<tr>	
		<td>Biller Name</td><td><input type=text name="b_name" size=25></td>
	</tr>
	<tr>
		<td>Address: Street</td><td><input type=text name="b_street_address" size=25></td>
	</tr>
	<tr>
		<td>Address: City</td><td><input type=text name="b_city" size=25></td>
	</tr>
	<tr>
		<td>Address: State</td><td><input type=text name="b_state" size=25></td>
	</tr>
	<tr>
		<td>Address: Zip</td><td><input type=text name="b_zip_code" size=25></td>
	</tr>
	<tr>
		<td>Address: Country (optional)</td><td><input type=text name="b_country" size=75></td>
	</tr>
	<tr>
		<td>Phone</td><td><input type=text name="b_phone" size=25></td>
	</tr>
	<tr>
		<td>Mobile Phone</td><td><input type=text name="b_mobile_phone" size=25></td>
	</tr>
	<tr>
		<td>Fax</td><td><input type=text name="b_fax" size=25></td>
	</tr>
	<tr>
		<td>Email</td><td><input type=text name="b_email" size=25></td>
	</tr>
	<tr>
		<td>Logo file <a href="./documentation/text/insert_biller_text.html" class="greybox">Note</a></td><td><?php echo $display_block_logo_list;?></td>
	</tr>
	<tr>
		<td>Invoice footer</td><td><textarea input type=text name="b_co_footer"  rows=4 cols=50></textarea></td>
	</tr>
	<tr>
                <td><?php echo $lang_notes; ?></td><td><textarea input type=text name='b_notes' rows=8 cols=50></textarea></td>
	</tr>

	<tr>
		<td><?php echo $wording_for_enabledField; ?></td><td><?php echo $display_block_enabled;?></td>
	</tr>

</table>


</div>
<div id="footer">
		<input type=submit name="submit" value="Insert Biller"><input type=hidden name="op" value="insert_biller">
</div>


</FORM>
</BODY>
</HTML>







