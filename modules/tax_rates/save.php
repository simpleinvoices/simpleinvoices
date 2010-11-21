<?php

//stop the direct browsing to this file - let index.php handle which files get displayed
checkLogin();

$display_block = "";
$refresh_total = "<meta http-equiv='refresh' content='2;url=index.php?module=tax_rates&amp;view=manage' />";

# Deal with op and add some basic sanity checking

$op = !empty( $_POST['op'] ) ? addslashes( $_POST['op'] ) : NULL;
$op = isset($_POST['cancel']) ? "cancel" : $op;

switch ($op) {

	case "insert_tax_rate":
		#insert tax rate
		$display_block = insertTaxRate();
		break;

	case "edit_tax_rate":
		#edit tax rate
		if (isset($_POST['save_tax_rate'])) 
			$display_block = updateTaxRate();
		else
			$refresh_total = '&nbsp';
		break;

	case "cancel":
		break;

	default:
		$refresh_total = '&nbsp';
}

$smarty -> assign('display_block',$display_block); 
$smarty -> assign('refresh_total',$refresh_total); 

$smarty -> assign('pageActive', 'tax_rate');
$smarty -> assign('active_tab', '#setting');
?>
