<?php
#table
include('./config/config.php'); 
include("./include/validation.php");
include("./lang/$language.inc.php");


jsBegin();
jsFormValidationBegin("frmpost");
jsValidateRequired("prod_description","Product Description");
jsValidateifNum("prod_unit_price","Product Unit Price");
jsFormValidationEnd();
jsEnd();


#get the invoice id
$product_id = $_GET[submit];


#Info from DB print
$conn = mysql_connect("$db_host","$db_user","$db_password");
mysql_select_db("$db_name",$conn);



#customer query
$print_product = "SELECT * FROM si_products WHERE prod_id = $product_id";
$result_print_product = mysql_query($print_product, $conn) or die(mysql_error());


while ($Array = mysql_fetch_array($result_print_product) ) {
                $prod_idField = $Array['prod_id'];
                $prod_descriptionField = $Array['prod_description'];
                $prod_enabledField = $Array['prod_enabled'];
                $prod_unit_priceField = $Array['prod_unit_price'];

	        if ($prod_enabledField == 1) {
        	        $wording_for_enabled = $wording_for_enabledField;
	        } else {
	                $wording_for_enabled = $wording_for_disabledField;
	        }

};


if ($_GET[action] == "view") {

$display_block =  "
	
	<table align=center>
	<tr>
		<td colspan=2 align=center><i>Product</i></td>
	</tr>	
	<tr>
		<td class='details_screen'>Produtct ID</td><td>$prod_idField</td>
	</tr>
	<tr>
		<td class='details_screen'>Product description</td><td>$prod_descriptionField</td>
	</tr>
	<tr>
		<td class='details_screen'>Product enabled</td><td>$wording_for_enabled</td>
	</tr>
	<tr>
		<td class='details_screen'>Unit price</td><td>$prod_unit_priceField</td>
	</tr>
	</table>
";

$footer =  "

<div id='footer'><a href='?submit=$prod_idField&action=edit'>Edit</a></div>
";




}

else if ($_GET[action] == "edit") {

#do the product enabled/disblaed drop down
$display_block_enabled = "<select name=\"prod_enabled\">
<option value=\"$prod_enabledField\" selected style=\"font-weight: bold\">$wording_for_enabled</option>
<option value=\"1\">$wording_for_enabledField</option>
<option value=\"0\">$wording_for_disabledField</option>
</select>";

$display_block =  "

        <table align=center>
        <tr>
                <td colspan=2 align=center><i>Product</i></td>
        </tr>
        <tr>
                <td class='details_screen'>Produtct ID</td><td>$prod_idField</td>
        </tr>
        <tr>
                <td class='details_screen'>Product description</td><td><input type=text name='prod_description' value='$prod_descriptionField' size=50></td>
        </tr>
        <tr>
                <td class='details_screen'>Product enabled</td><td>$display_block_enabled</td>
        </tr>
        <tr>
                <td class='details_screen'>Unit price</td><td><input type=text name='prod_unit_price' value='$prod_unit_priceField' size=25></td>
        </tr>
        </table>
";

$footer =  "

<p><input type=submit name='action' value='Cancel'>
<input type=submit name='action' value='Save Product'>
<input type=hidden name='op' value='edit_product'></p>
";

}

?>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<?php include('./include/menu.php'); ?>
<script type="text/javascript" src="niftycube.js"></script>
<script type="text/javascript">
window.onload=function(){
Nifty("div#container");
Nifty("div#content,div#nav","same-height small");
Nifty("div#header,div#footer","small");
}
</script>
<title>Simple Invoices - Customer details</title>
<?php include('./config/config.php'); ?>
</head>
<body>
<?php
$mid->printMenu('hormenu1');
$mid->printFooter();
?>

<link rel="stylesheet" type="text/css" href="themes/<?php echo $theme; ?>/tables.css">

<br>
<FORM name="frmpost" ACTION="insert_action.php?submit=<?php echo $_GET[submit];?>" METHOD=POST onsubmit="return frmpost_Validator(this)">
<div id="container">
<div id="header"></div>
<?php echo $display_block; ?>
<div id="footer">
<?php echo $footer; ?>
</div>
</div>
</form>
</body>
</html>




