<?php
include('./config/config.php'); 

include("./include/validation.php");

jsBegin();
jsFormValidationBegin("frmpost");
jsTextValidation("sel_id","Biller Name",1,100);
jsTextValidation("select_customer","Customer Name",1,100);
jsValidateifNum("inv_it_gross_total","Gross Total");
jsTextValidation("select_tax","Tax Rate",1,100);
jsPreferenceValidation("select_preferences","Invoice Preference",1,100);
jsFormValidationEnd();
jsEnd();




$conn = mysql_connect("$db_host","$db_user","$db_password");
mysql_select_db("$db_name",$conn);

#biller query
$sql = "SELECT * FROM si_biller where b_enabled != 0";
$result = mysql_query($sql, $conn) or die(mysql_error());

#customer
$sql_customer = "SELECT * FROM si_customers where c_enabled != 0";
$result_customer = mysql_query($sql_customer, $conn) or die(mysql_error());


#tax query
$sql_tax = "SELECT * FROM si_tax where tax_enabled != 0";
$result_tax = mysql_query($sql_tax, $conn) or die(mysql_error());

#invoice preference query
$sql_preferences = "SELECT * FROM si_preferences where pref_enabled != 0";
$result_preferences = mysql_query($sql_preferences, $conn) or die(mysql_error());

#DEFAULTS
#defaults query and DEFAULT NUMBER OF LINE ITEMS
$sql_defaults = "SELECT * FROM si_defaults";
$result_defaults = mysql_query($sql_defaults, $conn) or die(mysql_error());

while ($Array_defaults = mysql_fetch_array($result_defaults) ) {
                $def_idField = $Array_defaults['def_id'];
                $def_billerField = $Array_defaults['def_biller'];
                $def_customerField = $Array_defaults['def_customer'];
                $def_taxField = $Array_defaults['def_tax'];
                $def_inv_preferenceField = $Array_defaults['def_inv_preference'];
                $def_number_line_itemsField = $Array_defaults['def_number_line_items'];
};
#Get the names of the defaults from their id -start
#default biller name query
$sql_biller_default = "SELECT b_name FROM si_biller where b_id = $def_billerField";
$result_biller_default = mysql_query($sql_biller_default , $conn) or die(mysql_error());

while ($Array = mysql_fetch_array($result_biller_default) ) {
                $sql_biller_defaultField = $Array['b_name'];
}

#default customer name query
$print_customer = "SELECT * FROM si_customers WHERE c_id = $def_customerField";
$result_print_customer = mysql_query($print_customer, $conn) or die(mysql_error());

while ($Array_customer = mysql_fetch_array($result_print_customer)) {
       $c_nameField = $Array_customer['c_name'];
}

#default tax description query
$print_tax = "SELECT * FROM si_tax WHERE tax_id = $def_taxField";
$result_print_tax = mysql_query($print_tax, $conn) or die(mysql_error());

while ($Array_tax = mysql_fetch_array($result_print_tax)) {
       $tax_descriptionField = $Array_tax['tax_description'];
}

#default invoice preference description query
$print_inv_preference = "SELECT * FROM si_preferences WHERE pref_id = $def_inv_preferenceField";
$result_inv_preference = mysql_query($print_inv_preference, $conn) or die(mysql_error());

while ($Array_inv_preference = mysql_fetch_array($result_inv_preference)) {
       $pref_descriptionField = $Array_inv_preference['pref_description'];
}

#Get the names of the defaults from their id -end
#default biller name query

#DEFAULTS - END















#biller selector

if (mysql_num_rows($result) == 0) {
        //no records
        $display_block = "<p><em>Sorry, no biller available, please insert one</em></p>";

} else {
        //has records, so display them
        $display_block = "
        <select name=\"sel_id\">
        <option selected value=\"$def_billerField\" style=\"font-weight: bold\">$sql_biller_defaultField</option>
        <option value=\"\"></option>";

        while ($recs = mysql_fetch_array($result)) {
                $id = $recs['b_id'];
                $display_name = $recs['b_name'];

                $display_block .= "<option value=\"$id\">
                        $display_name</option>";
        }
}

#customer selector

if (mysql_num_rows($result_customer) == 0) {
        //no records
        $display_block_customer = "<p><em>Sorry, no biller available, please insert one</em></p>";

} else {
        //has records, so display them
        $display_block_customer = "
        <select name=\"select_customer\">
        <option selected value=\"$def_customerField\" style=\"font-weight: bold\">$c_nameField</option>
        <option value=\"\"></option>";

        while ($recs_customer = mysql_fetch_array($result_customer)) {
                $id_customer = $recs_customer['c_id'];
                $display_name_customer = $recs_customer['c_name'];

                $display_block_customer .= "<option value=\"$id_customer\">
                        $display_name_customer</option>";
        }
}


#tax selector

if (mysql_num_rows($result_tax) == 0) {
        //no records
        $display_block_tax = "<p><em>Sorry, no tax available, please insert one</em></p>";

} else {
        //has records, so display them
        $display_block_tax = "
        <select name=\"select_tax\">
        <option selected value=\"$def_taxField\" style=\"font-weight: bold\">$tax_descriptionField</option>
        <option value=\"\"></option>";

        while ($recs_tax = mysql_fetch_array($result_tax)) {
                $id_tax = $recs_tax['tax_id'];
                $display_name_tax = $recs_tax['tax_description'];

                $display_block_tax .= "<option value=\"$id_tax\">
                        $display_name_tax</option>";
        }
}

#invoice_preference selector

if (mysql_num_rows($result_preferences) == 0) {
        //no records
        $display_block_preferences = "<p><em>Sorry, no invoice preferences available, please insert one</em></p>";

} else {
        //has records, so display them
        $display_block_preferences = "
        <select name=\"select_preferences\">
        <option selected value=\"$def_inv_preferenceField\" style=\"font-weight: bold\">$pref_descriptionField</option>
        <option value=\"\"></option>";

        while ($recs_preferences = mysql_fetch_array($result_preferences)) {
                $id_preferences = $recs_preferences['pref_id'];
                $display_name_preferences = $recs_preferences['pref_description'];

                $display_block_preferences .= "<option value=\"$id_preferences\">
                        $display_name_preferences</option>";
        }
}




?>



<html>
<head>
<?php include('./include/menu.php'); ?>
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

<title>Simple Invoices - Invoice total
</title>

<?php include('./config/config.php'); ?>
</head>
<BODY>
<?php
$mid->printMenu('hormenu1');
$mid->printFooter();
?>

<link rel="stylesheet" type="text/css" href="themes/<?php echo $theme; ?>/tables.css">


<FORM name="frmpost" ACTION="insert_action.php" METHOD=POST onsubmit="return frmpost_Validator(this)">
<br>
<div id="container">
<div id="header">

<table width=100% align=center>

<tr>
<td colspan=2 align=center><b>Invoice - total</b></th>
</tr>
</table>

</div id="header">
<div id="subheader">

<table  align=center>
<tr>
<td>Biller Name</th><td input type=text name="biller_block" size=25><?php echo $display_block; ?></td>

</tr>
</tr>

<tr>
<td>Customer Name</th><td input type=text name="customer_block" size=25><?php echo $display_block_customer; ?></td>



</tr>

<tr>
	<td colspan=5>Description</td>
</tr>
<tr>
	<td colspan=5 ><textarea input type=text name="i_description" rows=10 cols=100 WRAP=hard></textarea></td>
</tr>
<tr>
	<td>Gross Total</td><td>Tax</td><td>Invoice Preference</td>
</tr>
<tr>
	<td><input type=text name="inv_it_gross_total" size=15></td><td input type=text name="inv_it_tax" size=15><?php echo $display_block_tax; ?></td><td input type=text name="inv_preferences" size=25><?php echo $display_block_preferences; ?></td>

</tr>
</table>
</div>

<div id="footer">
<p><input type=submit name="submit" value="Submit Invoice"><input type=hidden name="invoice_style" value="insert_invoice_total"> *All fields are mandatory</p>
</div>
</div>


</FORM>
</BODY>
</HTML>







