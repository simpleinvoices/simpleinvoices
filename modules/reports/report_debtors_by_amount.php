<?php

//   include phpreports library
require_once("./include/reportlib.php");

// Adjusted for NULL on computation with NULL values

	$sSQL  = "SELECT iv.id As id, b.name As Biller, c.name As Customer, ";
	$sSQL .= "@invd:=(select sum( IF(isnull(ivt.total), 0, ivt.total)) from " . TB_PREFIX . "invoice_items ivt where ivt.invoice_id = iv.id) as invd, "; 
	$sSQL .= "IF(isnull(@invd), 0, @invd) As INV_TOTAL, ";
	$sSQL .= "@apmt:=(select sum( IF(isnull(ap.ac_amount), 0, ap.ac_amount)) from " . TB_PREFIX . "account_payments ap where ap.ac_inv_id = iv.id) As pmt, ";
	$sSQL .= "IF(isnull(@apmt), 0, @apmt) As INV_PAID, ";
//	$sSQL .= "DATE_FORMAT(date, '%Y-%m-%d') As date, "; // date here is Invoice Date and is currently not used in the output
	$sSQL .= "(select (INV_TOTAL - INV_PAID)) as INV_OWING ";
	$sSQL .= "FROM " . TB_PREFIX . "invoices iv, " . TB_PREFIX . "biller b, " . TB_PREFIX . "customers c ";
	$sSQL .= "WHERE iv.biller_id = b.id AND iv.customer_id = c.id ";
	$sSQL .= "GROUP BY iv.id ";
//	$sSQL .= "HAVING INV_OWING > 0 ";	// comment out if all invoices are to be displayed and not just owing invoices
	$sSQL .= "ORDER BY INV_OWING DESC;";

	$oRpt->setXML("./modules/reports/report_debtors_by_amount.xml");

//   include phpreports run code
	include("./include/reportrunlib.php");

?>
