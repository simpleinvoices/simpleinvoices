<?php
global $pdoDb, $config, $http_auth, $httpPort, $install_path;
// fetch the page to write the pdf
$invoice_id = $_GET['invoice'];

$invoice    = Invoice::getInvoice($invoice_id);
$preference = Preferences::getPreference($invoice['preference_id']);
$biller     = Biller::select($invoice['biller_id']);
$customer   = Customer::get($invoice['customer_id']);
if ($biller || $customer) {} // elimiate unused warning

$pdoDb->addSimpleWhere("inv_ty_id", $invoice['type_id']);
$pdoDb->setSelectList("inv_ty_description AS type");
$rows = $pdoDb->request("SELECT","invoice_type");
$invoiceType = $rows[0];
/*
export.pdf.screensize               = 510
export.pdf.papersize                = Letter
export.pdf.leftmargin               = 10
export.pdf.rightmargin              = 10
export.pdf.topmargin                = 10
export.pdf.bottommargin             = 10

 */
$url_pdf = "http://{$http_auth}$_SERVER[HTTP_HOST]{$httpPort}$install_path/index.php?module=invoices&view=templates/template&invoice=$invoice_id&action=view&location=pdf&style=$invoiceType[type]";
// echo $url_pdf;
$url_pdf_encoded = urlencode($url_pdf);
$url_for_pdf = "./include/pdf/html2ps.php?process_mode=single&renderfields=1&renderlinks=1&renderimages=1&scalepoints=1&pixels=$config->export.pdf.screensize&media=$config->pdf.papersize&leftmargin=$config->pdf.leftmargin&rightmargin=$config->pdf.rightmargin&topmargin=$config->pdf.topmargin&bottommargin=$config->pdf.bottommargin&transparency_workaround=1&imagequality_workaround=1&output=2&location=pdf&pdfname=$preference[pref_inv_wording]$invoice[id]&URL=$url_pdf_encoded";
echo $url_for_pdf;
//we should now have the pdf and location
//email the pdf
//delete the pdf
