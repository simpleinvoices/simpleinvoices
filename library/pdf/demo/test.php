<?php
	//fetch the page to write the pdf
	$invoice_id = $_GET['invoice'];

	$invoice = getInvoice($invoice_id);
	$preference = getPreference($invoice['preference_id']);
	$biller = getBiller($invoice['biller_id']);
	$customer = getCustomer($invoice['customer_id']);
	
	$sql = "SELECT inv_ty_description AS type FROM ".TB_PREFIX."invoice_type WHERE inv_ty_id = :id";
	$sth = dbQuery($sql, ':id', $invoice['type_id']);
	$invoiceType = $sth->fetch();
	
	$url_pdf = "http://{$http_auth}$_SERVER[HTTP_HOST]{$httpPort}$install_path/index.php?module=invoices&view=templates/template&invoice=$invoice_id&action=view&location=pdf&style=$invoiceType[type]";
	//echo $url_pdf;
	$url_pdf_encoded = urlencode($url_pdf); 
	$url_for_pdf = "./include/pdf/html2ps.php?process_mode=single&renderfields=1&renderlinks=1&renderimages=1&scalepoints=1&pixels=$pdf_screen_size&media=$pdf_paper_size&leftmargin=$pdf_left_margin&rightmargin=$pdf_right_margin&topmargin=$pdf_top_margin&bottommargin=$pdf_bottom_margin&transparency_workaround=1&imagequality_workaround=1&output=2&location=pdf&pdfname=$preference[pref_inv_wording]$invoice[id]&URL=$url_pdf_encoded";
	echo $url_for_pdf;
	//we should now have the pdf and location
	//email the pdf
	//delete the pdf
?>
