<?php

//stop the direct browsing to this file - let index.php handle which files get displayed
checkLogin();


/*echo <<<EOD
<title>{$title} :: {$LANG['manage_invoices']}</title>
EOD;*/

#insert customer

$sql = "SELECT * FROM {$tb_prefix}invoices ORDER BY id desc";

$result = mysqlQuery($sql) or die(mysql_error());


$invoices = null;

for($i = 0;$invoice = getInvoices($result);$i++) {
	
	
	$biller = getBiller($invoice['biller_id']);
	$customer = getCustomer($invoice['customer_id']);
	$invoiceType = getInvoiceType($invoice['type_id']);
	$preference = getPreference($invoice['preference_id']);
	$defaults = getSystemDefaults();
	
	$invoices[$i]['invoice'] = $invoice;
	$invoices[$i]['biller'] = $biller;
	$invoices[$i]['customer'] = $customer;
	$invoices[$i]['invoiceType'] = $invoiceType;
	$invoices[$i]['preference'] = $preference;
	$invoices[$i]['defaults'] = $defaults;



	#Overdue - number of days - start
	if ($invoice['owing'] > 0 ) {
		$overdue_days = (strtotime(date('Y-m-d')) - strtotime($invoice['calc_date'])) / (60 * 60 * 24);
		//$overdue = floor($overdue_days);
		//$overdue = (($overdue_days%15)*15+1)."-".(($overdue_days%15+1)*15);
		if ($overdue_days == 0) {
			$overdue = "0-14";
		}
		elseif ($overdue_days <=14 ) {
			$overdue = "0-14";
		}
		elseif ($overdue_days <= 30 ) {
			$overdue = "15-30";
		}
		elseif ($overdue_days <= 60 ) {
			$overdue = "31-60";
		}
		elseif ($overdue_days <= 90 ) {
			$overdue = "61-90";
		}
		else {
			$overdue = "90+";
		}
	}		
	else {
		$overdue ="";
	}
	
	$url_pdf = "{$_SERVER['HTTP_HOST']}{$install_path}/index.php?module=invoices&view=templates/template&submit={$invoice['id']}&action=view&location=pdf&style={$invoiceType['inv_ty_description']}";
	$url_pdf_encoded = urlencode($url_pdf);
	$url_for_pdf = "./include/pdf/html2ps.php?process_mode=single&renderfields=1&renderlinks=1&renderimages=1&scalepoints=1&pixels=$pdf_screen_size&media=$pdf_paper_size&leftmargin=$pdf_left_margin&rightmargin=$pdf_right_margin&topmargin=$pdf_top_margin&bottommargin=$pdf_bottom_margin&transparency_workaround=1&imagequality_workaround=1&output=1&location=pdf&pdfname=$preference[pref_inv_wording]$invoice[id]&URL=$url_pdf_encoded";
        
	$invoices[$i]['overdue'] = $overdue;
	$invoices[$i]['url_for_pdf'] = $url_for_pdf;
}


$smarty -> assign("invoices",$invoices);
$smarty -> assign("spreadsheet",$spreadsheet);
$smarty -> assign("word_processor",$word_processor);


getRicoLiveGrid("ex1","	{ type:'number', decPlaces:0, ClassName:'alignleft' },,
	{ type:'number', decPlaces:2, ClassName:'alignleft' },
	{ type:'number', decPlaces:2, ClassName:'alignleft' },
	{ type:'number', decPlaces:2, ClassName:'alignleft' },,,,");

?>
