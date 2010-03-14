<?php
include_once("EwayConfig.inc.php");

class EwayPaymentLive {
    var $myGatewayURL;
    var $myCustomerID;
    var $myTransactionData = array();
    var $myCurlPreferences = array();

    //Class Constructor
	function EwayPaymentLive($customerID = EWAY_DEFAULT_CUSTOMER_ID, $method = EWAY_DEFAULT_PAYMENT_METHOD ,$liveGateway  = EWAY_DEFAULT_LIVE_GATEWAY) {
		$this->myCustomerID = $customerID;
	    switch($method){

		    case "REAL_TIME";

		    		if($liveGateway)
		    			$this->myGatewayURL = EWAY_PAYMENT_LIVE_REAL_TIME;
		    		else
	    				$this->myGatewayURL = EWAY_PAYMENT_LIVE_REAL_TIME_TESTING_MODE;
	    		break;
	    	 case "REAL_TIME_CVN";
		    		if($liveGateway)
		    			$this->myGatewayURL = EWAY_PAYMENT_LIVE_REAL_TIME_CVN;
		    		else
	    				$this->myGatewayURL = EWAY_PAYMENT_LIVE_REAL_TIME_CVN_TESTING_MODE;
	    		break;
	    	case "GEO_IP_ANTI_FRAUD";
		    		if($liveGateway)
		    			$this->myGatewayURL = EWAY_PAYMENT_LIVE_GEO_IP_ANTI_FRAUD;
		    		else
		    			//in testing mode process with REAL-TIME
	    				$this->myGatewayURL = EWAY_PAYMENT_LIVE_GEO_IP_ANTI_FRAUD_TESTING_MODE;
	    		break;
    	}
	}
	
	
	//Payment Function
	function doPayment() {
		$xmlRequest = "<ewaygateway><ewayCustomerID>" . $this->myCustomerID . "</ewayCustomerID>";
		foreach($this->myTransactionData as $key=>$value)
			$xmlRequest .= "<$key>$value</$key>";
        $xmlRequest .= "</ewaygateway>";

		$xmlResponse = $this->sendTransactionToEway($xmlRequest);

		if($xmlResponse!=""){
			$responseFields = $this->parseResponse($xmlResponse);
			return $responseFields;
		}
                else die("Error in XML response from eWAY: " + $xmlResponse);
	}

	//Send XML Transaction Data and receive XML response
	function sendTransactionToEway($xmlRequest) {
		$ch = curl_init($this->myGatewayURL);
                curl_setopt($ch, CURLOPT_POST, 1);
                curl_setopt($ch, CURLOPT_POSTFIELDS, $xmlRequest);
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        foreach($this->myCurlPreferences as $key=>$value)
        	curl_setopt($ch, $key, $value);

        $xmlResponse = curl_exec($ch);

        if(curl_errno( $ch ) == CURLE_OK)
        	return $xmlResponse;
	}
	
	
	//Parse XML response from eway and place them into an array
	function parseResponse($xmlResponse){
		$xml_parser = xml_parser_create();
		xml_parse_into_struct($xml_parser,  $xmlResponse, $xmlData, $index);
        $responseFields = array();
        foreach($xmlData as $data)
	    	if($data["level"] == 2)
        		$responseFields[$data["tag"]] = $data["value"];
        return $responseFields;
	}
	
	
	//Set Transaction Data
	//Possible fields: "TotalAmount", "CustomerFirstName", "CustomerLastName", "CustomerEmail", "CustomerAddress", "CustomerPostcode", "CustomerInvoiceDescription", "CustomerInvoiceRef",
	//"CardHoldersName", "CardNumber", "CardExpiryMonth", "CardExpiryYear", "TrxnNumber", "Option1", "Option2", "Option3", "CVN", "CustomerIPAddress", "CustomerBillingCountry"
	function setTransactionData($field, $value) {
		//if($field=="TotalAmount")
		//	$value = round($value*100);
		$this->myTransactionData["eway" . $field] = htmlentities(trim($value));
	}
	
	
	//receive special preferences for Curl
	function setCurlPreferences($field, $value) {
		$this->myCurlPreferences[$field] = $value;
	}
		
	
	//obtain visitor IP even if is under a proxy
	function getVisitorIP(){
		$ip = $_SERVER["REMOTE_ADDR"];
		$proxy = $_SERVER["HTTP_X_FORWARDED_FOR"];
		if(ereg("^[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}$",$proxy))
		        $ip = $_SERVER["HTTP_X_FORWARDED_FOR"];
		return $ip;
	}
}
?>