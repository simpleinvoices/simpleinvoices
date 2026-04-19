<?php

$logger->log('ACH API page called', LegacyLogger::INFO);
if ($_POST['pg_response_code']=='A01') {

	$logger->log('ACH validate success', LegacyLogger::INFO);

	//insert into payments
	$paypal_data ="";
	foreach ($_POST as $key => $value) { $paypal_data .= "\n$key: $value"; }
	$logger->log('ACH Data:', LegacyLogger::INFO);
	$logger->log($paypal_data, LegacyLogger::INFO);

	// Resolve domain_id from the invoice referenced by this payment (id is per-domain, not globally unique)
	$ach_invoice_id = (int)$_POST['pg_consumerorderid'];
	$domain_rows = dbQuery(
		"SELECT DISTINCT domain_id FROM ".TB_PREFIX."invoices WHERE id = :id",
		':id', $ach_invoice_id
	)->fetchAll(PDO::FETCH_ASSOC);
	$resolved_domain_id = '1';
	if (count($domain_rows) === 1) {
		$resolved_domain_id = (string) $domain_rows[0]['domain_id'];
	} elseif (count($domain_rows) > 1) {
		$logger->log(
			'ACH: invoice id ' . $ach_invoice_id . ' exists in multiple domains; cannot resolve domain for payment',
			LegacyLogger::ERROR
		);
		$xml_message = 'Payment could not be recorded: invoice id is ambiguous across domains. Contact support.';
		echo $xml_message;
		return;
	} else {
		$logger->log('ACH: no invoice row for id ' . $ach_invoice_id . '; defaulting domain to 1', LegacyLogger::WARN);
	}

	$check_payment = new payment();
	$check_payment->filter='online_payment_id';
	$check_payment->online_payment_id = $_POST['pg_consumerorderid'];
	$check_payment->domain_id = $resolved_domain_id;
    $number_of_payments = $check_payment->count();
	$logger->log('ACH - number of times this payment is in the db: '.$number_of_payments, LegacyLogger::INFO);

	if($number_of_payments > 0)
	{
		$xml_message = 'Online payment for invoices: '.$_POST['pg_consumerorderid'].' has already been entered into Simple Invoices';
		$logger->log($xml_message, LegacyLogger::INFO);
	}

	if($number_of_payments == '0')
	{

		$payment = new payment();
		$payment->ac_inv_id = $_POST['pg_consumerorderid'];
		$payment->ac_amount = $_POST['pg_total_amount'];
		$payment->ac_notes = $paypal_data;
		$payment->ac_date = date( 'Y-m-d');
		$payment->online_payment_id = $_POST['pg_consumerorderid'];
		$payment->domain_id = $resolved_domain_id;

			$payment_type = new payment_type();
			$payment_type->type = "ACH";
			$payment_type->domain_id = $resolved_domain_id;

		$payment->ac_payment_type = $payment_type->select_or_insert_where();
		$logger->log('ACH - payment_type='.$payment->ac_payment_type, LegacyLogger::INFO);
		$payment->insert();

		$invoiceobj = new invoice();
		$invoice = $invoiceobj->select((int) $_POST['pg_consumerorderid'], $resolved_domain_id);
		$biller = getBiller($invoice['biller_id'], $resolved_domain_id);

		//send email
		$body =  "A PaymentsGateway.com payment of ".$_POST['pg_total_amount']." was successfully received into Simple Invoices\n";
		$body .= "for invoice: ".$_POST['pg_consumerorderid'] ;
		$body .= " from ".$_POST['pg_billto_postal_name_company']." on ".date('m/d/Y');
		$body .= " at ".date('g:i A')."\n\nDetails:\n";
		$body .= $paypal_data;

		$email = new email();
		$email -> notes = $body;
		$email -> to = $biller['email'];
		$email -> from = "simpleinvoices@localhost.localdomain";
		$email -> subject = 'PaymentsGateway.com -Instant Payment Notification - Recieved Payment';
		$email -> send ();
        $xml_message = "+++++++++<br /><br />";
		$xml_message .= "Thank you for the payment, the details have been recorded and ". $biller['name'] ." has been notified via email.";
        $xml_message .= "<br /><br />+++++++++<br />";
	}
} else {

	$xml_message = "PaymentsGateway.com payment validate failed - please contact ". $biller['name'] ;
	$logger->log('ACH validate failed', LegacyLogger::INFO);
}

    echo $xml_message;

