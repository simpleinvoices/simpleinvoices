<?php

//stop the direct browsing to this file - let index.php handle which files get displayed
checkLogin();




# Deal with op and add some basic sanity checking

$op = !empty( $_POST['op'] ) ? addslashes( $_POST['op'] ) : NULL;


extract( $_POST );

#insert - process payment
#op=pay_selected_invoice means the user came from the print_view or manage_invoces
#op=pay_invoice means the user came from the process_paymen page

if ( $op === 'pay_invoice' OR $op === 'pay_selected_invoice' ) {

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

	if (mysql_query($sql, $conn)) {
		if ( $op === 'pay_selected_invoice' ) {
			$display_block =  $LANG_save_payment_invoice_success;
		}
		if ( $op === 'pay_invoice' ) {
			$display_block =  $LANG_save_payment_success;
		}
		

	} else {
		$display_block =  $LANG_save_payment_failure."<br>".$sql;
	}

	if ( $op === 'pay_selected_invoice' ) {

		//header( 'refresh: 2; url=manage_invoices.php' );
		$refresh_total = "<META HTTP-EQUIV=REFRESH CONTENT=2;URL=index.php?module=invoices&view=manage>";

	}
	else if ( $op === 'pay_invoice' ) {
		//header( 'refresh: 2; url=process_payment.php?op=pay_invoice' );
		$refresh_total = "<META HTTP-EQUIV=REFRESH CONTENT=2;URL=index.php?module=payments&view=manage>";
	}

}



include('./include/include_main.php');

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
<!-- ./src/include/design/footer.inc.php gets called here by controller srcipt -->
