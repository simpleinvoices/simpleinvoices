<?php

/*
* Script: auto_complete_search.php
*     Do the autocomplete of invoice id in the process payment page
*
* License:
*     GPL v2 or above
*/

define("BROWSE","browse");
//if this page has error with auth remove the above line and figure out how to do it right

$invoice = new Invoice();
$sth = $invoice->select_all();

$q = strtolower($_GET["q"]);
if (!$q) return;

$invoices = $sth->fetch_all(PDO::FETCH_ASSOC);
foreach ($invoices as $invoice) {
    if (strpos(strtolower($invoice['index_id']), $q) !== false) {
        // @formatter:off
        $invoice['id']        = htmlsafe($invoice['id']);
        $invoice['calc_date'] = date('Y-m-d', strtotime($invoice['date']));
        $invoice['date']      = siLocal::date($invoice['date']);

        // Calculate values
        $invoice['total'] = Invoice::getInvoiceTotal($invoice['id'], $invoice['domain_id']);
        $invoice['paid']  = Invoice::calc_invoice_paid($invoice['id'], $invoice['domain_id']);
        $invoice['owing'] = $invoice['total'] - $invoice['paid'];

        // Format values
        $invoice['total'] = htmlsafe(number_format($invoice['total'],2));
        $invoice['paid']  = htmlsafe(number_format($invoice['paid'],2));
        $invoice['owing'] = htmlsafe(number_format($invoice['owing'],2));
        // @formatter:on
        echo "$invoice[id]|<table><tr><td class='details_screen'>$invoice[preference]:</td><td>$invoice[index_id]</td><td  class='details_screen'>Total: </td><td>$invoice[total] </td></tr><tr><td class='details_screen'>Biller: </td><td>$invoice[biller] </td><td class='details_screen'>Paid: </td><td>$invoice[paid] </td></tr><tr><td class='details_screen'>Customer: </td><td>$invoice[customer] </td><td class='details_screen'>Owing: </td><td><u>$invoice[owing]</u></td></tr></table>\n";
    }
}
