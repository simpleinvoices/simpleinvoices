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

error_reporting(E_ALL & ~E_WARNING & ~E_NOTICE);

//stop the direct browsing to this file - let index.php handle which files get displayed
checkLogin();

#get the invoice id
$invoice_id = $_GET['id'];

$invoice = getInvoice($invoice_id);
$preference = getPreference($invoice['preference_id']);
$biller = getBiller($invoice['biller_id']);
$customer = getCustomer($invoice['customer_id']);


$sql = "SELECT inv_ty_description AS type FROM ".TB_PREFIX."invoice_type WHERE inv_ty_id = :type";
$sth = dbQuery($sql, ':type', $invoice['type_id']);
$invoiceType = $sth->fetch();

      
if ($_GET['stage'] == 2 ) {

	#echo $block_stage2;
	
	// Create invoice
	$export = new export();
	$export -> format = "pdf";
	$export -> file_location = 'file';
	$export -> module = 'invoice';
	$export -> id = $invoice_id;
	$export -> execute();

	// Create authentication with SMTP server
	$authentication = array();
	if($config->email->smtp_auth == true) {
		$authentication = array(
								'auth' => 'login',
								'username' => $config->email->username,
								'password' => $config->email->password,
								'ssl' => $config->email->secure,
								'port' => $config->email->smtpport
								);
	}
	$transport = new Zend_Mail_Transport_Smtp($config->email->host, $authentication);

	// Create e-mail message
	$mail = new Zend_Mail();
	$mail->setType(Zend_Mime::MULTIPART_MIXED);
	$mail->setBodyText($_POST[email_notes]);
	$mail->setBodyHTML($_POST[email_notes]);
	$mail->setFrom($_POST['email_from'], $biller['name']);
 	$to_addresses = preg_split('/\s*[,;]\s*/', $_POST['email_to']);
	if (!empty($to_addresses)) {
	   	foreach ($to_addresses as $to) {
		    $mail->addTo($to);
	   }
  	}
	if (!empty($_POST['email_bcc'])) {
 	    $bcc_addresses = preg_split('/\s*[,;]\s*/', $_POST['email_bcc']);
    	foreach ($bcc_addresses as $bcc) {
			$mail->addBcc($bcc);
		}
	}
	$mail->setSubject($_POST['email_subject']);

	// Create attachment
	$spc2us_pref = str_replace(" ", "_", $preference[pref_inv_wording]);
	$content = file_get_contents('./tmp/cache/' . $spc2us_pref . $invoice['id'] . '.pdf');
	$at = $mail->createAttachment($content);
	$at->type = 'application/pdf';
	$at->disposition = Zend_Mime::DISPOSITION_ATTACHMENT;
	$at->filename = $spc2us_pref . $invoice['id'] . '.pdf';

	// Send e-mail through SMTP
	try {
		$mail->send($transport);
	} catch(Zend_Mail_Protocol_Exception $e) {
		echo '<strong>Zend Mail Protocol Exception:</strong> ' .  $e->getMessage();
		exit;
	}

	// Remove temp invoice
	unlink("./tmp/cache/$spc2us_pref$invoice[id].pdf");

	// Create succes message
	$message  = "<meta http-equiv=\"refresh\" content=\"2;URL=index.php?module=invoices&amp;view=manage\">";
	$message .= "<br />$preference[pref_inv_wording] $invoice[id] has been sent as a PDF";

	#echo $block_stage3;

	//setInvoiceStatus($invoice["id"], 1);
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
