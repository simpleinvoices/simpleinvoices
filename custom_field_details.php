<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<?php
#table
include("./include/include_main.php");
include("./include/validation.php");
echo <<<EOD
<title>{$title} :: {$LANG_customer_details}</title>
<link rel="stylesheet" type="text/css" href="themes/{$theme}/tables.css" />

EOD;
jsBegin();
jsFormValidationBegin("frmpost");
jsValidateRequired("prod_description",$LANG_product_description);
jsValidateifNum("prod_unit_price",$LANG_product_unit_price);
jsFormValidationEnd();
jsEnd();


#get the invoice id
$cf_id = $_GET[submit];


#Info from DB print
$conn = mysql_connect("$db_host","$db_user","$db_password");
mysql_select_db("$db_name",$conn);



#customer query
$print_product = "SELECT * FROM si_custom_fields WHERE cf_id = $cf_id";
$result_print_product = mysql_query($print_product, $conn) or die(mysql_error());


while ($Array = mysql_fetch_array($result_print_product) ) {
        $cf_idField = $Array['cf_id'];
        $prod_descriptionField = $Array['cf_custom_field'];
        $prod_enabledField = $Array['cf_custom_label'];
        //get the nice name of the custom field
        $custom_field_name = get_custom_field_name($prod_descriptionField);

};


if ($_GET['action'] == "view") {

	$display_block = <<<EOD

	<div id="header"><b>{$LANG_products}</b> ::
	<a href="?submit={$cf_idField}&action=edit">{$LANG_edit}</a></div>
	
	<table align="center">
	<tr>
		<td class="details_screen">{$LANG_product_id}</td><td>{$cf_idField}</td>
	</tr>
	<tr>
		<td class="details_screen">DB field name - CHANGE</td>
		<td>{$prod_descriptionField}</td>
	</tr>
	<tr>
		<td class="details_screen">CF name - CHANGE</td>
		<td>{$custom_field_name}</td>
	</tr>
	<tr>
		<td class="details_screen">Custom Label - CHANGE</td>
		<td>{$prod_enabledField}</td>
	</tr>
	</table>

EOD;

$footer = <<<EOD

<div id="footer"><a href="?submit={$cf_idField}&action=edit">{$LANG_edit}</a></div>

EOD;
}

else if ($_GET['action'] == "edit") {

#do the product enabled/disblaed drop down
$display_block_enabled = "<select name=\"prod_enabled\">
<option value=\"$prod_enabledField\" selected style=\"font-weight: bold\">$wording_for_enabled</option>
<option value=\"1\">$wording_for_enabledField</option>
<option value=\"0\">$wording_for_disabledField</option>
</select>";

$display_block = <<<EOD
	<div id="header"><b>{$LANG_products}</b></div>

	<table align="center">
        <tr>
                <td class="details_screen">{$LANG_product_id}</td><td>{$cf_idField}</td>
        </tr>
        <tr>
                <td class="details_screen">DB field name - CHANGE</td>
                <td>{$prod_descriptionField}</td>
        </tr>
        <tr>
                <td class="details_screen">CF name - CHANGE</td>
                <td>{$custom_field_name}</td>
        </tr>
	<tr>
		<td class="details_screen">CF LABEL _ CHANGE</td>
		<td><input type="text" name="prod_custom_field1" size="50" value="{$prod_enabledField}" /></td>
	</tr>
	</table>

EOD;

$footer = <<<EOD

<input type="submit" name="cancel" value="{$LANG_cancel}" />
<input type="submit" name="save_product" value="{$LANG_save_product}" />
<input type="hidden" name="op" value="edit_product" />

EOD;
}

?>
<script type="text/javascript" src="niftycube.js"></script>
<script type="text/javascript">
window.onload=function(){
Nifty("div#container");
Nifty("div#content,div#nav","same-height small");
Nifty("div#header,div#footer","small");
}
</script>
<script language="javascript" type="text/javascript" src="include/tiny_mce/tiny_mce_src.js"></script>
<script language="javascript" type="text/javascript" src="include/tiny-mce.conf.js"></script>
</head>
<body>

<?php
$mid->printMenu('hormenu1');
$mid->printFooter();
echo <<<EOD

<br>
<FORM name="frmpost" ACTION="insert_action.php?submit={$_GET['submit']}"
 METHOD="POST" onsubmit="return frmpost_Validator(this)">
<div id="container">
{$display_block}
<div id="footer">
{$footer}

EOD;
?>
</div>
</div>
</form>
</body>
</html>
