<?php

//stop the direct browsing to this file - let index.php handle which files get displayed
checkLogin();

# Deal with op and add some basic sanity checking

#insert - process payment
#op=pay_selected_invoice means the user came from the print_view or manage_invoces
#op=pay_invoice means the user came from the process_paymen page

global $db_server;
if ( isset($_POST['process_payment']) ) {

	$sql = "INSERT into
			".TB_PREFIX."payment
		VALUES
			(	
				NULL,
				:invoice,
				:amount,
				:notes,
				:date,
				:payment_type
			)";
	if ($db_server == 'pgsql') {
		$sql = "INSERT into ".TB_PREFIX."payment
			(ac_inv_id, ac_amount, ac_notes, ac_date, ac_payment_type)
		VALUES
			(:invoice, :amount, :notes, :date, :payment_type)";
	}

	if (dbQuery($sql,
	  ':invoice', $_POST['ac_inv_id'],
	  ':amount', $_POST['ac_amount'],
	  ':notes', $_POST['ac_notes'],
	  ':date', $_POST['ac_date'],
	  ':payment_type', $_POST['ac_payment_type']
	  )) {
		$display_block =  $LANG['save_payment_success'];
	} else {
		$display_block =  $LANG['save_payment_failure']."<br>".$sql;
	}

	$refresh_total = "<META HTTP-EQUIV=REFRESH CONTENT=2;URL=index.php?module=payments&view=manage>";
}

$smarty->assign('display_block', $display_block);

$smarty -> assign('pageActive', 'payment');
$smarty -> assign('active_tab', '#money');
