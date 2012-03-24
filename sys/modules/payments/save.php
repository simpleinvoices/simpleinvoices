<?php

//stop the direct browsing to this file - let index.php handle which files get displayed
checkLogin();

# Deal with op and add some basic sanity checking

#insert - process payment
#op=pay_selected_invoice means the user came from the print_view or manage_invoces
#op=pay_invoice means the user came from the process_paymen page

global $auth_session;
if ( isset($_POST['process_payment']) ) {
    
    $SI_PAYMENTS = new SimpleInvoices_Db_Table_Payment();
    
    $payment_data = array(
        'ac_inv_id'         => $_POST['invoice_id'],
        'ac_amount'         => $_POST['ac_amount'],
        'ac_notes'          => $_POST['ac_notes'],
        'ac_date'           => $_POST['ac_date'],
        'ac_payment_type'   => $_POST['ac_payment_type'],
        'online_payment_id' => NULL
    );
    
	//works out any funds that are in excess of invoice total:
	$invoicetotal = getInvoiceTotal($payment_data['ac_inv_id']);
    $orig_amt = $payment_data['ac_amount'];
    $extrapayment = $payment_data['ac_amount']; - $invoicetotal;


	if (  $extrapayment > 0.0 && $_POST['distribute'] == '1' ) {    //is there any money left over to distribute to other invoices?
                                                                    //and check for user preference
        //reduce original payment variable to the invoice total:
        $payment_data['ac_amount'] = $invoicetotal;

        //grab the customer ID so we can look at the other invoices we can process payments for
        $sql = "SELECT customer_id from ".TB_PREFIX."invoices where id = :ac_inv_id";
        $sth = $db->query($sql,':ac_inv_id',$payment_data['ac_inv_id']) or die();
        $cust_id = $sth->fetch();

        // query to get unpaid invoices from the customer whose payment is being recorded
        $sql = "
            SELECT * from

            (SELECT invoice_id, customer_id, sum(total) as all_charge FROM ".TB_PREFIX."invoices
            inner join ".TB_PREFIX."invoice_items
            on
            ".TB_PREFIX."invoices.id = ".TB_PREFIX."invoice_items.invoice_id
            GROUP BY ".TB_PREFIX."invoices.id ) charge
            left join

            (SELECT ".TB_PREFIX."invoices.id, sum(ac_amount) as all_pay FROM ".TB_PREFIX."invoices
            inner join ".TB_PREFIX."payment
            on
            ".TB_PREFIX."invoices.id = ".TB_PREFIX."payment.ac_inv_id
            GROUP BY ".TB_PREFIX."invoices.id ) pay
            on charge.invoice_id = pay.id
            where all_charge > ifnull(all_pay,0) and customer_id=:cust_id and charge.invoice_id <> :ac_inv_id
        ";

        $sth = $db->query($sql, ':cust_id',$cust_id['customer_id'],':ac_inv_id',$payment_data['ac_inv_id']) or die();


        $paid_to = array(); //init array for tracking payment ID's
        $inv_info = "<ul>";
        $inv_info_format = "<li>".$LANG['invoice']." %d (%f ".$LANG['paid'].")</li>";

        //loop through extra invoices

        while ($curr_inv = $sth->fetch() ) {
            if ($extrapayment <= 0.0) break;

            $sub_payment_data = array(
                'ac_inv_id'         => $curr_inv['invoice_id'],
                'ac_amount'         => 0,
                'ac_notes'          => $_POST['ac_notes'],
                'ac_date'           => $_POST['ac_date'],
                'ac_payment_type'   => $_POST['ac_payment_type']
            );

            //work out how much we will be paying to this invoice:
            $curr_owing = $curr_inv['all_charge'] - $curr_inv['all_pay'];

            if ( $curr_owing < $extrapayment) { //we have more money to pay than on invoice
                $revert = 'more'; // for use if a rollback is needed
                $sub_payment_data['ac_amount'] = $curr_owing;
                $extrapayment = $extrapayment - $curr_owing;
            } elseif ($curr_owing >= $extrapayment) { //no more money
                $sub_payment_data['ac_amount'] = $extrapayment;
                $extrapayment = 0.0;
                $revert = 'nomore';
            }

            // Insert subpayment
            $sub_result = $SI_PAYMENTS->insert($sub_payment_data);

            //revert variables back to previous state if payment insert fails
            $sub_saved = !empty($sub_result) ? "true" : "false";
            if($sub_saved =='false') {
                if ($revert == 'more') {
                    //rollback amount we have to pay
                    $extrapayment = $extrapayment + $curr_owing;
                } elseif ($revert == 'nomore') {
                    $extrapayment = $sub_payment_data['ac_amount'];
                }

            } else { //payment succesful, record data in array and note html
                $paid_to[$SI_PAYMENTS->getLastInsertId()] = $sub_payment_data['ac_amount'];
                $inv_info .= sprintf($inv_info_format,$sub_payment_data['ac_inv_id'],$sub_payment_data['ac_amount']);
            }
        } //end while loop

        // return any unused funds to be paid to the original invoice
        $payment_data['ac_amount'] = $payment_data['ac_amount'] + $extrapayment;
    }

    $result = $SI_PAYMENTS->insert($payment_data);

	$saved = !empty($result) ? "true" : "false";
	if($saved =='true') {
		$display_block =  $LANG['save_payment_success'];
        $paid_to[$SI_PAYMENTS->getLastInsertId()] = $payment_data['ac_amount'];
        $inv_info .= sprintf($inv_info_format,$payment_data['ac_inv_id'],$payment_data['ac_amount']);
	} elseif ($saved == 'false' && $_POST['distribute'] == '1') {
        $display_block = sprintf('%s %f %s %f %s<br />%s',$LANG['something_went_wrong'],$payment_data['ac_amount'],$LANG['of'],$orig_amt,$LANG['save_payment_failure_distribute'],$sql);
    } else {
	    $display_block =  $LANG['save_payment_failure']."<br />".$sql;
	}

    $inv_info .= "</ul>";

    //Append distribution information to pmt notes
    foreach ($paid_to as $sub_pmt_id => $sub_amt) {
        $paid_formatted = sprintf("<br /><br />%s %f %s %f %s",$LANG['payment_of'],$sub_amt,$LANG['payment_larger'],$orig_amt,$LANG['payment_split_over']);
        $paid_formatted .= $inv_info;

        $update_data = array(
            'ac_notes' => new Zend_Db_Expr($SI_PAYMENTS->getAdapter()->quoteInto('CONCAT(ac_notes, ?)', $paid_formatted))
        );
        $SI_PAYMENTS->update($update_data, $sub_pmt_id);
    }

	$refresh_total = "<meta http-equiv='refresh' content='27;url=index.php?module=payments&view=manage' />";
}

$smarty->assign('display_block', $display_block);

$smarty -> assign('pageActive', 'payment');
$smarty -> assign('active_tab', '#money');
?>