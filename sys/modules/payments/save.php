<?php

//stop the direct browsing to this file - let index.php handle which files get displayed
checkLogin();

# Deal with op and add some basic sanity checking

#insert - process payment
#op=pay_selected_invoice means the user came from the print_view or manage_invoces
#op=pay_invoice means the user came from the process_paymen page

global $db_server;
global $auth_session;
if ( isset($_POST['process_payment']) ) {
	
	$payment = new payment();
	
	
	
	$payment->ac_inv_id = $_POST['invoice_id'];
	$payment->ac_amount = $_POST['ac_amount'];
	$payment->ac_notes = $_POST['ac_notes'];
	$payment->ac_date = $_POST['ac_date'];
	$payment->ac_payment_type = $_POST['ac_payment_type'];
	
	
		/* todo:
		 * use language file for process page text and appended notes
		 */
		
		//works out any funds that are in excess of invoice total:
		$invoicetotal = getInvoiceTotal($payment->ac_inv_id);
                $orig_amt = $payment->ac_amount;
               	$extrapayment = $payment->ac_amount - $invoicetotal;

		
		if (  $extrapayment > 0.0 && $_POST['distribute'] == '1' ) { //is there any money left over to distribute to other invoices?
                                                                             //and check for user preference
		
		//reduce original payment variable to the invoice total:
		$payment->ac_amount = $invoicetotal;
		
		//grab the customer ID so we can look at the other invoices we can process payments for
		$sql = "SELECT customer_id from ".TB_PREFIX."invoices where id = :ac_inv_id";
				$sth = $db->query($sql,
        ':ac_inv_id',$payment->ac_inv_id) or die();
		$cust_id = $sth->fetch();
		$cust_id = $cust_id['customer_id'];
		
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
			
		$sth = $db->query($sql, ':cust_id',$cust_id,':ac_inv_id',$payment->ac_inv_id) or die();
		

                $paid_to = array(); //init array for tracking payment ID's
                $inv_info = "<ul>";
                $inv_info_format = "<li>Invoice %d (%f was paid)</li>";

		//loop through extra invoices

		while ($curr_inv = $sth->fetch() ) {
		if ($extrapayment <= 0.0) {
		break;
		}
			$sub_payment = new payment();
			
			//work out how much we will be paying to this invoice:
			$curr_owing = $curr_inv['all_charge'] - $curr_inv['all_pay'];
			
			if ( $curr_owing < $extrapayment) { //we have more money to pay than on invoice
			$revert = 'more'; // for use if a rollback is needed
			$sub_payment->ac_amount = $curr_owing;
			$extrapayment = $extrapayment - $curr_owing;
			} elseif ($curr_owing >= $extrapayment) { //no more money
			$sub_payment->ac_amount = $extrapayment;
			$extrapayment = 0.0;
			$revert = 'nomore';
			}
			
			$sub_payment->ac_inv_id = $curr_inv['invoice_id'];
			$sub_payment->ac_notes = $_POST['ac_notes'];
			$sub_payment->ac_date = $_POST['ac_date'];
			$sub_payment->ac_payment_type = $_POST['ac_payment_type'];
			$sub_result = $sub_payment->insert();


			//revert variables back to previous state if payment insert fails
			$sub_saved = !empty($sub_result) ? "true" : "false";
			if($sub_saved =='false')
			{
				if ($revert == 'more') {
					//rollback amount we have to pay
					$extrapayment = $extrapayment + $curr_owing;
					}
				elseif ($revert == 'nomore') {
					$extrapayment = $sub_payment->ac_amount;
				}
			
			} else { //payment succesful, record data in array and note html
                            $paid_to[$db->lastInsertId()] = $sub_payment->ac_amount;
                            $inv_info .= sprintf($inv_info_format,$sub_payment->ac_inv_id,$sub_payment->ac_amount);

                        }
			
		} //end while loop
		
                

		// return any unused funds to be paid to the original invoice
		$payment->ac_amount = $payment->ac_amount + $extrapayment;
                
	}

	
	$result = $payment->insert();


        
	$saved = !empty($result) ? "true" : "false";
	if($saved =='true')
	{
		$display_block =  $LANG['save_payment_success'];
                $paid_to[$db->lastInsertId()] = $payment->ac_amount;
                $inv_info .= sprintf($inv_info_format,$payment->ac_inv_id,$payment->ac_amount);

	}
        elseif ($saved == 'false' && $_POST['distribute'] == '1')
        {
        $display_block = sprintf('An error ocurred! %f of your total payment of %f was not recorded in the database.<br />%s',$payment->ac_amount,$orig_amt,$sql);
        }
        else {
		$display_block =  $LANG['save_payment_failure']."<br />".$sql;
	}

        $inv_info .= "</ul>";

        //Append distribution information to pmt notes
        foreach ($paid_to as $sub_pmt_inv_id => $sub_amt)
        {

        $paid_formatted = sprintf("<br /><br />This payment of %f was part of a larger payment of %f and has been automatically split over the following invoices:",$sub_amt,$orig_amt);
        $paid_formatted .= $inv_info;

        $sql_notes = "UPDATE ".TB_PREFIX."payment SET ac_notes=concat(ac_notes,:extra_notes) WHERE id=:id;";
        $result = $db->query($sql_notes,
            ':extra_notes',$paid_formatted,
            ':id',$sub_pmt_inv_id
        ) or die();

        }

	$refresh_total = "<meta http-equiv='refresh' content='27;url=index.php?module=payments&view=manage' />";
}

$smarty->assign('display_block', $display_block);

$smarty -> assign('pageActive', 'payment');
$smarty -> assign('active_tab', '#money');
?>
