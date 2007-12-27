<?php
/*
* Script: manage.php
* 	Manage Invoices page
*
* Authors:
*	 Justin Kelly, Nicolas Ruflin, Ap.Muthu
*
* Last edited:
* 	 2007-12-27
*
* License:
*	 GPL v2 or above
*
* Website:
* 	http://www.simpleinvoices.org
 */

//stop the direct browsing to this file - let index.php handle which files get displayed
checkLogin();

/*echo <<<EOD
<title>{$title} :: {$LANG['manage_invoices']}</title>
EOD;*/

$sql = "SELECT	iv.id, b.name As biller, c.name As customer, 
	(CASE	WHEN datediff(now(),date) <= 14 THEN '0-14' 
			WHEN datediff(now(),date) <= 30 THEN '15-30'
			WHEN datediff(now(),date) <= 60 THEN '31-60'
			WHEN datediff(now(),date) <= 60 THEN '61-90'
			ELSE '90+' END ) as overdue,
	iv.type_id,
	pf.pref_inv_wording,
	iv.date,
	@invd:=(SELECT sum( IF(isnull(ivt.total), 0, ivt.total)) 
		FROM ".TB_PREFIX."invoice_items ivt where ivt.invoice_id = iv.id) As invd,
	@apmt:=(SELECT sum( IF(isnull(ap.ac_amount), 0, ap.ac_amount)) 
		FROM ".TB_PREFIX."account_payments ap where ap.ac_inv_id = iv.id) As pmt,
	IF(isnull(@invd), 0, @invd) As total,
	IF(isnull(@apmt), 0, @apmt) As paid_format,
	(select (total - paid_format)) as owing
FROM ".TB_PREFIX."invoices iv, ".TB_PREFIX."biller b, ".TB_PREFIX."customers c, ".TB_PREFIX."preferences pf
WHERE iv.customer_id = c.id AND iv.biller_id = b.id AND iv.preference_id = pf.pref_id
GROUP BY iv.id 
ORDER BY iv.id DESC";

// $result = mysqlQuery($sql) or die(mysql_error());

$invoices = sql2array($sql);
$defaults = getSystemDefaults();

$numrecs = count($invoices);

for($i = 0; $i < $numrecs; $i++) {
	
// why is this done?
	$invoices[$i]['defaults'] = $defaults;

	//$url_pdf = "{$http_auth}{$_SERVER['HTTP_HOST']}{$httpPort}{$install_path}/index.php?module=invoices&view=templates/template&invoice={$invoice['id']}&action=view&location=pdf&type={$invoice['type_id']}";
	$invid = $invoices[$i]['id'];
	$url_pdf = urlPDF($invid,$invoices[$i]['type_id']);
	$url_pdf_encoded = urlencode($url_pdf);
	$url_for_pdf = "./include/pdf/html2ps.php?process_mode=single&renderfields=1&renderlinks=1&renderimages=1&scalepoints=1&pixels=$pdf_screen_size&media=$pdf_paper_size&leftmargin=$pdf_left_margin&rightmargin=$pdf_right_margin&topmargin=$pdf_top_margin&bottommargin=$pdf_bottom_margin&transparency_workaround=1&imagequality_workaround=1&output=1&location=pdf&pdfname=$invoice[pref_inv_wording]$invid&URL=$url_pdf_encoded";
	$invoices[$i]['url_for_pdf'] = $url_for_pdf;
}

$pageActive = "invoices";

$smarty -> assign("invoices",$invoices);
$smarty -> assign("spreadsheet",$spreadsheet);
$smarty -> assign("word_processor",$word_processor);
$smarty -> assign('pageActive', $pageActive);

getRicoLiveGrid("ex1","	{ type:'number', decPlaces:0, ClassName:'alignleft' },,,
	{ type:'number', decPlaces:2, ClassName:'alignleft' },
	{ type:'number', decPlaces:2, ClassName:'alignleft' }");

?>
