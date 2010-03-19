<?php

class eway 
{

    public $biller;
    public $invoice;
    public $customer;

    public function payment()
    {
    
        global $config;
        global $logger;

        $eway = new ewaylib($this->biller['eway_customer_id'],'REAL_TIME', false);

        $eway_invoice_total = $this->invoice['total'] * 100 ;

        $enc = new encryption();
        $key = $config->encryption->default->key;	
        $credit_card_number = $enc->decrypt($key, $this->customer['credit_card_number']);

        $eway->setTransactionData("TotalAmount", $eway_invoice_total); //mandatory field
        $eway->setTransactionData("CustomerFirstName", $this->customer['name']);
    	$eway->setTransactionData("CustomerLastName", "");
        $eway->setTransactionData("CustomerAddress", "123 Someplace Street, Somewhere ACT");
        $eway->setTransactionData("CustomerPostcode", "2609");
        $eway->setTransactionData("CustomerInvoiceDescription", "Testing");
        $eway->setTransactionData("CustomerEmail", $this->customer['email']);
        $eway->setTransactionData("CustomerInvoiceRef", $this->invoice['index_name']);
        $eway->setTransactionData("CardHoldersName", $this->customer['credit_card_holder_name']); //mandatory field
        $eway->setTransactionData("CardNumber", $credit_card_number); //mandatory field
        $eway->setTransactionData("CardExpiryMonth", $this->customer['credit_card_expiry_month']); //mandatory field
        $eway->setTransactionData("CardExpiryYear", $this->customer['credit_card_expiry_year']); //mandatory field
        $eway->setTransactionData("Option1", "");
        $eway->setTransactionData("Option2", "");
        $eway->setTransactionData("Option3", "");
        $eway->setTransactionData("TrxnNumber", $this->invoice['id']);
        
        //special preferences for php Curl
        $eway->setCurlPreferences(CURLOPT_SSL_VERIFYPEER, 0);  //pass a long that is set to a zero value to stop curl from verifying the peer's certificate 
        $ewayResponseFields = $eway->doPayment();
        
        $message ="";
        if($ewayResponseFields["EWAYTRXNSTATUS"]=="False"){
			$logger->log("Transaction Error: " . $ewayResponseFields["EWAYTRXNERROR"] . "<br>\n", Zend_Log::INFO);
            foreach($ewayResponseFields as $key => $value)
                $message .= "\n<br>\$ewayResponseFields[\"$key\"] = $value";
			$logger->log("Eway message: " . $message . "<br>\n", Zend_Log::INFO);
            //header("Location: trasnactionerrorpage.php");
            //exit();		
        }else if($ewayResponseFields["EWAYTRXNSTATUS"]=="True"){


			$logger->log("Transaction Success: " . $ewayResponseFields["EWAYTRXNERROR"] . "<br>\n", Zend_Log::INFO);
            foreach($ewayResponseFields as $key => $value)
                $message .= "\n<br>\$ewayResponseFields[\"$key\"] = $value";
			$logger->log("Eway message: " . $message . "<br>\n", Zend_Log::INFO);
            //header("Location: trasnactionsuccess.php");
            //exit();
            $payment = new payment();
            $payment->ac_inv_id = $this->invoice['id'];
            #$payment->ac_inv_id = $_POST['invoice'];
            $payment->ac_amount = $this->invoice['total'];
            #$payment->ac_amount = $_POST['mc_gross'];
            $payment->ac_notes = $message;
            #$payment->ac_notes = $paypal_data;
            $payment->ac_date = date( 'Y-m-d' );
            #$payment->ac_date = date( 'Y-m-d', strtotime($_POST['payment_date']));
            $payment->online_payment_id = $ewayResponseFields['EWAYTRXNNUMBER'];
            $payment->domain_id = domain_id::get($this->domain_id);

                $payment_type = new payment_type();
                $payment_type->type = "Eway";
                $payment_type->domain_id = $domain_id;

            $payment->ac_payment_type = $payment_type->select_or_insert_where();
            $logger->log('Paypal - payment_type='.$payment->ac_payment_type, Zend_Log::INFO);
            $payment->insert();

            }

    }

}
