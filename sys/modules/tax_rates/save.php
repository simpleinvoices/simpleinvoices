<?php

//stop the direct browsing to this file - let index.php handle which files get displayed
checkLogin();

$SI_TAX = new SimpleInvoices_Db_Table_Tax();

$display_block = "";
$refresh_total = "<meta http-equiv='refresh' content='2;url=index.php?module=tax_rates&amp;view=manage' />";

# Deal with op and add some basic sanity checking

$op = !empty( $_POST['op'] ) ? addslashes( $_POST['op'] ) : NULL;
$op = isset($_POST['cancel']) ? "cancel" : $op;

switch ($op) {

	case "insert_tax_rate":
    {
        $tax_data = array(
            'tax_description'   => $_POST['tax_description'],
            'tax_percentage'    => $_POST['tax_percentage'],
            'type'              => $_POST['type'],
            'tax_enabled'       => $_POST['tax_enabled']
        );
        
        #insert tax rate
        if ($SI_TAX->insert($tax_data)) $display_block = $LANG['save_tax_rate_success'];
        else $display_block = $LANG['save_tax_rate_failure'];
        break;
    }
	case "edit_tax_rate":
		#edit tax rate
		if (isset($_POST['save_tax_rate'])) {
            $tax_data = array(
                'tax_description'   => $_POST['tax_description'],
                'tax_percentage'    => $_POST['tax_percentage'],
                'type'              => $_POST['type'],
                'tax_enabled'       => $_POST['tax_enabled']
            );
            
            if ($SI_TAX->update($tax_data, $_GET['id'])) $display_block = $LANG['save_tax_rate_success'];
            else $display_block = $LANG['save_tax_rate_failure'];
        } else {
            $refresh_total = '&nbsp';
        }
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