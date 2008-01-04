<?php
/*
* Script: manage.php
* 	Manage Invoices page
*
* Authors:
*	 Justin Kelly, Nicolas Ruflin, Ap.Muthu
*
* Last edited:
* 	 2008-01-03
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

#insert customer

$sql = "SELECT * FROM ".TB_PREFIX."invoices ORDER BY id desc";

$sth = dbQuery($sql) or die(end($dbh->errorInfo()));

$invoices = null;

for($i = 0;$invoice = getInvoices($sth);$i++) {
	
	
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
	
	$url_for_pdf = "./pdfmaker.php?id=" . $invoice['id'];
        
	$invoices[$i]['overdue'] = $overdue;
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
