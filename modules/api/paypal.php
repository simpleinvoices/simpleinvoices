<?php
global $logger;

$p = new paypal_class ();
$p->paypal_url = 'https://www.paypal.com/cgi-bin/webscr'; // paypal url

$xml_message = "";
$logger->log ( 'Paypal API page called', Zend_Log::INFO );

if ($p->validate_ipn ()) {
    $logger->log ( 'Paypal validate success', Zend_Log::INFO );

    // insert into payments
    $paypal_data = "";
    foreach ( $p->ipn_data as $key => $value ) {
        $paypal_data .= "\n$key: $value";
    }

    $logger->log ( "Paypal Data[$paypal_data", Zend_Log::INFO );

    // get the domain_id from the paypal invoice
    $custom_array = explode ( ";", $p->ipn_data ['custom'] );

    $logger->log ( 'Paypal - custom=' . $_POST ['custom'], Zend_Log::INFO );
    foreach ( $custom_array as $key => $value ) {
        if (strstr ( $value, "domain_id:" )) {
            $logger->log ("Paypal - value[$value]", Zend_Log::INFO );
            $domain_id = substr ( $value, 10 );
        }
    }

    $logger->log ( 'Paypal - domain_id=' . $domain_id . 'EOM', Zend_Log::INFO );

    // check if payment has already been entered
    $filter            = 'online_payment_id';
    $ol_pmt_id = $p->ipn_data ['txn_id'];
    $number_of_payments = Payment::count($filter, $ol_pmt_id);
    $logger->log ( 'Paypal - number of times this payment is in the db: ' . $number_of_payments, Zend_Log::INFO );
    if ($number_of_payments > 0) {
        $xml_message .= 'Online payment ' . $p->ipn_data ['tnx_id'] . ' has already been entered - exiting for domain_id=' . $domain_id;
        $logger->log ( $xml_message, Zend_Log::INFO );
    } else {
        $pmt_type = PaymentType::select_or_insert_where("Paypal");
        Payment::insert(array("ac_inv_id"         => $p->ipn_data ['invoice'],
                              "ac_amount"         => $p->ipn_data['mc_gross'],
                              "ac_notes"          => $paypal_data,
                              "ac_date"           => date('Y-m-d', strtotime($p->ipn_data['payment_date'])),
                              "online_payment_id" => $p->ipn_data['txn_id'],
                              "ac_payment_type"   => $pmt_type));
        $logger->log('Paypal - payment_type=' . $pmt_type, Zend_Log::INFO);

        $invoice = Invoice::select ( $p->ipn_data ['invoice'] );

        $biller = Biller::select ( $invoice ['biller_id'] );

        // send email
        $body = "A Paypal instant payment notification was successfully recieved\n";
        $body .= "from " . $p->ipn_data ['payer_email'] . " on " . date ( 'm/d/Y' );
        $body .= " at " . date ( 'g:i A' ) . "\n\nDetails:\n";
        $body .= $paypal_data;

        $email = new Email ();
        $email->notes   = $body;
        $email->to      = $biller ['email'];
        $email->from    = "simpleinvoices@localhost.localdomain";
        $email->subject = 'Instant Payment Notification - Recieved Payment';
        $email->send ();

        $xml_message ['data'] .= $body;
    }
} else {
    $xml_message .= "Paypal validate failed";
    $logger->log ( 'Paypal validate failed', Zend_Log::INFO );
}

header ( 'Content-type: application/xml' );
try {
    $xml = new encode ();
    $xml->xml ( $xml_message );
    echo $xml;
} catch ( Exception $e ) {
    echo $e->getMessage ();
}
