<?php

//stop the direct browsing to this file - let index.php handle which files get displayed
checkLogin();


# Deal with op and add some basic sanity checking

#insert - process payment
#op=pay_selected_invoice means the user came from the print_view or manage_invoces
#op=pay_invoice means the user came from the process_paymen page

if ( isset($_POST['process_payment']) ) {

	$sql = "INSERT into
			{$tb_prefix}account_payments
		VALUES
			(	
				'',
				'$_POST[ac_inv_id]',
				'$_POST[ac_amount]',
				'$_POST[ac_notes]',
				'$_POST[ac_date]',
				'$_POST[ac_payment_type]'
			)";

	if (mysqlQuery($sql, $conn)) {
		$display_block =  $LANG['save_payment_success'];
	} else {
		$display_block =  $LANG['save_payment_failure']."<br>".$sql;
	}

	$refresh_total = "<META HTTP-EQUIV=REFRESH CONTENT=2;URL=index.php?module=payments&view=manage>";
}


$refresh_total = isset($refresh_total) ? $refresh_total : '&nbsp';
$display_block_items = isset($display_block_items) ? $display_block_items : '&nbsp;';
echo <<<EOD

$refresh_total
<br>
<br>
{$display_block}
<br><br>
{$display_block_items}

EOD;
?>