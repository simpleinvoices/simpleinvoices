<?php
class eway {
    public $biller;
    public $invoice;
    public $customer;
    public $preference;
    public $domain_id;

    public function __construct() {
        $this->domain_id = domain_id::get();
    }

    public function pre_check() {
        //set customer,biller and preference if not defined
        if(empty($this->customer)) {
            $this->customer = Customer::get($this->invoice['customer_id']);
        }
        if(empty($this->biller)) {
            $this->biller = Biller::select($this->invoice['biller_id']);
        }
        if(empty($this->preference)) {
            $this->preference = getPreference($this->invoice['preference_id'], $this->domain_id);
        }

        if ($this->invoice['owing'] > 0 &&
            $this->biller['eway_customer_id'] != '' &&
            $this->customer['credit_card_number'] != '' &&
            in_array("eway_merchant_xml",explode(",", $this->preference['include_online_payment']))) {
            return 'true';
        }

        return 'false';
    }

    public function payment() {
        global $config, $logger;

        //set customer,biller and preference if not defined
        if(empty($this->customer)) {
            $this->customer = Customer::get($this->invoice['customer_id']);
        }

        if(empty($this->biller)) {
            $this->biller = Biller::select($this->invoice['biller_id']);
        }

        if(empty($this->preference)) {
            $this->preference = getPreference($this->invoice['preference_id'], $this->domain_id);
        }

        $eway = new ewaylib($this->biller['eway_customer_id'],'REAL_TIME', false);

        //Eway only accepts amount in cents - so times 100
        $value = $this->invoice['total'] * 100;
        $eway_invoice_total = htmlsafe(trim($value));
        $logger->log("eway total: " . $eway_invoice_total, Zend_Log::INFO);

        try {
            $key = $config->encryption->default->key;
            $enc = new Encryption();
            $credit_card_number = $enc->decrypt($key, $this->customer['credit_card_number']);
        } catch (Exception $e) {
            return 'false';
        }

        // @formatter:off
        $eway->setTransactionData("TotalAmount"               , $eway_invoice_total); //mandatory field
        $eway->setTransactionData("CustomerFirstName"         , $this->customer['name']);
        $eway->setTransactionData("CustomerLastName"          , "");
        $eway->setTransactionData("CustomerAddress"           , "");
        $eway->setTransactionData("CustomerPostcode"          , "");
        $eway->setTransactionData("CustomerInvoiceDescription", "");
        $eway->setTransactionData("CustomerEmail"             , $this->customer['email']);
        $eway->setTransactionData("CustomerInvoiceRef"        , $this->invoice['index_name']);
        $eway->setTransactionData("CardHoldersName"           , $this->customer['credit_card_holder_name']); //mandatory field
        $eway->setTransactionData("CardNumber"                , $credit_card_number); //mandatory field
        $eway->setTransactionData("CardExpiryMonth"           , $this->customer['credit_card_expiry_month']); //mandatory field
        $eway->setTransactionData("CardExpiryYear"            , $this->customer['credit_card_expiry_year']); //mandatory field
        $eway->setTransactionData("Option1"                   , "");
        $eway->setTransactionData("Option2"                   , "");
        $eway->setTransactionData("Option3"                   , "");
        $eway->setTransactionData("TrxnNumber"                , $this->invoice['id']);
        // @formatter:on

        //special preferences for php Curl
        //pass a long set to zero value stops curl from verifying peer's certificate
        $eway->setCurlPreferences(CURLOPT_SSL_VERIFYPEER, 0);
        $ewayResponseFields = $eway->doPayment();
        $this->message = $ewayResponseFields;
        $message ="";
        if($ewayResponseFields["EWAYTRXNSTATUS"]=="False") {
            $logger->log("Transaction Error: " . $ewayResponseFields["EWAYTRXNERROR"] . "<br>\n", Zend_Log::INFO);
            foreach($ewayResponseFields as $key => $value) {
                $message .= "\n<br>\$ewayResponseFields[\"$key\"] = $value";
            }
            $logger->log("Eway message: " . $message . "<br>\n", Zend_Log::INFO);
            return 'false';
        }

        if($ewayResponseFields["EWAYTRXNSTATUS"]=="True") {
            $logger->log("Transaction Success: " . $ewayResponseFields["EWAYTRXNERROR"] . "<br>\n", Zend_Log::INFO);
            foreach($ewayResponseFields as $key => $value) {
                $message .= "\n<br>\$ewayResponseFields[\"$key\"] = $value";
            }
            $logger->log("Eway message: " . $message . "<br>\n", Zend_Log::INFO);

            // @formatter:off
            Payment::insert(array("ac_inv_id"         => $this->invoice['id'],
                                   "ac_amount"         => $this->invoice['total'],
                                   "ac_notes"          => $message,
                                   "ac_date"           => date('Y-m-d'),
                                   "online_payment_id" => $ewayResponseFields['EWAYTRXNNUMBER'],
                                   "domain_id"         => $this->domain_id,
                                   "ac_payment_type"   => PaymentType::specialSelect("Eway")));
            // @formatter:on
            return 'true';
        }

        return 'false';
    }

    function get_message() {
        return $this->message;
    }

}
