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

$invoice = getInvoice($invoice_id);
$preference = getPreference($invoice['preference_id']);
$biller = getBiller($invoice['biller_id']);
$customer = getCustomer($invoice['customer_id']);


$sql = "SELECT inv_ty_description AS type FROM ".TB_PREFIX."invoice_type WHERE inv_ty_id = :type";
$sth = dbQuery($sql, ':type', $invoice['type_id']);
$invoiceType = $sth->fetch();

      
if ($_GET['stage'] == 2 ) {
	
	#get the invoice id
	$export = new export();
	$export -> format = "pdf";
	$export -> file_location = 'file';
	$export -> module = 'invoice';
	$export -> id = $invoice_id;
	$export -> execute();
		

	echo $block_stage2;

	require("./library/mail/class.phpmailer.php");

	$mail = new PHPMailer();

	$mail->IsSMTP();                                      // set mailer to use SMTP
	$mail->Host = $config->email->host;  // specify main and backup server - separating with ;
	$mail->SMTPAuth = $config->email->smtp_auth;     // turn on SMTP authentication
	$mail->Username = $config->email->username;  // SMTP username
	$mail->Password = $config->email->password; // SMTP password

	// if statements used for backwards compatibility for old config/config.php - Ap.Muthu
	if (isset($config->email->smtpport)) { $mail->Port = $config->email->smtpport;     } // SMTP Port
	if (isset($config->email->secure))   { $mail->SMTPSecure = $config->email->secure; } // Secure SMTP mode - '', 'ssl', or 'tls'
	if (isset($config->email->ack) && $config->email->ack) { $mail->ConfirmReadingTo = "$_POST[email_from]"; } // Sets Return receipt as Sender EMail ID
	
	$mail->From = "$_POST[email_from]";
	$mail->FromName = "$biller[name]";
	
	//allow split of email address via , or ;
 	$to_addresses = preg_split('/\s*[,;]\s*/', $_POST['email_to']);
	if (!empty($to_addresses)) 
	{
	   	foreach ($to_addresses as $to) {
		    $mail->AddAddress($to);
	   }
  	}
  	
	//allow split of email address via , or ;  	
	if (!empty($_POST['email_bcc']))
	{
 	    $bcc_addresses = preg_split('/\s*[,;]\s*/', $_POST['email_bcc']);
	
    	foreach ($bcc_addresses as $bcc)
		{
		    $mail->AddBCC($bcc);
		}
	}	
		
	
	$mail->WordWrap = 50;                                 // set word wrap to 50 characters
	$spc2us_pref = str_replace(" ", "_", $preference[pref_inv_wording]); // Ap.Muthu added to accomodate spaces in inv pref name
	$mail->AddAttachment("./tmp/cache/$spc2us_pref$invoice[id].pdf");  // all tmp in ./cache       // add attachments

	$mail->IsHTML(true);                                  // set email format to HTML

	$mail->Subject = "$_POST[email_subject]"; 
	$mail->Body    = "$_POST[email_notes]";
	$mail->AltBody = "$_POST[email_notes]";

	if(!$mail->Send())
	{
	   echo "Message could not be sent. <p>";
	   echo "Mailer Error: " . $mail->ErrorInfo;
	   exit;
	}
	unlink("./tmp/cache/$spc2us_pref$invoice[id].pdf");
	$message  = "<meta http-equiv=\"refresh\" content=\"2;URL=index.php?module=invoices&amp;view=manage\">";
	$message .= "<br />$preference[pref_inv_wording] $invoice[id] has been sent as a PDF";

	echo $block_stage3;

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
