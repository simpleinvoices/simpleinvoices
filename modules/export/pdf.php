<?php
// Author 
//		Ap.Muthu
// Last Edited on 
//		2008-01-04
/*
require_once("./include/init.php");	// for getInvoice() and getPreference()
*/
$invoice_id = $_GET['id'];
$invoice = getInvoice($invoice_id);

$preference = getPreference($invoice['preference_id']);
$pdfname = trim($preference['pref_inv_wording']) . $invoice_id;

$url_pdf = urlPDF($invoice_id);
$url_pdf_encoded = urlencode($url_pdf);
	$logger->log('Invoice ID: '.$invoice_id.' Get ID: '.$_GET['id'] , Zend_Log::INFO);
	$logger->log('PDF: '.$url_pdf, Zend_Log::INFO);
	$logger->log('PDF url: '.$url_pdf_encoded, Zend_Log::INFO);


$buffer = file_get_contents("../../index.php?module=invoices&view=template&id=28&action=view&location=print");
//$buffer = file_get_contents("$url_pdf");
echo $buffer;
/*
$curl_handle=curl_init();

curl_setopt($curl_handle,CURLOPT_URL,'file://index.php?module=invoices&view=template&id=28&action=view&location=print');
curl_setopt($curl_handle,CURLOPT_CONNECTTIMEOUT,2);
curl_setopt($curl_handle,CURLOPT_RETURNTRANSFER,1);
echo $buffer = curl_exec($curl_handle);
curl_close($curl_handle);
*/


/*
header("Cache-Control: no-cache, must-revalidate"); // HTTP/1.1
header("Expires: Mon, 26 Jul 1997 05:00:00 GMT"); 	// Date in the past

$myloc  = "library/pdf/html2ps.php";  // This file must be in the root of the application folder where the index.php resides
$myloc .= "?";
$myloc .= "&process_mode=single";
$myloc .= "&renderfields=1";
$myloc .= "&renderlinks=1";
$myloc .= "&renderimages=1";
$myloc .= "&scalepoints=1";
$myloc .= "&pixels=" 		. $config->export->pdf->screensize;
$myloc .= "&media=" 		. $config->export->pdf->papersize;
$myloc .= "&leftmargin=" 	. $config->export->pdf->leftmargin;
$myloc .= "&rightmargin="	. $config->export->pdf->rightmargin;
$myloc .= "&topmargin=" 	. $config->export->pdf->topmargin;
$myloc .= "&bottommargin=" 	. $config->export->pdf->bottommargin;
$myloc .= "&transparency_workaround=1";
$myloc .= "&imagequality_workaround=1";
$myloc .= "&output=1";
$myloc .= "&location=pdf";
$myloc .= "&pdfname=" 		. $pdfname;
$myloc .= "&URL=" 			. $url_pdf_encoded;

header("Location: $myloc");
*/
?>
