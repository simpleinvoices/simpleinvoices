<?php
global $logger;

$logger->log('ACH API page called', Zend_Log::INFO);
if ($_POST['pg_response_code'] == 'A01') {
    $logger->log('ACH validate success', Zend_Log::INFO);

    //insert into payments
    $paypal_data ="";
    foreach ($_POST as $key => $value) {
        $paypal_data .= "\n$key: $value";
    }
    $logger->log('ACH Data:', Zend_Log::INFO);
    $logger->log($paypal_data, Zend_Log::INFO);

    $number_of_payments = Payment::count('online_payment_id', $_POST['pg_consumerorderid']);
    $logger->log('ACH - number of times this payment is in the db: '.$number_of_payments, Zend_Log::INFO);

    if($number_of_payments > 0) {
        $xml_message = 'Online payment for invoices: '.$_POST['pg_consumerorderid'].' has already been entered';
        $logger->log($xml_message, Zend_Log::INFO);
    } else {
        $pmt_type = PaymentType::select_or_insert_where("ACH");
        Payment::insert(array("ac_inv_id"         => $_POST['pg_consumerorderid'],
                              "ac_amount"         => $_POST['pg_total_amount'],
                              "ac_notes"          => $paypal_data,
                              "ac_date"           => date('Y-m-d'),
                              "online_payment_id" => $_POST['pg_consumerorderid'],
                              "ac_payment_type"   => $pmt_type));
        $logger->log('ACH - payment_type='.$pmt_type, Zend_Log::INFO);

        $invoice    = Invoice::select($_POST['pg_consumerorderid']);
        $biller     = Biller::select($invoice['biller_id']);

        //send email
        $body  =  "A PaymentsGateway.com payment of ".$_POST['pg_total_amount']." was successfully received\n";
        $body .= "for invoice: ".$_POST['pg_consumerorderid'] ;
        $body .= " from ".$_POST['pg_billto_postal_name_company']." on ".date('m/d/Y');
        $body .= " at ".date('g:i A')."\n\nDetails:\n";
        $body .= $paypal_data;

        $email = new Email();
        $email->notes = $body;
        $email->to = $biller['email'];
        $email->from = "simpleinvoices@localhost.localdomain";
        $email->subject = 'PaymentsGateway.com -Instant Payment Notification - Recieved Payment';
        $email->send ();
        $xml_message = "+++++++++<br /><br />";
        $xml_message .= "Thank you for the payment, the details have been recorded and ". $biller['name'] ." has been notified via email.";
        $xml_message .= "<br /><br />+++++++++<br />";
    }
} else {
    $xml_message = "PaymentsGateway.com payment validate failed - please contact ". $biller['name'] ;
    $logger->log('ACH validate failed', Zend_Log::INFO);
}
echo $xml_message;
