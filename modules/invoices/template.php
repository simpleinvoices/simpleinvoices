<?php
/*
* Script: template.php
* 	invoice template page
*
* License:
*	 GPL v3 or above
*
* Website:
* 	http://www.simpleinvoices.org
 */
#define("BROWSE","browse");
/*
 * 
 * NOTE: THIS FILE IS NOT USED ANYMORE
 * 
 */
/*
#get the invoice id
$invoiceID = $_GET['id'];

#Info from DB print --> TODO: Needed?
//$conn = mysql_connect( $db_host, $db_user, $db_password );
//mysql_select_db( $db_name, $conn );

$invoice = getInvoice($invoiceID);
$customer = getCustomer($invoice['customer_id']);
$biller = getBiller($invoice['biller_id']);
$preference = getPreference($invoice['preference_id']);
$defaults = getSystemDefaults();
$logo = getLogo($biller);
$logo = str_replace(" ", "%20", $logo);
$invoiceItems = invoice::getInvoiceItems($invoiceID);

//for($i=1;$i<=4;$i++) {
//	$show["custom_field$i"] = show_custom_field("invoice_cf$i",$invoice["invoice_custom_field$i"],"read",'','tbl1-left','tbl1-right',3,':');
//

$customFieldLabels = getCustomFieldLabels();


// Set the template to the default
$template = $defaults['template'];




// The Export code - supports any file extensions - excel/word/open office - what reads html
if (isset($_GET['export'])) {
	$template = "export";
	$file_extension = $_GET['export'];
	header("Content-type: application/octet-stream");
	//header("Content-type: application/x-msdownload");
	header("Content-Disposition: attachment; filename=$preference['pref_inv_heading']$invoice['id'].$file_extension");
	header("Pragma: no-cache");
	header("Expires: 0");
}
// End Export code 

	
$templatePath = "./templates/invoices/${template}/template.blade.php";
$template_path = "templates.invoices.{$template}";
$css = $siUrl."/templates/invoices/${template}/style.css";
$pluginsdir = "./templates/invoices/${template}/plugins/";

$bladeView->plugins_dir = $pluginsdir;

$pageActive = "invoices";
$bladeView->assign('pageActive', $pageActive);

if(file_exists($templatePath)) {
	$bladeView -> assign('biller',$biller);
	$bladeView -> assign('customer',$customer);
	$bladeView -> assign('invoice',$invoice);
	$bladeView -> assign('preference',$preference);
	$bladeView -> assign('logo',$logo);
	$bladeView -> assign('template',$template);
	$bladeView -> assign('invoiceItems',$invoiceItems);
	$bladeView -> assign('template_path',$template_path);
	$bladeView -> assign('css',$css);
	$bladeView -> assign('customFieldLabels',$customFieldLabels);
	
	
	if ($_GET['location'] =="pdf")
	{
		$html = $bladeView -> fetch(".".$templatePath);
		pdfThis($html);
		exit();
	}
	$bladeView -> $blade_output(".".$templatePath);

}
*/