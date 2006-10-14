<?php
#table
include('./config/config.php'); 
include("./include/validation.php");
include("./lang/$language.inc.php");

/*validation code*/
jsBegin();
jsFormValidationBegin("frmpost");
jsValidateRequired("b_name","Biller name");
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
                $b_cityField = $Array['b_city'];
                $b_stateField = $Array['b_state'];
                $b_zip_codeField = $Array['b_zip_code'];
                $b_countryField = $Array['b_country'];
		$b_phoneField = $Array['b_phone'];
		$b_faxField = $Array['b_fax'];
		$b_emailField = $Array['b_email'];
		$b_co_logoField = $Array['b_co_logo'];
		$b_co_footerField = $Array['b_co_footer'];
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
$display_block_logo_list .= "<option>";
$display_block_logo_list .= $var;
$display_block_logo_list .= "</option>";
}
$display_block_logo_list .= "</select>";

/*end logo stuff */









if ($_GET[action] == "view") {

$display_block =  "
	
	<table align=center>
	<tr>
		<td colspan=2 align=center><i>Biller</i></td>
	</tr>	
	<tr>
		<td class='details_screen'>Biller ID </td><td>$b_idField</td>
	</tr>
	<tr>
		<td class='details_screen'>Biller name </td><td>$b_nameField</td>
	</tr>
	<tr>
		<td class='details_screen'>Street address</td><td>$b_street_addressField</td>
	</tr>
	<tr>
		<td class='details_screen'>City</td><td>$b_cityField</td>
	</tr>
	<tr>
		<td class='details_screen'> Zip code</td><td>$b_zip_codeField
	</tr>
	<tr>
		<td class='details_screen'>State</td><td>$b_stateField</td>
	</tr>
	<tr>
		<td class='details_screen'>Country</td><td>$b_countryField</td>
	</tr>
	<tr>
		<td class='details_screen'>Mobile phone </td><td>$b_mobile_phoneField</td>
	</tr>
	<tr>
		<td class='details_screen'>Phone </td><td>$b_phoneField</td>
	</tr>
	<tr>
		<td class='details_screen'>Fax</td><td>$b_faxField</td>
	</tr>	
	<tr>
		<td class='details_screen'>Email</td><td>$b_emailField</td>
	</tr>	
	<tr>
		<td class='details_screen'>Logo file</td><td>$b_co_logoField</td>
	</tr>	
	<tr>
		<td class='details_screen'>Invoice Footer</td><td>$b_co_footerField</td>
	</tr>
        <tr>
                <td class='details_screen'>$wording_for_enabledField</td><td>$wording_for_enabled</td>
        </tr>
        </table>


";

$footer =  "

<div id='footer'><a href='?submit=$b_idField&action=edit'>Edit</a></div>
";




}

else if ($_GET[action] == "edit") {

#do the product enabled/disblaed drop down
$display_block_enabled = "<select name=\"b_enabled\">
<option value=\"$b_enabledField\" selected style=\"font-weight: bold\">$wording_for_enabled</option>
<option value=\"1\">$wording_for_enabledField</option>
<option value=\"0\">$wording_for_disabledField</option>
</select>";



$display_block =  "
        <table align=center>
        <tr>
                <td colspan=2 align=center><i>Biller</i></td>
        </tr>
        <tr>
                <td class='details_screen'>Biller ID </td><td>$b_idField</td>
        </tr>
        <tr>
                <td class='details_screen'>Biller name </td><td><input type=text name='b_name' value='$b_nameField' size=50>    </td>
        </tr>
        <tr>
                <td class='details_screen'>Street address</td><td><input type=text name='b_street_address' value='$b_street_addressField' size=50>  </td>
        </tr>
        <tr>
                <td class='details_screen'>City</td><td><input type=text name='b_city' value='$b_cityField' size=50>  </td>
        </tr>
        <tr>
                <td class='details_screen'>Zip code</td><td><input type=text name='b_zip_code' value='$b_zip_codeField' size=50>  </td>
        </tr>
        <tr>
                <td class='details_screen'>State</td><td><input type=text name='b_state' value='$b_stateField' size=50>  </td>
        </tr>
        <tr>
                <td class='details_screen'>Country</td><td><input type=text name='b_country' value='$b_countryField' size=50>  </td>
        </tr>
        <tr>
                <td class='details_screen'>Mobile phone </td><td><input type=text name='b_mobile_phone' value='$b_mobile_phoneField' size=50>  </td>
        </tr>
        <tr>
        	<td class='details_screen'>Phone</td><td><input type=text name='b_phone' value='$b_phoneField' size=50>  </td>
        </tr>
        <tr>
        	<td class='details_screen'>Fax</td><td><input type=text name='b_fax' value='$b_faxField' size=50>  </td>
        </tr>
        <tr>
                <td class='details_screen'>Email</td><td><input type=text name='b_email' value='$b_emailField' size=50>  </td>
        </tr>
        <tr>
	        <td class='details_screen'>Logo file <a href='text/insert_biller_text.html' class='greybox'>Note</a></td><td>$display_block_logo_list</td> 
        </tr>
        <tr>
                <td class='details_screen'>Invoice footer</td><td><textarea input type=text name='b_co_footer' rows=4 cols=50>$b_co_footerField</textarea></td>
        </tr>
        <tr>
                <td class='details_screen'>$wording_for_enabledField </td><td>$display_block_enabled</td>
        </tr>
	</table>
</div>


";

$footer =  "

<p><input type=submit name='action' value='Cancel'>
<input type=submit name='action' value='Save Biller'>
<input type=hidden name='op' value='edit_biller'></p>
";


}



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

<title>Simple Invoices - Biller details
</title>
<?php include('./config/config.php'); ?>
</head>
<body>
<?php
$mid->printMenu('hormenu1');
$mid->printFooter();
?>

<link rel="stylesheet" type="text/css" href="themes/<?php echo $theme; ?>/tables.css">
<br>
<FORM name="frmpost" ACTION="insert_action.php?submit=<?php echo $_GET[submit];?>" METHOD=POST  onsubmit="return frmpost_Validator(this)">
<div id="container">
<div id="header"></div>

<?php echo $display_block; ?>
<div id="footer">
<?php echo $footer; ?>
</div>
</body>
</html>



