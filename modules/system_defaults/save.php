<?php

//stop the direct browsing to this file - let index.php handle which files get displayed
checkLogin();



# Deal with op and add some basic sanity checking

$op = !empty( $_POST['op'] ) ? addslashes( $_POST['op'] ) : NULL;


#update system defaults
if (  $op == 'update_system_defaults' ) {


	#UPDATE the default number of line items
	if ($_GET['sys_default'] == "line_items") {
		
		if (updateDefault("items",$_POST[def_num_line_items])) {
			$display_block =  $LANG['save_defaults_line_items_success'];
		} else {
			$display_block =  $LANG['save_default_failure'] . "<br><br>" . $sql;
		}

	//header( 'refresh: 2; url=index.php?module=system_defaults&view=manage' );
        $refresh_total = "<META HTTP-EQUIV=REFRESH CONTENT=2;URL=index.php?module=system_defaults&view=manage>";
	}



	#UPDATE the default invoice template field

	else if ($_GET['sys_default'] == "def_inv_template") {
		//echo "TEMPLATE";
		if (updateDefault("template",$_POST['def_inv_template'])) {
			$display_block =  $LANG['save_defaults_template_success'];
		} else {
			$display_block =  $LANG['save_default_failure'] . "<br><br>" . $sql;
		}

	//header( 'refresh: 2; url=index.php?module=system_defaults&view=manage' );
        $refresh_total = "<META HTTP-EQUIV=REFRESH CONTENT=2;URL=index.php?module=system_defaults&view=manage>";
	}

	#UPDATE the default biller field

	else if ($_GET['sys_default'] == "def_biller") {

		if (updateDefault("biller",$_POST['default_biller'])) {
			$display_block = $LANG['save_defaults_biller_success'];
		} else {
			$display_block =  $LANG['save_default_failure'] . "<br><br>" . $sql;
		}

	//header( 'refresh: 2; url=index.php?module=system_defaults&view=manage' );
        $refresh_total = "<META HTTP-EQUIV=REFRESH CONTENT=2;URL=index.php?module=system_defaults&view=manage>";
	}

	#UPDATE the default customer field

	else if ($_GET['sys_default'] == "def_customer") {

		if (updateDefault("customer",$_POST['default_customer'])) {
			$display_block =  $LANG['save_defaults_customer_success'];
		} else {
			$display_block =  $LANG['save_default_failure'] . "<br><br>" . $sql;
		}

	//header( 'refresh: 2; url=index.php?module=system_defaults&view=manage' );
        $refresh_total = "<META HTTP-EQUIV=REFRESH CONTENT=2;URL=index.php?module=system_defaults&view=manage>";
	}


	#UPDATE the default tax field

	else if ($_GET['sys_default'] == "def_tax") {

		if (updateDefault("tax",$_POST['default_tax'])) {
			$display_block = $LANG['save_defaults_tax_success'];
		} else {
			$display_block =  $LANG['save_default_failure'] . "<br><br>" . $sql;
}

	//header( 'refresh: 2; url=index.php?module=system_defaults&view=manage' );
        $refresh_total = "<META HTTP-EQUIV=REFRESH CONTENT=2;URL=index.php?module=system_defaults&view=manage>";
}


	#UPDATE the default invoice preference field

	else if ($_GET['sys_default'] == "def_invoice_preference") {

		if (updateDefault("invoice",$_POST['default_inv_preference'])) {
			$display_block = $LANG['save_defaults_preference_success'];
		} else {
			$display_block =  $LANG['save_default_failure'] . "<br><br>" . $sql;
		}

	//header( 'refresh: 2; url=index.php?module=system_defaults&view=manage' );
        $refresh_total = "<META HTTP-EQUIV=REFRESH CONTENT=2;URL=index.php?module=system_defaults&view=manage>";
	}

	#UPDATE the default payment_type field

	else if ($_GET['sys_default'] == "def_payment_type") {
		if (updateDefault("payment_type",$_POST['def_payment_type'])) {
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