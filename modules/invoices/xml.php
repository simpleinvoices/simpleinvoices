<?php
//Developed by -==[Mihir Shah]==- during my Project work
//for the output
header("Content-type: text/xml");

$start = (isset($_POST['start'])) ? $_POST['start'] : "0" ;
$dir = (isset($_POST['dir'])) ? $_POST['dir'] : "DESC" ;
$sort = (isset($_POST['sort'])) ? $_POST['sort'] : "id" ;
$limit = (isset($_POST['limit'])) ? $_POST['limit'] : "25" ;

//SC: Safety checking values that will be directly subbed in
if (intval($start) != $start) {
	$start = 0;
}
if (intval($limit) != $limit) {
	$limit = 25;
}
if (!preg_match('/^(asc|desc)$/iD', $dir)) {
	$dir = 'DESC';
}

/*Check that the sort field is OK*/
$validFields = array('id', 'biller', 'customer', 'invoice_total','owing','date','aging','type');

if (in_array($sort, $validFields)) {
	$sort = $sort;
} else {
	$sort = "id";
}


$sql = "SELECT * FROM ".TB_PREFIX."invoices ORDER BY $sort $dir LIMIT $start, $limit";

$sth = dbQuery($sql) or die(end($dbh->errorInfo()));

$invoices = null;

for($i = 0;$invoice = getInvoices($sth);$i++) {
	
	
	$biller = getBiller($invoice['biller_id']);
	$customer = getCustomer($invoice['customer_id']);
	//$invoiceType = getInvoiceType($invoice['type_id']);
	$preference = getPreference($invoice['preference_id']);
	$defaults = getSystemDefaults();
	
	$invoices[$i]['id'] = $invoice['id'];
	$invoices[$i]['biller'] = $biller['name'];
	$invoices[$i]['customer'] = $customer['name'];
	$invoices[$i]['invoice_total'] = $invoice['total'];
	$invoices[$i]['owing'] = $invoice['owing'];
	$invoices[$i]['date'] = $invoice['date'];
	//$invoices[$i]['invoiceType'] = $invoiceType;
	$invoices[$i]['preference'] = $preference['pref_description'];
	//$invoices[$i]['defaults'] = $defaults;



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
        
	$invoices[$i]['aging'] = $overdue;
	//$invoices[$i]['url_for_pdf'] = $url_for_pdf;
}

global $dbh;

$sqlTotal = "SELECT count(id) AS count FROM ".TB_PREFIX."invoices";
$tth = dbQuery($sqlTotal) or die(end($dbh->errorInfo()));
$resultCount = $tth->fetch();
$count = $resultCount[0];
echo sql2xml($invoices, $count);

?> 
