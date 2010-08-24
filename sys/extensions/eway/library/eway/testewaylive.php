<?php

error_reporting('E_ALL');
ini_set('display_startup_errors', 1);
ini_set('display_errors', 1);

	require_once('EwayPaymentLive.php');
	
	// input customerID,  method (REAL_TIME, REAL_TIME_CVN, GEO_IP_ANTI_FRAUD) and liveGateway or not
	#$eway = new EwayPaymentLive('87654321', GEO_IP_ANTI_FRAUD, false);
	$eway = new EwayPaymentLive('87654321','REAL_TIME' , false);
	#$eway = new EwayPaymentLive('87654321','REAL_TIME' , true);
	

	$eway->setTransactionData("TotalAmount", 1000); //mandatory field
	$eway->setTransactionData("CustomerFirstName", "Firstname");
	$eway->setTransactionData("CustomerLastName", "Lastname");
	$eway->setTransactionData("CustomerEmail", "name@xyz.com.au");
	$eway->setTransactionData("CustomerAddress", "123 Someplace Street, Somewhere ACT");
	$eway->setTransactionData("CustomerPostcode", "2609");
	$eway->setTransactionData("CustomerInvoiceDescription", "Testing");
	$eway->setTransactionData("CustomerInvoiceRef", "INV120394");
	$eway->setTransactionData("CardHoldersName", "John Smith"); //mandatory field
	$eway->setTransactionData("CardNumber", "4444333322221111"); //mandatory field
	$eway->setTransactionData("CardExpiryMonth", "08"); //mandatory field
	$eway->setTransactionData("CardExpiryYear", "10"); //mandatory field
	$eway->setTransactionData("TrxnNumber", "4230");
	$eway->setTransactionData("Option1", "");
	$eway->setTransactionData("Option2", "");
	$eway->setTransactionData("Option3", "");
	
	//for REAL_TIME_CVN
	$eway->setTransactionData("CVN", "123");

	//for GEO_IP_ANTI_FRAUD
	$eway->setTransactionData("CustomerIPAddress", $eway->getVisitorIP()); //mandatory field when using Geo-IP Anti-Fraud
	$eway->setTransactionData("CustomerBillingCountry", "AU"); //mandatory field when using Geo-IP Anti-Fraud
	
	
	//special preferences for php Curl
	$eway->setCurlPreferences(CURLOPT_SSL_VERIFYPEER, 0);  //pass a long that is set to a zero value to stop curl from verifying the peer's certificate 
	//$eway->setCurlPreferences(CURLOPT_CAINFO, "/usr/share/ssl/certs/my.cert.crt"); //Pass a filename of a file holding one or more certificates to verify the peer with. This only makes sense when used in combination with the CURLOPT_SSL_VERIFYPEER option. 
	//$eway->setCurlPreferences(CURLOPT_CAPATH, "/usr/share/ssl/certs/my.cert.path");
	//$eway->setCurlPreferences(CURLOPT_PROXYTYPE, CURLPROXY_HTTP); //use CURL proxy, for example godaddy.com hosting requires it
	//$eway->setCurlPreferences(CURLOPT_PROXY, "http://proxy.shr.secureserver.net:3128"); //use CURL proxy, for example godaddy.com hosting requires it
	
	$ewayResponseFields = $eway->doPayment();

	
	if($ewayResponseFields["EWAYTRXNSTATUS"]=="False"){
		print "Transaction Error: " . $ewayResponseFields["EWAYTRXNERROR"] . "<br>\n";		
		foreach($ewayResponseFields as $key => $value)
			print "\n<br>\$ewayResponseFields[\"$key\"] = $value";
		//header("Location: trasnactionerrorpage.php");
		//exit();		
	}else if($ewayResponseFields["EWAYTRXNSTATUS"]=="True"){
		print "Transaction Success: " . $ewayResponseFields["EWAYTRXNERROR"]  . "<br>\n";
		foreach($ewayResponseFields as $key => $value)
			print "\n<br>\$ewayResponseFields[\"$key\"] = $value";
		//header("Location: trasnactionsuccess.php");
		//exit();
	}
?>
