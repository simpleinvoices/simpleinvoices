<?php

/*
* Script: auto_complete_search.php
* 	Do the autocomplete of invoice id in the process payment page
*
* License:
*	 GPL v2 or above
*/

define("BROWSE","browse");
//if this page has error with auth remove the above line and figure out how to do it right

$domain_id = domain_id::get();

#$sql = "SELECT * FROM ".TB_PREFIX."invoices where domain_id = ".$domain_id;

#global $dbh;
#$sth = dbQuery($sql) or die(htmlsafe(end($dbh->errorInfo())));

//$sql = "SELECT * FROM ".TB_PREFIX."invoices LIMIT $start, $limit";
$invoice = new invoice();
$sth = $invoice->select_all();

$q = strtolower($_GET["q"]);
if (!$q) return;

while ($invoice = getInvoices($sth)) {

	$invoiceType = getInvoiceType($invoice['type_id']);

	if (strpos(strtolower($invoice['index_id']), $q) !== false) {
		$invoice['id'] = htmlsafe($invoice['id']);
		$invoice['total'] = htmlsafe(number_format($invoice['total'],2));
		$invoice['paid'] = htmlsafe(number_format($invoice['paid'],2));
		$invoice['owing'] = htmlsafe(number_format($invoice['owing'],2));
		echo "$invoice[id]|<table><tr><td class='details_screen'>$invoice[preference]:</td><td>$invoice[index_id]</td><td  class='details_screen'>Total: </td><td>$invoice[total] </td></tr><tr><td class='details_screen'>Biller: </td><td>$invoice[biller] </td><td class='details_screen'>Paid: </td><td>$invoice[paid] </td></tr><tr><td class='details_screen'>Customer: </td><td>$invoice[customer] </td><td class='details_screen'>Owing: </td><td><u>$invoice[owing]</u></td></tr></table>\n";
	}
}
