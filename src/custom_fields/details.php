<?php
include_once('./include/include_main.php');

//stop the direct browsing to this file - let index.php handle which files get displayed
if (!defined("BROWSE")) {
   echo "You Cannot Access This Script Directly, Have a Nice Day.";
   exit();
}

?>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<?php
#table

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
        $cf_custom_fieldField = $Array['cf_custom_field'];
        $cf_custom_labelField = $Array['cf_custom_label'];
        //get the nice name of the custom field
        $custom_field_name = get_custom_field_name($cf_custom_fieldField);

};


if ($_GET['action'] == "view") {

	$display_block = <<<EOD

	<b>{$LANG_custom_fields} ::
	<a href="index.php?module=custom_fields&view=details&submit={$cf_idField}&action=edit">{$LANG_edit}</a></b>
	<hr></hr>


	
	<table align="center">
	<tr>
		<td class="details_screen">{$LANG_id}</td><td>{$cf_idField}</td>
	</tr>
	<tr>
		<td class="details_screen">{$LANG_custom_field_db_field_name}</td>
		<td>{$cf_custom_fieldField}</td>
	</tr>
	<tr>
		<td class="details_screen">{$LANG_custom_field}</td>
		<td>{$custom_field_name}</td>
	</tr>
	<tr>
		<td class="details_screen">{$LANG_custom_label}</td>
		<td>{$cf_custom_labelField}</td>
	</tr>
	</table>
	<hr></hr>
EOD;

$footer = <<<EOD

<a href="index.php?module=custom_fields&view=details&submit={$cf_idField}&action=edit">{$LANG_edit}</a>

EOD;
}

else if ($_GET['action'] == "edit") {


$display_block = <<<EOD


	<b>{$LANG_custom_fields}</b>

	<hr></hr>

	<table align="center">
        <tr>
                <td class="details_screen">{$LANG_id}</td><td>{$cf_idField}</td>
        </tr>
        <tr>
                <td class="details_screen">{$LANG_custom_field_db_field_name}</td>
                <td>{$cf_custom_fieldField}</td>
        </tr>
        <tr>
                <td class="details_screen">{$LANG_custom_field}</td>
                <td>{$custom_field_name}</td>
        </tr>
	<tr>
		<td class="details_screen">{$LANG_custom_label}</td>
		<td><input type="text" name="cf_custom_label" size="50" value="{$cf_custom_labelField}" /></td>
	</tr>
	</table>
	<hr></hr>
EOD;

$footer = <<<EOD

<input type="submit" name="cancel" value="{$LANG_cancel}" />
<input type="submit" name="save_custom_field" value="{$LANG_save_custom_field}" />
<input type="hidden" name="op" value="edit_custom_field" />

EOD;
}

?>

<script language="javascript" type="text/javascript" src="include/tiny_mce/tiny_mce_src.js"></script>
<script language="javascript" type="text/javascript" src="include/tiny-mce.conf.js"></script>
</head>
<body>

<?php
echo <<<EOD

<FORM name="frmpost" ACTION="index.php?module=custom_fields&view=save&submit={$_GET['submit']}"
 METHOD="POST" onsubmit="return frmpost_Validator(this)">

{$display_block}
{$footer}

EOD;
?>
</form>
<!-- ./src/include/design/footer.inc.php gets called here by controller srcipt -->
