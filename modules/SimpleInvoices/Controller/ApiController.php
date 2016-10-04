<?php
/**
 * Simple Invoices (http://www.simpleinvoices.org/)
 *
 * @link      http://github.com/simpleinvoices/simpleinvoices for the source repository
 * @copyright Copyright (c) 2005-2016 Simple Invoices Community (http://www.simpleinvoices.org)
 * @license   https://www.gnu.org/licenses/gpl-3.0.html GPL v3
 */

namespace SimpleInvoices\Controller;

class ApiController
{
    /**
     * TODO: Once again the domain id is BAD on check payment.
     */
    public function achAction()
    {
        global $logger;
        
        $logger->log('ACH API page called', \Zend_Log::INFO);
        
        if ($_POST['pg_response_code']=='A01') {
            $logger->log('ACH validate success', \Zend_Log::INFO);
        
            //insert into payments
            $paypal_data ="";
            foreach ($_POST as $key => $value) { 
                $paypal_data .= "\n$key: $value"; 
            }
            $logger->log('ACH Data:', \Zend_Log::INFO);
            $logger->log($paypal_data, \Zend_Log::INFO);
        
            $check_payment                    = new \payment();
            $check_payment->filter            = 'online_payment_id';
            $check_payment->online_payment_id = $_POST['pg_consumerorderid'];
            $check_payment->domain_id         = '1';
            $number_of_payments               = $check_payment->count();
            $logger->log('ACH - number of times this payment is in the db: '.$number_of_payments, \Zend_Log::INFO);
        
            if($number_of_payments > 0)
            {
                $xml_message = 'Online payment for invoices: '.$_POST['pg_consumerorderid'].' has already been entered into Simple Invoices';
                $logger->log($xml_message, \Zend_Log::INFO);
            }
        
            if($number_of_payments == '0')
            {
                $payment = new \payment();
                $payment->ac_inv_id         = $_POST['pg_consumerorderid'];
                $payment->ac_amount         = $_POST['pg_total_amount'];
                $payment->ac_notes          = $paypal_data;
                $payment->ac_date           = date( 'Y-m-d');
                $payment->online_payment_id = $_POST['pg_consumerorderid'];
                $payment->domain_id         = '1';
        
                $payment_type               = new \payment_type();
                $payment_type->type         = "ACH";
                $payment_type->domain_id    = '1';
                $payment->ac_payment_type   = $payment_type->select_or_insert_where();
                $logger->log('ACH - payment_type ='.$payment->ac_payment_type, \Zend_Log::INFO);
                $payment->insert();
        
                $invoiceobj = new \invoice();
                $invoice    = $invoiceobj->select($_POST['pg_consumerorderid']);
                $biller     = getBiller($invoice['biller_id']);
        
                //send email
                $body  =  "A PaymentsGateway.com payment of ".$_POST['pg_total_amount']." was successfully received into Simple Invoices\n";
                $body .= "for invoice: ".$_POST['pg_consumerorderid'] ;
                $body .= " from ".$_POST['pg_billto_postal_name_company']." on ".date('m/d/Y');
                $body .= " at ".date('g:i A')."\n\nDetails:\n";
                $body .= $paypal_data;
        
                $email          = new \email();
                $email->notes   = $body;
                $email->to      = $biller['email'];
                $email->from    = "simpleinvoices@localhost.localdomain";
                $email->subject = 'PaymentsGateway.com -Instant Payment Notification - Recieved Payment';
                $email->send ();
                $xml_message = "+++++++++<br /><br />";
                $xml_message .= "Thank you for the payment, the details have been recorded and ". $biller['name'] ." has been notified via email.";
                $xml_message .= "<br /><br />+++++++++<br />";
            }
        } else {
        
            $xml_message = "PaymentsGateway.com payment validate failed - please contact ". $biller['name'] ;
            $logger->log('ACH validate failed', \Zend_Log::INFO);
        }
        
        echo $xml_message;
    }
    
    public function cronAction()
    {
        ini_set('max_execution_time', 600); //600 seconds = 10 minutes
        
        $cron = new \cron();
        // remove hardcoding for multi-domain usage
        // $cron->domain_id=1;
        $message = $cron->run();
        
        try
        {
        
            //json
            //header('Content-type: application/json');
            //echo encode::json( $message, 'pretty' );
        
            //xml
            ob_end_clean();
            header('Content-type: application/xml');
            echo \encode::xml( $message );
        }
        catch (\Exception $e)
        {
            echo $e->getMessage();
        }
    }
    
    public function invoiceAction()
    {
        $invoiceobj = new \invoice();
        // why hardcode invoice number below?
        $invoice = $invoiceobj->select('1');
        
        header('Content-type: application/xml');
        echo \encode::xml($invoice);
        print_r($invoice);
    }

    public function paypalAction()
    {
        global $logger;
        
        $p = new \paypal_class;             // initiate an instance of the class
        #$p->paypal_url = 'https://www.sandbox.paypal.com/cgi-bin/webscr';   // testing paypal url
        $p->paypal_url = 'https://www.paypal.com/cgi-bin/webscr';     // paypal url
        
        $xml_message = "";
        
        $logger->log('Paypal API page called', \Zend_Log::INFO);
        if ($p->validate_ipn()) {
            #if (!empty($_POST)) {
        
            $logger->log('Paypal validate success', \Zend_Log::INFO);
        
            //insert into payments
            $paypal_data ="";
            foreach ($p->ipn_data as $key => $value) { 
                $paypal_data .= "\n$key: $value"; 
            }
            $logger->log('Paypal Data:', \Zend_Log::INFO);
            $logger->log($paypal_data, \Zend_Log::INFO);
            //get the domain_id from the paypal invoice
            $custom_array = explode(";", $p->ipn_data['custom']);
            #$custom_array = explode(";", $_POST['custom']);
        
            $logger->log('Paypal - custom='.$_POST['custom'], \Zend_Log::INFO);
            foreach ($custom_array as $key => $value)
            {
                if( strstr($value,"domain_id:"))
                {
                    $logger->log('Paypal - value='.$value, \Zend_Log::INFO);
                    $domain_id = substr($value, 10);
                    #$domain_id = substr($domain_id, 0, -1);
                }
            }
        
            $logger->log('Paypal - domain_id='.$domain_id.'EOM',\ Zend_Log::INFO);
        
            //check if payment has already been entered
        
            $check_payment = new \payment();
            $check_payment->filter            = 'online_payment_id';
            $check_payment->online_payment_id = $p->ipn_data['txn_id'];
            $check_payment->domain_id         = $domain_id;
            $number_of_payments               = $check_payment->count();
            $logger->log('Paypal - number of times this payment is in the db: '.$number_of_payments, \Zend_Log::INFO);
        
            if($number_of_payments > 0)
            {
                $xml_message .= 'Online payment '.$p->ipn_data['tnx_id'].' has already been entered into Simple Invoices - exiting for domain_id='.$domain_id;
                $logger->log($xml_message, \Zend_Log::INFO);
            }
        
            if($number_of_payments == '0')
            {
        
                $payment                    = new \payment();
                $payment->ac_inv_id         = $p->ipn_data['invoice'];
                #$payment->ac_inv_id        = $_POST['invoice'];
                $payment->ac_amount         = $p->ipn_data['mc_gross'];
                #$payment->ac_amount        = $_POST['mc_gross'];
                $payment->ac_notes          = $paypal_data;
                #$payment->ac_notes         = $paypal_data;
                $payment->ac_date           = date( 'Y-m-d', strtotime($p->ipn_data['payment_date']));
                #$payment->ac_date          = date( 'Y-m-d', strtotime($_POST['payment_date']));
                $payment->online_payment_id = $p->ipn_data['txn_id'];
                $payment->domain_id         = $domain_id;
        
                $payment_type               = new \payment_type();
                $payment_type->type         = "Paypal";
                $payment_type->domain_id    = $domain_id;
        
                $payment->ac_payment_type   = $payment_type->select_or_insert_where();
                $logger->log('Paypal - payment_type='.$payment->ac_payment_type, \Zend_Log::INFO);
                $payment->insert();
        
                $invoiceobj                 = new \invoice();
                $invoice                    = $invoiceobj->select($p->ipn_data['invoice']);
        
                $biller                     = getBiller($invoice['biller_id']);
        
                //send email
                $body =  "A Paypal instant payment notification was successfully recieved into Simple Invoices\n";
                $body .= "from ".$p->ipn_data['payer_email']." on ".date('m/d/Y');
                $body .= " at ".date('g:i A')."\n\nDetails:\n";
                $body .= $paypal_data;
        
                $email          = new \email();
                $email->notes   = $body;
                $email->to      = $biller['email'];
                $email->from    = "simpleinvoices@localhost.localdomain";
                $email->subject = 'Instant Payment Notification - Recieved Payment';
                $email->send ();
        
                $xml_message['data'] .= $body;
            }
        } else {
            $xml_message .= "Paypal validate failed" ;
            $logger->log('Paypal validate failed', \Zend_Log::INFO);
        }
        
        header('Content-type: application/xml');
        try
        {
            $xml = new \encode();
            $xml->xml( $xml_message );
            echo $xml;
        }
        catch (\Exception $e)
        {
            echo $e->getMessage();
        }
    }
    
    public function recurAction()
    {
        $ni     = new \invoice();
        $ni->id = $_GET['id'];
        $ni->recur();
    }
    
    public function reorderAction()
    {
        ini_set('max_execution_time', 600); //600 seconds = 10 minutes
        
        $inventory            = new \inventory();
        $inventory->domain_id = 1;
        $message = $inventory->check_reorder_level();
        
        try
        {
        
            //json
            //header('Content-type: application/json');
            #echo encode::json( $message, 'pretty' );
        
            //xml
            ob_end_clean();
            header('Content-type: application/xml');
            echo \encode::xml( $message );
        }
        catch (Exception $e)
        {
            echo $e->getMessage();
        }
    }
}