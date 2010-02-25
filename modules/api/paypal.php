<?php

$p = new paypal_class;             // initiate an instance of the class
#$p->paypal_url = 'https://www.sandbox.paypal.com/cgi-bin/webscr';   // testing paypal url
$p->paypal_url = 'https://www.paypal.com/cgi-bin/webscr';     // paypal url


$logger->log('Paypal API page called', Zend_Log::INFO);
if ($p->validate_ipn()) {

	$logger->log('Paypal validate success', Zend_Log::INFO);
	//insert into payments
	$paypal_data ="";
	foreach ($p->ipn_data as $key => $value) { $paypal_data .= "\n$key: $value"; }
	$logger->log('Paypal Data:', Zend_Log::INFO);
	$logger->log($paypal_data, Zend_Log::INFO);
	//get the domain_id from the paypal invoice
	$custom_array = explode(";", $p->ipn_date['custom']);
	foreach ($custom_array as $key => $value)
	{
		if( strstr($key,"domain:"))
		{
			$domain_id = substr($value, 7);
			#$domain_id = substr($domain_id, 0, -1);
		}
	}

	$logger->log('Paypal - domain_id='.$domain_id, Zend_Log::INFO);

	$payment = new payment();
	$payment->ac_inv_id = $p->ipn_data['invoice'];
	$payment->ac_amount = $p->ipn_data['mc_gross'];
	$payment->ac_notes = $paypal_data;
	$payment->ac_date = date( 'Y-m-d', strtotime($p->ipn_data['payment_date']));
	$payment->domain_id = $domain_id;

	$payment_type = new payment_type();
	$payment_type->type = "Paypal";
	$payment_type->domain_id = $domain_id;

	$payment->ac_payment_type = $payment_type->select_or_insert_where();
	$logger->log('Paypal - payment_type='.$payment->ac_payment_type, Zend_Log::INFO);
	$payment->insert();

	$invoice = invoice::select($p->ipn_data['invoice']);
	$biller = getBiller($invoice['biller_id']);

	//send email
	$body =  "A Paypal instant payment notification was successfully recieved into Simple Invoices\n";
	$body .= "from ".$p->ipn_data['payer_email']." on ".date('m/d/Y');
	$body .= " at ".date('g:i A')."\n\nDetails:\n";
	$body .= $paypal_data;

	$email = new email();
	$email -> notes = $body;
	$email -> to = $biller['email'];
	$email -> from = "simpleinvoice@localhost";
	$email -> subject = 'Instant Payment Notification - Recieved Payment';
	$email -> send ();

} else {

	$logger->log('Paypal validate failed', Zend_Log::INFO);
}


