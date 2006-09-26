<?php
#table
include('./config/config.php'); 
include("./lang/$language.inc.php");
include('./include/validation.php');

jsBegin();
jsFormValidationBegin("frmpost");
jsValidateRequired("pref_description","Description");
jsFormValidationEnd();
jsEnd();



#get the invoice id
$preference_id = $_GET['submit'];


#Info from DB print
$conn = mysql_connect("$db_host","$db_user","$db_password");
mysql_select_db("$db_name",$conn);

$print_preferences = "SELECT * FROM si_preferences where pref_id = $preference_id";
$result_print_preferences  = mysql_query($print_preferences, $conn) or die(mysql_error());

while ($Array_preferences = mysql_fetch_array($result_print_preferences)) {
                        $pref_idField = $Array_preferences['pref_id'];
                        $pref_descriptionField = $Array_preferences['pref_description'];
                        $pref_currency_signField = $Array_preferences['pref_currency_sign'];
                        $pref_inv_headingField = $Array_preferences['pref_inv_heading'];
                        $pref_inv_wordingField = $Array_preferences['pref_inv_wording'];
                        $pref_inv_detail_headingField = $Array_preferences['pref_inv_detail_heading'];
                        $pref_inv_detail_lineField = $Array_preferences['pref_inv_detail_line'];
                        $pref_inv_payment_methodField = $Array_preferences['pref_inv_payment_method'];
                        $pref_inv_payment_line1_nameField = $Array_preferences['pref_inv_payment_line1_name'];
                        $pref_inv_payment_line1_valueField = $Array_preferences['pref_inv_payment_line1_value'];
                        $pref_inv_payment_line2_nameField = $Array_preferences['pref_inv_payment_line2_name'];
                        $pref_inv_payment_line2_valueField = $Array_preferences['pref_inv_payment_line2_value'];
                        $pref_enabledField = $Array_preferences['pref_enabled'];

                        if ($pref_enabledField == 1) {
                                $wording_for_enabled = $wording_for_enabledField;
                        } else {
                                $wording_for_enabled = $wording_for_disabledField;
                        }

};

if (  $_GET['action'] === 'view' ) {

$display_block =  "
	
	<table align=center>
	<tr>
		<td colspan=2 align=center><i>Preference</i></td>
	</tr>	
		<tr>
  			<td class='details_screen'>Preference ID</td><td>$pref_idField</td>
                </tr>
		<tr>	
			<td class='details_screen'>Description <a href=\"text/inv_pref_description.html\" class=\"greybox\">*</a></td><td>$pref_descriptionField</td>
                </tr>
                <tr>
			<td class='details_screen'>Currency sign <a href=\"text/inv_pref_currency_sign.html\" class=\"greybox\">*</a></td><td>$pref_currency_signField</td>
                </tr>
                <tr>
			<td class='details_screen'>Invoice heading <a href=\"text/inv_pref_invoice_heading.html\" class=\"greybox\">*</a></td><td>$pref_inv_headingField</td>
                </tr>
                <tr>
			<td class='details_screen'>Invoice wording <a href=\"text/inv_pref_invoice_wording.html\" class=\"greybox\">*</a></td><td>$pref_inv_wordingField</td>
                </tr>
                <tr>
			<td class='details_screen'>Invoice detail heading <a href=\"text/inv_pref_invoice_detail_heading.html\" class=\"greybox\">*</a></td><td>$pref_inv_detail_headingField</td>
                </tr>
                <tr>
			<td class='details_screen'>Invoice detail line <a href=\"text/inv_pref_invoice_detail_line.html\" class=\"greybox\">*</a></td><td>$pref_inv_detail_lineField</td>
                </tr>
                <tr>
			<td class='details_screen'>Invoice payment method <a href=\"text/inv_pref_invoice_payment_method.html\" class=\"greybox\">*</a></td><td>$pref_inv_payment_methodField</td>
                </tr>
                <tr>
			<td class='details_screen'>Invoice payment line1 name <a href=\"text/inv_pref_payment_line1_name.html\" class=\"greybox\">*</a></td><td>$pref_inv_payment_line1_nameField</td>
                </tr>
                <tr>
			<td class='details_screen'>Invoice payment line1 value <a href=\"text/inv_pref_payment_line1_value.html\" class=\"greybox\">*</a></td><td>$pref_inv_payment_line1_valueField</td>
                </tr>
                <tr>
			<td class='details_screen'>Invoice payment line2 name <a href=\"text/inv_pref_payment_line2_name.html\" class=\"greybox\">*</a></td><td>$pref_inv_payment_line2_nameField</td>
                </tr>
                <tr>
			<td class='details_screen'>Invoice payment line2 value <a href=\"text/inv_pref_payment_line2_value.html\" class=\"greybox\">*</a></td><td>$pref_inv_payment_line2_valueField</td>
		</tr>
	        <tr>
        	        <td class='details_screen'>$wording_for_enabledField <a href=\"text/inv_pref_invoice_enabled.html\" class=\"greybox\">*</a></td><td>$wording_for_enabled</td>
	        </tr>	
		<tr>
			<td colspan=2 align=center></td>
		</tr>
		<tr>
			<td colspan=2 align=center><a href=\"text/inv_pref_what_the.html\" class=\"greybox\">Whats all this \"Invoice Preference\" stuff about?</a></td>
		</tr>
		</table>
";

$footer =  "

<div id='footer'><a href='?submit=$pref_idField&action=edit'>Edit</a></div>
";

}


else if (  $_GET['action'] === 'edit' ) {

$display_block_enabled = "<select name=\"pref_enabled\">
<option value=\"$pref_enabledField\" selected style=\"font-weight: bold\">$wording_for_enabled</option>
<option value=\"1\">$wording_for_enabledField</option>
<option value=\"0\">$wording_for_disabledField</option>
</select>";

$display_block =  "
        <table align=center>
        <tr>
                <td colspan=2 align=center><i>Preference</i></td>
        </tr>
                <tr>
                        <td class='details_screen'>Preference ID</td><td>$pref_idField</td>
                </tr>
                <tr>
                        <td class='details_screen'>Description <a href=\"text/inv_pref_description.html\" class=\"greybox\">*</a></td><td><input type=text name='pref_description' value='$pref_descriptionField' size=50></td>
                </tr>
                <tr>
                        <td class='details_screen'>Currenc sign <a href=\"text/inv_pref_currency_sign.html\" class=\"greybox\">*</a></td><td><input type=text name='pref_currency_sign' value='$pref_currency_signField' size=50></td>
                </tr>
                <tr>
                        <td class='details_screen'>Invoice heading <a href=\"text/inv_pref_invoice_heading.html\" class=\"greybox\">*</a><td><input type=text name='pref_inv_heading' value='$pref_inv_headingField' size=50></td>
                </tr>
                <tr>
                        <td class='details_screen'>Invoice wording <a href=\"text/inv_pref_invoice_wording.html\" class=\"greybox\">*</a></td><td><input type=text name='pref_inv_wording' value='$pref_inv_wordingField' size=50></td>
                </tr>
                <tr>
                        <td class='details_screen'>Invoice detail heading <a href=\"text/inv_pref_invoice_detail_heading.html\" class=\"greybox\">*</a></td><td><input type=text name='pref_inv_detail_heading' value='$pref_inv_detail_headingField' size=50></td>
                </tr>
                <tr>
                        <td class='details_screen'>Invoice detail line <a href=\"text/inv_pref_invoice_detail_line.html\" class=\"greybox\">*</a></td><td><input type=text name='pref_inv_detail_line' value='$pref_inv_detail_lineField' size=75></td>
                </tr>
                <tr>
                        <td class='details_screen'>Invoice payment method <a href=\"text/inv_pref_invoice_payment_method.html\" class=\"greybox\">*</a></td><td><input type=text name='pref_inv_payment_method' value='$pref_inv_payment_methodField' size=50></td>
                </tr>
                <tr>
                        <td class='details_screen'>Invoice payment line1 name <a href=\"text/inv_pref_payment_line1_name.html\" class=\"greybox\">*</a></td><td><input type=text name='pref_inv_payment_line1_name' value='$pref_inv_payment_line1_nameField' size=50></td>
                </tr>
                <tr>
                        <td class='details_screen'>Invoice payment line1 value <a href=\"text/inv_pref_payment_line1_value.html\" class=\"greybox\">*</a></td><td><input type=text name='pref_inv_payment_line1_value' value='$pref_inv_payment_line1_valueField' size=50></td>
                </tr>
                <tr>
                        <td class='details_screen'>Invoice payment line2 name <a href=\"text/inv_pref_payment_line2_name.html\" class=\"greybox\">*</a></td><td><input type=text name='pref_inv_payment_line2_name' value='$pref_inv_payment_line2_nameField' size=50></td>
                </tr>
                <tr>
                        <td class='details_screen'>Invoice payment line2 value <a href=\"text/inv_pref_payment_line2_value.html\" class=\"greybox\">*</a></td><td><input type=text name='pref_inv_payment_line2_value' value='$pref_inv_payment_line2_valueField' size=50></td>
                </tr>
	        <tr>
        	        <td class='details_screen'>$wording_for_enabledField <a href=\"text/inv_pref_invoice_enabled.html\" class=\"greybox\">*</a></td><td>$display_block_enabled</td>
	        </tr>
                <tr>
                        <td colspan=2 align=center></td>
                </tr>
                <tr>
                        <td colspan=2 align=center><a href=\"text/inv_pref_what_the.html\" class=\"greybox\">Whats all this \"Invoice Preference\" stuff about?</a></td>
                </tr>

                </table>


";

$footer =  "

<p><input type=submit name='action' value='Cancel'>
<input type=submit name='action' value='Save Preference'> <input type=hidden name='op' value='edit_preference'></p>
";


}




?>
<html>
<head>

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
<title>Simple Invoices - Preference details
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
<form name="frmpost" action="insert_action.php?submit=<?php echo $_GET['submit'];?>" method="post" onsubmit="return frmpost_Validator(this)">
<div id="container">
<div id="header"></div>
<?php echo $display_block; ?>
<div id="footer">
<?php echo $footer; ?>
</div>
</div>
</body>
</html>




