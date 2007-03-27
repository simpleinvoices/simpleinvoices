<?php

//stop the direct browsing to this file - let index.php handle which files get displayed
checkLogin();

include("./src/invoices/email.tpl");


#get the invoice id
$invoice_id = $_GET['submit'];

#master invoice id select
$print_invoice_id = "SELECT * FROM {$tb_prefix}invoices WHERE inv_id = 8";
$invoice  = mysql_query($print_invoice_id , $conn) or die(mysql_error());

$biller = getBiller(4);

$customer = getCustomer(1);

$url_pdf = "$_SERVER[HTTP_HOST]$install_path/index.php?module=invoices&view=templates/template&submit=$inv_idField&action=view&location=pdf&invoice_style=$inv_ty_descriptionField";
$url_pdf_encoded = urlencode($url_pdf); 
$url_for_pdf = "pdf/html2ps.php?process_mode=single&renderfields=1&renderlinks=1&renderimages=1&scalepoints=1&pixels=$pdf_screen_size&media=$pdf_paper_size&leftmargin=$pdf_left_margin&rightmargin=$pdf_right_margin&topmargin=$pdf_top_margin&bottommargin=$pdf_bottom_margin&transparency_workaround=1&imagequality_workaround=1&output=2&location=pdf&pdfname=$pref_inv_wordingField$inv_idField&URL=$url_pdf_encoded";

include("./src/invoices/email.tpl");

//show the email stage info
//stage 1 = enter to, from, cc and message
if ($_GET['stage'] == 1 ) {
	echo $block_stage1;
}
//stage 2 = create pdf

else if ($_GET['stage'] == 2 ) {

        $url_pdf = "$_SERVER[HTTP_HOST]$install_path/index.php?module=invoices&view=templates/template&submit=$inv_idField&action=view&location=pdf&invoice_style=$inv_ty_descriptionField";
        $url_pdf_encoded = urlencode($url_pdf);
        $url_for_pdf = "pdf/html2ps.php?process_mode=single&renderfields=1&renderlinks=1&renderimages=1&scalepoints=1&pixels=$pdf_screen_size&media=$pdf_paper_size&leftmargin=$pdf_left_margin&rightmargin=$pdf_right_margin&topmargin=$pdf_top_margin&bottommargin=$pdf_bottom_margin&transparency_workaround=1&imagequality_workaround=1&output=1&location=pdf&pdfname=$pref_inv_wordingField$inv_idField&URL=$url_pdf_encoded";


	echo $block_stage2;
}
//stage 3 = assemble email and send
else if ($_GET['stage'] == 3 ) {
	echo $block_stage3;
}
else {
	echo "How did you get here :)";
}




?>
