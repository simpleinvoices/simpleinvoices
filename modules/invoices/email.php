<?php
/*
* Script: email.php
* 	Email invoice page
*
* License:
*	 GPL v3 or above
*
* Website:
* 	http://www.simpleinvoices.org
 */

//stop the direct browsing to this file - let index.php handle which files get displayed
checkLogin();

#get the invoice id
$invoice_id = $_GET['id'];

$invoice = invoice::select($invoice_id);
$preference = getPreference($invoice['preference_id']);
$biller = getBiller($invoice['biller_id']);
$customer = getCustomer($invoice['customer_id']);


$sql = "SELECT inv_ty_description AS type FROM ".TB_PREFIX."invoice_type WHERE inv_ty_id = :type";
$sth = dbQuery($sql, ':type', $invoice['type_id']);
$invoiceType = $sth->fetch();

#create PDF name
$spc2us_pref = str_replace(" ", "_", $invoice['index_name']);
$pdf_file_name = $spc2us_pref  . '.pdf';
      
if ($_GET['stage'] == 2 ) {

	#echo $block_stage2;
	
	// Create invoice
	$export = new export();
	$export -> format = "pdf";
	$export -> file_location = 'file';
	$export -> module = 'invoice';
	$export -> id = $invoice_id;
	$export -> execute();

	#$attachment = file_get_contents('./tmp/cache/' . $pdf_file_name);

	$email = new email();
	$email -> format = 'invoice';
	$email -> notes = $_POST['email_notes'];
	$email -> from = $_POST['email_from'];
	$email -> from_friendly = $biller['name'];
	$email -> to = $_POST['email_to'];
	$email -> bcc = $_POST['email_bcc'];
	$email -> subject = $_POST['email_subject'];
	$email -> attachment = $pdf_file_name;
	$message = $email -> send ();

}

//stage 3 = assemble email and send
else if ($_GET['stage'] == 3 ) {
	$message = "How did you get here :)";
}

$smarty -> assign('message', $message);
$smarty -> assign('biller',$biller);
$smarty -> assign('customer',$customer);
$smarty -> assign('invoice',$invoice);
$smarty -> assign('preferences',$preference);

$smarty -> assign('pageActive', 'invoice');
$smarty -> assign('active_tab', '#money');
?>
