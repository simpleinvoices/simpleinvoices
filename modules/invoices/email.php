<?php

//stop the direct browsing to this file - let index.php handle which files get displayed
checkLogin();

include("./modules/invoices/email.tpl");


#get the invoice id
$invoice_id = $_GET['submit'];

#master invoice id select
$print_invoice_id = "SELECT * FROM {$tb_prefix}invoices WHERE inv_id = $invoice_id";
$invoice_result  = mysql_query($print_invoice_id , $conn) or die(mysql_error());
$invoice = mysql_fetch_array($invoice_result);

$biller = getBiller($invoice[inv_biller_id]);
$customer = getCustomer($invoice[inv_customer_id]);

$url_pdf = "http://$_SERVER[HTTP_HOST]$install_path/index.php?module=invoices&view=templates/template&submit=$invoice_id&action=view&location=pdf&invoice_style=$invoice[inv_type]";
$url_pdf_encoded = urlencode($url_pdf); 
$url_for_pdf = "./include/pdf/html2ps.php?process_mode=single&renderfields=1&renderlinks=1&renderimages=1&scalepoints=1&pixels=$pdf_screen_size&media=$pdf_paper_size&leftmargin=$pdf_left_margin&rightmargin=$pdf_right_margin&topmargin=$pdf_top_margin&bottommargin=$pdf_bottom_margin&transparency_workaround=1&imagequality_workaround=1&output=2&location=pdf&pdfname=$pref_inv_wordingField$inv_idField&URL=$url_pdf_encoded";

include("./modules/invoices/email.tpl");

//show the email stage info
//stage 1 = enter to, from, cc and message
if ($_GET['stage'] == 1 ) {
	echo $block_stage1;
}
//stage 2 = create pdf

else if ($_GET['stage'] == 2 ) {

	require_once('./pdf/pipeline.class.php');
	parse_config_file('./pdf/html2ps.config');

	$g_config = array(
	                 'cssmedia'     => 'screen',
        	          'renderimages' => true,
                	  'renderforms'  => false,
	                  'renderlinks'  => true,
	                  'mode'         => 'html',
	                  'debugbox'     => false,
	                  'draw_page_border' => false
	                  );

	$media = Media::predefined('A4');
	$media->set_landscape(false);
	$media->set_margins(array('left'   => 0,
        	                  'right'  => 0,
                	          'top'    => 0,
                	          'bottom' => 0));
	$media->set_pixels(1024);

	$g_px_scale = mm2pt($media->width() - $media->margins['left'] - $media->margins['right']) / $media->pixels;
	$g_pt_scale = $g_px_scale * 1.43; 

	$pipeline = new Pipeline;
	$pipeline->fetchers[]     = new FetcherURL;
	$pipeline->data_filters[] = new DataFilterHTML2XHTML;
	$pipeline->parser         = new ParserXHTML;
	$pipeline->layout_engine  = new LayoutEngineDefault;
	$pipeline->output_driver  = new OutputDriverFPDF($media);
	$pipeline->destination    = new DestinationFile(null);



	$pipeline->process($url_pdf, $media); 

	echo $block_stage2;

	require("./modules/include/mail/class.phpmailer.php");

	$mail = new PHPMailer();

	$mail->IsSMTP();                                      // set mailer to use SMTP
	$mail->Host = $email_host;  // specify main and backup server - separating with ;
	$mail->SMTPAuth = $email_smtp_auth;     // turn on SMTP authentication
	$mail->Username = $email_username;  // SMTP username
	$mail->Password = $email_password; // SMTP password

	$mail->From = "$_POST[email_from]";
	$mail->FromName = "$biller[name]";
	$mail->AddAddress("$_POST[email_to]");
	$mail->AddBCC("$_POST[email_bcc]");

	$mail->WordWrap = 50;                                 // set word wrap to 50 characters
	$mail->AddAttachment("./pdf/out/unnamed.pdf");         // add attachments

	$mail->IsHTML(true);                                  // set email format to HTML

	$mail->Subject = "Invoice $invoice_id from $biller[name] attached";
	$mail->Body    = "$_POST[email_notes]";
	$mail->AltBody = "$_POST[email_notes]";

	if(!$mail->Send())
	{
	   echo "Message could not be sent. <p>";
	   echo "Mailer Error: " . $mail->ErrorInfo;
	   exit;
	}
	echo "<META HTTP-EQUIV=REFRESH CONTENT=2;URL=index.php?module=invoices&view=manage>";
	echo "Message has been sent";

	echo $block_stage3;


}

//stage 3 = assemble email and send
else if ($_GET['stage'] == 3 ) {
}
else {
	echo "How did you get here :)";
}




?>
