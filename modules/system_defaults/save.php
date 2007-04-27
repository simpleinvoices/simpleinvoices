<?php

//stop the direct browsing to this file - let index.php handle which files get displayed
checkLogin();



# Deal with op and add some basic sanity checking

$op = !empty( $_POST['op'] ) ? addslashes( $_POST['op'] ) : NULL;


#update system defaults
if (  $op == 'update_system_defaults' ) {

#get defaultsr query
$print_defaults = "SELECT * FROM {$tb_prefix}defaults WHERE def_id = 1";
$result_print_defaults = mysql_query($print_defaults, $conn) or die(mysql_error());


while ($Array = mysql_fetch_array($result_print_defaults) ) {
		$def_idField = $Array['def_id'];
		$def_customerField = $Array['def_customer'];
		$def_billerField = $Array['def_biller'];
		$def_taxField = $Array['def_tax'];
		$def_inv_preferenceField = $Array['def_inv_preference'];
		$def_number_line_itemsField = $Array['def_number_line_items'];
		$def_inv_templateField = $Array['def_inv_template'];
		$def_payment_typeField = $Array['def_payment_type'];
};

$default_biller = $_POST['default_biller'];
$default_customer = $_POST['default_customer'];
$default_tax = $_POST['default_tax'];
$default_inv_preference = $_POST['default_inv_preference'];
$default_num_line_items = $_POST['def_num_line_items'];
$def_inv_template = $_POST['def_inv_template'];
$default_payment_type = $_POST['def_payment_type'];


	#UPDATE the default number of line items

	if ($_GET['sys_default'] == "line_items") {
	
		$sql = "REPLACE INTO
				{$tb_prefix}defaults
			VALUES
				(
					1,
					$def_billerField,
					$def_customerField,
					$def_taxField,	
					$def_inv_preferenceField,
					$default_num_line_items,	
					'$def_inv_templateField',
					$def_payment_typeField
				)";

		if (mysql_query($sql, $conn)) {
			$display_block =  $LANG['save_defaults_line_items_success'];
		} else {
			$display_block =  $LANG['save_default_failure'] . "<br><br>" . $sql;
}

	//header( 'refresh: 2; url=index.php?module=system_defaults&view=manage' );
        $refresh_total = "<META HTTP-EQUIV=REFRESH CONTENT=2;URL=index.php?module=system_defaults&view=manage>";
}



	#UPDATE the default invoice template field

	else if ($_GET['sys_default'] == "def_inv_template") {

		$sql = "REPLACE INTO
				{$tb_prefix}defaults
			VALUES
				(
					1,
					$def_billerField,
					$def_customerField,
					$def_taxField,
					$def_inv_preferenceField,
					$def_number_line_itemsField,
					'$def_inv_template',
					$def_payment_typeField
				)";

		if (mysql_query($sql, $conn)) {
			$display_block =  $LANG['save_defaults_template_success'];
		} else {
			$display_block =  $LANG['save_default_failure'] . "<br><br>" . $sql;
}

	//header( 'refresh: 2; url=index.php?module=system_defaults&view=manage' );
        $refresh_total = "<META HTTP-EQUIV=REFRESH CONTENT=2;URL=index.php?module=system_defaults&view=manage>";
}

	#UPDATE the default biller field

	else if ($_GET['sys_default'] == "def_biller") {

		$sql = "REPLACE INTO
				{$tb_prefix}defaults
			VALUES
				 (
					1,
					$default_biller,
					$def_customerField,
					$def_taxField,
					$def_inv_preferenceField,
					$def_number_line_itemsField,
					'$def_inv_templateField',
					'$def_payment_typeField'
					)";

		if (mysql_query($sql, $conn)) {
			$display_block = $LANG['save_defaults_biller_success'];
		} else {
			$display_block =  $LANG['save_default_failure'] . "<br><br>" . $sql;
}

	//header( 'refresh: 2; url=index.php?module=system_defaults&view=manage' );
        $refresh_total = "<META HTTP-EQUIV=REFRESH CONTENT=2;URL=index.php?module=system_defaults&view=manage>";
}

	#UPDATE the default customer field

	else if ($_GET['sys_default'] == "def_customer") {

		$sql = "REPLACE INTO
				{$tb_prefix}defaults
			VALUES
				(
					1,
					$def_billerField,
					$default_customer,
					$def_taxField,
					$def_inv_preferenceField,
					$def_number_line_itemsField,
					'$def_inv_templateField',
					$def_payment_typeField
				)";

		if (mysql_query($sql, $conn)) {
			$display_block =  $LANG['save_defaults_customer_success'];
		} else {
			$display_block =  $LANG['save_default_failure'] . "<br><br>" . $sql;
}

	//header( 'refresh: 2; url=index.php?module=system_defaults&view=manage' );
        $refresh_total = "<META HTTP-EQUIV=REFRESH CONTENT=2;URL=index.php?module=system_defaults&view=manage>";
}


	#UPDATE the default tax field

	else if ($_GET['sys_default'] == "def_tax") {

		$sql = "REPLACE INTO
				{$tb_prefix}defaults
			VALUES
				(
					1,
					$def_billerField,
					$def_customerField,
					$default_tax,
					$def_inv_preferenceField,
					$def_number_line_itemsField,
					'$def_inv_templateField',
					$def_payment_typeField
				)";

		if (mysql_query($sql, $conn)) {
			$display_block = $LANG['save_defaults_tax_success'];
		} else {
			$display_block =  $LANG['save_default_failure'] . "<br><br>" . $sql;
}

	//header( 'refresh: 2; url=index.php?module=system_defaults&view=manage' );
        $refresh_total = "<META HTTP-EQUIV=REFRESH CONTENT=2;URL=index.php?module=system_defaults&view=manage>";
}


	#UPDATE the default invoice preference field

	else if ($_GET['sys_default'] == "def_invoice_preference") {

		$sql = "REPLACE INTO
				{$tb_prefix}defaults
			VALUES
				(
					1,
					$def_billerField,
					$def_customerField,
					$def_taxField,
					$default_inv_preference,
					$def_number_line_itemsField,
					'$def_inv_templateField',
					'$def_payment_typeField'
				)";

		if (mysql_query($sql, $conn)) {
			$display_block = $LANG['save_defaults_preference_success'];
		} else {
			$display_block =  $LANG['save_default_failure'] . "<br><br>" . $sql;
}

	//header( 'refresh: 2; url=index.php?module=system_defaults&view=manage' );
        $refresh_total = "<META HTTP-EQUIV=REFRESH CONTENT=2;URL=index.php?module=system_defaults&view=manage>";
}

	#UPDATE the default payment_type field

	else if ($_GET['sys_default'] == "def_payment_type") {

		$sql = "REPLACE INTO
				{$tb_prefix}defaults
			VALUES
				(
					1,
					$def_billerField,
					$def_customerField,
					$def_taxField,
					$def_inv_preferenceField,
					$def_number_line_itemsField,
					'$def_inv_templateField',
					$default_payment_type
				)";

		if (mysql_query($sql, $conn)) {
			$display_block = $LANG['save_defaults_payment_type_success'];
		} else {
			$display_block =  $LANG['save_default_failure'] . "<br><br>" . $sql;
	}

	//header( 'refresh: 2; url=index.php?module=system_defaults&view=manage' );
        $refresh_total = "<META HTTP-EQUIV=REFRESH CONTENT=2;URL=index.php?module=system_defaults&view=manage>";
}

}
#end system default section


$refresh_total = isset($refresh_total) ? $refresh_total : '&nbsp';
$display_block_items = isset($display_block_items) ? $display_block_items : '&nbsp;';

echo <<<EOD
{$refresh_total}

<br>

$display_block
<br><br>
$display_block_items

EOD;
?>