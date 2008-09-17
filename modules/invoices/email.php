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
$invoice_id = $_GET['invoice'];

$invoice = getInvoice($invoice_id);
$preference = getPreference($invoice['preference_id']);
$biller = getBiller($invoice['biller_id']);
$customer = getCustomer($invoice['customer_id']);

$sql = "SELECT inv_ty_description AS type FROM ".TB_PREFIX."invoice_type WHERE inv_ty_id = :type";
$sth = dbQuery($sql, ':type', $invoice['type_id']);
$invoiceType = $sth->fetch();

	$pathparts = pathinfo($_SERVER["SCRIPT_NAME"]);
	///echo $url_for_pdf = "http://".$_SERVER["SERVER_NAME"].$pathparts['dirname']."/pdfmaker.php?id=$invoice[id]";
	 $url = getURL();
	$url_for_pdf = $url."./pdfmaker.php?id=$invoice[id]";
      
if ($_GET['stage'] == 2 ) {
/*
	if (extension_loaded('curl')) {
		$ch = curl_init($url_for_pdf);
		// set URL and other appropriate options
		//curl_setopt($ch, CURLOPT_URL, $url_for_pdf);
		curl_setopt($ch, CURLOPT_HEADER, 0);
		curl_setopt($ch, CURLOPT_POST, 1);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER,1);
		
		// grab URL and pass it to the browser
		$response = curl_exec($ch);
		
		// close cURL resource, and free up system resources
		curl_close($ch);
	}else{
		$response = file_get_contents($url_for_pdf, "r");
	}
	
	//now save the stream to the out folder
	file_put_contents("./cache/$preference[pref_inv_wording]$invoice[id].pdf", $response);
	//use curl and baring that use fopen
	//
*/
	require_once('./library/pdf/config.inc.php');
	require_once('./library/pdf/pipeline.class.php');
	require_once('./library/pdf/fetcher.url.class.php');
	parse_config_file('./library/pdf/html2ps.config');

	$g_config = array(
	                 'cssmedia'     => 'screen',
        	          'renderimages' => true,
                	  'renderforms'  => false,
	                  'renderlinks'  => true,
	                  'mode'         => 'html',
	                  'debugbox'     => false,
	                  'draw_page_border' => false
	                  );

	$media = Media::predefined($config->export->pdf->papersize);
	$media->set_landscape(false);
	$media->set_margins(array('left'   => $config->export->pdf->leftmargin,
        	                  'right'  => $config->export->pdf->rightmargin,
                	          'top'    => $config->export->pdf->topmargin,
                	          'bottom' => $config->export->pdf->bottommargin));
	$media->set_pixels($config->export->pdf->screensize);

	$g_px_scale = mm2pt($media->width() - $media->margins['left'] - $media->margins['right']) / $media->pixels;
	$g_pt_scale = $g_px_scale * 1.43; 

	$pipeline = new Pipeline;
	$pipeline->fetchers[]     = new FetcherURL;
	$pipeline->data_filters[] = new DataFilterHTML2XHTML;
	$pipeline->parser         = new ParserXHTML;
	$pipeline->layout_engine  = new LayoutEngineDefault;
	$pipeline->output_driver  = new OutputDriverFPDF($media);
	$pipeline->destination    = new DestinationFile($preference[pref_inv_wording].$invoice[id]);


	$url_pdf = urlPDF($invoice['id']);
	$pipeline->process($url_pdf, $media); 

	echo $block_stage2;

	require("./include/mail/class.phpmailer.php");

	$mail = new PHPMailer();

	$mail->IsSMTP();                                      // set mailer to use SMTP
	$mail->Host = $email_host;  // specify main and backup server - separating with ;
	$mail->SMTPAuth = $email_smtp_auth;     // turn on SMTP authentication
	$mail->Username = $email_username;  // SMTP username
	$mail->Password = $email_password; // SMTP password

	// if statements used for backwards compatibility for old config/config.php - Ap.Muthu
	if (isset($email_smtpport)) { $mail->Port = $email_smtpport;     } // SMTP Port
	if (isset($email_secure))   { $mail->SMTPSecure = $email_secure; } // Secure SMTP mode - '', 'ssl', or 'tls'
	if (isset($email_ack) && $email_ack) { $mail->ConfirmReadingTo = "$_POST[email_from]"; } // Sets Return receipt as Sender EMail ID
	
	$mail->From = "$_POST[email_from]";
	$mail->FromName = "$biller[name]";
	$mail->AddAddress("$_POST[email_to]");
	if ($_POST[email_bcc]) {
	$mail->AddBCC("$_POST[email_bcc]");
	}
	$mail->WordWrap = 50;                                 // set word wrap to 50 characters
	$spc2us_pref = str_replace(" ", "_", $preference[pref_inv_wording]); // Ap.Muthu added to accomodate spaces in inv pref name
	$mail->AddAttachment("./cache/$spc2us_pref$invoice[id].pdf");  // all tmp in ./cache       // add attachments

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
	//unlink("./cache/$preference[pref_inv_wording]$invoice[id].pdf");
	$message  = "<META HTTP-EQUIV=REFRESH CONTENT=2;URL=index.php?module=invoices&view=manage>";
	$message .= "<br>$preference[pref_inv_wording] $invoice[id] has been sent as a PDF";

	echo $block_stage3;

	//setInvoiceStatus($invoice["id"], 1);
}

//stage 3 = assemble email and send
else if ($_GET['stage'] == 3 ) {
	$message = "How did you get here :)";
}

$pageActive = "invoices";

$smarty -> assign('pageActive', $pageActive);
$smarty -> assign('message', $message);
$smarty -> assign('biller',$biller);
$smarty -> assign('customer',$customer);
$smarty -> assign('invoice',$invoice);
$smarty -> assign('preferences',$preference);

?>
