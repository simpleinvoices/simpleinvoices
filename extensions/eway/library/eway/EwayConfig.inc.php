<?php
        $eWAY_CustomerID = "87654321";    // Set this to your eWAY Customer ID
        $eWAY_PaymentMethod = REAL_TIME;  // Set this to the payment gatway you would like to use (REAL_TIME, REAL_TIME_CVN or GEO_IP_ANTI_FRAUD)
        $eWAY_UseLive = false; // Set this to true to use the live gateway


	//define default values for eway
	define('EWAY_DEFAULT_CUSTOMER_ID','87654321');
	define('EWAY_DEFAULT_PAYMENT_METHOD', REAL_TIME); // possible values are: REAL_TIME, REAL_TIME_CVN, GEO_IP_ANTI_FRAUD
	define('EWAY_DEFAULT_LIVE_GATEWAY', false); //<false> sets to testing mode, <true> to live mode

        //define script constants
	define('REAL_TIME', 'REAL-TIME');
	define('REAL_TIME_CVN', 'REAL-TIME-CVN');
	define('GEO_IP_ANTI_FRAUD', 'GEO-IP-ANTI-FRAUD');

       	//define URLs for payment gateway
	define('EWAY_PAYMENT_LIVE_REAL_TIME', 'https://www.eway.com.au/gateway/xmlpayment.asp');
	define('EWAY_PAYMENT_LIVE_REAL_TIME_TESTING_MODE', 'https://www.eway.com.au/gateway/xmltest/testpage.asp');
	define('EWAY_PAYMENT_LIVE_REAL_TIME_CVN', 'https://www.eway.com.au/gateway_cvn/xmlpayment.asp');
	define('EWAY_PAYMENT_LIVE_REAL_TIME_CVN_TESTING_MODE', 'https://www.eway.com.au/gateway_cvn/xmltest/testpage.asp');
	define('EWAY_PAYMENT_LIVE_GEO_IP_ANTI_FRAUD', 'https://www.eway.com.au/gateway_beagle/xmlbeagle.asp');
	define('EWAY_PAYMENT_LIVE_GEO_IP_ANTI_FRAUD_TESTING_MODE', 'https://www.eway.com.au/gateway_beagle/test/xmlbeagle_test.asp'); //in testing mode process with REAL-TIME
	define('EWAY_PAYMENT_HOSTED_REAL_TIME', 'https://www.eway.com.au/gateway/payment.asp');
	define('EWAY_PAYMENT_HOSTED_REAL_TIME_TESTING_MODE', 'https://www.eway.com.au/gateway/payment.asp');
	define('EWAY_PAYMENT_HOSTED_REAL_TIME_CVN', 'https://www.eway.com.au/gateway_cvn/payment.asp');
	define('EWAY_PAYMENT_HOSTED_REAL_TIME_CVN_TESTING_MODE', 'https://www.eway.com.au/gateway_cvn/payment.asp');

	
?>