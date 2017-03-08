<?php
// Author 
//		Ap.Muthu
// Last Edited on 
//		2008-01-04
/*
require_once("include/init.php");	// for getInvoice() and Preferences::getPreference()
*/
error_log("modules/export/pdf.php accessed when flagged as not used");
/*
$invoice_id = $_GET['id'];
$invoice = Invoice::getInvoice($invoice_id);

$preference = Preferences::getPreference($invoice['preference_id']);
$pdfname = trim($preference['pref_inv_wording']) . $invoice_id;

$url_pdf = getURL();
$url_pdf .= "/index.php?module=invoices&view=template&id=$invoice_id&action=view&location=pdf";;
$url_pdf_encoded = urlencode($url_pdf);
$buffer = file_get_contents("index.php?module=invoices&view=template&id=28&action=view&location=print");
echo $buffer;
*/
