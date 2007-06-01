<?php

//stop the direct browsing to this file - let index.php handle which files get displayed
checkLogin();


#get the invoice id
$invoice_id = $_GET['submit'];

$invoice = getInvoice($invoice_id);
$preference = getPreference($invoice['preference_id']);
$biller = getBiller($invoice['biller_id']);
$customer = getCustomer($invoice['customer_id']);

$sql = "SELECT inv_ty_description AS type FROM {$tb_prefix}invoice_type WHERE inv_ty_id = $invoice[type_id]";
$query = mysqlQuery($sql);
$invoiceType = mysql_fetch_array($query);

$url_pdf = "http://$_SERVER[HTTP_HOST]$install_path/index.php?module=invoices&view=templates/template&submit=$invoice_id&action=view&location=pdf&style=$invoiceType[type]";
echo $url_pdf;
$url_pdf_encoded = urlencode($url_pdf); 
$url_for_pdf = "./include/pdf/html2ps.php?process_mode=single&renderfields=1&renderlinks=1&renderimages=1&scalepoints=1&pixels=$pdf_screen_size&media=$pdf_paper_size&leftmargin=$pdf_left_margin&rightmargin=$pdf_right_margin&topmargin=$pdf_top_margin&bottommargin=$pdf_bottom_margin&transparency_workaround=1&imagequality_workaround=1&output=2&location=pdf&pdfname=$preference[pref_inv_wording]$invoice[id]&URL=$url_pdf_encoded";

//show the email stage info
//stage 1 = enter to, from, cc and message
/*
if ($_GET['stage'] == 1 ) {
	echo $block_stage1;
}
*/
//stage 2 = create pdf

if ($_GET['stage'] == 2 ) {


	require_once('./include/pdf/pipeline.class.php');
	parse_config_file('./include/pdf/html2ps.config');

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
	$pipeline->destination    = new DestinationFile($preference['pref_inv_wording'].$invoice['id']);


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
	if ($_POST[email_bcc]) {
	$mail->AddBCC("$_POST[email_bcc]");
	}
	$mail->WordWrap = 50;                                 // set word wrap to 50 characters
	$mail->AddAttachment("./include/pdf/cache/$preference[pref_inv_wording]$invoice[id].pdf");         // add attachments

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
	echo "<META HTTP-EQUIV=REFRESH CONTENT=2;URL=index.php?module=invoices&view=manage>";
	echo "<br>$preference[pref_inv_wording] $invoice[id] has been sent as a PDF";

	echo $block_stage3;


}

//stage 3 = assemble email and send
else if ($_GET['stage'] == 3 ) {
	echo "How did you get here :)";
}



$smarty -> assign('biller',$biller);
$smarty -> assign('customer',$customer);
$smarty -> assign('invoice',$invoice);
$smarty -> assign('preferences',$preference);

?>
