<?php
#table
#define("BROWSE","browse");
include('./include/include_print.php');
/*include("./include/functions.php");*/

#get the invoice id
$master_invoice_id = $_GET['submit'];

#Info from DB print --> TODO: Needed?
$conn = mysql_connect( $db_host, $db_user, $db_password );
mysql_select_db( $db_name, $conn );

$invoice = getInvoice($master_invoice_id);
$customer = getCustomer($invoice['customer_id']);
$biller = getBiller($invoice['biller_id']);
$preference = getPreference($invoice['preference_id']);
$defaults = getSystemDefaults();


for($i=1;$i<=4;$i++) {
	$biller["custom_field_label$i"] = get_custom_field_label("biller_cf$i");
	$customer["custom_field_label$i"] = get_custom_field_label("customer_cf$i");
	$product_cf["custom_field_label$i"] = get_custom_field_label("product_cf$i");
	$show["custom_field$i"] = show_custom_field("invoice_cf$i",$invoice["invoice_custom_field$i"],"read",'','tbl1-left','tbl1-right',3,':');
}

/*Set the template to the default*/
$template = $defaults['template'];

if (isset($_GET['export'])) {
	$template = "export";
}

#logo field support - if not logo show nothing else show logo

//TODO
if(!empty($biller['logo'])) {
	$logo = "./images/logo/$biller[logo]";
}
else {
	$logo = "./images/logo/_default_blank_logo.png";
}
#end logo section



	
	//TODO: Was $master_invoice
	$invoiceItems = getInvoiceItems($master_invoice_id);


/* The Export code - supports any file extensions - excel/word/open office - what reads html */
if (isset($_GET['export'])) {
	$file_extension = $_GET['export'];
	header("Content-type: application/octet-stream");
	/*header("Content-type: application/x-msdownload");*/
	header("Content-Disposition: attachment; filename=$preference[pref_inv_heading]$invoice[id].$file_extension");
	header("Pragma: no-cache");
	header("Expires: 0");
}
/* End Export code */

	
	$templatePath = "./templates/invoices/${template}/template.tpl";
	$template_path = "../templates/invoices/${template}";
	$css = "./templates/invoices/${template}/style.css";

	if(file_exists($templatePath)) {
		$smarty -> assign('biller',$biller);
		$smarty -> assign('customer',$customer);
		$smarty -> assign('invoice',$invoice);
		$smarty -> assign('preference',$preference);
		$smarty -> assign('logo',$logo);
		$smarty -> assign('template',$template);
		$smarty -> assign('product_cf',$product_cf);
		$smarty -> assign('invoiceItems',$invoiceItems);
		$smarty -> assign('template_path',$template_path);
		$smarty -> assign('css',$css);
		
		
		$smarty -> display(".".$templatePath);
	}
	else {
		echo "Old Template....";
		$temp = file_get_contents("./templates/invoices/${template}/${template}.html");
		$temp = addslashes($temp); $content = "";
	
		eval ('$content = "'.$temp.'";');
		echo $content;
	}	
?>
